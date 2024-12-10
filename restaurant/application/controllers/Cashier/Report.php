<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report extends CI_Controller
{
	var $session_key;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('GlobalModel');
		$this->session_key = $this->session->userdata('cash_key' . CASH_SESS_KEY);
		if (!isset($this->session->userdata['cash_logged_in' . $this->session_key]['code'])) {
			redirect(base_url('Cashier/Login'), 'refresh');
		}
		$res = $this->GlobalModel->checkActiveSubscription();
		if ($res == "EXPIRED") {
			$this->load->view('errors/exppackage.php');
		}
	}

	public function getDayClosingReport()
	{
		$data['usermaster'] = $this->GlobalModel->selectQuery('usermaster.code,usermaster.userEmpNo', 'usermaster', array('usermaster.userRole' => 'R_3'));
		$data['branch'] = $this->GlobalModel->selectActiveData('branchmaster');
		$this->load->view('cashier/header');
		$this->load->view('cashier/report/dayClosing', $data);
		$this->load->view('cashier/footer');
	}

	public function getDayClosingList()
	{
		$cashierCode = $this->session->userdata['cash_logged_in' . $this->session_key]['code'];
		$dataCount = 0;
		$data = array();
		$branchCode = $this->input->get('branchCode');
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
		$orderColumns = array("ordermaster.branchCode,ordermaster.addID,ifnull(count(ordermaster.id),0) as totalOrders,ifnull(sum(ordermaster.grandTotal),0) as totalSale,ordermaster.discount,branchmaster.branchName,usermaster.userEmpNo,usermaster.name");
		$condition = array('ordermaster.branchCode' => $branchCode, "ordermaster.addID" => $cashierCode);
		$orderBy = array('ordermaster.id' => 'DESC');
		$joinType = array('branchmaster' => 'inner', 'usermaster' => 'inner');
		$join = array('branchmaster' => 'branchmaster.code=ordermaster.branchCode', 'usermaster' => 'usermaster.code=ordermaster.addID');
		$groupByColumn = array('ordermaster.branchCode', 'ordermaster.addID');
		$extraCondition = '';
		if ($fromDate != '' && $toDate != '') {
			$extraCondition = " (ordermaster.addDate between '" . $fromDate . " 00:00:01' and '" . $toDate . " 23:59:59')";
		}
		$like = array("branchmaster.branchName" => $search . "~both");
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		if ($Records) {
			foreach ($Records->result() as $row) {
				$sum = 0;
				$sum = $sum + $row->discount;
				$offerCount = 0;
				if ($row->discount > 0) {
					$offerCount = $offerCount + 1;
				}
				$cashPayment = $cardPayment = $upiPayment = $netbankingpayment = 0;
				$orderColumns1 = array("ordermaster.paymentMode,ordermaster.grandTotal");
				$condition1 = array("ordermaster.branchCode" => $row->branchCode, "ordermaster.addID" => $row->addID, "ordermaster.counter" => $row->counter);
				$paymentModeQuery = $this->GlobalModel->selectQuery($orderColumns1, 'ordermaster', $condition1, [], [], [], [], "", "", [], $extraCondition);
				if ($paymentModeQuery) {
					foreach ($paymentModeQuery->result() as $pm) {
						if ($pm->paymentMode == 'cash') {
							$cashPayment = $cashPayment + $pm->grandTotal;
						} else if ($pm->paymentMode == 'card') {
							$cardPayment = $cardPayment + $pm->grandTotal;
						} elseif ($pm->paymentMode == 'upi') {
							$upiPayment = $upiPayment + $pm->grandTotal;
						} elseif ($pm->paymentMode == 'netbanking') {
							$netbankingpayment = $netbankingpayment + $pm->grandTotal;
						}
					}
				}
				$data[] = array(
					$srno,
					$row->branchName,
					$row->name . '-' . $row->userEmpNo,
					$row->totalOrders,
					$row->totalSale,
					$cashPayment,
					$cardPayment,
					$upiPayment,
					$netbankingpayment,
					$offerCount,
					$sum
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
}
