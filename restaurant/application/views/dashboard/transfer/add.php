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
			<div class="col-12 col-md-6 order-md-1 order-last"><a href="<?php echo base_url(); ?>Transfer/listRecords"><i id="exitButton" class="fa fa-times fa-2x"></i></a></div>
		</div>
	</div>
</nav>


<div class="container">
	<section id="multiple-column-form" class="mt-5">
		<div class="row match-height">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">Add Transfer</h3>
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


									<div class="col-md-6 col-sm-12 col-xs-12 mb-3">
										<div class="form-group mandatory">
											<label class="form-label">From Branch</label>
											<div class="col-md-12 col-sm-12 col-xs-12">
												<input type="hidden" class="form-control" id="transferCode" name="transferCode">
												<select class="form-select select2" name="fromBranch" id="fromBranch" data-parsley-required="true" style="width:100%" required>

												</select>
											</div>

										</div>
									</div>


									<div class="col-md-6 col-sm-12 col-xs-12 mb-3">
										<div class="form-group mandatory">
											<label class="form-label">To Branch</label>
											<div class="col-md-12 col-sm-12 col-xs-12">
												<select class="form-select select2" name="toBranch" id="toBranch" data-parsley-required="true" required>

												</select>
											</div>

											<!--<a href="<?php echo base_url(); ?>Branch/listRecords" class="col-md-2 col-sm-12 col-xs-12 circleico btn btn-primary a_plus text-light d-flex align-items-center justify-content-center rounded-circle shadow-sm " style="">
                                                <i class="fa fa-plus"></i>
                                            </a>-->
										</div>
									</div>


								</div>


								<table class="table table-bordered table-hover" id="transferTable">
									<thead>
										<tr>
											<th class="text-center">Item Name<i class="text-danger">*</i></th>
											<th class="text-center">Price<i class="text-danger">*</i></th>
											<th class="text-center">Qty<i class="text-danger">*</i></th>
											<th class="text-center">UOM</th>
											<th class="text-center">Sub Total</th>
											<th class="text-center">Action</th>
										</tr>
									</thead>
									<tbody id="table_rows">
										<tr id="row0" class="inrows removeclassP0">
											<td>
												<input type="hidden" class="form-control" name="transferLineCode[]" id="transferLineCode0">
												<select class="form-select  itemsdropDown select2" id="itemCode0" name="itemCode[]" onchange="calculate_subTotal(0)">
													<option value="">Select Item</option>

												</select>
											</td>
											<td class="text-right">
												<input type="number" step="0.01" class="form-control" name="itemPrice[]" id="itemPrice0" readonly>

											</td>
											<td class="text-right">
												<input type="number" class="form-control" name="itemQty[]" id="itemQty0" onchange="checkQty(0);">
											</td>
											<td class="text-right">
												<select class="form-select select2" id="itemUnit0" name="itemUnit[]" readonly>
													<option value=""></option>
													<?= $units ?>
												</select>
											</td>
											<td class="test">
												<input type="text" class="form-control subtotal" name="subTotal[]" id="subTotal0" readonly>
											</td>
											<td class="add_btn">
												<a id="view" href="#" class="btn btn-success add_fields" data-id="0"><i class="fa fa-plus"></i></a>
											</td>
										</tr>
									</tbody>

								</table>
								<div id="pricesection_add_btn"></div>
								<div class="row">
									<div class="col-md-4 offset-md-8 col-12">
										<div class="form-group mandatory">
											<label for="" class="form-label">Total</label>
											<input type="number" step="0.01" id="total" class="form-control" name="total" required readonly>
										</div>
									</div>
									<div class="col-12 d-flex justify-content-end">

										<?php if ($insertRights == 1) { ?>
											<button type="submit" class="btn btn-primary submitBtn" id="approveTransferBtn" name="approveTransferBtn" value="1">Save & Approve</button>
											<button type="submit" class="btn btn-success" id="saveTransferBtn">Transfer</button>
										<?php } ?>
										<a href="<?php echo base_url(); ?>Transfer/listRecords" class="btn btn-light-secondary" id="cancelTransferBtn">Close</a>
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
	var room = 1;
	var objTo = document.querySelector('#table_rows');
	var unitoptions = "<?= $units ?>";
	var branchCode = "";
	$(document).ready(function() {
		$('.cancel').removeClass('btn-default').addClass('btn-info');
		var today = new Date().toISOString().split('T')[0];
		document.getElementsByName("transferDate")[0].setAttribute('max', today);

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
		}).on("select2:select", function(e) {
			// var branchCode = $(e.currentTarget).val();
			var toBranch = $("#toBranch").val();
			var fromBranch = $('#fromBranch').val();
			if (toBranch == fromBranch) {
				$("#toBranch").val('');
				$('#fromBranch').val('');
				toastr.error("From branch and To branch should not be same", 'Transfer', {
					"progressBar": true
				});
				return false;

			}
			if (branchCode !== "") {
				remove();
			}
			branchCode = $('#fromBranch').val();

			getItemsFromBranch(branchCode);
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

		$("body").on("change", "#toBranch", function(e) {
			e.preventDefault();
			var toBranch = $("#toBranch").val();
			var fromBranch = $('#fromBranch').val();
			if (toBranch == fromBranch) {
				$("#toBranch").val('');
				$('#fromBranch').val('');
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

	function getItemsFromBranch(branchCode) {
		$("select.itemsdropDown").select2({
			placeholder: "Select Item",
			allowClear: true,
			ajax: {
				url: base_path + 'Common/getItemFromBranch',
				type: "get",
				delay: 250,
				dataType: 'json',
				data: function(params) {
					var query = {
						search: params.term,
						branchCode: branchCode,
					}

					return query;
				},
				processResults: function(response) {
					return {
						//results: response
						results: $.map(response, function(item) {
							return {
								text: item.text,
								id: item.id,
								price: item.price,
								stock: item.stock
							}
						})
					};

				},
				cache: true
			}
		});

		eventFire();

	}

	function eventFire() {
		var $eventSelect = $(`select.itemsdropDown`);
		$eventSelect.on("select2:select", function(e) {
			var itemCode = e.target.value;
			var id = e.target.id;
			var no = id.substring(8);
			var getdata = $(this).select2('data')[0];
			$(`#itemPrice${no}`).val(getdata.price);
			if (itemCode != '') {
				checkDuplicateItem(no);
				$.ajax({
					url: base_path + "Transfer/getItemStorageUnit",
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
					toastr.error('Item already exists', 'Transfer', {
						"progressBar": true
					});
					$(`#itemCode${id}`).val(null).trigger("change");
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
			} else {
				swal.close();
			}
		});
	}

	function checkQty(id) {
		var itemQty = Number($('#itemQty' + id).val());
		var getdata = $('#itemCode' + id).select2('data')[0];
		var stock = getdata.stock

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
		calculate_subTotal(id)
	}


	function calculate_subTotal(id) {

		var itemQty = Number($('#itemQty' + id).val());
		var itemPrice = Number($('#itemPrice' + id).val());

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

	function transfer_rows(count, flag) {
		if (flag == 'edit') {
			if (room == 1) {
				room = count;
			}
		} else room = count;

		if (room == undefined || room == null) room = 0;

		var unit, product = "";
		var qty, price, amount = 0;
		product = $(`#itemCode${room}`).val();
		unit = $(`#itemUnit${room}`).val();
		qty = Number($(`#itemQty${room}`).val());
		price = Number($(`#itemPrice${room}`).val());
		amount = Number($(`#subTotal${room}`).val());

		if ($(`#itemCode${room}`).length > 0) {
			if (product == "" || product == null) {
				$(`#itemUnit${room}`).val(null);
				$(`#itemCode${room}`).focus();
				toastr.error("Please select product.", "Transfer");
				return false;
			}

			if (unit == "" || product == null) {
				$(`#itemUnit${room}`).focus();
				toastr.error("Please select an selling unit", "Transfer");
				return false;
			}

			if (qty == "" || Number(qty) <= 0) {
				$(`#itemQty${room}`).focus();
				toastr.error("Quantity must be non zero value", "Transfer");
				return false;
			}

			if ((isNaN(price)) || Number(price) <= 0) {
				$(`#itemPrice${room}`).focus();
				toastr.error("Price should be an non zero value ", "Transfer");
				return false;
			}

			if (Number(amount) > Number(amount)) {
				$(`#subTotal${room}`).focus();
				toastr.error("Product amount must be greater 0", "Transfer");
				return false;
			}
		}
		$(".add_btn").empty().append('<button type="button" class="btn btn-danger remove_price_fields" data-id="' + room + '"><i class="fa fa-trash"></i></button>');
		room++;
		var divtest = document.createElement("tr");
		divtest.setAttribute("class", "inrows removeclassP" + room);
		var element = `
		    <tr class="newitem">
			<td>
				<select class="form-select itemsdropDown select2" style="width:100%" name="itemCode[]" id="itemCode${room}" onchange="calculate_subTotal(${room})"></select>
				<input type="hidden" class="form-control" name="transferLineCode[]" id="transferLineCode${room}" value="${room}">
			</td>
			 <td class="text-right">
				<input type="number" step="0.01" class="form-control" name="itemPrice[]" id="itemPrice${room}"  readonly>

			</td>
			<td class="text-right">
				<input type="number" class="form-control" name="itemQty[]" id="itemQty${room}" onchange="checkQty(${room});">
			</td>
			<td class="text-right">
				<select class="form-select select2" id="itemUnit${room}" name="itemUnit[]" readonly>
					<option value=""></option>
					<?= $units ?>
				</select>
			</td>
			<td class="test">
				<input type="text" class="form-control subtotal" name="subTotal[]" id="subTotal${room}" readonly>
			</td>
			<td>
				<button type="button" class="btn btn-danger remove_price_fields" data-id="${room}"><i class="fa fa-trash"></i></button>
			</td>
			</tr>
		`;
		divtest.innerHTML = element;
		objTo.appendChild(divtest);
		$("#pricesection_add_btn").empty().append('<div class="col-md-1 mb-3"><button type="button" data-id="' + room + '" class="btn btn-success add_fields"><i class="fa fa-plus"></i></button></div>');
		$(`select#itemCode${room}`).select2({
			placeholder: "Select Item",
			allowClear: true,
			ajax: {
				url: base_path + 'Common/getItemFromBranch',
				type: "get",
				delay: 250,
				dataType: 'json',
				data: function(params) {
					var query = {
						search: params.term,
						branchCode: $("#fromBranch").val(),
					}

					return query;
				},
				processResults: function(response) {
					return {
						results: $.map(response, function(item) {
							return {
								text: item.text,
								id: item.id,
								price: item.price,
								stock: item.stock
							}
						})
					};

				},
				cache: true
			}
		});

		eventFire();

	}
</script>