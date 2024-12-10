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
		$this->rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		$this->branchCode = $this->session->userdata['logged_in' . $this->session_key]['branchCode'];
        $this->rights = $this->GlobalModel->getMenuRights('3.6', $this->rolecode);
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
            $data['insertRights'] = $this->rights['insert'];
            $data['branch'] = $this->GlobalModel->selectActiveData('branchmaster');
            $data['items'] = $this->GlobalModel->selectQuery('itemmaster.*,stockinfo.stock,stockinfo.itemPrice as price', 'itemmaster', array('itemmaster.isActive' => 1), array(), array('stockinfo' => 'stockinfo.itemCode=itemmaster.code'), array('stockinfo' => 'inner'));
            $data['unitmaster'] = $this->GlobalModel->selectActiveData('unitmaster');
			$data['branchCode']="";
			$data['branchName']="";
			if($this->branchCode!=""){
				$data['branchCode']=$this->branchCode;
				$data['branchName'] = $this->GlobalModel->selectDataById($this->branchCode, 'branchmaster')->result()[0]->branchName;
			}
            $this->load->view('dashboard/commonheader');
            $this->load->view('dashboard/transfer/add', $data);
            $this->load->view('dashboard/footer');
        } else {
            $this->load->view('errors/norights.php');
        }
    }

    public function edit()
    {
        if ($this->rights != '' && $this->rights['update'] == 1) {
            $data['updateRights'] = $this->rights['update'];
            $code = $this->uri->segment(3);
            $inwardData = $this->GlobalModel->selectQuery('inwardentries.*,branchmaster.branchName as fromBranchName,br.branchName as toBranchName', 'inwardentries', array('inwardentries.code' => $code), array(), array('branchmaster' => 'branchmaster.code=inwardentries.branchCode', 'branchmaster br' => 'br.code=inwardentries.supplierCode'), array('branchmaster' => 'inner', 'branchmaster br' => 'inner'));
            $data['inwardData'] = $inwardData;
            $data['inwardLineEntries'] = $this->GlobalModel->selectQuery('inwardlineentries.*,unitmaster.unitName', 'inwardlineentries', array('inwardlineentries.inwardCode' => $code, 'inwardlineentries.isActive' => 1), array(), array('unitmaster' => 'unitmaster.code=inwardlineentries.itemUom'), array('unitmaster' => 'inner'));
            $branch = $inwardData->result()[0]->branchCode;
            $data['items'] = $this->GlobalModel->selectQuery('itemmaster.*,stockinfo.stock,stockinfo.itemPrice as price', 'itemmaster', array('itemmaster.isActive' => 1, 'stockinfo.branchCode' => $branch), array(), array('stockinfo' => 'stockinfo.itemCode=itemmaster.code'), array('stockinfo' => 'inner'));
            $data['unitmaster'] = $this->GlobalModel->selectActiveData('unitmaster');

            $this->load->view('dashboard/commonheader');
            $this->load->view('dashboard/transfer/edit', $data);
            $this->load->view('dashboard/footer');
        } else {
            $this->load->view('errors/norights.php');
        }
    }

    public function view()
    {
        if ($this->rights != '' && $this->rights['update'] == 1) {
            $code = $this->input->post("code");
            $data['userLang'] = ($this->session->userdata['logged_in' . $this->session_key]['lang']);
            $inwardData = $this->GlobalModel->selectQuery('inwardentries.*,branchmaster.branchName as fromBranchName,br.branchName as toBranchName', 'inwardentries', array('inwardentries.code' => $code), array(), array('branchmaster' => 'branchmaster.code=inwardentries.branchCode', 'branchmaster br' => 'br.code=inwardentries.supplierCode'), array('branchmaster' => 'inner', 'branchmaster br' => 'inner'));
            $data['inwardData'] = $inwardData;
            $data['inwardLineEntries'] = $this->GlobalModel->selectQuery('inwardlineentries.*,itemmaster.itemEngName,itemmaster.itemArbName,itemmaster.itemHinName,itemmaster.itemUrduName,unitmaster.unitName', 'inwardlineentries', array('inwardlineentries.inwardCode' => $code, 'inwardlineentries.isActive' => 1), array(), array('unitmaster' => 'unitmaster.code=inwardlineentries.itemUom', 'itemmaster' => 'itemmaster.code=inwardlineentries.itemCode'), array('unitmaster' => 'inner', 'itemmaster' => 'inner'));
            $branch = $inwardData->result()[0]->branchCode;
            $data['items'] = $this->GlobalModel->selectQuery('itemmaster.*,stockinfo.stock', 'itemmaster', array('itemmaster.isActive' => 1, 'stockinfo.branchCode' => $branch), array(), array('stockinfo' => 'stockinfo.itemCode=itemmaster.code'), array('stockinfo' => 'inner'));
            $data['unitmaster'] = $this->GlobalModel->selectActiveData('unitmaster');
            $this->load->view('dashboard/transfer/view', $data);
        } else {
            $this->load->view('errors/norights.php');
        }
    }

    public function getTransferList()
    {
        $tableName = "inwardentries";
        $search = $this->input->GET("search")['value'];
        $orderColumns = array("inwardentries.isApproved,inwardentries.code,inwardentries.branchCode as fromBranchCode,inwardentries.supplierCode as toBranchCode,branchmaster.branchName as fromBranchName,br.branchName as toBranchName,inwardentries.inwardDate,inwardentries.total, inwardentries.isActive");
        $condition = array('inwardentries.flag' => 'transfer');
        $orderBy = array('inwardentries.id' => 'DESC');
        $joinType = array('branchmaster' => 'inner', 'branchmaster br' => 'inner');
        $join = array('branchmaster' => 'branchmaster.code=inwardentries.branchCode', 'branchmaster br' => 'br.code=inwardentries.supplierCode');
        $groupByColumn = array();
        $limit = $this->input->GET("length");
        $offset = $this->input->GET("start");
        $extraCondition = " inwardentries.isDelete=0 OR inwardentries.isDelete IS NULL";
        $like = array("branchmaster.branchName" => $search . "~both","inwardentries.code" => $search . "~both","inwardentries.total" => $search . "~both");
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
                $actionHtml = '<div class="d-flex">';
                if ($this->rights != '' && $this->rights['view'] == 1) {
                    $actionHtml .= '<a class="btn btn-success btn-sm cursor_pointer mx-2 edit_group" data-seq="' . $row->code . '"><i id="view" title="View" class="fa fa-eye"></i></a>';
                }
				if ($row->isApproved == 0) {
                    if ($this->rights != '' && $this->rights['update'] == 1) {
                    $actionHtml .= '<a href="' . base_url() . 'Transfer/edit/' . $row->code . '" class="btn btn-info btn-sm mx-2 cursor_pointer"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
					}
					if ($this->rights != '' && $this->rights['delete'] == 1) {
						$actionHtml .= '<a class="btn btn-danger btn-sm mx-2 cursor_pointer delete_group" data-seq="' . $row->code . '"><i id="dlt" title="Delete" class="fa fa-trash"></i></a>';
					}
                }               
                $actionHtml .='</div>';
                $data[] = array(
                    $srno,
                    $row->code,
                    date('d/m/Y', strtotime($row->inwardDate)),
                    $row->fromBranchName,
                    $row->toBranchName,
                    $row->total,
                    $status,
					$appstatus,
                    $actionHtml
                );
                $srno++;
            }
            $dataCount = sizeof($this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, array(), '', '', '', $extraCondition)->result());
            $output = array(
                "draw"              =>     intval($_GET["draw"]),
                "recordsTotal"    =>      $dataCount,
                "recordsFiltered" =>     $dataCount,
                "data"            =>     $data
            );
            echo json_encode($output);
        } else {
            $dataCount = 0;
            $data = array();
            $output = array(
                "draw"              =>     intval($_GET["draw"]),
                "recordsTotal"    =>     $dataCount,
                "recordsFiltered" =>     $dataCount,
                "data"            =>     $data
            );
            echo json_encode($output);
        }
    }

    public function saveTransfer(){
		$date = date('Y-m-d H:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$transferCode = trim($this->input->post("transferCode"));
        $frombranchCode = trim($this->input->post("fromBranch"));
        $tobranchCode = $this->input->post("toBranch");
        $transferDate = $this->input->post("transferDate");
        $total = $this->input->post("total");
        $ip = $_SERVER['REMOTE_ADDR'];
		$code='true';
		$data = array(
            'branchCode' => $frombranchCode,
            'supplierCode' => $tobranchCode,
            'inwardDate' => $transferDate,
            'flag' => 'transfer',
            'total' => $total,
            'isActive' => 1
        );
		$approve = 0;
		if($this->input->post('approveTransferBtn')==1) {
		   $approve = 1;
		}
		$code = $this->GlobalModel->addNew($data, 'inwardentries', 'IN');
        $transferLineCode = $this->input->post("transferLineCode");
        $itemCode = $this->input->post("itemCode");
        $itemQty = $this->input->post("itemQty");
        $itemUnit = $this->input->post("itemUnit");
        $itemPrice = $this->input->post("itemPrice");
        $subTotal = $this->input->post("subTotal");
        $fromBranchCode = $this->input->post("fromBranch");
        $toBranchCode = $this->input->post("toBranch");
        $addResultFlagNew = false;
		if (isset($itemCode)) {
			for ($j = 0; $j < sizeof($itemCode); $j++) {
				if ($itemCode[$j] != '') {
					$subdata = array(
						'inwardCode' => $code,
						'itemCode' => $itemCode[$j],
						'itemQty' => $itemQty[$j],
						'itemUom' => $itemUnit[$j],
						'itemPrice' => $itemPrice[$j],
						'subTotal' => $subTotal[$j],
						'isActive' => 1
					);
					$subdata['addIP'] = $ip;
					$subdata['addID'] = $addID;
				}
				 $result = $this->GlobalModel->addNew($subdata, 'inwardlineentries', 'INL');
				if ($result != 'false') {
					 if($approve==1) {
					       $this->GlobalModel->doEdit(array('isApproved' => 1), 'inwardentries', $code);
					       $this->updateStock($itemCode[$j], $itemUnit[$j], $itemQty[$j], $fromBranchCode, $toBranchCode,$itemPrice[$j]);
					 }
					$addResultFlagNew = true;
				}
			}
		}
		
		if ($code != 'false' || $addResultFlagNew == true) {
			$txt = $code . " - " . $frombranchCode . " of " . $tobranchCode . " having amount " . $total . "  is transfer.";
            $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
            $this->GlobalModel->activity_log($activity_text);
			$response['status'] = true;
			$response['test'] = "Transfer Added Successfully.";
		} else {
			$response['status'] = false;
			$response['test'] = "Failed to Add Transfer.";
		}
		$this->session->set_flashdata('test', json_encode($response));
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
        $transferDate = $this->input->post("transferDate");
        $total = $this->input->post("total");
        $ip = $_SERVER['REMOTE_ADDR'];
		$approve = 0;
		if($this->input->post('approveTransferBtn')==1) {
		   $approve = 1;
		}
		$data = array(
            'branchCode' => $frombranchCode,
            'supplierCode' => $tobranchCode,
            'inwardDate' => $transferDate,
            'flag' => 'transfer',
            'total' => $total,
            'isActive' => 1
        );
		$data['editID'] = $addID; 
		$data['editIP'] = $ip;
	    $result = $this->GlobalModel->doEdit($data, 'inwardentries', $transferCode);
		
		$transferLineCode = $this->input->post("transferLineCode");
        $itemCode = $this->input->post("itemCode");
        $itemQty = $this->input->post("itemQty");
        $itemUnit = $this->input->post("itemUnit");
        $itemPrice = $this->input->post("itemPrice");
        $subTotal = $this->input->post("subTotal");
        $fromBranchCode = $this->input->post("fromBranch");
        $toBranchCode = $this->input->post("toBranch");
        $addResultFlagNew = false;
		if (isset($itemCode)) {
			for ($j = 0; $j < sizeof($itemCode); $j++) {
				if ($itemCode[$j] != '') {
					$subdata = array(
						'inwardCode' => $transferCode,
						'itemCode' => $itemCode[$j],
						'itemQty' => $itemQty[$j],
						'itemUom' => $itemUnit[$j],
						'itemPrice' => $itemPrice[$j],
						'subTotal' => $subTotal[$j],
						'isActive' => 1
					);
					if($transferLineCode[$j]!=""){
					    $subdata['editIP'] = $ip;
					    $subdata['editID'] = $addID;
						 $result = $this->GlobalModel->doEdit($subdata, 'inwardlineentries',  $transferLineCode[$j]);
						if ($result == true) {
							if($approve==1) {
					          $this->GlobalModel->doEdit(array('isApproved' => 1), 'inwardentries', $transferCode);
							  $this->updateStock($itemCode[$j], $itemUnit[$j], $itemQty[$j], $fromBranchCode, $toBranchCode,$itemPrice[$j]);	
                            }							
							$addResultFlagNew = true;
						}
					} else {						
					    $subdata['addIP'] = $ip;
					    $subdata['addID'] = $addID;
						$result = $this->GlobalModel->addNew($subdata, 'inwardlineentries', 'INL');
						if ($result == true) {
							if($approve==1) {
					          $this->GlobalModel->doEdit(array('isApproved' => 1), 'inwardentries', $transferCode);
							  $this->updateStock($itemCode[$j], $itemUnit[$j], $itemQty[$j], $fromBranchCode, $toBranchCode,$itemPrice[$j]);
							}							  
							$addResultFlagNew = true;
						}
					}
				}
			}
		}
	    if ($result==true || $addResultFlagNew = true) {
			$txt = $transferCode . " - " . $frombranchCode . " of " . $tobranchCode . " having amount " . $total . "  is transfer.";
            $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
            $this->GlobalModel->activity_log($activity_text);
			$response['status'] = true;
			$response['test'] = "Transfer Updated Successfully.";
		} else {
			$response['status'] = false;
			$response['test'] = "Failed to Add Transfer.";
		}
		$this->session->set_flashdata('test', json_encode($response));
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
        $isActive = $this->input->post("isActive");
        $ip = $_SERVER['REMOTE_ADDR'];
        $data = array(
            'branchCode' => $frombranchCode,
            'supplierCode' => $tobranchCode,
            'inwardDate' => $transferDate,
            'flag' => 'transfer',
            'total' => $total,
            'isActive' => 1
        );
        if ($transferCode != '') {
            $data['editID'] = $addID;
            $data['editIP'] = $ip;
            $code = $this->GlobalModel->doEdit($data, 'inwardentries', $transferCode);
            $code = $transferCode;
            $successMsg = 'Transfer updated Successfully';
            $warningMsg = "Failed to update Transfer";
        } else {
            $data['addID'] = $addID;
            $data['addIP'] = $ip;
            $code = $this->GlobalModel->addNew($data, 'inwardentries', 'IN');
            $successMsg = 'Transfer Added Successfully';
            $warningMsg = "Failed to Add Transfer";
        }

        if ($code != 'false') {

            $txt = $code . " - " . $frombranchCode . " of " . $tobranchCode . " having amount " . $total . "  is transfer.";
            $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
            $this->GlobalModel->activity_log($activity_text);
            $response['status'] = true;
            $response['transferCode'] = $code;
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
        $transferLineCode = $this->input->post("transferLineCode");
        $itemCode = $this->input->post("itemCode");
        $itemQty = $this->input->post("itemQty");
        $itemUnit = $this->input->post("itemUnit");
        $itemPrice = $this->input->post("itemPrice");
        $subTotal = $this->input->post("subTotal");
        $fromBranchCode = $this->input->post("fromBranch");
        $toBranchCode = $this->input->post("toBranch");
        $data = array(
            'inwardCode' => $transferCode,
            'itemCode' => $itemCode,
            'itemQty' => $itemQty,
            'itemUom' => $itemUnit,
            'itemPrice' => $itemPrice,
            'subTotal' => $subTotal,
            'isActive' => 1
        );
        if ($transferLineCode != '') {
            $data['editIP'] = $ip;
            $data['editID'] = $addID;
            $result = $this->GlobalModel->doEdit($data, 'inwardlineentries',  $transferLineCode);
            if ($result != 'false') {
                $this->updateStock($itemCode, $itemUnit, $itemQty, $fromBranchCode, $toBranchCode);
            }
            $result =  $transferLineCode;
        } else {
            $data['addIP'] = $ip;
            $data['addID'] = $addID;
            $result = $this->GlobalModel->addNew($data, 'inwardlineentries', 'INL');
            if ($result != 'false') {
                $this->updateStock($itemCode, $itemUnit, $itemQty, $fromBranchCode, $toBranchCode);
            }
        }
    }
    public function getItemStorageUnit()
    {
        $itemCode = $this->input->post('itemCode');
        $storageUnit = '';
        $checkItem = $this->GlobalModel->selectQuery('itemmaster.storageUnit,unitmaster.code', 'itemmaster', array('itemmaster.code' => $itemCode), array(), array('unitmaster' => 'unitmaster.code=itemmaster.storageUnit'), array('unitmaster' => 'inner'));
        if ($checkItem && $checkItem->num_rows() > 0) {
            $storageUnit = $checkItem->result_array()[0]['code'];
        }
        echo $storageUnit;
    }

    public function updateStock($itemCode, $itemUnit, $itemQty, $fromBranchCode, $toBranchCode,$itemPrice) 
    {
        $fromBranchItem = $this->GlobalModel->selectQuery('stockinfo.*', 'stockinfo', array('stockinfo.itemCode' => $itemCode, 'stockinfo.branchCode' => $fromBranchCode), array(), array(), array());
        //$item = $this->GlobalModel->get_data_by_field($itemCode, 'stockinfo');
        if ($fromBranchItem) {
            $stockQty = $fromBranchItem->result_array()[0]['stock'];
            if ($stockQty > 0 &&  $itemQty <= $stockQty) {
                $finalResult = $stockQty - $itemQty;
                //$subdata = ["stock" => $finalResult];
                $updateQuery = $this->GlobalModel->plainQuery("UPDATE `stockinfo` SET `stock` = '" . $finalResult . "' WHERE `itemCode` ='" . $itemCode . "' and  `branchCode` ='" . $fromBranchCode . "'");
                //$code = $this->GlobalModel->doEditWithField($subdata, 'stockinfo', 'itemCode', $itemCode);
            }
        } else {
            $insertData = array(
                'itemCode' => $itemCode,
                'unitCode' =>  $itemUnit,
                'stock' => $itemQty,
                'isActive' => 1,
                'branchCode' => $fromBranchCode,
				'itemPrice'=>$itemPrice
            );
            $this->GlobalModel->addNew($insertData, 'stockinfo', 'ST'); 
        }

        $toBranchItem = $this->GlobalModel->selectQuery('stockinfo.*', 'stockinfo', array('stockinfo.itemCode' => $itemCode, 'stockinfo.branchCode' => $toBranchCode), array(), array(), array());

        if ($toBranchItem) {
            $stockQtyItem = $toBranchItem->result_array()[0]['stock'];
            if ($stockQtyItem > 0) {
                $finalResultItem = $stockQtyItem + $itemQty;
                $updateQuery = $this->GlobalModel->plainQuery("UPDATE `stockinfo` SET `stock` = '" . $finalResultItem . "' WHERE `itemCode` ='" . $itemCode . "' and  `branchCode` ='" . $toBranchCode . "'");
            }
        } else {

            $insertData = array(
                'itemCode' => $itemCode,
                'unitCode' =>  $itemUnit,
                'stock' => $itemQty,
                'isActive' => 1,
                'branchCode' => $toBranchCode,
				'itemPrice'=>$itemPrice
            );
            $this->GlobalModel->addNew($insertData, 'stockinfo', 'ST');
        }
    }

    public function getItems() 
    {
        $fromBranchCode = $this->input->post("branchCode");
        $itemHtml = '';
        $items = $this->GlobalModel->selectQuery('itemmaster.*,stockinfo.stock,stockinfo.itemPrice as price', 'itemmaster', array('itemmaster.isActive' => 1, 'stockinfo.branchCode' => $fromBranchCode), array(), array('stockinfo' => 'stockinfo.itemCode=itemmaster.code'), array('stockinfo' => 'inner'));
        //echo $this->db->last_query();
        if ($items && $items->num_rows() > 0) {
            $response['status'] = 'true';
            $itemHtml .= '<option value="">Select Item</option>';
            foreach ($items->result() as $prd) {
                $itemHtml .= '<option value="' . $prd->code . '"  data-stock="' . $prd->stock . '" data-price="' . $prd->price . '">' . $prd->itemEngName . '</option>';
            }
        } else {
            $response['status'] = 'false';
        }
        $response['items'] =  $itemHtml;

        echo json_encode($response);
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
            $txt = $code . " Transfer is deleted.";
            $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
            $this->GlobalModel->activity_log($activity_text);
        }
        echo $query;
    }
}
