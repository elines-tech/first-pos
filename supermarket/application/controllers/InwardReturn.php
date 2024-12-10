<?php
defined('BASEPATH') or exit('No direct script access allowed');

class InwardReturn extends CI_Controller
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
		$this->rights = $this->GlobalModel->getMenuRights('3.3',$this->rolecode);
		if($this->rights ==''){
			$this->load->view('errors/norights.php');
		}
	}

	public function listRecords()
	{
		if($this->rights !='' && $this->rights['view']==1){
			$data['branchCode']="";
			$data['branchName']="";
			if($this->branchCode!=""){
				$data['branchCode']=$this->branchCode;
				$data['branchName'] = $this->GlobalModel->selectDataById($this->branchCode, 'branchmaster')->result()[0]->branchName;
			}
			$data['insertRights'] = $this->rights['insert'];
			$data['branch'] = $this->GlobalModel->selectActiveData('branchmaster');
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/sidebar');
			$this->load->view('dashboard/inwardreturn/list',$data);
			$this->load->view('dashboard/footer');
		}else{
			$this->load->view('errors/norights.php');
		}
	}

	public function getReturnList()
	{
		$dataCount = 0;
		$data = array();
		$branch=$this->input->get('branch');
		if($this->branchCode!=""){
			$branch=$this->branchCode;
		}
		$tableName = "returnentries";
		$search = $this->input->GET("search")['value'];
		$orderColumns = array("returnentries.code,returnentries.inwardCode,inwardentries.batchNo,count(returnentries.id) as noOfReturns,branchmaster.branchName,suppliermaster.supplierName,inwardentries.inwardDate");
		$condition = array('branchmaster.code'=>$branch);
		$orderBy = array('returnentries.id' => 'DESC');
		$joinType = array('inwardentries'=>'inner','branchmaster' => 'inner', 'suppliermaster' => 'inner');
		$join = array('inwardentries'=>'inwardentries.code=returnentries.inwardCode','branchmaster' => 'branchmaster.code=inwardentries.branchCode', 'suppliermaster' => 'suppliermaster.code=inwardentries.supplierCode');
		$groupByColumn = array('returnentries.inwardCode');
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$extraCondition = " returnentries.isDelete=0 OR returnentries.isDelete IS NULL";
		$like = array("branchmaster.branchName" => $search . "~both","returnentries.code" => $search . "~both","inwardentries.batchNo" => $search . "~both","suppliermaster.supplierName" => $search . "~both");
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		$srno = $_GET['start'] + 1;
		if ($Records) {
			foreach ($Records->result() as $row) {
				$code = $row->code;
				$actionHtml='';
				if($this->rights !='' && $this->rights['view']==1){
					$actionHtml .= '<a href="' . base_url() . 'inwardReturn/view/' . $row->inwardCode . '" class="btn btn-success btn-sm cursor_pointer"><i id="view" title="View" class="fa fa-eye"></i></a>';
				}

				$data[] = array(
					$srno,
					$row->code,
					$row->batchNo,
					date('d/m/Y', strtotime($row->inwardDate)),
					$row->branchName,
					$row->supplierName,
					//$row->noOfReturns,
					$actionHtml
				);
				$srno++;
			}
			$dataCount = sizeof($this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, array(), '', '', '', $extraCondition)->result());
		}
		$output = array(
			"draw"			  =>     intval($_GET["draw"]),
			"recordsTotal"    =>     $dataCount,
			"recordsFiltered" =>     $dataCount,
			"data"            =>     $data
		);
		echo json_encode($output);
	}

	public function view()
	{
		if($this->rights !='' && $this->rights['view']==1){
			$inwardCode = $this->uri->segment(3);
			$data['supplier'] = $this->GlobalModel->selectActiveData('suppliermaster');
			$data['branch'] = $this->GlobalModel->selectActiveData('branchmaster');
			$data['items'] = $this->GlobalModel->selectActiveData('productmaster');
			$data['unitmaster'] = $this->GlobalModel->selectActiveData('unitmaster');
			$data['returnData'] = $this->GlobalModel->selectQuery('returnentries.*,inwardentries.inwardDate,inwardentries.branchCode,inwardentries.supplierCode,inwardentries.batchNo,inwardentries.code as inwardCode,unitmaster.unitName', 'returnentries', array('returnentries.inwardCode' => $inwardCode),array('returnentries.id'=>'DESC'),array('inwardentries'=>'inwardentries.code=returnentries.inwardCode','productmaster'=>'productmaster.code=returnentries.productCode','unitmaster'=>'unitmaster.code=productmaster.storageUnit'),array('inwardentries'=>'inner','productmaster'=>'inner','unitmaster'=>'inner'));
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/sidebar');
			$this->load->view('dashboard/inwardreturn/view', $data);
			$this->load->view('dashboard/footer');
		}else{
			$this->load->view('errors/norights.php');
		}
	}
}
