<div id="main-content">
	<div class="page-heading">
		<div class="page-title">
			<div class="row">
				<div class="col-12 col-md-6 order-md-1 order-last">
					<h3>Transfer</h3>
				</div>
				<div class="col-12 col-md-6 order-md-2 order-first">
					<nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
							<li class="breadcrumb-item active" aria-current="page">Transfer</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
		<div class="container">
			<section id="multiple-column-form" class="mt-5">
				<div class="row match-height">
					<div class="col-12">
						<div class="card">
							<div class="card-header">
								<h3>Add Transfer<span style="float:right"><a id="cancelDefaultButton" href="<?= base_url() ?>transfer/listRecords" class="btn btn-sm btn-primary">Back</a></span></h3>
							</div>
							<div class="card-content">
								<div class="card-body">
									<form id="transferForm" method="post" action="<?= base_url('transfer/saveTransfer') ?>" class="form" data-parsley-validate>
										<?php
										echo "<div class='text-danger text-center' id='error_message'>";
										if (isset($error_message)) {
											echo $error_message;
										}
										echo "</div>";
										?>


										<div class="form-group col-md-12 col-sm-12 col-xs-12 row">


											<div class="col-md-12 col-sm-12 col-xs-12 mb-3">
												<div class="form-group mandatory">
													<label class="form-label">Date</label>
													<input type="date" class="form-control bg-white" name="transferDate" id="transferDate" value="<?= date('Y-m-d') ?>">
												</div>
											</div>


											<div class="form-group col-md-6 col-sm-12 col-xs-12 mb-3 mandatory">
												<label class="form-label">From Branch</label>
												<div class="col-md-12 col-sm-12 col-xs-12">
													<input type="hidden" class="form-control" id="transferCode" name="transferCode">
													<?php if ($branchCode != "") { ?>
														<input type="hidden" class="form-control" name="branch" id="branch" value="<?= $branchCode; ?>" readonly>
														<input type="text" class="form-control" name="fromBranch" value="<?= $branchName; ?>" readonly>

													<?php } else { ?>
														<select class="form-select select2" name="fromBranch" id="fromBranch" data-parsley-required="true" style="width:100%" required onchange="getBranchBatches()">
														</select>
													<?php } ?>
												</div>
											</div>

											<div class="form-group col-md-6 col-sm-12 col-xs-12 mb-3 mandatory">
												<label class="form-label">To Branch</label>
												<div class="col-md-12 col-sm-12 col-xs-12">
													<select class="form-select select2" name="toBranch" id="toBranch" data-parsley-required="true" required>
													</select>
												</div>
											</div>

										</div>



										<table class="table table-hover" id="transferTable">
											<thead>
												<tr>
													<th style="width:25%">Batch No<i class="text-danger">*</i></th>
													<th style="width:25%">Product<i class="text-danger">*</i></th>
													<th style="width:12%">Qty<i class="text-danger">*</i></th>
													<th style="width:12%">Price</th>
													<th style="width:12%">UOM</th>
													<th style="width:12%">Sub Total</th>
													<th style="width:2%">Action</th>
												</tr>
											</thead>
											<tbody id="table-rows">
												<tr id="row0" class="inrows removeclassP0">
													<td>
														<select class="form-select select2" style="width:100%" required id="batchCode0" name="batchCode[]" onchange="getItem(0);getBranchBatches();">
														</select>
													</td>
													<td class="span3 supplier">
														<input type="hidden" class="form-control" name="transferLineCode[]" id="transferLineCode0">
														<select class="form-select select2" style="width:100%" required id="itemCode0" name="itemCode[]" onchange="checkDuplicateItem(0);getItemStorageUnit(0);calculate_subTotal(0)">
														</select>
													</td>
													<td class="text-right">
														<input type="number" class="form-control" required name="itemQty[]" id="itemQty0" onchange="checkQty(0);">
													</td>
													<td class="text-right">
														<input type="number" step="0.01" class="form-control" name="itemPrice[]" id="itemPrice0" onchange="checkPrice(0);" readonly>
													</td>
													<td class="text-right">
														<input type="text" class="form-control" name="itemUnit[]" id="itemUnit0" readonly>
														<input type="hidden" class="form-control" name="dbitemUnit[]" id="dbitemUnit0" value="">
														<input type="hidden" class="form-control" name="productCode[]" id="productCode0" readonly>
														<input type="hidden" class="form-control" name="variantCode[]" id="variantCode0" readonly>
														<input type="hidden" class="form-control" name="inwsku[]" id="inwsku0" readonly>
														<input type="hidden" class="form-control" name="expiryDate[]" id="expiryDate0" readonly>
														<input type="hidden" class="form-control" name="tax[]" id="tax0" readonly>
														<input type="hidden" class="form-control" name="stock[]" id="stock0" readonly>
													</td>
													<td class="test">
														<input type="text" class="form-control subtotal" name="subTotal[]" id="subTotal0" readonly>
													</td>
													<td>
														<a href="#" id="view" class="btn btn-success add_fields" data-id="0"><i class="fa fa-plus"></i></a>
													</td>
												</tr>
											</tbody>
										</table>
										<div id="pricesection_add_btn"></div>
										<div class="row">
											<div class="col-md-6 offset-md-6 mb-3 col-12">
												<div class="form-group mandatory">
													<label for="" class="form-label">Total</label>
													<input type="text" id="total" class="text-right form-control" name="total" value="0.00" readonly="readonly" autocomplete="off">
												</div>
											</div>
											<div class="col-12 d-flex justify-content-end">
												<button type="submit" class="btn btn-primary submitBtn" id="approveTransferBtn" name="approveTransferBtn" value="1">Save & Approve</button>
												<button type="submit" class="btn btn-success" id="saveTransferBtn">Save</button>
												<button type="reset" class="btn btn-light-secondary" id="cancelTransferBtn">Reset</button>
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
</div>
<script>
	var room = 1;
	var objTo = document.querySelector('#table-rows');
	var branchCode = "";
	var batchCode = "";
	$(document).ready(function() {
		$("#fromBranch").select2({
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
		$("#toBranch").select2({
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
		$('.cancel').removeClass('btn-default').addClass('btn-info');
		var today = new Date().toISOString().split('T')[0];
		document.getElementsByName("transferDate")[0].setAttribute('max', today);

		$("body").on("change", "#toBranch", function(e) {
			e.preventDefault();
			var toBranch = $("#toBranch").val();
			var fromBranch = $('#fromBranch').val();
			if (toBranch == fromBranch) {
				$("#toBranch").val(null).trigger('change');
				toastr.error("From branch and To branch should not be same", 'Transfer', {
					"progressBar": true
				});
				return false;
			}
		});

		$("body").on("change", "#fromBranch", function(e) {
			e.preventDefault();
			var toBranch = $("#toBranch").val();
			var fromBranch = $('#fromBranch').val();
			if (toBranch == fromBranch) {
				$("#fromBranch").val(null).trigger('change');
				toastr.error("From branch and To branch should not be same", 'Transfer', {
					"progressBar": true
				});
				return false;
			}
		});

		$("body").delegate(".add_fields", "click", function(e) {
			e.preventDefault();
			var pos = $(this).data('id');
			transfer_rows(pos, 'add');
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

	function remove() {
		swal({
			title: "Are you sure?",
			text: "are you change branch?",
			type: "warning",
			showCancelButton: !0,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes",
			cancelButtonText: "No",
			closeOnConfirm: !1,
			closeOnCancel: !1
		}, function(e) {
			if (e) {
				swal.close();
				$(".inrows").remove();
				transfer_rows(0, 'add');
				getBranchBatches();
			} else {
				swal.close();
			}
		});
	}

	function checkDuplicateItem(id) {
		var table = document.getElementById("transferTable");
		var table_len = (table.rows.length) - 2;
		var tr = table.getElementsByTagName("tr");
		var itemCode = document.getElementById("itemCode" + id).value.toLowerCase();
		if (itemCode != "") {
			for (i = 1; i <= table_len; i++) {
				var rowClass = tr[i].classList;
				var row_id = rowClass[1].substring(12);
				var itemCode_row = document.getElementById("itemCode" + row_id).value.toLowerCase();
				if (itemCode_row == itemCode && row_id != id) {
					toastr.error('Item already exists', 'Duplicate Transfer item', {
						"progressBar": true
					});
					//document.getElementById("itemCode" + id).value = "";
					$("#itemCode" + id).val(null).trigger("change");
					document.getElementById("itemUnit" + id).value = "";
					document.getElementById("itemCode" + id).focus();
					return false;
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
		var itemPrice = $('#itemPrice' + id).val();
		if (itemPrice != '' && itemPrice == 0) {
			$('#itemPrice' + id).val('');
			toastr.error('Item price should be greater than 0', 'Transfer Products', {
				"progressBar": false
			});
			return false;
		}
	}

	function checkQty(id) {
		var itemQty = Number($('#itemQty' + id).val());
		var stock = Number($('#stock' + id).val());
		if (itemQty != '' && itemQty > 0) {
			if (stock < itemQty) {
				$('#itemQty' + id).val('');
				calculate_subTotal(id)
				toastr.error('Stock is not available.', 'Transfer Products', {
					"progressBar": false
				});
				return false;
			}
		} else {
			$('#itemQty' + id).val('');
			toastr.error('Item Quantity should be greater than 0', 'Transfer Products', {
				"progressBar": false
			});
			return false;
		}
		calculate_subTotal(id);
	}

	function getBranchBatches() {
		if (branchCode !== "") {
			remove();
		}
		branchCode = $('#fromBranch').val();

		if (branchCode != '' && branchCode != undefined) {
			$.ajax({
				url: base_path + "transfer/getBranchBatches",
				type: 'POST',
				data: {
					'branchCode': branchCode
				},
				success: function(response) {
					var obj = JSON.parse(response);
					if (obj.status) {
						$('#batchCode0').html(obj.batchHtml);
						$("#batchCode0").select2();
					} else {
						$("#batchCode0").val(null).trigger('change.select2');
						$("#batchCode0").html('');
					}
				}
			})
		}
	}

	function calculate_subTotal(id) {
		var itemQty = Number($('#itemQty' + id).val()).toFixed(2);
		var itemPrice = Number($('#itemPrice' + id).val()).toFixed(2);

		subTotal = itemQty * itemPrice;
		$('input#subTotal' + id).val(subTotal.toFixed(2));
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

	function getItem(id) {
		var batchCode = $('#batchCode' + id).val();
		if (batchCode != '' && batchCode != undefined) {
			$.ajax({
				url: base_path + 'transfer/getItems',
				data: {
					'batchCode': batchCode
				},
				type: 'post',
				success: function(response) {
					var res = JSON.parse(response);
					if (res.status) {
						//$('select#itemCode'+id).prop('disabled', false);
						$('select#itemCode' + id).select2();
						$('select#itemCode' + id).html('');
						//$('select#itemCode'+id).val('').trigger('change.select2');
						$('select#itemCode' + id).html(res.items);
					} else {
						$('#batchCode' + id).val('');
						//$('select#itemCode'+id).val('').trigger('change.select2');
						//$('select#itemCode' +id).prop('disabled', true);
						toastr.error("No items are available in the selected batch. Please choose another.", 'Invalid Batch', {
							"progressBar": true
						});
					}
				}
			});
		}
	}

	function getItemStorageUnit(id) {
		var itemCode = $('#itemCode' + id).find(':selected').data('val');
		var itemPrice = $('#itemCode' + id).find(':selected').data('price');
		var iwdSku = $('#itemCode' + id).find(':selected').data('inw-sku');
		var variantCode = $('#itemCode' + id).find(':selected').attr('id');
		var stock = $('#itemCode' + id).find(':selected').data('stock');
		var tax = $('#itemCode' + id).find(':selected').data('tax');
		var expiryDate = $('#itemCode' + id).find(':selected').data('expirydate');
		if (itemCode != '' && itemCode != undefined) {
			$('#itemPrice' + id).val(itemPrice);
			$('#productCode' + id).val(itemCode);
			$('#inwsku' + id).val(iwdSku);
			$('#variantCode' + id).val(variantCode);
			$('#stock' + id).val(stock);
			$('#expiryDate' + id).val(expiryDate);
			$('#tax' + id).val(tax);
			$.ajax({
				url: base_path + "transfer/getItemStorageUnit",
				type: 'POST',
				data: {
					'itemCode': itemCode
				},
				success: function(response) {
					var obj = JSON.parse(response);
					if (obj.status) {
						$('#itemUnit' + id).val(obj.unitText)
						$('#dbitemUnit' + id).val(obj.unitCode)
					} else {
						$("#itemUnit" + id).val('')
						$("#dbitemUnit" + id).val('')
					}
				}
			});
		}
	}

	function delete_row(id) {
		var table = document.getElementById("transferTable");
		var table_len = (table.rows.length) - 2;
		var tr = table.getElementsByTagName("tr");
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
				var row = document.getElementById("row" + id);
				row.parentNode.removeChild(row);
				/*
				if (table_len == 2) {
				    $('#fromBranch').attr('disabled', false);
				}
				*/
			} else {
				swal.close();
			}
		});
	}

	function transfer_rows(count, flag) {
		if (flag == 'edit') {
			if (room == 1) {
				room = count;
			}
		} else room = count;

		if (room == undefined || room == null) room = 0;

		var unit, batchCode, product = "";
		var qty, price, amount = 0;
		batchCode = $(`#batchCode${room}`).val();
		product = $(`#itemCode${room}`).val();
		unit = $(`#itemUnit${room}`).val();
		qty = Number($(`#itemQty${room}`).val());
		price = Number($(`#itemPrice${room}`).val());
		amount = Number($(`#subTotal${room}`).val());
		$(".add_btn").empty().append('<button type="button" class="btn btn-danger remove_price_fields" data-id="' + room + '"><i class="fa fa-trash"></i></button>');
		room++;
		var divtest = document.createElement("tr");
		divtest.setAttribute("class", "inrows removeclassP" + room);
		var element = '<td>';
		element += '<select class="form-select select2" required style="width:100%" name="batchCode[]" id="batchCode' + room + '" onchange="getItem(' + room + ');"></select>';
		element += '<td class="span3 supplier">';
		element += '<input type="hidden" class="form-control" name="transferLineCode[]" id="transferLineCode' + room + '">';
		element += '<select class="form-select select2" required id="itemCode' + room + '" name="itemCode[]" onchange="checkDuplicateItem(' + room + ');getItemStorageUnit(' + room + ');calculate_subTotal(' + room + ');"></select>';
		element += '</td>';
		element += '<td class="text-right">';
		element += '<input type="number" class="form-control" required name="itemQty[]" id="itemQty' + room + '" onchange="checkQty(' + room + ');">';
		element += '</td>';
		element += '<td class="text-right">';
		element += '<input type="number" step="0.01" class="form-control" name="itemPrice[]" id="itemPrice' + room + '" onchange="checkPrice(' + room + ');"  readonly onkeyup="calculate_subTotal(' + room + ')">';
		element += '</td>';
		element += '<td class="text-right">';
		element += '<input type="text" class="form-control" name="itemUnit[]" id="itemUnit' + room + '" readonly>';
		element += '<input type="hidden" class="form-control" name="dbitemUnit[]" id="dbitemUnit' + room + '" value="">';
		element += '<input type="hidden" class="form-control" name="productCode[]" id="productCode' + room + '" readonly>';
		element += '<input type="hidden" class="form-control" name="variantCode[]" id="variantCode' + room + '" readonly>';
		element += '<input type="hidden" class="form-control" name="inwsku[]" id="inwsku' + room + '" readonly>';
		element += '<input type="hidden" class="form-control" name="expiryDate[]" id="expiryDate' + room + '" readonly>';
		element += '<input type="hidden" class="form-control" name="tax[]" id="tax' + room + '" readonly>';
		element += '<input type="hidden" class="form-control" name="stock[]" id="stock' + room + '" readonly>';
		element += '</td>';
		element += '<td class="test">';
		element += '<input type="text" class="form-control subtotal" name="subTotal[]" id="subTotal' + room + '" readonly>';
		element += '</td>';
		element += '<td>';
		element += '<button type="button" class="btn btn-danger remove_price_fields" data-id="' + room + '"><i class="fa fa-trash"></i></button>';
		element += '</td>';
		divtest.innerHTML = element;
		objTo.appendChild(divtest);
		var bcode = $('#fromBranch').val();
		if (bcode != '' && bcode != undefined) {
			$.ajax({
				url: base_path + "transfer/getBranchBatches",
				type: 'POST',
				data: {
					'branchCode': bcode
				},
				success: function(response) {
					var obj = JSON.parse(response);
					if (obj.status) {
						$('#batchCode' + room).html(obj.batchHtml);
						$('#batchCode' + room).select2();
					} else {
						$("#batchCode" + room).val(null).trigger('change.select2');
						$("#batchCode" + room).html('');
					}
				}
			})
		}
		$("#pricesection_add_btn").empty().append('<div class="col-md-1 mb-3"><button type="button" data-id="' + room + '" class="btn btn-success add_fields"><i class="fa fa-plus"></i></button></div>');
		$("#inwardForm").parsley('refresh');
	}
</script>