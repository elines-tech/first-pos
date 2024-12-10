<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Order extends CI_Controller
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
		$res = $this->GlobalModel->checkActiveSubscription();
		if ($res == "EXPIRED") {
			redirect('package', 'refresh');
		}
		$this->rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		$this->branchCode = $this->session->userdata['logged_in' . $this->session_key]['userBranch'];
		$this->rights = $this->GlobalModel->getMenuRights('6.1', $this->rolecode);
		if ($this->rights == '') {
			$this->load->view('errors/norights.php');
		}
	}

	public function listRecords()
	{
		$this->load->view('dashboard/header');
		$this->load->view('dashboard/order/list');
		$this->load->view('dashboard/footer');
	}
	public function getOrderList()
	{
		$branch="";
		if($this->branchCode!=""){
			$branch=$this->branchCode;
		}
		$dataCount = 0;
		$data = array();
		$tableName = "ordermaster";
		$search = $this->input->GET("search")['value'];
		$orderColumns = array("ordermaster.*,branchmaster.branchName,usermaster.userEmpNo as cashier");
		$condition = array("ordermaster.branchCode"=>$branch);
		$orderBy = array('ordermaster.id' => 'DESC');
		$joinType = array('branchmaster' => 'inner', 'usermaster' => 'inner');
		$join = array('branchmaster' => 'branchmaster.code=ordermaster.branchCode', 'usermaster' => 'usermaster.code=ordermaster.addID');
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$extraCondition = "";
		$like = array("ordermaster.code" => $search . "~both", "branchmaster.branchName" => $search . "~both", "usermaster.userEmpNo" => $search . "~both", "ordermaster.paymentMode" => $search . "~both", "ordermaster.counter" => $search . "~both", "ordermaster.name" => $search . "~both", "ordermaster.phone" => $search . "~both", "ordermaster.totalItems" => $search . "~both", "ordermaster.totalPayable" => $search . "~both");
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		$srno = $_GET['start'] + 1;
		if ($Records) {
			foreach ($Records->result() as $row) {
				$paymentMode = "<span class='badge bg-success'>" . ucwords($row->paymentMode) . "</span>";
				$actionHtml = '<a href="' . base_url() . 'order/view/' . $row->code . '" class="btn btn-success btn-sm cursor_pointer"><i id="view" title="View" class="fa fa-eye"></i></a>';
				$data[] = array(
					$srno,
					$row->branchName,
					date('d/m/Y h:i A', strtotime($row->orderDate)),
					$row->code . '<br>' . $paymentMode,
					$row->cashier,
					$row->counter,
					$row->name . ' - ' . $row->phone,
					$row->totalItems,
					$row->totalPayable,
					$actionHtml
				);
				$srno++;
			}
			$dataCount = sizeof($this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, array(), '', '', '', $extraCondition)->result());
		}
		$output = array(
			"draw"			  =>     intval($_GET["draw"]),
			"recordsTotal"    =>      $dataCount,
			"recordsFiltered" =>     $dataCount,
			"data"            =>     $data
		);
		echo json_encode($output);
	}

	public function view()
	{
		$code = $this->uri->segment(3);
		$data['query'] = $this->GlobalModel->selectDataById($code, 'ordermaster');
		$this->load->view('dashboard/header');
		$this->load->view('dashboard/order/view', $data);
		$this->load->view('dashboard/footer');
	}
	public function getOrderDetails()
	{
		$orderCode = $this->input->get('orderCode');
		$lang = $this->session->userdata['logged_in' . $this->session_key]['lang'];
		$tableName = 'orderlineentries';
		$orderColumns = array("orderlineentries.*,productvariants.variantName,unitmaster.unitSName,productmaster.productImage");
		$condition = array('orderlineentries.orderCode' => $orderCode);
		$orderBy = array('orderlineentries.id' => 'asc');
		$joinType = array('productmaster' => 'inner', 'productvariants' => 'left', 'unitmaster' => 'inner');
		$join = array('productmaster' => 'productmaster.code=orderlineentries.productCode', 'productvariants' => 'productvariants.code=orderlineentries.variantCode', 'unitmaster' => 'unitmaster.code=orderlineentries.unit');
		$groupByColumn = array('orderlineentries.productCode');
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$srno = $offset + 1;
		$extraCondition = "";
		$like = array();
		$data = array();
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		if ($Records) {
			foreach ($Records->result() as $row) {
				$prdExtras = '';
				$prdName = json_decode($row->productName);
				$productName = "";
				if (!empty($prdName)) {
					if ($lang == "urdu") {
						$productName = $prdName->productUrduName;
					} else if ($lang == "arabic") {
						$productName = $prdName->productArbName;
					} else if ($lang == "hindi") {
						$productName = $prdName->productHinName;
					} else {
						$productName = $prdName->productEngName;
					}
				}

				$productName = $productName . ' - ' . $row->variantName;
				$start = '<div class="d-flex align-items-center">';
				$end = ' <h6 class="m-b-0">' . $productName . '</h6></div>';
				$path = base_url() . 'assets/food.png';
				if (file_exists($row->productImage)) {
					$path = base_url() . $row->productImage;
				}
				$productPhoto = '<div style="margin-right:10px;"><img src="' . $path . '" alt="product" class="circle" width="45"></div><div class="">';
				$productName = $start . $productPhoto . $end;
				$data[] = array($srno, $row->barcode, $productName, $row->qty . ' ' . $row->unitSName, $row->price, $row->amount, $row->discountPrice, $row->tax, $row->totalPrice);
				$srno++;
			}
		}
		$dataCount = sizeOf($this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, '', '', $groupByColumn, $extraCondition)->result());
		$output = array("draw" => intval($_GET["draw"]), "recordsTotal" => $dataCount, "recordsFiltered" => $dataCount, "data" => $data);
		echo json_encode($output);
	}
}
