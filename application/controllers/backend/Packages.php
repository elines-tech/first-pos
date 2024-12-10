<?php
class Packages extends CI_Controller
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
		$this->rights = $this->GlobalModel->getMenuRights('2.1',$rolecode);
		if($this->rights ==''){
			$this->load->view('errors/norights.php');
		}
    }

    public function listRecords()
    {
		if($this->rights !='' && $this->rights['view']==1){
			$data = [];
			$this->load->view('backend/template/header');
			$this->load->view('backend/template/sidebar');
			$this->load->view('backend/packages/list', $data);
			$this->load->view('backend/template/footer');
		}else{
			$this->load->view('errors/norights.php');
		}
    }

    public function getPackages()
    {
    }

    public function add()
    {
        $this->load->view('backend/template/header');
        $this->load->view('backend/template/sidebar');
        $this->load->view('backend/packages/add');
        $this->load->view('backend/template/footer');
    }
	
	public function view()
    {
		if($this->rights !='' && $this->rights['view']==1){
			$table_name = 'subscriptionmaster';
			$orderColumns = array("subscriptionmaster.*");
			$cond = array('subscriptionmaster.code'=>'PAK_1');
			$data['supermarket'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);
			
			$cond1 = array('subscriptionmaster.code'=>'PAK_2');
			$data['restaurant'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond1);
			
			$this->load->view('backend/template/header');
			$this->load->view('backend/template/sidebar');
			$this->load->view('backend/packages/view',$data);
			$this->load->view('backend/template/footer'); 
		}else{
			$this->load->view('errors/norights.php');
		}
    }

    public function save()
    {
    }

    public function edit($id)
    {
		if($this->rights !='' && $this->rights['update']==1){
		$code = $id;
		$table_name = 'subscriptionmaster';
		$orderColumns = array("subscriptionmaster.*");
		$cond = array('subscriptionmaster.code'=>$code);
		$data['supermarket'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);
        $this->load->view('backend/template/header');
        $this->load->view('backend/template/sidebar');
        $this->load->view('backend/packages/edit',$data);
        $this->load->view('backend/template/footer');
		}else{
			$this->load->view('errors/norights.php'); 
		}		
	}

    public function update()
    {
        $code = $this->input->post("code");
		$packagefor = $this->input->post("packagefor");
        $ip = $_SERVER['REMOTE_ADDR'];
        $date = date('d-m-y H:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim');
        $this->form_validation->set_rules('freetrialdays', 'Trial Days', 'trim|required');
        $this->form_validation->set_rules('tax', 'Tax', 'trim|required');
        $this->form_validation->set_rules('numberofusers', 'Number Of Users', 'trim|required');
        $this->form_validation->set_rules('costperuser', 'Cost of Users', 'trim|required');
        $this->form_validation->set_rules('numberofbranches', 'Number Of Branches', 'trim|required');
        $this->form_validation->set_rules('costperbranch', 'Cost of Branches', 'trim|required');           
        if ($this->form_validation->run() == FALSE) {
            $data['error_message'] = '* Fields are Required!';     
            $table_name = 'subscriptionmaster';
			$orderColumns = array("subscriptionmaster.*");
			$cond = array('subscriptionmaster.code'=>$code);
			$data['supermarket'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);
			$this->load->view('backend/template/header');
			$this->load->view('backend/template/sidebar');
			$this->load->view('backend/packages/edit',$data);
			$this->load->view('backend/template/footer');
        } else {
            $data = array(
                //'packageFor' => $this->input->post("packagefor"),
                'title' => ucwords(strtolower($this->input->post("title"))),
                'description' => $this->input->post("description"),
                'freetrialday' => $this->input->post("freetrialdays"),
                'tax' => $this->input->post("tax"),
                'costperuser' => $this->input->post("costperuser"),
				'costperbranch' => $this->input->post("costperbranch"),
                'noofusers' => $this->input->post("numberofusers"),                   
                'noofbranch' => $this->input->post("numberofbranches"),
                'monthlychargesextax'  => $this->input->post("monthlyprice"),
                'monthlychargesincltax'  => $this->input->post("monthlyfinalprice"),
                'yearlychargesextax'  => $this->input->post("yearlyprice"),                    
                'yearlychargesincltax'  => $this->input->post("yearlyfinalprice"),
                'monthlyperusercharges' => $this->input->post("monthlyperuserprice"),
                'yearlyperusercharges'  => $this->input->post("yearlyperuserprice"),
                'monthlyperbranchcharges'  => $this->input->post("monthlyperbranchprice"),
                'yearlyperbranchcharges' => $this->input->post("yearlyperbranchprice"),
				'isActive'=>1,
                'editDate'=>$date,
                'editID' => $addID,
                'editIP' => $ip,
            );
            $result = $this->GlobalModel->doEdit($data, 'subscriptionmaster', $code);
            if ($result == true ) {
                $txt = $code . " - " . $userName . " Subscription  is updated.";
                $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
                $this->GlobalModel->activity_log($activity_text);
                $response['status'] = true;
                $response['message'] = "Subscription Successfully Updated.";
            } else {
                $response['status'] = false;
                $response['message'] = "No change In Subscription";
            }
            $this->session->set_flashdata('message', json_encode($response));
            redirect('packages/view', 'refresh');
        }
    }


    public function delete()
    {
    }
}
