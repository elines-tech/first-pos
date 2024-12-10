<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tax extends CI_Controller
{
	var $session_key;
	protected $rights;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form', 'url', 'html');
		$this->load->model('GlobalModel');
		$this->load->library('form_validation');
		$this->session_key = $this->session->userdata('key' . SESS_KEY);
		$rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
			redirect('Login', 'refresh');
		}
		$this->rights = $this->GlobalModel->getMenuRights('8.7', $rolecode);
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
			$data['taxes'] = $this->GlobalModel->selectActiveData('taxes');
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/tax/list', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function getTaxList()
	{
		$tableName = "taxes";
		$search = $this->input->GET("search")['value'];
		$orderColumns = array("taxes.code,taxes.taxName,taxPer,taxes.isActive");
		$condition = array();
		$orderBy = array('taxes.id' => 'DESC');
		$joinType = array();
		$join = array();
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$extraCondition = " (taxes.isDelete=0 OR taxes.isDelete IS NULL)";
		$like = array("taxes.taxName" => $search . "~both", "taxes.code" => $search . "~both", "taxes.taxPer" => $search . "~both");
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		$srno = $_GET['start'] + 1;
		if ($Records) {
			foreach ($Records->result() as $row) {
				 
				if ($row->isActive == 1) {
					$status = "<span class='badge bg-success'>Active</span>";
				} else {
					$status = "<span class='badge bg-danger'>Inactive</span>";
				}

				$actionHtml = '<div class="d-flex">';
				if ($this->rights != '' && $this->rights['update'] == 1) {
					$actionHtml .= '<a class="btn btn-sm btn-info mx-1 cursor_pointer edit_tax" data-seq="' . $row->code . '"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
				}
				if ($this->rights != '' && $this->rights['delete'] == 1) {
					$actionHtml .= '<a class="btn btn-sm btn-danger mx-1 delete_tax" data-seq="' . $row->code . '" ><i id="dlt" title="Delete" class="fa fa-trash"></i></a>';
				}
				$actionHtml .= '</div>';

				$data[] = array(
					$srno,
					$row->code,
					$row->taxName,
					$row->taxPer,
					$status,
					$actionHtml
				);
				$srno++;
			}
			$dataCount = sizeof($this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, array(), '', '', '', $extraCondition)->result());
			$output = array(
				"draw"			  =>     intval($_GET["draw"]),
				"recordsTotal"    =>      $dataCount,
				"recordsFiltered" =>     $dataCount,
				"data"            =>     $data
			);
			echo json_encode($output);
		} else {
			$dataCount = 0;
			$data = array();
			$output = array(
				"draw"			  =>     intval($_GET["draw"]),
				"recordsTotal"    =>     $dataCount,
				"recordsFiltered" =>     $dataCount,
				"data"            =>     $data
			);
			echo json_encode($output);
		}
	}

	public function saveTax()
	{
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$loginrole = "";
		$taxName = trim($this->input->post("taxName"));
		$taxPer = $this->input->post("taxPer");
		$taxCode = $this->input->post("taxCode");
		$isActive = $this->input->post("isActive");
		$ip = $_SERVER['REMOTE_ADDR'];
		$addID = '1';
		$condition2 = array('LOWER(taxName)' => strtolower($taxName));
		if ($taxCode != '') {
			$condition2['taxes.code!='] = $taxCode;
		}
		$result = $this->GlobalModel->checkDuplicateRecordNew($condition2, 'taxes');
		if ($result == true) {
			$response['status'] = false;
			$response['message'] = 'Duplicate Tax Name';
			echo json_encode($response);
			exit;
		} else {
			$data = array(
				'taxName' => $taxName,
				'taxPer' => $taxPer,
				'isActive' => $isActive
			);
			if ($taxCode != '') {
				$data['editID'] = $addID;
				$data['editIP'] = $ip;
				$code = $this->GlobalModel->doEdit($data, 'taxes', $taxCode);
				$code = $taxCode;
				$successMsg = "Tax Rates Updated Successfully";
				$errorMsg = "Failed To Update Tax Rates";
				$txt = $code . " - " . $taxName . " Tax Rate is updated.";
			} else {
				$data['addID'] = $addID;
				$data['addIP'] = $ip;
				$code = $this->GlobalModel->addWithoutYear($data, 'taxes', 'TAC');
				$successMsg = "Tax Rates Added Successfully";
				$errorMsg = "Failed To Add Tax Rates";
				$txt = $code . " - " . $taxName . " Tax Rate is updated.";
			}
			if ($code != 'false') {
				$taxHtml = '';
				$taxes = $this->GlobalModel->selectActiveData('taxes');
				if ($taxes) {
					foreach ($taxes->result() as $tr) {
						$taxHtml .= "<option value='" . $tr->code . "'>" . $tr->taxName . "</option>";
					}
				}
				$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
				$this->GlobalModel->activity_log($activity_text);
				$response['status'] = true;
				$response['message'] = $successMsg;
				$response['taxes'] = $taxHtml;
			} else {
				$response['status'] = false;
				$response['message'] = $errorMsg;
			}			
			echo json_encode($response);
			exit;
		}		
	}

	public function editTax()
	{
		$code = $this->input->post('taxCode');
		$taxQuery = $this->GlobalModel->selectQuery('taxes.code,taxes.taxName,taxes.taxPer,taxes.isActive', 'taxes', array('taxes.code' => $code));
		if ($taxQuery) {
			$result = $taxQuery->result_array()[0];
			$data['status'] = true;
			$data['code'] = $result['code'];
			$data['taxName'] = $result['taxName'];
			$data['taxPer'] = $result['taxPer'];
			$data['isActive'] = $result['isActive'];
		} else {
			$data['status'] = false;
		}
		echo json_encode($data);
	}

	public function checkTax()
	{
		$code = $this->input->post('code');
		$cnt = 0;
		$checkGroup = $this->GlobalModel->selectQuery('taxgroupmaster.code,taxgroupmaster.taxes', 'taxgroupmaster', array('taxgroupmaster.isActive' => 1));
		if ($checkGroup) {
			foreach ($checkGroup->result() as $ch) {
				$taxArr = json_decode($ch->taxes, true);
				if (in_array($code, $taxArr)) {
					$cnt++;
					break;
				}
			}
		}
		$response['cnt'] = $cnt;
		echo json_encode($response);
	}

	public function deleteTax()
	{
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$loginrole = "";
		$code = $this->input->post('code');
		$ip = $_SERVER['REMOTE_ADDR'];
		$taxName = $this->GlobalModel->selectDataById($code, 'taxes')->result()[0]->taxName;

		$query = $this->GlobalModel->delete($code, 'taxes');
		if ($query) {
			$txt = $code . " - " . $taxName . " Tax rate is deleted.";
			$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
			$this->GlobalModel->activity_log($activity_text);
		}
		$taxHtml = '';
		$taxes = $this->GlobalModel->selectActiveData('taxes');
		if ($taxes) {
			foreach ($taxes->result() as $tr) {
				$taxHtml .= "<option value='" . $tr->code . "'>" . $tr->taxName . "</option>";
			}
		}
		$data['status'] = true;
		$data['taxes'] = $taxHtml;
		echo json_encode($data);
	}

	public function getTaxGroupList()
	{
		$tableName = "taxgroupmaster";
		$search = $this->input->GET("search")['value'];
		$orderColumns = array("taxgroupmaster.code,taxgroupmaster.taxGroupName,taxgroupmaster.taxes,taxgroupmaster.isActive");
		$condition = array();
		$orderBy = array('taxgroupmaster.id' => 'DESC');
		$joinType = array();
		$join = array();
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$extraCondition = " (taxgroupmaster.isDelete=0 OR taxgroupmaster.isDelete IS NULL)";
		$like = array("taxgroupmaster.code" => $search . "~both", "taxgroupmaster.taxGroupName" => $search . "~both", "taxgroupmaster.taxGroupRef" => $search . "~both");
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		$srno = $_GET['start'] + 1;
		if ($Records) {
			foreach ($Records->result() as $row) {
				$taxNames = '';
				$taxes = json_decode($row->taxes, true);

				$getTaxes = $this->GlobalModel->directQuery("select ifnull(group_concat(taxes.taxName),'') as taxName from taxes where taxes.code in('" . implode("','", $taxes) . "')");
				if (!empty($getTaxes)) {
					$taxNames = $getTaxes[0]['taxName'];
				}

				if ($row->isActive == 1) {
					$status = "<span class='badge bg-success'>Active</span>";
				} else {
					$status = "<span class='badge bg-danger'>Inactive</span>";
				}

				$actionHtml = '<div class="d-flex">';
				if ($this->rights != '' && $this->rights['update'] == 1) {
					$actionHtml .= '<a class="btn btn-sm btn-info cursor_pointer edit_group mx-1" data-seq="' . $row->code . '"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
				}
				if ($this->rights != '' && $this->rights['delete'] == 1) {
					$actionHtml .= '<a class="btn btn-sm btn-danger cursor_pointer delete_group mx-1"  data-seq="' . $row->code . '" ><i id="dlt" title="Delete" class="fa fa-trash"></i></a>';
				}
				$actionHtml .= '</div>';

				$data[] = array(
					$srno,
					$row->code,
					$row->taxGroupName,
					$taxNames,
					$status,
					$actionHtml
				);
				$srno++;
			}
			$dataCount = sizeof($this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, array(), '', '', '', $extraCondition)->result());
			$output = array(
				"draw"			  =>     intval($_GET["draw"]),
				"recordsTotal"    =>      $dataCount,
				"recordsFiltered" =>     $dataCount,
				"data"            =>     $data
			);
			echo json_encode($output);
		} else {
			$dataCount = 0;
			$data = array();
			$output = array(
				"draw"			  =>     intval($_GET["draw"]),
				"recordsTotal"    =>     $dataCount,
				"recordsFiltered" =>     $dataCount,
				"data"            =>     $data
			);
			echo json_encode($output);
		}
	}
	public function saveTaxGroup()
	{
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$taxGroupName = trim($this->input->post("taxGroupName"));
		$taxGroupRef = trim($this->input->post("taxGroupRef"));
		$taxes = json_encode($this->input->post("taxes"));
		$taxGroupCode = $this->input->post("taxGroupCode");
		$isActive = $this->input->post("isActive");
		$ip = $_SERVER['REMOTE_ADDR'];
		$addID = '1';
		$condition2 = array('LOWER(taxGroupName)' => strtolower($taxGroupName));
		if ($taxGroupCode != '') {
			$condition2['taxgroupmaster.code!='] = $taxGroupCode;
		}
		$result = $this->GlobalModel->checkDuplicateRecordNew($condition2, 'taxgroupmaster');
		if ($result == true) {
			$response['status'] = false;
			$response['message'] = 'Duplicate Tax Group Name';
		} else {
			$data = array(
				'taxGroupName' => $taxGroupName,
				'taxes' => $taxes,
				'taxGroupRef' => $taxGroupRef,
				'isActive' => $isActive
			);
			if ($taxGroupCode != '') {
				$data['editID'] = $addID;
				$data['editIP'] = $ip;
				$code = $this->GlobalModel->doEdit($data, 'taxgroupmaster', $taxGroupCode);
				$code = $taxGroupCode;
				$successMsg = "Tax Group Updated Successfully";
				$errorMsg = "Failed To Update Tax Group";
				$txt = $code . " - " . $taxGroupName . " Tax Group is updated.";
			} else {
				$data['addID'] = $addID;
				$data['addIP'] = $ip;
				$code = $this->GlobalModel->addWithoutYear($data, 'taxgroupmaster', 'TAG');
				$successMsg = "Tax Group Added Successfully";
				$errorMsg = "Failed To Add Tax Group";
				$txt = $code . " - " . $taxGroupName . " Tax Group is added.";
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

	public function editTaxGroup()
	{
		$code = $this->input->post('code');
		$taxQuery = $this->GlobalModel->selectQuery('taxgroupmaster.code,taxgroupmaster.taxGroupName,taxgroupmaster.taxGroupRef,taxgroupmaster.taxes,taxgroupmaster.isActive', 'taxgroupmaster', array('taxgroupmaster.code' => $code));
		if ($taxQuery) {
			$result = $taxQuery->result_array()[0];
			$data['status'] = true;
			$data['taxGroupCode'] = $result['code'];
			$data['taxGroupName'] = $result['taxGroupName'];
			$data['taxGroupRef'] = $result['taxGroupRef'];
			$data['taxes'] = json_decode($result['taxes']);
			$data['isActive'] = $result['isActive'];
		} else {
			$data['status'] = false;
		}
		echo json_encode($data);
	}

	public function deleteTaxGroup()
	{
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$loginrole = "";
		$code = $this->input->post('code');
		$ip = $_SERVER['REMOTE_ADDR'];
		$groupName = $this->GlobalModel->selectDataById($code, 'taxgroupmaster')->result()[0]->taxName;
		$query = $this->GlobalModel->delete($code, 'taxgroupmaster');
		if ($query) {
			$txt = $code . " - " . $groupName . " Tax group is deleted.";
			$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
			$this->GlobalModel->activity_log($activity_text);
		}
		echo $query;
	}
}
