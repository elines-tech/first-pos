<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    var $session_key;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('GlobalModel');
        $this->session_key = $this->session->userdata('cash_key' . CASH_SESS_KEY);
        if (!isset($this->session->userdata['cash_logged_in' . $this->session_key]['code'])) {
            redirect('Cashier/Login', 'refresh');
        }
		$res = $this->GlobalModel->checkActiveSubscription();
        if ($res == "EXPIRED") {
			$this->load->view('errors/exppackage.php');
        }
    }

    public function index()
    {
        $this->load->view('cashier/header'); 
        $this->load->view('cashier/dashboard/index');
        $this->load->view('cashier/footer');
    }
    public function getOrderCounts()
    {
		$cashierCode = $this->session->userdata['cash_logged_in' . $this->session_key]['code'];
        $conOrder['ordermaster.addID'] = $cashierCode;
        $orderQuery = $this->GlobalModel->selectQuery("ifnull(count(id),0) as orderCount", "ordermaster", $conOrder);
        if ($orderQuery) {
            $totalOrders =  $orderQuery->result()[0]->orderCount;
        }
		
		$extraCond = " (ordermaster.orderDate between '".date("Y-m-d")." 00:00:01' and '".date("Y-m-d")." 23:59:59')";
        $torderQuery = $this->GlobalModel->selectQuery("ifnull(count(id),0) as torderCount", "ordermaster", $conOrder, array(), array(), array(), array(), '', '', array(), $extraCond);
        if ($torderQuery) {
            $todaysOrders =  $torderQuery->result()[0]->torderCount;
        }

        $res['totalOrders'] = $totalOrders;
        $res['todaysOrders'] = $todaysOrders;
        echo json_encode($res);
    }
}
