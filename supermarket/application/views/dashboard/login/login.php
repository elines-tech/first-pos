<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | <?= AppName ?></title>
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/main/app.css">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/logo/favicon.png" type="image/png">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/shared/iconly.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet" type='text/css'>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script>
        var base_path = "<?= base_url() ?>";
    </script>
</head>

<body id='login'>
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
                        <h4><?= AppName ?></h4>
                    </div>
                    <h3 class="card-title">Login</h3>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <form class="form" action="<?= base_url('Authentication/user_login_process') ?>" method="post" enctype="multipart/form-data" data-parsley-validate>
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
                                                <label for="arabicname-column" class="form-label">Company Code</label>
                                                <input type="text" id="companyCode" maxlength="7" minlength="7" class="form-control" placeholder="Ex. ABCDE" name="companyCode" data-parsley-required="true">
                                            </div>
                                            <?php echo form_error('username', '<small class="text-danger">', '</small>'); ?>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group mandatory">
                                                <label for="arabicname-column" class="form-label">Username</label>
                                                <input type="text" id="username" class="form-control" placeholder="Username" name="username" data-parsley-required="true">
                                            </div>
                                            <?php echo form_error('username', '<small class="text-danger">', '</small>'); ?>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group mandatory">
                                                <label for="arabicname-column" class="form-label">Password</label>
                                                <input type="password" id="password" class="form-control" placeholder="Password" name="password" data-parsley-required="true">
                                            </div>
                                            <?php echo form_error('password', '<small class="text-danger">', '</small>'); ?>
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
                                                <a href="<?= base_url() ?>Login/reset" class="text-right f-w-600"> Forgot
                                                    Password?</a>
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
                                        <div class="col-md-12 d-flex justify-content-center">
                                            <button type="submit" class="btn btn-primary w-25" name="btnSubmit">Login</button>
                                        </div>
                                        <div id="textButton" class="col-md-12 text-center my-3">
                                            <a href="<?= base_url("Cashier/Login") ?>">Cashier Login?</a>
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
    <script src="<?= base_url() ?>assets/js/webix.js"></script>
    <script src="<?= base_url() ?>assets/extensions/parsleyjs/parsley.min.js"></script>
    <script src="<?= base_url() ?>assets/js/pages/parsley.js"></script>
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