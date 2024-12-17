<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProductSubcategory extends CI_Controller
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
        $this->rights = $this->GlobalModel->getMenuRights('2.2', $rolecode);
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
        $table_category = 'productcategorymaster';
        $orderColumns_category = array("productcategorymaster.*");
        $cond_category = array('productcategorymaster' . '.isDelete' => 0, 'productcategorymaster' . '.isActive' => 1);
        $data['categorydata'] = $this->GlobalModel->selectQuery($orderColumns_category, $table_category, $cond_category);

        if ($this->rights != '' && $this->rights['view'] == 1) {
            $data['insertRights'] = $this->rights['insert'];
            $this->load->view('dashboard/header');
            $this->load->view('dashboard/productsubcategory/list', $data);
            $this->load->view('dashboard/footer');
        } else {
            $this->load->view('errors/norights.php');
        }
    }
    public function getSubCategoryList()
    {
        $tableName = "productsubcategorymaster";
        $search = $this->input->GET("search")['value'];
        $orderColumns = array("productsubcategorymaster.code,productsubcategorymaster.subcategoryName,productsubcategorymaster.subcategorySName,productsubcategorymaster.icon,productcategorymaster.categoryName,productsubcategorymaster.isActive");
        $condition = array("productsubcategorymaster.isDelete !=" => 1);
        $orderBy = array('productsubcategorymaster.id' => 'DESC');
        $joinType = array('productcategorymaster' => 'inner');
        $join = array('productcategorymaster' => 'productcategorymaster.code=productsubcategorymaster.categoryCode');
        $groupByColumn = array();
        $limit = $this->input->GET("length");
        $offset = $this->input->GET("start");
        $extraCondition = "";
        $like = array("productsubcategorymaster.code" => $search . "~both","productcategorymaster.categoryName" => $search . "~both", "productsubcategorymaster.subcategoryName" => $search . "~both", "productsubcategorymaster.subcategoryName" => $search . "~both");
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
                    $actionHtml .= ' <a id="view" class="btn btn-sm btn-success cursor_pointer edit_subcategory m-1" data-seq="' . $row->code . '" data-type="1"><i id="edt" title="View" class="fa fa-eye"></i></a>';
                }
                if ($this->rights != '' && $this->rights['update'] == 1) {
                    $actionHtml .= '<a id="edit" class="btn btn-sm btn-info cursor_pointer edit_subcategory m-1" data-seq="' . $row->code . '" data-type="2"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
                }
                if ($this->rights != '' && $this->rights['delete'] == 1) {
                    $actionHtml .= '<a id="delete" class="btn btn-sm btn-danger cursor_pointer delete_subcategory m-1" data-seq="' . $row->code . '"><i id="dlt" title="Delete" class="fa fa-trash"></i></a></div>';
                }

                $iconPath = '';
                if ($row->icon != '') {
                    $iconPath = "<img src='" . base_url() . $row->icon . "' height='50' width='50' alt='SubCategory Image'>";
                }
                $data[] = array(
                    $srno,
                    $row->code,
                    $row->categoryName,
                    $row->subcategoryName,
                    //$row->subcategorySName,
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

    public function saveSubCategory()
    {
        $date = date('Y-m-d H:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $loginrole = "";
		$cmpcode = $this->GlobalModel->getCompcode();
        $category = trim($this->input->post("category"));
        $subcategoryName = trim($this->input->post("subcategoryName"));
        $subcategorySName = $this->input->post("subcategorySName");
        $description = $this->input->post("description");
        $subcategoryCode = $this->input->post("code");
        $previousIcon = $this->input->post("previousIcon");
        $isActive = $this->input->post("isActive");
        $ip = $_SERVER['REMOTE_ADDR'];
        $condition2 = array('LOWER(subcategoryName)' => strtolower($subcategoryName));
        if ($subcategoryCode != '') {
            $condition2['productsubcategorymaster.code!='] = $subcategoryCode;
        }
        $result = $this->GlobalModel->checkDuplicateRecordNew($condition2, 'productsubcategorymaster');
        if ($result == true) {
            $response['status'] = false;
            $response['message'] = 'Duplicate Product SubCategory';
        } else {
            $data = array(
                'categoryCode' => $category,
                'subcategoryName' => $subcategoryName,
                'subcategorySName' => $subcategorySName,
                'description' => $description,
                'isActive' => $isActive
            );
            if ($subcategoryCode != '') {
                $data['editID'] = $addID;
                $data['editIP'] = $ip;
                $code = $this->GlobalModel->doEdit($data, 'productsubcategorymaster', $subcategoryCode);
                $code = $subcategoryCode;
                $successMsg = "Product SubCatgeory Updated Successfully";
                $errorMsg = "Failed To Update SubCatgeory";
                $txt = $code . " - " . $subcategoryName . " product Subcategory is updated.";
            } else {
                $data['addID'] = $addID;
                $data['addIP'] = $ip;
                $code = $this->GlobalModel->addWithoutYear($data, 'productsubcategorymaster', 'PSCT');
                $successMsg = "Product SubCategory Added Successfully";
                $errorMsg = "Failed To Add SubCategory";
                $txt = $code . " - " . $subcategoryName . " product Subcategory is added.";
            }
            if ($code != 'false') {
                $subcategoryIcon =  "";
                if (file_exists($previousIcon)) {
                    unlink($previousIcon);
                }
                $uploadDir = "upload/product/subcategory/$cmpcode";
                if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
                if (!empty($_FILES['subcategoryIcon']['name'])) {
                    $tmpFile = $_FILES['subcategoryIcon']['tmp_name'];
                    $ext = pathinfo($_FILES['subcategoryIcon']['name'], PATHINFO_EXTENSION);
                    $filename = $uploadDir . '/' . $code . '-' . time() . '.' . $ext;
                    move_uploaded_file($tmpFile, $filename);
                    if (file_exists($filename)) {
                        $subcategoryIcon = $filename;
                    }
                }
                $subData = array(
                    'icon' => $subcategoryIcon
                );
                $filedoc = $this->GlobalModel->doEdit($subData, 'productsubcategorymaster', $code);
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

    public function editSubCategory()
    {
        $code = $this->input->post('code');
        $subcategoryQuery = $this->GlobalModel->selectQuery("productsubcategorymaster.*", 'productsubcategorymaster', array('productsubcategorymaster.code' => $code));
        if ($subcategoryQuery) {
            $result = $subcategoryQuery->result_array()[0];
            $data['status'] = true;
            $data['code'] = $result['code'];
            $data['subcategoryName'] = $result['subcategoryName'];
            $data['subcategorySName'] = $result['subcategorySName'];
            $data['description'] = $result['description'];
            $data['isActive'] = $result['isActive'];
            $data['icon'] = '';
            $data['previousIcon'] = $result['icon'];


            $prdHtml = '';
            $category = $this->GlobalModel->selectQuery('productcategorymaster.*', 'productcategorymaster', array('productcategorymaster.isActive' => 1));
            if ($category && $category->num_rows() > 0) {
                $prdHtml .= '<option value="">Select Product Category</option>';
                foreach ($category->result() as $cat) {
                    $selected = $result['categoryCode'] == $cat->code ? 'selected' : '';
                    $prdHtml .= '<option value="' . $cat->code . '"' . $selected . '>' . $cat->categoryName . '</option>';
                }
            }
            $data['categoryData'] = $prdHtml;
            if ($result['icon'] != '') {
                $data['icon'] = base_url() . $result['icon'];
            }
        } else {
            $data['status'] = false;
        }
        echo json_encode($data);
    }

    public function deleteSubCategory()
    {
        $code = $this->input->post('code');
        $date = date('Y-m-d H:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $loginrole = "";
        $ip = $_SERVER['REMOTE_ADDR'];
        $categoryName = $this->GlobalModel->selectDataById($code, 'productsubcategorymaster')->result()[0]->categoryName;
        $query = $this->GlobalModel->delete($code, 'productsubcategorymaster');
        if ($query) {
            $txt = $code . " - " . $categoryName . " product subcategory is deleted.";
            $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
            $this->GlobalModel->activity_log($activity_text);
        }
        echo $query;
    }
}
