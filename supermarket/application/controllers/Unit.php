<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Unit extends CI_Controller
{
	var $session_key;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form', 'url', 'html');
		$this->load->model('GlobalModel');
		$this->load->library('form_validation');
		$this->session_key = $this->session->userdata('key' . SESS_KEY);
		if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
			redirect('Login', 'refresh');
		}
		$res = $this->GlobalModel->checkActiveSubscription();
		if ($res == "EXPIRED") {
			redirect('package', 'refresh');
		}
		$rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		$this->rights = $this->GlobalModel->getMenuRights('7.4', $rolecode);
		if ($this->rights == '') {
			$this->load->view('errors/norights.php');
		}
	}
	public function listRecords()
	{
		if ($this->rights != '' && $this->rights['view'] == 1) {
			$data['insertRights'] = $this->rights['insert'];
			$data['baseunit'] = $this->GlobalModel->selectActiveData('baseunitmaster');
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/unit/list', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}
	public function getUnitList()
	{
		$tableName = "unitmaster";
		$search = $this->input->GET("search")['value'];
		$orderColumns = array("unitmaster.code,baseunitmaster.baseunitName,unitmaster.unitRounding,unitmaster.unitName,unitmaster.unitSName,unitmaster.conversionFactor,unitmaster.isActive");
		$condition = array();
		$orderBy = array('unitmaster.id' => 'DESC');
		$joinType = array('baseunitmaster' => 'inner');
		$join = array('baseunitmaster' => 'baseunitmaster.code=unitmaster.baseUnitCode');
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start"); 
		$extraCondition = " (unitmaster.isDelete=0 OR unitmaster.isDelete IS NULL)";
		$like = array("unitmaster.conversionFactor" => $search . "~both","unitmaster.unitRounding" => $search . "~both","baseunitmaster.baseunitName" => $search . "~both","unitmaster.code" => $search . "~both", "unitmaster.unitName" => $search . "~both", "unitmaster.unitSName" => $search . "~both");
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
					$actionHtml .= '<a class="edit_unit btn btn-success btn-sm cursor_pointer m-1" data-seq="' . $row->code . '" data-type="1"><i id="edt" title="View" class="fa fa-eye"></i></a>';
				}
				if ($this->rights != '' && $this->rights['update'] == 1) {
					$actionHtml .= '<a class="edit_unit btn btn-info btn-sm cursor_pointer m-1" data-seq="' . $row->code . '" data-type="2"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
				}
				if ($this->rights != '' && $this->rights['delete'] == 1) {
					$actionHtml .= '<a class="btn btn-sm btn-danger delete_unit m-1" data-seq="' . $row->code . '"><i id="dlt" title="Delete" class="fa fa-trash"></i></a>';
				}

				$data[] = array(
					$srno,
					$row->code,
					$row->baseunitName,
					$row->unitName,
					$row->unitSName,
					$row->unitRounding,
					$row->conversionFactor,
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

	public function saveUnit()
	{
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$loginrole = "";
		$baseUnit = trim($this->input->post("baseUnit"));
		$unitName = trim($this->input->post("unitName"));
		$unitSName = $this->input->post("unitSName");
		$description = $this->input->post("description");
		$unitCode = $this->input->post("code");
		$rounding = $this->input->post("rounding");
		$conversionFactor = $this->input->post("conversionFactor");
		$isActive = $this->input->post("isActive");
		$ip = $_SERVER['REMOTE_ADDR'];
		$condition2 = array('LOWER(unitName)' => strtolower($unitName));
		if ($unitCode != '') {
			$condition2['unitmaster.code!='] = $unitCode;
		}
		$result = $this->GlobalModel->checkDuplicateRecordNew($condition2, 'unitmaster');
		if ($result == true) {
			$response['status'] = false;
			$response['message'] = 'Duplicate Unit';
		} else {
			$data = array(
				'unitName' => $unitName,
				'unitSName' => $unitSName,
				'unitDesc' => $description,
				'unitRounding' => $rounding,
				'baseUnitCode' => $baseUnit,
				'conversionFactor' => $conversionFactor,
				'isActive' => $isActive
			);
			if ($unitCode != '') {
				$data['editID'] = $addID;
				$data['editIP'] = $ip;
				$code = $this->GlobalModel->doEdit($data, 'unitmaster', $unitCode);
				$code = $unitCode;
				$successMsg = "Unit Updated Successfully";
				$errorMsg = "Failed To Update Unit";
				$txt = $code . " - " . $unitName . " unit is updated.";
			} else {
				$data['addID'] = $addID;
				$data['addIP'] = $ip;
				$code = $this->GlobalModel->addWithoutYear($data, 'unitmaster', 'UOM');
				$successMsg = "Unit Added Successfully";
				$errorMsg = "Failed To Add Unit";
				$txt = $code . " - " . $unitName . " unit is added.";
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

	public function editUnit()
	{
		if ($this->rights != '' && ($this->rights['update'] == 1 || $this->rights['view'] == 1)) {
			$code = $this->input->post('code');
			$unitQuery = $this->GlobalModel->selectQuery("unitmaster.conversionFactor,unitmaster.baseUnitCode,unitmaster.unitRounding,unitmaster.code,unitmaster.unitName,unitmaster.unitSName,unitmaster.unitDesc,unitmaster.isActive", 'unitmaster', array('unitmaster.code' => $code));
			if ($unitQuery) {
				$result = $unitQuery->result_array()[0];
				$data['status'] = true;
				$data['code'] = $result['code'];
				$data['unitName'] = $result['unitName'];
				$data['unitSName'] = $result['unitSName'];
				$data['description'] = $result['unitDesc'];
				$data['baseUnitCode'] = $result['baseUnitCode'];
				$data['isActive'] = $result['isActive'];
				$baseUnitCode = $result['baseUnitCode'];
				$data['unitRounding'] = $result['unitRounding'];
				$data['conversionFactor'] = $result['conversionFactor'];

				$unitHtml = '';
				$baseunit = $this->GlobalModel->selectQuery('baseunitmaster.*', 'baseunitmaster', array('baseunitmaster.isActive' => 1));
				//echo $this->db->last_query(); 
				if ($baseunit && $baseunit->num_rows() > 0) {
					$unitHtml .= '<option value="">Select</option>';
					foreach ($baseunit->result() as $base) {
						if ($baseUnitCode == $base->code) {
							$unitHtml .= '<option value="' . $base->code . '" id="' . $base->baseunitName . '" selected>' . $base->baseunitName . '</option>';
						} else {
							$unitHtml .= '<option value="' . $base->code . '" id="' . $base->baseunitName . '">' . $base->baseunitName . '</option>';
						}
					}
				}
				$response['baseunit'] = $unitHtml;
			} else {
				$data['status'] = false;
			}
		} else {
			$data['status'] = false;
		}
		echo json_encode($data);
	}

	public function deleteUnit()
	{
		if ($this->rights != '' && $this->rights['delete'] == 1) {
			$code = $this->input->post('code');
			$date = date('Y-m-d H:i:s');
			$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
			$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
			$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
			$loginrole = "";
			$ip = $_SERVER['REMOTE_ADDR'];
			$unitName = $this->GlobalModel->selectDataById($code, 'unitmaster')->result()[0]->unitName;
			$query = $this->GlobalModel->delete($code, 'unitmaster');
			if ($query) {
				$txt = $code . " - " . $unitName . " unit is deleted.";
				$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
				$this->GlobalModel->activity_log($activity_text);
			}
			echo $query;
		}
	}
}
