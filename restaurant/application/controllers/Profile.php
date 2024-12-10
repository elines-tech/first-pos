<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller
{
	var $session_key;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('GlobalModel');
		$this->session_key = $this->session->userdata('key' . SESS_KEY);
		if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
			redirect('Login', 'refresh');
		}
		$res = $this->GlobalModel->checkActiveSubscription();
        if ($res == "EXPIRED") {
            redirect('package', 'refresh');
        }
	}

	public function view()
	{
		$code = $this->uri->segment(3);
		$userData = $this->GlobalModel->get_data($code, 'usermaster');
		$data['userData'] = $userData;
		$branchCode = $userData[0]['userBranchCode'];
		$role = $userData[0]['userRole'];
		/*if ($role == 'R_17') {
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/profile/viewProfile', $data);
			$this->load->view('dashboard/footer');
		} else {*/
			$this->load->view('dashboard/header');

			$this->load->view('dashboard/profile/view', $data);
			$this->load->view('dashboard/footer');
		/*}*/
	}

	public function update()
	{
		$code = $this->input->post("code");
		$user = $this->input->post("username");
		$userEmail = $this->input->post("useremail");
		$userLang = $this->input->post("userlanguage");
		$fullname = $this->input->post("fullname");
		$ip = $_SERVER['REMOTE_ADDR'];
		$date = date('d-m-y h:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$empno = $this->session->userdata['logged_in' . $this->session_key]['empno'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userRoleCode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		$dbName = $this->session->userdata['current_db' . $this->session_key];
		$this->db->query('use ' . $dbName);		
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$resultUser = $this->db->query("SELECT * FROM usermaster WHERE userName='" . $userName . "' AND code !='" . $code . "' and (`isDelete` IS NULL OR `isDelete`='0')");
		if ($resultUser->num_rows() > 0) {
			$data['error_message'] = 'User Name already exists';
			$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/sidebar');
			$this->load->view('dashboard/profile/viewProfile', $data);
			$this->load->view('dashboard/footer');
		} else {
			$resultEmail = $this->db->query("SELECT * FROM usermaster WHERE userEmail='" . $userEmail . "' AND code !='" . $code . "' and (`isDelete` IS NULL OR `isDelete`='0')");
			if ($resultEmail->num_rows() > 0) {
				$data['error_message'] = 'User Email already exists.';
				$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
				$this->load->view('dashboard/header');
				$this->load->view('dashboard/sidebar');
				$this->load->view('dashboard/profile/viewProfile', $data);
				$this->load->view('dashboard/footer');
			} else {
				$this->form_validation->set_rules('userlanguage', 'User Language', 'trim|required');
				$this->form_validation->set_rules('useremail', 'User Email', 'trim|required');
				if ($this->form_validation->run() == FALSE) {
					//$data['error_message'] = '* Fields are Required!';
					$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
					$this->load->view('dashboard/header');
					$this->load->view('dashboard/sidebar');
					$this->load->view('dashboard/profile/viewProfile', $data);
					$this->load->view('dashboard/footer');
				} else {

					$data = array(
						'userName' => $this->input->post("username"),
						'userLang' =>	$this->input->post("userlanguage"),
						'userEmail' =>	$this->input->post("useremail"),
						'name' => $this->input->post("fullname"),
						'editID' => $addID,
						'editIP' => $ip,
					);

					$result = $this->GlobalModel->doEdit($data, 'usermaster', $code);
					if ($result == true) {
						$filedoc = false;
						$userImage = '';
						$uploadDir = 'upload/userImage/';
						if (!file_exists($uploadDir)) mkdir($uploadDir, 0755, false);
						if (!empty($_FILES['userImage']['name'])) {

							$filepro = $this->db->query("SELECT userImage FROM usermaster WHERE `code` = '" . $code . "'");
							if ($userImage != "") {
								unlink($uploadDir . $filepro);
							}
							$tmpFile = $_FILES['userImage']['tmp_name'];
							$ext = pathinfo($_FILES['userImage']['name'], PATHINFO_EXTENSION);
							$filename = $uploadDir . '/' . $code . '-' . time() . '.' . $ext;
							move_uploaded_file($tmpFile, $filename);
							if (file_exists($filename)) {
								$userImage = $uploadDir . $code . '-' . time() . '.' . $ext;
							}
							$subData = array(
								'userImage' => $userImage
							);
							$filedoc = $this->GlobalModel->doEdit($subData, 'usermaster', $code);
						}
						$txt = $code . " - " . $user . " Profile is updated.";
						$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
						$this->GlobalModel->activity_log($activity_text);
						$sess_array = array('username' => $this->input->post("username"));
						$this->session->unset_userdata('logged_in' . $this->session_key, $sess_array);
						$this->session->unset_userdata('current_db' . $this->session_key, "");
						$t = 'cashier_supermarket' . time();
						$this->session->set_userdata('key' . SESS_KEY, $t);
						$session_data = array('code' => $code, 'name' => $fullname, 'empno' => $empno, 'username' => $user, 'rolecode' => $userRoleCode, 'role' => $userRole, 'email' => $userEmail, 'lang' => $userLang, 'userImage' => $userImage);
						$this->session->set_userdata('logged_in' . $t, $session_data);
						$response['status'] = true;
						$response['message'] = 'Profile Successfully Updated.';
					} else {
						$response['status'] = false;
						$response['message'] = "No change In Profile";
					}
					$this->session->set_flashdata('message', json_encode($response));
					redirect('Profile/view/' . $code, 'refresh');
				}
			}
		}
	}

	public function updateUserProfile()
	{
		$code = $this->input->post("code");
		$user = $this->input->post("username");
		$userEmail = $this->input->post("useremail");
		$userempnumber = $this->input->post("userempnumber");
		$loginpin = $this->input->post("loginpin");
		$fullname = $this->input->post("fullname");
		$cmpcode = $this->GlobalModel->getCompcode();
		$ip = $_SERVER['REMOTE_ADDR'];
		$date = date('d-m-y h:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		
		$table = "usermaster";
		$where7 = ["userName" => $user, "code!=" => $code];
		$resultUser =  $this->GlobalModel->hasSimilarRecords($where7, $table);	
		
		$where = ["userEmpNo" => $userempnumber, "code!=" => $code];
		$resultEmpNo =  $this->GlobalModel->hasSimilarRecords($where, $table);
        if($resultUser){
			$data['error_message'] = 'User Name already exist';
			$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/sidebar');
			$this->load->view('dashboard/profile/view', $data);
			$this->load->view('dashboard/footer');
		}			
		else if ($resultEmpNo) {
			$data['error_message'] = 'User Employee Number already exist';
			$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/sidebar');
			$this->load->view('dashboard/profile/view', $data);
			$this->load->view('dashboard/footer');
		}else {
			$where = ["userEmail" => $userEmail, "code!=" => $code];
			$resultEmail =  $this->GlobalModel->hasSimilarRecords($where, $table);			
			if ($resultEmail) {
				$data['error_message'] = 'User Email already exist.';
				$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
				$this->load->view('dashboard/header');
				$this->load->view('dashboard/sidebar');
				$this->load->view('dashboard/profile/view', $data);
				$this->load->view('dashboard/footer');
			} else {
				$this->form_validation->set_rules('userlanguage', 'User Language', 'trim|required');
				$this->form_validation->set_rules('userempnumber', 'User Employee Number', 'trim|required');
				$this->form_validation->set_rules('useremail', 'User Email', 'trim|required');
				$this->form_validation->set_rules('fullname', 'Full Name', 'trim|required');
				$this->form_validation->set_rules('username', 'User Name', 'trim|required');
				if ($this->form_validation->run() == FALSE) {
					//$data['error_message'] = '* Fields are Required!';
					$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
					$this->load->view('dashboard/header');
					$this->load->view('dashboard/sidebar');
					$this->load->view('dashboard/profile/view', $data);
					$this->load->view('dashboard/footer');
				} else {

					$data = array(
						'userName' => $this->input->post("username"),
						'userLang' =>	$this->input->post("userlanguage"),
						'userEmpNo' =>	$this->input->post("userempnumber"),
						'userEmail' =>	$this->input->post("useremail"),
						'name' =>	$this->input->post("fullname"),
						'editID' => $addID,
						'editIP' => $ip,
					);
					$result = $this->GlobalModel->doEdit($data, 'usermaster', $code);
					if ($result == true) {
						$filedoc = false;
						$filename = '';
						$uploadDir = "upload/userImage/$cmpcode";
						if (!file_exists($uploadDir)) mkdir($uploadDir, 0755, false);
						if (!empty($_FILES['userImage']['name'])) {

							$filepro = $this->GlobalModel->directQuery("SELECT userImage FROM usermaster WHERE `code` = '" . $code . "'");
							if (!empty($filepro)) {
								$myImg = $filepro[0]['userImage'];
								if ($myImg != "" && file_exists($myImg))  unlink($myImg);
							}
							$tmpFile = $_FILES['userImage']['tmp_name'];
							$ext = pathinfo($_FILES['userImage']['name'], PATHINFO_EXTENSION);
							$filename = $uploadDir . '/' . $code . '-' . time() . '.' . $ext;
							move_uploaded_file($tmpFile, $filename);
							if (file_exists($filename)) {
								$subData = array(
									'userImage' => $filename
								);
							$filedoc = $this->GlobalModel->doEdit($subData, 'usermaster', $code);
							}
							
						}
						$txt = $code . " - " . $user . " Profile is updated.";
						$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
						$this->GlobalModel->activity_log($activity_text);
						if ($this->input->post("userrole") == "R_5") {
							$response['status'] = true;
							$response['message'] = 'Profile Successfully Updated.';
						} else {
							$response['status'] = true;
							$response['message'] = 'Profile Successfully Updated.';
						}
					} else {
						$response['status'] = false;
						$response['message'] = "No change In Profile";
					}
					$this->session->set_flashdata('message', json_encode($response));
					redirect('profile/view/' . $code, 'refresh');
				}
			}
		}
	}

	public function updatePassword()
	{
		$code = $this->uri->segment(3);
		$userData = $this->GlobalModel->get_data($code, 'usermaster');
		$data['userData'] = $userData;
		$this->load->view('dashboard/header');

		$this->load->view('dashboard/profile/updatePassword', $data);
		$this->load->view('dashboard/footer');
	}

	public function passwordUpdate()
	{
		$code = $this->input->post("code");
		$user = $this->input->post("username");
		$password = $this->input->post("password");
		$ip = $_SERVER['REMOTE_ADDR'];
		$date = date('d-m-y h:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];

		$this->form_validation->set_rules('password', 'password', 'trim|required');
		$this->form_validation->set_rules('confirmpassword', 'Confirm Password', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			//$data['error_message'] = '* Fields are Required!';
			$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
			$this->load->view('dashboard/header');

			$this->load->view('dashboard/profile/updatePassword', $data);
			$this->load->view('dashboard/footer');
		} else {
			$data = array(
				'userPassword' => md5($this->input->post("password")),
				'editID' => $addID,
				'editIP' => $ip,
			);
			$result = $this->GlobalModel->doEdit($data, 'usermaster', $code);
			if ($result == true) {
				$txt = $code . " - " . $user . " Password is updated.";
				$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
				$this->GlobalModel->activity_log($activity_text);
				$response['status'] = true;
				$response['message'] = 'Password Successfully Updated.';
			} else {
				$response['status'] = false;
				$response['message'] = "No change";
			}
			$this->session->set_flashdata('message', json_encode($response));
			redirect('profile/view/' . $code, 'refresh');
		}
	}
}
