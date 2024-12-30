<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Coupon extends CI_Controller
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
		$this->session_key = $this->session->userdata('key' . SESS_KEY);
		if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
			redirect('Login', 'refresh');
		}
		$this->rights = $this->GlobalModel->getMenuRights('10.2', $rolecode);
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
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/coupon/list', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function add()
	{
		if ($this->rights != '' && $this->rights['insert'] == 1) {
			$data['insertRights'] = $this->rights['insert'];
			$this->load->view('dashboard/commonheader');
			$this->load->view('dashboard/coupon/add', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function edit()
	{
		if ($this->rights != '' && $this->rights['update'] == 1) {
			$data['updateRights'] = $this->rights['update'];
			$code = $this->uri->segment(3);
			$data['couponData'] = $this->GlobalModel->selectQuery('couponoffer.*', 'couponoffer', array("couponoffer.code" => $code));
			$this->load->view('dashboard/commonheader');
			$this->load->view('dashboard/coupon/edit', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function getCouponList()
	{
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$search = $this->input->GET("search")['value'];
		$tableName = "couponoffer";
		$orderColumns = array("couponoffer.*");
		$condition = array();
		$orderBy = array('couponoffer.id' => 'DESC');
		$joinType = array();
		$join = array();
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$extraCondition = " (couponoffer.isDelete=0 OR couponoffer.isDelete IS NULL)";
		$like = array("couponoffer.code" => $search . "~both","couponoffer.couponCode" => $search . "~both","couponoffer.offerType" => $search . "~both","couponoffer.flatAmount" => $search . "~both","couponoffer.discount" => $search . "~both","couponoffer.minimumAmount" => $search . "~both");
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		$r = $this->db->last_query();
		$srno = $_GET['start'] + 1;
		$dataCount = 0;
		$data = array();
		if ($Records) {
			foreach ($Records->result() as $row) {
				$code = $row->code;
				if ($row->offerType == 'cap') {
					$offerType = 'Per';
				} else {
					$offerType = $row->offerType;
				}
				if ($row->isActive == 1) {
					$status = "<span class='badge bg-success'>Active</span>";
				} else {
					$status = "<span class='badge bg-danger'>Inactive</span>";
				}
				if ($row->offerType == 'flat') {
					$discount = $row->flatAmount . ' ₹';
				} else {
					$discount = $row->discount . ' %';
				}
				$actionHtml = '<div class="d-flex">';
				if ($this->rights != '' && $this->rights['view'] == 1) {
					$actionHtml .= '<a id="view" href="' . base_url() . 'coupon/view/' . $row->code . '" class="btn btn-success btn-sm cursor_pointer m-1"><i id="view" title="View" class="fa fa-eye"></i></a>';
				}
				if ($this->rights != '' && $this->rights['update'] == 1) {
					$actionHtml .= '<a id="edit" href="' . base_url() . 'coupon/edit/' . $row->code . '" class="btn btn-info btn-sm m-1 cursor_pointer"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
				}
				if ($this->rights != '' && $this->rights['delete'] == 1) {
					//$actionHtml .= '<a id="delete" class="btn btn-danger btn-sm m-1 cursor_pointer delete_coupon" id="' . $row->code . '"><i id="dlt" title="Delete" class="fa fa-trash"></i></a><div>';
				}
				$data[] = array(
					$srno,
					$row->code,
					$row->couponCode,
					ucfirst($offerType),
					$discount,
					$row->minimumAmount,
					$status,
					$actionHtml
				);
				$srno++;
			}
			$dataCount = sizeof($this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, '', '', $groupByColumn, $extraCondition)->result());
		}
		$output = array(
			"draw"			  =>     intval($_GET["draw"]),
			"recordsTotal"    =>     $dataCount,
			"recordsFiltered" =>     $dataCount,
			"data"            =>     $data,
			'r'				  => 	 $r
		);
		echo json_encode($output);
	}

	public function save()
	{
		$date = date('Y-m-d H:i:s');
		$couponCode = trim($this->input->post("couponCode"));
		$offerType = trim($this->input->post("offerType"));
		$isActive = trim($this->input->post("isActive"));
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$text = $userName . ' added new coupon code "' . $couponCode . '" from ' . $ip;

		$result = $this->GlobalModel->checkDuplicateRecord('couponCode', $couponCode, 'couponoffer');
		if ($result == true) {
			$response['status'] = true;
			$response['message'] = "Duplicate Coupon";
		} else {
			$data = array(
				'couponCode' => $couponCode,
				'offerType' => $offerType,
				'minimumAmount' => trim($this->input->post("minimumAmount")),
				'perUserLimit' => trim($this->input->post("perUserLimit")),
				'startDate' => $this->input->post("startDate"),
				'endDate' => $this->input->post("endDate"),
				'termsAndConditions' => trim($this->input->post("termsAndConditions")),
				'addID' => $addID,
				'addIP' => $ip,
				'isActive' => $isActive,

			);
			if ($offerType == 'cap') {
				$data['capLimit'] = trim($this->input->post("capLimit"));
				$data['discount'] = trim($this->input->post("discount"));
				$data['flatAmount'] = 0;
			}
			if ($offerType == 'flat') {
				$data['capLimit'] = 0;
				$data['discount'] = 0;
				$data['flatAmount'] = trim($this->input->post("flatAmount"));
			}
			$code = $this->GlobalModel->addNew($data, 'couponoffer', 'COP');

			if ($code != 'false') {
				$txt = $code . " - " . $couponCode . " coupon is added.";
				$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
				$this->GlobalModel->activity_log($activity_text);
				$response['status'] = true;
				$response['message'] = "Coupon Successfully Added.";
			} else {
				$response['status'] = false;
				$response['message'] = "Failed To Add Coupon";
			}
		}
		echo json_encode($response);
	}

	public function update()
	{
		$date = date('Y-m-d H:i:s');
		$code = trim($this->input->post("code"));
		$isActive = trim($this->input->post("isActive"));
		$couponCode = trim($this->input->post("couponCode"));
		$offerType = trim($this->input->post("offerType"));
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$text = $userName . ' updated coupon offer of "' . $couponCode . '" of code ' . $code . ' from ' . $ip;
		$condition2 = array('LOWER(couponCode)' => strtolower($couponCode));
		if ($code != '') {
			$condition2['couponoffer.code!='] = $code;
		}
		$result = $this->GlobalModel->checkDuplicateRecordNew($condition2, 'couponoffer');
		if ($result == true) {
			$response['status'] = true;
			$response['message'] = "Duplicate Coupon";
		} else {
			$data = array(
				'couponCode' => $couponCode,
				'offerType' => $offerType,
				'minimumAmount' => trim($this->input->post("minimumAmount")),
				'perUserLimit' => trim($this->input->post("perUserLimit")),
				'startDate' => $this->input->post("startDate"),
				'endDate' => $this->input->post("endDate"),
				'termsAndConditions' => trim($this->input->post("termsAndConditions")),
				'editID' => $addID,
				'editIP' => $ip,
				'isActive' => $isActive,
			);
			if ($offerType == 'cap') {
				$data['capLimit'] = trim($this->input->post("capLimit"));
				$data['discount'] = trim($this->input->post("discount"));
				$data['flatAmount'] = 0;
			}
			if ($offerType == 'flat') {
				$data['capLimit'] = 0;
				$data['discount'] = 0;
				$data['flatAmount'] = trim($this->input->post("flatAmount"));
			}
			$result = $this->GlobalModel->doEdit($data, 'couponoffer', $code);
			if ($result != 'false') {
				$txt = $code . " - " . $couponCode . " coupon is updated.";
				$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
				$this->GlobalModel->activity_log($activity_text);
				$response['status'] = true;
				$response['message'] = "Coupon Successfully Updated.";
			} else {
				$response['status'] = false;
				$response['message'] = "Failed To Update Offer";
			}
		}
		echo json_encode($response);
	}

	public function view()
	{
		if ($this->rights != '' && $this->rights['view'] == 1) {
			$code = $this->uri->segment(3);
			$data['couponData'] = $this->GlobalModel->selectQuery('couponoffer.*', 'couponoffer', array("couponoffer.code" => $code));
			$this->load->view('dashboard/commonheader');
			$this->load->view('dashboard/coupon/view', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function delete()
	{
		$code = $this->input->post('code');
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$loginrole = "";
		$ip = $_SERVER['REMOTE_ADDR'];
		$couponCode = $this->GlobalModel->selectDataById($code, 'couponoffer')->result()[0]->couponCode;
		$query = $this->GlobalModel->delete($code, 'couponoffer');
		if ($query) {
			$txt = $code . " - " . $couponCode . " coupon is deleted.";
			$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
			$this->GlobalModel->activity_log($activity_text);
		}
		echo $query;
	}
}
