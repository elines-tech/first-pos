<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CustomerGroup extends CI_Controller
{
    var $session_key;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('GlobalModel');
        $this->session_key = $this->session->userdata('key' . SESS_KEY);
        $rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
        if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
            redirect('Login', 'refresh');
        }
        $this->rights = $this->GlobalModel->getMenuRights('5.2', $rolecode);
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
            $data['error'] = $this->session->flashdata('response');
            $this->load->view('dashboard/header');
            $this->load->view('dashboard/customergroup/list', $data);
            $this->load->view('dashboard/footer');
        } else {
            $this->load->view('errors/norights.php');
        }
    }

    public function getGroupList()
    {
        $tableName = "customergroupmaster";
        $search = $this->input->GET("search")['value'];
        $orderColumns = array("customergroupmaster.*");
        $condition = array();
        $orderBy = array('customergroupmaster.id' => 'DESC');
        $joinType = array();
        $join = array();
        $groupByColumn = array();
        $limit = $this->input->GET("length");
        $offset = $this->input->GET("start");
        $extraCondition = " customergroupmaster.isDelete=0 OR customergroupmaster.isDelete IS NULL";
        $like = array("customergroupmaster.customerGroupName" => $search . "~both","customergroupmaster.code" => $search . "~both");
        $Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
        $srno = $_GET['start'] + 1;
        if ($Records) {
            foreach ($Records->result() as $row) {
                $code = $row->code;
                if ($row->isActive == 1) {
                    $status = "<span class='badge bg-success'>Active</span>";
                } else {
                    $status = "<span class='badge bg-danger'>Inactive</span>";
                }
                $actionHtml = '';
                if ($this->rights != '' && $this->rights['view'] == 1) {
                    $actionHtml .= '<a id="view" class="view_group btn btn-success btn-sm cursor_pointer m-1" data-seq="' . $row->code . '" data-type="1"><i id="edt" title="View" class="fa fa-eye"></i></a>';
                }
                if ($this->rights != '' && $this->rights['update'] == 1) {
                    $actionHtml .= '<a id="edit" class="edit_group btn btn-info btn-sm cursor_pointer m-1" data-seq="' . $row->code . '" id="' . $row->code . '" data-type="2"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
                }
                if ($this->rights != '' && $this->rights['delete'] == 1) {
                    $actionHtml .= '<a id="delete" class="btn btn-sm btn-danger delete_group m-1" data-seq="' . $row->code . '"><i id="dlt" title="Delete"  class="fa fa-trash"></i></a>';
                }
                $data[] = array(
                    $srno,
                    $row->code,
                    $row->customerGroupName,
                    $status,
                    $actionHtml
                );
                $srno++;
            }
            $dataCount = sizeof($this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, array(), '', '', '', $extraCondition)->result());
            $output = array(
                "draw"              =>     intval($_GET["draw"]),
                "recordsTotal"    =>      $dataCount,
                "recordsFiltered" =>     $dataCount,
                "data"            =>     $data
            );
            echo json_encode($output);
        } else {
            $dataCount = 0;
            $data = array();
            $output = array(
                "draw"              =>     intval($_GET["draw"]),
                "recordsTotal"    =>     $dataCount,
                "recordsFiltered" =>     $dataCount,
                "data"            =>     $data
            );
            echo json_encode($output);
        }
    }
    public function add()
    {
        if ($this->rights != '' && $this->rights['insert'] == 1) {
            $data['insertRights'] = $this->rights['insert'];
            $this->load->view('dashboard/customergroup/add', $data);
        } else {
            $this->load->view('errors/norights.php');
        }
    }

    public function save()
    {
        $date = date('Y-m-d H:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $groupname = trim($this->input->post("groupname"));
        $isActive = $this->input->post("isActive");
        $ip = $_SERVER['REMOTE_ADDR'];
        $condition2 = array('LOWER(customerGroupName)' => strtolower($groupname));
        $result = $this->GlobalModel->checkDuplicateRecordNew($condition2, 'customergroupmaster');
        if ($result == true) {
            $response['status'] = false;
            $response['message'] = 'Duplicate Group Name';
        } else {
            $data = array(
                'customerGroupName' => $groupname,
                'isActive' => $isActive,
                'addID' => $addID,
                'addIP' => $ip,
                'addDate' => $date
            );
            $code = $this->GlobalModel->addWithoutYear($data, 'customergroupmaster', 'CUSTG');
            $successMsg = "Customer Groupname Added Successfully";
            $errorMsg = "Failed To Add Customer Groupname";
            $txt = $code . " - " . $groupname . " Customer Groupname is added.";
            if ($code != 'false') {
                $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
                $this->GlobalModel->activity_log($activity_text);
                $response['status'] = true;
                $response['message'] = $successMsg;
            } else {
                $response['status'] = false;
                $response['message'] = $errorMsg;
            }
        }
        echo json_encode($response);
    }

    public function edit()
    {
        if ($this->rights != '' && $this->rights['update'] == 1) {
            $data['updateRights'] = $this->rights['update'];
            $code = $this->input->post('code');
            $data['query'] = $this->GlobalModel->selectDataById($code, 'customergroupmaster');
            $this->load->view('dashboard/customergroup/edit', $data);
        } else {
            $this->load->view('errors/norights.php');
        }
    }

    public function update()
    {
        $date = date('Y-m-d H:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $groupname = trim($this->input->post("groupname"));
        $code = trim($this->input->post("code"));
        $isActive = $this->input->post("isActive");
        $ip = $_SERVER['REMOTE_ADDR'];
        $condition2 = array('LOWER(customerGroupName)' => strtolower($groupname), 'code !=' => $code);
        $result = $this->GlobalModel->checkDuplicateRecordNew($condition2, 'customergroupmaster');
        if ($result == true) {
            $response['status'] = false;
            $response['message'] = 'Duplicate Group Name';
        } else {
            $data = array(
                'customerGroupName' => $groupname,
                'isActive' => $isActive,
                'editID' => $addID,
                'editIP' => $ip,
                'editDate' => $date
            );
            $code = $this->GlobalModel->doEdit($data, 'customergroupmaster', $code);
            $successMsg = "Customer Groupname Updated Successfully";
            $errorMsg = "Failed To Update Customer Groupname";
            $txt = $code . " - " . $groupname . " Customer Groupname is updated.";
            if ($code != 'false') {
                $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
                $this->GlobalModel->activity_log($activity_text);
                $response['status'] = true;
                $response['message'] = $successMsg;
            } else {
                $response['status'] = false;
                $response['message'] = $errorMsg;
            }
        }
        echo json_encode($response);
    }

    public function view()
    {
        if ($this->rights != '' && $this->rights['view'] == 1) {
            $code = $this->input->post('code');
            $data['query'] = $this->GlobalModel->selectDataById($code, 'customergroupmaster');
            $this->load->view('dashboard/customergroup/view', $data);
        } else {
            $this->load->view('errors/norights.php');
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

        $txt = $code . "Customer Group is deleted.";
        $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
        $this->GlobalModel->activity_log($activity_text);
        echo $this->GlobalModel->delete($code, 'customergroupmaster');
    }
}
