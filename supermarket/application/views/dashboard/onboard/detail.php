<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supermarket | <?= AppName ?></title>
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/main/app.css">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/logo/favicon.png" type="image/png">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/shared/iconly.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet" type='text/css'>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script>
        var base_path = "<?= base_url() ?>";
    </script>
    <style>
        .error_prefix {
            font-size: 0.8rem;
            color: #ff0000;
            width: 100%;
        }
    </style>
</head>

<body id='login'>
    <?php
    $vatno = $regno = $crno = "";
    if ($company) {
        $company = $company->result()[0];
        $vatno = $company->vatno;
        $crno = $company->crno;
    }
    ?>
    <!-- Preloader -->
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>
    <!-- Preloader -->
    <div class="container">
        <div class="row">
            <div class="col-lg-12 my-3">
                <h3><?= AppName ?> <span class="float-end"><a href="<?= base_url("authentication/logout") ?>" class="btn btn-outline-danger">Logout and Exit</a></span></h3>
                <h4>Setup your profile</h4>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <?php
                        if ($subscription) {
                            $subscription = $subscription->result_array()[0];
                        ?>
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <h4 class='text-success'>Your current plan</h4>
                                </div>
                                <div class="col-md-3 text-center">
                                    <h4>Amount</h4>
                                    <div><?= $subscription['amount'] ?></div>
                                </div>
                                <div class="col-md-3 text-center">
                                    <h4>Purchased On</h4>
                                    <div><?= date("d/M/y H:i A", strtotime($subscription['paymentDate'])) ?></div>
                                </div>
                                <div class="col-md-3 text-center">
                                    <h4>Expires On</h4>
                                    <div class="text-danger"><?= date("d/M/y H:i A", strtotime($subscription['expiryDate'])) ?></div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4>Fill your company/organization details to continue...</h4>
                        <form action="<?= base_url("onboard/save") ?>" method="post" enctype="multipart/form-data">
                            <div class="row">
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="col-sm-6 col-md-2">
                                    <img src="<?= base_url("assets/images/profile.png") ?>" data-src="<?= base_url("assets/images/samplelogo.jpg") ?>" id="preview" style="width:100%;object-fit: contain;" alt="Compnay Logo">
                                </div>
                            </div>

                                <div class="col-12 mb-2">
                                    <label for="cmpLogo">Logo</label>
                                    <input type="file" name="cmpLogo" id="cmpLogo" class="form-control">
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="crno">CR Number <span style="color:red">*</span></label>
                                    <input type="number" step="1" class="form-control" tabindex="4" id="crno" name="crno" value="<?= set_value('crno', $crno); ?>" required>
                                    <?php echo form_error('crno'); ?>
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="vatno">VAT Number <span style="color:red">*</span></label>
                                    <input type="number" step="1" class="form-control" tabindex="5" id="vatno" name="vatno" value="<?= set_value('vatno', $vatno); ?>" required>
                                    <?php echo form_error('vatno'); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div>Address</div>
                                <div class="col-md-6 mb-2">
                                    <label for="buildingNo">Building No</label>
                                    <input type="number" step="1" tabindex="11" name="buildingNo" id="buildingNo" value="<?= set_value('buildingNo'); ?>" class="form-control">
                                    <?php echo form_error('buildingNo'); ?>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="streetName">Street Name</label>
                                    <input type="text" tabindex="12" name="streetName" id="streetName" value="<?= set_value('streetName'); ?>" class="form-control">
                                    <?php echo form_error('streetName'); ?>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="district">District</label>
                                    <input type="text" tabindex="13" name="district" id="district" value="<?= set_value('district'); ?>" class="form-control">
                                    <?php echo form_error('district'); ?>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="city">City</label>
                                    <input type="text" tabindex="14" name="city" id="city" value="<?= set_value('city'); ?>" class="form-control">
                                    <?php echo form_error('city'); ?>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="postalCode">Postal Code</label>
                                    <input type="number" step="1" minlength="4" tabindex="15" name="postalCode" id="postalCode" value="<?= set_value('postalCode'); ?>" class="form-control">
                                    <?php echo form_error('postalCode'); ?>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="country">Country</label>
                                    <select tabindex="16" name="country" id="country" value="<?= set_value('country'); ?>" class="form-select">
                                        <option value="966" selected>Saudi Arabia</option>
                                        <option value="971">United Arab Emirates</option>
                                    </select>
                                    <?php echo form_error('country'); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 d-flex justify-content-center">
                                    <button class="btn btn-primary w-20 mt-3">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= base_url() ?>assets/js/bootstrap.js"></script>
    <script src="<?= base_url() ?>assets/js/app.js"></script>
    <script src="<?= base_url() ?>assets/extensions/jquery/jquery.min.js"></script> 
    <script src="<?= base_url() ?>assets/extensions/parsleyjs/parsley.min.js"></script>
    <script src="<?= base_url() ?>assets/js/pages/parsley.js"></script>
    <script>
        $(window).on('load', function() { // makes sure the whole site is loaded 
            $('#status').fadeOut(); // will first fade out the loading animation 
            $('#preloader').delay(250).fadeOut('slow'); // will fade out the white DIV that covers the website. 
            $('body').delay(250).css({
                'overflow': 'visible'
            });
            $("#cmpLogo").change(function() {
                const file = this.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        $("#preview").attr("src", event.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
</body>

</html>