<?php

require(APPPATH . '/libraries/REST_Controller.php');

use Restserver\Libraries\REST_Controller;

class Posorder extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form', 'url', 'html');
        $this->load->library('form_validation');
    }

    public function fetchProductByBarcode_post()
    {
        $this->load->model("PosorderModel");
        $getData = $this->post();
        if (!empty($getData)) {
            $barcode = $getData['barcode'];
            $lang = array_key_exists("lang", $getData) ? $getData['lang'] : "english";
            $customerName = trim($getData['customerName']);
            $customerPhone = trim($getData['customerPhone']);
            $tempOrderId = trim($getData['tempOrderId']);
            $customerCode = trim($getData['customerCode']);
            $customer = [
                "customerCode" => $customerCode,
                "customerName" => $customerName,
                "customerPhone" => $customerPhone,
            ];
            $userCode = $getData['userCode'];
            $counter = $this->PosorderModel->fetch_counter_number_for_user($userCode);
            $productDetails = $this->PosorderModel->fetch_product_by_barcode($barcode);
            if ($counter != "") {
                $counter = [
                    "branchCode" => $counter['branchCode'],
                    "userCode" => $userCode,
                    "counterName" => $counter['counterName']
                ];
                if (!empty($productDetails)) {
                    $today = date('Y-m-d H:i:00');
                    if ($productDetails['expiryDate'] != null && $productDetails['expiryDate'] != "0000-00-00") {
                        //check expiry date
                        if ($productDetails['expiryDate'] >= $today) {
                            //check stock    
                            $this->check_stock($productDetails, $counter, $tempOrderId, $customer);
                        }
                        return $this->response(array("status" => "300", "message" => "Product's expiry date has been lasped. Cannot add the selected product to the cart."), 200);
                    } else {
                        //check stock
                        $this->check_stock($productDetails, $counter, $tempOrderId, $customer);
                    }
                    return $this->response(array("status" => "300", "message" => "Product's expiry date has been lasped. Cannot add the selected product to the cart."), 200);
                } else {
                    return $this->response(array("status" => "300", "message" => "No product found related to the entered barcode number"), 200);
                }
            } else {
                return $this->response(array("status" => "300", "message" => "Failed to get counter details for user"), 200);
            }
        }
        return $this->response(array("status" => "400", "message" => "Request parameters not found"), 200);
    }

    public function check_stock(array $productDetails, array $counter, string $tempOrderId, array $customer)
    {
        $this->load->model('PosorderModel');
        $stock = $this->PosorderModel->check_product_stock_with_temp_order($productDetails, $counter, $tempOrderId);
        if ($stock=="in-stock") {
            if (trim($tempOrderId) != "") {
                $result = $this->PosorderModel->add_product_to_temp_order($productDetails, $tempOrderId);
                if ($result) {
                    $productDetails['tempRowId'] = $result['orderItemId'];
                    $productDetails['orderQty'] = $result['qty'];
                    $productDetails['totalPrice'] = $result['totalPrice'];
                    $data['order'] = $this->PosorderModel->get_temp_order_data_by_id($tempOrderId);
                    $data['product'] = $productDetails;
                    return $this->response(array("status" => "200", "message" => "Item/product added successfully.", "data" => $data), 200);
                }
                return $this->response(array("status" => "300", "message" => "Failed to add item/product.wh"), 200);
            } else {
                $resultTempOrderId = $this->PosorderModel->create_temp_order($customer, $counter);
                if ($resultTempOrderId) {
                    $result = $this->PosorderModel->add_product_to_temp_order($productDetails, $resultTempOrderId);
                    if ($result) {
                        $productDetails['tempRowId'] = $result['orderItemId'];
                        $productDetails['orderQty'] = $result['qty'];
                        $productDetails['totalPrice'] = $result['totalPrice'];
                        $data['order'] = $this->PosorderModel->get_temp_order_data_by_id($resultTempOrderId);
                        $data['product'] = $productDetails;
                        return $this->response(array("status" => "200", "message" => "Item/product added successfully.", "data" => $data), 200);
                    }
                    return $this->response(array("status" => "300", "message" => "Failed to add item/product."), 200);
                }
                return $this->response(array("status" => "300", "message" => "Failed to create order and add item/product. Please try again."), 200);
            }
        }
        return $this->response(array("status" => "300", "message" => "No more items in stock. Cannot add this product."), 200);
    }

    public function createCustomer_post()
    {
        $this->load->model('PosorderModel');
        $postData = $this->post();
        if (trim($postData['userCode']) != "" && trim($postData['name']) != "" && trim($postData['phone']) != "") {
            $phone = $postData['phone'];
            $name = ucwords(strtolower($postData['name']));
            $userCode =  $postData['userCode'];
            $customer = $this->PosorderModel->check_unique_users($phone);
            if (!empty($customer)) {
                return $this->response(array("status" => "200", "message" => "Customer found and selected successfully", "data" => $customer), 200);
            } else {
                $transaction = ['name' => $name, 'arabicName' => $name , "phone" => $phone, "addID" => $userCode];
                $this->load->model('GlobalModel');
                $customer = $this->GlobalModel->addNew($transaction, "customer", "CUST");
                if ($customer != "false") {
                    return $this->response(array("status" => "200", "message" => "Customer created successfully.", "data" => ["code" => $customer, "name" => $name, "phone" => $phone]), 200);
                }
                return $this->response(array("status" => "300", "message" => "Failed to create customer."), 200);
            }
        }
        return $this->response(array("status" => "400", "message" => "Invalid parameters passed"), 200);
    }

    public function createDraftOrder_post()
    {
        $this->load->model('PosorderModel');
        $postData = $this->post();
        if (trim($postData['orderId']) != "") {
            $orderId = $postData['orderId'];
            $customerCode = trim($postData['customerCode']);
            $customerName = trim($postData['customerName']);
            $customerPhone = trim($postData['customerPhone']);
            $customer = [
                "customerCode" => $customerCode,
                "customerName" => $customerName,
                "customerPhone" => $customerPhone,
            ];
            $update = $this->PosorderModel->update_query(["orderId" => $orderId], "ordertemp", ["draft" => date('Y-m-d H:i:s'), "status" => "draft", "customer" => json_encode($customer)]);
            if ($update) {
                return $this->response(array("status" => "200", "message" => "Draft order created successfully..."), 200);
            }
            return $this->response(array("status" => "300", "message" => "Failed to create current order as draft."), 200);
        }
        return $this->response(array("status" => "400", "message" => "Invalid parameters passed"), 200);
    }

    public function cancelOrder_post()
    {
        $this->load->model('PosorderModel');
        $postData = $this->post();
        if (trim($postData['orderId']) != "") {
            $orderId = $postData['orderId'];
            $delete = $this->PosorderModel->delete_query(["orderId" => $orderId], "ordertempproducts");
            $delete = $this->PosorderModel->delete_query(["orderId" => $orderId], "ordertemp");
            return $this->response(array("status" => "200", "message" => "Order cleared successfully"), 200);
        }
        return $this->response(array("status" => "400", "message" => "Invalid parameters passed"), 200);
    }

    public function removeItemFromOrder_post()
    {
        $this->load->model('PosorderModel');
        $postData = $this->post();
        if (trim($postData['orderId']) != "" && trim($postData['tempPrdId']) != "" && trim($postData['rowCount']) != "") {
            $orderId = $postData['orderId'];
            $this->PosorderModel->delete_query(["id" => $postData["tempPrdId"]], "ordertempproducts");
            $message = "Product removed successfully";
            if ($postData["rowCount"] == 1) {
                $this->PosorderModel->delete_query(["orderId" => $orderId], "ordertemp");
                $message = "Order removed successfully";
            }
            return $this->response(array("status" => "200", "message" => $message), 200);
        }
        return $this->response(array("status" => "400", "message" => "Invalid parameters passed"), 200);
    }

    public function fetchOrderDetails_post()
    {
        $this->load->model('PosorderModel');
        $postData = $this->post();
        if (trim($postData['orderId']) != "") {
            $orderId = $postData['orderId'];
            $order = $this->PosorderModel->get_row_array("*", ["orderId" => $orderId], "ordertemp");
            if (!empty($order)) {
                $orderProducts = $this->PosorderModel->get_temp_order_products($orderId);
                $order['products'] = $orderProducts;
                return $this->response(array("status" => "200", "message" => "Data Found", "data" => $order), 200);
            }
            return $this->response(array("status" => "300", "message" => "Failed to fetch order or order not found. Please try again."), 200);
        }
        return $this->response(array("status" => "400", "message" => "Invalid parameters passed"), 200);
    }

    public function placeOrder_post()
    {
        $this->load->model('PosorderModel');
        $postData = $this->post();
        if (trim($postData['orderId']) != "" && trim($postData['userCode']) != "" && trim($postData['paymentMode'] != "")) {
            $ip = $_SERVER['REMOTE_ADDR'];
            $orderId = $postData['orderId'];
            $userCode = $postData['userCode'];
            $customerCode = trim($postData['customerCode']);
            $customerName = trim($postData['customerName']);
            $customerPhone = trim($postData['customerPhone']);
            $paymentMode = trim($postData['paymentMode']);
            $amountdet = trim($postData['amountdet']);
            $offerCode = trim($postData['offerCode']);
            $giftCode = trim($postData['giftCode']);
            $customer = [
                "customerCode" => $customerCode,
                "customerName" => $customerName,
                "customerPhone" => $customerPhone,
            ];
            $order = $this->PosorderModel->get_row_array("*", ["orderId" => $orderId], "ordertemp");
            if (!empty($order)) {
                $branchCode = $order['branchCode'];
                $orderProducts = $this->PosorderModel->get_temp_order_products_by_id($orderId);

                if ($amountdet != "") {
                    $ar_amountdet = json_decode($amountdet, true);
                    $order['subTotal'] = $ar_amountdet['subTotal'];
                    $order['totalDiscount'] = $ar_amountdet['totalDiscount'];
                    $order['offerDiscountTotal'] = $ar_amountdet['totalOfferDiscount'];
                    $order['totalGiftDiscount'] = $ar_amountdet['totalGiftDiscount'];
                    $order['totalTax'] = $ar_amountdet['totalTax'];
                    $order['offerCode'] = $offerCode;
                    $order['totalPayable'] = $ar_amountdet['totalPayable'];
                    $order['offerType'] = $ar_amountdet['offerType'];
                    $order['offerDiscount'] = $ar_amountdet['offerAmount'];
                    $order['giftcardNo'] = $giftCode;
                    $order['giftDiscount'] = $ar_amountdet['giftAmount'];
                } else {
                    $order["offerCode"] = "";
                    $order["offerType"] = "";
                    $order["offerDiscount"] = "0.00";
                    $order["giftCardNo"] = "";
                    $order["giftDiscount"] = "0.00";
                    $order["subTotal"] = $order['subTotal'];
                    $order["discountTotal"] = $order['discountTotal'];
                    $order["offerDiscountTotal"] = "0.00";
                    $order["giftDiscountTotal"] = "0.00";
                    $order["totalTax"] = $order['taxTotal'];
                    $order["totalPayable"] = $order['totalPayable'];
                }

                $orderMaster = $this->PosorderModel->store_order_master($order, $customer, $paymentMode, $ip, $userCode);
                if (!empty($orderMaster)) {
                    if ($giftCode != "") {
                        $this->PosorderModel->update_query(["giftNo" => $giftCode], "salegiftcardlineentries", ["isUsed" => "1", "editID" => $userCode, "editDate" => date("Y-m-d H:i:s"), "editIP" => $ip]);
                    }
                    $orderCode = $orderMaster[0];
                    $txnId = $orderMaster[1];
                    $lineCount = 0;
                    for ($i = 0; $i < count($orderProducts); $i++) {
                        $product = $orderProducts[$i];
                        if ($amountdet != "") {
                            $ar_amountdet = json_decode($amountdet, true);
                            $productAmounts = $ar_amountdet['productAmounts'];
                            if (!empty($productAmounts)) {
                                foreach ($productAmounts as $k => $v) {
                                    if ($product['productCode'] == $v['productCode'] && $product['variantCode'] == $v['variantCode'] && $product['barcode'] == $v['barcode']) {
                                        $product['discount'] = $v['productTotalDiscount'];
                                        $product['tax'] = $v['tax'];;
                                        $product['totalPrice'] = $v['amount'];
                                        $product['discountPrice'] = $v['productDiscount'];
                                    }
                                }
                            }
                        }

                        $line = $this->PosorderModel->store_order_line($product, $orderCode, $ip, $userCode);
                        if ($line) {
                            $this->PosorderModel->updateStock($product, $branchCode);
                            $lineCount++;
                        }
                    }
                    if ($lineCount == count($orderProducts)) {
                        $this->PosorderModel->delete_query(["orderId" => $orderId], "ordertempproducts");
                        $this->PosorderModel->delete_query(["orderId" => $orderId], "ordertemp");
                        $this->PosorderModel->update_query(["code" => $orderCode], "ordermaster", ["totalItems" => count($orderProducts)]);
                        return $this->response(array("status" => "200", "txnId" => $txnId, "message" => "Order placed and processed successfully..."), 200);
                    } else {
                        $this->PosorderModel->delete_query(["code" => $orderId], "ordermaster");
                        $this->PosorderModel->delete_query(["orderCode" => $orderCode], "orderlineentries");
                        return $this->response(array("status" => "300", "message" => "Failed to process & store your requested order. Please try again"), 200);
                    }
                }
            }
            return $this->response(array("status" => "300", "message" => "Failed to fetch order or order not found. Please try again."), 200);
        }
        return $this->response(array("status" => "400", "message" => "Request parameters not found"), 200);
    }


    public function applyOfferGift_post()
    {
        $this->load->model('PosorderModel');
        $postData = $this->post();
        if (trim($postData['orderId']) != "") {
            $giftNo = trim($postData['giftCode']);
            $offerCode = trim($postData['offerCode']);
            $orderId = trim($postData["orderId"]);

            if ($offerCode != "" && $giftNo != "") {
                $offer = $this->PosorderModel->get_offer($postData['offerCode'], date('Y-m-d H:00:00'));
                if (!empty($offer)) {
                    $giftDiscount = $this->PosorderModel->getGiftDiscount($giftNo);
                    $giftErrorHayStack = ["used", "expired", "invalid"];
                    if (in_array($giftDiscount, $giftErrorHayStack)) {
                        return $this->response(array("status" => "300", "message" => "This gift card is $giftDiscount"), 200);
                    } else {
                        $this->calcGiftOffer($offer, $giftDiscount, $orderId);
                    }
                }
                return $this->response(array("status" => "300", "message" => "This offer code is either expired or invalid. Please try with different offer code"), 200);
            } else if ($offerCode != "" && $giftNo == "") {
                $offer = $this->PosorderModel->get_offer($postData['offerCode'], date('Y-m-d H:00:00'));
                if (!empty($offer)) {
                    $this->calcOffer($offer, $orderId);
                }
                return $this->response(array("status" => "300", "message" => "This offer code is either expired or invalid. Please try with different offer code"), 200);
            } else if ($offerCode == "" && $giftNo != "") {
                $giftDiscount = $this->PosorderModel->getGiftDiscount($giftNo);
                $giftErrorHayStack = ["used", "expired", "invalid"];
                if (in_array($giftDiscount, $giftErrorHayStack)) {
                    return $this->response(array("status" => "300", "message" => "This gift card is $giftDiscount"), 200);
                } else {
                    $this->calcGift($giftDiscount, $orderId);
                }
            }
        }
        return $this->response(array("status" => "400", "message" => "Invalid parameters passed"), 200);
    }

    public function calcGiftOffer($offer, $giftDiscount, $orderId)
    {
        $this->load->model("PosorderModel");
        $orderId = $orderId;
        $orderData = $this->PosorderModel->get_temp_order_data_by_id($orderId);
        $orderProducts = $this->PosorderModel->get_temp_order_products($orderId);
        if (!empty($orderProducts)) {
            $offerTitle = $offer['title'];
            $offerType = $offer['offerType'];
            $offerDiscount = $offer['discount'];
            $offerMinAmount = $offer['minimumAmount'];
            $offerCapLimit = $offer['capLimit'];
            $offerFlatAmount = $offer['flatAmount'];
            $orderAmount = number_format($orderData['subTotal'] - $orderData['discountTotal'], 2, ".", "");
            if ($offerMinAmount > 0) {
                if ($orderAmount < $offerMinAmount) {
                    return $this->response(array("status" => "300", "message" => "Minimum order amount must be minimum of - $offerMinAmount. Current Sub-Total is $orderAmount"), 200);
                }
            }
            $giftDiscountTotal = $subTotal = $disTotal = $totalPayable = $totalPayable = $offerDiscountTotal = $taxTot = $amountTot = 0;
            if ($offerType == "cap") {
                for ($i = 0; $i < count($orderProducts); $i++) {
                    $product = $orderProducts[$i];
                    $disPrice = number_format($product['price'] - $product['discountPrice'], 2, ".", "");
                    $actAmt = number_format(($disPrice * $product['qty']), 2, ".", "");
                    $dis = number_format(($actAmt * ($offerDiscount * 0.01)), 2, ".", "");
                    $disTotal += $dis;
                }
                if ($offerCapLimit > 0) {
                    if ($disTotal >= $offerCapLimit) {
                        $ar = [];
                        $offerDiscount = $offerCapLimit;
                        $disTotal = 0;
                        for ($i = 0; $i < count($orderProducts); $i++) {
                            $product = $orderProducts[$i];
                            $tempPrdId = $product['id'];
                            $subTotal += number_format($product['price'] * $product['qty'], 2, ".", "");
                            $disPrice = number_format($product['price'] - $product['discountPrice'], 2, ".", "");
                            $actAmt = number_format(($disPrice * $product['qty']), 2, ".", "");
                            $prdbaseDiscount = number_format($product['discountPrice'] * $product['qty'], 2, ".", "");
                            $disTotal += $prdbaseDiscount;
                            /** offer discount flat amount */
                            if ($offerDiscount >= $actAmt) {
                                $offerDiscount = number_format(($offerDiscount - $actAmt), 2, ".", "");
                                $offerDiscountTotal += $actAmt;
                                $amtafterDiscont = 0;
                            } else {
                                $amtafterDiscont =  number_format(($actAmt - $offerDiscount), 2, ".", "");
                                $offerDiscountTotal += $offerDiscount;
                                $amtafterDiscont = 0;
                            }
                            /** gift discoount percent */
                            $giftDiscountAmount = number_format(($amtafterDiscont * ($giftDiscount * 0.01)), 2, ".", "");
                            $giftDiscountTotal += $giftDiscountAmount;
                            $amtafterDiscont = $amtafterDiscont - $giftDiscountAmount;
                            /** product price calculation */
                            $tax = number_format(($amtafterDiscont * ($product['taxPercent'] * 0.01)), 2, ".", "");
                            $taxTot = $taxTot + $tax;
                            $productTotAmount = number_format($amtafterDiscont + $tax, 2, ".", "");
                            $totalPayable = number_format(($totalPayable + $productTotAmount), 2, ".", "");
                            /** per product discount */
                            $prdDis = number_format($prdbaseDiscount + $offerDiscount + $giftDiscountAmount, 2, ".", "");
                            $ar[] = [
                                "productCode" => $product['productCode'],
                                "variantCode" => $product['variantCode'],
                                "barcode" => $product['barcode'],
                                "rowCode" => $product['barcode'] . "-" . $product['productCode'] . "-" . $product['variantCode'],
                                "productPrice" => $product['price'],
                                "qty" => $product['qty'],
                                "productDiscount" => $prdbaseDiscount,
                                "offerDiscount" => $offerDiscount,
                                "giftDiscount" => $giftDiscountAmount,
                                "actualAmt" => $actAmt,
                                "productTotalDiscount" => $prdDis,
                                "amtafterDiscont" => $amtafterDiscont,
                                "tax" => $tax,
                                "amount" => $productTotAmount,
                            ];
                        }
                        $data['section'] = "offer+gift => cap limit exceed";
                        $data['offerType'] = "cap";
                        $data['offerAmount'] = $offerCapLimit;
                        $data['giftAmount'] = $giftDiscount;
                        $data['productAmounts'] = $ar;
                        $data['subTotal'] =  number_format($subTotal, 2, ".", "");
                        $data['totalDiscount'] = number_format($disTotal, 2, ".", "");
                        $data['totalOfferDiscount'] = number_format($offerDiscountTotal, 2, ".", "");
                        $data['totalGiftDiscount'] = number_format($giftDiscountTotal, 2, ".", "");
                        $data['totalTax'] = number_format($taxTot, 2, ".", "");
                        $data['totalPayable'] =  number_format($totalPayable, 2, ".", "");
                        return $this->response(array("status" => 200, "data" => $data), 200);
                    }
                }
                $ar = [];
                $offerDiscount = $offerDiscount;
                $disTotal = 0;
                for ($i = 0; $i < count($orderProducts); $i++) {
                    $product = $orderProducts[$i];
                    $tempPrdId = $product['id'];
                    $subTotal += number_format($product['price'] * $product['qty'], 2, ".", "");
                    $disPrice = number_format($product['price'] - $product['discountPrice'], 2, ".", "");
                    $actAmt = number_format(($disPrice * $product['qty']), 2, ".", "");
                    $prdbaseDiscount = number_format($product['discountPrice'] * $product['qty'], 2, ".", "");
                    $disTotal += $prdbaseDiscount;
                    /** offer discount cap percent */
                    echo $productOfferDiscount =  number_format(($actAmt * ($offerDiscount * 0.01)), 2, ".", "");
                    $offerDiscountTotal += $productOfferDiscount;
                    $amtafterDiscont =  $actAmt - $productOfferDiscount;
                    /** gift discount percent */
                    $giftDiscountAmount = number_format(($amtafterDiscont * ($giftDiscount * 0.01)), 2, ".", "");
                    $giftDiscountTotal += $giftDiscountAmount;
                    $amtafterDiscont = $amtafterDiscont - $giftDiscountAmount;
                    /** product price calculation */
                    $tax = number_format(($amtafterDiscont * ($product['taxPercent'] / 100)), 2, ".", "");
                    $taxTot = $taxTot + $tax;
                    $productTotAmount = number_format($amtafterDiscont + $tax, 2, ".", "");
                    $totalPayable = number_format(($totalPayable + $productTotAmount), 2, ".", "");
                    /** per product discount */
                    $prdDis = number_format($prdbaseDiscount + $productOfferDiscount + $giftDiscountAmount, 2, ".", "");
                    $ar[] = [
                        "productCode" => $product['productCode'],
                        "variantCode" => $product['variantCode'],
                        "barcode" => $product['barcode'],
                        "rowCode" => $product['barcode'] . "-" . $product['productCode'] . "-" . $product['variantCode'],
                        "productPrice" => $product['price'],
                        "qty" => $product['qty'],
                        "productDiscount" => $prdbaseDiscount,
                        "offerDiscount" => $productOfferDiscount,
                        "giftDiscount" => $giftDiscountAmount,
                        "actualAmt" => $actAmt,
                        "productTotalDiscount" => $prdDis,
                        "amtafterDiscont" => $amtafterDiscont,
                        "tax" => $tax,
                        "amount" => $productTotAmount,
                    ];
                }
                $data['section'] = "offer+gift => in cap limit";
                $data['offerType'] = "cap";
                $data['offerAmount'] = $offerDiscount;
                $data['giftAmount'] = $giftDiscount;
                $data['productAmounts'] = $ar;
                $data['subTotal'] =  number_format($subTotal, 2, ".", "");
                $data['totalDiscount'] = number_format($disTotal, 2, ".", "");
                $data['totalOfferDiscount'] = number_format($offerDiscountTotal, 2, ".", "");
                $data['totalGiftDiscount'] = number_format($giftDiscountTotal, 2, ".", "");
                $data['totalTax'] = number_format($taxTot, 2, ".", "");
                $data['totalPayable'] =  number_format($totalPayable, 2, ".", "");
                return $this->response(array("status" => 200, "data" => $data), 200);
            } else {
                $disTotal = 0;
                $ar = [];
                $offerDiscount = $offerFlatAmount;
                for ($i = 0; $i < count($orderProducts); $i++) {
                    $product = $orderProducts[$i];
                    $tempPrdId = $product['id'];
                    $subTotal += number_format($product['price'] * $product['qty'], 2, ".", "");
                    $disPrice = number_format($product['price'] - $product['discountPrice'], 2, ".", "");
                    $actAmt = number_format(($disPrice * $product['qty']), 2, ".", "");
                    $prdbaseDiscount = number_format($product['discountPrice'] * $product['qty'], 2, ".", "");
                    $disTotal += $prdbaseDiscount;
                    /** offer flat discount */
                    if ($offerDiscount >= $actAmt) {
                        $offerDiscount = number_format(($offerDiscount - $actAmt), 2, ".", "");
                        $offerDiscountTotal += $actAmt;
                        $amtafterDiscont = 0;
                    } else {
                        $amtafterDiscont =  number_format(($actAmt - $offerDiscount), 2, ".", "");
                        $offerDiscountTotal += $offerDiscount;
                        $offerDiscount = 0;
                    }
                    /** gift discount */
                    $giftDiscountAmount = number_format(($amtafterDiscont * ($giftDiscount * 0.01)), 2, ".", "");
                    $giftDiscountTotal += $giftDiscountAmount;
                    $amtafterDiscont = $amtafterDiscont - $giftDiscountAmount;
                    /** product price calculation */
                    $tax = number_format(($amtafterDiscont * ($product['taxPercent'] / 100)), 2, ".", "");
                    $taxTot = $taxTot + $tax;
                    $productTotAmount = number_format($amtafterDiscont + $tax, 2, ".", "");
                    $totalPayable = number_format(($totalPayable + $productTotAmount), 2, ".", "");
                    /** per product discount */
                    $prdDis = number_format($prdbaseDiscount + $offerDiscount + $giftDiscountAmount, 2, ".", "");
                    $ar[] = [
                        "productCode" => $product['productCode'],
                        "variantCode" => $product['variantCode'],
                        "barcode" => $product['barcode'],
                        "rowCode" => $product['barcode'] . "-" . $product['productCode'] . "-" . $product['variantCode'],
                        "productPrice" => $product['price'],
                        "qty" => $product['qty'],
                        "productDiscount" => $prdbaseDiscount,
                        "offerDiscount" => $offerDiscount,
                        "giftDiscount" => $giftDiscountAmount,
                        "actualAmt" => $actAmt,
                        "productTotalDiscount" => $prdDis,
                        "amtafterDiscont" => $amtafterDiscont,
                        "tax" => $tax,
                        "amount" => $productTotAmount,
                    ];
                }
                $data['section'] = "offer+gift => flat";
                $data['offerType'] = "flat";
                $data['offerAmount'] = $offerFlatAmount;
                $data['giftAmount'] = $giftDiscount;
                $data['productAmounts'] = $ar;
                $data['subTotal'] =  number_format($subTotal, 2, ".", "");
                $data['totalDiscount'] = number_format($disTotal, 2, ".", "");
                $data['totalOfferDiscount'] = number_format($offerDiscountTotal, 2, ".", "");
                $data['totalGiftDiscount'] = number_format($giftDiscountTotal, 2, ".", "");
                $data['totalTax'] = number_format($taxTot, 2, ".", "");
                $data['totalPayable'] =  number_format($totalPayable, 2, ".", "");
                return $this->response(array("status" => 200, "data" => $data), 200);
            }
        }
        return $this->response(array("status" => "300", "message" => "Please add some products to the cart to apply offer/gift"), 200);
    }

    public function calcOffer($offer, $orderId)
    {
        $this->load->model("PosorderModel");
        $giftDiscount = 0;
        $orderId = $orderId;
        $orderData = $this->PosorderModel->get_temp_order_data_by_id($orderId);
        $orderProducts = $this->PosorderModel->get_temp_order_products($orderId);
        if (!empty($orderProducts)) {
            $offerTitle = $offer['title'];
            $offerType = $offer['offerType'];
            $offerDiscount = $offer['discount'];
            $offerMinAmount = $offer['minimumAmount'];
            $offerCapLimit = $offer['capLimit'];
            $offerFlatAmount = $offer['flatAmount'];
            $orderAmount = number_format($orderData['subTotal'] - $orderData['discountTotal'], 2, ".", "");
            if ($offerMinAmount > 0) {
                if ($orderAmount < $offerMinAmount) {
                    return $this->response(array("status" => "300", "message" => "Minimum order amount must be minimum of - $offerMinAmount. Current Sub-Total is $orderAmount"), 200);
                }
            }
            $giftDiscountTotal = $subTotal = $disTotal = $totalPayable = $totalPayable = $offerDiscountTotal = $taxTot = $amountTot = 0;
            if ($offerType == "cap") {
                for ($i = 0; $i < count($orderProducts); $i++) {
                    $product = $orderProducts[$i];
                    $disPrice = number_format($product['price'] - $product['discountPrice'], 2, ".", "");
                    $actAmt = number_format(($disPrice * $product['qty']), 2, ".", "");
                    $dis = number_format(($actAmt * ($offerDiscount * 0.01)), 2, ".", "");
                    $disTotal += $dis;
                }
                if ($offerCapLimit > 0) {
                    if ($disTotal >= $offerCapLimit) {
                        $disTotal = 0;
                        $ar = [];
                        $offerDiscount = $offerCapLimit;
                        for ($i = 0; $i < count($orderProducts); $i++) {
                            $product = $orderProducts[$i];
                            $tempPrdId = $product['id'];
                            $subTotal += number_format($product['price'] * $product['qty'], 2, ".", "");
                            $disPrice = number_format($product['price'] - $product['discountPrice'], 2, ".", "");
                            $actAmt = number_format(($disPrice * $product['qty']), 2, ".", "");
                            $prdbaseDiscount = number_format($product['discountPrice'] * $product['qty'], 2, ".", "");
                            $disTotal += $prdbaseDiscount;
                            /** offer discount flat amount */
                            if ($offerDiscount >= $actAmt) {
                                $offerDiscount = number_format(($offerDiscount - $actAmt), 2, ".", "");
                                $offerDiscountTotal += $actAmt;
                                $amtafterDiscont = 0;
                            } else {
                                $amtafterDiscont =  number_format(($actAmt - $offerDiscount), 2, ".", "");
                                $offerDiscountTotal += $offerDiscount;
                                $offerDiscount = 0;
                            }
                            /** product price calculation */
                            $tax = number_format(($amtafterDiscont * ($product['taxPercent'] * 0.01)), 2, ".", "");
                            $taxTot = $taxTot + $tax;
                            $productTotAmount = number_format($amtafterDiscont + $tax, 2, ".", "");
                            $totalPayable = number_format(($totalPayable + $productTotAmount), 2, ".", "");
                            /** per product discount */
                            $prdDis = number_format($prdbaseDiscount + $offerDiscount, 2, ".", "");
                            $ar[] = [
                                "productCode" => $product['productCode'],
                                "variantCode" => $product['variantCode'],
                                "barcode" => $product['barcode'],
                                "rowCode" => $product['barcode'] . "-" . $product['productCode'] . "-" . $product['variantCode'],
                                "productPrice" => $product['price'],
                                "qty" => $product['qty'],
                                "productDiscount" => $prdbaseDiscount,
                                "offerDiscount" => $offerDiscount,
                                "giftDiscount" => "0.00",
                                "actualAmt" => $actAmt,
                                "productTotalDiscount" => $prdDis,
                                "amtafterDiscont" => $amtafterDiscont,
                                "tax" => $tax,
                                "amount" => $productTotAmount,
                            ];
                        }
                        $data['section'] = "cap limit exceed";
                        $data['offerType'] = "cap";
                        $data['offerAmount'] = $offerCapLimit;
                        $data['giftAmount'] = "0.00";
                        $data['productAmounts'] = $ar;
                        $data['subTotal'] =  number_format($subTotal, 2, ".", "");
                        $data['totalDiscount'] = number_format($disTotal, 2, ".", "");
                        $data['totalOfferDiscount'] = number_format($offerDiscountTotal, 2, ".", "");
                        $data['totalGiftDiscount'] = number_format($giftDiscountTotal, 2, ".", "");
                        $data['totalTax'] = number_format($taxTot, 2, ".", "");
                        $data['totalPayable'] =  number_format($totalPayable, 2, ".", "");
                        return $this->response(array("status" => 200, "data" => $data), 200);
                    }
                }
                $ar = [];
                $disTotal = 0;
                $offerDiscount = $offerDiscount;
                for ($i = 0; $i < count($orderProducts); $i++) {
                    $product = $orderProducts[$i];
                    $tempPrdId = $product['id'];
                    $subTotal += number_format($product['price'] * $product['qty'], 2, ".", "");
                    $disPrice = number_format($product['price'] - $product['discountPrice'], 2, ".", "");
                    $actAmt = number_format(($disPrice * $product['qty']), 2, ".", "");
                    $prdbaseDiscount = number_format($product['discountPrice'] * $product['qty'], 2, ".", "");
                    $disTotal += $prdbaseDiscount;
                    /** offer discount cap percent */
                    $productOfferDiscount =  number_format(($actAmt * ($offerDiscount * 0.01)), 2, ".", "");
                    $offerDiscountTotal += $productOfferDiscount;
                    $amtafterDiscont =  number_format($actAmt - $productOfferDiscount, 2, ".", "");
                    /** product price calculation */
                    $tax = number_format(($amtafterDiscont * ($product['taxPercent'] / 100)), 2, ".", "");
                    $taxTot = $taxTot + $tax;
                    $productTotAmount = number_format($amtafterDiscont + $tax, 2, ".", "");
                    $totalPayable = number_format(($totalPayable + $productTotAmount), 2, ".", "");
                    /** per product discount */
                    $prdDis = number_format($prdbaseDiscount + $productOfferDiscount, 2, ".", "");
                    $ar[] = [
                        "productCode" => $product['productCode'],
                        "variantCode" => $product['variantCode'],
                        "barcode" => $product['barcode'],
                        "rowCode" => $product['barcode'] . "-" . $product['productCode'] . "-" . $product['variantCode'],
                        "productPrice" => $product['price'],
                        "qty" => $product['qty'],
                        "productDiscount" => $prdbaseDiscount,
                        "offerDiscount" => $productOfferDiscount,
                        "giftDiscount" => "0.00",
                        "actualAmt" => $actAmt,
                        "productTotalDiscount" => $prdDis,
                        "amtafterDiscont" => $amtafterDiscont,
                        "tax" => $tax,
                        "amount" => $productTotAmount,
                    ];
                }
                $data['section'] = "in cap limit";
                $data['offerType'] = "cap";
                $data['offerAmount'] = $offerDiscount;
                $data['giftAmount'] = "0.00";
                $data['productAmounts'] = $ar;
                $data['subTotal'] =  number_format($subTotal, 2, ".", "");
                $data['totalDiscount'] = number_format($disTotal, 2, ".", "");
                $data['totalOfferDiscount'] = number_format($offerDiscountTotal, 2, ".", "");
                $data['totalGiftDiscount'] = $giftDiscountTotal;
                $data['totalTax'] = number_format($taxTot, 2, ".", "");
                $data['totalPayable'] =  number_format($totalPayable, 2, ".", "");
                return $this->response(array("status" => 200, "data" => $data), 200);
            } else {
                $ar = [];
                $offerDiscount = $offerFlatAmount;
                for ($i = 0; $i < count($orderProducts); $i++) {
                    $product = $orderProducts[$i];
                    $tempPrdId = $product['id'];
                    $subTotal += number_format($product['price'] * $product['qty'], 2, ".", "");
                    $disPrice = number_format($product['price'] - $product['discountPrice'], 2, ".", "");
                    $actAmt = number_format(($disPrice * $product['qty']), 2, ".", "");
                    $prdbaseDiscount = number_format($product['discountPrice'] * $product['qty'], 2, ".", "");
                    $disTotal += $prdbaseDiscount;
                    /** offer flat discount */
                    if ($offerDiscount >= $actAmt) {
                        $offerDiscount = number_format(($offerDiscount - $actAmt), 2, ".", "");
                        $offerDiscountTotal += $actAmt;
                        $amtafterDiscont = 0;
                    } else {
                        $amtafterDiscont =  number_format(($actAmt - $offerDiscount), 2, ".", "");
                        $offerDiscountTotal += $offerDiscount;
                        $offerDiscount = 0;
                    }
                    /** product price calculation */
                    $tax = number_format(($amtafterDiscont * ($product['taxPercent'] / 100)), 2, ".", "");
                    $taxTot = $taxTot + $tax;
                    $productTotAmount = number_format($amtafterDiscont + $tax, 2, ".", "");
                    $totalPayable = number_format(($totalPayable + $productTotAmount), 2, ".", "");
                    /** per product discount */
                    $prdDis = $prdbaseDiscount + $offerDiscount;
                    $ar[] = [
                        "productCode" => $product['productCode'],
                        "variantCode" => $product['variantCode'],
                        "barcode" => $product['barcode'],
                        "rowCode" => $product['barcode'] . "-" . $product['productCode'] . "-" . $product['variantCode'],
                        "productPrice" => $product['price'],
                        "qty" => $product['qty'],
                        "productDiscount" => $product['discountPrice'],
                        "offerDiscount" => $offerDiscount,
                        "giftDiscount" => "0.00",
                        "actualAmt" => $actAmt,
                        "productTotalDiscount" => $prdDis,
                        "amtafterDiscont" => $amtafterDiscont,
                        "tax" => $tax,
                        "amount" => $productTotAmount,
                    ];
                }
                $data['section'] = "flat";
                $data['offerType'] = "flat";
                $data['offerAmount'] = $offerFlatAmount;
                $data['giftAmount'] = "0.00";
                $data['productAmounts'] = $ar;
                $data['subTotal'] =  number_format($subTotal, 2, ".", "");
                $data['totalDiscount'] = number_format($disTotal, 2, ".", "");
                $data['totalOfferDiscount'] = number_format($offerDiscountTotal, 2, ".", "");
                $data['totalGiftDiscount'] = number_format($giftDiscountTotal, 2, ".", "");
                $data['totalTax'] = number_format($taxTot, 2, ".", "");
                $data['totalPayable'] =  number_format($totalPayable, 2, ".", "");
                return $this->response(array("status" => 200, "data" => $data), 200);
            }
        }
        return $this->response(array("status" => "300", "message" => "Please add some products to the cart to apply offer/gift"), 200);
    }

    public function calcGift($giftDiscount, $orderId)
    {
        $this->load->model("PosorderModel");
        $orderId = $orderId;
        $orderData = $this->PosorderModel->get_temp_order_data_by_id($orderId);
        $orderProducts = $this->PosorderModel->get_temp_order_products($orderId);
        $giftDiscountTotal = $subTotal = $disTotal = $totalPayable = $totalPayable = $offerDiscountTotal = $taxTot = $amountTot = 0;
        if (!empty($orderProducts)) {
            for ($i = 0; $i < count($orderProducts); $i++) {
                $product = $orderProducts[$i];
                $tempPrdId = $product['id'];
                $disPrice = number_format($product['price'] - $product['discountPrice'], 2, ".", "");
                $actAmt = number_format(($disPrice * $product['qty']), 2, ".", "");
                $subTotal += number_format($product['price'] * $product['qty'], 2, ".", "");
                $prdbaseDiscount = number_format($product['discountPrice'] * $product['qty'], 2, ".", "");
                $disTotal += $prdbaseDiscount;
                /** gift discount percent */
                $giftDiscountAmount = number_format(($actAmt * ($giftDiscount * 0.01)), 2, ".", "");
                $giftDiscountTotal += $giftDiscountAmount;
                $amtafterDiscont = number_format($actAmt - $giftDiscountAmount, 2, ".", "");
                /** product price calculation */
                $tax = number_format(($amtafterDiscont * ($product['taxPercent'] / 100)), 2, ".", "");
                $taxTot = $taxTot + $tax;
                $productTotAmount = number_format($amtafterDiscont + $tax, 2, ".", "");
                $totalPayable = number_format(($totalPayable + $productTotAmount), 2, ".", "");
                /** per product discount */
                $prdDis = number_format($prdbaseDiscount  + $giftDiscountAmount, 2, ".", "");
                $ar[] = [
                    "productCode" => $product['productCode'],
                    "variantCode" => $product['variantCode'],
                    "barcode" => $product['barcode'],
                    "rowCode" => $product['barcode'] . "-" . $product['productCode'] . "-" . $product['variantCode'],
                    "productPrice" => $product['price'],
                    "qty" => $product['qty'],
                    "productDiscount" => $prdbaseDiscount,
                    "actualAmt" => $actAmt,
                    "offerDiscount" => "0.00",
                    "giftDiscount" => $giftDiscountAmount,
                    "productTotalDiscount" => $prdDis,
                    "amtafterDiscont" => $amtafterDiscont,
                    "tax" => $tax,
                    "amount" => $productTotAmount,
                ];
            }
            $data['section'] = "only gift $giftDiscount";
            $data['offerType'] = "";
            $data['offerAmount'] = "0.00";
            $data['giftAmount'] = $giftDiscount;
            $data['productAmounts'] = $ar;
            $data['subTotal'] =  number_format($subTotal, 2, ".", "");
            $data['totalDiscount'] = number_format($disTotal, 2, ".", "");
            $data['totalOfferDiscount'] = number_format($offerDiscountTotal, 2, ".", "");
            $data['totalGiftDiscount'] = number_format($giftDiscountTotal, 2, ".", "");
            $data['totalTax'] = number_format($taxTot, 2, ".", "");
            $data['totalPayable'] =  number_format($totalPayable, 2, ".", "");
            return $this->response(array("status" => 200, "data" => $data), 200);
        }
        return $this->response(array("status" => "300", "message" => "Please add some products to the cart to apply offer/gift"), 200);
    }
}
