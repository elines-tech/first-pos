<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    var $session_key;
    public function __construct()
    {
        parent::__construct();
        $this->session_key = $this->session->userdata('key' . SESS_KEY);
        if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
            redirect('Login', 'refresh');
        }
		
    }

    public function listRecords()
    {
        //total packages
        $res['packages'] = 0;
        //totalClients
        $res['totalCli'] = 0;
        //desctivated restaurant clients
        $res['deactResCli'] = 0;
        //deactivated supermarket clients
        $res['deactSupCli'] = 0;
        //active restaurant clients        
        $res['actResCli'] = 0;
        //active supermarket clients
        $res['actSupCli'] = 0;
        //total payment this months
        $res['countOrders'] = number_format(0, 2, '.', '');
		$currentdate=date("Y-m-d h:i:s");
		
		
		$newSub = $this->GlobalModel->selectQuery("count('clients.code') as cnt", "clients", array(), array(), array(), array(),  array(), '', '', array(), "DATE(clients.registerDate) = DATE(CAST(NOW() - INTERVAL 1 DAY AS DATE))");
        if ($newSub) {
            $countNewSub =  $newSub->result()[0]->cnt;
        }
		
        $exSubscription = $this->GlobalModel->selectQuery("count('payments.code') as cnt", "payments", array(), array(), array('clients' => 'clients.code=payments.clientCode'), array('clients' => 'inner'),  array(), '', '', array(), "'".$currentdate."' > payments.expiryDate");
        if ($exSubscription) {
            $countExpiredSubscriptions =  $exSubscription->result()[0]->cnt;
        }
		
		$con['payments.isFreeTrial']=1;
		$freeTrial = $this->GlobalModel->selectQuery("count('payments.code') as cnt", "payments", $con, array(), array('clients' => 'clients.code=payments.clientCode'), array('clients' => 'inner'),  array(), '', '', array(), "");
        if ($freeTrial) {
            $countfreeTrial = $freeTrial->result()[0]->cnt;
        }
		
		$conclient['clients.isActive']=1;
		$activesubscribers = $this->GlobalModel->selectQuery("count('clients.code') as cnt", "clients",  $conclient,array(), array('payments' => 'payments.clientCode=clients.code'), array('payments' => 'inner'),  array(), '', '', array(), "");
        if ($activesubscribers) {
            $activesubscribers = $activesubscribers->result()[0]->cnt;
        }
			
		$cnt=0;
		$where['payments.type']='subscription';
		$where['payments.period']='year';
		$countYear = $this->GlobalModel->selectQuery("abs(timestampdiff(MONTH,startDate,expiryDate)) as year_of", "payments", $where,array(), array('clients' => 'clients.code=payments.clientCode'), array('clients' => 'inner'),  array(), '', '', array(), "");
		if($countYear){
			foreach ($countYear->result() as $row) {
			 $count=$row->year_of;
				 if($count<=1){
					 $cnt=$cnt+1;
				 }
			}
		}
		$c=0;
		$where2['payments.type']='subscription';
		$where2['payments.period']='month';
		$countMonth = $this->GlobalModel->selectQuery("abs(DATEDIFF(startDate,expiryDate)) as month_of", "payments", $where2, array(),  array('clients' => 'clients.code=payments.clientCode'), array('clients' => 'inner'),  array(), '', '', array(), "");
		if($countMonth){
			foreach ($countMonth->result() as $row) {
			 $co=$row->month_of;
				 if($co<=3){
					 $c=$c+1;
				 }
			}
		}
		$res['expiredSubscriptions']=$countExpiredSubscriptions;
		$res['freeTrial']=$countfreeTrial;
		$res['newsubscriber']=$countNewSub;
		$res['countactivesubscribers']=$activesubscribers;
		$res['remMonthSub']=$c;
		$res['remYearSub']=$cnt;
		
        $this->backend('dashboard', $res, "Dashboard");
    }
}
