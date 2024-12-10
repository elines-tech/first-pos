
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    var $session_key;
	protected $rolecode,$branchCode; 
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
		$this->rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		$this->branchCode = $this->session->userdata['logged_in' . $this->session_key]['userBranch'];
		$this->rights = $this->GlobalModel->getMenuRights('1.1', $this->rolecode);
		if ($this->rights == '') {
			$this->load->view('errors/norights.php');
		}
    }

    public function listRecords()
    {
		$branchCode="";
		if($this->branchCode!=""){
			$branchCode=$this->branchCode;
		}
        $conSuppliers['suppliermaster.isActive'] = 1;
        $totalSuppliers = $this->GlobalModel->selectQuery("count(distinct suppliermaster.code) as cnt", "suppliermaster", $conSuppliers, array(), array(), array(), array(), '', '', array(), "");
        if ($totalSuppliers) {
            $countSupplier =  $totalSuppliers->result()[0]->cnt;
        }

        $conPro['productmaster.isActive'] = 1;
        $totalPro = $this->GlobalModel->selectQuery("count(distinct productmaster.code) as cnt", "productmaster", $conPro, array(), array(), array(), array(), '', '', array(), "");
        if ($totalPro) {
            $countPro =  $totalPro->result()[0]->cnt;
        }

        $conBrand['brandmaster.isActive'] = 1;
        $totalBrand = $this->GlobalModel->selectQuery("count(distinct brandmaster.code) as cnt", "brandmaster", $conBrand, array(), array(), array(), array(), '', '', array(), "");
        if ($totalBrand) {
            $countBrand =  $totalBrand->result()[0]->cnt;
        }

        $conCategory['categorymaster.isActive'] = 1;
        $totalCategory = $this->GlobalModel->selectQuery("count(distinct categorymaster.code) as cnt", "categorymaster", $conCategory, array(), array(), array(), array(), '', '', array(), "");
        if ($totalCategory) {
            $countCategory =  $totalCategory->result()[0]->cnt;
        }
        
		$condition["ordermaster.branchCode"]=$branchCode;
        $totalOrders = $this->GlobalModel->selectQuery("count(distinct ordermaster.code) as cnt", "ordermaster", $condition, array(), array(), array(), array(), '', '', array(), "");
        if ($totalOrders) {
            $countOrders =  $totalOrders->result()[0]->cnt;
        }

        $res['countSupplier'] = $countSupplier;
        $res['countProduct'] = $countPro;
        $res['countBrand'] = $countBrand;
        $res['countCategory'] = $countCategory;
        $res['countOrders'] = $countOrders;
        $this->load->view('dashboard/header');

        $this->load->view('dashboard/dashboard/index', $res);
        $this->load->view('dashboard/footer');
    }

    public function getOrderCounts()
    {
        $conPurchase['inwardentries.isActive'] = 1;
        $totalPurchase = $this->GlobalModel->selectQuery("sum(inwardentries.total) as total", "inwardentries", $conPurchase, array(), array(), array(), array(), '', '', array(), "");
        if ($totalPurchase) {
            $tPurchase =  $totalPurchase->result()[0]->total;
        }
        $res['countItem'] = 0;
        $res['countBranch'] = 0;
        $res['countUser'] = 0;
        $res['totalPurchase'] = $tPurchase;
        echo json_encode($res);
    }

    public function listRecordsSales()
    {
        $this->load->view('dashboard/header');

        $this->load->view('dashboard/dashboard/sales');
        $this->load->view('dashboard/footer');
    }

    public function getSalesCount()
    {
        //$conOrder['ordermaster.isActive'] = 1;
        $extraConOrder = "";
		$branchCode="";
		if($this->branchCode!=""){
			$branchCode=$this->branchCode;
		} 
		$condition['ordermaster.branchCode']=$branchCode;
        $totalItemsOrder = $this->GlobalModel->selectQuery("count(distinct ordermaster.code) as cnt", "ordermaster", $condition, array(), array(), array(), array(), '', '', array(), $extraConOrder);
        if ($totalItemsOrder) {
            $countItemsOrder =  $totalItemsOrder->result()[0]->cnt;
        }
        $totalSaleOrder = $this->GlobalModel->selectQuery("sum(ordermaster.subTotal) as total", "ordermaster", $condition, array(), array(), array(), array(), '', '', array(), "");
        if ($totalSaleOrder) { 
            $totalSale =  $totalSaleOrder->result()[0]->total;
        }

        $totalOrderDiscount = $this->GlobalModel->selectQuery("sum(ordermaster.discountTotal) as total", "ordermaster", $condition, array(), array(), array(), array(), '', '', array(), "");
        if ($totalOrderDiscount) {
            $totalDiscount =  $totalOrderDiscount->result()[0]->total;
        }

        $totalOrderTax = $this->GlobalModel->selectQuery("sum(ordermaster.totalTax) as total", "ordermaster", $condition, array(), array(), array(), array(), '', '', array(), "");
        if ($totalOrderTax) {
            $totalTax =  $totalOrderTax->result()[0]->total;
        }

        //$customer['ordermaster.orderStatus'] = "CON";
        $totalCustomerBase = $this->GlobalModel->selectQuery("count(distinct ordermaster.code) as cnt", "ordermaster", $condition, array(), array(), array(), array(), '', '', array(), "");
        if ($totalCustomerBase) {
            $countCustomer =   $totalCustomerBase->result()[0]->cnt;
        }

        $res['countOrders'] = $countItemsOrder;
        $res['totalSale'] = $totalSale; 
        $res['totalDiscount'] = $totalDiscount;
        $res['totalTax'] = $totalTax;
        $res['totalCustomer'] = $countCustomer;

        echo json_encode($res);
    }

    public function getInwardList()
    {
        $tableName = "inwardentries";
        $search = $this->input->GET("search")['value'];
        $orderColumns = array("inwardentries.code,inwardentries.branchCode,branchmaster.branchName,suppliermaster.supplierName,inwardentries.inwardDate,inwardentries.total, inwardentries.isActive");
        $condition = array();
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
}
