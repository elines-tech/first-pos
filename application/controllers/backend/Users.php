<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
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
		$rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		$this->rights = $this->GlobalModel->getMenuRights('4.1',$rolecode);
		if($this->rights ==''){
			$this->load->view('errors/norights.php');
		}
	}

	public function listRecords()
	{
		$data=[];
		if($this->rights !='' && $this->rights['view']==1){
			$this->load->view('backend/template/header');
			$this->load->view('backend/template/sidebar');
			$this->load->view('backend/user/users', $data);
			$this->load->view('backend/template/footer');
		}else{
			$this->load->view('errors/norights.php');
		}
	}

	public function getList()
	{
		$tableName = 'usermaster';
		$orderColumns = array("usermaster.*,rolesmaster.role");
		$search = $this->input->GET("search")['value'];
		$condition = array('usermaster' . '.userRole !=' => 'R_1');
		$orderBy = array('usermaster' . '.id' => 'DESC');
		$joinType = array('rolesmaster' => 'inner');
		$join = array('rolesmaster' => 'rolesmaster.code=usermaster.userRole');
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$like = array();
		$extraCondition = " usermaster.isDelete=0 OR usermaster.isDelete IS NULL";
		$like = array("usermaster.firstName" => $search . "~both", "usermaster.lastName" => $search . "~both", "usermaster.userEmpNo" => $search . "~both", "usermaster.userEmail" => $search . "~both", "rolemaster.role" => $search . "~both");
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		$srno = $offset + 1;
		$data = array();
		$dataCount = 0;
		if ($Records) {
			foreach ($Records->result() as $row) {
				$actionHtml='';
				if($this->rights !='' && $this->rights['view']==1){
					$actionHtml .= '<a href="' . base_url() . 'users/view/' . $row->code . '" class="btn btn-sm btn-success m-1 cursor_pointer"><i id="edt" title="View" class="fa fa-eye"></i></a>';
				}
				if($this->rights !='' && $this->rights['update']==1){
					$actionHtml .= '<a href="' . base_url() . 'users/edit/' . $row->code . '" class="btn btn-info btn-sm cursor_pointer m-1"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
				}
				if($this->rights !='' && $this->rights['delete']==1){
					$actionHtml .= '<a class="btn btn-sm btn-danger m-1 cursor_pointer delete_id" id="' . $row->code . '" ><i  title="Delete" class="fa fa-trash"></i></a>';
				}
		

				if ($row->isActive == "1") {
					$status = " <span class='badge bg-success'>Active</span>";
				} else {
					$status = " <span class='badge bg-danger'>Inactive</span>";
				}

				$data[] = array(
					$srno,
					$row->code,
					$row->username,
					$row->firstName." ".$row->lastName,
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
		if($this->rights !='' && $this->rights['update']==1){
			$code = $this->uri->segment(3);
			$table_role = 'rolesmaster';
			$orderColumns_role = array("rolesmaster.*");
			$cond_role = array('rolesmaster' . '.isDelete' => 0, 'rolesmaster' . '.isActive' => 1);
			$data['roledata'] = $this->GlobalModel->selectQuery($orderColumns_role, $table_role, $cond_role);
			$data['userData']=$this->GlobalModel->get_data($code, 'usermaster');
			$this->load->view('backend/template/header');
			$this->load->view('backend/template/sidebar');
			$this->load->view('backend/user/user-edit', $data);
			$this->load->view('backend/template/footer');			
		}else{
			$this->load->view('errors/norights.php'); 
		}
	}

	public function view()
	{
		if($this->rights !='' && $this->rights['view']==1){
			$code = $this->uri->segment(3);
			$table_role = 'rolesmaster';
			$orderColumns_role = array("rolesmaster.*");
			$cond_role = array('rolesmaster' . '.isDelete' => 0, 'rolesmaster' . '.isActive' => 1);
			$data['roledata'] = $this->GlobalModel->selectQuery($orderColumns_role, $table_role, $cond_role);
			$data['userData']=$this->GlobalModel->get_data($code, 'usermaster');
			$this->load->view('backend/template/header');
			$this->load->view('backend/template/sidebar');
			$this->load->view('backend/user/user-view', $data);
			$this->load->view('backend/template/footer');	
		}else{
			$this->load->view('errors/norights.php');
		}
	}

	public function add()
	{
		if($this->rights !='' && $this->rights['insert']==1){
			$table_role = 'rolesmaster';
			$orderColumns_role = array("rolesmaster.*");
			$cond_role = array('rolesmaster' . '.isDelete' => 0, 'rolesmaster' . '.isActive' => 1);
			$data['roledata'] = $this->GlobalModel->selectQuery($orderColumns_role, $table_role, $cond_role);
			$this->load->view('backend/template/header');
			$this->load->view('backend/template/sidebar');
			$this->load->view('backend/user/user-add', $data);
			$this->load->view('backend/template/footer');
		}else{
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
		$this->form_validation->set_rules('firstname', 'First Name', 'trim|required');
		$this->form_validation->set_rules('lastname', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('userlanguage', 'User Language', 'trim|required');
		$this->form_validation->set_rules('username', 'User Name', 'trim|required');	
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
			'contactnumber',
			'Contact Number',
			'trim|required|is_unique[usermaster.contactNumber]',
			array(
				'is_unique'     => 'Contact Number already exists.'
			)
		);
		$this->form_validation->set_rules('userrole', 'User Role', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
				$table_role = 'rolesmaster';
				$orderColumns_role = array("rolesmaster.*");
				$cond_role = array('rolesmaster' . '.isDelete' => 0, 'rolesmaster' . '.isActive' => 1);
				$data['roledata'] = $this->GlobalModel->selectQuery($orderColumns_role, $table_role, $cond_role);
				$this->load->view('backend/template/header');
				$this->load->view('backend/template/sidebar');
				$this->load->view('backend/user/user-add', $data);
				$this->load->view('backend/template/footer');
			} else {
				$active = 0;
				if ($this->input->post('isActive') == 'on') {
					$active = 1;
				}
				$data = array(
					'firstname' => $this->input->post("firstname"),
					'lastname' => $this->input->post("lastname"),
					'username' => $this->input->post("username"),
					'userLang' =>	$this->input->post("userlanguage"),
					'userEmpNo' =>	$this->input->post("userempnumber"),
					'userEmail' =>	$this->input->post("useremail"),
					'contactNumber' =>	$this->input->post("contactnumber"), 
					'userRole' =>	$this->input->post("userrole"),
					'password' => md5($this->input->post("password")),
					'isActive' => $active,
					'addID' => $addID,
					'addIP' => $ip,
				);
				$result = $this->GlobalModel->addNew($data, 'usermaster', 'USR');
				if ($result != false) {
					
				$userImage =  "";
                $uploadDir = 'upload/userImage/';
                if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
                if (!empty($_FILES['userImage']['name'])) {
                    $tmpFile = $_FILES['userImage']['tmp_name'];
                    $ext = pathinfo($_FILES['userImage']['name'], PATHINFO_EXTENSION);
                    $filename = $uploadDir . '/' . $result . '-' . time() . '.' . $ext;
                    move_uploaded_file($tmpFile, $filename);
                    if (file_exists($filename)) {
                        $userImage = $uploadDir . $result . '-' . time() . '.' . $ext;
                    }
                } else {
                    $userImage = '';
                }
                $subData = array(
                    'profilePhoto' => $userImage 
                );
                $filedoc = $this->GlobalModel->doEdit($subData, 'usermaster', $result);

					$txt = $result . " - " . $user . " User is added.";
					$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
					$this->GlobalModel->activity_log($activity_text);
					
					$response['status'] = true;
					$response['message'] = 'User Added successfully.';

				} else {
					$response['status'] = false;
					$response['message'] = "Failed To Add User";
				}
				$this->session->set_flashdata('message', json_encode($response));
				redirect('users/listRecords', 'refresh');
				//echo json_encode($response);
			}
	}
	public function update()
	{
		$code = $this->input->post("code");
		$user = $this->input->post("username");
		$userEmail = $this->input->post("useremail");
		$userempnumber = $this->input->post("userempnumber");
		$contactnumber = $this->input->post("contactnumber");
		$ip = $_SERVER['REMOTE_ADDR'];
		$date = date('d-m-y h:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$resultEmpNo = $this->db->query("SELECT * FROM usermaster WHERE userEmpNo='" . $userempnumber . "' AND code !='" . $code . "' and (`isDelete` IS NULL OR `isDelete`='0')");
		$contact = $this->db->query("SELECT * FROM usermaster WHERE contactnumber='" . $contactnumber . "' AND code !='" . $code . "' and (`isDelete` IS NULL OR `isDelete`='0')");
		if ($resultEmpNo->num_rows() > 0) {
			$data['error_message'] = 'User Employee Number already exists';
			$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');			
			$table_role = 'rolesmaster';
			$orderColumns_role = array("rolesmaster.*");
			$cond_role = array('rolesmaster' . '.isDelete' => 0, 'rolesmaster' . '.isActive' => 1);
			$data['roledata'] = $this->GlobalModel->selectQuery($orderColumns_role, $table_role, $cond_role);
			$this->load->view('backend/template/header');
			$this->load->view('backend/template/sidebar');
			$this->load->view('backend/user/user-edit', $data);
			$this->load->view('backend/template/footer');
		} else if ($contact->num_rows() > 0) {
			$data['error_message'] = 'Contact Number already exists';
			$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');			
			$table_role = 'rolesmaster';
			$orderColumns_role = array("rolesmaster.*");
			$cond_role = array('rolesmaster' . '.isDelete' => 0, 'rolesmaster' . '.isActive' => 1);
			$data['roledata'] = $this->GlobalModel->selectQuery($orderColumns_role, $table_role, $cond_role);
			$this->load->view('backend/template/header');
			$this->load->view('backend/template/sidebar');
			$this->load->view('backend/user/user-edit', $data);
			$this->load->view('backend/template/footer');
		} else {
			$resultEmail = $this->db->query("SELECT * FROM usermaster WHERE userEmail='" . $userEmail . "' AND code !='" . $code . "' and (`isDelete` IS NULL OR `isDelete`='0')");
			if ($resultEmail->num_rows() > 0) {
				$data['error_message'] = 'User Email already exists.';
				$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');				
				$table_role = 'rolesmaster';
				$orderColumns_role = array("rolesmaster.*");
				$cond_role = array('rolesmaster' . '.isDelete' => 0, 'rolesmaster' . '.isActive' => 1);
				$data['roledata'] = $this->GlobalModel->selectQuery($orderColumns_role, $table_role, $cond_role);
				$this->load->view('backend/template/header');
				$this->load->view('backend/template/sidebar');
				$this->load->view('backend/user/user-edit', $data);
				$this->load->view('backend/template/footer');
			} else {
				$this->form_validation->set_rules('firstname', 'First Name', 'trim|required');
		        $this->form_validation->set_rules('lastname', 'Last Name', 'trim|required');
		        $this->form_validation->set_rules('username', 'User Name', 'trim|required');
				$this->form_validation->set_rules('userlanguage', 'User Language', 'trim|required');
				$this->form_validation->set_rules('userempnumber', 'User Employee Number', 'trim|required');
				$this->form_validation->set_rules('useremail', 'User Email', 'trim|required');
				$this->form_validation->set_rules('userrole', 'User Role', 'trim|required');
				 $this->form_validation->set_rules('contactnumber', 'Contact Number', 'trim|required');
				if ($this->form_validation->run() == FALSE) {
					//$data['error_message'] = '* Fields are Required!';
					$table_role = 'rolesmaster';
					$orderColumns_role = array("rolesmaster.*");
					$cond_role = array('rolesmaster' . '.isDelete' => 0, 'rolesmaster' . '.isActive' => 1);
					$data['roledata'] = $this->GlobalModel->selectQuery($orderColumns_role, $table_role, $cond_role);
					$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
					$this->load->view('backend/template/header');
					$this->load->view('backend/template/sidebar');
					$this->load->view('backend/user/user-edit', $data);
					$this->load->view('backend/template/footer');
				} else {

					$active = 0;
					if ($this->input->post('isActive') == 'on') {
						$active = 1;
					}
					$data = array(

						'firstname' => $this->input->post("firstname"),
						'lastname' => $this->input->post("lastname"),
						'username' => $this->input->post("username"),
						'userLang' =>	$this->input->post("userlanguage"),
						'userEmpNo' =>	$this->input->post("userempnumber"),
						'userEmail' =>	$this->input->post("useremail"),
						'contactNumber' =>	$this->input->post("contactnumber"), 
						'userRole' =>	$this->input->post("userrole"),
						'isActive' => $active,
						'editID' => $addID,
						'editIP' => $ip,
					);
					$password = $this->input->post("password");
					if ($password != '') {
						$newpassword =  md5($password);
						$data['password'] = $newpassword;
					}
					$result = $this->GlobalModel->doEdit($data, 'usermaster', $code);
					if ($result == true) {						
						    $filedoc = false;
							$userImage = '';
							$uploadDir = 'upload/userImage/';
							if (!file_exists($uploadDir)) mkdir($uploadDir, 0755, false);
							if (!empty($_FILES['userImage']['name'])) {

								$filepro = $this->db->query("SELECT profilePhoto FROM usermaster WHERE `code` = '" . $code . "'");
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
									'profilePhoto' => $userImage
								);

								$filedoc = $this->GlobalModel->doEdit($subData, 'usermaster', $code);
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
					redirect('users/listRecords', 'refresh');
					//echo json_encode($response);
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
		$resultData = $this->GlobalModel->doEdit($data, 'usermaster', $code);
		echo $this->GlobalModel->delete($code, 'usermaster');
	}

	public function role()
	{
		$this->load->view('dashboard/header');
		$this->load->view('dashboard/sidebar');
		$this->load->view('dashboard/user/user-role');
		$this->load->view('dashboard/footer');
	}
	
	public function getBranchCounters()
    {
        $branchCode= $this->input->post("branchCode");
        $counterHtml = '';
        $counters = $this->GlobalModel->selectQuery('countermaster.code,countermaster.counterName', 'countermaster', array('countermaster.isActive' => 1, 'countermaster.branchCode' => $branchCode)); 
        if ($counters && $counters->num_rows() > 0) {
            $response['status'] = true;
            $counterHtml .= '<option value="">Select Counter</option>';
            foreach ($counters->result() as $con) {
				$counterHtml .= '<option value="' .$con->code.'">' . $con->counterName .'</option>';  
			}
        } else {
            $response['status'] = false;
        }
        $response['counters'] =  $counterHtml;
        echo json_encode($response);
    }
	
	
}
