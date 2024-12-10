<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category extends CI_Controller
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
		$this->rights = $this->GlobalModel->getMenuRights('2.2', $rolecode);
		if ($this->rights == '') {
			$this->load->view('errors/norights.php');
		}
	}
	public function listRecords()
	{
		if ($this->rights != '' && $this->rights['view'] == 1) {
			$data['insertRights'] = $this->rights['insert'];
			$this->load->view('dashboard/header');

			$this->load->view('dashboard/category/list', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}
	public function getCategoryList()
	{
		$tableName = "categorymaster";
		$search = $this->input->GET("search")['value'];
		$orderColumns = array("categorymaster.code,categorymaster.categoryName,categorymaster.icon,categorymaster.isActive");
		$condition = array();
		$orderBy = array('categorymaster.id' => 'DESC');
		$joinType = array();
		$join = array();
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$extraCondition = " categorymaster.isDelete=0 OR categorymaster.isDelete IS NULL";
		$like = array("categorymaster.categoryName" => $search . "~both");
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
					$actionHtml .= '<a class="edit_category btn btn-success m-1 btn-sm cursor_pointer" data-seq="' . $row->code . '" data-type="1"><i id="edt" title="View" class="fa fa-eye"></i></a>';
				}
				if ($this->rights != '' && $this->rights['update'] == 1) {
					$actionHtml .= '<a class="edit_category btn btn-info m-1 btn-sm cursor_pointer" data-seq="' . $row->code . '" data-type="2"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
				}
				if ($this->rights != '' && $this->rights['delete'] == 1) {
					$actionHtml .= '<a class="btn btn-sm btn-danger m-1 delete_category" data-seq="' . $row->code . '"><i id="dlt" title="Delete"  class="fa fa-trash"></i></a>';
				}
				$actionHtml .= '</div>';
				$iconPath = '';
				if ($row->icon != '') {
					$iconPath = "<img src='" . base_url() . $row->icon . "' height='50' width='50' alt='Category Image'>";
				}
				$data[] = array(
					$srno,
					$row->code,
					$row->categoryName,
					//$row->categorySName,
					$iconPath,
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

	public function saveCategory()
	{
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$loginrole = "";
		$cmpcode = $this->GlobalModel->getCompcode();
		$categoryName = trim($this->input->post("categoryName"));
		$categorySName = $this->input->post("categorySName");
		$description = $this->input->post("description");
		$categoryCode = $this->input->post("code");
		$previousIcon = $this->input->post("previousIcon");
		$isActive = $this->input->post("isActive");
		$ip = $_SERVER['REMOTE_ADDR'];
		$condition2 = array('LOWER(categoryName)' => strtolower($categoryName));
		if ($categoryCode != '') {
			$condition2['categorymaster.code!='] = $categoryCode;
		}
		$result = $this->GlobalModel->checkDuplicateRecordNew($condition2, 'categorymaster');
		if ($result == true) {
			$response['status'] = false;
			$response['message'] = 'Duplicate Category';
		} else {
			$data = array(
				'categoryName' => $categoryName,
				'categorySName' => $categorySName,
				'description' => $description,
				'isActive' => $isActive
			);
			if ($categoryCode != '') {
				$data['editID'] = $addID;
				$data['editIP'] = $ip;
				$code = $this->GlobalModel->doEdit($data, 'categorymaster', $categoryCode);
				$code = $categoryCode;
				$successMsg = "Catgeory Updated Successfully";
				$errorMsg = "Failed To Update Catgeory";
				$txt = $code . " - " . $categoryName . " category is updated.";
			} else {
				$data['addID'] = $addID;
				$data['addIP'] = $ip;
				$code = $this->GlobalModel->addWithoutYear($data, 'categorymaster', 'CAT');
				$successMsg = "Category Added Successfully";
				$errorMsg = "Failed To Add Category";
				$txt = $code . " - " . $categoryName . " category is added.";
			}
			if ($code != 'false') {
				$categoryIcon =  "";
				if (!empty($_FILES['categoryIcon']['name'])) {
					if (file_exists($previousIcon)) {
						unlink($previousIcon);
					}
					$uploadDir = "upload/category/$cmpcode";
					if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
					$tmpFile = $_FILES['categoryIcon']['tmp_name'];
					$ext = pathinfo($_FILES['categoryIcon']['name'], PATHINFO_EXTENSION);
					$filename = $uploadDir . '/' . $code . '-' . time() . '.' . $ext;
					move_uploaded_file($tmpFile, $filename);
					if (file_exists($filename)) {
						$categoryIcon = $filename;
					}
					$subData = array(
						'icon' => $categoryIcon
					);
					$filedoc = $this->GlobalModel->doEdit($subData, 'categorymaster', $code);
				}
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

	public function editCategory()
	{
		if ($this->rights != '' && ($this->rights['update'] == 1 || $this->rights['view'] == 1)) {
			$code = $this->input->post('code');
			$categoryQuery = $this->GlobalModel->selectQuery("categorymaster.code,categorymaster.categoryName,categorymaster.categorySName,categorymaster.description,categorymaster.icon,categorymaster.isActive", 'categorymaster', array('categorymaster.code' => $code));
			if ($categoryQuery) {
				$result = $categoryQuery->result_array()[0];
				$data['status'] = true;
				$data['code'] = $result['code'];
				$data['categoryName'] = $result['categoryName'];
				$data['categorySName'] = $result['categorySName'];
				$data['description'] = $result['description'];
				$data['isActive'] = $result['isActive'];
				$data['icon'] = '';
				$data['previousIcon'] = $result['icon'];
				if ($result['icon'] != '') {
					$data['icon'] = base_url() . $result['icon'];
				}
			} else {
				$data['status'] = false;
			}
		} else {
			$data['status'] = false;
		}
		echo json_encode($data);
	}

	public function deleteCategory()
	{
		if ($this->rights != '' && $this->rights['delete'] == 1) {
			$code = $this->input->post('code');
			$date = date('Y-m-d H:i:s');
			$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
			$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
			$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
			$loginrole = "";
			$ip = $_SERVER['REMOTE_ADDR'];
			$categoryName = $this->GlobalModel->selectDataById($code, 'categorymaster')->result()[0]->categoryName;
			$query = $this->GlobalModel->delete($code, 'categorymaster');
			if ($query) {
				$txt = $code . " - " . $categoryName . " category is deleted.";
				$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
				$this->GlobalModel->activity_log($activity_text);
			}
			echo $query;
		}
	}
}
