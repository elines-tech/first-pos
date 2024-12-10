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
			redirect('Cashier/Login', 'refresh');
		}
		$res = $this->GlobalModel->checkActiveSubscription();
		if ($res == "EXPIRED") {
			$this->load->view('errors/exppackage.php');
		}
	}

	public function view()
	{
		//$code = $this->uri->segment(4);
		if ($this->input->post("code") != null) {
			$code = $this->input->post("code");
			$data['loginpin'] = rand(1000, 9999);
			$userData = $this->GlobalModel->get_data($code, 'usermaster');
			$data['userData'] = $userData;
			$branchCode = $userData[0]['userBranchCode'];

			$table_name = 'countermaster';
			$orderColumns = array("countermaster.*");
			$cond = array('countermaster' . '.isDelete' => 0, 'countermaster' . '.isActive' => 1, 'countermaster.branchCode' => $branchCode);
			$data['counterdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);

			$role = $userData[0]['userRole'];
			$this->load->view('cashier/header');
			$this->load->view('cashier/profile/view', $data);
			$this->load->view('cashier/footer');
		} else {
			$this->load->view('cashier/header');
			$this->load->view('cashier/dashboard/index');
			$this->load->view('cashier/footer');
		}
	}

	public function update()
	{
		$code = $this->input->post("code");
		$user = $this->input->post("name");
		$userEmail = $this->input->post("useremail");
		$userempnumber = $this->input->post("userempnumber");
		$ip = $_SERVER['REMOTE_ADDR'];
		$date = date('d-m-y h:i:s');
		$addID = $this->session->userdata['cash_logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['cash_logged_in' . $this->session_key]['role'];
		$userRoleCode = $this->session->userdata['cash_logged_in' . $this->session_key]['rolecode'];
		$cmpcode = $this->GlobalModel->getCompcode();
		$loginpin = $this->session->userdata['cash_logged_in' . $this->session_key]['loginpin'];
		//$resultUser = $this->db->query("SELECT * FROM usermaster WHERE loginpin='" . $loginpin . "' AND code !='" . $code . "' and (`isDelete` IS NULL OR `isDelete`='0')");
		//$resultEmpNo = $this->db->query("SELECT * FROM usermaster WHERE userEmpNo='" . $userempnumber . "' AND code !='" . $code . "' and (`isDelete` IS NULL OR `isDelete`='0')");		
		$table = "usermaster";
		$where = ["loginpin" => $loginpin, "code!=" => $code];
		$where7 = ["userName" => $user, "code!=" => $code];
		$where2 = ["userEmpNo" => $userempnumber, "code!=" => $code];

		$resultEmpNo =  $this->GlobalModel->hasSimilarRecords($where2, $table);
		$resultName =  $this->GlobalModel->hasSimilarRecords($where7, $table);
		$resultUser =  $this->GlobalModel->hasSimilarRecords($where, $table);
		if ($resultName) {
			$data['error_message'] = 'Username already exist';
			$userData = $this->GlobalModel->get_data($code, 'usermaster');
			$data['userData'] = $userData;
			$branchCode = $userData[0]['userBranchCode'];
			$table_name = 'countermaster';
			$orderColumns = array("countermaster.*");
			$cond = array('countermaster' . '.isDelete' => 0, 'countermaster' . '.isActive' => 1, 'countermaster.branchCode' => $branchCode);
			$data['counterdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);
			$this->load->view('cashier/header');
			$this->load->view('cashier/profile/view', $data);
			$this->load->view('cashier/footer');
		} else if ($resultUser) {
			$data['error_message'] = 'Loginpin already exist';
			$userData = $this->GlobalModel->get_data($code, 'usermaster');
			$data['userData'] = $userData;
			$branchCode = $userData[0]['userBranchCode'];
			$table_name = 'countermaster';
			$orderColumns = array("countermaster.*");
			$cond = array('countermaster' . '.isDelete' => 0, 'countermaster' . '.isActive' => 1, 'countermaster.branchCode' => $branchCode);
			$data['counterdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);
			$this->load->view('cashier/header');
			$this->load->view('cashier/profile/view', $data);
			$this->load->view('cashier/footer');
		} else if ($resultEmpNo) {
			$data['error_message'] = 'Employee number already exist.';
			$userData = $this->GlobalModel->get_data($code, 'usermaster');
			$data['userData'] = $userData;
			$branchCode = $userData[0]['userBranchCode'];
			$table_name = 'countermaster';
			$orderColumns = array("countermaster.*");
			$cond = array('countermaster' . '.isDelete' => 0, 'countermaster' . '.isActive' => 1, 'countermaster.branchCode' => $branchCode);
			$data['counterdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);
			$this->load->view('cashier/header');
			$this->load->view('cashier/profile/view', $data);
			$this->load->view('cashier/footer');
		} else {
			$where1 = ["userEmail" => $userEmail, "code!=" => $code];
			$resultEmail =  $this->GlobalModel->hasSimilarRecords($where1, $table);
			if ($resultEmail) {
				$data['error_message'] = 'Email already exist.';
				$userData = $this->GlobalModel->get_data($code, 'usermaster');
				$data['userData'] = $userData;

				$branchCode = $userData[0]['userBranchCode'];
				$table_name = 'countermaster';
				$orderColumns = array("countermaster.*");
				$cond = array('countermaster' . '.isDelete' => 0, 'countermaster' . '.isActive' => 1, 'countermaster.branchCode' => $branchCode);
				$data['counterdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);

				$this->load->view('cashier/header');
				$this->load->view('cashier/profile/view', $data);
				$this->load->view('cashier/footer');
			} else {
				$this->form_validation->set_rules('fullname', 'Full Name', 'trim|required');
				$this->form_validation->set_rules('name', 'Name', 'trim|required');
				$this->form_validation->set_rules('userlanguage', 'User Language', 'trim|required');
				$this->form_validation->set_rules('useremail', 'User Email', 'trim|required');
				if ($this->form_validation->run() == FALSE) {
					$data['error_message'] = '* Fields are Required!';
					$userData = $this->GlobalModel->get_data($code, 'usermaster');
					$data['userData'] = $userData;

					$branchCode = $userData[0]['userBranchCode'];
					$table_name = 'countermaster';
					$orderColumns = array("countermaster.*");
					$cond = array('countermaster' . '.isDelete' => 0, 'countermaster' . '.isActive' => 1, 'countermaster.branchCode' => $branchCode);
					$data['counterdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);

					$this->load->view('cashier/header');
					$this->load->view('cashier/profile/view', $data);
					$this->load->view('cashier/footer');
				} else {

					$data = array(
						'name' => $this->input->post("fullname"),
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
					$userData = $this->GlobalModel->get_data($code, 'usermaster');
					$response['userData'] = $userData;

					$branchCode = $userData[0]['userBranchCode'];
					$table_name = 'countermaster';
					$orderColumns = array("countermaster.*");
					$cond = array('countermaster' . '.isDelete' => 0, 'countermaster' . '.isActive' => 1, 'countermaster.branchCode' => $branchCode);
					$response['counterdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);
					$this->load->view('cashier/header'); 
					$this->load->view('cashier/profile/view', $response);
					$this->load->view('cashier/footer');
				}
			}
		}
	}
}
