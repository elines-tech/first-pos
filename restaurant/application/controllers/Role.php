<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Role extends CI_Controller
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
		$this->rights = $this->GlobalModel->getMenuRights('8.5', $rolecode);
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
			$this->load->view('dashboard/role/list', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}
	public function getRoleList()
	{
		$RoleName = "rolesmaster";
		$search = $this->input->GET("search")['value'];
		$orderColumns = array("rolesmaster.code,rolesmaster.role,rolesmaster.addDate,rolesmaster.isActive");
		$condition = array();
		$orderBy = array('rolesmaster.id' => 'DESC');
		$joinType = array();
		$join = array();
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$extraCondition = " rolesmaster.isDelete=0 OR rolesmaster.isDelete IS NULL";
		$like = array("rolesmaster.role" => $search . "~both","rolesmaster.code" => $search . "~both");
		$Records = $this->GlobalModel->selectQuery($orderColumns, $RoleName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
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
				if ($this->rights != '' && $this->rights['update'] == 1) {
					$actionHtml .= '<a class="edit_role btn btn-info btn-sm cursor_pointer" data-seq="' . $row->code . '"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
				}
				/*if ($this->rights != '' && $this->rights['delete'] == 1) {
					$actionHtml .= '<a class="btn btn-sm btn-danger delete_role" data-seq="' . $row->code . '"><i id="dlt" title="Delete" class="fa fa-trash"></i></a>';
				}*/
				$data[] = array(
					$srno,
					$row->code,
					$row->role,
					date('d M Y h:i A', strtotime($row->addDate)),
					$status,
					$actionHtml
				);
				$srno++;
			}
			$dataCount = sizeof($this->GlobalModel->selectQuery($orderColumns, $RoleName, $condition, $orderBy, $join, $joinType, array(), '', '', '', $extraCondition)->result());
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

	public function saveRole()
	{
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$loginrole = "";
		$role = trim($this->input->post("role"));
		$code = $this->input->post("code");
		$isActive = $this->input->post("isActive");
		$ip = $_SERVER['REMOTE_ADDR'];
		$condition2 = array('LOWER(role)' => strtolower($role));
		if ($code != '') {
			$condition2['rolesmaster.code!='] = $code;
		}
		$result = $this->GlobalModel->checkDuplicateRecordNew($condition2, 'rolesmaster');
		if ($result == true) {
			$response['status'] = false;
			$response['message'] = 'Duplicate Role';
		} else {
			$data = array(
				'role' => $role,
				'isActive' => $isActive
			);
			if ($code != '') {
				$data['editID'] = $addID;
				$data['editIP'] = $ip;
				$code = $this->GlobalModel->doEdit($data, 'rolesmaster', $code);
				$code = $code;
				$successMsg = "Role Updated Successfully";
				$errorMsg = "Failed To Update Role";
				$txt = $code . " - " . $role . " role is updated.";
			} else {
				$data['addID'] = $addID;
				$data['addIP'] = $ip;
				$code = $this->GlobalModel->addWithoutYear($data, 'rolesmaster', 'R');
				$successMsg = "Role Added Successfully";
				$errorMsg = "Failed To Add Role";
				$txt = $code . " - " . $role . " role is added.";
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

	public function editRole()
	{
		$code = $this->input->post('code');
		$RoleQuery = $this->GlobalModel->selectQuery("rolesmaster.code,rolesmaster.role,rolesmaster.isActive", 'rolesmaster', array('rolesmaster.code' => $code));
		if ($RoleQuery) {
			$result = $RoleQuery->result_array()[0];
			$data['status'] = true;
			$data['code'] = $result['code'];
			$data['role'] = $result['role'];
			$data['isActive'] = $result['isActive'];
		} else {
			$data['status'] = false;
		}
		echo json_encode($data);
	}

	public function deleteRole()
	{
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$loginrole = "";
		$code = $this->input->post('code');
		$ip = $_SERVER['REMOTE_ADDR'];
		$roleName = $this->GlobalModel->selectDataById($code, 'rolesmaster')->result()[0]->role;
		$query = $this->GlobalModel->delete($code, 'rolesmaster');
		if ($query) {
			$txt = $code . " - " . $roleName . " role is deleted.";
			$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
			$this->GlobalModel->activity_log($activity_text);
		}
		echo $query;
	}
}
