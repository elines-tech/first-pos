<style>
	.select2-container--default .select2-selection--multiple .select2-selection__choice {
		background-color: #e7e9ee !important;
	}
</style>
<div id="main-content">
	<div class="page-heading">
		<div class="page-title">
			<div class="row">
				<div class="col-12 col-md-6 order-md-1 order-last">
					<h3>Sms Alert</h3>
				</div>
				<div class="col-12 col-md-6 order-md-2 order-first">
					<nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="../dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
							<li class="breadcrumb-item active" aria-current="page">Sms Alert</li>
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
							<h3>Send Sms</h3>
						</div>
						<div class="card-content">
							<div class="card-body">
								<form class="form" action="<?= base_url('Sendsms/send') ?>" method="post" enctype="multipart/form-data" data-parsley-validate>
									<?php
									echo "<div class='text-danger text-center' id='error_message'>";
									if (isset($error_message)) {
										echo $error_message;
									}
									echo "</div>";
									?>

									<div class="col-md-12 col-12">
										<div class="form-group mandatory">
											<label class="form-label">Mobile Number</label>
											<select class="form-select select2" name="mobilenumber[]" multiple="multiple" id="mobilenumber">

											</select>
										</div>

									</div>
									<div class="col-md-12 col-12">
										<div class="form-group mandatory">
											<label class="form-label">Template</label>
											<select class="form-select select2" name="template" id="template">

											</select>
										</div>

									</div>
									<div class="col-md-12 col-12">
										<div class="form-group mandatory">
											<label class="form-label">Message</label>
											<textarea class="form-control" id="message" name="message" data-parsley-required="true"></textarea>
										</div>
										<?php echo form_error('message', '<span class="error text-danger text-right">', '</span>'); ?>
									</div>
									<div class="row">
										<div class="col-12 d-flex justify-content-end">
											<button id="saveDefaultButton" type="submit" class="btn btn-success">Send</button>
											<button id="cancelDefaultButton" type="reset" class="btn btn-light-secondary">Reset</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- // Basic multiple Column Form section end -->
	</div>
</div>
<script>
	$(document).ready(function() {
		var data = '<?php echo $this->session->flashdata('message');
					unset($_SESSION['message']); ?>';
		if (data != '') {
			var obj = JSON.parse(data);
			if (obj.status == false) {
				toastr.success(obj.message, 'Sms', {
					"progressBar": true
				});
			}
			/*else {
			               toastr.error(obj.message, 'Sms', {
			                   "progressBar": true
			               });
			           }*/
		}
		$("#mobilenumber").select2({
			placeholder: "Select Mobile",
			allowClear: true,
			ajax: {
				url: base_path + 'Common/getCustomer',
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

		$("#template").select2({
			placeholder: "Select Template",
			allowClear: true,
			ajax: {
				url: base_path + 'Common/getTemplate',
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
			var template = $(e.currentTarget).val();
			$("#message").val(template);
		});

	});
</script>