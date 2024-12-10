<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RoleWiseRights extends CI_Controller
{
	var $session_key;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form', 'url', 'html');
		$this->load->model('GlobalModel');
		$this->session_key = $this->session->userdata('key' . SESS_KEY);
		if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
			redirect('Login', 'refresh');
		}
		$res = $this->GlobalModel->checkActiveSubscription();
		if ($res == "EXPIRED") {
			redirect('package', 'refresh');
		}
		$rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		$this->rights = $this->GlobalModel->getMenuRights('7.8', $rolecode);
		if ($this->rights == '') {
			$this->load->view('errors/norights.php');
		}
	}

	public function listRecords()
	{
		$data['roles'] = $this->GlobalModel->selectQuery('rolesmaster.*', 'rolesmaster', array("rolesmaster.code !=" => 'R_5'));
		$this->load->view('dashboard/header');
		$this->load->view('dashboard/rolewiserights/list', $data);
		$this->load->view('dashboard/footer');
	}

	public function getMenuList()
	{
		$cmpcode = $this->GlobalModel->getCompcode();
		$role = $this->input->post("role");
		if (file_exists(APPPATH . 'rights/menu.json')) {
			$filecontents = file_get_contents(APPPATH . 'rights/menu.json');
			$menuJson = json_decode($filecontents, true);
			$filename = APPPATH . 'rights/' . $cmpcode . '/' . $role . '.json';
			$rightJson = [];
			if (file_exists($filename)) {
				$rightscontents = file_get_contents($filename);
				$rightJson = json_decode($rightscontents, true);
			}
			$menuHtml = '<table class="table table-bordered" style="width:100%;overflow-y:scroll" id="rights_table">
			<thead>
				<tr>
					<th>Sr No</th>
					<th>Menu/Submenus</th>
					<th>All</th>
					<th>View</th>
					<th>Insert</th>
					<th>Update</th>
					<th>Delete</th>
					<th>Default Page</th>
				</tr>
			</thead>
			<tbody>';
			$i = 0;
			$subCheck = 0;
			foreach ($menuJson as $menu) {
				$i++;
				$menuHtml .= '<tr><td>' . $i . '</td><td colspan="7"><b>' . $menu['name'] . '</b><input type="checkbox" style="width:20px;height:20px;margin-left:8px;vertical-align: inherit;" onchange="checkAllSubcheck(' . count($menu['submenu']) . ',' . $i . ',' . $subCheck . ')" name="allsubcheck' . $i . '" id="allsubcheck' . $i . '"> <b>(All Submenus)</b></td></tr>';
				foreach ($menu['submenu'] as $sub) {
					$subCheck++;
					$checked = '';
					$view = $delete = $insert = $update = $allcheck = '';
					if (!empty($rightJson)) {
						foreach ($rightJson as $rt) {
							if ($rt['menu'] == $sub['id']) {
								if ($rt['view'] == 1) $view = 'checked';
								if ($rt['insert'] == 1) $insert = 'checked';
								if ($rt['update'] == 1) $update = 'checked';
								if ($rt['delete'] == 1) $delete = 'checked';
								if ($rt['default'] == 1) $checked = 'checked';
								if ($rt['view'] == 1 && $rt['insert'] == 1 && $rt['update'] == 1 && $rt['delete'] == 1) $allcheck = 'checked';
							}
						}
					} else {
						if ($subCheck == 1) $checked = 'checked';
					}
					$menuHtml .= '<tr id="row' . $subCheck . '" align="center"><td></td>
						<td>' . $sub['name'] . '<input type="hidden" id="menu' . $subCheck . '" name="menu' . $subCheck . '" value="' . $sub['id'] . '"></td>
						<td><input type="checkbox" style="width:22px;height:22px" onchange="checkAll(' . $subCheck . ')" name="allcheck' . $subCheck . '" ' . $allcheck . ' id="allcheck' . $subCheck . '"></td>
						<td><input type="checkbox" class ="cb-element' . $subCheck . '" onchange="validateAllCheck(' . $subCheck . ')" style="width:22px;height:22px" name="view' . $subCheck . '" ' . $view . ' id="view' . $subCheck . '"></td>
						<td><input type="checkbox" class ="cb-element' . $subCheck . '" onchange="validateAllCheck(' . $subCheck . ')" style="width:22px;height:22px" name="insert' . $subCheck . '" ' . $insert . ' id="insert' . $subCheck . '"></td>
						<td><input type="checkbox" class ="cb-element' . $subCheck . '" onchange="validateAllCheck(' . $subCheck . ')" style="width:22px;height:22px" name="update' . $subCheck . '" ' . $update . ' id="update' . $subCheck . '"></td>
						<td><input type="checkbox" class ="cb-element' . $subCheck . '" onchange="validateAllCheck(' . $subCheck . ')" style="width:22px;height:22px" name="delete' . $subCheck . '" ' . $delete . ' id="delete' . $subCheck . '"></td>
						<td><input type="radio" style="width:22px;height:22px" name="default" id="default' . $subCheck . '" ' . $checked . '></td>
					</tr>';
				}
			}
			$menuHtml .= '</tbody>
			</table>';
			$response['status'] = true;
			$response['menuHtml'] = $menuHtml;
		} else {
			$response['status'] = false;
		}
		echo json_encode($response);
	}

	public function saveMenu()
	{
		$cmpcode = $this->GlobalModel->getCompcode();
		$role = $this->input->post("role");
		$finalRoleArray = $this->input->post("finalRoleArray");
		$filename = APPPATH . 'rights/' . $cmpcode . '/' . $role . '.json';
		if (file_put_contents($filename, $finalRoleArray)) {
			$response['status'] = true;
		} else {
			$response['status'] = false;
		}
		echo json_encode($response);
	}
}
