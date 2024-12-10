<div class="row">
    <?php
    if ($subscription) {
        $jplan = $subscription->result_array()[0];
        $subscription = $subscription->result()[0];
    ?>
        <div class="col-md-12">
            <form action="<?= base_url('register/new') ?>" method="POST">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12">
                            <div class="row ">
                                <div class="col-sm-12 my-2">
                                    <h5 class="">Base <?= ucwords($duration . 'ly') ?> Plan</h5>
                                    <input type="hidden" readonly name="trialPlan" value="0">
                                    <input type="hidden" readonly name="duration" value="<?= $duration ?>">
                                    <input type="hidden" readonly name="category" value="<?= strtolower($subscription->packageFor) ?>">
                                    <input type="hidden" readonly name="packageCode" value="<?= $subscription->code ?>">
                                    <input type="hidden" readonly name="defaultUsers" value="<?= $subscription->noofusers ?>">
                                    <input type="hidden" readonly name="defaultBranches" value="<?= $subscription->noofbranch ?>">
                                    <input type="hidden" readonly name="multiplyer" value="<?= $duration == "year" ? 12 : 1 ?>">
                                    <input type="hidden" readonly name="costperbranch" value="<?= $subscription->costperbranch ?>" />
                                    <input type="hidden" readonly name="costperuser" value="<?= $subscription->costperuser ?>" />
                                    <input type="hidden" readonly name="costperbranch" value="<?= $subscription->costperbranch ?>" />
                                    <input type="hidden" readonly name="costperuser" value="<?= $subscription->costperuser ?>" />
                                    <input type="hidden" readonly name="taxpercent" value="<?= $subscription->tax ?>" />
                                    <input type="hidden" readonly name="discount" value="0.00" />
                                    <input type="hidden" readonly name="planconfig" value="<?= base64_encode(stripslashes(json_encode($jplan))) ?>">
                                    <input type="hidden" readonly name="basicCharge" value="<?= $duration == "year" ? $subscription->yearlychargesextax : $subscription->monthlychargesextax ?>">
                                    <input type="hidden" readonly name="subtotal" value="<?= $duration == "year" ? $subscription->yearlychargesextax : $subscription->monthlychargesextax ?>">
                                    <?php
                                    $sub = $duration == "year" ? $subscription->yearlychargesextax : $subscription->monthlychargesextax;
                                    $pay = $duration == "year" ? $subscription->yearlychargesincltax : $subscription->monthlychargesincltax;
                                    $taxtotal = number_format($pay - $sub, 2, ".", "");
                                    ?>
                                    <input type="hidden" readonly name="taxtotal" value="<?= $taxtotal ?>">
                                    <input type="hidden" name="finalPrice" id="finalPrice" value="<?= $duration == "year" ? $subscription->yearlychargesincltax : $subscription->monthlychargesincltax ?>" readonly>
                                    <input type="hidden" name="planPrice" id="planPrice" value="<?= $duration == "year" ? $subscription->yearlychargesincltax : $subscription->monthlychargesincltax ?>" readonly>
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
                                    <label for="perUserPrice">User Price <small>(Inc.Taxes)</small></label>
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
                                    <input name="addaddonUserPrice" id="addonUserPrice" class="form-control border-none" value="0.00">
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
                                    <label for="perBranchPrice">Branch Price <small>(Inc.Taxes)</small></label>
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
                                    <input name="addonBranchPrice" id="addonBranchPrice" class="form-control border-none" value="0.00">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="">Final Price </label>
                            <h3 id="fpText"><?= $duration == "year" ? $subscription->yearlychargesincltax : $subscription->monthlychargesincltax ?></h3>
                        </div>
                        <div class="col-sm-12 mb-2">
                            <button class="btn btn-primary w-100">Register Now?</button>
                        </div>
                        <div class="col-sm-12 mb-2">
                            <small>Please register first and then you will be redirected to payment.</small>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    <?php
    }
    ?>
</div>