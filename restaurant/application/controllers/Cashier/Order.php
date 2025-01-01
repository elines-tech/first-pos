<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Salla\ZATCA\GenerateQrCode;
use Salla\ZATCA\Tags\InvoiceDate;
use Salla\ZATCA\Tags\InvoiceTaxAmount;
use Salla\ZATCA\Tags\InvoiceTotalAmount;
use Salla\ZATCA\Tags\Seller;
use Salla\ZATCA\Tags\TaxNumber;

class Order extends CI_Controller
{
	var $session_key;
	protected $branchCode, $cmpCode;
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper('form', 'url', 'html');
		$this->load->model('GlobalModel');
		$this->load->model('OrderApiModel');
		$this->session_key = $this->session->userdata('cash_key' . CASH_SESS_KEY);
		if (!isset($this->session->userdata['cash_logged_in' . $this->session_key]['code'])) {
			redirect(base_url('Cashier/Login'), 'refresh');
		}
		$this->branchCode = $this->session->userdata['cash_logged_in' . $this->session_key]['branchCode'];
		$this->cmpCode = $this->session->userdata['cash_logged_in' . $this->session_key]['cmpCode'];
		$res = $this->GlobalModel->checkActiveSubscription();
		if ($res == "EXPIRED") {
			$this->load->view('errors/exppackage.php');
		}
	}

	public function listRecords()
	{
		$header['pageTitle'] = "All Orders";
		$data['tableData'] = $this->GlobalModel->selectActiveData('tablemaster');
		$data['company'] = $this->GlobalModel->get_row_array(["companymaster.code" => $this->cmpCode], "companymaster");
		$data['branch'] = $this->GlobalModel->get_row_array(["branchmaster.code" => $this->branchCode], "branchmaster");
		$data['branchCode'] = $this->branchCode;
		$this->load->view('cashier/order/header', $header);
		$this->load->view('cashier/order/list', $data);
		$this->load->view('cashier/order/footer');
	}

	public function add()
	{
		$data['lang'] = strtolower($this->session->userdata['cash_logged_in' . $this->session_key]['lang']) ?? "english";
		$data['tableNumber'] = $this->input->post('tableNumber') ?? "";
		$data['tableSection'] = $this->input->post('tableSection') ?? "";
		$data['branchCode'] = $this->branchCode;
		$data['custPhone'] = $this->input->post('custPhone') ?? "";
		$data['custName'] = $this->input->post('custName') ?? "";
		$categories = $this->GlobalModel->selectActiveData('productcategorymaster');
		$data['company'] = $this->GlobalModel->get_row_array(["companymaster.code" => $this->cmpCode], "companymaster");
		$data['branch'] = $this->GlobalModel->get_row_array(["branchmaster.code" => $this->branchCode], "branchmaster");
		$data['sectors'] = $this->GlobalModel->selectQuery('sectorzonemaster.*', 'sectorzonemaster', ["sectorzonemaster.branchCode" => $this->branchCode, "sectorzonemaster.isActive" => 1]);
		$data['tables'] = $this->GlobalModel->selectQuery('tablemaster.*', 'tablemaster', ['tablemaster.branchCode' => $this->branchCode, "tablemaster.isActive" => 1]);
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
		$this->load->view('cashier/order/header', $header);
		$this->load->view('cashier/order/add', $data);
		$this->load->view('cashier/order/footer');
	}

	public function getKotDetails($cartId = '')
	{
		if ($cartId == '') {
			$cartId = $this->input->post('cartId');
		}
		$kotTotal = 0;
		$joinType = array('productmaster' => 'left', 'customizedcategorylineentries' => 'left');
		$join = array('productmaster' => 'productmaster.code=cartproducts.productCode', 'customizedcategorylineentries' => 'customizedcategorylineentries.code=cartproducts.variantCode');
		$cartDetails = $this->GlobalModel->selectQuery('customizedcategorylineentries.subCategoryTitle as variantName,cartproducts.id,cartproducts.isCombo,cartproducts.comboProducts,cartproducts.productCode,cartproducts.productQty,cartproducts.totalPrice,cartproducts.addOns,productmaster.productEngName,productmaster.productImage,cartproducts.productPrice,productmaster.productPrice as orgPrice', 'cartproducts', array('cartproducts.cartId' => $cartId), array(), $join, $joinType);
		if ($cartDetails) {
			foreach ($cartDetails->result() as $cart) {
				$kotTotal = $kotTotal + $cart->totalPrice;
			}
			$data['cartId'] = $cartId;
			$data['cartDetails'] = $cartDetails;
			$prdHtml = $this->load->view('cashier/order/cart', $data, true);
		} else {
			$prdHtml = 'No products available';
		}
		echo $prdHtml . '$$' . $kotTotal;
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
		$data['serviceCharges'] = $this->GlobalModel->selectActiveDataByField('code', 'STG_1', 'settings')->result()[0]->settingValue;
		$data['discounts'] = $this->GlobalModel->selectActiveData('discountmaster');
		$data['coupons'] = $this->GlobalModel->selectActiveData('couponoffer');
		if ($isDraft == 1) {
			$masterTbl = 'draftcart';
			$pertTbl = 'draftcartproducts';
			$primaryId = 'draftId';
		} else {
			$masterTbl = 'cart';
			$pertTbl = 'cartproducts';
			$primaryId = 'cartId';
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
			$cartHtml = $this->load->view('cashier/order/orderdetails', $data, true);
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
		$couponDiscountAmount = 0;
		if ($discountAmount != 0) {
			$discountAmount = $subTotal - $discountAmount;
			$couponDiscountAmount = $discountAmount;
			if ($offerType == 'flat') {
				$couponDiscountAmount = $discountAmount - $couponDiscount;
			} elseif ($offerType == 'cap') {
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
				$getTaxes = $this->GlobalModel->directQuery("select taxes.taxPer from taxes where taxes.isActive=1 and taxes.code in('" . implode("','", $taxes) . "')");
				if (!empty($getTaxes)) {
					foreach ($getTaxes as $t) {
						$taxAmount = $taxAmount + number_format(($couponDiscountAmount * $t['taxPer'] / 100), 2, ".", "");
					}
				}
			}
		}
		$response['couponDiscountAmount'] = number_format($couponDiscountAmount, 2, '.', '');
		$response['discountAmount'] = number_format($discountAmount, 2, '.', '');
		$response['taxAmount'] = number_format($taxAmount, 2, '.', '');
		echo json_encode($response);
	}

	public function removeKot()
	{
		$cartId = $this->input->post('cartId');
		$kotNumber = $this->input->post('kotNumber');
		$query = $this->GlobalModel->deleteForeverFromField('id', $cartId, 'cart');
		if ($query == 'true') {
			$this->GlobalModel->deleteForeverFromField('cartId', $cartId, 'cartproducts');
			$response['status'] = true;
		} else {
			$response['status'] = false;
			$response['message'] = 'Failed to remove KOT...Please try again';
		}
		echo json_encode($response);
	}

	public function deleteCartProduct()
	{
		$cartId = $this->input->post('cartId');
		$query = $this->GlobalModel->deleteForeverFromField('id', $cartId, 'cartproducts');
		if ($query == 'true') {
			$response['status'] = true;
		} else {
			$response['status'] = false;
			$response['message'] = 'Failed to remove product...Please try again';
		}
		echo json_encode($response);
	}

	public function removeAddon()
	{
		$newAddon = [];
		$productextraCode = $this->input->post('productextraCode');
		$cartPrdId = $this->input->post('cartPrdId');
		$checkProduct = $this->GlobalModel->selectQuery('cartproducts.addOns,cartproducts.totalPrice,cartproducts.cartId', 'cartproducts', array('cartproducts.id' => $cartPrdId));
		$addOns = $checkProduct->result()[0]->addOns;
		$cartId = $checkProduct->result()[0]->cartId;
		$totalPrice = $checkProduct->result()[0]->totalPrice;
		$cartPrdId = $this->input->post('cartPrdId');
		$addonString = json_decode($addOns, true);
		foreach ($addonString as $add) {
			if ($add['addonCode'] != $productextraCode) {
				array_push($newAddon, $add);
			} else {
				$totalPrice = $totalPrice - $add['addonPrice'];
			}
		}
		$this->GlobalModel->doEditWithField(array('addOns' => json_encode($newAddon), 'totalPrice' => $totalPrice), 'cartproducts', 'id', $cartPrdId);
		if ($this->db->affected_rows() > 0) {
			$response['status'] = true;
			$response['totalPrice'] = number_format($totalPrice, 2, '.', '');
		} else {
			$response['status'] = false;
			$response['message'] = 'Failed to remove addon...Please try again';
		}
		echo json_encode($response);
	}

	public function updateCartPrices()
	{
		$type = $this->input->post('type');
		$cartPrdId = $this->input->post('cartPrdId');
		$cartId = $this->input->post('cartId');
		$isCombo = $this->input->post('isCombo');
		$originalComboPrice = $this->input->post('originalComboPrice');
		$currentqty = $this->input->post('currentqty');
		$totalPrice = 0;
		$comboProducts = $newCombo = [];
		if ($isCombo == 1) {
			$originalPrice = $originalComboPrice;
			$cartPrice = $totalPrice = $currentqty * $originalComboPrice;
			$productQty = $currentqty;
			$checkProduct = $this->GlobalModel->selectQuery('cartproducts.comboProducts', 'cartproducts', array('cartproducts.id' => $cartPrdId));
			if ($checkProduct) {
				foreach ($checkProduct->result() as $chk) {
					$comboString = $chk->comboProducts;
					$comboString = json_decode($comboString, true);
					foreach ($comboString as $cmb) {
						if ($type == 1) {
							$cmb['productQty'] = $currentqty + 1;
						} else {
							$cmb['productQty'] = $currentqty - 1;
						}
						array_push($newCombo, $cmb);
					}
					$comboProducts = $newCombo;
				}
			}
		} else {
			$comboProducts = [];
			$checkProduct = $this->GlobalModel->selectQuery('cartproducts.cartId,cartproducts.productPrice,cartproducts.productQty,cartproducts.totalPrice,productmaster.productPrice as originalPrice', 'cartproducts', array('cartproducts.id' => $cartPrdId), array(), array('productmaster' => 'productmaster.code=cartproducts.productCode'), array('productmaster' => 'inner'));
			if ($checkProduct) {
				foreach ($checkProduct->result() as $chk) {
					$cartId = $chk->cartId;
					$originalPrice = $chk->originalPrice;
					$cartPrice = $chk->productPrice;
					$totalPrice = $chk->totalPrice;
					$productQty = $chk->productQty;
				}
			}
		}
		if ($type == 1) {
			$cartPrice = $cartPrice + $originalPrice;
			$totalPrice = $totalPrice + $originalPrice;
			$productQty = $productQty + 1;
		} else {
			$cartPrice = $cartPrice - $originalPrice;
			$totalPrice = $totalPrice - $originalPrice;
			$productQty = $productQty - 1;
		}
		$updateData = array(
			'productPrice' => $cartPrice,
			'totalPrice' => $totalPrice,
			'productQty' => $productQty,
			'comboProducts' => json_encode($comboProducts),
		);
		$this->GlobalModel->doEditWithField($updateData, 'cartproducts', 'id', $cartPrdId);
		$result = $this->getKotDetails($cartId);
		echo $result;
	}

	public function checkout()
	{
		$prdArr = [];
		$branchCode = $this->input->post('branchCode');
		$tableSection = $this->input->post('tableSection');
		$tableNumber = $this->input->post('tableNumber');
		$custPhone = $this->input->post('custPhone');
		$joinType = array('tablemaster' => 'inner', 'cartproducts' => 'inner', 'productmaster' => 'left', 'sectorzonemaster' => 'inner');
		$join = array('tablemaster' => "tablemaster.code=cart.tableNumber", 'cartproducts' => 'cartproducts.cartId=cart.id', 'productmaster' => 'productmaster.code=cartproducts.productCode', 'sectorzonemaster' => 'sectorzonemaster.id=tablemaster.zoneCode');
		$orderDetails = $this->GlobalModel->selectQuery("cart.*,cartproducts.id as cartPrdId,cartproducts.totalPrice,cartproducts.productPrice,cartproducts.isCombo,cartproducts.productCode,cartproducts.tax, cartproducts.taxAmount,cartproducts.comboProducts,cartproducts.productQty,cartproducts.addOns,productmaster.productImage,productmaster.productEngName,tablemaster.tableNumber,sectorzonemaster.zoneName", 'cart', array("cart.branchCode" => $branchCode, "cart.tableSection" => $tableSection, "cart.tableNumber" => $tableNumber, "cart.custPhone" => $custPhone), array("cart.id" => 'ASC'), $join, $joinType);
		//echo $this->db->last_query();
		if ($orderDetails) {
			$data['orderDetails'] = $orderDetails;
			foreach ($orderDetails->result() as $ord) {
				$cartPrdId = $ord->cartPrdId;
				$data['combo' . $cartPrdId] = $data['comboprd' . $cartPrdId] = $data['extras' . $cartPrdId] = [];
				$data['comboQuantity'] = 0;
				if ($ord->isCombo == 1) {
					$comboProducts = json_decode($ord->comboProducts, true);
					$data['comboQuantity'] = $comboProducts[0]['productQty'];
					$comboQuery = $this->GlobalModel->selectQuery('productcombo.productComboName,productcombo.productComboPrice,productcombo.productComboImage', 'productcombo', array('productcombo.code' => $ord->productCode));
					if ($comboQuery && $comboQuery->num_rows() > 0) {
						$data['combo' . $cartPrdId] = $comboQuery->result()[0];
					}
					$data['comboprd' . $cartPrdId] = $this->GlobalModel->selectQuery('productcombolineentries.productPrice,productmaster.productEngName', 'productcombolineentries', array('productcombolineentries.productComboCode' => $ord->productCode), array(), array('productmaster' => 'productmaster.code=productcombolineentries.productCode'), array('productmaster' => 'inner'));
				}
			}
			$header['pageTitle'] = "Checkout";
			$data['discount'] = $this->GlobalModel->selectActiveData('discountmaster');
			$data['company'] = $this->GlobalModel->get_row_array(["companymaster.code" => $this->cmpCode], "companymaster");
			$data['branch'] = $this->GlobalModel->get_row_array(["branchmaster.code" => $this->branchCode], "branchmaster");
			$this->load->view('cashier/order/header', $header);
			$this->load->view('cashier/order/checkout', $data);
			$this->load->view('cashier/order/footer');
		}
	}

	public function calculateAmount()
	{
		$taxAmount = 0;
		$cartId = $this->input->post("cartId");
		$discount = $this->input->post("discount");
		$couponCode = $this->input->post("couponCode");
		$cartPrdId = array_unique($this->input->post("cartPrdId"));
		$cartPrdId = implode("','", $cartPrdId);

		$offerdiscount = $caplimit = $flatdiscount = 0;
		$subTotal = $subtotalafterdiscount  = $totalDiscount = $grandTotal = $totalTax = $serviceCharges = 0;
		$totalBeforeServiceCharge = 0;

		$offerDataString = json_decode($this->input->post("offerDataString"), true);
		if (isset($offerDataString) && !empty($offerDataString)) {
			$offerData = $offerDataString[0];
			if ($offerData['offerType'] == 'flat') {
				$flatdiscount = $offerData['flatAmt'];
			} else {
				$offerdiscount = $offerData['discount'];
				$caplimit = $offerData['capLimit'];
			}
		}

		$cartProducts = $this->GlobalModel->selectQuery("cartproducts.*", "cartproducts", ["cartproducts.cartId" => $cartId], ["cartproducts.id" => "ASC"]);
		if ($cartProducts) {
			$cartProducts = $cartProducts->result();
			for ($i = 0; $i < count($cartProducts); $i++) {
				$cartProduct = $cartProducts[$i];
				$productAmount = number_format($cartProduct->productPrice, 2, '.', '');
				$subTotal = number_format($subTotal +  $productAmount, 2, '.', '');
			}

			$totaldiscountPercent = $discount;
			if ($flatdiscount > 0) {
				$flatdiscountPercent = number_format((($flatdiscount / $subTotal) * 100), 2, '.', '');
				$totaldiscountPercent = $totaldiscountPercent + $flatdiscountPercent;
			} else {
				if ($caplimit > 0) {
					$capdiscount = number_format(($subTotal * ($offerdiscount * 0.01)), 2, '.', '');
					if ($capdiscount >= $caplimit) {
						$caplimitDiscountPercent = number_format((($caplimit / $subTotal) * 100), 2, '.', '');
						$totaldiscountPercent = $totaldiscountPercent + $caplimitDiscountPercent;
					} else {
						$totaldiscountPercent = $totaldiscountPercent + $offerdiscount;
					}
				} else {
					$totaldiscountPercent = $totaldiscountPercent + $offerdiscount;
				}
			}

			for ($i = 0; $i < count($cartProducts); $i++) {
				$cartProduct = $cartProducts[$i];
				$psp = $cartProduct->productPrice;
				$discountAmount = number_format($psp * ($totaldiscountPercent / 100), 2, '.', '');
				$subtotalafterdiscount = number_format($subtotalafterdiscount + $discountAmount, 2, '.',);
				$totalDiscount = number_format($totalDiscount + $discountAmount, 2, '.', '');
				$productAmount = number_format($psp - $discountAmount, 2, '.', '');
				//echo "productAmt $productAmount taxPercent $cartProduct->tax <br>";
				if ($cartProduct->isCombo == 0) {
					$taxAmount = number_format($productAmount * ($cartProduct->tax * 0.01), 2, '.', '');
				} else {
					$taxAmount = $cartProduct->tax; 
				}
				$totalTax = number_format($totalTax + $taxAmount, 2, '.', '');
				$productAmount = number_format($productAmount + $taxAmount, 2, '.', '');
				$totalBeforeServiceCharge = number_format(($totalBeforeServiceCharge + $productAmount), 2, '.', '');
			}

			$serviceChargeSetting = $this->GlobalModel->selectActiveDataByField('code', 'STG_1', 'settings');
			if ($serviceChargeSetting) {
				$serviceCharges = $serviceChargeSetting->result()[0]->settingValue;
				if ($serviceCharges == null && $serviceCharges == "")
					$serviceCharges = 0;
			}

			$serviceCharges = number_format($serviceCharges, 2, '.', '');
			$grandTotal = number_format($totalBeforeServiceCharge + $serviceCharges, 2, '.', '');

			$response['subTotal'] = $subTotal;
			$response['discount'] = $totalDiscount;
			$response['subtotalafterdiscount'] = $subtotalafterdiscount;
			$response['totalTax'] = $totalTax;
			$response['actualTax'] = number_format($totalTax, 2, '.', '');
			$response['serviceCharges'] = $serviceCharges;
			$response['grandTotal'] = $grandTotal;

			$response['status'] = 200;
		} else {
			$response['status'] = 300;
		}
		echo json_encode($response);
	}

	public function placeOrder()
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		$addID = $this->session->userdata['cash_logged_in' . $this->session_key]['code'];
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
			$data = array(
				'name' => $custName,
				'arabicName' => $custName,
				'isActive' => 1
			);
			$checkCustomer = $this->GlobalModel->selectQuery('customer.code', 'customer', array('customer.isActive' => 1, 'customer.phone' => $custPhone));
			if ($checkCustomer && $checkCustomer->num_rows() > 0) {
				$code = $checkCustomer->result()[0]->code;
				$data['editID'] = $addID;
				$data['editIP'] = $ip;
				$this->GlobalModel->doEdit($data, 'customer', $code);
				$customerCode = $code;
			} else {
				$data['phone'] = $custPhone;
				$data['addID'] = $addID;
				$data['addIP'] = $ip;
				$customerCode = $this->GlobalModel->addNew($data, 'customer', 'CUST');
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
					'addID' => $addID,
					'addIP' => $ip,
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
							'customizes' => $cpt->customizes,
							'comboProductItemsName' => $cpt->comboProductItemsName,
							'comboProducts' => $cpt->comboProducts,
							'variantCode' => $cpt->variantCode,
							'totalPrice' => $cpt->totalPrice,
							'prodNames' => $cpt->prodNames,
							'isActive' => 1,
							'addID' => $addID,
							'addIP' => $ip,
						);
						$orderLineResult = $this->GlobalModel->addWithoutYear($orderLineData, 'orderlineentries', 'ORDL');
						if ($orderLineResult != "false") {
							$branchCode = $ct->branchCode;
							$lineQty = $cpt->productQty;
							$addons = $cpt->addOns;
							$custommizes = $cpt->customizes;
							$comboproducts = $cpt->comboProducts;
							$productCode = $cpt->productCode;
							$exp = explode("_", $productCode);
							if ($exp[0] == "PC") {
								// consume stock for combo products  
								$combos_array = json_decode($comboproducts);
								if (!empty($combos_array)) {
									$this->GlobalModel->consumeComboProductStkQty($combos_array, $lineQty, $branchCode);
								}
							} else {
								// consume stock for non products 
								$this->GlobalModel->consumeStkQty($productCode, $lineQty, $branchCode);
							}
							// consume stock for addons 
							$addons_array = json_decode($addons);
							if (!empty($addons_array)) {
								$this->GlobalModel->consumeAddonStkQty($addons_array, $branchCode);
							}
							$custommizes_array = json_decode($custommizes);
							if (!empty($custommizes_array)) {
								$this->GlobalModel->consumeAddonStkQty($custommizes_array, $branchCode);
							}
						}
					}
					$this->GlobalModel->plainQuery("Delete from cart where cart.id in ('" . $cartIds . "')");
					$this->GlobalModel->plainQuery("Delete from cartproducts where cartproducts.cartId in ('" . $cartIds . "')");
					$response['status'] = true;
					$response['message'] = 'Order Confirmed successfully';
					$response['orderCode'] = $insertResult;
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

	public function getCouponData()
	{
		$keywordSearch = $this->input->post('keywordsearch');
		$like = '';
		$i = 0;
		if ($keywordSearch != '') {
			$like = " and (couponoffer.couponCode LIKE '%$keywordSearch%')";
		}
		$couponData = $this->GlobalModel->plainQuery("select couponoffer.* from couponoffer where (couponoffer.isActive=1 and ('" . date('Y-m-d') . "' between couponoffer.startDate and couponoffer.endDate)) " . $like);
		$couponHtml = '<input type="hidden" class="couponCode" id="couponCode" value="">
			<input type="hidden" class="couponCodeValue" id="couponCodeValue" value="">
			<input type="hidden" class="voutureType" id="voutureType" value="">';
		if ($couponData && $couponData->num_rows() > 0) {
			foreach ($couponData->result() as $cop) {
				$i++;
				if ($cop->offerType == 'cap') {
					$text = " (%)";
					$offerType = 'PER';
					$discount = $cop->discount;
				} else {
					$text = " (Flat)";
					$offerType = 'FLAT';
					$discount = $cop->flatAmount;
				}
				$couponHtml .= '<li>
					<div class="infoWrap"> 
						<div class="row mb-2 my-2">
							<div class="col-12 mb-1">
								<span class="btn btn-sm btn-warning" style="background-color:transparent;color:black;border-radius:5px;">' . $cop->couponCode . '</span>
								<span class="float-end">
									<a href="#" class="btn btn-sm btn-success applyCoupon" id="applyBtn' . $cop->code . '" data-index="' . $i . '">Apply</a>
								</span>
							</div>
							<div class="col-12">
								<h6>Discount: <b>' . $discount . $text . '</b></h6>
								<div>
									<small>Minimum amount : <b>' . $cop->minimumAmount . '</b></small>
								</div>
								<input type="hidden" id="voutureType' . $i . '" name="voutureType' . $i . '" value="COUPON">
								<input type="hidden" id="code' . $i . '" name="code' . $i . '" value="' . $cop->code . '">
								<input type="hidden" id="text' . $i . '" name="text' . $i . '" value="' . $cop->couponCode . '">
								<input type="hidden" id="type' . $i . '" name="type' . $i . '" value="' . $cop->offerType . '">
								<input type="hidden" id="minAmt' . $i . '" name="minAmt' . $i . '" value="' . $cop->minimumAmount . '">
								<input type="hidden" id="discount' . $i . '" name="discount' . $i . '" value="' . $cop->discount . '">
								<input type="hidden" id="capLimit' . $i . '" name="capLimit' . $i . '" value="' . $cop->capLimit . '">
								<input type="hidden" id="flatAmount' . $i . '" name="flatAmount' . $i . '" value="' . $cop->flatAmount . '">								
							</div>
						</div>
					</div>	
				</li>';
			}
		}
		$like = '';
		if ($keywordSearch != '') {
			$like = " and (offer.title LIKE '%$keywordSearch%')";
		}
		$offerData = $this->GlobalModel->plainQuery("select offer.* from offer where (offer.isActive=1 and ('" . date('Y-m-d') . "' between offer.startDate and offer.endDate)) " . $like);
		if ($offerData && $offerData->num_rows() > 0) {
			foreach ($offerData->result() as $op) {
				$i++;
				if ($op->offerType == 'cap') {
					$text = " (%)";
					$offerType = 'PER';
					$discount = $op->discount;
				} else {
					$text = " (Flat)";
					$offerType = 'FLAT';
					$discount = $op->flatAmount;
				}
				$couponHtml .= '<li>
					<div class="infoWrap"> 
						<div class="row mb-2 my-2">
							<div class="col-12 mb-1">
								<button class="btn btn-sm btn-warning" style="background-color:transparent;color:black;border-radius:5px;">' . $op->title . '</button>
								<span class="float-end">
									<a href="#" class="btn btn-sm btn-success applyCoupon" id="applyBtn' . $op->code . '" data-index="' . $i . '">Apply</a>
								</span>
							</div>
							<div class="col-12">
								<h6>Discount: <b>' . $discount . $text . '</b></h6>
								<div>
									<small>Minimum amount : <b>' . $op->minimumAmount .  '</b></small>
								</div> 
								<input type="hidden" id="voutureType' . $i . '" name="voutureType' . $i . '" value="OFFER">
								<input type="hidden" id="code' . $i . '" name="code' . $i . '" value="' . $op->code . '">
								<input type="hidden" id="text' . $i . '" name="text' . $i . '" value="' . $op->title . '">
								<input type="hidden" id="type' . $i . '" name="type' . $i . '" value="' . $op->offerType . '">
								<input type="hidden" id="minAmt' . $i . '" name="minAmt' . $i . '" value="' . $op->minimumAmount . '">
								<input type="hidden" id="discount' . $i . '" name="discount' . $i . '" value="' . $op->discount . '">
								<input type="hidden" id="capLimit' . $i . '" name="capLimit' . $i . '" value="' . $op->capLimit . '">
								<input type="hidden" id="flatAmount' . $i . '" name="flatAmount' . $i . '" value="' . $op->flatAmount . '">								
							</div>
						</div>
					</div>	
				</li>';
			}
		}
		if ($offerData && $offerData->num_rows() == 0 && $offerData && $offerData->num_rows() == 0) {
			$couponHtml = '<li  style="text-align:center;margin-top:10px"><b>No Data Found...</b></li>';
		}
		echo $couponHtml;
	}

	public function print($orderCode)
	{
		$userCode = $this->session->userdata['cash_logged_in' . $this->session_key]['code'];
		$user = $this->GlobalModel->selectQuery("usermaster.*", "usermaster", ["code" => $userCode]);
		if ($user) {
			$user =  $user->result()[0];
			$userrole = $user->userRole ?? "R_1";
			$userlang = strtolower($user->userLang) ?? "english";
			$orderdata = $this->GlobalModel->selectQuery("ordermaster.*", "ordermaster", ["code" => $orderCode]);
			if ($orderdata) {
				$orderItems = [];
				$order = $orderdata->result_array()[0];
				$orderBy = ["orderlineentries.id" => "ASC"];
				$orderLine = $this->GlobalModel->selectQuery("orderlineentries.*", "orderlineentries", ["orderlineentries.orderCode" => $orderCode], $orderBy);
				if ($orderLine) {
					$orderItems = $orderLine->result_array();

					$cnd = $ord = $join = $joinType = $like = [];
					$limit = 1;
					$company = $this->GlobalModel->selectQuery("companymaster.*", "companymaster", $cnd, $ord, $join, $joinType, $like, $limit, 0);
					if ($company) {
						$company = $company->result_array()[0];

						$data['base64QrImg'] = GenerateQrCode::fromArray([
							new Seller($company['companyname']),
							new TaxNumber($company['vatno']),
							new InvoiceDate($order['addDate']),
							new InvoiceTotalAmount($order['grandTotal']),
							new InvoiceTaxAmount($order['tax'])
						])->render();

						$order['orderItems'] = $orderItems;
						$data['order'] = $order;
						$data['company'] = $company;
						$data['userrole'] = $userrole;
						$data['userlang'] = $userlang;
						$data['branch'] = $this->GlobalModel->get_row_array(["code" => $order['branchCode']], "branchmaster");
						$this->load->view('print/autocutbill', $data);
					} else {
						$this->session->set_flashdata("message", json_encode(["status" => false, "message" => "Something went wrong. Cannot print the bill right now."]));
						redirect("Cashier/Order/listRecords", 'refresh');
					}
				} else {
					$this->session->set_flashdata("message", json_encode(["status" => false, "message" => "Something went wrong. Cannot print the bill right now."]));
					redirect("Cashier/Order/listRecords", 'refresh');
				}
			} else {
				$this->session->set_flashdata("message", json_encode(["status" => false, "message" => "Something went wrong. Cannot print the bill right now."]));
				redirect("Cashier/Order/listRecords", 'refresh');
			}
		} else {
			$this->session->set_flashdata("message", json_encode(["status" => false, "message" => "Something went wrong. Cannot print the bill right now."]));
			redirect("Cashier/Order/listRecords", 'refresh');
		}
	}

	public function stock_modify()
	{
		$join = ["ordermaster" => "ordermaster.code=orderlineentries.orderCode"];
		$joinType = ["ordermaster" => "inner"];
		$condition = ["orderlineentries.orderCode" => "ORDER426_9"];
		$orderBy = ["orderlineentries.id" => "ASC"];
		$prodLines = $this->GlobalModel->selectQuery("orderlineentries.*,ordermaster.branchCode", "orderlineentries", $condition, $orderBy, $join, $joinType);
		if ($prodLines) {
			$prodLines = $prodLines->result();
			foreach ($prodLines as $line) {
				echo "lineCode => $line->code <br>";
				$branchCode = $line->branchCode;
				$lineQty = $line->productQty;
				$addons = $line->addons;
				$comboproducts = $line->comboProducts;
				$productCode = $line->productCode;
				$exp = explode("_", $productCode);
				if ($exp[0] == "PC") {
					// consume stock for combo products  
					$combos_array = json_decode($comboproducts);
					if (!empty($combos_array)) {
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;....... Combo Product .............................................<br>";
						$this->GlobalModel->consumeComboProductStkQty($combos_array, $lineQty, $branchCode);
					}
				} else {
					// consume stock for non products
					echo "&nbsp;&nbsp;&nbsp;&nbsp;....... Normal Product .............................................<br>";
					$this->GlobalModel->consumeStkQty($productCode, $lineQty, $branchCode);
				}
				// consume stock for addons 
				$addons_array = json_decode($addons);
				if (!empty($addons_array)) {
					echo "&nbsp;&nbsp;&nbsp;&nbsp;....... Addons .............................................<br>";
					$this->GlobalModel->consumeAddonStkQty($addons_array, $branchCode);
				}
			}
		}
	}
}
