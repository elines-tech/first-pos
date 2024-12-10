<?php
require(APPPATH . '/libraries/REST_Controller.php');

use Restserver\Libraries\REST_Controller;

class Kitchen extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form', 'url', 'html');
        $this->load->library('form_validation');
        $this->load->model('OrderApiModel');
        $this->load->model('GlobalModel');
        $this->load->library('cart');
    }

    public function fetchKotOrderDetails_post()
    {
		$postData = $this->post();
        if ($postData['cartId'] != "") {
			$products = $this->OrderApiModel->get_kot_order_cart_products($postData['cartId'] );
            if (!empty($products)) {
				return $this->response(array("status" => "200", "message" => "Data found", "data" => $products), 200);
			}
			return $this->response(array("status" => "300", "message" => "No Data found", "data" => []), 200);
		}
    }

    public function changeStatus_post()
    {
        $postData = $this->post();
        if ($postData['cartId'] != "" && $postData['kotStatus']) {
            $cartId = $postData['cartId'];
            $kotStatus = $postData['kotStatus'];
            $udpateData['kotStatus'] = $kotStatus;
            if ($kotStatus == "PRE") {
                $udpateData['waitingMinutes'] = 0;
                $udpateData['preparingDateTime'] = date("Y-m-d H:i:s");
            } else {
                $udpateData['servereadyDateTime'] = date("Y-m-d H:i:s");
            }
            $result = $this->OrderApiModel->change_kot_order_status($cartId, $udpateData);
            if ($result) {
                if ($kotStatus == "RTS") {
                    //update stock
                    $branchCode = $this->OrderApiModel->get_cart_branch_code($cartId);
                    $products = $this->OrderApiModel->get_kot_order_cart_products($cartId);
                    if (!empty($products)) {
                        foreach ($products as $key => $value) {
                            $productCode = $value['productCode'];
                            $productQty = $value['productQty'];                            
                            $addOns = json_decode($value['addOns'], true);
                            $this->GlobalModel->consumeStkQty($productCode,$productQty,$branchCode);
                            if (!empty($addOns)) {
                                foreach ($addOns as $addOn) {
                                    $productCode = $addOn['addonCode'];
                                    $productQty = $addOn['addonQty'];
                                    $this->GlobalModel->consumeStkQty($productCode,$productQty,$branchCode);
                                }
                            }
                        }
                    }
                }
                return $this->response(array("status" => "200", "message" => "KOT order status updated successfully"), 200);
            }
            return $this->response(array("status" => "300", "message" => "Failed to change the status"), 200);
        }
        return $this->response(array("status" => "400", "message" => "Something went wrong..."), 200);
    }
}
