<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Customer extends CI_Controller
{
	var $session_key;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('GlobalModel');
		$this->session_key = $this->session->userdata('key' . SESS_KEY);
		$rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
			redirect('Login', 'refresh');
		}
		$this->rights = $this->GlobalModel->getMenuRights('5.1', $rolecode);
		if ($this->rights == '') {
			$this->load->view('errors/norights.php');
		}
		$res = $this->GlobalModel->checkActiveSubscription();
		if ($res == "EXPIRED") {
			redirect('package', 'refresh');
		}
	}

	public function listRecords()
	{
		if ($this->rights != '' && $this->rights['view'] == 1) {
			$data['insertRights'] = $this->rights['insert'];
			$data['customer'] = $this->GlobalModel->selectActiveData('customer');
			$data['groupdata'] = $this->GlobalModel->selectActiveData('customergroupmaster');
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/customer/customer-list', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function getCustomerList()
	{
		$customer = $this->input->get('customer');
		$customergroup = $this->input->get('customergroup');
		$tableName = "customer";
		$search = $this->input->GET("search")['value'];
		$orderColumns = array("customer.*,customergroupmaster.customerGroupName");
		$condition = array();
		if ($customer != "") {
			$condition['customer.name'] = $customer;
		}
		if ($customergroup != "") {
			$condition['customer.custGroupCode'] = $customergroup;
		}

		$orderBy = array('customer.id' => 'DESC');
		$joinType = array('customergroupmaster' => 'left');
		$join = array('customergroupmaster' => 'customergroupmaster.code=customer.custGroupCode');
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$extraCondition = "";
		$like = array("customer.name" => $search . "~both", "customer.code" => $search . "~both", "customer.email" => $search . "~both", "customer.country" => $search . "~both", "customer.state" => $search . "~both", "customer.city" => $search . "~both", "customer.pincode" => $search . "~both", "customer.phone" => $search . "~both", "customergroupmaster.customerGroupName" => $search . "~both");
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		//echo $this->db->last_query();
		$srno = $_GET['start'] + 1;
		if ($Records) {
			foreach ($Records->result() as $row) {
				$actionHtml = '';
				if ($this->rights != '' && $this->rights['update'] == 1) {
					$actionHtml = '<a id="edit" class="apply_group btn btn-info btn-sm cursor_pointer" data-seq="' . $row->code . '" data-type="1"><i id="edt" title="Apply Customer Group" class="fa fa-pencil"></i></a>';
				}
				$data[] = array(
					$srno,
					$row->code,
					$row->name,
					$row->phone,
					$row->email,
					$row->customerGroupName,
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
	public function updateCustomerGroup()
	{
		$groupcode = $this->input->post("groupcode");
		$custcode = $this->input->post("custcode");

		$data = array(
			'custGroupCode' => $groupcode
		);
		$result = $this->GlobalModel->doEdit($data, 'customer',  $custcode);
		$successMsg = "Customer Group apply Successfully";
		$errorMsg = "Failed To Apply Customer Group";
		if ($result != false) {
			$response['status'] = true;
			$response['message'] = $successMsg;
		} else {
			$response['status'] = false;
			$response['message'] = $errorMsg;
		}
		echo json_encode($response);
	}
}
