<?php 
	$prdHtml = '<ul class="cartul" style="list-style-type: none">';
	foreach ($cartDetails->result() as $cart) {
		$prdExtras ='';
        $extras = json_decode($cart->addOns, true);
        foreach ($extras as $add) {
            $extrasText = $this->GlobalModel->selectQuery('productextras.code,productextras.custprice,unitmaster.unitSName', 'productextras', array('productextras.isActive' => 1, 'productextras.code' => $add['addonCode']),array(), array('unitmaster'=>'unitmaster.code=productextras.itemUom'),array('unitmaster'=>'inner'));
            if ($extrasText) {
                $result = $extrasText->result()[0];
				$extraCode="'".$result->code."'";
                $prdExtras .= '<tr id="addon_row'.$result->code.'"><td><a class="btn btn-sm" id="removeAddon'.$result->code.'" onclick="removeAddon('.$extraCode.','.$cart->id.')"><i class="fa fa-times"</i></a></td><td>'.$add['addonQty']."  ".$result->unitSName."  ".$add['addonTitle'] ."</td><td align='right'>". $add['addonPrice'].'</td></tr>';
            }
        }
		if($cart->isCombo==0){
			$productImage=base_url().'assets/food.png';
			if($cart->productImage!='' && $cart->productImage!=NULL){
				$productImage=base_url().$cart->productImage;
			}
			$prdHtml .= '<li id="productDiv'.$cart->id.'">
					<div class="infoWrap mt-2"> 
						<div class="row mt-1">
							<div class="col-md-11 col-12">
								<a class="btn btn-success btn-sm btnleftalign" onclick="updateCartPrices('.$cart->productQty.',0,1,'.$cart->id.')" id="addQty'.$cart->id.'"><i class="fa fa-plus" aria-hidden="true"></i></a>
								<span id="productionsetting-6-8">'.$cart->productQty.'</span>
								<a class="btn btn-danger btn-sm btnrightalign" onclick="updateCartPrices('.$cart->productQty.',0,2,'.$cart->id.')" id="minusQty'.$cart->id.'"><i class="fa fa-minus" aria-hidden="true"></i></a>
							</div>
							<div class="col-md-1 col-12">
								<a class="btn btn-sm btn-danger" onclick="deleteProduct('.$cart->id.')" id="productRemoveBtn'.$cart->id.'"><i class="fa fa-trash"></i></a>
							</div>
						</div>
						<div class="row mt-1">
								<div class="col-md-4 col-12"><img src="'.$productImage.'" alt="" class="itemImg img-responsive" style="position:absolute" width="70px" height="55px"> </div>
								<div class="col-md-8 col-12">
									<h6>'.$cart->productEngName.'<span class="rupee" style="float:right" id="cartProductPrice'.$cart->id.'">'.$cart->productPrice.'</span></h6>
								</div>
							</div>';
							if($prdExtras!=''){
								$prdHtml .= '<div class="row" id="extraDiv'.$cart->id.'">
									<div class="col-md-4 col-12"></div>
									<div class="col-md-8 col-12">
										<div><span class="cstmzn-hdng">Extras : <br><table class="table">'.$prdExtras.'</table></div>
									</div>
								</div>';
							}
							$prdHtml .='<div class="row mt-1">
								<div class="col-md-4 col-12"></div>
								<div class="col-md-8 col-12">
									<h6 class="rupee">Total <span class="rupee" style="float:right" id="cartTotalPrice'.$cart->id.'">'.$cart->totalPrice.'</span></h6>
								</div>
							</div>';
						$prdHtml .='</div>
					</li>';
			}else{
				$comboProducts=json_decode($cart->comboProducts,true);
				$comboQuantity = $comboProducts[0]['productQty'];
				$comboDetails = $this->GlobalModel->selectQuery('productcombo.productComboName,productcombo.productComboPrice,productcombo.productComboImage','productcombo',array('productcombo.code'=>$cart->productCode))->result()[0];
				$comboImage=base_url().'assets/food.png';
				if(file_exists($comboDetails->productComboImage) && $comboDetails->productComboImage!='' && $comboDetails->productComboImage!=NULL){
					$comboImage=base_url().$comboDetails->productComboImage;
				}
				$prdHtml .= '<li id="productDiv'.$cart->id.'">
					<div class="infoWrap mt-2"> 
						<div class="row">
							<div class="col-md-11 col-12">
								<a class="btn btn-success btn-sm btnleftalign" onclick="updateCartPrices('.$cart->productQty.',1,1,'.$cart->id.','.$comboDetails->productComboPrice.','.$cartId.')" id="addQty'.$cart->id.'"><i class="fa fa-plus" aria-hidden="true"></i></a>
								<span id="productionsetting-6-8">'.$cart->productQty.'</span>
								<a class="btn btn-danger btn-sm btnrightalign" onclick="updateCartPrices('.$cart->productQty.',1,2,'.$cart->id.','.$comboDetails->productComboPrice.','.$cartId.')" id="minusQty'.$cart->id.'"><i class="fa fa-minus" aria-hidden="true"></i></a>
							</div>
							<div class="col-md-1 col-12">
								<a class="btn btn-sm btn-danger" onclick="deleteProduct('.$cart->id.')" id="productRemoveBtn'.$cart->id.'"><i class="fa fa-trash"></i></a>
							</div>
						</div>
						<div class="row mt-1">
							<div class="col-md-4 col-12"><img src="'.$comboImage.'" alt="" class="itemImg img-responsive" style="position:absolute" width="70px" height="55px"> </div>
							<div class="col-md-8 col-12">
								<h6>'.$comboDetails->productComboName.'<span class="rupee" style="float:right">'.number_format($comboDetails->productComboPrice,2,'.',',').'</span></h6>
							</div>
						</div>
						<div class="row mt-1">
							<div class="col-md-4 col-12"></div>
							<div class="col-md-8 col-12">
								<h6>Total <span class="rupee" style="float:right">'.$cart->totalPrice.'</span></h6>
							</div>
						</div>
						<div class="row mt-2">
							<h6>Combo Products includes: </h6>
							<div class="col-md-12 col-12">
								<table class="table">';
								$comboProductDetails = $this->GlobalModel->selectQuery('productcombolineentries.productPrice,productmaster.productEngName','productcombolineentries',array('productcombolineentries.productComboCode'=>$cart->productCode),array(),array('productmaster'=>'productmaster.code=productcombolineentries.productCode'),array('productmaster'=>'inner'));
								if($comboProductDetails){
									$comboSrNo=0;
									foreach($comboProductDetails->result() as $cmbl){
										$comboSrNo++;
										$prdHtml .= '<tr><td>'.$comboQuantity."  ".$cmbl->productEngName.'</td><td>'.number_format($cmbl->productPrice, 2, '.', '').'</td></tr>';
									}
								}
								$prdHtml .= '</table>
							</div>
						</div>
					</div>
				</li>';
			}
        }
    $prdHtml .= '</ul>';
	echo $prdHtml;
	?>