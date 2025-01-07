<nav class="navbar navbar-light">
	<div class="container d-block">
		<div class="row">
			<div class="col-12 col-md-6 order-md-1 order-last"><a href="<?php echo base_url(); ?>recipe/listrecords"><i id="exitButton" class="fa fa-times fa-2x"></i></a></div>
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
						<h3 class="card-title"><?php echo $translations['Add Recipe']?></h3>
					</div>
					<div class="card-content">
						<div class="card-body">
							<form id="recipeForm" class="form" data-parsley-validate>
								<?php
								echo "<div class='text-danger text-center' id='error_message'>";
								if (isset($error_message)) {
									echo $error_message;
								}
								echo "</div>";
								?>
								<div class="row">
									<div class="col-md-12 col-12">
										<div class="row">
											<div class="col-md-12 text-center col-12 justify-content-center">
												<div class="form-group mandatory">
													<label for="product-name" class="form-label"><?php echo $translations['Product']?></label>
													<input type="hidden" class="form-control" id="recipeCode" name="recipeCode">
													<select class="form-select" style="width:100%" name="productCode" id="productCode" data-parsley-required="true">
													</select>
												</div>
											</div>
											<div class="col-md-2 col-12 d-none">
												<div class="form-group">
													<label class="form-label lng">Active</label>
													<div class="input-group">
														<div class="input-group-prepend">
															<span class="input-group-text bg-soft-primary">
																<input type="checkbox" name="isActive" checked class="form-check-input">
															</span>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12 col-12">
												<table id="pert_tbl" class="table table-sm table-stripped" style="width:100%;">
													<thead>
														<tr>
															<th width="35%"><?php echo $translations['Item']?></th>
															<th width="25%"><?php echo $translations['Ingredient Unit']?></th>
															<th width="15%"><?php echo $translations['Quantity']?></th>
															<th width="15%"><?php echo $translations['Cost']?></th>
															<th width="10%"><?php echo $translations['Customizable']?></th>
															<th></th>
														</tr>
													</thead>
													<tbody>
														<tr id="row0">
															<td width="35%">
																<input type="hidden" class="form-control" name="recipeLineCode" id="recipeLineCode0">
																<select class="form-select select2" style="width:100%" id="itemCode0" name="itemCode0" onchange="checkDuplicateItem(0);getItemIngredientUnit(0);getItem(0)">
																</select>
															</td>
															<td width="25%">
																<select class="form-control" id="itemUnit0" name="itemUnit0" disabled>
																	<option value=""></option>
																	<?php
																	if ($unitmaster) {
																		foreach ($unitmaster->result() as $um) {
																			echo "<option value='" . $um->code . "'>" . $um->unitName . "</option>";
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
																<a href="#" id="customize" class="btn btn-success" onclick="add_row()"><i class="fa fa-plus"></i>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<div class="col-md-12 col-12">
											<div class="form-group mandatory">
												<label for="arabicname-column" class="form-label"><?php echo $translations['Direction']?></label>
												<textarea id="recipeDirection" class="form-control" required name="recipeDirection"></textarea>
											</div>
										</div>
										<div class="row">
											<div class="col-12 d-flex justify-content-end">
												<?php if ($insertRights == 1) { ?>
													<button type="submit" class="btn btn-success" id="saveRecipeBtn"><?php echo $translations['Save']?></button>
												<?php } ?>
												<button type="reset" id="cancelRecipeBtn" class="btn btn-light-secondary"><?php echo $translations['Reset']?></button>
											</div>
										</div>
									</div>
								</div>
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
		$("#productCode").select2({
			placeholder: "Select Product",
			allowClear: true,
			ajax: {
				url: base_path + 'Common/getProduct',
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

		$("#itemCode0").select2({
			placeholder: "Select Item",
			allowClear: true,
			ajax: {
				url: base_path + 'Common/getItem',
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

		$('.cancel').removeClass('btn-default').addClass('btn-info');
		$("#recipeDirection").summernote({
			placeholder: 'Add preparation method here..',
			height: 250
		});
		$("#recipeForm").on("submit", function(e) {
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
				var table = document.getElementById("pert_tbl");
				var table_len = (table.rows.length) - 1;
				var tr = table.getElementsByTagName("tr");
				if (table_len == 1 && ($('#itemCode0').val() == '' || $('#itemQty0').val() == '' || $('#itemUnit0').val() == '' || $('#itemCost0').val() == '' || $('#itemQty0').val() == '')) {
					toastr.error('Please select atleast one item', 'Recipe', {
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
							$('#saveRecipeBtn').text('Save');
							$('#saveRecipeBtn').prop('disabled', false);
							$('#cancelRecipeBtn').prop('disabled', false);
							var obj = JSON.parse(data);
							if (obj.status) {
								var ajax_complete = 0;
								var num = 0;
								for (i = 1; i <= table_len; i++) {
									var id = tr[i].id.substring(3);
									document.getElementById("recipeCode").value = obj.recipeCode;
									var recipeCode = document.getElementById("recipeCode").value;
									var recipeLineCode = document.getElementById("recipeLineCode" + id).value;
									var itemCode = document.getElementById("itemCode" + id).value;
									var itemUnit = document.getElementById("itemUnit" + id).value;
									var itemCost = document.getElementById("itemCost" + id).value;
									var itemQty = document.getElementById("itemQty" + id).value;
									var isCustomizable = 0;
									if ($("#isCustomizable" + id).is(':checked')) {
										isCustomizable = 1;
									}
									if (itemCode != '' && itemUnit != '' && itemCost != '' && itemQty != '') {
										num++;
										$.ajax({
											type: 'post',
											url: base_path + "recipe/saveRecipeLine",
											data: {
												recipeCode: recipeCode,
												recipeLineCode: recipeLineCode,
												itemCode: itemCode,
												itemUnit: itemUnit,
												itemCost: itemCost,
												itemQty: itemQty,
												isCustomizable: isCustomizable,
											},
											success: function(response) {
												ajax_complete++;
												if (num == ajax_complete) {
													toastr.success(obj.message, 'Recipe', {
														"progressBar": true,
														"onHidden": function() {
															location.href = base_path + "recipe/listRecords"
														}
													});
												}
											}
										});
									}
								}
							} else {
								toastr.error(obj.message, 'Recipe', {
									"progressBar": true
								});
							}
						}
					});
				}
			}
		});
	});

	function getItem(id) {
		$("#itemCode" + id).select2({
			placeholder: "Select Item",
			allowClear: true,
			ajax: {
				url: base_path + 'Common/getItem',
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
	}

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
			'<td width="35%"><select class="form-control select2" style="width:100%" name="itemCode' + id + '" id="itemCode' + id + '">' +
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
		$("#itemCode0").val('').trigger('change');
		//document.getElementById("itemCode0").value = "";
		document.getElementById("itemUnit0").value = "";
		document.getElementById("itemQty0").value = "";
		document.getElementById("itemCost0").value = "";
		document.getElementById("isCustomizable0").checked = false;
		document.getElementById("itemCode0").focus();
	}

	function delete_row(id) {
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
				swal.close()
				var row = document.getElementById("row" + id);
				row.parentNode.removeChild(row);
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