<?php

require(APPPATH . '/libraries/REST_Controller.php');

use Restserver\Libraries\REST_Controller;

class Order extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form', 'url', 'html');
        $this->load->library('form_validation');
        $this->load->model('OrderApiModel');
        $this->load->model('GlobalModel');
        $this->load->library('cart');
    }

    public function getReservedTables_get()
    {
        $getData = $this->get();
        if (!empty($getData)) {
            $branchCode = $getData['branchCode'];
            $date = date('Y-m-d H:i');
            $SELECT = "tablereservation.code,tablereservation.customerCode,tablereservation.customerMobile,sectorzonemaster.zoneName,tablemaster.tableNumber,tablereservation.noOfPeople,tablereservation.resDate,tablereservation.startTime,tablereservation.endTime,customer.name";
            $condition = ["tablereservation.isActive" => 1, "tablereservation.branchCode" => $branchCode];
            $orderBy = ["tablereservation.id" => "ASC"];
            $join = [
                "sectorzonemaster" => "sectorzonemaster.id=tablereservation.sectorCode",
                "tablemaster" => "tablemaster.code=tablereservation.tableNumber",
                "customer" => "customer.code=tablereservation.customerCode"
            ];
            $joinType = [
                "sectorzonemaster" => "inner",
                "tablemaster" => "inner",
                "customer" => "inner"
            ];
            $extraCondition = "'$date' BETWEEN TIMESTAMP(date(tablereservation.resDate),tablereservation.startTime) AND TIMESTAMP(date(tablereservation.resDate),tablereservation.endTime)";
            $result = $this->GlobalModel->SELECTQuery($SELECT, "tablereservation", $condition, $orderBy, $join, $joinType, [], "", "", [], $extraCondition);
            if ($result) {
                $data = [];
                $result = $result->result();
                for ($i = 0; $i < count($result); $i++) {
                    $reservation = $result[$i];
                    $fDate = date('Y-m-d H:i:00');
                    $tDate = date('Y-m-d 23:59:59');
                    $orderCart = $this->GlobalModel->directQuery("SELECT * FROM cart WHERE custPhone='$reservation->customerMobile' AND branchCode='$branchCode' AND cartDate BETWEEN '$fDate' AND '$tDate'");
                    if (!empty($orderCart)) {
                        $orderOnGoing = "1";
                    } else {
                        $orderOnGoing = "0";
                    }
                    $ar = [
                        "revCode" => $reservation->code,
                        "customerCode" => $reservation->customerCode,
                        "customerMobile" => $reservation->customerMobile,
                        "customerName" => $reservation->name,
                        "zoneName" => $reservation->zoneName,
                        "tableNumber" => $reservation->tableNumber,
                        "noOfPeople" => $reservation->noOfPeople,
                        "resDate" => date('d/M/Y', strtotime($reservation->resDate)),
                        "startTime" => $reservation->startTime,
                        "endTime" => $reservation->endTime,
                        "orderOngoing" => $orderOnGoing
                    ];

                    $data[] = $ar;
                }
                return $this->response(array("status" => "200", "message" => "Data Found", "data" => $data), 200);
            } else {
                return $this->response(array("status" => "300", "message" => "No Reservations Found.."), 200);
            }
        }
        return $this->response(array("status" => "400", "message" => "Request parameters not found"), 200);
    }

    public function fetchProductDetails_old_get()
    {
        $getData = $this->get();
        if (!empty($getData)) {
            $productCode = $getData['productCode'];
            $lang = $getData['lang'];
            $comboProduct = $getData['comboProduct'];
            $addonHtml = $varientHtml = '';
            $i = 0;
            $varientOption = '';
            if ($productCode != '') {
                if ($comboProduct == "0") {
                    $productDetails = $this->GlobalModel->SELECTActiveDataByField('code', $productCode, 'productmaster');
                    if ($productDetails) {
                        $productDetails = $productDetails->result()[0];
                        $SELECT = "itemmaster.itemEngName,productextras.code,productextras.itemCode,productextras.itemQty,productextras.custPrice as price";
                        $table = "productextras";
                        $condition = ['itemmaster.isActive' => 1, "productextras.isActive" => 1, "productextras.productCode" => $productCode];
                        $orderBy = ['itemmaster.itemEngName' => "ASC"];
                        $join['itemmaster'] = "productextras.itemCode=itemmaster.code";
                        $joinType['itemmaster'] = "inner";
                        $addonDetails = $this->GlobalModel->SELECTQuery($SELECT, $table, $condition, $orderBy, $join, $joinType);
                        if ($addonDetails) {
                            foreach ($addonDetails->result() as  $add) {
                                $i++;
                                $addonHtml .= '<tr id="row' . $i . '">
                                    <td>
                                        <input type="hidden" id="addonCode' . $i . '" name="addonCode' . $i . '" value="' . $add->code . '">
                                        <input type="hidden" id="addonName' . $i . '" name="addonName' . $i . '" value="' . $add->itemEngName . '">
                                        <input type="hidden" id="oldaddonPrice' . $i . '" name="oldaddonPrice' . $i . '" value="' . $add->price . '">
                                        <input type="hidden" id="addonPrice' . $i . '" name="addonPrice' . $i . '" value="' . $add->price . '">
                                        <div class="checkbox checkbox-success">                                                
                                            <input type="checkbox" name="addons" class="addonCheckbox" value="' . $i . '" id="addons_' . $i . '">
                                            <label for="addons_' . $i . '"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">' . $add->itemEngName . '</td>
                                    <td>
                                        <input type="number" name="addonQty' . $i . '" id="addonQty' . $i . '" data-id="' . $i . '" class="form-control text-right addonQtyChange" value="1" min="1">
                                    </td>
                                    <td class="text-center" id="addonSubTotal' . $i . '">' . $add->price . '</td>
                                </tr>';
                            }
                        }

                        $varientHtml .= '<tr>';
                        $varientHtml .= '<td>';
                        $varientHtml .= '
                        <input type="hidden" name="comboProductCode" id="comboProductCode" readonly value="">
                        <input name="variantCode" readonly type="hidden" value="" id="variantCode" />
                        <input name="productCombo" type="hidden" id="productCombo" value="0">
                        <input name="productCode" type="hidden" id="productCode" value="' . $productCode . '">';
                        $varientHtml .= '<input name="productPrice" type="hidden" id="productPrice" value="' . $productDetails->productPrice . '">';
                        $varientHtml .= '<input name="oldproductPrice" type="hidden" id="oldproductPrice" value="' . $productDetails->productPrice . '">';
                        if ($lang == "urdu") {
                            $varientHtml = $productDetails->productUrduName;
                        } else if ($lang == "arabic") {
                            $varientHtml = $productDetails->productArbName;
                        } else if ($lang == "hindi") {
                            $varientHtml = $productDetails->productHinName;
                        } else {
                            $varientHtml .= $productDetails->productEngName;
                        }
                        $varientHtml .= '</td>';
                        $varientHtml .= '<td><input type="number" name="productQty" id="productQty" class="form-control text-right variantPriceChange" value="1" min="1"></td>';
                        $varientHtml .= '<td id="productPriceTotal">' . $productDetails->productPrice . '</td>';
                        $varientHtml .= '</tr>';
                    }
                } else {
                    $SELECT = "productcombo.productComboName,productcombo.productComboPrice,productcombo.productComboImage";
                    $productDetails = $this->GlobalModel->SELECTQuery("productcombo.*", 'productcombo', ["productcombo.code" => $productCode]);
                    if ($productDetails) {
                        $itemsrows = "";
                        $lineCodes = $productNameArr = [];
                        $sel = 'productcombolineentries.productCode,productmaster.productUrduName,productmaster.productArbName,productmaster.productHinName,productmaster.productEngName';
                        $cond = ['productcombolineentries.productComboCode' => $productCode];
                        $orderBy = ['productmaster.productEngName' => "ASC"];
                        $join["productmaster"] = "productcombolineentries.productCode=productmaster.code";
                        $joinType["productmaster"] = "inner";
                        $productLines = $this->GlobalModel->SELECTQuery($sel, "productcombolineentries", $cond, $orderBy, $join, $joinType);
                        if ($productLines) {
                            $itemsrows .= "<tr><td colspan='3'>Combo Items : </td></tr>";
                            foreach ($productLines->result() as $line) {
                                array_push($lineCodes, $line->productCode);
                                $itemsrows .= "<tr><td colspan='3'>";
                                if ($lang == "urdu") {
                                    $itemsrows = $line->productUrduName;
                                    $productName = $line->productUrduName;
                                } else if ($lang == "arabic") {
                                    $itemsrows = $line->productArbName;
                                    $productName = $line->productArbName;
                                } else if ($lang == "hindi") {
                                    $itemsrows = $line->productHinName;
                                    $productName = $line->productHinName;
                                } else {
                                    $itemsrows .= $line->productEngName;
                                    $productName = $line->productEngName;
                                }
                                $itemsrows .= "</td></tr>";
                                array_push($productNameArr, $productName);
                            }
                        }

                        $prdCodes = implode(",", $lineCodes);
                        $prdNames = implode(",", $productNameArr);

                        $productDetails = $productDetails->result()[0];
                        $varientHtml .= '<tr>';
                        $varientHtml .= '<td>';
                        $varientHtml .= '
                            <input type="hidden" name="comboProductCode" id="comboProductCode" readonly value="' . $prdCodes . '">
                            <input type="hidden" name="comboProductNames" id="comboProductNames" readonly value="' . $prdNames . '">
                            <input type="hidden" name="variantCode" id="variantCode" readonly>
                            <input name="productCombo" type="hidden" id="productCombo" value="1">
                            <input name="productCode" type="hidden" id="productCode" value="' . $productCode . '">
                        ';
                        $varientHtml .= '<input name="productPrice" type="hidden" id="productPrice" value="' . $productDetails->productComboPrice . '">';
                        $varientHtml .= '<input name="oldproductPrice" type="hidden" id="oldproductPrice" value="' . $productDetails->productComboPrice . '">';
                        $varientHtml .= $productDetails->productComboName;
                        $varientHtml .= '</td>';
                        $varientHtml .= '<td><input type="number" name="productQty" id="productQty" class="form-control text-right variantPriceChange" value="1" min="1"></td>';
                        $varientHtml .= '<td id="productPriceTotal">' . $productDetails->productComboPrice . '</td>';
                        $varientHtml .= '</tr>';
                        $varientHtml .= $itemsrows;
                    }
                }
                $data['varientHtml'] = $varientHtml;
                $data['addonHtml'] = $addonHtml;
                return $this->response(array("status" => "200", "message" => "Data Found", "data" => $data), 200);
            }
            return $this->response(array("status" => "300", "message" => "No Data Found", "data" => []), 200);
        }
        return $this->response(array("status" => "400", "message" => "Request parameters not found"), 200);
    }

    public function taxesByGroup($taxGroupCode)
    {
        $tax = 0;
        $taxes = $this->GlobalModel->SELECTQuery("taxes", "taxgroupmaster", ["code" => $taxGroupCode, "isActive" => 1]);
        if ($taxes) {
            $taxes = $taxes->result()[0]->taxes;
            $taxes = json_decode($taxes, true);
            if (!empty($taxes)) {
                $condition = ["isActive" => 1];
                $values = "";
                foreach ($taxes as $tax) {
                    $values != "" && $values .= ",";
                    $values .= "'" . $tax . "'";
                }
                $extraCondition = "code in ($values)";
                $orderBy = $join = $joinType = $like = $groupBy = [];
                $limit = 1;
                $offset = "";
                $result = $this->GlobalModel->SELECTQuery("IFNULL(SUM(taxPer),0) as taxPercent", "taxes", $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupBy, $extraCondition);
                if ($result) {
                    $tax = $result->result()[0]->taxPercent;
                    return $tax;
                } else {
                    return $tax;
                }
            } else {
                return $tax;
            }
        } else {
            return $tax;
        }
    }

    public function fetchProductDetails_get()
    {
        $getData = $this->get();
        if (!empty($getData)) {
            $lang = $getData['lang'];
            $productCode = $getData['productCode'];
            $comboProduct = $getData['comboProduct'];
            $data = [];
            $data['lang'] = strtolower($lang);
            if ($comboProduct == "0") {
                $productDetails = $this->GlobalModel->SELECTQuery("productmaster.*", 'productmaster', ["productmaster.code" => $productCode]);
                if ($productDetails) {
                    $data['comboProduct'] = 0;
                    $data['produtCode'] = $productCode;
                    $data['productDetails'] = $productDetails->result()[0];
                    $data['productTaxPercent'] = $this->taxesByGroup($data['productDetails']->productTaxGrp);
                    // customizable items
                    $SELECT = "recipelineentries.itemCode,recipelineentries.itemQty,recipelineentries.unitCode,recipelineentries.itemCost,itemmaster.itemEngName,itemmaster.itemArbName,itemmaster.itemHinName,itemmaster.itemUrduName,unitmaster.unitSName as unit";
                    $table = "recipelineentries";
                    $cond = ["recipecard.productCode" => $productCode, "recipelineentries.isCustomizable" => 1, "itemmaster.isActive" => 1];
                    $orderBy = ["recipelineentries.itemCode" => "ASC"];
                    $join = ["recipecard" => "recipecard.code=recipelineentries.recipeCode", "itemmaster" => "itemmaster.code=recipelineentries.itemCode", "unitmaster" => "unitmaster.code=recipelineentries.unitCode"];
                    $joinType = ["recipecard" => "inner", "itemmaster" => "inner", "unitmaster" => "inner"];
                    $data['customizes'] = $this->GlobalModel->SELECTQuery($SELECT, $table, $cond, $orderBy, $join, $joinType);
                    // extra items
                    $SELECT = "itemmaster.itemEngName,itemmaster.itemArbName,itemmaster.itemHinName,itemmaster.itemUrduName,productextras.code,productextras.itemCode,productextras.itemQty,productextras.itemUom as unitCode,productextras.custPrice as price,unitmaster.unitSName as unit";
                    $table = "productextras";
                    $condition = ['itemmaster.isActive' => 1, "productextras.isActive" => 1, "productextras.productCode" => $productCode];
                    $orderBy = ['itemmaster.itemEngName' => "ASC"];
                    $join = ['itemmaster' => "productextras.itemCode=itemmaster.code", "unitmaster" => "unitmaster.code=productextras.itemUom"];
                    $joinType = ['itemmaster' => "inner", "unitmaster" => "inner"];
                    $extraItems = $this->GlobalModel->SELECTQuery($SELECT, $table, $condition, $orderBy, $join, $joinType);
                    $data['extraitems'] = $extraItems;
                    $result = $this->load->view("cashier/order/productmodal", $data, true);
                    return $this->response(array("status" => "200", "message" => "Data Found", "data" => $result), 200);
                } else {
                    return $this->response(array("status" => "300", "message" => "Cannot add/SELECT this product/item right now..", "data" => []), 200);
                }
            } else {
                $productDetails = $this->GlobalModel->SELECTQuery("productcombo.*", 'productcombo', ["productcombo.code" => $productCode]);
                if ($productDetails) {
                    $data['comboProduct'] = 1;
                    $data['produtCode'] = $productCode;
                    $data['productDetails'] = $productDetails->result()[0];

                    $lineCodes = $productNameArr = [];
                    $sel = 'productcombolineentries.productCode,productmaster.productUrduName,productmaster.productArbName,productmaster.productHinName,productmaster.productEngName';
                    $cond = ['productcombolineentries.productComboCode' => $productCode];
                    $orderBy = ['productmaster.productEngName' => "ASC"];
                    $join["productmaster"] = "productcombolineentries.productCode=productmaster.code";
                    $joinType["productmaster"] = "inner";
                    $productLines = $this->GlobalModel->SELECTQuery($sel, "productcombolineentries", $cond, $orderBy, $join, $joinType);
                    if ($productLines) {
                        foreach ($productLines->result() as $line) {
                            array_push($lineCodes, $line->productCode);
                            $productNameArr[$line->productCode] = [
                                'productUrduName' => $line->productUrduName,
                                'productArbName' => $line->productArbName,
                                'productHinName' => $line->productHinName,
                                'productEngName' => $line->productEngName
                            ];
                        }
                    }
                    $prdCodes = implode(",", $lineCodes);
                    $data['productComoboNames'] = $productNameArr;
                    $data['prdCodes'] = $prdCodes;
                    $data['customizes'] = false;
                    $data['extraitems'] = false;
                    $result = $this->load->view("cashier/order/productmodal", $data, true);
                    return $this->response(array("status" => "200", "message" => "Data Found", "data" => $result), 200);
                } else {
                    return $this->response(array("status" => "300", "message" => "Cannot add/SELECT this product/item right now..", "data" => []), 200);
                }
            }
        }
        return $this->response(array("status" => "400", "message" => "Request parameters not found"), 200);
    }

    public function cartAddProduct_post()
    {
        $postData = $this->post();
        if (!empty($postData)) {
            $productImage = "assets/food.png";
            $branchCode = $postData["branchCode"];
            $tableSection = $postData["tableSection"];
            $tableNumber = $postData["tableNumber"];
            $custPhone = $postData["custPhone"];
            $custName = $postData["custName"];
            $productCode = $postData["productCode"];
            $variantCode = $postData["variantCode"];
            $productQty = $postData["productQty"];
            $productPrice = $postData["productPrice"];
            $customizeItems = $postData["customizeItems"];
            $addonItems = $postData["addonItems"];
            $tax = $postData["tax"];
            $taxAmount = $postData['taxAmount'];
            $totalPrice = $postData["totalPrice"];
            $productCombo = $postData["productCombo"];
            $cmbProducts = $postData["cmbProducts"];
            $cmbProductsNames = $postData["cmbProductsNames"];

            //json_decode
            $addonItems = json_decode($addonItems, true);
            $customizeItems = json_decode($customizeItems, true);
            $cmbProducts = json_decode($cmbProducts, true);
            $cmbProductsNames = json_decode($cmbProductsNames, true);

            $productEngName = $productHinName = $productUrduName =  $productAraName = "";

            //check product stock 
            $productCombo = $postData['productCombo'];
            if ($productCombo == "1") {
                $comboProductArray = $this->OrderApiModel->get_combo_products($productCode);
                if (!empty($comboProductArray)) {
                    $stock = $this->OrderApiModel->check_stock_exists_recipewise($branchCode, $comboProductArray, $productQty);
                    if ($stock == "OUTOFSTOCK") {
                        return $this->response(array("status" => "300", "message" => "Selected product is out of stock. Please SELECT another product"), 200);
                    }
                }
            } else {
                $stock = $this->OrderApiModel->check_stock_exists_recipewise($branchCode, $productCode, $productQty);
                if ($stock == "OUTOFSTOCK") {
                    return $this->response(array("status" => "300", "message" => "Selected product is out of stock. Please SELECT another product"), 200);
                }
            }

            if ($productCombo == "1") {
                $product = $this->GlobalModel->SELECTQuery("productcombo.productComboName,productcombo.productComboUrduName,productcombo.productComboHindiName,productcombo.productComboArabicName,productcombo.productComboImage", "productcombo", ["code" => $productCode]);
                if ($product) {
                    $product = $product->result_array()[0];
                    $productName = $product['productComboName'];
                    $productEngName = $product['productComboName'];
                    $productHinName = $product['productComboHindiName'];
                    $productUrduName = $product['productComboUrduName'];
                    $productAraName = $product['productComboArabicName'];
                    if ($product['productComboImage'] != null && $product['productComboImage'] != "")
                        $productImage =  $product['productComboImage'];
                }
            } else {
                $product = $this->GlobalModel->SELECTQuery("productmaster.productEngName,productmaster.productHinName,productmaster.productUrduName,productmaster.productArbName,productmaster.productImage", "productmaster", ["code" => $productCode]);
                if ($product) {
                    $product = $product->result_array()[0];
                    $productName = $product['productEngName'];
                    $productEngName = $product["productEngName"];
                    $productHinName = $product["productHinName"];
                    $productUrduName = $product["productUrduName"];
                    $productAraName = $product["productArbName"];
                    if ($product['productImage'] != null && $product['productImage'] != "")
                        $productImage =  $product['productImage'];
                }
            }

            $image = base_url($productImage);

            $customizeItemsArray = $addonItemsArray = [];

            $addonPrice = 0;
            //adons
            if (!empty($addonItems)) {
                foreach ($addonItems as $addonitem) {
                    $itemAmount =  $addonitem['itemAmount'];
                    $addonItemsArray[] = [
                        "itemCode"          => $addonitem['itemCode'],
                        "itemTitle"         => $addonitem['itemTitle'],
                        "itemName"          => $addonitem['itemName'],
                        "itemPrice"         => number_format($addonitem['itemPrice'], 2, '.', ''),
                        "itemUnitCode"      => $addonitem['itemUnitCode'],
                        "itemConsumeQty"    => $addonitem['itemConsumeQty'],
                        "itemQty"           => $addonitem['itemQty'],
                        "itemAmount"        => $addonitem['itemAmount']
                    ];
                    $addonPrice += number_format($itemAmount, 2, '.', '');
                }
            }
            //customize
            if (!empty($customizeItems)) {
                foreach ($customizeItems as $customizeitem) {
                    $itemAmount =  $customizeitem['itemAmount'];
                    $customizeItemsArray[] = [
                        "itemCode"          => $customizeitem['itemCode'],
                        "itemTitle"         => $customizeitem['itemTitle'],
                        "itemName"          => $customizeitem['itemName'],
                        "itemPrice"         => number_format($customizeitem['itemPrice'], 2, '.', ''),
                        "itemUnitCode"      => $customizeitem['itemUnitCode'],
                        "itemConsumeQty"    => $customizeitem['itemConsumeQty'],
                        "itemQty"           => $customizeitem['itemQty'],
                        "itemAmount"        => $customizeitem['itemAmount']
                    ];
                    $addonPrice += number_format($itemAmount, 2, '.', '');
                }
            }

            $prodNameArray = [
                "productEngName"    => $productEngName,
                "productHinName"    => $productHinName,
                "productUrduName"   => $productUrduName,
                "productAraName"    => $productAraName,
            ];

            $data = array(
                'productCode'           => $productCode,
                'variantCode'           => $variantCode,
                'productName'           => $productName,
                'productImage'          => $image,
                'addonItems'            => $addonItemsArray,
                'customizeItems'        => $customizeItemsArray,
                'productQty'            => $productQty,
                'productPrice'          => $productPrice,
                'tax'                   => $tax,
                'taxAmount'             => $taxAmount,
                'addonPrice'            => number_format($addonPrice, 2, '.', ''),
                'totalPrice'            => number_format($totalPrice, 2, '.', ''),
                'productCombo'          => $productCombo,
                'comboProductItems'     => $cmbProducts,
                'comboProductItemsName' => $cmbProductsNames,
                'prodNames' => stripslashes(json_encode($prodNameArray))
            );
            return $this->response(array("status" => "200", "message" => "Data found", "data" => $data), 200);
        }
        return $this->response(array("status" => "400", "message" => "Request parameters not found", "postData" => $postData), 200);
    }

    public function cartPlaceOrder_post()
    {
        $postData = $this->post();
        if (!empty($postData)) {

            $branchCode = $postData['branchCode'];
            $tableSection = $postData['tableSection'];
            $tableNumber = $postData['tableNumber'];
            $custPhone = $postData['custPhone'];
            $isDraft = (array_key_exists('isDraft', $postData) && $postData['isDraft'] != "") ? $postData['isDraft'] : 0;
            $draftId = (array_key_exists('draftId', $postData) && $postData['draftId'] != "") ? $postData['draftId'] : "";
            $custName = $postData['custName'] != "" ? $postData['custName'] : "New Customer " . rAND(1, 99);

            $todayDate = date('Y-m-d');
            $todayDateTime = date('Y-m-d H:i:s');
            if ($isDraft == 1) {
                $products = $postData['products'][0];
            } else {
                $products = $postData['products'];
            }

            $maxKotNumber = $this->OrderApiModel->get_max_kot_number_for_cart($branchCode, $tableSection, $tableNumber, $custPhone);
            $maxKotNumber++;

            $waitingSeconds =  $this->OrderApiModel->get_waiting_seconds();

            $minutes = $waitingSeconds / 60;
            $waitingMinutes = $minutes > 1 ? $minutes : 3;

            $storeCartData = [
                "kotNumber" => $maxKotNumber,
                "kotDate" => $todayDate,
                "branchCode" => $branchCode,
                "tableSection" => $tableSection,
                "tableNumber" => $tableNumber,
                "custPhone" => $custPhone,
                "custName" => $custName,
                "kotStatus" => "PND",
                "waitingMinutes" => $waitingMinutes,
                "cartBy" => "",
                "cartDate" => date('Y-m-d H:i:s')
            ];

            $preparationTimeArray = [];
            $storeCart = $this->OrderApiModel->store_cart($storeCartData);
            if ($storeCart) {
                $cartId = $storeCart;
                $cartProducts = [];
                foreach ($products as $product) {
                    $addonItems = [];
                    if (array_key_exists("addonItems", $product) && !empty($product['addOns'])) {
                        $allItems = $product['addOns'];
                        foreach ($allItems as $item) {
                            $addUpItem = $item;
                            $addUpItem['itemName'] = base64_decode($item['itemName']);
                            array_push($addonItems, $addUpItem);
                        }
                    }

                    $customizeItems = [];
                    if (array_key_exists("customizeItems", $product) && !empty($product['customizeItems'])) {
                        $allItems = $product['customizeItems'];
                        foreach ($allItems as $item) {
                            $addUpItem = $item;
                            $addUpItem['itemName'] = base64_decode($item['itemName']);
                            array_push($customizeItems, $addUpItem);
                        }
                    }

                    $comboProductItems = [];
                    if (array_key_exists("comboProductItems", $product) && !empty($product['comboProductItems'])) {
                        $comboProductItems = $product['comboProductItems'];
                    }

                    $prepationTime = $this->OrderApiModel->get_product_preparation_time($product['productCode']);

                    array_push($preparationTimeArray, $prepationTime);

                    $cartProduct = [
                        'cartId' => $cartId,
                        'productCode' => $product['productCode'],
                        'productQty' => $product['productQty'],
                        'addOns' => stripslashes(json_encode($addonItems)),
                        'customizes' => stripslashes(json_encode($customizeItems)),
                        'tax' => $product['tax'],
                        'taxAmount' => $product['taxAmount'],
                        'variantCode' => $product['variantCode'],
                        'productPrice' => $product['productPrice'],
                        'totalPrice' => $product['totalPrice'],
                        'isCombo' => $product['productCombo'],
                        'comboProducts' => stripslashes(json_encode($comboProductItems)),
                        'comboProductItemsName' => $product['comboProductItemsName'],
                        'prodNames' => $product['prodNames']
                    ];
                    array_push($cartProducts, $cartProduct);
                }
                $storeProducts = $this->OrderApiModel->store_cart_products($cartProducts);
                if (is_integer($storeProducts) && $storeProducts > 0) {
                    $maxPreparationTime = max($preparationTimeArray);
                    $this->OrderApiModel->update_max_preparation_time($cartId, $maxPreparationTime);
                    if ($isDraft == 1 && $draftId != '') {
                        $this->OrderApiModel->stringQuery("Delete FROM draftcart WHERE draftcart.id='" . $draftId . "'", false);
                        $this->OrderApiModel->stringQuery("Delete FROM draftcartproducts WHERE draftcartproducts.draftId='" . $draftId . "'", false);
                    }
                    return $this->response(array("status" => "200", "message" => "Order placed AND sent to kitchen successfully.."), 200);
                } else {
                    $this->OrderApiModel->trash_cart_master_when_empty_products($cartId);
                    return $this->response(array("status" => "400", "message" => "Products not found. Failed to place the order..."), 200);
                }
            }
        }
        return $this->response(array("status" => "400", "message" => "Request parameters not found"), 200);
    }

    public function fetchOrders_get()
    {
        $getData = $this->get();
        $branchCode = $getData['branchCode'];
        if ($branchCode != "") {
            $distinctTableOrders = $this->OrderApiModel->get_distinct_table_orders();
            if (!empty($distinctTableOrders)) {
                $orders = [];
                foreach ($distinctTableOrders as $order) {
                    if ($branchCode == $order['branchCode']) {
                        $kots = $this->OrderApiModel->get_kots_for_order($order['branchCode'], $order['tableSection'], $order['tableNumber'], $order['custPhone']);
                        $order['kots'] = $kots;
                        array_push($orders, $order);
                    }
                }
                return $this->response(array("status" => "200", "message" => "Data found", "data" => $orders), 200);
            } else {
                return $this->response(array("status" => "300", "message" => "No Data found", "data" => []), 200);
            }
        } else {
            return $this->response(array("status" => "300", "message" => "No Data found", "data" => []), 200);
        }
    }

    public function draftOrder_post()
    {
        $postData = $this->post();
        if (!empty($postData)) {
            $branchCode = $postData['branchCode'];
            $tableSection = $postData['tableSection'];
            $tableNumber = $postData['tableNumber'];
            $custPhone = $postData['custPhone'];
            $custName = $postData['custName'];
            $products = $postData['products'];
            $draftData = [
                "branchCode" => $branchCode,
                "tableSection" => $tableSection,
                "tableNumber" => $tableNumber,
                "custPhone" => $custPhone,
                "custName" => $custName,
                "cartBy" => "",
                "cartDate" => date('Y-m-d H:i:s')
            ];
            $draftId = $this->OrderApiModel->store_draft_cart($draftData);
            if ($draftId) {
                $cartProducts = [];
                foreach ($products as $product) {
                    $addonItems = [];
                    if (array_key_exists("addonItems", $product) && !empty($product['addOns'])) {
                        $allItems = $product['addOns'];
                        echo 'addonItems';
                        foreach ($allItems as $item) {
                            $addUpItem = $item;
                            $addUpItem['itemName'] = base64_decode($item['itemName']);
                            array_push($addonItems, $addUpItem);
                        }
                    }

                    $customizeItems = [];
                    if (array_key_exists("customizeItems", $product) && !empty($product['customizeItems'])) {
                        $allItems = $product['customizeItems'];
                        echo 'customizeItems';
                        foreach ($allItems as $item) {
                            $addUpItem = $item;
                            $addUpItem['itemName'] = base64_decode($item['itemName']);
                            array_push($customizeItems, $addUpItem);
                        }
                    }

                    $comboProductItems = [];
                    if (array_key_exists("comboProductItems", $product) && !empty($product['comboProductItems'])) {
                        $comboProductItems = $product['comboProductItems'];
                    }

                    $cartProduct = [
                        'draftId' => $draftId,
                        'productCode' => $product['productCode'],
                        'productQty' => $product['productQty'],
                        'addOns' => stripslashes(json_encode($addonItems)),
                        'customizes' => stripslashes(json_encode($customizeItems)),
                        'tax' => $product['tax'],
                        'taxAmount' => $product['taxAmount'],
                        'variantCode' => $product['variantCode'],
                        'productPrice' => $product['productPrice'],
                        'totalPrice' => $product['totalPrice'],
                        'isCombo' => $product['productCombo'],
                        'comboProducts' => stripcslashes(json_encode($comboProductItems)),
                        'prodNames' => $product['prodNames']
                    ];
                    array_push($cartProducts, $cartProduct);
                }
                $this->OrderApiModel->store_draft_cart_products($cartProducts);
                return $this->response(array("status" => "200", "message" => "Order added to draft successfully"), 200);
            }
            return $this->response(array("status" => "300", "message" => "Failed to add current order to draft. Please try again"), 200);
        }
        return $this->response(array("status" => "400", "message" => "Failed to add order to draft"), 200);
    }

    public function fetchDraftOrders_get()
    {
        $distinctTableOrders = $this->OrderApiModel->get_distinct_draft_orders();
        if (!empty($distinctTableOrders)) {
            $orderRows = "";
            $draftIndex = 0;
            foreach ($distinctTableOrders as $order) {
                $btnCheckout = "<a href='javascript:void(0)' data-draft-id='" . $order['id'] . "' data-customer-phone='" . $order['custPhone'] . "'  data-table-number='" . $order['tableNumber'] . "' data-branch-code='" . $order['branchCode'] . "' data-table-section='" . $order['tableSection'] . "' class='badge bg-light text-success mx-1 draft-checkout' title='Checkout Draft Order ?'> <i class='fa fa-check'></i> </a>";
                $btnDiscard = "<a href='javascript:void(0)' data-draft-id='" . $order['id'] . "' class='badge bg-light text-danger mx-1 trash-draft-order' title='Trash Draft Order ?'> <i class='fa fa-trash'></i> </a>";
                $btns = $btnDiscard . $btnCheckout;
                $orderRow = '<tr>';
                $orderRow .= '<td>' . date('d-m-y h:i A', strtotime($order['cartDate'])) . '</td>';
                $orderRow .= '<td>' . $order['zoneName'] . ' | ' . $order['tblNo'] . '</td>';
                $orderRow .= '<td>' . $order['custPhone'] . '</td>';
                $orderRow .= '<td>' . $btns . '</td>';
                $orderRow .= '</tr>';
                $orderRows .= $orderRow;
                $draftIndex++;
            }
            return $this->response(array("status" => "200", "message" => "Data found", "data" => $orderRows), 200);
        }
        return $this->response(array("status" => "300", "message" => "No Data found", "data" => []), 200);
    }

    function fetchDraftOrderById_get()
    {
        $getData = $this->get();
        if (!empty($getData)) {
            $draftId = $getData['draftId'];
            $draftProductsArr = [];
            $draftOrder = $this->OrderApiModel->get_draft_cart_by_id($draftId);
            if (!empty($draftOrder)) {
                $draftProducts = $this->OrderApiModel->get_draft_products_by_draft_id($draftId);
                foreach ($draftProducts as $draft) {
                    $productName = "";
                    $productImage = "assets/food.png";
                    if ($draft['isCombo'] == "1") {
                        $product = $this->GlobalModel->SELECTQuery("productcombo.productComboName,productcombo.productComboImage", "productcombo", ["code" => $draft['productCode']]);
                        if ($product) {
                            $product = $product->result_array()[0];
                            $productName = $product['productComboName'];
                            if ($product['productComboImage'] != null && $product['productComboImage'] != "") $productImage =  $product['productComboImage'];
                        }
                    } else {
                        $product = $this->GlobalModel->SELECTQuery("productmaster.productEngName,productmaster.productImage", "productmaster", ["code" => $draft['productCode']]);
                        if ($product) {
                            $product = $product->result_array()[0];
                            $productName = $product['productEngName'];
                            if ($product['productImage'] != null && $product['productImage'] != "") $productImage =  $product['productImage'];
                        }
                    }
                    $image = base_url($productImage);
                    $addonItems = json_decode($draft['addOns'], true);
                    $customizeItems = json_decode($draft['customizes'], true);
                    $cmbProducts = json_decode($draft['comboProducts'], true);
                    $productCombo = $draft['isCombo'];
                    $customizeItemsArray = $addonItemsArray = [];
                    $addonPrice = 0;
                    //adons
                    if (!empty($addonItems)) {
                        foreach ($addonItems as $addonitem) {
                            $itemAmount =  $addonitem['itemAmount'];
                            $addonItemsArray[] = [
                                "itemCode"          => $addonitem['itemCode'],
                                "itemTitle"         => $addonitem['itemTitle'],
                                "itemName"          => $addonitem['itemName'],
                                "itemPrice"         => number_format($addonitem['itemPrice'], 2, '.', ''),
                                "itemUnitCode"      => $addonitem['itemUnitCode'],
                                "itemConsumeQty"    => $addonitem['itemConsumeQty'],
                                "itemQty"           => $addonitem['itemQty'],
                                "itemAmount"        => $addonitem['itemAmount']
                            ];
                            $addonPrice += number_format($itemAmount, 2, '.', '');
                        }
                    }
                    //customize
                    if (!empty($customizeItems)) {
                        foreach ($customizeItems as $customizeitem) {
                            $itemAmount =  $customizeitem['itemAmount'];
                            $customizeItemsArray[] = [
                                "itemCode"          => $customizeitem['itemCode'],
                                "itemTitle"         => $customizeitem['itemTitle'],
                                "itemName"          => $customizeitem['itemName'],
                                "itemPrice"         => number_format($customizeitem['itemPrice'], 2, '.', ''),
                                "itemUnitCode"      => $customizeitem['itemUnitCode'],
                                "itemConsumeQty"    => $customizeitem['itemConsumeQty'],
                                "itemQty"           => $customizeitem['itemQty'],
                                "itemAmount"        => $customizeitem['itemAmount']
                            ];
                            $addonPrice += number_format($itemAmount, 2, '.', '');
                        }
                    }

                    $data[] = array(
                        'productCode'           => $draft['productCode'],
                        'variantCode'           => $draft['variantCode'],
                        'productName'           => $productName,
                        'productImage'          => $image,
                        'addonItems'            => $addonItemsArray,
                        'customizeItems'        => $customizeItemsArray,
                        'productQty'            => $draft['productQty'],
                        'productPrice'          => $draft['productPrice'],
                        'tax'                   => $draft['tax'],
                        'taxAmount'             => $draft['taxAmount'],
                        'addonPrice'            => number_format($addonPrice, 2, '.', ''),
                        'totalPrice'            => number_format($draft['totalPrice'], 2, '.', ''),
                        'productCombo'          => $productCombo,
                        'comboProductItems'     => $cmbProducts,
                        'comboProductItemsName' => $draft['cmbProductsNames'],
                        'prodNames'             => $draft['prodNames']
                    );
                    $draftProductsArr = $data;
                }
                return $this->response(array("status" => "200", "message" => "Data found", "draftData" => $draftOrder, "data" => $draftProductsArr), 200);
            }
            return $this->response(array("status" => "300", "message" => "No draft found...", "data" => []), 200);
        }
        return $this->response(array("status" => "400", "message" => "Incorrect parameters", "data" => []), 200);
    }

    function trashDraftOrder_post()
    {
        $postData = $this->post();
        if (!empty($postData)) {
            $draftId = $postData['draftId'];
            $this->OrderApiModel->stringQuery("DELETE FROM draftcartproducts WHERE draftId='$draftId'");
            $this->OrderApiModel->stringQuery("DELETE FROM draftcart WHERE id='$draftId'");
            return $this->response(array("status" => "200", "message" => "Draft order removed successfully"), 200);
        }
        return $this->response(array("status" => "400", "message" => "Incorrect parameters", "data" => []), 200);
    }

    function checkReservedTable_post()
    {
        $postData = $this->post();
        if (!empty($postData)) {
            $phone = $postData['phone'];
            $tNo = $postData['tNo'];
            $queryString =  "SELECT tablereservation.customerMobile FROM tablereservation WHERE tablereservation.tableNumber='" . $tNo . "' AND '" . date('Y-m-d H:i') . "' BETWEEN TIMESTAMP(date(tablereservation.resDate),tablereservation.startTime) AND TIMESTAMP(date(tablereservation.resDate),tablereservation.endTime)";
            $checkQuery = $this->OrderApiModel->stringQuery($queryString, true);
            if ($checkQuery && $checkQuery->num_rows() > 0) {
                $result = $checkQuery->result()[0];
                if ($phone == $result->customerMobile) {
                    return $this->response(array("status" => "200", "message" => "Order allowed"), 200);
                } else {
                    return $this->response(array("status" => "300", "message" => "Order not allowed"), 200);
                }
            } else {
                return $this->response(array("status" => "200", "message" => "Order allowed"), 200);
                /*$checkCartQuery = $this->db->query("SELECT cart.id FROM cart WHERE cart.tableNumber='".$tNo."' AND cart.custPhone='".$phone."'");
				if($checkCartQuery && $checkCartQuery->num_rows()>0){
					return $this->response(array("status" => "300", "message" => "Order not allowed"), 200);
				}*/
            }
        }
        return $this->response(array("status" => "400", "message" => "Incorrect parameters", "data" => []), 200);
    }

    public function checkProductInStock_post()
    {
        $postData = $this->post();
        if (!empty($postData)) {
            $productCombo = $postData['productCombo'];
            if ($productCombo == "1") {
                $comboProductArray = $this->OrderApiModel->get_combo_products($postData['productCode']);
                if (!empty($comboProductArray)) {
                    $stock = $this->OrderApiModel->check_stock_exists_recipewise($postData['branchCode'], $comboProductArray, $postData['productQty']);
                    if ($stock == "OUTOFSTOCK") {
                        return $this->response(array("status" => "300", "message" => "Selected product is out of stock. Please SELECT another product"), 200);
                    }
                }
            } else {
                $stock = $this->OrderApiModel->check_stock_exists_recipewise($postData['branchCode'], $postData['productCode'], $postData['productQty']);
                if ($stock == "OUTOFSTOCK") {
                    return $this->response(array("status" => "300", "message" => "Selected product is out of stock. Please SELECT another product"), 200);
                }
            }
            return $this->response(array("status" => "200", "message" => "Data found"), 200);
        }
        return $this->response(array("status" => "400", "message" => "Request parameters not found"), 200);
    }

    public function freeTableReservaion_post()
    {
        $postData = $this->post();
        if ($postData['reservCode'] != "") {
            $revCode = $postData['reservCode'];
            $data = ['isActive' => '0', 'isDelete' => '1', 'deleteDate' => date('Y-m-d H:i:s'), 'deleteIP' => $_SERVER['REMOTE_ADDR']];
            $result = $this->GlobalModel->doEdit($data, 'tablereservation', $revCode);
            return $this->response(array("status" => "200", "message" => "Success"), 200);
        }
        return $this->response(array("status" => "400", "message" => "Request parameters not found"), 200);
    }
}
