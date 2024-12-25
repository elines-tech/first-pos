<?php

defined('BASEPATH') or exit('No direct script access allowed');

class GiftCard extends CI_Controller
{
	var $session_key;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form', 'url', 'html');
		$this->load->model('GlobalModel');
		$this->load->library('form_validation');
		$this->session_key = $this->session->userdata('key' . SESS_KEY);
		if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
			redirect('Login', 'refresh');
		}
		$res = $this->GlobalModel->checkActiveSubscription();
        if ($res == "EXPIRED") {
            redirect('package', 'refresh');
        }
		$rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		$this->rights = $this->GlobalModel->getMenuRights('8.2',$rolecode);
		if($this->rights ==''){
			$this->load->view('errors/norights.php');
		}
	}

	public function listRecords()
	{
		if($this->rights !='' && $this->rights['view']==1){
			$data['insertRights'] = $this->rights['insert'];
			$this->load->view('dashboard/header');			
			$this->load->view('dashboard/giftcard/list',$data);
			$this->load->view('dashboard/footer');
		}else{
			$this->load->view('errors/norights.php');
		}
	}

	public function add()
	{
		if($this->rights !='' && $this->rights['insert']==1){
			$this->load->view('dashboard/header');			
			$this->load->view('dashboard/giftcard/add');
			$this->load->view('dashboard/footer');
		}else{
			$this->load->view('errors/norights.php');
		}
	}

	public function edit()
	{
		if($this->rights !='' && $this->rights['update']==1){
			$code = $this->uri->segment(3);
			$data['cardData'] = $this->GlobalModel->selectQuery('giftcard.*', 'giftcard', array("giftcard.code" => $code));
			$this->load->view('dashboard/header');			
			$this->load->view('dashboard/giftcard/edit', $data);
			$this->load->view('dashboard/footer');
		}else{
			$this->load->view('errors/norights.php');
		}
	}

	public function getgiftcardList()
	{
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$search = $this->input->GET("search")['value'];
		$tableName = "giftcard";
		$orderColumns = array("giftcard.*"); 
		$condition = array();
		$orderBy = array('giftcard.id' => 'DESC');
		$joinType = array();
		$join = array();
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$extraCondition = " (giftcard.isDelete=0 OR giftcard.isDelete IS NULL)";
		$like = array("giftcard.code" => $search . "~both","giftcard.title" => $search . "~both","giftcard.discount" => $search . "~both","giftcard.price" => $search . "~both","giftcard.validityInDays" => $search . "~both");
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		$r = $this->db->last_query();
		$srno = $_GET['start'] + 1;
		$dataCount = 0;
		$data = array();
		if ($Records) {
			foreach ($Records->result() as $row) {
				$code = $row->code;
				
                if ($row->isActive == 1) {
                    $status = "<span class='badge bg-success'>Active</span>";
				} else {
					$status = "<span class='badge bg-danger'>Inactive</span>";
				}
				$actionHtml='';
				if($this->rights !='' && $this->rights['view']==1){
					$actionHtml .= '<a id="view" href="' . base_url() . 'giftCard/view/' . $row->code . '" class="btn btn-success btn-sm cursor_pointer m-1"><i id="view" title="View" class="fa fa-eye"></i></a>';
				}
				if($this->rights !='' && $this->rights['update']==1){
					$actionHtml .= '<a id="edit" href="' . base_url() . 'giftCard/edit/' . $row->code . '" class="btn btn-info btn-sm m-1 cursor_pointer"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
				}
				if($this->rights !='' && $this->rights['delete']==1){
					$actionHtml .= '<a id="delete" class="btn btn-danger btn-sm m-1 cursor_pointer delete_card" id="'.$row->code.'"><i id="dlt" title="Delete" class="fa fa-trash"></i></a>';
				}
				$data[] = array(
					$srno,
					$row->code,
					$row->title,
					$row->discount,
					$row->price,
					$row->validityInDays,
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
		$cardType = trim($this->input->post("cardType"));
		$price = trim($this->input->post("price"));
		$isActive = trim($this->input->post("isActive"));
		$discount = trim($this->input->post("discount"));
		$validityInDays = trim($this->input->post("validityInDays"));
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$text = $userName . ' added new giftcard "' . $title . '" from ' . $ip;
		$data = array(
			'title' => $title,
			'description' => $description,
			'cardType' => $cardType,
			'discount' => $discount,
			'price' => $price,
			'validityInDays' => $validityInDays,
			'addID' => $addID,
			'addIP' => $ip,
			'isActive' => $isActive,
			
		);
		$code = $this->GlobalModel->addNew($data, 'giftcard', 'GIC');
		if ($code != 'false') {
			$txt = $code . " - " . $title . " giftcard is added.";
			$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
			$this->GlobalModel->activity_log($activity_text);
			$response['status'] = true;
			$response['message'] = "Giftcard Successfully Added."; 
		} else {
			$response['status'] = false;
			$response['message'] = "Failed To Add Giftcard";
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
		$cardType = trim($this->input->post("cardType"));
		$discount = trim($this->input->post("discount"));
		$price = trim($this->input->post("price"));
		$validityInDays = trim($this->input->post("validityInDays"));
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$text = $userName . ' updated giftcard "' . $title . '" of code ' . $code . ' from ' . $ip;
		$data = array(
			'title' => $title,
			'description' => $description,
			'cardType' => $cardType,
			'discount' => $discount,
			'price' => $price,
			'validityInDays' => $validityInDays,
			'editID' => $addID,
			'editIP' => $ip,
			'isActive' => $isActive,	
		);
		
		$result = $this->GlobalModel->doEdit($data, 'giftcard', $code);
		if ($result != 'false') {
			$txt = $code . " - " . $title . " giftcard is updated.";
			$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
			$this->GlobalModel->activity_log($activity_text);
			$response['status'] = true;
			$response['message'] = "Giftcard Successfully Updated.";
		} else {
			$response['status'] = false;
			$response['message'] = "Failed To Update Giftcard";
		}
		echo json_encode($response);
	}

	public function view()
	{
		if($this->rights !='' && $this->rights['view']==1){
			$code = $this->uri->segment(3);
			$data['cardData'] = $this->GlobalModel->selectQuery('giftcard.*', 'giftcard', array("giftcard.code" => $code));
			$this->load->view('dashboard/header');			
			$this->load->view('dashboard/giftcard/view', $data);
			$this->load->view('dashboard/footer');
		}else{
			$this->load->view('errors/norights.php');
		}
	}

	public function delete()
	{
		if($this->rights !='' && $this->rights['delete']==1){
			$code = $this->input->post('code');
			$date = date('Y-m-d H:i:s');
			$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
			$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
			$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
			$loginrole = "";
			$ip = $_SERVER['REMOTE_ADDR'];
			$title = $this->GlobalModel->selectDataById($code, 'giftcard')->result()[0]->title;
			$query = $this->GlobalModel->delete($code, 'giftcard');
			if ($query) {
				$txt = $code . " - " . $title . " giftcard is deleted.";
				$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
				$this->GlobalModel->activity_log($activity_text);
			}
			echo $query;
		}else{
			$this->load->view('errors/norights.php');
		}
	}
}