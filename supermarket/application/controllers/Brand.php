<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Brand extends CI_Controller
{
	var $session_key;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form', 'url', 'html');
		$this->load->model('GlobalModel');
		$this->session_key = $this->session->userdata('key' . SESS_KEY);
		if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
			redirect('Login', 'refresh');
		}
		$res = $this->GlobalModel->checkActiveSubscription();
		if ($res == "EXPIRED") {
			redirect('package', 'refresh');
		}
		$rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		$this->rights = $this->GlobalModel->getMenuRights('2.1', $rolecode);
		if ($this->rights == '') {
			$this->load->view('errors/norights.php');
		}
	}

	public function listRecords()
	{
		if ($this->rights != '' && $this->rights['view'] == 1) {
			$data['insertRights'] = $this->rights['insert'];
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/brand/list', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function getBrandList()
	{
		$tableName = "brandmaster";
		$search = $this->input->GET("search")['value'];
		$orderColumns = array("brandmaster.code,brandmaster.brandName,brandmaster.isActive,brandmaster.addDate");
		$condition = array();
		$orderBy = array('brandmaster.id' => 'DESC');
		$joinType = array();
		$join = array();
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$extraCondition = " (brandmaster.isDelete=0 OR brandmaster.isDelete IS NULL)";
		$like = array("brandmaster.code" => $search . "~both", "brandmaster.brandName" => $search . "~both");
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
					$actionHtml .= '<a class="btn btn-success btn-sm  m-1 cursor_pointer edit_brand"  data-seq="' . $row->code . '" data-type="1"><i id="edt" title="View" class="fa fa-eye"></i></a>';
				}
				if ($this->rights != '' && $this->rights['update'] == 1) {
					$actionHtml .= '<a class="btn btn-info btn-sm m-1 cursor_pointer edit_brand" data-seq="' . $row->code . '" data-type="2"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
				}
				if ($this->rights != '' && $this->rights['delete'] == 1) {
					$actionHtml .= '<a class="btn btn-sm btn-danger m-1 cursor_pointer delete_brand" data-seq="' . $row->code . '"><i id="dlt" title="Delete" class="fa fa-trash"></i></a>';
				}
				$actionHtml .= '</div>';
				$data[] = array(
					$srno,
					$row->code,
					$row->brandName,
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


	public function editBrand()
	{
		if ($this->rights != '' && ($this->rights['update'] == 1 || $this->rights['view'] == 1)) {
			$code = $this->input->post('code');
			$query = $this->GlobalModel->selectQuery("brandmaster.code,brandmaster.brandName,brandmaster.isActive", 'brandmaster', array('brandmaster.code' => $code));
			if ($query) {
				$result = $query->result_array()[0];
				$data['status'] = true;
				$data['code'] = $result['code'];
				$data['brandName'] = $result['brandName'];
				$data['isActive'] = $result['isActive'];
			} else {
				$data['status'] = false;
			}
		} else {
			$data['status'] = false;
		}
		echo json_encode($data);
	}

	public function saveBrand()
	{
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$brandName = trim($this->input->post("brand"));
		$code = $this->input->post("code");
		$isActive = $this->input->post("isActive");
		$ip = $_SERVER['REMOTE_ADDR'];
		$condition2 = array('LOWER(brandName)' => strtolower($brandName));
		if ($code != '') {
			$condition2['brandmaster.code!='] = $code;
		}
		$result = $this->GlobalModel->checkDuplicateRecordNew($condition2, 'brandmaster');
		if ($result == true) {
			$response['status'] = false;
			$response['message'] = 'Duplicate Brand';
		} else {
			$data = array(
				'brandName' => $brandName,
				'isActive' => $isActive
			);
			if ($code != '') {
				$data['editID'] = $addID;
				$data['editIP'] = $ip;
				$code = $this->GlobalModel->doEdit($data, 'brandmaster', $code);
				$code = $code;
				$successMsg = "Brand Updated Successfully";
				$errorMsg = "Failed To Update Brand";
				$txt = $code . " - " . $brandName . " brand is updated.";
			} else {
				$data['addID'] = $addID;
				$data['addIP'] = $ip;
				$code = $this->GlobalModel->addWithoutYear($data, 'brandmaster', 'BRD');
				$successMsg = "Brand Added Successfully";
				$errorMsg = "Failed To Add Brand";
				$txt = $code . " - " . $brandName . " brand is added.";
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
	public function deleteBrand()
	{
		$code = $this->input->post('code');
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$loginrole = "";
		$ip = $_SERVER['REMOTE_ADDR'];
		$brandName = $this->GlobalModel->selectDataById($code, 'brandmaster')->result()[0]->brandName;
		$query = $this->GlobalModel->delete($code, 'brandmaster');
		if ($query) {
			$txt = $code . " - " . $brandName . " brand is deleted.";
			$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
			$this->GlobalModel->activity_log($activity_text);
		}
		echo $query;
	}
}
