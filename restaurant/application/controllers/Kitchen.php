<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kitchen extends CI_Controller
{
	var $session_key;
	protected $rolecode, $branchCode, $userLang;

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
		$this->rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		$this->branchCode = $this->session->userdata['logged_in' . $this->session_key]['branchCode'];
		$this->userLang = $this->session->userdata['logged_in' . $this->session_key]['lang'];
	}

	public function listRecords()
	{
		//$header['pageTitle'] = "All Orders";
		$table_name = 'branchmaster';
		$orderColumns = array("branchmaster.*");
		$cond = array('branchmaster' . '.isDelete' => 0, 'branchmaster' . '.isActive' => 1);
		$data['branchdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);
		$data['branchCode'] = "";
		$data['branchName'] = "";
		if ($this->branchCode != "") {
			$data['branchCode'] = $this->branchCode;
			$data['branchName'] = $this->GlobalModel->selectDataById($this->branchCode, 'branchmaster')->result()[0]->branchName;
		}
		$data['role'] = "";
		if ($this->rolecode != "") {
			$data['role'] = $this->rolecode;
		}
		$data['userLang'] = strtolower($this->userLang);
		$this->load->view('dashboard/header');
		$this->load->view('dashboard/order-kitchen/kotlist', $data);
		$this->load->view('dashboard/footer');
		/*$this->load->view('dashboard/order-kitchen/header', $header);
        $this->load->view('dashboard/order-kitchen/list');
        $this->load->view('dashboard/order-kitchen/footer');*/
	}

	public function getKOTOrders()
	{
		$branchid = $this->input->get('branchID');
		if ($this->branchCode != "") {
			$branchid = $this->branchCode;
		}
		$data['kotOrders'] = $this->OrderApiModel->get_kitchen_orders($branchid);
		$this->load->view('dashboard/order-kitchen/orders', $data);
	}

	public function getKOTDataBranchWise()
	{
		$header['pageTitle'] = "All Orders";
		$data['branch'] = $this->input->post('branchCode');
		$data['role'] = "";
		if ($this->rolecode != "") {
			$data['role'] = $this->rolecode;
		}
		if ($data['branch'] != "") {
			$this->load->view('dashboard/order-kitchen/header', $header);
			$this->load->view('dashboard/order-kitchen/list', $data);
			$this->load->view('dashboard/order-kitchen/footer');
		} else {
			redirect("Kitchen/listRecords");
		}
	}

	public function getKotDetails_old()
	{
		$cartId = $this->input->post('cartId');
		$joinType = array('productmaster' => 'left', 'customizedcategorylineentries' => 'left');
		$join = array('productmaster' => 'productmaster.code=cartproducts.productCode', 'customizedcategorylineentries' => 'customizedcategorylineentries.code=cartproducts.variantCode');
		$cartDetails = $this->GlobalModel->selectQuery('customizedcategorylineentries.subCategoryTitle as variantName,cartproducts.isCombo,cartproducts.comboProducts,cartproducts.productCode,cartproducts.productQty,cartproducts.totalPrice,cartproducts.addOns,productmaster.productEngName,productmaster.productImage,cartproducts.productPrice,productmaster.productPrice as orgPrice', 'cartproducts', array('cartproducts.cartId' => $cartId), array(), $join, $joinType);
		if ($cartDetails) {
			$prdHtml = '<ul>';
			foreach ($cartDetails->result() as $cart) {
				$prdAdd = '';
				$addons = json_decode($cart->addOns, true);
				foreach ($addons as $add) {
					$addonText = $this->GlobalModel->selectQuery('customizedcategorylineentries.subCategoryTitle,customizedcategorylineentries.price', 'customizedcategorylineentries', array('customizedcategorylineentries.isActive' => 1, 'customizedcategorylineentries.code' => $add['addonCode']));
					if ($addonText) {
						$result = $addonText->result()[0];
						$prdAdd .= $result->subCategoryTitle . '<span style="float:right">' . $result->price . '</span><br>';
					}
				}
				if ($cart->isCombo == 0) {
					$productImage = 'assets/food.png';
					if ($cart->productImage != '' && $cart->productImage != NULL) {
						$productImage = $cart->productImage;
					}
					$prdHtml .= '<li>
						<div class="infoWrap mt-2">
							<div class="row">
								<div class="col-md-8 col-12"></div>
								<div class="col-md-4 col-12">
									<a class="btn btn-sm btn-success" style="float:right" target="_blank" href="' . base_url() . 'kitchen/viewRecipe/' . $cart->productCode . '">View Recipe</a>
								</div>
							</div>
							<div class="row mt-2">
								<div class="col-md-3 col-12"><img src="' . base_url() . $productImage . '" alt="" class="itemImg img-responsive" width="65px" height="70px"> </div>
								<div class="col-md-9 col-12">
									<h6>Product Name : <span style="float:right">' . $cart->productEngName . '</span></h6>
									<h6>Product Quantity : <span style="float:right">' . $cart->productQty . '</span></h6>';
					if ($cart->variantName != '') {
						$prdHtml .= '<h6>Variant : <span style="float:right">' . $cart->variantName . '</span></h6>';
					}
					if ($prdAdd != '') {
						$prdHtml .= '<h6>Addon : <h6>
									<div><span class="cstmstn--txt">' . $prdAdd . '</span></div>';
					}
					$prdHtml .= '</div>
							</div>
						</div>
					</li>';
				} else {
					$comboProducts = json_decode($cart->comboProducts, true);
					$comboQuantity = $comboProducts[0]['productQty'];
					$comboDetails = $this->GlobalModel->selectQuery('productcombo.productComboName,productcombo.productComboPrice,productcombo.productComboImage', 'productcombo', array('productcombo.code' => $cart->productCode))->result()[0];
					$comboImage = 'assets/food.png';
					if ($comboDetails->productComboImage != '' && $comboDetails->productComboImage != NULL) {
						$comboImage = $comboDetails->productComboImage;
					}
					$prdHtml .= '<li>
						<div class="infoWrap mt-2"> 
							<div class="row">
								<div class="col-md-3 col-12"><img src="' . base_url() . $comboImage . '" alt="" class="itemImg img-responsive" width="65px" height="70px"> </div>
								<div class="col-md-9 col-12">
									<h6>Product Combo Name : <span style="float:right">' . $comboDetails->productComboName . '</span></h6>
									<h6>Product Combo Quantity: <span style="float:right">' . $comboQuantity . '</span></h6>
									 
					            </div>
							</div><div class="row mt-2">
							<h6>Product Details: <h6>
							<table class="table table-bordered">
							<tr><th>Sr No</th><th>Name</th><th>Quantity</th><th>Price</th></tr>';
					$comboProductDetails = $this->GlobalModel->selectQuery('productcombolineentries.productPrice,productmaster.productEngName', 'productcombolineentries', array('productcombolineentries.productComboCode' => $cart->productCode), array(), array('productmaster' => 'productmaster.code=productcombolineentries.productCode'), array('productmaster' => 'inner'));
					if ($comboProductDetails) {
						$comboSrNo = 0;
						foreach ($comboProductDetails->result() as $cmbl) {
							$comboSrNo++;
							$prdHtml .= '<tr><td>' . $comboSrNo . '</td><td>' . $cmbl->productEngName . '</td><td>' . $comboQuantity . '</td><td>' . number_format($cmbl->productPrice, 2, '.', '') . '</td></tr>';
						}
					}
					$prdHtml .= '</table></div>
						</div>
					</li>';
				}
			}
			$prdHtml .= '</ul>';
		} else {
			$prdHtml = 'No products available';
		}
		echo $prdHtml;
	}

	public function getKotDetails()
	{
		$cartId = $this->input->post('cartId');
		$select = 'cartproducts.*';
		$table = 'cartproducts';
		$condition = array('cartproducts.cartId' => $cartId);
		$orderby = array('cartproducts.id' => "ASC");
		$join = [];
		$joinType = [];
		$data['cartProducts'] = $this->GlobalModel->selectQuery($select, $table, $condition, $orderby, $join, $joinType);
		$data['userLang'] = strtolower($this->userLang);
		$this->load->view('dashboard/order-kitchen/kotproducts', $data);
	}

	public function viewRecipe()
	{
		$header['pageTitle'] = "Recipe Details";
		$productCode = $this->uri->segment(3);
		$getRecipeDetails = $this->GlobalModel->selectQuery('recipecard.code,recipecard.productCode,recipecard.recipeDirection,productmaster.productImage,productmaster.productEngName,productmaster.preparationTime,productmaster.productEngDesc,productcategorymaster.categoryName,productcategorymaster.categorySName,productsubcategorymaster.subcategoryName,productsubcategorymaster.subcategorySName', 'recipecard', array('recipecard.productCode' => $productCode), array(), array('productmaster' => 'productmaster.code=recipecard.productCode', 'productcategorymaster' => 'productcategorymaster.code=productmaster.productCategory', 'productsubcategorymaster' => 'productsubcategorymaster.code=productmaster.subcategory'), array('productmaster' => 'productmaster.inner', 'productcategorymaster' => 'inner', 'productsubcategorymaster' => 'left'));
		if ($getRecipeDetails) {
			$data['productCode'] = $productCode;
			$data['recipeDeatils'] = $getRecipeDetails;
			$code = $getRecipeDetails->result()[0]->code;
			$data[$productCode] = $this->GlobalModel->selectQuery('recipelineentries.code,recipelineentries.itemQty,recipelineentries.itemCost,itemmaster.itemEngName,unitmaster.unitSName', 'recipelineentries', array('recipelineentries.recipeCode' => $code, 'recipelineentries.isActive' => 1), array(), array('itemmaster' => 'itemmaster.code=recipelineentries.itemCode', 'unitmaster' => 'unitmaster.code=recipelineentries.unitCode'), array('itemmaster' => 'inner', 'unitmaster' => 'inner'));
			$this->load->view('dashboard/order-kitchen/header', $header);
			$this->load->view('dashboard/order-kitchen/recipeDetails', $data);
			$this->load->view('dashboard/order-kitchen/footer');
		}
	}
}
