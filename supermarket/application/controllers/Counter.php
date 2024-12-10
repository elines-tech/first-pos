<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Counter extends CI_Controller
{
	var $session_key;
	protected $rolecode,$branchCode; 
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
		$this->rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		$this->branchCode = $this->session->userdata['logged_in' . $this->session_key]['userBranch'];
		$this->rights = $this->GlobalModel->getMenuRights('7.5', $this->rolecode);
		if ($this->rights == '') {
			$this->load->view('errors/norights.php');
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
			$this->load->view('dashboard/counter/list', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}
	public function getCounterList()
	{
		$branch="";
		if($this->branchCode!=""){
			$branch=$this->branchCode;
		}
		$dataCount = 0;
		$data = array();
		$tableName = "countermaster";
		$search = $this->input->GET("search")['value'];
		$orderColumns = array("countermaster.code,branchmaster.branchName,countermaster.counterName,countermaster.isActive");
		$condition = array("countermaster.branchCode"=>$branch);
		$orderBy = array('countermaster.id' => 'DESC');
		$joinType = array('branchmaster' => 'inner');
		$join = array('branchmaster' => 'branchmaster.code=countermaster.branchCode');
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$extraCondition = " (countermaster.isDelete=0 OR countermaster.isDelete IS NULL)";
		$like = array("countermaster.code" => $search . "~both","countermaster.counterName" => $search . "~both", "branchmaster.branchName" => $search . "~both");
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
				$actionHtml = '';
				if ($this->rights != '' && $this->rights['view'] == 1) {
					$actionHtml .= '<a class="edit_counter btn btn-success btn-sm cursor_pointer m-1" data-seq="' . $row->code . '" data-type="1"><i id="edt" title="View" class="fa fa-eye"></i></a>';
				}
				if ($this->rights != '' && $this->rights['update'] == 1) {
					$actionHtml .= '<a class="edit_counter btn btn-info btn-sm cursor_pointer m-1" data-seq="' . $row->code . '" data-type="2"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
				}
				if ($this->rights != '' && $this->rights['delete'] == 1) {
					$actionHtml .= '<a class="btn btn-sm btn-danger delete_counter m-1" data-seq="' . $row->code . '"><i id="dlt" title="Delete" class="fa fa-trash"></i></a>';
				}

				$data[] = array(
					$srno,
					$row->code,
					$row->branchName,
					$row->counterName,
					$status,
					$actionHtml
				);
				$srno++;
			}
			$dataCount = sizeof($this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, array(), '', '', '', $extraCondition)->result());
		}
		$output = array(
			"draw"			  =>     intval($_GET["draw"]),
			"recordsTotal"    =>     $dataCount,
			"recordsFiltered" =>     $dataCount,
			"data"            =>     $data
		);
		echo json_encode($output);
	}

	public function saveCounter()
	{
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$loginrole = "";
		$counterCode = trim($this->input->post("counterCode"));
		$branchCode = trim($this->input->post("branchCode"));
		if($this->branchCode!=""){
			$branchCode=$this->branchCode;
		}
		$counterName = trim($this->input->post("counterName"));
		$isActive = $this->input->post("isActive");
		$ip = $_SERVER['REMOTE_ADDR'];
		$condition2 = array('LOWER(branchCode)' => strtolower($branchCode), 'LOWER(counterName)' => strtolower($counterName));
		if ($counterCode != '') {
			$condition2['countermaster.code!='] = $counterCode;
		}
		$result = $this->GlobalModel->checkDuplicateRecordNew($condition2, 'countermaster');
		if ($result == true) {
			$response['status'] = false;
			$response['message'] = 'Duplicate Counter name';
		} else {
			$data = array(
				'branchCode' => $branchCode,
				'counterName' => $counterName,
				'isActive' => $isActive
			);
			if ($counterCode != '') {
				$data['editID'] = $addID;
				$data['editIP'] = $ip;
				$code = $this->GlobalModel->doEdit($data, 'countermaster', $counterCode);
				$code = $counterCode;
				$successMsg = "Counter Updated Successfully";
				$errorMsg = "Failed To Update Counter";
				$txt = $code . " - " . $counterName . " counter is updated.";
			} else {
				$data['addID'] = $addID;
				$data['addIP'] = $ip;
				$code = $this->GlobalModel->addNew($data, 'countermaster', 'CO');
				$successMsg = "Counter Added Successfully";
				$errorMsg = "Failed To Add Counter";
				$txt = $code . " - " . $counterName . " counter is added.";
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

	public function editCounter()
	{
		if ($this->rights != '' && ($this->rights['update'] == 1 || $this->rights['view'] == 1)) {
			$code = $this->input->post('code');
			$unitQuery = $this->GlobalModel->selectQuery("branchmaster.branchName,countermaster.code,countermaster.branchCode,countermaster.counterName,countermaster.isActive", 'countermaster', array('countermaster.code' => $code),array(),array('branchmaster' => 'branchmaster.code=countermaster.branchCode'),array('branchmaster' => 'inner'));
			
			
			if ($unitQuery) {
				$result = $unitQuery->result_array()[0];
				$data['status'] = true;
				$data['code'] = $result['code'];
				$data['counterName'] = $result['counterName'];
				$data['branchCode'] = $result['branchCode'];
				$data['branchName'] = $result['branchName'];
				$data['isActive'] = $result['isActive'];
			} else {
				$data['status'] = false;
			}
		} else {
			$data['status'] = false;
		}
		echo json_encode($data);
	}

	public function deleteCounter()
	{
		if ($this->rights != '' && $this->rights['delete'] == 1) {
			$code = $this->input->post('code');
			$date = date('Y-m-d H:i:s');
			$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
			$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
			$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
			$loginrole = "";
			$ip = $_SERVER['REMOTE_ADDR'];
			$unitName = $this->GlobalModel->selectDataById($code, 'countermaster')->result()[0]->unitName;
			$query = $this->GlobalModel->delete($code, 'countermaster');
			if ($query) {
				$txt = $code . " - " . $unitName . " counter is deleted.";
				$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
				$this->GlobalModel->activity_log($activity_text);
			}
			echo $query;
		} else {
			$data['status'] = false;
		}
	}
}
