<style>
    .fadeing {
        animation-name: fadein;
        animation-duration: 1.5s;
        animation-timing-function: linear;
        animation-delay: 1s;
        animation-iteration-count: infinite;
        animation-direction: alternate;
    }

    @keyframes fadein {
        0% {
            opacity: 0.4;
        }

        50% {
            opacity: 1;
        }

        100% {
            opacity: 0.4;
        }
    }
</style>
<?php
if ($this->session->userdata('companyCode') !== null) {
    $companyCode = $this->session->userdata("companyCode");
?>
    <div class="d-flex align-items-center justify-content-center vh-100" style="background: #7367F0;">
        <div class="container col-md-6 col-lg-4">
            <div class="card bg-white">
                <div class="card-body">
                    <div class="mb-3">
                        <h4 class="mb-0"> KAEM Software </h4>
                        <b class="text-muted">Application Setup</b>
                        <br />
                    </div>
                    <?php
                    if ($this->session->flashdata("done")) {
                        $msg = $this->session->flashdata("done");
                    ?>
                        <div class="alert alert-success d-flex align-items-center doneres" role="alert">
                            <i class="fa fa-checked"></i>
                            <div> <?= $msg ?></div>
                        </div>
                    <?php
                    }
                    ?>
                    <div class="response"></div>
                    <form id="setupform">
                        <input type="hidden" readonly name="companyCode" value="<?= $companyCode ?>">
                        <img src="<?= base_url("assets/images/processing.png") ?>" class="img-fluid fadeing" alt="Waiting (undrow)">
                        <p>We are installing your application and setting few things up. <br /><small class="text-danger">Please do not close this window untill prompted.</small></p>
                        <button type="button" class="btn btn-primary w-100 btnprocess">Hold on <i class="fa fa-spinner fa-spin"></i></button>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <script>
        const btnprocess = $("button.btnprocess");
        const response = $("div.response");
        const setupform = $("#setupform");

        function setupapplicaiton() {
            $.ajax({
                url: "<?= base_url("setup/app") ?>",
                type: "POST",
                time: 0,
                dataType: "JSON",
                data: {
                    companyCode: $("input[name='companyCode']").val(),
                },
                beforeSend: function() {
                    setupform.fadeIn();
                    $("div.response").html("");
                    btnprocess.attr("disabled", true);
                },
                success: function(res) {
                    if (res.err === 200) {
                        setupform.fadeOut();
                        btnprocess.text = "Done...";
                        $("div.response").addClass("alert").addClass("alert-success");
                        $("div.response").html(res.msg);
                        setTimeout(() => {
                            location.replace("<?= base_url() ?>");
                        }, 5000);
                    } else {
                        $("div.response").addClass("alert").addClass("alert-danger")
                        $("div.response").html(res.msg);
                        btnprocess.text = "Failed. Try again?";
                        btnprocess.removeAttr("disabled");
                    }
                },
                complete: function() {
                    $(".doneres").remove();
                },
                error: function() {
                    response.addClass("alert-danger")
                    $("div.response").html("Something went wrong while setting up your application");
                }
            })
        }
        $(function() {
            setTimeout(() => {
                setupapplicaiton();
            }, 1000);
        });
    </script>
<?php
} else {
?>
    <div class="d-flex align-items-center justify-content-center vh-100" style="background: #ededed;">
        <div class="container col-md-6 col-lg-4">
            <div class="card bg-white">
                <div class="card-body">
                    <div class="mb-3">
                        <h4 class="mb-0"> KAEM Software </h4>
                        <b class="text-muted">Application Setup</b>
                        <br />
                    </div>
                    <div class="alert alert-danger" role="alert">
                        <h5><i class="fa fa-times"></i> Opps!</h5>
                        <p>Your session has been expired or you have landed here unknowingly. We are unbale to process your request.</p>
                    </div>
                    <a href="<?= base_url() ?>" class="btn btn-outline-danger w-100"> Go Back </a>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>