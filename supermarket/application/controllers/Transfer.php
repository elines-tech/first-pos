<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transfer extends CI_Controller
{
    var $session_key;
	protected $rolecode,$branchCode; 
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
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
        $this->rights = $this->GlobalModel->getMenuRights('3.5', $this->rolecode);
        if ($this->rights == '') {
            $this->load->view('errors/norights.php');
        }
    }

    public function listRecords()
    {
        if ($this->rights != '' && $this->rights['view'] == 1) {
            $data['insertRights'] = $this->rights['insert'];
            $this->load->view('dashboard/header');
            $this->load->view('dashboard/transfer/list', $data);
            $this->load->view('dashboard/footer');
        } else {
            $this->load->view('errors/norights.php');
        }
    }

    public function add()
    {
        if ($this->rights != '' && $this->rights['insert'] == 1) {
			$data['branchCode']="";
			$data['branchName']="";
			if($this->branchCode!=""){
				$data['branchCode']=$this->branchCode;
				$data['branchName'] = $this->GlobalModel->selectDataById($this->branchCode, 'branchmaster')->result()[0]->branchName;
			}
            $data['branch'] = $this->GlobalModel->selectActiveData('branchmaster');
            $data['unitmaster'] = $this->GlobalModel->selectActiveData('unitmaster');
            $this->load->view('dashboard/header');
            $this->load->view('dashboard/transfer/add', $data);
            $this->load->view('dashboard/footer');
        } else {
            $this->load->view('errors/norights.php');
        }
    }

    public function getItems()
    {
        $batchCode = $this->input->post("batchCode");
        $itemHtml = '';
        $items = $this->GlobalModel->selectQuery('inwardlineentries.*,stockinfo.stock,productmaster.productEngName,productmaster.sku as productSKU,productvariants.variantName,productvariants.sku as variantSKU', 'inwardlineentries', array('inwardlineentries.isActive' => 1, 'inwardlineentries.batchNo' => $batchCode), array(), array('productmaster' => 'inwardlineentries.productCode=productmaster.code', 'productvariants' => 'inwardlineentries.variantCode=productvariants.code', 'inwardentries' => 'inwardentries.code=inwardlineentries.inwardCode', 'stockinfo' => 'stockinfo.productCode=inwardlineentries.productCode and stockinfo.variantCode=inwardlineentries.variantCode and stockinfo.branchCode=inwardentries.branchCode and stockinfo.batchNo=inwardlineentries.batchNo'), array('productmaster' => 'inner', 'productvariants' => 'left', 'inwardentries' => 'inner', 'stockinfo' => 'left'));
        if ($items && $items->num_rows() > 0) {
            $response['status'] = 'true';
            $itemHtml .= '<option value="">Select Product</option>';
            foreach ($items->result() as $prd) {
                if ($prd->variantName != "") {
                    $itemHtml .= '<option value="' . $prd->productEngName . '-' . $prd->variantName . '" data-expirydate="' . $prd->expiryDate . '" data-tax="' . $prd->tax . '" data-inw-sku="' . $prd->sku . '" id="' . $prd->variantCode . '" data-val="' . $prd->productCode . '" data-stock="' . $prd->stock . '" data-sku="' . $prd->variantSKU . '" data-price="' . $prd->productPrice . '">' . $prd->productEngName . '-' . $prd->variantName . '</option>';
                } else {
                    $itemHtml .= '<option value="' . $prd->productEngName . '" id="' . $prd->variantCode . '" data-stock="' . $prd->stock . '" data-expirydate="' . $prd->expiryDate . '" data-tax="' . $prd->tax . '" data-inw-sku="' . $prd->productSKU . '" data-val="' . $prd->productCode . '" data-sku="' . $prd->productSKU . '" data-price="' . $prd->productPrice . '">' . $prd->productEngName . '</option>';
                }
            }
        } else {
            $response['status'] = 'false';
        }
        $response['items'] =  $itemHtml;
        echo json_encode($response);
    }

    public function getItemStorageUnit()
    {
        $itemCode = $this->input->post('itemCode');
        $storageUnit = '';
        $checkItem = $this->GlobalModel->selectQuery('productmaster.code,unitmaster.code as unitCode,unitmaster.unitName', 'productmaster', array('productmaster.code' => $itemCode), array(), array('unitmaster' => 'unitmaster.code=productmaster.storageUnit'), array('unitmaster' => 'inner'));
        if ($checkItem && $checkItem->num_rows() > 0) {
            $result = $checkItem->result_array()[0];
            $unitText  = $result['unitName'];
            $unitCode  = $result['unitCode'];
            $response['status'] = true;
            $response['unitText'] = $unitText;
            $response['unitCode'] = $unitCode;
        } else {
            $response['status'] = false;
        }

        echo json_encode($response);
    }

    public function getTransferList()
    {
        $data = array();
        $dataCount = 0;
		$frombranchCode="";
		if($this->branchCode!=""){
			$frombranchCode=$this->branchCode;
		}
        $tableName = "inwardentries";
        $search = $this->input->GET("search")['value'];
        $orderColumns = array("inwardentries.batchNo,inwardentries.addDate,inwardentries.code,inwardentries.branchCode as fromBranchCode,inwardentries.supplierCode as toBranchCode,branchmaster.branchName as fromBranchName,br.branchName as toBranchName,inwardentries.inwardDate,inwardentries.total, inwardentries.isActive,inwardentries.isApproved");
        $condition = array('inwardentries.flag' => 'transfer','inwardentries.branchCode'=>$frombranchCode);
        $orderBy = array('inwardentries.id' => 'DESC');
        $joinType = array('branchmaster' => 'inner', 'branchmaster br' => 'inner');
        $join = array('branchmaster' => 'branchmaster.code=inwardentries.branchCode', 'branchmaster br' => 'br.code=inwardentries.supplierCode');
        $groupByColumn = array();
        $limit = $this->input->GET("length");
        $offset = $this->input->GET("start");
        $extraCondition = " inwardentries.isDelete=0 OR inwardentries.isDelete IS NULL";
        $like = array("branchmaster.branchName" => $search . "~both", "inwardentries.code" => $search . "~both", "inwardentries.total" => $search . "~both");
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
                if ($row->isApproved == 1) {
                    $appstatus = "<span class='badge bg-success'>Yes</span>";
                } else {
                    $appstatus = "<span class='badge bg-danger'>No</span>";
                }
                $actionHtml = '<div calss="d-flex">';
                if ($this->rights != '' && $this->rights['view'] == 1) {
                    $actionHtml .= ' <a class="btn btn-success btn-sm cursor_pointer m-2 edit_transfer" data-seq="' . $row->code . '"><i id="view" title="View" class="fa fa-eye"></i></a>';
                }
                if ($row->isApproved == 0) {
                    if ($this->rights != '' && $this->rights['update'] == 1) {
                        $actionHtml .= ' <a href="' . base_url() . 'transfer/edit/' . $row->code . '" class="btn btn-info btn-sm m-2 cursor_pointer"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
                    }
                    if ($this->rights != '' && $this->rights['delete'] == 1) {
                        $actionHtml .= '<a class="btn btn-danger btn-sm m-2 cursor_pointer delete_group" data-seq="' . $row->code . '"><i id="dlt" title="Delete" class="fa fa-trash"></i></a>';
                    }
                }
                $actionHtml .= '</div>';
                $data[] = array(
                    $srno,
                    $row->code,
                    $row->batchNo,
                    date('d/m/Y', strtotime($row->inwardDate)),
                    $row->fromBranchName,
                    $row->toBranchName,
                    $row->total,
                    $appstatus,
                    $actionHtml
                );
                $srno++;
            }
            $dataCount = sizeof($this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, array(), '', '', '', $extraCondition)->result());
        }
        $output = array(
            "draw"              =>     intval($_GET["draw"]),
            "recordsTotal"    =>     $dataCount,
            "recordsFiltered" =>     $dataCount,
            "data"            =>     $data
        );
        echo json_encode($output);
    }

    public function getBranchBatches()
    {
        $branchCode = $this->input->post('branchCode');
        //$batchHtml = '';
        $batchHtml = '<option>Select</option>';
        $Records = $this->GlobalModel->selectQuery(array("inwardentries.batchNo"), 'inwardentries', array('inwardentries.isApproved' => 1, 'inwardentries.isActive' => 1, 'inwardentries.branchCode' => $branchCode));
        if ($Records && $Records->num_rows() > 0) {
            foreach ($Records->result() as $gets) {
                $batchHtml .= "<option value='" . $gets->batchNo . "'>" . $gets->batchNo . "</option>";
            }
            $response['status'] = true;
            $response['batchHtml'] = $batchHtml;
        } else {
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    public function saveTransfer()
    {
        $date = date('Y-m-d H:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $frombranchCode = $this->input->post("fromBranch");
        $tobranchCode = $this->input->post("toBranch");
        $transferDate = $this->input->post("transferDate");
        $total = $this->input->post("total");
        $approve = $this->input->post("approve");
        $isActive = $this->input->post("isActive");
        $ip = $_SERVER['REMOTE_ADDR'];
        $number = rand(10, 99);
        $batchNo = date('dmyhis') . '' . $number;
        $code = 'true';
        $approve = 0;
        if ($this->input->post('approveTransferBtn') == 1) {
            $approve = 1;
        }
        $data = array(
            'batchNo' => $batchNo,
            'branchCode' => $tobranchCode,
            'supplierCode' => $frombranchCode,
            'flag' => 'transfer',
            'total' => $total,
            'isActive' => 1,
            'inwardDate' => $transferDate,
        );
        $data['addID'] = $addID;
        $data['addIP'] = $ip;
        $code = $this->GlobalModel->addNew($data, 'inwardentries', 'IN');

        $fromBatchNo = $this->input->post("batchCode");
        $transferLineCode = $this->input->post("transferLineCode");
        $itemCode = $this->input->post("itemCode");
        $productCode = $this->input->post("productCode");
        $variantCode = $this->input->post("variantCode");
        $itemQty = $this->input->post("itemQty");
        $itemUnit = $this->input->post("dbitemUnit");
        $itemPrice = $this->input->post("itemPrice");
        $subTotal = $this->input->post("subTotal");
        $fromBranchCode = $this->input->post("fromBranch");
        $toBranchCode = $this->input->post("toBranch");
        $expirydate = $this->input->post("expiryDate");
        $sku = $this->input->post("inwsku");
        $tax = $this->input->post("tax");
        $addResultFlagNew = false;
        if (isset($itemCode)) {
            for ($j = 0; $j < sizeof($itemCode); $j++) {
                if ($itemCode[$j] != '') {
                    $subdata = array(
                        'inwardCode' => $code,
                        'batchNo' => $batchNo,
                        'fromBatchNo' => $fromBatchNo[$j],
                        'productCode' => $productCode[$j],
                        'variantCode' => $variantCode[$j],
                        'proNameVarName' => $itemCode[$j],
                        'productQty' => $itemQty[$j],
                        'productUnit' => $itemUnit[$j],
                        'productPrice' => $itemPrice[$j],
                        'subTotal' => $subTotal[$j],
                        'sku' => $sku[$j],
                        'tax' => $tax[$j],
                        'expiryDate' => $expirydate[$j],
                        'isActive' => 1
                    );
                    $subdata['addIP'] = $ip;
                    $subdata['addID'] = $addID;
                }
                $result = $this->GlobalModel->addNew($subdata, 'inwardlineentries', 'INL');
                if ($result != 'false') {
                    if ($approve == 1) {
                        $this->GlobalModel->doEdit(array('isApproved' => 1), 'inwardentries', $code);
                        $this->updateStock($productCode[$j], $variantCode[$j], $itemCode[$j], $itemUnit[$j], $itemQty[$j], $batchNo, $fromBatchNo[$j], $fromBranchCode, $toBranchCode, $sku[$j]);
                    }
                    $addResultFlagNew = true;
                }
            }
        }
        if ($code != 'false') {

            $txt = $code . " - " . $frombranchCode . " of " . $tobranchCode . " having amount " . $total . "  is transfer.";
            $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
            $this->GlobalModel->activity_log($activity_text);
            $response['status'] = true;
            $response['message'] = "Transfer Added Successfully.";
        } else {
            $response['status'] = false;
            $response['message'] = "Failed to Add Transfer.";
        }
        $this->session->set_flashdata('transfer_message', json_encode($response));
        redirect('transfer/listRecords', 'refresh');
    }

    public function updateTransfer()
    {
        $date = date('Y-m-d H:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $transferCode = trim($this->input->post("transferCode"));
        $frombranchCode = trim($this->input->post("fromBranch"));
        $tobranchCode = $this->input->post("toBranch");
        $total = $this->input->post("total");
        $ip = $_SERVER['REMOTE_ADDR'];
        $result = '';
        $data = array(
            'total' => $total,
        );
        $approve = 0;
        if ($this->input->post('approveTransferBtn') == 1) {
            $approve = 1;
        }
        $data['editID'] = $addID;
        $data['editIP'] = $ip;
        $result = $this->GlobalModel->doEdit($data, 'inwardentries', $transferCode);
        $fromBatchNo = $this->input->post("batchCode");
        $batchNo = $this->input->post("batch");
        $transferLineCode = $this->input->post("transferLineCode");
        $itemCode = $this->input->post("itemCode");
        $productCode = $this->input->post("productCode");
        $variantCode = $this->input->post("variantCode");
        $itemQty = $this->input->post("itemQty");
        $itemUnit = $this->input->post("dbitemUnit");
        $itemPrice = $this->input->post("itemPrice");
        $subTotal = $this->input->post("subTotal");
        $fromBranchCode = $this->input->post("fromBranch");
        $toBranchCode = $this->input->post("toBranch");
        $expirydate = $this->input->post("expiryDate");
        $sku = $this->input->post("inwsku");
        $tax = $this->input->post("tax");
        $addResultFlagNew = false;
        if (isset($itemCode)) {
            for ($j = 0; $j < sizeof($itemCode); $j++) {
                if ($itemCode[$j] != '') {
                    $subdata = array(
                        'batchNo' => $batchNo,
                        'fromBatchNo' => $fromBatchNo[$j],
                        'productCode' => $productCode[$j],
                        'variantCode' => $variantCode[$j],
                        'proNameVarName' => $itemCode[$j],
                        'productQty' => $itemQty[$j],
                        'productUnit' => $itemUnit[$j],
                        'productPrice' => $itemPrice[$j],
                        'subTotal' => $subTotal[$j],
                        'sku' => $sku[$j],
                        'tax' => $tax[$j],
                        'expiryDate' => $expirydate[$j],
                        'isActive' => 1
                    );

                    if ($transferLineCode[$j] != "") {
                        $subdata['editIP'] = $ip;
                        $subdata['editID'] = $addID;
                        $subresult = $this->GlobalModel->doEdit($subdata, 'inwardlineentries',  $transferLineCode[$j]);
                        if ($subresult == true) {
                            if ($approve == 1) {
                                $this->GlobalModel->doEdit(array('isApproved' => 1), 'inwardentries', $transferCode);
                                $this->updateStock($productCode[$j], $variantCode[$j], $itemCode[$j], $itemUnit[$j], $itemQty[$j], $batchNo, $fromBatchNo[$j], $fromBranchCode, $toBranchCode, $sku[$j]);
                            }
                            $addResultFlagNew = true;
                        }
                    } else {
                        $subdata['addIP'] = $ip;
                        $subdata['addID'] = $addID;
                        $subdata['inwardCode'] = $transferCode;
                        $subresult = $this->GlobalModel->addNew($subdata, 'inwardlineentries', 'INL');
                        if ($subresult != 'false') {
                            if ($approve == 1) {
                                $this->GlobalModel->doEdit(array('isApproved' => 1), 'inwardentries', $transferCode);
                                $this->updateStock($productCode[$j], $variantCode[$j], $itemCode[$j], $itemUnit[$j], $itemQty[$j], $batchNo, $fromBatchNo[$j], $fromBranchCode, $toBranchCode, $sku[$j]);
                            }
                            $addResultFlagNew = true;
                        }
                    }
                }
            }
        }
        if ($result == true || $addResultFlagNew = true) {
            $txt = $transferCode . " - " . $frombranchCode . " of " . $tobranchCode . " having amount " . $total . "  is transfer.";
            $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
            $this->GlobalModel->activity_log($activity_text);
            $response['status'] = true;
            $response['message'] = "Transfer Updated Successfully.";
        } else {
            $response['status'] = false;
            $response['message'] = "Failed to Add Transfer.";
        }
        $this->session->set_flashdata('transfer_message', json_encode($response));
        redirect('transfer/listRecords', 'refresh');
    }

    public function saveTransferold()
    {
        $date = date('Y-m-d H:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $transferCode = trim($this->input->post("transferCode"));
        $frombranchCode = trim($this->input->post("fromBranch"));
        $tobranchCode = $this->input->post("toBranch");
        $transferDate = $this->input->post("transferDate");
        $total = $this->input->post("total");
        $approve = $this->input->post("approve");
        $isActive = $this->input->post("isActive");
        $ip = $_SERVER['REMOTE_ADDR'];
        $batchNo = 'BT-' . date('dmy-hi');
        $data = array(
            'batchNo' => $batchNo,
            'branchCode' => $frombranchCode,
            'supplierCode' => $tobranchCode,
            'flag' => 'transfer',
            'total' => $total,
            'isActive' => 1
        );
        if ($approve == 1) {
        } else {
            $data['inwardDate'] = $transferDate;
        }
        if ($transferCode != '') {
            $data['editID'] = $addID;
            $data['editIP'] = $ip;
            $code = $this->GlobalModel->doEdit($data, 'inwardentries', $transferCode);
            $code = $transferCode;
            $successMsg = 'Transfer Successfully';
            $warningMsg = "Failed to update Transfer";
        } else {
            $data['addID'] = $addID;
            $data['addIP'] = $ip;
            $code = $this->GlobalModel->addNew($data, 'inwardentries', 'IN');
            $successMsg = 'Transfer Successfully';
            $warningMsg = "Failed to Add Transfer";
        }

        if ($code != 'false') {

            $txt = $code . " - " . $frombranchCode . " of " . $tobranchCode . " having amount " . $total . "  is transfer.";
            $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
            $this->GlobalModel->activity_log($activity_text);
            $response['status'] = true;
            $response['transferCode'] = $code;
            $response['batchNo'] = $batchNo;
            $response['message'] = $successMsg;
        } else {
            $response['status'] = false;
            $response['message'] = $warningMsg;
        }
        echo json_encode($response);
    }

    public function saveTransferLine()
    {
        $role = ($this->session->userdata['logged_in' . $this->session_key]['role']);
        $addID = ($this->session->userdata['logged_in' . $this->session_key]['code']);
        $ip = $_SERVER['REMOTE_ADDR'];
        $transferCode = $this->input->post("transferCode");
        $batchNo = trim($this->input->post("batchNo"));
        $fromBatchNo = trim($this->input->post("fromBatchNo"));
        $transferLineCode = $this->input->post("transferLineCode");
        $itemCode = $this->input->post("itemCode");
        $productCode = $this->input->post("productCode");
        $variantCode = $this->input->post("variantCode");
        $itemQty = $this->input->post("itemQty");
        $itemUnit = $this->input->post("itemUnit");
        $itemPrice = $this->input->post("itemPrice");
        $subTotal = $this->input->post("subTotal");
        $fromBranchCode = $this->input->post("fromBranch");
        $toBranchCode = $this->input->post("toBranch");
        $expirydate = $this->input->post("expirydate");
        $sku = $this->input->post("sku");
        $tax = $this->input->post("tax");
        $approve = $this->input->post("approve");
        $data = array(
            'inwardCode' => $transferCode,
            'batchNo' => $batchNo,
            'fromBatchNo' => $fromBatchNo,
            'productCode' => $productCode,
            'variantCode' => $variantCode,
            'proNameVarName' => $itemCode,
            'productQty' => $itemQty,
            'productUnit' => $itemUnit,
            'productPrice' => $itemPrice,
            'subTotal' => $subTotal,
            'sku' => $sku,
            'tax' => $tax,
            'expiryDate' => $expirydate,
            'isActive' => 1
        );
        if ($transferLineCode != '') {
            $data['editIP'] = $ip;
            $data['editID'] = $addID;
            $result = $this->GlobalModel->doEdit($data, 'inwardlineentries',  $transferLineCode);
        } else {
            $data['addIP'] = $ip;
            $data['addID'] = $addID;
            $result = $this->GlobalModel->addNew($data, 'inwardlineentries', 'INL');
        }
        if ($approve == "1") {
            $this->GlobalModel->doEdit(array('isApproved' => 1), 'inwardentries', $transferCode);
            $this->updateStock($productCode, $variantCode, $itemCode, $itemUnit, $itemQty, $batchNo, $fromBatchNo, $fromBranchCode, $toBranchCode, $sku);
        }
    }

    public function updateStock($productCode, $variantCode, $itemCode, $itemUnit, $itemQty, $batchNo, $fromBatchNo, $fromBranchCode, $toBranchCode, $sku)
    {
        $fromBranchItem = $this->GlobalModel->selectQuery('stockinfo.code,stockinfo.stock', 'stockinfo', array('stockinfo.productCode' => $productCode, 'stockinfo.variantCode' => $variantCode, 'stockinfo.branchCode' => $fromBranchCode, 'stockinfo.unitCode' => $itemUnit, 'stockinfo.batchNo' => $fromBatchNo));
        if ($fromBranchItem) {
            $stockQty = $fromBranchItem->result_array()[0]['stock'];
            $code = $fromBranchItem->result_array()[0]['code'];
            if ($stockQty > 0 &&  $itemQty <= $stockQty) {
                $finalResult = $stockQty - $itemQty;
                $this->GlobalModel->doEdit(array('stockinfo.stock' => $finalResult), 'stockinfo', $code);
            }
        } else {
            $insertData = array(
                'productCode' => $productCode,
                'variantCode' => $variantCode,
                'proNameVarName' => $itemCode,
                'unitCode' => $itemUnit,
                'stock' => $itemQty,
                'sku' => $sku,
                'isActive' => 1,
                'branchCode' => $fromBranchCode,
                'batchNo' => $fromBatchNo,
            );
            $this->GlobalModel->addNew($insertData, 'stockinfo', 'ST');
        }

        $toBranchItem = $this->GlobalModel->selectQuery('stockinfo.stock,stockinfo.code', 'stockinfo', array('stockinfo.productCode' => $productCode, 'stockinfo.variantCode' => $variantCode, 'stockinfo.branchCode' => $toBranchCode, 'stockinfo.unitCode' => $itemUnit, 'stockinfo.batchNo' => $batchNo));
        if ($toBranchItem) {
            $stockQtyItem = $toBranchItem->result_array()[0]['stock'];
            $code = $toBranchItem->result_array()[0]['code'];
            if ($stockQtyItem > 0) {

                $finalResultItem = $stockQtyItem + $itemQty;
                $this->GlobalModel->doEdit(array('stockinfo.stock' => $finalResultItem), 'stockinfo', $code);
            }
        } else {
            $insertData = array(
                'productCode' => $productCode,
                'variantCode' => $variantCode,
                'proNameVarName' => $itemCode,
                'unitCode' => $itemUnit,
                'stock' => $itemQty,
                'isActive' => 1,
                'branchCode' => $toBranchCode,
                'batchNo' => $batchNo,
                'sku' => $sku,
                'fromBatchNo' => $fromBatchNo,
            );
            $this->GlobalModel->addNew($insertData, 'stockinfo', 'ST');
        }
    }

    public function deleteTransferLine()
    {
        $date = date('Y-m-d H:i:s');
        $lineCode = $this->input->post('lineCode');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $ip = $_SERVER['REMOTE_ADDR'];

        $query = $this->GlobalModel->delete($lineCode, 'inwardlineentries');
        if ($query) {
            $getDetails = $this->GlobalModel->selectQuery('inwardlineentries.*', 'inwardlineentries', array('inwardlineentries.isActive' => 1, 'inwardlineentries.code' => $lineCode));
            if ($getDetails && $getDetails->num_rows() > 0) {
                $pert = $getDetails->result()[0];
                $masterDetails = $getDetails = $this->GlobalModel->selectQuery('inwardentries.branchCode,inwardentries.supplierCode', 'inwardentries', array('inwardentries.isActive' => 1, 'inwardentries.inwardCode' => $pert->inwardCode));
                if ($masterDetails && $masterDetails->num_rows() > 0) {
                    $master = $masterDetails->result()[0];
                    $this->updateStock($pert->productCode, $pert->variantCode, $pert->proNameVarName, $pert->productUnit, $pert->productQty, $pert->batchNo, $pert->fromBatchNo, $master->branchCode, $master->supplierCode, 1);
                }
            }
            $txt = $lineCode . " deleted Transfer items";
            $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
            $this->GlobalModel->activity_log($activity_text);
            $msg = true;
            echo json_encode($msg);
        } else {
            $msg = false;
            echo json_encode($msg);
        }
    }

    public function deleteTransfer()
    {
        $code = $this->input->post('code');
        $date = date('Y-m-d H:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $query = $this->GlobalModel->delete($code, 'inwardentries');
        if ($query) {
            $master = $getDetails = $this->GlobalModel->selectQuery('inwardentries.branchCode,inwardentries.supplierCode', 'inwardentries', array('inwardentries.isActive' => 1, 'inwardentries.code' => $code));
            if ($master && $master->num_rows() > 0) {
                foreach ($master->result() as $ms) {
                    $getDetails = $this->GlobalModel->selectQuery('inwardlineentries.*', 'inwardlineentries', array('inwardlineentries.isActive' => 1, 'inwardlineentries.inwardCode' => $ms->code));
                    if ($getDetails && $getDetails->num_rows() > 0) {
                        $pert = $getDetails->result()[0];
                        updateStock($pert->productCode, $pert->variantCode, $pert->proNameVarName, $pert->productUnit, $pert->productQty, $pert->batchNo, $pert->fromBatchNo, $ms->branchCode, $ms->supplierCode, 1);
                        $this->GlobalModel->delete($pert->code, 'inwardlineentries');
                    }
                }
            }
            $txt = $code . " Transfer is deleted.";
            $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
            $this->GlobalModel->activity_log($activity_text);
        }
        echo $query;
    }

    public function edit()
    {
        $i = 0;
        $prdArr = [];
        $code = $this->uri->segment(3);
        $inwardData = $this->GlobalModel->selectQuery('inwardentries.*,branchmaster.branchName as fromBranchName,br.branchName as toBranchName', 'inwardentries', array('inwardentries.code' => $code), array(), array('branchmaster' => 'branchmaster.code=inwardentries.branchCode', 'branchmaster br' => 'br.code=inwardentries.supplierCode'), array('branchmaster' => 'inner', 'branchmaster br' => 'inner'));
        $data['inwardData'] = $inwardData;
        $inwardLineData = $this->GlobalModel->selectQuery('inwardlineentries.*,unitmaster.unitName,stockinfo.stock', 'inwardlineentries', array('inwardlineentries.inwardCode' => $code, 'inwardlineentries.isActive' => 1), array(), array('unitmaster' => 'unitmaster.code=inwardlineentries.productUnit', 'stockinfo' => 'stockinfo.productCode=inwardlineentries.productCode and stockinfo.variantCode=inwardlineentries.variantCode  and stockinfo.batchNo=inwardlineentries.batchNo'), array('unitmaster' => 'inner', 'stockinfo' => 'left'));
        $data['inwardLineEntries'] = $inwardLineData;
        if ($inwardData) {
            $branch = $inwardData->result()[0]->branchCode;
            $data['branches'] = $this->GlobalModel->selectQuery(array("inwardentries.batchNo"), 'inwardentries', array('inwardentries.isApproved' => 1, 'inwardentries.isActive' => 1, 'inwardentries.branchCode' => $branch));
            $data['items'] = $this->GlobalModel->selectQuery('productmaster.*,stockinfo.stock', 'productmaster', array('productmaster.isActive' => 1, 'stockinfo.branchCode' => $branch), array(), array('stockinfo' => 'stockinfo.productCode=productmaster.code'), array('stockinfo' => 'inner'));
            $data['unitmaster'] = $this->GlobalModel->selectActiveData('unitmaster');
            if ($inwardLineData) {
                foreach ($inwardLineData->result() as $inl) {
                    $i++;
                    $batchCode = $inl->fromBatchNo;
                    $items = $this->GlobalModel->selectQuery('inwardlineentries.*,stockinfo.stock,productmaster.productEngName,productmaster.sku as productSKU,productvariants.variantName,productvariants.sku as variantSKU', 'inwardlineentries', array('inwardlineentries.isActive' => 1, 'inwardlineentries.batchNo' => $batchCode), array(), array('productmaster' => 'inwardlineentries.productCode=productmaster.code', 'productvariants' => 'inwardlineentries.variantCode=productvariants.code', 'inwardentries' => 'inwardentries.code=inwardlineentries.inwardCode', 'stockinfo' => 'stockinfo.productCode=inwardlineentries.productCode and stockinfo.variantCode=inwardlineentries.variantCode and stockinfo.branchCode=inwardentries.branchCode and stockinfo.batchNo=inwardlineentries.batchNo'), array('productmaster' => 'inner', 'productvariants' => 'left', 'inwardentries' => 'inner', 'stockinfo' => 'left'));
                    if ($items && $items->num_rows() > 0) {
                        $html = '<option value="">Select Product</option>';
                        foreach ($items->result() as $prd) {
                            $selected = '';
                            if ($prd->variantCode == $inl->variantCode && $prd->productCode == $inl->productCode) $selected = 'selected';
                            if ($prd->variantName != "") {
                                $html .= '<option value="' . $prd->productEngName . '-' . $prd->variantName . '" ' . $selected . ' data-expirydate="' . $prd->expiryDate . '" data-tax="' . $prd->tax . '" data-inw-sku="' . $prd->sku . '" id="' . $prd->variantCode . '" data-val="' . $prd->productCode . '" data-stock="' . $prd->stock . '" data-sku="' . $prd->variantSKU . '" data-price="' . $prd->productPrice . '">' . $prd->productEngName . '-' . $prd->variantName . '</option>';
                            } else {
                                $html .= '<option value="' . $prd->productEngName . '" id="' . $prd->variantCode . '" ' . $selected . ' data-stock="' . $prd->stock . '" data-expirydate="' . $prd->expiryDate . '" data-tax="' . $prd->tax . '" data-inw-sku="' . $prd->sku . '" data-val="' . $prd->productCode . '" data-sku="' . $prd->productSKU . '" data-price="' . $prd->productPrice . '">' . $prd->productEngName . '</option>';
                            }
                        }
                    }
                    array_push($prdArr, $html);
                }
            }
        }
        $data['prdArr'] = $prdArr;
        $this->load->view('dashboard/header');
        $this->load->view('dashboard/transfer/edit', $data);
        $this->load->view('dashboard/footer');
    }

    public function view()
    {
        $code = $this->input->post('code');
        $inwardData = $this->GlobalModel->selectQuery('inwardentries.*,branchmaster.branchName as fromBranchName,br.branchName as toBranchName', 'inwardentries', array('inwardentries.code' => $code), array(), array('branchmaster' => 'branchmaster.code=inwardentries.branchCode', 'branchmaster br' => 'br.code=inwardentries.supplierCode'), array('branchmaster' => 'inner', 'branchmaster br' => 'inner'));
        $data['inwardData'] = $inwardData;
        $inwardLineData = $this->GlobalModel->selectQuery('inwardlineentries.*,unitmaster.unitName', 'inwardlineentries', array('inwardlineentries.inwardCode' => $code, 'inwardlineentries.isActive' => 1), array(), array('unitmaster' => 'unitmaster.code=inwardlineentries.productUnit'), array('unitmaster' => 'inner'));
        $data['inwardLineEntries'] = $inwardLineData;
        $branch = $inwardData->result()[0]->branchCode;
        $data['branches'] = $this->GlobalModel->selectQuery(array("inwardentries.batchNo"), 'inwardentries', array('inwardentries.isActive' => 1, 'inwardentries.branchCode' => $branch));
        $data['items'] = $this->GlobalModel->selectQuery('productmaster.*,stockinfo.stock', 'productmaster', array('productmaster.isActive' => 1, 'stockinfo.branchCode' => $branch), array(), array('stockinfo' => 'stockinfo.productCode=productmaster.code'), array('stockinfo' => 'inner'));
        $data['unitmaster'] = $this->GlobalModel->selectActiveData('unitmaster');

        $this->load->view('dashboard/transfer/view', $data);
    }
}
