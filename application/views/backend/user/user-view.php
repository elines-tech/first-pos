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
                        <h3>View User <span style="float:right"><a href="<?= base_url()?>users/listRecords" class="btn btn-sm btn-primary">Back</a></span></h3>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                             
                                    <div class="row">
                                        <div class="col-md-4 col-12">
												<div class="form-group mandatory">
													<label for="arabicname-column" class="form-label">First Name</label>
													<input type="text" id="firstname"  class="form-control" placeholder="First Name" name="firstname" readonly value="<?= $userData[0]['firstName'] ?>">
												</div>
												
										    </div>
											<div class="col-md-4 col-12">
												<div class="form-group mandatory">
													<label for="arabicname-column" class="form-label">Last Name</label>
													<input type="text" id="lastname"  class="form-control" placeholder="Last Name" name="lastname" readonly value="<?= $userData[0]['lastName'] ?>">
												</div>
												
										    </div>
                                            <div class="col-md-4 col-12">
                                                    <div class="form-group mandatory">
                                                        <label for="arabicname-column" class="form-label">User Name</label>
                                                        <input type="text" id="username" class="form-control" placeholder="User Name" name="username" value="<?= $userData[0]['username'] ?>" readonly>
                                                    </div>
                                                   
                                            </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label for="arabicname-column" class="form-label">User Language</label>
                                                <select class="form-select" name="userlanguage" id="userlanguage" disabled>
                                                    <option value="">Select Language</option>
                                                    <option value="English" <?= $userData[0]['userLang'] == 'English' ? 'selected' : '' ?>>English</option>
                                                    <option value="Arabic" <?= $userData[0]['userLang'] == 'Arabic' ? 'selected' : '' ?>>Arabic</option>
                                                    <option value="Hindi" <?= $userData[0]['userLang'] == 'Hindi' ? 'selected' : '' ?>>Hindi</option>
                                                    <option value="Urdu" <?= $userData[0]['userLang'] == 'Urdu' ? 'selected' : '' ?>>Urdu</option>
                                                </select>
                                            </div>
                                        </div>
			
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label for="arabicname-column" class="form-label">User Employee Number</label>
                                                <input type="number" id="userempnumber" class="form-control" placeholder="User Employee Number" name="userempnumber" value="<?= $userData[0]['userEmpNo'] ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label for="arabicname-column" class="form-label">User Email</label>
                                                <input type="email" id="useremail" class="form-control" placeholder="Email" name="useremail" value="<?= $userData[0]['userEmail'] ?>" readonly>
                                            </div>
                                            <div id="emailDuplicate" style="color:#e66060;"></div>
                                        </div>
										<div class="col-md-4 col-12">
                                                <div class="form-group">
                                                    <label for="arabicname-column" class="form-label">Contact Number</label>
                                                    <input type="text" id="contactnumber" class="form-control" placeholder="Contact Number" name="contactnumber" value="<?= $userData[0]['contactNumber'] ?>" readonly>
                                                </div>                                               
                                            </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label for="product-name" class="form-label">User Role</label>
                                                <select class=" form-select" name="userrole" id="userrole" disabled>
                                                    <option value="">Select Role</option>
                                                    <?php if ($roledata) {
                                                        foreach ($roledata->result() as $role) {
                                                            $selected = $userData[0]['userRole'] == $role->code ? 'selected' : '';
                                                            echo '<option value="' . $role->code . '"  ' . $selected . '>' . $role->role . '</option>';
                                                        }
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
					
										<div class="row">
										     <div class="col-md-4">
                                                    <div class="form-group">												
													 <label class="form-label">Image</label>
														<div class="col-md-5 col-sm-6 col-xs-6 mb-2 p-0 text-left">
														     <?php if ($userData[0]['profilePhoto'] != "") { ?>
																<img class="img-thumbnail mb-2" width="120px" id="userimg" src="<?= base_url() . $userData[0]['profilePhoto'] ?>" data-src="">
															<?php } else { ?>
																<img class="img-thumbnail mb-2" width="120px" id="userimg" src="<?= base_url() ?>/assets/images/faces/default-img.jpg" data-src="">
															<?php } ?>																													
															
														</div>
													</div>
											</div>
											<div class="col-md-4 col-12 mb-3">
												<div class="form-group">
													<label for="status" class="col-sm-2 col-form-label">Status:</label>
													<div class="col-sm-10">
														<?php if ($userData[0]['isActive'] == 1) {
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
</div>