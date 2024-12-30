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
                            <li class="breadcrumb-item"><a href="../../dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
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
                            <h3>View User <span style="float:right"><a id="cancelDefaultButton" href="<?= base_url() ?>users/listRecords" class="btn btn-sm btn-primary">Back</a></span></h3>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="row">


                                        <div class="col-md-12 col-12 row">
                                            <div class="form-group col-md-12 col-12">
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


                                        <!--<div id="userDetails" style="display:<?= $userData[0]['userRole'] == 'R_5' ? '' : 'none' ?>">-->
                                        <div class="row col-md-12 col-12">

                                            <div class="form-group col-md-6 col-12">
                                                <label for="arabicname-column" class="form-label">Name</label>
                                                <input type="text" id="name" class="form-control" placeholder="Name" name="name" value="<?= $userData[0]['name'] ?>" readonly>
                                            </div>


                                            <div class="form-group col-md-6 col-12">
                                                <label for="arabicname-column" class="form-label">User Employee Number</label>
                                                <input type="number" id="userempnumber" class="form-control" placeholder="User Employee Number" name="userempnumber" value="<?= $userData[0]['userEmpNo'] ?>" readonly>
                                            </div>


                                        </div>
                                        <!--</div>-->



                                        <div class="col-md-12 row col-12">


                                            <div class="form-group col-md-12 col-12" id="userDetails" style="display:<?= $userData[0]['userRole'] == 'R_5' ? '' : 'none' ?>">
                                                <label for="arabicname-column" class="form-label">User Name</label>
                                                <input type="text" id="username" class="form-control" placeholder="User Name" name="username" value="<?= $userData[0]['userName'] ?>" readonly>
                                            </div>


                                            <div class="form-group col-md-12 col-12 d-none mandatory" id="counterDiv">
                                                <label for="product-name" class="form-label">User Counter</label>
                                                <select class=" form-select select2" name="userCounter" disabled id="userCounter" style="width:100%">
                                                    <option value="">Select Counter</option>
                                                    <?php if ($counterdata) {
                                                        foreach ($counterdata->result() as $cnt) {
                                                            $selected = $userData[0]['userCounter'] == $cnt->code ? 'selected' : '';
                                                            echo '<option value="' . $cnt->code . '"  ' . $selected . '>' . $cnt->counterName . '</option>';
                                                        }
                                                    } ?>
                                                </select>
                                            </div>
                                            <?php echo form_error('userCounter', '<span class="error text-danger text-right">', '</span>'); ?>


                                            <script>
                                                var role = '<?= $userData[0]['userRole'] ?>'
                                                if (role == 'R_5') {
                                                    $('#counterDiv').removeClass('d-none');
                                                }
                                            </script>



                                        </div>


                                        <div class="row col-md-12 col-12">
                                            <div class="form-group col-md-12 col-12">
                                                <label for="arabicname-column" class="form-label">User Email</label>
                                                <input type="email" id="useremail" class="form-control" placeholder="Email" name="useremail" value="<?= $userData[0]['userEmail'] ?>" readonly>
                                            </div>
                                            <div id="emailDuplicate" style="color:#e66060;"></div>
                                        </div>



                                        <div class="row col-md-12 col-12">

                                            <div class="form-group col-md-6 col-12">
                                                <label for="arabicname-column" class="form-label">User Language</label>
                                                <select class="form-select" name="userlanguage" id="userlanguage" disabled>
                                                    <option value="">Select Language</option>
                                                    <option value="English" <?= $userData[0]['userLang'] == 'English' ? 'selected' : '' ?>>English</option>
                                                    <option value="Arabic" <?= $userData[0]['userLang'] == 'Arabic' ? 'selected' : '' ?>>Arabic</option>
                                                    <option value="Hindi" <?= $userData[0]['userLang'] == 'Hindi' ? 'selected' : '' ?>>Hindi</option>
                                                    <option value="Urdu" <?= $userData[0]['userLang'] == 'Urdu' ? 'selected' : '' ?>>Urdu</option>
                                                </select>
                                            </div>


                                            <div class="form-group col-md-6 col-12">
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


                                        <div class="row col-md-12 col-12">
                                            <div class="form-group mandatory col-md-6 col-12">
                                                <label for="invoicePreference" class="form-label">Bill Print Preference</label>
                                                <select class="form-select" name="invoicePreference" id="invoicePreference">
                                                    <option value="">Select Language</option>
                                                    <option value="invoice" <?= set_select('invoicePreference', 'invoice', False) ?>>Invoice</option>
                                                    <option value="autocut" <?= set_select('userlanguage', 'autocut', False) ?>>Auto Cut Bill</option>
                                                </select>
                                            </div>
                                            <?php echo form_error('invoicePreference', '<span class="error text-danger text-right">', '</span>'); ?>


                                            <div class="col-md-6 col-12" id="loginPinDetails" style="display:<?= $userData[0]['userRole'] == 'R_5' ? '' : 'none' ?>">
                                                <div class="form-group mandatory">
                                                    <label class="form-label">Login Pin</label>
                                                    <input type="text" id="loginpin" value="<?= $userData[0]['loginpin'] ?>" class="form-control" placeholder="Login Pin" name="loginpin" readonly>
                                                </div>
                                                <?php echo form_error('loginpin', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>

                                        </div>











                                        <!--<div class="col-md-4 col-12 d-none" id="counterDiv">
                                            <div class="form-group mandatory">
                                                <label for="product-name" class="form-label">User Counter</label>
                                                <select class=" form-select select2" name="userCounter" disabled id="userCounter" style="width:100%">
                                                    <option value="">Select Counter</option>
                                                    <?php if ($counterdata) {
                                                        foreach ($counterdata->result() as $cnt) {
                                                            $selected = $userData[0]['userCounter'] == $cnt->code ? 'selected' : '';
                                                            echo '<option value="' . $cnt->code . '"  ' . $selected . '>' . $cnt->counterName . '</option>';
                                                        }
                                                    } ?>
                                                </select>
                                            </div>
                                            <?php echo form_error('userCounter', '<span class="error text-danger text-right">', '</span>'); ?>
                                        </div>


                                        <script>
                                            var role = '<?= $userData[0]['userRole'] ?>'
                                            if (role == 'R_5') {
                                                $('#counterDiv').removeClass('d-none');
                                            }
                                        </script>-->


                                        <!--<div id="loginPinDetails" style="display:<?= $userData[0]['userRole'] == 'R_5' ? '' : 'none' ?>">
                                            <div class="col-md-4 col-12">
                                                <div class="form-group mandatory">
                                                    <label class="form-label">Login Pin</label>
                                                    <input type="text" id="loginpin" value="<?= $userData[0]['loginpin'] ?>" class="form-control" placeholder="Login Pin" name="loginpin" readonly>
                                                </div>
                                                <?php echo form_error('loginpin', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>
                                        </div>-->


                                        <div class="row">

                                            <div class="col-md-12 col-12 mt-3">
                                                <div class="form-group col-md-12 col-12 text-center">
                                                    <!--<label class="form-label">Image</label>-->
                                                    <!--<div class="col-md-5 col-sm-6 col-xs-6 mb-2 p-0 text-left">-->
                                                    <?php if ($userData[0]['userImage'] != "") { ?>
                                                        <img class="img-thumbnail mb-2" width="120px" id="userimg" src="<?= base_url() . $userData[0]['userImage'] ?>" data-src="">
                                                    <?php } else { ?>
                                                        <img class="img-thumbnail mb-2" width="120px" id="userimg" src="/assets/images/faces/default-img.jpg" data-src="">
                                                    <?php } ?>

                                                    <!--</div>-->
                                                </div>
                                            </div>


                                            <div class="col-md-12 col-12 items-center text-center justify-content-center row">
                                                <div class="form-group items-center text-center justify-content-center row">
                                                    <!--<label for="status" class="col-sm-2 col-form-label">Status:</label>-->
                                                    <div class="col-sm-5 items-center text-center justify-content-center">
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