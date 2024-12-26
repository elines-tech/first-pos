<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reservation extends CI_Controller
{
    var $session_key;
	protected $rolecode,$branchCode;
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
        $this->rights = $this->GlobalModel->getMenuRights('8.4', $this->rolecode);
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
            $data['sectors'] = $this->GlobalModel->selectActiveData('sectorzonemaster');
            $data['tables'] = $this->GlobalModel->selectActiveData('tablemaster');
            $data['customer'] = $this->GlobalModel->selectQuery('customer.*', 'customer', array());
            $data['branchCode']="";
			$data['branchName']="";
			if($this->branchCode!=""){
				$data['branchCode']=$this->branchCode;
				$data['branchName'] = $this->GlobalModel->selectDataById($this->branchCode, 'branchmaster')->result()[0]->branchName;
			}
			$this->load->view('dashboard/header');
            $this->load->view('dashboard/reservation/list', $data);
            $this->load->view('dashboard/footer');
        } else {
            $this->load->view('errors/norights.php');
        }
    }

    public function getReservationList()
    {
		$branch ="";
		if($this->branchCode!=""){
			$branch=$this->branchCode;
		}
        $tableName = "tablereservation";
        $search = $this->input->GET("search")['value'];
        $orderColumns = array("tablereservation.*,customer.name,tablemaster.tableNumber,branchmaster.branchName,sectorzonemaster.zoneName");
        $condition = array('tablereservation.branchCode'=>$branch);
        $orderBy = array('tablereservation.id' => 'DESC');
        $joinType = array('sectorzonemaster' => 'inner','branchmaster' => 'inner','customer' => 'inner', 'tablemaster' => 'inner');
        $join = array('sectorzonemaster' => 'sectorzonemaster.id=tablereservation.sectorCode','branchmaster' => 'branchmaster.code=tablereservation.branchCode','customer' => 'customer.code=tablereservation.customerCode', 'tablemaster' => 'tablemaster.code=tablereservation.tableNumber');
        $groupByColumn = array();
        $limit = $this->input->GET("length");
        $offset = $this->input->GET("start");
        $extraCondition = " (tablereservation.isDelete=0 OR tablereservation.isDelete IS NULL)";
        $like = array("tablereservation.endTime" => $search . "~both","tablereservation.startTime" => $search . "~both","tablereservation.noOfPeople" => $search . "~both","tablereservation.code" => $search . "~both","customer.name" => $search . "~both", "tablemaster.tableNumber" => $search . "~both", "tablereservation.customerMobile" => $search . "~both");
        $Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
        $srno = $_GET['start'] + 1;
        if ($Records) {
            foreach ($Records->result() as $row) {
                $code = $row->code;
                $actionHtml = '<div class="d-flex">';
                if ($this->rights != '' && $this->rights['view'] == 1) {
                    $actionHtml .= '<a id="view" class="btn btn-success btn-sm  m-1 cursor_pointer view_booktable m-1" data-seq="' . $row->code . '" ><i id="edt" title="View" class="fa fa-eye"></i></a>';
                }
                if ($this->rights != '' && $this->rights['update'] == 1) {
                    //$actionHtml .= '<a class="btn btn-info btn-sm m-1 cursor_pointer edit_booktable m-1" data-seq="' . $row->code . '"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
                    $actionHtml .= '<a id="edit" class="btn btn-info btn-sm m-1" href="' . base_url() . 'Reservation/edit/' . $row->code . '"><i id="edt" title="Edit" class="fa fa-pencil cursor_pointer"></i></a>';
				}
                if ($this->rights != '' && $this->rights['delete'] == 1) {
                    $actionHtml .= '<a id="delete" class="btn btn-sm btn-danger m-1 cursor_pointer delete_booktable m-1" data-seq="' . $row->code . '"><i id="dlt" title="Delete" class="fa fa-trash"></i></a>';
                }
                $actionHtml .= '</div>';
                $data[] = array(
                    $srno,
                    $row->code,
                    $row->name,
                    $row->customerMobile,
					$row->branchName,
					$row->zoneName,
                    $row->tableNumber,
                    $row->noOfPeople,
                    $row->startTime,
                    $row->endTime,
                    date('d M Y h:i A', strtotime($row->resDate)),
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

    public function tableReservation()
    {
        $date = date('Y-m-d H:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $customerName = trim($this->input->post("customerName"));
        $mobilephone = $this->input->post("mobilephone");
		$country = $this->input->post('countrycode');
		$clientPhone = $country . $mobilephone; 
		$branchCode=$this->input->post("branchCode");
		$sectorCode=$this->input->post("sector");
        $tableNumber = $this->input->post("tableNumber");
        $numberofpeople = $this->input->post("numberofpeople");
        $resdate = $this->input->post("date");
        $startTime = $this->input->post("stime");
        $endTime = $this->input->post("etime");
        $ip = $_SERVER['REMOTE_ADDR'];
        $condition2 = array('customerCode' => $customerName, 'tableNumber' => $tableNumber, 'isActive' => 1);
        $result = $this->GlobalModel->checkDuplicateRecordNew($condition2, 'tablereservation');
        if ($result == true) {
            $response['status'] = false;
            $response['message'] = 'You have already booked the table.';
        } else {
            $data = array(
                'customerCode' => $customerName,
                'customerMobile' => $clientPhone,
				'branchCode'=>$branchCode,
				'sectorCode'=>$sectorCode, 
                'tableNumber' => $tableNumber,
                'noOfPeople' => $numberofpeople,
                'resDate' => $resdate,
                'startTime' => $startTime,
                'endTime' => $endTime,
                'addID' => $addID,
                'addIP' => $ip,
                'isActive' => 1
            );
            $code = $this->GlobalModel->addNew($data, 'tablereservation', 'TR');
            if ($code != 'false') {
                $txt = $code . " - " . $tableNumber . " Tbale is reserved.";
                $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
                $this->GlobalModel->activity_log($activity_text);
                $response['status'] = true;
                $response['message'] = 'Table Booked Successfully';
            } else {
                $response['status'] = false;
                $response['message'] = 'Failed to Booked Table';
            }
        }
        echo json_encode($response);
    }

    public function edit()
    {
        if ($this->rights != '' && $this->rights['update'] == 1) {
            $data['updateRights'] = $this->rights['update'];
            $code = $this->uri->segment(3);
            $data['sectors'] = $this->GlobalModel->selectActiveData('sectorzonemaster');
            $data['tables'] = $this->GlobalModel->selectActiveData('tablemaster');
            $data['customer'] = $this->GlobalModel->selectQuery('customer.*', 'customer', array());
            $joinType = array('sectorzonemaster' => 'inner','branchmaster' => 'inner','customer' => 'inner', 'tablemaster' => 'inner');
            $join = array('sectorzonemaster' => 'sectorzonemaster.id=tablereservation.sectorCode','branchmaster' => 'branchmaster.code=tablereservation.branchCode','customer' => 'customer.code=tablereservation.customerCode', 'tablemaster' => 'tablemaster.code=tablereservation.tableNumber');
			$data['restable'] = $this->GlobalModel->selectQuery('tablereservation.*,tablemaster.tableNumber as tablenumber,branchmaster.branchName,sectorzonemaster.zoneName', 'tablereservation', array('tablereservation.code' => $code),array(), $join, $joinType);
            $data['branchCode']="";
			$data['branchName']="";
			if($this->branchCode!=""){
				$data['branchCode']=$this->branchCode;
				$data['branchName'] = $this->GlobalModel->selectDataById($this->branchCode, 'branchmaster')->result()[0]->branchName;
			}            
			//echo $this->load->view('dashboard/reservation/edit', $data, true);
			$this->load->view('dashboard/commonheader');
		     $this->load->view('dashboard/reservation/edit', $data);
			 $this->load->view('dashboard/footer'); 
        } else {
           $this->load->view('errors/norights.php');
        }
    }

    public function updateReservation()
    {
        $date = date('Y-m-d H:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $resCode = $this->input->post("resCode");
        $customerName = $this->input->post("modalcustomerName");
        $mobilephone = $this->input->post("modalmobilephone");
		$country=$this->input->post("countrycode");
		$clientPhone = $country . $mobilephone; 
		$branchCode=$this->input->post("branchCode");
		$sectorCode=$this->input->post("sector");
        $tableNumber = $this->input->post("tableNumber");
        $numberofpeople = $this->input->post("modalnumberofpeople");
        $resDate = $this->input->post("modaldate");
        $starttime = $this->input->post("modalstime");
        $endtime = $this->input->post("modaletime");
        $ip = $_SERVER['REMOTE_ADDR'];
        $addID = '1';
        $condition2 = array('customerCode' => $customerName, 'tableNumber' => $tableNumber, 'code!=' => $resCode);
        $result = $this->GlobalModel->checkDuplicateRecordNew($condition2, 'tablereservation');
        if ($result == true) {
            $response['status'] = false;
            $response['message'] = 'You have already booked the table.';
        } else {

            $data = array(
                'customerCode' => $customerName,
                'customerMobile' => $clientPhone,
				'branchCode'=>$branchCode,
				'sectorCode'=>$sectorCode, 
                'tableNumber' => $tableNumber,
                'noOfPeople' => $numberofpeople,
                'resDate' => $resDate,
                'startTime' => $starttime,
                'endTime' => $endtime,
                'editIP' => $ip,
                'editID' => $addID
            );
            $code = $this->GlobalModel->doEdit($data, 'tablereservation', $resCode);
            if ($code != 'false') {
                $txt = $resCode . " - " . $customerName . " " . $tableNumber . " Table booked is updated.";
                $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
                $this->GlobalModel->activity_log($activity_text);
                $response['status'] = true;
                $response['message'] = 'Table Booked  Updated Successfully';
            } else {
                $response['status'] = false;
                $response['message'] = 'Failed to Update Table Booked';
            }
        }
        echo json_encode($response);
    }

    public function view()
    {
        $code = $this->input->post('code');
        $data['sectors'] = $this->GlobalModel->selectActiveData('sectorzonemaster');
        $data['tables'] = $this->GlobalModel->selectActiveData('tablemaster');
        $data['customer'] = $this->GlobalModel->selectQuery('customer.*', 'customer', array());
        $joinType = array('sectorzonemaster' => 'inner','branchmaster' => 'inner','customer' => 'inner', 'tablemaster' => 'inner');
        $join = array('sectorzonemaster' => 'sectorzonemaster.id=tablereservation.sectorCode','branchmaster' => 'branchmaster.code=tablereservation.branchCode','customer' => 'customer.code=tablereservation.customerCode', 'tablemaster' => 'tablemaster.code=tablereservation.tableNumber');
		$data['restable'] = $this->GlobalModel->selectQuery('tablereservation.*,tablemaster.tableNumber as tablenumber,branchmaster.branchName,sectorzonemaster.zoneName', 'tablereservation', array('tablereservation.code' => $code),array(), $join, $joinType);
        $data['branchCode']="";
		$data['branchName']="";
		if($this->branchCode!=""){
			$data['branchCode']=$this->branchCode;
			$data['branchName'] = $this->GlobalModel->selectDataById($this->branchCode, 'branchmaster')->result()[0]->branchName;
		}   
		echo $this->load->view('dashboard/reservation/view', $data, true); 
    }

    public function deleteTable()
    {
        $code = $this->input->post('code');
        $date = date('Y-m-d H:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $loginrole = "";
        $ip = $_SERVER['REMOTE_ADDR'];
        $query = $this->GlobalModel->delete($code, 'tablereservation');
        if ($query) {
            $txt = $code . "Table Reservation is deleted.";
            $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
            $this->GlobalModel->activity_log($activity_text);
        }
        echo $query;
    }
	
	public function saveCustomer(){
		$date = date('Y-m-d H:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $cmpcode = $this->GlobalModel->getCompcode();
        $customerName = $this->input->post("name");
        $country = $this->input->post('countrycode');
        $phone = $this->input->post("phone");
		$email = $this->input->post("email");
		$clientPhone = $country . $phone; 
        $ip = $_SERVER['REMOTE_ADDR']; 
        $condition2 = array('email' => $email);
        $result = $this->GlobalModel->checkDuplicateRecordNew($condition2, 'customer');
		$checkPhone=$this->GlobalModel->checkDuplicateRecordNew(array('phone' => $clientPhone), 'customer');
		if ($result == true) {
            $response['status'] = false;
            $response['message'] = 'Duplicate Email';
        } else if($checkPhone==true){
		    $response['status'] = false;
            $response['message'] = 'Duplicate Phone';
		}
		else{
			$data = array(
                'name' => $customerName,
                'phone' => $clientPhone,
				'email'=>$email,
                'isActive' => 1
            );
            $data['addID'] = $addID;
            $data['addIP'] = $ip;
            $code = $this->GlobalModel->addNew($data, 'customer', 'CUST');
            $successMsg = "Customer Added Successfully";
            $errorMsg = "Failed To Add Customer";
            $txt = $code . " - " . $customerName . " customer is added.";

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
	
	public function getData(){
		$code = $this->input->post('code');
        $data = $this->GlobalModel->selectQuery('customer.*', 'customer', array("customer.code" => $code));
        if ($data) {
			 $response['status'] = true;
			 $number=$data->result_array()[0]['phone'];
			 $countryCode = substr($number, 0, 4);
			 $mobile=substr($number,4);
             $response['countryCode'] =  $countryCode;
			 $response['mobile'] =  $mobile;
        } else {
            $response['status'] = false;
             $response['countryCode'] =  "";
			 $response['mobile'] = "";
        }
        echo json_encode($response);
		
	}
}
