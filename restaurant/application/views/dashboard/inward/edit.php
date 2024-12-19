<?php
$units = "";
if ($unitmaster) {
	foreach ($unitmaster->result() as $um) {
		$units .= "<option value='" . $um->code . "'>" . $um->unitName . "</option>";
	}
}
?>
<nav class="navbar navbar-light">
	<div class="container d-block">
		<div class="row">
			<div class="col-12 col-md-6 order-md-1 order-last">
				<a href="<?php echo base_url(); ?>inward/listRecords"><i id="exitButton" class="fa fa-times fa-2x"></i></a>
			</div>
		</div>
	</div>
</nav>
<div class="container">
	<section id="multiple-column-form" class="mt-5">
		<div class="row match-height">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">Edit Inward</h3>
					</div>
					<div class="card-content">
						<div class="card-body">
							<form id="inwardForm" class="form" method="post" enctype="multipart/form-data" action="<?= base_url('inward/updateInwards') ?>" data-parsley-validate>
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
												<div class="col-md-4 col-12">
													<div class="form-group mandatory">
														<label for="" class="form-label">Inward</label>
														<input type="date" id="inwardDate" class="form-control" name="inwardDate" id="inwardDate" value="<?= date('Y-m-d', strtotime($result['inwardDate'])) ?>" required>
													</div>
												</div>
												<div class="col-md-4 col-12">
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
												<div class="col-md-4 col-12">
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
												<div class="col-md-4 col-12">
													<div class="form-group mandatory">
														<label for="" class="form-label">Total</label>
														<input type="number" step="0.01" id="total" class="form-control" required name="total" readonly value="<?= $result['total'] ?>" required>
													</div>
												</div>
												<!--<div class="col-md-4 col-12">
													<div class="form-group">
														<label for="product-name" class="form-label">Reference</label>
														<input type="text" class="form-control" name="refNo" id="refNo" value="<?= $result['ref'] ?>">
													</div>
												</div>-->
												<div class="col-md-2 col-12 d-none">
													<div class="form-group">
														<label class="form-label lng">Active</label>
														<div class="input-group">
															<div class="input-group-prepend"><span class="input-group-text bg-soft-primary">
																	<input type="checkbox" name="isActive" class="form-check-input"></span>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12 col-12">
													<table id="pert_tbl" class="table table-sm table-stripped" style="width:100%;">
														<thead>
															<tr>
																<th width="35%">Item <span style="color:red;">*</span></th>
																<th width="15%">Unit <span style="color:red;">*</span></th>
																<th width="15%">Qty <span style="color:red;">*</span></th>
																<th width="15%">Price <span style="color:red;">*</span></th>
																<th width="15%">Subtotal <span style="color:red;">*</span></th>
																<th width="5%"></th>
															</tr>
														</thead>
														<tbody id="table-rows">
															<?php
															$i = 1;
															if ($inwardLineEntries) {
																foreach ($inwardLineEntries->result_array() as $co) {

															?>
																	<tr id="row<?= $i ?>" class="inrows removeclassP<?= $i ?>">
																		<td>
																			<input type="hidden" class="form-control" name="inwardLineCode[]" id="inwardLineCode<?= $i ?>" value="<?= $co['code'] ?>">
																			<select class="form-select select2" style="width:100%" id="itemCode<?= $i ?>" name="itemCode[]" onchange="checkDuplicateItem(<?= $i ?>);getItemStorageUnit(<?= $i ?>)">
																				<option value="">Select Item</option>
																				<?php
																				if ($items) {
																					foreach ($items->result() as $it) {
																						if ($it->code == $co['itemCode']) {
																							echo "<option value='" . $it->code . "' selected >" . $it->itemEngName . "</option>";
																						} else {
																							echo "<option value='" . $it->code . "'>" . $it->itemEngName . "</option>";
																						}
																					}
																				}
																				?>
																			</select>
																		</td>
																		<td>
																			<select class="form-select select2" style="width:100%" id="itemUnit<?= $i ?>" name="itemUnit[]" readonly>
																				<option value=""></option>
																				<?php
																				if ($unitmaster) {
																					foreach ($unitmaster->result() as $um) {
																						if ($um->code == $co['itemUom']) {
																							echo "<option value='" . $um->code . "' selected >" . $um->unitName . "</option>";
																						} else {
																							echo "<option value='" . $um->code . "'>" . $um->unitName . "</option>";
																						}
																					}
																				}
																				?>
																			</select>
																		</td>
																		<td>
																			<input type="number" class="form-control" name="itemQty[]" id="itemQty<?= $i ?>" value="<?= $co['itemQty'] ?>" onchange="checkQty(<?= $i ?>);" onkeyup="calculate_subTotal(<?= $i ?>);">
																		</td>
																		<td>
																			<input type="number" step="0.01" class="form-control" name="itemPrice[]" onchange="checkPrice(<?= $i ?>);" id="itemPrice<?= $i ?>" value="<?= $co['itemPrice'] ?>" onkeyup="calculate_subTotal(<?= $i ?>)">
																		</td>
																		<td>
																			<input type="number" step="0.01" class="form-control subtotal" name="subTotal[]" id="subTotal<?= $i ?>" readonly value="<?= $co['subTotal'] ?>">
																		</td>
																		<td>
																			<a href="#" class="btn btn-danger" onclick="delete_row(<?= $i ?>,'<?= $co['code'] ?>')"><i class="fa fa-trash"></i>
																		</td>
																	</tr>
															<?php
																	$i += 1;
																}
															} ?>
														</tbody>
													</table>
													<div id="pricesection_add_btn">
														<div class="col-md-1 mb-3">
															<?php if ($i == 1) { ?>
																<button class="btn btn-success" type="button" onclick="add_row(1,'add');"><i class="fa fa-plus"></i></button>
															<?php } else { ?>
																<button class="btn btn-success" type="button" onclick="add_row(<?= $i - 1 ?>,'edit');"><i class="fa fa-plus"></i></button>
															<?php } ?>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-12 d-flex justify-content-end"> 
													<button type="submit" id="saveDefault" class="btn btn-primary submitBtn" name="approveInwardBtn" value="1">Save & Approve</button>
													<button type="submit" class="btn btn-success" id="saveInwardBtn">Save</button>
													<a href="<?php echo base_url(); ?>inward/listRecords" id="cancelInwardBtn" class="btn btn-light-secondary">Close</a>
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
	var room = 1;
	var objTo = document.querySelector('#table-rows');
	var unitoptions = "<?= $units ?>";

	function eventFire() {
		var $eventSelect = $(`select.itemsdropDown`);
		$eventSelect.on("select2:select", function(e) {
			var itemCode = e.target.value;
			var id = e.target.id;
			var no = id.substring(8);
			if (itemCode != '') {
				checkDuplicateItem(no);
				$.ajax({
					url: base_path + "inward/getItemStorageUnit",
					type: 'POST',
					data: {
						'itemCode': itemCode
					},
					success: function(response) {
						if (response != '') {
							$(`#itemUnit${no}`).val(response).trigger('change');
						} else {
							$(`#itemCode${no}`).val(null).trigger('change');
						}
					}
				})
			}
		});
	}
	$(document).ready(function() {

		$("select.itemsdropDown").select2({
			placeholder: "Select Item",
			allowClear: true,
			ajax: {
				url: base_path + 'Common/getItemInward',
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
		eventFire();

		$('.cancel').removeClass('btn-default').addClass('btn-info');
		var today = new Date().toISOString().split('T')[0];
		document.getElementsByName("inwardDate")[0].setAttribute('max', today);
		$("#inwardForm").parsley();
		$('.numberonly').keypress(function(e) {
			var charCode = (e.which) ? e.which : event.keyCode
			if (String.fromCharCode(charCode).match(/[^1-9]/g))
				return false;
		});

		$('#cancelInwardBtn').click(function(e) {
			window.location.reload();
		});

		$("body").delegate(".add_fields", "click", function() {
			var pos = $(this).data('id');
			var flag = $(this).data('flag');
			add_row(pos, flag);
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

	function add_row(count, flag) {
		if (flag == 'edit') {
			if (room == 1) {
				room = count;
			}
		} else room = count;

		if (room == undefined || room == null) room = 0;

		var unit, product = "";
		var qty, price, tax, amount = 0;
		product = $(`#itemCode${room}`).val();
		unit = $(`#itemUnit${room}`).val();
		qty = Number($(`#itemQty${room}`).val());
		price = Number($(`#itemPrice${room}`).val());
		amount = Number($(`#subTotal${room}`).val());
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

			if ((isNaN(price)) || Number(price) <= 0) {
				$(`#itemPrice${room}`).focus();
				toastr.error("Price should be an non zero value ", "Inward");
				return false;
			}

			if (Number(amount) > Number(amount)) {
				$(`#subTotal${room}`).focus();
				toastr.error("Product amount must be greater 0", "InwardD");
				return false;
			}
		}
		$(".add_btn").empty().append('<button type="button" class="btn btn-danger remove_price_fields" data-id="' + room + '"><i class="fa fa-trash"></i></button>');
		room++;
		var divtest = document.createElement("tr");
		divtest.setAttribute("class", "inrows removeclassP" + room);
		var element = `
			<td>
				<select class="form-select itemsdropDown select2" style="width:100%" name="itemCode[]" id="itemCode${room}"></select>
				<input type="hidden" class="form-control" name="inwardLineCode[]" id="inwardLineCode${room}" value=""></td>
			<td>
				<select class="form-select2" style="width:100%" name="itemUnit[]" id="itemUnit${room}" readonly>${unitoptions}</select>
			</td>
			<td>
				<input type="number" class="form-control" name="itemQty[]" id="itemQty${room}" value="0" onkeyup="calculate_subTotal(${room})" onchange="checkQty(${room});"  onkeypress="return isNumber(event)">
			</td>
			<td>
				<input type="number" step="0.01" class="form-control" name="itemPrice[]" id="itemPrice${room}" value="0.00" onkeyup="calculate_subTotal(${room})" onchange="checkPrice(${room});"  onkeypress="return isDecimal(event)">
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
		$("#pricesection_add_btn").empty().append('<div class="col-md-1 mb-3"><button type="button" data-flag="edit" data-id="' + room + '" class="btn btn-success add_fields"><i class="fa fa-plus"></i></button></div>');
		$(`select#itemCode${room}`).select2({
			placeholder: "Select Item",
			allowClear: true,
			ajax: {
				url: base_path + 'Common/getItemInward',
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
		eventFire();
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
			closeOnCancel: !1
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


	function checkDuplicateItem(id) {
		var table = document.getElementById("pert_tbl");
		var table_len = (table.rows.length) - 1;
		var tr = table.getElementsByTagName("tr");
		var itemCode = document.getElementById("itemCode" + id).value.toLowerCase();
		if (itemCode != "") {
			for (i = 1; i <= table_len; i++) {
				var rowClass = tr[i].classList;
				var row_id = rowClass[1].substring(12);
				var itemCode_row = document.getElementById("itemCode" + row_id).value.toLowerCase();
				if (itemCode_row == itemCode && row_id != id) {
					toastr.error('Item already exists', 'Duplicate', {
						"progressBar": true
					});
					$(`#itemCode${id}`).val(null).trigger("change");
					document.getElementById("itemCode" + id).value = "";
					document.getElementById("itemUnit" + id).value = "";
					document.getElementById("itemPrice" + id).value = "0.00";
					document.getElementById("itemQty" + id).value = "0";
					document.getElementById("subTotal" + id).value = "0.00";
					calculateTotal();
					document.getElementById("itemCode" + id).focus();
					return false;
				}
			}
		}
	}

	function calculate_subTotal(id) {
		var itemQty = Number($('#itemQty' + id).val());
		var itemPrice = Number($('#itemPrice' + id).val());
		subTotal = itemQty * itemPrice;
		$('#subTotal' + id).val(subTotal.toFixed(2));
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

	function getItemStorageUnit(id) {
		var itemCode = $('#itemCode' + id).val();
		if (itemCode != '') {
			$.ajax({
				url: base_path + "inward/getItemStorageUnit",
				type: 'POST',
				data: {
					'itemCode': itemCode
				},
				success: function(response) {
					if (response != '') {
						$('#itemUnit' + id).val(response).trigger('change');
					} else {
						$("#itemCode" + id).val('').trigger('change');
					}
				}
			});
		}
	}
</script>