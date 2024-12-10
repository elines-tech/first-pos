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
        $rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
        if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
            redirect('Login', 'refresh');
        }
        $this->rights = $this->GlobalModel->getMenuRights('2.3', $rolecode);
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
            $this->load->view('dashboard/product/list', $data);
            $this->load->view('dashboard/footer');
        } else {
            $this->load->view('errors/norights.php');
        }
    }

    public function getList()
    {
        $tableName = 'productmaster';
        $orderColumns = array("productmaster.*,productcategorymaster.categoryName,taxgroupmaster.taxGroupName,productsubcategorymaster.subcategoryName");
        $search = $this->input->GET("search")['value'];
        $condition = array("productmaster.isDelete !="=>1);
        $orderBy = array('productmaster' . '.id' => 'DESC');
        $joinType = array('taxgroupmaster' => 'inner', 'productcategorymaster' => 'left outer', 'productsubcategorymaster' => 'left outer');
        $join = array('taxgroupmaster' => 'taxgroupmaster.code=productmaster.productTaxGrp', 'productcategorymaster' => 'productcategorymaster.code=productmaster.productCategory', 'productsubcategorymaster' => 'productsubcategorymaster.code=productmaster.subcategory');
        $groupByColumn = array();
        $limit = $this->input->GET("length");
        $offset = $this->input->GET("start");
        $like = array();
        $extraCondition = "";
        $like = array("productmaster.code" => $search . "~both","productmaster.productEngName" => $search . "~both", "productmaster.productArbName" => $search . "~both", "productmaster.productHinName" => $search . "~both", "productmaster.productUrduName" => $search . "~both", "productmaster.preparationTime" => $search . "~both", "productmaster.productCalories" => $search . "~both", "productmaster.productFixedCost" => $search . "~both", "productmaster.productPrice" => $search . "~both", "productcategorymaster.categoryName" => $search . "~both", "taxgroupmaster.taxGroupName" => $search . "~both","productsubcategorymaster.subcategoryName" => $search . "~both");
        $Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
        $srno = $offset + 1;
        $data = array();
        $dataCount = 0;
        if ($Records) {
            foreach ($Records->result() as $row) {
                $actionHtml = '';
                if ($this->rights != '' && $this->rights['view'] == 1) {
                    $actionHtml .= '<a href="' . base_url() . 'Product/view/' . $row->code . '" class="btn btn-primary btn-sm m-1"><i id="edt" title="View" class="fa fa-eye cursor_pointer" ></i></a>';
                }
                if ($this->rights != '' && $this->rights['update'] == 1) {
                    $actionHtml .= '<a href="' . base_url() . 'Product/edit/' . $row->code . '" class="btn btn-info btn-sm m-1"><i id="edt" title="Edit" class="fa fa-pencil cursor_pointer"></i></a>';
                }
                if ($this->rights != '' && $this->rights['delete'] == 1) {
                    $actionHtml .= '<a class="btn btn-sm btn-danger delete_id m-1" id="' . $row->code . '" ><i title="Delete" class="fa fa-trash cursor_pointer"></i></a>';
                }
                if ($this->rights != '' && $this->rights['insert'] == 1) {
                    $actionHtml .= '<a href="' . base_url() . 'Product/extras/' . $row->code . '" class="btn btn-success btn-sm m-1"><i id="edt" title="Product Extras" class="fa fa-plus cursor_pointer" ></i></a>';
                }
                // <a href="' . base_url() . 'Product/customizeaddon/' . $row->code . '" class="btn btn-success btn-sm m-1"><i id="edt" title="Addons" class="fa fa-plus cursor_pointer" ></i></a>--></div>';

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
                    $row->productEngName,
                    $row->productArbName,
                    $row->productHinName,
                    $row->productUrduName,
                    $row->categoryName,
                    $row->subcategoryName,
                    $row->taxGroupName,
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
            $data['insertRights'] = $this->rights['insert'];
            $table_name = 'taxgroupmaster';
            $orderColumns = array("taxgroupmaster.*");
            $cond = array('taxgroupmaster' . '.isDelete' => 0, 'taxgroupmaster' . '.isActive' => 1);
            $data['taxGroupData'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);

            $table_category = 'productcategorymaster';
            $orderColumns_category = array("productcategorymaster.*");
            $cond_category = array('productcategorymaster' . '.isDelete' => 0, 'productcategorymaster' . '.isActive' => 1);
            $data['categorydata'] = $this->GlobalModel->selectQuery($orderColumns_category, $table_category, $cond_category);

            $table_branch = 'branchmaster';
            $orderColumns_branch = array("branchmaster.*");
            $cond_branch = array('branchmaster' . '.isDelete' => 0, 'branchmaster' . '.isActive' => 1);
            $data['branchdata'] = $this->GlobalModel->selectQuery($orderColumns_branch, $table_branch, $cond_branch);

            $this->load->view('dashboard/commonheader');
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
        $user = $this->input->post("product-arabic-name");
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $cmpcode = $this->GlobalModel->getCompcode();
		$dbName = $this->session->userdata['current_db' . $this->session_key];
		$this->db->query('use ' . $dbName);
        $this->form_validation->set_rules(
            'product-english-name',
            'Product English Name',
            'trim|required|is_unique[productmaster.productEngName]',
            array(
                'is_unique'     => 'Product Name already exists.'
            )
        );
        $this->form_validation->set_rules('product-arabic-name', 'Product Arabic Name', 'trim|required');
        //$this->form_validation->set_rules('product-hindi-name', 'Product Hindi Name', 'trim|required');
        //$this->form_validation->set_rules('product-urdu-name', 'Product Urdu Name', 'trim|required');
        $this->form_validation->set_rules('productcategory', 'Product Category', 'trim|required');
        $this->form_validation->set_rules('producttaxgroup', 'Product Tax Group', 'trim|required');
        $this->form_validation->set_rules('productsubcategory', 'Product Subcategory', 'trim');
        $this->form_validation->set_rules('product-english-description', 'Product English Description', 'trim');
        $this->form_validation->set_rules('product-arabic-description', 'Product Arabic Description', 'trim');
        $this->form_validation->set_rules('product-hindi-description', 'Product Hindi Description', 'trim');
        $this->form_validation->set_rules('product-urdu-description', 'Product Urdu Description', 'trim');
        $this->form_validation->set_rules('productcookingtime', 'Product Cooking Time', 'trim');
        $this->form_validation->set_rules('number_of_person_served', 'Number of person served', 'trim');
        $this->form_validation->set_rules('productprice', 'Product Price', 'trim');
        $this->form_validation->set_rules('productcalories', 'Product Calories', 'trim');

        if ($this->form_validation->run() == FALSE) {
            //$data['error_message'] = '* Fields are Required!';
            $table_name = 'taxgroupmaster';
            $orderColumns = array("taxgroupmaster.*");
            $cond = array('taxgroupmaster' . '.isDelete' => 0, 'taxgroupmaster' . '.isActive' => 1);
            $data['taxGroupData'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);

            $table_category = 'productcategorymaster';
            $orderColumns_category = array("productcategorymaster.*");
            $cond_category = array('productcategorymaster' . '.isDelete' => 0, 'productcategorymaster' . '.isActive' => 1);
            $data['categorydata'] = $this->GlobalModel->selectQuery($orderColumns_category, $table_category, $cond_category);
            $this->load->view('dashboard/commonheader');
            $this->load->view('dashboard/product/add', $data);
            $this->load->view('dashboard/footer');
        } else {
            $active = 0;
            $isAddOnProduct = 0;
            $branch = json_encode($this->input->post("branches"));
            if ($this->input->post('isActive') == 'on') {
                $active = 1;
            }
            if ($this->input->post('isAddOnProduct') == 'on') {
                $isAddOnProduct = 1;
            }

            if ($this->input->post("productpricingmethod") == "1") {
                $productprice = $this->input->post("productprice");
            } else {
                $productprice = "";
            }
            $data = array(

                'productEngName' => $this->input->post("product-english-name"),
                'productArbName' => $this->input->post("product-arabic-name"),
                'productHinName' => $this->input->post("product-hindi-name"),
                'productUrduName' => $this->input->post("product-urdu-name"),
                'productCategory' => $this->input->post("productcategory"),
                'productMethod'  => $this->input->post("productpricingmethod"),
                'productPrice' => $productprice,
                'productTaxGrp'  => $this->input->post("producttaxgroup"),
                //'costingMethod'  => $this->input->post("productcostingmethod"),
                //'productFixedCost'  => $this->input->post("productcost"),
                'preparationTime'  => $this->input->post("productcookingtime"),
                'productCalories'  => $this->input->post("productcalories"),
                'productEngDesc'  => $this->input->post("product-english-description"),
                'productArbDesc' => $this->input->post("product-arabic-description"),
                'productHinDesc'  => $this->input->post("product-hindi-description"),
                'productUrduDesc'  => $this->input->post("product-urdu-description"),
                'numberOfPersonServed' => $this->input->post("number_of_person_served"),
                'subcategory'  => $this->input->post("productsubcategory"),
                'inActiveBranches' => $branch,
                'isActive' => $active,
                'isAddOn' => $isAddOnProduct,
                'addID' => $addID,
                'addIP' => $ip,
            );
            $result = $this->GlobalModel->addNew($data, 'productmaster', 'PRO');
            if ($result != false) {


                $filename =  "";
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

    public function edit()
    {
        if ($this->rights != '' && $this->rights['update'] == 1) {
            $data['updateRights'] = $this->rights['update'];
            $code = $this->uri->segment(3);
            $data['productData'] = $this->GlobalModel->get_data($code, 'productmaster');
            $query = $data['productData'];
            $categoryCode = $query[0]['productCategory'];

            $table_name = 'taxgroupmaster';
            $orderColumns = array("taxgroupmaster.*");
            $cond = array('taxgroupmaster' . '.isDelete' => 0, 'taxgroupmaster' . '.isActive' => 1);
            $data['taxGroupData'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);

            $table_category = 'productcategorymaster';
            $orderColumns_category = array("productcategorymaster.*");
            $cond_category = array('productcategorymaster' . '.isDelete' => 0, 'productcategorymaster' . '.isActive' => 1);
            $data['categorydata'] = $this->GlobalModel->selectQuery($orderColumns_category, $table_category, $cond_category);


            $table_subcategory = 'productsubcategorymaster';
            $orderColumns_subcategory = array("productsubcategorymaster.*");
            $cond_subcategory = array('productsubcategorymaster' . '.categoryCode' => $categoryCode, 'productsubcategorymaster' . '.isDelete' => 0, 'productsubcategorymaster' . '.isActive' => 1);
            $data['subcategorydata'] = $this->GlobalModel->selectQuery($orderColumns_subcategory, $table_subcategory, $cond_subcategory);

            $table_branch = 'branchmaster';
            $orderColumns_branch = array("branchmaster.*");
            $cond_branch = array('branchmaster' . '.isDelete' => 0, 'branchmaster' . '.isActive' => 1);
            $data['branchdata'] = $this->GlobalModel->selectQuery($orderColumns_branch, $table_branch, $cond_branch);

            $this->load->view('dashboard/commonheader');
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
            $query = $data['productData'];
            $categoryCode = $query[0]['productCategory'];


            $table_name = 'taxgroupmaster';
            $orderColumns = array("taxgroupmaster.*");
            $cond = array('taxgroupmaster' . '.isDelete' => 0, 'taxgroupmaster' . '.isActive' => 1);
            $data['taxGroupData'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);

            $table_category = 'productcategorymaster';
            $orderColumns_category = array("productcategorymaster.*");
            $cond_category = array('productcategorymaster' . '.isDelete' => 0, 'productcategorymaster' . '.isActive' => 1);
            $data['categorydata'] = $this->GlobalModel->selectQuery($orderColumns_category, $table_category, $cond_category);

            $table_subcategory = 'productsubcategorymaster';
            $orderColumns_subcategory = array("productsubcategorymaster.*");
            $cond_subcategory = array('productsubcategorymaster' . '.categoryCode' => $categoryCode, 'productsubcategorymaster' . '.isDelete' => 0, 'productsubcategorymaster' . '.isActive' => 1);
            $data['subcategorydata'] = $this->GlobalModel->selectQuery($orderColumns_subcategory, $table_subcategory, $cond_subcategory);


            $table_branch = 'branchmaster';
            $orderColumns_branch = array("branchmaster.*");
            $cond_branch = array('branchmaster' . '.isDelete' => 0, 'branchmaster' . '.isActive' => 1);
            $data['branchdata'] = $this->GlobalModel->selectQuery($orderColumns_branch, $table_branch, $cond_branch);

            $this->load->view('dashboard/commonheader');
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
        $date = date('d-m-y h:i:s');
        $cmpcode = $this->GlobalModel->getCompcode();
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        
		$table = "productmaster";
		$where = ["productEngName" => $englishName, "code!=" => $code];
		$resultEName =  $this->GlobalModel->hasSimilarRecords($where, $table);
        //$resultEName = $this->db->query("SELECT * FROM productmaster WHERE productEngName='" . $englishName . "' AND code !='" . $code . "' and (`isDelete` IS NULL OR `isDelete`='0')");

        if ($resultEName) {
            $data['error_message'] = 'Product Name already exists.';
            $data['productData'] = $this->GlobalModel->get_data($code, 'productmaster');
            $query = $data['productData'];
            $categoryCode = $query[0]['productCategory'];

            $table_name = 'taxgroupmaster';
            $orderColumns = array("taxgroupmaster.*");
            $cond = array('taxgroupmaster' . '.isDelete' => 0, 'taxgroupmaster' . '.isActive' => 1);
            $data['taxGroupData'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);

            $table_category = 'productcategorymaster';
            $orderColumns_category = array("productcategorymaster.*");
            $cond_category = array('productcategorymaster' . '.isDelete' => 0, 'productcategorymaster' . '.isActive' => 1);
            $data['categorydata'] = $this->GlobalModel->selectQuery($orderColumns_category, $table_category, $cond_category);


            $table_subcategory = 'productsubcategorymaster';
            $orderColumns_subcategory = array("productsubcategorymaster.*");
            $cond_subcategory = array('productsubcategorymaster' . '.categoryCode' => $categoryCode, 'productsubcategorymaster' . '.isDelete' => 0, 'productsubcategorymaster' . '.isActive' => 1);
            $data['subcategorydata'] = $this->GlobalModel->selectQuery($orderColumns_subcategory, $table_subcategory, $cond_subcategory);

            $table_branch = 'branchmaster';
            $orderColumns_branch = array("branchmaster.*");
            $cond_branch = array('branchmaster' . '.isDelete' => 0, 'branchmaster' . '.isActive' => 1);
            $data['branchdata'] = $this->GlobalModel->selectQuery($orderColumns_branch, $table_branch, $cond_branch);

            $this->load->view('dashboard/commonheader');
            $this->load->view('dashboard/product/edit', $data);
            $this->load->view('dashboard/footer');
        } else {
            $this->form_validation->set_rules('product-english-name', 'Prduct English Name', 'trim|required');
            $this->form_validation->set_rules('product-arabic-name', 'Prduct Arabic Name', 'trim|required');
            //$this->form_validation->set_rules('product-hindi-name', 'Prduct Hindi Name', 'trim|required');
            //$this->form_validation->set_rules('product-urdu-name', 'Prduct Urdu Name', 'trim|required');
            $this->form_validation->set_rules('productcategory', 'Prduct Category', 'trim|required');
            $this->form_validation->set_rules('producttaxgroup', 'Product Tax Group', 'trim|required');
            $this->form_validation->set_rules('productsubcategory', 'Product Subcategory', 'trim');
            $this->form_validation->set_rules('product-english-description', 'Product English Description', 'trim');
            $this->form_validation->set_rules('product-arabic-description', 'Product Arabic Description', 'trim');
            $this->form_validation->set_rules('product-hindi-description', 'Product Hindi Description', 'trim');
            $this->form_validation->set_rules('product-urdu-description', 'Product Urdu Description', 'trim');
            $this->form_validation->set_rules('productcookingtime', 'Product Cooking Time', 'trim');
            $this->form_validation->set_rules('number_of_person_served', 'Number of person served', 'trim');
            $this->form_validation->set_rules('productprice', 'Product Price', 'trim');
            $this->form_validation->set_rules('productcalories', 'Product Calories', 'trim');

            if ($this->form_validation->run() == FALSE) {
                $data['error_message'] = '* Fields are Required!';
                $data['productData'] = $this->GlobalModel->get_data($code, 'productmaster');
                $table_name = 'taxgroupmaster';
                $orderColumns = array("taxgroupmaster.*");
                $cond = array('taxgroupmaster' . '.isDelete' => 0, 'taxgroupmaster' . '.isActive' => 1);
                $data['taxGroupData'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);

                $table_category = 'productcategorymaster';
                $orderColumns_category = array("productcategorymaster.*");
                $cond_category = array('productcategorymaster' . '.isDelete' => 0, 'productcategorymaster' . '.isActive' => 1);
                $data['categorydata'] = $this->GlobalModel->selectQuery($orderColumns_category, $table_category, $cond_category);

                $table_branch = 'branchmaster';
                $orderColumns_branch = array("branchmaster.*");
                $cond_branch = array('branchmaster' . '.isDelete' => 0, 'branchmaster' . '.isActive' => 1);
                $data['branchdata'] = $this->GlobalModel->selectQuery($orderColumns_branch, $table_branch, $cond_branch);

                $this->load->view('dashboard/commonheader');
                $this->load->view('dashboard/user/user-edit', $data);
                $this->load->view('dashboard/footer');
            } else {

                $active = 0;
                $isAddOnProduct = 0;
                $branch = json_encode($this->input->post("branches"));
                if ($this->input->post('isActive') == 'on') {
                    $active = 1;
                }
                if ($this->input->post('isAddOnProduct') == 'on') {
                    $isAddOnProduct = 1;
                }

                if ($this->input->post("productpricingmethod") == "1") {
                    $productprice = $this->input->post("productprice");
                } else {
                    $productprice = "";
                }
                $data = array(

                    'productEngName' => $this->input->post("product-english-name"),
                    'productArbName' => $this->input->post("product-arabic-name"),
                    'productHinName' => $this->input->post("product-hindi-name"),
                    'productUrduName' => $this->input->post("product-urdu-name"),
                    'productCategory' => $this->input->post("productcategory"),
                    'productMethod'  => $this->input->post("productpricingmethod"),
                    'productPrice' => $productprice,
                    'productTaxGrp'  => $this->input->post("producttaxgroup"),
                    //'costingMethod'  => $this->input->post("productcostingmethod"),
                    //'productFixedCost'  => $this->input->post("productcost"),
                    'preparationTime'  => $this->input->post("productcookingtime"),
                    'productCalories'  => $this->input->post("productcalories"),
                    'productEngDesc'  => $this->input->post("product-english-description"),
                    'productArbDesc' => $this->input->post("product-arabic-description"),
                    'productHinDesc'  => $this->input->post("product-hindi-description"),
                    'productUrduDesc'  => $this->input->post("product-urdu-description"),
                    'numberOfPersonServed' => $this->input->post("number_of_person_served"),
                    'subcategory'  => $this->input->post("productsubcategory"),
                    'inActiveBranches' => $branch,
                    'isActive' => $active,
                    'isAddOn' => $isAddOnProduct,
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
    }

    public function customizeaddon()
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

            $table1 = "customizedcategory";
            $orderColumns1 = "customizedcategory.*";
            $condition1 = array("customizedcategory.productCode" => $code);
            $orderBy1 = array();
            $data['categories'] = $this->GlobalModel->selectQuery($orderColumns1, $table1, $condition1, $orderBy1);

            $table2 = "customizedcategorylineentries";
            $orderColumns2 = "customizedcategorylineentries.*,productmaster.productEngName";
            $condition2 = array("customizedcategory.productCode" => $code);
            $orderBy2 = array("customizedcategorylineentries.id" => "ASC");
            $join2 = array("customizedcategory" => "customizedcategorylineentries.customizedCategoryCode=customizedcategory.code", "productmaster" => "productmaster.code=customizedcategorylineentries.subCategoryTitle");
            $joinType2 = array("customizedcategory" => 'inner', "productmaster" => 'left');
            $data['categoriesline'] = $this->GlobalModel->selectQuery($orderColumns2, $table2, $condition2, $orderBy2, $join2, $joinType2);

            $data['products'] = $this->GlobalModel->selectQuery('productmaster.code,productmaster.productEngName,productmaster.productPrice', 'productmaster', array('productmaster.isActive' => 1, 'productmaster.isAddOn' => 1));


            $this->load->view('dashboard/commonheader');
            $this->load->view('dashboard/product/addons', $data);
            $this->load->view('dashboard/footer');
        } else {
            $this->load->view('errors/norights.php');
        }
    }

    public function addAddonCategory()
    {
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $date = date('d-m-y h:i:s');


        $productCode = trim($this->input->post("productCode"));
        $categoryTitle = trim($this->input->post("categoryTitle"));
        $categoryType = trim($this->input->post("categoryType"));
        $isCateEnabled = trim($this->input->post("isCateEnabled"));

        $ip = $_SERVER['REMOTE_ADDR'];
        $where = array("productCode" => $productCode, "categoryTitle" => $categoryTitle);
        $result = $this->GlobalModel->checkDuplicateRecordNew($where, 'customizedcategory');
        if ($result == true) {
            $response['status']  = 'exists';
            $response['message'] = 'Duplicate Category Exists';
        } else {
            $data = array(
                'productCode' => $productCode,
                'categoryTitle' => $categoryTitle,
                'categoryType' => $categoryType,
                'isEnabled' => $isCateEnabled,
                'addID' => $addID,
                'addIP' => $ip,
                'isActive' => 1,
            );
            $code = $this->GlobalModel->addWithoutYear($data, 'customizedcategory', 'ITCC');
            //$code = '12';
            if ($code != 'false') {
                $prdHtml = '';
                $products = $this->GlobalModel->selectQuery('productmaster.code,productmaster.productEngName,productmaster.productPrice', 'productmaster', array('productmaster.isActive' => 1, 'productmaster.isAddOn' => 1));
                if ($products && $products->num_rows() > 0) {
                    $prdHtml .= '<option value="">Select Product</option>';
                    foreach ($products->result() as $prd) {
                        $prdHtml .= '<option value="' . $prd->code . '">' . $prd->productEngName . '</option>';
                    }
                }
                $response['products'] = $prdHtml;
                $txt = $code . " - " .  $categoryTitle . " Category add on is added.";
                $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
                $this->GlobalModel->activity_log($activity_text);

                $response['status']  = 'true';
                $response['code']  = $code;
                $response['message'] = 'Category Added Successfully';
            } else {
                $response['status']  = 'failed';
                $response['message'] = 'Failed to add category';
            }
        }
        echo json_encode($response);
    }


    public function deleteAddonCategory()
    {
        $code = $this->input->post('code');
        $date = date('d-m-y h:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $ip = $_SERVER['REMOTE_ADDR'];

        $txt = $code . "Category is deleted.";
        $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
        $this->GlobalModel->activity_log($activity_text);

        $data = array(
            'editID' => $addID,
            'editIP' => $ip,
            'isDeleteRequested' => 1
        );

        $resultDataLine = $this->GlobalModel->deleteForeverFromField('customizedCategoryCode', $code, 'customizedcategorylineentries');

        $resultData = $this->GlobalModel->deleteForever($code, 'customizedcategory');
        if ($resultData == 'true') echo true;
        else echo false;
    }

    public function getAddonCategoryData()
    {
        $code = $this->input->post('code');
        $data = $this->GlobalModel->selectQuery('customizedcategory.*', 'customizedcategory', array("customizedcategory.code" => $code));
        if ($data) {
            // $r['status'] = true;
            // $r['data'] = json_encode($data->result_array()[0]);
            $dataRes = json_encode($data->result_array()[0]);
        } else {
            // $r['status'] = false;
            $dataRes = "";
        }
        // echo json_encode($r);
        echo $dataRes;
    }

    public function updateAddonCategory()
    {
        $date = date('d-m-y h:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];

        $productCode = trim($this->input->post("productCode"));
        $categoryTitle = trim($this->input->post("categoryTitle"));
        $categoryType = trim($this->input->post("categoryType"));
        $isCateEnabled = trim($this->input->post("isCateEnabled"));
        $customizedCategoryCode = trim($this->input->post("customizedCategoryCode"));
        $ip = $_SERVER['REMOTE_ADDR'];


        $where = array("productCode" => $productCode, "categoryTitle" => $categoryTitle, 'code!=' => $customizedCategoryCode);
        $result = $this->GlobalModel->checkDuplicateRecordNew($where, 'customizedcategory');
        if ($result == true) {
            $response['status']  = 'exists';
            $response['message'] = 'Duplicate Category Exists';
        } else {
            $data = array(
                'productCode' => $productCode,
                'categoryTitle' => $categoryTitle,
                'categoryType' => $categoryType,
                'isEnabled' => $isCateEnabled,
                'editID' => $addID,
                'editIP' => $ip,
                'isActive' => 1,
            );
            $resultUpdate = $this->GlobalModel->doEdit($data, 'customizedcategory', $customizedCategoryCode);
            if ($resultUpdate != 'false') {

                $txt = $customizedCategoryCode . " - " .  $categoryTitle . " Category add on is updated.";
                $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
                $this->GlobalModel->activity_log($activity_text);

                $response['status']  = 'true';
                $response['code']  = $customizedCategoryCode;
                $response['updatedtitle']  = $categoryTitle;
                $response['message'] = 'Category updated successfully';
            } else {
                $response['status']  = 'failed';
                $response['code']  = $customizedCategoryCode;
                $response['message'] = 'Failed to update category';
            }
        }
        echo json_encode($response);
    }
    public function addAddonLine()
    {
        $date = date('d-m-y h:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];

        $cateCode = trim($this->input->post("cateCode"));
        $subTitle = trim($this->input->post("subTitle"));
        $price = trim($this->input->post("price"));
        $categoryType = trim($this->input->post("categoryType"));
        $price = $price != "" ? $price : 0;
        $ip = $_SERVER['REMOTE_ADDR'];


        $where = array("customizedCategoryCode" => $cateCode, "subCategoryTitle" => $subTitle);
        $result = $this->GlobalModel->checkDuplicateRecordNew($where, 'customizedcategorylineentries');
        if ($result == true) {
            $response['status']  = 'exists';
            $response['message'] = 'Duplicate Sub Category Exists';
        } else {
            $response['title'] = '';
            if ($categoryType == 'product') {
                $productName = $this->GlobalModel->selectDataByField('code',  $subTitle, 'productmaster');
                $response['title']  = $productName->result_array()[0]['productEngName'];
            }
            $data = array(
                'customizedCategoryCode' => $cateCode,
                'subCategoryTitle' => $subTitle,
                'price' => $price,
                'isEnabled' => trim($this->input->post("isEnabled")),
                'addID' => $addID,
                'addIP' => $ip,
                'isActive' => 1,
            );
            $code = $this->GlobalModel->addWithoutYear($data, 'customizedcategorylineentries', 'CCLN');
            if ($code != 'false') {
                $txt = $code . " - " .  $subTitle . " SubCategory add on is added.";
                $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
                $this->GlobalModel->activity_log($activity_text);

                $response['status']  = 'true';
                $response['code']  = $code;
                $response['message'] = 'Sub Category Added Successfully';
            } else {
                $response['status']  = 'failed';
                $response['message'] = 'Failed to add Sub Category';
            }
        }
        echo json_encode($response);
    }

    public function deleteAddonLine()
    {
        $date = date('d-m-y h:i:s');
        $code = $this->input->post('code');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $ip = $_SERVER['REMOTE_ADDR'];

        $txt = $code . "Sub Category is deleted.";
        $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
        $this->GlobalModel->activity_log($activity_text);


        $data = array(
            'editID' => $addID,
            'editIP' => $ip,
            'isDeleteRequested' => 1
        );

        $resultData = $this->GlobalModel->deleteForever($code, 'customizedcategorylineentries');
        if ($resultData == 'true') echo true;
        else echo false;
    }

    public function getPrice()
    {
        $code =  $this->input->post("code");
        $price = $this->GlobalModel->selectDataByField('code', $code, 'productmaster');
        $response['status']  = 'true';
        $response['price']  = $price->result_array()[0]['productPrice'];
        echo json_encode($response);
    }

    public function getSubCategoryList()
    {
        $productCategory = $this->input->post('productCategory');
        $records = "";
        if ($productCategory != "") {
            $table_name = 'productsubcategorymaster';
            $orderColumns = array("productsubcategorymaster.*");
            $cond = array('productsubcategorymaster.isDelete' => 0, 'productsubcategorymaster.isActive' => 1, 'categoryCode' => $productCategory);
            $categories = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);
            if ($categories) {
                $records = '<option value="" readonly>Select Subcategory</option> ';
                foreach ($categories->result() as $r) {
                    $records .= '<option value="' . $r->code . '">' . $r->subcategoryName . '</option> ';
                }
            }
        }
        echo $records;
    }

    public function extras()
    {
        $data['insertRights'] = $this->rights['insert'];
        $code = $this->uri->segment('3');
        $tableName = "productmaster";
        $orderColumns = array("productmaster.*");
        $condition = array('productmaster.code' => $code);
        $orderBy = array();
        $joinType = array();
        $join = array();

        $data['productData'] = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType);

        $data['productExtras'] = $this->GlobalModel->selectQuery('productextras.*,unitmaster.unitName', 'productextras', array('productextras.productCode' => $code, 'productextras.isActive' => 1), array(), array('unitmaster' => 'unitmaster.code=productextras.itemUom'), array('unitmaster' => 'inner'));

        $data['items'] = $this->GlobalModel->selectQuery('itemmaster.*', 'itemmaster', array('itemmaster.isActive' => 1), array(), array(), array());
        $data['unitmaster'] = $this->GlobalModel->selectActiveData('unitmaster');

        $this->load->view('dashboard/commonheader');
        $this->load->view('dashboard/productextras/list', $data);
        $this->load->view('dashboard/footer');
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

    public function deleteExtrasLine()
    {
        $date = date('Y-m-d H:i:s');
        $lineCode = $this->input->post('lineCode');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $query = $this->GlobalModel->delete($lineCode, 'productextras');
        if ($query) {
            $txt = $lineCode . " deleted Product Extras";
            $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
            $this->GlobalModel->activity_log($activity_text);
            $msg = true;
            echo json_encode($msg);
        } else {
            $msg = false;
            echo json_encode($msg);
        }
    }

    public function saveExtrasLine()
    {
        $role = ($this->session->userdata['logged_in' . $this->session_key]['role']);
        $addID = ($this->session->userdata['logged_in' . $this->session_key]['code']);
        $ip = $_SERVER['REMOTE_ADDR'];
        $productCode = $this->input->post("productCode");
        $extrasLineCode = $this->input->post("extrasLineCode");
        $itemCode = $this->input->post("itemCode");
        $itemQty = $this->input->post("itemQty");
        $itemUnit = $this->input->post("itemUnit");
        $itemCost = $this->input->post("itemCost");
        $itemCustPrice = $this->input->post("itemCustPrice");
        $data = array(
            'productCode' => $productCode,
            'itemCode' => $itemCode,
            'itemQty' => $itemQty,
            'itemUom' => $itemUnit,
            'itemPrice' => $itemCost,
            'custPrice' => $itemCustPrice,
            'isActive' => 1
        );
        if ($extrasLineCode != '') {
            $data['editIP'] = $ip;
            $data['editID'] = $addID;
            $result = $this->GlobalModel->doEdit($data, 'productextras',  $extrasLineCode);

            $result =  $extrasLineCode;
        } else {
            $data['addIP'] = $ip;
            $data['addID'] = $addID;
            $result = $this->GlobalModel->addNew($data, 'productextras', 'PEXT');
        }
    }
}
