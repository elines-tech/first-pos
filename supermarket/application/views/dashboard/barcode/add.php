<div id="main-content">
	<div class="page-heading">
		<div class="page-title">
			<div class="row">
				<div class="col-12 col-md-6 order-md-1 order-last">
					<h3>Barcode</h3>
				</div>
				<div class="col-12 col-md-6 order-md-2 order-first">
					<nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><i class="fa fa-dashboard"></i> Dashboard</li>
							<li class="breadcrumb-item active" aria-current="page">Barcode</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
	<section class="section">
		<div class="row match-height">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h3>Barcode<span class="float-end"><a class="btn btn-primary" href="<?= base_url() ?>barcode/listRecords">Back</a></span></h3>
					</div>
					<div class="card-content">
						<div class="card-body">
							<form id="barcodeForm" class="form" data-parsley-validate>
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
											<div class="col-md-4 col-12">
												<div class="form-group mandatory">
													<label for="branchCode" class="form-label">Branch</label>
													<?php if($branchCode!=""){?>
														  <input type="hidden" class="form-control" name="branchCode" id="branchCode" value="<?= $branchCode; ?>" readonly>
														  <input type="text" class="form-control" name="branchName" value="<?= $branchName; ?>" readonly>
													<?php } else{?>
													<select class="form-select select2 branchCode" id="branchCode" name="branchCode" required style="width:100%">
													</select>
													<?php } ?>
												</div>
											</div>
											<div class="col-md-4 col-12">
												<div class="form-group mandatory">
													<label for="batchNo" class="form-label">Batch</label>
													<select class="form-select select2" id="batchNo" name="batchNo" required style="width:100%">
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4 col-sm-6">
												<div class="form-group mandatory">
													<label for="branchCode" class="form-label">Product</label>
													<select class="form-select prds select2" required id="productCode" style="width:100%" name="productCode" onchange="getProductDetails()">
													</select>
													<input type="hidden" class="form-control" id="code" name="code" value=''>
												</div>
											</div>
											<div class="col-md-4 col-sm-6">
												<div class="form-group mandatory">
													<label for="branchCode" class="form-label">Selling Unit</label>
													<select class="form-select select2" required id="sellingUnit" style="width:100%" name="sellingUnit">
													</select>
												</div>
											</div>
											<div class="col-md-4 col-sm-6">
												<div class="form-group mandatory">
													<label for="sellingQty" class="form-label">Selling Qty</label>
													<input type="text" required class="form-control text-right" name="sellingQty" id="sellingQty" onkeypress="return isDecimal(event)" onkeyup="checkQty(0);">
												</div>
											</div>
											<div class="col-md-4 col-sm-6">
												<div class="form-group mandatory">
													<label for="sellingPrice" class="form-label">Selling Price</label>
													<input type="text" required class="form-control text-right" name="sellingPrice" id="sellingPrice" onkeypress="return isDecimal(event)" onkeyup="checkPrice(0);calculate_subTotal(0)">
												</div>
											</div>
											<div class="col-md-4 col-sm-6">
												<div class="form-group mandatory">
													<label for="discountPrice" class="form-label">Discount Price</label>
													<input type="text" required class="form-control text-right" name="discountPrice" id="discountPrice" onkeypress="return isDecimal(event)" onkeyup="calculate_subTotal(0)">
												</div>
											</div>
											<div class="col-md-4 col-sm-6">
												<div class="form-group mandatory">
													<label for="taxPercent" class="form-label">Tax(%)</label>
													<input type="text" required class="form-control text-right" name="taxPercent" id="taxPercent" readonly>
												</div>
											</div>
											<div class="col-md-4 col-sm-6">
												<div class="form-group mandatory">
													<lable for="taxAmount" class="form-label">Tax Amount</lable>
													<input type="text" required class="form-control text-right" name="taxAmount" id="taxAmount" readonly>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-12 d-flex justify-content-end">
										<button type="submit" class="btn btn-success white me-1 mb-1 sub_1" id="saveBarcodeBtn">Save</button>
										<button type="button" id="cancelBarcodeBtn" class="btn btn-light-secondary me-1 mb-1">Reset</button>
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
    var selectedBranch = selectedBatchNo = "";
	$(document).ready(function() {
		$('.cancel').removeClass('btn-default').addClass('btn-info');

		/*$(".branchCode").on("focus", function() {
			selectedBranch = $(this).val();
		});

		$("#batchNo").on("focus", function() {
			selectedBatchNo = $(this).val();
		});

		$(".branchCode").on("change", function() {
			if (selectedBranch !== "" && selectedBranch !== null) {
				//if (selectedBranch != $(this).val()) {
					$("#batchNo").val(null).trigger("change");
					$("#productCode").val(null).trigger("change"); 
				//}
			}
		});

		$("#batchNo").on("change", function() {
			if (selectedBatchNo !== "" && selectedBatchNo !== null) {
				if (selectedBatchNo != $(this).val()) {
					$("#productCode").val(null).trigger("change");
				}
			}
			fetchPorducts();
		});*/

		$(".branchCode").select2({
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
			if (selectedBranch !== "") {
				$("#batchNo").val(null).trigger("change");
				$("#productCode").val(null).trigger("change"); 
			}
			selectedBranch = $('.branchCode').val();
		});

		$("#batchNo").select2({
			placeholder: "Select Batch Number",
			allowClear: true,
			ajax: {
				url: base_path + 'Common/getBatchesByBranch',
				type: "get",
				delay: 250,
				dataType: 'json',
				data: function(params) {
					var query = { 
						search: params.term,      
						branch: $("#branchCode").val(),
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
			if (selectedBatchNo !== "") {
				$("#productCode").val(null).trigger("change"); 
			}
			selectedBatchNo = $('#batchNo').val();
			fetchPorducts();
		});


		$("#barcodeForm").on("submit", function(e) {
			e.preventDefault();
			var formData = new FormData(this);
			var form = $(this);
			form.parsley().validate();
			if (form.parsley().isValid()) {
				var batchNo = $('#batchNo').val();
				var inwardLineCode = $("#productCode").val();
				var sellingUnit = $("#sellingUnit").val();
				var sellingQty = $("#sellingQty").val();
				var sellingPrice = $("#sellingPrice").val();
				var taxPercent = $("#taxPercent").val();
				var taxAmount = $("#taxAmount").val();
				var discountPrice = $("#discountPrice").val();
				$.ajax({
					type: 'post',
					url: base_path + "barcode/saveBarcode",
					data: {
						batchNo: batchNo,
						inwardLineCode: inwardLineCode,
						sellingUnit: sellingUnit,
						sellingQty: sellingQty,
						sellingPrice: sellingPrice,
						taxPercent: taxPercent,
						taxAmount: taxAmount,
						discountPrice: discountPrice
					},
					dataType: "json",
					beforeSend: function() {
						$('#saveBarcodeBtn').prop('disabled', true);
						$('#cancelBarcodeBtn').prop('disabled', true);
						$('#saveBarcodeBtn').text('Please wait..');
					},
					success: function(response) {
						if (response.status) {
							toastr.success(response.message, 'Barcode', {
								"progressBar": true,
								"onHidden": function() {
									location.reload();
								}
							});
						} else {
							toastr.warning(response.message, 'Barcode', {
								"progressBar": true,
								"onHidden": function() {
									location.reload();
								}
							});
						}
					},
					error: function() {
						toastr.error("Something went wrong. Please try again later", "Barcode", {
							"progressBar": true,
							"onHidden": function() {
								location.reload();
							}
						});
					},
					complete: function() {
						$('#saveBarcodeBtn').text('Save');
						$('#saveBarcodeBtn').prop('disabled', false);
						$('#cancelBarcodeBtn').prop('disabled', false);
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

	function fetchPorducts() {
		if ($("#batchNo").val() !== null && $("#batchNo").val() !== "") {
			$("#productCode").select2({
				placeholder: "Select Products",
				allowClear: true,
				ajax: {
					url: base_path + "barcode/getBatchwiseInwardProducts",
					type: 'POST',
					delay: 250,
					dataType: 'json',
					data: function(params) {
						var query = {
							search: params.term,
							'batchCode': $('#batchNo').val()
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
		}
	}

	function getProductDetails() {
		debugger
		var productCode = $("#productCode").val();
		var getdata = $('#productCode').select2('data')[0];
		var baseUnit = getdata.unit;
		var taxGroup = getdata.taxgroup;
		console.log(getdata);
		if (productCode != '') {
			$.ajax({
				url: base_path + "barcode/getProductDetails",
				type: 'POST',
				data: {
					'productCode': productCode,
					'baseUnit': baseUnit,
					'taxGroup': taxGroup,
				},
				success: function(response) {
					var obj = JSON.parse(response);
					$("#sellingUnit").html('').trigger('change');
					$("#sellingUnit").html(obj.unitHtml).trigger('change');
					$("#taxPercent").val(obj.taxPer);
					calculate_subTotal();
				}
			})
		}
	}
</script>