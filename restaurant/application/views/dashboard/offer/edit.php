<nav class="navbar navbar-light">
	<div class="container d-block">
		<div class="row">
			<div class="col-12 col-md-6 order-md-1 order-last"><a href="<?php echo base_url(); ?>offer/listRecords"><i class="fa fa-times fa-2x"></i></a></div>

		</div>
	</div>
</nav>


<div class="container">
	<section id="multiple-column-form" class="mt-5">
		<div class="row match-height">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">Edit Offer</h3>
					</div>
					<div class="card-content">
						<div class="card-body">
							<div class="row">
							 <div class="col-sm-3"></div>
                            <div class="col-sm-6">
							<form id="offerForm"  method="post"  enctype="multipart/form-data" data-parsley-validate="">
								<?php if ($offerData) {
									$result = $offerData->result()[0];
								?>
								<div class="row">
									<div class="col-md-12 col-12">
										<div class="row">
											<input type="hidden" class="form-control" id="code" name="code" value="<?= $result->code?>">
											<div class="col-md-8 col-12">
												<div class="form-group mandatory">
													<label for="" class="form-label">Offer Title</label>
													<input type="text" id="title" name="title" class="form-control" required value="<?= $result->title?>"> 
												</div>
											</div>
											<div class="col-md-4 col-12">
												<div class="form-group mandatory">
													<label for="product-name" class="form-label">Offer type</label>
													<select id="offerType" name="offerType" class="form-control" required>
														<option value="">Select type</option>
														<option value="flat">Flat</option>
														<option value="cap">Cap</option>
													</select>
												</div>
												<script>
												var offerType = '<?= $result->offerType?>';
												$('#offerType').val(offerType);
												</script>
											</div>
											
										</div>
										<div class="row">
											<div class="col-md-4 col-12">
												<div class="form-group mandatory">
													<label for="" class="form-label">Minimum Amount</label>
													<input type="number" id="minimumAmount" name="minimumAmount" class="form-control" required value="<?= $result->minimumAmount?>">
												</div>
											</div>
											<div class="col-md-4 col-12 d-none" id="discountDiv">
												<div class="form-group mandatory">
													<label for="" class="form-label"> Discount (%)</label>
													<input type="number" step="0.01" id="discount" name="discount" class="form-control" required value="<?= $result->discount?>">
												</div>
											</div>
											<div class="col-md-4 col-12 d-none" id="capDiv">
												<div class="form-group mandatory">
													<label for="" class="form-label">Cap limit</label>
													<input type="number" id="capLimit" name="capLimit" class="form-control" value="<?= $result->capLimit?>">
												</div>
											</div>
											<div class="col-md-4 col-12 d-none" id="flatAmountDiv">
												<div class="form-group mandatory">
													<label for="" class="form-label">Flat Amount</label>
													 <input type="number" id="flatAmount" name="flatAmount" class="form-control" value="<?= $result->flatAmount?>">
												</div>
											</div>
											
										</div>
										
										<div class="row">
											<div class="col-md-12 col-12">
												<div class="form-group mandatory">
													<label for="description" class="form-label mb-1">Offer Description : </label>
													<textarea class="form-control" id="description" name="description" placeholder="Description"><?= $result->description?></textarea>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6 col-12">
												<div class="form-group mandatory">
													<label for="" class="form-label">Start Date</label>
													 <input type="date" id="startDate" name="startDate" class="form-control" required value="<?= $result->startDate?>">
												</div>
											</div>
											<div class="col-md-6 col-12">
												<div class="form-group mandatory">
													<label for="" class="form-label">End Date</label>
													 <input type="date" id="endDate" name="endDate" class="form-control" required value="<?= $result->endDate?>">
												</div>
											</div>
											
										</div>
										<div class="row">
											<div class="col-md-2 col-12">
												<div class="form-group">
													<label class="form-label lng">Active</label>
													<div class="input-group">
														<div class="input-group-prepend"><span class="input-group-text bg-soft-primary">
																<input type="checkbox" checked id="isActive" name="isActive" <?php if($result->isActive==1){ echo 'checked';}?> class="form-check-input"></span>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-12 d-flex justify-content-end">
												<?php if($updateRights==1){ ?>
												<button type="submit" class="btn btn-success white me-1 mb-1 sub_1" id="saveOfferBtn">Update</button>
												<?php } ?>
												<button type="button" id="closeOfferBtn" class="btn btn-light-secondary me-1 mb-1">Reset</button>
											</div>
										</div>
									</div>
								</div>
								<?php } ?>
							</form>
							</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script type="text/javascript">
	$( document ).ready(function() {
	$('input[type=number]').on('mousewheel',function(e){ $(this).blur(); });
		// Disable keyboard scrolling
		$('input[type=number]').on('keydown',function(e) {
		var key = e.charCode || e.keyCode;
		// Disable Up and Down Arrows on Keyboard
		if(key == 38 || key == 40 ) {
			e.preventDefault();
		} 
		else 
		{
			return;
		}
	});
	var typeget = $('#offerType').val().trim();
	 if (typeget == "flat") {
            $('#discountDiv').addClass('d-none');
            $("#discount").prop('required', false);
            $('#capDiv').addClass('d-none');
            $("#capLimit").prop('required', false);
            $('#flatAmountDiv').removeClass('d-none');
            $("#flatAmount").prop('required', true);
        } else if (typeget == "cap") {
            $('#discountDiv').removeClass('d-none');
            $("#discount").prop('required', true);
            $('#capDiv').removeClass('d-none');
            $("#capLimit").prop('required', true);
            $('#flatAmountDiv').addClass('d-none');
            $("#flatAmount").prop('required', false);
        } else {
            $('#discountDiv').addClass('d-none');
            $("#discount").prop('required', false);
            $('#capDiv').addClass('d-none');
            $("#capLimit").prop('required', false);
            $('#flatAmountDiv').addClass('d-none');
            $("#flatAmount").prop('required', false);
        }
	
	$("#description").summernote({
		placeholder: 'Offer Description...',
		height: 200
	});
	
	    $("body").on("change", "#endDate", function(e) {
            var endDate = $(this).val();
            var startDate = $('#startDate').val();
            if (startDate > endDate) {
                $("#endDate").val('');
                toastr.error("The End Date should be greater than the Start date.", "Opps", {
                    "progressBar": true
                });
                return false;
            }
        });

        $("body").on("change", "#startDate", function(e) {
            var endDate = $('#endDate').val();
            if (endDate != "") {
                var startDate = $(this).val();
                if (startDate > endDate) {
                    $("#startDate").val('');
                    toastr.error("The End Date should be greater than the Start date.", "Opps", {
                        "progressBar": true
                    });
                    return false;
                }
            }
        });
	$("body").on("change","#offerType",function(e){
		var typeValue = $(this).val().trim();
		 if (typeValue == "flat") {
                $('#discountDiv').addClass('d-none');
                $("#discount").prop('required', false);
                $('#capDiv').addClass('d-none');
                $("#capLimit").prop('required', false);
                $('#flatAmountDiv').removeClass('d-none');
                $("#flatAmount").prop('required', true);
            } else if (typeValue == "cap") {
                $('#discountDiv').removeClass('d-none');
                $("#discount").prop('required', true);
                $('#capDiv').removeClass('d-none');
                $("#capLimit").prop('required', true);
                $('#flatAmountDiv').addClass('d-none');
                $("#flatAmount").prop('required', false);
            } else {
                $('#discountDiv').addClass('d-none');
                $("#discount").prop('required', false);
                $('#capDiv').addClass('d-none');
                $("#capLimit").prop('required', false);
                $('#flatAmountDiv').addClass('d-none');
                $("#flatAmount").prop('required', false);
            }
	});

});
$("#offerForm").submit(function(e) {
		e.preventDefault();  
		var formData = new FormData(this);
		var form = $(this);
	    form.parsley().validate();
		if (form.parsley().isValid()){
			var isActive=0;
			if($("#isActive").is(':checked')){
				isActive=1;
			}
			formData.append('isActive',isActive);
			$.ajax({
				url: base_path + "offer/update",
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$('#saveOfferBtn').prop('disabled',true);
					$('#saveOfferBtn').text('Please wait..');
					$('#closeOfferBtn').prop('disabled',true);
				},
				success: function(response) {
					$('#saveOfferBtn').prop('disabled',false);
					$('#saveOfferBtn').text('Update');
					$('#closeOfferBtn').prop('disabled',false);
					var obj = JSON.parse(response);
					if (obj.status) {
						toastr.success(obj.message, 'Offer', {
							"progressBar": true
						});
						location.href=base_path+"offer/listRecords"
					} else {
						toastr.error(obj.message, 'Offer', {
							"progressBar": true
						});
					}
				}
			});
		}
    });
	function validateFlatAmount(){
		var minimumAmount = $('#minimumAmount').val();
		var offerType = $('#offerType').val();
		var flatAmount = $('#flatAmount').val();
		if(offerType=='flat'){
			if(minimumAmount!='' && flatAmount!=''){
				if(Number(flatAmount)>Number(minimumAmount)){
					toastr.error("Flat amount should be less than or equal to minimum amount", 'Vendor Offer', {"progressBar": true});
					$('#flatAmount').val('');
					$('#flatAmount').focus();
					return true;
				}
			}
		}
		
	}
</script>