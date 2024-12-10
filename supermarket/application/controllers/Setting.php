<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setting extends CI_Controller
{
	var $session_key;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form', 'url', 'html');
		$this->load->model('GlobalModel');
		$this->load->library('form_validation');
		$this->session_key = $this->session->userdata('key' . SESS_KEY);
		if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
			redirect('Login', 'refresh');
		}
		$res = $this->GlobalModel->checkActiveSubscription();
		if ($res == "EXPIRED") {
			redirect('package', 'refresh');
		}
		$rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		$this->rights = $this->GlobalModel->getMenuRights('7.7', $rolecode);
		if ($this->rights == '') {
			$this->load->view('errors/norights.php');
		}
	}

	public function listRecords()
	{
		if ($this->rights != '' && $this->rights['view'] == 1) {
			$data['updateRights'] = $this->rights['update'];
			$data['setting'] = $this->GlobalModel->selectQuery("settings.*", 'settings', array(), array("settings.id" => "ASC"));
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/setting/list', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function skuprefix()
	{
		$settingCode = 'STG_1';
		if ($this->rights != '' && $this->rights['update'] == 1) {
			$date = date('Y-m-d H:i:s');
			$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
			$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
			$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
			$settingValue = $this->input->post("settingValue");

			$ip = $_SERVER['REMOTE_ADDR'];
			$this->GlobalModel->plainQuery("DELETE FROM settings where code='$settingCode'");
			$data = array(
				"id" => 1,
				"code" => "STG_1",
				'settingName' => "SKU Prefix",
				'settingValue' => $settingValue,
				'isActive' => 1,
				"addID" => $addID,
				"addIP" => $ip
			);
			$result = $this->GlobalModel->directInsert($data, 'settings');
			if ($result) {
				$txt = "SKU PREFIX Setting is updated.";
				$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
				$this->GlobalModel->activity_log($activity_text);
				$res = array(
					"status" => true,
					"message" => "Setting updated successfully"
				);
				$this->session->set_flashdata("message", json_encode($res));
			} else {
				$res = array(
					"status" => false,
					"message" => "Failed to update the settings"
				);
				$this->session->set_flashdata("message", json_encode($res));
			}
			redirect("setting/listRecords");
		} else {
			$this->load->view('errors/norights.php');
		}
	}


	public function update_email()
	{
		$settingCode = 'STG_2';
		if ($this->rights != '' && $this->rights['update'] == 1) {
			$date = date('Y-m-d H:i:s');
			$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
			$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
			$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
			$config = array(
				array(
					'field' => 'maildriver',
					'label' => 'Mail Driver',
					'rules' => 'trim|required|min_length[4]|max_length[50]',
					'errors' => array(
						'required' => 'You must provide a %s.',
						'min_length' => 'Minimum 4 characters are needed',
						'max_length' => 'Maximum 50 character are allowed'
					),
				),
				array(
					'field' => 'host',
					'label' => 'Host',
					'rules' => 'trim|required|min_length[4]|max_length[50]',
					'errors' => array(
						'required' => 'You must provide a %s.',
						'min_length' => 'Minimum 4 characters are needed',
						'max_length' => 'Maximum 50 character are allowed'
					),
				),
				array(
					'field' => 'port',
					'label' => 'Port',
					'rules' => 'trim|required|integer|min_length[2]|max_length[6]',
					'errors' => array(
						'required' => 'You must provide a %s.',
						'integer' => '%s must be an integer',
						'min_length' => 'Minimum 4 characters are needed',
						'max_length' => 'Maximum 50 character are allowed'
					),
				),
				array(
					'field' => 'username',
					'label' => 'username',
					'rules' => 'trim|required|min_length[4]|max_length[50]',
					'errors' => array(
						'required' => 'You must provide a %s.',
						'min_length' => 'Minimum 4 characters are needed',
						'max_length' => 'Maximum 50 character are allowed'
					),
				),
				array(
					'field' => 'password',
					'label' => 'Password',
					'rules' => 'trim|required|min_length[4]|max_length[50]',
					'errors' => array(
						'required' => 'You must provide a %s.',
						'min_length' => 'Minimum 4 characters are needed',
						'max_length' => 'Maximum 50 character are allowed'
					),
				),
				array(
					'field' => 'fromaddress',
					'label' => 'From Address',
					'rules' => 'trim|required|valid_email',
					'errors' => array(
						'required' => 'You must provide a %s.',
						'valid_email' => '%s must be valid from address',
						'max_length' => 'Maximum 50 character are allowed'
					),
				),
				array(
					'field' => 'fromname',
					'label' => 'fromname',
					'rules' => 'trim|required|min_length[4]|max_length[50]',
					'errors' => array(
						'required' => 'You must provide a %s.',
						'min_length' => 'Minimum 4 characters are needed',
						'max_length' => 'Maximum 50 character are allowed'
					),
				),
			);
			$this->form_validation->set_rules($config);
			if ($this->form_validation->run() == FALSE) {
				$data['setting'] = $this->GlobalModel->selectQuery("settings.*", 'settings', array(), array("settings.id" => "ASC"));
				$this->load->view('dashboard/header');
				$this->load->view('dashboard/setting/skuprefix', $data);
				$this->load->view('dashboard/footer');
			} else {
				$ip = $_SERVER['REMOTE_ADDR'];
				$this->GlobalModel->plainQuery("DELETE FROM settings where code='$settingCode'");
				$maildriver = trim($this->input->post('maildriver'));
				$host = trim($this->input->post('host'));
				$port = trim($this->input->post('port'));
				$username = trim($this->input->post('username'));
				$password = trim($this->input->post('password'));
				$fromaddress = trim($this->input->post('fromaddress'));
				$fromname = trim($this->input->post('fromname'));
				$setting = [
					"maildriver" => $maildriver,
					"host" => $host,
					"port" => $port,
					"username" => $username,
					"password" => $password,
					"fromaddress" => $fromaddress,
					"fromname" => $fromname
				];
				$settingValue = stripslashes(json_encode($setting));
				$data = array(
					"id" => 2,
					"code" => "STG_2",
					"settingName" => "Email Settings",
					'settingValue' => $settingValue,
					'isActive' => 1,
					"addID" => $addID,
					"addIP" => $ip
				);
				$result = $this->GlobalModel->directInsert($data, 'settings');
				if ($result) {
					$txt =  "Email Setting is updated.";
					$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
					$this->GlobalModel->activity_log($activity_text);
					$res = array(
						"status" => true,
						"message" => "Setting updated successfully"
					);
					$this->session->set_flashdata("message", json_encode($res));
				} else {
					$res = array(
						"status" => false,
						"message" => "Failed to update the settings"
					);
					$this->session->set_flashdata("message", json_encode($res));
				}
				redirect("setting/listRecords");
			}
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function twilio()
	{
		$settingCode = "STG_3";
		if ($this->rights != '' && $this->rights['update'] == 1) {
			$date = date('Y-m-d H:i:s');
			$ip = $_SERVER['REMOTE_ADDR'];
			$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
			$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
			$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];			
			$smsprovider = trim($this->input->post('smsprovider'));
			$sid = trim($this->input->post('sid')); 
			$token = trim($this->input->post('token'));
			$twilionumber = trim($this->input->post('twilionumber'));
			$setting = [
				"smsprovider" => $smsprovider,
				"sid" => $sid,
				"token" => $token,
				"twilionumber" => $twilionumber
			];
			$settingValue = stripslashes(json_encode($setting));
			$data = array(
				"settingName" => "Text SMS",
				'settingValue' => $settingValue,
				'isActive' => 1,
			);
			$hasRecord = $this->GlobalModel->get_row_array(["code" => "STG_3"], "settings");
			if (!empty($hasRecord)) {
				$data["editID"] = $addID;
				$data["editIP"] = $ip;
				$result = $this->GlobalModel->doEdit($data, 'settings', 'STG_3');
			} else {
				$data["id"] = 3;
				$data["code"] = "STG_3";
				$data["addID"] = $addID;
				$data["addIP"] = $ip;
				$result = $this->GlobalModel->directInsert($data, 'settings');
			}
			if ($result) {
				$txt =  "Text-SMS Setting is updated.";
				$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
				$this->GlobalModel->activity_log($activity_text);
				$res = array(
					"status" => true,
					"message" => "Setting updated successfully"
				);
				$this->session->set_flashdata("message", json_encode($res));
			} else {
				$res = array(
					"status" => false,
					"message" => "Failed to update the settings"
				);
				$this->session->set_flashdata("message", json_encode($res));
			}
			redirect("setting/listRecords");
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function plivo()
	{
		$settingCode = "STG_3";
		if ($this->rights != '' && $this->rights['update'] == 1) {
			$date = date('Y-m-d H:i:s');
			$ip = $_SERVER['REMOTE_ADDR'];
			$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
			$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
			$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];			
			$smsprovider = trim($this->input->post('smsprovider'));
			$authid = trim($this->input->post('authid'));
			$authtoken = trim($this->input->post('authtoken'));
			$senderId = trim($this->input->post('senderId'));
			$setting = [
				"smsprovider" => $smsprovider,
				"authid" => $authid,
				"authtoken" => $authtoken,
				"senderId" => $senderId
			];
			$settingValue = stripslashes(json_encode($setting));
			$data = array(
				"settingName" => "Text SMS",
				'settingValue' => $settingValue,
				'isActive' => 1,
			);
			$hasRecord = $this->GlobalModel->get_row_array(["code" => "STG_3"], "settings");
			if (!empty($hasRecord)) {
				$data["editID"] = $addID;
				$data["editIP"] = $ip;
				$result = $this->GlobalModel->doEdit($data, 'settings', 'STG_3');
			} else {
				$data["id"] = 3;
				$data["code"] = "STG_3";
				$data["addID"] = $addID;
				$data["addIP"] = $ip;
				$result = $this->GlobalModel->directInsert($data, 'settings');
			}
			if ($result) {
				$txt =  "Text-SMS Setting is updated.";
				$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
				$this->GlobalModel->activity_log($activity_text);
				$res = array(
					"status" => true,
					"message" => "Setting updated successfully"
				);
				$this->session->set_flashdata("message", json_encode($res));
			} else {
				$res = array(
					"status" => false,
					"message" => "Failed to update the settings"
				);
				$this->session->set_flashdata("message", json_encode($res));
			}
			redirect("setting/listRecords");
		} else {
			$this->load->view('errors/norights.php');
		}
	}
}
