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
					<h3>Purchase Report</h3>
				</div>
				<div class="col-12 col-md-6 order-md-2 order-first">
					<nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="../Dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
							<li class="breadcrumb-item active" aria-current="page">Purchase Report</li>
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
						<div class="col-md-6">
							<label class="form-label lng">Branch</label>
							<div class="form-group mandatory">
								<?php if ($branchCode != "") { ?>
									<input type="hidden" class="form-control" name="branch" id="bCode" value="<?= $branchCode; ?>" readonly>
									<input type="text" class="form-control" name="fromBranch" value="<?= $branchName; ?>" readonly>

								<?php } else { ?>
									<select class="form-select select2" name="branch" id="branch">

									</select>
								<?php } ?>
							</div>
						</div>
						<div class="col-md-6">
							<label class="form-label lng">To Branch</label>
							<div class="form-group mandatory">
								<select class="form-select select2" name="tobranch" id="tobranch">
									<option value="">Select Branch</option>
									<?php if ($branch) {
										echo "<optgroup label='Branch'>";
										foreach ($branch->result() as $br) {

											echo '<option value="' . $br->code . '">' . $br->branchName . '</option>';
										}
										echo "</optgroup>";
									} ?>
									<?php if ($supplier) {
										echo "<optgroup label='supplier'>";
										foreach ($supplier->result() as $sr) {

											echo '<option value="' . $sr->code . '">' . $sr->supplierName . '</option>';
										}
										echo "</optgroup>";
									} ?>
								</select>
							</div>
						</div>

					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="d-flex mt-4 justify-content-center">
								<button type="button" class="btn btn-success" id="btnSearch">Search</button>
								<button type="reset" class="btn btn-light-secondary" id="btnClear">Clear</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-body" id="print_div">
					<table class="table table-striped" id="datatableTable">
						<thead>
							<tr>
								<th>Sr No</th>
								<th>Code</th>
								<th>Inward Date</th>
								<th>Branch</th>
								<th>Supplier</th>
								<th>Total</th>
								<th>Action</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</section>
	</div>
</div>

<table id="purchaseReport" class="table table-striped table-bordered d-none">
	<thead>
		<tr>
			<th>Sr No</th>
			<th>Code</th>
			<th>Inward Date</th>
			<th>Branch</th>
			<th>Supplier</th>
			<th>Total</th>
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
			var toBranch = $("#tobranch").val();
			loadTable(branchCode, toBranch);
		});
		$('#btnClear').on('click', function(e) {
			$("#branch").val('').trigger('change');
			$("#tobranch").val('').trigger('change')
			loadTable("", "");
		});
		$("#branch").select2({
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
		});
	});

	function loadTable(branchCode, toBranch) {
		if ($.fn.DataTable.isDataTable("#datatableTable")) {
			$('#datatableTable').DataTable().clear().destroy();
		}
		jQuery.fn.DataTable.Api.register('buttons.exportData()', function(options) {
			if (this.context.length) {
				var jsonResult = $.ajax({
					url: base_path + "report/getPurchaseList",
					data: {
						'export': 1,
						'branchCode': branchCode,
						'toBranch': toBranch
					},
					type: "GET",
					success: function(result) {},
					async: false
				});
				var jencode = JSON.parse(jsonResult.responseText);
				return {
					body: jencode.data,
					header: $("#purchaseReport thead tr th").map(function() {
						return this.innerHTML;
					}).get()
				};
			}
		});
		var dataTable = $('#datatableTable').DataTable({
			dom: 'B<"flex-wrap mt-2"fl>trip',
			buttons: [{
				extend: 'excel',
				title: 'Purchase Report'
			}, {
				extend: 'pdf',
				title: 'Purchase Report'
			}],
			stateSave: true,
			"processing": true,
			"serverSide": true,
			"order": [],
			"searching": true,
			"ajax": {
				url: base_path + "report/getPurchaseList",
				type: "GET",
				data: {
					'branchCode': branchCode,
					'toBranch': toBranch
				},
				"complete": function(response) {}
			}
		});
	}
</script>