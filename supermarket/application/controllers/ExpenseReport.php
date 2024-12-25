<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ExpenseReport extends CI_Controller
{
    var $session_key;
	protected $rolecode,$branchCode; 
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
        $this->rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		$this->branchCode = $this->session->userdata['logged_in' . $this->session_key]['userBranch'];
        $this->rights = $this->GlobalModel->getMenuRights('6.3', $this->rolecode);
        if ($this->rights == '') {
            $this->load->view('errors/norights.php');
        }
    }

    public function getExpenseReport()
    {
        if ($this->rights != '' && $this->rights['view'] == 1) {
            $data['insertRights'] = $this->rights['insert'];
            $data['usermaster'] = $this->GlobalModel->selectQuery('usermaster.code,usermaster.userEmpNo', 'usermaster', array('usermaster.userRole' => 'R_3'));
            $data['branch'] = $this->GlobalModel->selectActiveData('branchmaster');
			$data['branchCode']="";
			$data['branchName']="";
			if($this->branchCode!=""){
				$data['branchCode']=$this->branchCode;
				$data['branchName'] = $this->GlobalModel->selectDataById($this->branchCode, 'branchmaster')->result()[0]->branchName;
			}
            $this->load->view('dashboard/header');
            $this->load->view('dashboard/report/expense', $data);
            $this->load->view('dashboard/footer');
        } else {
            $this->load->view('errors/norights.php');
        }
    }

    public function getAccountExpenseList()
    {
        $branchCode = $this->input->get('branchCode');
		if($this->branchCode!=""){
			$branchCode=$this->branchCode;
		}
        $fromDate = $this->input->get('fromDate');
        $toDate = $this->input->get('toDate');
        $export = $this->input->get('export');
        $search = $limit = $offset = '';
        $srno = 1;
        $draw = 0;
        if ($export == 0) {
            $search = $this->input->GET("search")['value'];
            $limit = $this->input->GET("length");
            $offset = $this->input->GET("start");
            $srno = $_GET['start'] + 1;
            $draw = $_GET["draw"];
        }
        $tableName = "accountexpense";
        //$search = $this->input->GET("search")['value'];
        $orderColumns = array("accountexpense.code,accountexpense.accExpenseDate,branchmaster.branchName,accountexpense.title,accountexpense.cost");
        $condition = array('accountexpense.branchCode' => $branchCode);
        $orderBy = array('accountexpense.id' => 'DESC');
        $joinType = array('branchmaster' => 'inner');
        $join = array('branchmaster' => 'branchmaster.code=accountexpense.branchCode');
        $groupByColumn = array();
        $limit = $this->input->GET("length");
        $offset = $this->input->GET("start");
        $extraCondition = " (accountexpense.isDelete=0 OR accountexpense.isDelete IS NULL)";
        if ($fromDate != '' && $toDate != '') {
            $extraCondition .= " and (accountexpense.accExpenseDate between '" . $fromDate . " 00:00:00' and '" . $toDate . " 00:00:00')";
        }
        $like = array("accountexpense.code" => $search . "~both","accountexpense.cost" => $search . "~both", "accountexpense.title" => $search . "~both", "accountexpense.cost" => $search . "~both", "branchmaster.branchName" => $search . "~both");
        $Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
        //$srno = $_GET['start'] + 1;
        $total = 0;
        //echo $this->db->last_query();
        if ($Records) {
            foreach ($Records->result() as $row) {
                $code = $row->code;
                $actionHtml = '<div class="d-flex"><a id="view" class="btn btn-sm btn-success cursor_pointer view_account_expense m-1" data-seq="' . $row->code . '" data-type="1"><i id="edt" title="View" class="fa fa-eye"></i></a>
					</div>';
                $date = "";
                if ($row->accExpenseDate != "") {
                    $date = date('d/m/Y', strtotime($row->accExpenseDate));
                }
                if ($export == 0) {
                    $data[] = array(
                        $srno,
                        $row->code,
                        $date,
                        $row->branchName,
                        $row->title,
                        $row->cost,
                        $actionHtml
                    );
                } else {
                    $data[] = array(
                        $srno,
                        $row->code,
                        $date,
                        $row->branchName,
                        $row->title,
                        $row->cost
                    );
                }
                $total += $row->cost;
                $srno++;
            }
            $dataCount = sizeof($this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, array(), '', '', '', $extraCondition)->result());
            $output = array(
                "draw"              =>     intval($draw),
                "recordsTotal"    =>      $dataCount,
                "recordsFiltered" =>     $dataCount,
                "data"            =>     $data,
                "total"           =>     $total
            );
            echo json_encode($output);
        } else {
            $dataCount = 0;
            $data = array();
            $output = array(
                "draw"              =>  intval($draw),
                "recordsTotal"    =>     $dataCount,
                "recordsFiltered" =>     $dataCount,
                "data"            =>     $data,
                "total"           =>     $total
            );
            echo json_encode($output);
        }
    }

    public function totalAmount()
    {
        $branchCode = $this->input->get('branchCode');
		if($this->branchCode!=""){
			$branchCode=$this->branchCode;
		}
        $fromDate = $this->input->get('fromDate');
        $toDate = $this->input->get('toDate');
        $export = $this->input->get('export');
        $search = $limit = $offset = '';
        $srno = 1;
        $total = 0;
        $tableName = "accountexpense";
        $search = $this->input->GET("search")['value'];
        $orderColumns = array("IFNULL(sum(accountexpense.cost),0) as totalCost");
        $condition = array('accountexpense.branchCode' => $branchCode);
        $orderBy = array('accountexpense.id' => 'DESC');
        $joinType = array('branchmaster' => 'inner');
        $join = array('branchmaster' => 'branchmaster.code=accountexpense.branchCode');
        $groupByColumn = array();
        $extraCondition = " (accountexpense.isDelete=0 OR accountexpense.isDelete IS NULL)";
        if ($fromDate != '' && $toDate != '') {
            $extraCondition .= " and (accountexpense.accExpenseDate between '" . $fromDate . " 00:00:00' and '" . $toDate . " 00:00:00')";
        }
        $like = array("accountexpense.cost" => $search . "~both", "accountexpense.title" => $search . "~both", "accountexpense.cost" => $search . "~both", "branchmaster.branchName" => $search . "~both");
        $Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
        if ($Records) {
            $total = $Records->result_array()[0]['totalCost'];
        }
        //echo $this->db->last_query(); 
        echo $total;
    }

    public function viewAccountExpense()
    {
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
        echo json_encode($data);
    }
}
