<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Offer extends CI_Controller
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
		$this->rights = $this->GlobalModel->getMenuRights('10.3', $rolecode);
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
			$this->load->view('dashboard/offer/list', $data);
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
			$this->load->view('dashboard/offer/add', $data);
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
			$data['offerData'] = $this->GlobalModel->selectQuery('offer.*', 'offer', array("offer.code" => $code));
			$this->load->view('dashboard/commonheader');
			$this->load->view('dashboard/offer/edit', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function getOfferList()
	{
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$search = $this->input->GET("search")['value'];
		$tableName = "offer";
		$orderColumns = array("offer.*");
		$condition = array();
		$orderBy = array('offer.id' => 'DESC');
		$joinType = array();
		$join = array();
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$extraCondition = " (offer.isDelete=0 OR offer.isDelete IS NULL)";
		$like = array("offer.code" => $search . "~both","offer.offerType" => $search . "~both","offer.title" => $search . "~both","offer.minimumAmount" => $search . "~both","offer.discount" => $search . "~both","offer.flatAmount" => $search . "~both");
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
				$actionHtml = '';
				if ($this->rights != '' && $this->rights['view'] == 1) {
					$actionHtml .= '<a id="view" href="' . base_url() . 'offer/view/' . $row->code . '" class="btn btn-success btn-sm cursor_pointer m-1"><i id="view" title="View" class="fa fa-eye"></i></a>';
				}
				if ($this->rights != '' && $this->rights['update'] == 1) {
					$actionHtml .= '<a id="edit" href="' . base_url() . 'offer/edit/' . $row->code . '" class="btn btn-info btn-sm m-1 cursor_pointer"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
				}
				if ($this->rights != '' && $this->rights['delete'] == 1) {
					//$actionHtml .= '<a id="delete" class="btn btn-danger btn-sm m-1 cursor_pointer delete_offer" id="' . $row->code . '"><i id="dlt" title="Delete" class="fa fa-trash"></i></a>';
				}
				$data[] = array(
					$srno,
					$row->code,
					$row->title,
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
		$title = trim($this->input->post("title"));
		$description = trim($this->input->post("description"));
		$offerType = trim($this->input->post("offerType"));
		$isActive = trim($this->input->post("isActive"));
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$text = $userName . ' added new offer "' . $title . '" from ' . $ip;
		$data = array(
			'title' => $title,
			'description' => $description,
			'offerType' => $offerType,
			'minimumAmount' => trim($this->input->post("minimumAmount")),
			'startDate' => $this->input->post("startDate"),
			'endDate' => $this->input->post("endDate"),
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
		$code = $this->GlobalModel->addNew($data, 'offer', 'OFF');
		if ($code != 'false') {
			$txt = $code . " - " . $title . " offer is added.";
			$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
			$this->GlobalModel->activity_log($activity_text);
			$response['status'] = true;
			$response['message'] = "Offer Successfully Added.";
		} else {
			$response['status'] = false;
			$response['message'] = "Failed To Add Offer";
		}
		echo json_encode($response);
	}

	public function update()
	{
		$date = date('Y-m-d H:i:s');
		$code = trim($this->input->post("code"));
		$isActive = trim($this->input->post("isActive"));
		$title = trim($this->input->post("title"));
		$description = trim($this->input->post("description"));
		$offerType = trim($this->input->post("offerType"));
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$text = $userName . ' updated offer "' . $title . '" of code ' . $code . ' from ' . $ip;
		$data = array(
			'title' => $title,
			'description' => $description,
			'offerType' => $offerType,
			'minimumAmount' => trim($this->input->post("minimumAmount")),
			'startDate' => $this->input->post("startDate"),
			'endDate' => $this->input->post("endDate"),
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
		$result = $this->GlobalModel->doEdit($data, 'offer', $code);
		if ($result != 'false') {
			$txt = $code . " - " . $title . " offer is updated.";
			$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
			$this->GlobalModel->activity_log($activity_text);
			$response['status'] = true;
			$response['message'] = "Offer Successfully Updated.";
		} else {
			$response['status'] = false;
			$response['message'] = "Failed To Update Offer";
		}
		echo json_encode($response);
	}

	public function view()
	{
		if ($this->rights != '' && $this->rights['view'] == 1) {
			$code = $this->uri->segment(3);
			$data['offerData'] = $this->GlobalModel->selectQuery('offer.*', 'offer', array("offer.code" => $code));
			$this->load->view('dashboard/commonheader');
			$this->load->view('dashboard/offer/view', $data);
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
		$title = $this->GlobalModel->selectDataById($code, 'offer')->result()[0]->title;
		$query = $this->GlobalModel->delete($code, 'offer');
		if ($query) {
			$txt = $code . " - " . $title . " offer is deleted.";
			$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
			$this->GlobalModel->activity_log($activity_text);
		}
		echo $query;
	}
}
