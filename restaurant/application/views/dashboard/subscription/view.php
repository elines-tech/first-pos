<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>View Subscriptions</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">View Subscription</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-10">
                            <h5>Subscription Detail</h5>
                        </div>
                        <div class="col-md-2 text-end">
                            <a id="cancelDefault" href="<?= base_url('subscriptions/listRecords')?>" class="btn btn-sm btn-primary">Back</a>
                        </div>
                    </div>
                </div>
                <?php
                if ($plan) {
                    $plan = $plan->result()[0];
                ?>
                    <div class="card-body">
                        <div class="row mb-3">
                            <label for="" class="col-md-4">For</label>
                            <div class="col-md-8">
                                <strong><?= ucwords($plan->category) ?></strong>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="" class="col-md-4">Duration</label>
                            <div class="col-md-8">
                                <strong><?= $plan->period ?></strong>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="" class="col-md-4">Transaction Date</label>
                            <div class="col-md-8">
                                <strong><?= date('d-M-Y', strtotime($plan->paymentDate)) ?></strong>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="" class="col-md-4">Receipt No</label>
                            <div class="col-md-8">
                                <strong><?= $plan->receiptId ?></strong>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="" class="col-md-4">Transaction Id</label>
                            <div class="col-md-8">
                                <strong><?= $plan->paymentId ?></strong>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="" class="col-md-4">Transaction Status</label>
                            <div class="col-md-8">
                                <strong><?= ucwords($plan->paymentStatus) ?></strong>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="" class="col-md-4">Amount (Incl. Tax)</label>
                            <div class="col-md-8">
                                <strong><?= $plan->amount ?></strong>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="" class="col-md-4">Service Period</label>
                            <div class="col-md-8">
                                <strong><?= date('d/M/Y', strtotime($plan->startDate)) . " - " . date("d/M/Y", strtotime($plan->expiryDate)); ?></strong>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="" class="col-md-4">No. of users</label>
                            <div class="col-md-8">
                                <strong><?= ($plan->defaultUsers + $plan->addonUsers) ?></strong>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="" class="col-md-4">No. of brnaches</label>
                            <div class="col-md-8">
                                <strong><?= ($plan->defaultBranches + $plan->addonBranches) ?></strong>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="" class="col-md-4">Trial Subscription</label>
                            <div class="col-md-8">
                                <strong><?= $plan->isFreeTrial == 0 ? "No" : "Yes" ?></strong>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </section>
    </div>
</div>