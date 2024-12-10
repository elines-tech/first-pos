<div id="register">
    <!-- Navbar-->
    <header class="header">
        <nav class="navbar navbar-expand-lg navbar-light py-3">
            <div class="container">
                <!-- Navbar Brand -->
                <a href="#" class="navbar-brand">
                    <img src="<?= base_url('assets/images/logo/logo-white.svg') ?>" alt="logo" width="60">
                </a>
            </div>
        </nav>
    </header>

    <div class="container">
        <div class="row py-2 mt-2 align-items-center">
            <!-- For Demo Purpose -->
            <div class="col-md-5 pr-lg-5 mb-5 mb-md-0">
            </div>
            <!-- Registeration Form -->
            <div class="col-md-7 ml-auto">
                <form action="<?= base_url("register/save") ?>" method="post">
                    <input type="hidden" name="trialPlan" readonly value="<?= set_value('freeTrial', $trialPlan); ?>">
                    <input type="hidden" name="plandetails" readonly value="<?= set_value('plandetails', $plandetails); ?>">
                    <div class="card">
                        <div class="card-body p-4">
                            <?php if ($this->session->flashdata("err")) {
                                $msg = $this->session->flashdata("err");
                            ?>
                                <div class="alert alert-danger d-flex align-items-center" role="alert">
                                    <i class="fa fa-checked"></i>
                                    <div> <?= $msg ?></div>
                                </div>
                            <?php
                            }
                            ?>
                            <a class="bkbtn" href="<?= base_url('/') ?>"><i class="bi bi-arrow-left"></i> Back</a>
                            <h3 class="reg-title"><b>Create an Account</b></h3>
                            <div class="btnbrd"></div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            <label for="category">Subscribe For</label>
                                            <input type="text" required name="category" class="form-control" value="<?= $category ?>" readonly>
                                            <?php echo form_error('category'); ?>
                                        </div>
                                        <div class="col-12 mb-2">
                                            <label for="companyname">Name of your business</label>
                                            <input type="text" class="form-control" tabindex="1" id="companyname" name="companyname" value="<?= set_value('companyname'); ?>" required>
                                            <?php echo form_error('companyname'); ?>
                                        </div>
                                        <div class="col-12 mb-2">
                                            <label for="name">Your Name</label>
                                            <input type="text" class="form-control" tabindex="2" id="name" name="name" value="<?= set_value('name'); ?>" required>
                                            <?php echo form_error('name'); ?>
                                        </div> 
                                        <div class="col-12 mb-2">
                                            <label for="crno">CR Number <span style="color:red">*</span></label>
                                            <input type="number" step="1" class="form-control" tabindex="4" id="crno" name="crno" value="<?= set_value('crno'); ?>" required>
                                            <?php echo form_error('crno'); ?>
                                        </div>                                        
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" tabindex="5" id="email" name="email" value="<?= set_value('email'); ?>" required>
                                            <?php echo form_error('email'); ?>
                                        </div>
                                        <div class="col-12 mb-2">
                                            <label for="phone">Phone</label>
                                            <div class="input-group">
                                                <select tabindex="6" name="countrycode" id="countrycode">
                                                    <option value="+966">+966 (SAR)</option>
                                                    <option value="+966">+971 (UAE)</option>
                                                </select>
                                                <input type="number" class="form-control" tabindex="7" name="phone" id="phone" value="<?= set_value('phone'); ?>" required>
                                            </div>
                                            <?php echo form_error('phone'); ?>
                                        </div>
                                        <div class="col-12 mb-2">
                                            <label for="password">Password</label>
                                            <input type="password" class="form-control" tabindex="8" id="password" name="password" required>
                                            <?php echo form_error('password'); ?>
                                        </div>
                                        <div class="col-12 mb-2">
                                            <label for="confrimpassword">Confirm Password</label>
                                            <input type="password" class="form-control" tabindex="9" id="confirmpassword" name="confirmpassword" required>
                                            <?php echo form_error('confirmpassword'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" tabindex="14" type="checkbox" value="1" required id="flexCheckDefault">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            I do here by have read & accept all terms and conditions while creating the account
                                        </label>
                                    </div>
                                </div>
                                <!-- Submit Button -->
                                <div class="form-group col-lg-12 mx-auto mb-0">
                                    <button class="btn btn-primary btn-block py-2">
                                        <span class="font-weight-bold">Create your account</span>
                                    </button>
                                </div>
                                <!-- Divider Text -->
                                <div class="form-group col-lg-12 mx-auto d-flex align-items-center my-4">
                                    <div class="border-bottom w-100 ml-5"></div>
                                    <span class="px-2 small text-muted font-weight-bold text-muted">OR</span>
                                    <div class="border-bottom w-100 mr-5"></div>
                                </div>
                                <!-- Already Registered -->
                                <div class="text-center w-100">
                                    <p class="text-muted font-weight-bold">Already Registered? </p>
                                    <a href="<?= base_url('supermarket/login') ?>" class="text-primary mx-2">Supermarket Login</a>
                                    <a href="<?= base_url('restaurant/login') ?>" class="text-danger mx-2">Restaurant Login</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('input, select').on('focus', function() {
            $(this).parent().find('.input-group-text').css('border-color', '#80bdff');
        });
        $('input, select').on('blur', function() {
            $(this).parent().find('.input-group-text').css('border-color', '#ced4da');
        });
    });
</script>