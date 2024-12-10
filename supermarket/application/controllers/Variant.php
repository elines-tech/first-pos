<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Variant extends CI_Controller
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
		$this->rights = $this->GlobalModel->getMenuRights('2.4', $rolecode);
		if ($this->rights == '') {
			$this->load->view('errors/norights.php');
		}
	}

	public function listRecords()
	{
		if ($this->rights != '' && $this->rights['insert'] == 1) {
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/variant/list');
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function add()
	{
		if ($this->rights != '' && $this->rights['insert'] == 1) {
			$data['product'] = $this->GlobalModel->selectActiveData('productmaster');
			$data['unitmaster'] = $this->GlobalModel->selectActiveData('unitmaster');
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/variant/add', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function getVariantList()
	{
		$dataCount = 0;
		$data = array();
		$tableName = "productvariants";
		$search = $this->input->GET("search")['value'];
		$orderColumns = array("productvariants.*,productmaster.productEngName");
		$condition = array();
		$orderBy = array('productvariants.id' => 'DESC');
		$joinType = array('productmaster' => 'inner');
		$join = array('productmaster' => 'productmaster.code=productvariants.productCode');
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$extraCondition = " productvariants.isDelete=0 OR productvariants.isDelete IS NULL";
		$like = array("productmaster.branchName" => $search . "~both");
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		$srno = $_GET['start'] + 1;
		if ($Records) {
			foreach ($Records->result() as $row) {
				$code = $row->code;
				$actionHtml = '<a href="' . base_url() . 'variant/view/' . $row->code . '" class="btn btn-success btn-sm cursor_pointer m-1"><i id="view" title="View" class="fa fa-eye"></i></a>';
				$data[] = array(
					$srno,
					$row->productEngName,
					$row->variants,
					$row->sellingUnit,
					$row->sellingQty,
					$row->price,
					$actionHtml
				);
				$srno++;
			}
			$dataCount = sizeof($this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, array(), '', '', '', $extraCondition)->result());
		}
		$output = array(
			"draw"			  =>     intval($_GET["draw"]),
			"recordsTotal"    =>      $dataCount,
			"recordsFiltered" =>     $dataCount,
			"data"            =>     $data
		);
		echo json_encode($output);
	}

	public function saveVariants()
	{
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$sku = $this->input->post("sku");
		$productCode = $this->input->post("productCode");
		$sellingUnit = $this->input->post("sellingUnit");
		$sellingQty = $this->input->post("sellingQty");
		$price = $this->input->post("price");
		$ip = $_SERVER['REMOTE_ADDR'];
		$data = array(
			'sku' => $sku,
			'productCode' => $productCode,
			'variants' => $variants,
			'sellingQty' => $sellingQty,
			'sellingUnit' => $sellingUnit,
			'price' => $price,
			'isActive' => 1,
			'addID' => $addID,
			'addIP' => $ip,
		);
		$code = $this->GlobalModel->addNew($data, 'productvariants', 'IN');
		if ($code != 'false') {
			$txt = $code . " - Variant added for product " . $productCode;
			$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
			$this->GlobalModel->activity_log($activity_text);
			$response['status'] = true;
			$response['message'] = "Variant added successfully";
		} else {
			$response['status'] = false;
			$response['message'] = 'Failed to add variant';
		}
		echo json_encode($response);
	}

	public function delete()
	{
		$code = $this->input->post('code');
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$query = $this->GlobalModel->delete($code, 'productvariants');
		if ($query) {
			$txt = $code . " product variants is deleted.";
			$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
			$this->GlobalModel->activity_log($activity_text);
		}
		echo $query;
	}

	public function getAttributeList()
	{
		$productCode = $this->input->post('productCode');
		$attrHtml = '';
		$attrList = $this->GlobalModel->selectQuery('attributemaster.attributeName', 'productattributes', array('productattributes.code' => $productCode), array(), array('attributemaster' => 'attributemaster.code=productattributes.attributesCode'), array('attributemaster' => 'inner'));
		if ($attrList && $attrList->num_rows() > 0) {
			$attrHtml = "<option value=''>Select attribute</option>";
			foreach ($attrList->result_array() as $attr) {
				$attrHtml = $attrHtml . "<option value='" . $attr->attributeName . "'>" . $attr->attributeName . "</option>";
			}
		}
		echo $attrHtml;
	}

	public function getAttributeOption()
	{
		$attribute = $this->input->post('attribute');
		$optionHtml = '';
		$options = $this->GlobalModel->selectQuery('attributemaster.attributeName', 'productattributeslineentries', array('productattributeslineentries.productAttCode' => $attribute), array(), array('attributemaster' => 'attributemaster.code=productattributes.attributesCode'), array('attributemaster' => 'inner'));
		if ($options && $options->num_rows() > 0) {
			$optionHtml = "<option value=''>Select option</option>";
			foreach ($options->result_array() as $op) {
				$optionHtml = $optionHtml . "<option value='" . $op->attributeName . "'>" . $attr->attributeName . "</option>";
			}
		}
		echo $optionHtml;
	}
}
