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
							<li class="breadcrumb-item"><a href="../../Cashier/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
							<li class="breadcrumb-item active" aria-current="page">Day Closing Report</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>

		<section class="section">
			<div class="card">
				<div class="card-header">

					<div class="row mt-1">
						<div class="col-md-12 text-center">
							<label class="form-label lng mb-2">Date</label>
							<div class="form-group mandatory">
								<input type="date" class="form-control" id="date" name="date" value="<?= date('Y-m-d') ?>">
							</div>
						</div>
						<div class="col-md-12 text-center" style="margin-top:20px;">
							<button type="button" class="btn btn-success" style="padding-top:2px;padding-bottom:2px" id="btnSearch">Search</button>
							<button type="button" class="btn btn-success" style="padding-top:2px;padding-bottom:2px" id="btnClear">Clear</button>
						</div>
					</div>
				</div>
			</div>
			<div class="card">
				<div id="loader" class="d-none" style="position: absolute;left: 50%;top: 75%;transform: translate(-50%, -50%);"> <i class="fa fa-2x fa-spinner spin"></i></div>
				<div class="row card-body" id="print_div">
				</div>
			</div>
	</div>
	</section>
</div>
</div>
</div>
</div>
</body>
<script>
	$(document).ready(function() {
		loadData('<?= date('Y-m-d') ?>');
		$('#btnSearch').on('click', function(e) {
			var date = $("#date").val();
			loadData(date);
		});
		$('#btnClear').on('click', function(e) {
			$("#date").val('<?= date('Y-m-d') ?>')
			loadData("<?= date('Y-m-d') ?>");
			$(".buttons-html5").removeClass('btn-primary').addClass('btn-primary sub_1');
		});
	});

	function loadData(date) {
		$.ajax({
			url: "<?= base_url() ?>Cashier/report/getCounterData",
			type: 'POST',
			data: {
				'date': date,
			},
			beforeSend: function() {
				$('#btnSearch').prop('disabled', true);
				$('#loader').removeClass('d-none');
			},
			success: function(response) {
				$('#btnSearch').prop('disabled', false);
				$('#loader').addClass('d-none');
				var obj = JSON.parse(response);
				$('#print_div').html('');

				$('#print_div').html(obj.reportHtml)

			}
		})
	}
</script>