<div id="register">
    <header class="header">
        <nav class="navbar navbar-expand-lg navbar-light py-3">
            <div class="container">
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
                        <h5> Transaction Failed </h5>
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <small>Payment ID (Transction ID)</small>
                                <div><b><?= $paymentId ?></b></div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <small>Receipt No.</small>
                                <div><b><?= $receiptId ?></b></div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <small>Amount</small>
                                <div><b><?= $amount ?></b></div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <small>Transaction Status</small>
                                <div><b><?= ucwords($transactionStatus) ?></b></div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <small>Transaction Status</small>
                                <div><b><?= date('d/M/Y h:i A', strtotime($transactionDate)) ?></b></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3 text-center">
                <button id="btn" class="btn btn-lg btn-danger"> Go Back </button>
            </div>
        </div>
    </div>
</div>
<script>
    const btn = document.querySelector("button#btn");
    btn.addEventListener("click", click);

    function click(e) {
        window.location.replace("<?= base_url("/") ?>");
    }
</script>