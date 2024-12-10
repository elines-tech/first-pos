<?php

class Pos extends CI_Controller
{
    private $session_key;
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form', 'url', 'html');
        $this->load->model('GlobalModel');
        $this->load->model("PosorderModel");
        $this->load->library('form_validation');
        $this->session_key = $this->session->userdata('key' . SESS_KEY);
        if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
            redirect('Login', 'refresh');
        }
        $res = $this->GlobalModel->checkActiveSubscription();
        if ($res == "EXPIRED") {
            redirect('package', 'refresh');
        }
    }

    public function index()
    {
        $userCode = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $data['draftOrders'] = $this->PosorderModel->fetch_draft_orders($userCode);
        $this->load->view('pos/order', $data);
    }
}
