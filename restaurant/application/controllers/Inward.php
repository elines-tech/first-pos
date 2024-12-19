<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inward extends CI_Controller
{
	var $session_key;
	protected $rolecode,$branchCode;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form', 'url', 'html');
		$this->load->model('GlobalModel');
		$this->session_key = $this->session->userdata('key' . SESS_KEY);
		if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
			redirect('Login', 'refresh');
		}
		$this->rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		$this->branchCode = $this->session->userdata['logged_in' . $this->session_key]['branchCode'];
		$this->rights = $this->GlobalModel->getMenuRights('3.4',$this->rolecode);
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
			$data['branch'] = $this->GlobalModel->selectActiveData('branchmaster');
			$data['supplier'] = $this->GlobalModel->selectActiveData('suppliermaster');
			$data['branchCode']="";
			$data['branchName']="";
			if($this->branchCode!=""){
				$data['branchCode']=$this->branchCode;
				$data['branchName'] = $this->GlobalModel->selectDataById($this->branchCode, 'branchmaster')->result()[0]->branchName;
			}
			$this->load->view('dashboard/header');		
			$this->load->view('dashboard/inward/list',$data);
			$this->load->view('dashboard/footer');
		}else{
			$this->load->view('errors/norights.php');
		}
	}

	public function add() 
	{
		if($this->rights !='' && $this->rights['insert']==1){
			$data['insertRights'] = $this->rights['insert'];
			$data['supplier'] = $this->GlobalModel->selectActiveData('suppliermaster');
			//$data['branch'] = $this->GlobalModel->selectQuery("distinct branchmaster.code,branchmaster.branchName","branchmaster",array('branchmaster.isActive'=>1),array(),array('inwardentries'=>'inwardentries.branchCode=branchmaster.code'),array('inwardentries'=>'inner'));
			$data['branch'] = $this->GlobalModel->selectActiveData('branchmaster');
			$data['items'] = $this->GlobalModel->selectActiveData('itemmaster');
			$data['unitmaster'] = $this->GlobalModel->selectActiveData('unitmaster');
			
			$data['branchCode']="";
			$data['branchName']="";
			if($this->branchCode!=""){
				$data['branchCode']=$this->branchCode;
				$data['branchName'] = $this->GlobalModel->selectDataById($this->branchCode, 'branchmaster')->result()[0]->branchName;
			}
			$this->load->view('dashboard/commonheader');
			$this->load->view('dashboard/inward/add', $data);
			$this->load->view('dashboard/footer');
		}else{
			$this->load->view('errors/norights.php');
		}
	}

	public function getInwardList()
	{
		$tableName = "inwardentries"; 
		$branch = $this->input->get('branch');
		if($this->branchCode!=""){
			$branch=$this->branchCode;
		}
        $supplier = $this->input->get('supplier');
		$fromDate = $this->input->get('fromDate');
		$toDate = $this->input->get('toDate');
		$search = $this->input->GET("search")['value']; 
		$orderColumns = array("inwardentries.isApproved,inwardentries.code,inwardentries.branchCode,branchmaster.branchName,suppliermaster.supplierName,inwardentries.inwardDate,inwardentries.total, inwardentries.isActive");
		$condition = array();
		if ($branch != "") {
			$condition['inwardentries.branchCode'] = $branch;
		}
		if ($supplier != "") {
			$condition['inwardentries.supplierCode'] = $supplier;
		}
		
		$orderBy = array('inwardentries.id' => 'DESC');
		$joinType = array('branchmaster' => 'inner', 'suppliermaster' => 'inner');
		$join = array('branchmaster' => 'branchmaster.code=inwardentries.branchCode', 'suppliermaster' => 'suppliermaster.code=inwardentries.supplierCode');
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$dateCondition = "";
		if ($fromDate != "") {
			$dateCondition = " AND (inwardentries.inwardDate BETWEEN '" . $fromDate . " 00:00:00' AND '" . $toDate . " 00:00:00')";
		}
		$extraCondition = " (inwardentries.isDelete=0 OR inwardentries.isDelete IS NULL)" . $dateCondition; 
		$like = array("inwardentries.code" => $search . "~both","branchmaster.branchName" => $search . "~both","branchmaster.branchName" => $search . "~both","suppliermaster.supplierName" => $search . "~both","inwardentries.total" => $search . "~both");
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		//echo $this->db->last_query();
		$srno = $_GET['start'] + 1;
		if ($Records) {
			foreach ($Records->result() as $row) {
				$code = $row->code;
				if ($row->isActive == 1) {
					$status = "<span class='badge bg-success'>Active</span>";
				} else {
					$status = "<span class='badge bg-danger'>Inactive</span>";
				}
				if ($row->isApproved == 1) {
                    $appstatus = "<span class='badge bg-success'>Yes</span>";
                } else {
                    $appstatus = "<span class='badge bg-danger'>No</span>";
                }
				$actionHtml ='<div class="d-flex">';
				if($this->rights !='' && $this->rights['view']==1){
					$actionHtml .='<a id="view" href="' . base_url() . 'inward/view/' . $row->code . '" class="btn btn-success btn-sm cursor_pointer m-1"><i id="view" title="View" class="fa fa-eye"></i></a>';
				}
				
				if($row->isApproved==0){
					if($this->rights !='' && $this->rights['update']==1){
						$actionHtml .=' <a id="edit" href="' . base_url() . 'inward/edit/' . $row->code . '" class="btn btn-info btn-sm m-1 cursor_pointer"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
					}
					if($this->rights !='' && $this->rights['delete']==1){
						$actionHtml .= '<a id="delete" class="btn btn-danger btn-sm m-1 cursor_pointer delete_inward" id="'.$row->code.'"><i id="dlt" title="Delete" class="fa fa-trash"></i></a>';
					}
				}else if($row->isApproved==1){
					if($this->rights !='' && $this->rights['update']==1){
						$actionHtml .='<a id="customize" class="btn btn-warning btn-sm m-1 cursor_pointer" href="' . base_url() . 'inward/returns/' . $row->code . '"><i id="dlt" title="Return" class="fa fa-undo"></i></a>';
					}
				}
                $actionHtml .= '</div>';
				$data[] = array(
					$srno,
					$row->code,
					date('d/m/Y', strtotime($row->inwardDate)),
					$row->branchName,
					$row->supplierName,
					$row->total,
					$status,
					$appstatus,
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
			$code = $this->uri->segment(3);
			$data['supplier'] = $this->GlobalModel->selectActiveData('suppliermaster');
			$data['branch'] = $this->GlobalModel->selectActiveData('branchmaster');
			$data['items'] = $this->GlobalModel->selectActiveData('itemmaster');
			$data['unitmaster'] = $this->GlobalModel->selectActiveData('unitmaster');
			$data['inwardData'] = $this->GlobalModel->selectQuery('inwardentries.*', 'inwardentries', array('inwardentries.code' => $code));
			$data['inwardLineEntries'] = $this->GlobalModel->selectQuery('inwardlineentries.*,unitmaster.unitName', 'inwardlineentries', array('inwardlineentries.inwardCode' => $code, 'inwardlineentries.isActive' => 1), array("inwardlineentries.id"=>"ASC"), array('unitmaster' => 'unitmaster.code=inwardlineentries.itemUom'), array('unitmaster' => 'inner'));
			$data['branchCode']="";
			$data['branchName']="";
			if($this->branchCode!=""){
				$data['branchCode']=$this->branchCode;
				$data['branchName'] = $this->GlobalModel->selectDataById($this->branchCode, 'branchmaster')->result()[0]->branchName;
			}
			$this->load->view('dashboard/commonheader');
			$this->load->view('dashboard/inward/edit', $data);
			$this->load->view('dashboard/footer');
		}else{
			$this->load->view('errors/norights.php');
		}
	}
	public function view()
	{
		if($this->rights !='' && $this->rights['view']==1){
			$code = $this->uri->segment(3);
			$data['supplier'] = $this->GlobalModel->selectActiveData('suppliermaster');
			$data['branch'] = $this->GlobalModel->selectActiveData('branchmaster');
			$data['items'] = $this->GlobalModel->selectActiveData('itemmaster');
			$data['unitmaster'] = $this->GlobalModel->selectActiveData('unitmaster');
			$data['inwardData'] = $this->GlobalModel->selectQuery('inwardentries.*', 'inwardentries', array('inwardentries.code' => $code));
			$data['inwardLineEntries'] = $this->GlobalModel->selectQuery('inwardlineentries.*,unitmaster.unitName', 'inwardlineentries', array('inwardlineentries.inwardCode' => $code, "inwardlineentries.isActive" => 1), array(), array('unitmaster' => 'unitmaster.code=inwardlineentries.itemUom'), array('unitmaster' => 'inner'));
			$this->load->view('dashboard/commonheader');
			$this->load->view('dashboard/inward/view', $data);
			$this->load->view('dashboard/footer');
		}else{
			$this->load->view('errors/norights.php');
		}
	}
	
	public function saveInwards()
	{
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$inwardCode = trim($this->input->post("inwardCode"));
		$branchCode = trim($this->input->post("branchCode"));
		if($this->branchCode!=""){
			$branchCode=$this->branchCode;
		}
		$supplierCode = $this->input->post("supplierCode");
		$inwardDate = $this->input->post("inwardDate");
		$total = $this->input->post("total");
		$isActive = $this->input->post("isActive"); 
		$refNo = $this->input->post("refNo");
		$ip = $_SERVER['REMOTE_ADDR'];
		$approve = 0;
		if($this->input->post('approveInwardBtn')==1) {
		   $approve = 1;
		}
		$data = array(
			'branchCode' => $branchCode,
			'supplierCode' => $supplierCode,
			'inwardDate' => $inwardDate,
			'total' => $total,
			//'ref' => $refNo,
			'isActive' => 1,
			'flag' => 'inward',
		);
		$code = $this->GlobalModel->addNew($data, 'inwardentries', 'IN');
		//$code='false';
		$inwardLineCode = $this->input->post("inwardLineCode");
		$itemCode = $this->input->post("itemCode");
		$itemQty = $this->input->post("itemQty");
		$itemUnit = $this->input->post("itemUnit");
		$itemPrice = $this->input->post("itemPrice");
		$subTotal = $this->input->post("subTotal");
		//$branchCode = $this->input->post("branchCode");
		$addResultFlagNew = false;
		if (isset($itemCode)) {
			for ($j = 0; $j < sizeof($itemCode); $j++) {
				if ($itemCode[$j] != '') {
					$subdata = array(
						'inwardCode' => $code,
						'itemCode' => $itemCode[$j],
						'itemQty' => $itemQty[$j],
						'itemUom' => $itemUnit[$j],
						'itemPrice' => $itemPrice[$j],
						'subTotal' => $subTotal[$j],
						'isActive' => 1
					);
					$subdata['addIP'] = $ip;
					$subdata['addID'] = $addID;
					$result = $this->GlobalModel->addNew($subdata, 'inwardlineentries', 'INL');
					if ($result != 'false') {
						 if($approve==1) {
						   $this->GlobalModel->doEdit(array('isApproved'=>1),'inwardentries',$code);
						   $this->addToStock($itemCode[$j], $itemUnit[$j], $itemQty[$j], $branchCode,$itemPrice[$j]);
						}
						$addResultFlagNew = true;
					}
				}
			}
		}	
		if ($code != 'false' || $addResultFlagNew == true) {
			$txt = $code . " - " . $supplierCode . " of " . $branchCode . " having amount " . $total . " inward is added.";
			$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
			$this->GlobalModel->activity_log($activity_text);
			$response['status'] = true;
			$response['inwardCode'] = $code;
			$response['message'] = "Inward Added Successfully.";
		} else {
			$response['status'] = false;
			$response['message'] = "Failed to Add Inward.";
		}
		$this->session->set_flashdata('message', json_encode($response));
        redirect('inward/listRecords', 'refresh');
	}
	
	public function updateInwards() 
	{
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$inwardCode = trim($this->input->post("inwardCode"));
		$branchCode = trim($this->input->post("branchCode"));
		if($this->branchCode!=""){
			$branchCode=$this->branchCode;
		}
		$supplierCode = $this->input->post("supplierCode");
		$inwardDate = $this->input->post("inwardDate");
		$total = $this->input->post("total");
		$isActive = $this->input->post("isActive");
		$refNo = $this->input->post("refNo");
		$ip = $_SERVER['REMOTE_ADDR'];
		$approve = 0;
		if($this->input->post('approveInwardBtn')==1) {
		   $approve = 1;
		}
		$data = array(
			'branchCode' => $branchCode,
			'supplierCode' => $supplierCode,
			'inwardDate' => $inwardDate,
			'total' => $total,
			//'ref' => $refNo,
			'isActive' => 1,
			'flag' => 'inward',
		);
		$data['editID'] = $addID;
		$data['editIP'] = $ip;
		$result = $this->GlobalModel->doEdit($data, 'inwardentries', $inwardCode);
		
		$inwardLineCode = $this->input->post("inwardLineCode");
		$itemCode = $this->input->post("itemCode");
		$itemQty = $this->input->post("itemQty");
		$itemUnit = $this->input->post("itemUnit");
		$itemPrice = $this->input->post("itemPrice");
		$subTotal = $this->input->post("subTotal");
		//$branchCode = $this->input->post("branchCode");
		
		//print_r($inwardLineCode);die;
		
		$addResultFlagNew = false;
		if (isset($itemCode)) {
			for ($j = 0; $j < sizeof($itemCode); $j++) {
				if ($itemCode[$j] != '') {
					$subdata = array(
						'inwardCode' => $inwardCode,
						'itemCode' => $itemCode[$j],
						'itemQty' => $itemQty[$j],
						'itemUom' => $itemUnit[$j],
						'itemPrice' => $itemPrice[$j],
						'subTotal' => $subTotal[$j],
						'isActive' => 1
					);
					if($inwardLineCode[$j]!=""){
					    $subdata['editIP'] = $ip;
					    $subdata['editID'] = $addID;
						$result = $this->GlobalModel->doEdit($subdata, 'inwardlineentries', $inwardLineCode[$j]);
						if ($result == true) {
							 if($approve==1) {
								$this->GlobalModel->doEdit(array('isApproved'=>1),'inwardentries',$inwardCode);
								$this->addToStock($itemCode[$j], $itemUnit[$j], $itemQty[$j], $branchCode,$itemPrice[$j]);
							 }
							$addResultFlagNew = true;
						}
					} else {						
					    $subdata['addIP'] = $ip;
					    $subdata['addID'] = $addID;
						$result = $this->GlobalModel->addNew($subdata, 'inwardlineentries', 'INL');
						if ($result != 'false') {
							 if($approve==1) {
								$this->GlobalModel->doEdit(array('isApproved'=>1),'inwardentries',$inwardCode);
								$this->addToStock($itemCode[$j], $itemUnit[$j], $itemQty[$j], $branchCode,$itemPrice[$j]);
							 }
							$addResultFlagNew = true;
						}
					}
				}
			}
		}
	    if ($result==true || $addResultFlagNew = true) {
			$txt = $inwardCode . " - " . $supplierCode . " of " . $branchCode . " having amount " . $total . " inward is updated.";
			$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
			$this->GlobalModel->activity_log($activity_text);
			$response['status'] = true;
			$response['message'] = "Inward Updated Successfully.";
		} else {
			$response['status'] = false;
			$response['message'] = "Failed to Add Inward.";
		}
		$this->session->set_flashdata('message', json_encode($response));
        redirect('inward/listRecords', 'refresh');
	}
	public function saveInward()
	{
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$inwardCode = trim($this->input->post("inwardCode"));
		$branchCode = trim($this->input->post("branchCode"));
		$supplierCode = $this->input->post("supplierCode");
		$inwardDate = $this->input->post("inwardDate");
		$total = $this->input->post("total");
		$isActive = $this->input->post("isActive");
		$refNo = $this->input->post("refNo");
		$ip = $_SERVER['REMOTE_ADDR'];
		$data = array(
			'branchCode' => $branchCode,
			'supplierCode' => $supplierCode,
			'inwardDate' => $inwardDate,
			'total' => $total,
			//'ref' => $refNo,
			'isActive' => 1,
			'flag' => 'inward',
		);
		if ($inwardCode != '') {
			$data['editID'] = $addID;
			$data['editIP'] = $ip;
			$code = $this->GlobalModel->doEdit($data, 'inwardentries', $inwardCode);
			$code = $inwardCode;
			$successMsg = 'Inward updated Successfully';
			$warningMsg = "Failed to update Inward";
		} else {
			$data['addID'] = $addID;
			$data['addIP'] = $ip;
			$code = $this->GlobalModel->addNew($data, 'inwardentries', 'IN');
			$successMsg = 'Inward Added Successfully';
			$warningMsg = "Failed to Add Inward";
		}

		if ($code != 'false') {

			$txt = $code . " - " . $supplierCode . " of " . $branchCode . " having amount " . $total . " inward is added.";
			$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
			$this->GlobalModel->activity_log($activity_text);
			$response['status'] = true;
			$response['inwardCode'] = $code;
			$response['message'] = $successMsg;
		} else {
			$response['status'] = false;
			$response['message'] = $warningMsg;
		}
		echo json_encode($response);
	}

	public function saveInwardLine()
	{
		$role = ($this->session->userdata['logged_in' . $this->session_key]['role']);
		$addID = ($this->session->userdata['logged_in' . $this->session_key]['code']);
		$ip = $_SERVER['REMOTE_ADDR'];
		$inwardCode = $this->input->post("inwardCode");
		$inwardLineCode = $this->input->post("inwardLineCode");
		$itemCode = $this->input->post("itemCode");
		$itemQty = $this->input->post("itemQty");
		$itemUnit = $this->input->post("itemUnit");
		$itemPrice = $this->input->post("itemPrice");
		$subTotal = $this->input->post("subTotal");
		$branchCode = $this->input->post("branchCode"); 
		$data = array(
			'inwardCode' => $inwardCode,
			'itemCode' => $itemCode,
			'itemQty' => $itemQty,
			'itemUom' => $itemUnit,
			'itemPrice' => $itemPrice,
			'subTotal' => $subTotal,
			'isActive' => 1
		);
		if ($inwardLineCode != '') {
			$data['editIP'] = $ip;
			$data['editID'] = $addID;
			$result = $this->GlobalModel->doEdit($data, 'inwardlineentries', $inwardLineCode);
			if ($result != 'false') {
				$this->addToStock($itemCode, $itemUnit, $itemQty, $branchCode,$itemPrice);
			}
			$result = $inwardLineCode;
		} else {
			$data['addIP'] = $ip;
			$data['addID'] = $addID;
			$result = $this->GlobalModel->addNew($data, 'inwardlineentries', 'INL');
			if ($result != 'false') {

				$this->addToStock($itemCode, $itemUnit, $itemQty, $branchCode,$itemPrice);
			}
		}
	}

	public function deleteInward()
	{
		$code = $this->input->post('code');
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$query = $this->GlobalModel->delete($code, 'inwardentries');
		if ($query) {
			$txt = $code . " inward is deleted.";
			$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
			$this->GlobalModel->activity_log($activity_text);
		}
		echo $query;
	}

	public function deleteInwardLine()
	{
		$date = date('Y-m-d H:i:s');
		$lineCode = $this->input->post('lineCode');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$query = $this->GlobalModel->deleteForever($lineCode, 'inwardlineentries');
		if ($query) {
			$txt = $lineCode . " deleted inward items";
			$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
			$this->GlobalModel->activity_log($activity_text);
			$msg = true;
			echo json_encode($msg); 
		} else {
			$msg = false;
			echo json_encode($msg);
		}
	}

	public function addToStock($itemCode, $unitCode, $stock, $branchCode,$itemPrice)
	{
		//$prevstock=0;
		$checkPreviousStock = $this->GlobalModel->selectQuery('stockinfo.code,ifnull(stockinfo.stock,0) as stock', 'stockinfo', array('stockinfo.itemCode' => $itemCode,'stockinfo.branchCode' => $branchCode, 'stockinfo.unitCode' => $unitCode));
		if ($checkPreviousStock && $checkPreviousStock->num_rows() > 0) {
			$code = $checkPreviousStock->result()[0]->code;
			$prevstock = $checkPreviousStock->result()[0]->stock;
			$newstock = $prevstock + $stock;
			$updateData = array(
				'stock' => $newstock
			);
			$this->GlobalModel->doEdit($updateData, 'stockinfo', $code);
		} else {
			$insertData = array(
				'itemCode' => $itemCode,
				'unitCode' => $unitCode,
				'stock' => $stock,
				'isActive' => 1,
				'branchCode' => $branchCode,
                'itemPrice'=>$itemPrice 				
			);
			$this->GlobalModel->addNew($insertData, 'stockinfo', 'ST');
		}
	}

	public function getItemStorageUnit()
	{
		$itemCode = $this->input->post('itemCode');
		$storageUnit = '';
		$checkItem = $this->GlobalModel->selectQuery('itemmaster.storageUnit,unitmaster.code', 'itemmaster', array('itemmaster.code' => $itemCode), array(), array('unitmaster' => 'unitmaster.code=itemmaster.storageUnit'), array('unitmaster' => 'inner'));
		if ($checkItem && $checkItem->num_rows() > 0) {
			$storageUnit = $checkItem->result_array()[0]['code'];
		}
		echo $storageUnit;
	}
	public function getItems()
	{
		$branchCode = $this->input->post("branchCode");
		$itemHtml = '';
		$items = $this->GlobalModel->selectQuery('itemmaster.*,stockinfo.stock', 'itemmaster', array('itemmaster.isActive' => 1, 'stockinfo.branchCode' => $branchCode), array(), array('stockinfo' => 'stockinfo.itemCode=itemmaster.code'), array('stockinfo' => 'inner'));
		if ($items && $items->num_rows() > 0) {
			$response['status'] = 'true';
			$itemHtml .= '<option value="">Select Item</option>';
			foreach ($items->result() as $prd) {
				$itemHtml .= '<option value="' . $prd->code . '"  data-stock="' . $prd->stock . '">' . $prd->itemEngName . '</option>';
			}
		} else {
			$response['status'] = 'false';
		}
		$response['items'] =  $itemHtml;
		echo json_encode($response);
	}
	
	public function returns()
	{
		if($this->rights !='' && $this->rights['update']==1){
			$code = $this->uri->segment(3);
			$data['supplier'] = $this->GlobalModel->selectActiveData('suppliermaster');
			$data['branch'] = $this->GlobalModel->selectActiveData('branchmaster');
			$data['items'] = $this->GlobalModel->selectActiveData('itemmaster');
			$data['unitmaster'] = $this->GlobalModel->selectActiveData('unitmaster');
			$query=$this->GlobalModel->selectQuery('inwardentries.*', 'inwardentries', array('inwardentries.code' => $code));
			$data['inwardData'] = $query;
			$branchCode=$query->result()[0]->branchCode;
			$data['inwardLineEntries'] = $this->GlobalModel->selectQuery('inwardlineentries.*,unitmaster.unitName,stockinfo.stock', 'inwardlineentries', array('inwardlineentries.inwardCode' => $code,'stockinfo.branchCode'=>$branchCode, "inwardlineentries.isActive" => 1), array(), array('unitmaster' => 'unitmaster.code=inwardlineentries.itemUom','stockinfo'=>'stockinfo.itemCode=inwardlineentries.itemCode and stockinfo.unitCode=inwardlineentries.itemUom'), array('unitmaster' => 'inner','stockinfo'=>'inner'));
			$this->load->view('dashboard/commonheader');
			$this->load->view('dashboard/inward/return', $data);
			$this->load->view('dashboard/footer');
		}else{
			$this->load->view('errors/norights.php');
		}
	}
	
	public function saveReturn(){
		$role = ($this->session->userdata['logged_in' . $this->session_key]['role']);
		$addID = ($this->session->userdata['logged_in' . $this->session_key]['code']);
		$ip = $_SERVER['REMOTE_ADDR'];
		$inwardCode = $this->input->post("inwardCode");
		$branchCode = $this->input->post("branchCode");
		$unitCode = $this->input->post("unitCode");
		$itemCode = $this->input->post("itemCode");
		$returnQty = $this->input->post("returnQty");
		$data = array(
			'inwardCode' => $inwardCode,
			'itemCode' => $itemCode,
			'returnQty' => $returnQty,
			'addID'=>$addID,
			'addIP'=>$ip,
			'isActive' => 1
		);
		$result = $this->GlobalModel->addNew($data, 'returnentries', 'RET');
		if ($result != 'false') {
			$this->deductStock($itemCode, $unitCode, $returnQty, $branchCode);
		}
	}
	
	public function deductStock($itemCode, $unitCode, $returnQty, $branchCode)
	{
		//$prevstock=0;
		$checkPreviousStock = $this->GlobalModel->selectQuery('stockinfo.code,ifnull(stockinfo.stock,0) as stock', 'stockinfo', array('stockinfo.itemCode' => $itemCode,'stockinfo.branchCode' => $branchCode, 'stockinfo.unitCode' => $unitCode));
		if ($checkPreviousStock && $checkPreviousStock->num_rows() > 0) {
			$code = $checkPreviousStock->result()[0]->code;
			$prevstock = $checkPreviousStock->result()[0]->stock;
			$newstock = $prevstock - $returnQty;
			$updateData = array(
				'stock' => $newstock
			);
			$this->GlobalModel->doEdit($updateData, 'stockinfo', $code);
		}
	}
}
