<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Item extends CI_Controller
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
		$this->rights = $this->GlobalModel->getMenuRights('3.2', $rolecode);
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
			$table_category = 'unitmaster';
			$orderColumns_category = array("unitmaster.*");
			$cond_category = array('unitmaster' . '.isDelete' => 0, 'unitmaster' . '.isActive' => 1);
			$orderby = array("unitmaster.unitName" => 'ASC');
			$data['units'] = $this->GlobalModel->selectQuery($orderColumns_category, $table_category, $cond_category, $orderby);
			$table_category = 'categorymaster';
			$orderColumns_category = array("categorymaster.*");
			$cond_category = array('categorymaster' . '.isDelete' => 0, 'categorymaster' . '.isActive' => 1);
			$data['categorydata'] = $this->GlobalModel->selectQuery($orderColumns_category, $table_category, $cond_category);
			$this->load->view('dashboard/header');

			$this->load->view('dashboard/item/add', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function getItemList()
	{
		$tableName = "itemmaster";
		$lang = $this->session->userdata['logged_in' . $this->session_key]['lang'];
		$search = $this->input->GET("search")['value'];
		$orderColumns = array("itemmaster.code,itemmaster.itemArbName,itemmaster.itemHinName,itemmaster.itemUrduName,categorymaster.categoryName,itemmaster.itemEngName,itemmaster.ingredientUnit,itemmaster.ingredientFactor,unitmaster.unitSName,unitmaster.unitName,u.unitName as ingredientUnitName,u.unitSName as ingredientUnitSName,itemmaster.storageUnit,itemmaster.itemPrice,itemmaster.isActive");
		$condition = array("itemmaster.isDelete !=" => 1);
		$orderBy = array('itemmaster.id' => 'DESC');
		$joinType = array('unitmaster' => 'inner', 'unitmaster u' => 'left', 'categorymaster' => 'left');
		$join = array('unitmaster' => 'unitmaster.code=itemmaster.storageUnit', 'unitmaster u' => 'u.code=itemmaster.ingredientUnit', 'categorymaster' => 'categorymaster.code=itemmaster.categoryCode');
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$extraCondition = "";
		$like = array("categorymaster.categoryName" => $search . "~both", "itemmaster.code" => $search . "~both", "itemmaster.itemEngName" => $search . "~both", "itemmaster.storageUnit" => $search . "~both", "itemmaster.ingredientUnit" => $search . "~both", "unitmaster.unitName" => $search . "~both");
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		$srno = $_GET['start'] + 1;
		//echo $this->db->last_query();
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
					$actionHtml .= '<a href="' . base_url() . 'item/view/' . $row->code . '" class="btn btn-sm btn-success cursor_pointer m-1"><i id="edt" title="View" class="fa fa-eye"></i></a>';
				}
				if ($this->rights != '' && $this->rights['update'] == 1) {
					$actionHtml .= '<a class="btn btn-sm btn-info cursor_pointer edit_item m-1" href="' . base_url() . 'item/edit/' . $row->code . '"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
				}
				if ($this->rights != '' && $this->rights['delete'] == 1) {
					$actionHtml .= '<a class="btn btn-danger btn-sm cursor_pointer delete_item m-1" data-seq="' . $row->code . '"><i id="dlt" title="Delete" class="fa fa-trash"></i></a>';
				}
				if (strtolower($lang) == "urdu") {
					$itemName = $row->itemUrduName;
				} else if (strtolower($lang) == "arabic") {
					$itemName = $row->itemArbName;
				} else if (strtolower($lang) == "hindi") {
					$itemName = $row->itemHinName;
				} else {
					$itemName = $row->itemEngName;
				}
				$data[] = array(
					$srno,
					$row->code,
					$itemName,
					$row->categoryName,
					$row->unitName,
					$row->itemPrice,
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

	public function edit($code)
	{
		if ($this->rights != '' && $this->rights['update'] == 1) {
			$table_category = 'unitmaster';
			$orderColumns_category = array("unitmaster.*");
			$cond_category = array('unitmaster' . '.isDelete' => 0, 'unitmaster' . '.isActive' => 1);
			$orderby = array("unitmaster.unitName" => 'ASC');
			$data['units'] = $this->GlobalModel->selectQuery($orderColumns_category, $table_category, $cond_category, $orderby);
			$data['itemData'] = $this->GlobalModel->selectQuery('itemmaster.*,unitmaster.unitSName,unitmaster.unitName,u.unitSName as ingUnitSName,u.unitName as ingUnitName', 'itemmaster', array('itemmaster.code' => $code), array('unitmaster.unitName' => 'ASC'), array('unitmaster' => 'unitmaster.code=itemmaster.storageUnit', 'unitmaster u' => 'u.code=itemmaster.ingredientUnit'), array('unitmaster' => 'inner', 'unitmaster u' => 'left'));

			$table_category = 'categorymaster';
			$orderColumns_category = array("categorymaster.*");
			$cond_category = array('categorymaster' . '.isDelete' => 0, 'categorymaster' . '.isActive' => 1);
			$data['categorydata'] = $this->GlobalModel->selectQuery($orderColumns_category, $table_category, $cond_category);
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/item/edit', $data);
			$this->load->view('dashboard/footer');
		} else {
			echo $this->load->view('errors/norights.php', true);
		}
	}
	public function view()
	{
		if ($this->rights != '' && $this->rights['view'] == 1) {
			$code = $this->uri->segment(3);
			$table_category = 'unitmaster';
			$orderColumns_category = array("unitmaster.*");
			$cond_category = array('unitmaster' . '.isDelete' => 0, 'unitmaster' . '.isActive' => 1);
			$orderby = array("unitmaster.unitName" => 'ASC');
			$data['units'] = $this->GlobalModel->selectQuery($orderColumns_category, $table_category, $cond_category, $orderby);
			$data['itemData'] = $this->GlobalModel->selectQuery('itemmaster.*,unitmaster.unitSName,unitmaster.unitName,u.unitSName as ingUnitSName,u.unitName as ingUnitName', 'itemmaster', array('itemmaster.code' => $code), array('unitmaster.unitName' => 'ASC'), array('unitmaster' => 'unitmaster.code=itemmaster.storageUnit', 'unitmaster u' => 'u.code=itemmaster.ingredientUnit'), array('unitmaster' => 'inner', 'unitmaster u' => 'left'));

			$table_category = 'categorymaster';
			$orderColumns_category = array("categorymaster.*");
			$cond_category = array('categorymaster' . '.isDelete' => 0, 'categorymaster' . '.isActive' => 1);
			$data['categorydata'] = $this->GlobalModel->selectQuery($orderColumns_category, $table_category, $cond_category);

			$this->load->view('dashboard/header');
			$this->load->view('dashboard/item/view', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}
	public function saveItem()
	{
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$itemName = trim($this->input->post("itemName"));
		$itemUnit = $this->input->post("itemUnit");
		$ingredientUnit = $this->input->post("ingredientUnit");
		$ingredientFactor = $this->input->post("ingredientFactor");
		$itemPrice = $this->input->post("itemPrice");
		$itemDesc = $this->input->post("itemDesc");
		$category = $this->input->post("category");
		$isActive = $this->input->post("isActive");
		$itemArbName = $this->input->post("itemArbName");
		$itemHinName = $this->input->post("itemHinName");
		$itemUrduName = $this->input->post("itemUrduName");
		$itemArbDesc = $this->input->post("itemArbDesc");
		$itemHinDesc = $this->input->post("itemHinDesc");
		$itemUrduDesc = $this->input->post("itemUrduDesc");
		$ip = $_SERVER['REMOTE_ADDR'];
		$condition2 = array('LOWER(itemEngName)' => strtolower($itemName));
		$result = $this->GlobalModel->checkDuplicateRecordNew($condition2, 'itemmaster');
		if ($result == true) {
			$response['status'] = false;
			$response['message'] = 'Duplicate Item Name';
		} else {
			$data = array(
				'itemEngName' => $itemName,
				'itemEngDesc' => $itemDesc,
				'storageUnit' => $itemUnit,
				'categoryCode' => $category,
				'ingredientUnit' => $ingredientUnit,
				'ingredientFactor' => $ingredientFactor,
				'itemPrice' => $itemPrice,
				'itemArbName' => $itemArbName,
				'itemArbDesc' => $itemArbDesc,
				'itemHinName' => $itemHinName,
				'itemHinDesc' => $itemHinDesc,
				'itemUrduName' => $itemUrduName,
				'itemUrduDesc' => $itemUrduDesc,
				'addID' => $addID,
				'addIP' => $ip,
				'isActive' => $isActive
			);
			$code = $this->GlobalModel->addNew($data, 'itemmaster', 'ITEM');
			if ($code != 'false') {
				$txt = $code . " - " . $itemName . " item is added.";
				$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
				$this->GlobalModel->activity_log($activity_text);
				$response['status'] = true;
				$response['message'] = 'Item Added Successfully';
			} else {
				$response['status'] = false;
				$response['message'] = 'Failed to Add Item';
			}
		}
		echo json_encode($response);
	}
	public function updateItem()
	{
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$itemCode = $this->input->post("modalItemCode");
		$itemUnit = $this->input->post("modalItemUnit");
		$ingredientUnit = $this->input->post("modalIngredientUnit");
		$ingredientFactor = $this->input->post("modalIngredientFactor");
		$itemPrice = $this->input->post("modalItemPrice");
		$itemName = $this->input->post("modalItemName");
		$itemDesc = $this->input->post("modalItemDesc");
		$itemArbName = $this->input->post("modalItemArbName");
		$itemHinName = $this->input->post("modalItemHinName");
		$itemUrduName = $this->input->post("modalItemUrduName");
		$itemArbDesc = $this->input->post("modalItemArbDesc");
		$itemHinDesc = $this->input->post("modalItemHinDesc");
		$itemUrduDesc = $this->input->post("modalItemUrduDesc");
		$isActive = $this->input->post("modalisActive");
		$category = $this->input->post("category");
		$ip = $_SERVER['REMOTE_ADDR'];
		$condition2 = array('LOWER(itemEngName)' => strtolower($itemName), 'code!=' => $itemCode);
		$result = $this->GlobalModel->checkDuplicateRecordNew($condition2, 'itemmaster');
		if ($result == true) {
			$response['status'] = false;
			$response['message'] = 'Duplicate Item Name';
		} else {
			$data = array(
				'itemEngName' => $itemName,
				'itemEngDesc' => $itemDesc,
				'storageUnit' => $itemUnit,
				'ingredientUnit' => $ingredientUnit,
				'ingredientFactor' => $ingredientFactor,
				'itemPrice' => $itemPrice,
				'itemArbName' => $itemArbName,
				'itemArbDesc' => $itemArbDesc,
				'itemHinName' => $itemHinName,
				'itemHinDesc' => $itemHinDesc,
				'itemUrduName' => $itemUrduName,
				'itemUrduDesc' => $itemUrduDesc,
				'categoryCode' => $category,
				'editIP' => $ip,
				'editID' => $addID,
				'isActive' => $isActive
			);
			$code = $this->GlobalModel->doEdit($data, 'itemmaster', $itemCode);
			if ($code != 'false') {
				$txt = $code . " - " . $itemName . " item is updated.";
				$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
				$this->GlobalModel->activity_log($activity_text);
				$response['status'] = true;
				$response['message'] = 'Item Updated Successfully';
			} else {
				$response['status'] = false;
				$response['message'] = 'Failed to Update Item';
			}
		}
		echo json_encode($response);
	}
	public function deleteItem()
	{
		$code = $this->input->post('code');
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$loginrole = "";
		$ip = $_SERVER['REMOTE_ADDR'];
		$itemName = $this->GlobalModel->selectDataById($code, 'itemmaster')->result()[0]->itemName;
		$query = $this->GlobalModel->delete($code, 'itemmaster');
		if ($query) {
			$txt = $code . " - " . $itemName . " item is deleted.";
			$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
			$this->GlobalModel->activity_log($activity_text);
		}
		echo $query;
	}
	public function editItem()
	{
		$itemCode = $this->input->post('itemCode');
		$ip = $_SERVER['REMOTE_ADDR'];
		$query = $this->GlobalModel->selectQuery('itemmaster.*', 'itemmaster', array('itemmaster.code' => $itemCode));
		if ($query) {
			$data['r'] = $query->result()[0];
			$data['status'] = true;
		} else {
			$data['status'] = false;
		}
		echo json_encode($data);
	}
}
