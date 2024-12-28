<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superadmin | <?= AppName ?></title>
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/main/app.css">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/logo/favicon.png" type="image/png">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/shared/iconly.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet" type='text/css'>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <link href="<?php echo base_url() . 'assets/admin/assets/libs/toastr/build/toastr.min.css' ?>" rel="stylesheet">
    <link href="<?php echo base_url() . 'assets/admin/assets/libs/sweetalert2/dist/sweet-alert.css'; ?>" rel="stylesheet">
    <script>
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            document.querySelectorAll('#table1_length, #table1_filter,#table1_info,#table1_paginate')
                .forEach(img => img.remove());
            window.print();

            document.body.innerHTML = originalContents;
        }

        var fadeElements = document.getElementsByClassName('scrollFade');

        function scrollFade() {
            var viewportBottom = window.scrollY + window.innerHeight;

            for (var index = 0; index < fadeElements.length; index++) {
                var element = fadeElements[index];
                var rect = element.getBoundingClientRect();

                var elementFourth = rect.height / 4;
                var fadeInPoint = window.innerHeight - elementFourth;
                var fadeOutPoint = -(rect.height / 2);

                if (rect.top <= fadeInPoint) {
                    element.classList.add('scrollFade--visible');
                    element.classList.add('scrollFade--animate');
                    element.classList.remove('scrollFade--hidden');
                } else {
                    element.classList.remove('scrollFade--visible');
                    element.classList.add('scrollFade--hidden');
                }

                if (rect.top <= fadeOutPoint) {
                    element.classList.remove('scrollFade--visible');
                    element.classList.add('scrollFade--hidden');
                }
            }
        }

        document.addEventListener('scroll', scrollFade);
        window.addEventListener('resize', scrollFade);
        document.addEventListener('DOMContentLoaded', function() {
            scrollFade();
        });

        var base_path = "<?= base_url() ?>";
    </script>
</head>

<body id="login">
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="col-sm-6 col-md-4">
            <section class="login-form">
                <div class="row match-height">
                    <div class="card">
                        <div class="card-content">
                            <center>
                                <!--<img class="text-center image-responsive mt-4" src="<?= base_url() . 'assets/images/logo/logomain.png' ?>">-->
                                <img class="text-center image-responsive mt-4" src="<?= base_url() . 'assets/images/logo/Group.svg' ?>">
                                <h2><?= AppName ?></h2>
                            </center>
                            <h3 class="card-title">Reset your password</h3>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <form class="form" action="<?= base_url('login/updatePassword') ?>" method="post" enctype="multipart/form-data" data-parsley-validate>
                                            <?php
                                            if (($this->session->flashdata('message'))) {
                                                echo '<span class="text-center text-danger">' . $this->session->flashdata('message') . '</span>';
                                            }
                                            ?>
                                            <div class="row">
                                                <div class="col-md-12 col-12">
                                                    <div class="form-group mandatory">
                                                        <label class="form-label">New Password</label>
                                                        <input type="hidden" name="code" value="<?= $code ?>" readonly class="form-control">
                                                        <input type="hidden" name="token" value="<?= $token ?>" readonly class="form-control">
                                                        <input type="password" id="password" class="form-control" placeholder="Password" name="password" data-parsley-required="true">
                                                    </div>
                                                    <?= form_error('password', '<small class="text-danger">', '</small>'); ?>
                                                </div>
                                                <div class="col-md-12 col-12">
                                                    <div class="form-group mandatory">
                                                        <label class="form-label">Confirm Password Password</label>
                                                        <input type="password" id="confirmPassword" class="form-control" placeholder="Confirm Password" name="confirmPassword" data-parsley-required="true">
                                                    </div>
                                                    <?= form_error('confirmPassword', '<small class="text-danger">', '</small>'); ?>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-12 text-center">
                                                    <input type="submit" class="btn btn-success w-25" name="btnSubmit" value="Update Password">
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-10">
                                                    <!--<p class="text-inverse text-left m-b-0">Thank you.</p>-->
                                                    <p class="text-inverse text-left"><a href="<?php echo base_url(); ?>login"><b class="f-w-600">Back
                                                                to Login</b></a></p>
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