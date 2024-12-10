<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProductCategory extends CI_Controller
{
    var $session_key;
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form', 'url', 'html');
        $this->load->model('GlobalModel');
        $this->load->library('form_validation');
        $this->session_key = $this->session->userdata('key' . SESS_KEY);
        $rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
        if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
            redirect('Login', 'refresh');
        }
        $this->rights = $this->GlobalModel->getMenuRights('2.1', $rolecode);
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
            $this->load->view('dashboard/productcategory/list', $data);
            $this->load->view('dashboard/footer');
        } else {
            $this->load->view('errors/norights.php');
        }
    }
    public function getCategoryList()
    {
        $tableName = "productcategorymaster";
        $search = $this->input->GET("search")['value'];
        $orderColumns = array("productcategorymaster.code,productcategorymaster.categoryName,productcategorymaster.icon,productcategorymaster.categorySName,productcategorymaster.isActive");
        $condition = array("productcategorymaster.isDelete !=" => 1);
        $orderBy = array('productcategorymaster.id' => 'DESC');
        $joinType = array();
        $join = array();
        $groupByColumn = array();
        $limit = $this->input->GET("length");
        $offset = $this->input->GET("start");
        $extraCondition = "";
        $like = array("productcategorymaster.code" => $search . "~both","productcategorymaster.categoryName" => $search . "~both");
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
                    $actionHtml = ' <a class="view_category btn btn-success btn-sm m-1 cursor_pointer" data-seq="' . $row->code . '" data-type="1"><i id="edt" title="View" class="fa fa-eye"></i></a>';
                }
                if ($this->rights != '' && $this->rights['update'] == 1) {
                    $actionHtml .= '<a class="edit_category btn btn-info btn-sm m-1 cursor_pointer" data-seq="' . $row->code . '" data-type="2"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
                }
                if ($this->rights != '' && $this->rights['delete'] == 1) {
                    $actionHtml .= '<a class="btn btn-sm btn-danger m-1 delete_category" data-seq="' . $row->code . '"><i id="dlt" title="Delete"  class="fa fa-trash"></i></a></div>';
                }
                $iconPath = '';
                if ($row->icon != '') {
                    $iconPath = "<img src='" . base_url() . $row->icon . "' height='50' width='50' alt='Category Image'>";
                }
                $data[] = array(
                    $srno,
                    $row->code,
                    $row->categoryName,
                    //$row->categorySName,
                    $iconPath,
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

    public function saveCategory()
    {
        $date = date('Y-m-d H:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$cmpcode = $this->GlobalModel->getCompcode();
        $loginrole = "";
        $categoryName = trim($this->input->post("categoryName"));
        $categorySName = $this->input->post("categorySName");
        $description = $this->input->post("description");
        $categoryCode = $this->input->post("code");
        $previousIcon = $this->input->post("previousIcon");
        $isActive = $this->input->post("isActive");
        $ip = $_SERVER['REMOTE_ADDR'];
        $condition2 = array('LOWER(categoryName)' => strtolower($categoryName));
        if ($categoryCode != '') {
            $condition2['productcategorymaster.code!='] = $categoryCode;
        }
        $result = $this->GlobalModel->checkDuplicateRecordNew($condition2, 'productcategorymaster');
        if ($result == true) {
            $response['status'] = false;
            $response['message'] = 'Duplicate Product Category';
        } else {
            $data = array(
                'categoryName' => $categoryName,
                'categorySName' => $categorySName,
                'description' => $description,
                'isActive' => $isActive
            );
            if ($categoryCode != '') {
                $data['editID'] = $addID;
                $data['editIP'] = $ip;
                $code = $this->GlobalModel->doEdit($data, 'productcategorymaster', $categoryCode);
                $code = $categoryCode;
                $successMsg = "Product Catgeory Updated Successfully";
                $errorMsg = "Failed To Update Catgeory";
                $txt = $code . " - " . $categoryName . " product category is updated.";
            } else {
                $data['addID'] = $addID;
                $data['addIP'] = $ip;
                $code = $this->GlobalModel->addWithoutYear($data, 'productcategorymaster', 'PCAT');
                $successMsg = "Product Category Added Successfully";
                $errorMsg = "Failed To Add Category";
                $txt = $code . " - " . $categoryName . " product category is added.";
            }
            if ($code != 'false') {
                $categoryIcon =  "";
                if (file_exists($previousIcon)) {
                    unlink($previousIcon);
                }
                $uploadDir = "upload/product/category/$cmpcode";
                if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
                if (!empty($_FILES['categoryIcon']['name'])) {
                    $tmpFile = $_FILES['categoryIcon']['tmp_name'];
                    $ext = pathinfo($_FILES['categoryIcon']['name'], PATHINFO_EXTENSION);
                    $filename = $uploadDir . '/' . $code . '-' . time() . '.' . $ext;
                    move_uploaded_file($tmpFile, $filename);
                    if (file_exists($filename)) {
                        $categoryIcon = $filename;
                    }
                }
                $subData = array(
                    'icon' => $categoryIcon 
                );
                $filedoc = $this->GlobalModel->doEdit($subData, 'productcategorymaster', $code);
                $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
                $this->GlobalModel->activity_log($activity_text);
                $response['status'] = true;
                $response['message'] = $successMsg;
            } else {
                $response['status'] = false;
                $response['message'] = $errorMsg;
            }
        }
        echo json_encode($response);
    }

    public function editCategory()
    {
        $code = $this->input->post('code');
        $categoryQuery = $this->GlobalModel->selectQuery("productcategorymaster.code,productcategorymaster.categoryName,productcategorymaster.categorySName,productcategorymaster.description,productcategorymaster.icon,productcategorymaster.isActive", 'productcategorymaster', array('productcategorymaster.code' => $code));
        if ($categoryQuery) {
            $result = $categoryQuery->result_array()[0];
            $data['status'] = true;
            $data['code'] = $result['code'];
            $data['categoryName'] = $result['categoryName'];
            $data['categorySName'] = $result['categorySName'];
            $data['description'] = $result['description'];
            $data['isActive'] = $result['isActive'];
            $data['icon'] = '';
            $data['previousIcon'] = $result['icon'];
            if ($result['icon'] != '') {
                $data['icon'] = base_url() . $result['icon'];
            }
        } else {
            $data['status'] = false;
        }
        echo json_encode($data);
    }

    public function viewCategory()
    {
        $code = $this->input->post('code');
        $categoryQuery = $this->GlobalModel->selectQuery("productcategorymaster.code,productcategorymaster.categoryName,productcategorymaster.categorySName,productcategorymaster.description,productcategorymaster.icon,productcategorymaster.isActive", 'productcategorymaster', array('productcategorymaster.code' => $code));
        if ($categoryQuery) {
            $result = $categoryQuery->result_array()[0];
            $data['status'] = true;
            $data['code'] = $result['code'];
            $data['categoryName'] = $result['categoryName'];
            $data['categorySName'] = $result['categorySName'];
            $data['description'] = $result['description'];
            $data['isActive'] = $result['isActive'];
            $data['icon'] = '';
            $data['previousIcon'] = $result['icon'];
            if ($result['icon'] != '') {
                $data['icon'] = base_url() . $result['icon'];
            }
        } else {
            $data['status'] = false;
        }
        echo json_encode($data);
    }


    public function deleteCategory()
    {
        if ($this->rights != '' && $this->rights['view'] == 1) {
            $code = $this->input->post('code');
            $date = date('Y-m-d H:i:s');
            $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
            $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
            $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
            $loginrole = "";
            $ip = $_SERVER['REMOTE_ADDR'];
            $categoryName = $this->GlobalModel->selectDataById($code, 'productcategorymaster')->result()[0]->categoryName;
            $query = $this->GlobalModel->delete($code, 'productcategorymaster');
            if ($query) {
                $txt = $code . " - " . $categoryName . " product category is deleted.";
                $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
                $this->GlobalModel->activity_log($activity_text);
            }
            echo $query;
        } else {
            $this->load->view('errors/norights.php');
        }
    }
}
