<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller
{
	var $session_key;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('GlobalModel');
		$this->session_key = $this->session->userdata('cash_key' . CASH_SESS_KEY);
		if (!isset($this->session->userdata['cash_logged_in' . $this->session_key]['code'])) {
			redirect(base_url('Cashier/Login'), 'refresh');
		}
		$res = $this->GlobalModel->checkActiveSubscription();
		if ($res == "EXPIRED") {
			$this->load->view('errors/exppackage.php');
		}
	}

	public function view()
	{
		$code = $this->uri->segment(4);
		$data['loginpin'] = rand(1000, 9999);
		$userData = $this->GlobalModel->get_data($code, 'usermaster');
		$data['userData'] = $userData;
		$branchCode = $userData[0]['userBranchCode'];
		$role = $userData[0]['userRole'];
		$table_name = 'branchmaster';
		$orderColumns = array("branchmaster.*");
		$cond = array('branchmaster' . '.isDelete' => 0, 'branchmaster' . '.isActive' => 1);
		$data['branchdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);
		$this->load->view('cashier/header');
		$this->load->view('cashier/profile/view', $data);
		$this->load->view('cashier/footer');
	}

	public function update()
	{
		$code = $this->input->post("code");
		$user = $this->input->post("name");
		$userEmail = $this->input->post("useremail");
		$ip = $_SERVER['REMOTE_ADDR'];
		$date = date('d-m-y h:i:s');
		$addID = $this->session->userdata['cash_logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['cash_logged_in' . $this->session_key]['role'];
		$userRoleCode = $this->session->userdata['cash_logged_in' . $this->session_key]['rolecode'];
		$loginpin = $this->session->userdata['cash_logged_in' . $this->session_key]['loginpin'];
		$cmpcode = $this->GlobalModel->getCompcode();
		$table = "usermaster";
		$where = ["loginpin" => $loginpin, "code!=" => $code];
		$where7 = ["userName" => $user, "code!=" => $code];
		$resultName =  $this->GlobalModel->hasSimilarRecords($where7, $table);
		$resultUser =  $this->GlobalModel->hasSimilarRecords($where, $table);
		if ($resultName) {
			$data['error_message'] = 'User Name Already exist.';
			$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
			$table_name = 'branchmaster';
			$orderColumns = array("branchmaster.*");
			$cond = array('branchmaster' . '.isDelete' => 0, 'branchmaster' . '.isActive' => 1);
			$data['branchdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);
			$this->load->view('cashier/header');
			$this->load->view('cashier/profile/view', $data);
			$this->load->view('cashier/footer');
		} else if ($resultUser) {
			$data['error_message'] = 'Duplicate Login pin';
			$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
			$table_name = 'branchmaster';
			$orderColumns = array("branchmaster.*");
			$cond = array('branchmaster' . '.isDelete' => 0, 'branchmaster' . '.isActive' => 1);
			$data['branchdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);
			$this->load->view('cashier/header');
			$this->load->view('cashier/profile/view', $data);
			$this->load->view('cashier/footer');
		} else {
			$where1 = ["userEmail" => $userEmail, "code!=" => $code];
			$resultEmail =  $this->GlobalModel->hasSimilarRecords($where1, $table);
			if ($resultEmail) {
				$data['error_message'] = 'User Email already exist.';
				$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
				$table_name = 'branchmaster';
				$orderColumns = array("branchmaster.*");
				$cond = array('branchmaster' . '.isDelete' => 0, 'branchmaster' . '.isActive' => 1);
				$data['branchdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);
				$this->load->view('cashier/header');
				$this->load->view('cashier/profile/view', $data);
				$this->load->view('cashier/footer');
			} else {
				$this->form_validation->set_rules('name', 'Name', 'trim|required');
				$this->form_validation->set_rules('userlanguage', 'User Language', 'trim|required');
				$this->form_validation->set_rules('useremail', 'User Email', 'trim|required');
				if ($this->form_validation->run() == FALSE) {
					//$data['error_message'] = '* Fields are Required!';
					$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
					$table_name = 'branchmaster';
					$orderColumns = array("branchmaster.*");
					$cond = array('branchmaster' . '.isDelete' => 0, 'branchmaster' . '.isActive' => 1);
					$data['branchdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);
					$this->load->view('cashier/header');
					$this->load->view('cashier/profile/view', $data);
					$this->load->view('cashier/footer');
				} else {
					$data = array(
						'userName' => $this->input->post("name"),
						'userLang' =>	$this->input->post("userlanguage"),
						'userEmpNo' =>	$this->input->post("userempnumber"),
						'userEmail' =>	$this->input->post("useremail"),
						'loginpin' =>	$this->input->post("loginpin"),
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
								$this->GlobalModel->doEdit($subData, 'usermaster', $code);
							}
						}
						$txt = $code . " - " . $user . " Cashier profile is updated.";
						$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $loginpin . "\t" . $txt;
						$this->GlobalModel->activity_log($activity_text);
						$response['status'] = true;
						$response['message'] = 'Profile Successfully Updated.';
					} else {
						$response['status'] = false;
						$response['message'] = "No change In Profile";
					}
					$this->session->set_flashdata('message', json_encode($response));
					redirect('Cashier/Profile/view/' . $code, 'refresh');
				}
			}
		}
	}
}
