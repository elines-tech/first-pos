<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Common extends CI_Controller
{
	var $session_key;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form', 'url', 'html');
		$this->load->model('GlobalModel');
		$this->load->library('form_validation');
		$this->session_key = $this->session->userdata('key' . SESS_KEY);
		$rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
			redirect('Login', 'refresh');
		}
	}

	public function getProductCategory()
	{
		$html = array();
		$search = $this->input->get('search');
		$like = array('categorymaster.categoryName' => $search . '~both');
		$getCategory = $this->GlobalModel->selectQuery("categorymaster.*", "categorymaster", array("categorymaster" . ".isDelete" => 0, "categorymaster" . ".isActive" => 1), array("categorymaster.id" => 'desc'), array(), array(), $like, '10');
		if ($getCategory) {
			foreach ($getCategory->result() as $item) {
				$html[] = array('id' => $item->code, 'text' => $item->categoryName);
			}
		}
		echo  json_encode($html);
	}

	public function getBaseUnit()
	{
		$html = array();
		$search = $this->input->get('search');
		$like = array('baseunitmaster.baseunitName' => $search . '~both');
		$baseunit = $this->GlobalModel->selectQuery("baseunitmaster.*", "baseunitmaster", array("baseunitmaster" . ".isDelete" => 0, "baseunitmaster" . ".isActive" => 1), array("baseunitmaster.id" => 'desc'), array(), array(), $like, '10');
		if ($baseunit) {
			foreach ($baseunit->result() as $item) {
				$html[] = array('id' => $item->code, 'text' => $item->baseunitName);
			}
		}
		echo  json_encode($html);
	}

	public function getBrand()
	{
		$html = array();
		$search = $this->input->get('search');
		$like = array('brandmaster.brandName' => $search . '~both');
		$getBrand = $this->GlobalModel->selectQuery("brandmaster.*", "brandmaster", array("brandmaster" . ".isDelete" => 0, "brandmaster" . ".isActive" => 1), array("brandmaster.id" => 'desc'), array(), array(), $like, '10');
		if ($getBrand) {
			foreach ($getBrand->result() as $item) {
				$html[] = array('id' => $item->code, 'text' => $item->brandName);
			}
		}
		echo  json_encode($html);
	}
	public function getProductSubcategory()
	{
		$html = array();
		$search = $this->input->get('search');
		$categoryCode = $this->input->get('categoryCode');
		$like = array('subcategorymaster.subcategoryName' => $search . '~both');
		$getSubCategory = $this->GlobalModel->selectQuery("subcategorymaster.*", "subcategorymaster", array("subcategorymaster" . ".isDelete" => 0, "subcategorymaster" . ".isActive" => 1, "subcategorymaster.categoryCode" => $categoryCode), array("subcategorymaster.id" => 'desc'), array(), array(), $like, '10');
		if ($getSubCategory) {
			foreach ($getSubCategory->result() as $item) {
				$html[] = array('id' => $item->code, 'text' => $item->subcategoryName);
			}
		}
		echo  json_encode($html);
	}

	public function getTaxGroup()
	{
		$html = array();
		$search = $this->input->get('search');
		$like = array('taxgroupmaster.taxGroupName' => $search . '~both');
		$getTaxGroup = $this->GlobalModel->selectQuery("taxgroupmaster.*", "taxgroupmaster", array("taxgroupmaster" . ".isDelete" => 0, "taxgroupmaster" . ".isActive" => 1), array("taxgroupmaster.id" => 'desc'), array(), array(), $like, '10');
		if ($getTaxGroup) {
			foreach ($getTaxGroup->result() as $item) {
				$html[] = array('id' => $item->code, 'text' => $item->taxGroupName);
			}
		}
		echo  json_encode($html);
	}

	public function getBranch()
	{
		$html = array();
		$search = $this->input->get('search');
		$like = array('branchmaster.branchName' => $search . '~both');
		$getBranch = $this->GlobalModel->selectQuery("branchmaster.*", "branchmaster", array("branchmaster" . ".isDelete" => 0, "branchmaster" . ".isActive" => 1), array("branchmaster.id" => 'desc'), array(), array(), $like, '10');
		if ($getBranch) {
			foreach ($getBranch->result() as $item) {
				$html[] = array('id' => $item->code, 'text' => $item->branchName);
			}
		}
		echo  json_encode($html);
	}

	public function getProduct()
	{
		$html = array();
		$search = $this->input->get('search');
		$like = array('productmaster.productEngName' => $search . '~both');
		$getProduct = $this->GlobalModel->selectQuery("productmaster.*", "productmaster", array("productmaster" . ".isDelete" => 0, "productmaster" . ".isActive" => 1), array("productmaster.id" => 'desc'), array(), array(), $like, '10');
		if ($getProduct) {
			foreach ($getProduct->result() as $item) {
				$html[] = array('id' => $item->code, 'text' => $item->productEngName);
			}
		}
		echo  json_encode($html);
	}
	public function getInwardItem()
	{
		$html = array();
		$search = $this->input->get('search');
		$like = array('itemmaster.itemEngName' => $search . '~both');
		$extraCondition = " (productvariants.isDelete=0 OR productvariants.isDelete IS NULL)";
		$getItems = $this->GlobalModel->selectQuery('productmaster.*,productvariants.variantName,productvariants.code as variantCode,productvariants.sku as variantSKU', 'productmaster', array("productmaster.isActive" => 1), array(), array('productvariants' => 'productvariants.productCode=productmaster.code'), array('productvariants' => 'left'), array(), '', '', array(), $extraCondition);
		if ($getItems) {
			foreach ($getItems->result() as $item) {
				if ($item->variantName != "") {
					$html[] = array('id' => $item->productEngName . "-" . $item->variantName, 'data-variant' => $item->variantCode, 'data-val' => $item->code, 'data-sku' => $item->variantSKU, 'text' => $item->productEngName . "-" . $item->variantName);
				} else {
					$html[] = array('id' => $item->productEngName, 'data-val' => $item->code, 'data-sku' => $item->sku, 'text' => $item->productEngName);
				}
			}
		}
		echo  json_encode($html);
	}

	public function getItemCategory()
	{
		$html = array();
		$search = $this->input->get('search');
		$like = array('categorymaster.categoryName' => $search . '~both');
		$getCategory = $this->GlobalModel->selectQuery("categorymaster.*", "categorymaster", array("categorymaster" . ".isDelete" => 0, "categorymaster" . ".isActive" => 1), array("categorymaster.id" => 'desc'), array(), array(), $like, '10');
		if ($getCategory) {
			foreach ($getCategory->result() as $item) {
				$html[] = array('id' => $item->code, 'text' => $item->categoryName);
			}
		}
		echo  json_encode($html);
	}

	public function getUnit()
	{
		$html = array();
		$search = $this->input->get('search');
		$like = array('unitmaster.unitName' => $search . '~both');
		$getUnit = $this->GlobalModel->selectQuery("unitmaster.*", "unitmaster", array("unitmaster" . ".isDelete" => 0, "unitmaster" . ".isActive" => 1), array("unitmaster.id" => 'desc'), array(), array(), $like, '10');
		if ($getUnit) {
			foreach ($getUnit->result() as $item) {
				$html[] = array('id' => $item->code, 'text' => $item->unitName);
			}
		}
		echo  json_encode($html);
	}

	public function getSupplier()
	{
		$html = array();
		$search = $this->input->get('search');
		$like = array('suppliermaster.supplierName' => $search . '~both');
		$getSupplier = $this->GlobalModel->selectQuery("suppliermaster.*", "suppliermaster", array("suppliermaster" . ".isDelete" => 0, "suppliermaster" . ".isActive" => 1), array("suppliermaster.id" => 'desc'), array(), array(), $like, '10');
		if ($getSupplier) {
			foreach ($getSupplier->result() as $item) {
				$html[] = array('id' => $item->code, 'text' => $item->supplierName);
			}
		}
		echo  json_encode($html);
	}

	public function getOrder()
	{
		$html = array();
		$search = $this->input->get('search');
		$like = array('ordermaster.code' => $search . '~both');
		$getOrders = $this->GlobalModel->selectQuery("ordermaster.*", "ordermaster", array(), array("ordermaster.id" => 'desc'), array(), array(), $like, '10');
		if ($getOrders) {
			foreach ($getOrders->result() as $item) {
				$html[] = array('id' => $item->code, 'text' => $item->code);
			}
		}
		echo  json_encode($html);
	}

	public function getTables()
	{
		$html = array();
		$search = $this->input->get('search');
		$like = array('tablemaster.tableNumber' => $search . '~both');
		$getTables = $this->GlobalModel->selectQuery("tablemaster.code,tablemaster.tableNumber,sectorzonemaster.zoneName", "tablemaster", array('tablemaster.isActive' => 1), array("tablemaster.id" => 'desc'), array('sectorzonemaster' => 'sectorzonemaster.id=tablemaster.zoneCode'), array('sectorzonemaster' => 'inner'), $like, '10');
		if ($getTables) {
			foreach ($getTables->result() as $item) {
				$html[] = array('id' => $item->code, 'text' => $item->zoneName . " / " . $item->tableNumber);
			}
		}
		echo  json_encode($html);
	}

	public function getProductForStock()
	{
		$html = array();
		$search = $this->input->get('search');
		$like = array('inwardlineentries.proNameVarName' => $search . '~both');
		$getProduct = $this->GlobalModel->selectQuery("inwardlineentries.*", "inwardlineentries", array("inwardlineentries" . ".isDelete" => 0, "inwardlineentries" . ".isActive" => 1), array("inwardlineentries.id" => 'desc'), array(), array(), $like, '10');
		if ($getProduct) {
			foreach ($getProduct->result() as $item) {
				$html[] = array('id' => $item->proNameVarName, 'text' => $item->proNameVarName);
			}
		}
		echo  json_encode($html);
	}

	public function getCashier()
	{
		$html = array();
		$search = $this->input->get('search');
		$like = array('usermaster.userName' => $search . '~both');
		$getCashier = $this->GlobalModel->selectQuery("usermaster.code,usermaster.userEmpNo,usermaster.userName", "usermaster", array("usermaster" . ".isDelete" => 0, "usermaster" . ".isActive" => 1, 'usermaster.userRole' => 'R_5'), array("usermaster.id" => 'desc'), array(), array(), $like, '10');
		if ($getCashier) {
			foreach ($getCashier->result() as $item) {
				$html[] = array('id' => $item->code, 'text' => $item->userName . "-" . $item->userEmpNo);
			}
		}
		echo  json_encode($html);
	}

	public function getCustomer()
	{
		$html = array();
		$search = $this->input->get('search');
		$like = array('customer.name' => $search . '~both');
		$getCustomer = $this->GlobalModel->selectQuery("customer.*", "customer", array(), array("customer.id" => 'desc'), array(), array(), $like, '10');
		if ($getCustomer) {
			foreach ($getCustomer->result() as $item) {
				$html[] = array('id' => $item->phone, 'text' => $item->name . "-" . $item->phone);
			}
		}
		echo  json_encode($html);
	}

	public function getTemplate()
	{
		$html = array();
		$search = $this->input->get('search');
		$like = array('smstemplates.template' => $search . '~both');
		$getTemplate = $this->GlobalModel->selectQuery("smstemplates.*", "smstemplates", array(), array("smstemplates.id" => 'desc'), array(), array(), $like, '10');
		if ($getTemplate) {
			foreach ($getTemplate->result() as $item) {
				$html[] = array('id' => $item->template, 'text' => $item->templateName);
			}
		}
		echo  json_encode($html);
	}
	
	public function getCustomerWithEmail()
	{
		$html = array();
		$search = $this->input->get('search');
		$like = array('customer.email' => $search . '~both');
		$getCustomer = $this->GlobalModel->selectQuery("customer.*", "customer", array(), array("customer.id" => 'desc'), array(), array(), $like, '10');
		if ($getCustomer) {
			foreach ($getCustomer->result() as $item) {
				$html[] = array('id' => $item->email, 'text' => $item->email);
			}
		}
		echo  json_encode($html);
	}

	public function getEmailTemplate()
	{
		$html = array();
		$search = $this->input->get('search');
		$like = array('emailtemplates.templateName' => $search . '~both');
		$getTemplate = $this->GlobalModel->selectQuery("emailtemplates.*", "emailtemplates", array(), array("emailtemplates.id" => 'desc'), array(), array(), $like, '10');
		if ($getTemplate) {
			foreach ($getTemplate->result() as $item) {
				$html[] = array('id' => $item->templateName, 'source' => $item->subject, 'message'=>$item->message,'text' => $item->templateName);
			}
		}
		echo  json_encode($html);
	}

	public function getCounterByBranch()
	{
		$html = array();
		$branchCode = $this->input->get("branch");
		$search = $this->input->get('search');
		if ($branchCode != "") {
			$condition = ["countermaster.branchCode" => $branchCode];
			if ($search != "")
				$like = array('countermaster.counterName' => $search . '~both');
			else $like = [];
			$getTemplate = $this->GlobalModel->selectQuery("countermaster.*", "countermaster", $condition, array("countermaster.id" => 'desc'), array(), array(), $like, 10);
			if ($getTemplate) {
				foreach ($getTemplate->result() as $item) {
					$html[] = array('id' => $item->code, 'text' => $item->counterName);
				}
			}
		}
		echo  json_encode($html);
	}

	public function getBatchesByBranch()
	{
		$html = array();
		$branchCode = $this->input->get("branch");
		$search = $this->input->get('search');
		if ($branchCode != "") {
			$condition = ["inwardentries.branchCode" => $branchCode, "inwardentries.isApproved" => "1", "inwardentries.isActive" => 1];
			if ($search != "")
				$like = array('inwardentries.batchNo' => $search . '~both');
			else $like = [];
			$batches = $this->GlobalModel->selectQuery("inwardentries.batchNo", "inwardentries", $condition, array("inwardentries.id" => 'desc'), array(), array(), $like, 10);
			if ($batches) {
				foreach ($batches->result() as $item) {
					$html[] = array('id' => $item->batchNo, 'text' => $item->batchNo);
				}
			}
		}
		echo  json_encode($html);
	}

	public function getProductsBatch()
	{
		$search = $this->input->get('search');
		$branchCode = $this->input->post('branchCode');
		$batchNo = $this->input->post('batchCode');
		$condition = array('inwardlineentries.batchNo' => $batchNo, 'inwardentries.branchCode' => $branchCode);
		$join = array(
			'inwardentries' => 'inwardlineentries.inwardCode=inwardentries.code',
			'productmaster' => 'productmaster.code=inwardlineentries.productCode',
			'unitmaster' => 'unitmaster.code=productmaster.storageUnit',
			'stockinfo' => 'stockinfo.proNameVarName=inwardlineentries.proNameVarName'
		);
		$orderColumns = array("inwardlineentries.productCode,inwardlineentries.expiryDate,inwardlineentries.code,inwardlineentries.code,stockinfo.stock,inwardlineentries.proNameVarName,unitmaster.baseUnitCode,unitmaster.conversionFactor,productmaster.productTaxGrp");
		$joinType = array("productmaster" => 'inner', 'unitmaster' => 'inner', 'stockinfo' => 'inner');
		$orderBy = array('inwardlineentries.id' => 'ASC');
		$like = [];
		if ($search != "") {
			$like = array('inwardlineentries.proNameVarName' => $search . '~both');
		}
		$Records = $this->GlobalModel->selectQuery($orderColumns, 'inwardlineentries', $condition, $orderBy, $join, $joinType, $like, 10);
		$htmlarray = [];
		if ($Records && $Records->num_rows() > 0) {
			foreach ($Records->result() as $gets) {
				$htmlarray[] = [
					"id" => $gets->code,
					"text" => $gets->proNameVarName,
					"stock" => $gets->stock,
					"unit" => $gets->baseUnitCode,
					"taxgroup" => $gets->productTaxGrp,
					"storageconvfactor" => $gets->storageconvfactor
				];
			}
		}
		echo  json_encode($htmlarray);
		exit;
	}
}
