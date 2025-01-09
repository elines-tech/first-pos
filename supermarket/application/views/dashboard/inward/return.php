<?php include '../supermarket/config.php'; ?>

<div id="main-content">
	<div class="page-heading">
		<div class="page-title">
			<div class="row">
				<div class="col-12 col-md-6 order-md-1 order-last">
					<h3><?php echo $translations['Inward']?></h3>
				</div>
				<div class="col-12 col-md-6 order-md-2 order-first">
					<nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="../../dashboard/listRecords"><i class="fa fa-dashboard"></i><?php echo $translations['Dashboard']?></a></li>
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
							<h3 class="card-title"><?php echo $translations['Add Return']?></h3>
						</div>
						<div class="card-content">
							<div class="card-body">
								<form id="inwardForm" class="form" name="returnForm" method="post" enctype="multipart/form-data" data-parsley-validate>
									<?php if ($inwardData) {
										$result = $inwardData->result_array()[0];
									?>
										<div class="row">
											<div class="col-md-12 col-12">
												<div class="row">
													<div class="col-md-3 col-12">
														<div class="form-group mandatory">
															<label for="" class="form-label"><?php echo $translations['Batch No']?></label>
															<input type="text" id="batchNo" disabled class="form-control" name="batchNo" value="<?= $result['batchNo'] ?>">
														</div>
													</div>
													<div class="col-md-3 col-12">
														<div class="form-group mandatory">
															<label for="" class="form-label"><?php echo $translations['Inward Date']?></label>
															<input type="hidden" class="form-control" id="inwardCode" name="inwardCode" value="<?= $result['code'] ?>">
															<input type="date" id="inwardDate" disabled class="form-control" name="inwardDate" value="<?= date('Y-m-d', strtotime($result['inwardDate'])) ?>">
														</div>
													</div>
													<div class="col-md-3 col-12">
														<div class="form-group mandatory">
															<label for="product-name" class="form-label"><?php echo $translations['Branch']?></label>
															<select class="form-select select2" name="branchCode" id="branchCode" disabled>
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
															<label for="product-name" class="form-label"><?php echo $translations['Supplier']?></label>
															<select class="form-select select2" name="supplierCode" id="supplierCode" disabled>
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
															<label for="product-name" class="form-label"><?php echo $translations['Reference']?></label>
															<input type="text" class="form-control" name="refNo" id="refNo" value="<?= $result['ref'] ?>" disabled>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12 col-12">
														<table id="pert_tbl" class="table table-sm table-stripped" style="width:100%;">
															<thead>
																<tr>
																	<th width="5%"><?php echo $translations['Return']?></th>
																	<th width="25%"><?php echo $translations['Product']?></th>
																	<th width="15%"><?php echo $translations['Unit']?></th>
																	<th width="15%"><?php echo $translations['Stock Quantity']?></th>
																	<th width="15%"><?php echo $translations['Return Quantity']?></th>
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
																				<?php
																				if ($co['stock'] != '' && $co['stock'] > 0) { ?>
																					<input type="checkbox" style="width:25px;height:25px" onchange="enableReturnQty(<?= $i ?>);" id="isReturn<?= $i ?>" name="isReturn<?= $i ?>">
																				<?php } ?>
																			</td>
																			<td><input type="hidden" class="form-control" id="itemCode<?= $i ?>" name="itemCode<?= $i ?>" value="<?= $co['productCode'] ?>">
																				<input type="hidden" class="form-control" id="variantCode<?= $i ?>" name="variantCode<?= $i ?>" value="<?= $co['variantCode'] ?>">
																				<input type="hidden" class="form-control" id="proNameVarName<?= $i ?>" name="proNameVarName<?= $i ?>" value="<?= $co['proNameVarName'] ?>"><?= $co['proNameVarName'] ?>
																			</td>
																			<td><input type="hidden" class="form-control" id="itemUnit<?= $i ?>" name="itemUnit<?= $i ?>" value="<?= $co['productUnit'] ?>"><?= $co['unitName'] ?></td>
																			<td>
																				<input type="text" class="form-control" name="stockQty<?= $i ?>" disabled id="stockQty<?= $i ?>" value="<?= $co['stock'] ?>">
																			</td>
																			<td>
																				<input type="text" class="form-control" name="returnQty<?= $i ?>" disabled onchange="checkReturnQty(<?= $i ?>);" id="returnQty<?= $i ?>" onkeypress="return isDecimal(event)">
																			</td>
																		</tr>
																<?php }
																} ?>
															</tbody>
														</table>
													</div>
												</div>
												<div class="row">
													<div class="col-12 d-flex justify-content-end">
														<button type="submit" class="btn btn-success" id="saveReturnBtn"><?php echo $translations['Save']?></button>
														<button type="button" id="cancelReturnBtn" class="btn btn-light-secondary"><?php echo $translations['Reset']?></button>
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
	$(document).ready(function() {
		var today = new Date().toISOString().split('T')[0];
		$("#inwardForm").parsley();
		$("form").on("submit", function(e) {
			e.preventDefault();
			if ($('input[type="checkbox"]').length == 0) {
				toastr.error('At lease one item should be returned', 'Inward Return', {
					"progressBar": true
				});
				return false;
			} else {
				var table = document.getElementById("pert_tbl");
				var table_len = (table.rows.length) - 1;
				var tr = table.getElementsByTagName("tr");
				var ajax_complete = 0;
				var num = 0;
				for (i = 1; i <= table_len; i++) {
					var id = tr[i].id.substring(3);
					if (document.getElementById("isReturn" + id).checked && ($('#returnQty' + id).val() == '' || $('#returnQty' + id).val() == 0 || isNaN($("#returnQty" + id).val()))) {
						toastr.error('Please provide valid return quantity for checked return', 'Inward Return', {
							"progressBar": true
						});
						return false;
					}
				}
				for (i = 1; i <= table_len; i++) {
					var id = tr[i].id.substring(3);
					var batchNo = document.getElementById("batchNo").value;
					var branchCode = document.getElementById("branchCode").value;
					var inwardCode = document.getElementById("inwardCode").value;
					var productCode = document.getElementById("itemCode" + id).value;
					var variantCode = document.getElementById("variantCode" + id).value;
					var proNameVarName = document.getElementById("proNameVarName" + id).value;
					var unitCode = document.getElementById("itemUnit" + id).value;
					var returnQty = document.getElementById("returnQty" + id).value;

					if (productCode != '' && returnQty != '') {
						num++;
						$.ajax({
							type: 'post',
							url: base_path + "inward/saveReturn",
							data: {
								batchNo: batchNo,
								inwardCode: inwardCode,
								branchCode: branchCode,
								unitCode: unitCode,
								returnQty: returnQty,
								variantCode: variantCode,
								productCode: productCode,
								proNameVarName: proNameVarName

							},
							beforeSend: function() {
								$('#saveReturnBtn').prop('disabled', true);
								$('#cancelReturnBtn').prop('disabled', true);
								$('#saveReturnBtn').text('Please wait..');
							},
							success: function(response) {
								ajax_complete++;
								if (num == ajax_complete) {
									toastr.success('Inward returned successfully', 'Inward', {
										"progressBar": true
									});
									location.href = base_path + "inward/listRecords"
								} else {
									toastr.success('Failed to return inward', 'Inward', {
										"progressBar": true
									});
									$('#saveReturnBtn').text('Save');
									$('#saveReturnBtn').prop('disabled', false);
									$('#cancelReturnBtn').prop('disabled', false);
								}
							}
						});
					}
				}
			}
		});
	});

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

	function enableReturnQty(id) {
		if ($("#isReturn" + id).is(':checked')) {
			$('#returnQty' + id).prop('disabled', false)
		} else {
			$('#returnQty' + id).prop('disabled', true)
		}
	}

	function checkReturnQty(id) {
		var stockQty = Number($('#stockQty' + id).val());
		var returnQty = Number($('#returnQty' + id).val());
		if (returnQty != '' && stockQty != 0 && stockQty != '' && returnQty > 0) {
			if (returnQty > stockQty) {
				toastr.error('Return Quantity should be less than or equal to stock quantity', 'Inward Return', {
					"progressBar": false
				});
				$('#returnQty' + id).val('');
				$('#returnQty' + id).focus();
				return false
			}
		} else {
			toastr.error('Return Quantity should be greater than 0', 'Inward Return', {
				"progressBar": false
			});
			$('#returnQty' + id).val('');
			$('#returnQty' + id).focus();
			return false;
		}
	}
</script>