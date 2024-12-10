<ul class="cartul" style="list-style-type: none">
	<?php 
	$totalPrice=0;
	$discount=0;
	$tax=0;
		if($cartDetails){
			foreach($cartDetails->result() as $cart){
				$totalPrice = $totalPrice + $cart->totalPrice;
		?>
		<li>
			<div class="infoWrap"> 
				<input type="hidden" class="form-control" id="tableCode" name="tableCode" value="<?= $cart->tableCode?>">
				<div class="row cartSection">
					<div class="col-md-6 col-12"><img src="<?= base_url().$cart->productImage?>" alt="" class="itemImg img-responsive" width="60px" height="60px"> </div>
					<div class="col-md-6 col-12">
						<h6><?= $cart->productEngName ?></h6>
						<div class="extra">
							<div><span class="cstmzn-hdng">Added Extras</span><div class="cstmstn--cntnr"><span> : </span><span class="cstmstn--txt">Extra Cheese, Crisp Capsicum</span></div></div>
						</div>
					</div>
				</div>
				<div class="row cartqty">
					<div class="col-md-6 col-12">
						<a class="btn btn-primary btn-sm btnleftalign"  onclick="updateCartPrices(1,'<?= $cart->productCode ?>')"><i class="fa fa-plus" aria-hidden="true"></i></a>
						<span id="productionsetting-6-8"><?= $cart->productQty ?></span>
						<a class="btn btn-danger btn-sm btnrightalign" onclick="updateCartPrices(2,'<?= $cart->productCode ?>')"><i class="fa fa-minus" aria-hidden="true"></i></a>
					</div>
					<div class="col-md-6 col-12">
						<h6 class="rupee" id="cartTotalPrice"><?= $cart->totalPrice?></h6>
					</div>
				</div>
			</div>
		</li>
	<?php } ?>
</ul>
	<div class="priclist">
		<div class="row pricecart">
			<div class="leftdivcart">
				<div class="promoCode"><label for="promo" class="col-md-12">Have A Coupon?</label>
					<div class="row">
						<div class="col-md-7"><input type="text" name="promo" placholder="Enter Code"></div>
						<div class="col-md-5 float-lg-end"><a href="#" class="btn btn-success me-1 mb-1 sub_1">Add</a></div>
					</div>
				</div>
				<div class="col-md-12"><span class="label">Discount :</span><span class="value">Rs.<?= $discount ?></span></div>
			</div>
		</div>
		<div class="row mainpric bg-light">
			<input type="hidden" class="form-control" id="serviceCharges" name="serviceCharges" value="<?= $serviceCharges?>">
			<input type="hidden" class="form-control" id="tax" name="tax" value="<?= $tax?>">
			<div class="col-md-6 col-xs-6"><span class="label1"><strong>Subtotal :</strong></span></div>
			<div class="col-md-6 col-xs-6 text-right"><span class="value1"><?= number_format($totalPrice,2,'.',',') ?></span></div>
			<div class="col-md-6 col-xs-6"><span class="label1"><strong>Discount :</strong></span></div>
			<div class="col-md-6 col-xs-6 text-right"><span class="value1"><?= number_format($discount,2,'.',',') ?></span></div>
			<div class="col-md-6 col-xs-6"><span class="label1"><strong>Tax :</strong></span></div>
			<div class="col-md-6 col-xs-6 text-right"><span class="value1" id="taxSpan"><?= number_format($tax,2,'.',',') ?></span></div>
			<div class="col-md-6 col-xs-6"><span class="label1"><strong>Service Charges :</strong></span></div>
			<div class="col-md-6 col-xs-6 text-right"><span class="value1" id="serviceChargesSpan"><?= number_format($serviceCharges,2,'.',',')?></span></div>
			<div class="col-md-6 col-xs-6"><span class="label"><strong>Total :</strong></span></div>
			<div class="col-md-6 col-xs-6 text-right"><span class="value" id="grandTotalSpan"><?= number_format($totalPrice-$discount+$tax+$serviceCharges,2,'.',',') ?></span></div>
			<div class="col-md-12 frmbtn d-flex">
				<a id="paynowBtn" class="btn btn-success me-1 mb-1 sub_1 paynow" style="width:100%;font-size: 18px;">Pay Now</a> 
            </div> 
		</div> 
	</div>
		<?php }?>