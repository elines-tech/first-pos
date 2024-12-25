<?php

class Emailnotification extends CI_Controller
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
        $this->rights = $this->GlobalModel->getMenuRights('9.3', $rolecode);
        if ($this->rights == '') {
            $this->load->view('errors/norights.php');
        }
    }

    public function templates()
    {
        if ($this->rights != '' && $this->rights['view'] == 1) {
			$data['insertRights'] = $this->rights['insert'];
            $this->load->view('dashboard/header');
            $this->load->view('dashboard/notifications/email/list',$data);
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
        $tableName = "emailtemplates";
        $orderColumns = array("emailtemplates.*");
        $condition = array();
        $orderBy = array('emailtemplates.id' => 'DESC');
        $joinType = $join = $groupByColumn = [];
        $extraCondition = 'emailtemplates.isDelete !=1';
        $like = array("emailtemplates.templateName" => $search . "~both", "emailtemplates.code" => $search . "~both", "emailtemplates.template" => $search . "~both");
        $Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
        if ($Records) {
            foreach ($Records->result() as $row) {
				$actionBtn='<div class="d-flex">';
				if($this->rights !='' && $this->rights['view']==1){
					//$actionBtn .= '<a href="javascript:void(0)" data-id="' . $row->code . '" class="btn btn-sm btn-success btn-view m-1"><i class="fa fa-eye"></i></a>';
				}
				if($this->rights !='' && $this->rights['update']==1){
					$actionBtn .= ' <a id="edit" href="javascript:void(0)" data-id="' . $row->code . '" class="btn btn-sm btn-info btn-edit m-1"><i class="fa fa-edit"></i></a>';
				}
				if($this->rights !='' && $this->rights['delete']==1){
					$actionBtn .= ' <a id="delete" href="javascript:void(0)" data-id="' . $row->code . '" class="btn btn-sm btn-danger btn-delete m-1"><i class="fa fa-trash"></i></a>';
				}
                $actionBtn .='</div>';
                $data[] = array(
                    $srno,
                    $row->templateName,
                    $row->subject,
                    $row->message,
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
        $subject = trim($this->input->post('subject'));
        $message = trim($this->input->post('message'));
        $isActive = trim($this->input->post('isActive')) == "" ? 0 : 1;
        $data = array(
            'templateName' => $templateName,
            'subject' => $subject,
            'message' => $message,
            'isActive' => $isActive
        );
        if ($code != '') {
            $data['editID'] = $addID;
            $data['editIP'] = $ip;
            $result = $this->GlobalModel->doEdit($data, 'emailtemplates', $code);
            $successMsg = "Template updated successfully";
            $errorMsg = "Failed to update template";
            $txt = $code . " - " . $subject . " email template is updated.";
        } else {
            $data['addID'] = $addID;
            $data['addIP'] = $ip;
            $result = $this->GlobalModel->addWithoutYear($data, 'emailtemplates', 'CAT');
            $successMsg = "Template Added Successfully";
            $errorMsg = "Failed to add template";
            $txt = $code . " - " . $subject . " email template is added.";
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
        if ($this->rights != '' && ($this->rights['update'] == 1 || $this->rights['view'] == 1)) {
            $code = $this->input->post('code');
            $categoryQuery = $this->GlobalModel->selectQuery("emailtemplates.*", 'emailtemplates', array('emailtemplates.code' => $code));
            if ($categoryQuery) {
                $result = $categoryQuery->result_array()[0];
                $data['status'] = true;
                $data['code'] = $result['code'];
                $data['templateName'] = $result['templateName'];
                $data['subject'] = $result['subject'];
                $data['message'] = $result['message'];
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
            $subject = $this->GlobalModel->selectDataById($code, 'emailtemplates')->result()[0]->subject;
            $query = $this->GlobalModel->delete($code, 'emailtemplates');
            if ($query) {
                $txt = $code . " - " . $subject . " email template is deleted.";
                $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
                $this->GlobalModel->activity_log($activity_text);
            }
            echo $query;
        }
    }

    public function notify()
    {
    }

    public function send()
    {
    }
}
