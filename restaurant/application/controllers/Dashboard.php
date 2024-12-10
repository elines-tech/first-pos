<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    var $session_key;
    protected $rolecode, $branchCode;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('GlobalModel');
        $this->session_key = $this->session->userdata('key' . SESS_KEY);
        if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
            redirect('Login', 'refresh');
        }
        $this->rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
        $this->salesrights = $this->GlobalModel->getMenuRights('1.1', $this->rolecode);
        $this->invrights = $this->GlobalModel->getMenuRights('1.2', $this->rolecode);
        $this->branchCode = $this->session->userdata['logged_in' . $this->session_key]['branchCode'];
        $res = $this->GlobalModel->checkActiveSubscription();
        if ($res == "EXPIRED") {
            redirect('package', 'refresh');
        }
    }

    public function listRecords()
    {
        if ($this->invrights != '') {
            $this->load->view('dashboard/header');
            $this->load->view('dashboard/dashboard/index');
            $this->load->view('dashboard/footer');
        } else {
            $this->load->view('errors/norights.php');
        }
    }

    public function getOrderCounts()
    {
        $branchCode = "";
        if ($this->branchCode != "") {
            $branchCode = $this->branchCode;
        }
        $con['itemmaster.isActive'] = 1;
        $extraCon = "";
        $totalItems = $this->GlobalModel->selectQuery("count(distinct itemmaster.code) as cnt", "itemmaster", $con, array(), array(), array(), array(), '', '', array(), $extraCon);
        if ($totalItems) {
            $countItems = $totalItems->result()[0]->cnt;
        }

        $conPro['productmaster.isActive'] = 1;
        $totalPro = $this->GlobalModel->selectQuery("count(distinct productmaster.code) as cnt", "productmaster", $conPro, array(), array(), array(), array(), '', '', array(), "");
        if ($totalPro) {
            $countPro =  $totalPro->result()[0]->cnt;
        }

        $conBranch['branchmaster.isActive'] = 1;
        $totalBranch = $this->GlobalModel->selectQuery("count(distinct branchmaster.code) as cnt", "branchmaster", $conBranch, array(), array(), array(), array(), '', '', array(), "");
        if ($totalBranch) {
            $countBranch =  $totalBranch->result()[0]->cnt;
        }

        $conUsers['usermaster.isActive'] = 1;
        $totalUsers = $this->GlobalModel->selectQuery("count(distinct usermaster.code) as cnt", "usermaster", $conUsers, array(), array(), array(), array(), '', '', array(), "");
        if ($totalUsers) {
            $countUser =  $totalUsers->result()[0]->cnt;
        }

        $conSuppliers['suppliermaster.isActive'] = 1;
        $totalSuppliers = $this->GlobalModel->selectQuery("count(distinct suppliermaster.code) as cnt", "suppliermaster", $conSuppliers, array(), array(), array(), array(), '', '', array(), "");
        if ($totalSuppliers) {
            $countSupplier =  $totalSuppliers->result()[0]->cnt;
        }


        $conPurchase['inwardentries.isActive'] = 1;
        $conPurchase['inwardentries.branchCode'] = $branchCode;
        $totalPurchase = $this->GlobalModel->selectQuery("sum(inwardentries.total) as total", "inwardentries", $conPurchase, array(), array(), array(), array(), '', '', array(), "");
        if ($totalPurchase) {
            $tPurchase =  $totalPurchase->result()[0]->total;
        }
        $res['countItem'] = $countItems;
        $res['countProduct'] = $countPro;
        $res['countBranch'] = $countBranch;
        $res['countUser'] = $countUser;
        $res['countSupplier'] = $countSupplier;
        $res['totalPurchase'] = $tPurchase;
        echo json_encode($res);
    }

    public function listRecordsSales()
    {
        //$this->checkSubscription();
        if ($this->salesrights != '') {
            $table_name = 'branchmaster';
            $orderColumns = array("branchmaster.*");
            $cond = array('branchmaster' . '.isDelete' => 0, 'branchmaster' . '.isActive' => 1);
            $data['branchdata'] = $this->GlobalModel->selectQuery($orderColumns, $table_name, $cond);
            $data['branchCode'] = "";
            $data['branchName'] = "";
            if ($this->branchCode != "") {
                $data['branchCode'] = $this->branchCode;
                $data['branchName'] = $this->GlobalModel->selectDataById($this->branchCode, 'branchmaster')->result()[0]->branchName;
            }
            $this->load->view('dashboard/header');
            $this->load->view('dashboard/dashboard/sales', $data);
            $this->load->view('dashboard/footer');
        } else {
            $this->load->view('errors/norights.php');
        }
    }

    public function getSalesCount()
    {
        $branchCode = $this->input->get("branchCode");
        if ($this->branchCode != "") {
            $branchCode = $this->branchCode;
        }
        $con['ordermaster.branchCode'] = $branchCode;
        $extraCond = " (ordermaster.addDate between '" . date("Y-m-d") . " 00:00:01' and '" . date("Y-m-d") . " 23:59:59')";
        $torderQuery = $this->GlobalModel->selectQuery("ifnull(count(id),0) as torderCount", "ordermaster", $con, array(), array(), array(), array(), '', '', array(), $extraCond);
        if ($torderQuery) {
            $todaysOrders =  $torderQuery->result()[0]->torderCount;
        }

        $condition['ordermaster.branchCode'] = $branchCode;
        $condition['ordermaster.orderType'] = 'dine-in';
        $dining = $this->GlobalModel->selectQuery("ifnull(count(id),0) as dining", "ordermaster", $condition, array(), array(), array(), array(), '', '', array(), $extraCond);
        if ($dining) {
            $todaydining =  $dining->result()[0]->dining;
        }

        $condition1['ordermaster.branchCode'] = $branchCode;
        $condition1['ordermaster.orderType'] = 'take-away';
        $pickup = $this->GlobalModel->selectQuery("ifnull(count(id),0) as pickup", "ordermaster", $condition1, array(), array(), array(), array(), '', '', array(), $extraCond);
        if ($pickup) {
            $todaypickup =  $pickup->result()[0]->pickup;
        }

        $condition2['ordermaster.branchCode'] = $branchCode;
        $condition2['ordermaster.orderType'] = 'delivery';
        $deliver = $this->GlobalModel->selectQuery("ifnull(count(id),0) as deliver", "ordermaster", $condition2, array(), array(), array(), array(), '', '', array(), $extraCond);
        if ($deliver) {
            $todaydeliver =  $deliver->result()[0]->deliver;
        }

        $sale = $this->GlobalModel->selectQuery("ifnull(sum(ordermaster.grandTotal),0) as sales", "ordermaster", $con, array(), array(), array(), array(), '', '', array(), $extraCond);
        if ($sale) {
            $todaysale =  $sale->result()[0]->sales;
        }

        $discount = $this->GlobalModel->selectQuery("ifnull(sum(ordermaster.discount),0) as discount", "ordermaster", $con, array(), array(), array(), array(), '', '', array(), $extraCond);
        if ($discount) {
            $todaydiscount =  $discount->result()[0]->discount;
        }

        $tax = $this->GlobalModel->selectQuery("ifnull(sum(ordermaster.tax),0) as tax", "ordermaster", $con, array(), array(), array(), array(), '', '', array(), $extraCond);
        if ($tax) {
            $todaytax =  $tax->result()[0]->tax;
        }
        $extra = " (customer.addDate between '" . date("Y-m-d") . " 00:00:01' and '" . date("Y-m-d") . " 23:59:59')";
        $customer = $this->GlobalModel->selectQuery("ifnull(count(id),0) as customer", "customer", array(), array(), array(), array(), array(), '', '', array(), $extra);
        if ($customer) {
            $todaycustomer =  $customer->result()[0]->customer;
        }

        $res['countOrders'] = $todaysOrders;
        $res['countDining'] = $todaydining;
        $res['countPickup'] = $todaypickup;
        $res['countDeliver'] = $todaydeliver;
        $res['totalSale'] = $todaysale;
        $res['totalDiscount'] = $todaydiscount;
        $res['totalTax'] = $todaytax;
        $res['totalCustomer'] = $todaycustomer;

        echo json_encode($res);
    }

    public function getInwardList()
    {
        $branchCode = "";
        if ($this->branchCode != "") {
            $branchCode = $this->branchCode;
        }
        $tableName = "inwardentries";
        $search = $this->input->GET("search")['value'];
        $orderColumns = array("inwardentries.code,inwardentries.branchCode,branchmaster.branchName,suppliermaster.supplierName,inwardentries.inwardDate,inwardentries.total, inwardentries.isActive");
        $condition = array("inwardentries.branchCode" => $branchCode);
        $orderBy = array('inwardentries.id' => 'DESC');
        $joinType = array('branchmaster' => 'inner', 'suppliermaster' => 'inner');
        $join = array('branchmaster' => 'branchmaster.code=inwardentries.branchCode', 'suppliermaster' => 'suppliermaster.code=inwardentries.supplierCode');
        $groupByColumn = array();
        $limit = 5;
        $offset = $this->input->GET("start");
        $extraCondition = " inwardentries.isDelete=0 OR inwardentries.isDelete IS NULL";
        $like = array("branchmaster.branchName" => $search . "~both");
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

                $data[] = array(
                    $srno,
                    date('d/m/Y', strtotime($row->inwardDate)),
                    $row->branchName,
                    $row->supplierName,
                    $row->total,
                    $status
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

    public function getCategoryWiseStock()
    {
        $dbName = $this->session->userdata['current_db' . $this->session_key];
        $this->db->query('use ' . $dbName);
        $data = [];
        $this->db->select("categorymaster.categoryName,SUM(stock) as totalStock");
        $this->db->from("stockinfo");
        $this->db->join("itemmaster", "itemmaster.code = stockinfo.itemCode");
        $this->db->join("categorymaster", "categorymaster.code = itemmaster.categoryCode");
        $this->db->group_by("categorymaster.code");
        $query = $this->db->get();

        if ($query != false) {
            foreach ($query->result_array() as $rec) {
                $data['label'][] = $rec['categoryName'];
                $data['data'][] = $rec['totalStock'];
                $data['color'][] = "#" . substr(md5(rand()), 0, 6);
            }
        }

        $result_array = [
            'data'  => $data
        ];
        echo json_encode($result_array);
        exit();
    }

    public function getalertstock()
    {
        $itemImage = base_url("assets/food.png");
        $select = "IFNULL(SUM(stock),0) as totalStock,itemmaster.itemEngName";
        $table = "stockinfo";
        $condition = ["itemmaster.isActive" => 1, "stockinfo.stock<=" => '20'];
        if ($this->branchCode != "") {
            $condition["stockinfo.branchCode"] = $this->branchCode;
        }
        $join = ["itemmaster" => "itemmaster.code = stockinfo.itemCode"];
        $joinType = ["itemmaster" => "inner"];
        $like = [];
        $limit = 20;
        $offset = 0;
        $groupBy = ["itemmaster.code"];
        $query = $this->GlobalModel->selectQuery($select, $table, $condition, [], $join, $joinType, $like, $limit, $offset, $groupBy);
        $data = [];
        if ($query) {
            foreach ($query->result() as $rec) {
                $ar = ["itemName" => $rec->itemEngName, "stock" => $rec->totalStock, "itemImage" => $itemImage];
                $data[] = $ar;
            }
            $res = [
                'status' => 200,
                'data'  => $data
            ];
        } else {
            $res = ['status' => 300];
        }
        echo json_encode($res);
        exit();
    }
}
