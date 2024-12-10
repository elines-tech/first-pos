<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report extends CI_Controller
{
	var $session_key;
	protected $rolecode,$branchCode; 
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper('form', 'url', 'html');
		$this->load->model('GlobalModel');
		$this->session_key = $this->session->userdata('key' . SESS_KEY);
		if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
			redirect('Login', 'refresh');
		}
		$res = $this->GlobalModel->checkActiveSubscription();
		if ($res == "EXPIRED") {
			redirect('package', 'refresh');
		}
		$this->rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		$this->branchCode = $this->session->userdata['logged_in' . $this->session_key]['userBranch'];
		$this->rights = $this->GlobalModel->getMenuRights('6.2', $this->rolecode);
		if ($this->rights == '') {
			$this->load->view('errors/norights.php');
		}
	}

	public function getDayClosingReport()
	{
		if ($this->rights != '' && $this->rights['view'] == 1) {
			$data['usermaster'] = $this->GlobalModel->selectQuery('usermaster.code,usermaster.userEmpNo', 'usermaster', array('usermaster.userRole' => 'R_5'));
			$data['branch'] = $this->GlobalModel->selectActiveData('branchmaster');
			$data['branchCode']="";
			$data['branchName']="";
			if($this->branchCode!=""){
				$data['branchCode']=$this->branchCode;
				$data['branchName'] = $this->GlobalModel->selectDataById($this->branchCode, 'branchmaster')->result()[0]->branchName;
			}
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/report/dayClosing', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function getDayClosingList()
	{
		$dataCount = 0;
		$data = array();
		$branchCode = $this->input->get('branchCode');
		if($this->branchCode!=""){
			$branchCode=$this->branchCode;
		}
		$cashierCode = $this->input->get('cashierCode');
		$fromDate = $this->input->get('fromDate');
		$toDate = $this->input->get('toDate');
		$export = $this->input->get('export');
		$search = $limit = $offset = '';
		$srno = 1;
		$draw = 0;
		if ($export == 0) {
			$search = $this->input->GET("search")['value'];
			$limit = $this->input->GET("length");
			$offset = $this->input->GET("start");
			$srno = $_GET['start'] + 1;
			$draw = $_GET["draw"];
		}
		$tableName = "ordermaster";
		$orderColumns = array("ordermaster.branchCode,ordermaster.addID,ordermaster.counter,ifnull(count(ordermaster.id),0) as totalOrders,ifnull(sum(ordermaster.totalPayable),0) as totalSale,ifnull(count(offerType),0) as offerApplied,ifnull(sum(offerDiscount),0) as offerDiscount,branchmaster.branchName,usermaster.userEmpNo,usermaster.name");
		$condition = array('ordermaster.branchCode' => $branchCode, "ordermaster.addID" => $cashierCode);
		$orderBy = array('ordermaster.id' => 'DESC');
		$joinType = array('branchmaster' => 'inner', 'usermaster' => 'inner');
		$join = array('branchmaster' => 'branchmaster.code=ordermaster.branchCode', 'usermaster' => 'usermaster.code=ordermaster.addID');
		$groupByColumn = array('ordermaster.branchCode', 'ordermaster.addID', 'ordermaster.counter');
		$extraCondition = '';
		if ($fromDate != '' && $toDate != '') {
			$extraCondition = " (ordermaster.orderDate between '" . $fromDate . " 00:00:01' and '" . $toDate . " 23:59:59')";
		}
		$like = array("branchmaster.branchName" => $search . "~both","usermaster.name" => $search . "~both","usermaster.name" => $search . "~both","usermaster.userEmpNo" => $search . "~both","ordermaster.counter" => $search . "~both","ordermaster.offerDiscount" => $search . "~both");  
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		
		if ($Records) {
			foreach ($Records->result() as $row) {
				$cashPayment = $cardPayment = $upiPayment = $netbankingpayment = 0;
				$orderColumns1 = array("ordermaster.paymentMode,ordermaster.totalPayable");
				$condition1 = array("ordermaster.branchCode" => $row->branchCode, "ordermaster.addID" => $row->addID, "ordermaster.counter" => $row->counter);
				$paymentModeQuery = $this->GlobalModel->selectQuery($orderColumns1, 'ordermaster', $condition1, [], [], [], [], "", "", [], $extraCondition);
				if ($paymentModeQuery) {
					foreach ($paymentModeQuery->result() as $pm) {
						if ($pm->paymentMode == 'cash') {
							$cashPayment = $cashPayment + $pm->totalPayable;
						} else if ($pm->paymentMode == 'card') {
							$cardPayment = $cardPayment + $pm->totalPayable;
						} elseif ($pm->paymentMode == 'upi') {
							$upiPayment = $upiPayment + $pm->totalPayable;
						} elseif ($pm->paymentMode == 'netbanking') {
							$netbankingpayment = $netbankingpayment + $pm->totalPayable;
						}
					}
				}
				$data[] = array(
					$srno,
					$row->branchName,
					$row->name . '-' . $row->userEmpNo,
					$row->counter,
					$row->totalOrders,
					$row->totalSale,
					$cashPayment,
					$cardPayment,
					$upiPayment,
					$netbankingpayment,
					$row->offerApplied,
					$row->offerDiscount
				);

				$srno++;
			}
			$dataCount = sizeof($this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, array(), '', '', $groupByColumn, $extraCondition)->result());
		}
		$output = array(
			"draw"              =>     intval($draw),
			"recordsTotal"    =>     $dataCount,
			"recordsFiltered" =>     $dataCount,
			"data"            =>     $data
		);
		echo json_encode($output);
	}
	public function getPurchaseList()
	{
		$dataCount = 0;
		$data = array();
		$branchCode = $this->input->get('branchCode');
		$export = $this->input->get('export');
		$search = $limit = $offset = '';
		$srno = 1;
		$draw = 0;
		if ($export == 0) {
			$search = $this->input->GET("search")['value'];
			$limit = $this->input->GET("length");
			$offset = $this->input->GET("start");
			$srno = $_GET['start'] + 1;
			$draw = $_GET["draw"];
		}
		$tableName = "inwardentries";
		$orderColumns = array("inwardentries.code,inwardentries.branchCode,branchmaster.branchName,suppliermaster.supplierName,inwardentries.inwardDate,inwardentries.total, inwardentries.isActive");
		$condition = array('inwardentries.branchCode' => $branchCode, "inwardentries.isActive" => 1);
		$orderBy = array('inwardentries.id' => 'DESC');
		$joinType = array('branchmaster' => 'inner', 'suppliermaster' => 'inner');
		$join = array('branchmaster' => 'branchmaster.code=inwardentries.branchCode', 'suppliermaster' => 'suppliermaster.code=inwardentries.supplierCode');
		$groupByColumn = array();
		$extraCondition = " inwardentries.isDelete=0 OR inwardentries.isDelete IS NULL";
		$like = array("branchmaster.branchName" => $search . "~both","inwardentries.code" => $search . "~both","suppliermaster.supplierName" => $search . "~both","inwardentries.total" => $search . "~both");
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		if ($Records) {
			foreach ($Records->result() as $row) {
				$actionHtml = '<a href="' . base_url() . 'inward/view/' . $row->code . '" class="btn btn-success btn-sm cursor_pointer m-1"><i id="view" title="View" class="fa fa-eye"></i></a>';
				if ($export == 0) {
					$data[] = array($srno, $row->code, date('d/m/Y', strtotime($row->inwardDate)), $row->branchName, $row->supplierName, $row->total, $actionHtml);
				} else {
					$data[] = array($srno, $row->code, date('d/m/Y', strtotime($row->inwardDate)), $row->branchName, $row->supplierName, $row->total);
				}
				$srno++;
			}
			$dataCount = sizeof($this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, array(), '', '', '', $extraCondition)->result());
		}
		$output = array(
			"draw"			  =>     intval($draw),
			"recordsTotal"    =>      $dataCount,
			"recordsFiltered" =>     $dataCount,
			"data"            =>     $data
		);
		echo json_encode($output);
	}

	public function getsaleList()
	{
		$branchCode = $this->input->get('branchCode');
		$orderCode = $this->input->get('orderCode');
		$export = $this->input->get('export');
		$dataCount = 0;
		$data = array();
		$search = $limit = $offset = '';
		$srno = 1;
		$draw = 0;
		if ($export == 0) {
			$search = $this->input->GET("search")['value'];
			$limit = $this->input->GET("length");
			$offset = $this->input->GET("start");
			$srno = $_GET['start'] + 1;
			$draw = $_GET["draw"];
		}
		$tableName = "ordermaster";
		$orderColumns = array("ordermaster.*,branchmaster.branchName,tablemaster.tableNumber");
		$condition = array('ordermaster.code' => $orderCode, 'ordermaster.branchCode' => $branchCode);
		$orderBy = array('ordermaster.id' => 'DESC');
		$joinType = array('branchmaster' => 'inner', 'tablemaster' => 'inner');
		$join = array('branchmaster' => 'branchmaster.code=ordermaster.branchCode', 'tablemaster' => 'tablemaster.code=ordermaster.tableNumber');
		$groupByColumn = array();
		$extraCondition = " (ordermaster.isDelete=0 OR ordermaster.isDelete IS NULL)";
		$like = array("ordermaster.code" => $search . "~both");
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);

		if ($Records) {
			foreach ($Records->result() as $row) {
				$actionHtml = '<a href="' . base_url() . 'orderList/view/' . $row->code . '" class="btn btn-success btn-sm cursor_pointer"><i id="view" title="View" class="fa fa-eye"></i></a>';
				if ($export == 0) {
					$data[] = array($srno, date('d/m/Y h:i A', strtotime($row->addDate)), $row->code, $row->branchName, $row->tableNumber . ' / ' . $row->tableSection, $row->custName . ' - ' . $row->custPhone, "<span class='badge bg-success'>" . $row->paymentMode . "</span>", $row->grandTotal, $actionHtml);
				} else {
					$data[] = array($srno, date('d/m/Y h:i A', strtotime($row->addDate)), $row->code, $row->branchName, $row->tableNumber . ' / ' . $row->tableSection, $row->custName . ' - ' . $row->custPhone, $row->paymentMode, $row->grandTotal);
				}
				$srno++;
			}
			$dataCount = sizeof($this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, array(), '', '', '', $extraCondition)->result());
		}
		$output = array(
			"draw"			  =>     intval($draw),
			"recordsTotal"    =>      $dataCount,
			"recordsFiltered" =>     $dataCount,
			"data"            =>     $data
		);
		echo json_encode($output);     
	}
}
