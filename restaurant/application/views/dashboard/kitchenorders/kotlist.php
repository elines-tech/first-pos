<nav class="navbar navbar-light">
    <div class="container d-block">
        <div class="row">
            <!--<div class="col-12 col-md-6 order-md-1 order-last"><a href="index.php"><i class="fa fa-times fa-2x"></i></a></div>-->
        </div>
    </div>
</nav>

<div class="container">
        <div class="row">
          <div class="col-sm-6">
		      <select class="form-select select2" name="branchCode" id="branchCode">
					<option value="">All Branch</option>
					<?php if ($branchdata) {
						foreach ($branchdata->result() as $branch) {
					?>
							<option value="<?= $branch->code ?>"><?= $branch->branchName ?></option>
					<?php
						}
					} ?>
			   </select>
		  </div>
		</div>
</div>

<script src="<?= base_url() ?>assets/js/bootstrap.js"></script>
<script src="<?= base_url() ?>assets/js/app.js"></script>

<!-- Need: Apexcharts -->
<script src="<?= base_url() ?>assets/extensions/apexcharts/apexcharts.min.js"></script>
<script src="<?= base_url() ?>assets/js/pages/dashboard.js"></script>

<script src="<?= base_url() ?>assets/extensions/jquery/jquery.min.js"></script>
<script src="<?= base_url() ?>assets/extensions/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="<?= base_url() ?>assets/js/pages/form-element-select.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.js"></script>
<script src="<?= base_url() ?>assets/js/pages/datatables.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>assets/js/webix.js">


<script src="<?= base_url() ?>assets/extensions/parsleyjs/parsley.min.js"></script>
<script src="<?= base_url() ?>assets/js/pages/parsley.js"></script>


<script src="<?php echo base_url() . 'assets/admin/assets/libs/toastr/build/toastr.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/admin/assets/libs/sweetalert2/dist/sweet-alert.min.js'; ?>"></script>

<script>
    $(window).on('load', function() { // makes sure the whole site is loaded 
        $('#status').fadeOut(); // will first fade out the loading animation 
        $('#preloader').delay(250).fadeOut('slow'); // will fade out the white DIV that covers the website. 
        $('body').delay(250).css({
            'overflow': 'visible'
        });
    });
</script>

</body>

</html>