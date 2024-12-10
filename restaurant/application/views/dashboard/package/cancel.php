<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancelled | Supermarket - TKMN POS</title>
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
                <h3>Supermarket (KAEM Software)</h3>
                <h4>Transaction Cancelled...</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 offset-md-3 col-lg-4 offset-lg-2">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 mb-2">
                                <div>Receipt No</div>
                                <div><b></b></div>
                            </div>
                            <div class="col-sm-12 mb-2">
                                <div>Txn No</div>
                                <div><b></b></div>
                            </div>
                            <div class="col-sm-12 mb-2">
                                <div>Date</div>
                                <div><b></b></div>
                            </div>
                            <div class="col-sm-12 mb-2">
                                <div>Status</div>
                                <div><b></b></div>
                            </div>
                            <div class="col-sm-12 mb-2">
                                <a href"<?= base_url("dashboard/listRecords") ?>" class="btn btn-primary w-100">Okay & Exit</a>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    <script src="<?= base_url() ?>assets/extensions/jquery/jquery.min.js"></script>
    <script src="<?= base_url() ?>assets/js/bootstrap.js"></script>
    <script src="<?= base_url() ?>assets/js/app.js"></script>
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