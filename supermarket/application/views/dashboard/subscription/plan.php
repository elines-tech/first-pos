<style>
    button.confirm {
        background-color: #e72f2f !important;
        color: #FFFFFF !important;
    }

    button.cancel {
        border-color: #9e9e9e !important;
    }
</style>

<?php include '../supermarket/config.php'; ?>


<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3><?php echo $translations['Current Plan']?></h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">View Plan</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <?php
        if ($plan) {
            $plan = $plan->result()[0];
            $extrausers = $extrabranches = 0;
            if ($addons) {
                $addons = $addons->result();
                foreach ($addons as $a) {
                    $extrausers += $a->addonUsers;
                    $extrabranches += $a->addonBranches;
                }
            }
        ?>
            <section class="section">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5><?php echo $translations['Subscription Detail']?></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <label for="" class="col-md-4"><?php echo $translations['For']?></label>
                                    <div class="col-md-8">
                                        <strong><?= ucwords($plan->category) ?></strong>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="" class="col-md-4"><?php echo $translations['Duration']?></label>
                                    <div class="col-md-8">
                                        <strong><?= ucwords($plan->period) ?></strong>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="" class="col-md-4"><?php echo $translations['Transaction Date']?></label>
                                    <div class="col-md-8">
                                        <strong><?= date('d-M-Y', strtotime($plan->paymentDate)) ?></strong>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="" class="col-md-4"><?php echo $translations['Receipt No']?></label>
                                    <div class="col-md-8">
                                        <strong><?= $plan->receiptId ?></strong>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="" class="col-md-4"><?php echo $translations['Transaction Id']?></label>
                                    <div class="col-md-8">
                                        <strong><?= $plan->paymentId ?? "-" ?></strong>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="" class="col-md-4"><?php echo $translations['Transaction Status']?></label>
                                    <div class="col-md-8">
                                        <strong><?= ucwords($plan->paymentStatus) ?></strong>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="" class="col-md-4"><?php echo $translations['Amount (Incl. Tax)']?></label>
                                    <div class="col-md-8">
                                        <strong><?= $plan->amount ?></strong>
                                    </div>
                                </div>
                                <?php
                                if ($plan->type != "addon") { ?>
                                    <div class="row mb-3">
                                        <label for="" class="col-md-4"><?php echo $translations['Service Period']?></label>
                                        <div class="col-md-8">
                                            <strong><?= date('d/M/Y', strtotime($plan->startDate)) . " - " . date("d/M/Y", strtotime($plan->expiryDate)); ?></strong>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="row mb-3">
                                    <label for="" class="col-md-4"><?php echo $translations['No. of users']?></label>
                                    <div class="col-md-8">
                                        <strong><?= ($plan->defaultUsers + $plan->addonUsers) ?></strong>
                                    </div>
                                </div>
                                <div class="row mb-3 text-danger">
                                    <label for="" class="col-md-4"><?php echo $translations['Added No. of users']?></label>
                                    <div class="col-md-8">
                                        <strong><?= $extrausers ?></strong>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="" class="col-md-4"><?php echo $translations['No. of Branches']?></label>
                                    <div class="col-md-8">
                                        <strong><?= ($plan->defaultBranches + $plan->addonBranches) ?></strong>
                                    </div>
                                </div>
                                <div class="row mb-3 text-danger">
                                    <label for="" class="col-md-4"><?php echo $translations['Added No. of Branches']?></label>
                                    <div class="col-md-8">
                                        <strong><?= $extrabranches ?></strong>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="" class="col-md-4"><?php echo $translations['Trial Subscription']?></label>
                                    <div class="col-md-8">
                                        <strong><?= $plan->isFreeTrial == 0 ? "No" : "Yes" ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                if ($plan->type != "addon") {
                    $today = date('Y-m-d H:i:00');
                    if ($today >= $plan->startDate && $today <= $plan->expiryDate) {
                        if ($plan->type == "subscription") {
                ?>
                            <div class="row">
                                <div class="col-md-4 offset-md-8 mb-3">
                                    <form action="<?= base_url('subscriptions/modify') ?>" method="post">
                                        <input type="hidden" name="code" value="<?= $plan->code ?>" readonly>
                                        <button class="btn btn-primary w-100"><?php echo $translations['Upgrade Plan']?></button>
                                    </form>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                        <div class="row">
                            <div class="col-md-4 offset-md-8 mb-3">
                                <button class="btn btn-danger w-100" id="cancelplan" data-id="<?= $plan->code ?>" data-client="<?= $plan->clientCode ?>"><?php echo $translations['Cancel Plan']?></button>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </section>
        <?php
        }
        ?>
    </div>
</div>
<script>
    $(document).on("click", "button#cancelplan", function(e) {
        e.preventDefault();
        var btn = $(this);
        var code = $(this).data('id');
        var client = $(this).data('client');
        swal({
            title: '<?php echo $translations['Cancel Plan?']?>',
            text: '<?php echo $translations['You will not be able to recover the plan and access the panel as well, You have to purchase the subscription plan again. Continue?']?>',
            type: "error",
            showCancelButton: !0,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "<?php echo $translations['Yes, continue']?>",
            cancelButtonText: "<?php echo $translations['No, Go Back']?>",
            closeOnConfirm: !1,
            closeOnCancel: !1
        }, function(e) {
            if (e) {
                $.ajax({
                    url: base_path + "subscriptions/cancelplan",
                    type: 'POST',
                    data: {
                        "paymentCode": code,
                        "clientCode": client
                    },
                    dataType: "JSON",
                    beforeSend: function() {
                        btn.attr("disabled", true);
                        btn.html("Please wait....");
                    },
                    success: function(res) {
                        swal.close();
                        if (res.status) {
                            toastr.success(res.msg, 'Subscription', {
                                "progressBar": true,
                                "onHidden": function() {
                                    window.location.href = base_path + "authentication/logout";
                                }
                            });
                        } else {
                            toastr.error(res.msg, 'Subscription', {
                                "progressBar": true,
                                "onHidden": function() {
                                    btn.removeAttr("disabled");
                                    btn.html("Cancel Plan");
                                }
                            });
                        }
                    },
                    error: function() {
                        toastr.error("Somthing went wrong. Server faile to process your request. Please try again later.", 'Subscription', {
                            "progressBar": true,
                            "onHidden": function() {
                                btn.removeAttr("disabled");
                                btn.html("Cancel Plan");
                            }
                        });
                    }
                });
            } else {
                swal.close()
            }
        });
    });
</script>