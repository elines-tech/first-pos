<nav class="navbar navbar-light">
    <div class="container d-block">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last"><a href="<?php echo base_url(); ?>purchaseReport/list"><i class="fa fa-times fa-2x"></i></a></div>

        </div>
    </div>
</nav>


<div class="container">
    <section id="multiple-column-form" class="mt-5">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">View Purchase</h3>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form id="inwardForm" class="form" method="post" enctype="multipart/form-data" data-parsley-validate>
                                <?php
                                echo "<div class='text-danger text-center' id='error_message'>";
                                if (isset($error_message)) {
                                    echo $error_message;
                                }
                                echo "</div>";
                                ?>
								<?php if($inwardData) {
									$result = $inwardData->result_array()[0];
								?>
                                <div class="row">
								    <div class="col-md-12 col-12">
										<div class="row">
											<div class="col-md-4 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="" class="form-label">Inward</label>
                                                    <input type="date" id="inwardDate" class="form-control" name="inwardDate" id="inwardDate" value="<?= date('Y-m-d' ,strtotime($result['inwardDate']))?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12">
												<div class="form-group mandatory">
													<label for="product-name" class="form-label">Branch</label>
													<input type="hidden" class="form-control" id="inwardCode" name="inwardCode" value="<?= $result['code']?>">
													<select class="form-control" disabled name="branchCode" id="branchCode" data-parsley-required="true" required>
														<option value="">Select</option>
														<?php if ($branch) {
															foreach ($branch->result() as $br) {
																if($result['branchCode']==$br->code){
																	echo '<option value="' . $br->code . '" selected>' . $br->branchName . '</option>';
																}else{
																	echo '<option value="' . $br->code . '">' . $br->branchName . '</option>';	
																}
																
															}
														} ?>
													</select>
												</div>
											</div>
											 <div class="col-md-4 col-12">
												<div class="form-group mandatory">
													<label for="product-name" class="form-label">Supplier</label>
													<select class="form-control" disabled name="supplierCode" id="supplierCode" data-parsley-required="true" required>
														<option value="">Select</option>
														<?php if ($supplier) {
															foreach ($supplier->result() as $sr) {
																if($result['supplierCode']==$sr->code){
																	echo '<option value="' . $sr->code . '" selected>' . $sr->supplierName . '</option>';
																}else{
																	echo '<option value="' . $sr->code . '">' . $sr->supplierName . '</option>';
																}
															}
														} ?>
													</select>
												</div>
											</div>
                                           
										</div>
										<div class="row">
											 <div class="col-md-4 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="" class="form-label">Total</label>
                                                    <input type="text" id="total" class="form-control" required name="total" disabled value="<?= $result['total']?>" required>
                                                </div>
                                            </div>
                                             <div class="col-md-2 col-12 d-none">
                                                <div class="form-group">
                                                    <label class="form-label lng">Active</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend"><span class="input-group-text bg-soft-primary">
                                                            <input type="checkbox" name="isActive" class="form-check-input"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
										<div class="row">
											<div class="col-md-12 col-12">
												<table id="pert_tbl" class="table table-sm table-stripped" style="width:100%;">
													<thead>
														<tr>
															<th width="35%">Item</th>
															<th width="15%">Unit</th>
															<th width="15%">Qty</th>
															<th width="15%">Price</th>
															<th width="15%">Subtotal</th>
														</tr>
													</thead>
													<tbody>
														<?php 
													$i=0;
													if($inwardLineEntries){
														foreach($inwardLineEntries->result_array() as $co){
															$i++;
													?>
													<tr id="row<?= $i?>">
														<td>
															<input type="hidden" class="form-control" name="inwardLineCode<?= $i?>" id="inwardLineCode<?= $i?>" value="<?= $co['code']?>">
															<select class="form-control" disabled id="itemCode<?= $i?>" name="itemCode<?= $i?>" onchange="checkDuplicateItem(<?= $i?>);getItemStorageUnit(<?=$i?>)">
															<option value="">Select Item</option>
																<?php 
																	if($items){
																		foreach($items->result() as $it){
																			if($it->code==$co['itemCode']){
																				echo "<option value='".$it->code."' selected >".$it->itemEngName."</option>";
																			}else{
																				echo "<option value='".$it->code."'>".$it->itemEngName."</option>";
																			}
																		}
																	}
																?>
															</select>
														</td>
														<td>
																<select class="form-control" disabled id="itemUnit<?= $i?>" name="itemUnit<?= $i?>" disabled>
																		<option value=""></option>
																		<?php 
																			if($unitmaster){
																				foreach($unitmaster->result() as $um){
																					if($um->code==$co['itemUom']){
																						echo "<option value='".$um->code."' selected >".$um->unitName."</option>";
																					}else{
																							echo "<option value='".$um->code."'>".$um->unitName."</option>";
																					}
																				}
																			}
																		?>
																	</select>
														</td>
														<td>
															<input type="text" class="form-control" disabled name="itemQty<?= $i ?>" id="itemQty<?= $i ?>" value="<?= $co['itemQty']?>" onkeypress="return isNumber(event)" onkeyup="calculate_subTotal(<?= $i?>)">
														</td>
														<td>
															<input type="text" class="form-control" disabled name="itemPrice<?= $i ?>" id="itemPrice<?= $i ?>" value="<?= $co['itemPrice']?>" onkeypress="return isNumber(event)" onkeyup="calculate_subTotal(<?= $i?>)">
														</td>
														<td>
															<input type="text" class="form-control" name="subTotal<?= $i ?>" id="subTotal<?= $i ?>" disabled value="<?= $co['subTotal']?>">
														</td>
														
													</tr>
														<?php }
													}?>
														
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
