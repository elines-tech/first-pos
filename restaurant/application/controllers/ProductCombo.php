<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProductCombo extends CI_Controller
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
        $this->rights = $this->GlobalModel->getMenuRights('2.5', $rolecode);
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
            $table_category = 'productcategorymaster';
            $orderColumns_category = array("productcategorymaster.*");
            $cond_category = array('productcategorymaster' . '.isDelete' => 0, 'productcategorymaster' . '.isActive' => 1);
            $data['category'] = $this->GlobalModel->selectQuery($orderColumns_category, $table_category, $cond_category);
            $data['subcategory'] = $this->GlobalModel->selectActiveData('productsubcategorymaster');

            $table_category = 'productmaster';
            $orderColumns_category = array("productmaster.*");
            $cond_category = array('productmaster' . '.isActive' => 1, 'recipecard' . '.isActive' => 1);
            $data['productdata'] = $this->GlobalModel->selectQuery($orderColumns_category, $table_category, $cond_category, array(), array('recipecard' => 'recipecard.productCode=productmaster.code'), array('recipecard' => 'inner'));

            $this->load->view('dashboard/header');

            $this->load->view('dashboard/productcombo/list', $data);
            $this->load->view('dashboard/footer');
        } else {
            $this->load->view('errors/norights.php');
        }
    }

    public function getProductComboList()
    {
        $tableName = "productcombo";
        $search = $this->input->GET("search")['value'];
        $orderColumns = array("productcombo.*,productsubcategorymaster.subcategoryName");
        $condition = array();
        $orderBy = array('productcombo.id' => 'DESC');
        $joinType = array('productsubcategorymaster' => 'left outer');
        $join = array('productsubcategorymaster' => 'productsubcategorymaster.code=productcombo.productCategoryCode');
        $groupByColumn = array();
        $limit = $this->input->GET("length");
        $offset = $this->input->GET("start");
        $extraCondition = " (productcombo.isDelete=0 OR productcombo.isDelete IS NULL)";
        $like = array("productsubcategorymaster.subcategoryName" => $search . "~both","productcombo.productComboPrice" => $search . "~both","productcombo.code" => $search . "~both","productcombo.productComboName" => $search . "~both");
        $Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
        //echo $this->db->last_query();
        $srno = $_GET['start'] + 1;
        if ($Records) {
            foreach ($Records->result() as $row) {
                $code = $row->code;
                if ($row->isActive == 1) {
                    $status = "<span class='badge bg-success'>Active</span>";
                } else {
                    $status = "<span class='badge bg-danger'>Inactive</span>";
                }
                $actionHtml = '';
                if ($this->rights != '' && $this->rights['view'] == 1) {
                    $actionHtml .= '<a class="view_group btn btn-primary btn-sm m-1" data-seq="' . $row->code . '"><i id="edt" title="View" class="fa fa-eye cursor_pointer"></i></a>';
                }
                if ($this->rights != '' && $this->rights['update'] == 1) {
                    $actionHtml .= '<a class="btn btn-info btn-sm m-1" href="' . base_url() . 'ProductCombo/editProductCombo/' . $row->code . '"><i id="edt" title="Edit" class="fa fa-pencil cursor_pointer"></i></a>';
                }
                if ($this->rights != '' && $this->rights['delete'] == 1) {
                    $actionHtml .= '<a class="btn btn-danger btn-sm  delete_group m-1" data-seq="' . $row->code . '" ><i id="dlt" title="Delete" class="fa fa-trash cursor_pointer"></i></a>';
                }
                $data[] = array(
                    $srno,
                    $row->code,
                    $row->productComboName,
                    $row->subcategoryName,
                    $row->productComboPrice,
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

    public function save()
    {
        $date = date('Y-m-d H:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $cmpcode = $this->GlobalModel->getCompcode();
        $productComboName = trim($this->input->post("cname"));
        $productHindiName = trim($this->input->post("chindiname"));
        $productUrduName = trim($this->input->post("curduname"));
        $productArabicName = trim($this->input->post("carabicname"));
        $productCategory  = $this->input->post("productcategory");
        $productComboPrice = $this->input->post("price");
        $productTaxPrice = $this->input->post("totalTaxAmount");
        $finalAmount = $this->input->post("finalAmount");
        $productComboCode = $this->input->post("productComboCode");
        $isActive = $this->input->post("isActive");
        $productName = $this->input->post("pro_name");
        $productPrice = $this->input->post("pr_price");
        $productTax = $this->input->post("tax_price");
        $ip = $_SERVER['REMOTE_ADDR'];

        $condition2 = array('LOWER(productComboName)' => strtolower($productComboName));
        if ($productComboCode != '') {
            $condition2['productcombo.code!='] =  $productComboCode;
        }
        $result = $this->GlobalModel->checkDuplicateRecordNew($condition2, 'productcombo');
        if ($result == true) {
            $response['status'] = false;
            $response['message'] = 'Duplicate Productcombo';
        } else {
            $data = array(
                'productComboName' => $productComboName,
                'productComboUrduName' => $productUrduName,
                'productComboHindiName' => $productHindiName,
                'productComboArabicName' => $productArabicName,
                'productCategoryCode' => $productCategory,
                'productComboPrice' => $productComboPrice,
                'taxAmount' => $productTaxPrice,
                'productFinalPrice' => $finalAmount,
                'isActive' => $isActive
            );

            $data['addID'] = $addID;
            $data['addIP'] = $ip;
            $code = $this->GlobalModel->addWithoutYear($data, 'productcombo', 'PC');

            $count = count($productName);
            for ($i = 0; $i < $count; $i++) {
                $proName = $this->input->post("pro_name")[$i];
                $prPrice = $this->input->post("pr_price")[$i];
                $taxPrice = $this->input->post("tax_price")[$i];
                if ($proName != '' && $prPrice != '') {
                    $dataLine = array(
                        'productComboCode' => $code,
                        'productCode' => $proName,
                        'productPrice' => $prPrice,
                        'productTaxPrice' => $taxPrice,
                        'isActive' => 1,
                        'addID' => $addID,
                        'addIP' => $ip,
                    );
                    $resultnew = $this->GlobalModel->addNew($dataLine, 'productcombolineentries', 'PCOL');
                }
            }

            $successMsg = "Product Combo Added Successfully";
            $errorMsg = "Failed To Add Product Combo";
            $txt = $code . " - " . $productComboName . "Product Combo is updated.";
            if ($code != 'false') {
                if ($productComboCode == '' && $isActive == 1) {
                    $count = count($this->GlobalModel->selectActiveData('productcombo')->result_array());
                }

                $productComboImage =  "";
				$filename="";
                $uploadDir = "upload/productComboImage/$cmpcode";
                if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
                if (!empty($_FILES['productComboImage']['name'])) {
                    $tmpFile = $_FILES['productComboImage']['tmp_name'];
                    $ext = pathinfo($_FILES['productComboImage']['name'], PATHINFO_EXTENSION);
                    $filename = $uploadDir . '/' . $result . '-' . time() . '.' . $ext;
                    move_uploaded_file($tmpFile, $filename);
                    if (file_exists($filename)) {
                        $subData = array(
							'productComboImage' => $filename
						);
						$file = $this->GlobalModel->doEdit($subData, 'productcombo', $code);
                    }
                } 
                

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

    public function update()
    {
        $date = date('Y-m-d H:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $cmpcode = $this->GlobalModel->getCompcode();
        $productComboName = trim($this->input->post("cname1"));
        $productHindiName = trim($this->input->post("chindiname1"));
        $productUrduName = trim($this->input->post("curduname1"));
        $productArabicName = trim($this->input->post("carabicname1"));
        $productCategory  = $this->input->post("productcategory1");
        $productComboPrice = $this->input->post("price1");
        $productTaxPrice = $this->input->post("totalTaxAmount");
        $finalAmount = $this->input->post("finalAmount");
        $productComboCode = $this->input->post("ccode");
        $isActive = $this->input->post("isActive");
        $productName = $this->input->post("pro_name_line");
        $productPrice = $this->input->post("pr_price_line");
        //$taxPrice = $this->input->post("tax_price_line");
        $ip = $_SERVER['REMOTE_ADDR'];

        $condition2 = array('LOWER(productComboName)' => strtolower($productComboName));
        if ($productComboCode != '') {
            $condition2['productcombo.code!='] =  $productComboCode;
        }
        $result = $this->GlobalModel->checkDuplicateRecordNew($condition2, 'productcombo');
        if ($result == true) {
            $response['status'] = false;
            $response['message'] = 'Duplicate Productcombo';
        } else {
            $data = array(
                'productComboName' => $productComboName,
                'productComboUrduName' => $productUrduName,
                'productComboHindiName' => $productHindiName,
                'productComboArabicName' => $productArabicName,
                'productCategoryCode' => $productCategory,
                'productComboPrice' => $productComboPrice,
                'taxAmount' => $productTaxPrice,
                'productFinalPrice' => $finalAmount,
                'isActive' => $isActive
            );
            $data['editID'] = $addID;
            $data['editIP'] = $ip;
            $result = $this->GlobalModel->doEdit($data, 'productcombo',  $productComboCode);

            $previousCount = 0;

            $count = count($productName);
            $delete = $this->GlobalModel->plainQuery("DELETE FROM productcombolineentries WHERE `productComboCode`= '" . $productComboCode . "'");

            for ($i = 0; $i < $count; $i++) {
                $proName = $this->input->post("pro_name_line")[$i];
                $prPrice = $this->input->post("pr_price_line")[$i];
                $taxPrice = $this->input->post("tax_price_line")[$i];
                if ($proName != '' && $prPrice != '') {
                    $dataLine = array(
                        'productComboCode' => $productComboCode,
                        'productCode' => $proName,
                        'productPrice' => $prPrice,
                        'productTaxPrice' => $taxPrice,
                        'isActive' => 1,
                        'addID' => $addID,
                        'addIP' => $ip,
                    );
                    $resultnew = $this->GlobalModel->addNew($dataLine, 'productcombolineentries', 'PCOL');
                    $previousCount++;
                }
            }


            $filedoc = false;
            $filename = '';
            $uploadDir = "upload/productComboImage/$cmpcode";
            if (!file_exists($uploadDir)) mkdir($uploadDir, 0755, false);
            if (!empty($_FILES['productComboImage']['name'])) {

                $filepro = $this->GlobalModel->directQuery("SELECT productComboImage FROM productcombo WHERE `code` = '" .  $productComboCode . "'");
                if (!empty($filepro)) {
							$myImg = $filepro[0]['productComboImage'];
							if ($myImg != "" && file_exists($myImg))  unlink($myImg);
						}
                $tmpFile = $_FILES['productComboImage']['tmp_name'];
                $ext = pathinfo($_FILES['productComboImage']['name'], PATHINFO_EXTENSION);
                $filename = $uploadDir . '/' .  $productComboCode . '-' . time() . '.' . $ext;
                move_uploaded_file($tmpFile, $filename);
                if (file_exists($filename)) {
                     $subData = array(
						'productComboImage' => $filename
					);
                  $filedoc = $this->GlobalModel->doEdit($subData, 'productcombo',  $productComboCode);
                }               
            }

            $successMsg = "Product Combo Updated Successfully"; 
            $errorMsg = "Failed To Update Product Combo";
            $txt = $productComboCode . " - " . $productComboName . "Product Combo is updated.";
            if ($result != 'false' || $previousCount > 0) {
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

    public function deleteProductCombo()
    {
        $date = date('Y-m-d H:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $loginrole = "";
        $code = $this->input->post('code');
        $ip = $_SERVER['REMOTE_ADDR'];
        $groupName = $this->GlobalModel->selectDataById($code, 'productcombo')->result()[0]->taxName;
        $query = $this->GlobalModel->delete($code, 'productcombo');
        if ($query) {
            $txt = $code . " - " . $groupName . " Product Combo is deleted.";
            $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
            $this->GlobalModel->activity_log($activity_text);
        }
        echo $query;
    }

    public function editProductCombo()
    {
        if ($this->rights != '' && $this->rights['update'] == 1) {
            $data['updateRights'] = $this->rights['update'];
            //$code = $this->input->post('code');
            $code = $this->uri->segment(3);
            $data['category'] = $this->GlobalModel->selectActiveData('productcategorymaster');
            $data['subcategory'] = $this->GlobalModel->selectActiveData('productsubcategorymaster');

            $table_category = 'productmaster';
            $orderColumns_category = array("productmaster.*");
            $cond_category = array('productmaster' . '.isDelete' => 0, 'productmaster' . '.isActive' => 1, 'recipecard' . '.isActive' => 1);
            $data['productdata'] = $this->GlobalModel->selectQuery($orderColumns_category, $table_category, $cond_category, array(), array('recipecard' => 'recipecard.productCode=productmaster.code'), array('recipecard' => 'inner'));

            $tableName = "productcombo";
            $orderColumns = array("productcombo.*");
            $condition = array("productcombo.code" => $code);
            $orderBy = array();
            $joinType = array();
            $join = array();
            $groupByColumn = array();
            $limit = $this->input->GET("length");
            $offset = $this->input->GET("start");
            $extraCondition = "";
            $like = array();
            $Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
            if ($Records) {
                $data['productCombo'] = $Records->result()[0];
                $tableNameLine = "productcombolineentries";
                $orderColumnsLine = array("productcombolineentries.*");
                $conditionLine = array("productcombolineentries.productComboCode" => $code,"productcombolineentries.isActive"=>1);
                $orderByLine = array();
                $joinTypeLine = array();
                $joinLine = array();
                $groupByColumnLine = array();
                $limitLine = $this->input->GET("length");
                $offsetLine = $this->input->GET("start");
                $extraConditionLine = "";
                $likeLine = array();
                $RecordsLine = $this->GlobalModel->selectQuery($orderColumnsLine, $tableNameLine, $conditionLine, $orderByLine, $joinLine, $joinTypeLine, $likeLine, $limitLine, $offsetLine, $groupByColumnLine, $extraConditionLine);
                $data['productComboLines'] = '';
                if ($RecordsLine) {
                    $data['productComboLines'] = $RecordsLine->result();
                }
                $this->load->view('dashboard/commonheader');
                $this->load->view('dashboard/productcombo/edit', $data);
                $this->load->view('dashboard/footer');
            }
        } else {
            $this->load->view('errors/norights.php');
        }
    }


    public function viewProductCombo()
    {
        if ($this->rights != '' && $this->rights['view'] == 1) {
            $code = $this->input->post('code');
            $data['category'] = $this->GlobalModel->selectActiveData('productcategorymaster');
            $data['subcategory'] = $this->GlobalModel->selectActiveData('productsubcategorymaster');
            $table_category = 'productmaster';
            $orderColumns_category = array("productmaster.*");
            $cond_category = array('productmaster' . '.isDelete' => 0, 'productmaster' . '.isActive' => 1);
            $data['productdata'] = $this->GlobalModel->selectQuery($orderColumns_category, $table_category, $cond_category);

            $tableName = "productcombo";
            $orderColumns = array("productcombo.*");
            $condition = array("productcombo.code" => $code);
            $orderBy = array();
            $joinType = array();
            $join = array();
            $groupByColumn = array();
            $limit = $this->input->GET("length");
            $offset = $this->input->GET("start");
            $extraCondition = "";
            $like = array();
            $Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
            if ($Records) {
                $data['productCombo'] = $Records->result()[0];
                $tableNameLine = "productcombolineentries";
                $orderColumnsLine = array("productcombolineentries.*");
                $conditionLine = array("productcombolineentries.productComboCode" => $code,"productcombolineentries.isActive"=>1);
                $orderByLine = array();
                $joinTypeLine = array();
                $joinLine = array();
                $groupByColumnLine = array();
                $limitLine = $this->input->GET("length");
                $offsetLine = $this->input->GET("start");
                $extraConditionLine = "";
                $likeLine = array();
                $RecordsLine = $this->GlobalModel->selectQuery($orderColumnsLine, $tableNameLine, $conditionLine, $orderByLine, $joinLine, $joinTypeLine, $likeLine, $limitLine, $offsetLine, $groupByColumnLine, $extraConditionLine);
                $data['productComboLines'] = '';
                if ($RecordsLine) {
                    $data['productComboLines'] = $RecordsLine->result();
                }
                $this->load->view('dashboard/productcombo/view', $data);
            }
        } else {
            $this->load->view('errors/norights.php');
        }
    }

    public function getProductData()
    {
        $prdHtml = '';
        $products = $this->GlobalModel->selectQuery('productmaster.code,productmaster.productEngName,productmaster.productPrice', 'productmaster', array('productmaster.isActive' => 1, 'productmaster.isDelete' => 0));
        if ($products && $products->num_rows() > 0) {
            $prdHtml .= '<option value="">Select Product</option>';
            foreach ($products->result() as $prd) {
                $prdHtml .= '<option value="' . $prd->code . '">' . $prd->productEngName . '</option>';
            }
        }
        $response['products'] = $prdHtml;
        $response['status'] = 'true';
        echo json_encode($response);
    }

    public function getPrice()
    {
        $code =  $this->input->post("code");
		$price = $this->GlobalModel->directQuery("SELECT productmaster.* FROM productmaster WHERE `code` = '" .  $code . "'");
        if($price){
			$response['status']  = 'true';
			$response['price']  = $price[0]['productPrice'];
			$productPrice = $price[0]['productPrice'];
			$productTaxGroup = $price[0]['productTaxGrp'];
			$data = $this->GlobalModel->directQuery("SELECT taxgroupmaster.* FROM taxgroupmaster WHERE `code` = '" .  $productTaxGroup . "'");
			$taxes = json_decode($data[0]['taxes']);
			$sum = 0;
			foreach ($taxes as $tax) {
				$getTax = $this->GlobalModel->directQuery("SELECT taxes.* FROM taxes WHERE `code` = '" .  $tax . "'");
				$taxPer = $getTax[0]['taxPer'];
				$sum = $sum + $taxPer;
			}
			$finalAmount = round(($productPrice / 100) * $sum); 
			$response['taxprice'] = $finalAmount;
		}else{
		    $response['status']  = 'true';
			$response['price']=0;
			$response['taxprice'] =0;
		}
        echo json_encode($response);
    }
	
	public function deleteProductComboItem()
	{
		$date = date('Y-m-d H:i:s');
		$lineCode = $this->input->post('lineCode');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$ip = $_SERVER['REMOTE_ADDR'];
		
		$productComboEntry = $this->GlobalModel->directQuery("SELECT productcombolineentries.* FROM productcombolineentries WHERE `code` = '" .  $lineCode . "'");
		if(!empty($productComboEntry)){
			$productComboCode=$productComboEntry[0]['productComboCode'];
			$productPrice  = $productComboEntry[0]['productPrice'];
			$productTaxPrice = $productComboEntry[0]['productTaxPrice'];
	        $productCombo=$this->GlobalModel->directQuery("SELECT productcombo.* FROM productcombo WHERE `code` = '" .  $productComboCode . "'");
		    $productUpdatesComboPrice=$productCombo[0]['productComboPrice']-$productPrice;
			$productUpdatedTaxPrice=$productCombo[0]['taxAmount']-$productTaxPrice;
			$productFinalPrice=$productCombo[0]['productFinalPrice']-$productPrice-$productTaxPrice;
			$subData = array(
						'productComboPrice' => $productUpdatesComboPrice,
						'taxAmount'=>$productUpdatedTaxPrice,
						'productFinalPrice'=>$productFinalPrice
					);
           $filedoc = $this->GlobalModel->doEdit($subData, 'productcombo',  $productComboCode);
		}
		
		$query = $this->GlobalModel->delete($lineCode, 'productcombolineentries');
		if ($query) {
			$txt = $lineCode . " Product combo items deleted";
			$activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
			$this->GlobalModel->activity_log($activity_text);
			$msg = true;
			echo json_encode($msg);
		} else {
			$msg = false;
			echo json_encode($msg);
		}
	}
}
