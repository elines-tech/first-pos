<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Barcode</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Inward</li>
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
						
						<h3>View Barcode<span class="float-end"><a id="cancelDefaultButton" class="btn btn-primary" href="<?= base_url()?>barcode/listRecords">Back</a></span></h3>
					</div>
					<div class="card-content">
						<div class="card-body">
							<form id="inwardForm" class="form" data-parsley-validate>
								<?php
								echo "<div class='text-danger text-center' id='error_message'>";
								if (isset($error_message)) {
									echo $error_message;
								}
								echo "</div>";
								?>
								<?php if ($barcodeData) {
									$result = $barcodeData->result_array()[0];
								?>
								<div class="row">
									<div class="col-md-12 col-12">
										<div class="row">
											<div class="col-md-3 col-12">
												 <div class="form-group mandatory">
													<label for="" class="form-label">Batch</label>
													<select class="form-select select2" id="batchCode" disabled onchange="getProducts()" name="batchCode" required style="width:100%">
														<option value="">Select</option>
														<?php
														if ($batches) {
															foreach ($batches->result() as $bt) {
																if ($result['batchNo'] == $bt->batchNo) {
																	echo "<option value='" . $bt->batchNo . "' selected>" . $bt->batchNo . "</option>";
																} else {
																	echo "<option value='" . $bt->batchNo . "'>" . $bt->batchNo . "</option>";
																}
															}
														}
														?>
													</select>
													
											   </div>
											</div>
											
										</div>
										
										<div class="row">
											<div class="col-md-12 col-12">
												<table id="pert_tbl" class="table table-sm table-stripped" style="width:100%;overflow-x:scroll;">
													<thead>
														<tr>
															<th width="20%">Product</th>
															<th width="15%">Selling Unit</th>
															<th width="12%">Selling Qty</th>
															<th width="15%">Selling Price</th>
															<th width="15%">Discount Price</th>
															<th width="10%">Tax(%)</th>
															<th width="15%">Tax Amount</th>
														</tr>
													</thead>
													<tbody>
													<?php
														$i = 0;
														if ($barcodeData) {
															foreach ($barcodeData->result_array() as $co) {
																$i++;
														?>
														<tr id="row<?= $i?>">
															<td><?= $co['proNameVarName']?></td>
															<td><?= $co['unitName']?></td>
															<td><?= $co['sellingQty']?></td>
															<td><?= $co['sellingPrice']?></td>
															<td><?= $co['discountPrice']?></td>
															<td><?= $co['taxPercent']?></td>
															<td><?= $co['taxAmount']?></td>
															
														</tr>
															<?php }
														}?>
														
													</tbody>
												</table>
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
	</section>
</div>
</div>
<script type="text/javascript">
</script>