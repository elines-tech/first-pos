<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PurchaseReport extends CI_Controller
{
	var $session_key;
	protected $rolecode,$branchCode; 
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
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
		$this->rights = $this->GlobalModel->getMenuRights('6.4', $this->rolecode);
		if ($this->rights == '') {
			$this->load->view('errors/norights.php');
		}
	}

	public function getPurchaseReport()
	{
		if ($this->rights != '' && $this->rights['view'] == 1) {
			$data['usermaster'] = $this->GlobalModel->selectQuery('usermaster.code,usermaster.userEmpNo', 'usermaster', array('usermaster.userRole' => 'R_3'));
			$data['branch'] = $this->GlobalModel->selectActiveData('branchmaster');
			$data['supplier'] = $this->GlobalModel->selectActiveData('suppliermaster');
			$data['branchCode']="";
			$data['branchName']="";
			if($this->branchCode!=""){
				$data['branchCode']=$this->branchCode;
				$data['branchName'] = $this->GlobalModel->selectDataById($this->branchCode, 'branchmaster')->result()[0]->branchName;
			}
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/report/purchaseReport', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function getPurchaseList()
	{
		$dataCount = 0;
		$data = array();
		$frombranchCode = $this->input->get('frombranchCode');
		if($this->branchCode!=""){
			$frombranchCode=$this->branchCode;
		}
		$tobranchCode = $this->input->get('tobranchCode');
		$fromDate = $this->input->get('fromDate');
		$toDate = $this->input->get('toDate');
		$supplierCode = $this->input->get('supplierCode');
		$export = $this->input->get('export');
		$search = $limit = $offset = '';
		$srno = 1;
		$draw = 0;
		$total = 0;
		if ($export == 0) {
			$search = $this->input->GET("search")['value'];
			$limit = $this->input->GET("length");
			$offset = $this->input->GET("start");
			$srno = $_GET['start'] + 1;
			$draw = $_GET["draw"];
		}
		$tableName = "inwardentries";
		$orderColumns = array("inwardentries.batchNo,inwardentries.code,inwardentries.branchCode,inwardentries.supplierCode,branchmaster.branchName,inwardentries.inwardDate,inwardentries.total, inwardentries.isActive,inwardentries.flag");
		$condition = array('inwardentries.branchCode' => $frombranchCode, 'inwardentries.supplierCode' => $tobranchCode, "inwardentries.isActive" => 1);
		$orderBy = array('inwardentries.id' => 'DESC');
		$joinType = array('branchmaster' => 'inner');
		$join = array('branchmaster' => 'branchmaster.code=inwardentries.branchCode');
		$groupByColumn = array();
		$extraCondition = " (inwardentries.isDelete=0 OR inwardentries.isDelete IS NULL)";
		if ($fromDate != '' && $toDate != '') {
			$extraCondition .= " and (inwardentries.inwardDate between '" . $fromDate . " 00:00:00' and '" . $toDate . " 00:00:00')";
		}
		$like = array("branchmaster.branchName" => $search . "~both", "inwardentries.batchNo" => $search . "~both", "inwardentries.code" => $search . "~both", "inwardentries.total" => $search . "~both", "inwardentries.flag" => $search . "~both");
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		//echo $this->db->last_query();
		if ($Records) {
			foreach ($Records->result() as $row) {
				if ($row->flag == 'inward') {
					$supplier = $this->GlobalModel->selectQuery("suppliermaster.*", "suppliermaster", array("suppliermaster.code" => $row->supplierCode), array(), array(), array(), array(), '', '', array(), '');
					$name = $supplier->result_array()[0]['supplierName'];
				} else {
					$branch = $this->GlobalModel->selectQuery("branchmaster.*", "branchmaster", array("branchmaster.code" => $row->supplierCode), array(), array(), array(), array(), '', '', array(), '');
					$name = $branch->result_array()[0]['branchName'];
				}
				$actionHtml = '<a href="' . base_url() . 'purchaseReport/view/' . $row->code . '" class="btn btn-success btn-sm cursor_pointer m-1"><i id="view" title="View" class="fa fa-eye"></i></a>';
				if ($export == 0) {
					$data[] = array($srno, $row->batchNo, date('d/m/Y', strtotime($row->inwardDate)), $row->branchName, $name, $row->flag, $row->total, $actionHtml);
				} else {
					$data[] = array($srno, $row->batchNo, date('d/m/Y', strtotime($row->inwardDate)), $row->branchName, $name, $row->flag, $row->total);
				}
				$total += $row->total;
				$srno++;
			}
			$dataCount = sizeof($this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, array(), '', '', '', $extraCondition)->result());
		}
		$output = array(
			"draw"			  =>     intval($draw),
			"recordsTotal"    =>      $dataCount,
			"recordsFiltered" =>     $dataCount,
			"data"            =>     $data,
			"total"           =>     $total
		);
		echo json_encode($output);
	}

	public function view($code)
	{
		if ($this->rights != '' && $this->rights['view'] == 1) {
			$data['supplier'] = $this->GlobalModel->selectActiveData('suppliermaster');
			$data['branch'] = $this->GlobalModel->selectActiveData('branchmaster');
			$extraCondition = " (productvariants.isDelete=0 OR productvariants.isDelete IS NULL)";
			$data['items'] = $this->GlobalModel->selectQuery('productmaster.*,productvariants.variantName,productvariants.code as variantCode,productvariants.sku as variantSKU', 'productmaster', array("productmaster.isActive" => 1), array(), array('productvariants' => 'productvariants.productCode=productmaster.code'), array('productvariants' => 'left'), array(), '', '', array(), $extraCondition);
			$data['unitmaster'] = $this->GlobalModel->selectActiveData('unitmaster');
			$data['inwardData'] = $this->GlobalModel->selectQuery('inwardentries.*', 'inwardentries', array('inwardentries.code' => $code));
			$data['inwardLineEntries'] = $this->GlobalModel->selectQuery('inwardlineentries.*,unitmaster.unitName', 'inwardlineentries', array('inwardlineentries.inwardCode' => $code, 'inwardlineentries.isActive' => 1), array(), array('unitmaster' => 'unitmaster.code=inwardlineentries.productUnit'), array('unitmaster' => 'inner'));
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/report/purchaseview', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}
}
