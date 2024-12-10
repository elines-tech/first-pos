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
	}

	public function view()
	{
		//$code = $this->uri->segment(3);
		$code = $this->input->post("code");
		$data['loginpin'] = rand(1000, 9999);
		$userData = $this->GlobalModel->get_data($code, 'usermaster');
		$data['userData'] = $userData;
		$branchCode = $userData[0]['userBranchCode'];
		$role = $userData[0]['userRole'];
		if ($role == 'R_5') {
			$this->load->view('dashboard/header');

			$this->load->view('dashboard/profile/viewProfile', $data);
			$this->load->view('dashboard/footer');
		} else {
			$table_name = 'countermaster';
			$orderColumns = array("countermaster.*");
			$cond = array('countermaster' . '.isDelete' => 0, 'countermaster' . '.isActive' => 1, 'countermaster.branchCode' => $branchCode);
			$data['counterdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);
			$table_name = 'branchmaster';
			$orderColumns = array("branchmaster.*");
			$cond = array('branchmaster' . '.isDelete' => 0, 'branchmaster' . '.isActive' => 1);
			$data['branchdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);
			$table_role = 'rolesmaster';
			$orderColumns_role = array("rolesmaster.*");
			$cond_role = array('rolesmaster' . '.isDelete' => 0, 'rolesmaster' . '.isActive' => 1);
			$data['roledata'] = $this->GlobalModel->selectQuery($orderColumns_role, $table_role, $cond_role);
			$this->load->view('dashboard/header');

			$this->load->view('dashboard/profile/view', $data);
			$this->load->view('dashboard/footer');
		}
	}

	public function update()
	{
		$code = $this->input->post("code");
		$user = $this->input->post("username");
		$userEmail = $this->input->post("useremail");
		$fullname = $this->input->post("fullname");
		$ip = $_SERVER['REMOTE_ADDR'];
		$date = date('d-m-y h:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$cmpcode = $this->session->userdata['logged_in' . $this->session_key]['cmpcode'];
		$resultUser = $this->db->query("SELECT * FROM usermaster WHERE userName='" . $userName . "' AND code !='" . $code . "' and (`isDelete` IS NULL OR `isDelete`='0')");
		if ($resultUser->num_rows() > 0) {
			$data['error_message'] = 'User Name already exists';
			$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/profile/viewProfile', $data);
			$this->load->view('dashboard/footer');
		} else {
			$resultEmail = $this->db->query("SELECT * FROM usermaster WHERE userEmail='" . $userEmail . "' AND code !='" . $code . "' and (`isDelete` IS NULL OR `isDelete`='0')");
			if ($resultEmail->num_rows() > 0) {
				$data['error_message'] = 'User Email already exists.';
				$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
				$this->load->view('dashboard/header');
				$this->load->view('dashboard/profile/viewProfile', $data);
				$this->load->view('dashboard/footer');
			} else {
				$this->form_validation->set_rules('userlanguage', 'User Language', 'trim|required');
				$this->form_validation->set_rules('useremail', 'User Email', 'trim|required');
				if ($this->form_validation->run() == FALSE) {
					//$data['error_message'] = '* Fields are Required!';
					$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
					$this->load->view('dashboard/header');

					$this->load->view('dashboard/profile/viewProfile', $data);
					$this->load->view('dashboard/footer');
				} else {
					$active = 0;
					if ($this->input->post('isActive') == 'on') {
						$active = 1;
					}
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
						$uploadDir = "upload/userImage/$cmpcode";
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
								$subData = array(
									'userImage' => $filename
								);
								$this->GlobalModel->doEdit($subData, 'usermaster', $code);
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
					//redirect('Profile/view/'.$code, 'refresh');
					$response['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
					$this->load->view('dashboard/header');

					$this->load->view('dashboard/profile/viewProfile', $response);
					$this->load->view('dashboard/footer');
				}
			}
		}
	}

	public function updateUserProfile()
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		$date = date('d-m-y h:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$cmpcode = $this->session->userdata['logged_in' . $this->session_key]['cmpcode'];

		$code = trim($this->input->post("code"));
		$userName = trim($this->input->post("username"));
		$userEmail = trim($this->input->post("useremail"));
		$userEmpNo = trim($this->input->post("userempnumber"));
		$userlanguage = trim($this->input->post("userlanguage"));
		$fullname = ucwords(strtolower(trim($this->input->post("fullname"))));

		$this->form_validation->set_rules('userlanguage', 'User Language', 'trim|required');
		$this->form_validation->set_rules('userempnumber', 'User Employee Number', 'trim|required');
		$this->form_validation->set_rules('useremail', 'User Email', 'trim|required');
		$this->form_validation->set_rules('fullname', 'Full Name', 'trim|required');
		$this->form_validation->set_rules('username', 'User Name', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/profile/view', $data);
			$this->load->view('dashboard/footer');
		} else {
			$table = "usermaster";
			$where = ["userEmail" => $userEmail, "code!=" => $code];
			$hasSimilarRecords =  $this->GlobalModel->hasSimilarRecords($where, $table);
			if ($hasSimilarRecords) {
				$data['error_message'] = 'User Email already exists.';
				$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
				$this->load->view('dashboard/header');
				$this->load->view('dashboard/profile/view', $data);
				$this->load->view('dashboard/footer');
			}

			$where = ["userEmpNo" => $userEmpNo, "code!=" => $code];
			$hasSimilarRecords =  $this->GlobalModel->hasSimilarRecords($where, $table);
			if ($hasSimilarRecords) {
				$data['error_message'] = 'Employee number already exists.';
				$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
				$this->load->view('dashboard/header');
				$this->load->view('dashboard/profile/view', $data);
				$this->load->view('dashboard/footer');
			}

			$where = ["userName" => $userName, "code!=" => $code];
			$hasSimilarRecords =  $this->GlobalModel->hasSimilarRecords($where, $table);
			if ($hasSimilarRecords) {
				$data['error_message'] = 'Username already exists.';
				$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
				$this->load->view('dashboard/header');
				$this->load->view('dashboard/profile/view', $data);
				$this->load->view('dashboard/footer');
			}

			$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
			$data = array(
				'userName' => $userName,
				'userLang' => $userlanguage,
				'userEmpNo' => $userEmpNo,
				'userEmail' => $userEmail,
				'name' => $fullname,
				'editID' => $addID,
				'editIP' => $ip,
			);
			$result = $this->GlobalModel->doEdit($data, 'usermaster', $code);
			if ($result == true) {
				$filedoc = false;
				$userImage = '';
				if ($cmpcode) {
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
							$this->GlobalModel->doEdit($subData, 'usermaster', $code);
						}
					}
				}
				$txt = $code . " - " . $addID . " Profile is updated.";
				$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
				$this->GlobalModel->activity_log($activity_text);
				$response['status'] = true;
				$response['message'] = 'Profile Successfully Updated.';
			} else {
				$response['status'] = false;
				$response['message'] = "No change In Profile";
			}
			$this->session->set_flashdata('message', json_encode($response));
			$response['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/profile/view', $response);
			$this->load->view('dashboard/footer');
		}
	}

	public function updatePassword()
	{
		$code = $this->input->post("code");
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
			$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
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
			//redirect('Profile/view/'.$code, 'refresh'); 
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/profile/updatePassword', $data);
			$this->load->view('dashboard/footer');
		}
	}
}
