<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    var $session_key;
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form', 'url', 'html');
        $this->load->model('ForgotpasswordModel');
        $this->load->model('ClientModel');
        $this->load->library('form_validation');
        $this->load->library('sendemail');
    }

    public function index()
    {
        $this->load->view('cashier/login/login');
    }

    public function test()
    {
        $this->load->view('errors/exppackage.php');
    }

    public function resetpin()
    {
        $this->load->view('cashier/login/resetpin');
    }

    public function resetLoginPin()
    {
        $this->load->model('GlobalModel');
        $urlToken = $this->uri->segment('5');
        $clientCode = $this->uri->segment('4');
        $getClientDB = $this->ForgotpasswordModel->getClientData($clientCode);
        if ($getClientDB != false) {
            $this->db->query('use ' . $getClientDB);
        }
        $Records = $this->GlobalModel->selectQuery('usermaster.*', 'usermaster', array('usermaster.token' => $urlToken));
        if ($Records) {
            $code = $Records->result()[0]->code;
            $data['code'] = $code;
            $data['loginpin'] = rand(1000, 9999);
            $data['cmpcode'] = $clientCode;
            $data['token'] = $urlToken;
            $this->load->view('cashier/login/updatepin', $data);
        } else {
            $this->session->set_flashdata('message_new', 'Loginpin Reset Link is Expired Please Reset Password Again to Continue');
            redirect("Cashier/login");
        }
    }

    public function updateLoginPin()
    {
        $this->load->model('GlobalModel');
        $code = $this->input->post('code');
        $token = $this->input->post('token');
        $loginpin = $this->input->post('loginpin');
        $clientCode = $this->input->post('cmpcode');
        $getClientDB = $this->ForgotpasswordModel->getClientData($clientCode);
        if ($getClientDB != false) {
            $this->db->query('use ' . $getClientDB);
        }
        $Records = $this->GlobalModel->selectQuery('usermaster.*', 'usermaster', array('usermaster.token' => $token));
        if ($Records) {
            $pin = $this->GlobalModel->selectQuery('usermaster.*', 'usermaster', array('usermaster.loginpin' => $loginpin,'usermaster.code !=' =>$code));
			 if($pin){
				 $this->session->set_flashdata('message_duplicate_login_pin', 'Duplicate login pin. Plz enter another pin.');
				 redirect('Cashier/login/resetLoginPin/' . $clientCode . '/' . $token);
			 }else{
				$data = array(
					'loginpin' => $this->input->post('loginpin'),
					'token' => null,
					'loginpin' => $loginpin
				);
				$result = $this->GlobalModel->doEdit($data, 'usermaster', $code);
				if ($result) {
					$this->session->set_flashdata('message', 'Loginpin Reset Successfully.. Please Login to Continue.');
					redirect("Cashier/login");
				} else {
					$this->session->set_flashdata('message', 'Problem During Reset Loginpin.. Please Try Again');
					redirect('Cashier/login/resetLoginPin/' . $clientCode . '/' . $token);
				}
			 }
        } else {
            $this->session->set_flashdata('message', 'Reset Link ins broken! Please try again...');
            redirect("Cashier/login");
        }
    }

    public function recover()
    {
        $this->load->model('GlobalModel');
        $this->form_validation->set_rules('userEmail', 'Email ', 'required|valid_email');
        $this->form_validation->set_rules('cmpcode', 'Company code ', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('cashier/login/resetpin');
        } else {
            $userEmail = $this->input->post('userEmail');
            $companyCode = strtoupper(strtolower($this->input->post('cmpcode')));
            $clientCode = $this->ClientModel->getCliCode($companyCode);
            if ($clientCode == 'false') {
                $this->session->set_flashdata('message', 'Company code is not found. Sorry cannot reset the password');
                $this->load->view('cashier/login/resetpin');
            } else {
                $getClientDB = $this->ForgotpasswordModel->getClientData($clientCode);
                if ($getClientDB != false) {
                    $this->db->query('use ' . $getClientDB);
                    $result = $this->GlobalModel->selectQuery('usermaster.*', 'usermaster', array('usermaster.userEmail' => $userEmail, 'usermaster.userRole' => 'R_5'));
                    if ($result) {
                        $token = $this->GlobalModel->randomCharacters(5);
                        $token .= date('Hdm');
                        $data['sendLink'] = base_url() . 'Cashier/Login/resetLoginPin/' . $clientCode . '/' . $token;
                        $sendLink = base_url() . 'Cashier/Login/resetLoginPin/' . $clientCode . '/' . $token;
                        $to = $userEmail;
                        $subject = 'Reset your Pin';
                        $message = '<html>
											<body>
												<p>Hello,</p>
												<p>We have received a request to reset your login pin. If you did not make the request just ignore this email. Otherwise, you can reset your login pin using this link.</p>
												<p><a href="' . $sendLink . '" target="_blank" style="margin-bottom:8px;background:green; width:50%; padding: 8px 12px; border: 1px solid green;border-radius: 2px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #ffffff;text-decoration: none;font-weight:bold;display: inline-block; text-align:center;">RESET LOGIN PIN</a></p>
											</body>
									</html>';

                        $mail = $this->sendemail->sendMailOnly($to, $subject, $message);
                        $code = $result->result_array()[0]["code"];
                        $data = array('token' => $token);
                        $resultAfterMail = $this->GlobalModel->doEditWithField($data, 'usermaster', 'code', $code);
                        if ($resultAfterMail) {
                            $this->session->set_flashdata('message', 'Reset Link was sent to your email...');
                            $this->load->view('cashier/login/resetpin');
                        } else {
                            $this->session->set_flashdata('message', 'Error Occured! Please try again!');
                            $this->load->view('cashier/login/resetpin');
                        }
                    } else {
                        $this->session->set_flashdata('message', 'No users were found with the email address provided! Sorry cannot reset the password');
                        $this->load->view('cashier/login/resetpin');
                    }
                }
            }
        }
    }
}
