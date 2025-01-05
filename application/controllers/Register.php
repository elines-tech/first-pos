<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Register extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library("sendemail");
    }

    /**
     * Free trial plan purchase and register new customer
     */
    public function index()
    {
        $this->session->unset_userdata('companyCode');
        $this->frontend_template('register', [], "Create Account");
    }

    public function create()
    {
        $config = array(
            array(
                'field' => 'category',
                'label' => 'Category',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'companyname',
                'label' => 'Name of company',
                'rules' => 'trim|required|min_length[4]|max_length[50]',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                    'min_length' => 'Minimum 4 characters are needed',
                    'max_length' => 'Maximum 50 character are allowed'
                ),
            ),
            array(
                'field' => 'name',
                'label' => 'name',
                'rules' => 'trim|required|min_length[4]|max_length[50]',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                    'min_length' => 'Minimum 4 characters are needed',
                    'max_length' => 'Maximum 50 character are allowed'
                ),
            ),
            array(
                'field' => 'crno',
                'label' => 'Cr No',
                'rules' => 'trim|required|integer',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                    'integer' => 'Invalid %s.',
                ),
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|is_unique[clients.email]',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                    'is_unique' => 'This %s is already taken'
                ),
            ),
            array(
                'field' => 'phone',
                'label' => 'Phone',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                    'is_unique' => 'This %s is already taken'
                ),
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|required|min_length[8]|max_length[20]',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                    'min_length' => 'Minimum 8 characters are needed',
                    'max_length' => 'Maximum 20 character are allowed'
                ),
            ),
            array(
                'field' => 'confirm_password',
                'label' => 'Password Confirmation',
                'rules' => 'trim|required|matches[password]',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                    'matches' => 'Password & Confirm Password does not match.'
                ),
            ),

        );

        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() == FALSE) {
            //$this->session->set_flashdata("err", "Please enter valid information"); 
            $this->frontend_template('register', [], "Create Account");
        } else {
            $country = trim($this->input->post('countrycode'));
            $phone = trim($this->input->post("phone"));
            $clientPhone = $country . $phone;
            $duplicatePhone = $this->GlobalModel->selectQuery("clients.id", "clients", ["phone" => $clientPhone]);
            if ($duplicatePhone && $duplicatePhone->num_rows() > 0) {
                $this->session->set_flashdata("err", "Cannot create multiple accounts with same phone number. Please enter another phone number");
                $this->frontend_template('register', [], "Create Account");
            } else {
                $password = trim($this->input->post('password'));
                $client = [
                    "category" => trim($this->input->post('category')),
                    "companyname" => trim($this->input->post('companyname')),
                    "name" => trim($this->input->post('name')),
                    "email" => trim($this->input->post('email')),
                    "phone" => $clientPhone,
                    "password" => $password,
                    "registerDate" => date('Y-m-d H:i:s'),
                    "isActive" => 1,
                    "onBoard" => 1,
                    "crno" => trim($this->input->post('crno')),
                    "addIP" => $_SERVER['REMOTE_ADDR']
                ];
                $resultCode = $this->GlobalModel->addNew($client, "clients", "CLT");
                if ($resultCode != "false") {
                    $genCmpCode = $this->generateCompanyCode();
                    $this->db->where('code', $resultCode)->update("clients", ["cmpcode" => $genCmpCode, "addID" => $resultCode]);
                    $this->session->set_flashdata("done", "Your trail account is created successfully. Please be patient.");
                    $this->session->set_userdata('companyCode', $resultCode);
                    $this->session->mark_as_temp('companyCode', 300);
                    redirect('register/success');
                } else {
                    $this->session->set_flashdata("err", "Failed to create your account. Please try again");
                    $this->frontend_template('register', [], "Create Account");
                }
            }
        }
    }

    /**
     * Register new user with paid plan purchase 
     */
    public function new()
    {
        $this->session->unset_userdata('companyCode');
        $array = [];
        $post = $_POST;
        $data['category'] = $post['category'];
        $data['trialPlan'] = $post['trialPlan'];
        foreach ($post as $key => $value) {
            $array[$key] = $value;
        }
        unset($array['category']);
        unset($array['freeTrail']);
        $data['plandetails'] = json_encode($array);
        $this->frontend_template('planregister', $data, "Create Account");
    }

    public function save()
    {
        $post = $_POST;
        $data['category'] = $post['category'];
        $data['trialPlan'] = $post['trialPlan'];
        $data['plandetails'] = $post['plandetails'];
        $config = array(
            array(
                'field' => 'category',
                'label' => 'Catergory',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'companyname',
                'label' => 'Name of compnay',
                'rules' => 'trim|required|min_length[4]|max_length[50]',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                    'min_length' => 'Minimum 4 characters are needed',
                    'max_length' => 'Maximum 50 character are allowed'
                ),
            ),
            array(
                'field' => 'name',
                'label' => 'name',
                'rules' => 'trim|required|min_length[4]|max_length[50]',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                    'min_length' => 'Minimum 4 characters are needed',
                    'max_length' => 'Maximum 50 character are allowed'
                ),
            ),
            array(
                'field' => 'crno',
                'label' => 'Cr No',
                'rules' => 'trim|required|integer',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                    'integer' => 'Invalid %s.',
                ),
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|is_unique[clients.email]',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                    'is_unique' => 'This %s is already taken'
                ),
            ),
            array(
                'field' => 'phone',
                'label' => 'Phone',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                    'is_unique' => 'This %s is already taken'
                ),
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|required|min_length[8]|max_length[20]',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                    'min_length' => 'Minimum 8 characters are needed',
                    'max_length' => 'Maximum 20 character are allowed'
                ),
            ),
            array(
                'field' => 'confirmpassword',
                'label' => 'Password Confirmation',
                'rules' => 'trim|required|matches[password]',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                    'matches' => 'Password do not match'
                ),
            )
        );
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata("err", "Please enter valid information");
            $this->frontend_template('planregister', $data, "Create Account");
        } else {
            $country = trim($this->input->post('countrycode'));
            $phone = trim($this->input->post("phone"));
            $clientPhone = $country . $phone;
            $duplicatePhone = $this->GlobalModel->selectQuery("clients.id", "clients", ["phone" => $clientPhone]);
            if ($duplicatePhone && $duplicatePhone->num_rows() > 0) {
                $this->session->set_flashdata("err", "Cannot create multiple accounts with same phone number. Please enter another phone number");
                $this->frontend_template('planregister', $data, "Create Account");
            } else {
                $password = trim($this->input->post('password'));
                $email = trim($this->input->post("email"));
                $this->db->where('temp_clients.email', $email)->or_where('temp_clients.phone', $phone)->delete('temp_clients');
                $client = [
                    "category" => trim($this->input->post('category')),
                    "companyname" => trim($this->input->post('companyname')),
                    "name" => trim($this->input->post('name')),
                    "email" => trim($this->input->post('email')),
                    "plandetails" => trim($this->input->post('plandetails')),
                    "phone" => trim($country . $phone),
                    "password" => $password,
                    "onBoard" => 1,
                    "crno" => trim($this->input->post('crno')),
                    "registerDate" => date('Y-m-d H:i:s'),
                    "isActive" => 1,
                    "addIP" => $_SERVER['REMOTE_ADDR']
                ];
                $resultCode = $this->GlobalModel->addNew($client, "temp_clients", "TMP");
                if ($resultCode != "false") {
                    $this->session->set_flashdata("done", "Account created! Now make the subscription payment to complete the proccess.");
                    $this->session->set_userdata('regcompanyCode', $resultCode);
                    $this->session->mark_as_temp('companyCode', 900);
                    redirect('payment/index');
                } else {
                    $this->session->set_flashdata("err", "Failed to create your account. Please try again");
                    $this->frontend_template('planregister', [], "Create Account");
                }
            }
        }
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

    public function success()
    {
        $this->frontend_template('registersuccess', ["companyCode" => "123456"], "Registration successfull");
    }

    public function setup()
    {
        $cmpCode = trim($this->input->post("companyCode"));
        $clientResult = $this->GlobalModel->selectQuery("*", "clients", ["code" => $cmpCode]);
        if ($clientResult) {
            $client = $clientResult->result_array()[0];
            $ip = $_SERVER['REMOTE_ADDR'];
            $this->db->query('use ' . MAIN_DBNAME);
            //$dbName = strtolower('myvegizc_' . $client['cmpcode']);
            $dbName = strtolower(MAIN_DB_NAME .'_'. $client['cmpcode']);
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
            //$result = curl_exec($curl);
           // $loginTokenResult = json_decode($result);
          //  $cpsess = $loginTokenResult->security_token;
           // $query2 = "https://myvegiz.com:2083$cpsess/json-api/cpanel?cpanel_jsonapi_user=user&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=MysqlFE&cpanel_jsonapi_func=createdb&db=" . $dbName;
         //   curl_setopt($curl, CURLOPT_URL, $query2);
          //  $resultcurl = curl_exec($curl);
            if ($client['category'] == "supermarket") {
                $receiptId = "KSSUP" . date('dmyHis');
                $plan = $this->db->select("subscriptionmaster.*")->where('subscriptionmaster.code', 'PAK_1')->get("subscriptionmaster")->row_array();
                if (!empty($plan)) {
                    $freeDays = $plan['freetrialday'];
                    $usersCount = $plan['noofusers'];
                    $branchCount = $plan['noofbranch'];
                    $subscription =  [
                        "subscriptionCode" => 'PAK_1',
                        "clientCode" => $client['code'],
                        "amount" => '0.00',
                        "paymentDate" => date('Y-m-d H:i:s'),
                        "startDate" => date('Y-m-d H:i:s'),
                        "expiryDate" => date('Y-m-d 23:59:59', strtotime(' + ' . $freeDays . ' DAYS')),
                        "status" => "ACTIVE",
                        "isActive" => 1,
                        "addID" => $client['code'],
                        "addIP" => $client['addIP'],
                        "addDate" => date('Y-m-d H:i:s'),
                        "defaultUsers" => $usersCount,
                        "defaultBranches" => $branchCount,
                        "addonUsers" => '0',
                        "addonBranches" => '0',
                        "isFreeTrial" => '1',
                        "paymentStatus" => "SUCCESS",
                        "category" => "supermarket",
                        "period" => "month",
                        "addonUserPrice" => '0',
                        "addonBranchPrice" => '0',
                        "type" => "trial",
                        "perBranchPrice" => '0',
                        "perUserPrice" => '0',
                        "receiptId" => $receiptId
                    ];

                    $mainPaymentCode = $this->GlobalModel->addNew($subscription, 'payments', 'PMT');
                    if ($mainPaymentCode != "false") {
                        $this->maskSupermarket($dbName, $client);
                    }
                }
            } else {
                $receiptId = "KSRES" . date('dmyHis');
                $plan = $this->db->select("subscriptionmaster.*")->where('subscriptionmaster.code', 'PAK_2')->get("subscriptionmaster")->row_array();
                if (!empty($plan)) {
                    $freeDays = $plan['freetrialday'];
                    $usersCount = $plan['noofusers'];
                    $branchCount = $plan['noofbranch'];
                    $subscription =  [
                        "subscriptionCode" => 'PAK_2',
                        "clientCode" => $client['code'],
                        "amount" => '0.00',
                        "paymentDate" => date('Y-m-d H:i:s'),
                        "startDate" => date('Y-m-d H:i:s'),
                        "expiryDate" => date('Y-m-d 23:59:59', strtotime(' + ' . $freeDays . ' DAYS')),
                        "status" => "ACTIVE",
                        "isActive" => 1,
                        "addID" => $client['code'],
                        "addIP" => $client['addIP'],
                        "addDate" => date('Y-m-d H:i:s'),
                        "defaultUsers" => $usersCount,
                        "defaultBranches" => $branchCount,
                        "addonUsers" => '0',
                        "addonBranches" => '0',
                        "isFreeTrial" => '1',
                        "paymentStatus" => "SUCCESS",
                        "category" => "restaurant",
                        "period" => "month",
                        "addonUserPrice" => '0',
                        "addonBranchPrice" => '0',
                        "type" => "trial",
                        "perBranchPrice" => '0',
                        "perUserPrice" => '0',
                        "receiptId" => $receiptId
                    ];
                    $mainPaymentCode = $this->GlobalModel->addNew($subscription, 'payments', 'PMT');
                    if ($mainPaymentCode != "false") {
                        $this->maskRestaurant($dbName, $client);
                    }
                }
            }
            $res['err'] = 200;
            $res['msg'] = "Setup complete successfully. Your application is ready to use. Please check your mail for further instructions";
        } else {
            $res['err'] = 300;
            $res['msg'] = "Failed to install and setup your application. Please click on try again button to initiate setup again";
        }
        $this->session->unset_userdata('companyCode');
        echo json_encode($res);
        exit;
    }

    public function maskSupermarket(string $dbName, array $client)
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
            `barcodeText` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
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
            `userBranchCode` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
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
        $this->sendemail->sendMailOnly($client['email'], "Yay! Registration complete.", $html_template);
    }

    public function maskRestaurant(string $dbName, array $client)
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
                `startDate` date NULL DEFAULT NULL,
                `endDate` date NULL DEFAULT NULL,
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
                ) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;";
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
                `totalTax` decimal(18, 2) NULL DEFAULT NULL,
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
                `tax` decimal(18, 2) NULL DEFAULT NULL,
                `taxamount` decimal(18, 2) NULL DEFAULT NULL,
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
        $this->db->insert("settings", ["id" => 4, "code" => "STG_4", "settingName" => "Text SMS", "settingValue" => "", "isActive" =>  1]);

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
        $this->sendemail->sendMailOnly($client['email'], "Yay! Registration complete.", $html_template);
    }
}
