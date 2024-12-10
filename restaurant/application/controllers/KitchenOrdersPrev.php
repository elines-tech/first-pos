<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KitchenOrders extends CI_Controller
{
    var $session_key;
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form', 'url', 'html');
        $this->load->model('GlobalModel');
        $this->load->library('form_validation');
        $this->session_key = $this->session->userdata('key' . SESS_KEY);
        if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
            redirect('Login', 'refresh');
        }
    }
    public function listOrders()
    {
        $this->load->view('dashboard/commonheader');
        $this->load->view('dashboard/kitchenorders/list');
    }

    public function getKitchenOrder()
    {
        $table_category = 'orderstatusmaster';
        $orderColumns_category = array("orderstatusmaster.*");
        $cond_category = array();
        $data['orderstatus'] = $this->GlobalModel->selectQuery($orderColumns_category, $table_category, $cond_category);

        $today = date('Y-m-d');
        $orderColumns = array("ordermaster.*,bookorderstatuslineentries.statusTime,bookorderstatuslineentries.orderStatus as statusLine");
        $table = "ordermaster";
        $condition = array();
        $orderBy = array('ordermaster' . '.id' => 'DESC');
        $join = array('bookorderstatuslineentries' => 'bookorderstatuslineentries.orderCode=ordermaster.code and bookorderstatuslineentries.orderStatus=ordermaster.orderStatus');
        $joinType = array('bookorderstatuslineentries' => 'inner');
        $limit = "";
        $offset = "";
        $fromDate = $today . " 00:00:00";
        $toDate = $today . " 23:59:59";
        $groupByColumn = array();
        $extraCondition = "ordermaster.orderStatus in ('PND','PRO','PRE','RTS') and (ordermaster.isDelete=0 OR ordermaster.isDelete IS NULL)";
        $like = array();
        $ordersArray = array();
        $Records = $this->GlobalModel->selectQuery($orderColumns, $table, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
        if ($Records) {
            foreach ($Records->result() as $row) {
                $orderCode = $row->code;
                $orderDate = date('Y-m-d h:i:s', strtotime($row->addDate));
                $orderAcceptDateTime = $row->statusTime;
                $preparingMinutes = $row->totalpreparationTime;

                //empty order array
                $particulars = $order = array();

                // get order products
                $tableName = 'orderlineentries';
                $orderColumns = array("orderlineentries.*,productmaster.productEngName,productmaster.productImage");
                $condition = array('orderlineentries.orderCode' => $orderCode);
                $orderBy = array('orderlineentries.id' => 'desc');
                $joinType = array('productmaster' => 'inner');
                $join = array('productmaster' => 'productmaster.code=orderlineentries.productCode');
                $extraCondition = "orderlineentries.isActive=1";
                $items = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, array(), "", "", array(), $extraCondition);
                $srno = 0;
                if ($items) {
                    foreach ($items->result() as $rr) {
                        $productImage = $rr->productImage;
                        $itemPhoto = "nophoto";
                        if ($productImage != "") {
                            $path = $productImage;
                            if (file_exists($path)) {
                                $itemPhoto = base_url($path);
                            }
                        }
                        $particulars[] = array("productName" => $rr->productEngName, "productImage" => $itemPhoto, "quantity" => $rr->productQty, "pricewithQty" => $rr->productPrice);
                        $srno++;
                    }
                }

                //assignOrdeValues
                $order['orderCode'] = $row->code;
                $order['tableCode'] = $row->tableCode;
                $order['orderStatus'] = $row->orderStatus;
                $order['couponCode'] = $row->couponCode;
                $order['discount'] = $row->discount;
                $order['subtotal'] = $row->subtotal;
                $amount = $row->grandTotal;
                $order['totalAmount'] = $amount;
                $order['actualAmount'] = $amount - $row->discount;
                $order['orderDate'] = $orderDate;
                $order['preparingMinutes'] = $preparingMinutes;
                $order['prepareDateTime'] = $orderAcceptDateTime;
                $order['noofItems'] = $srno;
                $order['particulars'] = $particulars;
                $order['statusLine'] = $row->statusLine;
                $ordersArray[] = $order;
            }
            $data["ordersData"] = $ordersArray;
        } else {
            $data["ordersData"] = false;
        }

        $this->load->view('dashboard/kitchenorders/kitchenorder', $data);
    }

    public function updateOrderStatus()
    {
        $orderCode = $this->input->post('orderCode');
        $orderStatus = $this->input->post('orderStatus');
        $date = date("Y-m-d H:i:s");
        $string = "Placed";
        $statusReason = '';
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];

        $ip = $_SERVER['REMOTE_ADDR'];

        $txt =  $orderCode . " - " .  $orderCode . "Order is updated.";
        $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
        $this->GlobalModel->activity_log($activity_text);

        $data = array('orderStatus' => $orderStatus, 'editID' => $addID, 'editDate' => $date);
        /* if ($orderStatus == "PRO") {
            $data["preparingMinutes"] = 25;
        }*/

        $result = $this->GlobalModel->doEdit($data, 'ordermaster', $orderCode);

        if ($result != 'false') {
            if ($orderStatus == "PRO") {
                $statusReason = "Food is processing";
            } else if ($orderStatus == "RTS") {
                $statusReason = "Food is Ready to serve";
            } else {
                $statusReason = '';
            }
            $bookLineResult = 'true';
            $order_status = $this->GlobalModel->selectQuery("orderstatusmaster.*", "orderstatusmaster", array("orderstatusmaster.statusSName" => $orderStatus));
            if ($order_status && count($order_status->result_array()) > 0) {
                $order_status_record = $order_status->result()[0];
                #replace $ template in title 
                $dataBookLine = array(
                    "orderCode" => $orderCode,
                    "orderStatus" => $orderStatus,
                    "statusTime" => $date,
                    "statusText" => $statusReason,
                    "isActive" => 1,
                    "addID" => $addID,
                    "addDate" => $date,
                    "addIP" => $ip
                );
                $bookLineResult = $this->GlobalModel->addWithoutYear($dataBookLine, 'bookorderstatuslineentries', 'BOL');
            }

            if ($bookLineResult != 'false') {
                $orderData = $this->GlobalModel->selectQuery("ordermaster.*", 'ordermaster', array("ordermaster.code" => $orderCode));
                /*if($orderData){


                }*/
                $response['status'] = true;
                $response['message'] = "Order Status Changed Successfully.";
            } else {
                $response['status'] = false;
                $response['message'] = "Failed To Change Status.";
            }
        } else {
            $response['status'] = false;
            $response['message'] = "Failed To Change Status";
        }
        echo json_encode($response);
    }

    public function getOrderDetails()
    {
        $orderCode = $this->input->post('orderCode');
        $today = date('Y-m-d');
        $orderColumns = array("ordermaster.*");
        $table = "ordermaster";
        $condition = array("ordermaster.code" => $orderCode);
        $orderBy = array();
        $join = array();
        $joinType = array();
        $limit = "";
        $offset = "";
        $groupByColumn = array();
        $extraCondition = "";
        $like = array();
        $Records = $this->GlobalModel->selectQuery($orderColumns, $table, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
        if ($Records) {
            $data['orderData'] = $Records->result()[0];
            //$orderCode = $Records->result()[0]->code;
            $this->load->view('dashboard/kitchenorders/orderdetails', $data);
        }
    }

    public function getOrderLineEntries()
    {
        $orderCode = $this->input->get('orderCode');
        $noPic = $this->input->get('noPic');
        $tableName = 'orderlineentries';
        $orderColumns = array("orderlineentries.*,productmaster.productEngName,productmaster.productImage");
        $condition = array('orderlineentries.orderCode' => $orderCode);
        $orderBy = array('orderlineentries.id' => 'desc');
        $joinType = array('productmaster' => 'inner');
        $join = array('productmaster' => 'productmaster.code=orderlineentries.productCode');
        $groupByColumn = array();
        $limit = $this->input->GET("length");
        $offset = $this->input->GET("start");
        $srno = $offset + 1;
        $addonText = '';
        $extraCondition = '';
        //$extraCondition = "orderlineentries.isActive=1";
        $like = array();
        $data = array();
        $Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
        //echo $this->db->last_query();
        if ($Records) {
            foreach ($Records->result() as $row) {
                $addonText = '';
                if ($row->addonsCode != '' && $row->addonsCode != NULL) {
                    $row->addonsCode = rtrim($row->addonsCode, ',');
                    $savedaddonsCodes = explode(',', $row->addonsCode);
                    foreach ($savedaddonsCodes as $addon) {
                        $joinType1 = array('customizedcategory' => 'inner');
                        $condition1 = array('customizedcategorylineentries.code' => $addon);
                        $join1 = array('customizedcategory' => "customizedcategory.code=customizedcategorylineentries.customizedCategoryCode");
                        $getAddonDetails = $this->GlobalModel->selectQuery("customizedcategory.categoryTitle,customizedcategory.categoryType,customizedcategorylineentries.subCategoryTitle,customizedcategorylineentries.price", "customizedcategorylineentries", $condition1, array(), $join1, $joinType1, array(), array(), '', array(), '');
                        if ($getAddonDetails) {
                            foreach ($getAddonDetails->result() as $ad) {
                                $mainCategory = $ad->categoryTitle;

                                $addonText .= '<ul>
									<li style="text-align:left"><b>' . $ad->categoryTitle . ' - ' . ucfirst($ad->categoryType) .
                                    '</b>';
                                if ($ad->categoryType == 'product') {
                                    $productCode = $ad->subCategoryTitle;
                                    $productName = $this->GlobalModel->selectDataByField('code',  $productCode, 'productmaster');
                                    $productTitle = $productName->result_array()[0]['productEngName'];
                                    $addonText .= '<ul>
                                    <li style="text-align:left">' .  $productTitle . ' - ' . $ad->price . '</li>
                                     </ul>';
                                } else {
                                    $addonText .= '<ul style="margin-left:-15px">
										          <li style="text-align:left">' . $ad->subCategoryTitle . ' - ' . $ad->price . '</li>
									      </ul>';
                                }
                                $addonText .= '</li>
								</ul>';
                            }
                        }
                    }
                }
                $start = '<div class="d-flex align-items-center">';
                $end = ' <h5 class="m-b-0 font-16 font-medium">' . $row->productEngName . '</h5></div></div>';
                $itemPhotoCheck = $row->productImage;
                if ($itemPhotoCheck != "") {
                    $itemPhoto = base_url($itemPhotoCheck);
                    $photo = '<div class=""><img src="' . $itemPhoto . '?' . time() . '" alt="user" class="circle" width="50"></div><div class="ms-4">';
                    $itemName = $start . $photo . $end;
                    $data[] = array($srno, $itemName . '<br>' . $addonText, $row->productPrice, $row->productQty, $row->totalPrice);
                } else {
                    $itemName = ' <h5 class="m-b-0 font-16 font-medium">' . $row->itemName . '</h5></div></div>';
                    $data[] = array($srno, $itemName . '<br>' . $addonText, $row->productPrice, $row->productQty, $row->totalPrice);
                }
                $srno++;
            }
        }
        $dataCount = 0;
        $dataCount1 = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, '', '', $groupByColumn, $extraCondition);
        if ($dataCount1) {
            $dataCount = sizeOf($dataCount1->result());
        }
        $output = array("draw" => intval($_GET["draw"]), "recordsTotal" => $dataCount, "recordsFiltered" => $dataCount, "data" => $data);
        echo json_encode($output);
    }
}
