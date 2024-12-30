<link rel="stylesheet" href="<?= base_url("assets/init_site/orderaddstyle.css") ?>" />
<div class="container-fluid pos-window">
	<div class="cat-slider cat-tp" style="display:none">
		<div class="owl-carousel owl-theme">
			<?php echo $items; ?>
		</div>
	</div>
	<div class="row my-2">
		<div class="col-8">
			<h6 class="mb-1"></h6>
			<small><b><?= date('d-m-Y') ?></b></small>
		</div>
		<div class="col-4 text-end">
			<a class="btn btn-sm btn-outline-primary" href="javascript:void(0)"><i class="fa fa-expand"></i></a>
			<a class="btn btn-sm btn-outline-dark" href="<?= base_url('Cashier/Order/listRecords') ?>"><i class="fa fa-times"></i></a>
		</div>
	</div>
	<div class="row pos-section">
		<div class="row match-height">
			<div class="col-12">
				<div class="card">
					<div class="card-content">
						<div class="card-body">
							<form id="billForm" class="form" data-parsley-validate>
								<h6 id="myTabs">Order Detail:</h6>
								<div class="row">
									<div class="col-md-8 col-12">
										<div class="bg-light padcls mb-2">
											<div class="row col-12">
												<div class="col-md-4 col-12">
													<?php
													if (!empty($company)) {
														echo '<h3 class="mb-1">' . ucwords($company['companyname']) . '</h3>';
													}
													if (!empty($branch)) {
														echo '<div>[' . ucwords($branch['branchName']) . '] <b> Date ' . date('d-m-Y') . '</b></div>';
													}
													?>
												</div>
												<input type="hidden" class="form-control" name="cartId" id="cartId" value="<?= $orderDetails->result()[0]->id ?>">
												<input type="hidden" class="form-control" name="custName" id="custName" value="<?= $orderDetails->result()[0]->custName ?>">
												<input type="hidden" class="form-control" name="custPhone" id="custPhone" value="<?= $orderDetails->result()[0]->custPhone ?>">
												<div class="col-md-4 col-12">
													<h6>Sector Zone: <?= $orderDetails->result()[0]->zoneName ?></h6>
												</div>
												<div class="col-md-4 col-12">
													<h6>Table Number: <?= $orderDetails->result()[0]->tableNumber ?></h6>
												</div>
												<hr>
												<div class="col-md-4 col-12">
													<h6>Name : <?= $orderDetails->result()[0]->custName ?></h6>
												</div>
												<div class="col-md-4 col-12">
													<h6>Phone : <?= $orderDetails->result()[0]->custPhone ?></h6>
												</div>

											</div>
										</div>
										<h6 id="myTabs">Products in your cart:</h6>
										<div class="bg-light mb-2">
											<ul class="cartul" style="list-style-type: none">
												<?php
												$cartHtml = '';
												$totalPrice = 0;
												if ($orderDetails) {
													foreach ($orderDetails->result() as $r) {
														$totalPrice = $totalPrice + $r->totalPrice;
														$productImage = base_url() . 'assets/food.png';
														if ($r->productImage != '' && $r->productImage != NULL && file_exists($r->productImage)) {
															$productImage = base_url() . $r->productImage;
														}
														$productName = $r->productEngName;
														$productPrice = $r->productPrice;
														$extraText = '';
														if (!empty(${'combo' . $r->cartPrdId})) {
															$productName = ${'combo' . $r->cartPrdId}->productComboName;
															$productPrice = number_format(${'combo' . $r->cartPrdId}->productComboPrice, 2, '.', '');
														}
														if ($r->isCombo == 1) {
															$extraText .= '<b>Includes: </b><br>';
															if (${'comboprd' . $r->cartPrdId}) {
																foreach (${'comboprd' . $r->cartPrdId}->result() as $cmbPrd) {
																	$extraText .= "<span class='badge bg-warning' style='margin-right:2px;'> " . $cmbPrd->productEngName . "</span>";
																}
															}
														} elseif ($r->addOns != '[]') {
															$extraText = '<b>Addons: </b><br>';
															$extras = json_decode($r->addOns, true);
															foreach ($extras as $extrprd) {
																$extraText .= $extrprd['addonTitle'] . "  " . $extrprd['addonPrice'] . ', ';
															}
														}
														$cartHtml .= '<li id="productDiv' . $r->cartPrdId . '">
																		<input type="hidden" class="form-control" id="cartPrdId' . $r->cartPrdId . '" name="cartPrdId[]" value="' . $r->cartPrdId . '">
																		<input type="hidden" class="form-control" id="cartId' . $r->cartPrdId . '" name="cartId[]" value="' . $r->id . '">';
														if ($r->isCombo == 0) {
															$cartHtml .= '<input type="hidden" class="form-control" id="productCode' . $r->cartPrdId . '" name="productCode[]" value="' . $r->productCode . '">';
														} else {
															$cartHtml .= '<input type="hidden" class="form-control" id="comboproductCode' . $r->cartPrdId . '" name="comboproductCode[]" value="' . $r->productCode . '">';
														}
														$cartHtml .= '<div style="border-bottom:2px solid silver;min-height:115px;margin-bottom:5px"> 
																			<div class="row cartSection">
																				<div class="col-md-2 col-3">
																					<img src="' . $productImage . '" alt="" class="itemImg img-responsive" style="position:absolute" width="80px" height="80px" style="border-radius:20px;">
																				</div>
																				<div class="col-md-7 col-9">
																					<h6>' . $productName . '</h6>
																					<div><span class="mr-2">Qty : <span class="badge bg-success">' . $r->productQty . '</span></span><span class="mr-2">Tax : <span class="badge bg-primary">' . $r->taxAmount . '</span></span></div>
																					<div class="extra">' . rtrim($extraText, ', ') . '</div>
																				</div>
																				<div class="col-md-2 col-6 text-right">
																					<h6>' . $r->totalPrice . '</h6>
																				</div>
																				<div class="col-md-1 col-6">
																					<a class="btn btn-sm btn-danger" onclick="deleteProduct(' . $r->cartPrdId . ')" id="productRemoveBtn' . $r->cartPrdId . '"><i class="fa fa-times"></i></a>
																				</div>
																			</div>
																		</div>	
																	</li>';
													}
												}
												echo $cartHtml;
												?>
											</ul>
										</div>
										<div class="row col-12">
											<div class="col-md-4">
												<h6 id="myTabs">Payment Method:</h6>
												<div id="payment" class=" mycls bg-light">
													<input type="hidden" name="totalPrice" id="totalPrice" value="<?= $totalPrice ?>">
													<select class="form-select " name="payment_method" id="payment_method" required>
														<option value="">Select</option>
														<option value="CASH">CASH</option>
														<option value="CRDR">Credit/Debit Card</option>
														<option value="NET-BANKING">NET-BANKING</option>
														<option value="UPI">UPI</option>
													</select>
												</div>
											</div>
											<div class="col-md-4">
												<h6 id="myTabs">Order type:</h6>
												<div id="payment" class=" mycls bg-light">
													<select class="form-select" name="orderType" id="orderType" required>
														<option value="">Select</option>
														<option value="dine-in">Dine-in</option>
														<option value="drive-thru">Drive Through</option>
														<option value="take-away">Take away</option>
														<option value="delivery">Delivery</option>
													</select>
												</div>
											</div>
											<div class="col-md-4">
												<h6 id="myTabs">Discount:</h6>
												<div id="payment" class=" mycls bg-light">
													<select class="form-select select2" style="width:100%" name="discount" id="discount" onchange="calculateAmount()">
														<option value="0">Select</option>
														<?php
														if ($discount) {
															foreach ($discount->result() as $dis) {
																echo "<option value='" . $dis->discount . "'>" . $dis->discount . "</option>";
															}
														}
														?>
													</select>
												</div>
											</div>
										</div>
										<div class="row mt-2">
											<div class="col-md-12">
												<h6 id="myTabs">Remark(If any):</h6>
												<div id="payment" class=" mycls bg-light">
													<textarea type="text" class="form-control" rows="2" id="remark" name="remark"></textarea>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-4 col-12">
										<div class="maincart" id="checkoutcart">
											<h6>Coupons/Offers:<span id="couponloader" class="d-none"> <i class="fa fa-spinner spin"></i></span></h6>
											<div class="row">
												<div class="col-md-12 col-sm-6 mb-2">
													<input type="text" class="form-control" id="couponSearch" name="couponSearch" value="" onkeyup="couponData()" placeholder="Search here..">
												</div>
											</div>
											<div class="cart">
												<ul class="cartul" id="couponDiv" style="list-style-type: none">
												</ul>
											</div>
											<div class="priclist">
												<div id="loader" class="d-none" style="position: absolute;left: 50%;top: 75%;transform: translate(-50%, -50%);"> <i class="fa fa-2x fa-spinner spin"></i></div>
												<div class="row mainpric bg-light">
													<div class="col-md-6 col-xs-6"><span class="label1"><strong>Subtotal :</strong></span></div>
													<div class="col-md-6 col-xs-6 text-right"><span class="value1" id="subTotalText"></span></div>
													<div class="col-md-6 col-xs-6"><span class="label1"><strong id="discountLabel">Discount :</strong></span></div>
													<div class="col-md-6 col-xs-6 text-right"><span class="value1" id="discountText"></span></div>
													<b style="border-bottom:1px dashed black;margin-top:5px;"></b>
													<div class="col-md-6 col-xs-6"><span class="label1"><strong>Subtotal <label style="font-size:13px;">(after discount)</label>:</strong></span></div>
													<div class="col-md-6 col-xs-6 text-right"><span class="value1" id="subtotalafterdiscountText"></span></div>
													<div class="col-md-6 col-xs-6 d-none"><span class="label1"><strong>Total Tax <label style="font-size:13px;">(without discount)</label>:</strong></span></div>
													<div class="col-md-6 col-xs-6 text-right d-none"><span class="value1" id="totalTax"></span></div>
													<div class="col-md-6 col-xs-6"><span class="label1"><strong>Actual Tax:</strong></span></div>
													<div class="col-md-6 col-xs-6 text-right"><span class="value1" id="actualTax"></span></div>
													<div class="col-md-6 col-xs-6"><span class="label1"><strong>Service Charges :</strong></span></div>
													<div class="col-md-6 col-xs-6 text-right"><span class="value1" id="serviceChargesText"></span></div>
													<b style="border-bottom:1px dashed black;margin-top:5px;"></b>
													<div class="col-md-6 col-xs-6"><span class="label"><strong>Grand Total :</strong></span></div>
													<div class="col-md-6 col-xs-6 text-right"><span class="value" id="grandTotalText"></span></div>
													<div class="col-md-12 frmbtn d-flex justify-content-center">
														<a class="btn btn-success paynow" id="paynowBtn" style="width:100%;font-size: 18px;">Pay Now</a>
													</div>
												</div>
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
	</div>
</div>
<script src="<?= base_url("assets/init_site/checkout.js") ?>"></script>