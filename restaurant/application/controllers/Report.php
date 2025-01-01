<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report extends CI_Controller
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
		$this->rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
			redirect('Login', 'refresh');
		}
		$this->salerights = $this->GlobalModel->getMenuRights('7.1', $this->rolecode);
		$this->purchaserights = $this->GlobalModel->getMenuRights('7.2', $this->rolecode);
		$this->transferrights = $this->GlobalModel->getMenuRights('7.3', $this->rolecode);
		$this->daycloserights = $this->GlobalModel->getMenuRights('7.4', $this->rolecode);
		$this->rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		$this->branchCode = $this->session->userdata['logged_in' . $this->session_key]['branchCode'];
		$res = $this->GlobalModel->checkActiveSubscription();
        if ($res == "EXPIRED") {
            redirect('package', 'refresh');
        }
	}
	
	public function dayClosingReport()
    {
		if($this->daycloserights !='' && $this->daycloserights['view']==1){
			$data['usermaster'] = $this->GlobalModel->selectQuery('usermaster.code,usermaster.userEmpNo','usermaster',array('usermaster.userRole'=>'R_6'));
			$data['branch'] = $this->GlobalModel->selectActiveData('branchmaster');
			$data['branchCode']="";
			$data['branchName']="";
			if($this->branchCode!=""){
				$data['branchCode']=$this->branchCode;
				$data['branchName'] = $this->GlobalModel->selectDataById($this->branchCode, 'branchmaster')->result()[0]->branchName;
			}
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/sidebar');
			$this->load->view('dashboard/report/dayClosing',$data);
			$this->load->view('dashboard/footer');	
		}else{
			$this->load->view('errors/norights.php');
		}
    }
	 
    public function getDayClosingList()
    {
		$dataCount = 0;
		$data = array();
		$branchCode=$this->input->get('branchCode');
		if($this->branchCode!=""){
			$branchCode=$this->branchCode; 
		}
		$cashierCode=$this->input->get('cashierCode');
		$fromDate=$this->input->get('fromDate');
		$toDate=$this->input->get('toDate');
		$export=$this->input->get('export');
		$search=$limit=$offset='';
		$srno=1;
		$draw=0;
		if($export==0){
			$search = $this->input->GET("search")['value'];
			$limit = $this->input->GET("length");
			$offset = $this->input->GET("start");
			$srno = $_GET['start'] + 1;
			$draw = $_GET["draw"];
		}
        $tableName = "ordermaster";
        $orderColumns = array("ordermaster.branchCode,ordermaster.addID,ifnull(count(ordermaster.id),0) as totalOrders,ifnull(sum(ordermaster.grandTotal),0) as totalSale,ordermaster.discount,branchmaster.branchName,usermaster.userEmpNo,usermaster.userName");
        $condition = array('ordermaster.branchCode'=>$branchCode,"ordermaster.addID"=>$cashierCode);
        $orderBy = array('ordermaster.id' => 'DESC');
        $joinType = array('branchmaster' => 'inner','usermaster'=>'inner');
        $join = array('branchmaster' => 'branchmaster.code=ordermaster.branchCode','usermaster'=>'usermaster.code=ordermaster.addID');
        $groupByColumn = array('ordermaster.branchCode','ordermaster.addID');
		$extraCondition='';
		if($fromDate!='' && $toDate!=''){
			$extraCondition = " (ordermaster.addDate between '".$fromDate." 00:00:01' and '".$toDate." 23:59:59')";
		}
        $like = array("branchmaster.branchName" => $search . "~both","ordermaster.discount" => $search . "~both","usermaster.userName" => $search . "~both","ordermaster.discount" => $search . "~both","usermaster.userEmpNo" => $search . "~both");
        $Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		if ($Records) { 
            foreach($Records->result() as $row) {
				$sum=0;
				$sum=$sum+$row->discount;
                $offerCount=0;				
				if($row->discount>0){
					$offerCount=$offerCount+1; 
				}
				$cashPayment=$cardPayment=$upiPayment=$netbankingpayment=0;
				$orderColumns1 = array("ordermaster.paymentMode,ordermaster.grandTotal");
				$condition1 = array("ordermaster.branchCode"=>$row->branchCode,"ordermaster.addID"=>$row->addID);
				$paymentModeQuery = $this->GlobalModel->selectQuery($orderColumns1,'ordermaster', $condition1,[],[],[],[],"","",[], $extraCondition);
				if($paymentModeQuery){
					foreach($paymentModeQuery->result() as $pm){
						if($pm->paymentMode=='cash'){
							$cashPayment = $cashPayment+$pm->grandTotal;
						}else if($pm->paymentMode=='card'){
							$cardPayment = $cardPayment+$pm->grandTotal;
						}elseif($pm->paymentMode=='upi'){
							$upiPayment = $upiPayment+$pm->grandTotal;
						}elseif($pm->paymentMode=='netbanking'){
							$netbankingpayment = $netbankingpayment+$pm->grandTotal;
						}
					}
				}
				$data[] = array(
						$srno,
						$row->branchName, 
						$row->userName.'-'.$row->userEmpNo,						
						$row->totalOrders,
						$row->totalSale,
						$cashPayment,
						$cardPayment,
						$upiPayment,
						$netbankingpayment,
						$offerCount,
						$sum
					);
				
                $srno++;
            }
            $dataCount = sizeof($this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, array(), '', '', $groupByColumn, $extraCondition)->result());
        }
        $output = array(
            "draw"              =>     intval($draw),
            "recordsTotal"    =>     $dataCount,
            "recordsFiltered" =>     $dataCount,
            "data"            =>     $data
        );
        echo json_encode($output);
    }

	public function getTransferReport()
	{
		if ($this->transferrights != '') {
			$data['branch'] = $this->GlobalModel->selectActiveData('branchmaster');
			$data['branchCode']="";
			$data['branchName']="";
			if($this->branchCode!=""){
				$data['branchCode']=$this->branchCode;
				$data['branchName'] = $this->GlobalModel->selectDataById($this->branchCode, 'branchmaster')->result()[0]->branchName;
			}
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/report/transferReport', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}
	public function getSaleReport()
	{
		if ($this->salerights != '') {
			$data['branch'] = $this->GlobalModel->selectActiveData('branchmaster');
			$data['orders'] = $this->GlobalModel->selectActiveData('ordermaster');
			$data['tables'] = $this->GlobalModel->selectQuery('tablemaster.code,tablemaster.tableNumber,sectorzonemaster.zoneName', 'tablemaster', array('tablemaster.isActive' => 1), array(), array('sectorzonemaster' => 'sectorzonemaster.id=tablemaster.zoneCode'), array('sectorzonemaster' => 'inner'));
			$data['branchCode']="";
			$data['branchName']="";
			if($this->branchCode!=""){
				$data['branchCode']=$this->branchCode;
				$data['branchName'] = $this->GlobalModel->selectDataById($this->branchCode, 'branchmaster')->result()[0]->branchName;
			}
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/report/saleReport', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}
	public function getPurchaseReport()
	{
		if ($this->purchaserights != '') {
			$data['branchCode']="";
			$data['branchName']="";
			if($this->branchCode!=""){
				$data['branchCode']=$this->branchCode;
				$data['branchName'] = $this->GlobalModel->selectDataById($this->branchCode, 'branchmaster')->result()[0]->branchName;
			}
			$data['branch'] = $this->GlobalModel->selectActiveData('branchmaster');
			$data['supplier'] = $this->GlobalModel->selectActiveData('suppliermaster');
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/report/purchaseReport', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}
	public function getTransferList()
	{
		$dataCount = 0;
		$data = array();
		$branchCode = $this->input->get('branchCode');
		if($this->branchCode!=""){
			$branchCode=$this->branchCode;
		}
		$export = $this->input->get('export');
		$search = $limit = $offset = '';
		$srno = 1;
		$draw = 0;
		if ($export == 0) {
			$search = $this->input->GET("search")['value'];
			$limit = $this->input->GET("length");
			$offset = $this->input->GET("start");
			$srno = $_GET['start'] + 1;
			$draw = $_GET["draw"];
		}
		$tableName = "inwardentries";
		$orderColumns = array("inwardentries.code,inwardentries.branchCode as fromBranchCode,inwardentries.supplierCode as toBranchCode,branchmaster.branchName as fromBranchName,br.branchName as toBranchName,inwardentries.inwardDate,inwardentries.total, inwardentries.isActive");
		$condition = array('inwardentries.flag' => 'transfer', 'inwardentries.branchCode' => $branchCode);
		$orderBy = array('inwardentries.id' => 'DESC');
		$joinType = array('branchmaster' => 'inner', 'branchmaster br' => 'inner');
		$join = array('branchmaster' => 'branchmaster.code=inwardentries.branchCode', 'branchmaster br' => 'br.code=inwardentries.supplierCode');
		$groupByColumn = array();
		$extraCondition = " inwardentries.isDelete=0 OR inwardentries.isDelete IS NULL";
		$like = array("branchmaster.branchName" => $search . "~both","inwardentries.total" => $search . "~both","inwardentries.code" => $search . "~both");
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		if ($Records) {
			foreach ($Records->result() as $row) {
				$code = $row->code;
				$actionHtml = ' <a id="view" class="btn btn-success btn-sm cursor_pointer edit_group" data-seq="' . $row->code . '"><i id="view" title="View" class="fa fa-eye"></i></a>';
				if ($export == 0) {
					$data[] = array($srno, $row->code, date('d/m/Y', strtotime($row->inwardDate)), $row->fromBranchName, $row->toBranchName, $row->total, $actionHtml);
				} else {
					$data[] = array($srno, $row->code, date('d/m/Y', strtotime($row->inwardDate)), $row->fromBranchName, $row->toBranchName, $row->total);
				}
				$srno++;
			}
			$dataCount = sizeof($this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, array(), '', '', array(), $extraCondition)->result_array());
		}
		$output = array(
			"draw"              =>     intval($draw),
			"recordsTotal"    =>     $dataCount,
			"recordsFiltered" =>     $dataCount,
			"data"            =>     $data
		);
		echo json_encode($output);
	}
	public function getPurchaseList()
	{
		$dataCount = 0;
		$data = array();
		$branchCode = $this->input->get('branchCode');
		if($this->branchCode!=""){
			$branchCode=$this->branchCode;
		}
		$tobranch = $this->input->get('toBranch');
		$export = $this->input->get('export');
		$search = $limit = $offset = '';
		$srno = 1;
		$draw = 0;
		if ($export == 0) {
			$search = $this->input->GET("search")['value'];
			$limit = $this->input->GET("length");
			$offset = $this->input->GET("start");
			$srno = $_GET['start'] + 1;
			$draw = $_GET["draw"];
		}
		$tableName = "inwardentries";
		$orderColumns = array("inwardentries.code,inwardentries.branchCode,branchmaster.branchName,suppliermaster.supplierName,inwardentries.inwardDate,inwardentries.total, inwardentries.isActive");
		$condition = array('inwardentries.branchCode' => $branchCode,'inwardentries.supplierCode' =>$tobranch, "inwardentries.isActive" => 1);
		$orderBy = array('inwardentries.id' => 'DESC');
		$joinType = array('branchmaster' => 'inner', 'suppliermaster' => 'inner');
		$join = array('branchmaster' => 'branchmaster.code=inwardentries.branchCode', 'suppliermaster' => 'suppliermaster.code=inwardentries.supplierCode');
		$groupByColumn = array();
		$extraCondition = " inwardentries.isDelete=0 OR inwardentries.isDelete IS NULL";
		$like = array("branchmaster.branchName" => $search . "~both","inwardentries.code" => $search . "~both","suppliermaster.supplierName" => $search . "~both","inwardentries.total" => $search . "~both");
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		if ($Records) {
			foreach ($Records->result() as $row) {
				$actionHtml = '<a id="view" href="' . base_url() . 'purchaseReport/view/' . $row->code . '" class="btn btn-success btn-sm cursor_pointer m-1"><i id="view" title="View" class="fa fa-eye"></i></a>';
				if ($export == 0) {
					$data[] = array($srno, $row->code, date('d/m/Y', strtotime($row->inwardDate)), $row->branchName, $row->supplierName, $row->total, $actionHtml);
				} else {
					$data[] = array($srno, $row->code, date('d/m/Y', strtotime($row->inwardDate)), $row->branchName, $row->supplierName, $row->total);
				}
				$srno++;
			}
			$dataCount = sizeof($this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, array(), '', '', $groupByColumn, $extraCondition)->result());
		}
		$output = array(
			"draw"			  =>     intval($draw),
			"recordsTotal"    =>      $dataCount,
			"recordsFiltered" =>     $dataCount,
			"data"            =>     $data
		);
		echo json_encode($output);
	}
	public function purchaseView()
	{
		$code = $this->uri->segment(3);
		$data['supplier'] = $this->GlobalModel->selectActiveData('suppliermaster');
		$data['branch'] = $this->GlobalModel->selectActiveData('branchmaster');
		$data['items'] = $this->GlobalModel->selectActiveData('itemmaster');
		$data['unitmaster'] = $this->GlobalModel->selectActiveData('unitmaster');
		$data['inwardData'] = $this->GlobalModel->selectQuery('inwardentries.*', 'inwardentries', array('inwardentries.code' => $code));
		$data['inwardLineEntries'] = $this->GlobalModel->selectQuery('inwardlineentries.*,unitmaster.unitName', 'inwardlineentries', array('inwardlineentries.inwardCode' => $code, "inwardlineentries.isActive" => 1), array(), array('unitmaster' => 'unitmaster.code=inwardlineentries.itemUom'), array('unitmaster' => 'inner'));
		$this->load->view('dashboard/commonheader');
		$this->load->view('dashboard/report/purchaseView', $data);
		$this->load->view('dashboard/footer');
	}

	public function getsaleList()
	{
		$branchCode = $this->input->get('branchCode');
		if($this->branchCode!=""){
			$branchCode=$this->branchCode;
		}
		$orderCode = $this->input->get('orderCode');
		$tableCode = $this->input->get('tableCode');
		$export = $this->input->get('export');
		$dataCount = 0;
		$data = array();
		$search = $limit = $offset = '';
		$srno = 1;
		$draw = 0;
		if ($export == 0) {
			$search = $this->input->GET("search")['value'];
			$limit = $this->input->GET("length");
			$offset = $this->input->GET("start");
			$srno = $_GET['start'] + 1;
			$draw = $_GET["draw"];
		}
		$tableName = "ordermaster";
		$orderColumns = array("ordermaster.*,branchmaster.branchName,tablemaster.tableNumber,sectorzonemaster.zoneName");
		$condition = array('ordermaster.code' => $orderCode, 'ordermaster.branchCode' => $branchCode,'ordermaster.tableNumber'=>$tableCode);
		$orderBy = array('ordermaster.id' => 'DESC');
		$joinType = array('branchmaster' => 'inner', 'tablemaster' => 'inner', 'sectorzonemaster' => 'inner');
		$join = array('branchmaster' => 'branchmaster.code=ordermaster.branchCode', 'tablemaster' => 'tablemaster.code=ordermaster.tableNumber', 'sectorzonemaster' => 'sectorzonemaster.id=ordermaster.tableSection');
		$groupByColumn = array();
		$extraCondition = " (ordermaster.isDelete=0 OR ordermaster.isDelete IS NULL)";
		$like = array("ordermaster.paymentMode" => $search . "~both","ordermaster.remark" => $search . "~both","ordermaster.grandTotal" => $search . "~both","ordermaster.custPhone" => $search . "~both","ordermaster.custName" => $search . "~both","ordermaster.code" => $search . "~both","branchmaster.branchName" => $search . "~both","tablemaster.tableNumber" => $search . "~both","sectorzonemaster.zoneName" => $search . "~both");
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);

		if ($Records) {
			foreach ($Records->result() as $row) {
				$actionHtml = '<a id="view" href="' . base_url() . 'saleReport/view/' . $row->code . '" class="btn btn-success btn-sm cursor_pointer"><i id="view" title="View" class="fa fa-eye"></i></a>';
				if ($export == 0) {
					$data[] = array($srno, date('d/m/Y h:i A', strtotime($row->addDate)), $row->code, $row->branchName, $row->tableNumber . ' / ' . $row->zoneName, $row->custName . ' - ' . $row->custPhone, "<span class='badge bg-success'>" . $row->paymentMode . "</span>", $row->grandTotal, $actionHtml);
				} else {
					$data[] = array($srno, date('d/m/Y h:i A', strtotime($row->addDate)), $row->code, $row->branchName, $row->tableNumber . ' / ' . $row->zoneName, $row->custName . ' - ' . $row->custPhone, $row->paymentMode, $row->grandTotal);
				}
				$srno++;
			}
			$dataCount = sizeof($this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, array(), '', '', '', $extraCondition)->result());
		}
		$output = array(
			"draw"			  =>     intval($draw),
			"recordsTotal"    =>      $dataCount,
			"recordsFiltered" =>     $dataCount,
			"data"            =>     $data
		);
		echo json_encode($output);
	}
	public function saleView()
	{
		$code = $this->uri->segment(3);
		$data['query'] = $this->GlobalModel->selectDataById($code, 'ordermaster');
		$this->load->view('dashboard/commonheader');
		$this->load->view('dashboard/report/saleView', $data);
		$this->load->view('dashboard/footer');
	}
}
