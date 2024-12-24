<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product extends CI_Controller
{
    var $session_key;
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
        $rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
        $this->rights = $this->GlobalModel->getMenuRights('2.4', $rolecode);
        if ($this->rights == '') {
            $this->load->view('errors/norights.php');
        }
    }

    public function listRecords()
    {
        if ($this->rights != '' && $this->rights['view'] == 1) {
            $data['insertRights'] = $this->rights['insert'];
            $this->load->view('dashboard/header');
            $this->load->view('dashboard/product/list', $data);
            $this->load->view('dashboard/footer');
        } else {
            $this->load->view('errors/norights.php');
        }
    }

    public function getList()
    {
        $tableName = 'productmaster';
        $orderColumns = array("productmaster.*,brandmaster.brandName,categorymaster.categoryName,subcategorymaster.subcategoryName,unitmaster.unitName,taxgroupmaster.taxGroupName");
        $search = $this->input->GET("search")['value'];
        $condition = array();
        $orderBy = array('productmaster' . '.id' => 'DESC');
        $joinType = array('taxgroupmaster' => 'inner', 'unitmaster' => 'inner', 'categorymaster' => 'inner', 'subcategorymaster' => 'left', 'brandmaster' => 'left');
        $join = array('taxgroupmaster' => 'taxgroupmaster.code=productmaster.productTaxGrp', 'unitmaster' => 'unitmaster.code=productmaster.storageUnit', 'categorymaster' => 'categorymaster.code=productmaster.productCategory', 'subcategorymaster' => 'subcategorymaster.code=productmaster.productSubcategory', 'brandmaster' => 'brandmaster.code=productmaster.brandCode');
        $groupByColumn = array();
        $limit = $this->input->GET("length");
        $offset = $this->input->GET("start");
        $like = array();
        $extraCondition = " (productmaster.isDelete=0 OR productmaster.isDelete IS NULL)";
        $like = array("productmaster.productEngName" => $search . "~both", "productmaster.productArbName" => $search . "~both", "productmaster.productHinName" => $search . "~both", "productmaster.productUrduName" => $search . "~both", "categorymaster.categoryName" => $search . "~both", "taxgroupmaster.taxGroupName" => $search . "~both", "brandmaster.brandName" => $search . "~both", "productmaster.code" => $search . "~both", "productmaster.sku" => $search . "~both", "unitmaster.unitName" => $search . "~both");
        $Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
        $srno = $offset + 1;
        $data = array();
        $dataCount = 0;
        if ($Records) {
            foreach ($Records->result() as $row) {
                $actionHtml = '<div class="d-flex">';
                if ($this->rights != '' && $this->rights['view'] == 1) {
                    $actionHtml .= '<a id="view" href="' . base_url() . 'product/view/' . $row->code . '" class="btn btn-success btn-sm cursor_pointer m-1"><i id="edt" title="View" class="fa fa-eye" ></i></a>';
                }
                if ($this->rights != '' && $this->rights['update'] == 1) {
                    $actionHtml .= '<a id="edit" href="' . base_url() . 'product/edit/' . $row->code . '" class="btn btn-info btn-sm cursor_pointer m-1"><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
                }
                if ($this->rights != '' && $this->rights['delete'] == 1) {
                    $actionHtml .= '<a id="delete" class="btn btn-sm btn-danger delete_id cursor_pointer m-1" id="' . $row->code . '" ><i title="Delete" class="fa fa-trash"></i></a>';
                }
                if ($row->hasVariants == 1) {
                    $actionHtml .= '<a id="customize" href="' . base_url() . 'Product/variant/' . $row->code . '" class="btn btn-success btn-sm m-1"><i id="edt" title="Variants" class="fa fa-plus cursor_pointer" ></i></a></div>';
                }

                if ($row->isActive == "1") {
                    $status = " <span class='badge bg-success'>Active</span>";
                } else {
                    $status = " <span class='badge bg-danger'>Inactive</span>";
                }

                $productImage = '';
                if ($row->productImage != '') {
                    $productImage = "<img src='" . base_url() . $row->productImage . "' height='50' width='50' alt='Product Image'>";
                }

                $data[] = array(
                    $srno,
                    $row->code,
                    $row->sku,
                    $row->brandName,
                    $row->categoryName,
                    $row->subcategoryName,
                    $row->productEngName,
                    $row->unitName,
                    $productImage,
                    $status,
                    $actionHtml
                );
                $srno++;
            }
            $dataCount = sizeof($this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, array(), '', '', '', $extraCondition)->result_array());
        }
        $output = array(
            "draw" => intval($_GET["draw"]),
            "recordsTotal" => $dataCount,
            "recordsFiltered" => $dataCount,
            "data" => $data
        );
        echo json_encode($output);
    }

    public function add()
    {
        if ($this->rights != '' && $this->rights['insert'] == 1) {
            $data['taxGroupData'] = $this->GlobalModel->selectActiveData('taxgroupmaster');
            $data['unitdata'] = $this->GlobalModel->selectActiveData('unitmaster');
            $data['categorydata'] = $this->GlobalModel->selectActiveData('categorymaster');
            $data['subcategorydata'] = $this->GlobalModel->selectActiveData('subcategorymaster');
            $data['branddata'] = $this->GlobalModel->selectActiveData('brandmaster');
            $this->load->view('dashboard/header');
            $this->load->view('dashboard/sidebar');
            $this->load->view('dashboard/product/add', $data);
            $this->load->view('dashboard/footer');
        } else {
            $this->load->view('errors/norights.php');
        }
    }

    public function save()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $date = date('d-m-y h:i:s');
        $cmpcode = $this->GlobalModel->getCompcode();
        $user = $this->input->post("product-arabic-name");
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $this->form_validation->set_rules(
            'product-english-name',
            'Product English Name',
            'trim|required|min_length[4]'
        );
        $this->form_validation->set_rules('product-arabic-name', 'Product Arabic Name', 'trim|required');
        $this->form_validation->set_rules('product-hindi-name', 'Product Hindi Name', 'trim');
        $this->form_validation->set_rules('product-urdu-name', 'Product Urdu Name', 'trim');
        $this->form_validation->set_rules('productbrand', 'Product Brand', 'trim|required');
        $this->form_validation->set_rules('productsku', 'Product SKU', 'trim');
        $this->form_validation->set_rules('productcategory', 'Product Category', 'trim|required');
        $this->form_validation->set_rules('productsubcategory', 'Product Subcategory', 'trim');
        $this->form_validation->set_rules('productUnit', 'Product Unit', 'trim|required');
        $this->form_validation->set_rules('producttaxgroup', 'Product Tax Group', 'trim|required');
        $this->form_validation->set_rules('product-english-description', 'Product English Description', 'trim');
        $this->form_validation->set_rules('product-arabic-description', 'Product Arabic Description', 'trim');
        $this->form_validation->set_rules('product-hindi-description', 'Product Hindi Description', 'trim');
        $this->form_validation->set_rules('product-urdu-description', 'Product Urdu Description', 'trim');
        //$this->form_validation->set_rules('productprice', 'Product Price', 'trim');
        if ($this->form_validation->run() == FALSE) {
            $data['taxGroupData'] = $this->GlobalModel->selectActiveData('taxgroupmaster');
            $data['categorydata'] = $this->GlobalModel->selectActiveData('categorymaster');
            $data['subcategorydata'] = $this->GlobalModel->selectActiveData('subcategorymaster');
            $data['branddata'] = $this->GlobalModel->selectActiveData('brandmaster');
            $data['unitdata'] = $this->GlobalModel->selectActiveData('unitmaster');
            $this->load->view('dashboard/header');
            $this->load->view('dashboard/sidebar');
            $this->load->view('dashboard/product/add', $data);
            $this->load->view('dashboard/footer');
        } else {
            $exists = $this->GlobalModel->selectQuery("code", "productmaster", ["lower(productEngName)" => strtolower($this->input->post("product-english-name"))]);
            if ($exists != false && $exists->num_rows() > 0) {
                $data['error_message'] = 'Product Name already exists.';
                $data['taxGroupData'] = $this->GlobalModel->selectActiveData('taxgroupmaster');
                $data['categorydata'] = $this->GlobalModel->selectActiveData('categorymaster');
                $data['subcategorydata'] = $this->GlobalModel->selectActiveData('subcategorymaster');
                $data['branddata'] = $this->GlobalModel->selectActiveData('brandmaster');
                $data['unitdata'] = $this->GlobalModel->selectActiveData('unitmaster');
                $this->load->view('dashboard/header');
                $this->load->view('dashboard/sidebar');
                $this->load->view('dashboard/product/add', $data);
                $this->load->view('dashboard/footer');
            } else {

                $active = 0;
                if ($this->input->post('isActive') == 'on') {
                    $active = 1;
                }
                $hasVariants = 0;
                if ($this->input->post('isVariants') == 'on') {
                    $hasVariants = 1;
                }
                $checkSKU = trim($this->input->post("productsku"));
                $alertQty = trim($this->input->post("alertQty")) != "" ? $this->input->post("alertQty") : '0';
                $data = array(
                    'productEngName' => $this->input->post("product-english-name"),
                    'productArbName' => $this->input->post("product-arabic-name"),
                    'productHinName' => $this->input->post("product-hindi-name"),
                    'productUrduName' => $this->input->post("product-urdu-name"),
                    'brandCode' => $this->input->post("productbrand"),
                    //'sku' => $this->input->post("productsku"),
                    'productCategory' => $this->input->post("productcategory"),
                    'productSubcategory' => $this->input->post("productsubcategory"),
                    'storageUnit' => $this->input->post("productUnit"),
                    'alertQty' => $alertQty,
                    //'productPrice' => $this->input->post("productprice"),
                    'productPrice' => "0",
                    'productTaxGrp'  => $this->input->post("producttaxgroup"),
                    'productEngDesc'  => $this->input->post("product-english-description"),
                    'productArbDesc' => $this->input->post("product-arabic-description"),
                    'productHinDesc'  => $this->input->post("product-hindi-description"),
                    'productUrduDesc'  => $this->input->post("product-urdu-description"),
                    'hasVariants' => $hasVariants,
                    'isActive' => $active,
                    'addID' => $addID,
                    'addIP' => $ip,
                );
                $result = $this->GlobalModel->addNew($data, 'productmaster', 'PRO');
                if ($result != false) {
                    $filename = "";
                    $uploadDir = "upload/productImage/$cmpcode";
                    if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
                    if (!empty($_FILES['productImage']['name'])) {
                        $tmpFile = $_FILES['productImage']['tmp_name'];
                        $ext = pathinfo($_FILES['productImage']['name'], PATHINFO_EXTENSION);
                        $filename = $uploadDir . '/' . $result . '-' . time() . '.' . $ext;
                        move_uploaded_file($tmpFile, $filename);
                        if (file_exists($filename)) {
                            $subData = array(
                                'productImage' => $filename
                            );
                            $filedoc = $this->GlobalModel->doEdit($subData, 'productmaster', $result);
                        }
                    }

                    if ($checkSKU == "") {
                        $productSKU = $this->GlobalModel->selectQuery("settings.*", "settings", array('settings.code' => 'STG_1'));
                        if ($productSKU) {
                            $SKU = $productSKU->result_array()[0]['settingValue'];
                            $idProduct = explode("_", $result);
                            $checkSKU = $SKU . "-" . "PR-" . $idProduct[1];
                        }
                    }
                    $skuData = array('sku' => $checkSKU);
                    $skuuUpdate = $this->GlobalModel->doEdit($skuData, 'productmaster', $result);


                    $txt = $result . " - " . $user . " Product is added.";
                    $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
                    $this->GlobalModel->activity_log($activity_text);

                    $response['status'] = true;
                    $response['message'] = 'Product Added successfully.';
                } else {
                    $response['status'] = false;
                    $response['message'] = "Failed To Add Product";
                }
                $this->session->set_flashdata('message', json_encode($response));
                redirect('product/listRecords', 'refresh');
            }
        }
    }

    public function edit()
    {
        if ($this->rights != '' && $this->rights['update'] == 1) {
            $code = $this->uri->segment(3);
            $data['productData'] = $this->GlobalModel->get_data($code, 'productmaster');
            $data['taxGroupData'] = $this->GlobalModel->selectActiveData('taxgroupmaster');
            $data['categorydata'] = $this->GlobalModel->selectActiveData('categorymaster');
            $data['subcategorydata'] = $this->GlobalModel->selectActiveData('subcategorymaster');
            $data['branddata'] = $this->GlobalModel->selectActiveData('brandmaster');
            $data['unitdata'] = $this->GlobalModel->selectActiveData('unitmaster');

            $this->load->view('dashboard/header');
            $this->load->view('dashboard/sidebar');
            $this->load->view('dashboard/product/edit', $data);
            $this->load->view('dashboard/footer');
        } else {
            $this->load->view('errors/norights.php');
        }
    }

    public function view()
    {
        if ($this->rights != '' && $this->rights['view'] == 1) {
            $code = $this->uri->segment(3);
            $data['productData'] = $this->GlobalModel->get_data($code, 'productmaster');
            $data['taxGroupData'] = $this->GlobalModel->selectActiveData('taxgroupmaster');
            $data['categorydata'] = $this->GlobalModel->selectActiveData('categorymaster');
            $data['subcategorydata'] = $this->GlobalModel->selectActiveData('subcategorymaster');
            $data['unitdata'] = $this->GlobalModel->selectActiveData('unitmaster');
            $data['branddata'] = $this->GlobalModel->selectActiveData('brandmaster');
            $this->load->view('dashboard/header');
            $this->load->view('dashboard/sidebar');
            $this->load->view('dashboard/product/view', $data);
            $this->load->view('dashboard/footer');
        } else {
            $this->load->view('errors/norights.php');
        }
    }

    public function update()
    {
        $code = $this->input->post("code");
        $user = $this->input->post("product-arabic-name");

        $englishName = $this->input->post("product-english-name");
        $ip = $_SERVER['REMOTE_ADDR'];
        $date = date('d-m-y H:i:s');

        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $cmpcode = $this->GlobalModel->getCompcode();
        $exists = $this->GlobalModel->selectQuery("code", "productmaster", ["lower(productEngName)" => strtolower($englishName), "code!=" => $code]);
        if ($exists != false && $exists->num_rows() > 0) {
            $data['error_message'] = 'Product Name already exists.';
            $data['productData'] = $this->GlobalModel->get_data($code, 'productmaster');
            $data['taxGroupData'] = $this->GlobalModel->selectActiveData('taxgroupmaster');
            $data['branddata'] = $this->GlobalModel->selectActiveData('brandmaster');
            $data['categorydata'] = $this->GlobalModel->selectActiveData('categorymaster');
            $data['unitdata'] = $this->GlobalModel->selectActiveData('unitmaster');
            $this->load->view('dashboard/header');
            $this->load->view('dashboard/sidebar');
            $this->load->view('dashboard/product/edit', $data);
            $this->load->view('dashboard/footer');
        } else {
            $this->form_validation->set_rules('product-english-name', 'Product English Name', 'trim|required');
            $this->form_validation->set_rules('product-arabic-name', 'Product Arabic Name', 'trim|required');
            $this->form_validation->set_rules('product-hindi-name', 'Product Hindi Name', 'trim');
            $this->form_validation->set_rules('product-urdu-name', 'Product Urdu Name', 'trim');
            $this->form_validation->set_rules('productsku', 'Product SKU', 'trim');
            $this->form_validation->set_rules('productcategory', 'Product Category', 'trim|required');
            $this->form_validation->set_rules('productsubcategory', 'Product Subcategory', 'trim');
            $this->form_validation->set_rules('productUnit', 'Product Unit', 'trim|required');
            $this->form_validation->set_rules('producttaxgroup', 'Product Tax Group', 'trim|required');
            $this->form_validation->set_rules('product-english-description', 'Product English Description', 'trim');
            $this->form_validation->set_rules('product-arabic-description', 'Product Arabic Description', 'trim');
            $this->form_validation->set_rules('product-hindi-description', 'Product Hindi Description', 'trim');
            $this->form_validation->set_rules('product-urdu-description', 'Product Urdu Description', 'trim');
            //$this->form_validation->set_rules('productprice', 'Product Price', 'trim');
            if ($this->form_validation->run() == FALSE) {
                $data['error_message'] = '* Fields are Required!';
                $data['productData'] = $this->GlobalModel->get_data($code, 'productmaster');
                $data['taxGroupData'] = $this->GlobalModel->selectActiveData('taxgroupmaster');
                $data['categorydata'] = $this->GlobalModel->selectActiveData('categorymaster');
                $data['subcategorydata'] = $this->GlobalModel->selectActiveData('subcategorymaster');
                $data['branddata'] = $this->GlobalModel->selectActiveData('brandmaster');
                $data['unitdata'] = $this->GlobalModel->selectActiveData('unitmaster');

                $this->load->view('dashboard/header');
                $this->load->view('dashboard/sidebar');
                $this->load->view('dashboard/product/edit', $data);
                $this->load->view('dashboard/footer');
            } else {
                $active = 0;
                if ($this->input->post('isActive') == 'on') {
                    $active = 1;
                }
                $hasVariants = 0;
                if ($this->input->post('isVariants') == 'on') {
                    $hasVariants = 1;
                }
                $alertQty = trim($this->input->post("alertQty")) != "" ? $this->input->post("alertQty") : '0';
                $data = array(
                    'productEngName' => $this->input->post("product-english-name"),
                    'productArbName' => $this->input->post("product-arabic-name"),
                    'productHinName' => $this->input->post("product-hindi-name"),
                    'productUrduName' => $this->input->post("product-urdu-name"),
                    'brandCode' => $this->input->post("productbrand"),
                    'productCategory' => $this->input->post("productcategory"),
                    'productSubcategory' => $this->input->post("productsubcategory"),
                    'storageUnit' => $this->input->post("productUnit"),
                    //'productPrice' => $this->input->post("productprice"),
                    'productPrice' => "0",
                    'productTaxGrp'  => $this->input->post("producttaxgroup"),
                    'alertQty'  => $alertQty,
                    'sku'  => $this->input->post("productsku"),
                    'productEngDesc'  => $this->input->post("product-english-description"),
                    'productArbDesc' => $this->input->post("product-arabic-description"),
                    'productHinDesc'  => $this->input->post("product-hindi-description"),
                    'productUrduDesc'  => $this->input->post("product-urdu-description"),
                    'isActive' => $active,
                    'hasVariants' => $hasVariants,
                    'editID' => $addID,
                    'editIP' => $ip,
                );
                $result = $this->GlobalModel->doEdit($data, 'productmaster', $code);
                $filedoc = false;
                $filename = '';
                $uploadDir = "upload/productImage/$cmpcode";
                if (!file_exists($uploadDir)) mkdir($uploadDir, 0755, false);
                if (!empty($_FILES['productImage']['name'])) {
                    $filepro = $this->GlobalModel->directQuery("SELECT productImage FROM productmaster WHERE `code` = '" . $code . "'");

                    if (!empty($filepro)) {
                        $myImg = $filepro[0]['productImage'];
                        if ($myImg != "" && file_exists($myImg))  unlink($myImg);
                    }
                    $tmpFile = $_FILES['productImage']['tmp_name'];
                    $ext = pathinfo($_FILES['productImage']['name'], PATHINFO_EXTENSION);
                    $filename = $uploadDir . '/' . $code . '-' . time() . '.' . $ext;
                    move_uploaded_file($tmpFile, $filename);
                    if (file_exists($filename)) {
                        $subData = array(
                            'productImage' => $filename
                        );
                        $filedoc = $this->GlobalModel->doEdit($subData, 'productmaster', $code);
                    }
                }
                if ($result == true || $filedoc == true) {

                    $txt = $code . " - " . $user . " Product is updated.";
                    $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
                    $this->GlobalModel->activity_log($activity_text);

                    $response['status'] = true;
                    $response['message'] = "Product Successfully Updated.";
                } else {
                    $response['status'] = false;
                    $response['message'] = "No change In Product";
                }
                $this->session->set_flashdata('message', json_encode($response));
                redirect('Product/listRecords', 'refresh');
            }
        }
    }

    public function delete()
    {
        if ($this->rights != '' && $this->rights['delete'] == 1) {
            $date = date('d-m-y h:i:s');
            $code = $this->input->post('code');
            $ip = $_SERVER['REMOTE_ADDR'];

            $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
            $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
            $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];

            $txt = $code . "Product is deleted.";
            $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
            $this->GlobalModel->activity_log($activity_text);
            echo $this->GlobalModel->delete($code, 'productmaster');
        } else {
            $this->load->view('errors/norights.php');
        }
    }

    public function getSubCategoryList()
    {
        $categoryCode = $this->input->post('categoryCode');
        $subcategoryCode = $this->input->post('subcategoryCode');
        $subHtml = '';
        $getSubcategory = $this->GlobalModel->selectQuery('subcategorymaster.code,subcategorymaster.subcategoryName', 'subcategorymaster', array('subcategorymaster.isActive' => 1, 'subcategorymaster.categoryCode' => $categoryCode));
        if ($getSubcategory && $getSubcategory->num_rows() > 0) {
            $subHtml =  "<option value=''>Select</option>";
            foreach ($getSubcategory->result() as $gets) {
                if ($subcategoryCode == $gets->code) {
                    $subHtml .= "<option value='" . $gets->code . "' selected>" . $gets->subcategoryName . "</option>";
                } else {
                    $subHtml .= "<option value='" . $gets->code . "'>" . $gets->subcategoryName . "</option>";
                }
            }
            $response['status'] = true;
            $response['subHtml'] = $subHtml;
        } else {
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    public function attribute()
    {
        if ($this->rights != '' && $this->rights['insert'] == 1) {
            $code = $this->uri->segment('3');
            $tableName = "productmaster";
            $orderColumns = array("productmaster.*");
            $condition = array('productmaster.code' => $code);
            $orderBy = array();
            $joinType = array();
            $join = array();

            $data['productData'] = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType);

            $table1 = "attributemaster";
            $orderColumns1 = "attributemaster.*";
            $condition1 = array("attributemaster.isActive" => 1);
            $orderBy1 = array();
            $data['attributes'] = $this->GlobalModel->selectQuery($orderColumns1, $table1, $condition1, $orderBy1);

            $table2 = "productattributes";
            $orderColumns2 = "productattributes.*,attributemaster.attributeName";
            $condition2 = array("productattributes.productCode" => $code);
            $join2 = array("attributemaster" => "attributemaster.code=productattributes.attributesCode");
            $joinType2 = array("attributemaster" => 'inner');
            $orderBy2 = array();
            $data['productattribute'] = $this->GlobalModel->selectQuery($orderColumns2, $table2, $condition2, $orderBy2, $join2, $joinType2);

            $table3 = "productattributeslineentries";
            $orderColumns3 = "productattributeslineentries.*,productattributes.productCode";
            $condition3 = array("productattributes.productCode" => $code);
            $orderBy3 = array("productattributeslineentries.id" => "ASC");
            $join3 = array("productattributes" => "productattributeslineentries.productAttCode=productattributes.code");
            $joinType3 = array("productattributes" => 'inner');
            $data['productattributeline'] = $this->GlobalModel->selectQuery($orderColumns3, $table3, $condition3, $orderBy3, $join3, $joinType3);
            $data['products'] = $this->GlobalModel->selectQuery('productmaster.code,productmaster.productEngName,productmaster.productPrice', 'productmaster', array('productmaster.isActive' => 1));
            $this->load->view('dashboard/header');
            $this->load->view('dashboard/sidebar');
            $this->load->view('dashboard/product/attribute', $data);
            $this->load->view('dashboard/footer');
        } else {
            $this->load->view('errors/norights.php');
        }
    }

    public function addAttribute()
    {
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $date = date('d-m-y h:i:s');

        $productCode = trim($this->input->post("productCode"));
        $attributesCode = trim($this->input->post("attributecode"));

        $ip = $_SERVER['REMOTE_ADDR'];
        $where = array("productCode" => $productCode, "attributesCode" => $attributesCode);
        $result = $this->GlobalModel->checkDuplicateRecordNew($where, 'productattributes');
        if ($result == true) {
            $response['status']  = 'exists';
            $response['message'] = 'Duplicate Product Attribute.';
        } else {
            $data = array(
                'productCode' => $productCode,
                'attributesCode' =>  $attributesCode,
                'addID' => $addID,
                'addIP' => $ip,
                'isActive' => 1,
            );
            $code = $this->GlobalModel->addWithoutYear($data, 'productattributes', 'PAT');
            if ($code != 'false') {

                $txt = $code . " - " .  $attributesCode . " Product Attribute is added.";
                $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
                $this->GlobalModel->activity_log($activity_text);

                $response['status']  = 'true';
                $response['code']  = $code;
                $response['message'] = 'Product Attribute Added Successfully';
            } else {
                $response['status']  = 'failed';
                $response['message'] = 'Failed to add product attribute';
            }
        }
        echo json_encode($response);
    }

    public function getAttribute()
    {
        $code = $this->input->post('code');
        $data = $this->GlobalModel->selectQuery('productattributes.*', 'productattributes', array("productattributes.code" => $code));
        $attrHtml = '';
        $attribute = $this->GlobalModel->selectQuery('attributemaster.*', 'attributemaster', array('attributemaster.isActive' => 1));
        if ($attribute && $attribute->num_rows() > 0) {
            $attrHtml .= '<option value="">Select</option>';
            foreach ($attribute->result() as $attr) {
                $selected = $data->result_array()[0]['attributesCode'] == $attr->code ? 'selected' : '';
                $attrHtml .= '<option value="' . $attr->code . '" id="' . $attr->attributeName . '" ' . $selected . '>' . $attr->attributeName . '</option>';
            }
        }
        $response['attribute'] = $attrHtml;
        $response['code'] = $data->result_array()[0]['code'];
        echo json_encode($response);
    }

    public function updateAttribute()
    {
        $date = date('d-m-y h:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];

        $productCode = trim($this->input->post("productCode"));
        $attributesCode = trim($this->input->post("attributecode"));
        $productAttributeCode = trim($this->input->post("productAttributeCode"));
        $ip = $_SERVER['REMOTE_ADDR'];

        $where = array("productCode" => $productCode, "attributesCode" => $attributesCode, 'code!=' => $productAttributeCode);
        $result = $this->GlobalModel->checkDuplicateRecordNew($where, 'productattributes');
        if ($result == true) {
            $response['status']  = 'exists';
            $response['message'] = 'Duplicate Product Attribute';
        } else {
            $data = array(
                'productCode' => $productCode,
                'attributesCode' =>  $attributesCode,
                'editID' => $addID,
                'editIP' => $ip,
                'isActive' => 1,
            );
            $resultUpdate = $this->GlobalModel->doEdit($data, 'productattributes', $productAttributeCode);
            if ($resultUpdate != 'false') {
                $txt = $productAttributeCode . " - " .  $attributesCode . " Attribute is updated";
                $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
                $this->GlobalModel->activity_log($activity_text);
                $response['status']  = 'true';
                $response['code']  = $productAttributeCode;
                $response['message'] = 'Product Attribute updated successfully';
            } else {
                $response['status']  = 'failed';
                $response['code']  = $productAttributeCode;
                $response['message'] = 'Failed to update product attribute';
            }
        }
        echo json_encode($response);
    }

    public function deleteAttribute()
    {
        $code = $this->input->post('code');
        $date = date('d-m-y h:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $ip = $_SERVER['REMOTE_ADDR'];

        $txt = $code . "Product Attribute is deleted.";
        $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
        $this->GlobalModel->activity_log($activity_text);
        $data = array(
            'editID' => $addID,
            'editIP' => $ip
        );
        $resultDataLine = $this->GlobalModel->deleteForeverFromField('productAttCode', $code, 'productattributeslineentries');
        $resultData = $this->GlobalModel->deleteForever($code, 'productattributes');
        if ($resultData == 'true') echo true;
        else echo false;
    }

    public function addAttributeLine()
    {
        $date = date('d-m-y h:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];

        $attributeCode = trim($this->input->post("attributeCode"));
        $subTitle = trim($this->input->post("subTitle"));
        $ip = $_SERVER['REMOTE_ADDR'];
        $where = array("productAttCode" => $attributeCode, "subTitle" => $subTitle);
        $result = $this->GlobalModel->checkDuplicateRecordNew($where, 'productattributeslineentries');
        if ($result == true) {
            $response['status']  = 'exists';
            $response['message'] = 'Duplicate Product Options';
        } else {
            $data = array(
                'productAttCode' => $attributeCode,
                'subTitle' => $subTitle,
                'addID' => $addID,
                'addIP' => $ip,
                'isActive' => 1,
            );
            $code = $this->GlobalModel->addWithoutYear($data, 'productattributeslineentries', 'PATLN');
            if ($code != 'false') {
                $txt = $code . " - " .  $subTitle . "Product Options is added.";
                $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
                $this->GlobalModel->activity_log($activity_text);

                $response['status']  = 'true';
                $response['code']  = $code;
                $response['message'] = 'Product Option Added Successfully';
            } else {
                $response['status']  = 'failed';
                $response['message'] = 'Failed to add Product Option';
            }
        }
        echo json_encode($response);
    }

    public function deleteAttributeLine()
    {
        $date = date('d-m-y h:i:s');
        $code = $this->input->post('code');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $ip = $_SERVER['REMOTE_ADDR'];

        $txt = $code . "Product Option is deleted.";
        $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
        $this->GlobalModel->activity_log($activity_text);


        $data = array(
            'editID' => $addID,
            'editIP' => $ip,
        );

        $resultData = $this->GlobalModel->deleteForever($code, 'productattributeslineentries');
        if ($resultData == 'true') echo true;
        else echo false;
    }

    public function variant()
    {
        $code = $this->uri->segment('3');
        $tableName = "productmaster";
        $orderColumns = array("productmaster.*");
        $condition = array('productmaster.code' => $code);
        $orderBy = array();
        $joinType = array();
        $join = array();

        $data['productData'] = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType);

        $data['productVariants'] = $this->GlobalModel->selectQuery('productvariants.*', 'productvariants', array('productvariants.productCode' => $code), array(), array(), array());

        $this->load->view('dashboard/header');
        $this->load->view('dashboard/sidebar');
        $this->load->view('dashboard/product/variants', $data);
        $this->load->view('dashboard/footer');
    }

    public function saveVariantsLine()
    {
        $role = ($this->session->userdata['logged_in' . $this->session_key]['role']);
        $addID = ($this->session->userdata['logged_in' . $this->session_key]['code']);
        $ip = $_SERVER['REMOTE_ADDR'];
        $productCode = $this->input->post("productCode");
        $extrasLineCode = $this->input->post("extrasLineCode");
        $variantName = $this->input->post("variantName");
        $checkSKU = $this->input->post("variantSKU");
        $isActive = $this->input->post("isActive");
        $addResultFlagNew = false;
        if (isset($variantName)) {
            for ($j = 0; $j < sizeof($variantName); $j++) {
                if ($variantName[$j] != '') {
                    $data = array(
                        'productCode' => $productCode,
                        'variantName' => $variantName[$j],
                        'isActive' => $isActive[$j],
                        //'isDelete' => $isActive[$j] == 1 ? 0 : 1,
                        'sku' => $checkSKU[$j]
                    );
                    if ($extrasLineCode[$j] != '') {
                        $data['editIP'] = $ip;
                        $data['editID'] = $addID;
                        $result = $this->GlobalModel->doEdit($data, 'productvariants',  $extrasLineCode[$j]);
                        if ($result == true) {
                            $addResultFlagNew = true;
                        }
                    } else {
                        $data['addIP'] = $ip;
                        $data['addID'] = $addID;
                        $result = $this->GlobalModel->addNew($data, 'productvariants', 'PVAR');
                        if ($result != "false") {
                            if ($checkSKU[$j] == "") {
                                $productSKU = $this->GlobalModel->selectQuery("settings.*", "settings", array('settings.code' => 'STG_1'));
                                if ($productSKU) {
                                    $SKU = $productSKU->result_array()[0]['settingValue'];
                                    $idProduct = explode("_", $result);
                                    $checkSKU[$j] = $SKU . "-VR-" . $idProduct[1];
                                }
                            }
                            $skuData = array('sku' => $checkSKU[$j]);
                            $skuuUpdate = $this->GlobalModel->doEdit($skuData, 'productvariants', $result);
                            $addResultFlagNew = true;
                        }
                    }
                }
            }
        }
        if ($addResultFlagNew) {
            $response['status'] = true;
            $response['message'] = "Variants added Successfully.";
        } else {
            $response['status'] = false;
            $response['message'] = "Failed to Add variants.";
        }
        $this->session->set_flashdata('message', json_encode($response));
        redirect("/Product/variant/" . $productCode, 'refresh');
    }

    public function saveVariantsLineold()
    {
        $role = ($this->session->userdata['logged_in' . $this->session_key]['role']);
        $addID = ($this->session->userdata['logged_in' . $this->session_key]['code']);
        $ip = $_SERVER['REMOTE_ADDR'];
        $productCode = $this->input->post("productCode");
        $extrasLineCode = $this->input->post("extrasLineCode");
        $variantName = $this->input->post("variantName");
        $checkSKU = $this->input->post("variantSKU");
        $isActive = $this->input->post("isActive");
        if ($isActive == 1) {
            $delete = 0;
        } else {
            $delete = 1;
        }
        $data = array(
            'productCode' => $productCode,
            'variantName' => $variantName,
            'isActive' => $isActive,
            'isDelete' => $delete
        );
        if ($extrasLineCode != '') {
            $data['editIP'] = $ip;
            $data['editID'] = $addID;
            $result = $this->GlobalModel->doEdit($data, 'productvariants',  $extrasLineCode);
            $result =  $extrasLineCode;
        } else {
            $data['addIP'] = $ip;
            $data['addID'] = $addID;
            $result = $this->GlobalModel->addNew($data, 'productvariants', 'PVAR');
            if ($result != false) {
                if ($checkSKU == "") {
                    $productSKU = $this->GlobalModel->selectQuery("settings.*", "settings", array('settings.code' => 'STG_1'));
                    if ($productSKU) {
                        $SKU = $productSKU->result_array()[0]['settingValue'];
                        $idProduct = explode("_", $result);
                        $checkSKU = $SKU . "-" . "VR-" . $idProduct[1];
                    }
                }
                $skuData = array('sku' => $checkSKU);
                $skuuUpdate = $this->GlobalModel->doEdit($skuData, 'productvariants', $result);
            }
        }
    }

    public function deleteVariantsLine()
    {
        $date = date('Y-m-d H:i:s');
        $lineCode = $this->input->post('lineCode');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $query = $this->GlobalModel->deleteForever($lineCode, 'productvariants');
        if ($query) {
            $txt = $lineCode . " deleted Product Variants";
            $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
            $this->GlobalModel->activity_log($activity_text);
            $msg = true;
            echo json_encode($msg);
        } else {
            $msg = false;
            echo json_encode($msg);
        }
    }

    public function saveBrand()
    {
        $date = date('Y-m-d H:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $brandName = trim($this->input->post("brand"));
        $ip = $_SERVER['REMOTE_ADDR'];
        $condition2 = array('LOWER(brandName)' => strtolower($brandName));
        $result = $this->GlobalModel->checkDuplicateRecordNew($condition2, 'brandmaster');
        if ($result == true) {
            $response['status'] = false;
            $response['message'] = 'Duplicate Brand';
        } else {
            $data = array(
                'brandName' => $brandName,
                'isActive' => 1
            );
            $data['addID'] = $addID;
            $data['addIP'] = $ip;
            $code = $this->GlobalModel->addWithoutYear($data, 'brandmaster', 'BRD');
            $successMsg = "Brand Added Successfully";
            $errorMsg = "Failed To Add Brand";
            $txt = $code . " - " . $brandName . " brand is added.";

            if ($code != 'false') {
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


    public function saveCategory()
    {
        $date = date('Y-m-d H:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $loginrole = "";
        $cmpcode = $this->GlobalModel->getCompcode();
        $categoryName = trim($this->input->post("categoryName"));
        $ip = $_SERVER['REMOTE_ADDR'];
        $condition2 = array('LOWER(categoryName)' => strtolower($categoryName));
        $result = $this->GlobalModel->checkDuplicateRecordNew($condition2, 'categorymaster');
        if ($result == true) {
            $response['status'] = false;
            $response['message'] = 'Duplicate Category';
        } else {
            $data = array(
                'categoryName' => $categoryName,
                'isActive' => 1
            );

            $data['addID'] = $addID;
            $data['addIP'] = $ip;
            $code = $this->GlobalModel->addWithoutYear($data, 'categorymaster', 'CAT');
            $successMsg = "Category Added Successfully";
            $errorMsg = "Failed To Add Category";
            $txt = $code . " - " . $categoryName . " category is added.";

            if ($code != 'false') {
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

    public function savesubCategory()
    {
        $date = date('Y-m-d H:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $cmpcode = $this->GlobalModel->getCompcode();
        $subcategoryName = trim($this->input->post("subcategoryName"));
        $categoryCode = $this->input->post("scategory");
        $ip = $_SERVER['REMOTE_ADDR'];
        $condition2 = array('LOWER(subcategoryName)' => strtolower($subcategoryName));
        $result = $this->GlobalModel->checkDuplicateRecordNew($condition2, 'subcategorymaster');
        if ($result == true) {
            $response['status'] = false;
            $response['message'] = 'Duplicate Subcategory';
        } else {
            $data = array(
                'categoryCode' => $categoryCode,
                'subcategoryName' => $subcategoryName,
                'isActive' => 1
            );
            $data['addID'] = $addID;
            $data['addIP'] = $ip;
            $code = $this->GlobalModel->addWithoutYear($data, 'subcategorymaster', 'SCAT');
            $successMsg = "Subcategory Added Successfully";
            $errorMsg = "Failed To Add Subcategory";
            $txt = $code . " - " . $subcategoryName . " subcategory is added.";

            if ($code != 'false') {
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
}
