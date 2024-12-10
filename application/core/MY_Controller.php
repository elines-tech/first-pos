<?php
defined('BASEPATH') or exit('No direct script access allowed');
class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function backend(string $view, array $data, string $pageTitle = "")
    {
        $this->load->view("backend/template/header", ["pageTitle" => $pageTitle != "" ? $pageTitle : "KAEM Software"]);
        $this->load->view("backend/template/sidebar");
        $this->load->view("backend/$view", $data);
        $this->load->view("backend/template/footer");
    }

    public function frontend(string $view, array $data = [])
    {
        $this->load->view($view, $data);
    }

    public function frontend_template(string $view, array $data = [], string $pageTitle = "")
    {
        $this->load->view('header', ["pageTitle" => $pageTitle != "" ? $pageTitle : "KAEM Software"]);
        $this->load->view("$view", $data);
        $this->load->view('footer');
    }
}
