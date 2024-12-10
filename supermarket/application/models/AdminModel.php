<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');
class AdminModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('planchecker');
	}

	//select data by creating view
	function selectQuery($sel, $table, $cond = array(), $orderBy = array(), $join = array(), $joinType = array(), $like = array(), $limit = '', $offset = '', $groupByColumn = '', $extraCondition = "")
	{
		$this->db->select($sel, FALSE);
		$this->db->from($table);
		foreach ($cond as $k => $v) {
			if ($v != "")
				$this->db->where($k, $v);
		}
		if ($extraCondition != "") {
			$this->db->where($extraCondition);
		}
		foreach ($orderBy as $key => $val) {
			$this->db->order_by($key, $val);
		}
		$lc = 0;
		foreach ($like as $k => $v) {
			$val = explode("~", $v);
			if ($val[0] != "") {
				if ($lc == 0) {
					$this->db->like($k, $val[0], $val[1]);
					$lc++;
				} else {
					$this->db->or_like($k, $val[0], $val[1]);
				}
			}
		}
		foreach ($join as $key => $val) {
			if (!empty($joinType) && $joinType[$key] != "") {
				$this->db->join($key, $val, $joinType[$key]);
			} else {
				$this->db->join($key, $val);
			}
		}
		if ($limit != '')
			$this->db->limit($limit, $offset);
		$this->db->group_by($groupByColumn);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query;
		} else {
			return false;
		}
	}

	public function checkCompanySubscription($companyCode)
	{
		$companyCode = strtoupper(strtolower($companyCode));
		$checkData = $this->db->select("code")->from("clients")->where("cmpcode", $companyCode)->where("category", "supermarket")->get()->row_array();
		if (!empty($checkData)) {
			$clientCode = $checkData['code'];
			$this->storeExpiryDate($clientCode);
			return $clientCode;
		}
		return 'false';
	}

	public function getClientCode($companyCode)
	{
		$companyCode = strtoupper(strtolower($companyCode));
		$checkData = $this->db->select("code")->from("clients")->where("cmpcode", $companyCode)->where("category", "supermarket")->get()->row_array();
		if (!empty($checkData)) {
			$clientCode = $checkData['code'];
			return $clientCode;
		}
		return 'false';
	}

	public function addNew($transaction, $tblname, $initial)
	{
		$this->db->insert($tblname, $transaction);
		$nowdate = date('Y-m-d H:i:s');
		$currentId = $this->db->insert_id();
		//Update Code with update query
		$hashCode = $initial . date("y") . "_" . $currentId;
		$this->db->query("UPDATE `" . $tblname . "` SET `code` = '" . $hashCode . "', `addDate` = '" . $nowdate . "' WHERE `id` = '" . $currentId . "'");
		if ($this->db->affected_rows() > 0) {
			$res = $hashCode;
		} else {
			$res = 'false';
		}
		return $res;
	}

	public function doEditWithField($data, $tblname, $field, $code)
	{
		$nowdate = date('Y-m-d H:i:s');
		$editDate = array('editDate' => $nowdate);
		$data = array_merge($data, $editDate);
		$this->db->where($field, $code);
		$this->db->update($tblname, $data);
		if ($this->db->affected_rows() > 0) {
			$res = 'true';
		} else {
			$res = 'false';
		}
		return $res;
	}


	public function storeExpiryDate($clientCode)
	{
		$today = date('Y-m-d H:i:00');
		$this->db->select('*');
		$this->db->from('payments');
		$this->db->where('clientCode', $clientCode);
		$this->db->where('status', 'ACTIVE');
		$this->db->where('type!=', "addon");
		$this->db->where("'$today' between startDate and expiryDate");
		$this->db->order_by("expiryDate", "ASC");
		$result = $this->db->get();
		if ($result->num_rows() > 0) {
			$data = $result->result();
			$count = count($data);
			$expiryDate = $data[$count - 1]->expiryDate;
			$this->planchecker->modifyexpiry($clientCode, $expiryDate, "ACTIVE");
			return "ACTIVE";
		} else {
			$this->planchecker->modifyexpiry($clientCode, "", "EXPIRED");
			return "EXPIRED";
		}
	}

	public function lastPackage($clientCode)
	{
		$this->db->select('*');
		$this->db->from('payments');
		$this->db->where('clientCode', $clientCode);
		$this->db->where('type!=', "addon");
		$this->db->order_by("id", "DESC");
		$this->db->limit(1);
		$result = $this->db->get()->row_array();
		if (!empty($result)) {
			$plan = $result;
			//print_r($plan);
			$this->db->select("IFNULL(SUM(addonUsers),0) as addonUsers,IFNULL(SUM(addonBranches),0) as addonBranches");
			$this->db->where("refCode", $plan['code']);
			$this->db->where("type", "addon");
			$addons = $this->db->get("payments")->row_array();
			if (!empty($addons)) {
				$plan['addonUsers'] = $plan['addonUsers'] + $addons['addonUsers'];
				$plan['addonBranches'] = $plan['addonBranches'] + $addons['addonBranches'];
			}
			return $plan;
		}
		return [];
	}

	public function expirePreviousPlans(string $clientCode, string $newCode)
	{
		$this->db->where('clientCode', $clientCode)->where("code!=", $newCode)->update("payments", ["status" => "EXPIRED"]);
		return true;
	}

	public function get_payment_by_similar_invoice(string $invoice)
	{
		return $this->db->query("SELECT * FROM payments WHERE receiptId LIKE '%$invoice%' LIMIT 1")->row_array();
	}

	public function updateQuery(string $table, array $data, array $where)
	{
		$this->db->where($where)->update($table, $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	public function getClientDatabase($clientcode)
	{
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

	public function customSelect(string $columns, string $tablename, array $where, int $returnType)
	{
		switch ($returnType) {
			case 0:
				# return single row array
				return $this->db->select($columns)->from($tablename)->where($where)->get()->row_array();
				break;
			case 1:
				# return result array of multiple rows
				return $this->db->select($columns)->from($tablename)->where($where)->get()->result_array();
				break;
			case 2:
				# return result object of multiple rows
				return $this->db->select($columns)->from($tablename)->where($where)->get()->result();
		}
	}

	public function get_subscriber_max_branches($clientCode)
	{
		$today = date('Y-m-d H:i');
		$this->db->select('*');
		$this->db->from('payments');
		$this->db->where('clientCode', $clientCode);
		$this->db->where('type!=', "addon");
		$this->db->where("'$today' BETWEEN startDate AND expiryDate");
		$this->db->order_by("id", "DESC");
		$this->db->limit(1);
		$result = $this->db->get()->row_array();
		if (!empty($result)) {
			$plan = $result;
			$total = $plan['defaultBranches'] + $plan['addonBranches'];
			$this->db->select("IFNULL(SUM(addonBranches),0) as addonBranches");
			$this->db->where("refCode", $plan['code']);
			$this->db->where("type", "addon");
			$addons = $this->db->get("payments")->row_array();
			if (!empty($addons)) {
				$total = $total + $addons['addonBranches'];
			}
			return $total;
		}
		return 0;
	}

	public function get_subscriber_max_users($clientCode)
	{
		$today = date('Y-m-d H:i');
		$this->db->select('*');
		$this->db->from('payments');
		$this->db->where('clientCode', $clientCode);
		$this->db->where('type!=', "addon");
		$this->db->where("'$today' BETWEEN startDate AND expiryDate");
		$this->db->order_by("id", "DESC");
		$this->db->limit(1);
		$result = $this->db->get()->row_array();
		if (!empty($result)) {
			$plan = $result;
			$total = $plan['defaultUsers'] + $plan['addonUsers'];
			$this->db->select("IFNULL(SUM(addonUsers),0) as addonUsers");
			$this->db->where("refCode", $plan['code']);
			$this->db->where("type", "addon");
			$addons = $this->db->get("payments")->row_array();
			if (!empty($addons)) {
				$total = $total + $addons['addonUsers'];
			}
			return $total;
		}
		return 0;
	}
}
