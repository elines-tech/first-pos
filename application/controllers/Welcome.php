<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Salla\ZATCA\GenerateQrCode;
use Salla\ZATCA\Tags\InvoiceDate;
use Salla\ZATCA\Tags\InvoiceTaxAmount;
use Salla\ZATCA\Tags\InvoiceTotalAmount;
use Salla\ZATCA\Tags\Seller;
use Salla\ZATCA\Tags\TaxNumber;



class Welcome extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("GlobalModel");
        $this->load->library("Sendemail");
    }

    public function index()
    {
        $data['subscriptionmaster'] = $this->GlobalModel->selectQuery("subscriptionmaster.*", "subscriptionmaster", ["subscriptionmaster.isActive" => 1]);
        $this->frontend_template('index', $data, "Welcome");
    }

    public function getPlan()
    {
        $code = trim($this->input->get('packageCode'));
        $data['subscription'] = $this->GlobalModel->selectQuery("subscriptionmaster.*", "subscriptionmaster", ["subscriptionmaster.code" => $code]);
        $data['duration'] = trim($this->input->get('duration'));
        $this->laod->view('configureplan', $data);
    }

    public function createfile()
    {
        $rightsFolder = FCPATH . "restaurant/application/rights/CLT22_1";
        copy(FCPATH . "assets/projconfig/restaurant/R_1.json", $rightsFolder . "/R_1.json");
    }

    public function paymentpdf()
    {
        $company = $this->db->select("companydetails.*")->where("companydetails.id", 1)->get("companydetails")->row_array();
        $data['company'] = $company;
        $data['client'] =  $this->db->select("clients.*")->from("clients")->where("clients.code", 'CLT22_1')->get()->row_array();
        $payment =  $this->GlobalModel->selectQuery("payments.*", "payments", ["payments.clientCode" => 'CLT22_1']);
        if ($payment) {
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
            $this->load->view("print/invoicebill", $data);
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
            $attanchment = [FCPATH . $path];
            $this->sendemail->sendMailWithAttachment("abhiharshe1191@gmail.com", "Invoice", "Please check the invoice as below", $attanchment);
        }
    }

    public function genqr()
    {
        $date = date('Y-m-dTH:i:sZ', strtotime('2022-11-19 11:10:26'));
        echo $date;
        echo '<br>';
        $generatedString = GenerateQrCode::fromArray([
            new Seller('Salla'), // seller name        
            new TaxNumber('1234557483003'), // seller tax number
            new InvoiceDate($date), // invoice date as Zulu ISO8601 @see https://en.wikipedia.org/wiki/ISO_8601
            new InvoiceTotalAmount('100.71'), // invoice total amount
            new InvoiceTaxAmount('15.71') // invoice tax amount

        ])->toBase64();
        echo $generatedString;

        $generatedString = GenerateQrCode::fromArray([
            new Seller('Salla'), // seller name        
            new TaxNumber('1234567891'), // seller tax number
            new InvoiceDate($date), // invoice date as Zulu ISO8601 @see https://en.wikipedia.org/wiki/ISO_8601
            new InvoiceTotalAmount('100.00'), // invoice total amount
            new InvoiceTaxAmount('15.00') // invoice tax amount
            // TODO :: Support others tags
        ])->toTLV();
        echo $generatedString;
        echo '<br>';

        $displayQRCodeAsBase64 = GenerateQrCode::fromArray([
            new Seller('Salla'), // seller name        
            new TaxNumber('1234567891'), // seller tax number
            new InvoiceDate('2021-07-12T14:25:09Z'), // invoice date as Zulu ISO8601 @see https://en.wikipedia.org/wiki/ISO_8601
            new InvoiceTotalAmount($date), // invoice total amount
            new InvoiceTaxAmount('15.00') // invoice tax amount
            // TODO :: Support others tags
        ])->render();

        echo '<img src="' . $displayQRCodeAsBase64 . '" alt="QR Code" />';
    }
}
