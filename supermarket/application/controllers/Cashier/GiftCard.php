

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class GiftCard extends CI_Controller
{
	var $session_key;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form', 'url', 'html');
		$this->load->model('GlobalModel');
		$this->load->library('form_validation');
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
		$this->load->view('cashier/header'); 
		$this->load->view('cashier/giftcard/list');
		$this->load->view('cashier/footer');
	}

	public function sale()
	{
		$giftCode = $this->input->post("giftCode");
		$data['cardData'] = $this->GlobalModel->selectQuery('giftcard.*', 'giftcard', array('giftcard.code' => $giftCode, 'giftcard.isActive' => 1));
		$this->load->view('cashier/header'); 
		$this->load->view('cashier/giftcard/sale_card', $data);
		$this->load->view('cashier/footer');
	}

	public function salehistory()
	{
		$giftCode = $this->input->post("giftCode");
		$data['cardData'] = $this->GlobalModel->selectQuery('giftcard.*', 'giftcard', array('giftcard.code' => $giftCode, 'giftcard.isActive' => 1));
		$this->load->view('cashier/header'); 
		$this->load->view('cashier/giftcard/sale_list', $data);
		$this->load->view('cashier/footer');
	}

	public function getgiftcardList()
	{
		$addID = $this->session->userdata['cash_logged_in' . $this->session_key]['code'];
		$tableName = "giftcard";
		$orderColumns = array("giftcard.*");
		$condition = array('giftcard.isActive' => 1);
		$orderBy = array('giftcard.id' => 'DESC');
		$joinType = array();
		$join = array();
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$extraCondition = " (giftcard.isDelete=0 OR giftcard.isDelete IS NULL)";
		$like = array();
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		$r = $this->db->last_query();
		$srno = $_GET['start'] + 1;
		$dataCount = 0;
		$data = array();
		if ($Records) {
			foreach ($Records->result() as $row) {
				$code = $row->code;
				$actionHtml = '<form method="post" action="' . base_url() . 'Cashier/giftCard/sale">
					<input type="hidden" class="form-control" name="giftCode" value="' . $row->code . '">
					<button type="submit" id="saveDefaultButton" class="btn btn-primary btn-sm m-1 w-100 cursor_pointer"><i title="Add Sale" class="fa fa-plus"></i><?php echo $translations["New Sale"]?></button> </form>';
				$actionHtml .= '<form method="post" action="' . base_url() . 'Cashier/giftCard/salehistory">
				<input type="hidden" class="form-control" name="giftCode" value="' . $row->code . '">
				<button type="submit" id="saveDefaultButton" class="btn btn-info btn-sm m-1 cursor_pointer w-100"><i title="Sale History" class="fa fa-time"></i><?php echo $translations["Sale History"]?></button></form>';
				$data[] = array(
					$srno,
					$row->title,
					$row->discount,
					$row->price,
					$row->validityInDays,
					$actionHtml
				);
				$srno++;
			}
			$dataCount = sizeof($this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, '', '', $groupByColumn, $extraCondition)->result());
		}
		$output = array(
			"draw"			  =>     intval($_GET["draw"]),
			"recordsTotal"    =>     $dataCount,
			"recordsFiltered" =>     $dataCount,
			"data"            =>     $data,
			'r'				  => 	 $r
		);
		echo json_encode($output);
	}

	public function saveCardDetails()
	{
		$date = date('Y-m-d H:i:s'); 
		$giftCode = $this->input->post("giftCode");
		$mcardCount = $this->input->post("mcardCount");
		$mcustPhone = $this->input->post("mcustPhone");
		$mexpiryDate = $this->input->post("mexpiryDate");
		$mtotalPrice = $this->input->post("mtotalPrice");
		$mcustEmail = $this->input->post("mcustEmail");
		$mcustName = $this->input->post("mcustName");
		$custName = explode(",", $this->input->post("custName"));
		$custEmail = explode(",", $this->input->post("custEmail"));
		$custPhone = explode(",", $this->input->post("custPhone"));
		$countryCode = explode(",", $this->input->post("countryCode"));
		$cardDetails = '{}';
		$giftDetails = $this->GlobalModel->selectQuery('giftcard.*', 'giftcard', array('giftcard.code' => $giftCode, 'giftcard.isActive' => 1));
		if ($giftDetails && $giftDetails->num_rows() > 0) {
			$result = $giftDetails->result()[0];
			$cardDetails = array(
				"title" => $result->title,
				"description" => $result->description,
				"discount" => $result->discount,
				"price" => $result->price,
				"validityInDays" => $result->validityInDays,
			);
			$cardDetails = json_encode($cardDetails);
		}

		$addID = $this->session->userdata['cash_logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['cash_logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['cash_logged_in' . $this->session_key]['username'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$text = $userName . ' purchased new giftcard "' . $giftCode . '" from ' . $ip;
		$data = array(
			'cardDetails' => $cardDetails,
			'cardCode' => $giftCode,
			'custName' => $mcustName,
			'cardCount' => $mcardCount,
			'custEmail' => $mcustEmail,
			'expiryDate' => $mexpiryDate,
			'custPhone' => $mcustPhone,
			'totalPrice' => $mtotalPrice,
			'addID' => $addID,
			'addIP' => $ip,
			'isActive' => 1,
		);
		$code = $this->GlobalModel->addNew($data, 'salegiftcard', 'SGC'); 
		if ($code != 'false') {
			for ($i = 0; $i < count($custPhone); $i++) {
				$barcodeText = $this->GlobalModel->generateCardNo();
				$phone = $countryCode[$i] . $custEmail[$i];
				$data = array(
					'salecardCode' => $code,
					'giftNo' => $barcodeText,
					'custName' => $custName[$i],
					'custEmail' => $custEmail[$i],
					'custPhone' => $phone,
					'addID' => $addID,
					'addIP' => $ip,
					'isActive' => 1,
				);
				$salecode = $this->GlobalModel->addNew($data, 'salegiftcardlineentries', 'SGCL');
			}
			$txt = $code . " - " . $giftCode . " Gift card is purchases.";
			$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
			$this->GlobalModel->activity_log($activity_text);
			$response['status'] = true;
			$response['message'] = "Giftcard purchased successfully.";
		} else {
			$response['status'] = false;
			$response['message'] = "Failed to purchase giftcard";
		}
		echo json_encode($response);
	}

	public function getHistoryList()
	{
		$giftCode = $this->input->post("giftCode");
		$data['cardData'] = $this->GlobalModel->selectQuery('giftcard.*', 'giftcard', array('giftcard.code' => $giftCode, 'giftcard.isActive' => 1));
		$this->load->view('cashier/header'); 
		$this->load->view('cashier/giftcard/sale_list', $data);
		$this->load->view('cashier/footer');
	}

	public function getGiftCardHistoryList()
	{
		$cardCode = $this->input->GET('cardCode');
		$addID = $this->session->userdata['cash_logged_in' . $this->session_key]['code'];
		$tableName = "salegiftcard";
		$orderColumns = array("salegiftcard.*");
		$condition = array('salegiftcard.isActive' => 1, 'salegiftcard.cardCode' => $cardCode);
		$orderBy = array('salegiftcard.id' => 'DESC');
		$joinType = array();
		$join = array();
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$extraCondition = " (salegiftcard.isDelete=0 OR salegiftcard.isDelete IS NULL)";
		$like = array();
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		$r = $this->db->last_query();
		$srno = $_GET['start'] + 1;
		$dataCount = 0;
		$data = array();
		if ($Records) {
			foreach ($Records->result() as $row) {
				$code = $row->code;
				$actionHtml = '<form method="post" action="' . base_url() . 'Cashier/giftCard/print">
				<input type="hidden" class="form-control" name="giftCode" value="' . $row->code . '">
				<button type="submit" id="printCards" class="btn btn-info btn-sm m-1 cursor_pointer w-100"><i title="Sale History" class="fa fa-print"></i> Print Cards</button></form>';
				$data[] = array(
					$srno,
					$row->custName,
					$row->custEmail,
					$row->custPhone,
					$row->cardCount,
					$row->totalPrice,
					date('d/m/Y', strtotime($row->expiryDate)),
					$actionHtml
				);
				$srno++;
			}
			$dataCount = sizeof($this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, '', '', $groupByColumn, $extraCondition)->result());
		}
		$output = array(
			"draw"			  =>     intval($_GET["draw"]),
			"recordsTotal"    =>     $dataCount,
			"recordsFiltered" =>     $dataCount,
			"data"            =>     $data,
			'r'				  => 	 $r
		);
		echo json_encode($output);
	}

	public function print()
	{
		$code = $this->input->post("giftCode");
		$data['cards'] = [];
		$data['sale'] = [];
		$sel = "salegiftcard.cardDetails,salegiftcard.expiryDate";
		$table = "salegiftcard";
		$cond = ["salegiftcard.code" => $code];
		$order = $join = $jointype = $like = [];
		$limit = 1;
		$offset = 0;
		$sale = $this->GlobalModel->selectQuery($sel, $table, $cond, $order, $join, $jointype, $like, $limit, $offset);
		if ($sale) {
			$data['sale'] = $sale->result_array()[0];
			$sel = "salegiftcardlineentries.giftNo,salegiftcardlineentries.custName,salegiftcardlineentries.custPhone,salegiftcardlineentries.custEmail";
			$table = "salegiftcardlineentries";
			$cond = ['salegiftcard.code' => $code];
			$order = ["salegiftcardlineentries.id" => "ASC"];
			$join = ["salegiftcard" => "salegiftcardlineentries.salecardCode=salegiftcard.code"];
			$jointype = ["salegiftcard" => "inner"];
			$cards = $this->GlobalModel->selectQuery($sel, $table, $cond, $order, $join, $jointype);
			if ($cards) {
				$cards = $cards->result_array();
				$cards_array = [];
				foreach ($cards as $card) {
					$crd = $card;
					$crd['barcode'] = ""; //base64_encode($this->GlobalModel->generateBarcodeImage($card['giftNo']));
					$cards_array[] = $crd;
				}
				$data['cards'] = $cards_array;
			}
		}
		$this->load->view('cashier/giftcard/print', $data);
	}
}
