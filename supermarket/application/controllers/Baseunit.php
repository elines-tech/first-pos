<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Baseunit extends CI_Controller
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
		$this->rights = $this->GlobalModel->getMenuRights('7.3', $rolecode);
		if ($this->rights == '') {
			$this->load->view('errors/norights.php');
		}
	}
	public function listRecords()
	{
		if ($this->rights != '' && $this->rights['view'] == 1) {
			$data['insertRights'] = $this->rights['insert'];
			$this->load->view('dashboard/header');

			$this->load->view('dashboard/baseunit/list', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}
	public function getBaseUnitList()
	{
		$tableName = "baseunitmaster";
		$search = $this->input->GET("search")['value'];
		$orderColumns = array("baseunitmaster.*");
		$condition = array();
		$orderBy = array('baseunitmaster.id' => 'DESC');
		$joinType = array();
		$join = array();
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$extraCondition = " (baseunitmaster.isDelete=0 OR baseunitmaster.isDelete IS NULL)";
		$like = array("baseunitmaster.code" => $search . "~both","baseunitmaster.baseunitName" => $search . "~both", "baseunitmaster.baseunitSName" => $search . "~both");
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		$srno = $_GET['start'] + 1;
		if ($Records) {
			foreach ($Records->result() as $row) {
				$code = $row->code;
				if ($row->isActive == 1) {
					$status = "<span class='badge bg-success'>Active</span>";
				} else {
					$status = "<span class='badge bg-danger'>Inactive</span>";
				}
				$actionHtml = '<div class="d-flex">';
				if ($this->rights != '' && $this->rights['view'] == 1) {
					$actionHtml .= '<a class="edit_baseunit btn btn-success btn-sm cursor_pointer" data-seq="' . $row->code . '" data-type="1"><i id="edt" title="View" class="fa fa-eye"></i></a>';
				}
				if ($this->rights != '' && $this->rights['update'] == 1) {
					$actionHtml .= '<a class="edit_baseunit btn btn-info btn-sm cursor_pointer" data-seq="' . $row->code . '" data-type="2"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
				}
				if ($this->rights != '' && $this->rights['delete'] == 1) {
					$actionHtml .= '<a class="btn btn-sm btn-danger delete_baseunit" data-seq="' . $row->code . '"><i id="dlt" title="Delete" class="fa fa-trash"></i></a>';
				}
                 $actionHtml .= '</div>';
				$data[] = array(
					$srno,
					$row->code,
					$row->baseunitName,
					$row->baseunitSName,
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

	public function saveBaseUnit()
	{
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$loginrole = "";
		$baseunitName = trim($this->input->post("baseunitName"));
		$baseunitSName = $this->input->post("baseunitSName");
		$description = $this->input->post("description");
		$baseunitCode = $this->input->post("code");
		$isActive = $this->input->post("isActive");
		$ip = $_SERVER['REMOTE_ADDR'];
		$condition2 = array('LOWER(baseunitName)' => strtolower($baseunitName));
		if ($baseunitCode != '') {
			$condition2['baseunitmaster.code!='] = $baseunitCode;
		}
		$result = $this->GlobalModel->checkDuplicateRecordNew($condition2, 'baseunitmaster');
		if ($result == true) {
			$response['status'] = false;
			$response['message'] = 'Duplicate Base Unit';
		} else {
			$data = array(
				'baseunitName' => $baseunitName,
				'baseunitSName' => $baseunitSName,
				'baseunitDesc' => $description,
				'isActive' => $isActive
			);
			if ($baseunitCode != '') {
				$data['editID'] = $addID;
				$data['editIP'] = $ip;
				$code = $this->GlobalModel->doEdit($data, 'baseunitmaster', $baseunitCode);
				$successMsg = "Base Unit Updated Successfully";
				$errorMsg = "Failed To Update Base Unit";
				$txt = $baseunitCode . " - " . $baseunitName . " base unit is updated.";
			} else {
				$data['addID'] = $addID;
				$data['addIP'] = $ip;
				$code = $this->GlobalModel->addWithoutYear($data, 'baseunitmaster', 'BUOM');
				$successMsg = "Base Unit Added Successfully";
				$errorMsg = "Failed To Base Unit";
				$txt = $baseunitCode . " - " . $baseunitName . " base unit is added.";
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

	public function editBaseUnit()
	{
		if ($this->rights != '' && ($this->rights['update'] == 1 || $this->rights['view'] == 1)) {
			$code = $this->input->post('code');
			$baseunitQuery = $this->GlobalModel->selectQuery("baseunitmaster.*", 'baseunitmaster', array('baseunitmaster.code' => $code));
			if ($baseunitQuery) {
				$result = $baseunitQuery->result_array()[0];
				$data['status'] = true;
				$data['code'] = $result['code'];
				$data['baseunitName'] = $result['baseunitName'];
				$data['baseunitSName'] = $result['baseunitSName'];
				$data['description'] = $result['baseunitDesc'];
				$data['isActive'] = $result['isActive'];
			} else {
				$data['status'] = false;
			}
		} else {
			$data['status'] = false;
		}
		echo json_encode($data);
	}

	public function deleteBaseUnit()
	{
		if ($this->rights != '' && $this->rights['delete'] == 1) {
			$code = $this->input->post('code');
			$date = date('Y-m-d H:i:s');
			$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
			$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
			$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
			$loginrole = "";
			$ip = $_SERVER['REMOTE_ADDR'];
			$baseunitName = $this->GlobalModel->selectDataById($code, 'baseunitmaster')->result()[0]->baseunitName;
			$query = $this->GlobalModel->delete($code, 'baseunitmaster');
			if ($query) {
				$txt = $code . " - " . $baseunitName . " base unit is deleted.";
				$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
				$this->GlobalModel->activity_log($activity_text);
			}
			echo $query;
		} else {
		}
	}
}
