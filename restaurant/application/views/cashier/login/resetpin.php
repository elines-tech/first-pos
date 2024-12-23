<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Reset Pin | <?= AppName ?></title>
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/main/app.css">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/logo/favicon.png" type="image/png">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/shared/iconly.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet" type='text/css'>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <link href="<?php echo base_url() . 'assets/admin/assets/libs/toastr/build/toastr.min.css' ?>" rel="stylesheet">
    <link href="<?php echo base_url() . 'assets/admin/assets/libs/sweetalert2/dist/sweet-alert.css'; ?>" rel="stylesheet">
    <script>
        var base_path = "<?= base_url() ?>";
    </script>
</head>

<body id="login">
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>
    <div class="d-flex align-items-center justify-content-center vh-100">
        <section class="login-form col-md-6 col-lg-4">
            <div class="card">
                <div class="card-content">
                    <div class="text-center">
                        <!--<img class="text-center image-responsive mt-4" src="<?= base_url() . 'assets/images/logo/logomain.png' ?>">-->
                        <img class="text-center image-responsive mt-4" src="<?= base_url() . 'assets/images/logo/Group.svg' ?>">
                        <h2><?= AppName ?></h2>
                        <h3 class="card-title">Recover Your login Pin</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <form class="form" action="<?= base_url('Cashier/Login/recover') ?>" method="post" enctype="multipart/form-data" data-parsley-validate>
                                    <?php
                                    if (($this->session->flashdata('message'))) {
                                        echo '<span class="text-center text-danger">' . $this->session->flashdata('message') . '</span>';
                                        unset($_SESSION['message']);
                                    }
                                    ?>
                                    <?php
                                    if (($this->session->flashdata('message_new'))) {
                                        echo '<span class="text-center text-danger">' . $this->session->flashdata('message_new') . '</span>';
                                        unset($_SESSION['message_new']);
                                    }
                                    ?>
                                    <p>Please enter your email address, futher directions shall be sent to your email...</p>
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group mandatory">
                                                <label for="arabicname-column" class="form-label">Company Code</label>
                                                <input type="text" id="cmpcode" class="form-control" placeholder="Ex. ABCDE" maxlength="7" name="cmpcode" data-parsley-required="true">
                                            </div>
                                            <?php echo form_error('cmpcode', '<small class="text-danger">', '</small>'); ?>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group mandatory">
                                                <label class="form-label">Email</label>
                                                <input type="email" id="userEmail" class="form-control" placeholder="User Email" name="userEmail" data-parsley-required="true">
                                            </div>
                                            <?= form_error('userEmail', '<small class="text-danger">', '</small>'); ?>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12 d-flex justify-content-center">
                                            <input type="submit" class="btn btn-success w-25" name="btnSubmit" value="Send Email">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-10">
                                            <!--<p class="text-inverse text-left m-b-0">Thank you.</p>-->
                                            <p class="text-inverse text-left"><a href="<?php echo base_url('Cashier/login'); ?>"><b class="f-w-600">Back to login</b></a></p>
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