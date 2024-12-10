<?php
class Payments extends CI_Controller
{
	var $session_key;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('GlobalModel');
		$this->load->model('GeneralModel');
		$this->load->library('sendemail');
		$this->session_key = $this->session->userdata('key' . SESS_KEY);
		if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
			redirect('Login', 'refresh');
		}
		$rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		$this->rights = $this->GlobalModel->getMenuRights('3.2', $rolecode);
		if ($this->rights == '') {
			$this->load->view('errors/norights.php');
		}
	}

	public function listRecords()
	{
		if ($this->rights != '' && $this->rights['view'] == 1) {
			$table_name = 'clients';
			$orderColumns = array("DISTINCT(clients.companyname)");
			$data['clients'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, array());
			$table = 'payments';
			$orderColumns2 = array("payments.paymentId,payments.receiptId");
			$data['payment'] = $this->GlobalModel->selectQuery($orderColumns2, $table, array());
			$this->load->view('backend/template/header');
			$this->load->view('backend/template/sidebar');
			$this->load->view('backend/payment/list', $data);
			$this->load->view('backend/template/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function getList()
	{
		$companyname = $this->input->get('companyname');
		$paymentstatus = $this->input->get('paymentstatus');
		$fromDate = $this->input->get('fromDate');
		$toDate = $this->input->get('toDate');
		$paymentid = $this->input->get('paymentid');
		$receiptid = $this->input->get('receiptid');
		$search = $this->input->GET("search")['value'];
		$tableName = 'payments';
		$orderColumns = array("payments.*,clients.companyname,clients.name as clientName,clients.category");
		$condition = array();

		if ($paymentstatus != '') {
			$condition['payments.paymentStatus'] = $paymentstatus;
		}

		if ($companyname != '') {
			$condition['clients.companyname'] = $companyname;
		}

		if ($paymentid != '') {
			$condition['payments.paymentId'] = $paymentid;
		}

		if ($receiptid != '') {
			$condition['payments.receiptId'] = $receiptid;
		}

		$orderBy = array('clients' . '.id' => 'DESC');
		$joinType = array('clients' => 'inner');
		$join = array('clients' => 'clients.code=payments.clientCode');
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$like = array();
		$like = array("payments.paymentDate" => $search . "~both", "payments.amount" => $search . "~both", "payments.paymentStatus" => $search . "~both", "clients.name" => $search . "~both", "clients.companyname" => $search . "~both", "clients.category" => $search . "~both", "clients.email" => $search . "~both", "clients.phone" => $search . "~both");
		$extraCondition = " (clients.isDelete=0 OR clients.isDelete IS NULL)";
		if ($fromDate != '' && $toDate != '') {
			$extraCondition .= " and (payments.paymentDate between '" . $fromDate . " 00:00:00' and '" . $toDate . " 23:59:59')";
		}
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		//echo $this->db->last_query();
		$srno = $offset + 1;
		$data = array();
		$dataCount = 0;
		if ($Records) {
			foreach ($Records->result() as $row) {
				$actionHtml = '';
				if ($this->rights != '' && $this->rights['view'] == 1) {
					$actionHtml .= '<div class="d-flex">';
					$actionHtml .= '<a href="' . base_url() . 'payment/view/' . $row->code . '" class="btn btn-sm btn-success cursor_pointer m-1" ><i id="edt" title="View" class="fa fa-eye" ></i></a>';
					$actionHtml .= '</div>';
				}
				$date = date('d/m/Y h:i A', strtotime($row->paymentDate));
				$data[] = array(
					$srno,
					$row->companyname,
					$row->clientName,
					$row->category,
					$row->amount,
					$date,
					$row->receiptId,
					$row->paymentId,
					$row->paymentStatus,
					$actionHtml
				);
				$srno++;
			}
			$dataCount = sizeof($this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, array(), '', '', '', $extraCondition)->result_array());
		}
		$output = array(
			"draw" => intval($_GET["draw"]),
			"recordsTotal" => $dataCount,
			"recordsFiltered" => $dataCount,
			"data" => $data
		);
		echo json_encode($output);
	}

	public function view($id)
	{
		$code = $id;
		$table_name = 'payments';
		$orderColumns = array("payments.*,clients.companyname,clients.name as clientName,clients.category,subscriptionmaster.title");
		$cond = array('payments.code' => $code);
		$joinType = array('clients' => 'inner', 'subscriptionmaster' => 'inner');
		$join = array('clients' => 'clients.code=payments.clientCode', 'subscriptionmaster' => 'subscriptionmaster.code=payments.subscriptionCode');
		$data['payments'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond, array(), $join, $joinType);
		$this->load->view('backend/template/header');
		$this->load->view('backend/template/sidebar');
		$this->load->view('backend/payment/view', $data);
		$this->load->view('backend/template/footer');
	}

	public function expiryPlan()
	{
		$plancode = $this->input->post("code");
		$data = array(
			'expiryDate' => date('Y-m-d h:i:s'),
			'status' => 'EXPIRED'
		);
		$result = $this->GlobalModel->doEdit($data, 'payments', $plancode);
		if ($result == true) {
			$response['status'] = true;
			$response['message'] = "Expired Plan is updated";
		} else {
			$response['status'] = false;
			$response['message'] = "Fail to update expired plan.";
		}

		echo json_encode($response);
	}

	public function extraDays()
	{
		$plancode = $this->input->post("plancode");
		$noofdays = $this->input->post("noofdays");
		$expireddate = $this->input->post("expireddate");
		$clientcode = $this->input->post("clientcode");
		$dbData = $this->GlobalModel->get_data_by_given_field('companyCode', $clientcode, 'databasemaster');
		$clientDb = $dbData[0]['databaseName'];
		$data = array(
			'planExpanded' => 1,
			'expiryDate' => $expireddate,
			'noOfDays' => $noofdays,
			'expandOn' => date('Y-m-d h:i:s')
		);
		$result = $this->GlobalModel->doEdit($data, 'payments', $plancode);

		$subdata = array('expiryDate' => $expireddate);
		$where = array('mainPaymentCode' => $plancode);

		$resDb = $this->GeneralModel->doUpdate($clientDb, 'subscriptions', $subdata, $where);
		if ($result == true) {
			$response['status'] = true;
			$response['message'] = "Expired Plan is updated";
		} else {
			$response['status'] = false;
			$response['message'] = "Fail to update expired plan.";
		}

		echo json_encode($response);
	}
}
