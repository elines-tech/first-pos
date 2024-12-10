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
		$like = array('productcategorymaster.categoryName' => $search . '~both');
		$getCategory = $this->GlobalModel->selectQuery("productcategorymaster.*", "productcategorymaster", array("productcategorymaster" . ".isDelete" => 0, "productcategorymaster" . ".isActive" => 1), array("productcategorymaster.id" => 'desc'), array(), array(), $like, '10');
		if ($getCategory) {
			foreach ($getCategory->result() as $item) {
				$html[] = array('id' => $item->code, 'text' => $item->categoryName);
			}
		}
		echo  json_encode($html);
	}

	public function getProductSubcategory()
	{
		$html = array();
		$search = $this->input->get('search');
		$categoryCode = $this->input->get('categoryCode');
		$like = array('productsubcategorymaster.subcategoryName' => $search . '~both');
		$getSubCategory = $this->GlobalModel->selectQuery("productsubcategorymaster.*", "productsubcategorymaster", array("productsubcategorymaster" . ".isDelete" => 0, "productsubcategorymaster" . ".isActive" => 1, "productsubcategorymaster.categoryCode" => $categoryCode), array("productsubcategorymaster.id" => 'desc'), array(), array(), $like, '10');
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

	public function getItem()
	{
		$html = array();
		$search = $this->input->get('search');
		$like = array('itemmaster.itemEngName' => $search . '~both');
		$getItem = $this->GlobalModel->selectQuery("itemmaster.*,unitmaster.unitName as storageUnitName", "itemmaster", array("itemmaster" . ".isDelete" => 0, "itemmaster" . ".isActive" => 1), array("itemmaster.id" => 'desc'), array('unitmaster' => 'unitmaster.code=itemmaster.storageUnit'), array('unitmaster' => 'inner'), $like, '10');
		if ($getItem) {
			foreach ($getItem->result() as $item) {
				$html[] = array('id' => $item->code, 'text' => $item->itemEngName . "-" . $item->storageUnitName);
			}
		}
		echo  json_encode($html);
	}


	public function getItemInward()
	{
		$html = array();
		$search = $this->input->get('search');
		$like = array('itemmaster.itemEngName' => $search . '~both');
		$getItem = $this->GlobalModel->selectQuery("itemmaster.*,unitmaster.unitName as storageUnitName", "itemmaster", array("itemmaster" . ".isDelete" => 0, "itemmaster" . ".isActive" => 1), array("itemmaster.id" => 'desc'), array('unitmaster' => 'unitmaster.code=itemmaster.storageUnit'), array('unitmaster' => 'inner'), $like, '10');
		if ($getItem) {
			foreach ($getItem->result() as $item) {
				$html[] = array('id' => $item->code, 'text' => $item->itemEngName);
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

	public function getEmailTemplate()
	{
		$html = array();
		$search = $this->input->get('search');
		$like = array('emailtemplates.templateName' => $search . '~both');
		$getTemplate = $this->GlobalModel->selectQuery("emailtemplates.*", "emailtemplates", array(), array("emailtemplates.id" => 'desc'), array(), array(), $like, '10');
		if ($getTemplate) {
			foreach ($getTemplate->result() as $item) {
				$html[] = array('id' => $item->templateName, 'source' => $item->subject, 'message' => $item->message, 'text' => $item->templateName);
			}
		}
		echo  json_encode($html);
	}

	public function getItemFromBranch()
	{
		$fromBranchCode = $this->input->get("branchCode");
		$html = array();
		$search = $this->input->get('search');
		$like = array('itemmaster.itemEngName' => $search . '~both');
		$items = $this->GlobalModel->selectQuery('DISTINCT(itemmaster.itemEngName),itemmaster.code,stockinfo.stock,stockinfo.itemPrice as price', 'itemmaster', array('itemmaster.isActive' => 1, 'stockinfo.branchCode' => $fromBranchCode, 'inwardentries.isApproved' => 1), array(), array('stockinfo' => 'stockinfo.itemCode=itemmaster.code', 'inwardentries' => 'stockinfo.branchCode=inwardentries.branchCode'), array('stockinfo' => 'inner', 'inwardentries' => 'inner'), $like, '10');

		if ($items) {
			foreach ($items->result() as $item) {
				$html[] = array('id' => $item->code, 'stock' => $item->stock, 'price' => $item->price, 'text' => $item->itemEngName);
			}
		}
		echo  json_encode($html);
	}

	public function getBranchesForZone()
	{
		$html = array();
		$search = $this->input->get('search');
		$like = array('branchmaster.branchName' => $search . '~both');
		$records = $this->GlobalModel->selectQuery("branchmaster.*", "branchmaster", array("branchmaster.isDelete!=" => 1), array("branchmaster.id" => 'desc'), array(), array(), $like, '10');
		if ($records) {
			foreach ($records->result() as $item) {
				$html[] = array('id' => $item->code, 'text' => $item->branchName);
			}
		}
		echo  json_encode($html);
	}

	public function getZoneByBranch()
	{
		$html = array();
		$search = $this->input->get('search');
		$branch = $this->input->get('branch');
		$condition = ['sectorzonemaster.branchCode' => $branch, "sectorzonemaster.isDelete!=" => 1];
		$like = array('sectorzonemaster.zoneName' => $search . '~both');
		$records = $this->GlobalModel->selectQuery("sectorzonemaster.*", "sectorzonemaster", $condition, array("sectorzonemaster.id" => 'desc'), array(), array(), $like, '10');
		if ($records) {
			foreach ($records->result() as $item) {
				$html[] = array('id' => $item->id, 'text' => $item->zoneName);
			}
		}
		echo  json_encode($html);
	}

	public function getTableBySector()
	{
		$html = array();
		$search = $this->input->get('search');
		$sector = $this->input->get('sector');
		$condition = ['tablemaster.zoneCode' => $sector];
		$like = array('tablemaster.tableNumber' => $search . '~both');
		$records = $this->GlobalModel->selectQuery("tablemaster.*", "tablemaster", $condition, array("tablemaster.id" => 'desc'), array(), array(), $like, '10');
		if ($records) {
			foreach ($records->result() as $item) {
				$html[] = array('id' => $item->code, 'text' => $item->tableNumber);
			}
		}
		echo  json_encode($html);
	}
}
