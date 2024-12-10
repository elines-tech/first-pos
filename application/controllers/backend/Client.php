<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Client extends CI_Controller
{
	var $session_key;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('GlobalModel');
		$this->load->library('sendemail');
		$this->session_key = $this->session->userdata('key' . SESS_KEY);
		if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
			redirect('Login', 'refresh');
		}
		$rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		$this->rights = $this->GlobalModel->getMenuRights('3.1', $rolecode);
		if ($this->rights == '') {
			$this->load->view('errors/norights.php');
		}
	}

	public function listRecords()
	{
		if ($this->rights != '' && $this->rights['view'] == 1) {
			$table_name = 'clients';
			$orderColumns = "DISTINCT clients.name,clients.phone";
			$data['subscribers'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, array("isActive" => 1));
			$this->load->view('backend/template/header');
			$this->load->view('backend/template/sidebar');
			$this->load->view('backend/client/list', $data);
			$this->load->view('backend/template/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function getList()
	{
		$category = $this->input->get('category');
		$status = $this->input->get('status');
		$fromDate = $this->input->get('fromDate');
		$toDate = $this->input->get('toDate');
		$name = $this->input->get('name');
		$phone = $this->input->get('phone');
		$search = $this->input->GET("search")['value'];
		$tableName = 'clients';
		$orderColumns = array("clients.*");
		$condition = array();

		if ($category != '') {
			$condition['clients.category'] = $category;
		}

		if ($name != '') {
			$condition['clients.name'] = $name;
		}

		if ($phone != '') {
			$condition['clients.phone'] = $phone;
		}

		if ($status == "expired") {
			$condition['payments.expiryDate<'] = date('Y-m-d H:i:s');
		} else if ($status == "active") {
			$condition['payments.expiryDate>'] = date('Y-m-d H:i:s');
		}

		$orderBy = array('clients' . '.id' => 'DESC');
		$joinType = array('payments' => 'inner');
		$join = array('payments' => 'payments.clientCode=clients.code');
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$like = array();
		$like = array("clients.companyname" => $search . "~both", "clients.category" => $search . "~both", "clients.email" => $search . "~both", "clients.phone" => $search . "~both");
		$extraCondition = " (clients.isDelete=0 OR clients.isDelete IS NULL)";
		if ($fromDate != '' && $toDate != '') {
			$extraCondition .= " and (clients.registerDate between '" . $fromDate . " 00:00:00' and '" . $toDate . " 23:59:59')";
		}
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		$srno = $offset + 1;
		$data = array();
		$dataCount = 0;
		if ($Records) {
			foreach ($Records->result() as $row) {
				$actionHtml = '';
				if ($this->rights != '' && $this->rights['view'] == 1) {
					$actionHtml .= '<div class="d-flex">';
					$actionHtml .= '<a href="' . base_url() . 'client/view/' . $row->code . '" class="btn btn-sm btn-success cursor_pointer m-1" ><i id="edt" title="View" class="fa fa-eye" ></i></a>';
					$actionHtml .= '<a style="cursor:pointer" class="apply_sms btn btn-sm btn-primary cursor_pointer m-1"  data-seq="' . $row->code . '"  data-phone="' . $row->phone . '"><i class="fa fa-commenting-o" aria-hidden="true"></i></a>';
					$actionHtml .= '<a style="cursor:pointer" class="apply_email btn btn-sm btn-primary cursor_pointer m-1"  data-seq="' . $row->code . '"  data-email="' . $row->email . '"><i class="fa fa-envelope-o" aria-hidden="true"></i></a>';
					$actionHtml .= '</div>';
				}
				$date = date('d/m/Y h:i A', strtotime($row->registerDate));
				$data[] = array(
					$srno,
					$row->cmpcode,
					$row->companyname,
					$row->name,
					$row->email,
					$row->phone,
					$date,
					$row->category,
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

	public function resview($id)
	{
		$code = $id;
		$table_name = 'clients';
		$orderColumns = array("clients.*");
		$cond = array('clients.code' => $code);
		$data['clients'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);
		$this->load->view('backend/template/header');
		$this->load->view('backend/template/sidebar');
		$this->load->view('backend/client/view', $data);
		$this->load->view('backend/template/footer');
	}

	public function sendEmail()
	{
		$email = $this->input->post("email");
		$message = $this->input->post("message");
		$subject = $this->input->post("subject");
		$mail = $this->sendemail->sendMailOnly($email, $subject, $message);

		if ($mail['success'] == 'true') {
			$response['status'] = true;
			$response['message'] = "Mail send successfully.";
		} else {
			$response['status'] = false;
			$response['message'] = "Fail to send email.";
		}

		echo json_encode($response);
	}

	public function sendSms()
	{
		$phone = $this->input->post("phone");
		$message = $this->input->post("message");

		//$mail = $this->sendemail->sendMailOnly($email, $subject, $message);
		$sms = "true";
		if ($sms == 'true') {
			$response['status'] = true;
			$response['message'] = "SMS send successfully.";
		} else {
			$response['status'] = false;
			$response['message'] = "Fail to send sms.";
		}

		echo json_encode($response);
	}
}
