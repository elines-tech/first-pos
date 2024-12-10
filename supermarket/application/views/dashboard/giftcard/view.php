<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Giftcard</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Giftcard</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
	<section id="multiple-column-form" class="mt-3">
		<div class="row match-height">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h3>View Giftcard<span style="float:right"><a href="<?= base_url()?>giftCard/listRecords" class="btn btn-sm btn-primary">Back</a></span></h3>
					</div>
					<div class="card-content">
						<div class="card-body">
						  <div class="row">
                            <div class="col-sm-12">
							<form id="cardForm"  method="post"  enctype="multipart/form-data" data-parsley-validate="">
							<?php if ($cardData) {
									$result = $cardData->result()[0];
								?>
								<input type="hidden" class="form-control" id="code" name="code" value="<?= $result->code?>">
								<div class="row">
									<div class="col-md-12 col-12">
										<div class="row">
											<div class="col-md-4 col-12">
												<div class="form-group ">
													<label for="" class="form-label">Title</label>
													<input type="text" id="title" name="title" class="form-control-line" required 	value="<?= $result->title?>">
												</div>
											</div>
											<div class="col-md-4 col-12 d-none">
												<div class="form-group">
													<label for="product-name" class="form-label">Type</label>
													<select id="cardType" name="cardType" readonly class="form-select" required>
														<option value="per" selected>Per</option>
													</select>
												</div>
											</div>
											<div class="col-md-4 col-12">
												<div class="form-group">
													<label for="" class="form-label"> Discount (%)</label>
													<input type="text" id="discount" name="discount" class="form-control-line" required	value="<?= $result->discount?>">
												</div>
											</div>
											<div class="col-md-4 col-12">
												<div class="form-group">
													<label for="" class="form-label">Price</label>
													<input type="text" id="price" name="price" class="form-control-line" required	value="<?= $result->price?>">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4 col-12">
												<div class="form-group">
													<label for="" class="form-label">Validity (In days)</label>
													 <input type="text" id="validityInDays" name="validityInDays" class="form-control-line" required	value="<?= $result->validityInDays?>">
												</div>
											</div>
										
											<div class="col-md-4 col-12">
												<div class="form-group">
													<label class="form-label lng">Active</label>
													<div class="input-group">
														<div class="input-group-prepend">
															<?php if($result->isActive==1){ 
																	echo '<span class="badge bg-success">Active</span>';
																}else{
																	echo '<span class="badge bg-danger">Inactive</span>';
																}?>
													
														</div>
													</div>
												</div>
											</div>
										</div>
										
										<div class="row">
											<div class="col-md-12 col-12">
												<div class="form-group">
													<label for="description" class="form-label mb-1">Description </label>
													<p><?= $result->description?></p>
												</div>
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
</div>

</script>