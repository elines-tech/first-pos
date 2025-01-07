<nav class="navbar navbar-light">
    <div class="container d-block">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last"><a href="<?php echo base_url(); ?>Supplier/listrecords"><i id="exitButton" class="fa fa-times fa-2x"></i></a></div>
        </div>
    </div>
</nav>

<?php include '../restaurant/config.php'; ?>


<div class="container">

    <!-- // Basic multiple Column Form section start -->
    <section id="multiple-column-form" class="mt-5">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?php echo $translations['View Supplier']?></h3>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 col-12">

                                    <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                        <div class="card card-custom gutter-b bg-white border-0">
                                            <div class="card-body">
                                                <h3 class="mt-0 header-title lng text-center"><?php echo $translations['Image']?></h3>

                                                <div class="col-md-12 col-sm-6 col-xs-6 mb-2 p-0 text-center">
                                                    <?php if ($supplierData[0]['supplierImage']  != "") { ?>
                                                        <img class="img-thumbnail mb-2" width="120px" id="logo_icon" src="<?= base_url() . $supplierData[0]['supplierImage']  ?>" data-src="">
                                                    <?php } else { ?>
                                                        <img class="img-thumbnail mb-2" width="120px" id="logo_icon" src="https://sub.kaemsoftware.com/development/assets/images/faces/default-img.jpg" data-src="">
                                                    <?php } ?>

                                                </div>
                                                <!-- <div class="col-md-12 col-sm-6 col-xs-6 mb-2 p-0 text-center">
                                            <button type="button" data-val="single" class="btn btn-primary up_media lng" id="upload">Upload Image</button>
                                            <button type="button" class="btn btn-danger lng" key="clear" id="clear_img">Clear</button>
                                        </div>-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9 col-12">
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="supplier-name" class="form-label"><?php echo $translations['Name']?></label>
                                                <input type="text" id="supplier-name" class="form-control" placeholder="Supplier Name" name="suppliername" readonly value="<?= $supplierData[0]['supplierName'] ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="arabicname-column" class="form-label"><?php echo $translations['Arabic Name']?></label>
                                                <input type="text" id="arabicname" class="form-control" placeholder="Arabic Name" name="arabicname" value="<?= $supplierData[0]['arabicName'] ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="company-name" class="form-label"><?php echo $translations['Company Name']?></label>
                                                <input type="text" id="companyname" class="form-control" placeholder="Company Name" name="companyname" value="<?= $supplierData[0]['companyName'] ?>" readonly>
                                            </div>
                                        </div>
										<div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="Phone" class="form-label"><?php echo $translations['Phone']?></label>
                                                <input type="number" id="phone" class="form-control" placeholder="Phone" name="phone" value="<?= $supplierData[0]['phone'] ?>" readonly>
                                            </div>
                                        </div>
                                       
                                    </div>
									<div class="row">
									    <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="email-column" class="form-label"><?php echo $translations['Email']?></label>
                                                <input type="email" id="email" class="form-control" placeholder="Email" name="email" value="<?= $supplierData[0]['email'] ?>" readonly>
                                            </div>
                                        </div>
										<div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="financial" class="form-label"><?php echo $translations['Financial Account']?></label>
                                                <input type="text" id="financial" class="form-control" placeholder="Financial Account" name="financial" value="<?= $supplierData[0]['financialAccount'] ?>" readonly>
                                            </div>
                                        </div>
									</div>
									<div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group">
                                                <label for="addr-column" class="form-label"><?php echo $translations['Address']?></label>
                                                <textarea class="form-control" placeholder="Address" id="address" name="address"><?= $supplierData[0]['address'] ?></textarea>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group row align-items-center">

                                                <label for="Country" class="form-label"><?php echo $translations['Country']?></label>
                                               <?php
                                                      $country = file_get_contents('assets/country.json');	
  													  $items = json_decode($country,true);		
				                                      
													?>
													<select class="form-select select2" name="country" id="country" disabled>
                                                         <?php foreach($items as $item){ 
														     if($item['country']==$supplierData[0]['country']){
														 ?>
														 <option value="<?= $item['country'] ?>" selected ><?= $item['country'] ?></option>
														 <?php }else {?>
														   <option value="<?= $item['country'] ?>"><?= $item['country'] ?></option>
														 <?php } }?>
                                                    </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group row align-items-center">

                                                <label for="State" class="form-label"><?php echo $translations['State']?></label>
                                                <input type="text" id="state" class="form-control" placeholder="State Name" name="state" value="<?= $supplierData[0]['state'] ?>" readonly>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="City" class="form-label"><?php echo $translations['City']?></label>
                                                <input type="text" id="city" class="form-control" placeholder="City" name="city" value="<?= $supplierData[0]['city'] ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="pincode" class="form-label"><?php echo $translations['Postal Code']?></label>
                                                <input type="number" id="pincode" class="form-control" placeholder="Postal Code" name="pincode" value="<?= $supplierData[0]['postalCode'] ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        
                                        <div class="col-md-6 col-12 d-none">
                                            <div class="form-group">
                                                <label for="Tax" class="form-label">Tax (%)</label>
                                                <input type="text" id="tax" class="form-control" placeholder="Tax" name="tax" value="<?= $supplierData[0]['tax'] ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        
                                        <div class="col-md-2 col-12">
                                            <div class="form-group">
                                                <label class="form-label lng" key="status"><?php echo $translations['Status']?></label>
                                                <div class="input-group">
                                                    <?php if ($supplierData[0]['isActive'] == 1) {
                                                        echo " <span class='badge bg-success mt-2'>Active</span>";
                                                    } else {
                                                        echo "<span class='badge bg-danger mt-2'>Inactive</span>";
                                                    }

                                                    ?>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- // Basic multiple Column Form section end -->

</div>