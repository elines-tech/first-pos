<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Recipe extends CI_Controller
{
	var $session_key;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form', 'url', 'html');
		$this->load->model('GlobalModel');
		$this->session_key = $this->session->userdata('key' . SESS_KEY);
		$rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
			redirect('Login', 'refresh');
		}
		$this->rights = $this->GlobalModel->getMenuRights('2.4', $rolecode);
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
			$this->load->view('dashboard/recipe/list', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function add()
	{
		if ($this->rights != '' && $this->rights['insert'] == 1) {
			$data['insertRights'] = $this->rights['insert'];
			$data['product'] = $this->GlobalModel->selectActiveData('productmaster');
			$data['unitmaster'] = $this->GlobalModel->selectActiveData('unitmaster');
			$data['itemList'] = $this->GlobalModel->selectQuery('itemmaster.code,itemmaster.itemEngName,unitmaster.unitName as storageUnitName', 'itemmaster', array('itemmaster.isActive' => 1), [], array('unitmaster' => 'unitmaster.code=itemmaster.storageUnit'), array('unitmaster' => 'inner'));
			$this->load->view('dashboard/commonheader');
			$this->load->view('dashboard/recipe/add', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function getRecipeList()
	{
		$tableName = "recipecard";
		$search = $this->input->GET("search")['value'];
		$orderColumns = array("recipecard.code,recipecard.productCode,productmaster.productEngName,ifnull(count(recipelineentries.id),0) as itemCount,recipecard.isActive,recipecard.recipeDirection");
		$condition = array("recipecard.isDelete !=" => 1);
		$orderBy = array('recipecard.id' => 'DESC');
		$joinType = array('productmaster' => 'left', 'recipelineentries' => 'inner');
		$join = array('productmaster' => 'productmaster.code=recipecard.productCode', 'recipelineentries' => 'recipelineentries.recipeCode=recipecard.code');
		$groupByColumn = array('recipelineentries.recipeCode');
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$extraCondition = "";
		$like = array("recipecard.code" => $search . "~both", "productmaster.productEngName" => $search . "~both");
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		//echo $this->db->last_query();
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
					$actionHtml .= '<a href="' . base_url() . 'recipe/view/' . $row->code . '" class="btn btn-success btn-sm cursor_pointer m-1"><i id="view" title="View" class="fa fa-eye"></i></a>';
				}
				if ($this->rights != '' && $this->rights['update'] == 1) {
					$actionHtml .= '<a href="' . base_url() . 'recipe/edit/' . $row->code . '" class="btn btn-info btn-sm cursor_pointer m-1"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
				}
				if ($this->rights != '' && $this->rights['delete'] == 1) {
					$actionHtml .= '<a class="btn btn-danger btn-sm cursor_pointer delete_recipe m-1" id="' . $row->code . '" ><i id="dlt" title="Delete" class="fa fa-trash"></i></a>';
				}

				$data[] = array(
					$srno,
					$row->code,
					$row->productEngName,
					$row->itemCount,
					$status,
					$actionHtml
				);
				$srno++;
			}
			$dataCount = sizeof($this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, array(), '', '', $groupByColumn, $extraCondition)->result());
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

	public function edit()
	{
		if ($this->rights != '' && $this->rights['update'] == 1) {
			$data['updateRights'] = $this->rights['update'];
			$code = $this->uri->segment(3);
			$data['product'] = $this->GlobalModel->selectActiveData('productmaster');
			$data['itemList'] = $this->GlobalModel->selectQuery('itemmaster.code,itemmaster.itemEngName,unitmaster.unitName as storageUnitName', 'itemmaster', array('itemmaster.isActive' => 1), [], array('unitmaster' => 'unitmaster.code=itemmaster.storageUnit'), array('unitmaster' => 'inner'));
			$data['unitmaster'] = $this->GlobalModel->selectActiveData('unitmaster');
			$data['recipeData'] = $this->GlobalModel->selectQuery('recipecard.*,productmaster.productEngName', 'recipecard', array('recipecard.code' => $code), array(), array('productmaster' => 'productmaster.code=recipecard.productCode'), array('productmaster' => 'inner'));
			$data['recipelineentries'] = $this->GlobalModel->selectQuery('recipelineentries.*', 'recipelineentries', array('recipelineentries.recipeCode' => $code, 'recipelineentries.isActive' => 1));
			$this->load->view('dashboard/commonheader');
			$this->load->view('dashboard/recipe/edit', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}
	public function view()
	{
		if ($this->rights != '' && $this->rights['view'] == 1) {
			$code = $this->uri->segment(3);
			$data['product'] = $this->GlobalModel->selectActiveData('productmaster');
			$data['itemList'] = $this->GlobalModel->selectQuery('itemmaster.code,itemmaster.itemEngName,unitmaster.unitName as storageUnitName', 'itemmaster',  array('itemmaster.isActive' => 1), [], array('unitmaster' => 'unitmaster.code=itemmaster.storageUnit'), array('unitmaster' => 'inner'));
			$data['unitmaster'] = $this->GlobalModel->selectActiveData('unitmaster');
			$data['recipeData'] = $this->GlobalModel->selectQuery('recipecard.*,productmaster.productEngName', 'recipecard', array('recipecard.code' => $code), array(), array('productmaster' => 'productmaster.code=recipecard.productCode'), array('productmaster' => 'inner'));
			$data['recipelineentries'] = $this->GlobalModel->selectQuery('recipelineentries.*', 'recipelineentries', array('recipelineentries.recipeCode' => $code, 'recipelineentries.isActive' => 1));
			$this->load->view('dashboard/commonheader');
			$this->load->view('dashboard/recipe/view', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}
	public function saveRecipe()
	{
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$recipeCode = trim($this->input->post("recipeCode"));
		$productCode = trim($this->input->post("productCode"));
		$recipeDirection = $this->input->post("recipeDirection");
		$isActive = $this->input->post("isActive");
		$ip = $_SERVER['REMOTE_ADDR'];
		$condition2 = array('productCode' => $productCode);
		if ($recipeCode != '') {
			$condition2['recipecard.code!='] = $recipeCode;
		}
		$result = $this->GlobalModel->checkDuplicateRecordNew($condition2, 'recipecard');
		if ($result == true) {
			$response['status'] = false;
			$response['message'] = 'Duplicate Receipe';
		} else {
			$data = array(
				'productCode' => $productCode,
				'recipeDirection' => $recipeDirection,
				'isActive' => 1
			);
			if ($recipeCode != '') {
				$data['editID'] = $addID;
				$data['editIP'] = $ip;
				$code = $this->GlobalModel->doEdit($data, 'recipecard', $recipeCode);
				$code = $recipeCode;
				$successMsg = 'Recipe updated Successfully';
				$warningMsg = "Failed to update Recipe";
				$txt = $code . " - " . $productCode . " recipe is updated.";
			} else {
				$data['addID'] = $addID;
				$data['addIP'] = $ip;
				$code = $this->GlobalModel->addNew($data, 'recipecard', 'REC');
				$successMsg = 'Recipe Added Successfully';
				$warningMsg = "Failed to Add Recipe";
				$txt = $code . " - " . $productCode . " recipe is added.";
			}
			if ($code != 'false') {
				$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
				$this->GlobalModel->activity_log($activity_text);
				$response['status'] = true;
				$response['recipeCode'] = $code;
				$response['message'] = $successMsg;
			} else {
				$response['status'] = false;
				$response['message'] = $warningMsg;
			}
		}
		echo json_encode($response);
	}

	public function saveRecipeLine()
	{
		$role = ($this->session->userdata['logged_in' . $this->session_key]['role']);
		$addID = ($this->session->userdata['logged_in' . $this->session_key]['code']);
		$ip = $_SERVER['REMOTE_ADDR'];
		$recipeCode = $this->input->post("recipeCode");
		$recipeLineCode = $this->input->post("recipeLineCode");
		$itemCode = $this->input->post("itemCode");
		$itemUnit = $this->input->post("itemUnit");
		$itemQty = $this->input->post("itemQty");
		$itemCost = $this->input->post("itemCost");
		$isCustomizable = $this->input->post("isCustomizable");
		$data = array(
			'recipeCode' => $recipeCode,
			'itemCode' => $itemCode,
			'unitCode' => $itemUnit,
			'itemQty' => $itemQty,
			'itemCost' => $itemCost,
			'isCustomizable' => $isCustomizable,
			'isActive' => 1
		);
		if ($recipeLineCode != '') {
			$data['editIP'] = $ip;
			$data['editID'] = $addID;
			$result = $this->GlobalModel->doEdit($data, 'recipelineentries', $recipeLineCode);
			$result = $recipeLineCode;
		} else {
			$data['addIP'] = $ip;
			$data['addID'] = $addID;
			$result = $this->GlobalModel->addNew($data, 'recipelineentries', 'RECL');
		}
	}

	public function saveLineData()
	{
		$role = ($this->session->userdata['logged_in' . $this->session_key]['role']);
		$addID = ($this->session->userdata['logged_in' . $this->session_key]['code']);
		$ip = $_SERVER['REMOTE_ADDR'];
		$recipeCode = $this->input->post("recipeCode");
		$recipeLineCode = $this->input->post("recipeLineCode");
		$itemCode = $this->input->post("itemCode");
		$itemUnit = $this->input->post("itemUnit");
		$itemQty = $this->input->post("itemQty");
		$itemCost = $this->input->post("itemCost");
		$isCustomizable = $this->input->post("isCustomizable");
		$resultLine = false;
		if (is_array($itemCode) && !empty($itemCode)) {
			for ($i = 0; $i < count($itemCode); $i++) {
				$data = array(
					'recipeCode' => $recipeCode,
					'itemCode' => $itemCode[$i],
					'unitCode' => $itemUnit[$i],
					'itemQty' => $itemQty[$i],
					'itemCost' => $itemCost[$i],
					'isCustomizable' => $isCustomizable[$i],
					'isActive' => 1
				);
				if ($recipeLineCode[$i] != '') {
					$data['editIP'] = $ip;
					$data['editID'] = $addID;
					$result = $this->GlobalModel->doEdit($data, 'recipelineentries', $recipeLineCode[$i]);
					if ($result != "false") $resultLine = true;
					else $resultLine = false;
				} else {
					$data['addIP'] = $ip;
					$data['addID'] = $addID;
					$result = $this->GlobalModel->addNew($data, 'recipelineentries', 'RECL');
					if ($result != "false") $resultLine = true;
					else $resultLine = false;
				}
			}
		}
		if ($resultLine) {
			$res['status'] = true;
			$res['message'] = "Receipe updated successfully";
		} else {
			$res['status'] = false;
			$res['message'] = "No changes were found to update";
		}
		echo json_encode($res);
		exit;
	}

	public function deleteRecipe()
	{
		$code = $this->input->post('code');
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$loginrole = "";
		$ip = $_SERVER['REMOTE_ADDR'];
		$productCode = $this->GlobalModel->selectDataById($code, 'recipecard')->result()[0]->productCode;
		$query = $this->GlobalModel->delete($code, 'recipecard');
		if ($query) {
			$txt = $code . " - " . $productCode . " recipe is deleted.";
			$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
			$this->GlobalModel->activity_log($activity_text);
		}
		echo $query;
	}

	public function deleteRecipeLine()
	{
		$date = date('Y-m-d H:i:s');
		$lineCode = $this->input->post('lineCode');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$query = $this->GlobalModel->delete($lineCode, 'recipelineentries');
		if ($query) {
			$txt = $lineCode . " recipe item deleted";
			$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
			$this->GlobalModel->activity_log($activity_text);
			$msg = true;
			echo json_encode($msg);
		} else {
			$msg = false;
			echo json_encode($msg);
		}
	}

	public function getItemIngredientUnit()
	{
		$itemCode = $this->input->post('itemCode');
		$storageUnit = '';
		$checkItem = $this->GlobalModel->selectQuery('itemmaster.ingredientUnit,unitmaster.code', 'itemmaster', array('itemmaster.code' => $itemCode), array(), array('unitmaster' => 'unitmaster.code=itemmaster.ingredientUnit'), array('unitmaster' => 'inner'));
		if ($checkItem && $checkItem->num_rows() > 0) {
			$storageUnit = $checkItem->result_array()[0]['code'];
		}
		echo $storageUnit;
	}
}
