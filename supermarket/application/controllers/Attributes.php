<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Attributes extends CI_Controller
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
	}
	public function listRecords()
	{
		$this->load->view('dashboard/header');
		
		$this->load->view('dashboard/attribute/list', $data);
		$this->load->view('dashboard/footer');
	}
	public function getAttributeList()
	{
		$tableName = "attributemaster";
		$search = $this->input->GET("search")['value'];
		$orderColumns = array("attributemaster.*");
		$condition = array();
		$orderBy = array('attributemaster.id' => 'DESC');
		$joinType = array();
		$join = array();
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$extraCondition = " attributemaster.isDelete=0 OR attributemaster.isDelete IS NULL";
		$like = array("attributemaster.attributeName" => $search . "~both", "attributemaster.code" => $search . "~both");
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
				$actionHtml = ' <a class="edit_option btn btn-success btn-sm cursor_pointer" data-seq="' . $row->code . '" data-type="1"><i id="edt" title="View" class="fa fa-eye"></i></a>
					<a class="edit_option btn btn-info btn-sm cursor_pointer" data-seq="' . $row->code . '" data-type="2"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>
					<a class="btn btn-sm btn-danger delete_option" data-seq="' . $row->code . '"><i id="dlt" title="Delete"  class="fa fa-trash"></i></a></td>';
				$data[] = array(
					$srno,
					$row->code,
					$row->attributeName,
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

	public function saveAttribute()
	{
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$loginrole = "";
		$attribute = trim($this->input->post("option"));
		$isActive = $this->input->post("isActive");
		$attributeCode = $this->input->post("code");
		$ip = $_SERVER['REMOTE_ADDR'];
		$condition2 = array('LOWER(attributeName)' => strtolower($attribute));
		if ($attributeCode != '') {
			$condition2['attributemaster.code!='] = $attributeCode;
		}
		$result = $this->GlobalModel->checkDuplicateRecordNew($condition2, 'attributemaster');
		if ($result == true) {
			$response['status'] = false;
			$response['message'] = 'Duplicate Attribute';
		} else {
			$data = array(
				'attributeName' => $attribute,
				'isActive' => $isActive
			);
			if ($optionCode != '') {
				$data['editID'] = $addID;
				$data['editIP'] = $ip;
				$code = $this->GlobalModel->doEdit($data, 'attributemaster', $attributeCode);
				$code = $optionCode;
				$successMsg = "Attribute Updated Successfully";
				$errorMsg = "Failed To Update Attribute";
				$txt = $attributeCode . " - " . $attribute . " Attribute is updated.";
			} else {
				$data['addID'] = $addID;
				$data['addIP'] = $ip;
				$code = $this->GlobalModel->addWithoutYear($data, 'attributemaster', 'OPT');
				$successMsg = "Attribute Added Successfully";
				$errorMsg = "Failed To Add Attribute";
				$txt = $attributeCode . " - " . $attribute . " Attribute is added.";
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

	public function editAttribute()
	{
		$code = $this->input->post('code');
		$query = $this->GlobalModel->selectQuery("attributemaster.*", 'attributemaster', array('attributemaster.code' => $code));
		if ($query) {
			$result = $query->result_array()[0];
			$data['status'] = true;
			$data['code'] = $result['code'];
			$data['attributeName'] = $result['attributeName'];
		} else {
			$data['status'] = false;
		}
		echo json_encode($data);
	}

	public function editAttributeLine()
	{
		$code = $this->input->post('code');
		$query = $this->GlobalModel->selectQuery("productattributeslineentries.*", 'productattributeslineentries', array('productattributeslineentries.code' => $code));
		if ($query) {
			$result = $query->result_array()[0];
			$data['status'] = true;
			$data['code'] = $result['code'];
			$data['subTitle'] = $result['subTitle'];
		} else {
			$data['status'] = false;
		}
		echo json_encode($data);
	}

	public function deleteAttribute()
	{
		$code = $this->input->post('code');
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$loginrole = "";
		$ip = $_SERVER['REMOTE_ADDR'];
		$attributeName = $this->GlobalModel->selectDataById($code, 'attributemaster')->result()[0]->attributeName;
		$query = $this->GlobalModel->delete($code, 'attributemaster');
		if ($query) {
			$txt = $code . " - " . $attributeName . " option is deleted.";
			$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
			$this->GlobalModel->activity_log($activity_text);
		}
		echo $query;
	}
}
