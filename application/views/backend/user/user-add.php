<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>User</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><i class="fa fa-dashboard"></i> Dashboard</li>
                            <li class="breadcrumb-item active" aria-current="page">User</li>
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
                        <h3>Add User <span style="float:right"><a href="<?= base_url()?>users/listRecords" class="btn btn-sm btn-primary">Back</a></span></h3>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                              
                                    <form class="form" method="post" action="<?php echo base_url(); ?>users/save" id="addUserForm" enctype="multipart/form-data" data-parsley-validate="">
                                        <?php
                                        echo "<div class='text-danger text-center' id='error_message'>";
                                        if (isset($error_message)) {
                                            echo $error_message;
                                        }
                                        echo "</div>";
                                        ?>
                                        <div class="row">
                                           
											<div class="col-md-4 col-12">
												<div class="form-group mandatory">
													<label for="arabicname-column" class="form-label">First Name</label>
													<input type="text" id="firstname" value="<?= set_value('firstname') ?>" class="form-control" placeholder="First Name" name="firstname" onkeypress="return  ValidateAlpha(event)"  data-parsley-required="true">
												</div>
												<?php echo form_error('firstname', '<span class="error text-danger text-right">', '</span>'); ?>
										    </div>
											<div class="col-md-4 col-12">
												<div class="form-group mandatory">
													<label for="arabicname-column" class="form-label">Last Name</label>
													<input type="text" id="lastname" value="<?= set_value('lastname') ?>" class="form-control" placeholder="Last Name" name="lastname" onkeypress="return  ValidateAlpha(event)"  data-parsley-required="true">
												</div>
												<?php echo form_error('lastname', '<span class="error text-danger text-right">', '</span>'); ?>
										    </div>
											<div class="col-md-4 col-12">
												<div class="form-group mandatory">
													<label for="arabicname-column" class="form-label">User Name</label>
													<input type="text" id="username" value="<?= set_value('username') ?>" class="form-control" placeholder="User Name" name="username" onkeypress="return  ValidateAlpha(event)"  data-parsley-required="true">
												</div>
												<?php echo form_error('username', '<span class="error text-danger text-right">', '</span>'); ?>
										    </div>	
                                            <div class="col-md-4 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="arabicname-column" class="form-label">User Language</label>
                                                    <select class="form-select select2" name="userlanguage" id="userlanguage" data-parsley-required="true">
                                                        <option value="">Select Language</option>
                                                        <option value="English" <?= set_select('userlanguage', 'English', False) ?>>English</option>
                                                        <option value="Arabic" <?= set_select('userlanguage', 'Arabic', False) ?>>Arabic</option>
                                                        <option value="Hindi" <?= set_select('userlanguage', 'Hindi', False) ?>>Hindi</option>
                                                        <option value="Urdu" <?= set_select('userlanguage', 'Urdu', False) ?>>Urdu</option>
                                                    </select>

                                                </div>
                                                <?php echo form_error('userlanguage', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="arabicname-column" class="form-label">User Employee Number</label>
                                                    <input type="text" id="userempnumber" class="form-control" value="<?= set_value('userempnumber') ?>" placeholder="User Employee Number" name="userempnumber" data-parsley-required="true" onkeypress="return isNumberKey(event)">
                                                </div>
                                                <?php echo form_error('userempnumber', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="arabicname-column" class="form-label">User Email</label>
                                                    <input type="email" id="useremail" class="form-control" placeholder="Email" value="<?= set_value('useremail') ?>" name="useremail" data-parsley-required="true" pattern="^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$" data-parsley-type-message="Valid Email is required">
                                                </div>
                                                <?php echo form_error('useremail', '<span class="error text-danger text-right">', '</span>'); ?>

                                            </div>
											 <div class="col-md-4 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="arabicname-column" class="form-label">Contact Number</label>
                                                    <input type="text" id="contactnumber" class="form-control" placeholder="Contact Number" name="contactnumber"  value="<?= set_value('contactnumber') ?>" onkeypress="return isNumberKey(event)" data-parsley-required="true">
                                                </div>
                                                <?php echo form_error('contactnumber', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="product-name" class="form-label">User Role</label>
                                                    <select class=" form-select select2" name="userrole" id="userrole" data-parsley-required="true">
                                                        <option value="">Select Role</option>
                                                        <?php if ($roledata) {
                                                            foreach ($roledata->result() as $role) { ?>
                                                                <option value="<?= $role->code ?>"<?= set_select('userrole', $role->code, False) ?>><?= $role->role ?></option>
                                                            <?php 
															}
                                                        } ?>
                                                    </select>
                                                </div>
                                                <?php echo form_error('userrole', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>											                  
                                           									   
											<div class="col-md-4 col-12">
												<div class="form-group mandatory">
													<label for="arabicname-column" class="form-label">Password</label>
													<input type="password" id="password" class="form-control" placeholder="Password" name="password">
												</div>
											</div>
											<div class="col-md-4 col-12">
												<div class="form-group mandatory">
													<label for="arabicname-column" class="form-label">Confirm Password</label>
													<input type="password" id="confirmpassword" class="form-control" placeholder="Confirm Password" name="confirmpassword" onchange="checkPasswordMatch();">
												</div>
												<div id="CheckPasswordMatch" style="color:#e66060;"></div>
											</div>							   
                                           							
                                            </div>
											<div class="row">	
												<div class="col-md-4">
                                                    <div class="form-group">												
													 <label class="form-label">Image</label>
														<div class="col-md-5 col-sm-6 col-xs-6 mb-2 p-0 text-left">
															<img class="img-thumbnail mb-2" width="120px" id="userimg" src="../assets/images/faces/default-img.jpg" data-src="">
															<input class="form-control" type="file" id="formFile" name="userImage">
														</div>
													</div>
												</div>
												 <div class="col-md-4 col-12 mb-3">
													<div class="form-group">
														<label for="status" class="form-label">Active:</label>                    
															<div class="checkbox">
																<input type="checkbox" name="isActive" id="isActive" class=" " style="width:25px; height:25px">
															</div>
														</div>
													</div>
                                             </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-success white me-1 mb-1 sub_1" id="submit">Save</button>
                                                <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
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
    <!-- // Basic multiple Column Form section end -->
</div>
</div>
<script type="text/javascript">
    var cnt = 0;

    function isNumberKey(evt) {
        var charCode = evt.which ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) return false;
        return true;
    }

    function ValidateAlpha(evt) {
        var keyCode = evt.which ? evt.which : evt.keyCode;
        if (keyCode > 47 && keyCode < 58) return false;
        return true;
    }

    function checkPasswordMatch() {
        var password = $("#password").val();
        var confirmPassword = $("#confirmpassword").val();
        if (password != confirmPassword) {
            $('#submit').prop('disabled', true);
            $("#CheckPasswordMatch").html("Passwords does not match!");
        } else {
            $('#submit').prop('disabled', false);
            $("#CheckPasswordMatch").html("");
        }
    }
    $(document).ready(function() {
		$("#formFile").change(function() {
            const file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    $("#userimg")
                        .attr("src", event.target.result);
                };
                reader.readAsDataURL(file);
            }
        });

    });
	
	
</script>