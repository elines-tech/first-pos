<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');
//date_default_timezone_set("Asia/Riyadh");
class ForgotpasswordModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('planchecker');
	}
	public function getClientData($clientcode){
		$this->db->select("databasemaster.databaseName");
		$this->db->from("clients");
		$this->db->join("databasemaster", "clients.code = databasemaster.companyCode");
		$this->db->where("clients.code", $clientcode);
		$getResult = $this->db->get();
		if ($getResult->num_rows() > 0) {
			$dbName = $getResult->result_array()[0]['databaseName'];
			return $dbName;
		}
		return false;
	}
}