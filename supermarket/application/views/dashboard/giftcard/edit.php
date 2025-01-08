<?php include '../supermarket/config.php'; ?>

<div id="main-content">
	<div class="page-heading">
		<div class="page-title">
			<div class="row">
				<div class="col-12 col-md-6 order-md-1 order-last">
					<h3><?php echo $translations['Giftcard']?></h3>
				</div>
				<div class="col-12 col-md-6 order-md-2 order-first">
					<nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="../../dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
							<li class="breadcrumb-item active" aria-current="page">Giftcard</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
		<section id="multiple-column-form" class="mt-3">
			<div class="row match-height">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h3><?php echo $translations['Update Giftcard']?><span style="float:right"><a id="cancelDefaultButton" href="<?= base_url() ?>giftCard/listRecords" class="btn btn-sm btn-primary"><?php echo $translations['Back']?></a></span></h3>
						</div>
						<div class="card-content">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-12">
										<form id="cardForm" method="post" enctype="multipart/form-data" data-parsley-validate="">
											<?php if ($cardData) {
												$result = $cardData->result()[0];
											?>
												<input type="hidden" class="form-control" id="code" name="code" value="<?= $result->code ?>">
												<div class="row">
													<div class="col-md-12 col-12">
														<div class="row">
															<div class="col-md-4 col-12">
																<div class="form-group mandatory">
																	<label for="title" class="form-label"><?php echo $translations['Title']?></label>
																	<input type="text" id="title" minlength="2" maxlength="100" name="title" class="form-control" required value="<?= $result->title ?>">
																</div>
															</div>
															<div class="col-md-4 col-12 d-none">
																<div class="form-group mandatory">
																	<label for="product-name" class="form-label"><?php echo $translations['Type']?></label>
																	<select id="cardType" name="cardType" readonly class="form-select" required>
																		<option value="per" selected>Per</option>
																	</select>
																</div>
															</div>
															<div class="col-md-4 col-12">
																<div class="form-group mandatory">
																	<label for="discount" class="form-label"><?php echo $translations['Discount (%)']?></label>
																	<input type="number" id="discount" name="discount" min="1" max="99" class="form-control" required value="<?= $result->discount ?>" onkeypress="return isNumber(event)">
																</div>
															</div>
															<div class="col-md-4 col-12">
																<div class="form-group mandatory">
																	<label for="price" class="form-label"><?php echo $translations['Price']?></label>
																	<input type="number" id="price" name="price" class="form-control" required value="<?= $result->price ?>" onkeypress="return isNumber(event)">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-md-12 col-12">
																<div class="form-group mandatory">
																	<label for="" class="form-label"><?php echo $translations['Validity (In days)']?></label>
																	<input type="number" step="1" id="validityInDays" min="1" max="365" name="validityInDays" class="form-control" onkeypress="return isNumber(event)" required value="<?= $result->validityInDays ?>">
																</div>
															</div>


														</div>
														<div class="row">
															<div class="col-md-12 col-12">
																<div class="form-group mandatory">
																	<label for="description" class="form-label mb-1"><?php echo $translations['Description']?></label>
																	<textarea class="form-control" id="description" name="description" placeholder="Description"><?= $result->description ?></textarea>
																</div>
															</div>
														</div>

														<div class="col-md-4 col-12">
															<div class="form-group">
																<div class="form-check">
																	<input class="form-check-input" type="checkbox" value="1" id="isActive" name="isActive" <?= ($result->isActive == 1) ? "checked" : "" ?>>
																	<label class="form-check-label" for="isActive">
																		<?php echo $translations['Active']?>
																	</label>
																</div>
															</div>
														</div>


														<div class="row">
															<div class="col-12 d-flex justify-content-end">
																<button type="submit" class="btn btn-success" id="saveCardBtn"><?php echo $translations['Update']?></button>
																<button type="reset" id="closeCardBtn" class="btn btn-light-secondary"><?php echo $translations['Reset']?></button>
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
		$("#description").summernote({
			placeholder: 'Description...',
			height: 150
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
	$("#cardForm").submit(function(e) {
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
			if ($('#validityInDays').val() > 0) {
				$.ajax({
					url: base_path + "giftCard/update",
					type: 'POST',
					data: formData,
					cache: false,
					contentType: false,
					processData: false,
					beforeSend: function() {
						$('#saveCardBtn').prop('disabled', true);
						$('#saveCardBtn').text('Please wait..');
						$('#closeCardBtn').prop('disabled', true);
					},
					success: function(response) {
						$('#saveCardBtn').prop('disabled', false);
						$('#saveCardBtn').text('Update');
						$('#closeCardBtn').prop('disabled', false);
						var obj = JSON.parse(response);
						if (obj.status) {
							toastr.success(obj.message, 'Gift Card', {
								"progressBar": true,
								onHidden: function() {
									window.location.href = base_path + "giftCard/listRecords";
								}
							});
						} else {
							toastr.error(obj.message, 'Gift Card', {
								"progressBar": true
							});
						}
					}
				});
			}
		}
	});
</script>