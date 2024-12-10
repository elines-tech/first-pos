<?php

class Sendsms extends CI_Controller
{

    var $session_key;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('form', 'url', 'html');
        $this->load->model('GlobalModel');
		$this->load->library("smstwilio");
		$this->load->library("smsplivo");
		$this->load->library("emailer");
        $this->session_key = $this->session->userdata('key' . SESS_KEY);
        if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
            redirect('Login', 'refresh');
        }
        $res = $this->GlobalModel->checkActiveSubscription();
        if ($res == "EXPIRED") {
            redirect('package', 'refresh');
        }
        $rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
        $this->rights = $this->GlobalModel->getMenuRights('9.2', $rolecode);
        if ($this->rights == '') {
            $this->load->view('errors/norights.php');
        }
    }

    public function notifiy()
    {
        if ($this->rights != '' && $this->rights['view'] == 1) {
            $this->load->view('dashboard/header');
            $this->load->view('dashboard/notifications/sms/sendsms');
            $this->load->view('dashboard/footer');
        } else {
            $this->load->view('errors/norights.php');
        }
    }
	
	public function send(){
		
		$smsConfig = $this->GlobalModel->selectQuery("settings.*", "settings", array('settings.code' => 'STG_4'));
		if ($smsConfig) {
			$data['mobilenumber']=	$this->input->post('mobilenumber');
			$data['message'] = $this->input->post('message');
			$response['status'] = true;
			//$response['message'] = "SMS Sent Successfully";
			$this->load->view('dashboard/notifications/sms/sms', $data); 
		}
		else{
			 
			 $response['status'] = false;
             $response['message'] = "SMS Configuration required.";
			 $this->session->set_flashdata('message', json_encode($response));
			 redirect('sendsms/notifiy', 'refresh');
		}
			
	}
	
	public function sendSmsNotification(){
		  $mobilenumber=$this->input->post('mobilenumber');
		  $message = $this->input->post('message');
		  $smsConfig = $this->GlobalModel->selectQuery("settings.*", "settings", array('settings.code' => 'STG_3'));
		   if($smsConfig) {
					 $value = $smsConfig->result_array()[0]['settingValue'];
					 $config=json_decode($value,true);
		    }
			   if($config['smsprovider']=="TWILIO"){
				  $config_setting = [
						"sid" => $config['sid'],
						"token" =>$config['token'],
						"twilionumber" =>$config['twilionumber'],
				   ];
				   if($mobilenumber!=''){
					  foreach($mobilenumber as $mobile){
							  $sendresult=$this->smstwilio->transact($config_setting, $mobile, $message);  
					  }
				   }else{
					    $getCustomer = $this->GlobalModel->selectQuery("customer.*","customer",array(),array("customer.id"=>'desc'),array(),array(),array(),'');
				        if($getCustomer){
						foreach($getCustomer ->result() as $item){
							    $sendresult=$this->smstwilio->transact($config_setting, $item->phone, $message);
							   } 
						}
				   }	
			  }
			  if($config['smsprovider']=="PLIVO"){
				  $config_setting = [
						"authid" => $config['authid'],
						"authToken" => $config['authToken'],
						"senderid" => $config['senderid'], 
				   ];
				   if($mobilenumber!=''){
					  foreach($mobilenumber as $mobile){
							  $sendresult=$this->smsplivo->transact($config_setting, $mobile, $message);  
					  }
				   }else{
						$getCustomer = $this->GlobalModel->selectQuery("customer.*","customer",array(),array("customer.id"=>'desc'),array(),array(),array(),'');
						if($getCustomer){
						foreach($getCustomer ->result() as $item){
								$sendresult=$this->smsplivo->transact($config_setting, $item->phone, $message);
							  } 
						}
					   
				   }
			  }
			  
		  if($sendresult){
			  //return true;
		  }else{
			   return "Sms is not sending."; 
		  }
	}
}