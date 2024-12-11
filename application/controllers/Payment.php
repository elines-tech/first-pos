<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Salla\ZATCA\GenerateQrCode;
use Salla\ZATCA\Tags\InvoiceDate;
use Salla\ZATCA\Tags\InvoiceTaxAmount;
use Salla\ZATCA\Tags\InvoiceTotalAmount;
use Salla\ZATCA\Tags\Seller;
use Salla\ZATCA\Tags\TaxNumber;

class Payment extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->library("sendemail");
  }

  function generateCompanyCode()
  {
    $cmpcode = $this->getRandomString(5);
    $res = $this->db->where('cmpcode', $cmpcode)->limit(1)->get("clients");
    if ($res->num_rows() > 0) {
      $this->generateCompanyCode();
    }
    return $cmpcode;
  }

  function getRandomString($n)
  {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $n; $i++) {
      $index = rand(0, strlen($characters) - 1);
      $randomString .= $characters[$index];
    }
    return date('y') . strtoupper($randomString);
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

  public function index()
  {
    $data['companyCode'] = $this->session->userdata("regcompanyCode");
    $tempData = $this->db->select("*")->from("temp_clients")->where("code", $data['companyCode'])->get()->row_array();
    if (!empty($tempData)) {
      $data['tempClient'] = $tempData;
      $plandata = json_decode($tempData['plandetails'], true);

      $apiURL = FATOORAH_URL;
      $apiKey = FATOORAH_KEY;
      $category = $tempData['category'];

      $ipPostFields = ['InvoiceAmount' => $plandata['finalPrice'], 'CurrencyIso' => 'KWD'];

      $data['paymentMethods'] = $this->initiatePayment($apiURL, $apiKey, $ipPostFields);

      $this->frontend_template("payment/checkout", $data, "Checkout");
    }
  }

  public function generatePaymentLink()
  {
    $apiURL = FATOORAH_URL;
    $apiKey = FATOORAH_KEY;
    $clientId = $this->input->post('clientId');
    $category = $this->input->post('category');
    $tempClient = $this->db->select("temp_clients.*")->where("id", $clientId)->get("temp_clients")->row_array();
    if (!empty($tempClient)) {
      $clientData = $tempClient;
      $phonetext = $clientData['phone'];
      $countryCode = substr($phonetext, 0, 4);
      $phone = substr($phonetext, 4);
      $text = "Buy Plan " . ucwords($clientData['category']) . " on " . date("Y/m/d h:i A");
      $postFields = [
        'PaymentMethodId'   => $this->input->post("PaymentMethodId"),
        'InvoiceValue'      => $this->input->post("InvoiceAmount"),
        'CallBackUrl'       => base_url("payment/checkout"),
        'ErrorUrl'          => base_url("payment/checkout"),
        "CustomerName"      => $clientData['name'],
        "MobileCountryCode" => $countryCode,
        "CustomerMobile"    => $phone,
        "CustomerEmail"     => $clientData['email'],
        "CustomerReference" => $text
      ];
      //Call endpoint
      $data = $this->executePayment($apiURL, $apiKey, $postFields);
      //You can save payment data in database as per your needs
      $invoiceId   = $data->InvoiceId;
      $paymentLink = $data->PaymentURL;
      if ($category == "supermarket") {
        $receiptNo = "KSSUP" . $invoiceId;
      } else {
        $receiptNo = "KSRES" . $invoiceId;
      }
      $this->db->where("id", $clientId)->update("temp_clients", ["receiptNo" => $receiptNo]);
      $res['status'] = true;
      $res["invoiceId"] = $invoiceId;
      $res["paymentLink"] = $paymentLink;
    } else {
      $res['status'] = false;
    }
    echo json_encode($res);
    exit;
  }

  public function checkout()
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
    $amount = $json->Data->InvoiceTransactions[0]->TransationValue;
    $transactionStatus = strtolower($json->Data->InvoiceTransactions[0]->TransactionStatus);
    $tempClient = $this->db->select("*")->like("receiptNo", $InvoiceId)->get("temp_clients")->row_array();
    if (!empty($tempClient)) {
      $paymentId = $tempClient['paymentId'];
      $paymentStatus = $tempClient['status'];
      if ($status == "Paid" || $transactionStatus == "succss" || $transactionStatus == "success") {
        if ($paymentId == "" && $paymentStatus != "success") {
          $update = [
            "status" => "success",
            "paymentId" => $keyId,
            "paymentResponse" => stripslashes(json_encode($json)),
            "editDate" => date("Y-m-d H:i:s")
          ];
          $this->db->where('id', $tempClient['id'])->update("temp_clients", $update);
        }
        $cmpCode = base64_encode($tempClient['code']);
        redirect("payment/success/$cmpCode");
      } else {
        $update = [
          "status" => strtolower($status),
          "paymentId" => $keyId,
          "paymentResponse" => stripslashes(json_encode($json)),
          "editDate" => date("Y-m-d H:i:s"),
        ];
        $this->db->where('id', $tempClient['id'])->update("temp_clients", $update);
        $cmpCode = base64_encode($tempClient['code']);
        $data = [
          "paymentId" => $keyId,
          "receiptId" => $tempClient['receiptNo'],
          "transactionDate" => $tempClient['registerDate'],
          "transactionStatus" => $transactionStatus,
          "amount" => $amount
        ];
        $this->frontend_template("payment/fail", $data, "Failed");
      }
    } else {
      $data = [
        "paymentId" => $keyId,
        "receiptId" => $InvoiceId,
        "transactionDate" => date("Y-m-d H:i:s"),
        "transactionStatus" => $transactionStatus,
        "amount" => $amount
      ];
      $this->frontend_template("payment/fail", $data, "Failed");
    }
  }

  public function success($companyCode)
  {
    $cmpCode = base64_decode($companyCode);
    $clientResult = $this->GlobalModel->selectQuery("*", "temp_clients", ["code" => $cmpCode]);
    if ($clientResult) {
      $client = $clientResult->result_array()[0];
      $tmpCode = $client['code'];
      $genCmpCode = $this->generateCompanyCode();
      $client["cmpcode"] = $genCmpCode;

      $plandata = json_decode($client['plandetails'], true);
      $planconfig = base64_decode($plandata['planconfig']);
      $paymentId = $client['paymentId'];
      $paymentResponse = $client['paymentResponse'];
      $receiptNo = $client['receiptNo'];
      $startDate = date('Y-m-d H:i:s');
      if ($plandata['duration'] == "month") {
        $expiryDate = date('Y-m-d 23:59:59', strtotime(" + 30 days"));
      } else {
        $expiryDate = date('Y-m-d 23:59:59', strtotime(" + 365 days"));
      }
      //remove unnecessary key-pairs
      unset($client['id']);
      unset($client['plandetails']);
      unset($client['receiptNo']);
      unset($client['paymentId']);
      unset($client['paymentResponse']);
      unset($client['status']);
      $resultCode = $this->GlobalModel->addNew($client, "clients", "CLT");
      if ($resultCode != 'false') {
        $client['code'] = $resultCode;
        $ip = $_SERVER['REMOTE_ADDR'];
        $this->db->query('use ' . MAIN_DBNAME);
        $dbName = strtolower('myvegizc_' . $client['cmpcode']);
        $dbData = array(
          'code' => 'xyz',
          'companyCode' => $client['code'],
          "databaseName" => $dbName,
          'isActive' => 1,
          'addID' => $client['code'],
          'addIP' => $ip
        );
        $this->GlobalModel->addNew($dbData, 'databasemaster', 'DB');
        $query = "https://myvegiz.com:2083/login/?login_only=1&user=myvegizc&pass=Password@0101";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_COOKIESESSION, true);
        curl_setopt($curl, CURLOPT_COOKIEJAR, 'cookiee-name');
        curl_setopt($curl, CURLOPT_COOKIEFILE, '/var/www/ip4.x/filee/tmp');
        curl_setopt($curl, CURLOPT_URL, $query);
        $result = curl_exec($curl);
        $loginTokenResult = json_decode($result);
        $cpsess = $loginTokenResult->security_token;
        $query2 = "https://myvegiz.com:2083$cpsess/json-api/cpanel?cpanel_jsonapi_user=user&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=MysqlFE&cpanel_jsonapi_func=createdb&db=" . $dbName;
        curl_setopt($curl, CURLOPT_URL, $query2);
        $resultcurl = curl_exec($curl);
        $subscription =  [
          "subscriptionCode" => $plandata['packageCode'],
          "clientCode" => $client['code'],
          "basicCharge" => $plandata['basicCharge'],
          "planconfig" => $planconfig,
          "subtotal" => $plandata['subtotal'],
          "taxtotal" => $plandata['taxtotal'],
          "amount" => $plandata['finalPrice'],
          "taxpercent" => $plandata['taxpercent'],
          "discount" => $plandata['discount'],
          "paymentDate" => $startDate,
          "startDate" => $startDate,
          "expiryDate" => $expiryDate,
          "status" => "ACTIVE",
          "isActive" => 1,
          "addID" => $client['code'],
          "addIP" => $client['addIP'],
          "addDate" => $startDate,
          "defaultUsers" => $plandata['defaultUsers'],
          "defaultBranches" => $plandata['defaultBranches'],
          "addonUsers" => $plandata['addonUsers'],
          "addonBranches" => $plandata['addonBranches'],
          "isFreeTrial" => '0',
          "paymentStatus" => "SUCCESS",
          "category" => $client['category'],
          "period" => $plandata['duration'],
          "addonUserPrice" => $plandata['addaddonUserPrice'],
          "addonBranchPrice" => $plandata['addonBranchPrice'],
          "type" => "subscription",
          "perBranchPrice" => $plandata['perBranchPrice'],
          "perUserPrice" => $plandata['perUserPrice'],
          "receiptId" => $receiptNo,
          "paymentId" => $paymentId,
          "paymentResponse" => $paymentResponse,
        ];
        $mainPaymentCode = $this->GlobalModel->addNew($subscription, 'payments', 'PMT');
        if ($mainPaymentCode != "false") {
          $this->db->where('code', $tmpCode)->delete('temp_clients');
          if ($client['category'] == "supermarket") {
            $this->maskSupermarket($dbName, $client, $mainPaymentCode);
          } else {
            $this->maskRestaurant($dbName, $client, $mainPaymentCode);
          }
          $this->session->unset_userdata('regcompanyCode');
          $res['receiptId'] = $receiptNo;
          $res['date'] = $startDate;
          $res['err'] = 200;
          $res['msg'] = "Setup complete successfully. Your application is ready to use. Please check your mail for further instructions";
          $this->frontend_template("payment/success", $res, "Successful");
        } else {
          $this->session->unset_userdata('regcompanyCode');
          $res['receiptId'] = $receiptNo;
          $res['date'] = $startDate;
          $res['err'] = 300;
          $res['msg'] = "Failed to install and setup your application. Please click on try again button to initiate setup again";
          $this->frontend_template("payment/noprocess", $res, "Failed!");
        }
      } else {
        $this->session->unset_userdata('regcompanyCode');
        $res['err'] = 300;
        $res['msg'] = "Failed to install and setup your application. Please click on try again button to initiate setup again";
        $this->frontend_template("payment/noprocess", $res, "Failed!");
      }
    } else {
      $this->session->unset_userdata('regcompanyCode');
      $res['err'] = 300;
      $res['msg'] = "Failed to install and setup your application. Please click on try again button to initiate setup again";
      $this->frontend_template("payment/noprocess", $res, "Opps!");
    }
  }

  public function maskSupermarket(string $dbName, array $client, string $mainPaymentCode)
  {
    $clCode = $client['code'];
    $rightsFolder = FCPATH . "supermarket/application/rights/$clCode";
    if (!file_exists($rightsFolder)) {
      mkdir($rightsFolder, 0777, true);
      copy(FCPATH . "assets/projconfig/supermarket/R_1.json", $rightsFolder . "/R_1.json");
    }

    
    $this->db->query("CREATE DATABASE `$dbName`");
    $this->db->query('use ' . $dbName);

    $this->db->query("DROP TABLE IF EXISTS `companymaster`");
    $qry = "CREATE TABLE `companymaster`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `cmpcode` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `companyname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `category` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `crno` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `vatno` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `regno` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `registerDate` datetime(0) NULL DEFAULT NULL,
            `address` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `cmplogo` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `onBoard` tinyint(1) NULL DEFAULT 1,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT 0,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
            ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic";
    $this->db->query($qry);

    $this->db->insert("companymaster", $client);

    $this->db->query("DROP TABLE IF EXISTS `accountexpense`");
    $qry = "CREATE TABLE `accountexpense`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `branchCode` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `cost` decimal(18, 2) NULL DEFAULT NULL,
            `accExpenseDate` timestamp(0) NULL DEFAULT NULL,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT 0,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            `description` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
            ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `attributemaster`");
    $qry = "CREATE TABLE `attributemaster`  (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
                `attributeName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `isActive` tinyint(1) NULL DEFAULT NULL,
                `isDelete` tinyint(1) NULL DEFAULT 0,
                `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addDate` timestamp(0) NULL DEFAULT NULL,
                `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editDate` timestamp(0) NULL DEFAULT NULL,
                `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteDate` timestamp(0) NULL DEFAULT NULL,
                PRIMARY KEY (`code`) USING BTREE,
                UNIQUE INDEX `id`(`id`) USING BTREE
              ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `barcodeentries`");
    $qry = "CREATE TABLE `barcodeentries`  (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
                `batchNo` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `inwardLineCode` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `sellingUnit` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `sellingQty` decimal(15, 2) NULL DEFAULT NULL,
                `sellingPrice` decimal(15, 2) NULL DEFAULT NULL,
                `taxPercent` decimal(15, 2) NULL DEFAULT NULL,
                `taxAmount` decimal(15, 2) NULL DEFAULT NULL,
                `discountPrice` decimal(15, 2) NULL DEFAULT NULL,
                `barcodeText` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
                `isActive` tinyint(1) NULL DEFAULT NULL,
                `isDelete` tinyint(1) NULL DEFAULT 0,
                `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addDate` timestamp(0) NULL DEFAULT NULL,
                `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editDate` timestamp(0) NULL DEFAULT NULL,
                `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteDate` timestamp(0) NULL DEFAULT NULL,
                PRIMARY KEY (`code`) USING BTREE,
                UNIQUE INDEX `id`(`id`) USING BTREE
              ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `baseunitmaster`");
    $qry = "CREATE TABLE `baseunitmaster`  (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
                `baseunitName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `baseunitSName` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `baseunitDesc` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
                `isActive` tinyint(1) NULL DEFAULT NULL,
                `isDelete` tinyint(1) NULL DEFAULT 0,
                `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addDate` timestamp(0) NULL DEFAULT NULL,
                `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editDate` timestamp(0) NULL DEFAULT NULL,
                `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteDate` timestamp(0) NULL DEFAULT NULL,
                PRIMARY KEY (`code`) USING BTREE,
                UNIQUE INDEX `id`(`id`) USING BTREE
              ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `branchmaster`");
    $qry = "CREATE TABLE `branchmaster`  (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
                `branchName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `taxGroup` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `branchTaxRegName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `branchTaxRegNo` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `openingFrom` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `openingTo` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `branchPhoneNo` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `branchAddress` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
                `branchLat` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `branchLong` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `receiptHead` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `receiptFoot` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `isActive` tinyint(1) NULL DEFAULT NULL,
                `isDelete` tinyint(1) NULL DEFAULT 0,
                `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addDate` timestamp(0) NULL DEFAULT NULL,
                `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editDate` timestamp(0) NULL DEFAULT NULL,
                `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteDate` timestamp(0) NULL DEFAULT NULL,
                PRIMARY KEY (`code`) USING BTREE,
                UNIQUE INDEX `id`(`id`) USING BTREE
              ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `brandmaster`");
    $qry = "CREATE TABLE `brandmaster`  (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
                `brandName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `isActive` tinyint(1) NULL DEFAULT NULL,
                `isDelete` tinyint(1) NULL DEFAULT 0,
                `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addDate` timestamp(0) NULL DEFAULT NULL,
                `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editDate` timestamp(0) NULL DEFAULT NULL,
                `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteDate` timestamp(0) NULL DEFAULT NULL,
                PRIMARY KEY (`code`) USING BTREE,
                UNIQUE INDEX `id`(`id`) USING BTREE
              ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `categorymaster`");
    $qry = "CREATE TABLE `categorymaster`  (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
                `categoryName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `categorySName` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `icon` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `description` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
                `isActive` tinyint(1) NULL DEFAULT NULL,
                `isDelete` tinyint(1) NULL DEFAULT 0,
                `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addDate` timestamp(0) NULL DEFAULT NULL,
                `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editDate` timestamp(0) NULL DEFAULT NULL,
                `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteDate` timestamp(0) NULL DEFAULT NULL,
                PRIMARY KEY (`code`) USING BTREE,
                UNIQUE INDEX `id`(`id`) USING BTREE
              ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `countermaster`");
    $qry = "CREATE TABLE `countermaster`  (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
                `branchCode` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `counterName` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `isActive` tinyint(1) NULL DEFAULT NULL,
                `isDelete` tinyint(1) NULL DEFAULT 0,
                `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addDate` timestamp(0) NULL DEFAULT NULL,
                `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editDate` timestamp(0) NULL DEFAULT NULL,
                `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteDate` timestamp(0) NULL DEFAULT NULL,
                PRIMARY KEY (`code`) USING BTREE,
                UNIQUE INDEX `id`(`id`) USING BTREE
              ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `customer`");
    $qry = "CREATE TABLE `customer`  (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
                `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `arabicName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `country` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `state` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `city` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `pincode` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `countryCode` varchar(6) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `address` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
                `isActive` tinyint(1) NULL DEFAULT NULL,
                `isDelete` tinyint(1) NULL DEFAULT 0,
                `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addDate` timestamp(0) NULL DEFAULT NULL,
                `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editDate` timestamp(0) NULL DEFAULT NULL,
                `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteDate` timestamp(0) NULL DEFAULT NULL,
                `custGroupCode` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                PRIMARY KEY (`code`) USING BTREE,
                UNIQUE INDEX `id`(`id`) USING BTREE
              ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `customergroupmaster`");
    $qry = "CREATE TABLE `customergroupmaster`  (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
                `customerGroupName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `isActive` tinyint(1) NULL DEFAULT NULL,
                `isDelete` tinyint(1) NULL DEFAULT 0,
                `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addDate` timestamp(0) NULL DEFAULT NULL,
                `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editDate` timestamp(0) NULL DEFAULT NULL,
                `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteDate` timestamp(0) NULL DEFAULT NULL,
                PRIMARY KEY (`code`) USING BTREE,
                UNIQUE INDEX `id`(`id`) USING BTREE
              ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `emailtemplates`");
    $qry = "CREATE TABLE `emailtemplates`  (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
                `subject` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
                `message` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
                `isActive` tinyint(1) NULL DEFAULT 1,
                `isDelete` tinyint(1) NULL DEFAULT 0,
                `addID` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addDate` datetime(0) NULL DEFAULT NULL,
                `editID` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editDate` datetime(0) NULL DEFAULT NULL,
                `deleteID` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteDate` datetime(0) NULL DEFAULT NULL,
                `templateName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                PRIMARY KEY (`code`) USING BTREE,
                UNIQUE INDEX `id`(`id`) USING BTREE
              ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `giftcard`");
    $qry = "CREATE TABLE `giftcard`  (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
                `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `description` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
                `cardType` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `discount` int(11) NULL DEFAULT NULL,
                `price` int(11) NULL DEFAULT NULL,
                `validityInDays` int(11) NULL DEFAULT NULL,
                `isActive` tinyint(1) NOT NULL DEFAULT 0,
                `isDelete` tinyint(1) NOT NULL DEFAULT 0,
                `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addDate` timestamp(0) NULL DEFAULT NULL,
                `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editDate` timestamp(0) NULL DEFAULT NULL,
                `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteDate` timestamp(0) NULL DEFAULT NULL,
                PRIMARY KEY (`code`) USING BTREE,
                UNIQUE INDEX `id`(`id`) USING BTREE
              ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `inwardentries`");
    $qry = "CREATE TABLE `inwardentries`  (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
                `batchNo` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `branchCode` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `supplierCode` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `inwardDate` timestamp(0) NULL DEFAULT NULL,
                `total` decimal(18, 2) NULL DEFAULT NULL,
                `isApproved` int(11) NULL DEFAULT 0,
                `ref` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `isActive` tinyint(1) NULL DEFAULT NULL,
                `isDelete` tinyint(1) NULL DEFAULT 0,
                `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addDate` timestamp(0) NULL DEFAULT NULL,
                `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editDate` timestamp(0) NULL DEFAULT NULL,
                `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteDate` timestamp(0) NULL DEFAULT NULL,
                `flag` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                PRIMARY KEY (`code`) USING BTREE,
                UNIQUE INDEX `id`(`id`) USING BTREE
              ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `inwardlineentries`");
    $qry = "CREATE TABLE `inwardlineentries`  (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
                `inwardCode` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `batchNo` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `productCode` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `variantCode` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `proNameVarName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `productQty` int(11) NULL DEFAULT NULL,
                `productUnit` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `productPrice` decimal(18, 2) NULL DEFAULT NULL,
                `subTotal` decimal(18, 2) NULL DEFAULT NULL,
                `fromBatchNo` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `isActive` tinyint(1) NULL DEFAULT NULL,
                `isDelete` tinyint(1) NULL DEFAULT 0,
                `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addDate` timestamp(0) NULL DEFAULT NULL,
                `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editDate` timestamp(0) NULL DEFAULT NULL,
                `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteDate` timestamp(0) NULL DEFAULT NULL,
                `expiryDate` date NULL DEFAULT NULL,
                `tax` decimal(10, 2) NULL DEFAULT NULL,
                `sku` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                PRIMARY KEY (`code`) USING BTREE,
                UNIQUE INDEX `id`(`id`) USING BTREE
              ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `offer`");
    $qry = "CREATE TABLE `offer`  (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
                `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `description` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
                `offerType` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `discount` int(11) NULL DEFAULT NULL,
                `minimumAmount` int(11) NULL DEFAULT NULL,
                `startDate` datetime NULL DEFAULT NULL,
                `endDate` datetime NULL DEFAULT NULL,
                `capLimit` int(11) NULL DEFAULT NULL,
                `flatAmount` int(11) NULL DEFAULT NULL,
                `isActive` tinyint(1) NOT NULL DEFAULT 0,
                `isDelete` tinyint(1) NOT NULL DEFAULT 0,
                `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addDate` timestamp(0) NULL DEFAULT NULL,
                `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editDate` timestamp(0) NULL DEFAULT NULL,
                `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteDate` timestamp(0) NULL DEFAULT NULL,
                `isAdminApproved` tinyint(1) NULL DEFAULT 0,
                PRIMARY KEY (`code`) USING BTREE,
                UNIQUE INDEX `id`(`id`) USING BTREE
              ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `orderlineentries`");
    $qry = "CREATE TABLE `orderlineentries`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `orderCode` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `productCode` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `variantCode` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `barcode` int(11) NULL DEFAULT NULL,
            `unit` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `price` decimal(10, 2) NULL DEFAULT NULL,
            `qty` decimal(10, 3) NULL DEFAULT NULL,
            `amount` decimal(10, 2) NULL DEFAULT NULL,
            `discountPrice` decimal(10, 2) NULL DEFAULT NULL,
            `discount` decimal(10, 2) NULL DEFAULT NULL,
            `taxPercent` decimal(10, 2) NULL DEFAULT NULL,
            `tax` decimal(10, 2) NULL DEFAULT NULL,
            `totalPrice` decimal(10, 2) NULL DEFAULT NULL,
            `sku` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` datetime(0) NULL DEFAULT NULL,
            `productName` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            PRIMARY KEY (`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `ordermaster`");
    $qry = "CREATE TABLE `ordermaster`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `code` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `txnId` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `orderDate` datetime(0) NULL DEFAULT NULL,
            `branchCode` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `counter` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `customerCode` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `countryCode` varchar(6) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `phone` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `paymentMode` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'CASH',
            `remark` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `offerCode` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `offerType` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `offerDiscount` decimal(18, 2) NULL DEFAULT NULL,
            `giftcardNo` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `giftDiscount` decimal(18, 2) NULL DEFAULT NULL,
            `totalItems` int(11) NULL DEFAULT NULL,
            `subTotal` decimal(18, 2) NULL DEFAULT NULL,
            `discountTotal` decimal(18, 2) NULL DEFAULT NULL,
            `offerDiscountTotal` decimal(18, 2) NULL DEFAULT NULL,
            `giftDiscountTotal` decimal(18, 2) NULL DEFAULT NULL,
            `totalTax` decimal(18, 2) NULL DEFAULT NULL,
            `totalPayable` decimal(18, 2) NULL DEFAULT NULL,
            `addID` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` datetime(0) NULL DEFAULT NULL,
            `editID` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editIP` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` datetime(0) NULL DEFAULT NULL,
            `return` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `orderreturnlineentries`");
    $qry = "CREATE TABLE `orderreturnlineentries`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `orderCode` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `productCode` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `variantCode` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `barcode` int(11) NULL DEFAULT NULL,
            `unit` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `price` decimal(10, 2) NULL DEFAULT NULL,
            `qty` decimal(10, 3) NULL DEFAULT NULL,
            `amount` decimal(10, 2) NULL DEFAULT NULL,
            `discountPrice` decimal(10, 2) NULL DEFAULT NULL,
            `discount` decimal(10, 2) NULL DEFAULT NULL,
            `taxPercent` decimal(10, 2) NULL DEFAULT NULL,
            `tax` decimal(10, 2) NULL DEFAULT NULL,
            `totalPrice` decimal(10, 2) NULL DEFAULT NULL,
            `sku` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` datetime(0) NULL DEFAULT NULL,
            `code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `ordertemp`");
    $qry = "CREATE TABLE `ordertemp`  (
            `orderId` int(11) NOT NULL AUTO_INCREMENT,
            `branchCode` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `userCode` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `counter` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `customer` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `offerCode` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `offerDiscount` decimal(18, 2) NULL DEFAULT NULL,
            `offerType` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `giftcardNo` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `giftDiscount` decimal(15, 2) NULL DEFAULT NULL,
            `subTotal` decimal(18, 2) NULL DEFAULT NULL,
            `discountTotal` decimal(18, 2) NULL DEFAULT NULL,
            `offerDiscountTotal` decimal(18, 2) NULL DEFAULT NULL,
            `giftDiscountTotal` decimal(18, 2) NULL DEFAULT NULL,
            `taxTotal` decimal(18, 2) NULL DEFAULT NULL,
            `totalPayable` decimal(18, 2) NULL DEFAULT NULL,
            `draft` datetime(0) NULL DEFAULT NULL,
            `created_at` datetime(0) NULL DEFAULT NULL,
            `status` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'noaction',
            PRIMARY KEY (`orderId`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `ordertempproducts`");
    $qry = "CREATE TABLE `ordertempproducts`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `orderId` int(11) NULL DEFAULT NULL,
            `sku` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `productCode` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `variantCode` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `barcode` int(11) NULL DEFAULT NULL,
            `productQty` decimal(18, 3) NULL DEFAULT NULL,
            `unit` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `price` decimal(18, 2) NULL DEFAULT NULL,
            `qty` decimal(18, 3) NULL DEFAULT NULL,
            `amount` decimal(18, 2) NULL DEFAULT NULL,
            `discountPrice` decimal(18, 2) NULL DEFAULT NULL,
            `discount` decimal(18, 2) NULL DEFAULT NULL,
            `giftDiscount` decimal(18, 2) NULL DEFAULT NULL,
            `offerDiscoount` decimal(18, 2) NULL DEFAULT NULL,
            `taxPercent` decimal(18, 2) NULL DEFAULT NULL,
            `tax` decimal(18, 2) NULL DEFAULT NULL,
            `totalPrice` decimal(18, 2) NULL DEFAULT NULL,
            `productName` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            PRIMARY KEY (`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `productattributes`");
    $qry = "CREATE TABLE `productattributes`  (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
                `productCode` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `attributesCode` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `isActive` tinyint(1) NULL DEFAULT NULL,
                `isDelete` tinyint(1) NULL DEFAULT NULL,
                `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addDate` timestamp(0) NULL DEFAULT NULL,
                `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editDate` timestamp(0) NULL DEFAULT NULL,
                `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteDate` datetime(0) NULL DEFAULT NULL,
                PRIMARY KEY (`code`) USING BTREE,
                UNIQUE INDEX `id`(`id`) USING BTREE
              ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `productattributeslineentries`");
    $qry = "CREATE TABLE `productattributeslineentries`  (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
                `productAttCode` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `subTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `isActive` tinyint(1) NULL DEFAULT NULL,
                `isDelete` tinyint(1) NULL DEFAULT NULL,
                `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addDate` timestamp(0) NULL DEFAULT NULL,
                `editDate` timestamp(0) NULL DEFAULT NULL,
                `deleteDate` timestamp(0) NULL DEFAULT NULL,
                PRIMARY KEY (`code`) USING BTREE,
                UNIQUE INDEX `id`(`id`) USING BTREE
              ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `productmaster`");
    $qry = "CREATE TABLE `productmaster`  (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
                `productEngName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `productArbName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `productHinName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `productUrduName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `brandCode` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `sku` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `productCategory` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `productSubcategory` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `storageUnit` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `ingredientUnit` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `productPrice` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `alertQty` decimal(18, 2) NULL DEFAULT NULL,
                `productTaxGrp` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `productEngDesc` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
                `productArbDesc` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
                `productHinDesc` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
                `productUrduDesc` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
                `isActive` tinyint(1) NULL DEFAULT NULL,
                `isDelete` tinyint(1) NULL DEFAULT 0,
                `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addDate` timestamp(0) NULL DEFAULT NULL,
                `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editDate` timestamp(0) NULL DEFAULT NULL,
                `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteDate` timestamp(0) NULL DEFAULT NULL,
                `productImage` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `hasVariants` tinyint(1) NULL DEFAULT NULL,
                PRIMARY KEY (`code`) USING BTREE,
                UNIQUE INDEX `id`(`id`) USING BTREE
              ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `productvariants`");
    $qry = "CREATE TABLE `productvariants`  (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
                `sku` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `productCode` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `variantName` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
                `price` decimal(10, 2) NULL DEFAULT NULL,
                `sellingUnit` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `sellingQty` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `isActive` tinyint(1) NULL DEFAULT NULL,
                `isDelete` tinyint(1) NULL DEFAULT NULL,
                `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addDate` timestamp(0) NULL DEFAULT NULL,
                `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editDate` timestamp(0) NULL DEFAULT NULL,
                `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteDate` datetime(0) NULL DEFAULT NULL,
                PRIMARY KEY (`code`) USING BTREE,
                UNIQUE INDEX `id`(`id`) USING BTREE
              ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `returnentries`");
    $qry = "CREATE TABLE `returnentries`  (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
                `inwardCode` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `productCode` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `returnQty` decimal(18, 2) NULL DEFAULT NULL,
                `isActive` tinyint(1) NULL DEFAULT NULL,
                `isDelete` tinyint(1) NULL DEFAULT 0,
                `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addDate` timestamp(0) NULL DEFAULT NULL,
                `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editDate` timestamp(0) NULL DEFAULT NULL,
                `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteDate` timestamp(0) NULL DEFAULT NULL,
                `flag` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `variantCode` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `proNameVarName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                PRIMARY KEY (`code`) USING BTREE,
                UNIQUE INDEX `id`(`id`) USING BTREE
              ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `rolesmaster`");
    $qry = "CREATE TABLE `rolesmaster`  (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
                `role` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `isActive` tinyint(1) NULL DEFAULT NULL,
                `isDelete` tinyint(1) NULL DEFAULT 0,
                `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addDate` timestamp(0) NULL DEFAULT NULL,
                `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editDate` timestamp(0) NULL DEFAULT NULL,
                `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteDate` timestamp(0) NULL DEFAULT NULL,
                PRIMARY KEY (`code`) USING BTREE,
                UNIQUE INDEX `id`(`id`) USING BTREE
              ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->insert("rolesmaster", ["code" => "R_1", "role" => "Admin", "isActive" => 1, "addIP" => $client['addIP'], "addDate" => $client['addDate']]);
    $this->db->insert("rolesmaster", ["code" => "R_2", "role" => "Manager", "isActive" => 1, "addIP" => $client['addIP'], "addDate" => $client['addDate']]);
    $this->db->insert("rolesmaster", ["code" => "R_3", "role" => "Accounts", "isActive" => 1, "addIP" => $client['addIP'], "addDate" => $client['addDate']]);
    $this->db->insert("rolesmaster", ["code" => "R_4", "role" => "Staff", "isActive" => 1, "addIP" => $client['addIP'], "addDate" => $client['addDate']]);
    $this->db->insert("rolesmaster", ["code" => "R_5", "role" => "Cashier", "isActive" => 1, "addIP" => $client['addIP'], "addDate" => $client['addDate']]);
    $this->db->insert("rolesmaster", ["code" => "R_6", "role" => "Store", "isActive" => 1, "addIP" => $client['addIP'], "addDate" => $client['addDate']]);

    $this->db->query("DROP TABLE IF EXISTS `salegiftcard`");
    $qry = "CREATE TABLE `salegiftcard`  (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
                `cardCode` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `cardDetails` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
                `custName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `custPhone` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `countryCode` varchar(6) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `custEmail` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `expiryDate` date NULL DEFAULT NULL,
                `totalPrice` int(11) NULL DEFAULT NULL,
                `isActive` tinyint(1) NOT NULL DEFAULT 0,
                `isDelete` tinyint(1) NOT NULL DEFAULT 0,
                `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addDate` timestamp(0) NULL DEFAULT NULL,
                `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editDate` timestamp(0) NULL DEFAULT NULL,
                `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteDate` timestamp(0) NULL DEFAULT NULL,
                `cardCount` int(11) NULL DEFAULT NULL,
                PRIMARY KEY (`code`) USING BTREE,
                UNIQUE INDEX `id`(`id`) USING BTREE
              ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `salegiftcardlineentries`");
    $qry = "CREATE TABLE `salegiftcardlineentries`  (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
                `salecardCode` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `giftNo` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `custName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `custPhone` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `countryCode` varchar(6) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `custEmail` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,   
                `isUsed` tinyint(1) NULL DEFAULT 0,            
                `isActive` tinyint(1) NOT NULL DEFAULT 0,
                `isDelete` tinyint(1) NOT NULL DEFAULT 0,
                `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addDate` timestamp(0) NULL DEFAULT NULL,
                `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editDate` timestamp(0) NULL DEFAULT NULL,
                `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteDate` timestamp(0) NULL DEFAULT NULL,
                PRIMARY KEY (`code`) USING BTREE,
                UNIQUE INDEX `id`(`id`) USING BTREE
              ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `settings`");
    $qry = "CREATE TABLE `settings`  (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'X',
                `settingName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `settingValue` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
                `isActive` tinyint(1) NULL DEFAULT NULL,
                `isDelete` tinyint(1) NULL DEFAULT NULL,
                `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addDate` datetime(0) NULL DEFAULT NULL,
                `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editDate` datetime(0) NULL DEFAULT NULL,
                `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteDate` datetime(0) NULL DEFAULT NULL,
                PRIMARY KEY (`code`) USING BTREE,
                UNIQUE INDEX `id`(`id`) USING BTREE
              ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->insert("settings", ["id" => 1, "code" => "STG_1", "settingName" => "SUK Prefix", "settingValue" => "SKU", "isActive" =>  1]);
    $this->db->insert("settings", ["id" => 2, "code" => "STG_2", "settingName" => "Email", "settingValue" => "", "isActive" =>  1]);
    $this->db->insert("settings", ["id" => 3, "code" => "STG_3", "settingName" => "Text SMS", "settingValue" => "", "isActive" =>  1]);

    $this->db->query("DROP TABLE IF EXISTS `smstemplates`");
    $qry = "CREATE TABLE `smstemplates`  (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
                `template` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `isActive` tinyint(1) NULL DEFAULT 1,
                `isDelete` tinyint(1) NULL DEFAULT 0,
                `addID` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addDate` datetime(0) NULL DEFAULT NULL,
                `editID` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editDate` datetime(0) NULL DEFAULT NULL,
                `deleteID` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteDate` datetime(0) NULL DEFAULT NULL,
                `templateName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                PRIMARY KEY (`code`) USING BTREE,
                UNIQUE INDEX `id`(`id`) USING BTREE
              ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `stockinfo`");
    $qry = "CREATE TABLE `stockinfo`  (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
                `productCode` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `stock` decimal(18, 2) NULL DEFAULT NULL,
                `sku` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `isActive` tinyint(1) NULL DEFAULT NULL,
                `isDelete` tinyint(1) NULL DEFAULT 0,
                `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addDate` timestamp(0) NULL DEFAULT NULL,
                `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editDate` timestamp(0) NULL DEFAULT NULL,
                `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteDate` timestamp(0) NULL DEFAULT NULL,
                `unitCode` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `batchNo` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `fromBatchNo` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `branchCode` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `variantCode` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `proNameVarName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                PRIMARY KEY (`code`) USING BTREE,
                UNIQUE INDEX `id`(`id`) USING BTREE
              ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `subcategorymaster`");
    $qry = "CREATE TABLE `subcategorymaster`  (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
                `categoryCode` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `subcategoryName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `subcategorySName` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `icon` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `description` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
                `isActive` tinyint(1) NULL DEFAULT NULL,
                `isDelete` tinyint(1) NULL DEFAULT 0,
                `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addDate` timestamp(0) NULL DEFAULT NULL,
                `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editDate` timestamp(0) NULL DEFAULT NULL,
                `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteDate` timestamp(0) NULL DEFAULT NULL,
                PRIMARY KEY (`code`) USING BTREE,
                UNIQUE INDEX `id`(`id`) USING BTREE
              ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `suppliermaster`");
    $qry = "CREATE TABLE `suppliermaster`  (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
                `supplierName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `arabicName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `companyName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `country` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `state` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `city` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `postalCode` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `countryCode` varchar(6) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `tax` int(11) NULL DEFAULT NULL,
                `financialAccount` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `address` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
                `isActive` tinyint(1) NULL DEFAULT NULL,
                `isDelete` tinyint(1) NULL DEFAULT 0,
                `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addDate` timestamp(0) NULL DEFAULT NULL,
                `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editDate` timestamp(0) NULL DEFAULT NULL,
                `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteDate` timestamp(0) NULL DEFAULT NULL,
                `supplierImage` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                PRIMARY KEY (`code`) USING BTREE,
                UNIQUE INDEX `id`(`id`) USING BTREE
              ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `taxes`");
    $qry = "CREATE TABLE `taxes`  (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
                `taxName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `taxPer` decimal(18, 2) NOT NULL,
                `isActive` tinyint(1) NULL DEFAULT NULL,
                `isDelete` tinyint(1) NULL DEFAULT 0,
                `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addDate` timestamp(0) NULL DEFAULT NULL,
                `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editDate` timestamp(0) NULL DEFAULT NULL,
                `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteDate` timestamp(0) NULL DEFAULT NULL,
                PRIMARY KEY (`code`) USING BTREE,
                UNIQUE INDEX `id`(`id`) USING BTREE
              ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `taxgroupmaster`");
    $qry = "CREATE TABLE `taxgroupmaster`  (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
                `taxGroupName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `taxes` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
                `taxGroupRef` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `isActive` tinyint(1) NULL DEFAULT NULL,
                `isDelete` tinyint(1) NULL DEFAULT 0,
                `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addDate` timestamp(0) NULL DEFAULT NULL,
                `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editDate` timestamp(0) NULL DEFAULT NULL,
                `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteDate` timestamp(0) NULL DEFAULT NULL,
                PRIMARY KEY (`code`) USING BTREE,
                UNIQUE INDEX `id`(`id`) USING BTREE
              ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `unitmaster`");
    $qry = "CREATE TABLE `unitmaster`  (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
                `unitName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `unitSName` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `unitDesc` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
                `conversionFactor` decimal(15, 3) NULL DEFAULT 1.000,
                `isActive` tinyint(1) NULL DEFAULT NULL,
                `isDelete` tinyint(1) NULL DEFAULT 0,
                `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addDate` timestamp(0) NULL DEFAULT NULL,
                `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editDate` timestamp(0) NULL DEFAULT NULL,
                `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteDate` timestamp(0) NULL DEFAULT NULL,
                `unitRounding` tinyint(1) NULL DEFAULT 0,
                `baseUnitCode` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                PRIMARY KEY (`code`) USING BTREE,
                UNIQUE INDEX `id`(`id`) USING BTREE
              ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `usermaster`");
    $qry =  "CREATE TABLE `usermaster`  (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
                `userBranchCode` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci  NULL,
                `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `userName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `userLang` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `userEmpNo` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `userEmail` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `userPassword` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `userCounter` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `isActive` tinyint(1) NULL DEFAULT NULL,
                `isDelete` tinyint(1) NULL DEFAULT 0,
                `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `addDate` timestamp(0) NULL DEFAULT NULL,
                `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `editDate` timestamp(0) NULL DEFAULT NULL,
                `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `deleteDate` timestamp(0) NULL DEFAULT NULL,
                `userRole` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `token` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
                `loginpin` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `userImage` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                `invoicePreference` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'autocut',  
                PRIMARY KEY (`code`) USING BTREE,
                UNIQUE INDEX `id`(`id`) USING BTREE
              ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $password = $client['password'];
    $username = "admin";
    $adminUser = [
      "code" => "USR" . date("y") . "_1",
      "name" => $client['name'],
      "userName" => $username,
      "userLang" => "ENGLISH",
      "userEmail" => $client['email'],
      "userPassword" => md5($password),
      "userRole" => "R_1",
      "isActive" => 1,
      "isDelete" => 0,
      "addID" => $client['addID'],
      "addIP" => $client['addIP'],
      "addDate" => date('Y-m-d H:i:s')
    ];
    $this->db->insert("usermaster", $adminUser);
    $userdetails = [
      "cmpcode" => $client['cmpcode'],
      "name" => $client['name'],
      "category" => $client['category'],
      "username" => "admin",
      "password" => $password
    ];
    $html_template = $this->load->view("mails/register", $userdetails, true);
    $this->sendemail->sendMailWithAttachment($client['email'], "Yay! Registration complete.", $html_template, []);
  }

  public function maskRestaurant(string $dbName, array $client, string $mainPaymentCode)
  {
    $clCode = $client['code'];
    $rightsFolder = FCPATH . "restaurant/application/rights/$clCode";
    if (!file_exists($rightsFolder)) {
      mkdir($rightsFolder, 0777, true);
      copy(FCPATH . "assets/projconfig/restaurant/R_1.json", $rightsFolder . "/R_1.json");
    }

    $this->db->query("CREATE DATABASE `$dbName`");
    $this->db->query('use ' . $dbName);

    $this->db->query("DROP TABLE IF EXISTS `companymaster`");
    $qry = "CREATE TABLE `companymaster`  (
          `id` int(11) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `cmpcode` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `companyname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `category` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `crno` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `vatno` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `regno` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `registerDate` datetime(0) NULL DEFAULT NULL,
            `address` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `cmplogo` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `onBoard` tinyint(1) NULL DEFAULT 1,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT 0,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
            ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic";
    $this->db->query($qry);

    $this->db->insert("companymaster", $client);

    $this->db->query("DROP TABLE IF EXISTS `accountexpense`");
    $qry = "CREATE TABLE `accountexpense`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `branchCode` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `cost` decimal(18, 2) NULL DEFAULT NULL,
            `accExpenseDate` timestamp(0) NULL DEFAULT NULL,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT 0,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            `description` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `bookorderstatuslineentries`");
    $qry = "CREATE TABLE `bookorderstatuslineentries`  (
            `id` int(5) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `orderCode` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `orderstatus` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `statusTime` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `statusText` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT 0,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `branchmaster`");
    $qry = "CREATE TABLE `branchmaster`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `branchName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            `taxGroup` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            `branchTaxRegName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `branchTaxRegNo` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `openingFrom` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `openingTo` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `branchPhoneNo` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `branchAddress` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `branchLat` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `branchLong` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `receiptHead` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `receiptFoot` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT 0,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `cart`");
    $qry = "CREATE TABLE `cart`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `kotNumber` int(11) NULL DEFAULT NULL,
            `kotDate` date NULL DEFAULT NULL,
            `branchCode` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
            `tableSection` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
            `tableNumber` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `custPhone` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
            `custName` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
            `kotStatus` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT 'WAITING',
            `waitingMinutes` int(11) NULL DEFAULT NULL,
            `preparingMinutes` int(11) NULL DEFAULT NULL,
            `cartBy` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
            `cartDate` datetime(0) NULL DEFAULT NULL,
            `preparingDateTime` datetime(0) NULL DEFAULT NULL,
            `servereadyDateTime` datetime(0) NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `cartproducts`");
    $qry = "CREATE TABLE `cartproducts`  (
            `id` int NOT NULL AUTO_INCREMENT,
            `cartId` int NOT NULL,
            `productCode` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            `productQty` int NULL DEFAULT NULL,
            `addOns` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `variantCode` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `productPrice` decimal(18, 2) NULL DEFAULT NULL,
            `totalPrice` decimal(18, 2) NULL DEFAULT NULL,
            `comboProducts` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `isCombo` tinyint(1) NULL DEFAULT 0,
            `prodNames` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `productTax` decimal(18, 2) NULL DEFAULT NULL,
            `productTaxAmount` decimal(18, 2) NULL DEFAULT NULL,
            `customizes` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `tax` decimal(18, 2) NULL DEFAULT NULL,
            `taxAmount` decimal(18, 2) NULL DEFAULT NULL,
            `comboProductItemsName` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `categorymaster`");
    $qry = "CREATE TABLE `categorymaster`  (
            `id` int(5) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `categoryName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `categorySName` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            `icon` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `description` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT 0,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `couponoffer`");
    $qry = "CREATE TABLE `couponoffer`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `couponCode` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `offerType` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `discount` int(11) NULL DEFAULT NULL,
            `minimumAmount` int(11) NULL DEFAULT NULL,
            `perUserLimit` int(11) NULL DEFAULT NULL,
            `startDate` datetime NULL DEFAULT NULL,
            `endDate` datetime NULL DEFAULT NULL,
            `capLimit` int(11) NULL DEFAULT NULL,
            `termsAndConditions` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `flatAmount` int(11) NULL DEFAULT NULL,
            `isActive` tinyint(1) NOT NULL DEFAULT 0,
            `isDelete` tinyint(1) NOT NULL DEFAULT 0,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            `isAdminApproved` tinyint(1) NULL DEFAULT 0,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `customer`");
    $qry = "CREATE TABLE `customer`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            `arabicName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `country` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `state` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `city` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `pincode` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `address` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT 0,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            `custGroupCode` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `customergroupmaster`");
    $qry = "CREATE TABLE `customergroupmaster`  (
            `id` int(5) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `customerGroupName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT 0,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `customizedcategory`");
    $qry = "CREATE TABLE `customizedcategory`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `productCode` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `categoryTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `categoryType` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `isEnabled` tinyint(1) NULL DEFAULT NULL,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT NULL,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` datetime(0) NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `customizedcategorylineentries`");
    $qry = "CREATE TABLE `customizedcategorylineentries`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `customizedCategoryCode` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `subCategoryTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `price` decimal(10, 2) NULL DEFAULT NULL,
            `isEnabled` tinyint(1) NULL DEFAULT NULL,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT NULL,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `discountmaster`");
    $qry = "CREATE TABLE `discountmaster`  (
            `id` int(5) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `discount` decimal(15, 2) NULL DEFAULT NULL,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT 0,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `draftcart`");
    $qry = "CREATE TABLE `draftcart`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `branchCode` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
            `tableSection` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
            `tableNumber` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `custPhone` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
            `custName` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
            `cartBy` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
            `cartDate` datetime(0) NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `draftcartproducts`");
    $qry = "CREATE TABLE `draftcartproducts`  (
            `id` int NOT NULL AUTO_INCREMENT,
            `draftId` int NOT NULL,
            `productCode` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            `productQty` int NULL DEFAULT NULL,
            `addOns` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `variantCode` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `productPrice` decimal(18, 2) NULL DEFAULT NULL,
            `totalPrice` decimal(18, 2) NULL DEFAULT NULL,
            `comboProducts` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `isCombo` tinyint(1) NULL DEFAULT 0,
            `prodNames` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `productTax` decimal(18, 2) NULL DEFAULT NULL,
            `productTaxAmount` decimal(18, 2) NULL DEFAULT NULL,
            `customizes` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `tax` decimal(18, 2) NULL DEFAULT NULL,
            `taxAmount` decimal(18, 2) NULL DEFAULT NULL,
            `comboProductItemsName` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `emailtemplates`");
    $query = "CREATE TABLE `emailtemplates`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `subject` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `message` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `isActive` tinyint(1) NULL DEFAULT 1,
            `isDelete` tinyint(1) NULL DEFAULT 0,
            `addID` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` datetime(0) NULL DEFAULT NULL,
            `editID` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` datetime(0) NULL DEFAULT NULL,
            `deleteID` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` datetime(0) NULL DEFAULT NULL,
            `templateName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
            ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `inwardentries`");
    $qry = "CREATE TABLE `inwardentries`  (
            `id` int(5) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `branchCode` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `supplierCode` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `ref` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `inwardDate` timestamp(0) NULL DEFAULT NULL,
            `total` decimal(18, 2) NULL DEFAULT NULL,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT 0,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            `flag` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,            
            `isApproved` tinyint(1) NULL DEFAULT 0,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `inwardlineentries`");
    $qry = "CREATE TABLE `inwardlineentries`  (
            `id` int(5) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `inwardCode` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `itemCode` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `itemQty` int(11) NULL DEFAULT NULL,
            `itemUom` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `itemPrice` decimal(18, 2) NULL DEFAULT NULL,
            `subTotal` decimal(18, 2) NULL DEFAULT NULL,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT 0,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `itemmaster`");
    $qry = "CREATE TABLE `itemmaster`  (
            `id` int(5) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `itemEngName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `itemArbName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `itemHinName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `itemUrduName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `storageUnit` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `itemPrice` decimal(18, 2) NULL DEFAULT NULL,
            `itemEngDesc` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `itemArbDesc` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `itemHinDesc` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `itemUrduDesc` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT 0,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            `ingredientUnit` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `ingredientFactor` decimal(18, 2) NULL DEFAULT NULL,
            `categoryCode` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `itemSKU` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `itemTax` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `offer`");
    $qry = "CREATE TABLE `offer`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `description` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `offerType` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `discount` int(11) NULL DEFAULT NULL,
            `minimumAmount` int(11) NULL DEFAULT NULL,
            `startDate` datetime NULL DEFAULT NULL,
            `endDate` datetime NULL DEFAULT NULL,
            `capLimit` int(11) NULL DEFAULT NULL,
            `flatAmount` int(11) NULL DEFAULT NULL,
            `isActive` tinyint(1) NOT NULL DEFAULT 0,
            `isDelete` tinyint(1) NOT NULL DEFAULT 0,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            `isAdminApproved` tinyint(1) NULL DEFAULT 0,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `orderlineentries`");
    $qry = "CREATE TABLE `orderlineentries`  (
            `id` int NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `orderCode` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `productCode` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `productQty` int NULL DEFAULT NULL,
            `productPrice` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addons` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `comboProducts` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `variantCode` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `totalPrice` decimal(10, 2) NULL DEFAULT NULL,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT 0,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp NULL DEFAULT NULL,
            `prodNames` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `tax` decimal(18, 2) NULL DEFAULT NULL,
            `taxAmount` decimal(18, 2) NULL DEFAULT NULL,
            `customizes` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `comboProductItemsName` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL, 
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `ordermaster`");
    $qry = "CREATE TABLE `ordermaster`  (
            `id` int(5) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `branchCode` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `tableSection` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `tableNumber` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `custCode` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `custPhone` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `custName` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `orderStatus` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            `totalpreparationTime` int(11) NULL DEFAULT NULL,
            `paymentMode` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `orderType` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `subtotal` decimal(10, 2) NULL DEFAULT NULL,
            `discountPer` decimal(18, 2) NULL DEFAULT NULL,
            `offerData` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `discount` decimal(18, 2) NULL DEFAULT NULL,
            `tax` decimal(18, 2) NULL DEFAULT NULL,
            `serviceCharges` decimal(18, 2) NULL DEFAULT NULL,
            `grandTotal` decimal(15, 2) NULL DEFAULT NULL,
            `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT 0,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `orderstatusmaster`");
    $qry = "CREATE TABLE `orderstatusmaster`  (
            `id` int(5) NOT NULL AUTO_INCREMENT,
            `statusSName` varchar(3) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `statusName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `productcategorymaster`");
    $qry = "CREATE TABLE `productcategorymaster`  (
            `id` int(5) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `categoryName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `categorySName` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            `icon` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `description` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT 0,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `productcombo`");
    $qry = "CREATE TABLE `productcombo`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `productComboName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `productCategoryCode` varchar(35) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `productComboPrice` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `taxAmount` decimal(18, 2) NULL DEFAULT NULL,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT NULL,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` datetime(0) NULL DEFAULT NULL,
            `productComboImage` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `productComboUrduName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `productComboHindiName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `productComboArabicName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `productFinalPrice` decimal(18, 2) NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `productcombolineentries`");
    $qry = "CREATE TABLE `productcombolineentries`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `productComboCode` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `productCode` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `productPrice` decimal(10, 2) NULL DEFAULT NULL,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT NULL,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` datetime(0) NULL DEFAULT NULL,
            `productTaxPrice` decimal(10, 2) NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `productextras`");
    $qry = "CREATE TABLE `productextras`  (
            `id` int(5) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `productCode` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `itemCode` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `itemQty` int(11) NULL DEFAULT NULL,
            `itemUom` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `itemPrice` decimal(18, 2) NULL DEFAULT NULL,
            `custPrice` decimal(18, 2) NULL DEFAULT NULL,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT 0,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `productmaster`");
    $qry = "CREATE TABLE `productmaster`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `productEngName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `productArbName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `productHinName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `productUrduName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `productCategory` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `productMethod` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `productPrice` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `productTaxGrp` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `costingMethod` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `productFixedCost` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `preparationTime` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `productCalories` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `productEngDesc` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `productArbDesc` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `productHinDesc` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `productUrduDesc` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `inActiveBranches` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT 0,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            `productImage` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `isAddOn` tinyint(1) NULL DEFAULT NULL,
            `numberOfPersonServed` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `subcategory` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `productsubcategorymaster`");
    $qry = "CREATE TABLE `productsubcategorymaster`  (
            `id` int(5) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `categoryCode` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `subcategoryName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `subcategorySName` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            `icon` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `description` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT 0,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `quotationentries`");
    $qry = "CREATE TABLE `quotationentries`  (
            `id` int(5) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `eventName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `date` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `peoples` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `subTotal` decimal(18, 2) NULL DEFAULT NULL,
            `discount` decimal(18, 2) NULL DEFAULT NULL,
            `taxAmount` decimal(18, 2) NULL DEFAULT NULL,
            `grandTotal` decimal(18, 2) NULL DEFAULT NULL,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT 0,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            `flag` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `remarkDate` timestamp(0) NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `quotationlineentries`");
    $qry = "CREATE TABLE `quotationlineentries`  (
            `id` int(5) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `quotationCode` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `categoryCode` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `subCategoryCode` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `productCode` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `qtyPerPerson` decimal(18, 2) NULL DEFAULT NULL,
            `pricePerPerson` decimal(18, 2) NULL DEFAULT NULL,
            `subTotal` decimal(18, 2) NULL DEFAULT NULL,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT 0,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            `flag` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `recipecard`");
    $qry = "CREATE TABLE `recipecard`  (
            `id` int(5) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `productCode` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `recipeDirection` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT 0,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `recipelineentries`");
    $qry = "CREATE TABLE `recipelineentries`  (
            `id` int(5) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `recipeCode` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `itemCode` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `unitCode` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `itemQty` decimal(18, 2) NULL DEFAULT NULL,
            `itemCost` decimal(18, 2) NULL DEFAULT NULL,
            `isCustomizable` int(1) NULL DEFAULT 0,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT 0,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `returnentries`");
    $qry = "CREATE TABLE `returnentries`  (
            `id` int(5) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `inwardCode` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `itemCode` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `returnQty` decimal(18, 2) NULL DEFAULT NULL,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT 0,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            `flag` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `rolesmaster`");
    $qry = "CREATE TABLE `rolesmaster`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `role` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT 0,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->insert("rolesmaster", ["code" => "R_1", "role" => "Admin", "isActive" => 1, "addIP" => $client['addIP'], "addDate" => $client['addDate']]);
    $this->db->insert("rolesmaster", ["code" => "R_2", "role" => "Manager", "isActive" => 1, "addIP" => $client['addIP'], "addDate" => $client['addDate']]);
    $this->db->insert("rolesmaster", ["code" => "R_3", "role" => "Accounts", "isActive" => 1, "addIP" => $client['addIP'], "addDate" => $client['addDate']]);
    $this->db->insert("rolesmaster", ["code" => "R_4", "role" => "Staff", "isActive" => 1, "addIP" => $client['addIP'], "addDate" => $client['addDate']]);
    $this->db->insert("rolesmaster", ["code" => "R_5", "role" => "Chef", "isActive" => 1, "addIP" => $client['addIP'], "addDate" => $client['addDate']]);
    $this->db->insert("rolesmaster", ["code" => "R_6", "role" => "Cashier", "isActive" => 1, "addIP" => $client['addIP'], "addDate" => $client['addDate']]);
    $this->db->insert("rolesmaster", ["code" => "R_7", "role" => "Store", "isActive" => 1, "addIP" => $client['addIP'], "addDate" => $client['addDate']]);
    $this->db->insert("rolesmaster", ["code" => "R_8", "role" => "Waiter", "isActive" => 1, "addIP" => $client['addIP'], "addDate" => $client['addDate']]);

    $this->db->query("DROP TABLE IF EXISTS `sectorzonemaster`");
    $qry = "CREATE TABLE `sectorzonemaster`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `branchCode` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            `zoneName` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT 0,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `settings`");
    $qry = "CREATE TABLE `settings`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `settingName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `settingValue` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            `messageTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `messageDescription` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = MyISAM AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->insert("settings", ["id" => 1, "code" => "STG_1", "settingName" => "Order service charges", "settingValue" => "0", "isActive" =>  1]);
    $this->db->insert("settings", ["id" => 2, "code" => "STG_2", "settingName" => "Waiting Time", "settingValue" => "180", "isActive" =>  1]);
    $this->db->insert("settings", ["id" => 3, "code" => "STG_3", "settingName" => "Email", "settingValue" => "", "isActive" =>  1]);
    $this->db->insert("settings", ["id" => 4, "code" => "STG_4", "settingName" => "Texxt SMS", "settingValue" => "", "isActive" =>  1]);

    $this->db->query("DROP TABLE IF EXISTS `smstemplates`");
    $qry = "CREATE TABLE `smstemplates`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `template` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `isActive` tinyint(1) NULL DEFAULT 1,
            `isDelete` tinyint(1) NULL DEFAULT 0,
            `addID` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` datetime(0) NULL DEFAULT NULL,
            `editID` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` datetime(0) NULL DEFAULT NULL,
            `deleteID` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` datetime(0) NULL DEFAULT NULL,
            `templateName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
            ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `stockinfo`");
    $qry = "CREATE TABLE `stockinfo`  (
            `id` int(5) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `itemCode` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `stock` decimal(18, 2) NULL DEFAULT NULL,
            `minimumStock` decimal(18, 2) NULL DEFAULT NULL,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT 0,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            `unitCode` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `branchCode` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `itemPrice` decimal(18, 2) NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `suppliermaster`");
    $qry = "CREATE TABLE `suppliermaster`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `supplierName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `arabicName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `companyName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `country` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `state` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `city` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `postalCode` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `tax` int(11) NULL DEFAULT NULL,
            `financialAccount` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `address` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT 0,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            `supplierImage` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `tablemaster`");
    $qry = "CREATE TABLE `tablemaster`  (
            `id` int(5) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `branchCode` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `zoneCode` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `tableNumber` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            `tableSeats` int(11) NULL DEFAULT NULL,
            `urlToken` varchar(12) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT 0,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `tablereservation`");
    $qry = "CREATE TABLE `tablereservation`  (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
            `customerCode` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            `customerMobile` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `branchCode` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `sectorCode` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `tableNumber` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `noOfPeople` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `resDate` timestamp(0) NULL DEFAULT NULL,
            `startTime` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `endTime` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `isActive` tinyint(1) NULL DEFAULT NULL,
            `isDelete` tinyint(1) NULL DEFAULT 0,
            `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `addDate` timestamp(0) NULL DEFAULT NULL,
            `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `editDate` timestamp(0) NULL DEFAULT NULL,
            `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
            `deleteDate` timestamp(0) NULL DEFAULT NULL,
            PRIMARY KEY (`code`) USING BTREE,
            UNIQUE INDEX `id`(`id`) USING BTREE
          ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `taxes`");
    $qry = "CREATE TABLE `taxes`  (
          `id` int(5) NOT NULL AUTO_INCREMENT,
          `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
          `taxName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
          `taxPer` int(11) NOT NULL,
          `isActive` tinyint(1) NULL DEFAULT NULL,
          `isDelete` tinyint(1) NULL DEFAULT 0,
          `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `addDate` timestamp(0) NULL DEFAULT NULL,
          `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `editDate` timestamp(0) NULL DEFAULT NULL,
          `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `deleteDate` timestamp(0) NULL DEFAULT NULL,
          PRIMARY KEY (`code`) USING BTREE,
          UNIQUE INDEX `id`(`id`) USING BTREE
        ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `taxgroupmaster`");
    $qry = "CREATE TABLE `taxgroupmaster`  (
          `id` int(5) NOT NULL AUTO_INCREMENT,
          `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
          `taxGroupName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
          `taxes` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
          `taxGroupRef` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `isActive` tinyint(1) NULL DEFAULT NULL,
          `isDelete` tinyint(1) NULL DEFAULT 0,
          `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `addDate` timestamp(0) NULL DEFAULT NULL,
          `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `editDate` timestamp(0) NULL DEFAULT NULL,
          `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `deleteDate` timestamp(0) NULL DEFAULT NULL,
          PRIMARY KEY (`code`) USING BTREE,
          UNIQUE INDEX `id`(`id`) USING BTREE
        ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `unitmaster`");
    $qry = "CREATE TABLE `unitmaster`  (
          `id` int(5) NOT NULL AUTO_INCREMENT,
          `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
          `unitName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `unitSName` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
          `unitDesc` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
          `isActive` tinyint(1) NULL DEFAULT NULL,
          `isDelete` tinyint(1) NULL DEFAULT 0,
          `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `addDate` timestamp(0) NULL DEFAULT NULL,
          `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `editDate` timestamp(0) NULL DEFAULT NULL,
          `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `deleteDate` timestamp(0) NULL DEFAULT NULL,
          PRIMARY KEY (`code`) USING BTREE,
          UNIQUE INDEX `id`(`id`) USING BTREE
        ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $this->db->query("DROP TABLE IF EXISTS `usermaster`");
    $qry = "CREATE TABLE `usermaster`  (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `code` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'x',
          `userBranchCode` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
          `userImage` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `userName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
          `userLang` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `userEmpNo` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `userEmail` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `userPassword` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `isActive` tinyint(1) NULL DEFAULT NULL,
          `isDelete` tinyint(1) NULL DEFAULT 0,
          `addID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `addIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `addDate` timestamp(0) NULL DEFAULT NULL,
          `editIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `editID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `editDate` timestamp(0) NULL DEFAULT NULL,
          `deleteID` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `deleteIP` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `deleteDate` timestamp(0) NULL DEFAULT NULL,
          `userRole` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `token` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
          `loginpin` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, 
          `invoicePreference` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'autocut',     
          PRIMARY KEY (`code`) USING BTREE,
          UNIQUE INDEX `id`(`id`) USING BTREE
        ) ENGINE = InnoDB AUTO_INCREMENT = 0 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
    $this->db->query($qry);

    $password = $client['password'];
    $username = "admin";
    $adminUser = [
      "code" => "USR" . date("y") . "_1",
      "name" => $client['name'],
      "userName" => $username,
      "userLang" => "ENGLISH",
      "userEmail" => $client['email'],
      "userPassword" => md5($password),
      "userRole" => "R_1",
      "isActive" => 1,
      "isDelete" => 0,
      "addID" => $client['addID'],
      "addIP" => $client['addIP'],
      "addDate" => date('Y-m-d H:i:s')
    ];
    $this->db->insert("usermaster", $adminUser);
    $userdetails = [
      "cmpcode" => $client['cmpcode'],
      "name" => $client['name'],
      "category" => $client['category'],
      "username" => "admin",
      "password" => $password
    ];
    $html_template = $this->load->view("mails/register", $userdetails, true);
    $this->sendemail->sendMailWithAttachment($client['email'], "Yay! Registration complete.", $html_template, []);
  }

  public function processing()
  {
    $this->frontend_template("payment/process", [], "Processing");
  }

  public function cancelled()
  {
    $this->frontend_template("payment/cancel", [], "Cancelled");
  }
}
