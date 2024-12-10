<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Quotation extends CI_Controller
{
	var $session_key;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form', 'url', 'html');
		$this->load->model('GlobalModel');
		$this->session_key = $this->session->userdata('key' . SESS_KEY);
		$rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
			redirect('Login', 'refresh');
		}
		$this->rights = $this->GlobalModel->getMenuRights('6.3',$rolecode);
		if($this->rights ==''){
			$this->load->view('errors/norights.php');
		}
		$res = $this->GlobalModel->checkActiveSubscription();
        if ($res == "EXPIRED") {
            redirect('package', 'refresh');
        }
	}

	public function listRecords()
	{
		if($this->rights !='' && $this->rights['view']==1){
			$data['insertRights'] = $this->rights['insert'];
			$this->load->view('dashboard/header');		
			$this->load->view('dashboard/quotation/list',$data);
			$this->load->view('dashboard/footer');
		}else{
			$this->load->view('errors/norights.php');
		}
	}

	public function add()
	{
		if($this->rights !='' && $this->rights['insert']==1){
			$data['insertRights'] = $this->rights['insert'];
			//$data['category'] = $this->GlobalModel->selectActiveData('productcategorymaster');
			//$data['subcategory'] = $this->GlobalModel->selectActiveData('productsubcategorymaster');
			$data['product'] = $this->GlobalModel->selectActiveData('productmaster');
			$this->load->view('dashboard/commonheader');
			$this->load->view('dashboard/quotation/add', $data);
			$this->load->view('dashboard/footer');
		}else{
			$this->load->view('errors/norights.php');
		}
	}

	public function getquotationList()
	{
		$remark = $this->input->get('remark');
		$tableName = "quotationentries";
		$search = $this->input->GET("search")['value'];
		$orderColumns = array("quotationentries.remark,quotationentries.remarkDate,quotationentries.code,quotationentries.eventName,quotationentries.date,quotationentries.peoples,quotationentries.subTotal,quotationentries.discount, quotationentries.isActive,quotationentries.discount,quotationentries.taxAmount,quotationentries.grandTotal");
		$condition = array();
		if ($remark  != "") {
			$condition['quotationentries.remark'] = $remark;
		}
		$orderBy = array('quotationentries.id' => 'DESC');
		$joinType = array();
		$join = array();
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$extraCondition = " quotationentries.isDelete=0 OR quotationentries.isDelete IS NULL";
		$like = array("quotationentries.eventName" => $search . "~both","quotationentries.remark" => $search . "~both","quotationentries.code" => $search . "~both","quotationentries.peoples" => $search . "~both","quotationentries.remark" => $search . "~both");
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		$srno = $_GET['start'] + 1;
		if ($Records) {
			foreach ($Records->result() as $row) {
				$code = $row->code;
				$date = "";
				if ($row->isActive == 1) {
					$status = "<span class='badge bg-success'>Active</span>";
				} else {
					$status = "<span class='badge bg-danger'>Inactive</span>";
				}
				if ($row->remarkDate != "") {
					$date = date('d/m/Y', strtotime($row->remarkDate));
				} else {
					$date = "";
				}
				 $actionHtml='<div class="d-flex">';
				if($this->rights !='' && $this->rights['view']==1){
					$actionHtml .='<a href="' . base_url() . 'quotation/view/' . $row->code . '" class="btn btn-success btn-sm cursor_pointer m-1"><i id="view" title="View" class="fa fa-eye"></i></a>';
				}
				if($this->rights !='' && $this->rights['update']==1){
					$actionHtml .='<a href="' . base_url() . 'quotation/edit/' . $row->code . '" class="btn btn-info btn-sm cursor_pointer m-1"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
				}
				if($this->rights !='' && $this->rights['delete']==1){
					$actionHtml .='<a class="btn btn-danger btn-sm cursor_pointer delete_quotation m-1" data-seq="' . $row->code . '"><i id="dlt" title="Delete" class="fa fa-trash"></i></a></div>';
				}

				$data[] = array(
					$srno,
					$row->code,
					date('d/m/Y', strtotime($row->date)),
					$row->eventName,
					$row->peoples,
					$row->remark . "<br>" . $date,
					$actionHtml
				);
				$srno++;
			}
			$dataCount = sizeof($this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, array(), '', '', '', $extraCondition)->result());
			$output = array(
				"draw"			  =>     intval($_GET["draw"]),
				"recordsTotal"    =>      $dataCount,
				"recordsFiltered" =>     $dataCount,
				"data"            =>     $data
			);
			echo json_encode($output);
		} else {
			$dataCount = 0;
			$data = array();
			$output = array(
				"draw"			  =>     intval($_GET["draw"]),
				"recordsTotal"    =>     $dataCount,
				"recordsFiltered" =>     $dataCount,
				"data"            =>     $data
			);
			echo json_encode($output);
		}
	}

	public function edit()
	{
		if($this->rights !='' && $this->rights['update']==1){
			$data['updateRights'] = $this->rights['update'];
			$code = $this->uri->segment(3);
			$data['category'] = $this->GlobalModel->selectActiveData('productcategorymaster');
			$data['subcategory'] = $this->GlobalModel->selectActiveData('productsubcategorymaster');
			$data['quotationData'] = $this->GlobalModel->selectQuery('quotationentries.*', 'quotationentries', array('quotationentries.code' => $code));
			$joinType = array('productmaster' => 'inner');
            $join = array('productmaster' => 'productmaster.code=quotationlineentries.productCode');
			$data['quotationLineEntries'] = $this->GlobalModel->selectQuery('quotationlineentries.*,productmaster.productEngName', 'quotationlineentries', array('quotationlineentries.quotationCode' => $code, 'quotationlineentries.isActive' => 1),array(),$join, $joinType);
			$this->load->view('dashboard/commonheader');
			$this->load->view('dashboard/quotation/edit', $data);
			$this->load->view('dashboard/footer');
		}else{
			$this->load->view('errors/norights.php');
		}
	}
	public function view()
	{
		if($this->rights !='' && $this->rights['view']==1){
			$code = $this->uri->segment(3);
			$data['category'] = $this->GlobalModel->selectActiveData('productcategorymaster');
			$data['subcategory'] = $this->GlobalModel->selectActiveData('productsubcategorymaster');
			$data['quotationData'] = $this->GlobalModel->selectQuery('quotationentries.*', 'quotationentries', array('quotationentries.code' => $code));
			$joinType = array('productmaster' => 'inner');
            $join = array('productmaster' => 'productmaster.code=quotationlineentries.productCode');
			$data['quotationLineEntries'] = $this->GlobalModel->selectQuery('quotationlineentries.*,productmaster.productEngName', 'quotationlineentries', array('quotationlineentries.quotationCode' => $code, 'quotationlineentries.isActive' => 1),array(),$join, $joinType);
			$this->load->view('dashboard/commonheader');
			$this->load->view('dashboard/quotation/view', $data);
			$this->load->view('dashboard/footer');
		}
		else{
			$this->load->view('errors/norights.php');
		}
	}
	public function savequotation()
	{
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$quotationCode = trim($this->input->post("quotationCode"));
		$eventName = trim($this->input->post("eventName"));
		$eventDate = $this->input->post("date");
		$people = $this->input->post("people");
		$subTotal = $this->input->post("subTotal");
		$discount = $this->input->post("discount");
		$taxAmount = $this->input->post("taxAmount");
		$tax = $this->input->post("totalTax"); 
		$grandTotal = $this->input->post("grandTotal");
		$remark = $this->input->post("remark");
		$remarkDate = $this->input->post("remarkDate");
		$isActive = $this->input->post("isActive");
		$ip = $_SERVER['REMOTE_ADDR'];
		$data = array(
			'eventName' => $eventName,
			'date' => $eventDate,
			'peoples' => $people,
			'subTotal' => $subTotal,
			'discount' => $discount,
			'taxAmount' => $taxAmount,
			'totalTax'=>$tax,
			'grandTotal' => $grandTotal,
			'isActive' => 1,
		);
		if ($quotationCode != '') {
			$data['remark'] = $remark;
			$data['remarkDate'] = $remarkDate;
			$data['editID'] = $addID;
			$data['editIP'] = $ip;
			$code = $this->GlobalModel->doEdit($data, 'quotationentries', $quotationCode);
			$code = $quotationCode;
			$successMsg = 'Quotation updated Successfully';
			$warningMsg = "Failed to update quotation";
		} else {
			$data['addID'] = $addID;
			$data['addIP'] = $ip;
			$code = $this->GlobalModel->addNew($data, 'quotationentries', 'QUO');
			$successMsg = 'Quotation Added Successfully';
			$warningMsg = "Failed to Add quotation";
		}

		if ($code != 'false') {
			$txt = $code . " quotation is added.";
			$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
			$this->GlobalModel->activity_log($activity_text);
			$response['status'] = true;
			$response['quotationCode'] = $code;
			$response['message'] = $successMsg;
		} else {
			$response['status'] = false;
			$response['message'] = $warningMsg;
		}
		echo json_encode($response);
	}

	public function savequotationLine()
	{
		$role = ($this->session->userdata['logged_in' . $this->session_key]['role']);
		$addID = ($this->session->userdata['logged_in' . $this->session_key]['code']);
		$ip = $_SERVER['REMOTE_ADDR'];
		$quotationCode = $this->input->post("quotationCode");
		$quotationLineCode = $this->input->post("quotationLineCode");
		$categoryCode = $this->input->post("categoryCode");
		$subCategoryCode = $this->input->post("subCategoryCode");
		$productCode = $this->input->post("productCode");
		$qtyPerPerson = $this->input->post("qtyPerPerson");
		$pricePerPerson = $this->input->post("pricePerPerson");
		$subTotal = $this->input->post("subTotal");
		$perProductTaxAmount=$this->input->post("perProductTaxAmount");
		$perProductTax=$this->input->post("perProductTax");
		$data = array(
			'quotationCode' => $quotationCode,
			'categoryCode' => $categoryCode,
			'subCategoryCode' => $subCategoryCode,
			'productCode' => $productCode,
			'qtyPerPerson' => $qtyPerPerson,
			'pricePerPerson' => $pricePerPerson,
			'tax'=>$perProductTax,
			'taxamount'=>$perProductTaxAmount,
			'subTotal' => $subTotal,
			'isActive' => 1
		);
		if ($quotationLineCode != '') {
			$data['editIP'] = $ip;
			$data['editID'] = $addID;
			$result = $this->GlobalModel->doEdit($data, 'quotationlineentries', $quotationLineCode);
			$result = $quotationLineCode;
		} else {
			$data['addIP'] = $ip;
			$data['addID'] = $addID;
			$result = $this->GlobalModel->addNew($data, 'quotationlineentries', 'INL');
		}
	}

	public function deletequotation()
	{
		$code = $this->input->post('code');
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$query = $this->GlobalModel->delete($code, 'quotationentries');
		if ($query) {
			$txt = $code . " quotation is deleted.";
			$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
			$this->GlobalModel->activity_log($activity_text);
		}
		echo $query;
	}

	public function deletequotationLine()
	{
		$date = date('Y-m-d H:i:s');
		$lineCode = $this->input->post('lineCode');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$people=$this->input->post('people');
		$getDetails = $this->GlobalModel->selectQuery('quotationlineentries.*', 'quotationlineentries', array('quotationlineentries.code' => $lineCode));
		if ($getDetails  && $getDetails->num_rows() > 0) {
			$code = $getDetails->result()[0]->quotationCode;
			$tax = $getDetails->result()[0]->tax;
			$taxAmount = $getDetails->result()[0]->taxamount;
			$subTotal=$getDetails->result()[0]->subTotal*$people;
			$getQut = $this->GlobalModel->selectQuery('quotationentries.*', 'quotationentries', array('quotationentries.code' => $code));
		    if ($getQut && $getQut->num_rows() > 0) {
				$finalTax=$getQut->result()[0]->totalTax-$tax;
				$finaltaxAmount=$getQut->result()[0]->taxAmount-$taxAmount;
				$finalsubTotal=$getQut->result()[0]->subTotal-$subTotal;
				$finalGrandTotal=$getQut->result()[0]->grandTotal-$subTotal-$taxAmount;
				//echo $finalTax;
				$subdata=["subTotal"=>$finalsubTotal,
				       "totalTax"=>$finalTax,
					   "taxAmount"=>$finaltaxAmount,
					   "grandTotal"=>$finalGrandTotal
					   ];
				$this->GlobalModel->doEdit($subdata, 'quotationentries', $code);
			}
		}
		$query = $this->GlobalModel->delete($lineCode, 'quotationlineentries');
		//$query=true;
		if ($query) {
			$txt = $lineCode . " deleted quotation items";
			$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
			$this->GlobalModel->activity_log($activity_text);
			$msg = true;
			echo json_encode($msg);
		} else {
			$msg = false;
			echo json_encode($msg);
		}
	}

	public function getProductByCategory()
	{
		$categoryCode = $this->input->post("categoryCode");
		$subCategoryCode = $this->input->post("subCategoryCode");
		$productCode = $this->input->post("productCode");
		$productHtml = '';
		$response['status'] = 'false';
		$products = $this->GlobalModel->selectQuery('productmaster.code,productmaster.productEngName', 'productmaster', array('productmaster.isActive' => 1, 'productmaster.productCategory' => $categoryCode, 'productmaster.subcategory' => $subCategoryCode));
		if ($products && $products->num_rows() > 0) {
			$response['status'] = 'true';
			$productHtml .= '<option value="">Select</option>';
			foreach ($products->result() as $prd) {
				if ($prd->code == $productCode) {
					$productHtml .= '<option value="' . $prd->code . '" selected>' . $prd->productEngName . '</option>';
				} else {
					$productHtml .= '<option value="' . $prd->code . '">' . $prd->productEngName . '</option>';
				}
			}
		}
		$response['products'] =  $productHtml;
		echo json_encode($response);
	}

    public function getProduct()
	{
		$productHtml = '';
		$response['status'] = 'false';
		$productCode = $this->input->post("productCode");
		$products = $this->GlobalModel->selectQuery('productmaster.code,productmaster.productEngName', 'productmaster', array('productmaster.isActive' => 1));
		if ($products && $products->num_rows() > 0) {
			$response['status'] = 'true';
			$productHtml .= '<option value="">Select</option>';
			foreach ($products->result() as $prd) {
				$productHtml .= '<option value="' . $prd->code . '">' . $prd->productEngName . '</option>';
			}
		}
		$response['products'] =  $productHtml;
		echo json_encode($response);
	}

	public function getTaxAmount()
	{
		$taxAmount = 0;
		$tax=0;
		$productCodes = $this->input->post("productCodes");
		$productCodes = implode("','", $productCodes);
		$discount = $this->input->post("discount");
		$subTotal = $this->input->post("subTotal");
		$discountAmount = $subTotal - $discount;
		$joinType = array('taxgroupmaster' => 'inner');
		$join = array('taxgroupmaster' => 'taxgroupmaster.code=productmaster.productTaxGrp');
		$extraCondition = " productmaster.code in ('" . $productCodes . "')";
		$prdDetails = $this->GlobalModel->selectQuery('productmaster.productTaxGrp,taxgroupmaster.taxes', 'productmaster', array('productmaster.isActive' => 1, 'taxgroupmaster.isActive' => 1), array('productmaster.id' => 'ASC'), $join, $joinType, array(), "", "", array(), $extraCondition);
		if ($prdDetails) {
			foreach ($prdDetails->result() as $pt) {
				$taxes = json_decode($pt->taxes, true);
				$getTaxes = $this->GlobalModel->plainQuery("select taxes.taxPer from taxes where taxes.isActive=1 and taxes.code in('" . implode("','", $taxes) . "')");
				if ($getTaxes) {
					foreach ($getTaxes->result() as $t) {
						$tax=$tax+$t->taxPer;
						$taxAmount = $taxAmount + ($discountAmount * $t->taxPer / 100);
					}
				}
			}
		}
		echo $taxAmount;
	}
	
	public function getProductTaxAmount(){  
		$taxAmount = 0;
		$tax=0;
		$productCode = $this->input->post("productCode");
		$subTotal = $this->input->post("subTotal");
		$joinType = array('taxgroupmaster' => 'inner');
		$join = array('taxgroupmaster' => 'taxgroupmaster.code=productmaster.productTaxGrp');
	    $prdDetails = $this->GlobalModel->selectQuery('productmaster.productTaxGrp,taxgroupmaster.taxes', 'productmaster', array('productmaster.isActive' => 1, 'taxgroupmaster.isActive' => 1,"productmaster.code"=>$productCode), array('productmaster.id' => 'ASC'), $join, $joinType, array(), "", "", array());
		if ($prdDetails) {
			foreach ($prdDetails->result() as $pt) {
				$taxes = json_decode($pt->taxes, true);
				$getTaxes = $this->GlobalModel->plainQuery("select taxes.taxPer from taxes where taxes.isActive=1 and taxes.code in('" . implode("','", $taxes) . "')");
				if ($getTaxes) {
					foreach ($getTaxes->result() as $t) {
						$tax=$tax+$t->taxPer;
						$taxAmount = $taxAmount + ((int)$subTotal * (int)$t->taxPer / 100);
					}
				}
			}
		}
		$response['tax'] =  $tax;
		$response['taxAmount']=$taxAmount;
		echo json_encode($response);
	}
}
