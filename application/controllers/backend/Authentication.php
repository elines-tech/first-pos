<?php
class Authentication extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function user_login_process()
    {
        if ($this->input->post('btnSubmit')) {
            $this->form_validation->set_rules('username', 'Username', 'trim|required');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
            $enc_password = md5($this->input->post('password'));
            if ($this->form_validation->run() == false) {
                redirect("login");
            } else {
                $enc_password = md5($this->input->post('password'));
                $data = array('username' => $this->input->post('username'), 'password' => $enc_password);
                $result = $this->GlobalModel->login($data);
                if ($result == true) {
                    $username = $this->input->post('username');
                    $result = $this->GlobalModel->read_user_information($username);
                    if ($result != false) {
                        $t = 'superadmin' . time();
                        $this->session->set_userdata('key' . SESS_KEY, $t);
                        $rolename = $this->GlobalModel->selectQuery('rolesmaster.code,rolesmaster.role', 'rolesmaster', array('rolesmaster.code' => $result[0]->userRole))->result_array()[0]['role'];
                        $session_data = array('code' => $result[0]->code, 'empno' => $result[0]->userEmpNo, 'name' => $result[0]->firstName." ".$result[0]->lastName, 'username' => $result[0]->username, 'role' => $rolename, 'rolecode' => $result[0]->userRole, 'email' => $result[0]->userEmail, 'lang' => $result[0]->userLang, 'userImage' => $result[0]->profilePhoto);
                        // Add user data in session
                        $this->session->set_userdata('logged_in' . $t, $session_data);
                        $addID = $this->session->userdata['logged_in' . $t]['code'];
                        $userRole = $this->session->userdata['logged_in' . $t]['role'];
						$userName = $this->session->userdata['logged_in' . $t]['username'];
                        $ip = $_SERVER['REMOTE_ADDR'];
                        $date = date('d-m-y h:i:s');
                        //activity track starts
                        $txt = $addID . " " . "Super User id is login.";
                        $activity_text = "Dt --> " . $date . " IP --> " . $ip . " LOGIN USER --> " . $addID . " ADDED USER --> " . $addID . " 	" . $txt;
                        $this->GlobalModel->activity_log($activity_text);
                        if (file_exists('assets/rights/' . $result[0]->userRole . '.json')) {
                            $rightscontents = file_get_contents('assets/rights/' . $result[0]->userRole . '.json');
                            $rightJson = json_decode($rightscontents, true);
                            $filecontents = file_get_contents('assets/rights/menu.json');
                            $menuJson = json_decode($filecontents, true);
                            $key = array_search("1", array_column($rightJson, 'default'));
                            //echo $key;
                            $menuId = $rightJson[$key]['menu'];
                            $defaultUrlKey = array_search(explode(".", $menuId)[0], array_column($menuJson, 'id'));
                            $defaultUrl = $menuJson[$defaultUrlKey]['submenu'][explode(".", $menuId)[1] - 1]['url'];
                            redirect($defaultUrl);
                        } else {
                            $this->session->set_flashdata('message', "You don't have access to the system..please contact administrator");
                            redirect("login");
                        }
                    }
                } else {
                    $this->session->set_flashdata('message', 'Invalid Username or Password');
                    redirect("login");
                }
            }
        } else {
            redirect("login");
        }
    }

    // Logout from admin page
    public function logout()
    {
        $session_key = $this->session->userdata('key' . SESS_KEY);
        $userNm = ($this->session->userdata['logged_in' . $session_key]['username']);
        $addID = $this->session->userdata['logged_in' . $session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $session_key]['role'];
        // Removing session data
        $sess_array = array('username' => $userNm);
        $this->session->unset_userdata('logged_in' . $session_key, $sess_array);
        $this->session->unset_userdata('current_db' . $session_key, "");
        $ip = $_SERVER['REMOTE_ADDR'];
        $date = date('d-m-y h:i:s');
        //activity track starts
        $txt = $addID . " " . "User id is logout.";
        $activity_text = "Dt --> " . $date . " IP --> " . $ip . " LOGIN USER --> " . $addID . " ADDED USER --> " . $addID . " 	" . $txt;
        $this->GlobalModel->activity_log($activity_text);
        //activity track end
        $this->session->set_flashdata('logout_message', 'Successfully Logged out');
        redirect("login");
    }


    public function logoutUser()
    {
        $session_key = $this->session->userdata('key' . SESS_KEY);
        $userNm = ($this->session->userdata['logged_in' . $session_key]['username']);
        $addID = $this->session->userdata['logged_in' . $session_key]['code'];
        $role = "";
        // Removing session data
        $sess_array = array('username' => $userNm);
        $this->session->unset_userdata('logged_in' . $session_key, $sess_array);
        $this->session->unset_userdata('current_db' . $session_key, "");

        $this->session->set_flashdata('logout_message', 'Successfully Logged out');
        redirect("login/userlogin");
    }

    public function resetAdminPassword()
    {
    }
}
