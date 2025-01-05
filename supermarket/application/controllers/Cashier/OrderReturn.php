<?php
defined('BASEPATH') or exit('No direct script access allowed');

class OrderReturn extends CI_Controller
{
	var $session_key;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('GlobalModel');
		$this->session_key = $this->session->userdata('cash_key' . CASH_SESS_KEY);
		if (!isset($this->session->userdata['cash_logged_in' . $this->session_key]['code'])) {
			redirect('Cashier/Login', 'refresh');
		}
		$res = $this->GlobalModel->checkActiveSubscription();
		if ($res == "EXPIRED") {
			$this->load->view('errors/exppackage.php');
		}
	}

	public function listRecords()
	{
		$data['customer'] = $this->GlobalModel->selectQuery('customer.*', 'customer', array(), array(), array(), array(), array(), '', '', array(), '');
		$data['order'] = $this->GlobalModel->selectQuery('ordermaster.*', 'ordermaster', array(), array(), array(), array(), array(), '', '', array(), '');
		$this->load->view('cashier/header');
		$this->load->view('cashier/orderreturn/list', $data);
		$this->load->view('cashier/footer');
	}

	public function getOrderList()
	{
		$ordercode = $this->input->get('ordercode');
		$mobile = $this->input->get('mobile');
		$fromDate = $this->input->get('fromDate');
		$toDate = $this->input->get('toDate');
		$dataCount = 0;
		$data = array();
		$tableName = "ordermaster";
		$search = $this->input->GET("search")['value'];
		$orderColumns = array("ordermaster.*,branchmaster.branchName,usermaster.userEmpNo as cashier");
		$condition = array('ordermaster.code' => $ordercode, 'customer.phone' => $mobile);
		$orderBy = array('ordermaster.id' => 'DESC');
		$joinType = array('branchmaster' => 'inner', 'usermaster' => 'inner', 'customer' => 'inner');
		$join = array('branchmaster' => 'branchmaster.code=ordermaster.branchCode', 'usermaster' => 'usermaster.code=ordermaster.addID', 'customer' => 'customer.code=ordermaster.customerCode');
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$extraCondition = "";
		if ($fromDate != '' && $toDate != '') {
			$extraCondition .= " ordermaster.orderDate between '" . $fromDate . " 00:00:00' and '" . $toDate . " 23:59:00'";
		}
		//echo $extraCondition; 
		
		$like = array("ordermaster.code" => $search . "~both");
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		$srno = $_GET['start'] + 1;
		//echo $this->db->last_query(); 
		if ($Records) {
			foreach ($Records->result() as $row) {
				$actionHtml = "Order Return";
				$paymentMode = "<span class='badge bg-success'>" . ucwords($row->paymentMode) . "</span>";
				if ($row->return == false || $row->return == '') {
					$actionHtml = '<a id="edit" href="' . base_url() . 'Cashier/OrderReturn/details/' . $row->code . '" class="btn btn-info btn-sm cursor_pointer"><i id="view" title="View" class="fa fa-undo"></i></a>';
				}
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

	public function details()
	{
		$code = $this->uri->segment(4);
		$data['query'] = $this->GlobalModel->selectQuery('ordermaster.*,customer.name,customer.phone', 'ordermaster', array("ordermaster.code" => $code), array(), array('customer' => 'customer.code=ordermaster.customerCode'), array('customer' => 'inner'));
		$tableName = 'orderlineentries';
		$orderColumns = array("orderlineentries.orderCode,orderlineentries.*,productvariants.variantName,productmaster.productEngName,productmaster.productArbName,unitmaster.unitName,productmaster.productHinName,productmaster.productUrduName,productmaster.productImage");
		$condition = array('orderlineentries.orderCode' => $code);
		$orderBy = array('orderlineentries.id' => 'asc');
		$joinType = array('productmaster' => 'inner', 'productvariants' => 'left', 'unitmaster' => 'inner');
		$join = array('productmaster' => 'productmaster.code=orderlineentries.productCode', 'productvariants' => 'productvariants.code=orderlineentries.variantCode', 'unitmaster' => 'unitmaster.code=orderlineentries.unit');
		$groupByColumn = array('orderlineentries.productCode');
		$data['orderlineentries'] = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, array(), '', '', $groupByColumn, '');
		$data['items'] = $this->GlobalModel->selectQuery('productmaster.*,productvariants.variantName,productvariants.code as variantCode,productvariants.sku as variantSKU', 'productmaster', array("productmaster.isActive" => 1), array(), array('productvariants' => 'productvariants.productCode=productmaster.code'), array('productvariants' => 'left'));
		$data['unitmaster'] = $this->GlobalModel->selectQuery('unitmaster.code,unitmaster.unitName', 'unitmaster');
		$this->load->view('cashier/header');		
		$this->load->view('cashier/orderreturn/orderdetails', $data);
		$this->load->view('cashier/footer');
	}
	public function saveOrder()
	{
		$orderCode = $this->input->post('orderCode');
		$lang = $this->session->userdata['cash_logged_in' . $this->session_key]['lang'];
		$role = ($this->session->userdata['cash_logged_in' . $this->session_key]['role']);
		$addID = ($this->session->userdata['cash_logged_in' . $this->session_key]['code']);
		$ip = $_SERVER['REMOTE_ADDR'];
		$query = $this->GlobalModel->selectQuery('ordermaster.*,customer.name,customer.phone', 'ordermaster', array("ordermaster.code" => $orderCode), array(), array('customer' => 'customer.code=ordermaster.customerCode'), array('customer' => 'inner'));
		if ($query) {
			$branchCode = $query->result_array()[0]['branchCode'];
		}
		$updateData = array(
			'return' => true
		);
		$this->GlobalModel->doEdit($updateData, 'ordermaster', $orderCode);

		$tableName = 'orderlineentries';
		$orderColumns = array("orderlineentries.*,productvariants.variantName,productmaster.productEngName,productmaster.productArbName,unitmaster.unitSName,productmaster.productHinName,productmaster.productUrduName,productmaster.productImage");
		$condition = array('orderlineentries.orderCode' => $orderCode);
		$orderBy = array('orderlineentries.id' => 'asc');
		$joinType = array('productmaster' => 'inner', 'productvariants' => 'left', 'unitmaster' => 'inner');
		$join = array('productmaster' => 'productmaster.code=orderlineentries.productCode', 'productvariants' => 'productvariants.code=orderlineentries.variantCode', 'unitmaster' => 'unitmaster.code=orderlineentries.unit');
		$groupByColumn = array('orderlineentries.productCode');
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, array(), '', '', $groupByColumn, '');
		if ($Records) {
			foreach ($Records->result() as $row) {
				$data = array(
					"orderCode" => $row->orderCode,
					"productCode" => $row->productCode,
					"variantCode" => $row->variantCode,
					"barcode" => $row->barcode,
					"unit" => $row->unit,
					"price" => $row->price,
					"qty" => $row->qty,
					"amount" => $row->amount,
					"discountPrice" => $row->discountPrice,
					"discount" => $row->discount,
					"taxPercent" => $row->taxPercent,
					"tax" => $row->tax,
					"totalPrice" => $row->totalPrice,
					"sku" => $row->sku,
					"addID" => $addID,
					"addIP" => $ip
				);
				$result = $this->GlobalModel->addNew($data, 'orderreturnlineentries', 'ORLE');
				if ($result != 'false') {
					$this->updateStock($row->productCode, $row->variantCode, $branchCode, $row->qty);
				}
			}
		}
		$msg = true;
		echo json_encode($msg); 
	}


	public function saveOrderReturn()
	{
		$lang = $this->session->userdata['cash_logged_in' . $this->session_key]['lang'];
		$role = ($this->session->userdata['cash_logged_in' . $this->session_key]['role']);
		$addID = ($this->session->userdata['cash_logged_in' . $this->session_key]['code']);
		$ip = $_SERVER['REMOTE_ADDR'];
		
		$branchCode = $this->input->post('branchCode');
		$code = $this->input->post('orderCode');
		$orderLineCode = $this->input->post('orderLineCode');
		$productQty = $this->input->post('productQty');
		$returnQty = $this->input->post('returnQty');
		
		$total = 0;
		
        $orderTotalAmount = $this->input->post('total');
		$data = array("totalPayable" => $orderTotalAmount);
		//$data = array("totalAmount" => $orderTotalAmount, "totalPayable" => $orderTotalAmount);
		$updateOrderMaster = $this->GlobalModel->doEdit($data, 'ordermaster', $code);

        $addResultFlagNew = false;
		if (isset($orderLineCode)) {
			for ($j = 0; $j < sizeof($orderLineCode); $j++) {
				if($orderLineCode[$j] != '') {
					$finalQty[$j] = $productQty[$j] - $returnQty[$j]; 
					$query = $this->GlobalModel->selectQuery('orderlineentries.*', 'orderlineentries', array("orderlineentries.id" => $orderLineCode[$j]), array(), array(), array());
					if ($query) {
						$price = $query->result_array()[0]['price'];
						$taxPercent = $query->result_array()[0]['taxPercent'];
						$orderCode = $query->result_array()[0]['orderCode'];
						$productCode = $query->result_array()[0]['productCode'];
						$variantCode = $query->result_array()[0]['variantCode'];
						$barcode = $query->result_array()[0]['barcode'];
						$unit = $query->result_array()[0]['unit'];
						$discountPrice = $query->result_array()[0]['discountPrice'];
						$discount = $query->result_array()[0]['discount'];
						$sku = $query->result_array()[0]['sku'];
					}
					$total = $returnQty[$j] * $price;
					$subTotal = (($total / 100) * $taxPercent);
					$finalAmount = $total + $subTotal;
                    $data = array(
						"orderCode" => $orderCode,
						"productCode" => $productCode,
						"variantCode" => $variantCode,
						"barcode" => $barcode,
						"unit" => $unit,
						"price" => $price,
						"qty" => $returnQty[$j],
						"amount" => $total,
						"discountPrice" => $discountPrice,
						"discount" => $discount,
						"taxPercent" => $taxPercent,
						"tax" => $subTotal,
						"totalPrice" => $finalAmount,
						"sku" => $sku,
						"addID" => $addID,
						"addIP" => $ip
					);
					$result = $this->GlobalModel->addNew($data, 'orderreturnlineentries', 'ORLE');
                    if($finalQty[$j] > 0){
						$totalNew = $finalQty[$j] * $price;
						$per = (($totalNew / 100) * $taxPercent);
						$finalTotal = $totalNew + $per;
						$updateData = array(
							"qty" => $finalQty[$j],
							"amount" => $totalNew,
							"discountPrice" => $discountPrice,
							"discount" => $discount,
							"tax" => $per,
							"totalPrice" => $finalTotal,
						);
						$this->GlobalModel->doEditForOrders($updateData, 'orderlineentries', 'id', $orderLineCode[$j]);
						$addResultFlagNew = true;
					}
                    else{
						$this->GlobalModel->deleteForeverFromField('id', $orderLineCode[$j], 'orderlineentries');
						$addResultFlagNew = true;
					}						
				}
				if ($result != 'false') {
				  $this->updateStock($productCode, $variantCode, $branchCode, $finalQty[$j]);
				}
			}
		}
		if ($result != 'false' || $addResultFlagNew == true) {
					$response['status'] = true;
					$response['message'] = "Order Return successfully";
		} else {
			$response['status'] = false;
			$response['message'] = "Failed to Add Order.";
		}
		$this->session->set_flashdata('message', json_encode($response));
        redirect('Cashier/OrderReturn/listRecords', 'refresh');
   }

	public function updateStock($productCode, $variantCode, $branchCode, $qty)
	{
		$checkPreviousStock = $this->GlobalModel->selectQuery('stockinfo.code,ifnull(stockinfo.stock,0) as stock', 'stockinfo', array('stockinfo.productCode' => $productCode, 'stockinfo.variantCode' => $variantCode, 'stockinfo.branchCode' => $branchCode));
		if ($checkPreviousStock && $checkPreviousStock->num_rows() > 0) {
			$code = $checkPreviousStock->result()[0]->code;
			$prevstock = $checkPreviousStock->result()[0]->stock;
			$newstock = $prevstock + $qty;
			$updateData = array(
				'stock' => $newstock
			);
			$this->GlobalModel->doEdit($updateData, 'stockinfo', $code);
		}
	}
}
