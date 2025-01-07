<style>
	ol {
		margin-left: 20px !important;
	}
</style>
<nav class="navbar navbar-light">
	<div class="container d-block">
		<div class="row">
			<div class="col-12 col-md-6 order-md-1 order-last"><a href="<?php echo base_url(); ?>recipe/listRecords"><i id="exitButton" class="fa fa-times fa-2x"></i></a></div>
		</div>
	</div>
</nav>

<?php include '../restaurant/config.php'; ?>


<div class="container">
	<section id="multiple-column-form" class="mt-5">
		<div class="row match-height">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title"><?php echo $translations['View Recipe']?></h3>
					</div>
					<div class="card-content">
						<div class="card-body">
							<form id="recipeForm" class="form" data-parsley-validate>
								<?php
								if ($recipeData) {
									foreach ($recipeData->result() as $br) {
								?>
										<div class="row">
											<div class="col-md-12 col-12">
												<div class="row">
													<div class="col-md-12 text-center col-12">
														<div class="form-group">
															<label for="product-name" class="form-label"><?php echo $translations['Product']?></label>
															<input type="hidden" class="form-control" id="recipeCode" name="recipeCode" value="<?= $br->code ?>">
															<select class="form-control text-center" name="productCode" id="productCode" disabled>
																<option value="">Select</option>
																<?php if ($product) {
																	foreach ($product->result() as $pr) {
																		if ($br->productCode == $pr->code) {
																			echo '<option value="' . $pr->code . '" selected>' . $pr->productEngName . '</option>';
																		} else {
																			echo '<option value="' . $pr->code . '">' . $pr->productEngName . '</option>';
																		}
																	}
																} ?>
															</select>
														</div>
													</div>
													<div class="col-md-2 col-12 d-none">
														<div class="form-group">
															<label class="form-label lng">Active</label>
															<div class="input-group">
																<div class="input-group-prepend"><span class="input-group-text bg-soft-primary">
																		<input type="checkbox" name="isActive" checked class="form-check-input"></span>
																</div>
															</div>
														</div>

													</div>
												</div>
												<div class="row">
													<div class="col-md-12 col-12">
														<table id="pert_tbl" class="table table-sm table-stripped disabled" style="width:100%;">
															<thead>
																<tr>
																	<th width="35%"><?php echo $translations['Item']?></th>
																	<th width="25%"><?php echo $translations['Ingredient Unit']?></th>
																	<th width="15%"><?php echo $translations['Quantity']?></th>
																	<th width="15%"><?php echo $translations['Cost']?></th>
																	<th width="10%"><?php echo $translations['Customizable']?></th>
																</tr>
															</thead>
															<tbody>
																<?php
																$i = 0;
																if ($recipelineentries) {
																	foreach ($recipelineentries->result() as $re) {
																		$i++;
																?>
																		<tr id="row<?= $i ?>">
																			<td width="35%">
																				<input type="hidden" class="form-control" name="recipeLineCode" id="recipeLineCode<?= $i ?>" value="<?= $re->code ?>">
																				<select class="form-control" id="itemCode<?= $i ?>" name="itemCode<?= $i ?>" disabled>
																					<option value="">Select Item</option>
																					<?php
																					if ($itemList) {
																						foreach ($itemList->result() as $itm) {
																							if ($itm->code == $re->itemCode) {
																								echo "<option value='" . $itm->code . "' selected >" . $itm->itemEngName . ' - ' . $itm->storageUnitName . "</option>";
																							} else {
																								echo "<option value='" . $itm->code . "'>" . $itm->itemEngName . "</option>";
																							}
																						}
																					}
																					?>
																				</select>
																			</td>
																			<td width="25%">
																				<select class="form-control" id="itemUnit<?= $i ?>" name="itemUnit<?= $i ?>" disabled>
																					<option value=""></option>
																					<?php
																					if ($unitmaster) {
																						foreach ($unitmaster->result() as $um) {
																							if ($um->code == $re->unitCode) {
																								echo "<option value='" . $um->code . "' selected >" . $um->unitName . "</option>";
																							} else {
																								echo "<option value='" . $um->code . "'>" . $um->unitName . "</option>";
																							}
																						}
																					}
																					?>
																				</select>
																			</td>
																			<td width="15%">
																				<input type="text" class="form-control" name="itemQty<?= $i ?>" id="itemQty<?= $i ?>" onkeypress="return isNumber(event)" value="<?= $re->itemQty ?>" disabled>
																			</td>
																			<td width="15%">
																				<input type="text" class="form-control" name="itemCost<?= $i ?>" id="itemCost<?= $i ?>" onkeypress="return isNumber(event)" value="<?= $re->itemCost ?>" disabled>
																			</td>
																			<td width="15%" align="center">
																				<input type="checkbox" style="width:25px; height:25px;" class="" name="isCustomizable<?= $i ?>" id="isCustomizable<?= $i ?>" <?php if ($re->isCustomizable == 1) {
																																																					echo 'checked';
																																																				} ?> disabled>
																			</td>
																		</tr>

																<?php
																	}
																} ?>

															</tbody>
														</table>
														<div class="col-md-12">
															<div class="form-group">
																<label for="arabicname-column" class="form-label"><?php echo $translations['Direction']?></label>
																<p><?= $br->recipeDirection ?></p>
															</div>
														</div>
													</div>

												</div>
											</div>
										</div>
								<?php
									}
								} ?>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
</script>