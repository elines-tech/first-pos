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
		$this->load->library('QrCodeGenerator');
		$this->session_key = $this->session->userdata('key' . SESS_KEY);
		if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
			redirect('Login', 'refresh');
		}
		$res = $this->GlobalModel->checkActiveSubscription();
		if ($res == "EXPIRED") {
			redirect('package', 'refresh');
		}
		$dbName = $this->session->userdata['current_db' . $this->session_key];
		$this->db->query('use ' . $dbName);
		$this->rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		$this->branchCode = $this->session->userdata['logged_in' . $this->session_key]['userBranch'];
		$this->rights = $this->GlobalModel->getMenuRights('3.2', $this->rolecode);
		if ($this->rights == '') {
			$this->load->view('errors/norights.php');
		}
	}

	public function listRecords()
	{
		if ($this->rights != '' && $this->rights['view'] == 1) { 
			 
			$data['insertRights'] = $this->rights['insert'];
			$data['branchCode']="";
			$data['branchName']="";
			if($this->branchCode!=""){
				$data['branchCode']=$this->branchCode;
				$data['branchName'] = $this->GlobalModel->selectDataById($this->branchCode, 'branchmaster')->result()[0]->branchName;
			}
			$data['branch'] = $this->GlobalModel->selectActiveData('branchmaster');
			$data['supplier'] = $this->GlobalModel->selectActiveData('suppliermaster');
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/inward/list', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function add()
	{
		if ($this->rights != '' && $this->rights['insert'] == 1) {
			$data['branchCode']="";
			$data['branchName']="";
			if($this->branchCode!=""){
				$data['branchCode']=$this->branchCode;
				$data['branchName'] = $this->GlobalModel->selectDataById($this->branchCode, 'branchmaster')->result()[0]->branchName;
			}
			$extraCondition = " (productvariants.isDelete=0 OR productvariants.isDelete IS NULL)";
			$data['items'] = $this->GlobalModel->selectQuery('productmaster.*,productvariants.variantName,productvariants.code as variantCode,productvariants.sku as variantSKU', 'productmaster', array("productmaster.isActive" => 1), array(), array('productvariants' => 'productvariants.productCode=productmaster.code'), array('productvariants' => 'left'), array(), '', '', array(), $extraCondition);
			$data['unitmaster'] = $this->GlobalModel->selectActiveData('unitmaster');
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/inward/add', $data);
			$this->load->view('dashboard/footer');  
		} else {
			$this->load->view('errors/norights.php');  
		}
	}

	public function getInwardList()
	{
		$frombranchCode = $this->input->get('frombranchCode');
		if($this->branchCode!=""){
			$frombranchCode=$this->branchCode;
		}
		$tobranchCode = $this->input->get('tobranchCode');
		$fromDate = $this->input->get('fromDate');
		$toDate = $this->input->get('toDate');
		$supplierCode = $this->input->get('supplierCode');
		$tableName = "inwardentries";
		$search = $this->input->GET("search")['value'];
		$orderColumns = array("inwardentries.code,inwardentries.batchNo,inwardentries.branchCode,branchmaster.branchName,suppliermaster.supplierName,inwardentries.inwardDate,inwardentries.total, inwardentries.isActive,inwardentries.isApproved");
		$condition = array('inwardentries.branchCode' => $frombranchCode, 'inwardentries.supplierCode' => $tobranchCode, "inwardentries.isActive" => 1);
		$orderBy = array('inwardentries.id' => 'DESC');
		$joinType = array('branchmaster' => 'inner', 'suppliermaster' => 'inner');
		$join = array('branchmaster' => 'branchmaster.code=inwardentries.branchCode', 'suppliermaster' => 'suppliermaster.code=inwardentries.supplierCode');
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$extraCondition = " (inwardentries.isDelete=0 OR inwardentries.isDelete IS NULL)";
		if ($fromDate != '' && $toDate != '') {
			$extraCondition .= " and (inwardentries.inwardDate between '" . $fromDate . " 00:00:00' and '" . $toDate . " 00:00:00')";
		}
		$like = array("branchmaster.branchName" => $search . "~both", "inwardentries.code" => $search . "~both", "inwardentries.batchNo" => $search . "~both", "suppliermaster.supplierName" => $search . "~both", "inwardentries.total" => $search . "~both");
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		$srno = $_GET['start'] + 1;
		if ($Records) {
			foreach ($Records->result() as $row) {
				$code = $row->code;
				if ($row->isApproved == 1) {
					$appstatus = "<span class='badge bg-success'>Yes</span>";
				} else {
					$appstatus = "<span class='badge bg-danger'>No</span>";
				}
				$actionHtml = '<div class="d-flex">';
				if ($this->rights != '' && $this->rights['view'] == 1) {
					$actionHtml .= '<a id="view" href="' . base_url() . 'inward/view/' . $row->code . '" class="btn btn-success btn-sm cursor_pointer m-1"><i id="view" title="View" class="fa fa-eye"></i></a>';
				}
				if ($row->isApproved == 0) {
					if ($this->rights != '' && $this->rights['update'] == 1) {
						$actionHtml .= '<a id="edit" href="' . base_url() . 'inward/edit/' . $row->code . '" class="btn btn-info btn-sm m-1 cursor_pointer"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
					}
					if ($this->rights != '' && $this->rights['delete'] == 1) {
						$actionHtml .= '<a id="delete" class="btn btn-danger btn-sm m-1 cursor_pointer delete_inward" id="' . $row->code . '"><i id="dlt" title="Delete" class="fa fa-trash"></i></a>';
					}
				} else if ($row->isApproved == 1) {
					if ($this->rights != '' && $this->rights['update'] == 1) {
						$actionHtml .= '<a id="customize" class="btn btn-warning btn-sm m-1 cursor_pointer" href="' . base_url() . 'inward/returns/' . $row->code . '"><i id="dlt" title="Return" class="fa fa-undo"></i></a>';
					}
				}
				$actionHtml .= '</div>';
				$data[] = array(
					$srno,
					$row->batchNo,
					date('d/m/Y', strtotime($row->inwardDate)),
					$row->branchName,
					$row->supplierName,
					$row->total,
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
		if ($this->rights != '' && $this->rights['update'] == 1) {
			$code = $this->uri->segment(3);
			$data['branchCode']="";
			$data['branchName']="";
			if($this->branchCode!=""){
				$data['branchCode']=$this->branchCode;
				$data['branchName'] = $this->GlobalModel->selectDataById($this->branchCode, 'branchmaster')->result()[0]->branchName;
			}
			$data['supplier'] = $this->GlobalModel->selectActiveData('suppliermaster');
			$data['branch'] = $this->GlobalModel->selectActiveData('branchmaster');
			$extraCondition = " (productvariants.isDelete=0 OR productvariants.isDelete IS NULL)";
			$data['items'] = $this->GlobalModel->selectQuery('productmaster.*,productvariants.variantName,productvariants.code as variantCode,productvariants.sku as variantSKU', 'productmaster', array("productmaster.isActive" => 1), array(), array('productvariants' => 'productvariants.productCode=productmaster.code'), array('productvariants' => 'left'), array(), '', '', array(), $extraCondition);
			$data['unitmaster'] = $this->GlobalModel->selectActiveData('unitmaster');
			$data['inwardData'] = $this->GlobalModel->selectQuery('inwardentries.*', 'inwardentries', array('inwardentries.code' => $code));
			$data['inwardLineEntries'] = $this->GlobalModel->selectQuery('inwardlineentries.*,unitmaster.unitName', 'inwardlineentries', array('inwardlineentries.inwardCode' => $code, 'inwardlineentries.isActive' => 1), array(), array('unitmaster' => 'unitmaster.code=inwardlineentries.productUnit'), array('unitmaster' => 'inner'));
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/inward/edit', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}
	public function view()
	{
		if ($this->rights != '' && $this->rights['view'] == 1) {
			$code = $this->uri->segment(3);
			$data['supplier'] = $this->GlobalModel->selectActiveData('suppliermaster');
			$data['branch'] = $this->GlobalModel->selectActiveData('branchmaster');
			$extraCondition = " (productvariants.isDelete=0 OR productvariants.isDelete IS NULL)";
			$data['items'] = $this->GlobalModel->selectQuery('productmaster.*,productvariants.variantName,productvariants.code as variantCode,productvariants.sku as variantSKU', 'productmaster', array("productmaster.isActive" => 1), array(), array('productvariants' => 'productvariants.productCode=productmaster.code'), array('productvariants' => 'left'), array(), '', '', array(), $extraCondition);
			$data['unitmaster'] = $this->GlobalModel->selectActiveData('unitmaster');
			$data['inwardData'] = $this->GlobalModel->selectQuery('inwardentries.*', 'inwardentries', array('inwardentries.code' => $code));
			$data['inwardLineEntries'] = $this->GlobalModel->selectQuery('inwardlineentries.*,unitmaster.unitName', 'inwardlineentries', array('inwardlineentries.inwardCode' => $code, 'inwardlineentries.isActive' => 1), array(), array('unitmaster' => 'unitmaster.code=inwardlineentries.productUnit'), array('unitmaster' => 'inner'));
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/inward/view', $data);
			$this->load->view('dashboard/footer');
		} else {
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
		$batchNo = trim($this->input->post("batchNo"));
		$supplierCode = $this->input->post("supplierCode");
		$inwardDate = $this->input->post("inwardDate");
		$total = $this->input->post("total");
		$isActive = $this->input->post("isActive");
		$refNo = $this->input->post("refNo");
		$ip = $_SERVER['REMOTE_ADDR'];
		$code = "true";
		$data = array(
			'batchNo' => $batchNo,
			'branchCode' => $branchCode,
			'supplierCode' => $supplierCode,
			'inwardDate' => $inwardDate,
			'total' => $total,
			'ref' => $refNo,
			'isActive' => 1,
			'flag' => 'inward',
		);
		$code = $this->GlobalModel->addNew($data, 'inwardentries', 'IN');

		$itemCode = $this->input->post("itemCode");
		$itemQty = $this->input->post("itemQty");
		$itemUnit = $this->input->post("itemUnit");
		$itemPrice = $this->input->post("itemPrice");
		$subTotal = $this->input->post("subTotal");
		$tax = $this->input->post("itemTax");
		$expiryDate = $this->input->post("expiryDate");
		$variantCode = $this->input->post("variantCode");
		$productCode = $this->input->post("productCode");
		$sku = $this->input->post("sku");
		$approve = 0;
		if ($this->input->post('approveInwardBtn') == 1) {
			$approve = 1;
		}
		$addResultFlagNew = false;
		if (isset($itemCode)) {
			for ($j = 0; $j < sizeof($itemCode); $j++) {
				if ($itemCode[$j] != '') {
					$subdata = array(
						'inwardCode' => $code,
						'batchNo' => $batchNo,
						'productCode' => $productCode[$j],
						'variantCode' => $variantCode[$j],
						'proNameVarName' => $itemCode[$j],
						'productQty' => $itemQty[$j],
						'productUnit' => $itemUnit[$j],
						'productPrice' => $itemPrice[$j],
						'tax' => $tax[$j],
						'sku' => $sku[$j],
						'subTotal' => $subTotal[$j],
						'isActive' => 1
					);
					if ($expiryDate[$j] != "" && $expiryDate[$j] != null) {
						$subdata['expiryDate'] = $expiryDate[$j];
					}
					$subdata['addIP'] = $ip;
					$subdata['addID'] = $addID;
				}
				$result = $this->GlobalModel->addNew($subdata, 'inwardlineentries', 'INL');
				if ($result != 'false') {
					if ($approve == 1) {
						$this->GlobalModel->doEdit(array('isApproved' => 1), 'inwardentries', $code);
						$this->addToStock($productCode[$j], $variantCode[$j], $itemCode[$j], $itemUnit[$j], $itemQty[$j], $branchCode, $batchNo, $sku[$j]);
					}
					$addResultFlagNew = true;
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
		$batchNo = trim($this->input->post("batchNo"));
		$supplierCode = $this->input->post("supplierCode");
		$inwardDate = $this->input->post("inwardDate");
		$total = $this->input->post("total");
		$isActive = $this->input->post("isActive");
		$refNo = $this->input->post("refNo");
		$approve = 0;
		if ($this->input->post('approveInwardBtn') == 1) {
			$approve = 1;
		}
		$ip = $_SERVER['REMOTE_ADDR'];
		$data = array(
			'batchNo' => $batchNo,
			'branchCode' => $branchCode,
			'supplierCode' => $supplierCode,
			'inwardDate' => $inwardDate,
			'total' => $total,
			'ref' => $refNo,
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
		$tax = $this->input->post("itemTax");
		$expiryDate = $this->input->post("expiryDate");
		$variantCode = $this->input->post("variantCode");
		$productCode = $this->input->post("productCode");
		$sku = $this->input->post("sku");
		$addResultFlagNew = false;
		if (isset($itemCode)) {

			for ($j = 0; $j < sizeof($itemCode); $j++) {
				if ($itemCode[$j] != '') {
					$subdata = array(
						'inwardCode' => $inwardCode,
						'batchNo' => $batchNo,
						'productCode' => $productCode[$j],
						'variantCode' => $variantCode[$j],
						'proNameVarName' => $itemCode[$j],
						'productQty' => $itemQty[$j],
						'productUnit' => $itemUnit[$j],
						'productPrice' => $itemPrice[$j],
						'tax' => $tax[$j],
						'sku' => $sku[$j],						
						'subTotal' => $subTotal[$j],
						'isActive' => 1
					);
					if ($expiryDate[$j] != "" && $expiryDate[$j] != null) {
						$subdata['expiryDate'] = $expiryDate[$j];
					}
					if ($inwardLineCode[$j] != "") {
						$subdata['editIP'] = $ip;
						$subdata['editID'] = $addID;
						$result = $this->GlobalModel->doEdit($subdata, 'inwardlineentries', $inwardLineCode[$j]);
						if ($result == true) {
							if ($approve == 1) {
								$this->GlobalModel->doEdit(array('isApproved' => 1), 'inwardentries', $inwardCode);
								$this->addToStock($productCode[$j], $variantCode[$j], $itemCode[$j], $itemUnit[$j], $itemQty[$j], $branchCode, $batchNo, $sku[$j]);
							}
							$addResultFlagNew = true;
						}
					} else {
						$subdata['addIP'] = $ip;
						$subdata['addID'] = $addID;
						$result = $this->GlobalModel->addNew($subdata, 'inwardlineentries', 'INL');
						if ($result != 'false') {
							if ($approve == 1) {
								$this->GlobalModel->doEdit(array('isApproved' => 1), 'inwardentries', $inwardCode);
								$this->addToStock($productCode[$j], $variantCode[$j], $itemCode[$j], $itemUnit[$j], $itemQty[$j], $branchCode, $batchNo, $sku[$j]);
							}
							$addResultFlagNew = true;
						}
					}
				}
			}
		}

		if ($result == true || $addResultFlagNew = true) {
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
		$batchNo = trim($this->input->post("batchNo"));
		$supplierCode = $this->input->post("supplierCode");
		$inwardDate = $this->input->post("inwardDate");
		$total = $this->input->post("total");
		$isActive = $this->input->post("isActive");
		$refNo = $this->input->post("refNo");
		$ip = $_SERVER['REMOTE_ADDR'];
		$data = array(
			'batchNo' => $batchNo,
			'branchCode' => $branchCode,
			'supplierCode' => $supplierCode,
			'inwardDate' => $inwardDate,
			'total' => $total,
			'ref' => $refNo,
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
			$response['batchNo'] = $batchNo;
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
		$batchNo = $this->input->post("batchNo");
		$inwardLineCode = $this->input->post("inwardLineCode");
		$itemCode = $this->input->post("itemCode");
		$itemQty = $this->input->post("itemQty");
		$itemUnit = $this->input->post("itemUnit");
		$itemPrice = $this->input->post("itemPrice");
		$subTotal = $this->input->post("subTotal");
		$tax = $this->input->post("itemTax");
		$expiryDate = $this->input->post("expiryDate");
		$variantCode = $this->input->post("variantCode");
		$productCode = $this->input->post("productCode");
		$sku = $this->input->post("sku");
		$approve = $this->input->post("approve");
		$branchCode = $this->input->post("branchCode");
		$data = array(
			'inwardCode' => $inwardCode,
			'batchNo' => $batchNo,
			'productCode' => $productCode,
			'variantCode' => $variantCode,
			'proNameVarName' => $itemCode,
			'productQty' => $itemQty,
			'productUnit' => $itemUnit,
			'productPrice' => $itemPrice,
			'tax' => $tax,
			'sku' => $sku,
			'expiryDate' => $expiryDate,
			'subTotal' => $subTotal,
			'isActive' => 1
		);
		if ($inwardLineCode != '') {
			$data['editIP'] = $ip;
			$data['editID'] = $addID;
			$result = $this->GlobalModel->doEdit($data, 'inwardlineentries', $inwardLineCode);
			$result = $inwardLineCode;
		} else {
			$data['addIP'] = $ip;
			$data['addID'] = $addID;
			$result = $this->GlobalModel->addNew($data, 'inwardlineentries', 'INL');
		}
		if ($approve == '1') {
			$this->GlobalModel->doEdit(array('isApproved' => 1), 'inwardentries', $inwardCode);
			$this->addToStock($productCode, $variantCode, $itemCode, $itemUnit, $itemQty, $branchCode, $batchNo, $sku);
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

	public function addToStock($productCode, $variantCode, $itemCode, $unitCode, $stock, $branchCode, $batchNo, $sku)
	{
		//$prevstock=0;
		$checkPreviousStock = $this->GlobalModel->selectQuery('stockinfo.code,ifnull(stockinfo.stock,0) as stock', 'stockinfo', array('stockinfo.productCode' => $productCode, 'stockinfo.variantCode' => $variantCode, 'stockinfo.branchCode' => $branchCode, 'stockinfo.unitCode' => $unitCode, 'stockinfo.batchNo' => $batchNo));
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
				//'qrCode' => $qrCode,
				//'qrCodeImage' => $qrCodeImage,
				'productCode' => $productCode,
				'variantCode' => $variantCode,
				'proNameVarName' => $itemCode,
				'unitCode' => $unitCode,
				'stock' => $stock,
				'sku' => $sku,
				'isActive' => 1,
				'branchCode' => $branchCode,
				'batchNo' => $batchNo,
			);
			$this->GlobalModel->addNew($insertData, 'stockinfo', 'ST');
		}
	}

	public function getItemStorageUnit()
	{
		$itemCode = $this->input->post('itemCode');
		/*$qrCode = $this->GlobalModel->randomCharacters(16);
		$checkCode = $this->GlobalModel->selectQuery('inwardlineentries.qrCode','inwardlineentries',array('inwardlineentries.qrCode'=>$qrCode));
		if($checkCode && $checkCode->num_rows()>0){
			$qrCode = $this->GlobalModel->randomCharacters(16);
		}*/
		$storageUnit = '';
		//$qrImageGenerateUrl = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$qrCode%2F&choe=UTF-8";
		$checkItem = $this->GlobalModel->selectQuery('productmaster.storageUnit,unitmaster.code,unitmaster.unitName', 'productmaster', array('productmaster.code' => $itemCode), array(), array('unitmaster' => 'unitmaster.code=productmaster.storageUnit'), array('unitmaster' => 'inner'));
		if ($checkItem && $checkItem->num_rows() > 0) {
			$storageUnit = $checkItem->result_array()[0]['code'];
			$storageUnitName = $checkItem->result_array()[0]['unitName'];
		}
		//$response['qrCode'] = $qrCode;
		$response['storageUnit'] = $storageUnit;
		$response['storageUnitName'] = $storageUnitName;
		//$response['qrCodeImage'] = $qrImageGenerateUrl;
		echo json_encode($response);
	}

	public function returns()
	{
		if ($this->rights != '' && $this->rights['update'] == 1) {
			$code = $this->uri->segment(3);
			$data['supplier'] = $this->GlobalModel->selectActiveData('suppliermaster');
			$data['branch'] = $this->GlobalModel->selectActiveData('branchmaster');
			$data['items'] = $this->GlobalModel->selectQuery('productmaster.*,productvariants.variantName,productvariants.code as variantCode,productvariants.sku as variantSKU', 'productmaster', array("productmaster.isActive" => 1), array(), array('productvariants' => 'productvariants.productCode=productmaster.code'), array('productvariants' => 'left'));
			$data['unitmaster'] = $this->GlobalModel->selectQuery('unitmaster.code,unitmaster.unitName', 'unitmaster');
			$query = $this->GlobalModel->selectQuery('inwardentries.*', 'inwardentries', array('inwardentries.code' => $code));
			$data['inwardData'] = $query;
			$batchNo = $query->result()[0]->batchNo;
			$branchCode = $query->result()[0]->branchCode;
			//$data['inwardLineEntries'] = $this->GlobalModel->selectQuery('inwardlineentries.*,unitmaster.unitName,stockinfo.stock', 'inwardlineentries', array('inwardlineentries.inwardCode' => $code,'stockinfo.branchCode'=>$branchCode,"stockinfo.batchNo"=>$batchNo, "inwardlineentries.isActive" => 1), array(), array('unitmaster' => 'unitmaster.code=inwardlineentries.productUnit','stockinfo'=>'stockinfo.productCode=inwardlineentries.productCode and stockinfo.unitCode=inwardlineentries.productUnit and stockinfo.variantCode=inwardlineentries.variantCode'), array('unitmaster' => 'inner','stockinfo'=>'inner')); 
			$data['inwardLineEntries'] = $this->GlobalModel->selectQuery('inwardlineentries.*,unitmaster.unitName,stockinfo.stock', 'inwardlineentries', array('inwardlineentries.inwardCode' => $code, 'stockinfo.batchNo' => $batchNo, 'inwardlineentries.isActive' => 1), array(), array('unitmaster' => 'unitmaster.code=inwardlineentries.productUnit', 'stockinfo' => 'stockinfo.proNameVarName=inwardlineentries.proNameVarName'), array('unitmaster' => 'inner', 'stockinfo' => 'inner'));
			//echo $this->db->last_query();
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/inward/return', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function saveReturn()
	{
		$role = ($this->session->userdata['logged_in' . $this->session_key]['role']);
		$addID = ($this->session->userdata['logged_in' . $this->session_key]['code']);
		$ip = $_SERVER['REMOTE_ADDR'];
		$inwardCode = $this->input->post("inwardCode");
		$batchNo = $this->input->post("batchNo");
		$branchCode = $this->input->post("branchCode");
		$unitCode = $this->input->post("unitCode");
		$proNameVarName = $this->input->post("proNameVarName");
		$productCode = $this->input->post("productCode");
		$variantCode = $this->input->post("variantCode");
		$returnQty = $this->input->post("returnQty");
		$data = array(
			'inwardCode' => $inwardCode,
			'productCode' => $productCode,
			'variantCode' => $variantCode,
			'proNameVarName' => $proNameVarName,
			'returnQty' => $returnQty,
			'addID' => $addID,
			'addIP' => $ip,
			'isActive' => 1
		);
		$result = $this->GlobalModel->addNew($data, 'returnentries', 'RET');
		if ($result != 'false') {
			$this->deductStock($productCode, $variantCode, $itemCode, $unitCode, $returnQty, $branchCode, $batchNo);
		}
	}

	public function deductStock($productCode, $variantCode, $itemCode, $unitCode, $returnQty, $branchCode, $batchNo)
	{
		$checkPreviousStock = $this->GlobalModel->selectQuery('stockinfo.code,ifnull(stockinfo.stock,0) as stock', 'stockinfo', array('stockinfo.productCode' => $productCode, 'stockinfo.variantCode' => $variantCode, 'stockinfo.branchCode' => $branchCode, 'stockinfo.unitCode' => $unitCode, 'stockinfo.batchNo' => $batchNo));
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
