<div id="register">
    <header class="header">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <a href="#" class="navbar-brand">
                    <!--<img src="<?= base_url('assets/images/logo/logo-white.svg') ?>" alt="logo" width="60">-->
                    <img src="<?= base_url('assets/images/logo/Group.svg') ?>" style="height:70px" alt="POS Software" />

                </a>
            </div>
        </nav>
    </header>

    <div class="">
        <div class="row py-2 mt-2 align-items-center">

            <div class="col-md-3 pr-lg-3 mb-5 mb-md-0"></div>

            <div class="col-md-6 ml-auto">
                <form action="<?= base_url("register/create") ?>" method="post">
                    <input type="hidden" name="trialPlan" readonly value="1">
                    <input type="hidden" name="plandetails" readonly value="">
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
                                            <label for="category">Subscribe For <span style="color:red">*</span></label>
                                            <select class="form-selectt" id="category" name="category" required tabindex="1">
                                                <option value="">-- Select your business category --</option>
                                                <option value="supermarket" <?= set_select('category', 'supermarket', False) ?>>Supermarket</option>
                                                <option value="restaurant" <?= set_select('category', 'restaurant', False) ?>>Restaurant</option>
                                            </select>
                                            <?php echo form_error('category'); ?>
                                        </div>
                                        <div class="col-12 mb-2">
                                            <label for="companyname">Name of your business <span style="color:red">*</span></label>
                                            <input type="text" class="form-control" tabindex="2" id="companyname" name="companyname" value="<?= set_value('companyname'); ?>" required>
                                            <?php echo form_error('companyname'); ?>
                                        </div>
                                        <div class="col-12 mb-2">
                                            <label for="name">Your Name <span style="color:red">*</span></label>
                                            <input type="text" class="form-control" tabindex="3" id="name" name="name" value="<?= set_value('name'); ?>" required>
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
                                            <label for="email">Email <span style="color:red">*</span></label>
                                            <input type="email" class="form-control" tabindex="6" id="email" name="email" value="<?= set_value('email'); ?>" required>
                                            <?php echo form_error('email'); ?>
                                        </div>
                                        <div class="col-12 mb-2">
                                            <label for="phone">Phone <span style="color:red">*</span></label>
                                            <div class="input-group">
                                                <select name="countrycode" id="countrycode" tabindex="7">
                                                    <option value="+966">+966 (SAR)</option>
                                                    <option value="+971">+971 (UAE)</option>
                                                </select>
                                                <input type="number" class="form-control" tabindex="8" name="phone" id="phone" value="<?= set_value('phone'); ?>" required>
                                            </div>
                                            <?php echo form_error('phone'); ?>
                                        </div>
                                        <div class="col-12 mb-2">
                                            <label for="password">Password <span style="color:red">*</span></label>
                                            <input type="password" class="form-control" tabindex="9" id="password" name="password" required>
                                            <?php echo form_error('password'); ?>
                                        </div>
                                        <div class="col-12 mb-2">
                                            <label for="confrimpassword">Confirm Password <span style="color:red">*</span></label>
                                            <input type="password" class="form-control" tabindex="10" id="confirm_password" name="confirm_password" required>
                                            <?php echo form_error('confirm_password'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" tabindex="17" type="checkbox" value="1" required id="flexCheckDefault">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            I do hereby have read & accept all terms and conditions while creating the account
                                        </label>
                                    </div>
                                </div>

                                <!--<div class="form-group col-lg-12 mx-auto mb-0 justify-content-center">-->
                                <div class="form-group col-lg-12 d-flex justify-content-center">
                                    <button class="btn btn-primary btn-block py-2" tabindex="18">
                                        <span class="font-weight-bold">Create your account</span>
                                    </button>
                                </div>

                                <div class="form-group col-lg-12 mx-auto d-flex align-items-center my-4">
                                    <div class="border-bottom w-100 ml-5"></div>
                                    <span class="px-2 small text-muted font-weight-bold text-muted">OR</span>
                                    <div class="border-bottom w-100 mr-5"></div>
                                </div>
                                <div class="text-center w-100">
                                    <p class="text-muted font-weight-bold">Already Registered? </p>
                                    <a href="<?= base_url('supermarket/login') ?>" id="supermarket" class="text-primary mx-2">Supermarket Login</a>
                                    <a href="<?= base_url('restaurant/login') ?>" id="resturant" class="text-danger mx-2">Restaurant Login</a>
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