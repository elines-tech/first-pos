<?php

require(APPPATH . '/libraries/REST_Controller.php');

use Restserver\Libraries\REST_Controller;

class Payhook extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form', 'url', 'html');
        $this->load->library('form_validation');
    }

    public function callback_post()
    {
        $postData = $this->post();
        log_message("error", "payment_response => " . stripslashes(json_encode($postData)));
        return $this->response(array("status" => "200", "message" => "OK"), 200);
    }
}
