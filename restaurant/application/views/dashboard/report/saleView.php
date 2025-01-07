<nav class="navbar navbar-light">
	<div class="container d-block">
		<div class="row">
			<div class="col-12 col-md-6 order-md-1 order-last"><a href="<?php echo base_url(); ?>saleReport/list"><i id="exitButton" class="fa fa-times fa-2x"></i></a></div>
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
						<h3 class="float-left"><?php echo $translations['View Sale']?></h3>
					</div>
					<div class="card-content">
						<div class="card-body">
							<form class="needs-validation" method="post" id="order" novalidate>
								<?php
								if ($query) {
									foreach ($query->result() as $row) {
										$offerText = '';
										$paymentMode = $row->paymentMode;
										if ($row->paymentMode == 'CRDR') {
											$paymentMode = 'Credit/Debit Card';
										}
										if ($row->offerData != []) {
											$offerData = json_decode($row->offerData, true);
											if (!empty($offerData)) {
												$discount = $offerData['discount'];
												if ($offerData['offerType'] == 'flat') {
													$discount = $offerData['flatAmt'];
												}
												$offerText = $offerData['offerText'] . ' - ' . ucfirst($offerData['offerType']) . ' - ' . $discount;
											}
										}
								?>
										<div class="col-12">
											<div class="row">
												<div class="col-md-4 col-12">
													<div class="form-group mandatory">
														<label for="orderDate"><?php echo $translations['Order Date']?></label>
														<input type="datetime" id="orderDate" name="orderDate" class="form-control" value="<?= $row->addDate ?>" disabled>
													</div>
												</div>
												<div class="col-md-4 col-12">
													<div class="form-group mandatory">
														<label for="orderCode"><?php echo $translations['Order Code']?></label>
														<input type="text" id="orderCode" name="orderCode" class="form-control" value="<?= $row->code ?>" required readonly>
													</div>
												</div>
												<div class="col-md-4 col-12">
													<div class="form-group mandatory">
														<label for="clientName"><?php echo $translations['Customer Name']?></label>
														<input type="text" id="clientName" name="clientName" value="<?= $row->custName ?>" class="form-control" required disabled>
													</div>
												</div>

											</div>
											<div class="row">
												<div class="col-md-4 col-12">
													<div class="form-group mandatory">
														<label for="phone"><?php echo $translations['Phone']?></label>
														<input type="number" id="phone" name="phone" class="form-control" value="<?= $row->custPhone ?>" readonly>
													</div>
												</div>
												<div class="col-md-4 col-12">
													<div class="form-group mandatory">
														<label for="orderStatus"><?php echo $translations['Order Status']?></label>
														<input type="text" id="orderStatus" name="orderStatus" value="Confirmed" class="form-control-line" required disabled>
													</div>
												</div>
												<div class="col-md-4 col-12">
													<div class="form-group mandatory">
														<label for="paymentmode"><?php echo $translations['Payment Mode']?></label>
														<input type="text" id="paymentmode" name="paymentmode" class="form-control" value="<?= $paymentMode ?>" readonly>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-4 col-12">
													<div class="form-group mandatory">
														<label for="couponCode"><?php echo $translations['Coupon/Offer']?></label>
														<input type="text" id="offerText" name="offerText" class="form-control" value="<?= $offerText ?>" readonly>
													</div>
												</div>
												<div class="col-md-8 col-12">
													<div class="form-group mandatory">
														<label for="orderStatus"><?php echo $translations['Remark']?></label>
														<input type="text" id="remark" name="remark" value="<?= $row->remark ?>" class="form-control" required disabled>
													</div>
												</div>
											</div>
										</div>
										<div class="col-12">
										   <div class="card">
											<div class="card-body">
												<h5 class="float-left"><?php echo $translations['Product Details']?></h5>
											</div>
										  <div class="col-12 p-3">
											<div class="table-responsive">
												<table id="datatableOrderDetails" class="table table-striped table-bordered">
													<thead>
														<tr>
															<th><?php echo $translations['Sr No']?></th>
															<th><?php echo $translations['Product Name']?></th>
															<th><?php echo $translations['Product Price']?></th>
															<th><?php echo $translations['Quantity']?></th>
															<th><?php echo $translations['Total Price']?></th>
														</tr>
													</thead>
												</table>
											</div>
											<div class="row mt-2 float-right">
												<div class="col-md-6 offset-md-6">
													<b><label><?php echo $translations['Subtotal']?></label> <span style="float:right;"><?= number_format($row->subtotal, 2, '.', '') ?></span></b>
												</div>
												<div class="col-md-6 offset-md-6">
													<b style="width:100%"><label><?php echo $translations['Discount']?></label><span style="float:right;"><?= number_format($row->discount, 2, '.', '') ?></span></b>
												</div>
												<div class="col-md-6 offset-md-6">
													<div style="border-bottom:2px dashed;margin:10px 0"></div>
												</div>
												<div class="col-md-6 offset-md-6">
													<b style="width:100%"><label><?php echo $translations['Subtotal']?><span style="font-size:13px;"><?php echo $translations['(after discount)']?></span>:</label><span style="float:right;"><?= number_format($row->subtotal - $row->discount, 2, '.', '') ?></span></b>
												</div>
												<div class="col-md-6 offset-md-6">
													<b style="width:100%"><label><?php echo $translations['Actual Tax']?></label><span style="float:right;"><?= number_format($row->tax, 2, '.', '') ?></span></b>
												</div>
												<div class="col-md-6 offset-md-6">
													<b style="width:100%;"><label><?php echo $translations['Service Charges']?></label><span style="float:right;"><?= number_format($row->serviceCharges, 2, '.', '') ?></span></b>
												</div>
												<div class="col-md-6 offset-md-6">
													<div style="border-bottom:2px dashed;margin:10px 0"></div>
												</div>
												<div class="col-md-6 offset-md-6">
													<b style="width:100%;"><label><?php echo $translations['Grand Total']?></label><span style="float:right;"><?= number_format($row->grandTotal, 2, '.', '') ?></span></b>
												</div>
											</div>
										</div>
									   </div>
								   </div>
								<?php
									}
								}
								?>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	$(document).ready(function() {
		loadTable();

		function loadTable() {
			if ($.fn.DataTable.isDataTable("#datatableOrderDetails")) {
				$('#datatableOrderDetails').DataTable().clear().destroy();
			}
			var orderCode = $('#orderCode').val();
			var dataTable = $('#datatableOrderDetails').DataTable({
				"processing": true,
				"serverSide": true,
				"order": [],
				"searching": false,
				"ajax": {
					url: base_path + "orderList/getOrderDetails",
					type: "GET",
					data: {
						'orderCode': orderCode
					},
					"complete": function(response) {}
				}
			});
		}
	});
</script>