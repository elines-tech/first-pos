<?php

class Sendemail extends CI_Controller
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
		$this->rights = $this->GlobalModel->getMenuRights('9.4', $rolecode);
		if ($this->rights == '') {
			$this->load->view('errors/norights.php');
		}
	}

	public function notifiy()
	{
		if ($this->rights != '' && $this->rights['insert'] == 1) {
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/notifications/email/sendemail');
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function send()
	{
		$emailConfig = $this->GlobalModel->selectQuery("settings.*", "settings", array('settings.code' => 'STG_2'));
		if ($emailConfig) {
			$data['email'] =	$this->input->post('email');
			$data['subject'] = $this->input->post('subject');
			$data['message'] = $this->input->post('message');
			$response['status'] = true;
			$this->load->view('dashboard/notifications/email/email', $data);
		} else {
			$response['status'] = false;
			$response['message'] = "Email Configuration required.";
			$this->session->set_flashdata('message', json_encode($response));
			redirect('sendemail/notifiy', 'refresh');
		}
	}

	public function sendEmailNotification()
	{
		$result=false;
		$email = $this->input->post('email');
		$message = $this->input->post('message');
		$subject = $this->input->post('subject');
		$emailConfig = $this->GlobalModel->selectQuery("settings.*", "settings", array('settings.code' => 'STG_2'));
		if ($emailConfig) {
			$value = $emailConfig->result_array()[0]['settingValue'];
			$config = json_decode($value, true);
			if ($email != '') {
				foreach ($email as $e) {
					$sendresult = $this->emailer->transact($config, $e, $subject, $message);
				     if($sendresult=="SUCCESS"){
						  $result=true;
					 }
				}
			} else {
				$getCustomer = $this->GlobalModel->selectQuery("customer.*", "customer", array(), array("customer.id" => 'desc'), array(), array(), array(), '');
				if ($getCustomer) {
					foreach ($getCustomer->result() as $item) {
						if ($item->email != "" || $item->email != null) {
							$sendresult = $this->emailer->transact($config, $item->email, $subject, $message);
							 if($sendresult=="SUCCESS"){
								  $result=true;
							 }
						}
					}
				}
			}
			if($result==true){
			  return true;
			  }else{
				  return "Email is not sending."; 
			}
		}
	}
}
