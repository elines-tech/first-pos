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
                            <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
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
						<h3 class="card-title">View Order</h3>
					</div>
					<div class="card-content">
						<div class="card-body">
							<form class="needs-validation" method="post" id="order" action="<?php echo base_url() . 'Order/confirm'; ?>" novalidate> 
							<?php
							if($query){
								foreach ($query->result() as $row) {
									$offerText='';
									$paymentMode=$row->paymentMode;
									if($row->paymentMode=='CRDR'){
										$paymentMode = 'Credit/Debit Card';
									}
									if($row->offerData!=[]){
										$offerData = json_decode($row->offerData,true)[0];
										$discount = $offerData['discount'];
										if($offerData['offerType']=='flat'){
											$discount = $offerData['flatAmt'];
										}
										$offerText = $offerData['offerText'].' - '.ucfirst($offerData['offerType']).' - '.$discount;
									}
							?>
								<div class="col-12">
									<div class="card">
										<div class="card-body">
											<div class="row">
												<div class="col-md-4 col-12">
													<div class="form-group mandatory">
														<label for="orderDate"> Order Date : </label>
														<input type="text" id="orderDate" name="orderDate" class="form-control-line" value="<?= date('d/m/Y h:i A',strtotime($row->addDate)) ?>" >
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
														<label for="clientName"> Customer Name : </label>
														<input type="text" id="clientName" name="clientName" value="<?= $row->custName ?>" class="form-control-line" required disabled>
													</div>
												</div>
												
											</div>
											<div class="row">
												<div class="col-md-4 col-12">
													<div class="form-group mandatory">
														<label for="phone"> Phone : </label>
														<input type="number" id="phone" name="phone" class="form-control-line" value="<?= $row->custPhone ?>" readonly>
													</div>
												</div>
												<div class="col-md-4 col-12">
													<div class="form-group mandatory">
														<label for="orderStatus"> Order Status : </label>
														<input type="text" id="orderStatus" name="orderStatus" value="Confirmed" class="form-control-line" required disabled>
													</div>
												</div>
												<div class="col-md-4 col-12">
													<div class="form-group mandatory">
														<label for="paymentmode"> Payment Mode : </label>
														<input type="text" id="paymentmode" name="paymentmode" class="form-control-line" value="<?= $paymentMode ?>" readonly>
													</div>
												</div>
												
											</div>
											<div class="row">
												<div class="col-md-4 col-12">
													<div class="form-group mandatory">
														<label for="couponCode"> Coupon/Offer :</label>
														<input type="text" id="offerText" name="offerText" class="form-control-line" value="<?= $offerText ?>" readonly>
													</div>
												</div>
												<div class="col-md-6 col-12">
													<div class="form-group mandatory">
														<label for="orderStatus"> Remark: </label>
														<input type="text" id="remark" name="remark" value="<?= $row->remark ?>" class="form-control-line" required disabled>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="col-12">
									<div class="card">
										<div class="card-body">
											<h4 class="card-title">Product Details</h4>
											<hr />
											<div class="table-responsive">
												<table id="datatableOrderDetails" class="table table-striped table-bordered">
													<thead>
														<tr>
															<th>Sr.No.</th>
															<th>Product Name</th>
															<th>Product Price</th>
															<th>Quantity </th>
															<th>Total Price</th>
														</tr>
													</thead>
												</table>
											</div>
											<div class="row mt-2 float-right">
													<div class="col-md-6 offset-md-6">
														<b><label>Subtotal: </label> <span style="float:right;"><?= number_format($row->subtotal, 2, '.', '') ?></span></b>
													</div>
													<div class="col-md-6 offset-md-6">
														<b style="width:100%"><label>Discount (-): </label><span style="float:right;"><?= number_format($row->discount, 2, '.', '') ?></span></b>
													</div>
													<div class="col-md-6 offset-md-6">
														<div style="border-bottom:2px dashed;margin:10px 0"></div>
													</div>
													<div class="col-md-6 offset-md-6">
														<b style="width:100%"><label>Subtotal <span style="font-size:13px;">(after discount)</span>:</label><span style="float:right;"><?= number_format($row->subtotal-$row->discount, 2, '.', '') ?></span></b>
													</div>
													<div class="col-md-6 offset-md-6">
														<b style="width:100%"><label>Actual Tax: </label><span style="float:right;"><?= number_format($row->tax, 2, '.', '') ?></span></b>
													</div>
													<div class="col-md-6 offset-md-6">
														<b style="width:100%;"><label>Service Charges: </label><span style="float:right;"><?= number_format($row->serviceCharges, 2, '.', '') ?></span></b>
													</div>
													<div class="col-md-6 offset-md-6">
														<div style="border-bottom:2px dashed;margin:10px 0"></div>
													</div>
													<div class="col-md-6 offset-md-6">
														<b style="width:100%;"><label>Grand Total: </label><span style="float:right;"><?= number_format($row->grandTotal, 2, '.', '') ?></span></b>
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
 					url: base_path + "Cashier/orderList/getOrderDetails",
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