<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stock extends CI_Controller
{
	var $session_key;
	protected $rolecode,$branchCode; 
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
		$this->rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		$this->branchCode = $this->session->userdata['logged_in' . $this->session_key]['userBranch'];
		$this->rights = $this->GlobalModel->getMenuRights('3.4', $this->rolecode);
		if ($this->rights == '') {
			$this->load->view('errors/norights.php');
		}
	}

	public function listRecords()
	{
		if ($this->rights != '' && $this->rights['view'] == 1) {
			if($this->branchCode!=""){
				$data['branchCode']=$this->branchCode;
				$data['branchName'] = $this->GlobalModel->selectDataById($this->branchCode, 'branchmaster')->result()[0]->branchName;
			}
			$data['branch'] = $this->GlobalModel->selectActiveData('branchmaster');
			$data['inwardline'] = $this->GlobalModel->selectActiveData('inwardlineentries');
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/stock/list', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function getStockList()
	{
		$branchCode = $this->input->get('branch');
		if($this->branchCode!=""){
			$branchCode=$this->branchCode;
		}
		$product = $this->input->get('product');
		$tableName = "stockinfo";
		$search = $this->input->GET("search")['value'];
		$orderColumns = array("stockinfo.code,stockinfo.variantCode,stockinfo.proNameVarName,stockinfo.batchNo,stockinfo.productCode,productmaster.productEngName,categorymaster.categoryName,unitmaster.unitName,unitmaster.unitSName,ifnull(sum(stockinfo.stock),0) as stock,branchmaster.branchName,stockinfo.addDate,productvariants.variantName");
		$condition = array("stockinfo.isActive" => 1, 'stockinfo.proNameVarName' => $product, 'stockinfo.branchCode' => $branchCode);
		$orderBy = array('stockinfo.id' => 'DESC');
		$joinType = array('productmaster' => 'inner', 'categorymaster' => 'inner', 'unitmaster' => 'inner', 'branchmaster' => 'inner', 'productvariants' => 'left');
		$join = array('productmaster' => 'productmaster.code=stockinfo.productCode', 'categorymaster' => 'categorymaster.code=productmaster.productcategory', 'unitmaster' => 'unitmaster.code=stockinfo.unitCode', 'branchmaster' => 'branchmaster.code=stockinfo.branchCode', 'productvariants' => 'productvariants.code=stockinfo.variantCode');
		$groupByColumn = array('stockinfo.branchCode', 'stockinfo.productCode', 'stockinfo.variantCode');
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$extraCondition = "";
		$like = array("productvariants.variantName" => $search . "~both","productmaster.productEngName" => $search . "~both","branchmaster.branchName" => $search . "~both", "unitmaster.unitName" => $search . "~both", "stockinfo.code" => $search . "~both","stockinfo.stock" => $search . "~both");
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		$srno = $_GET['start'] + 1;
		if ($Records) {
			foreach ($Records->result() as $row) {
				$name = "";
				if ($row->variantName != "") {
					$name = $row->productEngName . "-" . $row->variantName;
				} else {
					$name = $row->productEngName;
				}
				$data[] = array(
					$srno,
					$row->branchName,
					$name,
					$row->stock . "  " . $row->unitName . ".",
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
}
