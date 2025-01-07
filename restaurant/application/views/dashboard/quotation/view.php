<nav class="navbar navbar-light">
	<div class="container d-block">
		<div class="row">
			<div class="col-12 col-md-6 order-md-1 order-last"><a href="<?php echo base_url(); ?>quotation/listRecords"><i id="exitButton" class="fa fa-times fa-2x"></i></a></div>

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
						<h3 class="card-title"><?php echo $translations['View Sale Quotation']?></h3>
					</div>
					<div class="card-content">
						<div class="card-body">
							<form id="quotationForm" class="form" method="post" enctype="multipart/form-data" data-parsley-validate>

								<?php if ($quotationData) {
									$result = $quotationData->result_array()[0];
								?>
									<div class="row">
										<div class="col-md-12 col-12">
											<div class="row">
												<div class="col-md-4 col-12">
													<div class="form-group mandatory">
														<label for="" class="form-label"><?php echo $translations['Date']?></label>
														<input type="date" class="form-control" name="date" id="date" required value="<?= $result['date'] ?>">
													</div>
												</div>
												<div class="col-md-4 col-12">
													<div class="form-group mandatory">
														<label for="" class="form-label"><?php echo $translations['Event Name']?></label>
														<input type="text" class="form-control" name="eventName" id="eventName" required value="<?= $result['eventName'] ?>">
													</div>
												</div>
												<div class="col-md-4 col-12">
													<div class="form-group mandatory">
														<label for="" class="form-label"><?php echo $translations['People']?></label>
														<input type="text" class="form-control" name="people" id="people" required value="<?= $result['peoples'] ?>">

													</div>
												</div>
												<input type="hidden" class="form-control" name="quotationCode" id="quotationCode" value="<?= $result['code'] ?>">
											</div>

											<div class="row">
												<div class="col-md-12 col-12">
													<table id="pert_tbl" class="table table-sm table-stripped" style="width:100%;">
														<thead>
															<tr>
																<th width="25%"><?php echo $translations['Products']?></th>
																<th width="25%"><?php echo $translations['Quantity/person']?></th>
																<th width="25%"><?php echo $translations['Price/person']?></th>
																<th width="25%"><?php echo $translations['Subtotal']?></th>
															</tr>
														</thead>
														<tbody>
															<?php
															$i = 0;
															if ($quotationLineEntries) {
																foreach ($quotationLineEntries->result() as $co) {
																	$i++;
															?>
																	<tr id="row<?= $i ?>">
																		<td>
																			<select class="form-control" id="productCode<?= $i ?>" name="productCode<?= $i ?>" disabled>
																				<option value="<?= $co->productCode ?>"><?= $co->productEngName ?></option>

																			</select>
																		</td>
																		<td>
																			<input type="text" class="text-left form-control" name="qtyPerPerson<?= $i ?>" id="qtyPerPerson<?= $i ?>" onkeypress="return isNumber(event)" value="<?= $co->qtyPerPerson ?>" disabled>
																		</td>
																		<td>
																			<input type="text" class="text-left form-control" name="pricePerPerson<?= $i ?>" id="pricePerPerson<?= $i ?>" onkeypress="return isNumber(event)" value="<?= $co->pricePerPerson ?>" disabled>
																		</td>
																		<td>
																			<input type="text" class="text-left form-control" name="subTotal<?= $i ?>" id="subTotal<?= $i ?>" disabled value="<?= $co->subTotal ?>">
																		</td>

																	</tr>
															<?php }
															} ?>

														</tbody>

														<tfoot>


															<tr>
																<td colspan="3">
																	<label><b><?php echo $translations['Subtotal']?></b></label>
																	<input type="text" id="subTotal" class="text-center form-control" name="subTotal" value="<?= $result['subTotal'] ?>" readonly="readonly" autocomplete="off">
																</td>
															</tr>


															<tr>
																<td colspan="3">
																	<label><b><?php echo $translations['Discount (₹)']?></b>
																	</label> <input type="text" id="discount" class="text-center form-control" disabled name="discount" value="<?= $result['discount'] ?>" onkeypress="return isNumber(event)" autocomplete="off" onkeyup="getTaxAmount()">
																</td>
															</tr>


															<tr>
																<td colspan="3">
																	<label><b><?php echo $translations['Discount Amount']?></b></label>
																	<input type="text" id="discountAmount" class="text-center form-control decimal" name="discountAmount" disabled value="<?= $result['subTotal'] - $result['discount'] ?>">
																</td>
															</tr>


															<tr>
																<td colspan="3">
																	<label><b><?php echo $translations['Tax']?></b></label>
																	<input type="text" id="taxAmount" class="text-center form-control" name="taxAmount" readonly="readonly" value="<?= $result['taxAmount'] ?>" autocomplete="off">
																	<input type="hidden" id="discountAmount" class="form-control" name="discountAmount" value="0.00">
																</td>
															</tr>


															<tr>
																<td colspan="3">
																	<label><b><?php echo $translations['Grand Total']?></b>
																	</label> <input type="text" id="grandTotal" class="text-center form-control" name="grandTotal" readonly="readonly" value="<?= $result['grandTotal'] ?>" autocomplete="off">
																</td>
															</tr>


														</tfoot>
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