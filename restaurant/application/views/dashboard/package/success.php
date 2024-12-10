<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Successfull | <?= AppName ?></title>
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/main/app.css">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/logo/favicon.png" type="image/png">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/shared/iconly.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet" type='text/css'>
    <style>
        ul {
            list-style: none;
            padding-left: 0rem !important;
        }

        ul li {
            margin-top: 10px;
            margin-bottom: 10px;
            font-size: 1.0rem;
        }

        h3 {
            font-size: 2.0rem;
        }

        h3 small {
            font-size: 0.9rem;
        }

        .card .card-header,
        .card .card-footer {
            border: none !important;
            box-shadow: none !important;
        }
    </style>
</head>

<body>
    <!-- Preloader -->
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>
    <!-- Preloader -->
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="col-md-6 col-lg-4">
            <?php
            $txnId =  $receiptId = $txndate = $status = $expirydate = "";
            $amount = "0.00";
            if ($payments) {
                $payment  = $payments->result()[0];
                $txnId = $payment->paymentId != "" ? $payment->paymentId : "-";
                $receiptId = $payment->receiptId;
                $txndate = date("d/M/y h:i A", strtotime($payment->paymentDate));
                $status = $payment->paymentStatus;
                $expirydate = date("d/M/y h:i A", strtotime($payment->expiryDate));
                $amount = $payment->amount;
            }
            ?>
            <div class="row my-3">
                <div class="col-md-122">
                    <h3><?= AppName ?> Subscription</h3>
                    <h4>Transaction successfull...</h4>
                    <p class="downloadBegins">Page will close in <span max="10" id="remainingTime"></span> seconds automatically. <span class="text-danger">Donot close or press back button.</span></p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12 mb-2">
                            <div>Receipt No</div>
                            <div><b><?= $receiptId ?></b></div>
                        </div>
                        <div class="col-sm-12 mb-2">
                            <div>Amount</div>
                            <div><b><?= $amount ?></b></div>
                        </div>
                        <div class="col-sm-12 mb-2">
                            <div>Txn No</div>
                            <div><b><?= $txnId ?></b></div>
                        </div>
                        <div class="col-sm-12 mb-2">
                            <div>Date</div>
                            <div><b><?= $txndate ?></b></div>
                        </div>
                        <div class="col-sm-12 mb-2">
                            <div>Status</div>
                            <div><b><?= $status ?></b></div>
                        </div>
                        <div class="col-sm-12 mb-2">
                            <div>Plan Expiry Date</div>
                            <div><b><?= $expirydate ?></b></div>
                        </div>
                        <hr>
                        <div class="col-sm-12 mb-2">
                            <small>Please click the button below to logout. Login again and continue using the software hassle free.</small>
                        </div>
                        <div class="col-sm-12 text-center">
                            <a href="<?= base_url("authentication/logout") ?>" class="btn btn-primary w-100">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= base_url() ?>assets/extensions/jquery/jquery.min.js"></script>
    <script src="<?= base_url() ?>assets/js/bootstrap.js"></script>
    <script src="<?= base_url() ?>assets/js/app.js"></script>
    <script>
        $(window).on('load', function() { // makes sure the whole site is loaded 
            $('#status').fadeOut(); // will first fade out the loading animation 
            $('#preloader').delay(250).fadeOut('slow'); // will fade out the white DIV that covers the website. 
            $('body').delay(250).css({
                'overflow': 'visible'
            });
        });
        let remainingTimeElement = document.querySelector("#remainingTime"),
            secondsLeft = 10,
            downloadBegins = document.querySelector(".downloadBegins"),
            downloadDuration = 10;

        const downloadTimer = setInterval(
            () => {
                if (secondsLeft <= 0) {
                    clearInterval(downloadTimer)
                    downloadBegins.style.display = "None"
                    window.location.replace("<?= base_url("authentication/logout") ?>")
                }
                remainingTimeElement.value = secondsLeft
                remainingTimeElement.textContent = secondsLeft
                secondsLeft -= 1
            },
            1000);
    </script>
</body>

</html>