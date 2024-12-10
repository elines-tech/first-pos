<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Product Attribute</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Product Attribute</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

<div class="container">
    	<section id="multiple-column-form" class="mt-5">
		<div class="row match-height">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">Add Varaint</h3>
					</div>
					<div class="card-content">
						<div class="card-body">
							<form id="variantForm" class="form" data-parsley-validate>
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
											<div class="col-md-4 col-12">
												<div class="form-group mandatory">
													<label for="product-name" class="form-label">Product </label>
													<select class="form-select select2" name="productCode" id="productCode" onchange="getAttributeList()" data-parsley-required="true">
														<option value="">Select</option>
														<?php if ($product) {
															foreach ($product->result() as $pr) {
																echo '<option value="' . $pr->code . '">' . $pr->productEngName . '</option>';
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
												<table id="pert_tbl" class="table table-sm table-stripped" style="width:100%;">
													<thead>
														<tr>
															<th>Attribute</th>
															<th>Option</th>
															<th>Selling Unit</th>
															<th>Selling Quantity</th>
															<th>Price</th>
															<th></th>
														</tr>
													</thead>
													<tbody>
														<tr id="row0">
															<td>
																<select class="form-select select2" id="attribute0" name="attribute0" onchange="checkDuplicate(0);getOptionList(0)">
																	
																</select>
															</td>
															<td>
																<select class="form-select" id="option0" name="option0" disabled>
																	<option value=""></option>
																	
																</select>
															</td> 
															<td>
																<select class="form-select" name="sellingUnit0" id="sellingUnit0">
																</select>
															</td>
															<td>
																<input type="text" class="form-control right-align" name="sellingQty0" id="sellingQty0" onkeypress="return isNumber(event)">
															</td>
															<td>
																<input type="text" class="form-control right-align" name="price0" id="price0" onkeypress="return isNumber(event)">
															</td>
															<td>
																<a href="#" class="btn btn-success" onclick="add_row()"><i class="fa fa-plus"></i> 
															</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										
										<div class="row">
											<div class="col-12 d-flex justify-content-end">
												<button type="submit" class="btn btn-success white me-1 mb-1 sub_1" id="saveVariantBtn">Save</button>
												<button type="button" id="cancelVariantBtn" class="btn btn-light-secondary me-1 mb-1">Reset</button>
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
<div>
</div>

<script>
   $(document).ready(function() {
		$("#variantForm").on("submit", function(e) {
			e.preventDefault();
			debugger;
			var table = document.getElementById("pert_tbl");
			var table_len = (table.rows.length) - 1;
			var tr = table.getElementsByTagName("tr");
			var ajax_complete = 0;
			var num = 0;
			for (i = 1; i <= table_len; i++) {
				var id = tr[i].id.substring(3);
				var productCode = document.getElementById("productCode").value;
				var attribute = document.getElementById("attribute"+id).value;
				var option = document.getElementById("option"+id).value;
				var sellingUnit = document.getElementById("sellingUnit" + id).value;
				var sellingQty = document.getElementById("sellingQty" + id).value;
				var price = document.getElementById("price" + id).value;
				if (attribute != '' && option != '' && sellingUnit!='' && $sellingQty!='' && price!='') {
					num++;
						$.ajax({
							type: 'post',
							url: base_path + "variant/saveVariant",
							data: {
								productCode: productCode,
								attribute: attribute,
								option: option,
								sellingUnit: sellingUnit,
								sellingQty: sellingQty,
								price:price
							},
							beforeSend: function() {
								$('#saveVariantBtn').prop('disabled', true);
								$('#cancelVariantBtn').prop('disabled', true);
								$('#saveVariantBtn').text('Please wait..');
							},
							success: function(response) {
								ajax_complete++;
								if (num == ajax_complete) {
									toastr.success('Variant saved successfully', 'Variant', {
										"progressBar": true
									});
									location.href = base_path + "variant/add"
								} else {
									toastr.success('Failed to save variant', 'Variant', {
										"progressBar": true
									});
									$('#saveVariantBtn').text('Save');
									$('#saveVariantBtn').prop('disabled', false);
									$('#cancelVariantBtn').prop('disabled', false);
								}
							}
						});
					}
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
		 var price = Number($('#price'+id).val());
		 var result = isNaN(price);
		 if(result==true){
			 $('#price'+id).val('');
				toastr.error('Quantity is not valid', 'Varaint', {
					"progressBar": false
				});
				return false;
    
		 }
		 else{
		   $('#price'+id).val(price.toFixed(2)); 
		 }
	}


	function add_row() {
		var table = document.getElementById("pert_tbl");
		var table_len = (table.rows.length) - 1;
		var tr = table.getElementsByTagName("tr");
		var id = 0;
		var attribute = document.getElementById("attribute0").value;
		var option = document.getElementById("option0").value;
		var sellingUnit = document.getElementById("sellingUnit0").value;
		var sellingQty = document.getElementById("sellingQty0").value;
		var price = document.getElementById("price0").value;
		
		if (attribute != '' && option != '' && sellingUnit != '' && sellingQty != '') {} else {
			toastr.error('Please provide all the details', 'Variant', {
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
			'<td><select class="form-select select2" name="attribute' + id + '" id="attribute' + id + '" onchange="checkDuplicate('+id+');getOptionList('+id+')">' +
			document.getElementById("attribute0").innerHTML +
			'</select></td>' +
			'<td><select class="form-select" name="option' + id + '" id="option' + id + '" >' +
			document.getElementById("option0").innerHTML +
			'</select></td>' +
			'<td><select class="form-select" name="sellingUnit' + id + '" id="sellingUnit' + id + '">'+
			document.getElementById("sellingUnit0").innerHTML +
			'</select></td>' +
			'<td><input type="text" class="form-control right-align" name="sellingQty' + id + '" id="sellingQty' + id + '" onkeypress="return isNumber(event)"></td>' +
			'<td><input type="text" class="form-control right-align" name="price' + id + '" id="price' + id + '" onkeypress="return isNumber(event)"></td>' +
			'<td><a href="#" class="btn btn-danger" onclick="delete_row(' + id + ')"><i class="fa fa-trash"></i></a>' +
			'</td>' +
			'</tr>';
		document.getElementById("attribute" + id).value = attribute;
		document.getElementById("option" + id).value = option;
		document.getElementById("sellingUnit" + id).value = sellingUnit;
		$("#attribute0").val('').trigger('change');
		document.getElementById("option0").value = "";
		document.getElementById("sellingUnit0").value = "";
		document.getElementById("sellingQty0").value = "";
		document.getElementById("price0").value = "";
		document.getElementById("attribute0").focus();


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

	function checkDuplicate(id) {
		var table = document.getElementById("pert_tbl");
		var table_len = (table.rows.length) - 1;
		var tr = table.getElementsByTagName("tr");
		var attribute = document.getElementById("attribute" + id).value.toLowerCase();
		var option = document.getElementById("option" + id).value.toLowerCase();
		if (itemCode != "") {
			for (i = 1; i <= table_len; i++) {
				var row_id = tr[i].id.substring(3);
				var attribute_row = document.getElementById("attribute" + row_id).value.toLowerCase();
				var option_row = document.getElementById("option" + row_id).value.toLowerCase();
				if (attribute_row == attribute && option==option_row && row_id != id) {
					toastr.error('Combination already exists.. please provide another', 'Duplicate product variant', {
						"progressBar": true
					});
					document.getElementById("attribute" + id).value = "";
					document.getElementById("option" + id).focus();
					return 1;
				}
			}
		}
	}
	function getAttributeList() {
		var productCode = $('#productCode' + id).val();
		if (productCode != '') {
			$.ajax({
				url: base_path + "variant/getAttributeList",
				type: 'POST',
				data: {
					'productCode': productCode
				},
				success: function(response) {
					if (response != '') {
						$('#attribute0').html(response)
					} else {
						$('#attribute0').html('')
					}
				}
			})
		}
	}
	function getOptionList(id) {
		var attribute = $('#attribute' + id).val();
		if (attribute != '') {
			$.ajax({
				url: base_path + "variant/getAttributeOption",
				type: 'POST',
				data: {
					'attribute': attribute
				},
				success: function(response) {
					if (response != '') {
						$('#option' + id).html(response)
					} else {
						$('#option' + id).html('')
					}
				}
			})
		}
	}
</script>