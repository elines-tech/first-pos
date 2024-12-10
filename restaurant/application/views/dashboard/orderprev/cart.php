<ul class="cartul" style="list-style-type: none">
	<?php 
	$totalPrice=0;
		if($cartDetails){
			foreach($cartDetails->result() as $cart){
				$totalPrice = $totalPrice + $cart->totalPrice;
		?>
		<li id="<?= $cart->code?>">
			<div class="infoWrap"> 
				<div class="row mt-1">
					<div class="col-md-8 col-12"></div>
					<div class="col-md-4 col-12">
						<a class="btn btn-sm btn-danger deleteCartProduct" data-seq="<?= $cart->code?>"><i class="fa fa-trash"></i></a>
					</div>
				</div>
				<div class="row cartSection">
					<div class="col-md-6 col-12"><img src="<?= base_url().$cart->productImage?>" alt="" class="itemImg img-responsive" width="60px" height="60px"> </div>
					<div class="col-md-6 col-12">
						<h6><?= $cart->productEngName ?></h6>
						<input type="hidden" class="form-control" id="tableCode" name="tableCode" value="<?= $tableCode?>">
					</div>
				</div>
				<div class="row">
					<div class="extra">
						<?php 
						if(${$cart->productCode}!=''){ ?>
						<div><span class="cstmzn-hdng">Addons : <br><span class="cstmstn--txt"><?= ${$cart->productCode} ?></span></div>
						<?php }?>
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
				<div class="col-md-12"><span class="label">Discount :</span><span class="value">Rs.35.00</span></div>
			</div>
		</div>
		<div class="row mainpric bg-light">
			<div class="col-md-6 col-xs-6"><span class="label">Subtotal :</span></div><div class="col-md-6 col-xs-6"><span class="value"><?= $totalPrice ?></span></div>
			<div class="col-md-12 frmbtn d-flex"><a href="<?= base_url() ?>order/checkOut/<?= $tableNumber?>" class="btn btn-success me-1 mb-1 sub_1" style="width:100%;font-size: 18px;">Checkout</a> </div>   
		</div> 
	</div>
		<?php }?>