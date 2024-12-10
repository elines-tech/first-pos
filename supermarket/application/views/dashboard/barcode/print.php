<style>
	.barcodeDiv{
		border:1px solid black;
	}
</style>
<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3> Barcode</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><i class="fa fa-dashboard"></i> Dashboard</li>
                            <li class="breadcrumb-item active" aria-current="page">Barcode</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
       
		<section class="section">
			<div class="row match-height">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h3>Barcode Details<span class="float-end"><a class="btn btn-sm btn-primary" href="<?= base_url()?>barcode/listRecords">Back</a></span></h3>
						</div>
						<div class="card-content">
							<div class="card-body">
							<?php if ($barcodeData) {
									$result = $barcodeData->result()[0];
								?>
								<div class="row">
									<div class="col-md-3 col-12 mb-2">
										<label for="batchNo"><b>Batch No: </b></label>
										<input type="hidden" id="code" name="code" class="form-control" value="<?= $result->code ?>">
										<input type="text" id="batchNo" name="batchNo" class="form-control-line" value="<?= $result->batchNo ?>">
									</div>
									<div class="col-md-3 col-12 mb-2">
										<label for="productName"><b>Product Name : </b></label>
										<input type="text" id="productName" name="productName" class="form-control-line" value="<?= $result->productEngName ?>">
									</div>
									<div class="col-md-3 col-12 mb-2">
										<label for="sellingUnit"><b>Selling Unit : </b></label>
										<input type="text" id="sellingUnit" name="sellingUnit" class="form-control-line" value="<?= $result->unitName ?>">
									</div>
									<div class="col-md-3 col-12 mb-2">
										<label for="sellingQty"><b>Selling Quantity : </b></label>
										<input type="text" id="sellingQty" name="sellingQty" class="form-control-line" value="<?= $result->sellingQty ?>">
									</div>
									<div class="col-md-3 col-12 mb-2">
										<label for="sellingPrice"><b>Selling Price: </b></label>
										<input type="text" id="sellingPrice" name="sellingPrice" class="form-control-line" value="<?= $result->sellingPrice ?>">
									</div>
									<div class="col-md-3 col-12 mb-2">
										<label for="discountPrice"><b>Discount: </b></label>
										<input type="text" id="discountPrice" name="discountPrice" class="form-control-line" value="<?= $result->discountPrice ?>">
									</div>
									<div class="col-md-3 col-12 mb-2">
										<label for="taxPercent"><b>Tax (%) : </b></label>
										<input type="text" id="taxPercent" name="taxPercent" class="form-control-line" value="<?= $result->taxPercent ?>">
									</div>
									<div class="col-md-3 col-12 mb-2">
										<label for="taxAmount"><b>Tax Amount : </b></label>
										<input type="text" id="taxAmount" name="taxAmount" class="form-control-line" value="<?= $result->taxAmount ?>">
									</div>
								</div>
							<?php }
							?>
								<div class="row">
									<div class="col-md-3 col-12">
										<label class="form-label"><b>Barcode Quantity : </b><b style="color:red">*</b></label>
										<input type="text" id="barcodeQty" class="form-control" onkeypress="return isNumber(event)"  name="barcodeQty" required>
									</div>
									<div class="col-md-3 col-12">
										<label class="form-label"><b>Language: </b><b style="color:red">*</b></label>
										<select id="language" class="form-control" name="language" required>
											<option value="1">English</option>
											<option value="2">Arabic</option>
											<option value="3">Urdu</option>
											<option value="4">Hindi</option>
										</select>
									</div>
									<div class="col-md-5 col-12" style="margin-top:32px;">
										<button type="button" onclick="generate()" class="btn btn-primary white sub_1" id="generateBtn">Generate</button>
										<button type="button" onclick="print()" id="printBtn" class="btn btn-primary white sub_1 d-none" id="printBtn">Print</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card d-none" id="previewDiv">
						<div class="card-content">
							<div class="card-body m-0" id="println">
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>
</body>
<script>
	function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	}
	function generate() {
		var lineCode = $("#code").val();
		var language = $("#language").val();
		var barcodeQty = $("#barcodeQty").val();
		if (lineCode != '' && language!=''  && barcodeQty!='') {
			if(barcodeQty>0){
				$.ajax({
					url: "<?= base_url() ?>barcode/generateBarcode",
					type: 'POST',
					data: {
						'lineCode': lineCode,
						'barcodeQty': barcodeQty,
						'language': language,
					},
					beforeSend: function() {
						$('#generateBtn').prop('disabled', true);
						$('#generateBtn').text('Please wait..');
					},
					success: function(response) {
						$('#generateBtn').prop('disabled', false);
						$('#generateBtn').text('Generate');
						var obj = JSON.parse(response);
						$('#previewDiv .card-body').html('');
						$('#printBtn').addClass('d-none');
						if (obj.status) {
							$('#previewDiv').removeClass('d-none');
							$('#printBtn').removeClass('d-none');
							$('#previewDiv .card-body').html(obj.barcodeHtml)
						} 
					}
				})
			}else{
				toastr.error('Barcode Quantity should be greater than zero', 'Barcode print', {"progressBar": false});
				$('#barcodeQty').val('');
				$('#barcodeQty').focus();
				return false;
			}
		}else{
			toastr.error('* fields are required', 'Barcode print', {"progressBar": false});
			return false;
		}
	}
	
	function print(){
		var divToPrint=document.getElementById('println');
		var newWin=window.open('','Print-Window');
		newWin.document.open();
		newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
		newWin.document.close();
		setTimeout(function(){newWin.close();},10);
	}
</script>