<?php
defined('BASEPATH') or exit('No direct script access allowed');

class OrderList extends CI_Controller
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
		$this->rights = $this->GlobalModel->getMenuRights('6.2', $this->rolecode);
		if ($this->rights == '') {
			$this->load->view('errors/norights.php');
		}
		$res = $this->GlobalModel->checkActiveSubscription();
        if ($res == "EXPIRED") {
            redirect('package', 'refresh');
        }
	}

	public function getOrders()
	{
		$this->load->view('dashboard/header');
		$this->load->view('dashboard/orderList/list');
		$this->load->view('dashboard/footer');
	}
	public function getOrderList()
	{
		$branch="";
		if($this->branchCode!=""){
			$branch=$this->branchCode;
		}
		$tableName = "ordermaster";
		$search = $this->input->GET("search")['value'];
		$orderColumns = array("ordermaster.*,branchmaster.branchName,tablemaster.tableNumber,sectorzonemaster.zoneName");
		$condition = array("ordermaster.branchCode"=>$branch);
		$orderBy = array('ordermaster.id' => 'DESC');
		$joinType = array('branchmaster' => 'inner', 'tablemaster' => 'inner', 'sectorzonemaster' => 'inner');
		$join = array('branchmaster' => 'branchmaster.code=ordermaster.branchCode', 'tablemaster' => 'tablemaster.code=ordermaster.tableNumber', 'sectorzonemaster' => 'sectorzonemaster.id=ordermaster.tableSection');
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$extraCondition = " (ordermaster.isDelete=0 OR ordermaster.isDelete IS NULL)";
		$like = array("ordermaster.paymentMode" => $search . "~both","ordermaster.remark" => $search . "~both","ordermaster.grandTotal" => $search . "~both","ordermaster.custPhone" => $search . "~both","ordermaster.custName" => $search . "~both","ordermaster.code" => $search . "~both","branchmaster.branchName" => $search . "~both","tablemaster.tableNumber" => $search . "~both","sectorzonemaster.zoneName" => $search . "~both");
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		$srno = $_GET['start'] + 1;
		if ($Records) {
			foreach ($Records->result() as $row) {
				$paymentMode = "<span class='badge bg-success'>" . $row->paymentMode . "</span>";
				$actionHtml = '';
				if ($this->rights != '' && $this->rights['view'] == 1) {
					$actionHtml = '<a id="view" href="' . base_url() . 'orderList/view/' . $row->code . '" class="btn btn-success btn-sm cursor_pointer"><i id="view" title="View" class="fa fa-eye"></i></a>';
				}
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

	public function view()
	{
		if ($this->rights != '' && $this->rights['view'] == 1) {
			$code = $this->uri->segment(3);
			$data['query'] = $this->GlobalModel->selectDataById($code, 'ordermaster');
			$this->load->view('dashboard/commonheader');
			$this->load->view('dashboard/orderList/view', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}
	public function getOrderDetails()
	{
		$orderCode = $this->input->get('orderCode');
		$lang = $this->session->userdata['logged_in' . $this->session_key]['lang'];
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
				if ($lang == "urdu") {
					$productName = $row->productUrduName;
				} else if ($lang == "arabic") {
					$productName = $row->productArbName;
				} else if ($lang == "hindi") {
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
				if($productImage!=""){
					if (file_exists($productImage)) {
						$path = base_url() . $productImage;
					}
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
