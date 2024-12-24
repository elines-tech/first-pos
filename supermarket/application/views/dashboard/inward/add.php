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
							<h3>Add Inward <span style="float:right"><a id="cancelDefaultButton" href="<?= base_url() ?>inward/listRecords" class="btn btn-sm btn-primary">Back</a></span></h3>
						</div>
						<div class="card-content">
							<div class="card-body">
								<form id="inwardForm" method="post" action="<?= base_url('inward/saveInwards') ?>" class="form" data-parsley-validate>
									<?php
									echo "<div class='text-danger text-center' id='error_message'>";
									if (isset($error_message)) {
										echo $error_message;
									}
									echo "</div>";
									?>
									<div class="row">
										<div class="col-md-12 col-12">
											<div class="row">
												<div class="col-md-3 col-12">
													<div class="form-group mandatory">
														<label for="" class="form-label">Batch No</label>
														<input type="text" id="batchNo" class="form-control" name="batchNo" required readonly value="<?= date('dmyhis') . "" . rand(10, 99); ?>">
													</div>
												</div>
												<div class="col-md-3 col-12">
													<div class="form-group mandatory">
														<label for="" class="form-label">Inward Date</label>
														<input type="date" id="inwardDate" class="form-control" name="inwardDate" id="inwardDate" required value="<?= date('Y-m-d') ?>">
													</div>
												</div>
												<div class="col-md-3 col-12">
													<div class="form-group mandatory">
														<label for="product-name" class="form-label">Branch</label>
														<input type="hidden" class="form-control" id="inwardCode" name="inwardCode">
														<?php if($branchCode!=""){?>
														      <input type="hidden" class="form-control" name="branchCode" value="<?= $branchCode; ?>" readonly>
															  <input type="text" class="form-control" name="branchName" value="<?= $branchName; ?>" readonly>
														<?php } else{?>
														    <select class="form-select select2" name="branchCode" id="branchCode" data-parsley-required="true" required>
														    </select>
														<?php } ?>
													</div>
												</div>
												<div class="col-md-3 col-12">
													<div class="form-group mandatory">
														<label for="product-name" class="form-label">Supplier</label>
														<select class="form-select select2" name="supplierCode" id="supplierCode" data-parsley-required="true" required>
														</select>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12 col-12">
													<div class="form-group">
														<label for="product-name" class="form-label">Reference</label>
														<input type="text" class="form-control" name="refNo" id="refNo" value="">
													</div>
												</div>
											</div>
											<hr>
											<div class="form-group removeclassP0">
												<div class="row">
													<div class="col-md-3 col-12">
														<div class="form-group mandatory">
															<label class="form-label">Product</label>
															<input type="hidden" class="form-control" name="inwardLineCode[]" id="inwardLineCode0">
															<select class="form-select select2 items" required id="itemCode0" style="width:100%" name="itemCode[]" onchange="getItemStorageUnit(0);checkDuplicateItem(0);">
																<option value="">Select Item</option>
																<?= $product ?>
															</select>
														</div>
													</div>
													<div class="col-md-3 col-12">
														<div class="form-group">
															<label class="form-label">Item Unit</label>
															<input type="hidden" class="form-control" name="itemUnit[]" id="itemUnit0" readonly>
															<input type="text" class="form-control" name="itemUnitName[]" id="itemUnitName0" readonly>
															<input type="hidden" class="form-control" name="productCode[]" id="productCode0" readonly>
															<input type="hidden" class="form-control" name="variantCode[]" id="variantCode0" readonly>
															<input type="hidden" class="form-control" name="sku[]" id="sku0" readonly>
														</div>
													</div>
													<div class="col-md-3 col-12">
														<div class="form-group">
															<label class="form-label">Expiry Date</label>
															<input type="date" class="form-control" name="expiryDate[]" id="expiryDate0" onblur="validateExpiryDate(0)">
														</div>
													</div>
													<div class="col-md-3 col-12">
														<div class="form-group mandatory">
															<label class="form-label">Quantity</label>
															<input type="number" step="0.01" max="9999999" min="0" required class="form-control" name="itemQty[]" id="itemQty0" onchange="checkQty(0);" onkeypress="return isNumber(event)" onkeyup="calculate_subTotal(0)">
														</div>
													</div>
													<div class="col-md-3 col-12">
														<div class="form-group mandatory">
															<label class="form-label">Price</label>
															<input type="number" min="0" max="9999999" step="0.01" value="0.00" class="form-control" name="itemPrice[]" id="itemPrice0" onchange="checkPrice(0);" onkeyup="calculate_subTotal(0)">
														</div>
													</div>
													<div class="col-md-3 col-12">
														<div class="form-group">
															<label class="form-label">Tax</label>
															<input type="number" min="0" max="99" step="0.01" value="0.00" class="form-control" name="itemTax[]" id="itemTax0" onkeyup="calculate_subTotal(0)">
														</div>
													</div>
													<div class="col-md-3 col-12">
														<div class="form-group mandatory">
															<label class="form-label">Subtotal</label>
															<input type="number" min="0" max="9999999999999" step="0.01" value="0.00" class="form-control subtotal" name="subTotal[]" id="subTotal0" readonly>
														</div>
													</div>
													<div class="col-md-3 col-12 mt-4">
														<div class="add_btn"><a id="view" href="#" class="btn btn-success add_fields" data-id="0"><i class="fa fa-plus"></i></a></div>
													</div>
												</div>
												<hr>
											</div>
											<div id="add_fields_section"></div>
											<div class="row" id="pricesection_add_btn"></div>
											<div class="row">
												<div class="col-md-4 offset-md-8 col-12 mb-2">
													<div class="form-group mandatory">
														<label for="total" class="form-label">Total</label>
														<input type="number" value="0.00" min="0" step="0.01" id="total" class="form-control" name="total" required readonly>
													</div>
												</div>
												<div class="col-12 d-flex justify-content-end">
													<button type="submit" id="saveDefaultButton" class="btn btn-primary submitBtn" name="approveInwardBtn" value="1">Save & Approve</button>
													<button type="submit" class="btn btn-success" id="saveInwardBtn">Save</button>
													<a href="<?= base_url() ?>inward/listRecords" id="cancelInwardBtn" class="btn btn-light-secondary">Close</a>
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
</div>
<script type="text/javascript">
	var room = 1;
	var product = "<?= $product ?>";

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

	function delete_row(id) {
		swal({
			title: "Are you sure?",
			text: "You want to delete this item ",
			type: "warning",
			showCancelButton: !0,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, delete it!",
			cancelButtonText: "No, cancel it!",
			closeOnConfirm: !1,
			closeOnCancel: true
		}, function(e) {
			if (e) {
				swal.close();
				calculateTotal();
				var row = document.getElementById("row" + id);
				row.parentNode.removeChild(row);
			} else {
				swal.close();
			}
		});
	}

	function checkDuplicateItem(id) {
		var itemCode = document.getElementById("itemCode" + id).value.toLowerCase();
		var cls = document.getElementsByClassName('items');
		for (row_id = 0; row_id < cls.length; row_id++) {
			var row_code = document.getElementById("itemCode" + row_id).value.toLowerCase();
			if ((row_code == itemCode) && id != row_id) {
				toastr.error("Item already exist", "Inward");
				document.getElementById("itemCode" + id).value = '';
				$("#productCode" + id).val('');
				$("#variantCode" + id).val('');
				$("#sku" + id).val('');
				$('#itemUnit' + id).val('');
				$('#itemUnitName' + id).val('');
				return;
			}
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

	function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	}

	function checkPrice(id) {
		var itemPrice = Number($('#itemPrice' + id).val());
		if (itemPrice != '' && itemPrice > 0) {} else {
			$('#itemPrice' + id).val('');
			toastr.error('Item price should be greater than 0', 'Inward', {
				"progressBar": false
			});
			return false;
		}
	}

	function checkQty(id) {
		var itemQty = Number($('#itemQty' + id).val());
		if (itemQty != '' && itemQty > 0) {} else {
			$('#itemQty' + id).val('');
			toastr.error('Item Quantity should be greater than 0', 'Inward', {
				"progressBar": false
			});
			return false;
		}

	}

	function checkTax(id) {
		var itemTax = Number($('#itemTax' + id).val());
		if (itemTax != '' && itemTax > 0) {} else {
			$('#itemTax' + id).val('');
			toastr.error('Item Tax should be greater than 0', 'Inward', {
				"progressBar": false
			});
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

	$(document).ready(function() {

		$("#branchCode").select2({
			placeholder: "Select Branch",
			allowClear: true,
			ajax: {
				url: base_path + 'Common/getBranch',
				type: "get",
				delay: 250,
				dataType: 'json',
				data: function(params) {
					var query = {
						search: params.term
					}
					return query;
				},
				processResults: function(response) {
					return {
						results: response
					};
				},
				cache: true
			}
		});

		$("#supplierCode").select2({
			placeholder: "Select Supplier",
			allowClear: true,
			ajax: {
				url: base_path + 'Common/getSupplier',
				type: "get",
				delay: 250,
				dataType: 'json',
				data: function(params) {
					var query = {
						search: params.term
					}
					return query;
				},
				processResults: function(response) {
					return {
						results: response
					};
				},
				cache: true
			}
		});

		$('.cancel').removeClass('btn-default').addClass('btn-info');
		var today = new Date().toISOString().split('T')[0];
		document.getElementsByName("inwardDate")[0].setAttribute('max', today);

		$("body").delegate(".add_fields", "click", function(e) {
			e.preventDefault();
			var pos = $(this).data('id');
			inward_rows(pos, 'add');
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

		/*
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

			if ((isNaN(tax)) || Number(tax) <= 0) {
				$(`#itemTax${room}`).focus();
				toastr.error("Tax should be an non zero value ", "Inward");
				return false;
			}

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
		*/

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
						<input type="number" min="0" max="9999999" step="0.01" class="form-control" name="itemQty[]" id="itemQty${room}" onchange="checkQty(${room});" onkeypress="return isNumber(event)" onkeyup="calculate_subTotal(${room})">
					</div>
				</div>
				 <div class="col-md-3 col-12">
					 <div class="form-group mandatory">
						<label class="form-label">Price</label>
						<input type="number" min="0" max="9999999" step="0.01"  value="0.00" class="form-control" name="itemPrice[]" id="itemPrice${room}" onchange="checkPrice(${room});"  onkeyup="calculate_subTotal(${room})">
					</div>
				 </div>
				 <div class="col-md-3 col-12">
					 <div class="form-group">
					 	<label class="form-label">Tax</label>
						<input type="number" min="0" max="99" step="0.01" value="0.00" class="form-control" name="itemTax[]" id="itemTax${room}"  onkeyup="calculate_subTotal(${room})">
					</div>
				</div>
				 <div class="col-md-3 col-12">
					 <div class="form-group mandatory">
						<label class="form-label">Subtotal</label>
						<input type="number" min="0" max="9999999999999" step="0.01" value="0.00" class="form-control subtotal" name="subTotal[]" id="subTotal${room}" readonly>
					</div>
				</div>
				<div class="col-md-3 col-12 mt-4">
					 <div class="add_btn"><button type="button" class="btn btn-danger remove_price_fields" data-id="${room}"><i class="fa fa-trash"></i></button></div>
				</div>
                 <hr>				
			</div>			
		`;
		divtest.innerHTML = element;
		objTo.appendChild(divtest);
		$("#pricesection_add_btn").empty().append('<div class="col-md-3"><button type="button" data-id="' + room + '" class="btn btn-success add_fields"><i class="fa fa-plus"></i></button></div>');
		$("#inwardForm").parsley('refresh');
	}
</script>