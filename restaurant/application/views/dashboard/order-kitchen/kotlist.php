<div class="page-heading m-5">
    <div class="page-breadcrumb">
	    <?php if($role == "R_1" || $role == "R_5"){?>
		<div class="row">
		    <form action="<?= base_url('Kitchen/getKOTDataBranchWise') ?>" method="post" data-parsley-validate>
				<div class="col-sm-4">
				   <label class="form-label">Branch:</label>
				   <?php if($branchCode != "") { ?>
						<input type="hidden" class="form-control" name="branchCode" id="branchCode" value="<?= $branchCode; ?>" readonly>
						<input type="text" class="form-control" name="branch" value="<?= $branchName; ?>" readonly>
					<?php } else { ?>
				   <select class="form-select select2" name="branchCode" id="branchCode" data-parsley-required="true">
						<option value="">Select Branch</option>
						<?php if ($branchdata) {
							foreach ($branchdata->result() as $branch) {
						?>
								<option value="<?= $branch->code ?>"><?= $branch->branchName ?></option>
						<?php
							}
						} ?>
					</select>
					<?php } ?>
				</div>
				<div class="col-sm-4 mt-3">
					  <button type="submit" class="btn btn-success white me-3 mb-1 sub_1">Submit</button>
				</div>
			</div>
		</div>
		<?php }else {?>
		      <div class="row">
			   <div class="col-sm-12">
			       <h3 class="text-center">"Sorry, you don't have access to Kitchen Orders".</h3>
			   </div>
			  </div>
		<?php }?>
    </div>
</div>