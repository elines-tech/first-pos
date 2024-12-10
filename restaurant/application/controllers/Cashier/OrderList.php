<?php
defined('BASEPATH') or exit('No direct script access allowed');

class OrderList extends CI_Controller
{
	var $session_key;
	protected $branchCode;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form', 'url', 'html');
		$this->load->model('GlobalModel');
		$this->session_key = $this->session->userdata('cash_key' . CASH_SESS_KEY);
		$rolecode = $this->session->userdata['cash_logged_in' . $this->session_key]['rolecode'];
		if (!isset($this->session->userdata['cash_logged_in' . $this->session_key]['code'])) {
			redirect('Cashier/Login', 'refresh');
		}
		$this->branchCode = trim($this->session->userdata['cash_logged_in' . $this->session_key]['branchCode']);
		$res = $this->GlobalModel->checkActiveSubscription();
		if ($res == "EXPIRED") {
			$this->load->view('errors/exppackage.php');
		}
	}

	public function getOrders()
	{
		$this->load->view('cashier/header');
		$this->load->view('cashier/orderList/list');
		$this->load->view('cashier/footer');
	}
	public function getOrderList()
	{
		$data = [];
		$dataCount = 0;
		$cashierCode = $this->session->userdata['cash_logged_in' . $this->session_key]['code'];
		$tableName = "ordermaster";
		$search = $this->input->GET("search")['value'];
		$orderColumns = array("ordermaster.*,branchmaster.branchName,tablemaster.tableNumber,sectorzonemaster.zoneName");
		$condition = array("ordermaster.addID" => $cashierCode, "ordermaster.branchCode" => $this->branchCode);
		$orderBy = array('ordermaster.id' => 'DESC');
		$joinType = array('branchmaster' => 'inner', 'tablemaster' => 'inner', 'sectorzonemaster' => 'inner');
		$join = array('branchmaster' => 'branchmaster.code=ordermaster.branchCode', 'tablemaster' => 'tablemaster.code=ordermaster.tableNumber', 'sectorzonemaster' => 'sectorzonemaster.id=ordermaster.tableSection');
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$extraCondition = " (ordermaster.isDelete=0 OR ordermaster.isDelete IS NULL)";
		$like = array("ordermaster.code" => $search . "~both");
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		$srno = $_GET['start'] + 1;
		if ($Records) {
			foreach ($Records->result() as $row) {
				$paymentMode = "<span class='badge bg-success'>" . $row->paymentMode . "</span>";
				$actionHtml = '<a href="' . base_url() . 'Cashier/orderList/view/' . $row->code . '" class="btn btn-success btn-sm cursor_pointer"><i id="view" title="View" class="fa fa-eye"></i></a>';

				$data[] = array(
					$srno,
					date('d/m/Y h:i A', strtotime($row->addDate)),
					$row->code . '<br>' . $paymentMode,
					$row->branchName,
					$row->tableNumber . ' / ' . $row->zoneName,
					$row->custName . ' - ' . $row->custPhone,
					$row->grandTotal,
					$row->remark,
					$actionHtml
				);
				$srno++;
			}
			$dataCount = sizeof($this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, array(), '', '', '', $extraCondition)->result());
		}
		$output = array(
			"draw"			  =>     intval($_GET["draw"]),
			"recordsTotal"    =>     $dataCount,
			"recordsFiltered" =>     $dataCount,
			"data"            =>     $data
		);
		echo json_encode($output);
	}

	public function view()
	{
		$code = $this->uri->segment(4);
		$data['query'] = $this->GlobalModel->selectDataById($code, 'ordermaster');
		$this->load->view('cashier/header');
		$this->load->view('cashier/orderList/view', $data);
		$this->load->view('cashier/footer');
	}
	public function getOrderDetails()
	{
		$orderCode = $this->input->get('orderCode');
		$lang = $this->session->userdata['cash_logged_in' . $this->session_key]['lang'];
		$tableName = 'orderlineentries';
		$orderColumns = array("orderlineentries.orderCode,orderlineentries.addons,orderlineentries.comboProducts,orderlineentries.productCode,orderlineentries.productPrice,orderlineentries.productQty,orderlineentries.totalPrice,orderlineentries.isActive,productmaster.productEngName,productmaster.productArbName,productmaster.productHinName,productmaster.productUrduName,productmaster.productImage");
		$condition = array('orderlineentries.orderCode' => $orderCode);
		$orderBy = array('orderlineentries.id' => 'asc');
		$joinType = array('productmaster' => 'left');
		$join = array('productmaster' => 'productmaster.code=orderlineentries.productCode');
		$groupByColumn = array('orderlineentries.productCode');
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$srno = $offset + 1;
		$extraCondition = "orderlineentries.isActive=1";
		$like = array();
		$data = array();
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		if ($Records) {
			foreach ($Records->result() as $row) {
				$prdExtras = '';
				if (strtolower($lang) == "urdu") {
					$productName = $row->productUrduName;
				} else if (strtolower($lang) == "arabic") {
					$productName = $row->productArbName;
				} else if (strtolower($lang) == "hindi") {
					$productName = $row->productHinName;
				} else {
					$productName = $row->productEngName;
				}
				$productImage = $row->productImage;
				if ($row->addons != '[]') {
					$prdExtras .= '<b>Addons: </b><br>';
					$extras = json_decode($row->addons, true);
					foreach ($extras as $add) {
						$extrasText = $this->GlobalModel->selectQuery('productextras.code,productextras.custprice,unitmaster.unitSName', 'productextras', array('productextras.isActive' => 1, 'productextras.code' => $add['addonCode']), array(), array('unitmaster' => 'unitmaster.code=productextras.itemUom'), array('unitmaster' => 'inner'));
						if ($extrasText) {
							$result = $extrasText->result()[0];
							$extraCode = "'" . $result->code . "'";
							$prdExtras .= $add['addonQty'] . "  " . $add['addonTitle'] . "  " . $add['addonPrice'] . '<br>';
						}
					}
				}
				if ($row->comboProducts != '[]') {
					$prdExtras .= '<b>Includes: </b><br>';
					$comboProducts = json_decode($row->comboProducts, true);
					$comboQuantity = $comboProducts[0]['productQty'];
					$comboDetails = $this->GlobalModel->selectQuery('productcombo.productComboName,productcombo.productComboPrice,productcombo.productComboImage', 'productcombo', array('productcombo.code' => $row->productCode))->result()[0];
					$productImage = base_url() . 'assets/food.png';
					if (file_exists($comboDetails->productComboImage) && $comboDetails->productComboImage != '' && $comboDetails->productComboImage != NULL) {
						$productImage = $comboDetails->productComboImage;
					}
					$comboProductDetails = $this->GlobalModel->selectQuery('productcombolineentries.productPrice,productmaster.productEngName', 'productcombolineentries', array('productcombolineentries.productComboCode' => $row->productCode), array(), array('productmaster' => 'productmaster.code=productcombolineentries.productCode'), array('productmaster' => 'inner'));
					if ($comboProductDetails) {
						foreach ($comboProductDetails->result() as $cmbl) {
							$prdExtras .= '<span class="badge bg-success" style="margin-right:2px;">' . $cmbl->productEngName . '</span>';
						}
					}
					$productName = $comboDetails->productComboName;
				}
				$start = '<div class="d-flex align-items-center">';
				$end = ' <h6 class="m-b-0">' . $productName . '</h6>' . $prdExtras . '</div></div>';
				$path = base_url() . 'assets/food.png';
				if (file_exists($productImage)) {
					$path = base_url() . $productImage;
				}
				$productPhoto = '<div style="margin-right:10px;"><img src="' . $path . '" alt="product" class="circle" width="45"></div><div class="">';
				$productName = $start . $productPhoto . $end;
				$data[] = array($srno, $productName, $row->productPrice / $row->productQty, $row->productQty, $row->totalPrice);
				$srno++;
			}
		}
		$dataCount = sizeOf($this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, '', '', $groupByColumn, $extraCondition)->result());
		$output = array("draw" => intval($_GET["draw"]), "recordsTotal" => $dataCount, "recordsFiltered" => $dataCount, "data" => $data);
		echo json_encode($output);
	}
}
