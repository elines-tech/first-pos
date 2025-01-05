<?php
class GiftcardReport extends CI_Controller
{
    var $session_key;
	protected $rolecode,$branchCode; 
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('form', 'url', 'html');
        $this->load->model('GlobalModel');
        $this->session_key = $this->session->userdata('key' . SESS_KEY);
        if (!isset($this->session->userdata['logged_in' . $this->session_key]['code'])) {
            redirect('Login', 'refresh');
        }
        $this->rolecode = $this->session->userdata['logged_in' . $this->session_key]['rolecode'];
		$this->branchCode = $this->session->userdata['logged_in' . $this->session_key]['userBranch'];
        $this->rights = $this->GlobalModel->getMenuRights('6.2',  $this->rolecode);
        if ($this->rights == '') {
            $this->load->view('errors/norights.php');
        }
    }


    public function list() 
    {
        if ($this->rights != '' && $this->rights['view'] == 1) {
            $this->load->view('dashboard/header');            
            $this->load->view('dashboard/report/giftcardsell');
            $this->load->view('dashboard/footer');
        } else {
            $this->load->view('errors/norights.php');
        }
    }

    public function getListRecords()
    {
        $dataCount = 0;
        $data = array();
        $branchCode = $this->input->get('branchCode');
        $fromDate = $this->input->get('fromDate');
        $toDate = $this->input->get('toDate');
        $export = $this->input->get('export');
        $search = $limit = $offset = '';
        $srno = 1;
        $totalAmount = $draw = 0;
        if ($export == 0) {
            $search = $this->input->GET("search")['value'];
            $limit = $this->input->GET("length");
            $offset = $this->input->GET("start");
            $srno = $_GET['start'] + 1;
            $draw = $_GET["draw"];
        }

        $tableName = "salegiftcard";
        $orderColumns = array("salegiftcard.code,salegiftcard.cardDetails,salegiftcard.custName,salegiftcard.custPhone,salegiftcard.custEmail,salegiftcard.expiryDate,salegiftcard.totalPrice,salegiftcard.addDate,salegiftcard.cardCount");
        $condition = array();
        $orderBy = array('salegiftcard.id' => 'DESC');
        $joinType = $join = $groupByColumn = [];
        $extraCondition = '';
        if ($fromDate != '' && $toDate != '') {
            $extraCondition = " (salegiftcard.addDate between '" . $fromDate . " 00:00:00' and '" . $toDate . " 23:59:59')";
        }
        $like = array("salegiftcard.cardCount" => $search . "~both","salegiftcard.cardDetails" => $search . "~both","salegiftcard.code" => $search . "~both", "salegiftcard.custName" => $search . "~both", "salegiftcard.custPhone" => $search . "~both", "salegiftcard.custEmail" => $search . "~both", "salegiftcard.totalPrice" => $search . "~both");
        $Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
        if ($Records) {
            foreach ($Records->result() as $row) {
                $card = json_decode($row->cardDetails, true);
                $cardDetails = '<div>
                    <div>Title : ' . $card['title'] . '</div>
                    <div>Discount : ' . $card['discount'] . '</div>
                    <div>Price : ' . $card['price'] . '</div>                    
                </div>';
                $data[] = array(
                    $srno,
                    $row->code,
                    $cardDetails,
                    date('d/m/Y', strtotime($row->addDate)),
                    date('d/m/Y', strtotime($row->expiryDate)),
                    $row->custName,
                    $row->custPhone,
                    number_format($row->totalPrice, 2, '.', ''),
                    $row->cardCount,
                    '<a id="saveDefaultButton" href="' . base_url('giftcardReport/view/' . $row->code) . '" class="btn btn-sm btn-success">View<a>'
                );
                $srno++;
            }
            $dataCount = sizeof($this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, array(), '', '', $groupByColumn, $extraCondition)->result());
            $totalResult = $this->GlobalModel->selectQuery("IFNULL(SUM(salegiftcard.totalPrice),0) as tp", $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
            if ($totalResult) {
                $totalAmount = $totalResult->result()[0]->tp;
            }
        }
        $output = array(
            "draw"            =>     intval($draw),
            "recordsTotal"    =>     $dataCount,
            "recordsFiltered" =>     $dataCount,
            "data"            =>     $data,
            "totalAmount"     =>     number_format($totalAmount, 2, '.', '')
        );
        echo json_encode($output);
    }

    public function view()
    {
        $code = $this->uri->segment('3');
        $data['giftSell'] = $this->GlobalModel->selectQuery("salegiftcard.*", "salegiftcard", array("salegiftcard.code" => $code));
        $data['giftSellLine'] = $this->GlobalModel->selectQuery("salegiftcardlineentries.*", "salegiftcardlineentries", array("salegiftcardlineentries.salecardCode" => $code));
        if ($this->rights != '' && $this->rights['view'] == 1) {
            $this->load->view('dashboard/header');            
            $this->load->view('dashboard/report/giftcardsellview', $data);
            $this->load->view('dashboard/footer');
        } else {
            $this->load->view('errors/norights.php');
        }
    }
}
