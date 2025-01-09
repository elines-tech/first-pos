<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Inward</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-dashboard"></i><?php echo $translations['Dashboard']?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $translations['Inward']?></li>
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
						<h3>Edit Inward<span style="float:right"><a href="<?= base_url()?>inward/listRecords" class="btn btn-sm btn-primary">Back</a></span></h3>
					</div>
					<div class="card-content">
						<div class="card-body">
							<form id="inwardForm" class="form" method="post" enctype="multipart/form-data" data-parsley-validate>
								<?php
								echo "<div class='text-danger text-center' id='error_message'>";
								if (isset($error_message)) {
									echo $error_message;
								}
								echo "</div>";
								?>
								<?php if ($inwardData) {
									$result = $inwardData->result_array()[0];
								?>
									<div class="row">
										<div class="col-md-12 col-12">
											<div class="row">
												<div class="col-md-3 col-12">
													<div class="form-group mandatory">
														<label for="" class="form-label">Batch No</label>
														<input type="text" id="batchNo" class="form-control" name="batchNo" required disabled value="<?= $result['batchNo']?>">
													</div>
												</div>
												<div class="col-md-3 col-12">
													<div class="form-group mandatory">
														<label for="" class="form-label">Inward</label>
														<input type="date" id="inwardDate" class="form-control" name="inwardDate" id="inwardDate" value="<?= date('Y-m-d', strtotime($result['inwardDate'])) ?>" required>
													</div>
												</div>
												<div class="col-md-3 col-12">
													<div class="form-group mandatory">
														<label for="product-name" class="form-label">Branch</label>
														<input type="hidden" class="form-control" id="inwardCode" name="inwardCode" value="<?= $result['code'] ?>">
														<select class="form-select select2" name="branchCode" id="branchCode" data-parsley-required="true" required>
															<option value="">Select</option>
															<?php if ($branch) {
																foreach ($branch->result() as $br) {
																	if ($result['branchCode'] == $br->code) {
																		echo '<option value="' . $br->code . '" selected>' . $br->branchName . '</option>';
																	} else {
																		echo '<option value="' . $br->code . '">' . $br->branchName . '</option>';
																	}
																}
															} ?>
														</select>
													</div>
												</div>
												<div class="col-md-3 col-12">
													<div class="form-group mandatory">
														<label for="product-name" class="form-label">Supplier</label>
														<select class="form-select select2" name="supplierCode" id="supplierCode" data-parsley-required="true" required>
															<option value="">Select</option>
															<?php if ($supplier) {
																foreach ($supplier->result() as $sr) {
																	if ($result['supplierCode'] == $sr->code) {
																		echo '<option value="' . $sr->code . '" selected>' . $sr->supplierName . '</option>';
																	} else {
																		echo '<option value="' . $sr->code . '">' . $sr->supplierName . '</option>';
																	}
																}
															} ?>
														</select>
													</div>
												</div>

											</div>
										<div class="row">
											<div class="col-md-3 col-12">
												<div class="form-group">
													<label for="product-name" class="form-label">Reference</label>
													<input type="text" class="form-control" name="refNo" id="refNo" value="<?= $result['ref']?>">
												</div>
											</div>
										</div>
											<div class="row">
												<div class="col-md-12 col-12">
													<table id="pert_tbl" class="table table-sm table-stripped" style="width:100%;">
														<thead>
															<tr>
																<th width="18%">Product</th>
																<th width="22%">Unit</th>
																<th width="5%">Expiry Date</th>
																<th width="5%">Quantity</th>
																<th width="15%">Price</th>
																<th width="10%">Tax</th>
																<th width="30%">Subtotal</th>
																<th width="1%"></th>
																
															</tr>
														</thead>
														<tbody>
															<?php
															$i = 0;
															if ($inwardLineEntries) {
																foreach ($inwardLineEntries->result_array() as $co) {
																	$i++;
															?>
																	<tr id="row<?= $i ?>">
																		<td>
																			<input type="hidden" class="form-control" name="inwardLineCode<?= $i ?>" id="inwardLineCode<?= $i ?>" value="<?= $co['code'] ?>">
																			<select class="form-select select2" style="width:100%" id="itemCode<?= $i ?>" name="itemCode<?= $i ?>" onchange="checkDuplicateItem(<?= $i ?>);getItemStorageUnit(<?= $i ?>)">
																				<option value="">Select Product</option>
																				<?php
																				if ($items) {
																					foreach ($items->result() as $it) {
																						if ($it->code == $co['productCode'] && $it->variantCode==$co['variantCode']) {
																							if($it->variantName!=""){
																								echo "<option value='" .$it->productEngName. "-".$it->variantName."' id='".$it->variantCode."' data-val='".$it->code."' data-sku='".$it->variantSKU."'selected>" . $it->productEngName . "-".$it->variantName."</option>";
																							}
																							else{
																								echo "<option value='" . $it->productEngName . "' data-val='".$it->code."' data-sku='".$it->sku."' selected>" . $it->productEngName . "</option>";
																							}
																						}else{
																							if($it->variantName!=""){
																								echo "<option value='" .$it->productEngName. "-".$it->variantName."' id='".$it->variantCode."' data-val='".$it->code."' data-sku='".$it->variantSKU."'>" . $it->productEngName . "-".$it->variantName."</option>";
																							}
																							else{
																								echo "<option value='" . $it->productEngName . "' data-val='".$it->code."' data-sku='".$it->sku."'>" . $it->productEngName . "</option>";
																							} 
			
																						} 																				
																					}
																				}
																				?>
																			</select>
																		</td>
																		<td>
																			<input type="hidden" class="form-control"  id="itemUnit<?= $i ?>" name="itemUnit<?= $i ?>" value="<?= $co['productUnit'] ?>" readonly>
																			<input type="text" class="form-control"  id="itemUnitName<?= $i ?>" name="itemUnitName<?= $i ?>" value="<?= $co['unitName'] ?>" readonly>	
																				
																		</td>
																		<td>
																		  <input type="date" class="form-control" name="expiryDate<?= $i ?>" id="expiryDate<?= $i ?>" onblur="validateExpiryDate('<? $i ?>')" value="<?php if($co['expiryDate']!='' && $co['expiryDate']!='0000-00-00'){ echo date('Y-m-d', strtotime($co['expiryDate']));} ?>">
																		</td>
																		<td>
																			<input type="text" class="form-control" name="itemQty<?= $i ?>" onchange="checkQty(<?= $i ?>);" id="itemQty<?= $i ?>" value="<?= $co['productQty'] ?>" onkeypress="return isNumber(event)" onkeyup="calculate_subTotal(<?= $i ?>)">
																		</td>
																		<td>
																			<input type="text" class="form-control" name="itemPrice<?= $i ?>" onchange="checkPrice(<?= $i ?>);" id="itemPrice<?= $i ?>" value="<?= $co['productPrice'] ?>" onkeypress="return isNumber(event)" onkeyup="calculate_subTotal(<?= $i ?>)">
																		</td>
																		<td>
																		  <input type="text" class="form-control" name="itemTax<?= $i ?>" id="itemTax<?= $i ?>" onchange="checkPrice(<?= $i ?>);" onkeypress="return isDecimal(event)" onkeyup="calculate_subTotal(<?= $i ?>)" value="<?= $co['tax'] ?>">
																		</td>
																		<td>
																			<input type="text" class="form-control" name="subTotal<?= $i ?>" id="subTotal<?= $i ?>" disabled value="<?= $co['subTotal'] ?>">
																		</td>
																		<td>
																			<a href="#" class="btn btn-danger" onclick="delete_row(<?= $i ?>,'<?= $co['code'] ?>')"><i class="fa fa-trash"></i>
																		</td>
																		
																	</tr>
															<?php }
															} ?>
															<tr id="row0">
																<td>
																	<input type="hidden" class="form-control" name="inwardLineCode0" id="inwardLineCode0"> 
																	<select class="form-select select2" style="width:100%" id="itemCode0" name="itemCode0" onchange="checkDuplicateItem(0);getItemStorageUnit(0)">
																		<option value="">Select Product</option>
																		<?php
																		if ($items) {
																			foreach ($items->result() as $it) {
																				if($it->variantName!=""){
																				  echo "<option value='" .$it->productEngName. "-".$it->variantName."' id='".$it->variantCode."' data-val='".$it->code."' data-sku='".$it->variantSKU."'>" . $it->productEngName ."-".$it->variantName."</option>";
																				}
																				else{
																					echo "<option value='" . $it->productEngName . "' data-val='".$it->code."' data-sku='".$it->sku."'>" . $it->productEngName ."</option>";
																				}
																			}
																		}
																		?>
																	</select>
																</td>
																<td>
																	<input type="hidden" class="form-control"  id="itemUnit0" name="itemUnit0">
																	<input type="text" class="form-control"  id="itemUnitName0" name="itemUnitName0" readonly>	
																				
																</td>
																<td>
																   <input type="date" class="form-control" name="expiryDate0" id="expiryDate0" onblur="validateExpiryDate(0)" >
																</td>
																<td>
																	<input type="text" class="form-control" name="itemQty0" id="itemQty0" onkeypress="return isNumber(event)" onkeyup="calculate_subTotal(0)">
																</td>
																<td>
																	<input type="text" class="form-control" name="itemPrice0" id="itemPrice0" onkeypress="return isDecimal(event)" onkeyup="calculate_subTotal(0)">
																</td>
																<td>
																  <input type="text" class="form-control" name="itemTax0" id="itemTax0"  onkeypress="return isDecimal(event)" onkeyup="calculate_subTotal(0)">
																</td>
																<td>
																	<input type="text" class="form-control" name="subTotal0" id="subTotal0" disabled> 
																</td>
																
																<td>
																	<a href="#" class="btn btn-success" onclick="add_row()"><i class="fa fa-plus"></i>
																</td>
															
															</tr>
														</tbody>
														 <tfoot>
															<tr>
																<td colspan="6" class="text-right"><b>Total :</b></td>
																<td colspan="7">
																	<input type="text" id="total" class="form-control" name="total" value="<?= $result['total']?>" disabled>
																</td>
															</tr>
														</tfoot>
													</table>
												</div>
											</div>
											<div class="row">
												<div class="col-12 d-flex justify-content-end">
													<button type="submit" class="btn btn-primary white me-2 mb-1 sub_1 submitBtn" id="approveInwardBtn" value="1">Save & Approve</button>
                                        <button type="submit" class="btn btn-success white me-2 mb-1 sub_1 submitBtn" id="saveInwardBtn" value="2">Save</button>
													<button type="button" id="cancelInwardBtn" class="btn btn-light-secondary me-1 mb-1">Reset</button>
												</div>
											</div>
										</div>
									</div>
								<?php }
								?>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('.cancel').removeClass('btn-default').addClass('btn-info');
		var today = new Date().toISOString().split('T')[0];
		document.getElementsByName("inwardDate")[0].setAttribute('max', today);
		$("#inwardForm").parsley();
	
		 $(".submitBtn").click(function (e) {
			e.preventDefault();
			var approve = $(this).val();
			var btnId=$(this).attr('id')
            var formData = new FormData($('#inwardForm')[0]);
            var form = $('#inwardForm');
			var isActive = 0;
			if ($("#isActive").is(':checked')) {
				isActive = 1; 
			}
			form.parsley().validate();
            if (form.parsley().isValid()) {
               
				formData.append('total', $('#total').val());
				formData.append('batchNo', $('#batchNo').val());
				formData.append('isActive', isActive)
				formData.append('refNo', $('#refNo').val())
				
				var table = document.getElementById("pert_tbl");
				var table_len = (table.rows.length) - 2;
				var tr = table.getElementsByTagName("tr");
				debugger
				if (table_len == 1 && ($('#itemCode0').val() == '' || $('#itemQty0').val() == '' || $('#itemUnit0').val() == '' || $('#itemPrice0').val() == '' || $('#subTotal0').val() == '' || $('#itemTax0').val() == '')) {
					toastr.error('Please provide at least one entry', 'Inward', {
						"progressBar": true
					});
				} else {
					/*for ( j= 1; j <= table_len; j++) {
						 var id = tr[j].id.substring(3);
						if($('#itemCode'+id).val() == '' || $('#itemQty'+id).val() == '' || $('#itemUnit'+id).val() == '' || $('#itemPrice'+id).val() == '' || $('#subTotal'+id).val() == '' || $('#itemTax'+id).val() == '') {
							toastr.error('Please provide all the details', 'Inward', {
								"progressBar": true
							});
							return false;
						}
					}*/
					$.ajax({
						type: "POST",
						url: base_path + "inward/saveInward",
						enctype: 'multipart/form-data',
						contentType: false,
						processData: false,
						data: formData,
						beforeSend: function() {
							$('#'+btnId).prop('disabled', true);
								$('#cancelTransferBtn').prop('disabled', true);
								$('#'+btnId).text('Please wait..');
						},
						success: function(data) {
							
							 $('#'+btnId).text('Save');
								$('#'+btnId).prop('disabled', false);
							$('#cancelInwardBtn').prop('disabled', false);
							var obj = JSON.parse(data);

							if (obj.status) {
								var ajax_complete = 0;
								var num = 0;
								for (i = 1; i <= table_len; i++) {
									var id = tr[i].id.substring(3);
									document.getElementById("inwardCode").value = obj.inwardCode;
								var branchCode = $('#branchCode').val();
									var batchNo = obj.batchNo;
									var inwardCode = document.getElementById("inwardCode").value;
									var inwardLineCode = document.getElementById("inwardLineCode" + id).value;
									var itemCode = document.getElementById("itemCode" + id).value;
									var itemUnit = document.getElementById("itemUnit" + id).value;
									var itemQty = document.getElementById("itemQty" + id).value;
									var itemPrice = document.getElementById("itemPrice" + id).value;
									var itemTax = document.getElementById("itemTax" + id).value;
									var subTotal = document.getElementById("subTotal" + id).value;
									var expiryDate = document.getElementById("expiryDate" + id).value;
									var sku=$("#itemCode" +id).find('option:selected').attr('data-sku');
									var variantCode=$("#itemCode" +id).find('option:selected').attr('id');
									var productCode=$("#itemCode" +id).find('option:selected').attr('data-val');
									
									if ( itemTax != '' && itemCode != '' && itemUnit != '' && itemQty != '' && itemPrice != '' && subTotal != '') {
										num++;
										$.ajax({
											type: 'post',
											url: base_path + "inward/saveInwardLine",
											data: {
												inwardCode: inwardCode,
												inwardLineCode: inwardLineCode,
												itemCode: itemCode,
												itemUnit: itemUnit,
												itemQty: itemQty,
												itemPrice: itemPrice,
												subTotal: subTotal,
												branchCode: branchCode,
												batchNo: batchNo,
												expiryDate: expiryDate,
												variantCode:variantCode,
												productCode:productCode,
												itemTax: itemTax,
												sku:sku,
												approve:approve,
											},
											success: function(response) {
												
												ajax_complete++;
												if (num == ajax_complete) {
													toastr.success(obj.message, 'Inward', {
														"progressBar": true
													});
													location.href = base_path + "inward/listRecords"
												} else {}
											}
										});
									}
								}
							} else {
								toastr.error(obj.message, 'Inward', {
									"progressBar": true
								});
							}
						}
					});
				}
			}				
		});
	});

	function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
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

	function  validateExpiryDate(id){
		var  today = "<?=  date('Y-m-d') ?>";
		var expiryDate = $('#expiryDate'+id).val();
		if(expiryDate<=today && expiryDate!=''){
			toastr.error('Expiry date must be greater than today', 'Invalid expiry dates', {"progressBar": false});
			$('#expiryDate'+id).val('');
			$('#expiryDate'+id).focus();
			return false;
		}
	}
	function add_row() {
		var table = document.getElementById("pert_tbl");
		var table_len = (table.rows.length) - 2;
		var tr = table.getElementsByTagName("tr");
		var id = 0;
		var inwardLineCode = document.getElementById("inwardLineCode0").value;
		var itemCode = document.getElementById("itemCode0").value;
		var itemUnit = document.getElementById("itemUnit0").value;
		var itemUnitName = document.getElementById("itemUnitName0").value;
		var itemQty = document.getElementById("itemQty0").value;
		var itemPrice = document.getElementById("itemPrice0").value;
		var subTotal = document.getElementById("subTotal0").value;
		var itemTax = document.getElementById("itemTax0").value;
		var expiryDate = document.getElementById("expiryDate0").value;
		
		if(itemCode==''){
			toastr.error('Please provide Product', 'Inward Products', {"progressBar": false});
			$('#itemCode0').focus()
			return false;
		}
		if(itemUnit==''){
			toastr.error('Please provide Unit', 'Inward Products', {"progressBar": false});
			$('#itemUnit0').focus()
			return false;
		}
		if(itemPrice==''){
			toastr.error('Please provide Price', 'Inward Products', {"progressBar": false});
			$('#itemPrice0').focus()
			return false;
		}
		if(itemQty==''){
			toastr.error('Please provide Quantity', 'Inward Products', {"progressBar": false});
			$('#itemQty0').focus()
			return false;
		}
		
		if(itemTax==''){
			toastr.error('Please provide Tax', 'Inward Products', {"progressBar": false});
			$('#itemTax0').focus()
			return false;
		}
		if(subTotal==''){
			toastr.error('Please provide Subtotal', 'Inward Products', {"progressBar": false});
			$('#subTotal0').focus()
			return false;
		}
		for (i = 1; i < table_len; i++) {
			var n = tr[i].id.substring(3);
			var id = n;
		}
		id++;
		calculateTotal();
		var row = table.insertRow(table_len).outerHTML = '<tr id="row' + id + '">' +
			'<td><select class="form-select select2" style="width:100%" name="itemCode' + id + '" id="itemCode' + id + '">' +
			document.getElementById("itemCode0").innerHTML +
			'</select>' +
			'<input type="hidden" class="form-control" style="width:100%" name="inwardLineCode' + id + '" id="inwardLineCode' + id + '" value="' + inwardLineCode + '"></td>' +
			'<td><input type="hidden" class="form-control" name="itemUnit' + id + '" id="itemUnit' + id + '" value="'+itemUnit+'"><input type="text" class="form-control" name="itemUnitName' + id + '" id="itemUnitName' + id + '" value="'+itemUnitName+'" readonly>' +
			'</td>' +
			'<td><input type="date" class="form-control" name="expiryDate' + id + '" id="expiryDate' + id + '" value="'+expiryDate+'"></td>' +
			'<td><input type="text" class="form-control" name="itemQty' + id + '" id="itemQty' + id + '" value="' + itemQty + '" onkeyup="calculate_subTotal(' + id + ')" onchange="checkQty(' + id + ');" onkeypress="return isNumber(event)"></td>' +
			'<td><input type="text" class="form-control" name="itemPrice' + id + '" id="itemPrice' + id + '" value="' + itemPrice + '" onkeyup="calculate_subTotal(' + id + ')" onchange="checkPrice(' + id + ');" onkeypress="return isDecimal(event)"></td>' +
			'<td><input type="text" class="form-control" name="itemTax' + id + '" id="itemTax' + id + '" value="' + itemTax + '" onkeyup="calculate_subTotal(' + id + ')" onkeypress="return isDecimal(event)"></td>' +
			'<td><input type="text" class="form-control" name="subTotal' + id + '" id="subTotal' + id + '" value="' + subTotal + '" disabled></td>' +
			'<td><a href="#" class="btn btn-danger" onclick="delete_row(' + id + ')"><i class="fa fa-trash"></i></a></td>' +
			
			'</tr>';
		document.getElementById("itemCode" + id).value = itemCode;
		document.getElementById("itemUnit" + id).value = itemUnit;
		document.getElementById("itemTax0").value = "";
		document.getElementById("expiryDate0").value = "";
		$("#itemCode0").val('').trigger('change');
	
		document.getElementById("itemQty0").value = "";
		document.getElementById("itemPrice0").value = "";
		
		document.getElementById("subTotal0").value = "";
		document.getElementById("itemCode0").focus();
	}

	function delete_row(id, lineCode) {
		swal({
			title: "Are you sure?",
			text: "You want to delete this product ",
			type: "warning",
			showCancelButton: !0,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, delete it!",
			cancelButtonText: "No, cancel it!",
			closeOnConfirm: !1,
			closeOnCancel: true
		}, function(e) {
			if (e) {
				$.ajax({
					url: base_path + "inward/deleteInwardLine",
					type: 'POST',
					data: {
						'lineCode': lineCode
					},
					dataType: "JSON",
					success: function(response) {
						if (response) {
							swal({
									title: "Completed",
									text: "Successfully Deleted",
									type: "success"
								},
								function(isConfirm) {
									if (isConfirm) {
										var row = document.getElementById("row" + id);
										row.parentNode.removeChild(row);
										calculateTotal();
										swal.close();
									}
								});
						} else {
							toastr.success('Record Not Deleted', 'Failed', {
								"progressBar": true
							});
							swal.close()
						}
					}
				});
			}else{
				swal.close()
			}
		});
	}


	function checkDuplicateItem(id) {
		var table = document.getElementById("pert_tbl");
		var table_len = (table.rows.length) - 2;
		var tr = table.getElementsByTagName("tr");
		var itemCode = document.getElementById("itemCode" + id).value.toLowerCase();
		if (itemCode != "") {
			for (i = 1; i <= table_len; i++) {
				var row_id = tr[i].id.substring(3);
				var itemCode_row = document.getElementById("itemCode" + row_id).value.toLowerCase();
				if (itemCode_row == itemCode && row_id != id) {
					toastr.error('Item already exists', 'Duplicate inward item', {
						"progressBar": true
					});
					
					$("#itemCode"+id).val('').trigger('change');
					document.getElementById("itemCode" + id).focus();
					return false;
				}
			}
		}
	}

	function calculate_subTotal(id) {
		var itemQty = Number($('#itemQty' + id).val());
		var itemPrice = Number($('#itemPrice' + id).val());
		var tax = Number($('#itemTax' + id).val());
		total=itemQty * itemPrice;
		subTotal = ((total/100)*tax);
        finaltotal=total+subTotal;
		$('#subTotal' + id).val(finaltotal.toFixed(2));
		//calculateTotal(); 
	}

	function checkPrice(id) {
		var itemPrice = Number($('#itemPrice' + id).val());
		if (itemPrice != '' && itemPrice > 0) {} else {
			$('#itemPrice' + id).val('');
			toastr.error('Item price should be greater than 0', 'Transfer Products', {
				"progressBar": false
			});
			return false;
		}
	}


	function checkQty(id) {
		var itemQty = Number($('#itemQty' + id).val());
		if (itemQty != '' && itemQty > 0) {} else {
			$('#itemQty' + id).val('');
			toastr.error('Item Quantity should be greater than 0', 'Transfer Products', {
				"progressBar": false
			});
			return false;
		}

	}

	function calculateTotal() {
		var table = document.getElementById("pert_tbl");
		var table_len = (table.rows.length) - 2;
		var tr = table.getElementsByTagName("tr");
		var total = 0;
		for (i = 1; i <= table_len; i++) {
			var id = tr[i].id.substring(3);
			total = Number(total) + Number($('#subTotal' + id).val());
		}
		$('#total').val(total.toFixed(2));
	}

	function getItemStorageUnit(id) {
		var itemCode = $("#itemCode" +id).find('option:selected').attr('data-val');
		
		if (itemCode != '') {
			$.ajax({
				url: base_path + "inward/getItemStorageUnit",
				type: 'POST',
				data: {
					'itemCode': itemCode
				},
				success: function(response) {
					var obj = JSON.parse(response);
					if (obj.storageUnit != '') {
						//$('#qrCodeString' + id).val(obj.qrCode);
						//$('#qrCode' + id).attr('src',obj.qrCodeImage);
						$('#itemUnit' + id).val(obj.storageUnit);
						$('#itemUnitName' + id).val(obj.storageUnitName);
					} else {
						$("#itemCode" + id).val('').trigger('change');
					}
				}
			});
		}
	}
</script>