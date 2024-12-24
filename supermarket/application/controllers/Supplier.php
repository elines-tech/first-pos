<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supplier extends CI_Controller
{
    var $session_key;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('GlobalModel');
        $this->session_key = $this->session->userdata('key' . SESS_KEY);
        if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
            redirect('Login', 'refresh');
        }
        $res = $this->GlobalModel->checkActiveSubscription();
        if ($res == "EXPIRED") {
            redirect('package', 'refresh');
        }
        $dbName = $this->session->userdata['current_db' . $this->session_key];
        $this->db->query('use ' . $dbName);
        $rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
        $this->rights = $this->GlobalModel->getMenuRights('3.1', $rolecode);
        if ($this->rights == '') {
            $this->load->view('errors/norights.php');
        }
    }

    public function listRecords()
    {
        if ($this->rights != '' && $this->rights['view'] == 1) {
			$data['insertRights'] = $this->rights['insert'];
            $this->load->view('dashboard/header');
            $this->load->view('dashboard/supplier/list',$data);
            $this->load->view('dashboard/footer');
        } else {
            $this->load->view('errors/norights.php');
        }
    }

    public function getList()
    {
        $tableName = 'suppliermaster';
        $orderColumns = array("suppliermaster.*");
        $search = $this->input->GET("search")['value'];
        $condition = array();
        $orderBy = array('suppliermaster' . '.id' => 'DESC');
        $joinType = array();
        $join = array();
        $groupByColumn = array();
        $limit = $this->input->GET("length");
        $offset = $this->input->GET("start");
        $like = array();
        $extraCondition = " (suppliermaster.isDelete=0 OR suppliermaster.isDelete IS NULL)";
        $like = array("suppliermaster.code" => $search . "~both","suppliermaster.supplierName" => $search . "~both", "suppliermaster.arabicName" => $search . "~both", "suppliermaster.companyName" => $search . "~both", "suppliermaster.email" => $search . "~both", "suppliermaster.country" => $search . "~both", "suppliermaster.state" => $search . "~both", "suppliermaster.city" => $search . "~both", "suppliermaster.postalCode" => $search . "~both", "suppliermaster.phone" => $search . "~both","suppliermaster.countryCode" => $search . "~both");
        $Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
        $srno = $offset + 1;
        $data = array();
        $dataCount = 0;
        if ($Records) {
            foreach ($Records->result() as $row) {
                $actionHtml = '<div class="d-flex">';
                if ($this->rights != '' && $this->rights['view'] == 1) {
                    $actionHtml .= '<a id="view" href="' . base_url() . 'supplier/view/' . $row->code . '" class="btn btn-sm btn-success cursor_pointer m-1" ><i id="edt" title="View" class="fa fa-eye" ></i></a>';
                }
                if ($this->rights != '' && $this->rights['update'] == 1) {
                    $actionHtml .= '<a id="edit" class="btn btn-sm btn-info cursor_pointer m-1" href="' . base_url() . 'supplier/edit/' . $row->code . '" ><i id="edt" title="Edit" class="fa fa-pencil"></i></a>';
                }
                if ($this->rights != '' && $this->rights['delete'] == 1) {
                    $actionHtml .= '<a id="delete" class="btn btn-sm btn-danger delete_id cursor_pointer m-1" id="' . $row->code . '"  ><i title="Delete" id="edt" class="fa fa-trash" ></i></a>';
                }
                $actionHtml .= '</div>';
                if ($row->isActive == "1") {
                    $status = " <span class='badge bg-success'>Active</span>";
                } else {
                    $status = " <span class='badge bg-danger'>Inactive</span>";
                }
                $data[] = array(
                    $srno,
                    $row->code,
                    $row->supplierName,
                    $row->arabicName,
                    $row->companyName,
                    $row->email,
                    $row->countryCode . $row->phone,
                    $status,
                    $actionHtml
                );
                $srno++;
            }
            $dataCount = sizeof($this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, array(), '', '', '', $extraCondition)->result_array());
        }
        $output = array(
            "draw" => intval($_GET["draw"]),
            "recordsTotal" => $dataCount,
            "recordsFiltered" => $dataCount,
            "data" => $data
        );
        echo json_encode($output);
    }

    public function add()
    {
        if ($this->rights != '' && $this->rights['insert'] == 1) {
            $this->load->view('dashboard/header');
            $this->load->view('dashboard/supplier/add');
            $this->load->view('dashboard/footer');
        } else {
            $this->load->view('errors/norights.php');
        }
    }

    public function save()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $date = date('d-m-y h:i:s');
        $cmpcode = $this->GlobalModel->getCompcode();
        $supplier = $this->input->post("suppliername");
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];

        $this->form_validation->set_rules('suppliername', 'Supplier Name', 'trim|required');
        $this->form_validation->set_rules('email', 'email', 'trim|is_unique[suppliermaster.email]', array('is_unique' => 'Email already exist.'));
        $this->form_validation->set_rules('arabicname', 'Supplier Arabic Name', 'trim|required');
        $this->form_validation->set_rules('companyname', 'Company Name', 'trim|required');
        $this->form_validation->set_rules('country', 'Country Name', 'trim');
        $this->form_validation->set_rules('state', 'State', 'trim');
        $this->form_validation->set_rules('city', 'City Name', 'trim');
        $this->form_validation->set_rules('pincode', 'Pincode', 'trim');
        $this->form_validation->set_rules('phone', 'phone', 'trim|required|is_unique[suppliermaster.phone]', array('is_unique' => 'Phone number already exist.'));
        //$this->form_validation->set_rules('tax', 'Tax', 'trim|required');
        $this->form_validation->set_rules('financial', 'Financial', 'trim');
        $this->form_validation->set_rules('address', 'Address', 'trim');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('dashboard/header');
            $this->load->view('dashboard/supplier/add');
            $this->load->view('dashboard/footer');
        } else {
            $active = 0;
            if ($this->input->post('isActive') == 'on') {
                $active = 1;
            }
            $data = array(
                'supplierName' => $this->input->post("suppliername"),
                'arabicName' => $this->input->post("arabicname"),
                'companyName' =>    $this->input->post("companyname"),
                'email' =>    $this->input->post("email"),
                'country' =>    $this->input->post("country"),
                'state' =>    $this->input->post("state"),
                'city' => $this->input->post("city"),
                'postalcode' => $this->input->post("pincode"),
                'phone' => $this->input->post("phone"),
                'countryCode' => $this->input->post("countryCode"),
                //'tax' => $this->input->post("tax"),
                'financialAccount' => $this->input->post("financial"),
                'address' => $this->input->post("address"),
                'isActive' => $active,
                'addID' => $addID,
                'addIP' => $ip,
            );
            $result = $this->GlobalModel->addNew($data, 'suppliermaster', 'SUP');
            if ($result != false) {

                $filename =  "";
                $uploadDir = "upload/supplierImage/$cmpcode";
                if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
                if (!empty($_FILES['supplierImage']['name'])) {
                    $tmpFile = $_FILES['supplierImage']['tmp_name'];
                    $ext = pathinfo($_FILES['supplierImage']['name'], PATHINFO_EXTENSION);
                    $filename = $uploadDir . '/' . $result . '-' . time() . '.' . $ext;
                    move_uploaded_file($tmpFile, $filename);
                    if (file_exists($filename)) {
                        $subData = array(
                            'supplierImage' => $filename
                        );
                        $filedoc = $this->GlobalModel->doEdit($subData, 'suppliermaster', $result);
                    }
                }
                $txt = $result . " - " . $supplier . " Supplier is added.";
                $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
                $this->GlobalModel->activity_log($activity_text);

                $response['status'] = true;
                $response['message'] = 'Supplier Added successfully.';
            } else {
                $response['status'] = false;
                $response['message'] = "Failed To Add Supplier";
            }
            $this->session->set_flashdata('message', json_encode($response));
            redirect('supplier/listRecords', 'refresh');
        }
    }

    public function edit()
    {
        if ($this->rights != '' && $this->rights['update'] == 1) {
            $code = $this->uri->segment(3);
            $data['supplierData'] = $this->GlobalModel->get_data($code, 'suppliermaster');
            $this->load->view('dashboard/header');
            $this->load->view('dashboard/supplier/edit', $data);
            $this->load->view('dashboard/footer');
        } else {
            $this->load->view('errors/norights.php');
        }
    }

    public function update()
    {
        $code = $this->input->post("code");
        $supplier = $this->input->post("suppliername");

        $email = $this->input->post("email");
        $phone = $this->input->post("phone");
        $ip = $_SERVER['REMOTE_ADDR'];
        $date = date('d-m-y h:i:s');

        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $cmpcode = $this->session->userdata['logged_in' . $this->session_key]['cmpcode'];

        $table = "suppliermaster";
        $where = ["email" => $email, "code!=" => $code];
        $resultEmail =  $this->GlobalModel->hasSimilarRecords($where, $table);
        //$resultEmail = $this->db->query("SELECT * FROM suppliermaster WHERE email='" . $email . "' AND code !='" . $code . "' and (`isDelete` IS NULL OR `isDelete`='0')");
        if ($resultEmail) {
            $data['error_message'] = 'Email already exist.';
            $data['supplierData'] = $this->GlobalModel->get_data($code, 'suppliermaster');
            $this->load->view('dashboard/header');
            $this->load->view('dashboard/supplier/edit', $data);
            $this->load->view('dashboard/footer');
        } else {
            $where1 = ["phone" => $phone, "code!=" => $code];
            $resultPhone =  $this->GlobalModel->hasSimilarRecords($where1, $table);
            //$resultPhone = $this->db->query("SELECT * FROM suppliermaster WHERE phone='" . $phone . "' AND code !='" . $code . "' and (`isDelete` IS NULL OR `isDelete`='0')");
            if ($resultPhone) {
                $data['error_message'] = 'Phone number already exist.';
                $data['supplierData'] = $this->GlobalModel->get_data($code, 'suppliermaster');
                $this->load->view('dashboard/header');
                $this->load->view('dashboard/supplier/edit', $data);
                $this->load->view('dashboard/footer');
            } else {
                $this->form_validation->set_rules('suppliername', 'Supplier Name', 'trim|required');
                $this->form_validation->set_rules('email', 'email', 'trim');
                $this->form_validation->set_rules('arabicname', 'Supplier Arabic Name', 'trim|required');
                $this->form_validation->set_rules('companyname', 'Company Name', 'trim|required');
                $this->form_validation->set_rules('country', 'Country Name', 'trim');
                $this->form_validation->set_rules('state', 'State', 'trim');
                $this->form_validation->set_rules('city', 'City Name', 'trim');
                $this->form_validation->set_rules('pincode', 'Pincode', 'trim');
                $this->form_validation->set_rules('phone', 'phone', 'trim|required');
                // $this->form_validation->set_rules('tax', 'Tax', 'trim|required');
                $this->form_validation->set_rules('financial', 'Financial', 'trim');
                $this->form_validation->set_rules('address', 'Address', 'trim');
                if ($this->form_validation->run() == FALSE) {
                    $data['error_message'] = '* Fields are Required!';
                    $this->load->view('dashboard/header');
                    $this->load->view('dashboard/supplier/add', $data);
                    $this->load->view('dashboard/footer');
                } else {
                    $active = 0;
                    if ($this->input->post('isActive') == 'on') {
                        $active = 1;
                    }
                    $data = array(
                        'supplierName' => $this->input->post("suppliername"),
                        'arabicName' => $this->input->post("arabicname"),
                        'companyName' =>    $this->input->post("companyname"),
                        'email' =>    $this->input->post("email"),
                        'country' =>    $this->input->post("country"),
                        'state' =>    $this->input->post("state"),
                        'city' => $this->input->post("city"),
                        'postalcode' => $this->input->post("pincode"),
                        'phone' => $this->input->post("phone"),
                        'countryCode' => $this->input->post("countryCode"),
                        // 'tax' => $this->input->post("tax"),
                        'financialAccount' => $this->input->post("financial"),
                        'address' => $this->input->post("address"),
                        'isActive' => $active,
                        'editID' => $addID,
                        'editIP' => $ip,
                    );
                    $result = $this->GlobalModel->doEdit($data, 'suppliermaster', $code);

                    $filedoc = false;
                    $filename = '';
                    $uploadDir = "upload/supplierImage/$cmpcode";
                    if (!file_exists($uploadDir)) mkdir($uploadDir, 0755, false);
                    if (!empty($_FILES['supplierImage']['name'])) {
                        $filepro = $this->GlobalModel->directQuery("SELECT supplierImage FROM suppliermaster WHERE`code` = '" . $code . "'");
                        if (!empty($filepro)) {
                            $myImg = $filepro[0]['supplierImage'];
                            if ($myImg != "" && file_exists($myImg))  unlink($myImg);
                        }
                        $tmpFile = $_FILES['supplierImage']['tmp_name'];
                        $ext = pathinfo($_FILES['supplierImage']['name'], PATHINFO_EXTENSION);
                        $filename = $uploadDir . '/' . $code . '-' . time() . '.' . $ext;
                        move_uploaded_file($tmpFile, $filename);
                        if (file_exists($filename)) {
                            $subData = array(
                                'supplierImage' => $filename
                            );
                            $filedoc = $this->GlobalModel->doEdit($subData, 'suppliermaster', $code);
                        }
                    }

                    if ($result == true || $filedoc == true) {
                        $txt = $code . " - " . $supplier . "Supplier is updated.";
                        $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
                        $this->GlobalModel->activity_log($activity_text);
                        $response['status'] = true;
                        $response['message'] = 'Supplier Updated successfully.';
                    } else {
                        $response['status'] = false;
                        $response['message'] = "Failed To Update Supplier";
                    }
                    $this->session->set_flashdata('message', json_encode($response));
                    redirect('supplier/listRecords', 'refresh');
                }
            }
        }
    }

    public function view()
    {
        if ($this->rights != '' && $this->rights['view'] == 1) {
            $code = $this->uri->segment(3);
            $data['supplierData'] = $this->GlobalModel->get_data($code, 'suppliermaster');
            $this->load->view('dashboard/header');
            $this->load->view('dashboard/supplier/view', $data);
            $this->load->view('dashboard/footer');
        } else {
            $this->load->view('errors/norights.php');
        }
    }

    public function delete()
    {
        if ($this->rights != '' && $this->rights['delete'] == 1) {
            $date = date('d-m-y h:i:s');
            $code = $this->input->post('code');
            $ip = $_SERVER['REMOTE_ADDR'];
            $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
            $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
            $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
            $txt = $code . " Supplier is deleted.";
            $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
            $this->GlobalModel->activity_log($activity_text);
            $data = array('isDelete' => 1, 'deleteID' => $addID, 'deleteIP' => $ip, 'deleteDate' => date('Y-m-d H:i:s'));
            $resultData = $this->GlobalModel->doEdit($data, 'suppliermaster', $code);
            echo $this->GlobalModel->delete($code, 'suppliermaster');
        } else {
            $this->load->view('errors/norights.php');
        }
    }
}
