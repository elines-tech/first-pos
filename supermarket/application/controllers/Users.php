<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
{
	var $session_key;
	protected $rolecode, $branchCode, $cmpCode;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('GlobalModel');
		$this->load->model('AdminModel');
		$this->load->library("emailer");
		$this->session_key = $this->session->userdata('key' . SESS_KEY);
		if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
			redirect('Login', 'refresh');
		}
		$res = $this->GlobalModel->checkActiveSubscription();
		if ($res == "EXPIRED") {
			redirect('package', 'refresh');
		}
		$this->cmpCode = $this->session->userdata['logged_in' . $this->session_key]['cmpCode'];
		$this->rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		$this->branchCode = $this->session->userdata['logged_in' . $this->session_key]['userBranch'];
		$this->rights = $this->GlobalModel->getMenuRights('7.6', $this->rolecode);
		if ($this->rights == '') {
			$this->load->view('errors/norights.php');
		}
	}

	public function listRecords()
	{
		if ($this->rights != '' && $this->rights['view'] == 1) {
			$data['insertRights'] = $this->rights['insert'];

			$this->load->view('dashboard/header');
			$this->load->view('dashboard/user/users', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function getList()
	{
		$branch = '';
		if ($this->branchCode != "") {
			$branch = $this->branchCode;
		}
		$tableName = 'usermaster';
		$orderColumns = array("usermaster.*,branchmaster.branchName,rolesmaster.role");
		$search = $this->input->GET("search")['value'];
		$condition = array("usermaster.userBranchCode" => $branch);
		$orderBy = array('usermaster' . '.id' => 'DESC');
		$joinType = array('branchmaster' => 'inner', 'rolesmaster' => 'inner');
		$join = array('branchmaster' => 'branchmaster.code=usermaster.userBranchCode', 'rolesmaster' => 'rolesmaster.code=usermaster.userRole');
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$like = array();
		$extraCondition = " usermaster.isDelete=0 OR usermaster.isDelete IS NULL";
		$like = array("rolesmaster.role" => $search . "~both", "usermaster.code" => $search . "~both", "usermaster.userName" => $search . "~both", "usermaster.userLang" => $search . "~both", "usermaster.userEmpNo" => $search . "~both", "usermaster.userEmail" => $search . "~both", "branchmaster.branchName" => $search . "~both");
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		$srno = $offset + 1;
		$data = array();
		$dataCount = 0;
		if ($Records) {
			foreach ($Records->result() as $row) {
				$actionHtml = '';
				if ($this->rights != '' && $this->rights['view'] == 1) {
					$actionHtml .= '<a id="view" href="' . base_url() . 'Users/view/' . $row->code . '" class="btn btn-sm btn-success m-1 cursor_pointer"><i id="edt" title="View" class="fa fa-eye"></i></a>';
				}
				if ($this->rights != '' && $this->rights['update'] == 1) {
					$actionHtml .= '<a id="edit" href="' . base_url() . 'Users/edit/' . $row->code . '" class="btn btn-info btn-sm cursor_pointer m-1"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
				}
				if ($this->rights != '' && $this->rights['delete'] == 1) {
					$actionHtml .= '<a class="btn btn-sm btn-danger m-1 cursor_pointer delete_id" style="    background-color: transparent; color: #dc3545;font-size: large;transition: none;border: none;border-radius: 50%;" id="' . $row->code . '" ><i  title="Delete" class="fa fa-trash"></i></a>';
				}
				if ($row->isActive == "1") {
					$status = " <span class='badge bg-success'>Active</span>";
				} else {
					$status = " <span class='badge bg-danger'>Inactive</span>";
				}

				$data[] = array(
					$srno,
					$row->code,
					$row->branchName,
					$row->name,
					$row->userEmpNo,
					$row->userEmail,
					$row->role,
					$status,
					$actionHtml
				);
				$srno++;
			}
			$dataCount = sizeof($this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, array(), '', '', '', $extraCondition)->result_array());
		}
		$output = array(
			"draw" => intval($_GET["draw"]),
			"recordsTotal" => $dataCount,
			"recordsFiltered" => $dataCount,
			"data" => $data
		);
		echo json_encode($output);
	}

	public function edit()
	{
		if ($this->rights != '' && $this->rights['update'] == 1) {
			$data['branchCode'] = "";
			$data['branchName'] = "";
			if ($this->branchCode != "") {
				$data['branchCode'] = $this->branchCode;
				$data['branchName'] = $this->GlobalModel->selectDataById($this->branchCode, 'branchmaster')->result()[0]->branchName;
			}
			$code = $this->uri->segment(3);
			$data['loginpin'] = rand(1000, 9999);
			$userData = $this->GlobalModel->get_data($code, 'usermaster');
			$data['userData'] = $userData;
			$branchCode = $userData[0]['userBranchCode'];
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
			$cond_role = array('rolesmaster' . '.isDelete' => 0, 'rolesmaster' . '.isActive' => 1, 'rolesmaster' . '.code !=' => 'R_1');
			$data['roledata'] = $this->GlobalModel->selectQuery($orderColumns_role, $table_role, $cond_role);
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/user/user-edit', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function view()
	{
		if ($this->rights != '' && $this->rights['view'] == 1) {
			$code = $this->uri->segment(3);
			$userData = $this->GlobalModel->get_data($code, 'usermaster');
			$data['userData'] = $userData;
			$branchCode = $userData[0]['userBranchCode'];
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
			$cond_role = array('rolesmaster' . '.isDelete' => 0, 'rolesmaster' . '.isActive' => 1, 'rolesmaster' . '.code !=' => 'R_1');
			$data['roledata'] = $this->GlobalModel->selectQuery($orderColumns_role, $table_role, $cond_role);
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/user/user-view', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function add()
	{
		if ($this->rights != '' && $this->rights['insert'] == 1) {
			$data['branchCode'] = "";
			$data['branchName'] = "";
			$allowedUsersCount = $this->AdminModel->get_subscriber_max_users($this->cmpCode);
			$currentUserCount = 0;
			$currentUsers = $this->GlobalModel->selectQuery("usermaster.*", "usermaster", ["usermaster.isDelete!=" => 1]);
			if ($currentUsers) $currentUserCount = count($currentUsers->result());
			if ($currentUserCount <= $allowedUsersCount) {
				if ($this->branchCode != "") {
					$data['branchCode'] = $this->branchCode;
					$data['branchName'] = $this->GlobalModel->selectDataById($this->branchCode, 'branchmaster')->result()[0]->branchName;
				}
				$data['loginpin'] = rand(1000, 9999);
				$table_name = 'branchmaster';
				$orderColumns = array("branchmaster.*");
				$cond = array('branchmaster' . '.isDelete' => 0, 'branchmaster' . '.isActive' => 1);
				$data['branchdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);
				$table_name = 'countermaster';
				$orderColumns = array("countermaster.*");
				$cond = array('countermaster' . '.isDelete' => 0, 'countermaster' . '.isActive' => 1);
				$data['counterdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);
				$table_role = 'rolesmaster';
				$orderColumns_role = array("rolesmaster.*");
				$cond_role = array('rolesmaster' . '.isDelete' => 0, 'rolesmaster' . '.isActive' => 1, 'rolesmaster' . '.code !=' => 'R_1');
				$data['roledata'] = $this->GlobalModel->selectQuery($orderColumns_role, $table_role, $cond_role);
				$this->load->view('dashboard/header');
				$this->load->view('dashboard/user/user-add', $data);
				$this->load->view('dashboard/footer');
			} else {
				$response['status'] = false;
				$response['message'] = "You cannot create more users, maximum users limit reached. Please upgrade your current plan to create more users";
				$this->session->set_flashdata('message', json_encode($response));
				redirect('users/listRecords', 'refresh');
			}
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function save()
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		$date = date('d-m-y h:i:s');
		$user = $this->input->post("username");
		$userempnumber = $this->input->post("userempnumber");
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$cmpcode = $this->session->userdata['logged_in' . $this->session_key]['cmpcode'];
		$dbName = $this->session->userdata['current_db' . $this->session_key];
		$this->db->query('use ' . $dbName);
		$loginpin = $this->input->post('loginpin');
		$this->form_validation->set_rules('branchname', 'Branch Name', 'trim|required');
		$this->form_validation->set_rules(
			'username',
			'User Name',
			'trim|required|is_unique[usermaster.userName]',
			array(
				'is_unique'     => 'User Name already exists.'
			)
		);
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('userlanguage', 'User Language', 'trim|required');
		$this->form_validation->set_rules(
			'userempnumber',
			'User Employee Number',
			'trim|required|is_unique[usermaster.userEmpNo]',
			array(
				'is_unique'     => 'User Employee Number already exists.'
			)
		);
		$this->form_validation->set_rules(
			'useremail',
			'User Email',
			'trim|required|is_unique[usermaster.userEmail]',
			array(
				'is_unique'     => 'User Email already exists.'
			)
		);
		$this->form_validation->set_rules('userrole', 'User Role', 'trim|required');
		//$result = $this->db->query("SELECT * FROM usermaster WHERE loginpin='" . $loginpin . "'");

		$table = 'usermaster';
		$order = array("usermaster.*");
		$cond7 = array('usermaster' . '.loginpin' => $loginpin);
		$result = $this->GlobalModel->selectQuery($order, $table, $cond7);
		$checkRole = $this->input->post("userrole");
		if ($result && $checkRole == "R_5") {
			$data['loginpin'] = rand(1000, 9999);
			$data['error_message'] = 'Login pin already exist.';
			$table_name = 'branchmaster';
			$orderColumns = array("branchmaster.*");
			$cond = array('branchmaster' . '.isDelete' => 0, 'branchmaster' . '.isActive' => 1);
			$data['branchdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);

			$table_counter = 'countermaster';
			$orderColumnsCounter = array("countermaster.*");
			$condCounter = array('countermaster' . '.isDelete' => 0, 'countermaster' . '.isActive' => 1);
			$data['counterdata'] = $this->GlobalModel->selectQuery($orderColumnsCounter, $table_counter, $condCounter);

			$table_role = 'rolesmaster';
			$orderColumns_role = array("rolesmaster.*");
			$cond_role = array('rolesmaster' . '.isDelete' => 0, 'rolesmaster' . '.isActive' => 1, 'rolesmaster' . '.code !=' => 'R_1');
			$data['roledata'] = $this->GlobalModel->selectQuery($orderColumns_role, $table_role, $cond_role);
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/user/user-add', $data);
			$this->load->view('dashboard/footer');
		} else {
			if ($this->form_validation->run() == FALSE) {
				$data['loginpin'] = rand(1000, 9999);
				$table_name = 'branchmaster';
				$orderColumns = array("branchmaster.*");
				$cond = array('branchmaster' . '.isDelete' => 0, 'branchmaster' . '.isActive' => 1);
				$data['branchdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);

				$table_counter = 'countermaster';
				$orderColumnsCounter = array("countermaster.*");
				$condCounter = array('countermaster' . '.isDelete' => 0, 'countermaster' . '.isActive' => 1);
				$data['counterdata'] = $this->GlobalModel->selectQuery($orderColumnsCounter, $table_counter, $condCounter);

				$table_role = 'rolesmaster';
				$orderColumns_role = array("rolesmaster.*");
				$cond_role = array('rolesmaster' . '.isDelete' => 0, 'rolesmaster' . '.isActive' => 1, 'rolesmaster' . '.code !=' => 'R_1');
				$data['roledata'] = $this->GlobalModel->selectQuery($orderColumns_role, $table_role, $cond_role);
				$this->load->view('dashboard/header');
				$this->load->view('dashboard/user/user-add', $data);
				$this->load->view('dashboard/footer');
			} else {
				$loginpin = "";
				$active = 0;
				if ($this->input->post('isActive') == 'on') {
					$active = 1;
				}
				if ($this->input->post("userrole") == "R_5") {
					$loginpin = $this->input->post('loginpin');
				}
				$branch = $this->input->post("branchname");
				if ($this->branchCode != "") {
					$branch = $this->branchCode;
				}

				$mailData = [
					"name" => ucwords(trim($this->input->post("name"))),
					"username" => trim($this->input->post("username")),
					"email" => trim($this->input->post("useremail")),
					"password" => trim($this->input->post('password')),
					"loginpin" => $loginpin,
					"role" => trim($this->input->post("userrole")) == "R_5" ? "CASHIER" : "USER",
				];

				$data = array(
					'userBranchCode' => $branch,
					'name' => $this->input->post("name"),
					'userName' => $this->input->post("username"),
					'userLang' =>	$this->input->post("userlanguage"),
					'invoicePreference' =>	$this->input->post("invoicePreference"),
					'userEmpNo' =>	$this->input->post("userempnumber"),
					'userEmail' =>	$this->input->post("useremail"),
					'userRole' =>	$this->input->post("userrole"),
					'userPassword' => md5($this->input->post("password")),
					'loginpin' => $loginpin,
					'userCounter' => $this->input->post("userCounter"),
					'isActive' => $active,
					'addID' => $addID,
					'addIP' => $ip
				);
				$result = $this->GlobalModel->addNew($data, 'usermaster', 'USR');
				if ($result != false) {
					$this->send_credentials_mail($mailData);
					$filename = "";
					$uploadDir = "upload/userImage/$cmpcode";
					if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
					if (!empty($_FILES['userImage']['name'])) {
						$tmpFile = $_FILES['userImage']['tmp_name'];
						$ext = pathinfo($_FILES['userImage']['name'], PATHINFO_EXTENSION);
						$filename = $uploadDir . '/' . $result . '-' . time() . '.' . $ext;
						move_uploaded_file($tmpFile, $filename);
						if (file_exists($filename)) {
							$subData = array(
								'userImage' => $filename
							);
							$this->GlobalModel->doEdit($subData, 'usermaster', $result);
						}
					}
					$txt = $result . " - " . $user . " User is added.";
					$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
					$this->GlobalModel->activity_log($activity_text);
					if ($this->input->post("userrole") == "R_5") {
						$response['status'] = true;
						$response['message'] = 'User Added successfully with login pin ' . $loginpin;
					} else {
						$response['status'] = true;
						$response['message'] = 'User Added successfully.';
					}
				} else {
					$response['status'] = false;
					$response['message'] = "Failed To Add User";
				}
				$this->session->set_flashdata('message', json_encode($response));
				redirect('users/listRecords', 'refresh');
			}
		}
	}

	public function send_credentials_mail(array $mailData)
	{
		$company = $this->GlobalModel->get_row_array(["code" => $this->cmpCode], "companymaster");
		if (!empty($company)) {
			$emailConfig = $this->GlobalModel->selectQuery("settings.*", "settings", array('settings.code' => 'STG_2'));
			if ($emailConfig) {
				$value = $emailConfig->result_array()[0]['settingValue'];
				if ($value != null && $value != "") {
					$config = json_decode($value, true);
					if (!empty($config)) {
						$config_setting = [
							"maildriver" => $config['maildriver'],
							"host" => $config['host'],
							"port" => $config['port'],
							"username" => $config['username'],
							"password" => $config['password'],
							"fromaddress" => $config['fromaddress'],
							"fromname" => $config['fromname']
						];
						$subject = "Super-market Login Credentials";
						$message = '<html><body>';
						$message .=	'<p>Hi ' . $mailData['name'] . ',</p>';
						if ($mailData['role'] == "CASHIER") {
							$message .=	'<p>Welcome to <span style="font-weight:bold;color:#3cb371">' . $company['companyname'] . '</span>. To access the Super-market panel, you will need a login for it.<br>';
							$message .= 'Please use the links below to login into your account-section using the following account information:</p>';
							$message .=	'<p>company url: <b><a href="' . base_url('Cashier/Login') . '" target="_blank">' . base_url('Cashier/Login') . '</a></b></p>';
							$message .=	'<p>company: <b>' . $company['companyname'] . '</b></p>';
							$message .=	'<p>company code : <b>' . $company['cmpcode'] . '</b></p>';
							$message .=	'<p>login pin: <b>' . $mailData['loginpin'] . '</b></p>';
						} else {
							$message .=	'<p>Welcome to <span style="font-weight:bold;color:#3cb371">' . $company['companyname'] . '</span>. To access the Super-market panel, you will need a login for it.<br>';
							$message .= 'Please use the links below to login into your account-section using the following account information:</p>';
							$message .=	'<p>company url: <b><a href="' . base_url('login') . '" target="_blank">' . base_url('login') . '</a></b></p>';
							$message .=	'<p>company: <b>' . $company['companyname'] . '</b></p>';
							$message .=	'<p>company code : <b>' . $company['cmpcode'] . '</b></p>';
							$message .=	'<p>username : <b>' . $mailData['username'] . '</b></p>';
							$message .=	'<p>passcode: <b>' . $mailData['password'] . '</b></p>';
						}
						$message .=	'</body></html>';
						return $this->emailer->transact($config_setting, $mailData['email'], $subject, $message);
					}
				}
			}
		}
		return "FAILED";
	}

	public function try()
	{
		$mailData = [
			"name" => "TAMAKN SUPERMARKET USER",
			"email" => "support@tmkn-pos.com",
			"password" => "123456789",
			"username" => "supuser",
			"loginpin" => "1234",
			"role" => "U",
		];
		$this->send_credentials_mail($mailData);
	}

	public function update()
	{
		$code = $this->input->post("code");
		$user = $this->input->post("username");
		$userEmail = $this->input->post("useremail");
		$userempnumber = $this->input->post("userempnumber");
		$loginpin = $this->input->post("loginpin");
		$ip = $_SERVER['REMOTE_ADDR'];
		$date = date('d-m-y h:i:s');
		$checkRole = $this->input->post("userrole");

		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$cmpcode = $this->GlobalModel->getCompcode();
		//$dbName = $this->session->userdata['current_db' . $this->session_key];
		//$this->db->query('use ' . $dbName);

		$table = 'usermaster';
		$cond3 = ["userEmpNo" => $userempnumber, "code!=" => $code];
		$cond =  array('userEmpNo' => $userempnumber, 'code !=' => $code);
		$resultEmpNo = $this->GlobalModel->hasSimilarRecords($cond3, $table);
		$cond2 = ["loginpin" => $loginpin, "code!=" => $code];
		$resultPin =  $this->GlobalModel->hasSimilarRecords($cond2, $table);
		$cond1 = ["userName" => $user, "code!=" => $code];
		$resultName =  $this->GlobalModel->hasSimilarRecords($cond1, $table);
		if ($resultName) {
			$data['error_message'] = 'User Name already exist';
			$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
			$table_name = 'branchmaster';
			$orderColumns = array("branchmaster.*");
			$cond = array('branchmaster' . '.isDelete' => 0, 'branchmaster' . '.isActive' => 1);
			$data['branchdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);

			$table_counter = 'countermaster';
			$orderColumnsCounter = array("countermaster.*");
			$condCounter = array('countermaster' . '.isDelete' => 0, 'countermaster' . '.isActive' => 1);
			$data['counterdata'] = $this->GlobalModel->selectQuery($orderColumnsCounter, $table_counter, $condCounter);

			$table_role = 'rolesmaster';
			$orderColumns_role = array("rolesmaster.*");
			$cond_role = array('rolesmaster' . '.isDelete' => 0, 'rolesmaster' . '.isActive' => 1, 'rolesmaster' . '.code !=' => 'R_1');
			$data['roledata'] = $this->GlobalModel->selectQuery($orderColumns_role, $table_role, $cond_role);
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/user/user-edit', $data);
			$this->load->view('dashboard/footer');
		} else if ($resultEmpNo) {
			$data['error_message'] = 'User Employee Number already exist';
			$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
			$table_name = 'branchmaster';
			$orderColumns = array("branchmaster.*");
			$cond = array('branchmaster' . '.isDelete' => 0, 'branchmaster' . '.isActive' => 1);
			$data['branchdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);

			$table_counter = 'countermaster';
			$orderColumnsCounter = array("countermaster.*");
			$condCounter = array('countermaster' . '.isDelete' => 0, 'countermaster' . '.isActive' => 1);
			$data['counterdata'] = $this->GlobalModel->selectQuery($orderColumnsCounter, $table_counter, $condCounter);

			$table_role = 'rolesmaster';
			$orderColumns_role = array("rolesmaster.*");
			$cond_role = array('rolesmaster' . '.isDelete' => 0, 'rolesmaster' . '.isActive' => 1, 'rolesmaster' . '.code !=' => 'R_1');
			$data['roledata'] = $this->GlobalModel->selectQuery($orderColumns_role, $table_role, $cond_role);
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/user/user-edit', $data);
			$this->load->view('dashboard/footer');
		} else if ($resultPin && $checkRole == "R_5") {
			$data['error_message'] = 'Login Pin already exist';
			$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
			$table_name = 'branchmaster';
			$orderColumns = array("branchmaster.*");
			$cond = array('branchmaster' . '.isDelete' => 0, 'branchmaster' . '.isActive' => 1);
			$data['branchdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);

			$table_counter = 'countermaster';
			$orderColumnsCounter = array("countermaster.*");
			$condCounter = array('countermaster' . '.isDelete' => 0, 'countermaster' . '.isActive' => 1);
			$data['counterdata'] = $this->GlobalModel->selectQuery($orderColumnsCounter, $table_counter, $condCounter);

			$table_role = 'rolesmaster';
			$orderColumns_role = array("rolesmaster.*");
			$cond_role = array('rolesmaster' . '.isDelete' => 0, 'rolesmaster' . '.isActive' => 1, 'rolesmaster' . '.code !=' => 'R_1');
			$data['roledata'] = $this->GlobalModel->selectQuery($orderColumns_role, $table_role, $cond_role);
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/user/user-edit', $data);
			$this->load->view('dashboard/footer');
		} else {
			$cond9 = ["userEmail" => $userEmail, "code!=" => $code];
			$resultEmail =  $this->GlobalModel->hasSimilarRecords($cond9, $table);
			if ($resultEmail) {
				$data['error_message'] = 'User Email already exist.';
				$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
				$table_name = 'branchmaster';
				$orderColumns = array("branchmaster.*");
				$cond = array('branchmaster' . '.isDelete' => 0, 'branchmaster' . '.isActive' => 1);
				$data['branchdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);

				$table_counter = 'countermaster';
				$orderColumnsCounter = array("countermaster.*");
				$condCounter = array('countermaster' . '.isDelete' => 0, 'countermaster' . '.isActive' => 1);
				$data['counterdata'] = $this->GlobalModel->selectQuery($orderColumnsCounter, $table_counter, $condCounter);

				$table_role = 'rolesmaster';
				$orderColumns_role = array("rolesmaster.*");
				$cond_role = array('rolesmaster' . '.isDelete' => 0, 'rolesmaster' . '.isActive' => 1, 'rolesmaster' . '.code !=' => 'R_1');
				$data['roledata'] = $this->GlobalModel->selectQuery($orderColumns_role, $table_role, $cond_role);
				$this->load->view('dashboard/header');
				$this->load->view('dashboard/user/user-edit', $data);
				$this->load->view('dashboard/footer');
			} else {
				$this->form_validation->set_rules('branchname', 'Branch Name', 'trim|required');
				$this->form_validation->set_rules('name', 'Name', 'trim|required');
				$this->form_validation->set_rules('userlanguage', 'User Language', 'trim|required');
				$this->form_validation->set_rules('userempnumber', 'User Employee Number', 'trim|required');
				$this->form_validation->set_rules('useremail', 'User Email', 'trim|required');
				$this->form_validation->set_rules('userrole', 'User Role', 'trim|required');
				if ($this->form_validation->run() == FALSE) {
					//$data['error_message'] = '* Fields are Required!';
					$table_name = 'branchmaster';
					$orderColumns = array("branchmaster.*");
					$cond = array('branchmaster' . '.isDelete' => 0, 'branchmaster' . '.isActive' => 1);
					$data['branchdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);

					$table_counter = 'countermaster';
					$orderColumnsCounter = array("countermaster.*");
					$condCounter = array('countermaster' . '.isDelete' => 0, 'countermaster' . '.isActive' => 1);
					$data['counterdata'] = $this->GlobalModel->selectQuery($orderColumnsCounter, $table_counter, $condCounter);

					$table_role = 'rolesmaster';
					$orderColumns_role = array("rolesmaster.*");
					$cond_role = array('rolesmaster' . '.isDelete' => 0, 'rolesmaster' . '.isActive' => 1, 'rolesmaster' . '.code !=' => 'R_1');
					$data['roledata'] = $this->GlobalModel->selectQuery($orderColumns_role, $table_role, $cond_role);
					$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
					$this->load->view('dashboard/header');
					$this->load->view('dashboard/user/user-edit', $data);
					$this->load->view('dashboard/footer');
				} else {

					$loginpin = "";
					$active = 0;
					if ($this->input->post('isActive') == 'on') {
						$active = 1;
					}
					if ($this->input->post("userrole") == "R_5") {
						$loginpin = $this->input->post('loginpin');
					}
					$branch = $this->input->post("branchname");
					if ($this->branchCode != "") {
						$branch = $this->branchCode;
					}
					$data = array(
						'userBranchCode' => $branch,
						'name' => $this->input->post("name"),
						'userName' => $this->input->post("username"),
						'userLang' =>	$this->input->post("userlanguage"),
						'invoicePreference' =>	$this->input->post("invoicePreference"),
						'userEmpNo' =>	$this->input->post("userempnumber"),
						'userEmail' =>	$this->input->post("useremail"),
						'userRole' =>	$this->input->post("userrole"),
						'loginpin' => $loginpin,
						'userCounter' => $this->input->post("userCounter"),
						'isActive' => $active,
						'editID' => $addID,
						'editIP' => $ip,
					);
					$password = $this->input->post("password");
					if ($password != '') {
						$newpassword =  md5($password);
						$data['userPassword'] = $newpassword;
					}
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
						$txt = $code . " - " . $user . " User is updated.";
						$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
						$this->GlobalModel->activity_log($activity_text);
						$response['status'] = true;
						$response['message'] = 'User Successfully Updated.';
					} else {
						$response['status'] = false;
						$response['message'] = "No change In User";
					}
					$this->session->set_flashdata('message', json_encode($response));
					redirect('Users/listRecords', 'refresh');
				}
			}
		}
	}

	public function userDuplicate()
	{
		$username = $this->input->get("username");
		$condition = array('userName' => $username);
		$duplicateCon = $this->GlobalModel->checkDuplicateRecordNew($condition, 'usermaster');
		if ($duplicateCon == true) {
			$res['message'] = "Username already exist.";
			$res['status'] = true;
		} else {
			$res['message'] = "";
			$res['status'] = false;
		}
		echo json_encode($res);
	}

	public function emailDuplicate()
	{
		$email = $this->input->get("email");
		$condition = array('userEmail' => $email, 'isActive' => 1);
		$duplicateCon = $this->GlobalModel->checkDuplicateRecordNew($condition, 'usermaster');
		if ($duplicateCon == true) {
			$res['message'] = "Useremail already exist.";
			$res['status'] = true;
		} else {
			$res['message'] = "";
			$res['status'] = false;
		}
		echo json_encode($res);
	}

	public function empnoDuplicate()
	{
		$empno = $this->input->get("empno");
		$condition = array('userEmpNo' => $empno, 'isActive' => 1);
		$duplicateCon = $this->GlobalModel->checkDuplicateRecordNew($condition, 'usermaster');

		if ($duplicateCon == true) {
			$res['message'] = "Employee Number already exist.";
			$res['status'] = true;
		} else {
			$res['message'] = "";
			$res['status'] = false;
		}
		echo json_encode($res);
	}

	public function empnoDuplicateOnUpdate()
	{
		$empno = $this->input->get("empno");
		$code = $this->input->get("code");
		$condition = array('userEmpNo' => $empno, 'code !=' => $code, 'isActive' => 1);
		$duplicateCon = $this->GlobalModel->checkDuplicateRecordNew($condition, 'usermaster');
		if ($duplicateCon == true) {
			$res['message'] = "Employee Number already exist.";
			$res['status'] = true;
		} else {
			$res['message'] = "";
			$res['status'] = false;
		}
		echo json_encode($res);
	}

	public function userDuplicateOnUpdate()
	{
		$username = $this->input->get("username");
		$code = $this->input->get("code");
		$condition = array('userName' => $username);
		$duplicateCon = $this->GlobalModel->checkDuplicateRecordInUpdate('userName', $username, $code, 'usermaster');
		if ($duplicateCon == true) {
			$res['message'] = "Username already exist.";
			$res['status'] = true;
		} else {
			$res['message'] = "";
			$res['status'] = false;
		}
		echo json_encode($res);
	}

	public function emailDuplicateOnUpdate()
	{
		$email = $this->input->get("email");
		$code = $this->input->get("code");
		$condition = array('userEmail' => $email, 'code !=' => $code, 'isActive' => 1);
		$duplicateCon = $this->GlobalModel->checkDuplicateRecordNew($condition, 'usermaster');
		if ($duplicateCon == true) {
			$res['message'] = "Useremail already exist.";
			$res['status'] = true;
		} else {
			$res['message'] = "";
			$res['status'] = false;
		}
		echo json_encode($res);
	}

	public function delete()
	{
		$date = date('d-m-y h:i:s');
		$code = $this->input->post('code');
		$ip = $_SERVER['REMOTE_ADDR'];

		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];

		$txt = $code . " User is deleted.";
		$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
		$this->GlobalModel->activity_log($activity_text);

		$data = array('isDelete' => 1, 'deleteID' => $addID, 'deleteIP' => $ip, 'deleteDate' => date('Y-m-d H:i:s'));
		$this->GlobalModel->doEdit($data, 'usermaster', $code);
		echo $this->GlobalModel->delete($code, 'usermaster');
	}

	public function role()
	{
		$this->load->view('dashboard/header');
		$this->load->view('dashboard/user/user-role');
		$this->load->view('dashboard/footer');
	}

	public function getBranchCounters()
	{
		$branchCode = $this->input->post("branchCode");
		$counterHtml = '';
		$counters = $this->GlobalModel->selectQuery('countermaster.code,countermaster.counterName', 'countermaster', array('countermaster.isActive' => 1, 'countermaster.branchCode' => $branchCode));
		if ($counters && $counters->num_rows() > 0) {
			$response['status'] = true;
			$counterHtml .= '<option value="">Select Counter</option>';
			foreach ($counters->result() as $con) {
				$counterHtml .= '<option value="' . $con->code . '">' . $con->counterName . '</option>';
			}
		} else {
			$response['status'] = false;
		}
		$response['counters'] =  $counterHtml;
		echo json_encode($response);
	}
}
