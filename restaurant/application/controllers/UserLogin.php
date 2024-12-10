<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UserLogin extends CI_Controller
{
    var $session_key;
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form', 'url', 'html');
        $this->load->model('GlobalModel');
        $this->load->library('form_validation');
        $this->load->library('sendemail');
    }

    public function index()
    {
        $this->load->view('dashboard/login/userlogin');
    }

    public function reset()
    {
        $this->load->view('dashboard/login/userreset');
    }

    public function resetLoginPin()
    {
        $urlToken = $this->uri->segment('3');
        $Records = $this->GlobalModel->selectQuery('usermaster.*', 'usermaster', array('usermaster.token' => $urlToken));
        if ($Records) {
            $code = $Records->result()[0]->code;
            $data['code'] = $code;
            $data['loginpin'] = rand(1000, 9999);
            $data['token'] = $urlToken;
            $this->load->view('dashboard/login/userrecover', $data);
        } else {
            $this->session->set_flashdata('message_new', 'Loginpin Reset Link is Expired Please Reset Password Again to Continue');
            redirect("UserLogin");
        }
    }

    public function updateLoginPin()
    {
        $code = $this->input->post('code');
        $token = $this->input->post('token');
        $loginpin = $this->input->post('loginpin');
        $Records = $this->GlobalModel->selectQuery('usermaster.*', 'usermaster', array('usermaster.token' => $token));
        if ($Records) {
            $data = array(
                'loginpin' => $this->input->post('loginpin'),
                'token' => null,
                'loginpin' => $loginpin
            );
            $result = $this->GlobalModel->doEdit($data, 'usermaster', $code);
            if ($result) {
                $this->session->set_flashdata('message', 'Loginpin Reset Successfully.. Please Login to Continue.');
                redirect("UserLogin");
            } else {
                $this->session->set_flashdata('message', 'Problem During Reset Loginpin.. Please Try Again');
                redirect("UserLogin/resetLoginPin/" . $token);
            }
        } else {
            $this->session->set_flashdata('message', 'Reset Link ins broken! Please try again...');
            redirect("UserLogin");
        }
    }


    public function recover()
    {
        $this->form_validation->set_rules('userEmail', 'Email ', 'required|valid_email');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message', 'Email is requried');
            $this->load->view('dashboard/login/userreset');
        } else {
            $userEmail = $this->input->post('userEmail');
            $result = $this->GlobalModel->selectQuery('usermaster.*', 'usermaster', array('usermaster.userEmail' => $userEmail, 'usermaster.userRole !=' => 'R_15'));

            if (!empty($result)) {
                $resdata = $result->result_array()[0];
                $token = $this->GlobalModel->randomCharacters(5);
                $token .= date('Hdm');
                $data['sendLink'] = base_url() . 'UserLogin/resetLoginPin/' . $token;
                $sendLink = base_url() . 'UserLogin/resetLoginPin/' . $token;
                $to = $userEmail;
                $subject = 'Reset your Login Pin';
                $message = '<html>
						<body>
							<p>Hello,</p>
							<p>We have received a request to reset password. If you did not make the request just ignore this email. Otherwise, you can reset your login pin using this link.</p>
							<p><a href="' . $sendLink . '" target="_blank" style="margin-bottom:8px;background:green; width:50%; padding: 8px 12px; border: 1px solid green;border-radius: 2px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #ffffff;text-decoration: none;font-weight:bold;display: inline-block; text-align:center;">RESET LOGIN PIN</a></p>
						</body>
						</html>';

                $mail = $this->sendemail->sendMailOnly($to, $subject, $message);
                $code = $resdata['code'];
                $data = array('token' => $token);
                $resultAfterMail = $this->GlobalModel->doEditWithField($data, 'usermaster', 'code', $code);
                if ($resultAfterMail) {
                    $this->session->set_flashdata('message', 'Reset Link was sent to your email...');
                    $this->load->view('dashboard/login/userreset');
                } else {
                    $this->session->set_flashdata('message', 'Error Occured! Please try again!');
                    $this->load->view('dashboard/login/userreset');
                }
            } else {
                $this->session->set_flashdata('message', 'No users were found with the email address provided! Sorry cannot reset the password');
                $this->load->view('dashboard/login/userreset');
            }
        }
    }
}
