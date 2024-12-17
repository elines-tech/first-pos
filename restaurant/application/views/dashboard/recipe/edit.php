<nav class="navbar navbar-light">
	<div class="container d-block">
		<div class="row">
			<div class="col-12 col-md-6 order-md-1 order-last"><a href="<?php echo base_url(); ?>recipe/listRecords"><i id="exitButton" class="fa fa-times fa-2x"></i></a></div>
		</div>
	</div>
</nav>


<div class="container">
	<section id="multiple-column-form" class="mt-5 mb-5">
		<div class="row match-height">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">Edit Recipe</h3>
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
														<div class="form-group mandatory">
															<label for="product-name" class="form-label">Product :</label>
															<input type="hidden" class="form-control" id="recipeCode" name="recipeCode" value="<?= $br->code ?>">
															<select class="form-select select2" name="productCode" id="productCode" disabled>
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
																	<th width="35%">Item</th>
																	<th width="25%">Ingredient Unit</th>
																	<th width="15%">Quantity</th>
																	<th width="15%">Cost</th>
																	<th width="10%">Customizable</th>
																	<th></th>
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
																				<select class="form-select select2" style="width:100%" id="itemCode<?= $i ?>" name="itemCode<?= $i ?>" onchange="checkDuplicateItem(<?= $i ?>);;getItemIngredientUnit(<?= $i ?>)">
																					<option value="">Select Item</option>
																					<?php
																					if ($itemList) {
																						foreach ($itemList->result() as $itm) {
																							if ($itm->code == $re->itemCode) {
																								echo "<option value='" . $itm->code . "' selected >" . $itm->itemEngName . ' - ' . $itm->storageUnitName . "</option>";
																							} else {
																								echo "<option value='" . $itm->code . "'>" . $itm->itemEngName . ' - ' . $itm->storageUnitName . "</option>";
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
																				<input type="text" class="form-control" name="itemQty<?= $i ?>" id="itemQty<?= $i ?>" onkeypress="return isDecimal(event)" value="<?= $re->itemQty ?>" onchange="checkQty(<?= $i ?>)">
																			</td>
																			<td width="15%">
																				<input type="text" class="form-control" name="itemCost<?= $i ?>" id="itemCost<?= $i ?>" onkeypress="return isNumber(event)" value="<?= $re->itemCost ?>">
																			</td>
																			<td width="15%" align="center">
																				<input type="checkbox" style="width:25px; height:25px;" class="" name="isCustomizable<?= $i ?>" id="isCustomizable<?= $i ?>" <?php if ($re->isCustomizable == 1) {
																																																					echo 'checked';
																																																				} ?>>
																			</td>
																			<td>
																				<a href="#" class="btn btn-danger" onclick="delete_row(<?= $i ?>,'<?= $re->code ?>')"><i class="fa fa-trash"></i>
																			</td>
																		</tr>

																<?php
																	}
																} ?>
																<tr id="row0">
																	<td width="35%">
																		<input type="hidden" class="form-control" name="recipeLineCode" id="recipeLineCode0">
																		<select class="form-control select2" style="width:100%" id="itemCode0" name="itemCode0" onchange="checkDuplicateItem(0);;getItemIngredientUnit(0)">
																			<option value="">Select Item</option>
																			<?php
																			if ($itemList) {
																				foreach ($itemList->result() as $itm) {
																					echo "<option value='" . $itm->code . "'>" . $itm->itemEngName . ' - ' . $itm->storageUnitName . "</option>";
																				}
																			}
																			?>
																		</select>
																	</td>
																	<td width="25%">
																		<select class="form-control" id="itemUnit0" name="itemUnit0" disabled>
																			<option value=""></option>
																			<?php
																			if ($unitmaster) {
																				foreach ($unitmaster->result() as $um) {
																					echo "<option value='" . $um->code . "'>" . $um->unitName . ' - ' . $itm->storageUnitName . "</option>";
																				}
																			}
																			?>
																		</select>
																	</td>
																	<td width="15%">
																		<input type="text" class="form-control" name="itemQty0" id="itemQty0" onkeypress="return isDecimal(event)" onchange="checkQty(0)">
																	</td>
																	<td width="15%">
																		<input type="text" class="form-control" name="itemCost0" id="itemCost0" onkeypress="return isNumber(event)">
																	</td>
																	<td width="15%" align="center">
																		<input type="checkbox" style="width:25px; height:25px;" class="" name="isCustomizable0" id="isCustomizable0">
																	</td>
																	<td>
																		<a id="customize" href="#" class="btn btn-success" onclick="add_row()"><i class="fa fa-plus"></i>
																	</td>
																</tr>
															</tbody>
														</table>
													</div>
												</div>
												<div class="col-md-12 col-12">
													<div class="form-group mandatory">
														<label for="arabicname-column" class="form-label">Direction</label>
														<textarea id="recipeDirection" class="form-control" required name="recipeDirection"><?= $br->recipeDirection ?></textarea>
													</div>
												</div>
												<div class="row">
													<div class="col-12 d-flex justify-content-end">
														<?php if ($updateRights == 1) { ?>
															<button type="submit" class="btn btn-success" id="saveRecipeBtn">Update</button>
														<?php } ?>
														<button type="button" id="cancelRecipeBtn" class="btn btn-light-secondary">Reset</button>
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
<script type="text/javascript">
	$(document).ready(function() {
		$('.cancel').removeClass('btn-default').addClass('btn-info');
		$("#recipeDirection").summernote({
			placeholder: 'Add preparation method here..',
			height: 350
		});
		$("#recipeForm").on("submit", function(e) {
			e.preventDefault();
			var formData = new FormData(this);
			var form = $(this);
			form.parsley().validate();
			if (form.parsley().isValid()) {
				var isActive = 0;
				var productCode = $('#productCode').val();
				if ($("#isActive").is(':checked')) {
					isActive = 1;
				}
				formData.append('productCode', productCode);
				formData.append('isActive', isActive);
				var table = document.getElementById("pert_tbl");
				var table_len = (table.rows.length) - 1;
				var tr = table.getElementsByTagName("tr");
				if (table_len == 1 && ($('#itemCode0').val() == '' || $('#itemQty0').val() == '' || $('#itemCost0').val() == '' || $('#itemQty0').val() == '')) {
					toastr.error('Please provide at least one entry', 'Recipe', {
						"progressBar": true
					});
				} else {
					$.ajax({
						type: "POST",
						url: base_path + "recipe/saveRecipe",
						cache: false,
						contentType: false,
						processData: false,
						data: formData,
						beforeSend: function() {
							$('#saveRecipeBtn').prop('disabled', true);
							$('#cancelRecipeBtn').prop('disabled', true);
							$('#saveRecipeBtn').text('Please wait..');
						},
						success: function(data) {
							var obj = JSON.parse(data);
							if (obj.status) {
								var ajax_complete = 0;
								var num = 0;
								var recipeCode = document.getElementById("recipeCode").value;
								var recipeLineCode = [];
								var itemCode = [];
								var itemUnit = [];
								var itemCost = [];
								var itemQty = [];
								var isCustomizable = [];
								for (i = 1; i <= table_len; i++) {
									var id = tr[i].id.substring(3);

									var rlc = document.getElementById("recipeLineCode" + id).value;
									if (typeof(rlc) === "undefined" && rlc === null) {
										rlc = "";
									}
									recipeLineCode.push(rlc);

									var ic = document.getElementById("itemCode" + id).value;
									if (typeof(ic) === "undefined" && ic === null) {
										ic = "";
									}
									itemCode.push(ic);

									var iu = document.getElementById("itemUnit" + id).value;
									if (typeof(iu) === "undefined" && iu === null) {
										iu = "";
									}
									itemUnit.push(iu);

									var ic = document.getElementById("itemCost" + id).value;
									if (typeof(ic) === "undefined" && ic === null) {
										ic = "0";
									}
									itemCost.push(ic);

									var iq = document.getElementById("itemQty" + id).value;
									if (typeof(iq) === "undefined" && iq === null) {
										iq = "0";
									}
									itemQty.push(iq);

									var customize = 0;
									if ($("#isCustomizable" + id).is(':checked')) {
										customize = 1;
									}
									isCustomizable.push(customize);
								}
								$.ajax({
									type: 'post',
									url: base_path + "recipe/saveLineData",
									data: {
										recipeCode: recipeCode,
										recipeLineCode: recipeLineCode,
										itemCode: itemCode,
										itemUnit: itemUnit,
										itemCost: itemCost,
										itemQty: itemQty,
										isCustomizable: isCustomizable,
										isCustomizable: isCustomizable,
									},
									dataType: "JSON",
									success: function(response) {
										if (response.status) {
											toastr.success(response.message, 'Recipe', {
												"progressBar": true,
												"onHidden": function() {
													location.href = base_path + "recipe/listRecords"
												}
											});
										} else {
											toastr.error(response.message, 'Recipe', {
												"progressBar": true,
												"onHidden": function() {
													window.location.reload();
												}
											});
										}
									},
									error: function() {
										$('#saveRecipeBtn').text('Save');
										$('#saveRecipeBtn').prop('disabled', false);
										$('#cancelRecipeBtn').prop('disabled', false);
									}
								});
							} else {
								toastr.error(obj.message, 'Recipe', {
									"progressBar": true,
									"onHidden": function() {
										$('#saveRecipeBtn').text('Save');
										$('#saveRecipeBtn').prop('disabled', false);
										$('#cancelRecipeBtn').prop('disabled', false);
									}
								});
							}
						},
						error: function() {
							$('#saveRecipeBtn').text('Save');
							$('#saveRecipeBtn').prop('disabled', false);
							$('#cancelRecipeBtn').prop('disabled', false);
						}
					});
				}
			}
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

	function isDecimal(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if ((charCode >= 48 && charCode <= 57) || (charCode >= 96 && charCode <= 105) || charCode == 8 || charCode == 9 || charCode == 37 ||
			charCode == 39 || charCode == 46 || charCode == 190) {
			return true;
		} else {
			return false;
		}
	}

	function checkQty(id) {
		var itemQty = Number($('#itemQty' + id).val());
		var result = isNaN(itemQty);
		if (result == true) {
			$('#itemQty' + id).val('');
			toastr.error('Quantity is not valid', 'Recipe', {
				"progressBar": false
			});
			return false;

		} else {
			$('#itemQty' + id).val(itemQty.toFixed(2));
		}
	}

	function add_row() {
		var table = document.getElementById("pert_tbl");
		var table_len = (table.rows.length) - 1;
		var tr = table.getElementsByTagName("tr");
		var id = 0;
		var recipeLineCode = document.getElementById("recipeLineCode0").value;
		var itemCode = document.getElementById("itemCode0").value;
		var itemUnit = document.getElementById("itemUnit0").value;
		var itemQty = document.getElementById("itemQty0").value;
		var itemCost = document.getElementById("itemCost0").value;
		var isCustomizable = '';
		if ($("#isCustomizable0").is(':checked')) {
			isCustomizable = 'checked';
		}
		if (itemCode != '' && itemUnit != '' && itemQty != '' && itemCost != '') {} else {
			toastr.error('Please provide all the details', 'Recipe Item', {
				"progressBar": false
			});
			return;
		}

		for (i = 1; i < table_len; i++) {
			var n = tr[i].id.substring(3);
			var id = n;
		}
		id++;
		var row = table.insertRow(table_len).outerHTML = '<tr id="row' + id + '">' +
			'<td width="35%"><select class="form-select select2" style="width:100%" name="itemCode' + id + '" id="itemCode' + id + '">' +
			document.getElementById("itemCode0").innerHTML +
			'</select>' +
			'<input type="hidden" class="form-control" name="recipeLineCode' + id + '" id="recipeLineCode' + id + '" value="' + recipeLineCode + '"></td>' +
			'<td width="25%"><select class="form-control" name="itemUnit' + id + '" id="itemUnit' + id + '" disabled>' +
			document.getElementById("itemUnit0").innerHTML +
			'</select></td>' +
			'<td width="15%"><input type="text" class="form-control" name="itemQty' + id + '" id="itemQty' + id + '" value="' + itemQty + '" onkeypress="return isDecimal(event)" onchange="checkQty(' + id + ');"></td>' +
			'<td width="15%"><input type="text" class="form-control" name="itemCost' + id + '" id="itemCost' + id + '" value="' + itemCost + '" onkeypress="return isNumber(event)"></td>' +
			'<td width="15%" align="center"><input type="checkbox" style="width:25px; height:25px;" name="isCustomizable' + id + '" id="isCustomizable' + id + '" ' + isCustomizable + '></td>' +
			'<td><a href="#" class="btn btn-danger" onclick="delete_row(' + id + ')"><i class="fa fa-trash"></i></a>' +
			'</td>' +
			'</tr>';
		document.getElementById("itemCode" + id).value = itemCode;
		document.getElementById("itemUnit" + id).value = itemUnit;
		//document.getElementById("itemCode0").value = "";
		$("#itemCode0").val('').trigger('change');
		document.getElementById("itemUnit0").value = "";
		document.getElementById("itemQty0").value = "";
		document.getElementById("itemCost0").value = "";
		document.getElementById("isCustomizable0").checked = false;
		document.getElementById("itemCode0").focus();
	}

	function delete_row(id, lineCode) {
		swal({
			title: "Are you sure?",
			text: "You want to delete this item ",
			type: "warning",
			showCancelButton: !0,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes, delete it!",
			cancelButtonText: "No, cancel it!",
			closeOnConfirm: !1,
			closeOnCancel: !1
		}, function(e) {
			if (e) {
				$.ajax({
					url: base_path + "recipe/deleteRecipeLine",
					type: 'POST',
					data: {
						'lineCode': lineCode
					},
					dataType: "JSON",
					success: function(response) {
						if (response) {
							swal({
									title: "Completed",
									text: "Successfully Deleted",
									type: "success"
								},
								function(isConfirm) {
									if (isConfirm) {
										var row = document.getElementById("row" + id);
										row.parentNode.removeChild(row);
									}
								});
						} else {
							toastr.success('Record Not Deleted', 'Failed', {
								"progressBar": true
							});
						}

					}
				});
			} else {
				swal.close()
			}
		});
	}

	function checkDuplicateItem(id) {
		var table = document.getElementById("pert_tbl");
		var table_len = (table.rows.length) - 1;
		var tr = table.getElementsByTagName("tr");
		var itemCode = document.getElementById("itemCode" + id).value.toLowerCase();
		if (itemCode != "") {
			for (i = 1; i <= table_len; i++) {
				var row_id = tr[i].id.substring(3);
				var itemCode_row = document.getElementById("itemCode" + row_id).value.toLowerCase();
				if (itemCode_row == itemCode && row_id != id) {
					toastr.error('Item already exist. Please provide another Item', 'Duplicate recipe Item', {
						"progressBar": true
					});
					document.getElementById("itemCode" + id).value = "";
					document.getElementById("itemUnit" + id).value = "";
					document.getElementById("itemCode" + id).focus();
					return 1;
				}
			}
		}
	}

	function getItemIngredientUnit(id) {
		var itemCode = $('#itemCode' + id).val();
		if (itemCode != '') {
			$.ajax({
				url: base_path + "recipe/getItemIngredientUnit",
				type: 'POST',
				data: {
					'itemCode': itemCode
				},
				success: function(response) {
					if (response != '') {
						$('#itemUnit' + id).val(response)
					} else {
						$('#itemUnit' + id).val('')
					}
				}
			})
		}
	}
</script>