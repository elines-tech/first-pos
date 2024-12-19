<nav class="navbar navbar-light">
	<div class="container d-block">
		<div class="row">
			<div class="col-12 col-md-6 order-md-1 order-last"><a href="<?php echo base_url(); ?>quotation/listRecords"><i id="exitButton" class="fa fa-times fa-2x"></i></a></div>

		</div>
	</div>
</nav>


<div class="container">
	<section id="multiple-column-form" class="mt-5">
		<div class="row match-height">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">Add Sale Quotation</h3>
					</div>
					<div class="card-content">
						<div class="card-body">
							<form id="quotationForm" class="form" data-parsley-validate>

								<div class="row">
									<div class="col-md-12 col-12">
										<div class="row">
											<div class="col-md-4 col-12">
												<input type="hidden" class="form-control" id="today" name="today" value="<?= date("Y-m-d") ?>" onblur="validateDate()">
												<div class="form-group mandatory">
													<label for="" class="form-label">Date</label>
													<input type="date" class="form-control" name="date" onblur="validateDate()" id="date" required value="<?= date('Y-m-d') ?>">
												</div>
											</div>
											<div class="col-md-4 col-12">
												<div class="form-group mandatory">
													<label for="" class="form-label">Event Name</label>
													<input type="text" class="form-control" name="eventName" id="eventName" required>
												</div>
											</div>
											<div class="col-md-4 col-12">
												<div class="form-group mandatory">
													<label for="" class="form-label">People</label>
													<input type="text" class="form-control" name="people" id="people" onkeypress="return isNumber(event)" onkeyup="calculateTotal()" required>

												</div>
											</div>
											<input type="hidden" class="form-control" name="quotationCode" id="quotationCode">
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
														<tr id="row0">
															<td>
																<input type="hidden" class="form-control" name="quotationLineCode0" id="quotationLineCode0">
																<input type="hidden" class="form-control" name="perProductTax0" id="perProductTax0" readonly>
																<input type="hidden" class="form-control" name="perProductTaxAmount0" id="perProductTaxAmount0" readonly>
																<select class="form-select itemsdropDown select2" id="productCode0" name="productCode[]" onchange="checkDuplicateProduct(0);getProductTaxAmount(0);">

																</select>
															</td>
															<td>
																<input type="text" class="form-control decimal" name="qtyPerPerson0" id="qtyPerPerson0" onkeyup="calculate_subTotal(0);getProductTaxAmount(0);">
															</td>
															<td>
																<input type="text" class="form-control decimal" name="pricePerPerson0" id="pricePerPerson0" onkeyup="calculate_subTotal(0);getProductTaxAmount(0);">
															</td>
															<td>
																<input type="text" class="form-control subtotal" name="subTotal0" id="subTotal0" disabled>
															</td>
															<td>
																<a href="#" id="view" class="btn btn-success" onclick="add_row()"><i class="fa fa-plus"></i>
															</td>
														</tr>
													</tbody>



													<!--
													<tfoot>

														<tr>
															<td colspan="3" class="text-right"><b>Subtotal :</b></td>
															<td class="text-right">
																<input type="text" id="subTotal" class="text-right form-control" name="subTotal" value="0.00" readonly="readonly" autocomplete="off">
															</td>
														</tr>


														<tr>
															<td colspan="3" class="text-right"><b>Discount (₹) :</b></td>
															<td class="text-right">
																<input type="text" id="discount" class="text-right form-control decimal" name="discount" autocomplete="off" onkeyup="calculateTotal()">
															</td>
														</tr>


														<tr>
															<td colspan="3" class="text-right"><b>Discount Amount:</b></td>
															<td class="text-right">
																<input type="text" id="discountAmount" class="text-right form-control decimal" name="discountAmount" disabled>
															</td>
														</tr>


														<tr>
															<td colspan="3" class="text-right"><b>Tax :</b></td>
															<td>
																<input type="hidden" id="totalTax" class="text-right form-control" name="totalTax" readonly="readonly" value="0.00" autocomplete="off">
																<input type="text" id="taxAmount" class="text-right form-control" name="taxAmount" readonly="readonly" value="0.00" autocomplete="off">

															</td>
														</tr>


														<tr>
															<td colspan="3" class="text-right"><b>Grand Total :</b></td>
															<td class="text-right">
																<input type="text" id="grandTotal" class="text-right form-control" name="grandTotal" readonly="readonly" value="0.00" autocomplete="off">
															</td>
														</tr>


													</tfoot>
													-->



													<tfoot>

														<tr>
															<td class="text-right"><b>Subtotal :</b></td>
															<td>
																<input type="text" id="subTotal" class="text-right form-control" name="subTotal" value="0.00" readonly="readonly" autocomplete="off">
															</td>

															<td class="text-right"><b>Discount (₹) :</b></td>
															<td>
																<input type="text" id="discount" class="text-right form-control decimal" name="discount" autocomplete="off" onkeyup="calculateTotal()">
															</td>
														</tr>


														<tr>
															<td class="text-right"><b>Discount Amount:</b></td>
															<td>
																<input type="text" id="discountAmount" class="text-right form-control decimal" name="discountAmount" disabled>
															</td>


															<td class="text-right"><b>Tax :</b></td>
															<td>
																<input type="hidden" id="totalTax" class="text-right form-control" name="totalTax" readonly="readonly" value="0.00" autocomplete="off">
																<input type="text" id="taxAmount" class="text-right form-control" name="taxAmount" readonly="readonly" value="0.00" autocomplete="off">

															</td>
														</tr>


														<tr>
															<td class="text-right"><b>Grand Total :</b></td>
															<td>
																<input type="text" id="grandTotal" class="text-right form-control" name="grandTotal" readonly="readonly" value="0.00" autocomplete="off">
															</td>
														</tr>


													</tfoot>




												</table>


											</div>
										</div>
										<div class="row">
											<div class="col-12 d-flex justify-content-end">
												<?php if ($insertRights == 1) { ?>
													<button type="submit" class="btn btn-success" id="saveQuotationBtn">Save</button>
												<?php } ?>
												<button type="button" id="cancelQuotationBtn" class="btn btn-light-secondary">Reset</button>
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
<script type="text/javascript">
	/*function getProductByCategory(id){
        var categoryList = document.getElementById("categoryCode"+id);
        var subCategoryCode= categoryList.options[categoryList.selectedIndex].getAttribute("value");
        var categoryCode= categoryList.options[categoryList.selectedIndex].getAttribute("data-category-code");
		if(categoryCode!=''){
			$.ajax({
				url: base_path + 'quotation/getProductByCategory',
				data: {
					'categoryCode': categoryCode,
					'subCategoryCode':subCategoryCode
				},
				type: 'post',
				success: function(response) {
					var res = JSON.parse(response);
					if (res.status == 'true') {
						$('select#productCode'+id).empty();
						$('select#productCode'+id).append(res.products);
					} else {
						toastr.error("Selected category having no products..Please select another", 'Invalid Category', {
							"progressBar": true
						});
						$("#categoryCode"+id).val('');
					}
				}
			});
		}else{
			$('select#productCode'+id).empty();
		}
    }*/
	$(document).ready(function() {
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
		$("#quotationForm").on("submit", function(e) {
			e.preventDefault();
			var formData = new FormData(this);
			var form = $(this);
			form.parsley().validate();
			if (form.parsley().isValid()) {
				var isActive = 0;
				if ($("#isActive").is(':checked')) {
					isActive = 1;
				}
				formData.append('isActive', isActive);
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
						data: formData,
						beforeSend: function() {
							$('#saveQuotationBtn').prop('disabled', true);
							$('#cancelQuotationBtn').prop('disabled', true);
							$('#saveQuotationBtn').text('Please wait..');
						},
						success: function(data) {
							$('#saveQuotationBtn').text('Save');
							$('#saveQuotationBtn').prop('disabled', false);
							$('#cancelQuotationBtn').prop('disabled', false);
							var obj = JSON.parse(data);
							if (obj.status) {
								var ajax_complete = 0;
								var num = 0;
								for (i = 1; i <= table_len; i++) {
									var id = tr[i].id.substring(3);
									document.getElementById("quotationCode").value = obj.quotationCode;
									var quotationCode = document.getElementById("quotationCode").value;
									var quotationLineCode = document.getElementById("quotationLineCode" + id).value;
									//var categoryList = document.getElementById("categoryCode"+id);
									//var subCategoryCode= categoryList.options[categoryList.selectedIndex].getAttribute("value");
									//var categoryCode= categoryList.options[categoryList.selectedIndex].getAttribute("data-category-code");
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
			}
		});
	});

	function add_row() {
		var table = document.getElementById("pert_tbl");
		var table_len = (table.rows.length) - 6;
		var tr = table.getElementsByTagName("tr");
		var id = 0;
		var quotationLineCode = document.getElementById("quotationLineCode0").value;
		//var categoryList = document.getElementById("categoryCode0");
		//var subCategoryCode= categoryList.options[categoryList.selectedIndex].getAttribute("value");
		//var categoryCode= categoryList.options[categoryList.selectedIndex].getAttribute("data-category-code");
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
			'<td><select class="form-control select2 itemsdropDown" name="productCode[]" id="productCode' + id + '" disabled>' +
			document.getElementById("productCode0").innerHTML +
			'</select><input type="hidden" class="form-control" name="quotationLineCode' + id + '" id="quotationLineCode' + id + '" value="' + quotationLineCode + '"><input type="hidden" class="form-control" name="perProductTax' + id + '" id="perProductTax' + id + '" value="' + perProductTax + '"><input type="hidden" class="form-control" name="perProductTaxAmount' + id + '" id="perProductTaxAmount' + id + '" value="' + perProductTaxAmount + '"></td>' +
			'<td><input type="text" class="form-control decimal" name="qtyPerPerson' + id + '" id="qtyPerPerson' + id + '" value="' + qtyPerPerson + '" onkeyup="calculate_subTotal(' + id + ');getProductTaxAmount(' + id + ');"></td>' +
			'<td><input type="text" class="form-control decimal" name="pricePerPerson' + id + '" id="pricePerPerson' + id + '" value="' + pricePerPerson + '" onkeyup="calculate_subTotal(' + id + ');getProductTaxAmount(' + id + ');"></td>' +
			'<td><input type="text" class="form-control" name="subTotal' + id + '" id="subTotal' + id + '" value="' + subTotal + '" disabled></td>' +
			'<td><a href="#" class="btn btn-danger" onclick="delete_row(' + id + ')"><i class="fa fa-trash"></i></a>' +
			'</td>' +
			'</tr>';
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
			closeOnCancel: !1
		}, function(e) {
			if (e) {
				calculateTotal();
				var row = document.getElementById("row" + id);
				row.parentNode.removeChild(row);
			}
		});
	}

	function checkDuplicateProduct(id) {
		var table = document.getElementById("pert_tbl");
		var table_len = (table.rows.length) - 6;
		var tr = table.getElementsByTagName("tr");
		var productCode = document.getElementById("productCode" + id).value.toLowerCase();
		if (productCode != "") {
			for (i = 1; i <= table_len; i++) {
				var row_id = tr[i].id.substring(3);
				var productCode_row = document.getElementById("productCode" + row_id).value.toLowerCase();
				if (productCode_row == productCode && row_id != id) {
					toastr.error('product already exists', 'Duplicate Quotation', {
						"progressBar": true
					});
					//document.getElementById("categoryCode" + id).value = "";
					$(`#productCode${id}`).val(null).trigger("change");
					//document.getElementById("categoryCode" + id).focus();
					return false;
				}
			}
		}
	}

	function getTaxAmount() {
		var productCodes = $("select[name='productCode[]']").map(function() {
			return $(this).val();
		}).get();
		if (isNaN($('#discount').val())) {
			var discount = 0;
		} else {
			var discount = Number($('#discount').val());
		}
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
		if (charCode > 31 && (charCode < 46 || charCode > 57 || charCode == 47)) {
			return false;
		}
		return true;
	}

	function calculate_subTotal(id) {
		calculateTotal();
		if (isNaN($('#qtyPerPerson' + id).val())) {
			var qtyPerPerson = 0;
		} else {
			var qtyPerPerson = Number($('#qtyPerPerson' + id).val());
		}
		if (isNaN($('#pricePerPerson' + id).val())) {
			var pricePerPerson = 0;
		} else {
			var pricePerPerson = Number($('#pricePerPerson' + id).val());
		}
		subTotal = qtyPerPerson * pricePerPerson;
		$('#subTotal' + id).val(subTotal);

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
		var discount = Number($('#discount').val());
		var discountAmount = Number(subTotal - discount);
		$('#discountAmount').val(discountAmount);
		$('#totalTax').val(tax);
		$('#taxAmount').val(taxAmount);
		//getTaxAmount();
		//var taxAmount=$('#taxAmount').val();
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