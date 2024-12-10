<?php
class Authentication extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('AdminModel');
        $this->load->library('form_validation');
        $this->load->helper('form', 'url', 'html');
    }

    public function user_login_process()
    {
        if ($this->input->post('btnSubmit')) {
            $this->form_validation->set_rules('cmpcode', 'Company Code', 'trim|required');
            $this->form_validation->set_rules('loginpin', 'Login Pin', 'trim|required');
            if ($this->form_validation->run() == false) {
                redirect("Cashier/login");
            } else {
                $companyCode = trim($this->input->post('cmpcode'));
                $clientCode = $this->AdminModel->checkCompanySubscription($companyCode);
                if ($clientCode == 'false') {
                    $this->session->set_flashdata('message', 'Invalid Credentials');
                    redirect("Cashier/login");
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
                        sleep(1);
                        $this->load->model('GlobalModel');
                        $data = array('loginpin' => $this->input->post('loginpin'));
                        $result = $this->GlobalModel->login_pin($data);
                        if ($result == true) {
                            $loginpin = $this->input->post('loginpin');
                            $result = $this->GlobalModel->read_user_information_withpin($loginpin);
                            if ($result != false) {
                                $res = $this->GlobalModel->checkActiveSubscription();
                                if ($res == "EXPIRED") {
                                    $this->load->view('errors/exppackage.php');
                                }
                                $t = 'cashier_supermarket' . time();
                                $rolename = $this->GlobalModel->selectQuery('rolesmaster.code,rolesmaster.role', 'rolesmaster', array('rolesmaster.code' => $result[0]->userRole))->result_array()[0]['role'];
                                $this->session->set_userdata('cash_key' . CASH_SESS_KEY, $t);
                                $session_data = array(
                                    'cmpCode' => $cmpCode,
                                    'cmpdbkey' => $companyCode,
                                    'cmpcode' => $cmpCode,
                                    'code' => $result[0]->code,
                                    'branchCode' => trim($result[0]->userBranchCode),
                                    'empno' => $result[0]->userEmpNo,
                                    'name' => $result[0]->name,
                                    'loginpin' => $result[0]->loginpin,
                                    'username' => $result[0]->userName,
                                    'rolecode' => $result[0]->userRole,
                                    'role' => $rolename,
                                    'email' => $result[0]->userEmail,
                                    'lang' => $result[0]->userLang,
                                    'userImage' => $result[0]->userImage
                                );
                                // Add user data in session
                                $this->session->set_userdata('cash_logged_in' . $t, $session_data);
                                $addID = $this->session->userdata['cash_logged_in' . $t]['code'];
                                $userRole = $this->session->userdata['cash_logged_in' . $t]['role'];
                                $ip = $_SERVER['REMOTE_ADDR'];
                                $date = date('d-m-y h:i:s');
                                $txt = $addID . " " . "Cashier logged in.";
                                $activity_text = "Dt --> " . $date . " IP --> " . $ip . " LOGIN USER --> " . $addID . " ADDED USER --> " . $addID . " 	" . $txt;
                                $this->GlobalModel->activity_log($activity_text);
                                redirect("Cashier/dashboard");
                            }
                        } else {
                            $this->session->set_flashdata('message', 'Invalid Pin');
                            redirect("Cashier/login");
                        }
                    } else {
                        $this->session->set_flashdata('message', 'Invalid Credentials');
                        redirect("Cashier/login");
                    }
                }
            }
        } else {
            redirect("Cashier/login");
        }
    }

    // Logout from admin page
    public function logout()
    {
        $this->load->model("GlobalModel");
        $session_key = $this->session->userdata('cash_key' . CASH_SESS_KEY);
        $loginpin = ($this->session->userdata['cash_logged_in' . $session_key]['loginpin']);
        $addID = $this->session->userdata['cash_logged_in' . $session_key]['code'];
        $userRole = $this->session->userdata['cash_logged_in' . $session_key]['role'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $date = date('d-m-y h:i:s');
        $txt = $addID . " " . "Cashier loggedout.";
        $activity_text = "Dt --> " . $date . " IP --> " . $ip . " LOGIN USER --> " . $addID . " ADDED USER --> " . $addID . " 	" . $txt;
        $this->GlobalModel->activity_log($activity_text);
        // Removing session data
        $sess_array = array('loginpin' => $loginpin);
        $this->session->unset_userdata('cash_logged_in' . $session_key, $sess_array);
        $this->session->unset_userdata('current_db' . $session_key, "");
        $this->session->set_flashdata('logout_message', 'Successfully Logged out');
        redirect("Cashier/Login", "refresh");
    }
}
