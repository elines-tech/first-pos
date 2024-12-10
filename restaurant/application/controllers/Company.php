<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Company extends CI_Controller
{
    var $session_key;
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form', 'url', 'html');
        $this->load->model('GlobalModel');
        $this->load->model("AdminModel");
        $this->load->library('form_validation');
        $this->session_key = $this->session->userdata('key' . SESS_KEY);
        if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
            redirect('Login', 'refresh');
        }
        $res = $this->GlobalModel->checkActiveSubscription();
        if ($res == "EXPIRED") {
            redirect('package', 'refresh');
        }
        $rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
        if ($rolecode != 'R_1') {
            $this->load->view('errors/norights.php');
        }
    }

    public function index()
    {
        $cmpcode = $this->session->userdata['logged_in' . $this->session_key]['cmpcode'];
        $data['companymaster'] = $this->GlobalModel->selectQuery("*", "companymaster", ["code" => $cmpcode]);
        $this->load->view('dashboard/header');
        $this->load->view('dashboard/company/index', $data);
        $this->load->view('dashboard/footer');
    }

    public function update()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $date = date('d-m-y h:i:s');
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $cmpcode = $this->session->userdata['logged_in' . $this->session_key]['cmpcode'];

        $code = trim($this->input->post("code"));

        $this->form_validation->set_rules('crno', 'CR No', 'trim|required|min_length[10]|max_length[18]');
        $this->form_validation->set_rules('companyname', 'Company Name', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('vatno', 'Vat No', 'trim|required|min_length[15]|max_length[18]');
        $this->form_validation->set_rules('buildingNo', 'Building Number', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('streetName', 'Street name', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('district', 'District', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('city', 'City', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('postalCode', 'Postal Code', 'trim|required|integer|min_length[4]|max_length[6]');
        $this->form_validation->set_rules('country', 'Country', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['companymaster'] = $this->GlobalModel->selectQuery("*", "companymaster", ["code" => $cmpcode]);
            $this->load->view('dashboard/header');
            $this->load->view('dashboard/company/index', $data);
            $this->load->view('dashboard/footer');
        } else {
            $companyname = ucwords(trim($this->input->post("companyname")));
            $vatno = strtoupper(strtolower(trim($this->input->post("vatno"))));
            $crno = strtoupper(strtolower(trim($this->input->post("crno"))));
            $address['buildingNo'] = $this->input->post("buildingNo");
            $address['streetName'] = $this->input->post("streetName");
            $address['district'] = $this->input->post("district");
            $address['city'] = $this->input->post("city");
            $address['postalCode'] = $this->input->post("postalCode");
            $address['country'] = $this->input->post("country");
            $finaladress = json_encode($address);
            $data = [
                "companyname" => $companyname,
                "crno" => $crno,
                "vatno" => $vatno,
                "address" => $finaladress
            ];
            $uploadDir = "upload/company/$cmpcode";
            if (!file_exists($uploadDir)) mkdir($uploadDir, 0755, false);
            if (!empty($_FILES['cmpLogo']['name'])) {
                $filepro = $this->GlobalModel->directQuery("SELECT cmpLogo FROM companymaster WHERE `code` = '" . $code . "'");
                if (!empty($filepro)) {
                    $myImg = $filepro[0]['cmpLogo'];
                    if ($myImg != "" && file_exists($myImg)) unlink($myImg);
                }
                $tmpFile = $_FILES['cmpLogo']['tmp_name'];
                $ext = pathinfo($_FILES['cmpLogo']['name'], PATHINFO_EXTENSION);
                $filename = $uploadDir . '/' . $code . '-' . time() . '.' . $ext;
                move_uploaded_file($tmpFile, $filename);
                if (file_exists($filename)) {
                    $data['cmpLogo'] = $filename;
                }
            }
            $this->GlobalModel->doEditWithField($data, "companymaster", "code", $code);
            sleep(1);
            $this->AdminModel->doEditWithField($data, "clients", "code", $code);
            $txt = $code . " - " . $addID . " Company details is updated.";
            $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
            $this->GlobalModel->activity_log($activity_text);
            $response['status'] = true;
            $response['message'] = 'Company details are updated successfully';
            $this->session->set_flashdata('message', json_encode($response));
            redirect("company");
        }
    }
}
