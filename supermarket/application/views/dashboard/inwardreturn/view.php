<div id="main-content">
	<div class="page-heading">
		<div class="page-title">
			<div class="row">
				<div class="col-12 col-md-6 order-md-1 order-last">
					<h3>Inward</h3>
				</div>
				<div class="col-12 col-md-6 order-md-2 order-first">
					<nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="../../dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
							<li class="breadcrumb-item active" aria-current="page">Inward</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>

		<section class="section">
			<div class="row match-height">
				<div class="col-18">
					<div class="card">
						<div class="card-header">
							<h3>View Inward Return <span style="float:right"><a id="cancelDefaultButton" href="<?= base_url() ?>inwardReturn/listRecords" class="btn btn-sm btn-primary">Back</a></span></h3>
						</div>
						<div class="card-content">
							<div class="card-body">
								<form id="inwardForm" class="form" method="post" enctype="multipart/form-data" data-parsley-validate>
									<?php if ($returnData) {
										$result = $returnData->result_array()[0];
									?>
										<div class="row">
											<div class="col-md-12 col-12">
												<div class="row">
													<div class="col-md-3 col-12">
														<div class="form-group mandatory">
															<label for="" class="form-label">Batch No</label>
															<input type="text" disabled id="batchNo" class="form-control" name="batchNo" disabled value="<?= $result['batchNo'] ?>" required>
														</div>
													</div>
													<div class="col-md-3 col-12">
														<div class="form-group mandatory">
															<label for="" class="form-label">Inward Date</label>
															<input type="date" id="inwardDate" class="form-control" name="inwardDate" value="<?= date('Y-m-d', strtotime($result['inwardDate'])) ?>" disabled>
														</div>
													</div>
													<div class="col-md-3 col-12">
														<div class="form-group mandatory">
															<label for="product-name" class="form-label">Branch</label>
															<select class="form-control" disabled name="branchCode" id="branchCode" data-parsley-required="true" required>
																<option value="">Select</option>
																<?php if ($branch) {
																	foreach ($branch->result() as $br) {
																		if ($result['branchCode'] == $br->code) {
																			echo '<option value="' . $br->code . '" selected>' . $br->branchName . '</option>';
																		} else {
																			echo '<option value="' . $br->code . '">' . $br->branchName . '</option>';
																		}
																	}
																} ?>
															</select>
														</div>
													</div>
													<div class="col-md-3 col-12">
														<div class="form-group mandatory">
															<label for="product-name" class="form-label">Supplier</label>
															<select class="form-control" disabled name="supplierCode" id="supplierCode" data-parsley-required="true" required>
																<option value="">Select</option>
																<?php if ($supplier) {
																	foreach ($supplier->result() as $sr) {
																		if ($result['supplierCode'] == $sr->code) {
																			echo '<option value="' . $sr->code . '" selected>' . $sr->supplierName . '</option>';
																		} else {
																			echo '<option value="' . $sr->code . '">' . $sr->supplierName . '</option>';
																		}
																	}
																} ?>
															</select>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12 col-12">
														<table id="pert_tbl" class="table table-sm table-stripped" style="width:100%;">
															<thead>
																<tr>
																	<th width="15%">Return Date</th>
																	<th width="35%">Product Name</th>
																	<th width="15%">Return Quantity</th>
																</tr>
															</thead>
															<tbody>
																<?php
																$i = 0;
																if ($returnData) {
																	foreach ($returnData->result_array() as $co) {
																		$i++;
																?>
																		<tr id="row<?= $i ?>">
																			<td>
																				<?= date('d/m/Y h:i A', strtotime($co['addDate'])) ?>
																			</td>
																			<td><?= $co['proNameVarName'] ?></td>
																			<td>
																				<?= $co['returnQty'] ?>
																			</td>
																		</tr>
																<?php }
																} ?>

															</tbody>
														</table>
													</div>
												</div>

											</div>
										</div>
									<?php }
									?>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>