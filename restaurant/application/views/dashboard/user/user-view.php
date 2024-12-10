<nav class="navbar navbar-light">
    <div class="container d-block">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <a href="<?php echo base_url(); ?>Users/listrecords"><i class="fa fa-times fa-2x"></i></a>
            </div>
        </div>
    </div>
</nav>
<div class="container">
    <section id="multiple-column-form" class="mt-5">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">View User</h3>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group">
                                                <label for="product-name" class="form-label">Branch Name</label>
                                                <select class="form-control" name="branchname" id="branchname" disabled>
                                                    <option value="">Select Branch</option>
                                                    <?php if ($branchdata) {
                                                        foreach ($branchdata->result() as $branch) {
                                                            $selected = $userData[0]['userBranchCode'] == $branch->code ? 'selected' : '';
                                                            echo '<option value="' . $branch->code . '"' . $selected . '>' . $branch->branchName . '</option>';
                                                        }
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group">
                                                <label for="arabicname-column" class="form-label">User Name</label>
                                                <input type="text" id="username" class="form-control" placeholder="User Name" name="username" value="<?= $userData[0]['userName'] ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
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
                                        <div class="col-md-12 col-12">
                                            <div class="form-group mandatory">
                                                <label for="invoicePreference" class="form-label">Bill Print Preference</label>
                                                <select class="form-select" name="invoicePreference" id="invoicePreference">
                                                    <option value="">Select Language</option>
                                                    <option value="invoice" <?= set_select('invoicePreference', 'invoice', False) ?>>Invoice</option>
                                                    <option value="autocut" <?= set_select('userlanguage', 'autocut', False) ?>>Auto Cut Bill</option>
                                                </select>
                                            </div>
                                            <?php echo form_error('invoicePreference', '<span class="error text-danger text-right">', '</span>'); ?>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group">
                                                <label for="arabicname-column" class="form-label">User Employee Number</label>
                                                <input type="number" id="userempnumber" class="form-control" placeholder="User Employee Number" name="userempnumber" value="<?= $userData[0]['userEmpNo'] ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group">
                                                <label for="arabicname-column" class="form-label">User Email</label>
                                                <input type="email" id="useremail" class="form-control" placeholder="Email" name="useremail" value="<?= $userData[0]['userEmail'] ?>" readonly>
                                            </div>
                                            <div id="emailDuplicate" style="color:#e66060;"></div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group">
                                                <label for="product-name" class="form-label">User Role</label>
                                                <select class=" form-select" name="userrole" id="userrole" disabled>
                                                    <option value="">Select Role</option>
                                                    <?php if ($roledata) {
                                                            foreach ($roledata->result() as $role) {
                                                                $selected = $userData[0]['userRole'] == $role->code ? 'selected' : '';
                                                                echo '<option value="' . $role->code . '"' . $selected . '>' . $role->role . '</option>';
                                                            }
                                                        } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div id="loginPinDetails" style="display:<?= $userData[0]['userRole'] == 'R_6' ? '' : 'none' ?>">
                                            <div class="col-md-12 col-12">
                                                <div class="form-group mandatory">
                                                    <label class="form-label">Login Pin</label>
                                                    <input type="text" id="loginpin" value="<?= $userData[0]['loginpin'] ?>" class="form-control" placeholder="Login Pin" name="loginpin" readonly>
                                                </div>
                                                <?php echo form_error('loginpin', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Image</label>
                                                <div class="col-md-5 col-sm-6 col-xs-6 mb-2 p-0 text-left">
                                                    <?php if ($userData[0]['userImage'] != "") { ?>
                                                        <img class="img-thumbnail mb-2" width="120px" id="userimg" src="<?= base_url() . $userData[0]['userImage'] ?>" data-src="">
                                                    <?php } else { ?>
                                                        <img class="img-thumbnail mb-2" width="120px" id="userimg" src="/assets/images/faces/default-img.jpg" data-src="">
                                                    <?php } ?>


                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12 mb-3">
                                            <div class="form-group row">
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
                                <div class="col-sm-3"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- // Basic multiple Column Form section end -->
</div>