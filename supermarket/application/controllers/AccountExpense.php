<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AccountExpense extends CI_Controller
{
    var $session_key;
	protected $rolecode,$branchCode; 
    public function __construct()
    {
        parent::__construct();
        $this->load->model('GlobalModel');
        $this->session_key = $this->session->userdata('key' . SESS_KEY);
        if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
            redirect('Login', 'refresh');
        }
        $res = $this->GlobalModel->checkActiveSubscription();
        if ($res == "EXPIRED") {
            redirect('package', 'refresh');
        }
        $dbName = $this->session->userdata['current_db' . $this->session_key];
        $this->db->query('use ' . $dbName);
		$this->rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		$this->branchCode = $this->session->userdata['logged_in' . $this->session_key]['userBranch'];
		$this->rights = $this->GlobalModel->getMenuRights('4.1',$this->rolecode);
		if($this->rights ==''){
			$this->load->view('errors/norights.php');
		}
       
    }
    public function listRecords()
    {
		if($this->rights !='' && $this->rights['view']==1){
			$data['insertRights'] = $this->rights['insert'];
			$data['branch'] = $this->GlobalModel->selectActiveData('branchmaster');
			$data['branchCode']="";
			$data['branchName']="";
			if($this->branchCode!=""){
				$data['branchCode']=$this->branchCode;
				$data['branchName'] = $this->GlobalModel->selectDataById($this->branchCode, 'branchmaster')->result()[0]->branchName;
			} 
			$this->load->view('dashboard/header');
			
			$this->load->view('dashboard/expense/list', $data);
			$this->load->view('dashboard/footer');
		}else{
			$this->load->view('errors/norights.php');
		}
    }
    public function getAccountExpenseList()
    {
		$branch="";
		if($this->branchCode!=""){
			$branch=$this->branchCode;
		}
        $tableName = "accountexpense";
        $search = $this->input->GET("search")['value'];
        $orderColumns = array("accountexpense.*,branchmaster.branchName");
        $condition = array("accountexpense.branchCode"=>$branch);
        $orderBy = array('accountexpense.id' => 'DESC');
        $joinType = array('branchmaster' => 'inner');
        $join = array('branchmaster' => 'branchmaster.code=accountexpense.branchCode');
        $groupByColumn = array();
        $limit = $this->input->GET("length");
        $offset = $this->input->GET("start");
        $extraCondition = " accountexpense.isDelete=0 OR accountexpense.isDelete IS NULL";
        $like = array("accountexpense.title" => $search . "~both", "accountexpense.cost" => $search . "~both", "branchmaster.branchName" => $search . "~both");
        $Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
        $srno = $_GET['start'] + 1;
        if ($Records) {
            foreach ($Records->result() as $row) {
                $code = $row->code;
                $actionHtml='';
				if($this->rights !='' && $this->rights['view']==1){
					$actionHtml .= '<a id="view" class="btn btn-sm btn-success cursor_pointer view_account_expense m-1" data-seq="' . $row->code . '" data-type="1"><i id="edt" title="View" class="fa fa-eye"></i></a>';
				}
				if($this->rights !='' && $this->rights['update']==1){
					$actionHtml .= '<a id="edit" class="btn btn-sm btn-info cursor_pointer edit_account_expense m-1" data-seq="' . $row->code . '" data-type="2"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
				}
				if($this->rights !='' && $this->rights['delete']==1){
					$actionHtml .= '<a id="delete" class="btn btn-sm btn-danger cursor_pointer delete_account_expense m-1" data-seq="' . $row->code . '"><i id="dlt" title="Delete" class="fa fa-trash"></i></a>';
				}
                $date = "";
                if ($row->accExpenseDate != "") {
                    $date = date('d/m/Y', strtotime($row->accExpenseDate));
                }
                $data[] = array(
                    $srno,
                    $row->code,
                    $date,
                    $row->branchName,
                    $row->title,
                    $row->cost,
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

    public function saveAccountExpense()
    {
        $date = date('Y-m-d H:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $loginrole = "";
		$branch = trim($this->input->post("branch"));
		if($this->branchCode!=""){
			$branch=$this->branchCode;
		}
        $date = trim($this->input->post("date"));
        $expensename = $this->input->post("expensename");
        $expensecost = $this->input->post("expensecost");
        $expensedescription = $this->input->post("expensedescription");
        $code = $this->input->post("code");
        $ip = $_SERVER['REMOTE_ADDR'];
        $condition2 = array('LOWER(title)' => strtolower($expensename), 'branchCode' => $branch);
        if ($code != '') {
            $condition2['accountexpense.code!='] = $code;
        }
        $result = $this->GlobalModel->checkDuplicateRecordNew($condition2, 'accountexpense');
        if ($result == true) {
            $response['status'] = false;
            $response['message'] = 'Duplicate Expense Name';
        } else {
            $data = array(
                'branchCode' => $branch,
                'title' => $expensename,
                'cost' =>  $expensecost,
                'description' => $expensedescription,
                'accExpenseDate' => $date
            );
            if ($code != '') {
                $data['editID'] = $addID;
                $data['editIP'] = $ip;
                $code = $this->GlobalModel->doEdit($data, 'accountexpense', $code);
                $code = $code;
                $successMsg = "Account Expense Updated Successfully";
                $errorMsg = "Failed To Update SubCatgeory";
                $txt = $code . " - " . $expensename . " Account Expense is updated.";
            } else {
                $data['addID'] = $addID;
                $data['addIP'] = $ip;
                $code = $this->GlobalModel->addWithoutYear($data, 'accountexpense', 'ACTE');
                $successMsg = "Account Expense Added Successfully";
                $errorMsg = "Failed To Add Account Expense";
                $txt = $code . " - " . $expensename . " Account Expense is added.";
            }
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

    public function editAccountExpense()
    {
		if($this->rights !='' && $this->rights['update']==1){
			$code = $this->input->post('code');
			$query = $this->GlobalModel->selectQuery("accountexpense.*", 'accountexpense', array('accountexpense.code' => $code));
			if ($query) {
				$result = $query->result_array()[0];
				$data['status'] = true;
				$data['code'] = $result['code'];
				$data['expensename'] = $result['title'];
				$data['expensecost'] = $result['cost'];
				$data['expensedescription'] = $result['description'];
				$data['date'] =   date('Y-m-d', strtotime($result['accExpenseDate']));
				$prdHtml = '';
				$branch = $this->GlobalModel->selectQuery('branchmaster.*', 'branchmaster', array('branchmaster.isActive' => 1));
				if ($branch && $branch->num_rows() > 0) {
					$prdHtml .= '<option value="">Select Branch</option>';
					foreach ($branch->result() as $br) {
						$selected = $result['branchCode'] == $br->code ? 'selected' : '';
						$prdHtml .= '<option value="' . $br->code . '"' . $selected . '>' . $br->branchName . '</option>';
					}
				}
				$data['branch'] = $prdHtml;
			} else {
				$data['status'] = false;
			}
		}else{
			$data['status'] = false;
		}
		echo json_encode($data);
    }

    public function deleteAccountExpense()
    {
        $code = $this->input->post('code');
        $date = date('Y-m-d H:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $accountname = $this->GlobalModel->selectDataById($code, 'accountexpense')->result()[0]->title;
        $query = $this->GlobalModel->delete($code, 'accountexpense');
        if ($query) {
            $txt = $code . " - " . $accountname . " Acccount Expense is deleted.";
            $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
            $this->GlobalModel->activity_log($activity_text);
        }
        echo $query;
    }
}
