<style>
    .border-none {
        border: none !important;
        pointer-events: none;
        font-weight: 600;
        padding-left: 10px;
        padding-right: 10px;
    }
</style>
<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Upgrade Plan</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Upgrade Plan</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <?php
        if ($plan) {
            $plan = $plan->result()[0];
            $period = $plan->period;
            $today = date('Y-m-d H:i:00');
            $multiplier = 1;
            if ($period == "year") {
                $date1 = date_create($today);
                $date2 = date_create($plan->endDate);
                $diff = $date1->diff($date2);
                $yearsInMonths = $diff->format('%r%y') * 12;
                $months = $diff->format('%r%m');
                $totalMonths = $yearsInMonths + $months;
                $multiplier = round($totalMonths) == 0 ? 1 : round($totalMonths);
            }
            if ($today >= $plan->startDate && $today <= $plan->expiryDate) {
                if ($subscription) {
                    $subscription = $subscription->result()[0];
                    if ($period == "year") {
                        $perusercharge =  number_format(($subscription->yearlyperusercharges / 12), 2, ".", "");
                        $perusercharge = number_format(($perusercharge * $multiplier), 2, ".", "");
                        $perbranchcharge = number_format(($subscription->yearlyperbranchcharges / 12), 2, ".", "");
                        $perbranchcharge = number_format(($perbranchcharge * $multiplier), 2, ".", "");
                    } else {
                        $perusercharge =   $subscription->monthlyperusercharges;
                        $perbranchcharge =  $subscription->monthlyperbranchcharges;
                    }

                    $d1 = new DateTime(date('Y-m-d'));
                    $d2 = new DateTime($plan->expiryDate);
                    $interval = $d2->diff($d1);
                    $remainingPeriod = $interval->format('%m');
                    if ($remainingPeriod <= 0) $remainingPeriod = 1;

        ?>
                    <form action="<?= base_url("subscriptions/upgrade") ?>" method="post">
                        <input type="hidden" name="code" value="<?= $plan->code ?>" readonly>
                        <input type="hidden" name="packageCode" value="<?= $plan->subscriptionCode ?>" readonly>
                        <input type="hidden" name="period" value="<?= $period ?>" readonly>
                        <input type="hidden" name="mainPaymentCode" value="<?= $plan->code ?>" readonly>
                        <input type="hidden" name="clientCode" value="<?= $plan->clientCode ?>" readonly>
                        <input type="number" name="amount" value="0.00" readonly>
                        <input type="hidden" readonly name="interval" value="<?= $remainingPeriod ?>" />
                        <input type="hidden" readonly name="costperbranch" value="<?= $subscription->costperbranch ?>" />
                        <input type="hidden" readonly name="costperuser" value="<?= $subscription->costperuser ?>" />
                        <input type="hidden" readonly name="taxpercent" value="<?= $subscription->tax ?>" />
                        <input type="hidden" readonly name="discount" value="0.00" />
                        <input type="hidden" readonly name="planconfig" value="<?= base64_encode(stripslashes(json_encode($subscription))) ?>">
                        <input type="hidden" readonly name="basicCharge" value="<?= $duration == "year" ? $subscription->yearlychargesextax : $subscription->monthlychargesextax ?>">
                        <input type="hidden" readonly name="subtotal" value="<?= $duration == "year" ? $subscription->yearlychargesextax : $subscription->monthlychargesextax ?>">
                        <input type="hidden" readonly name="taxtotal" value="0.00">
                        <section class="section">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h5>Modify Current Plan</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="col-12 mb-3">
                                                <?= $period ?>
                                                <div class="row">
                                                    <div class="col-md-4 mb-2">
                                                        <label for="perUserPrice">Each User Price <small>(Inc.Taxes)</small></label>
                                                        <input type="number" name="perUserPrice" id="perUserPrice" class="form-control border-none" value="<?= $perusercharge ?>" readonly>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <label for="addonUsers">Add Users </label>
                                                        <div class="input-group mb-3">
                                                            <button class="btn btn-sm btn-outline-secondary minususer" type="button"><i class="fa fa-minus"></i></button>
                                                            <input type="number" name="addonUsers" readonly class="form-control" placeholder="" aria-label="Example text with two button addons" value="0">
                                                            <button class="btn btn-sm btn-outline-secondary plususer" type="button"><i class="fa fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <label for="addonUserPrice">Total <small>(Inc.Taxes)</small></label>
                                                        <input readonly name="addonUserPrice" id="addonUserPrice" class="form-control border-none" value="0.00">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 mb-2">
                                                    <label for="perBranchPrice">Each Branch Price <small>(Inc.Taxes)</small></label>
                                                    <input type="number" name="perBranchPrice" id="perBranchPrice" class="form-control border-none" readonly value="<?= $perbranchcharge ?>">
                                                </div>
                                                <div class="col-md-4 mb-2">
                                                    <label for="addonBranches">Add Branches</label>
                                                    <div class="input-group mb-3">
                                                        <button class="btn btn-sm btn-outline-secondary minusbranch" type="button"><i class="fa fa-minus"></i></button>
                                                        <input type="number" name="addonBranches" readonly class="form-control" placeholder="" aria-label="Example text with two button addons" value="0">
                                                        <button class="btn btn-sm btn-outline-secondary plusbranch" type="button"><i class="fa fa-plus"></i></button>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-2">
                                                    <label for="addonBranchPrice">Total <small>(Inc.Taxes)</small></label>
                                                    <input readonly name="addonBranchPrice" id="addonBranchPrice" class="form-control border-none" value="0.00">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label for="addonBranches">Payable Amount <h3 style="font-weight:bold" id="fpText">0.00</h3></label>
                                                </div>
                                                <div class="col-md-4">
                                                    <button class="btn btn-primary w-100">Checkout</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </form>
        <?php
                }
            }
        }
        ?>
    </div>
</div>
<script>
    function calculation() {
        var interval = Number($("input[name='interval']").val());
        var costperuser = Number($("input[name='costperuser']").val());
        var costperbranch = Number($("input[name='costperbranch']").val());
        var basicCharge = $("input[name='basicCharge']");
        var addonUsers = Number($("input[name='addonUsers']").val());
        var addonBranches = Number($("input[name='addonBranches']").val());
        var taxpercent = Number($("input[name='taxpercent']").val());

        var subtotal = $("input[name='subtotal']");
        var taxtotal = $("input[name='taxtotal']");
        var addonUserPrice = $("input[name='addonUserPrice']");
        var addonBranchPrice = $("input[name='addonBranchPrice']");
        var finalPrice = $("input[name='amount']");

        let costofUsers = Number(costperuser * interval * addonUsers).toFixed(2);
        let costofBranches = Number(costperbranch * interval * addonBranches).toFixed(2);
        let costSubtotal = (Number(costofUsers) + Number(costofBranches)).toFixed(2);
        let taxonUsers = (Number(costofUsers) * (Number(taxpercent) * 0.01)).toFixed(2);
        let taxonBranches = (Number(costofBranches) * (Number(taxpercent) * 0.01)).toFixed(2);
        let totalTax = (Number(taxonBranches) + Number(taxonUsers)).toFixed(2);
        let finalAmount = (Number(costSubtotal) + Number(totalTax)).toFixed(2);

        addonUserPrice.val((Number(costofUsers) + Number(taxonUsers)).toFixed(2));
        addonBranchPrice.val((Number(costofBranches) + Number(taxonBranches)).toFixed(2));
        subtotal.val(costSubtotal);
        basicCharge.val(costSubtotal);
        taxtotal.val(totalTax);
        finalPrice.val(finalAmount);
        $("h3[id='fpText']").text(finalAmount);
    }

    $(document).on("click", "button.minususer", function(e) {
        var count = Number($("input[name='addonUsers']").val());
        if (count === 0) {
            return false;
        }
        $("input[name='addonUsers']").val(count - 1);
        calculation();
        return false;
    });

    $(document).on("click", "button.plususer", function(e) {
        var count = Number($("input[name='addonUsers']").val());
        if (count >= 100) {
            return false;
        }
        $("input[name='addonUsers']").val(count + 1);
        calculation();
    });

    $(document).on("click", "button.minusbranch", function(e) {
        var count = Number($("input[name='addonBranches']").val());
        if (count === 0) {
            return false;
        }
        $("input[name='addonBranches']").val(count - 1);
        calculation();
        return false;
    });

    $(document).on("click", "button.plusbranch", function(e) {
        var count = Number($("input[name='addonBranches']").val());
        if (count >= 100) {
            return false;
        }
        $("input[name='addonBranches']").val(count + 1);
        calculation();
    });
</script>