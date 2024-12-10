<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription | <?= AppName ?></title>
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
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script>
        var base_path = "<?= base_url() ?>";
    </script>
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
                <h4>Subscription Expired...</h4>
                <p>
                    To continue to use our software-application you need to purchase an suitabale subscription from below.<br />
                    Do not worry! We have kept your data safely with us. Just subscribe and make the payment and continue using our product and services.
                </p>
            </div>
        </div>
        <?php
        if ($subscription) {
            $subscription = $subscription->result()[0];
            if (empty($lastplan) || $lastplan['isFreeTrial'] == 1) {
        ?>
                <div class="row">
                    <div class="col-md-6 col-lg-4 offset-lg-2">
                        <form action="<?= base_url('package/configure') ?>" method="POST">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12 my-2">
                                            <h5 class="card-title">Monthly Subscription</h5>
                                            <input type="hidden" readonly name="duration" value="month">
                                            <input type="hidden" readonly name="packageCode" value="<?= $subscription->code ?>">
                                        </div>
                                        <div class="col-sm-12 mb-2 text-center">
                                            <h3><?= $subscription->monthlychargesincltax ?><small>/Month</small></h3>
                                        </div>
                                        <div class="col-sm-12 mb-2">
                                            <ul>
                                                <li><span class="fa fa-check text-success mx-2"></span> <b><?= $subscription->noofbranch ?></b> Branches</li>
                                                <li><span class="fa fa-check text-success mx-2"></span> <b><?= $subscription->noofusers ?></b> Users</li>
                                            </ul>
                                        </div>
                                        <div class="col-sm-12 mb-2">
                                            <button class="btn btn-primary w-100">Select Plan</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <form action="<?= base_url('package/configure') ?>" method="POST">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12 my-2">
                                            <h5 class="card-title">Yearly Subscription</h5>
                                            <input type="hidden" readonly name="duration" value="year">
                                            <input type="hidden" readonly name="packageCode" value="<?= $subscription->code ?>">
                                        </div>
                                        <div class="col-sm-12 mb-2 text-center">
                                            <h3><?= $subscription->yearlychargesincltax ?><small>/Year</small></h3>
                                        </div>
                                        <div class="col-sm-12 mb-2">
                                            <ul>
                                                <li><span class="fa fa-check text-success mx-2"></span> <b><?= $subscription->noofbranch ?></b> Branches</li>
                                                <li><span class="fa fa-check text-success mx-2"></span> <b><?= $subscription->noofusers ?></b> Users</li>
                                            </ul>
                                        </div>
                                        <div class="col-sm-12 mb-2">
                                            <button class="btn btn-primary w-100">Select Plan</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <?php
            } else {
                $period = $lastplan['period'];
                $defaultUsers = $lastplan['defaultUsers'];
                $defaultBranches = $lastplan['defaultBranches'];
                $addonUsers = $lastplan['addonUsers'];
                $addonBranches = $lastplan['addonBranches'];
                if ($period == "month") {
                    $addonUserPrice =  number_format($addonBranches * $subscription->monthlyperusercharges, 2, '.', "");
                    $addonBranchPrice =  number_format($addonBranches * $subscription->monthlyperbranchcharges, 2, '.', "");
                    $fprice = $subscription->monthlychargesincltax + $addonBranchPrice + $addonUserPrice;
                    $totalPrice = number_format($fprice, 2, ".", "");
                    $subtotal = $subscription->monthlychargesextax + ($subscription->costperuser * $addonUsers) + ($subscription->costperbranch * $addonBranches);
                    $basicCharge = $subscription->monthlychargesextax;
                    $subtotal = number_format($subtotal, 2, ".", "");
                    $taxtotal = number_format($totalPrice - $subtotal, 2, ".", "");
                    $planconfig = stripslashes(json_encode($subscription));
                ?>
                    <div class="row">
                        <div class="col-md-12 col-lg-8 offset-lg-2">
                            <form action="<?= base_url('package/renew') ?>" method="POST">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-12 my-2">
                                                <h5 class="card-title">Monthly Subscription</h5>
                                                <input type="hidden" readonly name="duration" value="month">
                                                <input type="hidden" readonly name="clientCode" value="<?= $clientCode ?>">
                                                <input type="hidden" readonly name="packageCode" value="<?= $subscription->code ?>">
                                                <input type="hidden" readonly name="defaultUsers" value="<?= $defaultUsers ?>">
                                                <input type="hidden" readonly name="defaultBranches" value="<?= $defaultBranches ?>">
                                                <input type="hidden" readonly name="addonUsers" value="<?= $addonUsers ?>">
                                                <input type="hidden" readonly name="addonBranches" value="<?= $addonBranches ?>">
                                                <input type="hidden" readonly name="addonBranchPrice" value="<?= $addonBranchPrice ?>">
                                                <input type="hidden" readonly name="addonUserPrice" value="<?= $addonUserPrice ?>">
                                                <input type="hidden" readonly name="perUserPrice" value="<?= $subscription->monthlyperusercharges ?>">
                                                <input type="hidden" readonly name="perBranchPrice" value="<?= $subscription->monthlyperbranchcharges ?>">
                                                <input type="hidden" readonly name="subtotal" value="<?= $subtotal ?>">
                                                <input type="hidden" readonly name="taxtotal" value="<?= $taxtotal ?>">
                                                <input type="hidden" readonly name="totalPayable" value="<?= $totalPrice ?>">
                                                <input type="hidden" readonly name="taxpercent" value="<?= $subscription->tax ?>">
                                                <input type="hidden" readonly name="basicCharge" value="<?= $basicCharge ?>">
                                                <input type="hidden" readonly name="discount" value="0">
                                                <input type="hidden" readonly name="planconfig" value="<?= base64_encode($planconfig) ?>">
                                                <div class="col-sm-12 mb-2 text-center">
                                                    <div>
                                                        <span>Base Price</span>
                                                        <h5><?= $subscription->monthlychargesincltax ?><small>/Month</small></h5>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 mb-2">
                                                    <p>Default Configuration</p>
                                                    <ul>
                                                        <li><span class="fa fa-check text-success mx-2"></span> <b><?= $defaultUsers ?></b> Branches</li>
                                                        <li><span class="fa fa-check text-success mx-2"></span> <b><?= $defaultUsers ?></b> Users</li>
                                                    </ul>
                                                </div>
                                                <div class="col-sm-12 mb-2">
                                                    <p>Additional (bought as per previous plan)</p>
                                                    <?php if ($addonUsers > 0) { ?>
                                                        <table class="table">
                                                            <tr>
                                                                <th width="40%">Addon Users</th>
                                                                <th width="30%">Price (per user)</th>
                                                                <th width="30%">Total Price</th>
                                                            </tr>
                                                            <tr>
                                                                <td><?= $addonUsers ?></td>
                                                                <td><?= $subscription->monthlyperusercharges ?></td>
                                                                <td><?= $addonUserPrice ?></td>
                                                            </tr>
                                                        </table>
                                                    <?php } ?>
                                                    <?php if ($addonBranches > 0) { ?>
                                                        <table class="table">
                                                            <tr>
                                                                <th width="40%">Addon Branches</th>
                                                                <th width="30%">Price (per branch)</th>
                                                                <th width="30%">Total Price</th>
                                                            </tr>
                                                            <tr>
                                                                <td><?= $addonBranches ?></td>
                                                                <td><?= $subscription->monthlyperbranchcharges ?></td>
                                                                <td><?= $addonBranchPrice ?></td>
                                                            </tr>
                                                        </table>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-sm-12 mb-2 text-center">
                                                    <div>
                                                        <span>Total payable</span>
                                                        <h3><b><?= $totalPrice ?></b></h3>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 mb-2">
                                                    <button class="btn btn-primary w-100">Pay Now ?</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php
                } else {
                    $addonUserPrice =  number_format($addonBranches * $subscription->yearlyperusercharges, 2, '.', "");
                    $addonBranchesPrice =  number_format($addonBranches * $subscription->yearlyperbranchcharges, 2, '.', "");
                    $fprice = $subscription->yearlychargesincltax + $addonBranchesPrice + $addonUserPrice;
                    $totalPrice = number_format($fprice, 2, ".", "");
                    $subtotal = $subscription->yearlychargesextax + ($subscription->costperuser * $addonUsers) + ($subscription->costperbranch * $addonBranches);
                    $basicCharge = $subscription->yearlychargesextax;
                    $subtotal = number_format($subtotal, 2, ".", "");
                    $taxtotal = number_format($totalPrice - $subtotal, 2, ".", "");
                    $planconfig = stripslashes(json_encode($subscription));
                ?>
                    <div class="row">
                        <div class="col-md-12 col-lg-8 offset-lg-2">
                            <form action="<?= base_url('package/renew') ?>" method="POST">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-12 my-2">
                                                <h5 class="card-title">Yearly Subscription</h5>
                                                <input type="hidden" readonly name="duration" value="year">
                                                <input type="hidden" readonly name="clientCode" value="<?= $clientCode ?>">
                                                <input type="hidden" readonly name="packageCode" value="<?= $subscription->code ?>">
                                                <input type="hidden" readonly name="defaultUsers" value="<?= $defaultUsers ?>">
                                                <input type="hidden" readonly name="defaultBranches" value="<?= $defaultBranches ?>">
                                                <input type="hidden" readonly name="addonUsers" value="<?= $addonUsers ?>">
                                                <input type="hidden" readonly name="addonBranches" value="<?= $addonBranches ?>">
                                                <input type="hidden" readonly name="addonBranchPrice" value="<?= $addonBranchPrice ?>">
                                                <input type="hidden" readonly name="addonUserPrice" value="<?= $addonUserPrice ?>">
                                                <input type="hidden" readonly name="perUserPrice" value="<?= $subscription->yearlyperusercharges ?>">
                                                <input type="hidden" readonly name="perBranchPrice" value="<?= $subscription->yearlyperbranchcharges ?>">
                                                <input type="hidden" readonly name="totalPayable" value="<?= $totalPrice ?>">
                                                <input type="hidden" readonly name="subtotal" value="<?= $subtotal ?>">
                                                <input type="hidden" readonly name="taxtotal" value="<?= $taxtotal ?>">
                                                <input type="hidden" readonly name="totalPayable" value="<?= $totalPrice ?>">
                                                <input type="hidden" readonly name="taxpercent" value="<?= $subscription->tax ?>">
                                                <input type="hidden" readonly name="basicCharge" value="<?= $basicCharge ?>">
                                                <input type="hidden" readonly name="discount" value="0">
                                                <input type="hidden" readonly name="planconfig" value="<?= base64_encode($planconfig) ?>">
                                            </div>
                                            <div class="col-sm-12 mb-2 text-center">
                                                <div>
                                                    <span>Base Price</span>
                                                    <h5><?= $subscription->yearlychargesincltax ?><small>/Year</small></h3>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 mb-2">
                                                <p>Default Configuration</p>
                                                <ul>
                                                    <li><span class="fa fa-check text-success mx-2"></span> <b><?= $defaultUsers ?></b> Branches</li>
                                                    <li><span class="fa fa-check text-success mx-2"></span> <b><?= $defaultUsers ?></b> Users</li>
                                                </ul>
                                            </div>
                                            <div class="col-sm-12 mb-2">
                                                <p>Additional (bought as per previous plan)</p>
                                                <?php if ($addonUsers > 0) { ?>
                                                    <table class="table">
                                                        <tr>
                                                            <th width="40%">Addon Users</th>
                                                            <th width="30%">Price (per user)</th>
                                                            <th width="30%">Total Price</th>
                                                        </tr>
                                                        <tr>
                                                            <td><?= $addonUsers ?></td>
                                                            <td><?= $subscription->monthlyperusercharges ?></td>
                                                            <td><?= $addonUserPrice ?></td>
                                                        </tr>
                                                    </table>
                                                <?php } ?>
                                                <?php if ($addonBranches > 0) { ?>
                                                    <table class="table">
                                                        <tr>
                                                            <th width="40%">Addon Branches</th>
                                                            <th width="30%">Price (per branch)</th>
                                                            <th width="30%">Total Price</th>
                                                        </tr>
                                                        <tr>
                                                            <td><?= $addonBranches ?></td>
                                                            <td><?= $subscription->monthlyperbranchcharges ?></td>
                                                            <td><?= $addonBranchPrice ?></td>
                                                        </tr>
                                                    </table>
                                                <?php } ?>
                                            </div>
                                            <div class="col-sm-12 mb-2">
                                                <div><span>Total payable</span>
                                                    <h3><b><?= $totalPrice ?></b></h3>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 mb-2">
                                                <button class="btn btn-primary w-100">Buy Now?</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
        <?php
                }
            }
        }
        ?>
        <div class="row">
            <div class="col-md-12 col-lg-8 offset-lg-2">
                <div class="card">
                    <div class="card-body">
                        <h4>Need some help?</h4>
                        <p>Call us now on +1213231132.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= base_url() ?>assets/js/bootstrap.js"></script>
    <script src="<?= base_url() ?>assets/js/app.js"></script>
    <script src="<?= base_url() ?>assets/extensions/jquery/jquery.min.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>assets/js/webix.js">
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
    </script>
</body>

</html>