<style>
	.barcodeDiv {
		border: 1px solid black;
	}

	.parsley-pattern {
		color: red;
	}

	.parsley-error {
		color: red;
	}
</style>
<div id="main-content">
	<div class="page-heading">
		<div class="page-title">
			<div class="row">
				<div class="col-12 col-md-6 order-md-1 order-last">
					<h3> Giftcard</h3>
				</div>
				<div class="col-12 col-md-6 order-md-2 order-first">
					<nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><i class="fa fa-dashboard"></i> Dashboard</li>
							<li class="breadcrumb-item active" aria-current="page">Giftcard</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>

		<section class="section">
			<div class="row match-height">
				<form id="giftForm" class="form" data-parsley-validate>
					<div class="col-12">
						<div class="card">
							<div class="card-header">
								<h3>Giftcard Details<span class="float-end"><a class="btn btn-sm btn-primary" href="<?= base_url() ?>Cashier/giftCard/listRecords">Back</a></span></h3>
							</div>
							<div class="card-content">
								<div class="card-body">

									<?php
									$expiryDate = '';
									if ($cardData) {
										$result = $cardData->result()[0];
										if ($result->validityInDays != '') {
											$expiryDate = date('Y-m-d', strtotime(" + " . $result->validityInDays . " days"));
										}
									?>
										<div class="row">
											<div class="col-md-3 col-12 mb-2">
												<label for="batchNo"><b>Card Title : </b></label>
												<input type="hidden" id="cardCode" name="cardCode" class="form-control-line" value="<?= $result->code ?>">
												<input type="text" id="cardTitle" name="cardTitle" class="form-control-line" value="<?= $result->title ?>">
											</div>
											<div class="col-md-3 col-12 mb-2">
												<label for="discount"><b>Discount (%) : </b></label>
												<input type="text" id="discount" name="discount" class="form-control-line" value="<?= $result->discount ?>">
											</div>
											<div class="col-md-3 col-12 mb-2">
												<label for="price"><b>Price: </b></label>
												<input type="text" id="price" name="price" class="form-control-line" value="<?= $result->price ?>">
											</div>
											<div class="col-md-3 col-12 mb-2">
												<label for="validityInDays"><b>Validity (In Days) : </b></label>
												<input type="text" id="validityInDays" name="validityInDays" class="form-control-line" value="<?= $result->validityInDays ?>">
											</div>
											<div class="col-md-9 col-12 mb-2">
												<label for="description"><b>Description: </b></label>
												<p><?= $result->description ?></p>
											</div>

										</div>
									<?php }
									?>
									<hr>
									<div class="row mt-2">
										<h5>Giftcard Purchase Details</h5>
										<div class="row">
											<div class="col-md-4 col-12">
												<label class="form-label"><b>Customer Name: </b><b style="color:red">*</b></label>
												<input type="text" id="mcustName" class="form-control" name="custName" required>
											</div>
											<div class="col-md-4 col-12">
												<label class="form-label"><b>Email: </b><b style="color:red">*</b></label>
												<input type="text" id="mcustEmail" class="form-control" name="custEmail" required>
											</div>
											<div class="col-md-4 col-12">
												<label class="form-label"><b>Phone: </b><b style="color:red">*</b></label>
												<div class="input-group mb-3">
													<div class="input-group-prepend">
														<select class="form-select" id="mcountryCode" onchange="setPattern()" name="mcountryCode" required>
															<option value="+966" data-pattern="^\d{9}$" selected>+966 SAU</option>
															<option value="+971" data-pattern="^\d{9}$">+971 UAE</option>
														</select>
													</div>
													<input type="text" class="form-control" id="mcustPhone" name="custPhone" onkeypress="return isNumber(event)" data-parsley-required="true" data-parsley-pattern-message="Invalid Phone Number" />
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-3 col-12">
												<label class="form-label"><b>Giftcard Expiry Date: </b><b style="color:red">*</b></label>
												<input type="date" id="mexpiryDate" disabled class="form-control" name="expiryDate" required value="<?php if ($expiryDate != '') {
																																						echo $expiryDate;
																																					} ?>">
											</div>
											<div class="col-md-2 col-12">
												<label class="form-label"><b>No Of Cards: </b><b style="color:red">*</b></label>
												<input type="text" id="mcardCount" class="form-control" onkeyup="calculatePrice()" name="cardCount" required onkeypress="return isNumber(event)">
											</div>
											<div class="col-md-2 col-12">
												<label class="form-label"><b>Total Price: </b><b style="color:red">*</b></label>
												<input type="text" id="mtotalPrice" disabled class="form-control" name="totalPrice" required onkeypress="return isNumber(event)">
											</div>
											<div class="col-md-2 col-12" style="margin-top:32px;">
												<button type="button" onclick="generate()" class="btn btn-primary white sub_1" id="generateBtn">Generate</button>
												<button type="button" onclick="clear()" class="d-none btn btn-primary white sub_1" id="clearBtn">Clear</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card d-none" id="previewDiv">
							<div class="card-header">
								<h5>Giftcard Users</h5>
							</div>
							<div class="card-content">
								<div class="card-body m-0">
									<div class="row m-0" id="println">
									</div>
									<div class="row">
										<div class="col-12 d-flex justify-content-end">
											<button type="submit" class="btn btn-primary white me-2 mb-1 sub_1" id="saveCardBtn">Save</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</section>
	</div>
</div>
</body>
<script>
	function setPattern() {
		var countryCode = $('#mcountryCode').val();
		if (countryCode != '') {
			var pattern = $('#mcountryCode').find(':selected').data('pattern');
			//$('#mcustPhone').attr('data-parsley-pattern',pattern);
		}
	}

	function validatePhoneNumber(id) {
		var countryCode = $('#countryCode' + id).val();
		if (countryCode != '') {
			var pattern = $('#countryCode' + id).find(':selected').data('pattern');
			//$('#custPhone' + id).attr('data-parsley-pattern', pattern);
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

	function calculatePrice() {
		var price = Number($('#price').val())
		var cardCount = Number($('#mcardCount').val())
		totalPrice = price * cardCount;
		$('#mtotalPrice').val(totalPrice);
	}

	function hasEmptyElement(array) {
		for (var i = 0; i < array.length; i++) {
			if (array[i] === "")
				return false;
		}
		return true;
	}

	function clear() {
		swal({
			title: "Do you want to remove all the giftcard users?",
			type: "warning",
			showCancelButton: !0,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes",
			cancelButtonText: "No",
			closeOnConfirm: !1,
			closeOnCancel: !1
		}, function(e) {
			if (e) {
				$('#generateBtn').removeClass('d-none');
				$('#generateBtn').prop('disabled', false);
				$('#generateBtn').text('Generate');
				$('#println').html('');
				$('#previewDiv').addClass('d-none');
			}
		});
	}

	function generate() {

		$('#generateBtn').prop('disabled', true);
		$('#generateBtn').text('Please wait..');
		var cardCount = $('#mcardCount').val();
		if ($('#mcustName').val() != '' && $('#mcustEmail').val() != '' && $('#mcustPhone').val() != '' && $('#mcardCount').val() != '') {
			$('#println').html('');
			$('#previewDiv').addClass('d-none');
			if (cardCount > 0 && cardCount != '') {
				var lineHtml = '<table class="table table-sm table-bordered">' +
					'<thead><tr><th>Giftcard No</th><th>Name</th><th>Email</th><th>Phone</th></tr></thead><tbody>';
				for (i = 1; i <= cardCount; i++) {
					lineHtml += '<tr id="row' + i + '">' +
						'<td>' + i + '</td>' +
						'<td><input type="text" id="custName' + i + '" class="form-control" name="custName[]" required></td>' +
						'<td><input type="text" id="custEmail' + i + '" class="form-control" name="custEmail[]" required></td>' +
						'<td><div class="input-group mb-3">' +
						'<div class="input-group-prepend">' +
						'<select class="form-select" onchange="validatePhoneNumber(' + i + ')" id="countryCode' + i + '" onchange="setPattern()" name="countryCode[]" required>' +
						'<option value="+966" selected>+966 SAU</option>' +
						'<option value="+971">+971 UAE</option>' +
						'</select>' +
						'</div>' +
						'<input type="text" class="form-control custPhone" id="custPhone' + i + '" name="custPhone[]" onkeypress="return isNumber(event)" data-parsley-required="true" data-parsley-pattern-message="Invalid Phone Number"/>' +
						'</div></td>' +
						'</tr>';

				}
				lineHtml += '</tbody></table>';
				$('#previewDiv').removeClass('d-none');
				$('#println').html(lineHtml);
				$('#clearBtn').removeClass('d-none');
				$('#generateBtn').addClass('d-none');
				$('#generateBtn').text('Generate');
			} else {
				$('#generateBtn').removeClass('d-none');
				$('#generateBtn').prop('disabled', false);
				$('#generateBtn').text('Generate');
				$('#println').html('');
				$('#previewDiv').addClass('d-none');
				toastr.error('Please provide valid card count', 'Giftcard Users', {
					"progressBar": false
				});
				$('#mcardCount').val('');
				$('#mcardCount').focus();
				return false;
			}
		} else {
			$('#generateBtn').prop('disabled', false);
			$('#generateBtn').text('Generate');
			toastr.error('* fields are required', 'Giftcard Users', {
				"progressBar": false
			});
			return false;
		}
	}

	function randomString(length) {
		var result = '';
		var chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		for (var i = length; i > 0; --i) result += chars[Math.floor(Math.random() * chars.length)];
		return result;
	}

	$("#giftForm").submit(function(e) {
		e.preventDefault();
		var formData = new FormData(this);
		var form = $(this);
		form.parsley().validate();
		if (form.parsley().isValid()) {
			var giftCode = $('#cardCode').val();
			var mcustName = $('#mcustName').val();
			var mcountryCode = $('#mcountryCode').val();
			var mcustPhone = $('#mcustPhone').val();
			var mcardCount = $('#mcardCount').val();
			var mtotalPrice = $('#mtotalPrice').val();
			var mcustEmail = $('#mcustEmail').val();
			var mexpiryDate = $('#mexpiryDate').val();
			var countryCode = $("input[name='countryCode']").map(function() {
				return this.value;
			});
			var custName = $('input[name="custName[]"]').map(function() {
				return this.value;
			}).get();
			var custEmail = $('input[name="custEmail[]"]').map(function() {
				return this.value;
			}).get();
			var custPhone = $('input[name="custPhone[]"]').map(function() {
				return this.value;
			}).get();
			if (hasEmptyElement(custName) && hasEmptyElement(custEmail) && hasEmptyElement(cardNo) && hasEmptyElement(custPhone)) {
				formData.append('giftCode', giftCode);
				formData.append('mcustName', mcustName);
				formData.append('mcountryCode', mcountryCode);
				formData.append('mcustPhone', mcustPhone);
				formData.append('mcardCount', mcardCount);
				formData.append('mtotalPrice', mtotalPrice);
				formData.append('mcustEmail', mcustEmail);
				formData.append('mexpiryDate', mexpiryDate);
				formData.append('custName', custName);
				formData.append('custEmail', custEmail);
				formData.append('custPhone', custPhone);
				formData.append('countryCode', countryCode);

				$.ajax({
					url: base_path + "Cashier/giftCard/saveCardDetails",
					type: 'POST',
					data: formData,
					cache: false,
					contentType: false,
					processData: false,
					dataType: "JSON",
					beforeSend: function() {
						$('#saveCardBtn').prop('disabled', true);
						$('#saveCardBtn').text('Please wait..');
					},
					success: function(response) {
						if (response.status) {
							toastr.success(response.message, 'Gift Card sale', {
								"progressBar": true
							});
							window.location.replace(base_path + "Cashier/giftCard/getHistoryList");
						} else {
							toastr.error(response.message, 'Gift Card sale', {
								"progressBar": true
							});
						}
					},
					complete: function() {
						$('#saveCardBtn').text('Save');
						$('#saveCardBtn').prop('disabled', false);
					}
				});
			} else {
				toastr.error('All the fields are required', 'Gift Card sale', {
					"progressBar": true
				});
				return false
			}
		}
	});
	$(document).ready(function() {
		setPattern();
	});
</script>