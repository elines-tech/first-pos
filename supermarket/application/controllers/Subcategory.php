<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Subcategory extends CI_Controller
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
		$this->rights = $this->GlobalModel->getMenuRights('2.3', $rolecode);
		if ($this->rights == '') {
			$this->load->view('errors/norights.php');
		}
	}
	public function listRecords()
	{
		if ($this->rights != '' && $this->rights['view'] == 1) {
			$data['insertRights'] = $this->rights['insert'];
			$data['categorydata'] = $this->GlobalModel->selectActiveData('categorymaster');
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/subcategory/list', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}
	public function getCategoryList()
	{
		$tableName = "subcategorymaster";
		$search = $this->input->GET("search")['value'];
		$orderColumns = array("subcategorymaster.code,categorymaster.categoryName,subcategorymaster.subcategoryName,subcategorymaster.icon,subcategorymaster.isActive");
		$condition = array();
		$orderBy = array('subcategorymaster.id' => 'DESC');
		$joinType = array('categorymaster' => 'inner');
		$join = array('categorymaster' => 'categorymaster.code=subcategorymaster.categoryCode');
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$extraCondition = " subcategorymaster.isDelete=0 OR subcategorymaster.isDelete IS NULL";
		$like = array("subcategorymaster.subcategoryName" => $search . "~both", "subcategorymaster.code" => $search . "~both", "categorymaster.categoryName" => $search . "~both");
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
					$actionHtml .= ' <a class="edit_category btn btn-success btn-sm cursor_pointer m-1" data-seq="' . $row->code . '" data-type="1"><i id="edt" title="View" class="fa fa-eye"></i></a>';
				}
				if ($this->rights != '' && $this->rights['update'] == 1) {
					$actionHtml .= '<a class="edit_category btn btn-info btn-sm cursor_pointer m-1" data-seq="' . $row->code . '" data-type="2"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
				}
				if ($this->rights != '' && $this->rights['delete'] == 1) {
					$actionHtml .= '<a class="btn btn-sm btn-danger delete_category m-1" data-seq="' . $row->code . '"><i id="dlt" title="Delete"  class="fa fa-trash"></i></a>';
				}
				$iconPath = '';
				if ($row->icon != '') {
					$iconPath = "<img src='" . base_url() . $row->icon . "' height='50' width='50' alt='Category Image'>";
				}
				$data[] = array(
					$srno,
					$row->code,
					$row->categoryName,
					$row->subcategoryName,
					//$row->subcategorySName,
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
		$subcategoryName = trim($this->input->post("subcategoryName"));
		$subcategorySName = $this->input->post("subcategorySName");
		$categoryCode = $this->input->post("categoryCode");
		$description = $this->input->post("description");
		$subcategoryCode = $this->input->post("code");
		$previousIcon = $this->input->post("previousIcon");
		$isActive = $this->input->post("isActive");
		$ip = $_SERVER['REMOTE_ADDR'];
		$condition2 = array('LOWER(subcategoryName)' => strtolower($subcategoryName));
		if ($subcategoryCode != '') {
			$condition2['subcategorymaster.code!='] = $subcategoryCode;
		}
		$result = $this->GlobalModel->checkDuplicateRecordNew($condition2, 'subcategorymaster');
		if ($result == true) {
			$response['status'] = false;
			$response['message'] = 'Duplicate Subcategory';
		} else {
			$data = array(
				'categoryCode' => $categoryCode,
				'subcategoryName' => $subcategoryName,
				'subcategorySName' => $subcategorySName,
				'description' => $description,
				'isActive' => $isActive
			);
			if ($subcategoryCode != '') {
				$data['editID'] = $addID;
				$data['editIP'] = $ip;
				$code = $this->GlobalModel->doEdit($data, 'subcategorymaster', $subcategoryCode);
				$code = $subcategoryCode;
				$successMsg = "Subcatgeory Updated Successfully";
				$errorMsg = "Failed To Update Subcatgeory";
				$txt = $code . " - " . $subcategoryName . " Subcategory is updated.";
			} else {
				$data['addID'] = $addID;
				$data['addIP'] = $ip;
				$code = $this->GlobalModel->addWithoutYear($data, 'subcategorymaster', 'SCAT');
				$successMsg = "Category Added Successfully";
				$errorMsg = "Failed To Add Category";
				$txt = $code . " - " . $subcategoryName . " subcategory is added.";
			}
			if ($code != 'false') {
				$categoryIcon =  "";
				if (!empty($_FILES['categoryIcon']['name'])) {
					if (file_exists($previousIcon)) {
						unlink($previousIcon);
					}
					$uploadDir = "upload/subcategory/$cmpcode";
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
					$filedoc = $this->GlobalModel->doEdit($subData, 'subcategorymaster', $code);
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
			$categoryQuery = $this->GlobalModel->selectQuery("categorymaster.categoryName,subcategorymaster.code,subcategorymaster.categoryCode,subcategorymaster.subcategoryName,subcategorymaster.subcategorySName,subcategorymaster.description,subcategorymaster.icon,subcategorymaster.isActive", 'subcategorymaster', array('subcategorymaster.code' => $code),array(),array('categorymaster' => 'categorymaster.code=subcategorymaster.categoryCode'),array('categorymaster' => 'inner'));
			if ($categoryQuery) {
				$result = $categoryQuery->result_array()[0];
				$data['status'] = true;
				$data['code'] = $result['code'];
				$data['categoryCode'] = $result['categoryCode'];
				$data['categoryName'] = $result['categoryName'];
				$data['subcategoryName'] = $result['subcategoryName'];
				$data['subcategorySName'] = $result['subcategorySName'];
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
			$subcategoryName = $this->GlobalModel->selectDataById($code, 'subcategorymaster')->result()[0]->subcategoryName;
			$query = $this->GlobalModel->delete($code, 'subcategorymaster');
			if ($query) {
				$txt = $code . " - " . $subcategoryName . " subcategory is deleted.";
				$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
				$this->GlobalModel->activity_log($activity_text);
			}
			echo $query;
		} else {
			echo false;
		}
	}
}
