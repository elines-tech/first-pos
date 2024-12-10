<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configure Plan | <?= AppName ?></title>
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/logo/favicon.png" type="image/png">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/shared/iconly.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/main/app.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
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

        h3 small,
        h4 small {
            font-size: 0.9rem;
        }

        .card .card-header,
        .card .card-footer {
            border: none !important;
            box-shadow: none !important;
        }

        .border-none {
            border: none !important;
            font-weight: 600;
            padding-left: 0px;
        }

        hr {
            width: 100%;
        }

        p {
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <!-- Preloader -->
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>
    <!-- Preloader -->
    <div class="container">
        <div class="row my-3">
            <div class="col-md-12 col-lg-8 offset-lg-2">
                <h3><?= AppName ?> <a href="<?= base_url("authentication/logout") ?>" class="btn btn-outline-danger float-end">Exit</a></h3>
                <h4>Confiure Plan...</h4>
                <small>All prices are inclusive of taxes.</small>
            </div>
        </div>
        <div class="row">
            <?php
            if ($subscription) {
                $subscription = $subscription->result()[0];
            ?>
                <div class="col-md-12 col-lg-8 offset-lg-2">
                    <form action="<?= base_url('package/buy') ?>" method="POST">
                        <div class="card">
                            <div class="card-body">
                                <div class="col-12">
                                    <div class="row ">
                                        <div class="col-sm-12 my-2">
                                            <h5 class="">Base <?= ucwords($duration . 'ly') ?> Plan</h5>
                                            <input type="hidden" readonly name="duration" value="<?= $duration ?>">
                                            <input type="hidden" readonly name="packageCode" value="<?= $planId ?>">
                                            <input type="hidden" readonly name="defaultUsers" value="<?= $subscription->noofusers ?>">
                                            <input type="hidden" readonly name="defaultBranches" value="<?= $subscription->noofbranch ?>">
                                            <input type="hidden" readonly name="multiplyer" value="<?= $duration == "year" ? 12 : 1 ?>">
                                            <input type="hidden" name="finalPrice" id="finalPrice" value="<?= $duration == "year" ? $subscription->yearlychargesincltax : $subscription->monthlychargesincltax ?>" readonly>
                                            <input type="hidden" name="planPrice" id="planPrice" value="<?= $duration == "year" ? $subscription->yearlychargesincltax : $subscription->monthlychargesincltax ?>" readonly>
                                            <input type="hidden" readonly name="costperbranch" value="<?= $subscription->costperbranch ?>" />
                                            <input type="hidden" readonly name="costperuser" value="<?= $subscription->costperuser ?>" />
                                            <input type="hidden" readonly name="taxpercent" value="<?= $subscription->tax ?>" />
                                            <input type="hidden" readonly name="discount" value="0.00" />
                                            <input type="hidden" readonly name="planconfig" value="<?= base64_encode(stripslashes(json_encode($subscription))) ?>">
                                            <input type="hidden" readonly name="basicCharge" value="<?= $duration == "year" ? $subscription->yearlychargesextax : $subscription->monthlychargesextax ?>">
                                            <input type="hidden" readonly name="subtotal" value="<?= $duration == "year" ? $subscription->yearlychargesextax : $subscription->monthlychargesextax ?>">
                                            <?php
                                            $sub = $duration == "year" ? $subscription->yearlychargesextax : $subscription->monthlychargesextax;
                                            $pay = $duration == "year" ? $subscription->yearlychargesincltax : $subscription->monthlychargesincltax;
                                            $taxtotal = number_format($pay - $sub, 2, ".", "");
                                            ?>
                                            <input type="hidden" readonly name="taxtotal" value="<?= $taxtotal ?>">
                                        </div>
                                        <div class="col-sm-12 mb-2">
                                            <h4><?= $duration == "year" ? $subscription->yearlychargesincltax : $subscription->monthlychargesincltax ?><small>/Month</small></h4>
                                            <p>Users : <b><?= $subscription->noofusers ?></b> Branches: <b><?= $subscription->noofbranch ?></b> </p>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="moreusers" name="moreusers">
                                        <label class="form-check-label" for="moreusers">
                                            Want to add some more Users?
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="row">
                                        <div class="col-md-4 mb-2">
                                            <label for="perUserPrice">Each User Price <small>(Inc.Taxes)</small></label>
                                            <input type="number" name="perUserPrice" id="perUserPrice" class="form-control border-none" value="<?= $duration == "year" ? $subscription->yearlyperusercharges : $subscription->monthlyperusercharges ?>" readonly>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label for="addonUsers">Add Users </label>
                                            <div class="input-group mb-3">
                                                <button class="btn btn-sm btn-outline-secondary minususer" type="button"><i class="fa fa-minus"></i></button>
                                                <input type="number" name="addonUsers" readonly class="form-control" placeholder="" aria-label="Example text with two button addons" value="1">
                                                <button class="btn btn-sm btn-outline-secondary plususer" type="button"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label for="addonUserPrice">Total <small>(Inc.Taxes)</small></label>
                                            <input type="text" name="addonUserPrice" id="addonUserPrice" class="form-control border-none" value="0.00">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="morebranches" name="morebranches">
                                        <label class="form-check-label" for="morebranches">
                                            Want to add some more Branches?
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-md-4 mb-2">
                                            <label for="perBranchPrice">Each Branch Price <small>(Inc.Taxes)</small></label>
                                            <input type="number" name="perBranchPrice" id="perBranchPrice" class="form-control border-none" readonly value="<?= $duration == "year" ? $subscription->yearlyperbranchcharges : $subscription->monthlyperbranchcharges ?>">
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label for="addonBranches">Add Branches </label>
                                            <div class="input-group mb-3">
                                                <button class="btn btn-sm btn-outline-secondary minusbranch" type="button"><i class="fa fa-minus"></i></button>
                                                <input type="number" name="addonBranches" readonly class="form-control" placeholder="" aria-label="Example text with two button addons" value="1">
                                                <button class="btn btn-sm btn-outline-secondary plusbranch" type="button"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label for="addonBranchPrice">Total <small>(Inc.Taxes)</small></label>
                                            <input type="text" name="addonBranchPrice" id="addonBranchPrice" class="form-control border-none" value="0.00">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="">Final Price </label>
                                    <h3 id="fpText"><?= $duration == "year" ? $subscription->yearlychargesincltax : $subscription->monthlychargesincltax ?></h3>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <button class="btn btn-primary w-100">Checkout Now?</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
    <script src="<?= base_url() ?>assets/js/bootstrap.js"></script>
    <script src="<?= base_url() ?>assets/js/app.js"></script>
    <script src="<?= base_url() ?>assets/extensions/jquery/jquery.min.js"></script>
    <script src="<?= base_url() ?>assets/extensions/parsleyjs/parsley.min.js"></script>
    <script>
        $(window).on('load', function() { // makes sure the whole site is loaded 
            $('#status').fadeOut(); // will first fade out the loading animation 
            $('#preloader').delay(250).fadeOut('slow'); // will fade out the white DIV that covers the website. 
            $('body').delay(250).css({
                'overflow': 'visible'
            });
        });

        function calculation() {
            var duration = $("input[name='duration']").val();
            var multiplyer = $("input[name='multiplyer']").val();
            var planPrice = Number($("input[name='planPrice']").val());

            var perUserPrice = Number($("input[name='perUserPrice']").val());
            var addonUsers = Number($("input[name='addonUsers']").val());
            var addonUserPrice = $("input[name='addonUserPrice']");

            var perBranchPrice = Number($("input[name='perBranchPrice']").val());
            var addonBranches = Number($("input[name='addonBranches']").val());
            var addonBranchPrice = $("input[name='addonBranchPrice']");

            var costperuser = Number($("input[name='costperuser']").val());
            var costperbranch = Number($("input[name='costperbranch']").val());
            var basicCharge = Number($("input[name='basicCharge']").val());
            var subtotal = Number($("input[name='subtotal']").val());
            var taxtotal = Number($("input[name='taxtotal']").val());

            var a = Number(0);
            var b = Number(0);

            var finalPrice = $("input[name='finalPrice']");
            if ((!$("input[id='moreusers'").is(":checked")) && (!$("input[id='morebranches'").is(":checked"))) {
                addonUserPrice.val("0.00");
                addonBranchPrice.val("0.00");
                $("input[name='addonUsers']").val(1);
                $("input[name='addonBranches']").val(1);
            } else {
                if ($("input[id='moreusers'").is(":checked")) {
                    addonUserPrice.val((perUserPrice * addonUsers).toFixed(2));
                    if (duration == "year")
                        a = (12 * costperuser * addonUsers).toFixed(2);
                    else
                        a = (1 * costperuser * addonUsers).toFixed(2);
                } else {
                    addonUserPrice.val("0.00");
                    $("input[name='addonUsers']").val(1);
                }
                if ($("input[id='morebranches'").is(":checked")) {
                    addonBranchPrice.val((perBranchPrice * addonBranches).toFixed(2));
                    if (duration == "year")
                        b = (12 * costperuser * addonBranches).toFixed(2);
                    else
                        b = (1 * costperuser * addonBranches).toFixed(2);
                } else {
                    addonBranchPrice.val("0.00");
                    $("input[name='addonBranches']").val(1);
                }
            }

            var subtotal = (Number(basicCharge) + Number(a) + Number(b)).toFixed(2);
            var p = planPrice;
            var pu = Number(addonUserPrice.val());
            var pb = Number(addonBranchPrice.val());
            var final = (p + pu + pb).toFixed(2);
            var totalTax = (final - subtotal).toFixed(2);

            console.log("SubTotal =>", subtotal);
            console.log("TotalTax =>", totalTax);
            console.log("Final =>", final);

            var pu = Number(addonUserPrice.val());
            var pb = Number(addonBranchPrice.val());

            $("input[name='subtotal']").val(subtotal);
            $("input[name='taxtotal']").val(totalTax);
            finalPrice.val(final);
            $("h3[id='fpText']").text(final);
        }

        $(document).on("change", "#moreusers", function(e) {
            if ($(this).is(":checked")) {
                $("button.minususer").removeAttr("disabled");
                $("button.plususer").removeAttr("disabled");
            } else {
                $("button.minususer").attr("disabled", true);
                $("button.plususer").attr("disabled", true);
            }
            calculation()
        });

        $(document).on("change", "#morebranches", function(e) {
            if ($(this).is(":checked")) {
                $("button.minusbranch").removeAttr("disabled");
                $("button.plusbranch").removeAttr("disabled");
            } else {
                $("button.minusbranch").attr("disabled", true);
                $("button.plusbranch").attr("disabled", true);
            }
            calculation()
        });


        $(document).on("click", "button.minususer", function(e) {
            if ($("input[name='moreusers']").is(":checked")) {
                var count = Number($("input[name='addonUsers']").val());
                if (count === 1) {
                    return false;
                }
                $("input[name='addonUsers']").val(count - 1);
                calculation();
            }
            return false;
        });

        $(document).on("click", "button.plususer", function(e) {
            if ($("input[name='moreusers']").is(":checked")) {
                var count = Number($("input[name='addonUsers']").val());
                if (count >= 100) {
                    return false;
                }
                $("input[name='addonUsers']").val(count + 1);
                calculation();
            }
            return false;
        });

        $(document).on("click", "button.minusbranch", function(e) {
            if ($("input[name='morebranches']").is(":checked")) {
                var count = Number($("input[name='addonBranches']").val());
                if (count === 1) {
                    return false;
                }
                $("input[name='addonBranches']").val(count - 1);
                calculation();
            }
            return false;
        });

        $(document).on("click", "button.plusbranch", function(e) {
            if ($("input[name='morebranches']").is(":checked")) {
                var count = Number($("input[name='addonBranches']").val());
                if (count >= 100) {
                    return false;
                }
                $("input[name='addonBranches']").val(count + 1);
                calculation();
            }
            return false;
        });
    </script>
</body>

</html>