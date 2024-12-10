<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Salla\ZATCA\GenerateQrCode;
use Salla\ZATCA\Tags\InvoiceDate;
use Salla\ZATCA\Tags\InvoiceTaxAmount;
use Salla\ZATCA\Tags\InvoiceTotalAmount;
use Salla\ZATCA\Tags\Seller;
use Salla\ZATCA\Tags\TaxNumber;


class Package extends CI_Controller
{
    var $session_key;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('GlobalModel');
        $this->load->model('AdminModel');
        $this->load->library("sendemail");
        $this->session_key = $this->session->userdata('key' . SESS_KEY);
        if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
            redirect('Login', 'refresh');
        }
    }

    public function index()
    {
        $data['subscription'] = $this->AdminModel->selectQuery("*", "subscriptionmaster", ["code" => "PAK_2"], ["id" => "ASC"]);
        $client = $this->GlobalModel->getLoginClientCode();
        if (!empty($client)) {
            $clientCode = $client['companyCode'];
            $data['lastplan'] = $this->AdminModel->lastPackage($clientCode);
            $data['clientCode'] = $clientCode;
            $this->load->view('dashboard/package/list', $data);
        }
    }

    public function configure()
    {
        $planId = $this->input->post('packageCode');
        $data = [];
        $data = [
            "planId" => "PAK_2",
            "duration" => $this->input->post('duration'),
            "category" => "supermarket",
            "planPrice" => $this->input->post('finalPrice'),
        ];
        $data['subscription'] = $this->AdminModel->selectQuery("*", "subscriptionmaster", ["code" => "PAK_2"], ["id" => "ASC"]);
        $this->load->view("dashboard/package/configure", $data);
    }

    public function buy()
    {
        $duration = $this->input->post('duration');
        $defaultUsers = $this->input->post('defaultUsers');
        $defaultBranches = $this->input->post('defaultBranches');
        $perUserPrice = $this->input->post('perUserPrice');
        $addonUsers = $this->input->post('addonUsers');
        $addonUserPrice = $this->input->post('addonUserPrice');
        $perBranchPrice = $this->input->post('perBranchPrice');
        $addonBranches = $this->input->post('addonBranches');
        $addonBranchPrice = $this->input->post('addonBranchPrice');
        $planPrice = $this->input->post('planPrice');
        $finalPrice = $this->input->post('finalPrice');

        $subtotal = $this->input->post("subtotal");
        $taxtotal = $this->input->post("taxtotal");
        $taxpercent = $this->input->post("taxpercent");
        $discount = $this->input->post("discount");
        $basicCharge = $this->input->post("basicCharge");
        $planconfig = base64_decode($this->input->post("planconfig"));

        $ip = $_SERVER['REMOTE_ADDR'];
        $today = date('Y-m-d H:i:s');
        if ($duration == "year") {
            $expiry = date('Y-m-d 23:59:59', strtotime("+ 365 days"));
        } else {
            $expiry = date('Y-m-d 23:59:59', strtotime("+ 30 days"));
        }
        $cmpcode = $this->GlobalModel->getCompcode();
        if ($cmpcode) {
            $client = $this->GlobalModel->getLoginClientCode();
            if (!empty($client)) {
                $clientCode = $client['companyCode'];
                $cno = explode("_", $clientCode);
                $receiptNo = "KS" . $cno[1] . "RES" . date('YmdHis');
                $paymentData = [
                    "subscriptionCode" => "PAK_2",
                    "clientCode" => $clientCode,
                    "amount" => $finalPrice,
                    "paymentDate" => $today,
                    "startDate" => $today,
                    "expiryDate" => $expiry,
                    "status" => "-",
                    "isActive" => '1',
                    "isDelete" => '0',
                    "addID" => $clientCode,
                    "addIP" => $ip,
                    "addDate" => date('Y-m-d H:i:s'),
                    "defaultUsers" => $defaultUsers,
                    "defaultBranches" => $defaultBranches,
                    "addonUsers" => $addonUsers,
                    "addonBranches" => $addonBranches,
                    "isFreeTrial" => '0',
                    "type" => "subscription",
                    "paymentStatus" => "pending",
                    "category" => "restaurant",
                    "period" => $duration,
                    "perUserPrice" => $perUserPrice,
                    "perBranchPrice" => $perBranchPrice,
                    "addonUserPrice" => $addonUserPrice,
                    "addonBranchPrice" =>  $addonBranchPrice,
                    "subtotal" => $subtotal,
                    "taxtotal" => $taxtotal,
                    "taxpercent" => $taxpercent,
                    "discount" => $discount,
                    "basicCharge" => $basicCharge,
                    "planconfig" => $planconfig
                ];
                $resultCode = $this->AdminModel->addNew($paymentData, "payments", "PMT");
                if ($resultCode != 'false') {
                    $apiURL = FATOORAH_URL;
                    $apiKey = FATOORAH_KEY;
                    $data['clientId'] = $clientCode;
                    $data['finalPrice'] = $finalPrice;
                    $data['paymentCode'] = $resultCode;
                    $ipPostFields = ['InvoiceAmount' => $finalPrice, 'CurrencyIso' => PAY_GATEWAY_CURRENCY];
                    $data['paymentMethods'] = $this->initiatePayment($apiURL, $apiKey, $ipPostFields);
                    $this->load->view("dashboard/package/payment", $data);
                } else {
                    $this->session->set_flashdata("msg", "Your transaction is successful but failed to activate the subscription. Please contact the administrator for further assistance");
                    redirect("package/nonprocess/$receiptNo");
                }
            } else {
                $this->session->set_flashdata("msg", "Server failed to process and find your data. Please try again after sometime.");
                $data = [
                    "planId" => "PAK_2",
                    "duration" => $duration,
                    "category" => "restaurant",
                    "planPrice" => $planPrice,
                ];
                $data['subscription'] = $this->AdminModel->selectQuery("*", "subscriptionmaster", ["code" => "PAK_2"], ["id" => "ASC"]);
                $this->load->view("dashboard/package/configure", $data);
            }
        } else {
            $this->session->set_flashdata("msg", "Something went wrong while processsing. Please trya again later.");
            $data = [
                "planId" => "PAK_2",
                "duration" => $duration,
                "category" => "restaurant",
                "planPrice" => $planPrice,
            ];
            $data['subscription'] = $this->AdminModel->selectQuery("*", "subscriptionmaster", ["code" => "PAK_2"], ["id" => "ASC"]);
            $this->load->view("dashboard/package/configure", $data);
        }
    }

    public function renew()
    {
        $duration = $this->input->post("duration");
        $clientCode = $this->input->post("clientCode");
        $defaultUsers = $this->input->post("defaultUsers");
        $defaultBranches = $this->input->post("defaultBranches");
        $addonUsers = $this->input->post("addonUsers");
        $addonBranches = $this->input->post("addonBranches");
        $addonBranchPrice = $this->input->post("addonBranchPrice");
        $addonUserPrice = $this->input->post("addonUserPrice");
        $perUserPrice = $this->input->post("perUserPrice");
        $perBranchPrice = $this->input->post("perBranchPrice");
        $totalPayable = $this->input->post("totalPayable");

        $subtotal = $this->input->post("subtotal");
        $taxtotal = $this->input->post("taxtotal");
        $taxpercent = $this->input->post("taxpercent");
        $discount = $this->input->post("discount");
        $basicCharge = $this->input->post("basicCharge");
        $planconfig = base64_decode($this->input->post("planconfig"));

        $ip = $_SERVER['REMOTE_ADDR'];
        $today = date('Y-m-d H:i:s');
        if ($duration == "year") {
            $expiry = date('Y-m-d 23:59:59', strtotime("+ 365 days"));
        } else {
            $expiry = date('Y-m-d 23:59:59', strtotime("+ 30 days"));
        }
        $cno = explode("_", $clientCode);
        $receiptNo = "KS" . $cno[1] . "RES" . date('YmdHis');
        $paymentData = [
            "subscriptionCode" => "PAK_2",
            "clientCode" => $clientCode,
            "amount" => $totalPayable,
            "paymentDate" => $today,
            "startDate" => $today,
            "expiryDate" => $expiry,
            "status" => "-",
            "isActive" => '1',
            "isDelete" => '0',
            "addID" => $clientCode,
            "addIP" => $ip,
            "addDate" => date('Y-m-d H:i:s'),
            "defaultUsers" => $defaultUsers,
            "defaultBranches" => $defaultBranches,
            "addonUsers" => $addonUsers,
            "addonBranches" => $addonBranches,
            "isFreeTrial" => '0',
            "paymentStatus" => "pending",
            "type" => "subscription",
            "category" => "restaurant",
            "period" => $duration,
            "perUserPrice" => $perUserPrice,
            "perBranchPrice" => $perBranchPrice,
            "addonUserPrice" => $addonUserPrice,
            "addonBranchPrice" =>  $addonBranchPrice,
            "subtotal" => $subtotal,
            "taxtotal" => $taxtotal,
            "taxpercent" => $taxpercent,
            "discount" => $discount,
            "basicCharge" => $basicCharge,
            "planconfig" => $planconfig
        ];
        $resultCode = $this->AdminModel->addNew($paymentData, "payments", "PMT");
        if ($resultCode != 'false') {
            $apiURL = FATOORAH_URL;
            $apiKey = FATOORAH_KEY;
            $data['clientId'] = $clientCode;
            $data['finalPrice'] = $totalPayable;
            $data['paymentCode'] = $resultCode;
            $ipPostFields = ['InvoiceAmount' => $totalPayable, 'CurrencyIso' => PAY_GATEWAY_CURRENCY];
            $data['paymentMethods'] = $this->initiatePayment($apiURL, $apiKey, $ipPostFields);
            $this->load->view("dashboard/package/payment", $data);
        } else {
            $this->session->set_flashdata("msg", "Your transaction is successful but failed to activate the subscription. Please contact the administrator for further assistance");
            redirect("package/nonprocess/$receiptNo");
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
            $text = "Renew " . ucwords($clientData['category']) . " on " . date("Y/m/d h:i A");
            $postFields = [
                "PaymentMethodId"   => $this->input->post("PaymentMethodId"),
                "InvoiceValue"      => $this->input->post("InvoiceAmount"),
                "CallBackUrl"       => base_url("package/callback"),
                "ErrorUrl"          => base_url("package/callback"),
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
                    "status" => "ACTIVE",
                    "paymentStatus" => "SUCCESS",
                    "paymentId" => $keyId,
                    "paymentResponse" => stripslashes(json_encode($json)),
                    "editDate" => date("Y-m-d H:i:s")
                ];
                $where = ["code" => $payment['code']];
                $this->AdminModel->updateQuery("payments", $update, $where);
                $this->AdminModel->expirePreviousPlans($payment['clientCode'], $payment['code']);
                $message = "Transaction $keyId, Your transaction is successful and subscription is processed and activated. Kindly logout and login again for activation.";
                $this->session->set_flashdata("message", json_encode(["status" => true, "message" => $message]));
                $receiptNo = base64_encode($receiptNo);
                // send pdf invoice
                $this->sendInvoice($clientCode, $paymentCode);
                redirect("package/success/$receiptNo", 'refresh');
            } else {
                $message = "Your purchase is successful. Transaction Id $keyId. Please note down and kindly ask administrator for futher process";
                $this->session->set_flashdata("message", json_encode(["status" => true, "message" => $message]));
                $receiptNo = base64_encode($receiptNo);
                redirect("package/failed/$receiptNo", 'refresh');
            }
        } else {
            $receiptNo = $payment['receiptId'];
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
            $receiptNo = base64_encode($receiptNo);
            redirect("package/failed/$receiptNo", 'refresh');
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

    public function success($code)
    {
        $code = base64_decode($code);
        $data['payments'] = $this->AdminModel->selectQuery("*", "payments", ["receiptId" => $code], [], [], [], [], 1, 0);
        $this->load->view("dashboard/package/success", $data);
    }

    public function failed($code)
    {
        $code = base64_decode($code);
        $data['payments'] = $this->AdminModel->selectQuery("*", "payments", ["receiptId" => $code], [], [], [], [], 1, 0);
        $this->load->view("dashboard/package/failed", $data);
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
