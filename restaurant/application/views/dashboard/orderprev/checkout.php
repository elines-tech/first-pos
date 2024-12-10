<nav class="navbar navbar-light">
    <div class="container d-block">
		<div class="row"> 
            <div class="col-12 col-md-6 order-md-1 order-last"><a href="customer-pos.php"><i class="fa fa-times fa-2x"></i></a></div>
		</div>
    </div>
</nav>
<div class="container">
    <section id="multiple-column-form" class="mt-5 catproduct">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                       <div class="card-body">
							<form id="customerForm" class="form" data-parsley-validate>
								<div class="row">
									<div class="col-md-8 col-12">
										<h3 id="myTabs">  Billing Form:</h3> 
										<div class="bg-light padcls mb-2">
											<div class="row">
												<div class="col-md-6 col-12">
													<div class="form-group mandatory">
														<label for="customerName" class="form-label">Name</label>
														<input type="text" id="customerName" class="form-control" placeholder="Customer Name" name="customerName" data-parsley-required="true">
													</div>
												</div>
												<div class="col-md-6 col-12">
													<div class="form-group mandatory">
														<label for="arabicName" class="form-label">Arabic Name</label>
														<input type="text" id="arabicName" class="form-control" placeholder="Arabic Name" name="arabicName" data-parsley-required="true">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6 col-12">
													<div class="form-group mandatory">
														<label for="email" class="form-label">Email</label>
														<input type="email" id="email" class="form-control" placeholder="Email" name="email" data-parsley-required="true">
													</div>
												</div>
												<div class="col-md-6 col-12">
													<div class="form-group mandatory">
														<label for="country" class="form-label">Country</label>
														<input type="text" id="country" class="form-control" placeholder="Country" name="country" data-parsley-required="true">
                                                    </div>
                                                </div>
											</div>
											<div class="row">
                                                <div class="col-md-6 col-12">
													<div class="form-group mandatory">
														<label for="state" class="form-label">State</label>
														<input type="text" id="state" class="form-control" placeholder="State" name="state" data-parsley-required="true">
													</div>
                                                </div>
												<div class="col-md-6 col-12">
													<div class="form-group mandatory">
														<label for="city" class="form-label">City</label>
														<input type="text" id="city" class="form-control" placeholder="City" name="city" data-parsley-required="true">
													</div>
												</div>
                                            </div>
                                            <div class="row">
												<div class="col-md-6 col-12">
													<div class="form-group mandatory">
														<label for="pincode" class="form-label">Postal Code</label>
														<input type="text" id="pincode" class="form-control" placeholder="Postal Code" name="pincode" data-parsley-required="true" >
													</div>
												</div>
												<div class="col-md-6 col-12">
													<div class="form-group mandatory">
														<label for="phone" class="form-label">Phone</label>
														<input type="number" id="phone" class="form-control" placeholder="Phone" name="phone" data-parsley-required="true">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12 col-12">
													<div class="form-group mandatory">
														<label for="address" class="form-label">Address</label>
														<textarea class="form-control" placeholder="Address" id="address" name="address" data-parsley-required="true"></textarea>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="paymethod">
												<h3 id="myTabs">Payment Method:</h3>
												<div id="payment" class=" mycls bg-light">
													<ul class="payment_methods methods" style="list-style-type: none">
														<li id="cod" class="payment_method_cod form-check">
															<input id="payment_method_cod" type="radio" class="input-radio form-check-input" name="payment_method" value="cod" checked="checked" data-order_button_text="">
															<label for="payment_method_cod" class="form-check-label">Cash Payment </label>
														</li>
														<li id="razorpay" class="wc_payment_method payment_method_razorpay form-check">
															<input id="payment_method_razorpay" type="radio" class="input-radio form-check-input" name="payment_method" value="online" data-order_button_text="">
																<label for="payment_method_razorpay" class="form-check-label">Credit Card/Debit Card/NetBanking <img src="https://cdn.razorpay.com/static/assets/logo/payment.svg" alt="Credit Card/Debit Card/NetBanking"> </label>
														</li>
													</ul>
												</div>
											</div>
										</div>
									</div>
									<input type="hidden" class="form-control" id="tableCode" name="tableCode" value="<?= $tableCode?>">
									<div class="col-md-4 col-12">
										<div class="maincart" id="checkoutcart">
											<h3 id="myTab">Cart:</h3>                      
											<div class="cart" id="cartDiv">
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
$(document).ready(function() {
		
        getCartDetails();
   });
	function getCartDetails(){
		var tableCode = $('#tableCode').val()
		$.ajax({
			url: base_path + "order/getcheckoutCart",
			async:false,
			type:'POST',
			data:{
				'tableCode':tableCode
			},
			success: function(response) {
				var obj = JSON.parse(response)
				$('#cartDiv').html('');
				$('#cartDiv').append(obj.cartHtml);
				calculateAmount();
			}
		});
	}
	function calculateAmount(){
		var tableCode = $('#tableCode').val();
		$.ajax({
			url: base_path + "order/calculateAmount",
			type:'POST',
			data:{
				'tableCode':tableCode
			},
			success: function(response) {
				var obj = JSON.parse(response)
				$('#taxSpan').text(obj.taxAmount);
				$('#tax').val(obj.taxAmount);
				$('#serviceChargesSpan').text(obj.serviceCharges);
				$('#grandTotalSpan').text(obj.grandTotal);
				
			}
		});
	}
	function updateCartPrices(type1,productCode){
		$.ajax({
			url: base_path + "order/updateCartPrices",
			type:'POST',
			data:{
				'type':type1,
				'productCode':productCode
			},
			success: function(response) {
				getCartDetails();
			}
		});
	}
	$(document).on("click", ".paynow", function() {
		$('#customerForm').parsley();
		const form = document.getElementById('customerForm');
        var formData = new FormData(form);
		var serviceCharges = $('#serviceCharges').val();
		var tableCode = $('#tableCode').val();
		var tax = $('#tax').val();
		var paymentMode = $('input[name="payment_method"]:checked').val();
	    var isValid = true;
        $("#customerForm .form-control").each(function(e) {
            if ($(this).parsley().validate() !== true) isValid = false;
        });
        if (isValid) {
			formData.append('paymentMode',paymentMode)
			formData.append('serviceCharges',serviceCharges)
			formData.append('tax',tax)
			formData.append('tableCode',tableCode)
			$.ajax({
				url: base_path + "order/placeOrder",
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$('#paynowBtn').prop('disabled',true);
				},
				success: function(response) {
					$('#paynowBtn').prop('disabled',false);
					var obj = JSON.parse(response);
					if (obj.status) {
						toastr.success(obj.message, 'Order Place', {
							"progressBar": true
						});
						location.href=base_path+"order/listRecords";
					} else {
						toastr.error(obj.message, 'Order Place', {
							"progressBar": true
						});
					}
				}
			});
		}
    });
	$(document).on("change", "#phone", function() {
		var phone= $(this).val();
		if(phone!=''){
			$.ajax({
				url: base_path + "order/getCustomerByPhone",
				type: 'POST',
				data: {
					'phone':phone
				},
				success: function(response) {
					var obj = JSON.parse(response);
					if (obj.status) {
						$('#customerName').val(obj.data.name);
						$('#arabicName').val(obj.data.arabicName);
						$('#email').val(obj.data.email);
						$('#country').val(obj.data.country);
						$('#state').val(obj.data.state);
						$('#city').val(obj.data.city);
						$('#pincode').val(obj.data.pincode);
						$('#phone').val(obj.data.phone);
						$('#address').val(obj.data.address);
						$('#customerForm').parsley();
					} else {
						$('#customerName')[0].reset();
						$('#phone').val(phone)
					}
				}
			});
		}
	});
</script>