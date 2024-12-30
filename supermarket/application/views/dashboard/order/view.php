<div id="main-content">
	<div class="page-heading">
		<div class="page-title">
			<div class="row">
				<div class="col-12 col-md-6 order-md-1 order-last">
					<h3>Order</h3>
				</div>
				<div class="col-12 col-md-6 order-md-2 order-first">
					<nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="../../dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
							<li class="breadcrumb-item active" aria-current="page">Order</li>
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
							<h3>
								View Order
								<span class="float-end"><a href="<?= base_url("order/listRecords") ?>" class="btn btn-sm btn-outline-info">Back</a></span>
							</h3>
						</div>
						<div class="card-content">
							<div class="card-body">
								<?php
								if ($query) {
									foreach ($query->result() as $row) {
										$offerText = '';
										$paymentMode = $row->paymentMode;

								?>
										<div class="col-12">
											<div class="row">
												<div class="col-md-4 col-12">
													<div class="form-group mandatory">
														<label for="orderDate"> Order Date : </label>
														<input type="text" id="orderDate" name="orderDate" class="form-control-line" value="<?= date('d/m/Y h:i A', strtotime($row->orderDate)) ?>">
													</div>
												</div>
												<div class="col-md-4 col-12">
													<div class="form-group mandatory">
														<label for="orderCode"> Order Code : </label>
														<input type="text" id="orderCode" name="orderCode" class="form-control-line" value="<?= $row->code ?>" required readonly>
													</div>
												</div>
												<div class="col-md-4 col-12">
													<div class="form-group mandatory">
														<label for="txnId"> Transaction Number : </label>
														<input type="text" id="txnId" name="txnId" class="form-control-line" value="<?= $row->txnId ?>" required readonly>
													</div>
												</div>
												<div class="col-md-4 col-12">
													<div class="form-group mandatory">
														<label for="clientName"> Customer Name : </label>
														<input type="text" id="clientName" name="clientName" value="<?= $row->name ?>" class="form-control-line" required disabled>
													</div>
												</div>
												<div class="col-md-4 col-12">
													<div class="form-group mandatory">
														<label for="phone"> Phone : </label>
														<input type="number" id="phone" name="phone" class="form-control-line" value="<?= $row->phone ?>" readonly>
													</div>
												</div>
												<div class="col-md-4 col-12">
													<div class="form-group mandatory">
														<label for="paymentmode"> Payment Mode : </label>
														<input type="text" id="paymentmode" name="paymentmode" class="form-control-line" value="<?= ucwords($paymentMode) ?>" readonly>
													</div>
												</div>
											</div>
										</div>
										<div class="col-12 mt-4">
											<h4>Product Details</h4>
											<hr />
											<div class="table-responsive">
												<table id="datatableOrderDetails" class="table table-striped table-bordered">
													<thead>
														<tr>
															<th>Sr.No.</th>
															<th>Barcode</th>
															<th>Product-Variant</th>
															<th>Quantity Unit</th>
															<th>Price</th>
															<th>Subtotal</th>
															<th>Discount price</th>
															<th>Tax</th>
															<th>Total</th>
														</tr>
													</thead>
												</table>
											</div>
											<div class="row mt-2 float-right">
												<div class="col-md-6 offset-md-6">
													<b><label>Subtotal: </label> <span style="float:right;"><?= number_format($row->subTotal, 2, '.', '') ?></span></b>
												</div>
												<div class="col-md-6 offset-md-6">
													<b style="width:100%"><label>Total Product Discount :</label><span style="float:right;"><?= number_format($row->discountTotal, 2, '.', '') ?></span></b>
												</div>
												<div class="col-md-6 offset-md-6">
													<b style="width:100%"><label>Total Offer Discount :</label><span style="float:right;"><?= number_format($row->offerDiscountTotal, 2, '.', '') ?></span></b>
												</div>
												<div class="col-md-6 offset-md-6">
													<b style="width:100%"><label>Total Gift Discount :</label><span style="float:right;"><?= number_format($row->giftDiscountTotal, 2, '.', '') ?></span></b>
												</div>
												<div class="col-md-6 offset-md-6">
													<b style="width:100%"><label>Actual Tax (+): </label><span style="float:right;"><?= number_format($row->totalTax, 2, '.', '') ?></span></b>
												</div>
												<div class="col-md-6 offset-md-6">
													<div style="border-bottom:2px dashed;margin:10px 0"></div>
												</div>
												<div class="col-md-6 offset-md-6">
													<b style="width:100%;"><label>Grand Total: </label><span style="float:right;"><?= number_format($row->totalPayable, 2, '.', '') ?></span></b>
												</div>
											</div>
										</div>
								<?php
									}
								}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
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
					url: base_path + "order/getOrderDetails",
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