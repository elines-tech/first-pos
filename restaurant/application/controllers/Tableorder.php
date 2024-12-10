<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Tableorder extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('form', 'url', 'html');
        $this->load->model('AdminModel');
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

    public function add($enckey, $tokenNumber)
    {
        //echo $enckey . '<br>';
        //echo $tokenNumber . '<br>';
        if ($enckey != null && $tokenNumber != null) {
            $companyDB = $this->decrypt($enckey);
            $dbname = TBLORDER_DB_PREFIX . "_" . $companyDB;
            $tableName = "databasemaster";
            $orderColumns = array("databasemaster.databaseName");
            $condition = array("databasemaster.databaseName" => strtolower($dbname));
            $orderBy = array();
            $CompanyDbResult = $this->AdminModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, [], [], [], 1);
            if ($CompanyDbResult != false) {
                $t = 'receipt' . time();
                $this->session->set_userdata('key' . SESS_KEY, $t);
                $this->session->set_userdata('current_db' . $t, $CompanyDbResult->result_array()[0]["databaseName"]);
                sleep(2);
                $this->load->model('OrderApiModel');
                $data['table'] = $this->OrderApiModel->get_table_by_unique_id($tokenNumber);

                if (!empty($data['table'])) {
                    $tbl = $data['table'];
                    $this->load->model('GlobalModel');
                    $categories = $this->GlobalModel->selectActiveData('productcategorymaster');
                    $data['categories'] = $categories;
                    $categoryProducts = [];
                    if ($categories) {
                        foreach ($categories->result_array() as $category) {
                            $joinType['recipecard'] = "inner";
                            $join['recipecard'] = "productmaster.code=recipecard.productCode";
                            $products = $this->GlobalModel->selectQuery("productmaster.*", "productmaster", ["productmaster.productCategory" => $category['code'], "productmaster.isActive" => 1], array("productEngName" => "ASC"), $join, $joinType);
                            $joinType1['productsubcategorymaster'] = "inner";
                            $joinType1['productcategorymaster'] = "inner";
                            $join1["productsubcategorymaster"] = "productcombo.productCategoryCode = productsubcategorymaster.code";
                            $join1["productcategorymaster"] = "productsubcategorymaster.categoryCode = productcategorymaster.code";
                            $comboproducts = $this->GlobalModel->selectQuery("productcombo.*", "productcombo", ["productsubcategorymaster.categoryCode" => $category['code'], "productcombo.isActive" => 1], [], $join1, $joinType1);
                            $catg = [
                                "categoryCode" => $category['code'],
                                "categoryName" => $category['categoryName'],
                                "products" => $products ? $products->result_array() : [],
                                "comboProducts" => $comboproducts ? $comboproducts->result_array() : []
                            ];
                            array_push($categoryProducts, $catg);
                        }
                        $data['categoryProducts'] = $categoryProducts;
                        $data['mainOrder'] = $this->OrderApiModel->get_table_main_order_cart($tbl);
                        $this->load->view('tableorder/header');
                        $this->load->view('tableorder/add', $data);
                        $this->load->view('tableorder/footer');
                    }
                } else {
                    $this->load->view('dashboard/order/404');
                }
            } else {
                $this->load->view('dashboard/order/404');
            }
        } else {
            $this->load->view('dashboard/order/404');
        }
    }

    public function getMyOrders()
    {
        $this->load->model('OrderApiModel');
        $tableNumber = $this->input->get("tableNumber");
        $branchCode = $this->input->get("branchCode");
        $tableSection = $this->input->get("tableSection");
        $custPhone = $this->input->get("custPhone");
        $kotOrders = $this->OrderApiModel->get_customer_kot_orders($branchCode, $tableSection, $tableNumber,  $custPhone);
        if (!empty($kotOrders)) {
            $data = [];
            foreach ($kotOrders as $kotOrder) {
                $cartId = $kotOrder['id'];
                $products = $this->OrderApiModel->get_customer_cart_products($cartId);
                $kotOrder['products'] = $products;
                $data[] = $kotOrder;
            }
            $result['orders'] = $data;
            $this->load->view("tableorder/orders", $result);
        }
    }

    public function cancelKotOrder()
    {
        $this->load->model('GlobalModel');
        $cartId = $this->input->post("cartId");
        $result = $this->GlobalModel->get_row_array(['id' => $cartId], 'cart');
        if (!empty($result)) {
            switch ($result['kotStatus']) {
                case "PND":
                    $this->GlobalModel->delete_by_where(["cartId" => $cartId], 'cartproducts');
                    $this->GlobalModel->delete_by_where(["id" => $cartId], 'cart');
                    $res = ["status" => "200", "message" => "Your order was cancelled successfully"];
                    break;
                case "PRE":
                    $res = ["status" => "300", "message" => "Your food-order is being prepared in kitchen! Now you cannot cancel the order."];
                    break;
                case "RTS":
                    $res = ["status" => "300", "message" => "Your food-order was prepared and is ready to be served soon. Now you cannot cancel the order.."];
                    break;
            }
        } else {
            $res = ["status" => "300", "message" => "Something went wrong. Please try again"];
        }
        echo json_encode($res);
        exit();
    }
	
	public function checkBookedTable(){ 
	    $tableNumber = $this->input->post('tableNumber');
        $countryCode = $this->input->post('countryCode');
        $phone = $this->input->post('phoneNumber');
        $branchCode = $this->input->post('branchCode');
        $sectionCode = $this->input->post('tableSection');
        $phoneNumber = $countryCode . $phone;
		//$mobileNumber = $phone;
        $this->load->model('GlobalModel'); 
		
		$cartresult = $this->GlobalModel->directQuery("SELECT cart.* FROM cart WHERE `tableNumber` = '" . $tableNumber . "' and `branchCode` = '" . $branchCode . "' and `tableSection` = '" . $sectionCode . "' and `custPhone` = '" . $phoneNumber . "'");
		if(!empty($cartresult)){
			$response['status'] = true;
            $response['message'] = "Order is allowed"; 
			echo json_encode($response);
			die;
		}
		$resTable=$this->GlobalModel->directQuery("select * from tablereservation where `tableNumber`= '" . $tableNumber . "' and '" . date('Y-m-d H:i') . "' BETWEEN TIMESTAMP(date(tablereservation.resDate),tablereservation.startTime) and TIMESTAMP(date(tablereservation.resDate),tablereservation.endTime)");
	    if(!empty($resTable)){
			foreach($resTable as $item){
				if($phoneNumber==$item["customerMobile"]){
					$response['status'] = true;
					$response['message'] = "Order is allowed"; 
					echo json_encode($response);
					die;
				}
			}
            $response['status'] = false;
			$response['message'] = "Table is already reserved for another order."; 
			echo json_encode($response);			
		}else{
			$response['status'] = true;
            $response['message'] = "Order is allowed";  
			echo json_encode($response);
			die;
		}
	}

    public function checkBookedTableold()
    {
        $tableNumber = $this->input->post('tableNumber');
        $countryCode = $this->input->post('countryCode');
        $phone = $this->input->post('phoneNumber');
        $branchCode = $this->input->post('branchCode');
        $sectionCode = $this->input->post('tableSection');
        $phoneNumber = $countryCode . $phone;
        $this->load->model('GlobalModel');
        $tableName = "tablereservation";
        $orderColumns = "tablereservation.customerMobile";
        $condition = array('tablereservation.tableNumber' => $tableNumber, 'tablereservation.customerMobile' => $phoneNumber);
        $extraCondition = "'" . date('Y-m-d H:i') . "' between TIMESTAMP(date(tablereservation.resDate),tablereservation.startTime) and TIMESTAMP(date(tablereservation.resDate),tablereservation.endTime)";
        $Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, array(), array(), array(), array(), '', '', array(), $extraCondition);
		if ($Records && $Records->num_rows() > 0) {
            $response['status'] = true;
            $response['message'] = "Order is allowed"; 
        } else {
            $table = "cart";
            $order = "cart.id";
            $cond = array('cart.tableNumber' => $tableNumber, 'cart.branchCode' => $branchCode, 'cart.tableSection' => $sectionCode, 'cart.custPhone' => $phoneNumber);
            $cartResult = $this->GlobalModel->selectQuery($order, $table, $cond, array(), array(), array(), array(), '', '', array(), '');
            if ($cartResult && $cartResult->num_rows() > 0) {
                $response['status'] = true;
                $response['message'] = "Order is allowed";
            } else {
                $cond1 = array('cart.tableNumber' => $tableNumber, 'cart.branchCode' => $branchCode, 'cart.tableSection' => $sectionCode, 'cart.custPhone !=' => $phoneNumber);
                $result = $this->GlobalModel->selectQuery($order, $table, $cond1, array(), array(), array(), array(), '', '', array(), '');
                if ($result && $result->num_rows() > 0) {
                    $response['status'] = false;
                    $response['message'] = "Order is not allowed";
                } else {
                    $response['status'] = true;
                    $response['message'] = "Order is allowed";
                }
            }
        }
        echo json_encode($response);
    }
}
