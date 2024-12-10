<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');
//date_default_timezone_set("Asia/Riyadh");
class ClientModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('planchecker');
	}
	public function getCliCode($companyCode)
	{
		$companyCode = strtoupper(strtolower($companyCode));
		$checkData = $this->db->select("code")->from("clients")->where("cmpcode", $companyCode)->where("category", "restaurant")->get()->row_array();
		if (!empty($checkData)) {
			$clientCode = $checkData['code'];
			return $clientCode;
		}
		return 'false';
	}
}