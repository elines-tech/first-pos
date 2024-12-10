<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Login Pin | <?= AppName ?></title>
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/main/app.css">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/logo/favicon.png" type="image/png">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/shared/iconly.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet" type='text/css'>
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/webix.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <link href="<?php echo base_url() . 'assets/admin/assets/libs/toastr/build/toastr.min.css' ?>" rel="stylesheet">
    <link href="<?php echo base_url() . 'assets/admin/assets/libs/sweetalert2/dist/sweet-alert.css'; ?>" rel="stylesheet">
    <script>
        var base_path = "<?= base_url() ?>";
    </script>
</head>

<body>
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>
    <div class="d-flex align-items-center justify-content-center vh-100">
        <section class="login-form">
            <div class="card">
                <div class="card-content ">
                    <div class="text-center">
                        <img class="text-center image-responsive mt-4" src="<?= base_url() . 'assets/images/logo/logomain.png' ?>">
                        <h2><?= AppName ?></h2>
                        <h3 class="card-title">Reset your login pin</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <form class="form" action="<?= base_url('Cashier/Login/updateLoginPin') ?>" method="post" enctype="multipart/form-data" data-parsley-validate>
                                    <?php
                                    if (($this->session->flashdata('message_duplicate_login_pin'))) {
                                        echo '<span class="text-center text-danger">' . $this->session->flashdata('message_duplicate_login_pin') . '</span>';
                                        unset($_SESSION['message_duplicate_login_pin']);
                                    }
                                    ?>
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group mandatory">
                                                <label class="form-label">Login Pin</label>
                                                <input type="hidden" name="code" value="<?= $code ?>" readonly class="form-control">
                                                <input type="hidden" name="cmpcode" value="<?= $cmpcode ?>" readonly class="form-control">
                                                <input type="hidden" name="token" value="<?= $token ?>" readonly class="form-control">
                                                <input type="text" id="loginpin" class="form-control" placeholder="Login Pin" name="loginpin" value="<?= $loginpin ?>">
                                            </div>
                                            <?= form_error('loginpin', '<small class="text-danger">', '</small>'); ?>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <input type="submit" class="btn btn-success white me-3 mb-1 sub_1 w-100" name="btnSubmit" value="Update Pin">
                                        </div>
                                    </div>
                                    <div class="row mt-5">
                                        <div class="col-md-10">
                                            <p class="text-inverse text-left m-b-0">Thank you.</p>
                                            <p class="text-inverse text-left"><a href="<?php echo base_url(); ?>Cashier/login"><b class="f-w-600">Back
                                                        to Login</b></a></p>
                                        </div>
                                        <div class="col-md-2">
                                            <!-- <img src="<?= base_url() . 'assets/images/logo/logomain.png" alt="can_info_logo' ?>" class="img-fluid">-->
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script src="<?= base_url() ?>assets/js/bootstrap.js"></script>
    <script src="<?= base_url() ?>assets/js/app.js"></script>
    <script src="<?= base_url() ?>assets/extensions/jquery/jquery.min.js"></script>    
    <script src="<?= base_url() ?>assets/extensions/parsleyjs/parsley.min.js"></script>
    <script src="<?= base_url() ?>assets/js/pages/parsley.js"></script>
    <script src="<?php echo base_url() . 'assets/admin/assets/libs/toastr/build/toastr.min.js'; ?>"></script>
    <script src="<?php echo base_url() . 'assets/admin/assets/libs/sweetalert2/dist/sweet-alert.min.js'; ?>"></script>
    <script>
        $(window).on('load', function() { // makes sure the whole site is loaded 
            $('#status').fadeOut(); // will first fade out the loading animation 
            $('#preloader').delay(250).fadeOut('slow'); // will fade out the white DIV that covers the website. 
            $('body').delay(250).css({
                'overflow': 'visible'
            });
        });
    </script>
</body>

</html>