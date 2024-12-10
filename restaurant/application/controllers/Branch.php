<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Branch extends CI_Controller
{
	var $session_key;
	protected $cmpCode;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form', 'url', 'html');
		$this->load->model('AdminModel');
		$this->load->model('GlobalModel');
		$this->session_key = $this->session->userdata('key' . SESS_KEY);
		if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
			redirect('Login', 'refresh');
		}
		$this->cmpCode = $this->session->userdata['logged_in' . $this->session_key]['cmpCode'];
		$rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		$this->rights = $this->GlobalModel->getMenuRights('8.1', $rolecode);
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
		$data['taxGroups'] = $this->GlobalModel->selectActiveData('taxgroupmaster');
		if ($this->rights != '' && $this->rights['view'] == 1) {
			$data['insertRights'] = $this->rights['insert'];
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/branch/add', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function getBranchList()
	{
		$tableName = "branchmaster";
		$search = $this->input->GET("search")['value'];
		$orderColumns = array("branchmaster.code,taxgroupmaster.taxGroupName,branchmaster.branchName,branchmaster.isActive,branchmaster.addDate");
		$condition = array();
		$orderBy = array('branchmaster.id' => 'DESC');
		$joinType = array('taxgroupmaster' => 'inner');
		$join = array('taxgroupmaster' => 'taxgroupmaster.code=branchmaster.taxGroup');
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$extraCondition = " (branchmaster.isDelete=0 OR branchmaster.isDelete IS NULL)";
		$like = array("branchmaster.branchName" => $search . "~both", "taxgroupmaster.taxGroupName" => $search . "~both", "branchmaster.code" => $search . "~both");
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
					$actionHtml .= '<a class="btn btn-success btn-sm  m-1 cursor_pointer" href="' . base_url() . 'branch/view/' . $row->code . '"><i id="edt" title="View" class="fa fa-eye"></i></a>';
				}
				if ($this->rights != '' && $this->rights['update'] == 1) {
					$actionHtml .= '<a class="btn btn-info btn-sm cursor_pointer m-1 edit_branch" data-seq="' . $row->code . '"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
				}
				if ($this->rights != '' && $this->rights['delete'] == 1) {
					$actionHtml .= '<a class="btn btn-sm btn-danger cursor_pointer m-1 delete_branch" data-seq="' . $row->code . '"><i id="dlt" title="Delete" class="fa fa-trash"></i></a>';
				}
				$actionHtml .= '</div>';
				$data[] = array(
					$srno,
					$row->code,
					$row->branchName,
					$row->taxGroupName,
					date('d M Y h:i A', strtotime($row->addDate)),
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

	public function view()
	{
		if ($this->rights != '' && $this->rights['view'] == 1) {
			$code = $this->uri->segment(3);
			$data['branch'] = $this->GlobalModel->selectQuery('branchmaster.*,taxgroupmaster.taxGroupName', 'branchmaster', array('branchmaster.code' => $code), array(), array('taxgroupmaster' => 'taxgroupmaster.code=branchmaster.taxGroup'), array('taxgroupmaster' => 'left'));
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/branch/view', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function edit()
	{
		$code = $this->input->post('code');
		$data['taxGroups'] = $this->GlobalModel->selectActiveData('taxgroupmaster');
		$data['branch'] = $this->GlobalModel->selectQuery('branchmaster.*,taxgroupmaster.taxGroupName', 'branchmaster', array('branchmaster.code' => $code), array(), array('taxgroupmaster' => 'taxgroupmaster.code=branchmaster.taxGroup'), array('taxgroupmaster' => 'left'));
		echo $this->load->view('dashboard/branch/edit', $data, true);
	}

	public function saveBranch()
	{
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$branchName = trim($this->input->post("branchName"));
		$taxGroup = $this->input->post("taxGroup");
		$branchTaxRegName = $this->input->post("branchTaxRegName");
		$branchTaxRegNo = $this->input->post("branchTaxRegNo");
		$openingFrom = $this->input->post("openingFrom");
		$openingTo = $this->input->post("openingTo");
		$branchPhoneNo = $this->input->post("branchPhoneNo");
		$branchAddress = $this->input->post("branchAddress");
		$branchLat = $this->input->post("branchLat");
		$branchLong = $this->input->post("branchLong");
		$receiptHead = $this->input->post("receiptHead");
		$receiptFoot = $this->input->post("receiptFoot");
		$isActive = $this->input->post("isActive");
		$ip = $_SERVER['REMOTE_ADDR'];
		$allowedBranchCount = $this->AdminModel->get_subscriber_max_branches($this->cmpCode);
		$currentBranchCount = 0;
		$currentBranches = $this->GlobalModel->selectQuery("branchmaster.*", "branchmaster", ["branchmaster.isDelete!=" => 1]);
		if ($currentBranches) $currentBranchCount = count($currentBranches->result());
		if ($currentBranchCount <= $allowedBranchCount) {
			$condition2 = array('LOWER(branchName)' => strtolower($branchName));
			$result = $this->GlobalModel->checkDuplicateRecordNew($condition2, 'branchmaster');
			if ($result == true) {
				$response['status'] = false;
				$response['message'] = 'Duplicate Branch Name';
			} else {
				$data = array(
					'branchName' => $branchName,
					'taxGroup' => $taxGroup,
					'branchTaxRegName' => $branchTaxRegName,
					'branchTaxRegNo' => $branchTaxRegNo,
					'openingFrom' => $openingFrom,
					'openingTo' => $openingTo,
					'branchPhoneNo' => $branchPhoneNo,
					'branchAddress' => $branchAddress,
					'branchLat' => $branchLat,
					'branchLong' => $branchLong,
					'receiptHead' => $receiptHead,
					'receiptFoot' => $receiptFoot,
					'addID' => $addID,
					'addIP' => $ip,
					'isActive' => $isActive
				);
				$code = $this->GlobalModel->addNew($data, 'branchmaster', 'BR');
				if ($code != 'false') {
					$txt = $code . " - " . $branchName . " Branch is added.";
					$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
					$this->GlobalModel->activity_log($activity_text);
					$response['status'] = true;
					$response['message'] = 'Branch Added Successfully';
				} else {
					$response['status'] = false;
					$response['message'] = 'Failed to Add Branch';
				}
			}
		} else {
			$response['status'] = false;
			$response['message'] = 'You cannot create more branches, maximum branches limit reached. Please upgrade your current plan to create more branches';
		}
		echo json_encode($response);
	}

	public function updateBranch()
	{
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$branchCode = $this->input->post("branchCode");
		$taxGroup = $this->input->post("modaltaxGroup");
		$branchName = $this->input->post("modalbranchName");
		$branchTaxRegName = $this->input->post("modalbranchTaxRegName");
		$branchTaxRegNo = $this->input->post("modalbranchTaxRegNo");
		$openingFrom = $this->input->post("modalopeningFrom");
		$openingTo = $this->input->post("modalopeningTo");
		$branchPhoneNo = $this->input->post("modalbranchPhoneNo");
		$branchAddress = $this->input->post("modalbranchAddress");
		$branchLat = $this->input->post("modalbranchLat");
		$branchLong = $this->input->post("modalbranchLong");
		$receiptHead = $this->input->post("modalreceiptHead");
		$receiptFoot = $this->input->post("modalreceiptFoot");
		$isActive = $this->input->post("modalisActive");
		$ip = $_SERVER['REMOTE_ADDR'];
		$addID = '1';
		$condition2 = array('LOWER(branchName)' => strtolower($branchName), 'code!=' => $branchCode);
		$result = $this->GlobalModel->checkDuplicateRecordNew($condition2, 'branchmaster');
		if ($result == true) {
			$response['status'] = false;
			$response['message'] = 'Duplicate Branch Name';
		} else {
			$data = array(
				'branchName' => $branchName,
				'taxGroup' => $taxGroup,
				'branchTaxRegName' => $branchTaxRegName,
				'branchTaxRegNo' => $branchTaxRegNo,
				'openingFrom' => $openingFrom,
				'openingTo' => $openingTo,
				'branchPhoneNo' => $branchPhoneNo,
				'branchAddress' => $branchAddress,
				'branchLat' => $branchLat,
				'branchLong' => $branchLong,
				'receiptHead' => $receiptHead,
				'receiptFoot' => $receiptFoot,
				'editIP' => $ip,
				'editID' => $addID,
				'isActive' => $isActive
			);
			$code = $this->GlobalModel->doEdit($data, 'branchmaster', $branchCode);
			if ($code != 'false') {
				$txt = $branchCode . " - " . $branchName . " Branch is updated.";
				$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
				$this->GlobalModel->activity_log($activity_text);
				$response['status'] = true;
				$response['message'] = 'Branch Updated Successfully';
			} else {
				$response['status'] = false;
				$response['message'] = 'Failed to Update Branch';
			}
		}
		echo json_encode($response);
	}
	public function deleteBranch()
	{
		$code = $this->input->post('code');
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$loginrole = "";
		$ip = $_SERVER['REMOTE_ADDR'];
		$branchName = $this->GlobalModel->selectDataById($code, 'branchmaster')->result()[0]->branchName;
		$query = $this->GlobalModel->delete($code, 'branchmaster');
		if ($query) {
			$txt = $code . " - " . $branchName . " branch is deleted.";
			$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
			$this->GlobalModel->activity_log($activity_text);
		}
		echo $query;
	}
	public function getUserList()
	{
		$branchCode = $this->input->GET('branchCode');
		$tableName = "usermaster";
		$search = $this->input->GET("search")['value'];
		$orderColumns = array("usermaster.userName,usermaster.userEmail,usermaster.userEmpNo,rolesmaster.role");
		$condition = array('usermaster.userBranchCode' => $branchCode);
		$orderBy = array('usermaster.id' => 'ASC');
		$joinType = array('rolesmaster' => 'inner');
		$join = array('rolesmaster' => 'rolesmaster.code=usermaster.userRole');
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$extraCondition = " usermaster.isDelete=0 OR usermaster.isDelete IS NULL";
		$like = array("usermaster.taxName" => $search . "~both");
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		$srno = $_GET['start'] + 1;
		if ($Records) {
			foreach ($Records->result() as $row) {
				$data[] = array(
					$row->userName,
					$row->role,
					$row->userEmail,
					$row->userEmpNo,
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
}
