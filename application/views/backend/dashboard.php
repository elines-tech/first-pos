<style>
    .card{
		margin-bottom: 0.3rem !important;
	}
</style>
<div class="page-heading m-4">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h5 class="page-title">Dashboard </h5>
                <div class="d-flex align-items-center">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="page-content m-4">
    <section class="row">
        <div class="col-12 col-lg-12">
            <div class="row">
			    <div class="col-6 col-lg-4 col-md-6">
                    <div class="card h-100">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-2 col-lg-12 col-xl-12 col-xxl-3 d-flex justify-content-start ">
                                    <div class="stats-icon red mb-2">
                                        <i class="iconly-boldArrow---Right-Circle "></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-9">
                                    <h6 class="text-muted font-semibold">Active Subscribers</h6>
                                    <h6 class="font-extrabold mb-0"><?= $countactivesubscribers?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-4 col-md-6">
                    <div class="card h-100">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-2 col-lg-12 col-xl-12 col-xxl-3 d-flex justify-content-start ">
                                    <div class="stats-icon blue mb-2">
                                        <i class="iconly-boldArrow---Right-Circle "></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-9">
                                    <h6 class="text-muted font-semibold">New Subscribers</h6>
									<span style="font-size:14px;">Last 24 hours</span>
                                    <h6 class="font-extrabold mb-0"><?= $newsubscriber?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-4 col-md-6">
                    <div class="card h-100">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-2 col-lg-12 col-xl-12 col-xxl-3 d-flex justify-content-start ">
                                    <div class="stats-icon green mb-2">
                                        <i class="iconly-boldCategory"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-9">
                                    <h6 class="text-muted font-semibold">Expired Subscriptions</h6>
                                    <h6 class="font-extrabold mb-0"><?= $expiredSubscriptions ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-4 col-md-6 mt-4">
                    <div class="card h-100">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-2 col-lg-12 col-xl-12 col-xxl-3 d-flex justify-content-start ">
                                    <div class="stats-icon red mb-2">
                                        <i class="iconly-boldHome"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-9">
                                    <h6 class="text-muted font-semibold">Free Trial</h6>
                                    <h6 class="font-extrabold text-success mb-0"><?= $freeTrial ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-4 col-md-6 mt-4">
                    <div class="card h-100">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-2 col-lg-12 col-xl-12 col-xxl-3 d-flex justify-content-start ">
                                    <div class="stats-icon blue mb-2">
                                        <i class="iconly-boldHome"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-9">
                                    <h6 class="text-muted font-semibold">Monthly 10% period remaining</h6>
                                    <h6 class="font-extrabold text-success mb-0"><?= $remMonthSub ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				 <div class="col-6 col-lg-4 col-md-6 mt-4">
                    <div class="card h-100">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-2 col-lg-12 col-xl-12 col-xxl-3 d-flex justify-content-start ">
                                    <div class="stats-icon green mb-2">
                                        <i class="iconly-boldHome"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-9">
                                    <h6 class="text-muted font-semibold">Yearly 10% period remaining</h6>
                                    <h6 class="font-extrabold text-success mb-0"><?= $remYearSub ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
</script>