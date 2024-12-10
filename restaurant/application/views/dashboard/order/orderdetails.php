
	<div class="card card-custom gutter-b bg-white border-0">
		<div class="card-body">
			<div class="row invoice-head">
				<div class="row col-md-12">
					<div class="col-md-3 ">
						<div class="text-center1">
							<a href="index.php"><img src="../assets/images/logo/logomain.png" alt="Logo" srcset="" width="120px" height="40px"></a>
						</div>
						<span class="btn btn-outline-info m-r-15 p-10  mt-2" style="cursor: unset;font-size: 14px;padding: 4px 12px;">Billing from </span>
						<div class="text-center1 mt-2">
							<h6 class="mb-2"><strong class="lng"> Dhaka Restaurant</strong> </br></h6>
							<span>98 Green Road, Farmgate, Dhaka-1215. </br></span>
							<span> Print Date: <?= date('d-m-Y', strtotime($orderData->cartDate)) ?> </span>
						</div>
					</div>
					<div class="col-md-6"></div>
					<div class="col-md-3 text-left mb-0 p-0 float-lg-end">
						<div class="text-center1 ">
							<h3 class="mb-2"><strong class="lng">Invoice</strong> </h3>
							<span><br>
								Order Status: Pending order<br>
								Billing date : <?= date('d-m-Y h:i A', strtotime($orderData->cartDate)) ?><br>
							</span>
							<?php
							foreach($cartIdArr as $cartId){?>
								<input type="hidden" class="form-control" id="cartIdArr" name="cartId[]" value="<?= $cartId?>">
							<?php }
							?>
						</div>
						<span class="btn btn-outline-info m-r-15 p-10  mt-2" style="cursor: unset;font-size: 14px;padding: 4px 12px;">Billing to </span>
						<div class="text-center1  mt-2">
							<h6 class="mb-2"><strong class="lng"><?= $orderData->custName ?></strong> </h6>
							<span>Address: kolhapur<br>
								Mobile No.: <?= $orderData->custPhone ?><br></span>
							<input type="hidden" class="form-control" id="totalpreparationTime" name="totalpreparationTime" value="<?= $totalpreparationTime?>">
							<input type="hidden" class="form-control" id="isDraft" name="isDraft" value="<?= $isDraft?>">
						</div>
					</div>
				</div>
			</div>
			<div class="row mt-4">
				<table class="table table-bordered table-hover" id="orderLine">
					<thead>
						<tr>
							<th class="text-center" width="50px">Sr.No.</th>
							<th class="text-center" width="250px">Product Name</th>
							<th class="text-center" width="150px">Product Quantity</th>
							<th class="text-center" width="100px">Product Price</th>
							<th class="text-center" width="150px">Total Amount</th>
						</tr>
					</thead>
					<tbody id="addPurchaseItem">
						<?php
							$subTotal=0;
							foreach($kotNumber as $kot){ ?>
							<tr>
								<td colspan="5"><span class='badge bg-success'>KOT NO <?= $kot?></span></td>
							</tr>
							<?php 
							$cnt=0;
							$prdArr=[];
							 if (isset(${'kot'.$kot})) {
								foreach (${'kot'.$kot}->result() as $row) {
									$cnt++;
									$subTotal = $subTotal+$row->totalPrice;
									$productImage=base_url().'assets/food.png';
									if($row->productImage!='' && $row->productImage!=NULL){
										$productImage=base_url().$row->productImage;
									}
										?>
									<tr>
										<input type="hidden" class="form-control" name="productCode[]" id="productCode<?=$cnt?>" value="<?= $row->productCode?>">
										<td class="text-center" width="50px"><?= $cnt ?></td>
										<td class="text-center" width="350px">
											<div class="row">
												<div class="col-md-3 col-12"><img src="<?= $productImage?>" class="itemImg img-responsive" width="65px;" height="50px;"></div>
												<div class="col-md-9 col-12">
													<h6><?= $row->productEngName ?></h6>
												</div>
											</div></td>
										<td class="text-center" width="100px"><?= $row->productQty?></td>
										<td class="text-center" width="100px"><?= $row->productPrice?></td>
										<td class="text-center" width="100px"><?= $row->totalPrice?></td>
									</tr>
									<?php
									
									}
								}?>
						<?php } ?>
					</tbody>
					
						<tfoot>
							<tr>
								<td colspan="4" class="text-right"><b>Subtotal :</b></td>
								<td class="text-center" id="subTotalText"><?= number_format($subTotal, 2, '.', '') ?></td>
							</tr>
							<tr>
								<td colspan="4" class="text-right"><b>Discount (%):</b></td>
								<td class="text-center">
									<select class="form-select select2 text-center" id="discount" onchange="calculateTotalAmount()" name="discount">
										<option value="0">NA</option>
										<?php 
										if($discounts){
											foreach($discounts->result() as $dis){
												echo "<option value='".$dis->discount."'>".$dis->discount."</option>";
											}
										}
										?>
									</select></td>
							</tr>
							<tr>
								<td colspan="4" class="text-right"><b>Discount Amount :</b></td>
								<td class="text-center" id="discountAmount" style="background: #ff00fe21;"></td>
							</tr>
							<tr class="couponDiv d-none">
								<td colspan="4" class="text-right"><b>Coupon:</b></td>
								<td class="text-center">
									<select class="form-select select2 text-center" id="couponCode" onchange="calculateTotalAmount()" name="couponCode">
										<option value="0">NA</option>
										<?php 
										if($coupons){
											foreach($coupons->result() as $cs){
												echo "<option value='".$cs->code."' data-offertype='".$cs->offerType."' data-couponcode='".$cs->couponCode."' data-discount='".$cs->discount."' data-flatamount='".$cs->flatAmount."'>".$cs->couponCode.' - '.$cs->offerType."</option>";
											}
										}
										?>
									</select>
								</td>
							</tr>
							<tr class="couponDiv d-none">
								<td colspan="4" class="text-right"><b>Coupon Discount:</b></td>
								<td class="text-center" id="couponDiscountAmount" style="background: #ff00fe21;">0.00</td>
							</tr>
							<tr>
								<td colspan="4" class="text-right"><b>Tax :</b></td>
								<td class="text-center" id="taxText"></td>
							</tr>
							<tr>
								<td colspan="4" class="text-right"><b>Service Charges :</b></td>
								<td class="text-center" id="serviceChargesText"><?= number_format($serviceCharges, 2, '.', '') ?></td>
							</tr>
							
							<tr>
								<td colspan="4" class="text-right"><b>Grand Total :</b></td>
								<td class="text-center" id="grandTotalText" style="background: #4ff1ed69;"><?= number_format($subTotal+$serviceCharges, 2, '.', '')?></td>
							</tr>
						</tfoot>
				</table>
			</div>
			 <div class="row">
				  <div class="col-12 d-flex justify-content-end">
					  <label for="isCouponApplied">Apply Coupon</label>&nbsp;
					  <input type="checkbox" class="mt-1" style="width:20px;height:20px;margin-right:15px" id="isCouponApplied" name="isCouponApplied">
					  <button type="button" class="btn btn-primary white me-2 mb-1 sub_1 confirmOrder" id="confirmOrderBtn">Confirm</button>
				  </div>
			  </div>
		</div>
	</div>

<script>
    $(document).ready(function() {
        calculateTotalAmount();
	});
	function calculateTotalAmount(){
		var subTotal = $('#subTotalText').text();
		var discount = $('#discount').val();
		var serviceCharges = $('#serviceChargesText').text();
		var productCode = $("input[name='productCode[]']").map(function() {
            return $(this).val();
        }).get();
		var offerType=couponDiscount='';
		if($('#couponCode').val()!=0){
			var offerType = $('#couponCode').find(':selected').data('offertype');
			if(offerType=='flat'){
				var couponDiscount = $('#couponCode').find(':selected').data('flatamount');
			}else{
				var couponDiscount = $('#couponCode').find(':selected').data('discount');
			}
		}
		$.ajax({
            url: base_path + "order/getTaxAmount",
            type: 'POST',
            data: {
                'productCodes': productCode,
                'subTotal': subTotal,
                'discount': discount,
				'offerType':offerType,
				'couponDiscount':couponDiscount
            },
            success: function(response) {
				var obj = JSON.parse(response)
				$('#taxText').text(obj.taxAmount);
				$('#discountAmount').text(obj.discountAmount);
				if($('#couponCode').val()!=0){
					$('#couponDiscountAmount').text(obj.couponDiscountAmount);
				}else{
					$('#couponDiscountAmount').text(0.00);
				}
				if(obj.couponDiscountAmount!=0){
					grandTotal = Number(obj.couponDiscountAmount)+Number(obj.taxAmount)+Number(serviceCharges);
				}else{
					grandTotal = Number(subTotal - obj.couponDiscountAmount)+Number(obj.taxAmount)+Number(serviceCharges);
				}
				$('#grandTotalText').text(grandTotal.toFixed(2));
			}
		});
	}
	$("body").on("change", "#isCouponApplied", function(e) {
		if($("#isCouponApplied").is(':checked')){
			$('.couponDiv').removeClass('d-none');
		}else{
			$('.couponDiv').addClass('d-none');
		}
	});
		
	$("body").on("click", ".confirmOrder", function(e) {
		var cartId = $("input[name='cartId[]']").map(function() {
            return $(this).val();
        }).get();
        var subTotal = $('#subTotalText').text();
        var totalpreparationTime = $('#totalpreparationTime').val();
        var tax = $('#taxText').text();
		var discountPer = $('#discount').val();
		var discountAmount = $('#discountAmount').text();
		var couponCode = $('#couponCode').find(':selected').data('couponcode');
		var couponDiscount = $('#couponDiscountAmount').text();
		var serviceCharges = $('#serviceChargesText').text();
		var grandTotal = $('#grandTotalText').text();
        $.ajax({
            url: base_path + "order/confirmOrder",
            type: 'POST',
            data: {
                'cartId': cartId,
                'subTotal': subTotal,
                'tax': tax,
                'discountPer': discountPer,
                'discountAmount': discountAmount,
                'serviceCharges': serviceCharges,
                'grandTotal': grandTotal,
                'couponCode': couponCode,
                'couponDiscount': couponDiscount,
                'totalpreparationTime': totalpreparationTime,
            },
			beforeSend: function() {
                $('#confirmOrderBtn').prop('disabled', true);
                $('#confirmOrderBtn').text('Please wait..');
            },
            success: function(response) {
				var obj = JSON.parse(response)
				if(obj.status){
					$('#confirmOrderBtn').addClass('display','none');
					$('#printOrderBtn').addClass('display','inline');
					toastr.success(obj.message, 'Order', {
						"progressBar": true
					});
					$('#generl_modal1').modal('hide');
					fetchOrders();
				}else{
					toastr.error(obj.message, 'Order', {
						"progressBar": true
					});	
				}
            }
        });
    });


</script>