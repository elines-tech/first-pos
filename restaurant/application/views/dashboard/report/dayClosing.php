<style>
	.dataTables_filter {
		float: right;
	}

	.dataTables_length {
		float: left;
	}

	.dt_buttons {
		float: right;
	}
</style>
<div id="main-content">
	<div class="page-heading">
		<div class="page-title">
			<div class="row">
				<div class="col-12 col-md-6 order-md-1 order-last">
					<h3>Day Closing Report</h3>
				</div>
				<div class="col-12 col-md-6 order-md-2 order-first">
					<nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="../Dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
							<li class="breadcrumb-item active" aria-current="page">Day Closing Report</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>

		<section class="section">
			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
							<h5>Filter</h5>
						</div>
					</div>
					<hr>
					<div class="row mt-3">
						<div class="col-md-3">
							<label class="form-label lng">From Date</label>
							<div class="form-group mandatory">
								<input type="date" class="form-control" id="fromDate" name="fromDate" value="<?= date('Y-m-d') ?>">
							</div>
						</div>
						<div class="col-md-3">
							<label class="form-label lng">To Date</label>
							<div class="form-group mandatory">
								<input type="date" class="form-control" id="toDate" name="toDate" value="<?= date('Y-m-d') ?>">
							</div>
						</div>
						<div class="col-md-3">
							<label class="form-label lng">Branch</label>
							<div class="form-group mandatory">
								<?php if ($branchCode != "") { ?>
									<input type="hidden" class="form-control" name="branch" id="bCode" value="<?= $branchCode; ?>" readonly>
									<input type="text" class="form-control" name="fromBranch" value="<?= $branchName; ?>" readonly>

								<?php } else { ?>
									<select class="form-select select2" name="branch" id="branch">
										<option value="">Select </option>
										<?php if ($branch) {
											foreach ($branch->result() as $br) {
												echo '<option value="' . $br->code . '">' . $br->branchName . '</option>';
											}
										} ?>
									</select>
								<?php } ?>
							</div>
						</div>
						<div class="col-md-3">
							<label class="form-label lng">Cashier</label>
							<div class="form-group mandatory">
								<select class="form-select select2" name="cashierCode" id="cashierCode">
									<option value="">Select </option>
									<?php if ($usermaster) {
										foreach ($usermaster->result() as $um) {
											echo '<option value="' . $um->code . '">' . $um->userEmpNo . '</option>';
										}
									} ?>
								</select>
							</div>
						</div>
						<div style="text-align:center;">
							<div class="d-flex justify-content-center mt-2">
								<button type="button" class="btn btn-success" id="btnSearch">Search</button>
								<button type="reset" class="btn btn-light-secondary" id="btnClear">Clear</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-body" id="print_div">
					<table class="table table-striped" id="datatableDayClosing">
						<thead>
							<tr>
								<th>Sr No</th>
								<th>Branch</th>
								<th>Cashier</th>
								<th>Total Orders</th>
								<th>Total Sale</th>
								<th>Cash Payments</th>
								<th>Card Payments</th>
								<th>UPI Payments</th>
								<th>Netbanking Payments</th>
								<th>Offer Applied</th>
								<th>Offer Total</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</section>
	</div>
</div>
</div>
</div>
</body>
<table id="dayClosing" class="table table-striped table-bordered d-none">
	<thead>
		<tr>
			<th>Sr No</th>
			<th>Branch</th>
			<th>Cashier</th>
			<th>Total Orders</th>
			<th>Total Sale</th>
			<th>Cash Payments</th>
			<th>Card Payments</th>
			<th>UPI Payments</th>
			<th>Netbanking Payments</th>
			<th>Offer Applied</th>
			<th>Offer Total</th>
		</tr>
	</thead>
</table>
<script>
	$(document).ready(function() {
		loadTable();
		$(".buttons-html5").removeClass('btn-primary').addClass('btn-printFormat');
		$(".dt_buttons").removeClass('flex_wrap');
		$('#btnSearch').on('click', function(e) {
			if ($("#branch").val() != "") {
				var branchCode = $("#branch").val();
			} else {
				var branchCode = $("#bCode").val();
			}
			var cashierCode = $("#cashierCode").val();
			var fromDate = $("#fromDate").val();
			var toDate = $("#toDate").val();
			loadTable(branchCode, cashierCode, fromDate, toDate);
		});
		$('#btnClear').on('click', function(e) {
			$("#branch").val('').trigger('change')
			$("#cashierCode").val('').trigger('change')
			$("#fromDate").val('<?= date('Y-m-d') ?>')
			$("#toDate").val('<?= date('Y-m-d') ?>')
			loadTable("", "", "<?= date('Y-m-d') ?>", "<?= date('Y-m-d') ?>");
			$(".buttons-html5").removeClass('btn-primary').addClass('btn-primary sub_1');
		});
	});


	function loadTable(branchCode, cashierCode, fromDate, toDate) {
		if ($.fn.DataTable.isDataTable("#datatableDayClosing")) {
			$('#datatableDayClosing').DataTable().clear().destroy();
		}
		jQuery.fn.DataTable.Api.register('buttons.exportData()', function(options) {
			if (this.context.length) {
				var jsonResult = $.ajax({
					url: base_path + "report/getDayClosingList",
					data: {
						'export': 1,
						'branchCode': branchCode,
						'cashierCode': cashierCode,
						'fromDate': fromDate,
						'toDate': toDate,
					},
					type: "GET",
					success: function(result) {},
					async: false
				});
				var jencode = JSON.parse(jsonResult.responseText);
				return {
					body: jencode.data,
					header: $("#dayClosing thead tr th").map(function() {
						return this.innerHTML;
					}).get()
				};
			}
		});
		var dataTable = $('#datatableDayClosing').DataTable({
			dom: 'B<"flex-wrap mt-2"fl>trip',
			buttons: [{
					extend: 'excel',
					title: 'Day Closing Report'
				},
				{
					extend: 'pdf',
					title: 'Day Closing Report'
				}
			],
			"stateSave": false,
			"processing": true,
			"serverSide": true,
			"order": [],
			"searching": true,
			"ajax": {
				url: base_path + "report/getDayClosingList",
				type: "GET",
				data: {
					'branchCode': branchCode,
					'cashierCode': cashierCode,
					'fromDate': fromDate,
					'toDate': toDate,
					'export': 0
				},
				"complete": function(response) {}
			}
		});
	}
</script>