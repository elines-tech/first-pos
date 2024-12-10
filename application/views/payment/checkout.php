<?php
$plandetails = json_decode($tempClient['plandetails'], true);
if (!empty($plandetails)) {
?>
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
    <div id="preloader">
        <div id="loading">
            <img src="<?= base_url("assets/loader.gif") ?>" alt="Loader">
            <h4 id="loadingText">Loading...</h4>
        </div>
    </div>
    <div id="register">
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
                <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <h5> Procced to payment ?</h5>
                            <form>
                                <input type="hidden" name="companyCode" readonly value="<?= $companyCode ?>">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <small>Name</small>
                                        <div><?= $tempClient['name'] ?></div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <small>Company</small>
                                        <div><b><?= $tempClient['companyname'] ?></b></div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <b>Subscription Plan</b>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <small>Subscription</small>
                                        <div><b><?= ucwords($plandetails['duration']) ?></b></div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <small>Type</small>
                                        <div><b><?= ucwords($tempClient['category']) ?></b></div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <small>Total Users</small>
                                        <div><b><?= ($plandetails['defaultUsers'] + $plandetails['addonUsers']) ?></b></div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <small>Total Branches</small>
                                        <div><b><?= ($plandetails['defaultBranches'] + $plandetails['addonBranches']) ?></b></div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <small>Total Payable (incl. taxes)</small>
                                        <div><b><?= $plandetails['finalPrice'] ?></b></div>
                                    </div>
                                    <div class="col-md-12" id="paymentOptions">
                                        <?php
                                        if (!empty($paymentMethods)) {
                                            foreach ($paymentMethods as $key => $value) {
                                                if ($value->PaymentMethodId == 2) {
                                        ?>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" value="<?= $value->PaymentMethodId ?>" checked name="PaymentMethodId" id="payId<?= $value->PaymentMethodId ?>">
                                                        <label class="form-check-label" for="payId<?= $value->PaymentMethodId ?>">
                                                            <?= $value->PaymentMethodEn ?>
                                                        </label>
                                                    </div>
                                        <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </div>
                                    <div class="col-md-12 text-center">
                                        <button type="button" id="generateLink" class="btn btn-outline-primary w-100">PAY NOW</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var generateLink = $("button#generateLink");
        var paynowLink = $("a#paynowLink");
        $(function() {
            $('#preloader').delay(250).fadeOut('slow');
        });
        generateLink.on("click", function(e) {
            e.preventDefault();
            if ($("input[name='PaymentMethodId']").is(":checked")) {
                var PaymentMethodId = $("input[name='PaymentMethodId']").val();
                $.ajax({
                    type: "POST",
                    url: "<?= base_url('payment/generatePaymentLink') ?>",
                    data: {
                        clientId: "<?= $tempClient['id'] ?>",
                        category: "<?= $tempClient['category'] ?>",
                        PaymentMethodId: PaymentMethodId,
                        InvoiceAmount: "<?= $plandetails['finalPrice'] ?>",
                    },
                    dataType: "JSON",
                    beforeSend: function() {
                        $('#preloader').show();
                        generateLink.attr("disabled", true);
                    },
                    success: function(response) {
                        if (response.hasOwnProperty("paymentLink")) {
                            window.location.replace(response.paymentLink);
                        } else {
                            var confirm = confirn("Server failed to process your request. Want to try again?");
                            if (confirm) {
                                generateLink.attr("disabled", false);
                                generateLink.text("Try again..?");
                            }
                        }
                        $('#preloader').delay(250).fadeOut('slow');
                    },
                    error: function() {
                        $("#loadingText").html("Loading...")
                        $('#preloader').delay(250).fadeOut('slow');
                        var confirm = confirn("Server failed to process your request. Please try again later");
                        if (confirm) {
                            generateLink.attr("disabled", false);
                            generateLink.text("Try again..?");
                        }
                    },
                    complete: function() {

                    }
                });
            }
        });
    </script>
<?php
}
?>