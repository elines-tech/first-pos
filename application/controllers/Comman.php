<?php
header('Access-Control-Allow-Origin: *');
defined('BASEPATH') or exit('No direct script access allowed');

class Comman extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("GlobalModel");
    }
    
    public function getPlan()
    {
        $code = trim($this->input->get('packageCode'));
        $data['subscription'] = $this->GlobalModel->selectQuery("subscriptionmaster.*", "subscriptionmaster", ["subscriptionmaster.code" => $code]);
        $data['duration'] = trim($this->input->get('duration'));
        $this->load->view('configureplan', $data);
    }

    public function trymail()
    {
        $this->load->library("sendemail");
        $userdetails = [
            "cmpcode" => "VALJK",
            "name" => "Alex",
            "category" => "restaurant",
            "username" => "admin",
            "password" => "password"
        ];
        $html_template = $this->load->view("mails/register", $userdetails, true);
        $res = $this->sendemail->sendMailOnly("harsheabhishek19@outlook.in", "Yay! Registration complete.", $html_template);
        print_r($res);
    }
}
