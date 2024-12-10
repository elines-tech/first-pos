<?php
$product = "";
if ($items) {
	foreach ($items->result() as $it) {
		if ($it->variantName != "" || $it->variantName != null) {
			$product .= "<option value='" . $it->productEngName . "-" . $it->variantName . "' data-variant='" . $it->variantCode . "' data-val='" . $it->code . "' data-sku=" . $it->variantSKU . ">" . $it->productEngName . "-" . $it->variantName . "</option>";
		} else {
			$product .= "<option value='" . $it->productEngName . "' data-val='" . $it->code . "' data-sku=" . $it->sku . ">" . $it->productEngName . "</option>";
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
							<h3>Add Inward <span style="float:right"><a href="<?= base_url() ?>inward/listRecords" class="btn btn-sm btn-primary">Back</a></span></h3>
						</div>
						<div class="card-content">
							<div class="card-body">
								<form id="inwardForm" class="form" data-parsley-validate>
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
														<input type="text" id="batchNo" class="form-control" name="batchNo" required disabled value="<?= 'BT-' . date('dmy-hi') ?>">
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
														<select class="form-select select2" name="branchCode" id="branchCode" data-parsley-required="true" required>
														</select>
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
												<div class="col-md-3 col-12">
													<div class="form-group">
														<label for="product-name" class="form-label">Reference</label>
														<input type="text" class="form-control" name="refNo" id="refNo" value="">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12 col-12">
													<table id="pert_tbl" class="table table-sm table-stripped" style="width:100%;">
														<thead>
															<tr>
																<th width="19%">Product</th>
																<th width="15%">Unit</th>
																<th width="10%">Expiry Date</th>
																<th width="10%">Quantity</th>
																<th width="15%">Price</th>
																<th width="15%">Tax</th>
																<th width="15%">Subtotal</th>
																<th width="1%"></th>
															</tr>
														</thead>
														<tbody id="table-rows">
															<tr id="row0" class="inrows removeclassP0">
																<td>
																	<input type="hidden" class="form-control" name="inwardLineCode[]" id="inwardLineCode0">
																	<select class="form-select select2" id="itemCode0" style="width:100%" name="itemCode[]" onchange="checkDuplicateItem(0);getItemStorageUnit(0)">
																		<option value="">Select Item</option>
																		<?= $product ?>
																	</select>
																</td>
																<td>
																	<input type="hidden" class="form-control" name="itemUnit[]" id="itemUnit0" readonly>
																	<input type="text" class="form-control" name="itemUnitName[]" id="itemUnitName0" readonly>
																	  
																</td>
																<td>
																	<input type="date" class="form-control" name="expiryDate[]" id="expiryDate0" onblur="validateExpiryDate(0)">
																</td>
																<td>
																	<input type="number" class="form-control" name="itemQty[]" id="itemQty0" onchange="checkQty(0);" onkeypress="return isNumber(event)" onkeyup="calculate_subTotal(0)">
																</td>
																<td>
																	<input type="number" step="0.01" value="0.01" class="form-control" name="itemPrice[]" id="itemPrice0" onchange="checkPrice(0);"  onkeyup="calculate_subTotal(0)">
																</td>
																<td>
																	<input type="number" step="0.01" value="0.01" class="form-control" name="itemTax[]" id="itemTax0" onchange="checkPrice(0);"  onkeyup="calculate_subTotal(0)">
																</td>
																<td>
																	<input type="number" step="0.01" value="0.01" class="form-control subtotal" name="subTotal[]" id="subTotal0" readonly>
																</td>
																<td class="add_btn">
																	<a href="#" class="btn btn-success add_fields" data-id="0"><i class="fa fa-plus"></i></a>
																</td>

															</tr>
														</tbody>
														<div id="pricesection_add_btn"></div>
														<tfoot>
															<tr>
																<td colspan="6" class="text-right"><b>Total :</b></td>
																<td colspan="7">
																	<input type="text" id="total" class="text-right form-control" name="total" value="0.00" disabled>
																</td>
															</tr>
														</tfoot>
													</table>
													
												</div>
											</div>
											<div class="row">
												<div class="col-12 d-flex justify-content-end">
													<button type="submit" class="btn btn-success white me-1 mb-1 sub_1" id="saveInwardBtn">Save</button>
													<button type="button" id="cancelInwardBtn" class="btn btn-light-secondary me-1 mb-1">Reset</button>
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
	var objTo = document.querySelector('#table-rows');

	var product = "<?= $product ?>";
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
		
		
		$("#inwardForm").on("submit", function(e) {
			e.preventDefault();
			var formData = new FormData(this);
			var form = $(this);
			form.parsley().validate();
			if (form.parsley().isValid()) {
				var isActive = 0;
				if ($("#isActive").is(':checked')) {
					isActive = 1;
				}

				formData.append('batchNo', $('#batchNo').val())
				formData.append('refNo', $('#refNo').val())
				formData.append('total', $('#total').val())
				formData.append('isActive', isActive)
				var table = document.getElementById("pert_tbl");
				var table_len = (table.rows.length) - 2;
				var tr = table.getElementsByTagName("tr");
				if (table_len == 1 && ($('#itemCode0').val() == '' || $('#itemQty0').val() == '' || $('#itemUnit0').val() == '' || $('#itemPrice0').val() == '' || $('#subTotal0').val() == '' || $('#itemTax0').val() == '')) {
					toastr.error('Please provide at least one entry', 'Inward', {
						"progressBar": true
					});
				} else {
					$.ajax({
						type: "POST",
						url: base_path + "inward/saveInward",
						enctype: 'multipart/form-data',
						contentType: false,
						processData: false,
						data: formData,
						beforeSend: function() {
							$('#saveInwardBtn').prop('disabled', true);
							$('#cancelInwardBtn').prop('disabled', true);
							$('#saveInwardBtn').text('Please wait..');
						},
						success: function(data) {
							$('#saveInwardBtn').text('Save');
							$('#saveInwardBtn').prop('disabled', false);
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
									var expiryDate = document.getElementById("expiryDate" + id).value;
									var itemTax = document.getElementById("itemTax" + id).value;
									var variantCode = $("#itemCode" + id).find('option:selected').attr('data-variant');
									var productCode = $("#itemCode" + id).find('option:selected').attr('data-val');
									var sku = $("#itemCode" + id).find('option:selected').attr('data-sku');

									var subTotal = document.getElementById("subTotal" + id).value;
									if (itemTax != '' && itemCode != '' && itemUnit != '' && itemQty != '' && itemPrice != '' && subTotal != '') {
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
												itemTax: itemTax,
												variantCode: variantCode,
												productCode: productCode,
												sku: sku,
												approve: 0
											},
											success: function(response) {
												ajax_complete++;
												if (num == ajax_complete) {
													toastr.success(obj.message, 'Inward', {
														"progressBar": true
													});
													location.href = base_path + "inward/listRecords";
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
		tax= Number($(`#itemTax${room}`).val());
		price = Number($(`#itemPrice${room}`).val());
		amount = Number($(`#subTotal${room}`).val());
		expiryDate=$(`#expiryDate${room}`).val();
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
		$(".add_btn").empty().append('<button type="button" class="btn btn-danger remove_price_fields" data-id="' + room + '"><i class="fa fa-trash"></i></button>');
		room++;
		var divtest = document.createElement("tr");
		divtest.setAttribute("class", "inrows removeclassP" + room);
		
			var element = `
			<td>
				<select class="form-select select2" style="width:100%" name="itemCode[]" id="itemCode${room}">${product}</select>
				<input type="hidden" class="form-control" name="inwardLineCode[]" id="inwardLineCode${room}" value=""></td>
			<td>
				<input type="hidden" class="form-control" name="itemUnit[]" id="itemUnit${room}" readonly>
				<input type="text" class="form-control" name="itemUnitName[]" id="itemUnitName${room}" readonly>
			</td>
			<td>
				<input type="date" class="form-control" name="expiryDate[]" id="expiryDate${room}" onblur="validateExpiryDate${room}">
			</td>
			<td>
				<input type="number" class="form-control" name="itemQty[]" id="itemQty${room}" value="0" onkeyup="calculate_subTotal(${room})" onchange="checkQty(${room});" >
			</td>
			<td>
				<input type="number" step="0.01" class="form-control" name="itemPrice[]" id="itemPrice${room}" value="0.00" onkeyup="calculate_subTotal(${room})" onchange="checkPrice(${room});" >
			</td>
			<td>
				<input type="number" step="0.01" value="0.01" class="form-control" name="itemTax[]" id="itemTax${room}" onchange="checkPrice(${room});"  onkeyup="calculate_subTotal(${room})">
			</td>
			<td>
				<input type="number" step="0.01" class="form-control subtotal" name="subTotal[]" id="subTotal${room}" value="0.00" readonly>
			</td>
			<td>
				<button type="button" class="btn btn-danger remove_price_fields" data-id="${room}"><i class="fa fa-trash"></i></button>
			</td>
		`;
		divtest.innerHTML = element;
		objTo.appendChild(divtest);
		$("#pricesection_add_btn").empty().append('<button type="button" data-id="' + room + '" class="btn btn-success add_fields"><i class="fa fa-plus"></i></button>');
		
	}

	function add_row() {
		var table = document.getElementById("pert_tbl");
		var table_len = (table.rows.length) - 2;
		var tr = table.getElementsByTagName("tr");
		var id = 0;
		var inwardLineCode = document.getElementById("inwardLineCode0").value;
		var itemCode = document.getElementById("itemCode0").value;
		var expiryDate = document.getElementById("expiryDate0").value;
		var itemUnit = document.getElementById("itemUnit0").value;
		var itemUnitName = document.getElementById("itemUnitName0").value;
		var itemQty = document.getElementById("itemQty0").value;
		var itemPrice = document.getElementById("itemPrice0").value;
		var itemTax = document.getElementById("itemTax0").value;
		var subTotal = document.getElementById("subTotal0").value;
		if (itemCode == '') {
			toastr.error('Please select product', 'Inward Products', {
				"progressBar": false
			});
			$('#itemCode0').focus()
			return false;
		}
		if (itemUnit == '') {
			toastr.error('Please provide Unit', 'Inward Products', {
				"progressBar": false
			});
			$('#itemUnit0').focus()
			return false;
		}
		if (itemPrice == '') {
			toastr.error('Please provide Price', 'Inward Products', {
				"progressBar": false
			});
			$('#itemPrice0').focus()
			return false;
		}
		if (itemQty == '') {
			toastr.error('Please provide Quantity', 'Inward Products', {
				"progressBar": false
			});
			$('#itemQty0').focus()
			return false;
		}

		if (itemTax == '') {
			toastr.error('Please provide Tax', 'Inward Products', {
				"progressBar": false
			});
			$('#itemTax0').focus()
			return false;
		}
		if (subTotal == '') {
			toastr.error('Please provide Subtotal', 'Inward Products', {
				"progressBar": false
			});
			$('#subTotal0').focus()
			return false;
		}
		for (i = 1; i < table_len; i++) {
			var n = tr[i].id.substring(3);
			var id = n;
		}
		id++;
		var row = table.insertRow(table_len).outerHTML = '<tr id="row' + id + '">' +
			'<td><select class="form-select select2" style="width:100%" name="itemCode' + id + '" id="itemCode' + id + '">' +
			document.getElementById("itemCode0").innerHTML +
			'</select>' +
			'<input type="hidden" class="form-control" name="inwardLineCode' + id + '" id="inwardLineCode' + id + '" value="' + inwardLineCode + '"></td>' +
			'<td><input type="hidden" class="form-control" name="itemUnit' + id + '" id="itemUnit' + id + '" value="' + itemUnit + '"><input type="text" class="form-control" name="itemUnitName' + id + '" id="itemUnitName' + id + '" value="' + itemUnitName + '" readonly>' +
			'</td>' +
			'<td><input type="date" class="form-control" name="expiryDate' + id + '" id="expiryDate' + id + '" value="' + expiryDate + '"></td>' +
			'<td><input type="text" class="form-control" name="itemQty' + id + '" id="itemQty' + id + '" value="' + itemQty + '" onkeyup="calculate_subTotal(' + id + ')" onchange="checkQty(' + id + ');"  onkeypress="return isNumber(event)"></td>' +
			'<td><input type="text" class="form-control" name="itemPrice' + id + '" id="itemPrice' + id + '" value="' + itemPrice + '" onkeyup="calculate_subTotal(' + id + ')" onchange="checkPrice(' + id + ');"  onkeypress="return isDecimal(event)"></td>' +
			'<td><input type="text" class="form-control" name="itemTax' + id + '" id="itemTax' + id + '" value="' + itemTax + '" onkeyup="calculate_subTotal(' + id + ')" onchange="checkPrice(' + id + ');"  onkeypress="return isDecimal(event)"></td>' +
			'<td><input type="text" class="form-control" name="subTotal' + id + '" id="subTotal' + id + '" value="' + subTotal + '" disabled></td>' +
			'<td><a href="#" class="btn btn-danger" onclick="delete_row(' + id + ')"><i class="fa fa-trash"></i></a></td>' +
			'</tr>';
		document.getElementById("itemCode" + id).value = itemCode;

		$("#itemCode0").val('').trigger('change');
		//$("#itemUnit0").val('').trigger('change'); 
		document.getElementById("itemUnit0").value = "";
		document.getElementById("itemUnitName0").value = "";
		document.getElementById("itemQty0").value = "";
		document.getElementById("itemPrice0").value = "";
		document.getElementById("subTotal0").value = "";
		document.getElementById("itemTax0").value = "";

		document.getElementById("itemCode0").focus();
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
					document.getElementById("itemCode" + id).value = "";
					document.getElementById("itemUnit" + id).value = "";
					document.getElementById("itemCode" + id).focus();
					return 1;
				}
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
		var itemCode = $("#itemCode" + id).find('option:selected').attr('data-val');
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
			})
		}
	}
</script>