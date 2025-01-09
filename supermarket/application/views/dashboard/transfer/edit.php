<?php
$branch = '';
if ($branches) {
	foreach ($branches->result() as $it) {
		$branch .= "<option value='" . $it->batchNo . "'>" . $it->batchNo . "</option>";
	}
}
?>

<?php include '../supermarket/config.php'; ?>


<div id="main-content">
	<div class="page-heading">
		<div class="page-title">
			<div class="row">
				<div class="col-12 col-md-6 order-md-1 order-last">
					<h3><?php echo $translations['Transfer']?></h3>
				</div>
				<div class="col-12 col-md-6 order-md-2 order-first">
					<nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="../../dashboard/listRecords"><i class="fa fa-dashboard"></i><?php echo $translations['Dashboard']?></a></li>
							<li class="breadcrumb-item active" aria-current="page"><?php echo $translations['Transfer']?></li>
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
								<h3><?php echo $translations['Edit Transfer']?><span style="float:right"><a id="cancelDefaultButton" href="<?= base_url() ?>transfer/listRecords" class="btn btn-sm btn-primary"><?php echo $translations['Back']?></a></span></h3>
							</div>
							<div class="card-content">
								<div class="card-body">
									<form id="transferForm" class="form" action="<?= base_url('transfer/updateTransfer') ?>" method="post" data-parsley-validate>
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
											<div class="form-group row">


												<div class="col-md-2 col-sm-12 col-xs-12 mb-3">
													<label class="form-label lng"><?php echo $translations['Date']?><i class="text-danger">*</i></label>
													<input type="date" class="form-control bg-white" name="transferDate" id="transferDate" disabled value="<?= date('Y-m-d', strtotime($result['inwardDate'])) ?>">
												</div>


												<div class="col-md-5 col-sm-12 col-xs-12 mb-3">
													<label class="form-label lng"><?php echo $translations['From Branch']?></label>
													<div class="form-group mb-3 col-md-12 col-sm-12 col-xs-12 d-flex">
														<input type="hidden" class="form-control" id="transferCode" name="transferCode" value="<?= $result['code'] ?>">
														<input type="hidden" class="form-control" id="fromBranch" name="fromBranch" value="<?= $result['branchCode'] ?>">
														<input type="text" class="form-control" id="fromBranchName" disabled name="fromBranchName" value="<?= $result['fromBranchName'] ?>" readonly>
														<input type="hidden" class="form-control" id="batch" name="batch" value="<?= $result['batchNo'] ?>">
													</div>

												</div>


												<div class="col-md-5 col-sm-12 col-xs-12 mb-3">
													<label class="form-label lng"><?php echo $translations['To Branch']?></label>
													<div class="form-group mb-3 col-md-10 col-sm-12 col-xs-12 d-flex">
														<input type="hidden" class="form-control" id="toBranch" name="toBranch" value="<?= $result['supplierCode'] ?>">
														<input type="text" class="form-control" id="toBranchName" disabled name="toBranchName" value="<?= $result['toBranchName'] ?>" readonly>

														<a id="view" href="<?php echo base_url(); ?>Branch/listRecords" class="col-md-2 col-sm-12 col-xs-12 circleico btn btn-primary a_plus d-flex align-items-center justify-content-center rounded-circle shadow-sm " style="">
															<i class="fa fa-plus"></i>
														</a>

													</div>
												</div>


											</div>
											<table class="table table-hover" id="transferTable">
												<thead>
													<tr>
														<th style="width:25%"><?php echo $translations['Batch No']?><i class="text-danger">*</i></th>
														<th style="width:25%"><?php echo $translations['Product']?><i class="text-danger">*</i></th>
														<th style="width:12%"><?php echo $translations['Qty']?><i class="text-danger">*</i></th>
														<th style="width:12%"><?php echo $translations['Price']?></th>
														<th style="width:12%"><?php echo $translations['UOM']?></th>
														<th style="width:12%"><?php echo $translations['Sub Total']?></th>
														<th style="width:2%"><?php echo $translations['Action']?></th>
													</tr>
												</thead>
												<tbody id="table-rows">
													<?php
													$i = 1;
													if ($inwardLineEntries) {
														foreach ($inwardLineEntries->result_array() as $co) {

													?>
															<tr id="row<?= $i ?>" class="inrows removeclassP<?= $i ?>">
																<td class="span3 supplier">
																	<input type="hidden" class="form-control" name="transferLineCode[]" id="transferLineCode<?= $i ?>" value="<?= $co['code'] ?>">
																	<select class="form-select select2" id="batchCode<?= $i ?>" name="batchCode[]" onchange="getItem(<?= $i ?>);" style="width:100%">
																		<option value="">Select</option>
																		<?php
																		if ($branches) {
																			foreach ($branches->result() as $it) {
																				if ($it->batchNo == $co['fromBatchNo']) {
																					echo "<option value='" . $it->batchNo . "' selected>" . $it->batchNo . "</option>";
																				} else {
																					echo "<option value='" . $it->batchNo . "'>" . $it->batchNo . "</option>";
																				}
																			}
																		}
																		?>
																	</select>
																</td>
																<td class="span3 supplier">
																	<select class="form-select select2" id="itemCode<?= $i ?>" name="itemCode[]" onchange="getItemStorageUnit(<?= $i ?>);calculate_subTotal(<?= $i ?>);">
																		<?php
																		echo $prdArr[$i - 1];
																		?>
																	</select>
																</td>
																<td class="text-right">
																	<input type="number" class="form-control" name="itemQty[]" id="itemQty<?= $i ?>" value="<?= $co['productQty'] ?>" onchange="checkQty(<?= $i ?>);">
																</td>

																<td class="text-right">
																	<input type="number" step="0.01" class="form-control" name="itemPrice[]" id="itemPrice<?= $i ?>" value="<?= $co['productPrice'] ?>" readonly onchange="checkPrice(<?= $i ?>);" onkeypress="return isNumber(event)" onkeyup="calculate_subTotal(<?= $i ?>)">
																</td>

																<td class="text-right">
																	<input type="text" class="form-control" name="itemUnit[]" id="itemUnit<?= $i ?>" readonly value="<?= $co['unitName'] ?>">
																	<input type="hidden" class="form-control" name="dbitemUnit[]" id="dbitemUnit<?= $i ?>" value="<?= $co['productUnit'] ?>">
																	<input type="hidden" class="form-control" name="productCode[]" id="productCode<?= $i ?>" readonly value="<?= $co['productCode'] ?>">
																	<input type="hidden" class="form-control" name="variantCode[]" id="variantCode<?= $i ?>" readonly value="<?= $co['variantCode'] ?>">
																	<input type="hidden" class="form-control" name="inwsku[]" id="inwsku<?= $i ?>" readonly value="<?= $co['sku'] ?>">
																	<input type="hidden" class="form-control" name="expiryDate[]" id="expiryDate<?= $i ?>" readonly value="<?= $co['expiryDate'] ?>">
																	<input type="hidden" class="form-control" name="tax[]" id="tax<?= $i ?>" readonly value="<?= $co['tax'] ?>">
																	<input type="hidden" class="form-control" name="stock[]" id="stock<?= $i ?>" readonly value="<?= $co['stock'] ?>">
																</td>
																<td class="test">
																	<input type="text" class="form-control subtotal" name="subTotal[]" id="subTotal<?= $i ?>" readonly value="<?= $co['subTotal'] ?>">
																</td>
																<td>
																	<a href="#" class="btn btn-danger" onclick="delete_row(<?= $i ?>,'<?= $co['code'] ?>')"><i class="fa fa-trash"></i></a>
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
											<div class="row">
												<div class="col-md-4 offset-md-8 mb-3 col-12">
													<div class="form-group mandatory">
														<label for="" class="form-label"><?php echo $translations['Total']?></label>
														<input type="number" step="0.01" id="total" class="text-right form-control" name="total" value="<?= $result['total'] ?>" readonly autocomplete="off">
													</div>
												</div>
												<div class="col-12 d-flex justify-content-end">
													<button type="submit" class="btn btn-primary submitBtn" id="approveTransferBtn" name="approveTransferBtn" value="1"><?php echo $translations['Save & Approve']?></button>
													<button type="submit" class="btn btn-success submitBtn" id="saveTransferBtn" name="saveTransferBtn" value="2"><?php echo $translations['Save']?></button>

													<button type="reset" class="btn btn-light-secondary" id="cancelTransferBtn"><?php echo $translations['Reset']?></button>
												</div>
											</div>
										<?php } else {
											echo '<div class="text-center"><h5>No data found</h5></div>';
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
</div>
<script>
	var room = 1;
	var objTo = document.querySelector('#table-rows');
	var branch = <?php $branch; ?>
	$(document).ready(function() {
		getBranchBatches();
		$('.cancel').removeClass('btn-default').addClass('btn-info');

		var today = new Date().toISOString().split('T')[0];
		document.getElementsByName("transferDate")[0].setAttribute('max', today);

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
						//$('select#itemCode'+id).attr('disabled', false);
						$('select#itemCode' + id).empty();
						$('select#itemCode' + id).html(res.items);
					} else {
						$('#batchCode' + id).val('');
						// $('select#itemCode' +id).attr('disabled', true);
						toastr.error("No items are available in the selected batch. Please choose another.", 'Invalid Batch', {
							"progressBar": true
						});
					}
				}
			});
		}
	}

	function checkDuplicateItem(id) {
		var table = document.getElementById("transferTable");
		var table_len = (table.rows.length) - 2;
		var tr = table.getElementsByTagName("tr");
		var itemCode = document.getElementById("itemCode" + id).value.toLowerCase();
		if (itemCode != "") {
			for (i = 1; i <= table_len; i++) {
				var row_id = tr[i].id.substring(3);
				var itemCode_row = document.getElementById("itemCode" + row_id).value.toLowerCase();
				if (itemCode_row == itemCode && row_id != id) {
					toastr.error('Item already exists', 'Duplicate item', {
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
		var branchCode = $('#fromBranch').val();
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

					} else {
						$("#batchCode0").val(null).trigger('change.select2');
						$("#batchCode0").html('');
					}
				}
			})
		}
	}

	function calculate_subTotal(id) {
		var itemQty = Number($('#itemQty' + id).val());
		var itemPrice = Number($('#itemPrice' + id).val())
		subTotal = itemQty * itemPrice;
		$('input#subTotal' + id).val(subTotal);
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


	function getItemStorageUnit(id) {
		var itemCode = $('#itemCode' + id).find(':selected').data('val');
		var itemPrice = $('#itemCode' + id).find(':selected').data('price');
		var iwdSku = $('#itemCode' + id).find(':selected').data('inw-sku');
		var variantCode = $('#itemCode' + id).find(':selected').attr('id');
		var stock = $('#itemCode' + id).find(':selected').data('stock');
		var tax = $('#itemCode' + id).find(':selected').data('tax');
		var expiryDate = $('#itemCode' + id).find(':selected').data('expirydate');
		if (itemCode != '' && itemCode != undefined) {
			$('#productCode' + id).val(itemCode);
			$('#inwsku' + id).val(iwdSku);
			$('#variantCode' + id).val(variantCode);
			$('#stock' + id).val(stock);
			$('#expiryDate' + id).val(expiryDate);
			$('#tax' + id).val(tax);
			$('#itemPrice' + id).val(itemPrice);
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
			closeOnCancel: !1
		}, function(e) {
			if (e) {
				$.ajax({
					url: base_path + "Transfer/deleteTransferLine",
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
										swal.close();
										var row = document.getElementById("row" + id);
										row.parentNode.removeChild(row);
										var sum = 0;
										$('.subtotal').each(function() {
											sum += Number($(this).val());
										});
										$("input#total").val(sum);
									}
								});
						} else {
							swal.close();
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

	function add_row(count, flag) {

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

		if ($(`#batchCode${room}`).length > 0) {
			if (batchCode == "" || batchCode == null) {
				$(`#batchCode${room}`).focus();
				toastr.error("Please select batch", "Transfer");
				return false;
			}
			if (product == "" || product == null) {
				$(`#itemUnit${room}`).val(null);
				$(`#itemCode${room}`).focus();
				toastr.error("Please select product.", "Transfer");
				return false;
			}

			if (unit == "" || unit == null) {
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
						<td class="span3 supplier">
							<input type="hidden" class="form-control" name="transferLineCode[]" id="transferLineCode${room}" value="">
							<select class="form-select select2" id="batchCode${room}" name="batchCode[]" onchange="getItem(${room});" style="width:100%">
							 <option value=""></option>
                                <?= $branch ?>	
							</select>
						</td>
					<td class="span3 supplier">                                                 
						<select class="form-select select2" id="itemCode${room}" name="itemCode[]" onchange="getItemStorageUnit(${room});calculate_subTotal(${room});">
						 
						</select>
					</td>
					<td class="text-right">
						<input type="number" class="form-control" name="itemQty[]" id="itemQty${room}" onchange="checkQty(${room});">
					</td>

					<td class="text-right">
						<input type="number" step="0.01" class="form-control" name="itemPrice[]" id="itemPrice${room}"  readonly onchange="checkPrice(${room});" onkeyup="calculate_subTotal(${room})">
					</td>
					
					<td class="text-right">
						<input type="text" class="form-control" name="itemUnit[]" id="itemUnit${room}" readonly value="<?= $co['unitName'] ?>">
						<input type="hidden" class="form-control" name="dbitemUnit[]" id="dbitemUnit${room}" value="<?= $co['productUnit'] ?>">
						<input type="hidden" class="form-control" name="productCode[]" id="productCode${room}" readonly>
						<input type="hidden" class="form-control" name="variantCode[]" id="variantCode${room}" readonly>
						<input type="hidden" class="form-control" name="inwsku[]" id="inwsku${room}" readonly>
						<input type="hidden" class="form-control" name="expiryDate[]" id="expiryDate${room}" readonly>
						<input type="hidden" class="form-control" name="tax[]" id="tax${room}" readonly>
						<input type="hidden" class="form-control" name="stock[]" id="stock${room}" readonly>
					</td>
					<td class="test">
						<input type="text" class="form-control subtotal" name="subTotal[]" id="subTotal${room}" readonly>
					</td>
					<td>
						<button type="button" class="btn btn-danger remove_price_fields" data-id="${room}"><i class="fa fa-trash"></i></button>
					</td>                
                     `;
		divtest.innerHTML = element;
		objTo.appendChild(divtest);
		$("#pricesection_add_btn").empty().append('<div class="col-md-1 mb-3"><button type="button" data-id="' + room + '" class="btn btn-success add_fields"><i class="fa fa-plus"></i></button></div>');


	}
</script>