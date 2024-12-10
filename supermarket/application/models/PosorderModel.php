<?php

class PosorderModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->session_key = $this->session->userdata('key' . SESS_KEY);
        if (isset($this->session->userdata['current_db' . $this->session_key]) && $this->session->userdata['current_db' . $this->session_key] != "") {
            $dbName = $this->session->userdata['current_db' . $this->session_key];
            $dynamicDB = array(
                'hostname' => MAIN_DB_HOST,
                'username' => MAIN_DB_UNAME,
                'password' => MAIN_DB_PASSWORD,
                'database' => $dbName,
                'dbdriver' => 'mysqli',
                'dbprefix' => '',
                'pconnect' => TRUE,
                'db_debug' => TRUE,
                'cache_on' => FALSE,
                'cachedir' => '',
                'char_set' => 'utf8',
                'dbcollat' => 'utf8_general_ci',
                'swap_pre' => '',
                'encrypt' => FALSE,
                'compress' => FALSE,
                'stricton' => FALSE,
                'failover' => array(),
                'save_queries' => TRUE
            );
            $this->db = $this->load->database($dynamicDB, TRUE);
        }
    }

    //select data by creating view
    public function selectQuery($sel, $table, $cond = array(), $orderBy = array(), $join = array(), $joinType = array(), $like = array(), $limit = '', $offset = '', $groupByColumn = '', $extraCondition = "")
    {
        $this->db->select($sel, false);
        $this->db->from($table);
        foreach ($cond as $k => $v) {
            if ($v != "") {
                $this->db->where($k, $v);
            }
        }

        foreach ($orderBy as $key => $val) {
            $this->db->order_by($key, $val);
        }
        $lc = 0;
        foreach ($like as $k => $v) {
            $val = explode("~", $v);
            if ($val[0] != "") {
                if ($lc == 0) {
                    $this->db->like($k, $val[0], $val[1]);
                    $lc++;
                } else {
                    $this->db->or_like($k, $val[0], $val[1]);
                }
            }
        }
        foreach ($join as $key => $val) {
            if (!empty($joinType) && $joinType[$key] != "") {
                $this->db->join($key, $val, $joinType[$key]);
            } else {
                $this->db->join($key, $val);
            }
        }
        if ($extraCondition != "") {
            $this->db->where($extraCondition);
        }
        if ($limit != '') {
            $this->db->limit($limit, $offset);
        }

        $this->db->group_by($groupByColumn);
        $query = $this->db->get();
        if (is_bool($query)) {
            return false;
        } else {
            if ($query->num_rows() > 0) {
                return $query;
            } else {
                return false;
            }
        }
    }

    public function fetch_draft_orders(string $userCode)
    {
        $result = $this->db->select("ordertemp.orderId")->from("ordertemp")->where("draft IS NOT NULL AND status='draft'")->get();
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return [];
    }

    public function fetch_counter_number_for_user(string $code)
    {
        $this->db->select('usermaster.code as userCode,countermaster.counterName,countermaster.branchCode');
        $this->db->join('countermaster', "usermaster.userCounter=countermaster.code");
        $this->db->where("usermaster.code", $code);
        $result = $this->db->get("usermaster")->row_array();
        if (!empty($result)) {
            return $result;
        }
        return "";
    }

    public function create_temp_order(array $customer, array $counter)
    {
        $insertData = [
            "branchCode" => $counter['branchCode'],
            "userCode" => $counter['userCode'],
            "counter" => $counter['counterName'],
            "customer" => json_encode($customer),
            "created_at" => date('Y-m-d H:i:s')
        ];
        $this->db->insert('ordertemp', $insertData);
        $currentId = $this->db->insert_id();
        if ($currentId > 0) {
            return $currentId;
        }
        return false;
    }

    public function add_product_to_temp_order(array $productDetails, string $orderId)
    {
        $productName = [
            "productEngName" => $productDetails['productEngName'],
            "productArbName" => $productDetails['productArbName'],
            "productHinName" => $productDetails['productHinName'],
            "productUrduName" => $productDetails['productUrduName']
        ];
        $result = $this->db->select("ordertempproducts.*")->where('ordertempproducts.orderId', $orderId)->get("ordertempproducts");
        if ($result->num_rows() > 0) {
            $tempproducts = $result->result_array();
            $found = false;
            foreach ($tempproducts as $p) {
                if ($p['variantCode'] == $productDetails['variantCode'] && $p['barcode'] == $productDetails['barcode'] && $p['productCode'] == $productDetails['productCode']) {
                    $found = true;
                    $qty = $p['qty'] + 1;
                    $amount = number_format(($qty * $p['price']), 2, ".", "");
                    $discount = number_format(($qty * $productDetails['discountPrice']), 2, ".", ",");
                    $discountedAmount = $amount - $discount;
                    $tax = number_format(($discountedAmount * ($p['taxPercent'] / 100)), 2, ".", "");
                    $totalPrice = number_format($discountedAmount + $tax, 2, ".", "");
                    $udpateData = [
                        "qty" => $qty,
                        "amount" => $amount,
                        "discount" => $discount,
                        "tax" => $tax,
                        "totalPrice" => $totalPrice
                    ];
                    $this->db->where('id', $p['id']);
                    $this->db->update("ordertempproducts", $udpateData);
                    if ($this->db->affected_rows() > 0) {
                        $this->update_temp_order_amounts($orderId);
                        return [
                            "orderItemId" => $p['id'],
                            "qty" => $qty,
                            "totalPrice"  => $totalPrice
                        ];
                    } else {
                        return false;
                    }
                }
            }
            if (!$found) {
                $qty  = 1;
                $amount = number_format(($qty * $productDetails['sellingPrice']), 2, ".", "");
                $discount = number_format(($qty * $productDetails['discountPrice']), 2, ".", ",");
                $discountedAmount = $amount - $discount;
                $tax = number_format(($discountedAmount * ($productDetails['taxPercent'] / 100)), 2, ".", "");
                $totalPrice = number_format($discountedAmount + $tax, 2, ".", "");
                $insertData = [
                    "orderId" => $orderId,
                    "productCode" => $productDetails["productCode"],
                    "variantCode" => $productDetails["variantCode"],
                    "barcode" => $productDetails["barcode"],
                    "productQty" => $productDetails["sellingQty"],
                    "unit" => $productDetails["sellingUnit"],
                    "price" => $productDetails['sellingPrice'],
                    "qty" => $qty,
                    "amount" => $amount,
                    "discountPrice" => $productDetails["discountPrice"],
                    "discount" => $productDetails["discountPrice"],
                    "taxPercent" => $productDetails["taxPercent"],
                    "tax" => $tax,
                    "totalPrice" => $totalPrice,
                    "sku" => $productDetails["sku"],
                    "productName" => stripslashes(json_encode($productName))
                ];
                $this->db->insert("ordertempproducts", $insertData);
                $currentId = $this->db->insert_id();
                if ($currentId > 0) {
                    $this->update_temp_order_amounts($orderId);
                    return [
                        "orderItemId" => $currentId,
                        "qty" => $qty,
                        "totalPrice"  => $totalPrice
                    ];
                }
                return false;
            } else {
            }
        } else {
            $qty  = 1;
            $amount = number_format(($qty * $productDetails['sellingPrice']), 2, ".", "");
            $discount = number_format(($qty * $productDetails['discountPrice']), 2, ".", ",");
            $discountedAmount = $amount - $discount;
            $tax = number_format(($discountedAmount * ($productDetails['taxPercent'] / 100)), 2, ".", "");
            $totalPrice = number_format($discountedAmount + $tax, 2, ".", "");
            $insertData = [
                "orderId" => $orderId,
                "productCode" => $productDetails["productCode"],
                "variantCode" => $productDetails["variantCode"],
                "barcode" => $productDetails["barcode"],
                "productQty" => $productDetails["sellingQty"],
                "unit" => $productDetails["sellingUnit"],
                "price" => $productDetails['sellingPrice'],
                "qty" => $qty,
                "amount" => $amount,
                "discountPrice" => $productDetails["discountPrice"],
                "discount" => $productDetails["discountPrice"],
                "taxPercent" => $productDetails["taxPercent"],
                "tax" => $tax,
                "totalPrice" => $totalPrice,
                "sku" => $productDetails["sku"],
                "productName" => stripslashes(json_encode($productName))
            ];
            $this->db->insert("ordertempproducts", $insertData);
            $currentId = $this->db->insert_id();
            if ($currentId > 0) {
                $this->update_temp_order_amounts($orderId);
                return [
                    "orderItemId" => $currentId,
                    "qty" => $qty,
                    "totalPrice"  => $totalPrice
                ];
            }
            return false;
        }
    }

    public function update_temp_order_amounts($orderId)
    {
        $this->db->select("IFNULL(SUM(amount),0) as subTotal, IFNULL(SUM(discount),0) as discountTotal, IFNULL(SUM(tax),0) as taxTotal, IFNULL(SUM(totalPrice),0) as totalPrice");
        $this->db->from("ordertempproducts");
        $this->db->where('orderId', $orderId);
        $result = $this->db->get()->row_array();
        if (!empty($result)) {
            $updateData = [
                "subTotal" => $result['subTotal'],
                "discountTotal" => $result['discountTotal'],
                "taxTotal" => $result['taxTotal'],
                "totalPayable" => $result['totalPrice'],
            ];
            $this->db->where("orderId", $orderId)->update("ordertemp", $updateData);
        }
    }

    public function fetch_product_by_barcode(string $barcode)
    {
        $this->db->select("productvariants.variantName,productmaster.code as productCode,inwardlineentries.sku,productmaster.productEngName,productmaster.productEngName,productmaster.productArbName,productmaster.productHinName,productmaster.productUrduName,inwardlineentries.variantCode,inwardlineentries.expiryDate,barcodeentries.batchNo,barcodeentries.sellingUnit,barcodeentries.sellingPrice,barcodeentries.sellingQty,barcodeentries.taxPercent,barcodeentries.taxAmount,barcodeentries.discountPrice,barcodeentries.barcodeText as barcode");
        $this->db->from('barcodeentries');
        $this->db->join("inwardlineentries", "barcodeentries.inwardLineCode=inwardlineentries.code");
        $this->db->join("productmaster", "inwardlineentries.productCode=productmaster.code");
        $this->db->join("productvariants", "inwardlineentries.variantCode=productvariants.code", "left");
        $this->db->where('barcodeText', strtolower($barcode));
        //$this->db->where('productmaster.isActive', 1);
        $result = $this->db->get()->row_array();
        //echo $this->db->last_query();
        if (!empty($result)) {
            return $result;
        }
        return [];
    }

    public function check_product_stock_with_temp_order($product, $counter, $orderId)
    {
        $qty = 1;
        if ($orderId !== "") {
            $this->db->select("IFNULL(SUM(ordertempproducts.qty),0) as qty");
            $this->db->from("ordertempproducts");
            $this->db->join("ordertemp", "ordertemp.orderId=ordertempproducts.orderId");
            $this->db->where("ordertempproducts.barcode", $product['barcode']);
            $this->db->where('ordertemp.branchCode', $counter['branchCode']);
            $this->db->where('ordertemp.orderId', $orderId);
            $tempProducts = $this->db->get()->row_array();
            if (!empty($tempProducts)) {
                $qty = $qty + $tempProducts['qty'];
            }
        }
        //print_r($product);
        //get the unit conversion factor and unit rounding
        $this->db->select("unitmaster.conversionFactor,unitmaster.unitRounding");
        $this->db->join("unitmaster", "unitmaster.code=productmaster.storageUnit");
        $this->db->where("productmaster.code", $product['productCode']);
        $storagefactor = $this->db->get("productmaster")->row_array();
        if (!empty($storagefactor)) {
            $conversionFactor = $storagefactor['conversionFactor'];
            $unitRounding = $storagefactor['unitRounding'];
            if ($conversionFactor == null || $conversionFactor == "" || $conversionFactor == "0") $conversionFactor = 1;
            //fetch the actual stock
            $this->db->select("IFNULL(SUM(stockinfo.stock),0) as stock");
            $this->db->from("stockinfo");
            $this->db->where('stockinfo.productCode', $product['productCode']);
            if ($product['variantCode'] != "") {
                $this->db->where('stockinfo.variantCode', $product['variantCode']);
            }
            $this->db->where('stockinfo.branchCode', $counter['branchCode']);
            $this->db->where('stockinfo.stock>', "0");
            $stockResult = $this->db->get()->row_array();
            if (!empty($stockResult)) {
                //check the stock is greater than requested qty....
                $stockRow = $stockResult;
                $convertedStock = $stockRow['stock'] * $conversionFactor;
                $actualSellingQty = $qty * $conversionFactor;
                $convertedStock = number_format($convertedStock, $unitRounding, '.', '');
                //echo " pos sell qty => $actualSellingQty <br>";
                //echo " stock qty => $convertedStock <br>";
                if ($convertedStock >= $actualSellingQty) {
                    return "in-stock";
                }
                return "out-of-stock";
            }
            return "out-of-stock";
        }
        return "out-of-stock";
    }

    public function get_temp_order_data_by_id(string $orderId)
    {
        $this->db->select("ordertemp.*");
        $this->db->from("ordertemp");
        $this->db->where("orderId", $orderId);
        $result = $this->db->get()->row_array();
        return $result;
    }

    public function check_unique_users(string $phone)
    {
        $this->db->select("code,name,phone");
        $this->db->from("customer");
        $this->db->where("customer.phone", $phone);
        $result = $this->db->limit(1)->get()->row_array();
        if (!empty($result)) return $result;
        return [];
    }

    public function get_temp_order_products(string $orderId)
    {
        $this->db->select("productmaster.productEngName,
        productmaster.productArbName,
        productmaster.productHinName,
        productmaster.productUrduName, 
        productvariants.variantName,	
        ordertempproducts.id,
        ordertempproducts.productCode,
        ordertempproducts.variantCode,
        ordertempproducts.barcode,
        ordertempproducts.price,
        ordertempproducts.amount,
        ordertempproducts.discountPrice,
        ordertempproducts.discount,
        ordertempproducts.giftDiscount,
        ordertempproducts.taxPercent,
        ordertempproducts.tax,
        ordertempproducts.sku,
        sum(ordertempproducts.qty) as qty,
        sum(ordertempproducts.totalPrice) as totalPrice	");
        $this->db->join("productvariants", "ordertempproducts.variantCode=productvariants.code", "left");
        $this->db->join("productmaster", "ordertempproducts.productCode=productmaster.code");
        $this->db->where('ordertempproducts.orderId', $orderId);
        $this->db->group_by("ordertempproducts.productCode,ordertempproducts.variantCode,ordertempproducts.barcode");
        $result = $this->db->get("ordertempproducts");
        //print_r($this->db->last_query());
        if ($result->num_rows() > 0) {
            $products = $result->result_array();
            return $products;
        }
        return [];
    }

    public function get_temp_order_products_by_id(string $orderId)
    {
        $this->db->select(" 	        
        ordertempproducts.id,
        ordertempproducts.productName,
        ordertempproducts.productCode,
        ordertempproducts.variantCode,
        ordertempproducts.barcode,
        ordertempproducts.unit,
        ordertempproducts.sku,
        sum(ordertempproducts.price) as price,
        sum(ordertempproducts.qty) as qty,
        sum(ordertempproducts.amount) as amount,
        sum(ordertempproducts.discountPrice) as discountPrice,
        sum(ordertempproducts.discount) as discount,
        ordertempproducts.taxPercent,
        sum(ordertempproducts.tax) as tax,        
        sum(ordertempproducts.totalPrice) as totalPrice");
        $this->db->where('ordertempproducts.orderId', $orderId);
        $this->db->group_by("ordertempproducts.productCode,ordertempproducts.variantCode,ordertempproducts.barcode");
        $result = $this->db->get("ordertempproducts");
        if ($result->num_rows() > 0) {
            $products = $result->result_array();
            return $products;
        }
        return [];
    }

    public function store_order_master(array $temporder, array $customer, string $paymentMode, string $ip, string $userCode)
    {
        $customerCode = $customerPhone = $customerName = "";
        if ($customer['customerCode'] != "") {
            $customerCode = $customer['customerCode'];
            $customerPhone = $customer['customerPhone'];
            $customerName = $customer['customerName'];
        } else {
            $tempCustomer = json_decode($temporder['customer'], true);
            if (!empty($tempCustomer) && $tempCustomer['customerCode'] != "") {
                $customerCode = $tempCustomer['customerCode'];
                $customerPhone = $tempCustomer['customerPhone'];
                $customerName = $tempCustomer['customerName'];
            }
        }
        $today = date('Y-m-d H:i:s');
        $insertData = [
            "customerCode" => $customerCode,
            "branchCode" => $temporder['branchCode'],
            "counter" => $temporder['counter'],
            "name" => $customerName,
            "phone" => $customerPhone,
            "orderDate" => $today,
            "paymentMode" => $paymentMode,
            "remark" => "",
            "offerCode" => $temporder['offerCode'],
            "offerType" => $temporder['offerType'],
            "offerDiscount" => $temporder['offerDiscount'],
            "giftCardNo" => $temporder['giftcardNo'],
            "giftDiscount" => $temporder['giftDiscount'],
            "subTotal" => $temporder['subTotal'],
            "discountTotal" => $temporder['discountTotal'],
            "offerDiscountTotal" => $temporder['offerDiscountTotal'],
            "giftDiscountTotal" => $temporder['giftDiscountTotal'],
            "totalTax" => $temporder['totalTax'],
            "totalPayable" => $temporder['totalPayable'],
            "addID" => $userCode,
            "addIP" => $ip,
            "addDate" => $today
        ];
        $this->db->insert("ordermaster", $insertData);
        $currentId = $this->db->insert_id();
        if ($currentId > 0) {
            $orderCode = "ORD" . date('y') . "_" . $currentId;
            $txnId = "TK" . date("ymdHis") . $currentId;
            $this->db->where("id", $currentId)->update('ordermaster', array('code' => $orderCode, "txnId" => $txnId));
            if ($this->db->affected_rows() > 0) return [$orderCode, $txnId];
        }
        return [];
    }

    public function store_order_line(array $orderLine, string $orderCode, string $ip, string $userCode)
    {
        $insertData = [
            "orderCode" => $orderCode,
            "productName" => $orderLine['productName'],
            "sku" => $orderLine["sku"],
            "productCode" => $orderLine["productCode"],
            "variantCode" => $orderLine["variantCode"],
            "barcode" => $orderLine["barcode"],
            "unit" => $orderLine["unit"],
            "price" => $orderLine["price"],
            "qty" => $orderLine["qty"],
            "amount" => $orderLine["amount"],
            "discountPrice" => $orderLine["discountPrice"],
            "discount" => $orderLine["discount"],
            "taxPercent" => $orderLine["taxPercent"],
            "tax" => $orderLine["tax"],
            "totalPrice" => $orderLine["totalPrice"],
            "addID" => $userCode,
            "addIP" => $ip,
            'addDate' => date("Y-m-d H:i:s")
        ];
        $this->db->insert("orderlineentries", $insertData);
        $currentId = $this->db->insert_id();
        if ($currentId > 0) return $currentId;
        return false;
    }

    public function updateStock(array $product, string $branchCode)
    {
        log_message("error", "check =>" . $branchCode . " product " . json_encode($product));
        //select conversion factor
        $this->db->select("unitmaster.conversionFactor,unitmaster.unitRounding");
        $this->db->join("unitmaster", "unitmaster.code=productmaster.storageUnit");
        $this->db->where("productmaster.code", $product['productCode']);
        $storagefactor = $this->db->get("productmaster")->row_array();
        if (!empty($storagefactor)) {
            $conversionFactor = $storagefactor['conversionFactor'];
            $unitRounding = $storagefactor['unitRounding'];
            if ($conversionFactor == null || $conversionFactor == "" || $conversionFactor == "0") $conversionFactor = 1;
            $qty = $product['qty'];
            //select * stock qty
            $this->db->select("stockinfo.code,ifnull(stockinfo.stock,0) as stock,stockinfo.productCode");
            $this->db->from("stockinfo");
            $this->db->where('stockinfo.productCode', $product['productCode']);
            if ($product['variantCode'] != "") {
                $this->db->where('stockinfo.variantCode', $product['variantCode']);
            }
            $this->db->where('stockinfo.branchCode', $branchCode);
            $this->db->where('stockinfo.stock>', "0");
            $stockResult = $this->db->get();
            if ($stockResult->num_rows() > 0) {
                $stockRows = $stockResult->result_array();
                //set actual selling quantity
                $actualSellingQty = $qty * $conversionFactor;
                for ($i = 0; $i < count($stockRows); $i++) {
                    $stockRow = $stockRows[$i];
                    $stockCode = $stockRow['code'];
                    $convertedStock = $stockRow['stock'] * $conversionFactor;
                    $convertedStock = number_format($convertedStock, $unitRounding, '.', '');
                    if ($convertedStock >= $actualSellingQty) {
                        $newStock = $convertedStock - $actualSellingQty;
                        $orgStock = $newStock / $conversionFactor;
                        $orgStock = number_format($orgStock, $unitRounding, '.', '');
                        $this->db->where("code", $stockCode)->update("stockinfo", ["stock" => $orgStock]);
                    } else {
                        $actualSellingQty = $actualSellingQty - $convertedStock;
                        $orgStock = '0';
                        $this->db->where("code", $stockCode)->update("stockinfo", ["stock" => $orgStock]);
                    }
                }
                return "success";
            }
        }
        return "no-stock";
    }

    public function salegiftcardlines()
    {
        return $this->db->get("salegiftcardlineentries")->row_array();
    }

    public function update_query($where, $table, $data)
    {
        $this->db->where($where)->update($table, $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    public function delete_query($where, $table)
    {
        $this->db->where($where)->delete($table);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    public function get_row_array($sel, $where, $table)
    {
        return $this->db->select($sel)->where($where)->get($table)->row_array();
    }

    public function get_offer($offerCode, $today)
    {
        return $this->db->select("*")->where("LOWER(title)", strtolower($offerCode))->where("'$today' between startDate AND endDate")->get("offer")->row_array();
    }

    public function select_query($sel, $table, $where)
    {
        return $this->db->select($sel, $table, $where);
    }

    public function getGiftDiscount($cardNo)
    {
        $sel = "salegiftcard.expiryDate,salegiftcard.cardDetails,salegiftcardlineentries.giftNo,salegiftcardlineentries.isUsed,salegiftcardlineentries.custName,salegiftcardlineentries.custPhone,salegiftcardlineentries.custEmail";
        $table = "salegiftcardlineentries";
        $cond = ['salegiftcardlineentries.giftNo' => $cardNo];
        $order = ["salegiftcardlineentries.id" => "ASC"];
        $join = ["salegiftcard" => "salegiftcardlineentries.salecardCode=salegiftcard.code"];
        $jointype = ["salegiftcard" => "inner"];
        $card = $this->selectQuery($sel, $table, $cond, $order, $join, $jointype, [], 1);
        if ($card) {
            $card = $card->result_array()[0];
            $today = date('Y-m-d H:00:00');
            if ($card['isUsed'] == 1) {
                return "used";
            }
            if ($card['expiryDate'] >= $today) {
                $cardDetails = json_decode($card['cardDetails'], true);
                if (!empty($cardDetails)) {
                    return $cardDetails['discount'];
                }
            }
            return "expired";
        }
        return "invalid";
    }
}
