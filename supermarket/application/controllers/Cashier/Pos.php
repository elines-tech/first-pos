<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Salla\ZATCA\GenerateQrCode;
use Salla\ZATCA\Tags\InvoiceDate;
use Salla\ZATCA\Tags\InvoiceTaxAmount;
use Salla\ZATCA\Tags\InvoiceTotalAmount;
use Salla\ZATCA\Tags\Seller;
use Salla\ZATCA\Tags\TaxNumber;

class Pos extends CI_Controller
{
    private $session_key;
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form', 'url', 'html');
        $this->load->model('AdminModel');
        $this->load->model('GlobalModel');
        $this->load->model("PosorderModel");
        $this->load->library('form_validation');
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
        $userCode = $this->session->userdata['cash_logged_in' . $this->session_key]['code'];
        $data['draftOrders'] = $this->PosorderModel->fetch_draft_orders($userCode);
        $this->load->view('cashier/pos/order', $data);
    }

    public function print($txnId)
    {
        $userCode = $this->session->userdata['cash_logged_in' . $this->session_key]['code'];
        $user = $this->GlobalModel->selectQuery("usermaster.*", "usermaster", ["code" => $userCode]);
        if ($user) {
            $user =  $user->result()[0];
            $userrole = $user->userRole ?? "R_1";
            $userlang = strtolower($user->userLang) ?? "english";
            $orderdata = $this->GlobalModel->selectQuery("ordermaster.*", "ordermaster", ["txnId" => $txnId]);
            if ($orderdata) {
                $orderItems = [];
                $order = $orderdata->result_array()[0];
                $orderBy = ["orderlineentries.id" => "ASC"];
                $orderLine = $this->GlobalModel->selectQuery("orderlineentries.*", "orderlineentries", ["orderlineentries.orderCode" => $order['code']], $orderBy);
                if ($orderLine) {
                    $orderItems = $orderLine->result_array();

                    $cnd = $ord = $join = $joinType = $like = [];
                    $limit = 1;
                    $company = $this->GlobalModel->selectQuery("companymaster.*", "companymaster", $cnd, $ord, $join, $joinType, $like, $limit, 0);
                    if ($company) {
                        $company = $company->result_array()[0];

                        $data['base64QrImg'] = GenerateQrCode::fromArray([
                            new Seller($company['companyname']),
                            new TaxNumber($company['vatno']),
                            new InvoiceDate($order['orderDate']),
                            new InvoiceTotalAmount($order['totalPayable']),
                            new InvoiceTaxAmount($order['totalTax'])
                        ])->render();

                        $order['orderItems'] = $orderItems;
                        $data['order'] = $order;
                        $data['company'] = $company;
                        $data['userrole'] = $userrole;
                        $data['userlang'] = $userlang;
                        $data['branch'] = $this->GlobalModel->get_row_array(["code" => $order['branchCode']], "branchmaster");
                        $this->load->view('print/autocutbill', $data);
                    } else {
                        $this->session->set_flashdata("message", json_encode(["status" => false, "message" => "Something went wrong. Cannot print the bill right now."]));
                        redirect("Cashier/Pos/", 'refresh');
                    }
                } else {
                    $this->session->set_flashdata("message", json_encode(["status" => false, "message" => "Something went wrong. Cannot print the bill right now."]));
                    redirect("Cashier/Pos/", 'refresh');
                }
            } else {
                $this->session->set_flashdata("message", json_encode(["status" => false, "message" => "Something went wrong. Cannot print the bill right now."]));
                redirect("Cashier/Pos/", 'refresh');
            }
        } else {
            $this->session->set_flashdata("message", json_encode(["status" => false, "message" => "Something went wrong. Cannot print the bill right now."]));
            redirect("Cashier/Pos/", 'refresh');
        }
    }
}
