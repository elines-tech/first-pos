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
		if($this->input->post("code")!=null){
			$code = $this->input->post("code");
			$data['userData']=$this->GlobalModel->get_data($code, 'usermaster');
			
			$this->load->view('backend/template/header');
			$this->load->view('backend/template/sidebar');
			$this->load->view('backend/profile/viewProfile', $data);
			$this->load->view('backend/template/footer');
		}
		else{
			redirect('dashboard/listRecords', 'refresh');
		}
	}
	
	public function update()
	{
		$code = $this->input->post("code");
		$user = $this->input->post("username");
		$userEmail = $this->input->post("useremail");
		$firstname = $this->input->post("firstname");
		$lastname = $this->input->post("lastname");
		$contactnumber = $this->input->post("contactnumber");
		$ip = $_SERVER['REMOTE_ADDR'];
		$date = date('d-m-y h:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$resultUser = $this->db->query("SELECT * FROM usermaster WHERE userName='" . $userName . "' AND code !='" . $code . "' and (`isDelete` IS NULL OR `isDelete`='0')");
		
		$resultConatct = $this->db->query("SELECT * FROM usermaster WHERE contactNumber='" . $contactnumber . "' AND code !='" . $code . "' and (`isDelete` IS NULL OR `isDelete`='0')");
        if ($resultConatct->num_rows() > 0) {
			$data['error_message'] = 'Contact Number already exists';
			$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
			$this->load->view('backend/template/header');
			$this->load->view('backend/template/sidebar');
			$this->load->view('backend/profile/viewProfile', $data);
			$this->load->view('backend/template/footer');
		
		}		
		else if ($resultUser->num_rows() > 0) {
			$data['error_message'] = 'User Name already exists';
			$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
			$this->load->view('backend/template/header');
			$this->load->view('backend/template/sidebar');
			$this->load->view('backend/profile/viewProfile', $data);
			$this->load->view('backend/template/footer');
		
		} else {
			$resultEmail = $this->db->query("SELECT * FROM usermaster WHERE userEmail='" . $userEmail . "' AND code !='" . $code . "' and (`isDelete` IS NULL OR `isDelete`='0')");
			if ($resultEmail->num_rows() > 0) {
				$data['error_message'] = 'User Email already exists.';
				$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');		
				$this->load->view('backend/template/header');
				$this->load->view('backend/template/sidebar');
				$this->load->view('backend/profile/viewProfile', $data);
				$this->load->view('backend/template/footer');
				
			} else {
				$this->form_validation->set_rules('userlanguage', 'User Language', 'trim|required');
				$this->form_validation->set_rules('useremail', 'User Email', 'trim|required');
				if ($this->form_validation->run() == FALSE) {
					//$data['error_message'] = '* Fields are Required!';
					$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
					$this->load->view('backend/template/header');
					$this->load->view('backend/template/sidebar');
					$this->load->view('backend/profile/viewProfile', $data);
					$this->load->view('backend/template/footer');
					
				} else {
					$active = 0;
					if ($this->input->post('isActive') == 'on') {
						$active = 1;
					}
					$data = array(

						'username' => $this->input->post("username"),
						'firstname' => $this->input->post("firstname"),
						'lastname' => $this->input->post("lastname"),
						'userLang' =>	$this->input->post("userlanguage"),
						'userEmail' =>	$this->input->post("useremail"),
						'contactNumber'=> $this->input->post("contactnumber"),
						'editID' => $addID,
						'editIP' => $ip,
					);
					/*$password = $this->input->post("password");
					if ($password != '') {
						$newpassword =  md5($password);
						$data['userPassword'] = $newpassword;
					}*/
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
						$txt = $code . " - " . $user . " Profile is updated.";
						$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
						$this->GlobalModel->activity_log($activity_text);
						/*if ($this->input->post("userrole") == "R_5") {
							$response['status'] = true;
							$response['message'] = 'Profile Successfully Updated.';
						} else {*/
							$response['status'] = true;
							$response['message'] = 'Profile Successfully Updated.';
						//}
					} else {
						$response['status'] = false;
						$response['message'] = "No change In Profile";
					}
					$this->session->set_flashdata('profile_message', json_encode($response));
					//redirect('Profile/view/'.$code, 'refresh');
					$response['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
					$this->load->view('backend/template/header');
					$this->load->view('backend/template/sidebar');
					$this->load->view('backend/profile/viewProfile', $response);
					$this->load->view('backend/template/footer');
				}
			}
		}
	}

  
	public function updatePassword(){
		
		if($this->input->post("code")!=null){
			$code = $this->input->post("code");
			$data['userData']=$this->GlobalModel->get_data($code, 'usermaster');
			
			$this->load->view('backend/template/header');
			$this->load->view('backend/template/sidebar');
			$this->load->view('backend/profile/updatePassword', $data);
			$this->load->view('backend/template/footer');
		}
		else{
			redirect('dashboard/listRecords', 'refresh');
		}
	}
	
	public function passwordUpdate(){
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
			$this->load->view('backend/template/header');
			$this->load->view('backend/template/sidebar');
			$this->load->view('backend/profile/updatePassword', $data);
			$this->load->view('backend/template/footer');
		} else {
            //$data['userData'] = $this->GlobalModel->get_data($code, 'usermaster');
			$data = array(
				'password' => md5($this->input->post("password")),
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
			$this->session->set_flashdata('test_message', json_encode($response));
			//redirect('Profile/view/'.$code, 'refresh');
            $response['userData'] = $this->GlobalModel->get_data($code, 'usermaster');			
			$this->load->view('backend/template/header');
			$this->load->view('backend/template/sidebar');
			$this->load->view('backend/profile/updatePassword', $response);
			$this->load->view('backend/template/footer');
			
		}
		
	}
}