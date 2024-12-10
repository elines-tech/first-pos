<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Order extends CI_Controller
{
    var $session_key;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('form', 'url', 'html');
        $this->load->model('GlobalModel');
        $this->session_key = $this->session->userdata('key' . SESS_KEY);
        if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
            redirect('Login', 'refresh');
        }
    }

    public function listRecords()
    {
        $data = [];
        $header['pageTitle'] = "All Orders";
        $this->load->view('dashboard/order-kitchen/header', $header);
        $this->load->view('dashboard/order-kitchen/list', $data);
        $this->load->view('dashboard/order-kitchen/footer');
    }
}
