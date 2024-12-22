<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Table extends CI_Controller
{
    var $session_key;
    protected $rolecode, $branchCode;
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form', 'url', 'html');
        $this->load->model('GlobalModel');
        $this->load->library('QrCodeGenerator');
        $this->load->library('form_validation');
        $this->session_key = $this->session->userdata('key' . SESS_KEY);
        //$rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
        if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
            redirect('Login', 'refresh');
        }
        $this->rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
        $this->branchCode = $this->session->userdata['logged_in' . $this->session_key]['branchCode'];
        $this->rights = $this->GlobalModel->getMenuRights('8.3', $this->rolecode);
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
            $data['branchCode'] = "";
            $data['branchName'] = "";
            if ($this->branchCode != "") {
                $data['branchCode'] = $this->branchCode;
                $data['branchName'] = $this->GlobalModel->selectDataById($this->branchCode, 'branchmaster')->result()[0]->branchName;
            }
            $this->load->view('dashboard/header');
            $this->load->view('dashboard/table/list', $data);
            $this->load->view('dashboard/footer');
        } else {
            $this->load->view('errors/norights.php');
        }
    }

    public function getTableList()
    {
        $branch = "";
        if ($this->branchCode != "") {
            $branch = $this->branchCode;
        }
        $tableName = "tablemaster";
        $search = $this->input->GET("search")['value'];
        $orderColumns = array("tablemaster.code,sectorzonemaster.zoneName,branchmaster.branchName,tablemaster.tableNumber,tablemaster.tableSeats,tablemaster.isActive");
        $condition = array('branchmaster.isActive' => 1, 'branchmaster.code' => $branch);
        $orderBy = array('tablemaster.id' => 'DESC');
        $joinType = array('sectorzonemaster' => 'inner', 'branchmaster' => 'inner');
        $join = array('sectorzonemaster' => 'sectorzonemaster.id=tablemaster.zoneCode', 'branchmaster' => 'branchmaster.code=sectorzonemaster.branchCode');
        $groupByColumn = array();
        $limit = $this->input->GET("length");
        $offset = $this->input->GET("start");
        $extraCondition = " tablemaster.isDelete=0 OR tablemaster.isDelete IS NULL";
        $like = array("tablemaster.tableNumber" => $search . "~both", "tablemaster.code" => $search . "~both", "sectorzonemaster.zoneName" => $search . "~both", "branchmaster.branchName" => $search . "~both", "tablemaster.tableSeats" => $search . "~both");
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
                $actionHtml = '<div class="d-flex">';
                if ($this->rights != '' && $this->rights['view'] == 1) {
                    $actionHtml .= '<a id="view" class="btn btn-sm btn-success m-1 cursor_pointer edit_table" data-seq="' . $row->code . '" data-type="1"><i id="edt" title="View" class="fa fa-eye"></i></a>';
                }
                if ($this->rights != '' && $this->rights['update'] == 1) {
                    $actionHtml .= '<a id="edit" class="btn btn-sm btn-info m-1 cursor_pointer edit_table" data-seq="' . $row->code . '" data-type="2"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
                }
                if ($this->rights != '' && $this->rights['delete'] == 1) {
                    $actionHtml .= '<a id="delete" class="btn btn-sm btn-danger m-1 cursor_pointer delete_table" data-seq="' . $row->code . '"><i id="dlt" title="Delete" class="fa fa-trash"></i></a>';
                }
                $actionHtml .= '</div>';
                $data[] = array(
                    $srno,
                    $row->code,
                    $row->branchName,
                    $row->zoneName,
                    $row->tableNumber,
                    $row->tableSeats,
                    $status,
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

    public function saveTable()
    {
        $date = date('Y-m-d H:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $tableSeats = $this->input->post("tableSeats");
        $tableCode = $this->input->post("code");
        $zoneCode = $this->input->post("zoneCode");
        $isActive = $this->input->post("isActive");
        $branchCode = trim($this->input->post('branchCode'));
        $urlToken = $this->GlobalModel->randomCharacters(10);
        $nextIncId = 0;
        $nextInc = $this->GlobalModel->getMaxIdBeforeInsert('tablemaster');
        if ($nextInc != false) {
            $nextIncId = $nextInc->Auto_increment;
        }
        $ip = $_SERVER['REMOTE_ADDR'];
        $tableNumber = $zoneCode . '.' . $nextIncId;
        $data = array(
            'branchCode' => $branchCode,
            'tableSeats' => $tableSeats,
            'isActive' => $isActive
        );
        if ($tableCode != '') {
            $data['editID'] = $addID;
            $data['editIP'] = $ip;
            $code = $this->GlobalModel->doEdit($data, 'tablemaster', $tableCode);
            $code = $tableCode;
            $successMsg = "Table Updated Successfully";
            $errorMsg = "Failed To Update Table";
            $txt = $code . " table is updated.";
        } else {
            $data['urlToken'] = $urlToken;
            $data['zoneCode'] = $zoneCode;
            $data['tableNumber'] = $tableNumber;
            $data['addID'] = $addID;
            $data['addIP'] = $ip;
            $code = $this->GlobalModel->addWithoutYear($data, 'tablemaster', 'TAB');
            $successMsg = "Table Added Successfully";
            $errorMsg = "Failed To Add Table";
            $txt = $code . "  table is added.";
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
        echo json_encode($response);
    }

    public function saveTableBulk()
    {
        $date = date('Y-m-d H:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $zoneCode = $this->input->post("bulkzoneCode");
        $bulktableSeats = $this->input->post("bulktableSeats");
        $noOfTables = $this->input->post("noOfTables");
        $branchCode = trim($this->input->post('bulkbranchCode'));
        $isActive = $this->input->post("isActive");
        $ip = $_SERVER['REMOTE_ADDR'];
        $cnt = 0;
        for ($i = 1; $i <= $noOfTables; $i++) {
            $urlToken = $this->GlobalModel->randomCharacters(10);
            $nextIncId = 0;
            $nextInc = $this->GlobalModel->getMaxIdBeforeInsert('tablemaster');
            if ($nextInc != false) {
                $nextIncId = $nextInc->Auto_increment;
            }
            $tableNumber = $zoneCode . '.' . $nextIncId;
            $data = array(
                'branchCode' => $branchCode,
                'zoneCode' => $zoneCode,
                'tableSeats' => $bulktableSeats,
                'tableNumber' => $tableNumber,
                'isActive' => $isActive,
                'addID' => $addID,
                'addIP' => $ip,
                'urlToken' => $urlToken
            );
            $code = $this->GlobalModel->addWithoutYear($data, 'tablemaster', 'TAB');
            if ($code != 'false') {
                $cnt++;
            }
        }
        if ($cnt == $noOfTables) {
            $txt = $noOfTables . " Bulk tables are added.";
            $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
            $this->GlobalModel->activity_log($activity_text);
            $response['status'] = true;
            $response['message'] = "Bulk tables created Successfully";
        } else {
            $response['status'] = false;
            $response['message'] = "Failed to create Bulk table";
        }
        echo json_encode($response);
    }

    public function editTable()
    {
        $cmpdbkey =  $this->session->userdata['logged_in' . $this->session_key]['cmpdbkey'];
        $code = $this->input->post('code');
        $checkUrlToken = $this->GlobalModel->selectQuery("tablemaster.urlToken", 'tablemaster', array('tablemaster.code' => $code));
        if ($checkUrlToken) {
            $dbUrlToken = $checkUrlToken->result()[0]->urlToken;
            if ($dbUrlToken == '' || $dbUrlToken == NULL) {
                $urlToken = $this->GlobalModel->randomCharacters(10);
                $this->GlobalModel->doEdit(array('urlToken' => $urlToken), 'tablemaster', $code);
            }
        }
        $TableQuery = $this->GlobalModel->selectQuery("tablemaster.code,tablemaster.urlToken,tablemaster.zoneCode,tablemaster.tableNumber,tablemaster.tableSeats,tablemaster.isActive", 'tablemaster', array('tablemaster.code' => $code));
        if ($TableQuery) {
            $result = $TableQuery->result_array()[0];
            $zoneId = $result["zoneCode"];
            $data['branches'] = [];
            $branches = $this->GlobalModel->selectQuery("branchmaster.code, branchmaster.branchName", "branchmaster", ["branchmaster.isDelete!=" => 1]);
            if ($branches) {
                $data['branches'] = $branches->result_array();
            }
            $data['branchCode'] = "";
            $data['zones'] = [];
            $zones = $this->GlobalModel->selectQuery("sectorzonemaster.id, sectorzonemaster.zoneName, sectorzonemaster.branchCode", "sectorzonemaster", ["sectorzonemaster.id" => $zoneId]);
            if ($zones) {
                $data['zones'] = $zones->result_array();
                $data['branchCode'] = $zones->result_array()[0]['branchCode'];
            }
            $data['status'] = true;
            $data['code'] = $result['code'];
            $data['zoneCode'] = $result['zoneCode'];
            $data['tableNumber'] = $result['tableNumber'];
            $data['tableSeats'] = $result['tableSeats'];
            $data['isActive'] = $result['isActive'];
            $data['urlToken'] = $result['urlToken'];
            $enccmp = $this->encrypt($cmpdbkey);
            $text = base_url('restaurant/table/' . $enccmp . '/' . $result['urlToken']);
            $qrImageGenerateUrl = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$text%2F&choe=UTF-8";
            $data['qrCodeImage'] = $qrImageGenerateUrl;
        } else {
            $data['status'] = false;
        }
        echo json_encode($data);
    }

    public function deleteTable()
    {
        $code = $this->input->post('code');
        $date = date('Y-m-d H:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $loginrole = "";
        $ip = $_SERVER['REMOTE_ADDR'];
        $tableNumber = $this->GlobalModel->selectDataById($code, 'tablemaster')->result()[0]->tableNumber;
        $query = $this->GlobalModel->delete($code, 'tablemaster');
        if ($query) {
            $txt = $code . " - " . $tableNumber . " table is deleted.";
            $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
            $this->GlobalModel->activity_log($activity_text);
        }
        echo $query;
    }

    public function downloadQrImg()
    {
        $urlToken = $this->input->post('urlToken');
        $code = $this->input->post('code');
        $cmpdbkey =  $this->session->userdata['logged_in' . $this->session_key]['cmpdbkey'];
        $enccmp = $this->encrypt($cmpdbkey);
        $uploadDir = 'upload/tables';
        $filename = $uploadDir . '/' . $code . '.png';
        $params['data'] = base_url('restaurant/table/' . $enccmp . '/' . $urlToken);
        $params['level'] = 'H';
        $params['size'] = 10;
        $params['savename'] = $filename;
        $this->qrcodegenerator->generate($params);
        if (file_exists($filename)) {
            $response['status'] = true;
            $response['file'] = $filename;
            $response['qrImg'] = base_url() . $filename;
        } else {
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    public function removeFile()
    {
        $filename = $this->input->post("file");
        if (file_exists($filename)) {
            unlink($filename);
        }
    }

    public function encrypt($string)
    {
        $ciphering = "AES-128-CTR";
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        $encryption = openssl_encrypt($string, $ciphering, ENCKEY, $options, ENCIV);
        return $encryption;
    }

    public function decrypt($string)
    {
        $ciphering = "AES-128-CTR";
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        $decryption = openssl_decrypt($string, $ciphering, ENCKEY, $options, ENCIV);
        return $decryption;
    }
}
