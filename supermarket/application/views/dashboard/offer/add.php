<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<div id="main-content">
	<div class="page-heading">
		<div class="page-title">
			<div class="row">
				<div class="col-12 col-md-6 order-md-1 order-last">
					<h3>Offer</h3>
				</div>
				<div class="col-12 col-md-6 order-md-2 order-first">
					<nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
							<li class="breadcrumb-item active" aria-current="page">Offer</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
		<section id="multiple-column-form" class="mt-5">
			<div class="row match-height">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h3>Add Offer<span style="float:right"><a id="cancelDefaultButton" href="<?= base_url() ?>offer/listRecords" class="btn btn-sm btn-primary">Back</a></span></h3>
						</div>
						<div class="card-content">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-12">
										<form id="offerForm" method="post" enctype="multipart/form-data" data-parsley-validate="">
											<div class="row">
												<div class="col-md-12 col-12">
													<div class="row">
														<div class="col-md-6 col-12">
															<div class="form-group mandatory">
																<label for="" class="form-label">Offer Title</label>
																<input type="text" id="title" name="title" class="form-control" required>
															</div>
														</div>
														<div class="col-md-6 col-12">
															<div class="form-group mandatory">
																<label for="product-name" class="form-label">Offer type</label>
																<select id="offerType" name="offerType" class="form-control" required>
																	<option value="">Select type</option>
																	<option value="flat">Flat</option>
																	<option value="cap">Cap</option>
																</select>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-4 col-12">
															<div class="form-group mandatory">
																<label for="" class="form-label">Minimum Amount</label>
																<input type="number" id="minimumAmount" name="minimumAmount" class="form-control" required>
															</div>
														</div>
														<div class="col-md-4 col-12 d-none" id="discountDiv">
															<div class="form-group mandatory">
																<label for="" class="form-label"> Discount (%)</label>
																<input type="number" step="0.01" id="discount" name="discount" class="form-control" required>
															</div>
														</div>
														<div class="col-md-4 col-12 d-none" id="capDiv">
															<div class="form-group mandatory">
																<label for="" class="form-label">Cap limit</label>
																<input type="number" id="capLimit" name="capLimit" class="form-control">
															</div>
														</div>
														<div class="col-md-4 col-12 d-none" id="flatAmountDiv">
															<div class="form-group mandatory">
																<label for="" class="form-label">Flat Amount</label>
																<input type="number" id="flatAmount" name="flatAmount" class="form-control">
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-12">
															<div class="form-group mandatory">
																<label for="description" class="form-label mb-1">Offer Description : </label>
																<textarea class="form-control" id="description" name="description" placeholder="Offer Description"></textarea>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-6 col-sm-6">
															<div class="form-group mandatory">
																<label for="" class="form-label">Start Date</label>
																<input type="text" id="startDate" name="startDate" class="form-control datepicker" placeholder="Ex. dd-MM-yyyy hh:mm" required>
															</div>
														</div>
														<div class="col-md-6 col-sm-6">
															<div class="form-group mandatory">
																<label for="" class="form-label">End Date</label>
																<input type="text" id="endDate" name="endDate" class="form-control datepicker" placeholder="Ex. dd-MM-yyyy hh:mm" required>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-2 col-12">
															<div class="form-group">
																<label class="form-label lng">Active</label>
																<div class="input-group">
																	<div class="input-group-prepend">
																		<span class="input-group-text bg-soft-primary">
																			<input type="checkbox" checked id="isActive" name="isActive" class="form-check-input">
																		</span>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-12 d-flex justify-content-end">
															<button type="submit" class="btn btn-success" id="saveOfferBtn">Save</button>
															<button type="reset" id="closeOfferBtn" class="btn btn-light-secondary">Reset</button>
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
				</div>
			</div>
		</section>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {

		$('input[type=number]').on('mousewheel', function(e) {
			$(this).blur();
		});

		$('input[type=number]').on('keydown', function(e) {
			var key = e.charCode || e.keyCode;
			if (key == 38 || key == 40) {
				e.preventDefault();
			} else {
				return;
			}
		});

		$("#description").summernote({
			placeholder: 'Offer Description...',
			height: 200
		});

		$("body").on("change", "#offerType", function(e) {
			var typeValue = $(this).val().trim();
			if (typeValue == "flat") {
				$('#discountDiv').addClass('d-none');
				$("#discount").prop('required', false);
				$('#capDiv').addClass('d-none');
				$("#capLimit").prop('required', false);
				$('#flatAmountDiv').removeClass('d-none');
				$("#flatAmount").prop('required', true);
			} else if (typeValue == "cap") {
				$('#discountDiv').removeClass('d-none');
				$("#discount").prop('required', true);
				$('#capDiv').removeClass('d-none');
				$("#capLimit").prop('required', true);
				$('#flatAmountDiv').addClass('d-none');
				$("#flatAmount").prop('required', false);
			} else {
				$('#discountDiv').addClass('d-none');
				$("#discount").prop('required', false);
				$('#capDiv').addClass('d-none');
				$("#capLimit").prop('required', false);
				$('#flatAmountDiv').addClass('d-none');
				$("#flatAmount").prop('required', false);
			}
		});

		$("body").on("change", "#endDate", function(e) {
			var endDate = $(this).val();
			var startDate = $('#startDate').val();
			if (startDate > endDate) {
				$("#endDate").val('');
				toastr.error("The End Date should be greater than the Start date.", "Offer", {
					"progressBar": true,
					"preventDuplicates": true,
				});
				return false
			}
		});

		$("body").on("change", "#startDate", function(e) {
			var endDate = $('#endDate').val();
			if (endDate != "") {
				var startDate = $(this).val();
				if (startDate > endDate) {
					$("#startDate").val('');
					toastr.error("The End Date should be greater than the Start date.", "Offer", {
						"progressBar": true,
						"preventDuplicates": true,
					});
					return false
				}
			}
		});
	});

	$("#offerForm").submit(function(e) {
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
			$.ajax({
				url: base_path + "offer/save",
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$('#saveOfferBtn').prop('disabled', true);
					$('#saveOfferBtn').text('Please wait..');
					$('#closeOfferBtn').prop('disabled', true);
				},
				success: function(response) {
					$('#saveOfferBtn').prop('disabled', false);
					$('#saveOfferBtn').text('Save');
					$('#closeOfferBtn').prop('disabled', false);
					var obj = JSON.parse(response);
					if (obj.status) {
						toastr.success(obj.message, 'Offer', {
							"progressBar": true,
							onHidden: function() {
								window.location.href = base_path + "offer/listRecords";
							}
						});
					} else {
						toastr.error(obj.message, 'Offer', {
							"progressBar": true
						});
					}
				}
			});
		}
	});

	function validateFlatAmount() {
		var minimumAmount = $('#minimumAmount').val();
		var offerType = $('#offerType').val();
		var flatAmount = $('#flatAmount').val();
		if (offerType == 'flat') {
			if (minimumAmount != '' && flatAmount != '') {
				if (Number(flatAmount) > Number(minimumAmount)) {
					toastr.error("Flat amount should be less than or equal to minimum amount", 'Vendor Offer', {
						"progressBar": true
					});
					$('#flatAmount').val('');
					$('#flatAmount').focus();
					return true;
				}
			}
		}
	}
</script>