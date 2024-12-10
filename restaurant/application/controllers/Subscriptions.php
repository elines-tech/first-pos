<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Salla\ZATCA\GenerateQrCode;
use Salla\ZATCA\Tags\InvoiceDate;
use Salla\ZATCA\Tags\InvoiceTaxAmount;
use Salla\ZATCA\Tags\InvoiceTotalAmount;
use Salla\ZATCA\Tags\Seller;
use Salla\ZATCA\Tags\TaxNumber;

class Subscriptions extends CI_Controller
{
    var $session_key;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('GlobalModel');
        $this->load->model('AdminModel');
        $this->session_key = $this->session->userdata('key' . SESS_KEY);
        if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
            redirect('Login', 'refresh');
        }
        $res = $this->GlobalModel->checkActiveSubscription();
        if ($res == "EXPIRED") {
            redirect('package', 'refresh');
        }
    }

    public function listRecords()
    {
        $client = $this->GlobalModel->getLoginClientCode();
        if (!empty($client)) {
            $clientCode = $client['companyCode'];
            $data['payments'] = $this->AdminModel->selectQuery("payments.receiptId,payments.paymentId", "payments", ["payments.clientCode" => $clientCode], ["payments.id" => "DESC"]);
            $this->load->view('dashboard/header');
            $this->load->view('dashboard/subscription/list', $data);
            $this->load->view('dashboard/footer');
        }
    }

    public function getList()
    {
        $dataCount = 0;
        $data = array();
        $client = $this->GlobalModel->getLoginClientCode();
        if (!empty($client)) {
            $clientCode = $client['companyCode'];
            $fromDate = $this->input->get("fromDate");
            $toDate = $this->input->get("toDate");
            $receiptId = $this->input->get("receiptId");
            $paymentId = $this->input->get("paymentId");

            $tableName = "payments";
            $search = $this->input->GET("search")['value'];
            $orderColumns = array("payments.*");
            $condition = array("clientCode" => $clientCode);
            if ($receiptId != "") {
                $condition['receiptId'] = $receiptId;
            }
            if ($paymentId != "") {
                $condition['paymentId'] = $paymentId;
            }
            $orderBy = array('payments.id' => 'DESC');
            $joinType = $join = [];
            $groupByColumn = array();
            $limit = $this->input->GET("length");
            $offset = $this->input->GET("start");
            $extraCondition = "";
            if ($fromDate != "" && $toDate != "") $extraCondition = "paymentDate between '$fromDate 00:00:00' AND '$toDate 23:59:59'";
            $like = array("payments.code" => $search . "~both", "payments.receiptid" => $search . "~both", "payments.paymentId" => $search . "~both", "payments.amount" => $search . "~both");
            $Records = $this->AdminModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
            $srno = $_GET['start'] + 1;
            if ($Records) {
                foreach ($Records->result() as $row) {
                    $actionHtml = '<a href="' . base_url('subscriptions/view/' . $row->code) . '" class="btn btn-success btn-sm cursor_pointer"><i id="view" title="View" class="fa fa-eye"></i></a>';
                    if ($row->type == "subscription" || $row->type == "freetrial") {
                        $servicePeriod = date('d/M/Y', strtotime($row->startDate)) . " - " . date("d/M/Y", strtotime($row->expiryDate));
                        $status = date('Y-m-d H:i:00') > $row->expiryDate ? '<span class="badge bg-danger">Expired</span>' : '<span class="badge bg-success">Active</span>';
                    } else {
                        $servicePeriod = "-";
                        $status = "";
                    }
                    $data[] = array(
                        $srno,
                        $row->receiptId,
                        date('d/m/Y h:i A', strtotime($row->paymentDate)),
                        $row->paymentId,
                        $row->amount,
                        $servicePeriod,
                        ucwords($row->type),
                        $status,
                        $actionHtml
                    );
                    $srno++;
                }
                $dataCount = sizeof($this->AdminModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, array(), '', '', '', $extraCondition)->result());
            }
        }
        $output = array(
            "draw"            =>  intval($_GET["draw"]),
            "recordsTotal"    =>  $dataCount,
            "recordsFiltered" =>  $dataCount,
            "data"            =>  $data
        );
        echo json_encode($output);
    }

    public function view()
    {
        $code = $this->uri->segment("3");
        $data['plan'] = $this->AdminModel->selectQuery("payments.*", "payments", ["payments.code" => $code], []);
        $this->load->view('dashboard/header');
        $this->load->view('dashboard/subscription/view', $data);
        $this->load->view('dashboard/footer');
    }

    public function plan()
    {
        $client = $this->GlobalModel->getLoginClientCode();
        if (!empty($client)) {
            $clientCode = $client['companyCode'];
            $data['plan'] = $this->AdminModel->selectQuery("payments.*", "payments", ["payments.type!=" => "addon", "clientCode" => $clientCode], ["payments.id" => "DESC"], [], [], [], 1, 0);
            if ($data['plan']) {
                $refCode = $data['plan']->result()[0]->code;
                $data['addons'] = $this->AdminModel->selectQuery("payments.*", "payments", ["payments.type" => "addon", "clientCode" => $clientCode, "refCode" => $refCode], ["payments.id" => "DESC"], [], [], [], 1, 0);
            } else {
                $data['addons'] = false;
            }
            $this->load->view('dashboard/header');
            $this->load->view('dashboard/subscription/plan', $data);
            $this->load->view('dashboard/footer');
        }
    }

    public function modify()
    {
        $code = $this->input->post("code");
        $data['plan'] = $this->AdminModel->selectQuery("payments.*", "payments", ["payments.code" => $code, "payments.type!=" => "addon"]);
        $data['subscription'] = $this->AdminModel->selectQuery("*", "subscriptionmaster", ["code" => "PAK_2"], ["id" => "ASC"]);
        $this->load->view('dashboard/header');
        $this->load->view('dashboard/subscription/upgrade', $data);
        $this->load->view('dashboard/footer');
    }

    public function cancelplan()
    {
        $clientCode = $_POST['clientCode'];
        $paymentCode = $_POST['paymentCode'];
        $update = [
            "userCancelled" => '1',
            "isActive" => '0',
            "isDelete" => '1',
            "deleteID" => $clientCode,
            "deleteIP" => $_SERVER['REMOTE_ADDR'],
            "deleteDate" => date("Y-m-d H:i:s"),
            "status" => "EXPIRED"
        ];
        $where = ["code" => $paymentCode, "clientCode" => $clientCode];
        $result = $this->AdminModel->updateQuery("payments", $update, $where);

        $where = ["refCode" => $paymentCode, "clientCode" => $clientCode];
        $result2 = $this->AdminModel->updateQuery("payments", $update, $where);

        if ($result == true || $result2 == true) {
            $res['status'] = true;
            $res['msg'] = "Your subscription has been cancelled successfully. Now you will be able to access the panel. Please wait...";
        } else {
            $res['status'] = false;
            $res['msg'] = "Failed to process your request. Please try again later";
        }
        echo json_encode($res);
        exit;
    }

    public function upgrade()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $code = $this->input->post('code');
        $subscriptionCode = $this->input->post('subscriptionCode');
        $mainPaymentCode = $this->input->post('mainPaymentCode');
        $clientCode = $this->input->post('clientCode');
        $amount = $this->input->post('amount');
        $addonUsers = $this->input->post('addonUsers');
        $addonBranches = $this->input->post('addonBranches');
        $perUserPrice = $this->input->post('perUserPrice');
        $addaddonUserPrice = $this->input->post('addaddonUserPrice');
        $perBranchPrice = $this->input->post('perBranchPrice');
        $addonBranchPrice = $this->input->post('addonBranchPrice');
        $period = $this->input->post('period');

        $subtotal = $this->input->post("subtotal");
        $taxtotal = $this->input->post("taxtotal");
        $taxpercent = $this->input->post("taxpercent");
        $discount = $this->input->post("discount");
        $basicCharge = $this->input->post("basicCharge");
        $planconfig = base64_decode($this->input->post("planconfig"));

        //$this->form_validation->set_rules('addonUsers', 'Extra Users', 'greater_than[0]');
        //$this->form_validation->set_rules('addonBranches', 'Extra Branches', 'greater_than[0]');
        $this->form_validation->set_rules('amount', 'Payable Amount', 'greater_than[0]');
        if ($this->form_validation->run() == FALSE) {
            $data['plan'] = $this->AdminModel->selectQuery("payments.*", "payments", ["payments.code" => $code, "payments.type!=" => "addon"]);
            $data['subscription'] = $this->AdminModel->selectQuery("*", "subscriptionmaster", ["code" => "PAK_2"], ["id" => "ASC"]);
            $this->load->view('dashboard/header');
            $this->load->view('dashboard/sidebar');
            $this->load->view('dashboard/subscription/upgrade', $data);
            $this->load->view('dashboard/footer');
        } else {
            $today = date("Y-m-d H:i:s");
            $subscription =  [
                "clientCode" => $clientCode,
                "amount" => $amount,
                "paymentDate" => $today,
                "status" => "ACTIVE",
                "isActive" => 1,
                "addID" => $clientCode,
                "addIP" => $ip,
                "addDate" => $today,
                "defaultUsers" => 0,
                "defaultBranches" => 0,
                "addonUsers" => $addonUsers,
                "addonBranches" => $addonBranches,
                "isFreeTrial" => '0',
                "paymentStatus" => "pending",
                "category" => "restaurant",
                "refCode" => $mainPaymentCode,
                "period" => $period,
                "addonUserPrice" => $addaddonUserPrice,
                "addonBranchPrice" => $addonBranchPrice,
                "type" => "addon",
                "perBranchPrice" => $perBranchPrice,
                "perUserPrice" => $perUserPrice,
                "subtotal" => $subtotal,
                "taxtotal" => $taxtotal,
                "taxpercent" => $taxpercent,
                "discount" => $discount,
                "basicCharge" => $basicCharge,
                "planconfig" => $planconfig
            ];
            $resultCode = $this->AdminModel->addNew($subscription, 'payments', 'PMT');
            if ($resultCode != 'false') {
                $apiURL = FATOORAH_URL;
                $apiKey = FATOORAH_KEY;
                $data['clientId'] = $clientCode;
                $data['category'] = "supermarket";
                $data['newBranches'] = $addonBranches;
                $data['newUsers'] = $addonUsers;
                $data['finalPrice'] = $amount;
                $data['paymentCode'] = $resultCode;
                $ipPostFields = ['InvoiceAmount' => $amount, 'CurrencyIso' => PAY_GATEWAY_CURRENCY];
                $data['paymentMethods'] = $this->initiatePayment($apiURL, $apiKey, $ipPostFields);
                $this->load->view("dashboard/subscription/paymentlink", $data);
            } else {
                $this->session->set_flashdata("msg", "Opps! Something went wrong. Please try again");
                redirect('subscriptions/listRecords');
            }
        }
    }

    public function generatePaymentLink()
    {
        $apiURL = FATOORAH_URL;
        $apiKey = FATOORAH_KEY;
        $clientId = $this->input->post('clientId');
        $client = $this->AdminModel->selectQuery("clients.*", "clients", ["code" => $clientId]);
        if ($client) {
            $clientData = $client->result_array()[0];
            $phonetext = $clientData['phone'];
            $countryCode = substr($phonetext, 1, 3);
            $phone = substr($phonetext, 4);
            $paymentCode = $this->input->post('paymentCode');
            $text = "Upgrade " . ucwords($clientData['category']) . " on " . date("Y/m/d h:i A");
            $postFields = [
                "PaymentMethodId"   => $this->input->post("PaymentMethodId"),
                "InvoiceValue"      => $this->input->post("InvoiceAmount"),
                "CallBackUrl"       => base_url("subscriptions/callback"),
                "ErrorUrl"          => base_url("subscriptions/callback"),
                "CustomerName"      => $clientData['name'],
                "MobileCountryCode" => $countryCode,
                "CustomerMobile"    => $phone,
                "CustomerEmail"     => $clientData['email'],
                "CustomerReference" => $text
            ];
            $data = $this->executePayment($apiURL, $apiKey, $postFields);
            $invoiceId   = $data->InvoiceId;
            $paymentLink = $data->PaymentURL;
            $receiptNo = "KSRES" . $invoiceId;
            $data = ["receiptId" => $receiptNo, "editID" => $clientId, "editIP" => $_SERVER['REMOTE_ADDR']];
            $this->AdminModel->updateQuery("payments", $data, ["code" => $paymentCode]);
            sleep(1);
            $res["status"] = true;
            $res["invoiceId"] = $invoiceId;
            $res["paymentLink"] = $paymentLink;
        } else {
            $res["status"] = false;
        }
        echo json_encode($res);
        exit;
    }

    public function callback()
    {
        $apiURL = FATOORAH_URL;
        $apiKey = FATOORAH_KEY;
        $keyId   = $_GET['paymentId'];
        $KeyType = 'paymentId';
        $postFields = [
            'Key'     => $keyId,
            'KeyType' => $KeyType
        ];
        $json  = $this->callAPI("$apiURL/v2/getPaymentStatus", $apiKey, $postFields);
        $status = $json->Data->InvoiceStatus;
        $InvoiceId = $json->Data->InvoiceId;
        $payment = $this->AdminModel->get_payment_by_similar_invoice($InvoiceId);
        if ($status == "Paid") {
            if (!empty($payment)) {
                $receiptNo = $payment['receiptId'];
                $paymentCode = $payment['code'];
                $clientCode = $payment['clientCode'];
                $update = [
                    "paymentStatus" => "SUCCESS",
                    "paymentId" => $keyId,
                    "paymentResponse" => stripslashes(json_encode($json)),
                    "editDate" => date("Y-m-d H:i:s")
                ];
                $where = ["code" => $payment['code']];
                $this->AdminModel->updateQuery("payments", $update, $where);
                // send pdf invoice
                $this->sendInvoice($clientCode, $paymentCode);
                $message = "Your purchase is successful. Transaction Id $keyId";
                $this->session->set_flashdata("message", json_encode(["status" => true, "message" => $message]));
                redirect("subscriptions/listRecords", 'refresh');
            } else {
                $message = "Your purchase is successful. Transaction Id $keyId";
                $this->session->set_flashdata("message", json_encode(["status" => true, "message" => $message]));
                redirect("subscriptions/listRecords", 'refresh');
            }
        } else {
            $update = [
                "paymentStatus" => strtoupper(strtolower($status)),
                "paymentId" => $keyId,
                "paymentResponse" => stripslashes(json_encode($json)),
                "editDate" => date("Y-m-d H:i:s")
            ];
            $where = ["code", $payment['code']];
            $this->AdminModel->updateQuery("payments", $update, $where);
            $message = "Your purchase is $status. Transaction Id $keyId";
            $this->session->set_flashdata("message", json_encode(["status" => true, "message" => $message]));
            redirect("subscriptions/listRecords", 'refresh');
        }
    }

    public function sendInvoice($clientCode, $paymentCode)
    {
        $company = $this->AdminModel->customSelect("companydetails.*", "companydetails", ["companydetails.id" => 1], 0);
        $data['company'] = $company;
        $data['client'] =  $this->AdminModel->customSelect("clients.*", "clients", ["clients.code" => $clientCode], 0);
        $payment =  $this->AdminModel->selectQuery("payments.*", "payments", ["payments.code" => $paymentCode]);
        if ($payment) {
            $clientemail = $data['client']['email'];
            $payment = $payment->result_array()[0];
            $invoiceDate = $payment['addDate'];
            $data['payment'] = $payment;
            $date = date('Y-m-dTH:i:sZ', strtotime($invoiceDate));
            $data['base64QrImg'] = GenerateQrCode::fromArray([
                new Seller($company['cmpname']),
                new TaxNumber($company['cmpvatno']),
                new InvoiceDate($date),
                new InvoiceTotalAmount($payment['amount']),
                new InvoiceTaxAmount($payment['taxtotal'])
            ])->render();
            if (!file_exists("upload/invoice")) mkdir("upload/invoice", 0755, false);
            $path = "upload/invoice/" . $payment['code'] . ".pdf";
            if (file_exists($path)) unlink($path);
            $html = $this->load->view("print/invoicebill", $data, true);
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'orientation' => 'P',
                'default_font_size' => 11,
                'default_font' => 'xbriyaz',
                'margin_header' => 0,
                'margin_footer' => 0,
            ]);
            $mpdf->list_indent_first_level = 0;
            $mpdf->WriteHTML($html);
            $mpdf->Output($path,  \Mpdf\Output\Destination::FILE);
            sleep(1);
            $attanchment = [FCPATH . $path];
            $this->sendemail->sendMailWithAttachment($clientemail, "Subscription Invoice", "Your payment was successful and subscription plan was activated.", $attanchment);
        }
    }

    function initiatePayment($apiURL, $apiKey, $postFields)
    {
        $json = $this->callAPI("$apiURL/v2/InitiatePayment", $apiKey, $postFields);
        return $json->Data->PaymentMethods;
    }

    function executePayment($apiURL, $apiKey, $postFields)
    {
        $json = $this->callAPI("$apiURL/v2/ExecutePayment", $apiKey, $postFields);
        return $json->Data;
    }

    function callAPI($endpointURL, $apiKey, $postFields = [], $requestType = 'POST')
    {
        $curl = curl_init($endpointURL);
        curl_setopt_array($curl, array(
            CURLOPT_CUSTOMREQUEST  => $requestType,
            CURLOPT_POSTFIELDS     => json_encode($postFields),
            CURLOPT_HTTPHEADER     => array("Authorization: Bearer $apiKey", 'Content-Type: application/json'),
            CURLOPT_RETURNTRANSFER => true,
        ));
        $response = curl_exec($curl);
        $curlErr  = curl_error($curl);
        curl_close($curl);
        if ($curlErr) {
            die("Curl Error: $curlErr");
        }
        $error = $this->handleError($response);
        if ($error) {
            die("Error: $error");
        }
        return json_decode($response);
    }

    function handleError($response)
    {
        $json = json_decode($response);
        if (isset($json->IsSuccess) && $json->IsSuccess == true) {
            return null;
        }
        //Check for the errors
        if (isset($json->ValidationErrors) || isset($json->FieldsErrors)) {
            $errorsObj = isset($json->ValidationErrors) ? $json->ValidationErrors : $json->FieldsErrors;
            $blogDatas = array_column($errorsObj, 'Error', 'Name');

            $error = implode(', ', array_map(function ($k, $v) {
                return "$k: $v";
            }, array_keys($blogDatas), array_values($blogDatas)));
        } else if (isset($json->Data->ErrorMessage)) {
            $error = $json->Data->ErrorMessage;
        }
        if (empty($error)) {
            $error = (isset($json->Message)) ? $json->Message : (!empty($response) ? $response : 'API key or API URL is not correct');
        }
        return $error;
    }
}
