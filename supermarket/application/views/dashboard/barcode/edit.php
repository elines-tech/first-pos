<div id="main-content">
	<div class="page-heading">
		<div class="page-title">
			<div class="row">
				<div class="col-12 col-md-6 order-md-1 order-last">
					<h3> Barcode</h3>
				</div>
				<div class="col-12 col-md-6 order-md-2 order-first">
					<nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="../../dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
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
							<h3>
								Edit Barcode
								<span class="float-end">
									<a id="cancelDefaultButton" class="btn btn-primary" href="<?= base_url() ?>barcode/listRecords">Back</a>
								</span>
							</h3>
						</div>
						<div class="card-content">
							<div class="card-body">
								<?php
								echo "<div class='text-danger text-center' id='error_message'>";
								if (isset($error_message)) {
									echo $error_message;
								}
								echo "</div>";
								?>
								<?php if ($barcodeData) {
									$barcode = $barcodeData->result_array()[0];
								?>
									<form id="barcodeForm" class="form" data-parsley-validate>
										<input type="hidden" class="form-control" id="code" name="code" value='<?= $barcode['code'] ?>'>
										<div class="row">
											<div class="col-md-12 col-12">
												<div class="row">
													<div class="col-md-4 col-12">
														<div class="form-group">
															<label for="branchName" class="form-label">Branch</label>
															<input type="hidden" readonly id="branchCode" value="<?= $barcode['branchCode'] ?>">
															<input type="text" id="branchName" name="branchName" readonly class="form-control" value="<?= $barcode['branchName'] ?>">
														</div>
													</div>
													<div class="col-md-4 col-12">
														<div class="form-group">
															<label for="" class="form-label">Batch</label>
															<input type="text" id="batchNo" name="batchCode" readonly class="form-control" value="<?= $barcode['batchNo'] ?>">
														</div>
													</div>
													<div class="col-md-4 col-12">
														<div class="form-group">
															<label for="" class="form-label">Barcode Number</label>
															<input type="text" id="barcodeText" name="barcodeText" readonly class="form-control" value="<?= $barcode['barcodeText'] ?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-4 col-sm-6">
														<div class="form-group">
															<label for="" class="from-label">Product <b class="text-danger">*</b></label>
															<?php
															$productName = "";
															if ($proQuery) {
																foreach ($proQuery->result() as $gets) {
																	if ($barcode['inwardLineCode'] == $gets->code) $productName = $gets->proNameVarName;
																}
															}
															?>
															<input type="hidden" id="productCode" name="productCode" readonly value="<?= $barcode['inwardlineCode'] ?>">
															<input type="text" class="form-control" readonly value="<?= $productName ?>">
														</div>
													</div>
													<div class="col-md-4 col-sm-6 mb-3">
														<label for="sellingQty" class="from-label">Selling Quantity <b class="text-danger">*</b></label>
														<input type="number" min="1" step="1" class="form-control text-left" name="sellingQty" id="sellingQty" onkeypress="return isDecimal(event)" value="<?= $barcode['sellingQty'] ?>" onkeyup="checkQty();">
													</div>
													<div class="col-md-4 col-sm-6">
														<label for="sellingQty" class="from-label">Selling Unit <b class="text-danger">*</b></label>
														<select class="form-select select2" id="sellingUnit" style="width:100%" name="sellingUnit" value="<?= $co['sellingUnit'] ?>">
															<?php
															if ($unitData) {
																foreach ($unitData->result() as $unit) {
																	if ($co['sellingUnit'] == $unit->code) {
																		echo "<option value='" . $unit->code . "' data-convfactor='" . $unit->conversionFactor . "' selected>" . $unit->unitName . "</option>";
																	} else {
																		echo "<option value='" . $unit->code . "' data-convfactor='" . $unit->conversionFactor . "'>" . $unit->unitName . "</option>";
																	}
																}
															}
															?>
														</select>
													</div>
													<div class="col-md-4 col-sm-6 mb-3" class="from-label">
														<label for="">Selling Price <b class="text-danger">*</b></label>
														<input type="number" min="1" step="0.01" class="form-control text-left" name="sellingPrice" id="sellingPrice" onkeypress="return isDecimal(event)" onkeyup="checkPrice();calculate_subTotal('')" value="<?= $barcode['sellingPrice'] ?>">
													</div>
													<div class="col-md-4 col-sm-6 mb-3" class="from-label">
														<label for="">Discount Price <b class="text-danger">*</b></label>
														<input type="number" min="0" step="0.01" class="form-control text-left" name="discountPrice" id="discountPrice" onkeypress="return isDecimal(event)" onkeyup="calculate_subTotal()" value="<?= $barcode['discountPrice'] ?>">
													</div>
													<div class="col-md-4 col-sm-6 mb-3" class="from-label">
														<label for="">Tax Percent <b class="text-danger">*</b></label>
														<input type="number" step="0.01" min="0" class="form-control text-left" name="taxPercent" id="taxPercent" disabled value="<?= $barcode['taxPercent'] ?>">
													</div>
													<div class="col-md-4 col-sm-6 mb-3" class="from-label">
														<label for="">Tax Amount</label>
														<input type="number" class="form-control text-left" name="taxAmount" id="taxAmount" disabled value="<?= $barcode['taxAmount'] ?>">
													</div>
												</div>
												<div class="row">
													<div class="col-12 d-flex justify-content-end">
														<button type="submit" class="btn btn-success" id="saveBarcodeBtn">Update</button>
													</div>
												</div>
											</div>
										</div>
									</form>
								<?php } ?>
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
		$('.cancel').removeClass('btn-default').addClass('btn-info');
		$("#barcodeForm").submit(function(e) {
			e.preventDefault();
			var formData = new FormData(this);
			var form = $(this);
			form.parsley().validate();
			if (form.parsley().isValid()) {
				$.ajax({
					type: 'post',
					url: base_path + "barcode/updatebarcode",
					data: {
						code: $("input#code").val(),
						inwardLineCode: $("input#productCode").val(),
						sellingUnit: $("input#sellingUnit").val(),
						sellingQty: $("input#sellingQty").val(),
						sellingPrice: $("input#sellingPrice").val(),
						taxPercent: $("input#taxPercent").val(),
						taxAmount: $("input#taxAmount").val(),
						discountPrice: $("input#discountPrice").val()
					},
					dataType: "JSON",
					beforeSend: function() {
						$('#saveBarcodeBtn').prop('disabled', true);
						$('#saveBarcodeBtn').text('Please wait..');
					},
					success: function(response) {
						if (response.status) {
							toastr.success(response.message, 'Barcode', {
								"progressBar": true,
								"onHidden": function() {
									window.location.replace("<?= base_url("barcode/listRecords") ?>");
								}
							});
						} else {
							toastr.success(response.message, 'Barcode', {
								"progressBar": true,
								"onHidden": function() {
									$('#saveBarcodeBtn').removeAttr('disabled');
									$('#saveBarcodeBtn').text('Update');
								}
							});
						}
					},
					error: function() {
						$('#saveBarcodeBtn').removeAttr('disabled');
						$('#saveBarcodeBtn').text('Update');
					}
				});
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

	function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	}

	function checkPrice(id) {
		var sellingPrice = $('#sellingPrice' + id).val();
		if (sellingPrice != "" && sellingPrice == 0) {
			$('#sellingPrice' + id).val('');
			toastr.error('Selling price should be greater than 0', 'Barcode', {
				"progressBar": false
			});
			return false;
		}
	}

	function checkQty(id) {
		var sellingQty = $('#sellingQty' + id).val();
		if (sellingQty != "" && sellingQty == 0) {
			$('#sellingQty' + id).val('');
			toastr.error('Selling Qty should be greater than 0', 'Barcode', {
				"progressBar": false
			});
			return false;
		}

	}

	function calculate_subTotal() {
		var discountPrice = Number($('#discountPrice').val());
		var sellingPrice = Number($('#sellingPrice').val());
		if (discountPrice >= sellingPrice && discountPrice != '') {
			toastr.error('Discount should be less than selling price', 'Barcode', {
				"progressBar": true
			});
			$('#discountPrice').val('');
			discountPrice = 0;
		}
		discountAmount = Number(sellingPrice - discountPrice).toFixed(2);
		var taxPercent = Number($('#taxPercent').val());
		taxAmount = (discountAmount * (taxPercent / 100));
		$('#taxAmount').val(taxAmount.toFixed(2));
	}
</script>