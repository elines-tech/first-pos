<nav class="navbar navbar-expand-lg sticky-top bg-white">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="<?= base_url('assets/images/logo/logo_sm.png') ?>" style="height:30px" alt="TMKN Software" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarScroll">
            <ul class="navbar-nav ms-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                <li class="nav-item">
                    <a class="nav-link link-primary" title="Restaurnat login?" aria-current="page" href="<?= base_url('restaurant/login') ?>"><i class="fa fa-sign-in" aria-hidden="true"></i> Restaurant</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link link-primary" title="Supermarket login?" aria-current="page" href="<?= base_url('supermarket/login') ?>"><i class="fa fa-sign-in" aria-hidden="true"></i> Supermarket</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link link-default" href="<?= base_url('register') ?>" title="New Registration?"> <i class="fa fa-plus-square-o" aria-hidden="true"></i> Register</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<section id="hero" class="hero d-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 d-flex flex-column justify-content-center">
                <h1 style="color:#0D988C">POS</h1>
                <h2>Nobis, class ut aliquam lobortis rerum quia sodales quibusdam integer! Quos iure elit nostra natus lacus adipisicing penatibus.</h2>
                <div style="margin:15px 0">
                    <div class="text-start">
                        <a href="<?= base_url('register') ?>" class="btn btn-primary">Subscription</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 hero-img" data-aos="zoom-out" data-aos-delay="200">
                <img src="<?= base_url('assets/images/bg/herobg.png') ?>" class="img-fluid" alt="">
            </div>
        </div>
    </div>
</section>
<section class="plan">
    <div class="heading">
        <h3 style="color:#707070;font-weight:700;">Choose subscription plan</h3>
    </div>
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-2"></div>
            <?php
            if ($subscriptionmaster) {
                foreach ($subscriptionmaster->result() as $s) {
            ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h4><?= ucwords(strtolower($s->title)) ?></h4>
                                <div class="btnbrd"></div>
                                <div class="pkgtitle"><?= ucwords($s->packageFor) ?></div>
                                <div class="mt-3">
                                    Branches <span class="float-end count"><?= $s->noofbranch ?></span>
                                </div>
                                <div class="mt-3">
                                    Users <span class="float-end count"><?= $s->noofusers ?></span>
                                </div>
                                <h5 class="mt-3"><b>Feature</b> </h5>
                                <div class="my-2">Erat dui perspiciatis maiores congue amet eiusmod quia placerat sodales, eiusmod vulputate tristique</div>
                                <div class="price">
                                    <div class="">Price/month <span><?= $s->monthlychargesincltax ?></span></div>
                                    <button class="btn btn-primary w-100 my-1 btn-config" type="button" data-duration="month" data-packageid="<?= $s->code ?>">Pay Now</button>
                                </div>
                                <div class="price">
                                    <div class="">Price/year <span><?= $s->yearlychargesincltax ?></span></div>
                                    <button class="btn btn-primary w-100 my-1 btn-config" type="button" data-duration="year" data-packageid="<?= $s->code ?>">Pay Now</button>
                                </div>
                                <form action="<?= base_url('register') ?>" method="post">
                                    <input type="hidden" name="trialPlan" readonly value="1">
                                    <input type="hidden" name="category" readonly value="<?= strtolower($s->packageFor) ?>">
                                    <input type="hidden" name="plandetails" readonly value="" />
                                    <button class="btn btn-outline-primary w-100 my-1">Free Trial</button>
                                </form>
                            </div>
                        </div>
                    </div>
            <?php
                }
            }
            ?>
            <div class="col-lg-2"></div>
        </div>
    </div>
    <div>
        <img src="<?= base_url("assets/images/bg/planimg.svg") ?>" class="img-fluid" alt="">
    </div>
</section>
<div class="modal fade" id="configPlan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Configure Plan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body planBody">
            </div>
        </div>
    </div>
</div>
</div>
<script>
    let planBody = $(".planBody");
    $(document).on("click", "button.btn-config", function(e) {
        e.preventDefault();
        var packageCode = $(this).data("packageid");
        var duration = $(this).data("duration");
        var btn = $(this);
        if (packageCode !== "" && packageCode !== undefined) {
            planBody.empty();
            $.ajax({
                type: "get",
                url: "<?= base_url('comman/getPlan') ?>",
                data: {
                    packageCode: packageCode,
                    duration: duration
                },
                beforeSend: function() {
                    btn.attr("disabled", true);
                },
                success: function(response) {
                    if (response !== "") {
                        planBody.html(response);
                        $("#configPlan").modal("show");
                    }
                },
                error: function() {
                    planBody.empty();
                    btn.removeAttr("disabled");
                }
            });
        }
    });

    $(document).on("click", ".btn-close", function(e) {
        $("button.btn-config").removeAttr("disabled");
    });

    function calculation() {
        var duration = $("input[name='duration']").val();
        var planPrice = Number($("input[name='planPrice']").val());

        var perUserPrice = Number($("input[name='perUserPrice']").val());
        var addonUsers = Number($("input[name='addonUsers']").val());
        var addonUserPrice = $("input[name='addaddonUserPrice']");

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
        calculation();
    });

    $(document).on("change", "#morebranches", function(e) {
        if ($(this).is(":checked")) {
            $("button.minusbranch").removeAttr("disabled");
            $("button.plusbranch").removeAttr("disabled");
        } else {
            $("button.minusbranch").attr("disabled", true);
            $("button.plusbranch").attr("disabled", true);
        }
        calculation();
    });

    $(document).on("click", "button.minususer", function(e) {
        e.preventDefault();
        if ($("input[name='moreusers']").is(":checked")) {
            var count = Number($("input[name='addonUsers']").val());
            if (count === 1) {
                return false;
            }
            $("input[name='addonUsers']").val(count - 1);
            calculation();
            return;
        } else return false;
    });

    $(document).on("click", "button.plususer", function(e) {
        e.preventDefault();
        if ($("input[name='moreusers']").is(":checked")) {
            var count = Number($("input[name='addonUsers']").val());
            if (count >= 100) {
                return false;
            }
            $("input[name='addonUsers']").val(count + 1);
            calculation();
            return;
        } else {
            return false;
        }
    });

    $(document).on("click", "button.minusbranch", function(e) {
        e.preventDefault();
        if ($("input[name='morebranches']").is(":checked")) {
            var count = Number($("input[name='addonBranches']").val());
            if (count === 1) {
                return false;
            }
            $("input[name='addonBranches']").val(count - 1);
            calculation();
            return;
        } else return false;
    });

    $(document).on("click", "button.plusbranch", function(e) {
        e.preventDefault();
        if ($("input[name='morebranches']").is(":checked")) {
            var count = Number($("input[name='addonBranches']").val());
            if (count >= 100) {
                return false;
            }
            $("input[name='addonBranches']").val(count + 1);
            calculation();
            return;
        } else return false;
    });
</script>