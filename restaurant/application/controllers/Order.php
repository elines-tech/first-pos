<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Order extends CI_Controller
{
    var $session_key;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('form', 'url', 'html');
        $this->load->model('GlobalModel');
        $this->load->model('OrderApiModel');
        $this->session_key = $this->session->userdata('key' . SESS_KEY);
        if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
            redirect('Login', 'refresh');
        }
		$res = $this->GlobalModel->checkActiveSubscription();
        if ($res == "EXPIRED") {
            redirect('package', 'refresh');
        }
    }

    public function listRecords()
    {
        $data['tableData'] = $this->GlobalModel->selectActiveData('tablemaster');
        $header['pageTitle'] = "All Orders";
        $this->load->view('dashboard/order/header', $header);
        $this->load->view('dashboard/order/list', $data);
        $this->load->view('dashboard/order/footer');
    }

    public function add()
    {

        $data['lang'] = strtolower($this->session->userdata['logged_in' . $this->session_key]['lang']) ?? "english";
        $data['tableNumber'] = $this->input->post('tableNumber') ?? "";
        $data['tableSection'] = $this->input->post('tableSection') ?? "";
        $data['branchCode'] = $this->input->post('branchCode') ?? "";
        $data['custPhone'] = $this->input->post('custPhone') ?? "";
        $data['custName'] = $this->input->post('custName') ?? "";
        $categories = $this->GlobalModel->selectActiveData('productcategorymaster');
        $data['sectors'] = $this->GlobalModel->selectActiveData('sectorzonemaster');
        $data['tables'] = $this->GlobalModel->selectActiveData('tablemaster');
        $data['categories'] = $categories;
        $categoryProducts = [];
        if ($categories) {
            foreach ($categories->result_array() as $category) {
                $joinType['recipecard'] = "inner";
                $join['recipecard'] = "productmaster.code=recipecard.productCode";
                $products = $this->GlobalModel->selectQuery("productmaster.*", "productmaster", ["productmaster.productCategory" => $category['code'], "productmaster.isActive" => 1], array("productEngName" => "ASC"), $join, $joinType);
                $joinType1['productsubcategorymaster'] = "inner";
                $joinType1['productcategorymaster'] = "inner";               
                $join1["productsubcategorymaster"] = "productcombo.productCategoryCode = productsubcategorymaster.code";
                $join1["productcategorymaster"] = "productsubcategorymaster.categoryCode = productcategorymaster.code";
                $comboproducts = $this->GlobalModel->selectQuery("productcombo.*", "productcombo", ["productsubcategorymaster.categoryCode" => $category['code'], "productcombo.isActive" => 1], [], $join1, $joinType1);               
                $catg = [
                    "categoryCode" => $category['code'],
                    "categoryName" => $category['categoryName'],
                    "products" => $products ? $products->result_array() : [],
                    "comboProducts" => $comboproducts ? $comboproducts->result_array() : []
                ];
                array_push($categoryProducts, $catg);
            }
        }
        $data['categoryProducts'] = $categoryProducts;
        $header['pageTitle'] = "New Order";
        $this->load->view('dashboard/order/header', $header);
        $this->load->view('dashboard/order/add', $data);
        $this->load->view('dashboard/order/footer');
    }

    public function getKotDetails($cartId='')
    {
		if($cartId==''){
			$cartId = $this->input->post('cartId');
		}
		$kotTotal=0;
        $joinType = array('productmaster' => 'left', 'customizedcategorylineentries' => 'left');
        $join = array('productmaster' => 'productmaster.code=cartproducts.productCode', 'customizedcategorylineentries' => 'customizedcategorylineentries.code=cartproducts.variantCode');
        $cartDetails = $this->GlobalModel->selectQuery('customizedcategorylineentries.subCategoryTitle as variantName,cartproducts.id,cartproducts.isCombo,cartproducts.comboProducts,cartproducts.productCode,cartproducts.productQty,cartproducts.totalPrice,cartproducts.addOns,productmaster.productEngName,productmaster.productImage,cartproducts.productPrice,productmaster.productPrice as orgPrice', 'cartproducts', array('cartproducts.cartId' => $cartId), array(), $join, $joinType);
        if ($cartDetails) {
			foreach ($cartDetails->result() as $cart) {
				$kotTotal = $kotTotal+$cart->totalPrice;
			}
			$data['cartId']=$cartId;
			$data['cartDetails']=$cartDetails;
			$prdHtml = $this->load->view('dashboard/order/cart', $data, true);
        } else {
            $prdHtml = 'No products available';
        }
        echo $prdHtml.'$$'.$kotTotal;
    }

    public function getOrderDetails()
    {
        $orderHtml = '';
        $kotArr = [];
        $totalpreparationTime = 0;
        $arr = $cartIdArr = [];
        $branchCode = $this->input->post('branchCode');
        $tableSection = $this->input->post('tableSection');
        $tableNumber = $this->input->post('tableNumber');
        $custPhone = $this->input->post('custPhone');
        $isDraft = $this->input->post('isDraft');
        $data['serviceCharges'] = $this->GlobalModel->selectActiveDataByField('code', 'SET_1', 'settings')->result()[0]->settingValue;
        $data['discounts'] = $this->GlobalModel->selectActiveData('discountmaster');
        $data['coupons'] = $this->GlobalModel->selectActiveData('couponoffer');
		if($isDraft==1){
			$masterTbl='draftcart';
			$pertTbl='draftcartproducts';
			$primaryId='draftId';
		}else{
			$masterTbl='cart';
			$pertTbl='cartproducts';
			$primaryId='cartId';
		}
        $joinType = array('tablemaster' => 'inner');
        $join = array('tablemaster' => "tablemaster.code=$masterTbl.tableNumber");
        $orderDetails = $this->GlobalModel->selectQuery("$masterTbl.*,tablemaster.tableNumber", $masterTbl, array("$masterTbl.branchCode" => $branchCode, "$masterTbl.tableSection" => $tableSection, "$masterTbl.tableNumber" => $tableNumber, "$masterTbl.custPhone" => $custPhone), array("$masterTbl.id" => 'ASC'), $join, $joinType);
        if ($orderDetails) {
            foreach ($orderDetails->result() as $ord) {
                $totalpreparationTime = $totalpreparationTime + $ord->preparingMinutes;
                array_push($kotArr, $ord->kotNumber);
                array_push($cartIdArr, $ord->id);
                $data['kot' . $ord->kotNumber] = $this->db->query("select $pertTbl.*,productmaster.productEngName,productmaster.productImage from $pertTbl inner join productmaster on productmaster.code=$pertTbl.productCode where $primaryId='" . $ord->id . "'");
            }
            $data['totalpreparationTime'] = $totalpreparationTime;
            $data['cartIdArr'] = $cartIdArr;
            $data['kotNumber'] = $kotArr;
            $data['orderData'] = $orderDetails->result()[0];
            $data['isDraft'] = $isDraft;
            $cartHtml = $this->load->view('dashboard/order/orderdetails', $data, true);
            $response['status'] = true;
            $response['orderHtml'] = $cartHtml;
        } else {
            $response['status'] = false;
            $response['orderHtml'] = '';
        }
        echo json_encode($response);
    }
    public function getTaxAmount()
    {
        $taxAmount = 0;
        $productCodes = $this->input->post("productCodes");
        $productCodes = implode("','", $productCodes);
        $discount = $this->input->post("discount");
        $subTotal = $this->input->post("subTotal");
        $offerType = $this->input->post("offerType");
        $couponDiscount = $this->input->post("couponDiscount");
        $discountAmount = round(($subTotal * $discount / 100), 2);
		$couponDiscountAmount=0;
        if ($discountAmount != 0) {
            $discountAmount = $subTotal - $discountAmount;
			$couponDiscountAmount=$discountAmount;
			if($offerType=='flat'){
				$couponDiscountAmount = $discountAmount-$couponDiscount;
			}elseif($offerType=='cap'){
				$couponDiscountAmount = round(($discountAmount * $couponDiscount / 100), 2);
			}
        }
        $joinType = array('taxgroupmaster' => 'inner');
        $join = array('taxgroupmaster' => 'taxgroupmaster.code=productmaster.productTaxGrp');
        $extraCondition = " productmaster.code in ('" . $productCodes . "')";
        $prdDetails = $this->GlobalModel->selectQuery('productmaster.productTaxGrp,taxgroupmaster.taxes', 'productmaster', array('productmaster.isActive' => 1, 'taxgroupmaster.isActive' => 1), array('productmaster.id' => 'ASC'), $join, $joinType, array(), "", "", array(), $extraCondition);
        if ($prdDetails) {
            foreach ($prdDetails->result() as $pt) {
                $taxes = json_decode($pt->taxes, true);
                $getTaxes = $this->db->query("select taxes.taxPer from taxes where taxes.isActive=1 and taxes.code in('" . implode("','", $taxes) . "')");
                if ($getTaxes) {
                    foreach ($getTaxes->result() as $t) {
                        $taxAmount = $taxAmount + round(($couponDiscountAmount * $t->taxPer / 100), 2);
                    }
                }
            }
        }
        $response['couponDiscountAmount'] = number_format($couponDiscountAmount, 2, '.', '');
        $response['discountAmount'] = number_format($discountAmount, 2, '.', '');
        $response['taxAmount'] = number_format($taxAmount, 2, '.', '');
        echo json_encode($response);
    }

    public function confirmOrder()
    {
		$ip = $_SERVER['REMOTE_ADDR'];
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $cartId = $this->input->post('cartId');
        $subTotal = $this->input->post('subTotal');
        $tax = $this->input->post('tax');
        $discountPer = $this->input->post('discountPer');
        $discountAmount = $this->input->post('discountAmount');
        $couponCode = $this->input->post('couponCode');
        $couponDiscount = $this->input->post('couponDiscount');
        $serviceCharges = $this->input->post('serviceCharges');
        $grandTotal = $this->input->post('grandTotal');
        $totalpreparationTime = $this->input->post('totalpreparationTime');
        $isDraft = $this->input->post('isDraft');
        $cartIds = implode("','", $cartId);
		if($isDraft==1){
			$extraCondition = " cartproducts.cartId in ('" . $cartIds . "')";
			$cartproductDetails = $this->GlobalModel->selectQuery('cartproducts.*', 'cartproducts', [], [], [], [], [], "", "", [], $extraCondition);
		}else{
			$extraCondition = " draftcartproducts.draftId in ('" . $cartIds . "')";
			$cartproductDetails = $this->GlobalModel->selectQuery('draftcartproducts.*', 'cartproducts', [], [], [], [], [], "", "", [], $extraCondition);
		}
        if ($cartproductDetails) {
			$data=array(
				'name'=>$ct->custName,
				'isActive'=>1
			);
			$checkCustomer = $this->GlobalModel->selectQuery('customer.code','customer',array('customer.isActive'=>1,'customer.phone'=>$ct->custPhone));
			if($checkCustomer && $checkCustomer->num_rows()>0){
				$code = $checkCustomer->result()[0]->code;
				$data['editID'] = $addID;
				$data['editIP'] = $ip;
				$this->GlobalModel->doEdit($data,'customer',$code);
				$customerCode = $code;
			}else{
				$data['phone'] = $ct->custPhone;
				$data['addID'] = $addID;
				$data['addIP'] = $ip;
				$customerCode = $this->GlobalModel->addNew($data,'customer','CUST');
			}
            $extraCondition = " cart.id in ('" . $cartIds . "')";
            $cartDetails = $this->GlobalModel->selectQuery('distinct cart.branchCode,cart.tableNumber,cart.tableSection,cart.custPhone,cart.custName', 'cart', [], [], [], [], [], "", "", [], $extraCondition);
            if ($cartDetails) {
                $ct = $cartDetails->result()[0];
                $insertArr = array(
                    'branchCode' => $ct->branchCode,
                    'tableSection' => $ct->tableSection,
                    'tableNumber' => $ct->tableNumber,
                    'custCode' => $customerCode,
                    'custPhone' => $ct->custPhone,
                    'custName' => $ct->custName,
                    'orderStatus' => 'CON',
                    'paymentMode' => 'CASH',
                    'subTotal' => $subTotal,
                    'discountPer' => $discountPer,
                    'discountAmount' => $discountAmount,
                    'couponCode' => $couponCode,
                    'discount' => $couponDiscount,
                    'totalpreparationTime' => $totalpreparationTime,
                    'tax' => $tax,
                    'serviceCharges' => $serviceCharges,
                    'grandTotal' => $grandTotal,
                    'isActive' => 1,
                );
                $orderCode = 'ORDER' . rand(99, 99999);
                $insertResult = $this->GlobalModel->addWithoutYear($insertArr, 'ordermaster', $orderCode);
                if ($insertResult != 'false') {
                    foreach ($cartproductDetails->result() as $cpt) {
                        $orderLineData = array(
                            'orderCode' => $insertResult,
                            'productCode' => $cpt->productCode,
                            'productQty' => $cpt->productQty,
                            'productPrice' => $cpt->productPrice,
                            'addons' => $cpt->addOns,
                            'variantCode' => $cpt->variantCode,
                            'totalPrice' => $cpt->totalPrice,
                            'isActive' => 1,
                        );
                        $orderLineResult = $this->GlobalModel->addWithoutYear($orderLineData, 'orderlineentries', 'ORDL');
                    }
                    $this->db->query("Delete from cart where cart.id in ('" . $cartIds . "')");
                    $this->db->query("Delete from cartproducts where cartproducts.cartId in ('" . $cartIds . "')");
                    $response['status'] = true;
                    $response['message'] = 'Order Confirmed successfully';
                } else {
                    $response['status'] = false;
                    $response['message'] = 'Failed to confirm order';
                }
            }
        } else {
            $response['status'] = false;
            $response['message'] = 'Cart is empty...Please add products and try again..';
        }
        echo json_encode($response);
    }
	
	public function removeKot(){
		$cartId = $this->input->post('cartId');
		$kotNumber = $this->input->post('kotNumber');
		$query = $this->GlobalModel->deleteForeverFromField('id',$cartId,'cart');
		if($query=='true'){
			$this->GlobalModel->deleteForeverFromField('cartId',$cartId,'cartproducts');
			$response['status'] = true;
		}else{
			$response['status'] = false;
            $response['message'] = 'Failed to remove KOT...Please try again';
		}
		echo json_encode($response);
	}
	
	public function deleteCartProduct(){
		$cartId = $this->input->post('cartId');
		$query = $this->GlobalModel->deleteForeverFromField('id',$cartId,'cartproducts');
		if($query=='true'){
			$response['status'] = true;
		}else{
			$response['status'] = false;
            $response['message'] = 'Failed to remove product...Please try again';
		}
		echo json_encode($response);
	}

	public function removeAddon(){
		$newAddon=[];
		$productextraCode = $this->input->post('productextraCode');
		$cartPrdId = $this->input->post('cartPrdId');
		$checkProduct = $this->GlobalModel->selectQuery('cartproducts.addOns,cartproducts.totalPrice,cartproducts.cartId','cartproducts',array('cartproducts.id'=>$cartPrdId));
		$addOns = $checkProduct->result()[0]->addOns;
		$cartId = $checkProduct->result()[0]->cartId;
		$totalPrice = $checkProduct->result()[0]->totalPrice;
		$cartPrdId = $this->input->post('cartPrdId');
		$addonString = json_decode($addOns,true);
		foreach($addonString as $add){
			if($add['addonCode']!=$productextraCode){
				array_push($newAddon,$add);
			}else{
				$totalPrice = $totalPrice - $add['addonPrice'];
			}
		}
		$this->db->where('id', $cartPrdId);
		$this->db->update('cartproducts', array('addOns'=>json_encode($newAddon),'totalPrice'=>$totalPrice));
		if ($this->db->affected_rows() > 0) {
			$response['status'] = true;
			$response['totalPrice']=number_format($totalPrice, 2, '.', '');
		}else{
			$response['status'] = false;
            $response['message'] = 'Failed to remove addon...Please try again';
		}
		echo json_encode($response);
	}
	
	public function updateCartPrices(){
		$type=$this->input->post('type');
		$cartPrdId=$this->input->post('cartPrdId');
		$cartId=$this->input->post('cartId');
		$isCombo=$this->input->post('isCombo');
		$originalComboPrice=$this->input->post('originalComboPrice');
		$currentqty=$this->input->post('currentqty');
		$totalPrice=0;
		$comboProducts=$newCombo=[];
		if($isCombo==1){
			$originalPrice=$originalComboPrice;
			$cartPrice=$totalPrice=$currentqty*$originalComboPrice;
			$productQty=$currentqty;
			$checkProduct = $this->GlobalModel->selectQuery('cartproducts.comboProducts','cartproducts',array('cartproducts.id'=>$cartPrdId));
			if($checkProduct){
				foreach($checkProduct->result() as $chk){
					$comboString=$chk->comboProducts;
					$comboString = json_decode($comboString,true);
					foreach($comboString as $cmb){
						if($type==1){
							$cmb['productQty']=$currentqty+1;
						}else{
							$cmb['productQty']=$currentqty-1;
						}
						
						array_push($newCombo,$cmb);
					}
					$comboProducts =$newCombo;
				}
			}
			
		}else{
			$comboProducts=[];
			$checkProduct = $this->GlobalModel->selectQuery('cartproducts.cartId,cartproducts.productPrice,cartproducts.productQty,cartproducts.totalPrice,productmaster.productPrice as originalPrice','cartproducts',array('cartproducts.id'=>$cartPrdId),array(),array('productmaster'=>'productmaster.code=cartproducts.productCode'),array('productmaster'=>'inner'));
			if($checkProduct){
				foreach($checkProduct->result() as $chk){
					$cartId=$chk->cartId;
					$originalPrice=$chk->originalPrice;
					$cartPrice = $chk->productPrice;
					$totalPrice = $chk->totalPrice;
					$productQty = $chk->productQty;
				}
			}
		}
		if($type==1){
			$cartPrice = $cartPrice+$originalPrice;
			$totalPrice = $totalPrice+$originalPrice;
			$productQty = $productQty+1;
		}else{
			$cartPrice = $cartPrice-$originalPrice;
			$totalPrice = $totalPrice-$originalPrice;
			$productQty = $productQty-1;
		}
		$updateData = array(
			'productPrice'=>$cartPrice,
			'totalPrice'=>$totalPrice,
			'productQty'=>$productQty,
			'comboProducts'=>json_encode($comboProducts),
		);
		$this->db->where('id', $cartPrdId);
		$this->db->update('cartproducts', $updateData);
		$result = $this->getKotDetails($cartId);
		echo $result;
	}

	public function checkout(){
		$prdArr=[];
		$branchCode = $this->input->post('branchCode');
        $tableSection = $this->input->post('tableSection');
        $tableNumber = $this->input->post('tableNumber');
        $custPhone = $this->input->post('custPhone');
		$joinType = array('tablemaster' => 'inner','cartproducts'=>'inner','productmaster'=>'left','sectorzonemaster'=>'inner');
        $join = array('tablemaster' => "tablemaster.code=cart.tableNumber",'cartproducts'=>'cartproducts.cartId=cart.id','productmaster'=>'productmaster.code=cartproducts.productCode','sectorzonemaster'=>'sectorzonemaster.id=tablemaster.zoneCode');
        $orderDetails = $this->GlobalModel->selectQuery("cart.*,cartproducts.id as cartPrdId,cartproducts.totalPrice,cartproducts.productPrice,cartproducts.isCombo,cartproducts.productCode,cartproducts.comboProducts,cartproducts.productQty,cartproducts.addOns,productmaster.productImage,productmaster.productEngName,tablemaster.tableNumber,sectorzonemaster.zoneName", 'cart', array("cart.branchCode" => $branchCode, "cart.tableSection" => $tableSection, "cart.tableNumber" => $tableNumber, "cart.custPhone" => $custPhone), array("cart.id" => 'ASC'), $join, $joinType);
		//echo $this->db->last_query();
        if ($orderDetails) {
			$data['orderDetails']=$orderDetails;
			foreach($orderDetails->result() as $ord){
				$cartPrdId = $ord->cartPrdId;
				$data['combo'.$cartPrdId]=$data['comboprd'.$cartPrdId]=$data['extras'.$cartPrdId]=[];
				$data['comboQuantity']=0;
				if($ord->isCombo==1){
					$comboProducts=json_decode($ord->comboProducts,true);
					$data['comboQuantity'] = $comboProducts[0]['productQty'];
					$comboQuery = $this->GlobalModel->selectQuery('productcombo.productComboName,productcombo.productComboPrice,productcombo.productComboImage','productcombo',array('productcombo.code'=>$ord->productCode));
					if($comboQuery && $comboQuery->num_rows()>0){
						$data['combo'.$cartPrdId] = $comboQuery->result()[0];
					}
					$data['comboprd'.$cartPrdId] = $this->GlobalModel->selectQuery('productcombolineentries.productPrice,productmaster.productEngName','productcombolineentries',array('productcombolineentries.productComboCode'=>$ord->productCode),array(),array('productmaster'=>'productmaster.code=productcombolineentries.productCode'),array('productmaster'=>'inner'));
				}
			}
			
			$header['pageTitle'] = "Checkout";
			$data['discount'] = $this->GlobalModel->selectActiveData('discountmaster');
			$this->load->view('dashboard/order/header', $header);
			$this->load->view('dashboard/order/checkout',$data);
			$this->load->view('dashboard/order/footer');
		}
	}
	
	  public function calculateAmount(){
        $taxAmount = 0;
        $cartId = $this->input->post("cartId");
        $discount = $this->input->post("discount");
        $couponCode = $this->input->post("couponCode");
        $cartPrdId = array_unique($this->input->post("cartPrdId"));
        $cartPrdId = implode("','", $cartPrdId);
		
		$prdQuery = $this->db->query("select ifnull(sum(totalPrice),0) as subTotal,ifnull(count(id),0) as prdCount from cartproducts where cartproducts.id in ('" . $cartPrdId . "')")->result()[0];
		$subTotal = $prdQuery->subTotal;
		
		$serviceCharges = $this->GlobalModel->selectActiveDataByField('code', 'SET_1', 'settings')->result()[0]->settingValue;
		$flatdiscount=0;
		$offerDataString = json_decode($this->input->post("offerDataString"),true);
		//print_r($offerDataString);
		if(isset($offerDataString) && !empty($offerDataString)){
			$offerData = $offerDataString[0];
			if($offerData['offerType']=='flat'){
				$flatdiscount = $offerData['flatAmt'];
			}else{
				$discount = $discount+$offerData['discount'];
			}
		}
		$discountAmount=0;
		$totalTax=0;
		if(!empty($this->input->post("productCodes"))){
			 $productCodes = $this->input->post("productCodes");
			 $productCodes = implode("','", $productCodes);
			$joinType = array('taxgroupmaster' => 'inner','cartproducts'=>'inner');
			$join = array('taxgroupmaster' => 'taxgroupmaster.code=productmaster.productTaxGrp','cartproducts'=>'cartproducts.productCode=productmaster.code');
			$extraCondition = " productmaster.code in ('" . $productCodes . "') and cartproducts.id in ('" . $cartPrdId . "')";
			$prdDetails = $this->GlobalModel->selectQuery('productmaster.productTaxGrp,taxgroupmaster.taxes,cartproducts.totalPrice,cartproducts.isCombo', 'productmaster', array('productmaster.isActive' => 1, 'taxgroupmaster.isActive' => 1), array('productmaster.productTaxGrp' => 'ASC'), $join, $joinType, array(), "", "", array(), $extraCondition);
			if ($prdDetails) {
				foreach ($prdDetails->result() as $pt) {
					$taxes = json_decode($pt->taxes, true);
					$taxPer = $this->db->query("select ifnull(sum(taxes.taxPer),0) as taxPer from taxes where taxes.isActive=1 and taxes.code in('" . implode("','", $taxes) . "')")->result()[0]->taxPer;
					$discountAmount = $discountAmount + round(($pt->totalPrice*$discount/100),2);
					$totalTax = $totalTax + round(($pt->totalPrice*$taxPer/100),2);
				}
			}
		}
		
		if(!empty($this->input->post("comboproductCodes"))){
			 $comboproductCodes = $this->input->post("comboproductCodes");
			 $comboproductCodes = implode("','", $comboproductCodes);
			 $join = array('cartproducts'=>'cartproducts.productCode=productcombo.code');
			 $joinType = array('cartproducts'=>'inner');
			$extraCondition = " productcombo.code in ('" . $comboproductCodes . "') and cartproducts.id in ('" . $cartPrdId . "')";
			$comboDetails = $this->GlobalModel->selectQuery('cartproducts.totalPrice,productcombo.taxAmount', 'productcombo', array('productcombo.isActive' => 1), array('productcombo.id' => 'ASC'), $join, $joinType, array(), "", "", array(), $extraCondition);
			if ($comboDetails) {
				foreach ($comboDetails->result() as $cpt) {
					$totalTax = $totalTax + $cpt->taxAmount;
				}
			}
		}
		$discountAmount = $discountAmount+$flatdiscount;
		$subtotalafterdiscount = $subTotal - $discountAmount;
		$actualTax = $totalTax * ($subtotalafterdiscount/$subTotal);
		$grandTotal=$subtotalafterdiscount+$actualTax+$serviceCharges;
        $response['subTotal'] = number_format($subTotal, 2, '.', '');
        $response['discount'] = number_format($discountAmount, 2, '.', '');
        $response['subtotalafterdiscount'] = number_format($subtotalafterdiscount, 2, '.', '');
        $response['totalTax'] = number_format($totalTax, 2, '.', '');
        $response['actualTax'] = number_format($actualTax, 2, '.', '');
        $response['serviceCharges'] = number_format($serviceCharges, 2, '.', '');
        $response['grandTotal'] = number_format($grandTotal, 2, '.', '');
        echo json_encode($response);
    }
	
	public function placeOrder()
    {
		$ip = $_SERVER['REMOTE_ADDR'];
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $cartId = array_unique($this->input->post('cartId'));
        $custPhone = $this->input->post('custPhone');
        $custName = $this->input->post('custName');
        $subTotal = $this->input->post('subTotal');
        $orderType = $this->input->post('orderType');
        $tax = $this->input->post('tax');
        $discountPer = $this->input->post('discountPer');
        $discount = $this->input->post('discount');
        $offerData = $this->input->post('offerData');
        $serviceCharges = $this->input->post('serviceCharges');
        $grandTotal = $this->input->post('grandTotal');
        $paymentMode = $this->input->post('paymentMode');
        $remark = $this->input->post('remark');
        $cartIds = implode("','", $cartId);
		$extraCondition = " cartproducts.cartId in ('" . $cartIds . "')";
		$cartproductDetails = $this->GlobalModel->selectQuery('cartproducts.*', 'cartproducts', [], [], [], [], [], "", "", [], $extraCondition);
        if ($cartproductDetails) {
			$data=array(
				'name'=>$custName,
				'isActive'=>1
			);
			$checkCustomer = $this->GlobalModel->selectQuery('customer.code','customer',array('customer.isActive'=>1,'customer.phone'=>$custPhone));
			if($checkCustomer && $checkCustomer->num_rows()>0){
				$code = $checkCustomer->result()[0]->code;
				$data['editID'] = $addID;
				$data['editIP'] = $ip;
				$this->GlobalModel->doEdit($data,'customer',$code);
				$customerCode = $code;
			}else{
				$data['phone'] = $custPhone;
				$data['addID'] = $addID;
				$data['addIP'] = $ip;
				$customerCode = $this->GlobalModel->addNew($data,'customer','CUST');
			}
            $extraCondition = " cart.id in ('" . $cartIds . "')";
            $cartDetails = $this->GlobalModel->selectQuery('distinct cart.branchCode,cart.tableNumber,cart.tableSection,cart.custPhone,cart.custName', 'cart', [], [], [], [], [], "", "", [], $extraCondition);
            if ($cartDetails) {
                $ct = $cartDetails->result()[0];
                $insertArr = array(
                    'branchCode' => $ct->branchCode,
                    'tableSection' => $ct->tableSection,
                    'tableNumber' => $ct->tableNumber,
                    'custCode' => $customerCode,
                    'custPhone' => $ct->custPhone,
                    'custName' => $ct->custName,
                    'orderType' => $orderType,
                    'orderStatus' => 'CON',
                    'paymentMode' => $paymentMode,
                    'subTotal' => $subTotal,
                    'discountPer' => $discountPer,
                    'discount' => $discount,
                    'offerData' => $offerData,
                    'tax' => $tax,
                    'serviceCharges' => $serviceCharges,
                    'grandTotal' => $grandTotal,
                    'remark' => $remark,
                    'isActive' => 1,
                );
                $orderCode = 'ORDER' . rand(99, 99999);
                $insertResult = $this->GlobalModel->addWithoutYear($insertArr, 'ordermaster', $orderCode);
                if ($insertResult != 'false') {
                    foreach ($cartproductDetails->result() as $cpt) {
                        $orderLineData = array(
                            'orderCode' => $insertResult,
                            'productCode' => $cpt->productCode,
                            'productQty' => $cpt->productQty,
                            'productPrice' => $cpt->productPrice,
                            'addons' => $cpt->addOns,
                            'comboProducts' => $cpt->comboProducts,
                            'variantCode' => $cpt->variantCode,
                            'totalPrice' => $cpt->totalPrice,
                            'isActive' => 1,
                        );
                        $orderLineResult = $this->GlobalModel->addWithoutYear($orderLineData, 'orderlineentries', 'ORDL');
                    }
                   $this->db->query("Delete from cart where cart.id in ('" . $cartIds . "')");
                  $this->db->query("Delete from cartproducts where cartproducts.cartId in ('" . $cartIds . "')");
                    $response['status'] = true;
                    $response['message'] = 'Order Confirmed successfully';
                } else {
                    $response['status'] = false;
                    $response['message'] = 'Failed to confirm order';
                }
            }
        } else {
            $response['status'] = false;
            $response['message'] = 'Cart is empty...Please add products and try again..';
        }
        echo json_encode($response);
    }
	
	public function getCouponData(){
		$keywordSearch = $this->input->post('keywordsearch');
		$like='';
		$i=0;
		if($keywordSearch!=''){
			$like = " and (couponoffer.couponCode LIKE '%$keywordSearch%')";
		}
		$couponData = $this->db->query("select couponoffer.* from couponoffer where (couponoffer.isActive=1 and ('".date('Y-m-d')."' between couponoffer.startDate and couponoffer.endDate)) ".$like);
		$couponHtml ='<input type="hidden" class="couponCode" id="couponCode" value="">
			<input type="hidden" class="couponCodeValue" id="couponCodeValue" value="">
			<input type="hidden" class="voutureType" id="voutureType" value="">';
		if($couponData && $couponData->num_rows()>0){
			foreach($couponData->result() as $cop){
				$i++;
				if($cop->offerType=='cap'){
					$offerType = 'PER';
					$discount = $cop->discount;
				}else{
					$offerType='FLAT';
					$discount = $cop->flatAmount;
				}
				$couponHtml .='<li>
					<div class="infoWrap"> 
						<div class="row mb-2 mt-2">
							<div class="col-md-4 col-12">
								<button class="btn btn-sm btn-warning" style="background-color:transparent;color:black;border-radius:5px;">'.$cop->couponCode.'</button>
							</div>
							<div class="col-md-6 col-12" style="text-align:center"><h6>Discount:'.$discount.'</h6><br>Minimum amount : '.$cop->minimumAmount.'</label></div>
							<div class="col-md-2 col-12">
								<input type="hidden" id="voutureType'.$i.'" name="voutureType'.$i.'" value="COUPON">
								<input type="hidden" id="code'.$i.'" name="code'.$i.'" value="'.$cop->code.'">
								<input type="hidden" id="text'.$i.'" name="text'.$i.'" value="'.$cop->couponCode.'">
								<input type="hidden" id="type'.$i.'" name="type'.$i.'" value="'.$cop->offerType.'">
								<input type="hidden" id="minAmt'.$i.'" name="minAmt'.$i.'" value="'.$cop->minimumAmount.'">
								<input type="hidden" id="discount'.$i.'" name="discount'.$i.'" value="'.$cop->discount.'">
								<input type="hidden" id="capLimit'.$i.'" name="capLimit'.$i.'" value="'.$cop->capLimit.'">
								<input type="hidden" id="flatAmount'.$i.'" name="flatAmount'.$i.'" value="'.$cop->flatAmount.'">
								<a href="#" class="btn btn-sm btn-success applyCoupon" id="applyBtn'.$cop->code.'" data-index="'.$i.'">Apply</a>
							</div>
						</div>
					</div>	
				</li>';
			}
		}
		$like='';
		if($keywordSearch!=''){
			$like = " and (offer.title LIKE '%$keywordSearch%')";
		}
		$offerData = $this->db->query("select offer.* from offer where (offer.isActive=1 and ('".date('Y-m-d')."' between offer.startDate and offer.endDate)) ".$like);
		if($offerData && $offerData->num_rows()>0){
			foreach($offerData->result() as $op){
				$i++;
				if($op->offerType=='cap'){
					$offerType = 'PER';
					$discount = $op->discount;
				}else{
					$offerType='FLAT';
					$discount = $op->flatAmount;
				}
				$couponHtml .='<li>
					<div class="infoWrap"> 
						<div class="row mb-2 mt-2">
							<div class="col-md-6 col-12">
								<button class="btn btn-sm btn-warning" style="background-color:transparent;color:black;border-radius:5px;">'.$op->title.'</button>
							</div>
							<div class="col-md-4 col-12" style="text-align:center"><h6>Discount: '.$discount.'</h6><br><label>Minimum amount : '.$op->minimumAmount.'</label></div>
							<div class="col-md-2 col-12 ">
								<input type="hidden" id="voutureType'.$i.'" name="voutureType'.$i.'" value="OFFER">
								<input type="hidden" id="code'.$i.'" name="code'.$i.'" value="'.$op->code.'">
								<input type="hidden" id="text'.$i.'" name="text'.$i.'" value="'.$op->title.'">
								<input type="hidden" id="type'.$i.'" name="type'.$i.'" value="'.$op->offerType.'">
								<input type="hidden" id="minAmt'.$i.'" name="minAmt'.$i.'" value="'.$op->minimumAmount.'">
								<input type="hidden" id="discount'.$i.'" name="discount'.$i.'" value="'.$op->discount.'">
								<input type="hidden" id="capLimit'.$i.'" name="capLimit'.$i.'" value="'.$op->capLimit.'">
								<input type="hidden" id="flatAmount'.$i.'" name="flatAmount'.$i.'" value="'.$op->flatAmount.'">
								<a href="#" class="btn btn-sm btn-success applyCoupon" id="applyBtn'.$op->code.'" data-index="'.$i.'">Apply</a>
							</div>
						</div>
					</div>	
				</li>';
			}
		}
		if($offerData && $offerData->num_rows()==0 && $offerData && $offerData->num_rows()==0){
			$couponHtml = '<li  style="text-align:center;margin-top:10px"><b>No Data Found...</b></li>';
		}
		echo $couponHtml;
	}
}
