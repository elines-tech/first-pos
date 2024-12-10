<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    var $session_key;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('sendemail');
    }

    public function index()
    {
        $this->load->view('backend/login/login', [], "Login");
    }

    public function reset()
    {
        $this->load->view('backend/login/reset',[], "Login");
    }


    public function resetPassword()
    {
        $urlToken = $this->uri->segment('3');
        $Records = $this->GlobalModel->selectQuery('usermaster.*', 'usermaster', array('usermaster.token' => $urlToken));
        if ($Records) {
            $code = $Records->result()[0]->code;
            $data['code'] = $code;
            $data['token'] = $urlToken;
            $this->load->view('backend/login/recover', $data, "Reset Password");
        } else {
            $this->session->set_flashdata('message', 'Password Reset Link is Expired Please Reset Password Again to Continue');
            redirect("login");
        }
    }


    public function updatePassword()
    {
        $code = $this->input->post('code');
        $token = $this->input->post('token');
        $Records = $this->GlobalModel->selectQuery('usermaster.*', 'usermaster', array('usermaster.token' => $token));
        if ($Records) {
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
            $this->form_validation->set_rules('confirmPassword', 'Confirm password', 'trim|required|matches[password]');
            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('message', 'Password not provided or donot match...');
                redirect("login/resetPassword/" . $token);
            } else {
                $data = array(
                    'password' => md5($this->input->post('password')),
                    'token' => null,
                );
                $result = $this->GlobalModel->doEdit($data, 'usermaster', $code);
                if ($result) {
                    $this->session->set_flashdata('message', 'Password Reset Successfully.. Please Login to Continue in app');
                    redirect("login");
                } else {
                    $this->session->set_flashdata('message', 'Problem During Reset Password.. Please Try Again');
                    redirect("login/resetPassword/" . $token);
                }
            }
        } else {
            $this->session->set_flashdata('message', 'Reset Link ins broken! Please try again...');
            redirect("login");
        }
    }

    public function recover()
    {
        $this->form_validation->set_rules('userEmail', 'Email ', 'required|valid_email');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message', 'Email is requried');
            $this->load->view('login/reset');
        } else {
            $userEmail = $this->input->post('userEmail');
            $result = $this->GlobalModel->checkrecord_exists('userEmail', $userEmail, 'usermaster');
            if (!empty($result)) {
                //if ($result != 'false') {
                $token = $this->GlobalModel->randomCharacters(5);
                $token .= date('Hdm');
                $data['sendLink'] = base_url() . 'login/resetPassword/' . $token;
                $sendLink = base_url() . 'login/resetPassword/' . $token;
                $to = $userEmail;
                $subject = 'Reset your Password'; 
                $message = '<html>
						<body>
							<p>Hello,</p>
							<p>We have received a request to reset password. If you did not make the request just ignore this email. Otherwise, you can reset ypur password using this link.</p>
							<p><a  href="' . $sendLink . '" target="_blank" style="margin-bottom:8px;background:green; width:50%; padding: 8px 12px; border: 1px solid green;border-radius: 2px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #ffffff;text-decoration: none;font-weight:bold;display: inline-block; text-align:center;">RESET PASSWORD</a></p>
						</body>
						</html>';

                $mail = $this->sendemail->sendMailOnly($to, $subject, $message);
                $code = $result[0]['code'];
                $data = array('token' => $token);
                $resultAfterMail = $this->GlobalModel->doEditWithField($data, 'usermaster', 'code', $code);
                if ($resultAfterMail) {
                    $this->session->set_flashdata('message', 'Reset Link was sent to your email...');
                    $this->load->view('backend/login/reset', [], "Reset Password");
                } else {
                    $this->session->set_flashdata('message', 'Error Occured! Please try again!');
                    $this->load->view('backend/login/reset', [], "Reset Password");
                }
            } else {
                $this->session->set_flashdata('message', 'No users were found with the email address provided! Sorry cannot reset the password');
                $this->load->view('backend/login/reset', [], "Reset Password");
            }
        }
    }
}
