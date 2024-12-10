<div id="register">
    <!-- Navbar-->
    <header class="header">
        <nav class="navbar navbar-expand-lg navbar-light py-3">
            <div class="container">
                <!-- Navbar Brand -->
                <a href="#" class="navbar-brand">
                    <img src="<?= base_url('assets/images/logo/logo-white.svg') ?>" alt="logo" width="60">
                </a>
            </div>
        </nav>
    </header>

    <div class="container">
        <div class="row py-2 mt-2 align-items-center">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="alert bg-success">
                            <div>
                                <h3>Yay!</h3>
                            </div>
                            <div>
                                <?= $msg ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div>Date : <b><?= date('d/M/Y h:i A', strtotime($date)) ?></b></div>
                            </div>
                            <div class="col-md-12">
                                <div>Receipt No. : <b><?= $receiptId ?></b></div>
                            </div>
                            <div class="col-md-12">
                                <div>Please note down above receipt number and transaction date.</div>
                            </div>
                            <div class="col-md-12 text-center">
                                <a class="btn btn-outline-success d-block" href="<?= base_url('/') ?>"><i class="bi bi-arrow-left"></i> Go Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        setTimeout(() => {
            window.location.replace("<?= base_url('/') ?>");
        }, 5000);
    });
</script>