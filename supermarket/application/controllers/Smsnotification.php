<?php

use Twilio\Rest\Client;

class Smsnotification extends CI_Controller
{

    var $session_key;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('form', 'url', 'html');
        $this->load->model('GlobalModel');
        $this->session_key = $this->session->userdata('key' . SESS_KEY);
        if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
            redirect('Login', 'refresh');
        }
        $res = $this->GlobalModel->checkActiveSubscription();
        if ($res == "EXPIRED") {
            redirect('package', 'refresh');
        }
        $rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
        $this->rights = $this->GlobalModel->getMenuRights('9.1', $rolecode);
        if ($this->rights == '') {
            $this->load->view('errors/norights.php');
        }
    }

    public function templates()
    {
        if ($this->rights != '' && $this->rights['view'] == 1) {
			$data['insertRights'] = $this->rights['insert'];
            $this->load->view('dashboard/header');
            $this->load->view('dashboard/notifications/sms/list',$data);
            $this->load->view('dashboard/footer');
        } else {
            $this->load->view('errors/norights.php');
        }
    }

    public function list()
    {
        $dataCount = 0;
        $data = array();
        $search = $this->input->GET("search")['value'];
        $limit = $this->input->GET("length");
        $offset = $this->input->GET("start");
        $srno = $_GET['start'] + 1;
        $draw = $_GET["draw"];
        $srno = 1;
        $tableName = "smstemplates";
        $orderColumns = array("smstemplates.*");
        $condition = array();
        $orderBy = array('smstemplates.id' => 'DESC');
        $joinType = $join = $groupByColumn = [];
        $extraCondition = 'smstemplates.isDelete !=1';
        $like = array("smstemplates.templateName" => $search . "~both", "smstemplates.code" => $search . "~both", "smstemplates.template" => $search . "~both");
        $Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
        if ($Records) {
            foreach ($Records->result() as $row) {
				
				$actionBtn='<div class="d-flex">';
				if($this->rights !='' && $this->rights['view']==1){
					//$actionBtn .= '<a href="javascript:void(0)" data-id="' . $row->code . '" class="btn btn-sm btn-success btn-view m-1"><i class="fa fa-eye"></i></a>';
				}
				if($this->rights !='' && $this->rights['update']==1){
					$actionBtn .= '<a id="edit" href="javascript:void(0)" data-id="' . $row->code . '" class="btn btn-sm btn-info btn-edit m-1"><i class="fa fa-edit"></i></a>';
				}
				if($this->rights !='' && $this->rights['delete']==1){
					$actionBtn .= '<a id="delete" href="javascript:void(0)" data-id="' . $row->code . '" class="btn btn-sm btn-danger btn-delete m-1"><i class="fa fa-trash"></i></a>';
				}
                $actionBtn .='</div>';
			
                $data[] = array(
                    $srno,
                    $row->templateName,
                    $row->template,
                    $row->isActive == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">In-Active</span>',
                    $actionBtn
                );
                $srno++;
            }
            $dataCount = sizeof($this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, array(), '', '', $groupByColumn, $extraCondition)->result());
        }
        $output = array(
            "draw"            =>     intval($draw),
            "recordsTotal"    =>     $dataCount,
            "recordsFiltered" =>     $dataCount,
            "data"            =>     $data
        );
        echo json_encode($output);
    }

    public function save()
    {
        $date = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $code = trim($this->input->post('code'));
        $templateName = trim($this->input->post('templateName'));
        $template = trim($this->input->post('template'));
        $isActive = trim($this->input->post('isActive')) == "" ? 0 : 1;
        $data = array(
            'templateName' => $templateName,
            'template' => $template,
            'isActive' => $isActive
        );
        if ($code != '') {
            $data['editID'] = $addID;
            $data['editIP'] = $ip;
            $result = $this->GlobalModel->doEdit($data, 'smstemplates', $code);
            $successMsg = "Template updated successfully";
            $errorMsg = "Failed to update template";
            $txt = $code . " - " . $template . " sms template is updated.";
        } else {
            $data['addID'] = $addID;
            $data['addIP'] = $ip;
            $result = $this->GlobalModel->addWithoutYear($data, 'smstemplates', 'CAT');
            $successMsg = "Template Added Successfully";
            $errorMsg = "Failed to add template";
            $txt = $code . " - " . $template . " sms template is added.";
        }
        if ($result != 'false') {
            $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
            $this->GlobalModel->activity_log($activity_text);
            $response['status'] = true;
            $response['message'] = $successMsg;
        } else {
            $response['status'] = false;
            $response['message'] = $errorMsg;
        }
        echo json_encode($response);
        exit;
    }

    public function edit()
    {
        if ($this->rights != '' && $this->rights['update'] == 1) {
            $code = $this->input->post('code');
            $categoryQuery = $this->GlobalModel->selectQuery("smstemplates.*", 'smstemplates', array('smstemplates.code' => $code));
            if ($categoryQuery) {
                $result = $categoryQuery->result_array()[0];
                $data['status'] = true;
                $data['code'] = $result['code'];
                $data['templateName'] = $result['templateName'];
                $data['template'] = $result['template'];
                $data['isActive'] = $result['isActive'];
            } else {
                $data['status'] = false;
            }
        } else {
            $data['status'] = false;
        }
        echo json_encode($data);
    }

    public function delete()
    {
        if ($this->rights != '' && $this->rights['delete'] == 1) {
            $code = $this->input->post('code');
            $date = date('Y-m-d H:i:s');
            $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
            $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
            $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
            $loginrole = "";
            $ip = $_SERVER['REMOTE_ADDR'];
            $template = $this->GlobalModel->selectDataById($code, 'smstemplates')->result()[0]->template;
            $query = $this->GlobalModel->delete($code, 'smstemplates');
            if ($query) {
                $txt = $code . " - " . $template . " sms template is deleted.";
                $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
                $this->GlobalModel->activity_log($activity_text);
            }
            echo $query;
        }
    }
    public function send()
    {
        $sid = 'ACdccde8ddd4cf0cc39fffe7b0e8880eb4';
        $token = '8b6dca04c61a1b9138adf1d79ee21dce';
        $client = new Client($sid, $token);

        // Use the client to do fun stuff like send text messages!
        $client->messages->create(
            // the number you'd like to send the message to
            '+918983185204',
            [
                // A Twilio phone number you purchased at twilio.com/console
                'from' => '+12059904947',
                // the body of the text message you'd like to send
                'body' => 'Hey abhishek! Good luck on the bar exam! Happy Coding..'
            ]
        );
    }
	
	
}
