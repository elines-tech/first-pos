<nav class="navbar navbar-light">
	<div class="container d-block">
		<div class="row">
			<div class="col-12 col-md-6 order-md-1 order-last">
				<a href="<?php echo base_url(); ?>quotation/listRecords"><i id="exitButton" class="fa fa-times fa-2x"></i></a>
			</div>
		</div>
	</div>
</nav>


<div class="container mb-5">
	<section id="multiple-column-form" class="mt-5">
		<div class="row match-height">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">Edit Sale Quotation</h3>
					</div>
					<div class="card-content">
						<div class="card-body">
							<form id="quotationForm" class="form" method="post" enctype="multipart/form-data" data-parsley-validate>

								<?php if ($quotationData) {
									$result = $quotationData->result_array()[0];
								?>
									<div class="row">
										<div class="col-md-12 col-12">
											<div class="row">
												<div class="col-md-4 col-12">
													<input type="hidden" class="form-control" id="today" name="today" value="<?= date("Y-m-d") ?>" onblur="validateDate()">
													<div class="form-group mandatory">
														<label for="" class="form-label">Date</label>
														<input type="date" class="form-control" name="date" id="date" required value="<?= $result['date'] ?>">
													</div>
												</div>
												<div class="col-md-4 col-12">
													<div class="form-group mandatory">
														<label for="" class="form-label">Event Name</label>
														<input type="text" class="form-control" name="eventName" id="eventName" required value="<?= $result['eventName'] ?>">
													</div>
												</div>
												<div class="col-md-4 col-12">
													<div class="form-group mandatory">
														<label for="" class="form-label">Peoples</label>
														<input type="text" class="form-control" name="people" id="people" required value="<?= $result['peoples'] ?>" onkeyup="calculateTotal()">

													</div>
												</div>
												<div class="col-md-12 col-12">
													<div class="form-group">
														<label for="" class="form-label">Remark</label>
														<select class=" form-select" name="remark" id="remark">
															<option value="">Select Remark</option>
															<option value="Not interested" <?= $result['remark'] == 'Not interested' ? 'selected' : '' ?>>Not interested</option>
															<option value="Ask to call later" <?= $result['remark'] == 'Ask to call later' ? 'selected' : '' ?>>Ask to call later</option>
															<option value="Next follow up date" <?= $result['remark'] == 'Next follow up date' ? 'selected' : '' ?>> Next follow up date</option>
														</select>
													</div>
												</div>
												<div class="col-md-12 col-12" id="showDate" style="display:<?= $result['remark'] == 'Next follow up date' ? '' : 'none' ?>">
													<div class="form-group">
														<label for="" class="form-label">Next Follow Up Date</label>
														<input type="date" class="form-control bg-white" name="remarkDate" id="remarkDate" <?php if ($result['remarkDate'] != "") { ?>value="<?= date('Y-m-d', strtotime($result['remarkDate'])) ?>" <?php } ?>>
													</div>
												</div>
												<input type="hidden" class="form-control" name="quotationCode" id="quotationCode" value="<?= $result['code'] ?>">
											</div>

											<div class="row">
												<div class="col-md-12 col-12">
													<table id="pert_tbl" class="table table-sm table-stripped" style="width:100%;">
														<thead>
															<tr>
																<th width="25%">Products</th>
																<th width="15%">Quantity/person</th>
																<th width="25%">Price/person</th>
																<th width="25%">Subtotal</th>
																<th width="10%"></th>
															</tr>
														</thead>
														<tbody>
															<?php
															$i = 0;
															if ($quotationLineEntries) {
																foreach ($quotationLineEntries->result() as $co) {
																	$i++;
															?>
																	<tr id="row<?= $i ?>">
																		<td>
																			<input type="hidden" class="form-control" name="dbproductCode<?= $i ?>" id="dbproductCode<?= $i ?>" value="<?= $co->productCode ?>">
																			<input type="hidden" class="form-control" name="quotationLineCode<?= $i ?>" id="quotationLineCode<?= $i ?>" value="<?= $co->code ?>">
																			<input type="hidden" class="form-control" name="perProductTax<?= $i ?>" id="perProductTax<?= $i ?>" value="<?= $co->tax ?>" readonly>
																			<input type="hidden" class="form-control" name="perProductTaxAmount<?= $i ?>" id="perProductTaxAmount<?= $i ?>" value="<?= $co->taxamount ?>" readonly>
																			<select class="form-select itemsdropDown select2" id="productCode<?= $i ?>" name="productCode[]" onchange="checkDuplicateProduct(<?= $i ?>);getProductTaxAmount(<?= $i ?>);">
																				<option value="<?= $co->productCode ?>"><?= $co->productEngName ?></option>

																			</select>
																		</td>
																		<td>
																			<input type="text" class="text-left form-control decimal" name="qtyPerPerson<?= $i ?>" id="qtyPerPerson<?= $i ?>" value="<?= $co->qtyPerPerson ?>" onkeyup="calculate_subTotal(<?= $i ?>);getProductTaxAmount(<?= $i ?>);">
																		</td>
																		<td>
																			<input type="text" class="text-left form-control decimal" name="pricePerPerson<?= $i ?>" id="pricePerPerson<?= $i ?>" value="<?= $co->pricePerPerson ?>" onkeyup="calculate_subTotal(<?= $i ?>);getProductTaxAmount(<?= $i ?>);">
																		</td>
																		<td>
																			<input type="text" class="text-left form-control" name="subTotal<?= $i ?>" id="subTotal<?= $i ?>" disabled value="<?= $co->subTotal ?>">
																		</td>
																		<td>
																			<a href="#" class="btn btn-danger" onclick="delete_row(<?= $i ?>,'<?= $co->code ?>')"><i class="fa fa-trash"></i>
																		</td>
																	</tr>
															<?php
																	//echo '<script>getProductByCategory('.$i.')</script>';
																}
															} ?>
															<tr id="row0">
																<td>
																	<input type="hidden" class="form-control" name="quotationLineCode0" id="quotationLineCode0" value="">
																	<input type="hidden" class="form-control" name="perProductTax0" id="perProductTax0" readonly>
																	<input type="hidden" class="form-control" name="perProductTaxAmount0" id="perProductTaxAmount0" readonly>
																	<select class="form-select itemsdropDown select2" id="productCode0" name="productCode[]" onchange="checkDuplicateProduct(0);getProductTaxAmount(0);">
																		<option value=""></option>

																	</select>
																</td>
																<td>
																	<input type="text" class="text-left form-control decimal" name="qtyPerPerson0" id="qtyPerPerson0" onkeyup="calculate_subTotal(0);getProductTaxAmount(0);">
																</td>
																<td>
																	<input type="text" class="text-left form-control decimal" name="pricePerPerson0" id="pricePerPerson0" onkeypress="return isNumber(event)" onkeyup="calculate_subTotal(0);getProductTaxAmount(0);">
																</td>
																<td>
																	<input type="text" class="text-left form-control" name="subTotal0" id="subTotal0" disabled>
																</td>
																<td>
																	<a id="view" href="#" class="btn btn-success" onclick="add_row()"><i class="fa fa-plus"></i>
																</td>
															</tr>
														</tbody>


														<tfoot>


															<tr>
																<td colspan="3">
																	<label><b>Subtotal</b></label>
																	<input type="text" id="subTotal" class="text-center form-control" name="subTotal" value="<?= $result['subTotal'] ?>" readonly="readonly" autocomplete="off">
																</td>
															</tr>


															<tr>
																<td colspan="3">
																	<label><b>Discount (₹)</b></label>
																	<input type="text" id="discount" class="text-center form-control decimal" name="discount" value="<?= $result['discount'] ?>" onkeypress="return isNumber(event)" autocomplete="off" onkeyup="calculateTotal()">
																</td>
															</tr>


															<tr>
																<td colspan="3">
																	<label><b>Discount Amount</b></label>
																	<input type="text" id="discountAmount" class="text-center form-control decimal" name="discountAmount" disabled value="<?= $result['subTotal'] - $result['discount'] ?>">
																</td>
															</tr>


															<tr>
																<td colspan="3">
																	<label><b>Tax</b></label>
																	<input type="hidden" id="totalTax" class="text-center form-control" name="totalTax" readonly="readonly" autocomplete="off" value="<?= $result['totalTax'] ?>">
																	<input type="text" id="taxAmount" class="text-center form-control" name="taxAmount" readonly="readonly" value="<?= $result['taxAmount'] ?>" autocomplete="off">
																</td>
															</tr>


															<tr>
																<td colspan="3">
																	<label><b>Grand Total</b></label>
																	<input type="text" id="grandTotal" class="text-center form-control" name="grandTotal" readonly="readonly" value="<?= $result['grandTotal'] ?>" autocomplete="off">
																</td>
															</tr>


														</tfoot>


													</table>
												</div>
											</div>
											<div class="row">
												<div class="col-12 d-flex justify-content-end">
													<?php if ($updateRights == 1) { ?>
														<button type="submit" class="btn btn-success" id="saveQuotationBtn">Save</button>
													<?php } ?>
													<button type="reset" id="cancelQuotationBtn" class="btn btn-light-secondary">Reset</button>
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
	/*function getProductByCategory(id) {
		var categoryList = document.getElementById("categoryCode" + id);
		var subCategoryCode = categoryList.options[categoryList.selectedIndex].getAttribute("value");
		var categoryCode = categoryList.options[categoryList.selectedIndex].getAttribute("data-category-code");
		var productCode = $("#dbproductCode" + id).val();
		if (categoryCode != '') {
			$.ajax({
				url: base_path + 'quotation/getProductByCategory',
				data: {
					'categoryCode': categoryCode,
					'productCode': productCode
				},
				type: 'post',
				success: function(response) {
					var res = JSON.parse(response);
					if (res.status == 'true') {
						$('select#productCode' + id).empty();
						$('select#productCode' + id).append(res.items);
					} else {
						toastr.error("Selected category having no products..Please select another", 'Invalid Category', {
							"progressBar": true
						});
						$("#categoryCode" + id).val('');
					}
				}
			});
		} else {
			$('select#productCode' + id).empty();
		}
	}*/

	$(document).ready(function() {

		$("body").on("change", "#remark", function(e) {
			var thisVal = $(this).find('option:selected').val();
			var thisVal = $(this).val();
			if (thisVal == "Next follow up date") {
				$("#showDate").show();
			} else {
				$("#showDate").hide();
			}
		});

		$('.decimal').keyup(function() {
			var val = $(this).val();
			if (isNaN(val)) {
				val = val.replace(/[^0-9\.]/g, '');
				if (val.split('.').length > 2)
					val = val.replace(/\.+$/, "");
			}
			$(this).val(val);
		});
		$("select.itemsdropDown").select2({
			placeholder: "Select Product",
			allowClear: true,
			ajax: {
				url: base_path + 'Common/getProduct',
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

		$("#quotationForm").parsley();
		$("form").on("submit", function(e) {
			e.preventDefault();
			var fd = new FormData();
			var other_data = $('form').serializeArray();
			$.each(other_data, function(key, input) {
				fd.append(input.name, input.value);
			});
			var isActive = 0;
			if ($("#isActive").is(':checked')) {
				isActive = 1;
			}
			fd.append('total', $('#total').val())
			fd.append('isActive', isActive)

			var table = document.getElementById("pert_tbl");
			var table_len = (table.rows.length) - 6;
			var tr = table.getElementsByTagName("tr");
			if (table_len == 1 && ($('#productCode0').val() == '' || $('#qtyPerPerson0').val() == '' || $('#pricePerPerson0').val() == '' || $('#subTotal0').val() == '')) {
				toastr.error('Please provide at least one entry', 'Quotation', {
					"progressBar": true
				});
			} else {
				$.ajax({
					type: "POST",
					url: base_path + "quotation/saveQuotation",
					enctype: 'multipart/form-data',
					contentType: false,
					processData: false,
					data: fd,
					beforeSend: function() {
						$('#saveQuotationBtn').prop('disabled', true);
						$('#cancelQuotationBtn').prop('disabled', true);
						$('#saveQuotationBtn').text('Please wait..');
					},
					success: function(data) {
						$('#saveQuotationBtn').prop('disabled', false);
						$('#cancelQuotationBtn').prop('disabled', false);
						$('#saveQuotationBtn').text('Save');
						var obj = JSON.parse(data);

						if (obj.status) {
							var ajax_complete = 0;
							var num = 0;
							for (i = 1; i <= table_len; i++) {
								var id = tr[i].id.substring(3);
								document.getElementById("quotationCode").value = obj.quotationCode;
								var quotationCode = document.getElementById("quotationCode").value;
								var quotationLineCode = document.getElementById("quotationLineCode" + id).value;
								//var categoryList = document.getElementById("categoryCode" + id);
								//var subCategoryCode = categoryList.options[categoryList.selectedIndex].getAttribute("value");
								//var categoryCode = categoryList.options[categoryList.selectedIndex].getAttribute("data-category-code");
								var productCode = document.getElementById("productCode" + id).value;
								var qtyPerPerson = document.getElementById("qtyPerPerson" + id).value;
								var pricePerPerson = document.getElementById("pricePerPerson" + id).value;
								var subTotal = document.getElementById("subTotal" + id).value;
								var perProductTax = document.getElementById("perProductTax" + id).value;
								var perProductTaxAmount = document.getElementById("perProductTaxAmount" + id).value;
								if (productCode != '' && qtyPerPerson != '' && pricePerPerson != '' && subTotal != '') {
									num++;
									$.ajax({
										type: 'post',
										url: base_path + "Quotation/saveQuotationLine",
										data: {
											quotationCode: quotationCode,
											quotationLineCode: quotationLineCode,
											//categoryCode: categoryCode,
											//subCategoryCode: subCategoryCode,
											productCode: productCode,
											qtyPerPerson: qtyPerPerson,
											pricePerPerson: pricePerPerson,
											subTotal: subTotal,
											perProductTax: perProductTax,
											perProductTaxAmount: perProductTaxAmount
										},
										success: function(response) {
											ajax_complete++;
											if (num == ajax_complete) {
												toastr.success(obj.message, 'Quotation', {
													"progressBar": true
												});
												location.href = base_path + "quotation/listRecords";
											} else {}
										}
									});
								}
							}
						} else {
							toastr.error(obj.message, 'Quotation', {
								"progressBar": true
							});
						}
					}
				});
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

	function getTaxAmount() {
		var productCodes = $("select[name='productCode[]']").map(function() {
			return $(this).val();
		}).get();
		var discount = $('#discount').val();
		var subTotal = $('#subTotal').val();
		$.ajax({
			url: base_path + 'quotation/getTaxAmount',
			async: false,
			data: {
				'productCodes': productCodes,
				'discount': discount,
				'subTotal': subTotal
			},
			type: 'post',
			success: function(response) {
				$('#taxAmount').val(response);
			}
		});
	}

	function add_row() {
		var table = document.getElementById("pert_tbl");
		var table_len = (table.rows.length) - 6;
		var tr = table.getElementsByTagName("tr");
		var id = 0;
		var quotationLineCode = document.getElementById("quotationLineCode0").value;
		//var categoryList = document.getElementById("categoryCode0");
		//var subCategoryCode = categoryList.options[categoryList.selectedIndex].getAttribute("value");
		//var categoryCode = categoryList.options[categoryList.selectedIndex].getAttribute("data-category-code");
		var productCode = document.getElementById("productCode0").value;
		var qtyPerPerson = document.getElementById("qtyPerPerson0").value;
		var pricePerPerson = document.getElementById("pricePerPerson0").value;
		var subTotal = document.getElementById("subTotal0").value;
		var perProductTax = document.getElementById("perProductTax0").value;
		var perProductTaxAmount = document.getElementById("perProductTaxAmount0").value;
		if (productCode != '' && qtyPerPerson != '' && pricePerPerson != '' && subTotal != '') {} else {
			toastr.error('Please provide all the details', 'Quotation', {
				"progressBar": false
			});
			return;
		}

		for (i = 1; i < table_len; i++) {
			var n = tr[i].id.substring(3);
			var id = n;
		}
		id++;
		var row = table.insertRow(table_len).outerHTML = '<tr id="row' + id + '">' +
			'<td><select class="form-control" name="productCode[]" id="productCode' + id + '" disabled>' +
			document.getElementById("productCode0").innerHTML +
			'</select><input type="hidden" class="form-control" name="quotationLineCode' + id + '" id="quotationLineCode' + id + '" value="' + quotationLineCode + '"><input type="hidden" class="form-control" name="perProductTax' + id + '" id="perProductTax' + id + '" value="' + perProductTax + '"><input type="hidden" class="form-control" name="perProductTaxAmount' + id + '" id="perProductTaxAmount' + id + '" value="' + perProductTaxAmount + '"></td>' +
			'<td><input type="text" class="form-control decimal" name="qtyPerPerson' + id + '" id="qtyPerPerson' + id + '" value="' + qtyPerPerson + '" onkeyup="calculate_subTotal(' + id + ');getProductTaxAmount(' + id + ');"></td>' +
			'<td><input type="text" class="form-control decimal" name="pricePerPerson' + id + '" id="pricePerPerson' + id + '" value="' + pricePerPerson + '" onkeyup="calculate_subTotal(' + id + ');getProductTaxAmount(' + id + ');"></td>' +
			'<td><input type="text" class="form-control" name="subTotal' + id + '" id="subTotal' + id + '" value="' + subTotal + '" disabled></td>' +
			'<td><a href="#" class="btn btn-danger" onclick="delete_row(' + id + ')"><i class="fa fa-trash"></i></a>' +
			'</td>' +
			'</tr>';
		//document.getElementById("categoryCode" + id).value = subCategoryCode;
		document.getElementById("productCode" + id).value = productCode;
		//document.getElementById("categoryCode0").value = "";
		document.getElementById("productCode0").value = "";
		document.getElementById("productCode0").innerHTML = "";
		document.getElementById("qtyPerPerson0").value = "";
		document.getElementById("pricePerPerson0").value = "";
		document.getElementById("subTotal0").value = "";
		document.getElementById("perProductTax0").value = "";
		document.getElementById("perProductTaxAmount0").value = "";
		//document.getElementById("categoryCode0").focus();
	}

	function delete_row(id, lineCode) {
		var people = $("#people").val();
		swal({
			title: "Are you sure?",
			text: "You want to delete this product ",
			type: "warning",
			showCancelButton: !0,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, delete it!",
			cancelButtonText: "No, cancel it!",
			closeOnConfirm: !1,
			closeOnCancel: !1
		}, function(e) {
			if (e) {
				$.ajax({
					url: base_path + "quotation/deleteQuotationLine",
					type: 'POST',
					data: {
						'lineCode': lineCode,
						'people': people
					},
					dataType: "JSON",
					success: function(response) {
						if (response) {
							//calculateTotal()
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
									}
								});
						} else {
							toastr.success('Record Not Deleted', 'Failed', {
								"progressBar": true
							});
						}
					}
				});
			} else {
				swal.close();
			}
		});
	}


	function checkDuplicateProduct(id) {
		var table = document.getElementById("pert_tbl");
		var table_len = (table.rows.length) - 6;
		var tr = table.getElementsByTagName("tr");
		//var categoryList = document.getElementById("categoryCode" + id);
		//var subCategoryCode = categoryList.options[categoryList.selectedIndex].getAttribute("value");
		//var categoryCode = categoryList.options[categoryList.selectedIndex].getAttribute("data-category-code");
		var productCode = document.getElementById("productCode" + id).value.toLowerCase();
		if (productCode != "") {
			for (i = 1; i <= table_len; i++) {
				var row_id = tr[i].id.substring(3);
				//var categoryList_row = document.getElementById("categoryCode" + row_id);
				//var subCategoryCode_row = categoryList_row.options[categoryList_row.selectedIndex].getAttribute("value");
				//var categoryCode_row = categoryList_row.options[categoryList_row.selectedIndex].getAttribute("data-category-code");
				var productCode_row = document.getElementById("productCode" + row_id).value.toLowerCase();
				if (productCode_row == productCode && row_id != id) {
					toastr.error('Product already exists', 'Duplicate Quotation', {
						"progressBar": true
					});
					//document.getElementById("categoryCode" + id).value = "";
					//document.getElementById("productCode" + id).value = "";
					//document.getElementById("categoryCode" + id).focus();
					$(`#productCode${id}`).val(null).trigger("change");
					return false;
				}
			}
		}
	}

	function getProductTaxAmount(id) {
		var productCode = $('#productCode' + id).val();
		var subTotal = $('#subTotal' + id).val();
		$.ajax({
			url: base_path + 'quotation/getProductTaxAmount',
			async: false,
			data: {
				'productCode': productCode,
				'subTotal': subTotal
			},
			type: 'post',
			success: function(response) {
				var res = JSON.parse(response);
				$('#perProductTax' + id).val(res.tax);
				$('#perProductTaxAmount' + id).val(res.taxAmount);
				calculateTotal();
			}
		});
	}

	function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	}

	function calculate_subTotal(id) {
		var qtyPerPerson = Number($('#qtyPerPerson' + id).val());
		var pricePerPerson = Number($('#pricePerPerson' + id).val())
		subTotal = qtyPerPerson * pricePerPerson;
		$('#subTotal' + id).val(subTotal);
		calculateTotal();
	}

	function calculateTotal() {
		var table = document.getElementById("pert_tbl");
		var table_len = (table.rows.length) - 6;
		var tr = table.getElementsByTagName("tr");
		var total = 0;
		var tax = 0;
		var taxAmount = 0;
		for (i = 1; i <= table_len; i++) {
			var id = tr[i].id.substring(3);
			total = Number(total) + Number($('#subTotal' + id).val());
			tax = Number(tax) + Number($('#perProductTax' + id).val());
			taxAmount = Number(taxAmount) + Number($('#perProductTaxAmount' + id).val());
		}
		var people = Number($('#people').val());
		var subTotal = people * total;
		$('#subTotal').val(subTotal);
		var discount = $('#discount').val();
		var discountAmount = Number(subTotal - discount);
		$('#discountAmount').val(discountAmount);
		$('#totalTax').val(tax);
		$('#taxAmount').val(taxAmount);
		//getTaxAmount();
		//var taxAmount = $('#taxAmount').val();
		var grandTotal = Number(Number(subTotal - discount)) + Number(taxAmount);
		$('#grandTotal').val(grandTotal);
	}

	function validateDate() {
		var today = $('#today').val();
		var date = $('#date').val();
		if (date != '' && date > today) {
			toastr.error('Date should not be greater than todays date', 'Quotation', {
				"progressBar": true
			});
			document.getElementById("date").value = "";
			document.getElementById("date").focus();
			return false;
		}
	}
</script>