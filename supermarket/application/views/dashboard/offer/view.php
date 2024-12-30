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
							<li class="breadcrumb-item"><a href="../../dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
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
							<h3>View Offer<span style="float:right"><a id="cancelDefaultButton" href="<?= base_url() ?>offer/listRecords" class="btn btn-sm btn-primary">Back</a></span></h3>
						</div>
						<div class="card-content">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-12">
										<form id="offerForm" method="post" enctype="multipart/form-data" data-parsley-validate="">
											<?php if ($offerData) {
												$result = $offerData->result()[0];
											?>
												<div class="row">
													<div class="col-md-12 col-12">
														<div class="row">
															<input type="hidden" class="form-control" id="code" name="code" value="<?= $result->code ?>">
															<div class="col-md-6 col-12">
																<div class="form-group mandatory">
																	<label for="" class="form-label">Offer Title</label>
																	<input type="text" id="title" name="title" disabled class="form-control" required value="<?= $result->title ?>">
																</div>
															</div>
															<div class="col-md-6 col-12">
																<div class="form-group mandatory">
																	<label for="product-name" class="form-label">Offer type</label>
																	<select id="offerType" name="offerType" disabled class="form-control" required>
																		<option value="">Select type</option>
																		<option value="flat">Flat</option>
																		<option value="cap">Cap</option>
																	</select>
																</div>
																<script>
																	var offerType = '<?= $result->offerType ?>';
																	$('#offerType').val(offerType);
																</script>
															</div>

														</div>
														<div class="row">
															<div class="col-md-4 col-12">
																<div class="form-group mandatory">
																	<label for="" class="form-label">Minimum Amount</label>
																	<input type="number" id="minimumAmount" disabled name="minimumAmount" class="form-control" required value="<?= $result->minimumAmount ?>">
																</div>
															</div>
															<div class="col-md-4 col-12 d-none" id="discountDiv">
																<div class="form-group mandatory">
																	<label for="" class="form-label"> Discount (%)</label>
																	<input type="number" id="discount" name="discount" disabled class="form-control" required value="<?= $result->discount ?>">
																</div>
															</div>
															<div class="col-md-4 col-12 d-none" id="capDiv">
																<div class="form-group mandatory">
																	<label for="" class="form-label">Cap limit</label>
																	<input type="number" id="capLimit" name="capLimit" disabled class="form-control" value="<?= $result->capLimit ?>">
																</div>
															</div>
															<div class="col-md-4 col-12 d-none" id="flatAmountDiv">
																<div class="form-group mandatory">
																	<label for="" class="form-label">Flat Amount</label>
																	<input type="number" id="flatAmount" disabled name="flatAmount" class="form-control" value="<?= $result->flatAmount ?>">
																</div>
															</div>

														</div>

														<div class="row">
															<div class="col-md-12 col-12">
																<div class="form-group mandatory">
																	<label for="description" class="form-label mb-1">Offer Description : </label>
																	<textarea class="form-control" id="description" disabled name="description" placeholder="Description"><?= $result->description ?></textarea>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-md-6 col-12">
																<div class="form-group mandatory">
																	<label for="" class="form-label">Start Date</label>
																	<input type="date" id="startDate" name="startDate" disabled class="form-control" required value="<?= $result->startDate ?>">
																</div>
															</div>
															<div class="col-md-6 col-12">
																<div class="form-group mandatory">
																	<label for="" class="form-label">End Date</label>
																	<input type="date" id="endDate" name="endDate" disabled class="form-control" required value="<?= $result->endDate ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-md-2 col-12">
																<div class="form-group">
																	<label class="form-label lng">Active</label>
																	<div class="input-group">
																		<div class="input-group-prepend"><span class="input-group-text bg-soft-primary">
																				<input type="checkbox" checked disabled id="isActive" name="isActive" <?php if ($result->isActive == 1) {
																																							echo 'checked';
																																						} ?> class="form-check-input"></span>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											<?php } ?>
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
		var typeget = $('#offerType').val().trim();
		if (typeget == "flat") {
			$('#discountDiv').addClass('d-none');
			$("#discount").prop('required', false);
			$('#capDiv').addClass('d-none');
			$("#capLimit").prop('required', false);
			$('#flatAmountDiv').removeClass('d-none');
			$("#flatAmount").prop('required', true);
		} else if (typeget == "cap") {
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



		$("#description").summernote({
			placeholder: 'Offer Description...',
			height: 200
		});
	});
</script>