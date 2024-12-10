<?php
class Authentication extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('form', 'url', 'html');
        $this->load->model('AdminModel');
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
                $companyCode = $this->input->post('companyCode');
                $clientCode = $this->AdminModel->checkCompanySubscription($companyCode);
                if ($clientCode == 'false') {
                    $this->session->set_flashdata('message', 'Invalid Credentials');
                    redirect("login");
                } else {
                    $tableName = "clients";
                    $orderColumns = array("databasemaster.databaseName,clients.companyname,clients.code");
                    $condition = array("clients.code" => $clientCode);
                    $orderBy = array();
                    $joinType = array('databasemaster' => 'inner');
                    $join = array('databasemaster' => 'clients.code = databasemaster.companyCode');
                    $CompanyDbResult = $this->AdminModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType);
                    if ($CompanyDbResult != false) {
                        $t = 'receipt' . time();
                        $this->session->set_userdata('key' . SESS_KEY, $t);
                        $this->session->set_userdata('current_db' . $t, $CompanyDbResult->result_array()[0]["databaseName"]);
                        $company = $CompanyDbResult->result_array()[0]["companyname"];
                        $cmpCode = $CompanyDbResult->result_array()[0]["code"];
                        $this->load->model('GlobalModel');
                        $enc_password = md5($this->input->post('password'));
                        $data = array('username' => $this->input->post('username'), 'password' => $enc_password);
                        $result = $this->GlobalModel->login($data);
                        if ($result == true) {
                            $username = $this->input->post('username');
                            $result = $this->GlobalModel->read_user_information($username);
                            if ($result != false) {
                                //Project Name with time appended Key for session							
                                $rolename = "User";
                                $roledata = $this->GlobalModel->selectQuery('rolesmaster.code,rolesmaster.role', 'rolesmaster', array('rolesmaster.code' => $result[0]->userRole));
                                if ($roledata) {
                                    $rolename = ucwords(strtolower($roledata->result_array()[0]['role']));
                                }
                                $branchCode = $result[0]->userBranchCode != null ? $result[0]->userBranchCode : "";
                                $session_data = array(
                                    'cmpCode' => $cmpCode,
                                    'cmpdbkey' => $companyCode,
                                    'cmpcode' => $cmpCode,
                                    'code' => $result[0]->code,
                                    'branchCode' => trim($branchCode),
                                    'empno' => $result[0]->userEmpNo,
                                    'name' => $result[0]->name,
                                    'username' => $result[0]->userName,
                                    'role' => $rolename,
                                    'rolecode' => $result[0]->userRole,
                                    'email' => $result[0]->userEmail,
                                    'lang' => $result[0]->userLang,
                                    'userImage' => $result[0]->userImage
                                );
                                // Add user data in session
                                $this->session->set_userdata('logged_in' . $t, $session_data);
                                $addID = $this->session->userdata['logged_in' . $t]['code'];
                                $ip = $_SERVER['REMOTE_ADDR'];
                                $date = date('d-m-y h:i:s');
                                //activity track starts
                                $txt = $addID . " " . "User id is login.";
                                $activity_text = "Dt --> " . $date . " IP --> " . $ip . " LOGIN USER --> " . $addID . " ADDED USER --> " . $addID . " 	" . $txt;
                                $this->GlobalModel->activity_log($activity_text);
                                //activity track end
                                $firstInstall = $this->GlobalModel->checkfirstSubscrription();
                                if ($firstInstall)  redirect('onboard', 'refresh');
                                else redirect("dashboard/listRecords", 'refresh');
                            }
                        } else {
                            $this->session->set_flashdata('message', 'Invalid Username or Password');
                            redirect("login", "refresh");
                        }
                    } else {
                        $this->session->set_flashdata('message', 'Invalid Credentials');
                        redirect("login", "refresh");
                    }
                }
            }
        } else {
            redirect("login", "refresh");
        }
    }

    // Logout from admin page
    public function logout()
    {
        $this->load->model("GlobalModel");
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
        redirect("Login", "refresh");
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
        redirect("Login/userLogin", "refresh");
    }

    public function resetAdminPassword()
    {
    }
}
