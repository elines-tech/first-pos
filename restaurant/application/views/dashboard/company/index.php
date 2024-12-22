<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Company Details</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><i class="fa fa-dashboard"></i> Dashboard</li>
                            <li class="breadcrumb-item active" aria-current="page">Company Details</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="row match-height">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <form class="form" id="editUserForm" enctype="multipart/form-data" data-parsley-validate method="post" action="<?= base_url("company/update"); ?>">
                                        <?php
                                        echo "<div class='text-danger text-center' id='error_message'>";
                                        if (isset($error_message)) {
                                            echo $error_message;
                                        }
                                        echo "</div>";
                                        $crno = $companyname = $cmpLogo = $code = $cmpRegNo = $cmpTaxNo = $address = "";
                                        if ($companymaster) {
                                            $cmpmaster = $companymaster->result_array()[0];
                                            $companyname = $cmpmaster['companyname'];
                                            $crno = $cmpmaster['crno'];
											$cmplogo=$cmpmaster['cmplogo'];
											$regno=$cmpmaster['regno'];
											$vatno=$cmpmaster['vatno'];
											$address=json_decode($cmpmaster['address']);
											$email=$cmpmaster['email'];
	                                        $code=$cmpmaster['code'];
											$buildingno=$address->buildingNo;
											$streetName=$address->streetName;
											$district=$address->district;
											$city=$address->city;
											$postalCode=$address->postalCode;
											$country=$address->country;
                                        }
                                        ?>
                                        <input type="hidden" id="code" readonly name="code" class="form-control" value="<?= $code ?>">
                                        <div class="row">
                                            <div class="col-md-12 mt-3 d-flex text-center justify-content-center mb-3 items-center">
                                                <div class="form-group col-md-4 text-center justify-content-center items-center">
                                                    <!--label class="form-label">Company Logo</label>-->
                                                    <!--<div class="col-6">-->
                                                        <?php if ($cmplogo != "") { ?>
                                                            <img class="img-thumbnail mb-2" width="120px" id="preview" src="<?= base_url($cmplogo) ?>" data-src="">
                                                        <?php } else { ?>
                                                            <img class="img-thumbnail mb-2" width="120px" id="preview" src="/assets/images/faces/default-img.jpg" data-src="">
                                                        <?php } ?>
                                                    <!--</div>-->
                                                    <input class="form-control" type="file" id="cmpLogo" name="cmpLogo">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group mandatory">
                                                    <label class="form-label" for="companyname">Company Name </label>
                                                    <input type="text" id="companyname" tabindex="1" class="form-control" name="companyname" value="<?= $companyname ?>" data-parsley-required="true" data-parsley-length="[6, 100]">
                                                </div>
                                                <?php echo form_error('companyname', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>
	
                                        </div>                                   
                                        <div class="row">
										    <div class="col-md-6">
											    <div class="form-group mandatory">
                                                    <label for="crno" class="form-label">CR No.</label>
                                                    <input type="text" id="crno" class="form-control" name="crno" tabindex="3" value="<?= $crno ?>" data-parsley-required="true" data-parsley-length="[5, 50]">
                                                </div>
                                                <?php echo form_error('crno', '<span class="error text-danger text-right">', '</span>'); ?>
											</div> 
											<div class="col-md-6">
											    <div class="form-group mandatory">
													<label for="crno" class="form-label">VAT Number</label>
													<input type="number" step="1" class="form-control" tabindex="5" id="vatno" name="vatno" value="<?= $vatno ?>" required>
												</div>
												<?php echo form_error('vatno','<span class="error text-danger text-right">', '</span>'); ?>
											</div>
                                        </div>
										<div class="row">
										     <div>Address</div>
										    <div class="col-md-4">
											   <div class="form-group">
											    <label for="buildingNo">Building No</label>
												<input type="number" step="1" tabindex="6" name="buildingNo" id="buildingNo" value="<?= $buildingno ?>" class="form-control">
											   </div>
												<?php echo form_error('buildingNo','<span class="error text-danger text-right">', '</span>'); ?>
											</div>
										    <div class="col-md-4">
											    <div class="form-group">
												   <label for="streetName">Street Name</label>
													<input type="text" tabindex="7" name="streetName" id="streetName" value="<?= $streetName ?>" class="form-control">
													
												</div>
												<?php echo form_error('streetName','<span class="error text-danger text-right">', '</span>'); ?>
											</div>
											<div class="col-md-4">
											     <div class="form-group">
												      <label for="district">District</label>
                                                      <input type="text" tabindex="8" name="district" id="district" value="<?= $district ?>" class="form-control">
												</div>
												<?php echo form_error('district','<span class="error text-danger text-right">', '</span>'); ?>
											</div>
											<div class="col-md-4">
											     <div class="form-group">
												       <label for="city">City</label>
                                                       <input type="text" tabindex="9" name="city" id="city" value="<?= $city ?>" class="form-control">
												</div>
												<?php echo form_error('city','<span class="error text-danger text-right">', '</span>'); ?>
											</div>
											<div class="col-md-4">
											    <div class="form-group">
												     <label for="postalCode">Postal Code</label>
                                                     <input type="number" step="1" minlength="4" tabindex="10" name="postalCode" id="postalCode" value="<?= $postalCode ?>" class="form-control">
												</div>
												<?php echo form_error('postalCode','<span class="error text-danger text-right">', '</span>'); ?>
											</div>
											<div class="col-md-4">
											    <div class="form-group">
												    <label for="country">Country</label>
													<select tabindex="11" name="country" id="country"  class="form-select">
														<option value="966" <?php if($country=='966'){?>selected<?php }?>>Saudi Arabia</option>
														<option value="971" <?php if($country=='971'){?>selected<?php }?>>United Arab Emirates</option>
													</select>
												</div>
												<?php echo form_error('country','<span class="error text-danger text-right">', '</span>'); ?>
											</div>
										</div>
                                        
                                        <div class="row mt-3">
                                            <div class="col-12 d-flex justify-content-end">
                                                <button id="saveDefault" type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#cmpLogo").change(function() {
            const file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    $("#preview")
                        .attr("src", event.target.result);
                };
                reader.readAsDataURL(file);
            }
        });
        var data = '';
        if (data != '') {
            var obj = JSON.parse(data);
            if (obj.status) {
                toastr.success(obj.message, 'Company', {
                    "progressBar": true
                });
            } else {
                toastr.error(obj.message, 'Company', {
                    "progressBar": true
                });
            }
        }
    });
</script> 