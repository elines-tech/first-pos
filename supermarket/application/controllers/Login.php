<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
	var $session_key;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form', 'url', 'html');
		$this->load->library('form_validation');
		$this->load->library('sendemail');
		$this->load->library('emailer');
		$this->load->model('AdminModel');
	}

	public function index()
	{
		$this->load->view('dashboard/login/login');
	}

	public function access_denied()
	{
		echo "access denied";
	}

	public function reset()
	{
		$this->load->view('dashboard/login/reset');
	}

	public function resetPassword()
	{
		$urlToken = $this->uri->segment('4');
		$clientCode = $this->uri->segment('3');
		$tableName = "clients";
		$orderColumns = array("databasemaster.databaseName,clients.companyname,clients.code");
		$condition = array("clients.code" => $clientCode);
		$orderBy = array();
		$joinType = array('databasemaster' => 'inner');
		$join = array('databasemaster' => 'clients.code = databasemaster.companyCode');
		$CompanyDbResult = $this->AdminModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType);
		if ($CompanyDbResult != false) {
			$t = 'supdb' . time();
			$this->session->set_userdata('key' . SESS_KEY, $t);
			$this->session->set_userdata('current_db' . $t, $CompanyDbResult->result_array()[0]["databaseName"]);
			$this->load->model('GlobalModel');
			$Records = $this->GlobalModel->selectQuery('usermaster.*', 'usermaster', array('usermaster.token' => $urlToken));
			if ($Records) {
				$code = $Records->result()[0]->code;
				$data['code'] = $code;
				$data['token'] = $urlToken;
				$data['cmpcode'] = $clientCode;
				$this->load->view('dashboard/login/recover', $data);
			} else {
				$this->session->set_flashdata('message', 'Password Reset Link is Expired Please Reset Password Again to Continue');
				redirect("Login");
			}
		} else {
			$this->session->set_flashdata('message', 'Invalid Credentials');
			redirect("Login");
		}
	}


	public function updatePassword()
	{
		$code = $this->input->post('code');
		$clientCode = $this->input->post('cmpcode');
		$token = $this->input->post('token');
		$tableName = "clients";
		$orderColumns = array("databasemaster.databaseName,clients.companyname,clients.code");
		$condition = array("clients.code" => $clientCode);
		$orderBy = array();
		$joinType = array('databasemaster' => 'inner');
		$join = array('databasemaster' => 'clients.code = databasemaster.companyCode');
		$CompanyDbResult = $this->AdminModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType);
		if ($CompanyDbResult != false) {
			$t = 'supdb' . time();
			$this->session->set_userdata('key' . SESS_KEY, $t);
			$this->session->set_userdata('current_db' . $t, $CompanyDbResult->result_array()[0]["databaseName"]);
			$this->load->model('GlobalModel');
			$Records = $this->GlobalModel->selectQuery('usermaster.*', 'usermaster', array('usermaster.token' => $token));
			if ($Records) {
				$this->form_validation->set_rules('password', 'Password', 'trim|required');
				$this->form_validation->set_rules('confirmPassword', 'Confirm password', 'trim|required|matches[password]');
				if ($this->form_validation->run() == false) {
					$this->session->set_flashdata('message', 'Password not provided or donot match...');
					redirect("Login/resetPassword/" . $token);
				} else {
					$data = array(
						'userPassword' => md5($this->input->post('password')),
						'token' => null,
					);
					$result = $this->GlobalModel->doEdit($data, 'usermaster', $code);
					if ($result) {
						$this->session->set_flashdata('message', 'Password Reset Successfully.. Please Login to Continue in app');
						redirect("Login");
					} else {
						$this->session->set_flashdata('message', 'Problem During Reset Password.. Please Try Again');
						redirect('Login/resetPassword/' . $clientCode . '/' . $token);
					}
				}
			} else {
				$this->session->set_flashdata('message', 'Reset Link ins broken! Please try again...');
				redirect("Login");
			}
		} else {
			$this->session->set_flashdata('message', 'Something went to wrong try after some time');
			redirect("Login");
		}
	}

	public function recover()
	{
		$this->form_validation->set_rules('userEmail', 'Email ', 'required|valid_email');
		$this->form_validation->set_rules('cmpcode', 'Company code ', 'required');
		if ($this->form_validation->run() == FALSE) {
			// $this->session->set_flashdata('message', 'Email is requried');
			$this->load->view('dashboard/login/reset');
		} else {
			$userEmail = $this->input->post('userEmail');
			$companyCode = strtoupper(strtolower($this->input->post('cmpcode')));
			$clientCode = $this->AdminModel->checkCompanySubscription($companyCode);
			if ($clientCode == 'false') {
				$this->session->set_flashdata('message', 'Company code is not found. Sorry cannot reset the password');
				$this->load->view('dashboard/login/reset');
			} else {
				$tableName = "clients";
				$orderColumns = array("databasemaster.databaseName,clients.companyname,clients.code");
				$condition = array("clients.code" => $clientCode);
				$orderBy = array();
				$joinType = array('databasemaster' => 'inner');
				$join = array('databasemaster' => 'clients.code = databasemaster.companyCode');
				$CompanyDbResult = $this->AdminModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType);
				if ($CompanyDbResult != false) {
					$t = 'supdb' . time();
					$this->session->set_userdata('key' . SESS_KEY, $t);
					$this->session->set_userdata('current_db' . $t, $CompanyDbResult->result_array()[0]["databaseName"]);
					$this->load->model('GlobalModel');
					$result = $this->GlobalModel->checkrecord_exists('userEmail', $userEmail, 'usermaster');
					if (!empty($result)) {
						$mailsettings = $this->GlobalModel->selectQuery("settings.*", "settings", ["code" => "STG_2"]);
						if ($mailsettings) {
							$mailsettings = $mailsettings->result()[0]->settingValue;
							if ($mailsettings != "" && $mailsettings != null) {
								$mailsettings = json_decode($mailsettings, true);

								$token = $this->GlobalModel->randomCharacters(5);
								$token .= date('Hdm');
								$data['sendLink'] = base_url() . 'Login/resetPassword/' . $clientCode . '/' . $token;
								$sendLink = base_url() . 'Login/resetPassword/' . $clientCode . '/' . $token;
								$to = $userEmail;
								$subject = 'Reset your credentials';
								$message = '
                                    <html>
                                    <body>
                                        <p>Hello,</p>
                                        <p>We have received a request to reset password. If you did not make the request just ignore this email. Otherwise, you can reset your password using this link.</p> 
                                        <p><a  href="' . $sendLink . '" target="_blank" style="margin-bottom:8px;background:green; width:50%; padding: 8px 12px; border: 1px solid green;border-radius: 2px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #ffffff;text-decoration: none;font-weight:bold;display: inline-block; text-align:center;">RESET PASSWORD</a></p>
                                    </body>
                                    </html>
                                ';
								$res = $this->emailer->transact($mailsettings, $to, $subject, $message, []);
								if ($res == "SUCCESS") {
									$code = $result[0]['code'];
									$data = array('token' => $token);
									$resultAfterMail = $this->GlobalModel->doEditWithField($data, 'usermaster', 'code', $code);
									$this->session->set_flashdata('message', 'Reset Link was sent to your email...');
									redirect(base_url('login/reset'), 'refresh');
								} else {
									$this->session->set_flashdata('message', 'Error Occured! Please try again!');
									redirect(base_url('login/reset'), 'refresh');
								}
							} else {
								$this->session->set_flashdata('message', 'Reset password link could not be sent right now... please try again later...');
								redirect(base_url('login/reset'), 'refresh');
							}
						} else {
							$this->session->set_flashdata('message', 'Reset password link could not be sent right now... please try again later...');
							redirect(base_url('login/reset'), 'refresh');
						}
					} else {
						$this->session->set_flashdata('message', 'No users were found with the email address provided! Sorry cannot reset the password');
						$this->load->view('dashboard/login/reset');
					}
				} else {
					$this->session->set_flashdata('message', 'Invalid Credentials');
					$this->load->view('dashboard/login/reset');
				}
			}
		}
	}

	public function try()
	{
		$userEmail = "testing.neosaoservices@gmail.com";
		$companyCode = "22UGGWW";
		$clientCode = $this->AdminModel->checkCompanySubscription($companyCode);
		if ($clientCode == 'false') {
			$this->session->set_flashdata('message', 'Company code is not found. Sorry cannot reset the password');
			$this->load->view('dashboard/login/reset');
		} else {
			$tableName = "clients";
			$orderColumns = array("databasemaster.databaseName,clients.companyname,clients.code");
			$condition = array("clients.code" => $clientCode);
			$orderBy = array();
			$joinType = array('databasemaster' => 'inner');
			$join = array('databasemaster' => 'clients.code = databasemaster.companyCode');
			$CompanyDbResult = $this->AdminModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType);
			if ($CompanyDbResult != false) {
				$t = 'supdb' . time();
				$this->session->set_userdata('key' . SESS_KEY, $t);
				$this->session->set_userdata('current_db' . $t, $CompanyDbResult->result_array()[0]["databaseName"]);
				$this->load->model('GlobalModel');
				$result = $this->GlobalModel->checkrecord_exists('userEmail', $userEmail, 'usermaster');
				if (!empty($result)) {
					$mailsettings = $this->GlobalModel->selectQuery("settings.*", "settings", ["code" => "STG_2"]);
					if ($mailsettings) {
						$mailsettings = $mailsettings->result()[0]->settingValue;
						if ($mailsettings != "" && $mailsettings != null) {
							$mailsettings = json_decode($mailsettings, true);
							print_r($mailsettings);
							$token = $this->GlobalModel->randomCharacters(5);
							$token .= date('Hdm');
							$data['sendLink'] = base_url() . 'Login/resetPassword/' . $clientCode . '/' . $token;
							$sendLink = base_url() . 'Login/resetPassword/' . $clientCode . '/' . $token;
							$to = $userEmail;
							$subject = 'Reset your credentials';
							$message = '
                                <html>
                                <body>
                                    <p>Hello,</p>
                                    <p>We have received a request to reset password. If you did not make the request just ignore this email. Otherwise, you can reset your password using this link.</p> 
                                    <p><a  href="' . $sendLink . '" target="_blank" style="margin-bottom:8px;background:green; width:50%; padding: 8px 12px; border: 1px solid green;border-radius: 2px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #ffffff;text-decoration: none;font-weight:bold;display: inline-block; text-align:center;">RESET PASSWORD</a></p>
                                </body>
                                </html>
                            ';
							print_r($this->emailer->transact($mailsettings, $to, $subject, $message, []));
							die;
						}
					}
				}
			}
		}
	}
}
