<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stock extends CI_Controller
{
	var $session_key;
	protected $rolecode,$branchCode;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form', 'url', 'html');
		$this->load->model('GlobalModel');
		$this->session_key = $this->session->userdata('key' . SESS_KEY);
		if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
			redirect('Login', 'refresh');
		}
		$this->rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		$this->branchCode = $this->session->userdata['logged_in' . $this->session_key]['branchCode'];
		$this->rights = $this->GlobalModel->getMenuRights('3.5', $this->rolecode);
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
			$data['branch'] = $this->GlobalModel->selectActiveData('branchmaster');
			$data['branchCode']="";
			$data['branchName']="";
			if($this->branchCode!=""){
				$data['branchCode']=$this->branchCode;
				$data['branchName'] = $this->GlobalModel->selectDataById($this->branchCode, 'branchmaster')->result()[0]->branchName;
			}
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/stock/list', $data);
			$this->load->view('dashboard/footer');
		}else {
			$this->load->view('errors/norights.php');
		}
	}

	public function getStockList()
	{
		$branch = $this->input->get('branch');
		if($this->branchCode!=""){
			$branch=$this->branchCode;
		}
		$tableName = "stockinfo";
		$search = $this->input->GET("search")['value'];
		$orderColumns = array("stockinfo.code,stockinfo.itemCode,itemmaster.itemEngName,unitmaster.unitName,unitmaster.unitSName,stockinfo.stock,branchmaster.branchName,stockinfo.addDate");
		$condition = array("stockinfo.isActive" => 1);
		if ($branch != "") {
			$condition['stockinfo.branchCode'] = $branch;
		}

		$orderBy = array('stockinfo.id' => 'DESC');
		$joinType = array('itemmaster' => 'inner', 'unitmaster' => 'inner', 'branchmaster' => 'inner');
		$join = array('itemmaster' => 'itemmaster.code=stockinfo.itemCode', 'unitmaster' => 'unitmaster.code=stockinfo.unitCode', 'branchmaster' => 'branchmaster.code=stockinfo.branchCode');
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$extraCondition = "";
		$like = array("branchmaster.branchName" => $search . "~both", "itemmaster.itemEngName" => $search . "~both", "unitmaster.unitSName" => $search . "~both", "stockinfo.code" => $search . "~both","stockinfo.stock" => $search . "~both");
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		//echo $this->db->last_query();
		$srno = $_GET['start'] + 1;
		if ($Records) {
			foreach ($Records->result() as $row) {
				$data[] = array(
					$srno,
					$row->code,
					$row->branchName,
					$row->itemEngName,
					$row->stock . "  " . $row->unitSName . ".",
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
}
