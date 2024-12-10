<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Order extends CI_Controller
{
	var $session_key;
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
		$res = $this->GlobalModel->checkActiveSubscription();
        if ($res == "EXPIRED") {
            redirect('package', 'refresh');
        }
	}

	public function listRecords()
	{
		$data['tableData'] = $this->GlobalModel->selectActiveData('tablemaster');
		$this->load->view('dashboard/commonheader');
		$this->load->view('dashboard/order/list',$data);
		$this->load->view('dashboard/footer');
	}
    
	public function add()
	{
		$data['tableCode']='';
		$categories = $this->GlobalModel->selectActiveData('categorymaster');
		$data['tableData'] = $this->GlobalModel->selectActiveData('tablemaster');
		$data['categories'] = $this->GlobalModel->selectActiveData('categorymaster');
		$data['products'] = $this->GlobalModel->selectActiveData('productmaster');
		if($categories){
			foreach($categories->result() as $cat){
				$data[$cat->code] = $this->GlobalModel->selectActiveDataByField('productCategory',$cat->code,'productmaster');
			}
		}
		$this->load->view('dashboard/commonheader');
		$this->load->view('dashboard/order/add',$data);
		$this->load->view('dashboard/footer');

	}
    
	public function deleteCartProduct(){
		$cartCode = $this->input->post("cartCode");
		$delete = $this->GlobalModel->deleteForever($cartCode,'ordercart');
		if($delete=='true'){
			$response['status']=true;
		}else{
			$response['status']=false;
		}
		echo json_encode($response);
	}
    
	public function book()
	{
		$tableNumber = $this->uri->segment(3);
		$tableDetails = $this->GlobalModel->selectQuery('tablemaster.code','tablemaster',array('tablemaster.tableNumber'=>$tableNumber,'tablemaster.isActive'=>1));
		if($tableDetails){
			$data['tableCode'] = $tableDetails->result()[0]->code;
			$data['tableNumber'] = $tableNumber;
			$categories = $this->GlobalModel->selectActiveData('categorymaster');
			$data['tableData'] = $this->GlobalModel->selectActiveData('tablemaster');
			$data['categories'] = $this->GlobalModel->selectActiveData('categorymaster');
			$data['products'] = $this->GlobalModel->selectActiveData('productmaster');
			if($categories){
				foreach($categories->result() as $cat){
					$data[$cat->code] = $this->GlobalModel->selectActiveDataByField('productCategory',$cat->code,'productmaster');
				}
			}
			$this->load->view('dashboard/commonheader');
			$this->load->view('dashboard/order/add',$data);
			$this->load->view('dashboard/footer');
		}
	}
	
	public function getOrderLists(){
		$orderHtml='';
		$tableCode=$this->input->post('tableCode');
		$joinType=array('tablemaster'=>'inner','customermaster'=>'inner');
		$join = array('tablemaster'=>'tablemaster.code=ordermaster.tableCode','customermaster'=>'customermaster.code=ordermaster.customerCode');
		$extraCondition = " ordermaster.orderStatus in('PND','RTS')";
		$orderDetails = $this->GlobalModel->selectQuery('ordermaster.*,tablemaster.tableNumber,customermaster.name','ordermaster',array('ordermaster.tableCode'=>$tableCode,'ordermaster.orderStatus'=>'PND','ordermaster.isActive'=>1),array('ordermaster.id'=>'DESC'),$join,$joinType,[],"","","",$extraCondition);
		if($orderDetails){
			$data['orderDetails'] = $orderDetails;
			$cartHtml = $this->load->view('dashboard/order/myorders',$data,true);
			$response['orderHtml'] = $cartHtml;
		}else{
			$response['orderHtml']='';
		}
		echo json_encode($response);
	}
    
	public function getOrderDetails(){
		$orderHtml='';
		$orderCode =$this->input->post('orderCode');
		$joinType=array('tablemaster'=>'inner','customermaster'=>'inner');
		$join = array('tablemaster'=>'tablemaster.code=ordermaster.tableCode','customermaster'=>'customermaster.code=ordermaster.customerCode');
		$orderDetails = $this->GlobalModel->selectQuery('ordermaster.*,tablemaster.tableNumber,customermaster.name','ordermaster',array('ordermaster.code'=>$orderCode,'ordermaster.isActive'=>1),array('ordermaster.id'=>'DESC'),$join,$joinType);
		if($orderDetails){
			$data['orderData'] = $orderDetails->result()[0];
			$cartHtml = $this->load->view('dashboard/order/orderdetails',$data,true);
			$response['status'] = true;
			$response['orderHtml'] = $cartHtml;
		}else{
			$response['status']=false;
			$response['orderHtml']='';
		}
		echo json_encode($response);
	}
    
	public function getOrderLineEntries()
    {
        $orderCode = $this->input->get('orderCode');
        $noPic = $this->input->get('noPic');
        $tableName = 'orderlineentries';
        $orderColumns = array("orderlineentries.*,productmaster.productEngName,productmaster.productImage");
        $condition = array('orderlineentries.orderCode' => $orderCode);
        $orderBy = array('orderlineentries.id' => 'desc');
        $joinType = array('productmaster' => 'inner');
        $join = array('productmaster' => 'productmaster.code=orderlineentries.productCode');
        $groupByColumn = array();
        $limit = $this->input->GET("length");
        $offset = $this->input->GET("start");
        $srno = $offset + 1;
        $addonText = '';
        $extraCondition = "orderlineentries.isActive=1";
        $like = array();
        $data = array();
        $Records = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, $limit, $offset, $groupByColumn, $extraCondition);
        if ($Records) {
            foreach ($Records->result() as $row) {
                $addonText = '';
				
                if ($row->addonsCode != '' && $row->addonsCode != NULL) {
                    $row->addonsCode = rtrim($row->addonsCode, ',');
                    $savedaddonsCodes = explode(',', $row->addonsCode);
                    foreach ($savedaddonsCodes as $addon) {
						
                        $joinType1 = array('customizedcategory' => 'inner');
                        $condition1 = array('customizedcategorylineentries.code' => $addon);
                        $join1 = array('customizedcategory' => "customizedcategory.code=customizedcategorylineentries.customizedCategoryCode");
                        $getAddonDetails = $this->GlobalModel->selectQuery("customizedcategory.categoryTitle,customizedcategory.categoryType,customizedcategorylineentries.subCategoryTitle,customizedcategorylineentries.price", "customizedcategorylineentries", $condition1, array(), $join1, $joinType1, array(), array(), '', array(), '');
                        if ($getAddonDetails) {
                            foreach ($getAddonDetails->result() as $ad) {
								$prevCategory='';
								
								if($prevCategory!=$ad->categoryTitle){
									$prevCategory = $ad->categoryTitle;
									$mainCategory = $ad->categoryTitle;
									$addonText .= '<ul>
										<li style="text-align:left"><b>' . $ad->categoryTitle . ' - ' . ucfirst($ad->categoryType) .
										'</b>';
								}
                                if ($ad->categoryType == 'product') {
                                    $productCode = $ad->subCategoryTitle;
                                    $productName = $this->GlobalModel->selectDataByField('code',  $productCode, 'productmaster');
                                    $productTitle = $productName->result_array()[0]['productEngName'];
                                    $addonText .= '<ul>
                                    <li style="text-align:left">' .  $productTitle . ' - ' . $ad->price . '</li>
                                     </ul>';
                                } else {
                                    $addonText .= '<ul style="margin-left:-15px">
										          <li style="text-align:left">' . $ad->subCategoryTitle . ' - ' . $ad->price . '</li>';
										if($prevCategory==$ad->categoryTitle){
									     $addonText .=   '</ul>';
										}
                                }
									if($prevCategory==$ad->categoryTitle){
									$addonText .= '</li>';
								
									$addonText .= '</ul>';
									$prevCategory=$ad->categoryTitle;
								}
                            }
                        }
                    }
                }
                $start = '<div class="d-flex align-items-center">';
                $end = ' <h5 class="m-b-0 font-16 font-medium">' . $row->productEngName . '</h5></div></div>';
                $itemPhotoCheck = $row->productImage;
                if ($itemPhotoCheck != "") {
                    $itemPhoto = base_url($itemPhotoCheck);
                    $photo = '<div class=""><img src="' . $itemPhoto . '?' . time() . '" alt="user" class="circle" width="50"></div><div class="ms-4">';
                    $itemName = $start . $photo . $end;
                    $data[] = array($srno, $itemName . '<br>' . $addonText, $row->productPrice, $row->productQty, $row->totalPrice);
                } else {
                    $itemName = ' <h5 class="m-b-0 font-16 font-medium">' . $row->itemName . '</h5></div></div>';
                    $data[] = array($srno, $itemName . '<br>' . $addonText, $row->productPrice, $row->productQty, $row->totalPrice);
                }
                $srno++;
            }
        }
        $dataCount = 0;
        $dataCount1 = $this->GlobalModel->selectQuery($orderColumns, $tableName, $condition, $orderBy, $join, $joinType, $like, '', '', $groupByColumn, $extraCondition);
        if ($dataCount1) {
            $dataCount = sizeOf($dataCount1->result());
        }
        $output = array("draw" => intval($_GET["draw"]), "recordsTotal" => $dataCount, "recordsFiltered" => $dataCount, "data" => $data);
        echo json_encode($output);
    }
    
	public function add1()
	{
		$tableNumber = $this->uri->segment(3);
		$tableDetails = $this->GlobalModel->selectQuery('tablemaster.code','tablemaster',array('tablemaster.tableNumber'=>$tableNumber,'tablemaster.isActive'=>1));
		if($tableDetails){
			$data['tableCode'] = $tableDetails->result()[0]->code;
			$data['tableNumber'] = $tableNumber;
			$categories = $this->GlobalModel->selectActiveData('categorymaster');
			$data['categories'] = $this->GlobalModel->selectActiveData('categorymaster');
			$data['products'] = $this->GlobalModel->selectActiveData('productmaster');
			if($categories){
				foreach($categories->result() as $cat){
					$data[$cat->code] = $this->GlobalModel->selectActiveDataByField('productCategory',$cat->code,'productmaster');
				}
			}
			$this->load->view('dashboard/commonheader');
			$this->load->view('dashboard/order/add',$data);
			$this->load->view('dashboard/footer');
		}
	}
	
	public function edit(){
		$orderCode = $this->uri->segment(3);
        $tableNumber = "";
		$tableDetails = $this->GlobalModel->selectQuery('tablemaster.code','tablemaster',array('tablemaster.tableNumber'=>$tableNumber,'tablemaster.isActive'=>1));
		if($tableDetails){
			$data['tableCode'] = $tableDetails->result()[0]->code;
			$data['tableNumber'] = $tableNumber;
			$categories = $this->GlobalModel->selectActiveData('categorymaster');
			$data['categories'] = $this->GlobalModel->selectActiveData('categorymaster');
			$data['products'] = $this->GlobalModel->selectActiveData('productmaster');
			if($categories){
				foreach($categories->result() as $cat){
					$data[$cat->code] = $this->GlobalModel->selectActiveDataByField('productCategory',$cat->code,'productmaster');
				}
			}
			$this->load->view('dashboard/commonheader');
			$this->load->view('dashboard/order/add',$data);
			$this->load->view('dashboard/footer');
		}
	}
	
	public function getproductDetails(){
		$productCode = $this->input->post('productCode');
		$addonHtml=$varientHtml ='';
		$i=0;
		$varientOption='';
		$data['status']=false;
		if($productCode!=''){
			$productDetails = $this->GlobalModel->selectActiveDataByField('code',$productCode,'productmaster')->result()[0];
			$addonDetails = $this->GlobalModel->selectActiveDataByField('productCode',$productCode,'customizedcategory');
			if($addonDetails){
				$data['status']=true;
				foreach($addonDetails->result() as  $add){
					
					$subCatDetails = $this->GlobalModel->selectQuery('customizedcategorylineentries.*','customizedcategorylineentries',array('customizedcategorylineentries.customizedCategoryCode'=>$add->code,'customizedcategorylineentries.isEnabled'=>1));
					if($subCatDetails){
						foreach($subCatDetails->result() as $sub){
							$i++;
							if($add->categoryType=='addon'){
								$addonHtml .= '<tr id="row'.$i.'">
									<td>
										<div class="checkbox checkbox-success">
											<input type="hidden" id="addonCode'.$i.'" name="addonCode'.$i.'" value="'.$sub->code.'">
											<input type="hidden" id="addonName'.$i.'" name="addonName'.$i.'" value="'.$sub->subCategoryTitle.'">
											<input type="hidden" id="oldaddonPrice'.$i.'" name="oldaddonPrice'.$i.'" value="'.$sub->price.'">
											<input type="hidden" id="addonPrice'.$i.'" name="addonPrice'.$i.'" value="'.$sub->price.'">
											<input type="checkbox" role="5.00" title="souc" name="addons" value="'.$i.'" id="addons_'.$i.'">
											<label for="addons_'.$i.'"></label>
										</div>
									</td>
									<td class="text-center">'.$sub->subCategoryTitle.'</td>
									<td>
										<input type="number" name="addonQty'.$i.'" id="addonQty'.$i.'" onkeyup="calculateAddonTotal('.$i.')" class="form-control text-right" value="1" min="1">
									</td>
									<td class="text-center" id="addonSubTotal'.$i.'">'.$sub->price.'</td>
								</tr>';
							}else{
								$varientOption .= '<option value="'.$sub->code.'" data-price="'.$sub->price.'">'.$sub->subCategoryTitle.'</option>';
							}
						}
					}
				}
				$varientHtml .= '<tr>
						<td><input name="productCode" type="hidden" id="productCode" value="'.$productCode.'">
						<input name="productPrice" type="hidden" id="productPrice" value="'.$productDetails->productPrice.'">
						<input name="oldproductPrice" type="hidden" id="oldproductPrice" value="'.$productDetails->productPrice.'">
						'.$productDetails->productEngName.'</td>
						<td>
							<select name="variantCode" class="form-control"  id="variantCode">
								'.$varientOption.'
							</select>
						</td>
                        <td>
							<input type="number" name="productQty" id="productQty" class="form-control text-right" onkeyup="calculateProductTotal()" value="1" min="1">
                        </td>
                        <td id="productPriceTotal">'.$productDetails->productPrice.'</td>
                    </tr>';
				}
		}
		$data['varientHtml']=$varientHtml;
		$data['addonHtml']=$addonHtml;
		echo json_encode($data);
	}
	
	public function addToCart(){
		$date = date('Y-m-d H:i:s');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
		$userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$productCode=$this->input->post('productCode');
		$tableCode=$this->input->post('tableCode');
		$variantCode=$this->input->post('variantCode');
		$addOns=$this->input->post('addOns');
		$productQty=$this->input->post('productQty');
		$productPrice=$this->input->post('productPrice');
		$totalPrice=$this->input->post('totalPrice');
		$checkProduct = $this->GlobalModel->selectQuery('ordercart.id','ordercart',array('ordercart.productCode'=>$productCode));
		if($checkProduct && $checkProduct->num_rows()>0){
			$response['status']=false;
			$response['message']='Product already present to cart';
		}else{
			$insertData=array(
				'productCode'=>$productCode,
				'tableCode'=>$tableCode,
				'productQty'=>$productQty,
				'addOns'=>$addOns,
				'variantCode'=>$variantCode,
				'productPrice'=>$productPrice,
				'totalPrice'=>$totalPrice,
				'isActive'=>1,
				'addIP'=>$ip,
				'addID'=>$addID
			);
			$code = $this->GlobalModel->addNew($insertData,'ordercart','OR');
			if($code!='false'){
				$response['status']=true;
				$response['message']='Product added to cart';
			}else{
				$response['status']=false;
				$response['message']='Failed to add product';
			}
		}
		echo json_encode($response);
	}
    
	public function getCartDetails(){
		$cartHtml='';
		$taxAmount=0;
		$tableCode = $this->input->post('tableCode');
		$tableNumber = $this->input->post('tableNumber');
		$data['serviceCharges'] = $this->GlobalModel->selectActiveDataByField('code','SET_1','settings')->result()[0]->settingValue;
		$joinType=array('productmaster'=>'inner');
		$join=array('productmaster'=>'productmaster.code=ordercart.productCode');
		$cartDetails = $this->GlobalModel->selectQuery('ordercart.code as code,ordercart.productCode,ordercart.tableCode,ordercart.productQty,ordercart.addOns,productmaster.productEngName,productmaster.productImage,ordercart.totalPrice','ordercart',array('ordercart.tableCode'=>$tableCode,'ordercart.isActive'=>1),array('ordercart.id'=>'ASC'),$join,$joinType);
		if($cartDetails){
			foreach($cartDetails->result() as $ct){
				$prdAdd='';
				$addons = json_decode($ct->addOns,true);
				foreach($addons as $add){
					$addonText = $this->GlobalModel->selectQuery('customizedcategorylineentries.subCategoryTitle,customizedcategorylineentries.price','customizedcategorylineentries',array('customizedcategorylineentries.isActive'=>1,'customizedcategorylineentries.code'=>$add[0]));
					if($addonText){
						$result = $addonText->result()[0];
						$prdAdd .= $result->subCategoryTitle.'<span style="float:right">'.$result->price.'</span><br>';
					}
				}
				$data[$ct->productCode] = $prdAdd;
			}
			$data['cartDetails'] = $cartDetails;
			$data['tableNumber']=$tableNumber;
			$data['tableCode']=$tableCode;
			$cartHtml = $this->load->view('dashboard/order/cart',$data,true);
			$response['cartHtml'] = $cartHtml;
		}else{
			$response['cartHtml']='<div><h5>No products in the cart</h5></div>';
		}
		echo json_encode($response);
	}
	
	public function updateCartPrices(){
		$type=$this->input->post('type');
		$productCode=$this->input->post('productCode');
		$totalPrice=0;
		$checkProduct = $this->GlobalModel->selectQuery('ordercart.productPrice,ordercart.productQty,ordercart.totalPrice,productmaster.productPrice as originalPrice','ordercart',array('ordercart.productCode'=>$productCode),array(),array('productmaster'=>'productmaster.code=ordercart.productCode'),array('productmaster'=>'inner'));
		if($checkProduct){
			foreach($checkProduct->result() as $chk){
				$originalPrice=$chk->originalPrice;
				$cartPrice = $chk->productPrice;
				$totalPrice = $chk->totalPrice;
				$productQty = $chk->productQty;
				if($type==1){
					$cartPrice = $cartPrice+$originalPrice;
					$totalPrice = $totalPrice+$originalPrice;
					$productQty = $productQty+1;
				}else{
					$cartPrice = $cartPrice-$originalPrice;
					$totalPrice = $totalPrice-$originalPrice;
					$productQty = $productQty-1;
				}
				$updateData = array(
					'productPrice'=>$cartPrice,
					'totalPrice'=>$totalPrice,
					'productQty'=>$productQty
				);
				$this->GlobalModel->doEditWithField($updateData,'ordercart','productCode',$productCode);
			}
		}
	}
	
	public function checkOut(){
		$tableNumber = $this->uri->segment(3);
		$tableDetails = $this->GlobalModel->selectQuery('tablemaster.code','tablemaster',array('tablemaster.tableNumber'=>$tableNumber,'tablemaster.isActive'=>1));
		if($tableDetails){
			$data['tableCode'] = $tableDetails->result()[0]->code;
			$this->load->view('dashboard/commonheader');
			$this->load->view('dashboard/order/checkout',$data);
			$this->load->view('dashboard/footer');
		}
	}
	
	public function getcheckoutCart(){
		$taxAmount=0;
		$tableCode = $this->input->post('tableCode');
		$tableNumber = $this->input->post('tableNumber');
		$data['serviceCharges'] = $this->GlobalModel->selectActiveDataByField('code','SET_1','settings')->result()[0]->settingValue;
		$joinType=array('productmaster'=>'inner');
		$join=array('productmaster'=>'productmaster.code=ordercart.productCode');
		$cartDetails = $this->GlobalModel->selectQuery('ordercart.productCode,ordercart.tableCode,ordercart.productQty,ordercart.addOns,productmaster.productEngName,productmaster.productImage,ordercart.totalPrice','ordercart',array('ordercart.tableCode'=>$tableCode,'ordercart.isActive'=>1),array('ordercart.id'=>'ASC'),$join,$joinType);
		if($cartDetails){
			$data['cartDetails'] = $cartDetails;
			$data['tableNumber']=$tableNumber;
			$data['tableCode']=$tableCode;
			$cartHtml = $this->load->view('dashboard/order/checkoutCart',$data,true);
			$response['cartHtml'] = $cartHtml;
		}else{
			$response['cartHtml']='<div><h5>No products in the cart</h5></div>';
		}
		echo json_encode($response);
	}
	
	public function placeOrder(){
		$ip = $_SERVER['REMOTE_ADDR'];
		$tableCode=$this->input->post('tableCode');
		$serviceCharges=$this->input->post('serviceCharges');
		$tax=$this->input->post('tax');
		$paymentMode=$this->input->post('paymentMode');
		$discount=$this->input->post('discount');
		$addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
		$checkCart = $this->GlobalModel->selectQuery('ordercart.*','ordercart',array('ordercart.tableCode'=>$tableCode));
		if($checkCart && $checkCart->num_rows()>0){
			$data=array(
				'name'=>$this->input->post('customerName'),
				'arabicName'=>$this->input->post('arabicName'),
				'email'=>$this->input->post('email'),
				'country'=>$this->input->post('country'),
				'state'=>$this->input->post('state'),
				'city'=>$this->input->post('city'),
				'pincode'=>$this->input->post('pincode'),
				'address'=>$this->input->post('address'),
				'isActive'=>1
			);
			$checkCustomer = $this->GlobalModel->selectQuery('customermaster.code','customermaster',array('customermaster.isActive'=>1,'customermaster.phone'=>$this->input->post('phone')));
			if($checkCustomer && $checkCustomer->num_rows()>0){
				$code = $checkCustomer->result()[0]->code;
				$data['editID'] = $addID;
				$data['editIP'] = $ip;
				$this->GlobalModel->doEdit($data,'customermaster',$code);
				$customerCode = $code;
			}else{
				$data['phone'] = $this->input->post('phone');
				$data['addID'] = $addID;
				$data['addIP'] = $ip;
				$customerCode = $this->GlobalModel->addNew($data,'customermaster','CUST');
			}
			if($customerCode!='false'){
				$response['status']=true;
				$insertArr = array(
					'customerCode'=>$customerCode,
					'tableCode'=>$tableCode,
					'paymentMode'=>$paymentMode,
					'orderStatus'=>'PND',
					'isActive'=>1
				);
				$orderCode = 'ORDER' . rand(99, 99999);
				$subTotal=0;
				$insertResult = $this->GlobalModel->addWithoutYear($insertArr, 'ordermaster', $orderCode);
				$totalpreparationTime=0;
				if ($insertResult != 'false') {
					$response['status']=true;
					foreach($checkCart->result() as $chk){
						 $addonsCode='';
						 $addonsText='';
						$subTotal = $subTotal + $chk->totalPrice;
						$preparationTime = $this->GlobalModel->selectQuery('ifnull(productmaster.preparationTime,0) as preparationTime','productmaster',array('productmaster.code'=>$chk->productCode))->result()[0]->preparationTime;
						$totalpreparationTime = $totalpreparationTime+$preparationTime;
						$orderLineData =array(
							'orderCode'=>$insertResult,
							'productCode'=>$chk->productCode,
							'productQty'=>$chk->productQty,
							'productPrice'=>$chk->productPrice,
							'totalPrice'=>$chk->totalPrice,
							'productStatus'=>'PND',
							'isActive'=>1,
						);
						if($chk->addOns!='[]'){
							$addOns = json_decode($chk->addOns, true);
							foreach($addOns as $add){
								$addonsCode = $addonsCode.','.$add[0];
								$addonsText = $addonsText.','.$add[1];
							}
							$orderLineData["addons"] = ltrim($addonsText,',');
							$orderLineData["addonsCode"] = ltrim($addonsCode,',');
						}
						$orderLineResult = $this->GlobalModel->addWithoutYear($orderLineData, 'orderlineentries', 'ORDL');
					}
					$grandTotal = $subTotal-$discount+$tax+$serviceCharges;
					$updateOrder = array(
						'subTotal'=>$subTotal,
						'discount'=>$discount,
						'tax'=>$tax,
						'serviceCharges'=>$serviceCharges,
						'grandTotal'=>$grandTotal,
						'totalpreparationTime'=>$totalpreparationTime
					);
					$this->GlobalModel->doEdit($updateOrder,'ordermaster',$insertResult);
					$dataBookLine = array(
						"orderCode" => $insertResult,
						"addID" => $addID,
						"orderStatus" => 'PND',
						"statusText" => 'Table Booked',
						"statusTime" => date("Y-m-d H:i:s"),
						"isActive" => 1
					);
					$bookLineResult = $this->GlobalModel->addWithoutYear($dataBookLine, 'bookorderstatuslineentries', 'BOL'); 
					$response['status']=true;
					$response['message']='Order Placed Successfully';
				}else{
					$response['status']=false;
					$response['message']='Cart is empty';
				}
			}else{
				$response['status']=false;
				$response['message']='Something went wrong..Please try again';
			}
		}else{
			$response['status']=false;
			$response['message']='Cart is empty';
		}
		echo json_encode($response);
	}
	
	public function getCustomerByPhone(){
		$phone = $this->input->post('phone');
		$checkCustomer = $this->GlobalModel->selectQuery('customermaster.*','customermaster',array('customermaster.isActive'=>1,'customermaster.phone'=>$phone));
		$checkCustomer->num_rows();
		if($checkCustomer && $checkCustomer->num_rows()>0){
			$response['status']=true;
			$response['data']=$checkCustomer->result()[0];
		}else{
			$response['status']=false;
		}
		echo json_encode($response);
	}
    
	public function updateOrderStatus()
    {
        $orderCode = $this->input->post('orderCode');
        $orderStatus = "CON";
        $date = date("Y-m-d H:i:s");
        $addID = $this->session->userdata['logged_in' . $this->session_key]['code'];
        $userRole = $this->session->userdata['logged_in' . $this->session_key]['role'];
        $userName = $this->session->userdata['logged_in' . $this->session_key]['username'];
        $ip = $_SERVER['REMOTE_ADDR'];

        $txt =  "Order  - " .  $orderCode . " order is confirmed ";
        $activity_text =  $date . "\t" . $ip . "\t" . $userRole . "\t" . $userName . "\t" . $txt;
       
        $data = array('orderStatus' => $orderStatus, 'editID' => $addID, 'editDate' => $date);
        $result = $this->GlobalModel->doEdit($data, 'ordermaster', $orderCode);

        if ($result != 'false') {
			//$this->GlobalModel->deleteForeverFromField('tableCode','')
			$this->GlobalModel->activity_log($activity_text);
			$statusReason = 'Order is confirmed';
            $dataBookLine = array(
                "orderCode" => $orderCode,
                "orderStatus" => $orderStatus,
                "statusTime" => $date,
                "statusText" => $statusReason,
                "isActive" => 1,
                "addID" => $addID,
                "addDate" => $date,
                "addIP" => $ip
            );
            $bookLineResult = $this->GlobalModel->addWithoutYear($dataBookLine, 'bookorderstatuslineentries', 'BOL');
            if ($bookLineResult != 'false') {
                $response['status'] = true;
                $response['message'] = "Order Status Changed Successfully.";
            } else {
                $response['status'] = false;
                $response['message'] = "Failed To Change Status.";
            }
        } else {
            $response['status'] = false;
            $response['message'] = "Failed To Change Status";
        }
        echo json_encode($response);
    }
	
	public function calculateAmount(){
		$taxAmount=0;
		$totalPrice=0;
		$tableCode=$this->input->post('tableCode');
		$serviceCharges = $this->GlobalModel->selectActiveDataByField('code','SET_1','settings')->result()[0]->settingValue;
		$joinType=array('productmaster'=>'inner','taxgroupmaster'=>'inner');
		$join=array('productmaster'=>'productmaster.code=ordercart.productCode','taxgroupmaster'=>'taxgroupmaster.code=productmaster.productTaxGrp');
		$cartDetails = $this->GlobalModel->selectQuery('ordercart.productCode,productmaster.productTaxGrp,taxgroupmaster.taxes,ordercart.tableCode,ordercart.productQty,ordercart.addOns,productmaster.productEngName,productmaster.productImage,ordercart.totalPrice','ordercart',array('ordercart.tableCode'=>$tableCode,'ordercart.isActive'=>1),array('ordercart.id'=>'ASC'),$join,$joinType);
		if($cartDetails){
			foreach($cartDetails->result() as $ct){
				$totalPrice=$ct->totalPrice;
				$taxes = json_decode($ct->taxes,true);
				$getTaxes = $this->db->query("select taxes.taxPer from taxes where taxes.code in('" . implode("','", $taxes) . "')");
				if($getTaxes){
					foreach($getTaxes->result() as $t){
						$taxAmount =$taxAmount+($totalPrice*$t->taxPer/100);
					}
				}
			}
		}
		$response['totalPrice']=number_format($totalPrice,2,'.',',');
		$response['taxAmount']=number_format($taxAmount,2,'.',',');
		$response['serviceCharges']=number_format($serviceCharges,2,'.',',');
		$response['grandTotal'] = number_format($totalPrice+$taxAmount+$serviceCharges,2,'.',',');
		echo json_encode($response);
	}
}
