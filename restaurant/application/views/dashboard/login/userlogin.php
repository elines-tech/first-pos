<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login | <?= AppName ?></title>
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/logo/favicon.png" type="image/png">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/main/app.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/shared/iconly.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
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

<body>
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>
    <div class="d-flex align-items-center justify-content-center vh-100">
        <section class="login-form col-md-6 col-lg-4"> 
            <div class="card">
                <div class="card-content">
                    <center>
                        <img class="text-center image-responsive mt-4" src="<?= base_url() . 'assets/images/logo/logomain.png' ?>">
                        <h2><?= AppName ?></h2>
                    </center>
                    <h3 class="card-title">User Login</h3>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <form class="form" action="<?= base_url('Authentication/user_login_with_pin') ?>" method="post" enctype="multipart/form-data" data-parsley-validate>
                                    <?php
                                    echo "<div class='text-danger text-center' id='error_message'>";
                                    if (isset($error_message)) {
                                        echo $error_message;
                                    }
                                    echo "</div>";
                                    ?>
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group mandatory">
                                                <label for="arabicname-column" class="form-label">Username</label>
                                                <input type="text" id="username" class="form-control" placeholder="Username" name="username" data-parsley-required="true">
                                            </div>
                                            <?php echo form_error('username', '<small class="text-danger">', '</small>'); ?>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group mandatory">
                                                <label for="arabicname-column" class="form-label">Login Pin</label>
                                                <input type="number" id="loginpin" class="form-control" placeholder="Login Pin" name="loginpin" data-parsley-required="true">
                                            </div>
                                            <?php echo form_error('loginpin', '<small class="text-danger">', '</small>'); ?>
                                        </div>
                                    </div>
                                    <div class="row m-t-25 text-left">
                                        <div class="col-sm-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="customCheck1">
                                                <label class="form-check-label" for="customCheck1">
                                                    Remember me
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="forgot-phone text-right f-right">
                                                <a href="<?= base_url() ?>UserLogin/reset" class="text-right f-w-600"> Forgot
                                                    Login Pin?</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group text-center">
                                        <div class="col-xs-12">
                                            <?php
                                            if (isset($logout_message)) {
                                                echo "<div class='text-success'>";
                                                echo $logout_message;
                                                echo "</div>";
                                            }
                                            ?>
                                            <?php if ($this->session->flashdata('message')) : ?>
                                                <div class='text-danger'><?php echo $this->session->flashdata('message');
                                                                            unset($_SESSION['message']); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <input type="submit" class="btn btn-success white me-3 mb-1 sub_1 w-100" name="btnSubmit" value="Login">
                                        </div>
                                    </div>
                                    <div class="row mt-5">
                                        <div class="col-md-10">
                                            <p class="text-inverse text-left m-b-0">Thank you.</p>
                                            <p class="text-inverse text-left"><a href="#"><b class="f-w-600">Back
                                                        to website</b></a></p>
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

        //For Store Credentials
        $(function() {
            $('#username').change(function() {
                // $('#customCheck1').removeAttr('checked');
                $("#customCheck1").prop("checked", false);
            });
            $('#password').change(function() {
                // $('#customCheck1').removeAttr('checked');
                $("#customCheck1").prop("checked", false);
            });

            if (localStorage.chkbox && localStorage.chkbox != '') {
                $('#customCheck1').attr('checked', 'checked');
                $('#username').val(localStorage.username);
                $('#password').val(localStorage.pass);
            } else {
                $('#customCheck1').removeAttr('checked');
                $('#username').val('');
                $('#password').val('');
            }

            $('#customCheck1').click(function() {
                if ($('#customCheck1').is(':checked')) {
                    // save username and password
                    localStorage.username = $('#username').val();
                    localStorage.pass = $('#password').val();
                    localStorage.chkbox = $('#customCheck1').val();
                } else {
                    localStorage.username = '';
                    localStorage.pass = '';
                    localStorage.chkbox = '';
                }
            });
        });
    </script>
</body>

</html>