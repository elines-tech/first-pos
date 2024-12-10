<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Upgrade Plan | <?= AppName ?></title>
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/main/app.css">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/logo/favicon.png" type="image/png">
    <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet" type='text/css'>
    <style>
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 100;
        }
    </style>
</head>

<body>
    <div id="preloader">
        <div id="loading">
            <img src="<?= base_url("assets/loader.gif") ?>" alt="Loader">
            <h4 id="loadingText">Loading...</h4>
        </div>
    </div>
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="col-md-6">
            <div>
                <h2><?= AppName ?></h2>
                <p>Plan Upgrade</p>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4> Plan Upgrade </h4>
                </div>
                <div class="card-body">
                    <p> Add-On Branches <b><?= $newBranches ?></b></p>
                    <p> Add-On Users <b><?= $newUsers ?></b></p>
                    <h4> Payable Amount <b><?= $finalPrice ?></b></h4>
                    <p>Please the payment options form below</p>
                    <div class="col-md-12" id="paymentOptions">
                        <?php
                        if (!empty($paymentMethods)) {
                            foreach ($paymentMethods as $key => $value) {
                                if ($value->PaymentMethodId == 2 || $value->PaymentMethodId == "2") {
                        ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" checked value="<?= $value->PaymentMethodId ?>" name="PaymentMethodId" id="payId<?= $value->PaymentMethodId ?>">
                                        <label class="form-check-label" for="payId<?= $value->PaymentMethodId ?>">
                                            <img src="<?= $value->ImageUrl ?>" alt="" style="width:50px;"> <?= $value->PaymentMethodEn ?>
                                        </label>
                                    </div>

                        <?php
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="mb-2">
                        <div type="button" class="btn btn-primary w-100" id="generateLink"> Pay Now? </div>
                        <div id="paylink"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script type="text/javascript">
        $(window).on('load', function() { // makes sure the whole site is loaded 
            $('#status').fadeOut(); // will first fade out the loading animation 
            $('#preloader').delay(250).fadeOut('slow'); // will fade out the white DIV that covers the website. 
            $('body').delay(250).css({
                'overflow': 'visible'
            });
        });
        $(document).on("click", "div#generateLink", function(e) {
            var btn = $(this);
            if ($("input[name='PaymentMethodId']").is(":checked")) {
                var PaymentMethodId = $("input[name='PaymentMethodId']").val();
                $.ajax({
                    type: "POST",
                    url: "<?= base_url('subscriptions/generatePaymentLink') ?>",
                    data: {
                        paymentCode: "<?= $paymentCode ?>",
                        clientId: "<?= $clientId ?>",
                        category: "<?= $category ?>",
                        PaymentMethodId: PaymentMethodId,
                        InvoiceAmount: "<?= $finalPrice ?>",
                    },
                    dataType: "JSON",
                    beforeSend: function() {
                        $("#loadingText").html("Please wait. <br/> Please donot close or go-back to any other window or page.")
                        $('#preloader').show();
                    },
                    success: function(response) {
                        if (response.hasOwnProperty("paymentLink")) {
                            window.location.replace(response.paymentLink);
                        } else {
                            var confirm = confirn("Something went wrong");
                            if (confirm) {
                                window.location.replace("<?= base_url('subscriptions/listRecords') ?>");
                            }
                        }
                    },
                    error: function() {
                        $("#loadingText").html("Loading...")
                        $('#preloader').delay(250).fadeOut('slow');
                        var confirm = confirn("Server failed to process your request. Please try again later");
                        if (confirm) {
                            window.location.replace("<?= base_url('subscriptions/listRecords') ?>");
                        }
                    },
                    complete: function() {
                        $('#preloader').delay(250).fadeOut('slow');
                    }
                });
            }
        });
    </script>

</body>

</html>