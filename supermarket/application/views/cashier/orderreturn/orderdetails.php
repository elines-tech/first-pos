<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Order</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Order Return</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
		<div class="row match-height">
			<div class="col-12">
			  
				<div class="card">
					<div class="card-header"> 
						<h3>Order Return<span style="float:right"><a style="cursor:pointer;" class="btn btn-sm btn-primary totalOrderReturn">Total Order Return</a></span></h3>
					</div>
					<div class="card-content">
						<div class="card-body">						
							<form id="inwardForm" class="form" method="post" action="<?= base_url('Cashier/OrderReturn/saveOrderReturn') ?>" enctype="multipart/form-data" data-parsley-validate> 
							    <?php
								if($query){
									foreach ($query->result() as $row) {
										$offerText='';
										$paymentMode=$row->paymentMode;
										
								?>
								<div class="col-12">
									
											<div class="row">
												<div class="col-md-4 col-12">
													<div class="form-group mandatory">
														<label for="orderDate"> Order Date : </label>
														<input type="text" id="orderDate" name="orderDate" class="form-control-line" value="<?= date('d/m/Y h:i A',strtotime($row->orderDate)) ?>">
													</div>
												</div>
												<div class="col-md-2 col-12">
													<div class="form-group mandatory">
														<label for="orderCode"> Order Code : </label>
														<input type="text" id="orderCode" name="orderCode" class="form-control-line" value="<?= $row->code ?>" required readonly>
													    <input type="hidden" id="branchCode" name="branchCode" class="form-control-line" value="<?= $row->branchCode ?>"readonly>
													</div>
												</div>
												<div class="col-md-3 col-12">
													<div class="form-group mandatory">
														<label for="clientName"> Customer Name : </label>
														<input type="text" id="clientName" name="clientName" value="<?= $row->name ?>" class="form-control-line" required readonly>
													</div>
												</div>
												<div class="col-md-3 col-12">
													<div class="form-group mandatory">
														<label for="phone"> Phone : </label>
														<input type="number" id="phone" name="phone" class="form-control-line" value="<?= $row->phone ?>" readonly>
													</div>
												</div>
												
											</div>
											<div class="row">
												
												<div class="col-md-4 col-12">
													<div class="form-group mandatory">
														<label for="paymentmode"> Payment Mode : </label>
														<input type="text" id="paymentmode" name="paymentmode" class="form-control-line" value="<?= ucwords($paymentMode) ?>" readonly>
													</div>
												</div>												
											</div>
											
											<div class="row"> 
												<div class="col-md-12 col-12">
													<table id="pert_tbl" class="table table-sm table-stripped" style="width:100%;">
														<thead>
															<tr>
     															<th width="1%">Return</th>
																<th width="15%">Product</th>
																<th width="10%">Unit</th>
																<th width="15%">Quantity</th>
																<th width="12%">Price</th>
																<th width="10%">Tax Percent</th>
                                                                <th width="15%">Tax</th>																
																<th width="10%">Return Quantity</th>
																<th width="15%">Subtotal</th>
															</tr>
														</thead>
														<tbody>
															<?php
															$i = 0;
															if ($orderlineentries) {
			
																foreach ($orderlineentries->result_array() as $co) {
															    $i++;
															?>
																	<tr id="row<?= $i ?>">
																		<?php 
																		if($co['qty']!='' && $co['qty']>0){ ?>
																		<td>
																			<input type="checkbox"  style="width:25px;height:25px" onchange="enableReturnQty(<?= $i?>);" id="isReturn<?= $i?>" name="isReturn[]">
																			<input type="hidden" name="orderLineCode[]" id="orderLineCode<?= $i ?>" value="<?= $co['id'] ?>">
																		</td>
																		<?php }?> 																		
																		<td>
																		    <?= $co['productEngName']."-".$co["variantName"]?>																			
																		</td>
																		<td>
																			 <?= $co['unitName'] ?>									
																		</td>
																		<td>																		    
																			<input type="text" class="form-control" name="productQty[]" readonly id="productQty<?= $i ?>" value="<?= $co['qty'] ?>">
																		</td>
																		<td>
																			<input type="text" class="form-control" name="productPrice[]" readonly id="productPrice<?= $i ?>" value="<?= $co['price'] ?>">
																		</td>
																		<td>
																			<input type="text" class="form-control" name="tax[]" readonly id="tax<?= $i ?>" value="<?= $co['taxPercent'] ?>">
																		</td>
																		<td>
																			<input type="text" class="form-control" name="totaltax[]" readonly id="totaltax<?= $i ?>" value="<?= $co['tax'] ?>">
																		</td>
																		<td>
																			<input type="text" class="form-control" name="returnQty[]" onchange="checkReturnQty(<?= $i ?>);" id="returnQty<?= $i ?>" onkeypress="return isDecimal(event)">
																		</td>
																		<td>
																			<input type="text" class="form-control subTotalPrice" name="subtotal<?= $i ?>" readonly id="subtotal<?= $i ?>" value="<?= $co['totalPrice'] ?>">
																		</td>
																		
																	</tr>
															<?php }
															
															} ?>
														</tbody>
														<tfoot>
														<tr>
															<td colspan="6" class="text-right"><b>Total :</b></td>
															<td colspan="7">
																<input type="text" id="total" class="text-right form-control" name="total" value="<?= $row->totalAmount ?>" readonly>
															</td>
														</tr>
													</tfoot>
													</table>
												</div>
											</div>

                                            <div class="row">
												<div class="col-12 d-flex justify-content-end">
													<button type="submit" class="btn btn-success white me-1 mb-1 sub_1" id="saveReturnBtn">Order Return</button>
													
												</div>
											</div>											
										
								</div>
									<?php
								  } 
							}
									?>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
</div>
<script>



  function enableReturnQty(id){
		if ($("#isReturn"+id).is(':checked')) {
			$('#returnQty'+id).prop('disabled',false)
		}else{
			$('#returnQty'+id).prop('disabled',true)
		}
   }
   
   function isDecimal(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if ((charCode >= 48 && charCode <= 57) || (charCode >= 96 && charCode <= 105) || charCode == 8 || charCode == 9 || charCode == 37 ||
			charCode == 39 || charCode == 46 || charCode == 190) {
			return true;
		} else {
			return false;
		}
	}
   
   function calculate_subTotal(id) { 
	    var productQty = Number($('#productQty' + id).val());
		var returnQty = Number($('#returnQty' + id).val());
		var finalQty= productQty-returnQty;
		var productPrice = Number($('#productPrice' + id).val());
		var tax = Number($('#tax' + id).val());
        total=finalQty * productPrice;
		subTotal = ((total/100)*tax);
        finaltotal=total+subTotal;
		$('#subtotal' + id).val(finaltotal.toFixed(2)); 
		calculateTotal(); 
	}

	function calculateTotal() {
		 var total=0;
		$('.subTotalPrice').each(function() {
                total += Number($(this).val());
            });
		$('#total').val(total.toFixed(2)); 
	}
	
	function checkReturnQty(id) {
		var productQty = Number($('#productQty' + id).val());
		var returnQty = Number($('#returnQty' + id).val());
		if (returnQty != '' && productQty!=0  && productQty!='' && returnQty > 0) {
			if(returnQty>productQty){
				toastr.error('Return Quantity should be less than or equal to product quantity', 'Order Return', {
				"progressBar": false
			});
			$('#returnQty'+id).val('');
			$('#returnQty'+id).focus();
			return false
			}
		} else {
			toastr.error('Return Quantity should be greater than 0', 'Order Return', {
				"progressBar": false
			});
			$('#returnQty'+id).val('');
			$('#returnQty'+id).focus();
			return false;
		}
		calculate_subTotal(id);
	}

 	$(document).ready(function() {
		$('.cancel').removeClass('btn-default').addClass('btn-info');
		
		$("#inwardForm").parsley();
		/*$("form").on("submit", function(e) { 
			e.preventDefault();
            if($('input[type="checkbox"]').length==0){
				toastr.error('At lease one item should be returned', 'Order Return', {
							"progressBar": true
				});
				return false;
			}else{
				var table = document.getElementById("pert_tbl");
				var table_len = (table.rows.length) - 2;
				var tr = table.getElementsByTagName("tr");
				var ajax_complete = 0;
				var num = 0;
				for (i = 1; i <= table_len; i++) {
					var id = tr[i].id.substring(3);
					if(document.getElementById("isReturn"+id).checked && ($('#returnQty'+id).val()=='' || $('#returnQty'+id).val()==0 || isNaN( $("#returnQty"+id).val()))){
						toastr.error('Please provide valid return quantity', 'Order Return', {
							"progressBar": true
						});
						return false;
					}
				}
				
				for (i = 1; i <= table_len; i++) {
				var id = tr[i].id.substring(3);
				var branchCode = document.getElementById("branchCode").value;
				var orderCode = document.getElementById("orderCode").value;
				var orderLineCode = document.getElementById("orderLineCode"+id).value;
				var productQty = document.getElementById("productQty" + id).value;
				var returnQty = document.getElementById("returnQty" + id).value;
				var returnQty = document.getElementById("returnQty" + id).value;
				var total = document.getElementById("total").value;
				var checked=document.getElementById("isReturn"+id).checked;
				if (returnQty != '' && document.getElementById("isReturn"+id).checked) {
					num++;
					$.ajax({
						type: 'post',
						url: base_path + "Cashier/OrderReturn/saveOrderReturn",
						data: {
							branchCode: branchCode,
							orderLineCode: orderLineCode,
							returnQty: returnQty,
							productQty:productQty,
							count:num,
							total:total,
                            orderCode:orderCode							
						},
						beforeSend: function() {
							$('#saveReturnBtn').prop('disabled', true);
							$('#cancelReturnBtn').prop('disabled', true);
							$('#saveReturnBtn').text('Please wait..');
						},
						success: function(response) {
							//ajax_complete++;
							if (num >0) {
								toastr.success('Order returned successfully', 'Order Return', {
									"progressBar": true
								});
								location.href = base_path + "index.php/Cashier/OrderReturn/listRecords";
							} else {
								toastr.success('Failed to return Order', 'Order Return', {
									"progressBar": true
								});
								$('#saveReturnBtn').text('Save');
								$('#saveReturnBtn').prop('disabled', false);
								$('#cancelReturnBtn').prop('disabled', false); 
							}
						}
					});
				}
			}
				
			}					
		});*/
		
		
		$('.totalOrderReturn').click(function() {
            var orderCode = $("#orderCode").val();
            swal({
                //title: "Are you sure?",
                //text: "You want to delete Supplier Record of " + code,
                title: "Are you sure to return this order?",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: !1,
                closeOnCancel: !1
            }, function(e) {
                if (e) {
                    $.ajax({
                        url: base_path + "Cashier/OrderReturn/saveOrder",
                        type: 'POST',
                        data: {
                            'orderCode': orderCode
                        },
                        success: function(data) {
                            swal.close();
                            if (data) {
                                toastr.success('Order Return successfully', 'Order Return', {
                                    "progressBar": true
                                });
								 window.location.href=base_path + 'index.php/Cashier/OrderReturn/listRecords';
                            } else {
                                toastr.error('Order Return Cancel', 'Order Return', {
                                    "progressBar": true
                                });
                            }
                        }
                    });
                } else {
                    swal.close();
                }
            });
        });
 		
 	});
</script>