<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
{
	var $session_key;
	protected $rolecode, $branchCode;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('GlobalModel');
		$this->session_key = $this->session->userdata('key' . SESS_KEY);
		$rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
			redirect('Login', 'refresh');
		}
		$this->rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		$this->branchCode = $this->session->userdata['logged_in' . $this->session_key]['branchCode'];
		$this->rights = $this->GlobalModel->getMenuRights('8.6', $this->rolecode);
		if ($this->rights == '') {
			$this->load->view('errors/norights.php');
		}
		$res = $this->GlobalModel->checkActiveSubscription();
		if ($res == "EXPIRED") {
			redirect('package', 'refresh');
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
					$actionHtml .= '<a id="delete" class="btn btn-sm btn-danger m-1 cursor_pointer delete_id" id="' . $row->code . '" ><i  title="Delete" class="fa fa-trash"></i></a>';
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
					$row->userName,
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
			$data['updateRights'] = $this->rights['update'];
			$data['branchCode'] = "";
			$data['branchName'] = "";
			if ($this->branchCode != "") {
				$data['branchCode'] = $this->branchCode;
				$data['branchName'] = $this->GlobalModel->selectDataById($this->branchCode, 'branchmaster')->result()[0]->branchName;
			}
			$code = $this->uri->segment(3);
			$data['loginpin'] = rand(1000, 9999);
			$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
			$table_name = 'branchmaster';
			$orderColumns = array("branchmaster.*");
			$cond = array('branchmaster' . '.isDelete' => 0, 'branchmaster' . '.isActive' => 1);
			$data['branchdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);
			$table_role = 'rolesmaster';
			$orderColumns_role = array("rolesmaster.*");
			$cond_role = array('rolesmaster' . '.isDelete' => 0, 'rolesmaster' . '.isActive' => 1, 'rolesmaster' . '.code !=' => 'R_1');
			$data['roledata'] = $this->GlobalModel->selectQuery($orderColumns_role, $table_role, $cond_role);
			$this->load->view('dashboard/commonheader');
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
			$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
			$table_name = 'branchmaster';
			$orderColumns = array("branchmaster.*");
			$cond = array('branchmaster' . '.isDelete' => 0, 'branchmaster' . '.isActive' => 1);
			$data['branchdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);
			$table_role = 'rolesmaster';
			$orderColumns_role = array("rolesmaster.*");
			$cond_role = array('rolesmaster' . '.isDelete' => 0, 'rolesmaster' . '.isActive' => 1, 'rolesmaster' . '.code !=' => 'R_1');
			$data['roledata'] = $this->GlobalModel->selectQuery($orderColumns_role, $table_role, $cond_role);
			$this->load->view('dashboard/commonheader');
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
			if ($this->branchCode != "") {
				$data['branchCode'] = $this->branchCode;
				$data['branchName'] = $this->GlobalModel->selectDataById($this->branchCode, 'branchmaster')->result()[0]->branchName;
			}
			$data['insertRights'] = $this->rights['insert'];
			$data['loginpin'] = rand(1000, 9999);
			$table_name = 'branchmaster';
			$orderColumns = array("branchmaster.*");
			$cond = array('branchmaster' . '.isDelete' => 0, 'branchmaster' . '.isActive' => 1);
			$data['branchdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);

			$table_role = 'rolesmaster';
			$orderColumns_role = array("rolesmaster.*");
			$cond_role = array('rolesmaster' . '.isDelete' => 0, 'rolesmaster' . '.isActive' => 1, 'rolesmaster' . '.code !=' => 'R_1');
			$data['roledata'] = $this->GlobalModel->selectQuery($orderColumns_role, $table_role, $cond_role);

			$this->load->view('dashboard/commonheader');
			$this->load->view('dashboard/user/user-add', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function save()
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		$date = date('d-m-y h:i:s');
		$user = $this->input->post("username");
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$loginpin = $this->input->post('loginpin');
		$cmpcode = $this->GlobalModel->getCompcode();
		$dbName = $this->session->userdata['current_db' . $this->session_key];
		$this->db->query('use ' . $dbName);
		$this->form_validation->set_rules('branchname', 'Branch Name', 'trim|required');
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
		$this->form_validation->set_rules(
			'username',
			'User Name',
			'trim|required|is_unique[usermaster.userName]',
			array(
				'is_unique'     => 'User Name already exists.'
			)
		);
		$this->form_validation->set_rules('userrole', 'User Role', 'trim|required');
		$checkRole = $this->input->post("userrole");
		$table = 'usermaster';
		$order = array("usermaster.*");
		$cond7 = array('usermaster' . '.loginpin' => $loginpin);
		$result = $this->GlobalModel->selectQuery($order, $table, $cond7);
		if ($result && $checkRole == 'R_6') {
			$data['loginpin'] = rand(1000, 9999);
			$data['error_message'] = 'Login Pin already exists.';
			$table_name = 'branchmaster';
			$orderColumns = array("branchmaster.*");
			$cond = array('branchmaster' . '.isDelete' => 0, 'branchmaster' . '.isActive' => 1);
			$data['branchdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);
			$table_role = 'rolesmaster';
			$orderColumns_role = array("rolesmaster.*");
			$cond_role = array('rolesmaster' . '.isDelete' => 0, 'rolesmaster' . '.isActive' => 1, 'rolesmaster' . '.code !=' => 'R_1');
			$data['roledata'] = $this->GlobalModel->selectQuery($orderColumns_role, $table_role, $cond_role);
			$this->load->view('dashboard/commonheader');
			$this->load->view('dashboard/user/user-add', $data);
			$this->load->view('dashboard/footer');
		} else {
			if ($this->form_validation->run() == FALSE) {
				$data['loginpin'] = rand(1000, 9999);
				$table_name = 'branchmaster';
				$orderColumns = array("branchmaster.*");
				$cond = array('branchmaster' . '.isDelete' => 0, 'branchmaster' . '.isActive' => 1);
				$data['branchdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);
				$table_role = 'rolesmaster';
				$orderColumns_role = array("rolesmaster.*");
				$cond_role = array('rolesmaster' . '.isDelete' => 0, 'rolesmaster' . '.isActive' => 1, 'rolesmaster' . '.code !=' => 'R_1');
				$data['roledata'] = $this->GlobalModel->selectQuery($orderColumns_role, $table_role, $cond_role);
				$this->load->view('dashboard/commonheader');
				$this->load->view('dashboard/user/user-add', $data);
				$this->load->view('dashboard/footer');
			} else {
				$active = 0;
				if ($this->input->post('isActive') == 'on') {
					$active = 1;
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
					"loginpin" => trim($this->input->post('loginpin')),
					"role" => trim($this->input->post("userrole")) == "R_6" ? "CASHIER" : "USER",
				];

				$data = array(
					'userBranchCode' => $branch,
					'userName' => $this->input->post("username"),
					'userLang' =>	$this->input->post("userlanguage"),
					'invoicePreference' => $this->input->post('invoicePreference'),
					'userEmpNo' =>	$this->input->post("userempnumber"),
					'userEmail' =>	$this->input->post("useremail"),
					'userRole' =>	$this->input->post("userrole"),
					'userPassword' => md5($this->input->post("password")),
					'loginpin' => $this->input->post('loginpin'),
					'isActive' => $active,
					'addID' => $addID,
					'addIP' => $ip,
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
							$filedoc = $this->GlobalModel->doEdit($subData, 'usermaster', $result);
						}
					}

					$txt = $result . " - " . $user . " User is added.";
					$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
					$this->GlobalModel->activity_log($activity_text);
					if ($this->input->post("userrole") != "R_6") {
						$response['status'] = true;
						$response['message'] = 'User Added successfully.';
					} else {
						$response['status'] = true;
						$response['message'] = 'User Added successfully with login pin ' . $loginpin;
					}
				} else {
					$response['status'] = false;
					$response['message'] = "Failed To Add User";
				}
				$this->session->set_flashdata('message', json_encode($response));
				redirect('users/listRecords', 'refresh');
				//echo json_encode($response);
			}
		}
	}


	public function send_credentials_mail(array $mailData)
	{
		$company = $this->GlobalModel->get_row_array(["code" => $this->cmpCode], "companymaster");
		if (!empty($company)) {
			$emailConfig = $this->GlobalModel->selectQuery("settings.*", "settings", array('settings.code' => 'STG_3'));
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


	public function update()
	{
		$code = $this->input->post("code");
		$user = $this->input->post("username");
		$userEmail = $this->input->post("useremail");
		$userempnumber = $this->input->post("userempnumber");
		$loginpin = $this->input->post("loginpin");
		$cmpcode = $this->GlobalModel->getCompcode();
		$ip = $_SERVER['REMOTE_ADDR'];
		$date = date('d-m-y h:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];

		$table = 'usermaster';
		$cond3 = ["userEmpNo" => $userempnumber, "code!=" => $code];
		$cond =  array('userEmpNo' => $userempnumber, 'code !=' => $code);
		$resultEmpNo = $this->GlobalModel->hasSimilarRecords($cond3, $table);
		$cond2 = ["loginpin" => $loginpin, "code!=" => $code];
		$resultPin =  $this->GlobalModel->hasSimilarRecords($cond2, $table);
		$cond1 = ["userName" => $user, "code!=" => $code];
		$resultUser =  $this->GlobalModel->hasSimilarRecords($cond1, $table);
		$checkRole = $this->input->post("userrole");
		$cond9 = ["userEmail" => $userEmail, "code!=" => $code];
		$resultEmail =  $this->GlobalModel->hasSimilarRecords($cond9, $table);
		if ($resultUser) {
			$data['error_message'] = 'User Name already exists';
			$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
			$table_name = 'branchmaster';
			$orderColumns = array("branchmaster.*");
			$cond = array('branchmaster' . '.isDelete' => 0, 'branchmaster' . '.isActive' => 1);
			$data['branchdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);
			$table_role = 'rolesmaster';
			$orderColumns_role = array("rolesmaster.*");
			$cond_role = array('rolesmaster' . '.isDelete' => 0, 'rolesmaster' . '.isActive' => 1, 'rolesmaster' . '.code !=' => 'R_1');
			$data['roledata'] = $this->GlobalModel->selectQuery($orderColumns_role, $table_role, $cond_role);
			$this->load->view('dashboard/commonheader');
			$this->load->view('dashboard/user/user-edit', $data);
			$this->load->view('dashboard/footer');
		} else if ($resultEmpNo) {
			$data['error_message'] = 'User Employee Number already exists';
			$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
			$table_name = 'branchmaster';
			$orderColumns = array("branchmaster.*");
			$cond = array('branchmaster' . '.isDelete' => 0, 'branchmaster' . '.isActive' => 1);
			$data['branchdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);
			$table_role = 'rolesmaster';
			$orderColumns_role = array("rolesmaster.*");
			$cond_role = array('rolesmaster' . '.isDelete' => 0, 'rolesmaster' . '.isActive' => 1, 'rolesmaster' . '.code !=' => 'R_1');
			$data['roledata'] = $this->GlobalModel->selectQuery($orderColumns_role, $table_role, $cond_role);
			$this->load->view('dashboard/commonheader');
			$this->load->view('dashboard/user/user-edit', $data);
			$this->load->view('dashboard/footer');
		} else if ($resultPin && $checkRole == 'R_6') {
			$data['error_message'] = 'User Login Pin already exists';
			$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
			$table_name = 'branchmaster';
			$orderColumns = array("branchmaster.*");
			$cond = array('branchmaster' . '.isDelete' => 0, 'branchmaster' . '.isActive' => 1);
			$data['branchdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);
			$table_role = 'rolesmaster';
			$orderColumns_role = array("rolesmaster.*");
			$cond_role = array('rolesmaster' . '.isDelete' => 0, 'rolesmaster' . '.isActive' => 1, 'rolesmaster' . '.code !=' => 'R_1');
			$data['roledata'] = $this->GlobalModel->selectQuery($orderColumns_role, $table_role, $cond_role);
			$this->load->view('dashboard/commonheader');
			$this->load->view('dashboard/user/user-edit', $data);
			$this->load->view('dashboard/footer');
		} else {
			if ($resultEmail) {
				$data['error_message'] = 'User Email already exists.';
				$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
				$table_name = 'branchmaster';
				$orderColumns = array("branchmaster.*");
				$cond = array('branchmaster' . '.isDelete' => 0, 'branchmaster' . '.isActive' => 1);
				$data['branchdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);
				$table_role = 'rolesmaster';
				$orderColumns_role = array("rolesmaster.*");
				$cond_role = array('rolesmaster' . '.isDelete' => 0, 'rolesmaster' . '.isActive' => 1, 'rolesmaster' . '.code !=' => 'R_1');
				$data['roledata'] = $this->GlobalModel->selectQuery($orderColumns_role, $table_role, $cond_role);
				$this->load->view('dashboard/commonheader');
				$this->load->view('dashboard/user/user-edit', $data);
				$this->load->view('dashboard/footer');
			} else {
				$this->form_validation->set_rules('branchname', 'Branch Name', 'trim|required');
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
					$table_role = 'rolesmaster';
					$orderColumns_role = array("rolesmaster.*");
					$cond_role = array('rolesmaster' . '.isDelete' => 0, 'rolesmaster' . '.isActive' => 1, 'rolesmaster' . '.code !=' => 'R_1');
					$data['roledata'] = $this->GlobalModel->selectQuery($orderColumns_role, $table_role, $cond_role);
					$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
					$this->load->view('dashboard/commonheader');
					$this->load->view('dashboard/user/user-edit', $data);
					$this->load->view('dashboard/footer');
				} else {
					$lpin = "";
					$active = 0;
					if ($this->input->post('isActive') == 'on') {
						$active = 1;
					}
					if ($this->input->post("userrole") == "R_6") {
						$lpin = $this->input->post('loginpin');
					}
					$branch = $this->input->post("branchname");
					if ($this->branchCode != "") {
						$branch = $this->branchCode;
					}
					$data = array(
						'userBranchCode' => $branch,
						'userName' => $this->input->post("username"),
						'userLang' =>	$this->input->post("userlanguage"),
						'invoicePreference' => $this->input->post('invoicePreference'),
						'userEmpNo' =>	$this->input->post("userempnumber"),
						'userEmail' =>	$this->input->post("useremail"),
						'userRole' =>	$this->input->post("userrole"),
						'loginpin' => $lpin,
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
								$filedoc = $this->GlobalModel->doEdit($subData, 'usermaster', $code);
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
					//echo json_encode($response);
				}
			}
		}
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
		$resultData = $this->GlobalModel->doEdit($data, 'usermaster', $code);
		echo $this->GlobalModel->delete($code, 'usermaster');
	}

	public function role()
	{
		$this->load->view('dashboard/header');

		$this->load->view('dashboard/user/user-role');
		$this->load->view('dashboard/footer');
	}
}
