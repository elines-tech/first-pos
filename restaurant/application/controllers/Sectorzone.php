<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sectorzone extends CI_Controller
{
	var $session_key;
	protected $rolecode,$branchCode;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form', 'url', 'html');
		$this->load->model('GlobalModel');
		$this->load->library('QrCodeGenerator');
		$this->load->library('form_validation');
		$this->session_key = $this->session->userdata('key' . SESS_KEY);
		if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
			redirect('Login', 'refresh');
		}
		$this->rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		$this->branchCode = $this->session->userdata['logged_in' . $this->session_key]['branchCode'];
		$this->rights = $this->GlobalModel->getMenuRights('8.2', $this->rolecode);
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
			$data['branches'] = $this->GlobalModel->selectActiveData('branchmaster');
			$data['branchCode']="";
			$data['branchName']="";
			if($this->branchCode!=""){
				$data['branchCode']=$this->branchCode;
				$data['branchName'] = $this->GlobalModel->selectDataById($this->branchCode, 'branchmaster')->result()[0]->branchName;
			}
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/zone/list', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}
	public function getSectorzoneList()
	{
		$branch ="";
		if($this->branchCode!=""){
			$branch=$this->branchCode;
		}
		$tableName = "sectorzonemaster";
		$search = $this->input->GET("search")['value'];
		$orderColumns = array("sectorzonemaster.id,branchmaster.branchName,sectorzonemaster.zoneName,sectorzonemaster.isActive");
		$condition = array('branchmaster.isActive'=>1,'sectorzonemaster.branchCode'=>$branch);
		$orderBy = array('sectorzonemaster.id' => 'DESC');
		$joinType = array('branchmaster' => 'inner');
		$join = array('branchmaster' => 'branchmaster.code=sectorzonemaster.branchCode');
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$extraCondition = " sectorzonemaster.isDelete=0 OR sectorzonemaster.isDelete IS NULL";
		$like = array("branchmaster.branchName" => $search . "~both", "sectorzonemaster.zoneName" => $search . "~both");
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		$srno = $_GET['start'] + 1;
		if ($Records) {
			foreach ($Records->result() as $row) {
				if ($row->isActive == 1) {
					$status = "<span class='badge bg-success'>Active</span>";
				} else {
					$status = "<span class='badge bg-danger'>Inactive</span>";
				}
				$actionHtml = '<div class="d-flex">';
				if ($this->rights != '' && $this->rights['view'] == 1) {
					$actionHtml .= ' <a id="view" class="btn btn-sm btn-success cursor_pointer m-1 edit_zone" data-seq="' . $row->id . '" data-type="1"><i id="edt" title="View" class="fa fa-eye"></i></a>';
				}
				if ($this->rights != '' && $this->rights['update'] == 1) {
					$actionHtml .= '<a id="edit" class="btn btn-sm btn-info cursor_pointer m-1 edit_zone" data-seq="' . $row->id . '" data-type="2"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
				}
				if ($this->rights != '' && $this->rights['delete'] == 1) {
					$actionHtml .= '<a id="delete" class="btn btn-sm btn-danger cursor_pointer m-1 delete_zone" data-seq="' . $row->id . '"><i id="dlt" title="Delete" class="fa fa-trash"></i></a>';
				}
				$actionHtml .= '</div>';
				$data[] = array(
					$srno,
					$row->branchName,
					$row->zoneName,
					$status,
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

	public function saveZone()
	{
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$branchCode = $this->input->post("branchCode");
		if($this->branchCode!=""){
			$branchCode=$this->branchCode;
		}
		$zoneCode = $this->input->post("code");
		$zoneName = trim($this->input->post("zoneName"));
		$isActive = $this->input->post("isActive");
		$ip = $_SERVER['REMOTE_ADDR'];
		$condition2 = array('LOWER(zoneName)' => strtolower($zoneName), 'LOWER(branchCode)' => strtolower($branchCode));
		if ($zoneCode != '') {
			$condition2['sectorzonemaster.id!='] = $zoneCode;
		}
		$result = $this->GlobalModel->checkDuplicateRecordNew($condition2, 'sectorzonemaster');
		if ($result == true) {
			$response['status'] = false;
			$response['message'] = 'Duplicate Sector Zone';
		} else {
			$data = array(
				'branchCode' => $branchCode,
				'zoneName' => $zoneName,
				'isActive' => $isActive
			);
			if ($zoneCode != '') {
				$data['editID'] = $addID;
				$data['editIP'] = $ip;
				$code = $this->GlobalModel->doEditWithField($data, 'sectorzonemaster', 'id', $zoneCode);
				$code = $zoneCode;
				$successMsg = "Sector Zone Updated Successfully";
				$errorMsg = "Failed To Update Table";
				$txt = $code . " - " . $zoneName . " sector zone is updated.";
			} else {
				$data['addID'] = $addID;
				$data['addIP'] = $ip;
				$code = $this->GlobalModel->addWithoutCode($data, 'sectorzonemaster');
				$successMsg = "Table Zone Added Successfully";
				$errorMsg = "Failed To Add Table Zone";
				$txt = $code . " - " . $zoneName . " sector zone is added.";
			}
			if ($code != 'false') {
				$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
				$this->GlobalModel->activity_log($activity_text);
				$response['status'] = true;
				$response['message'] = $successMsg;
			} else {
				$response['status'] = false;
				$response['message'] = $errorMsg;
			}
		}
		echo json_encode($response);
	}

	public function editZone()
	{
		$code = $this->input->post('code');
		$TableQuery = $this->GlobalModel->selectQuery("sectorzonemaster.id,sectorzonemaster.branchCode,sectorzonemaster.zoneName,sectorzonemaster.isActive", 'sectorzonemaster', array('sectorzonemaster.id' => $code));
		if ($TableQuery) {
			$result = $TableQuery->result_array()[0];
			$data['status'] = true;
			$data['id'] = $result['id'];
			$data['branchCode'] = $result['branchCode'];
			$data['zoneName'] = $result['zoneName'];
			$data['isActive'] = $result['isActive'];
		} else {
			$data['status'] = false;
		}
		echo json_encode($data);
	}

	public function deleteZone()
	{
		$code = $this->input->post('code');
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$zoneName = $this->GlobalModel->selectDataByField('id', $code, 'sectorzonemaster')->result()[0]->zoneName;
		$query = $this->GlobalModel->deleteWithField('id', $code, 'sectorzonemaster');
		if ($query) {
			$txt = $code . " - " . $zoneName . " sector zone is deleted.";
			$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
			$this->GlobalModel->activity_log($activity_text);
		}
		echo $query;
	}
}
