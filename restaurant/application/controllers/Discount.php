<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Discount extends CI_Controller
{
	var $session_key;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form', 'url', 'html');
		$this->load->model('GlobalModel');
		$this->load->library('QrCodeGenerator');
		$this->load->library('form_validation');
		$this->session_key = $this->session->userdata('key' . SESS_KEY);
		$rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
			redirect('Login', 'refresh');
		}
		$this->rights = $this->GlobalModel->getMenuRights('10.1', $rolecode);
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
			$this->load->view('dashboard/discount/list', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}
	public function getDiscountList()
	{
		$tableName = "discountmaster";
		$search = $this->input->GET("search")['value'];
		$orderColumns = array("discountmaster.code,discountmaster.discount,discountmaster.isActive");
		$condition = array('discountmaster.isActive');
		$orderBy = array('discountmaster.id' => 'DESC');
		$joinType = array();
		$join = array();
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$extraCondition = " discountmaster.isDelete=0 OR discountmaster.isDelete IS NULL";
		$like = array("discountmaster.discount" => $search . "~both","discountmaster.code" => $search . "~both");
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
					$actionHtml .= '<a id="view" class="btn btn-sm btn-success cursor_pointer edit_discount m-1" data-seq="' . $row->code . '" data-type="1"><i id="edt" title="View" class="fa fa-eye"></i></a>';
				}
				if ($this->rights != '' && $this->rights['update'] == 1) {
					$actionHtml .= '<a id="edit" class="btn btn-sm btn-info cursor_pointer edit_discount m-1" data-seq="' . $row->code . '" data-type="2"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
				}
				if ($this->rights != '' && $this->rights['delete'] == 1) {
					$actionHtml .= '<a id="delete" class="btn btn-sm btn-danger cursor_pointer delete_discount m-1" data-seq="' . $row->code . '"><i id="dlt" title="Delete" class="fa fa-trash"></i></a></div>';
				}

				$data[] = array(
					$srno,
					$row->code,
					$row->discount,
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

	public function saveDiscount()
	{
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$discount = $this->input->post("discount");
		$discountCode = $this->input->post("code");
		$isActive = $this->input->post("isActive");
		$ip = $_SERVER['REMOTE_ADDR'];
		$condition2 = array('discountmaster.discount' => $discount);
		if ($discountCode != '') {
			$condition2['discountmaster.code!='] = $discountCode;
		}
		$result = $this->GlobalModel->checkDuplicateRecordNew($condition2, 'discountmaster');
		if ($result == true) {
			$response['status'] = false;
			$response['message'] = 'Duplicate discount';
		} else {
			$data = array(
				'discount' => $discount,
				'isActive' => $isActive
			);
			if ($discountCode != '') {
				$data['editID'] = $addID;
				$data['editIP'] = $ip;
				$code = $this->GlobalModel->doEdit($data, 'discountmaster', $discountCode);
				$code = $discountCode;
				$successMsg = "Discount Updated Successfully";
				$errorMsg = "Failed To Update Discount";
				$txt = $code . " - " . $discount . " discount is updated.";
			} else {
				$data['addID'] = $addID;
				$data['addIP'] = $ip;
				$code = $this->GlobalModel->addWithoutYear($data, 'discountmaster', 'DIS');
				$successMsg = "Discount Added Successfully";
				$errorMsg = "Failed To Add Discount";
				$txt = $code . " - " . $discount . " discount is added.";
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

	public function editDiscount()
	{
		$code = $this->input->post('code');
		$TableQuery = $this->GlobalModel->selectQuery("discountmaster.code,discountmaster.discount,discountmaster.isActive", 'discountmaster', array('discountmaster.code' => $code));
		if ($TableQuery) {
			$result = $TableQuery->result_array()[0];
			$data['status'] = true;
			$data['code'] = $result['code'];
			$data['discount'] = $result['discount'];
			$data['isActive'] = $result['isActive'];
		} else {
			$data['status'] = false;
		}
		echo json_encode($data);
	}

	public function deleteDiscount()
	{
		$code = $this->input->post('code');
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$discount = $this->GlobalModel->selectDataByField('code', $code, 'discountmaster')->result()[0]->discount;
		$query = $this->GlobalModel->delete($code, 'discountmaster');
		if ($query) {
			$txt = $code . " - " . $discount . " discount is deleted.";
			$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
			$this->GlobalModel->activity_log($activity_text);
		}
		echo $query;
	}
}
