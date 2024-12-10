<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report extends CI_Controller
{
    var $session_key;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('form', 'url', 'html');
        $this->load->model('GlobalModel');
        $this->session_key = $this->session->userdata('cash_key' . CASH_SESS_KEY);
		$this->rolecode = $this->session->userdata['cash_logged_in' . $this->session_key]['role'];
        if (!isset($this->session->userdata['cash_logged_in' . $this->session_key]['code'])) {
            redirect('Cashier/Login', 'refresh');
        }
		$res = $this->GlobalModel->checkActiveSubscription();
        if ($res == "EXPIRED") {
			$this->load->view('errors/exppackage.php');
        }
    }

    public function getDayClosingReport()
    {
		$this->load->view('cashier/header'); 
		$this->load->view('cashier/report/dayClosingCashier');
		$this->load->view('cashier/footer');	
    }
	
	public function getCounterData(){
		$cashierCode = $this->session->userdata['cash_logged_in' . $this->session_key]['code'];
		$date = $this->input->post("date");
		$orderColumns = array("ordermaster.branchCode,ordermaster.addID,ordermaster.counter,ifnull(count(ordermaster.id),0) as totalOrders,ifnull(sum(ordermaster.totalPayable),0) as totalSale,ifnull(count(offerType),0) as offerApplied,ifnull(sum(offerDiscount),0) as offerDiscount,branchmaster.branchName,usermaster.userEmpNo,usermaster.name");
		$condition = array('ordermaster.addID'=>$cashierCode);
		$orderBy = array('ordermaster.id' => 'DESC');
		$joinType = array('branchmaster' => 'inner','usermaster'=>'inner');
		$join = array('branchmaster' => 'branchmaster.code=ordermaster.branchCode','usermaster'=>'usermaster.code=ordermaster.addID');
		$extraCondition='';
		$groupBy=array('ordermaster.counter');
		if($date!=""){
			$extraCondition = " (ordermaster.orderDate between '".$date." 00:00:01' and '".$date." 23:59:59')";
		}
		$i=0;
		$counterHtml='';
		$Records = $this->GlobalModel->selectQuery($orderColumns,'ordermaster', $condition, $orderBy, $join, $joinType, [],"","",$groupBy, $extraCondition);
		if ($Records) {
			$width = 100/count($Records->result());
			foreach($Records->result() as $result){
				$i++;
				$cashPayment=$cardPayment=$upiPayment=$netbankingPayment=0;
				$orderColumns1 = array("ordermaster.paymentMode,ordermaster.totalPayable");
				$condition1 = array("ordermaster.addID"=>$result->addID,"ordermaster.counter"=>$result->counter);
				$paymentModeQuery = $this->GlobalModel->selectQuery($orderColumns1,'ordermaster', $condition1,[],[],[],[],"","",$groupBy, $extraCondition);
				if($paymentModeQuery){
					foreach($paymentModeQuery->result() as $pm){
						if($pm->paymentMode=='cash'){
							$cashPayment = $cashPayment+$pm->totalPayable;
						}else if($pm->paymentMode=='card'){
							$cardPayment = $cardPayment+$pm->totalPayable;
						}elseif($pm->paymentMode=='upi'){
							$upiPayment = $upiPayment+$pm->totalPayable;
						}elseif($pm->paymentMode=='netbanking'){
							$netbankingPayment = $netbankingPayment+$pm->totalPayable;
						}
					}
				}
				$counterHtml .='<div style="width:'.$width.'%">
					<table class="table table-bordered">
					<tr>
						<td colspan="2" style="text-align:center;font-size:20px;"><b>Day Closing Report</b></td>
					</tr>
					<tr>
						<td><b>Branch</b></td><td>'.$result->branchName.'</td>
					</tr>
					<tr>
						<td><b>Cashier</b></td><td>'.$result->userEmpNo.'</td>
					</tr>
					<tr>
						<td><b>Counter</b></td><td>'.$result->counter.'</td>
					</tr>
					<tr>
						<td><b>Total Orders</b></td><td>'.$result->totalOrders.'</td>
					</tr>
					<tr>
						<td><b>Total Sale</b></td><td>'.$result->totalSale.'</td>
					</tr>
					<tr>
						<td><b>Cash Payments<b></td><td>'.number_format($cashPayment,2,'.','').'</td>
					</tr>
					<tr>
						<td><b>Card Payments</b></td><td>'.number_format($cardPayment,2,'.','').'</td>
					</tr>
					<tr>
						<td><b>UPI Payments</b></td><td>'.number_format($upiPayment,2,'.','').'</td>
					</tr>
					<tr>
						<td><b>Netbanking Payment</b></td><td>'.number_format($netbankingPayment,2,'.','').'</td>
					</tr>
					<tr>
						<td><b>Offer Applied</b></td><td>'.$result->offerApplied.'</td>
					</tr>
					<tr>
						<td><b>Offer Total</b></td><td>'.$result->offerDiscount.'</td>
					</tr>
				</table></div>';
			}
			$response['reportHtml']=$counterHtml;
			$response['status']=true;
		}else{
			$counterHtml = '<div style="width:100%;text-align:center"><h5>No data found</h5></div>';
			$response['status']=false;
			$response['reportHtml']=$counterHtml;
		}
		echo json_encode($response);
	}
}
