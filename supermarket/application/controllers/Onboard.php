<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Salla\ZATCA\GenerateQrCode;
use Salla\ZATCA\Tags\InvoiceDate;
use Salla\ZATCA\Tags\InvoiceTaxAmount;
use Salla\ZATCA\Tags\InvoiceTotalAmount;
use Salla\ZATCA\Tags\Seller;
use Salla\ZATCA\Tags\TaxNumber;

class Onboard extends CI_Controller
{
    var $session_key;
    public function __construct()
    {
        parent::__construct();
        $this->session_key = $this->session->userdata('key' . SESS_KEY);
        if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
            redirect('Login', 'refresh');
        }
        $this->load->model('GlobalModel');
        $this->load->model('AdminModel');
        $this->load->library("sendemail");
    }

    public function index()
    {
        $cmpcode = $this->GlobalModel->getCompcode();
        $col = "payments.*";
        $condition = ["clientCode" => $cmpcode];
        $orderBy = ["payments.id" => "ASC"];
        $join = $joinType = $like = array();
        $data['subscription'] = $this->AdminModel->selectQuery($col, "payments", $condition, $orderBy, $join, $joinType, $like, 1, 0);
        $data['company'] = $this->GlobalModel->selectQuery("*", "companymaster", ["code" => $cmpcode]);
        $this->load->view('dashboard/onboard/detail', $data);
    }

    public function save()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $date = date('d-m-y h:i:s');
        $config = array(
            array(
                'field' => 'crno',
                'label' => 'Cr No',
                'rules' => 'trim|required|min_length[10]|max_length[18]',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                    'min_length' => '%s must be of 10 digigts',
                    'max_length' => '%s must be of 18 digigts',
                ),
            ),
            array(
                'field' => 'vatno',
                'label' => 'VAT No',
                'rules' => 'trim|required|min_length[15]|max_length[18]',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                    'min_length' => '%s must be of 15 digigts',
                    'max_length' => '%s must be of 18 digigts',
                ),
            ),
            array(
                'field' => 'buildingNo',
                'label' => 'Building No',
                'rules' => 'trim|required|min_length[1]|max_length[10]',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                    'min_length' => 'Minimum 1 characters are needed',
                    'max_length' => 'Maximum 10 character are allowed'
                ),
            ),
            array(
                'field' => 'streetName',
                'label' => 'Street Name No',
                'rules' => 'trim|required|min_length[2]|max_length[100]',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                    'min_length' => 'Minimum 2 characters are needed',
                    'max_length' => 'Maximum 100 character are allowed'
                ),
            ),
            array(
                'field' => 'district',
                'label' => 'District',
                'rules' => 'trim|required|min_length[2]|max_length[100]',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                    'min_length' => 'Minimum 2 characters are needed',
                    'max_length' => 'Maximum 100 character are allowed'
                ),
            ),
            array(
                'field' => 'city',
                'label' => 'City',
                'rules' => 'trim|required|min_length[2]|max_length[100]',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                    'min_length' => 'Minimum 2 characters are needed',
                    'max_length' => 'Maximum 100 character are allowed'
                ),
            ),
            array(
                'field' => 'postalCode',
                'label' => 'Postal Code',
                'rules' => 'trim|required|min_length[4]|max_length[10]',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                    'min_length' => 'Minimum 4 characters are needed',
                    'max_length' => 'Maximum 10 character are allowed'
                ),
            ),
            array(
                'field' => 'country',
                'label' => 'Country',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'You must provide a %s.',
                )
            )
        );
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() == FALSE) {
            $cmpcode = $this->GlobalModel->getCompcode();
            $col = "payments.*";
            $condition = ["clientCode" => $cmpcode];
            $orderBy = ["payments.id" => "ASC"];
            $join = $joinType = $like = array();
            $data['subscription'] = $this->AdminModel->selectQuery($col, "payments", $condition, $orderBy, $join, $joinType, $like, 1, 0);
            $data['company'] = $this->GlobalModel->selectQuery("*", "companymaster", ["id" => 1]);
            $this->load->view('dashboard/onboard/detail', $data);
        } else {
            $compCode = $this->GlobalModel->getCompcode();
            $address = [
                "buildingNo" => trim($this->input->post('buildingNo')),
                "streetName" => trim($this->input->post('streetName')),
                "district" => trim($this->input->post('district')),
                "city" => trim($this->input->post('city')),
                "postalCode" => trim($this->input->post('postalCode')),
                "country" => trim($this->input->post('country')),
            ];
            $data = array(
                "vatno" => trim($this->input->post('vatno')),
                "regno" => trim($this->input->post('regno')),
                "crno" => trim($this->input->post('crno')),
                "address" => stripslashes(json_encode($address)),
                'isActive' => 1,
                'onBoard' => '0',
                "editID" => $compCode,
                'editIP' => $ip,
                'editDate' => date('Y-m-d H:i:s')
            );
            $masterdata = $data;
            $uploadDir = 'upload/company/' . $compCode . '/logo';
            if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
            if (!empty($_FILES['cmpLogo']['name'])) {
                $tmpFile = $_FILES['cmpLogo']['tmp_name'];
                $ext = pathinfo($_FILES['cmpLogo']['name'], PATHINFO_EXTENSION);
                $filename = $uploadDir . '/logo-' . time() . '.' . $ext;
                move_uploaded_file($tmpFile, $filename);
                if (file_exists($filename)) {
                    $data['cmpLogo'] = $filename;
                    $masterdata['cmpLogo'] = base_url($filename);
                }
            }
            $result = $this->GlobalModel->doEditWithField($data, 'companymaster', "code", $compCode);
            if ($result != 'false') {
                $this->AdminModel->updateQuery("clients", $masterdata, ["code" => $compCode]);
                $activity_text =  $date . "\t" . $ip . "\t" . $compCode . "\t updated company details after account creation \t";
                $this->GlobalModel->activity_log($activity_text);
                $response['status'] = true;
                $response['message'] = 'Documents added successfully.';
                $this->session->set_flashdata('message', json_encode($response));
                //send invoice mail and pdf
                $this->sendinvoice($compCode);
                redirect('dashboard/listRecords', 'refresh');
            } else {
                $response['status'] = false;
                $response['message'] = "Failed To documents. Please try again";
                $this->session->set_flashdata('message', json_encode($response));
                redirect('onboard', 'refresh');
            }
        }
    }

    public function sendinvoice($clientCode)
    {
        $company = $this->AdminModel->customSelect("companydetails.*", "companydetails", ["companydetails.id" => 1], 0);
        $data['company'] = $company;
        $data['client'] =  $this->AdminModel->customSelect("clients.*", "clients", ["clients.code" => $clientCode], 0);
        $payment =  $this->AdminModel->selectQuery("payments.*", "payments", ["payments.clientCode" => $clientCode], ["payments.id" => "ASC"]);
        if ($payment) {
            $clientemail = $data['client']['email'];
            $payment = $payment->result_array()[0];
            if ($payment['type'] != "trial") {
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
    }
}
