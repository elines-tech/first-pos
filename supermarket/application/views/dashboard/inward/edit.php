<?php
$product = "";
if ($items) {
	foreach ($items->result() as $it) {
		if ($it->variantName != "" || $it->variantName != null) {
			$product .= "<option value='" . $it->productEngName . "-" . $it->variantName . "' data-variant='" . $it->variantCode . "' data-val='" . $it->code . "' data-sku=" . $it->variantSKU . ">" . $it->productEngName . "-" . $it->variantName . "</option>";
		} else {
			$product .= "<option value='" . $it->productEngName . "'  data-variant='' data-val='" . $it->code . "' data-sku=" . $it->sku . ">" . $it->productEngName . "</option>";
		}
	}
}
?>
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
							<li class="breadcrumb-item"><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
							<li class="breadcrumb-item active" aria-current="page">Inward</li>
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
							<h3>Edit Inward<span style="float:right"><a id="cancelDefaultButton" href="<?= base_url() ?>inward/listRecords" class="btn btn-sm btn-primary">Back</a></span></h3>
						</div>
						<div class="card-content">
							<div class="card-body">
								<form id="inwardForm" class="form" action="<?= base_url('inward/updateInwards') ?>" method="post" enctype="multipart/form-data" data-parsley-validate>
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
															<input type="text" id="batchNo" class="form-control" name="batchNo" required readonly value="<?= $result['batchNo'] ?>">
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
															<?php if ($branchCode != "") { ?>
																<input type="hidden" class="form-control" name="branchCode" value="<?= $branchCode; ?>" readonly>
																<input type="text" class="form-control" name="branchName" value="<?= $branchName; ?>" readonly>
															<?php } else { ?>
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
															<?php } ?>
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
													<div class="col-md-12 col-12">
														<div class="form-group">
															<label for="product-name" class="form-label">Reference</label>
															<input type="text" class="form-control" name="refNo" id="refNo" value="<?= $result['ref'] ?>">
														</div>
													</div>
												</div>
												<div id="add_fields_section">
													<?php
													$i = 1;
													if ($inwardLineEntries) {
														foreach ($inwardLineEntries->result_array() as $co) {
													?>
															<div class="form-group removeclassP<?= $i ?>">
																<div class="row">
																	<div class="col-md-3 col-12">
																		<div class="form-group mandatory">
																			<label class="form-label">Product</label>
																			<input type="hidden" class="form-control" name="inwardLineCode[]" id="inwardLineCode<?= $i ?>" value="<?= $co['code'] ?>">
																			<select class="form-select select2 items" required id="itemCode<?= $i ?>" style="width:100%" name="itemCode[]" onchange="getItemStorageUnit(<?= $i ?>);checkDuplicateItem(<?= $i ?>);">
																				<option value="">Select Item</option>
																				<?php
																				if ($items) {
																					foreach ($items->result() as $it) {
																						if ($it->code == $co['productCode'] && $it->variantCode == $co['variantCode']) {
																							if ($it->variantName != "") {
																								echo "<option value='" . $it->productEngName . "-" . $it->variantName . "' id='" . $it->variantCode . "' data-val='" . $it->code . "' data-sku='" . $it->variantSKU . "'selected>" . $it->productEngName . "-" . $it->variantName . "</option>";
																							} else {
																								echo "<option value='" . $it->productEngName . "' data-val='" . $it->code . "' data-sku='" . $it->sku . "' selected>" . $it->productEngName . "</option>";
																							}
																						} else {
																							if ($it->variantName != "") {
																								echo "<option value='" . $it->productEngName . "-" . $it->variantName . "' id='" . $it->variantCode . "' data-val='" . $it->code . "' data-sku='" . $it->variantSKU . "'>" . $it->productEngName . "-" . $it->variantName . "</option>";
																							} else {
																								echo "<option value='" . $it->productEngName . "' data-val='" . $it->code . "' data-sku='" . $it->sku . "'>" . $it->productEngName . "</option>";
																							}
																						}
																					}
																				}
																				?>
																			</select>
																		</div>
																	</div>
																	<div class="col-md-3 col-12">
																		<div class="form-group mandatory">
																			<label class="form-label">Item Unit</label>
																			<input type="hidden" class="form-control" name="itemUnit[]" id="itemUnit<?= $i ?>" value="<?= $co['productUnit'] ?>" readonly>
																			<input type="text" class="form-control" name="itemUnitName[]" id="itemUnitName<?= $i ?>" value="<?= $co['unitName'] ?>" readonly>
																			<input type="hidden" class="form-control" name="productCode[]" id="productCode<?= $i ?>" value="<?= $co['productCode'] ?>" readonly>
																			<input type="hidden" class="form-control" name="variantCode[]" id="variantCode<?= $i ?>" value="<?= $co['variantCode'] ?>" readonly>
																			<input type="hidden" class="form-control" name="sku[]" id="sku<?= $i ?>" value="<?= $co['sku'] ?>" readonly>
																		</div>
																	</div>
																	<div class="col-md-3 col-12">
																		<div class="form-group mandatory">
																			<label class="form-label">Expiry Date</label>
																			<input type="date" class="form-control" name="expiryDate[]" id="expiryDate<?= $i ?>" onblur="validateExpiryDate(<?= $i ?>)" value="<?php if ($co['expiryDate'] != '' && $co['expiryDate'] != '0000-00-00') {
																																																					echo date('Y-m-d', strtotime($co['expiryDate']));
																																																				} ?>">
																		</div>
																	</div>
																	<div class="col-md-3 col-12">
																		<div class="form-group mandatory">
																			<label class="form-label">Quantity</label>
																			<input type="number" min="0" max="99999" step="0.01" required class="form-control" name="itemQty[]" id="itemQty<?= $i ?>" onchange="checkQty(<?= $i ?>);" value="<?= $co['productQty'] ?>" onkeyup="calculate_subTotal(<?= $i ?>)">
																		</div>
																	</div>
																	<div class="col-md-3 col-12">
																		<div class="form-group mandatory">
																			<label class="form-label">Price</label>
																			<input type="number" min="0" max="9999999" step="0.01" required class="form-control" name="itemPrice[]" id="itemPrice<?= $i ?>" value="<?= $co['productPrice'] ?>" onchange="checkPrice(<?= $i ?>);" onkeyup="calculate_subTotal(<?= $i ?>)">
																		</div>
																	</div>
																	<div class="col-md-3 col-12">
																		<div class="form-group">
																			<label class="form-label">Tax</label>
																			<input type="number" min="0" max="99" step="0.01" required class="form-control" name="itemTax[]" id="itemTax<?= $i ?>" onchange="checkTax(<?= $i ?>);" onkeyup="calculate_subTotal(<?= $i ?>)" value="<?= $co['tax'] ?>">
																		</div>
																	</div>
																	<div class="col-md-3 col-12">
																		<div class="form-group mandatory">
																			<label class="form-label">Subtotal</label>
																			<input type="number" min="0" max="99999999999" step="0.01" required class="form-control subtotal" name="subTotal[]" id="subTotal<?= $i ?>" value="<?= $co['subTotal'] ?>" readonly>
																		</div>
																	</div>
																	<div class="col-md-3 col-12 mt-4">
																		<a href="#" class="btn btn-danger" onclick="delete_row(<?= $i ?>,'<?= $co['code'] ?>')"><i class="fa fa-trash"></i></a>
																	</div>
																</div>
																<hr>
															</div>
													<?php
															$i += 1;
														}
													}
													?>
												</div>
												<div id="pricesection_add_btn">
													<div class="col-md-1 mb-3">
														<?php if ($i == 1) { ?>
															<button class="btn btn-success" type="button" onclick="inward_rows(1,'add');"><i class="fa fa-plus"></i></button>
														<?php } else { ?>
															<button class="btn btn-success" type="button" onclick="inward_rows(<?= $i - 1 ?>,'edit');"><i class="fa fa-plus"></i></button>
														<?php } ?>
													</div>
												</div>
												<div class="row">
													<div class="col-md-4 offset-md-8 col-12 mb-2">
														<div class="form-group mandatory">
															<label for="" class="form-label">Total</label>
															<input type="number" step="0.01" id="total" class="form-control" name="total" required readonly value="<?= $result['total'] ?>">
														</div>
													</div>
													<div class="col-12 d-flex justify-content-end">
														<button id="saveDefaultButton" type="submit" class="btn btn-primary submitBtn" name="approveInwardBtn" value="1">Save & Approve</button>
														<button id="saveInwardBtn" type="submit" class="btn btn-success submitBtn" name="saveInwardBtn" value="2">Save</button>
														<button type="reset" id="cancelInwardBtn" class="btn btn-light-secondary ">Reset</button>
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
</div>
<script type="text/javascript">
	var room = 1;
	var product = "<?= $product ?>";

	$(document).ready(function() {
		$('.cancel').removeClass('btn-default').addClass('btn-info');
		var today = new Date().toISOString().split('T')[0];
		document.getElementsByName("inwardDate")[0].setAttribute('max', today);
		$("#inwardForm").parsley();
		$("body").delegate(".add_fields", "click", function() {
			var pos = $(this).data('id');
			var flag = $(this).data('flag');
			inward_rows(pos, flag);
		});

		$("body").delegate(".remove_price_fields", "click", function(e) {
			e.preventDefault();;
			var remove_room_id = Number($(this).data('id'));
			swal({
				title: "Are you sure?",
				text: "You want to delete this item ",
				type: "warning",
				showCancelButton: !0,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Yes, delete it!",
				cancelButtonText: "No, cancel it!",
				closeOnConfirm: !1,
				closeOnCancel: !1
			}, function(e) {
				if (e) {
					swal.close();
					$(".removeclassP" + remove_room_id).remove();
					$("#pricesection_add_btn").empty().append('<div class="col-md-1 mb-3"><button type="button" data-id="' + (remove_room_id - 1) + '" class="btn btn-success add_fields" ><i class="fa fa-plus"></i></button></div>');
					calculateTotal();
				} else {
					swal.close();
				}
			});
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

	function validateExpiryDate(id) {
		var today = "<?= date('Y-m-d') ?>";
		var expiryDate = $('#expiryDate' + id).val();
		if (expiryDate <= today && expiryDate != '') {
			toastr.error('Expiry date must be greater than today', 'Invalid expiry dates', {
				"progressBar": false
			});
			$('#expiryDate' + id).val('');
			$('#expiryDate' + id).focus();
			return false;
		}
	}


	function calculate_subTotal(id) {
		var itemQty = Number($('#itemQty' + id).val());
		var itemPrice = Number($('#itemPrice' + id).val());
		var tax = Number($('#itemTax' + id).val());
		total = itemQty * itemPrice;
		subTotal = ((total / 100) * tax);
		finaltotal = total + subTotal;
		$('#subTotal' + id).val(finaltotal.toFixed(2));
		calculateTotal();
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
			toastr.error('Item Tax should be greater than 0', 'Transfer Products', {
				"progressBar": false
			});
			return false;
		}

	}

	function calculateTotal() {
		let total = Number(0);
		var inrows = document.querySelectorAll("input.subtotal");
		if (inrows.length > 0) {
			var subtotal = $();
			$("input.subtotal").each(function(index, element) {
				total += Number(element.value);
			});
		}
		$('#total').val(total.toFixed(2));
	}

	function checkDuplicateItem(id) {
		var itemCode = $("#itemCode" + id).val();
		var cls = document.getElementsByClassName('items');
		for (row_id = 0; row_id < cls.length; row_id++) {
			var row_code = $("#itemCode" + row_id).val();
			if ((row_code == itemCode) && id != row_id) {
				toastr.error("Item already exist", "Inward");
				$("#itemCode" + id).val(null).trigger('change');
				$("#productCode" + id).val('');
				$("#variantCode" + id).val('');
				$("#sku" + id).val('');
				$("#itemUnit" + id).val('');
				$("#itemUnitName" + id).val('');
				return false;
			}
		}
	}

	function getItemStorageUnit(id) {
		var itemCode = $("#itemCode" + id).find('option:selected').attr('data-val');
		var variant = $("#itemCode" + id).find('option:selected').attr('data-variant');
		var sku = $("#itemCode" + id).find('option:selected').attr('data-sku');
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
						$('#productCode' + id).val(itemCode);
						$('#variantCode' + id).val(variant);
						$('#sku' + id).val(sku);
					} else {
						$("#itemCode" + id).val('').trigger('change');
						$("#productCode" + id).val('');
						$("#variantCode" + id).val('');
						$("#sku" + id).val('');
						$('#itemUnit' + id).val('');
						$('#itemUnitName' + id).val('');
					}
				}
			})
		}
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
										$('.removeclassP' + id).remove();
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
			} else {
				swal.close()
			}
		});
	}

	function inward_rows(count, flag) {
		if (flag == 'edit') {
			if (room == 1) {
				room = count;
			}
		} else room = count;

		if (room == undefined || room == null) room = 0;
		var unit, product = "";
		var today = "<?= date('Y-m-d') ?>";
		var qty, price, tax, amount = 0;
		product = $(`#itemCode${room}`).val();
		unit = $(`#itemUnit${room}`).val();
		qty = Number($(`#itemQty${room}`).val());
		tax = Number($(`#itemTax${room}`).val());
		price = Number($(`#itemPrice${room}`).val());
		amount = Number($(`#subTotal${room}`).val());
		expiryDate = $(`#expiryDate${room}`).val();
		if ($(`#itemCode${room}`).length > 0) {
			if (product == "" || product == null) {
				$(`#itemUnit${room}`).val(null);
				$(`#itemCode${room}`).focus();
				toastr.error("Please select product.", "Inward");
				return false;
			}

			if (unit == "" || product == null) {
				$(`#itemUnit${room}`).focus();
				toastr.error("Please select an selling unit", "Inward");
				return false;
			}

			if (qty == "" || Number(qty) <= 0) {
				$(`#itemQty${room}`).focus();
				toastr.error("Quantity must be non zero value", "Inward");
				return false;
			}

			/*if ((isNaN(tax)) || Number(tax) <= 0) {
				$(`#itemTax${room}`).focus();
				toastr.error("Tax should be an non zero value ", "Inward");
				return false;
			}*/

			if ((isNaN(price)) || Number(price) <= 0) {
				$(`#itemPrice${room}`).focus();
				toastr.error("Price should be an non zero value ", "Inward");
				return false;
			}

			if (Number(amount) > Number(amount)) {
				$(`#subTotal${room}`).focus();
				toastr.error("Product amount must be greater 0", "Inward");
				return false;
			}

			if (expiryDate <= today && expiryDate != '') {
				$(`#expiryDate${room}`).focus();
				toastr.error('Expiry date must be greater than today', 'Inward', {
					"progressBar": false
				});
				return false;
			}
		}
		$(".add_btn").empty().append('<button type="button" class="btn btn-danger remove_price_fields" data-id="' + room + '"><i class="fa fa-trash"></i></button>');
		room++;
		var objTo = document.getElementById('add_fields_section');
		var divtest = document.createElement("div");
		divtest.setAttribute("class", "form-group removeclassP" + room);

		var element = `
			<div class="row mb-1">
			    <div class="col-md-3 col-12">
					<div class="form-group mandatory">
						<label class="form-label">Product</label>
						<input type="hidden" class="form-control" name="inwardLineCode[]" id="inwardLineCode${room}">
						<select class="form-select select2 items" required id="itemCode${room}" style="width:100%" name="itemCode[]" onchange="getItemStorageUnit(${room});checkDuplicateItem(${room});">
							<option value="">Select Item</option>
							<?= $product ?>
						</select>
					</div>
				</div> 
				 <div class="col-md-3 col-12">
					 <div class="form-group mandatory">
						<label class="form-label">Item Unit</label>
						<input type="hidden" class="form-control" name="itemUnit[]" id="itemUnit${room}" readonly>
						<input type="text" class="form-control" name="itemUnitName[]" id="itemUnitName${room}" readonly>
					    <input type="hidden" class="form-control" name="productCode[]" id="productCode${room}" readonly>
						<input type="hidden" class="form-control" name="variantCode[]" id="variantCode${room}" readonly>
						<input type="hidden" class="form-control" name="sku[]" id="sku${room}" readonly> 
					</div>
				</div>
				<div class="col-md-3 col-12">
					 <div class="form-group mandatory">
						<label class="form-label">Expiry Date</label>
						<input type="date" class="form-control" name="expiryDate[]" id="expiryDate${room}" onblur="validateExpiryDate(${room})">
					</div>
				</div>
				<div class="col-md-3 col-12">
					 <div class="form-group mandatory">
						<label class="form-label">Quantity</label>
						<input type="number" min="0" max="99999999" step="0.01" required class="form-control" name="itemQty[]" id="itemQty${room}" onchange="checkQty(${room});" onkeypress="return isNumber(event)" onkeyup="calculate_subTotal(${room})">
					</div>
				</div>
				 <div class="col-md-3 col-12">
					 <div class="form-group mandatory">
						<label class="form-label">Price</label>
						<input type="number" min="0" max="999999" step="0.01" required step="0.01" value="0.00" class="form-control" name="itemPrice[]" id="itemPrice${room}" onchange="checkPrice(${room});"  onkeyup="calculate_subTotal(${room})">
					</div>
				 </div>
				 <div class="col-md-3 col-12">
					 <div class="form-group">
					 	<label class="form-label">Tax</label>
						<input type="number" min="0" max="99" step="0.01" required value="0.00" class="form-control" name="itemTax[]" id="itemTax${room}" onkeyup="calculate_subTotal(${room})" onchange="checkTax(${room});">
					</div>
				</div>
				 <div class="col-md-3 col-12">
					 <div class="form-group mandatory">
						<label class="form-label">Subtotal</label>
						<input type="number" min="0" max="999999999" step="0.01" value="0.00" required class="form-control subtotal" name="subTotal[]" id="subTotal${room}" readonly>
					</div>
				</div>
				<div class="col-md-3 col-12 mt-4">
					 <div class="add_btn"><button type="button" class="btn btn-danger remove_price_fields" data-id="${room}"><i class="fa fa-trash"></i></button></div>
				</div>
                <hr>				
			</div>`;
		divtest.innerHTML = element;
		objTo.appendChild(divtest);
		$("#pricesection_add_btn").empty().append('<div class="col-md-3"><button type="button" data-id="' + room + '" class="btn btn-success add_fields"><i class="fa fa-plus"></i></button></div>');
		$("#inwardForm").parsley('refresh');
	}
</script>