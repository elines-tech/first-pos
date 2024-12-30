<nav class="navbar navbar-light">
    <div class="container d-block">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last"><a href="<?php echo base_url(); ?>inwardReturn/listRecords"><i id="exitButton" class="fa fa-times fa-2x"></i></a></div>

        </div>
    </div>
</nav>


<div class="container">
    <section id="multiple-column-form" class="mt-5">
        <div class="row match-height">
            <div class="col-18">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">View Inward</h3>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form id="inwardForm" class="form" method="post" enctype="multipart/form-data" data-parsley-validate>
								<?php if($returnData) {
									$result = $returnData->result_array()[0];
								?>
                                <div class="row">
								    <div class="col-md-12 col-12">
										<div class="row">
											<div class="col-md-3 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="" class="form-label">Inward No</label>
                                                    <input type="text" disabled id="inwardNo" class="form-control" name="inwardNo" disabled  value="<?=$result['inwardCode']?>" required>
                                                </div>
                                            </div>
											<div class="col-md-3 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="" class="form-label">Inward Date</label>
                                                    <input type="date" id="inwardDate" class="form-control" name="inwardDate" value="<?= date('Y-m-d' ,strtotime($result['inwardDate']))?>" disabled >
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-12">
												<div class="form-group mandatory">
													<label for="product-name" class="form-label">Branch</label>
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
											 <div class="col-md-3 col-12">
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
											<div class="col-md-12 col-12">
												<table id="pert_tbl" class="table table-sm table-stripped" style="width:100%;">
													<thead>
														<tr>
															<th width="15%">Return Date</th>
															<th width="35%">Item Name</th>
															<th width="15%">Return Quantity</th>
														</tr>
													</thead>
													<tbody>
														<?php 
													$i=0;
													if($returnData){
														foreach($returnData->result_array() as $co){
															$i++;
													?>
													<tr id="row<?= $i?>">
														<td>
															<input type="datetime" class="form-control" disabled name="returnDate<?= $i ?>" id="returnDate<?= $i ?>" value="<?= date('d/m/Y h:i A',strtotime($co['addDate']))?>">
														</td>
														<td>
															<select class="form-select select2" style="width:100%" disabled id="itemCode<?= $i?>" name="itemCode<?= $i?>">
															<option value="">Select Item</option>
																<?php 
																	if($items){
																		foreach($items->result() as $it){
																			if($it->code==$co['itemCode']){
																				echo "<option value='".$it->code."' selected >".$it->itemEngName.' - '.$co['unitName']."</option>";
																			}else{
																				echo "<option value='".$it->code."'>".$it->itemEngName.' - '.$co['unitName']."</option>";
																			}
																		}
																	}
																?>
															</select>
														</td>
														<td>
															<input type="text" class="form-control" disabled name="returnQty<?= $i ?>" id="returnQty<?= $i ?>" value="<?= $co['returnQty']?>" onkeypress="return isNumber(event)" onkeyup="calculate_subTotal(<?= $i?>)">
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
