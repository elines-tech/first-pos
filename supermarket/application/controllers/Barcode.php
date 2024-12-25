<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barcode extends CI_Controller
{
	var $session_key;
	protected $rolecode,$branchCode; 
	public function __construct()
	{
		parent::__construct();
		$this->load->model('GlobalModel');
		$this->session_key = $this->session->userdata('key' . SESS_KEY);
		if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
			redirect('Login', 'refresh');
		}
		$res = $this->GlobalModel->checkActiveSubscription();
		if ($res == "EXPIRED") {
			redirect('package', 'refresh');
		}
		$dbName = $this->session->userdata['current_db' . $this->session_key];
		$this->db->query('use ' . $dbName);
		$this->rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		$this->branchCode = $this->session->userdata['logged_in' . $this->session_key]['userBranch'];
		$this->rights = $this->GlobalModel->getMenuRights('3.6', $this->rolecode);
		if ($this->rights == '') {
			$this->load->view('errors/norights.php');
		}
	}

	public function listRecords()
	{
		if ($this->rights != '' && $this->rights['view'] == 1) {
			$data['insertRights'] = $this->rights['insert'];
			$data['branchCode']="";
			$data['branchName']="";
			if($this->branchCode!=""){
				$data['branchCode']=$this->branchCode;
				$data['branchName'] = $this->GlobalModel->selectDataById($this->branchCode, 'branchmaster')->result()[0]->branchName;
			}
			$this->load->view('dashboard/header');

			$this->load->view('dashboard/barcode/list', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function add()
	{
		if ($this->rights != '' && $this->rights['insert'] == 1) {
			$data['insertRights'] = $this->rights['insert'];
			$data['branchCode']="";
			$data['branchName']="";
			if($this->branchCode!=""){
				$data['branchCode']=$this->branchCode;
				$data['branchName'] = $this->GlobalModel->selectDataById($this->branchCode, 'branchmaster')->result()[0]->branchName;
			}
			$this->load->view('dashboard/header');
			$this->load->view('dashboard/barcode/add', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function getBarcodeList()
	{
		$branch="";
		if($this->branchCode!=""){
			$branch=$this->branchCode;
		}
		$dataCount = 0;
		$data = array();
		$tableName = "barcodeentries";
		$search = $this->input->GET("search")['value'];
		$orderColumns = array("inwardlineentries.proNameVarName,barcodeentries.*,unitmaster.unitName");
		$condition = array("inwardentries.branchCode"=>$branch); 
		$orderBy = array('barcodeentries.id' => 'DESC');
		$joinType = array('inwardentries' => 'inner', 'inwardlineentries' => 'inner', 'unitmaster' => 'inner');
		$join = array('inwardentries' => 'inwardentries.batchNo=barcodeentries.batchNo', 'inwardlineentries' => 'inwardlineentries.code=barcodeentries.inwardLineCode', 'unitmaster' => 'unitmaster.code=barcodeentries.sellingUnit');
		$groupByColumn = array();
		$limit = $this->input->GET("length");
		$offset = $this->input->GET("start");
		$extraCondition = " barcodeentries.isDelete=0 OR barcodeentries.isDelete IS NULL";
		$like = array("barcodeentries.batchNo" => $search . "~both", "barcodeentries.barcodeText" => $search . "~both", "inwardlineentries.proNameVarName" => $search . "~both", "unitmaster.unitName" => $search . "~both", "barcodeentries.sellingQty" => $search . "~both", "barcodeentries.sellingPrice" => $search . "~both", "barcodeentries.discountPrice" => $search . "~both", "barcodeentries.taxPercent" => $search . "~both", "barcodeentries.taxAmount" => $search . "~both");
		$Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
		$srno = $_GET['start'] + 1;
		if ($Records) {
			foreach ($Records->result() as $row) {
				$code = $row->code;
				$actionHtml = '';
				if ($this->rights != '' && $this->rights['view'] == 1) {
					$actionHtml .= '<a id="view" href="' . base_url() . 'barcode/view/' . $row->code . '" class="btn btn-success btn-sm cursor_pointer m-1"><i id="view" title="View" class="fa fa-eye"></i></a>';
				}
				if ($this->rights != '' && $this->rights['update'] == 1) {
					$actionHtml .= '<a id="edit" href="' . base_url() . 'barcode/edit/' . $row->code . '" class="btn btn-info btn-sm m-1 cursor_pointer"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
				}
				if ($this->rights != '' && $this->rights['delete'] == 1) {
					$actionHtml .= '<a id="delete" class="btn btn-danger btn-sm m-1 cursor_pointer delete_barcode" id="' . $row->code . '"><i id="dlt" title="Delete" class="fa fa-trash"></i></a>';
				}

				$actionHtml .= '<a id="customize" class="btn btn-warning btn-sm m-1 cursor_pointer" target="_blank" href="' . base_url() . 'barcode/print/' . $row->code . '" ><i id="dlt" title="Print" class="fa fa-barcode"></i></a>';


				$data[] = array(
					$srno,
					$row->batchNo,
					$row->barcodeText,
					$row->proNameVarName,
					$row->sellingQty . " " . $row->unitName,
					$row->sellingPrice,
					$row->discountPrice,
					$row->taxPercent,
					$row->taxAmount,
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

	public function edit()
	{
		if ($this->rights != '' && $this->rights['update'] == 1) {
			$data['insertRights'] = $this->rights['insert'];
			$code = $this->uri->segment(3);
			$select = "barcodeentries.code,inwardentries.branchCode,barcodeentries.batchNo,barcodeentries.inwardLineCode,barcodeentries.sellingUnit,barcodeentries.sellingQty,barcodeentries.sellingPrice,";
			$select .= "barcodeentries.taxPercent,barcodeentries.taxAmount,barcodeentries.discountPrice,barcodeentries.barcodeText,barcodeentries.isActive,branchmaster.branchName,";
			$select .= "inwardlineentries.proNameVarName,unitmaster.unitName";
			$table = "barcodeentries";
			$condition = array('barcodeentries.code' => $code);
			$orderBy = array();
			$join = array(
				'inwardlineentries' => 'inwardlineentries.code=barcodeentries.inwardLineCode',
				'unitmaster' => 'unitmaster.code=barcodeentries.sellingUnit',
				'inwardentries' => "inwardentries.batchNo=barcodeentries.batchNo",
				'branchmaster' => "inwardentries.branchCode=branchmaster.code",
			);
			$joinType = array(
				'inwardlineentries' => 'inner',
				'unitmaster' => 'inner',
				'inwardentries' => 'inner',
				'branchmaster' => 'inner'
			);
			$barcodeData = $this->GlobalModel->selectQuery($select, $table, $condition, $orderBy, $join, $joinType);
			if ($barcodeData) {
				$batchNo = $barcodeData->result()[0]->batchNo;
				$data['barcodeData'] = $barcodeData;
				$condition = array('inwardlineentries.batchNo' => $batchNo);
				$join = array('productmaster' => 'productmaster.code=inwardlineentries.productCode', 'unitmaster' => 'unitmaster.code=productmaster.storageUnit', 'stockinfo' => 'stockinfo.proNameVarName=inwardlineentries.proNameVarName');
				$orderColumns = array("inwardlineentries.productCode,inwardlineentries.expiryDate,inwardlineentries.code,inwardlineentries.code,stockinfo.stock,inwardlineentries.proNameVarName,unitmaster.baseUnitCode,unitmaster.conversionFactor,productmaster.productTaxGrp");
				$joinType = array("productmaster" => 'inner', 'unitmaster' => 'inner', 'stockinfo' => 'inner');
				$orderBy = array('inwardlineentries.id' => 'ASC');
				$proQuery = $this->GlobalModel->selectQuery($orderColumns, 'inwardlineentries', $condition, [], $join, $joinType);
				$data['proQuery'] = $proQuery;
				if ($proQuery) {
					$baseUnit = $proQuery->result()[0]->baseUnitCode;
					$data['unitData'] = $this->GlobalModel->selectQuery('unitmaster.code,unitmaster.unitName,conversionFactor', 'unitmaster', array('unitmaster.baseUnitCode' => $baseUnit));
				}
				$this->load->view('dashboard/header');
				$this->load->view('dashboard/barcode/edit', $data);
				$this->load->view('dashboard/footer');
			}
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function view()
	{
		if ($this->rights != '' && $this->rights['view'] == 1) {
			$code = $this->uri->segment(3);
			$data['batches'] = $this->GlobalModel->selectActiveData('inwardentries');
			$data['barcodeData'] = $this->GlobalModel->selectQuery('barcodeentries.*,inwardlineentries.proNameVarName,unitmaster.unitName', 'barcodeentries', array('barcodeentries.code' => $code), array(), array('inwardlineentries' => 'inwardlineentries.code=barcodeentries.inwardLineCode', 'unitmaster' => 'unitmaster.code=barcodeentries.sellingUnit'), array('inwardlineentries' => 'inner', 'unitmaster' => 'inner'));
			$this->load->view('dashboard/header');

			$this->load->view('dashboard/barcode/view', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function getInwardProducts()
	{
		$batchNo = $this->input->post('batchCode');
		$productHtml = '<option value="">Select</option>';
		$condition = array('inwardlineentries.batchNo' => $batchNo, 'stockinfo.batchNo' => $batchNo);
		$join = array('productmaster' => 'productmaster.code=inwardlineentries.productCode', 'unitmaster' => 'unitmaster.code=productmaster.storageUnit', 'stockinfo' => 'stockinfo.proNameVarName=inwardlineentries.proNameVarName');
		$orderColumns = array("inwardlineentries.productCode,inwardlineentries.expiryDate,inwardlineentries.code,inwardlineentries.code,stockinfo.stock,inwardlineentries.proNameVarName,unitmaster.baseUnitCode,unitmaster.conversionFactor,productmaster.productTaxGrp");
		$joinType = array("productmaster" => 'inner', 'unitmaster' => 'inner', 'stockinfo' => 'inner');
		$orderBy = array('inwardlineentries.id' => 'ASC');
		$Records = $this->GlobalModel->selectQuery($orderColumns, 'inwardlineentries', $condition, [], $join, $joinType);
		if ($Records && $Records->num_rows() > 0) {
			foreach ($Records->result() as $gets) {
				$productHtml .= "<option value='" . $gets->code . "' data-stock='" . $gets->stock . "' data-unit='" . $gets->baseUnitCode . "' data-taxgroup='" . $gets->productTaxGrp . "' data-storageconvfactor='" . $gets->conversionFactor . "'>" . $gets->proNameVarName . "</option>";
			}
			$response['status'] = true;
			$response['productHtml'] = $productHtml;
		} else {
			$response['status'] = false;
		}
		echo json_encode($response);
	}

	public function getBatchwiseInwardProducts()
	{
		$batchNo = $this->input->post('batchCode');
		$data = [];
		$condition = array('inwardlineentries.batchNo' => $batchNo, 'stockinfo.batchNo' => $batchNo);
		$join = array('productmaster' => 'productmaster.code=inwardlineentries.productCode', 'unitmaster' => 'unitmaster.code=productmaster.storageUnit', 'stockinfo' => 'stockinfo.proNameVarName=inwardlineentries.proNameVarName');
		$orderColumns = array("inwardlineentries.productCode,inwardlineentries.expiryDate,inwardlineentries.code,inwardlineentries.code,stockinfo.stock,inwardlineentries.proNameVarName,unitmaster.baseUnitCode,unitmaster.conversionFactor,productmaster.productTaxGrp");
		$joinType = array("productmaster" => 'inner', 'unitmaster' => 'inner', 'stockinfo' => 'inner');
		$orderBy = array('inwardlineentries.id' => 'ASC');
		$Records = $this->GlobalModel->selectQuery($orderColumns, 'inwardlineentries', $condition, [], $join, $joinType);
		if ($Records && $Records->num_rows() > 0) {
			foreach ($Records->result() as $gets) {
				$element = [
					"id" => $gets->code,
					"stock" => $gets->stock,
					"unit" => $gets->baseUnitCode,
					"taxgroup" => $gets->productTaxGrp,
					"storageconvfactor" => $gets->conversionFactor,
					"text" => $gets->proNameVarName
				];
				array_push($data, $element);
			}
		}
		echo json_encode($data);
	}

	public function getProductDetails()
	{
		$baseUnit = $this->input->post('baseUnit');
		$taxGroup = $this->input->post('taxGroup');
		$unitData = $this->GlobalModel->selectQuery('unitmaster.code,unitmaster.unitName,conversionFactor', 'unitmaster', array('unitmaster.baseUnitCode' => $baseUnit, 'unitmaster.isActive' => 1));
		if ($unitData && $unitData->num_rows() > 0) {
			$unitHtml = '<option value="">Select</option>';
			if ($unitData->num_rows() > 0) {
				foreach ($unitData->result() as $unit) {
					$unitHtml .= "<option value='" . $unit->code . "' data-convfactor='" . $unit->conversionFactor . "'>" . $unit->unitName . "</option>";
				}
			}
		}
		$tax = 0;
		$prdDetails = $this->GlobalModel->selectQuery('taxgroupmaster.taxes', 'taxgroupmaster', array('taxgroupmaster.code' => $taxGroup, 'taxgroupmaster.isActive' => 1));
		if ($prdDetails) {
			foreach ($prdDetails->result() as $pt) {
				$taxes = json_decode($pt->taxes, true);
				//$tax = $this->db->query("select ifnull(sum(taxes.taxPer),0) as taxPer from taxes where taxes.isActive=1 and taxes.code in('" . implode("','", $taxes) . "')")->result()[0]->taxPer;
				$taxPer = $this->GlobalModel->directQuery("select ifnull(sum(taxes.taxPer),0) as taxPer from taxes where taxes.isActive=1 and taxes.code in('" . implode("','", $taxes) . "')");
				if (!empty($taxPer)) {
					$tax = $taxPer[0]['taxPer']; 
				}
			}
		}
		$response['unitHtml'] = $unitHtml;
		$response['taxPer'] = $tax;
		echo json_encode($response);
	}

	public function print()
	{
		if ($this->rights != '' && $this->rights['update'] == 1) {
			$code = $this->uri->segment(3);
			$data['barcodeData'] =  $this->GlobalModel->selectQuery('productmaster.productEngName,unitmaster.unitName,barcodeentries.*', 'barcodeentries', array('barcodeentries.code' => $code), array(), array('inwardlineentries' => 'inwardlineentries.code=barcodeentries.inwardLineCode', 'productmaster' => 'productmaster.code=inwardlineentries.productCode', 'unitmaster' => 'unitmaster.code=barcodeentries.sellingUnit'), array('inwardlineentries' => 'inner', 'productmaster' => 'inner', 'unitmaster' => 'inner'));
			$this->load->view('dashboard/header');

			$this->load->view('dashboard/barcode/print', $data);
			$this->load->view('dashboard/footer');
		} else {
			$this->load->view('errors/norights.php');
		}
	}

	public function generateBarcode()
	{
		$barcodeLineCode = $this->input->post("lineCode");
		$language = $this->input->post("language");
		$barcodeQty = $this->input->post("barcodeQty");
		$join = array('inwardlineentries' => 'inwardlineentries.code=barcodeentries.inwardLineCode', 'inwardentries' => 'inwardentries.batchNo=barcodeentries.batchNo', 'branchmaster' => 'branchmaster.code=inwardentries.branchCode', 'productmaster' => 'productmaster.code=inwardlineentries.productCode', 'unitmaster' => 'unitmaster.code=barcodeentries.sellingUnit');
		$joinType = array('inwardentries' => 'inner', 'branchmaster' => 'inner', 'productmaster' => 'inner', 'inwardlineentries' => 'inner', 'unitmaster' => 'inner');
		$RecordsPrint = $this->GlobalModel->selectQuery('productmaster.productEngName,productmaster.productArbName,productUrduName,productmaster.productHinName,barcodeentries.*,unitmaster.unitSName as unitName,inwardentries.batchNo,branchmaster.branchName,barcodeentries.barcodeText', 'barcodeentries', array('barcodeentries.code' => $barcodeLineCode), array(), $join, $joinType);
		//echo $this->db->last_query();
		if ($RecordsPrint && $RecordsPrint->num_rows() > 0) {
			$product = $RecordsPrint->result()[0];
			$productName = $product->productEngName;
			if ($language == 2) {
				$productName = $product->productArbName;
			} elseif ($language == 3) {
				$productName = $product->productUrduName;
			} else if ($language == 4) {
				$productName = $product->productHinName;
			}
			$barcodeImage = $this->generateBarcodeImage($product->barcodeText);
			$barcodeHtml = '<div style="width:100%;display:flex;flex-wrap:wrap">';
			for ($i = 1; $i <= $barcodeQty; $i++) {
				$barcodeHtml .= '<div style="border:1px solid black;width:33%;text-align:center;">
					<h5 style="margin:.40rem !important;">' . $product->branchName . '</h5>
					<p style="margin:.40rem !important;font-size:21px;"><b>' . $productName . '</b></p>
					<h6 style="margin:.40rem !important;text-align:left">Price: ' . $product->sellingPrice . '<span class="float-end">Unit: ' . $product->sellingQty . ' ' . $product->unitName . '</span></h6>
					<img width="250" height="25" src="data:image/png;base64,' . base64_encode($barcodeImage) . '">
					<p style="font-size:12px;text-align:center;margin-bottom:0px;"><b>' . $product->barcodeText . '</b></p>
				</div>';
			}
			$barcodeHtml .= '</div>';
			$response['status'] = true;
			$response['barcodeHtml'] = $barcodeHtml;
		} else {
			$response['status'] = false;
		}
		echo json_encode($response);
	}

	public function saveBarcode()
	{
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$batchNo = trim($this->input->post("batchNo"));
		$inwardLineCode = trim($this->input->post("inwardLineCode"));
		$sellingUnit = trim($this->input->post("sellingUnit"));
		$sellingQty = $this->input->post("sellingQty");
		$sellingPrice = $this->input->post("sellingPrice");
		$taxAmount = $this->input->post("taxAmount");
		$taxPercent = $this->input->post("taxPercent");
		$discountPrice = $this->input->post("discountPrice");
		$ip = $_SERVER['REMOTE_ADDR'];
		$barcodeText = $this->GlobalModel->randomCharacters(8);
		$checkCode = $this->GlobalModel->selectQuery('barcodeentries.barcodeText', 'barcodeentries', array('barcodeentries.barcodeText' => $barcodeText));
		if ($checkCode && $checkCode->num_rows() > 0) {
			$barcodeText = $this->GlobalModel->randomCharacters(8);
		}
		$data = array(
			'batchNo' => $batchNo,
			'inwardLineCode' => $inwardLineCode,
			'sellingQty' => $sellingQty,
			'sellingUnit' => $sellingUnit,
			'sellingPrice' => $sellingPrice,
			'taxPercent' => $taxPercent,
			'taxAmount' => $taxAmount,
			'discountPrice' => $discountPrice,
			'barcodeText' => $barcodeText,
			'isActive' => 1,
			'addID' => $addID,
			'addIP' => $ip,
		);
		$code = $this->GlobalModel->addNew($data, 'barcodeentries', 'BAR');
		if ($code != 'false') {
			$txt = $code . " - barcode is generated.";
			$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
			$this->GlobalModel->activity_log($activity_text);
			$response['status'] = true;
			$response['message'] = 'Barcode generated successfully';
		} else {
			$response['status'] = false;
			$response['message'] = 'Failed to generate barcode';
		}
		echo json_encode($response);
		exit;
	}

	public function updatebarcode()
	{
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$code = $this->input->post("code");
		$inwardLineCode = trim($this->input->post("inwardLineCode"));
		$sellingUnit = trim($this->input->post("sellingUnit"));
		$sellingQty = $this->input->post("sellingQty");
		$sellingPrice = $this->input->post("sellingPrice");
		$taxAmount = $this->input->post("taxAmount");
		$taxPercent = $this->input->post("taxPercent");
		$discountPrice = $this->input->post("discountPrice");
		$ip = $_SERVER['REMOTE_ADDR'];
		$data = array(
			'inwardLineCode' => $inwardLineCode,
			'sellingQty' => $sellingQty,
			'sellingUnit' => $sellingUnit,
			'sellingPrice' => $sellingPrice,
			'taxPercent' => $taxPercent,
			'taxAmount' => $taxAmount,
			'discountPrice' => $discountPrice,
			'isActive' => 1,
			'editID' => $addID,
			'editIP' => $ip,
		);
		$result = $this->GlobalModel->doEdit($data, 'barcodeentries', $code);
		if ($result != 'false') {
			$txt = $code . " - barcode is updated";
			$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
			$this->GlobalModel->activity_log($activity_text);
			$response['status'] = true;
			$response['message'] = 'Barcode updated successfully';
		} else {
			$response['status'] = false;
			$response['message'] = 'No changes were found';
		}
		echo json_encode($response);
		exit;
	}

	public function deleteBarcode()
	{
		$code = $this->input->post('code');
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$query = $this->GlobalModel->delete($code, 'barcodeentries');
		if ($query) {
			$txt = $code . " barcode is deleted.";
			$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
			$this->GlobalModel->activity_log($activity_text);
		}
		echo $query;
	}

	public function checkDuplicate()
	{
		$productCode = $this->input->post("productCode");
		$sellingQty = $this->input->post("sellingQty");
		$sellingUnit = $this->input->post("sellingUnit");
		$code = $this->input->post("code");
		$check = $this->GlobalModel->selectQuery('barcodeentries.code', 'barcodeentries', array('barcodeentries.inwardLineCode' => $productCode, 'barcodeentries.sellingQty' => $sellingQty, 'barcodeentries.sellingUnit' => $sellingUnit, 'barcodeentries.code!=' => $code));
		if ($check && $check->num_rows() > 0) {
			$response['status'] = false;
		} else {
			$response['status'] = true;
		}
		echo json_encode($response);
	}

	public function generateBarcodeImage($text = "0", $size = "20", $orientation = "horizontal", $code_type = "code128", $print = false, $SizeFactor = 1, $filepath = "")
	{
		$code_string = "";
		// Translate the $text into barcode the correct $code_type
		if (in_array(strtolower($code_type), array("code128", "code128b"))) {
			$chksum = 104;
			// Must not change order of array elements as the checksum depends on the array's key to validate final code
			$code_array = array(" " => "212222", "!" => "222122", "\"" => "222221", "#" => "121223", "$" => "121322", "%" => "131222", "&" => "122213", "'" => "122312", "(" => "132212", ")" => "221213", "*" => "221312", "+" => "231212", "," => "112232", "-" => "122132", "." => "122231", "/" => "113222", "0" => "123122", "1" => "123221", "2" => "223211", "3" => "221132", "4" => "221231", "5" => "213212", "6" => "223112", "7" => "312131", "8" => "311222", "9" => "321122", ":" => "321221", ";" => "312212", "<" => "322112", "=" => "322211", ">" => "212123", "?" => "212321", "@" => "232121", "A" => "111323", "B" => "131123", "C" => "131321", "D" => "112313", "E" => "132113", "F" => "132311", "G" => "211313", "H" => "231113", "I" => "231311", "J" => "112133", "K" => "112331", "L" => "132131", "M" => "113123", "N" => "113321", "O" => "133121", "P" => "313121", "Q" => "211331", "R" => "231131", "S" => "213113", "T" => "213311", "U" => "213131", "V" => "311123", "W" => "311321", "X" => "331121", "Y" => "312113", "Z" => "312311", "[" => "332111", "\\" => "314111", "]" => "221411", "^" => "431111", "_" => "111224", "\`" => "111422", "a" => "121124", "b" => "121421", "c" => "141122", "d" => "141221", "e" => "112214", "f" => "112412", "g" => "122114", "h" => "122411", "i" => "142112", "j" => "142211", "k" => "241211", "l" => "221114", "m" => "413111", "n" => "241112", "o" => "134111", "p" => "111242", "q" => "121142", "r" => "121241", "s" => "114212", "t" => "124112", "u" => "124211", "v" => "411212", "w" => "421112", "x" => "421211", "y" => "212141", "z" => "214121", "{" => "412121", "|" => "111143", "}" => "111341", "~" => "131141", "DEL" => "114113", "FNC 3" => "114311", "FNC 2" => "411113", "SHIFT" => "411311", "CODE C" => "113141", "FNC 4" => "114131", "CODE A" => "311141", "FNC 1" => "411131", "Start A" => "211412", "Start B" => "211214", "Start C" => "211232", "Stop" => "2331112");
			$code_keys = array_keys($code_array);
			$code_values = array_flip($code_keys);
			for ($X = 1; $X <= strlen($text); $X++) {
				$activeKey = substr($text, ($X - 1), 1);
				$code_string .= $code_array[$activeKey];
				$chksum = ($chksum + ($code_values[$activeKey] * $X));
			}
			$code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];

			$code_string = "211214" . $code_string . "2331112";
		} elseif (strtolower($code_type) == "code128a") {
			$chksum = 103;
			$text = strtoupper($text); // Code 128A doesn't support lower case
			// Must not change order of array elements as the checksum depends on the array's key to validate final code
			$code_array = array(" " => "212222", "!" => "222122", "\"" => "222221", "#" => "121223", "$" => "121322", "%" => "131222", "&" => "122213", "'" => "122312", "(" => "132212", ")" => "221213", "*" => "221312", "+" => "231212", "," => "112232", "-" => "122132", "." => "122231", "/" => "113222", "0" => "123122", "1" => "123221", "2" => "223211", "3" => "221132", "4" => "221231", "5" => "213212", "6" => "223112", "7" => "312131", "8" => "311222", "9" => "321122", ":" => "321221", ";" => "312212", "<" => "322112", "=" => "322211", ">" => "212123", "?" => "212321", "@" => "232121", "A" => "111323", "B" => "131123", "C" => "131321", "D" => "112313", "E" => "132113", "F" => "132311", "G" => "211313", "H" => "231113", "I" => "231311", "J" => "112133", "K" => "112331", "L" => "132131", "M" => "113123", "N" => "113321", "O" => "133121", "P" => "313121", "Q" => "211331", "R" => "231131", "S" => "213113", "T" => "213311", "U" => "213131", "V" => "311123", "W" => "311321", "X" => "331121", "Y" => "312113", "Z" => "312311", "[" => "332111", "\\" => "314111", "]" => "221411", "^" => "431111", "_" => "111224", "NUL" => "111422", "SOH" => "121124", "STX" => "121421", "ETX" => "141122", "EOT" => "141221", "ENQ" => "112214", "ACK" => "112412", "BEL" => "122114", "BS" => "122411", "HT" => "142112", "LF" => "142211", "VT" => "241211", "FF" => "221114", "CR" => "413111", "SO" => "241112", "SI" => "134111", "DLE" => "111242", "DC1" => "121142", "DC2" => "121241", "DC3" => "114212", "DC4" => "124112", "NAK" => "124211", "SYN" => "411212", "ETB" => "421112", "CAN" => "421211", "EM" => "212141", "SUB" => "214121", "ESC" => "412121", "FS" => "111143", "GS" => "111341", "RS" => "131141", "US" => "114113", "FNC 3" => "114311", "FNC 2" => "411113", "SHIFT" => "411311", "CODE C" => "113141", "CODE B" => "114131", "FNC 4" => "311141", "FNC 1" => "411131", "Start A" => "211412", "Start B" => "211214", "Start C" => "211232", "Stop" => "2331112");
			$code_keys = array_keys($code_array);
			$code_values = array_flip($code_keys);
			for ($X = 1; $X <= strlen($text); $X++) {
				$activeKey = substr($text, ($X - 1), 1);
				$code_string .= $code_array[$activeKey];
				$chksum = ($chksum + ($code_values[$activeKey] * $X));
			}
			$code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];

			$code_string = "211412" . $code_string . "2331112";
		} elseif (strtolower($code_type) == "code39") {
			$code_array = array("0" => "111221211", "1" => "211211112", "2" => "112211112", "3" => "212211111", "4" => "111221112", "5" => "211221111", "6" => "112221111", "7" => "111211212", "8" => "211211211", "9" => "112211211", "A" => "211112112", "B" => "112112112", "C" => "212112111", "D" => "111122112", "E" => "211122111", "F" => "112122111", "G" => "111112212", "H" => "211112211", "I" => "112112211", "J" => "111122211", "K" => "211111122", "L" => "112111122", "M" => "212111121", "N" => "111121122", "O" => "211121121", "P" => "112121121", "Q" => "111111222", "R" => "211111221", "S" => "112111221", "T" => "111121221", "U" => "221111112", "V" => "122111112", "W" => "222111111", "X" => "121121112", "Y" => "221121111", "Z" => "122121111", "-" => "121111212", "." => "221111211", " " => "122111211", "$" => "121212111", "/" => "121211121", "+" => "121112121", "%" => "111212121", "*" => "121121211");

			// Convert to uppercase
			$upper_text = strtoupper($text);

			for ($X = 1; $X <= strlen($upper_text); $X++) {
				$code_string .= $code_array[substr($upper_text, ($X - 1), 1)] . "1";
			}

			$code_string = "1211212111" . $code_string . "121121211";
		} elseif (strtolower($code_type) == "code25") {
			$code_array1 = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
			$code_array2 = array("3-1-1-1-3", "1-3-1-1-3", "3-3-1-1-1", "1-1-3-1-3", "3-1-3-1-1", "1-3-3-1-1", "1-1-1-3-3", "3-1-1-3-1", "1-3-1-3-1", "1-1-3-3-1");

			for ($X = 1; $X <= strlen($text); $X++) {
				for ($Y = 0; $Y < count($code_array1); $Y++) {
					if (substr($text, ($X - 1), 1) == $code_array1[$Y])
						$temp[$X] = $code_array2[$Y];
				}
			}

			for ($X = 1; $X <= strlen($text); $X += 2) {
				if (isset($temp[$X]) && isset($temp[($X + 1)])) {
					$temp1 = explode("-", $temp[$X]);
					$temp2 = explode("-", $temp[($X + 1)]);
					for ($Y = 0; $Y < count($temp1); $Y++)
						$code_string .= $temp1[$Y] . $temp2[$Y];
				}
			}

			$code_string = "1111" . $code_string . "311";
		} elseif (strtolower($code_type) == "codabar") {
			$code_array1 = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "-", "$", ":", "/", ".", "+", "A", "B", "C", "D");
			$code_array2 = array("1111221", "1112112", "2211111", "1121121", "2111121", "1211112", "1211211", "1221111", "2112111", "1111122", "1112211", "1122111", "2111212", "2121112", "2121211", "1121212", "1122121", "1212112", "1112122", "1112221");

			// Convert to uppercase
			$upper_text = strtoupper($text);

			for ($X = 1; $X <= strlen($upper_text); $X++) {
				for ($Y = 0; $Y < count($code_array1); $Y++) {
					if (substr($upper_text, ($X - 1), 1) == $code_array1[$Y])
						$code_string .= $code_array2[$Y] . "1";
				}
			}
			$code_string = "11221211" . $code_string . "1122121";
		}

		// Pad the edges of the barcode
		$code_length = 20;
		if ($print) {
			$text_height = 30;
		} else {
			$text_height = 0;
		}

		for ($i = 1; $i <= strlen($code_string); $i++) {
			$code_length = $code_length + (int)(substr($code_string, ($i - 1), 1));
		}

		if (strtolower($orientation) == "horizontal") {
			$img_width = $code_length * $SizeFactor;
			$img_height = $size;
		} else {
			$img_width = $size;
			$img_height = $code_length * $SizeFactor;
		}

		$image = imagecreate($img_width, $img_height + $text_height);
		$black = imagecolorallocate($image, 0, 0, 0);
		$white = imagecolorallocate($image, 255, 255, 255);

		imagefill($image, 0, 0, $white);
		if ($print) {
			imagestring($image, 5, 31, $img_height, $text, $black);
		}

		$location = 10;
		for ($position = 1; $position <= strlen($code_string); $position++) {
			$cur_size = $location + (substr($code_string, ($position - 1), 1));
			if (strtolower($orientation) == "horizontal")
				imagefilledrectangle($image, $location * $SizeFactor, 0, $cur_size * $SizeFactor, $img_height, ($position % 2 == 0 ? $white : $black));
			else
				imagefilledrectangle($image, 0, $location * $SizeFactor, $img_width, $cur_size * $SizeFactor, ($position % 2 == 0 ? $white : $black));
			$location = $cur_size;
		}
		ob_start();
		// Draw barcode to the screen or save in a file
		if ($filepath == "") {
			header('Content-type: image/png');
			imagepng($image);
			imagedestroy($image);
		} else {
			imagepng($image, $filepath);
			imagedestroy($image);
		}
		$imagedata = ob_get_contents();
		ob_end_clean();
		return $imagedata;
	}
}
