<?php include '../supermarket/config.php'; ?>

<div class="page-heading m-4">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h5 class="page-title"><?php echo $translations['Dashboard']?></h5>
                <div class="d-flex align-items-center">

                </div>
            </div>

        </div>
    </div>
</div>
<div class="page-content m-4">



    <section class="row col-12 col-lg-12 d-flex align-items-center justify-content-center text-center" style="height: 50vh;">
        <div class="col-12 col-lg-12 d-flex flex-wrap justify-content-center text-center">

            <div class="card col-12 col-md-6 p-5 d-flex justify-center text-center m-3">

                <div class="col-md-12 col-lg-12 mb-2 col-xl-12 d-flex flex-column align-items-center justify-content-center">
                    <div class="stats-icon blue mb-2 d-flex justify-content-center align-items-center" style="height: 50px; width: 50px;">
                        <i class="iconly-boldAdd-User"></i>
                    </div>
                </div>

                <div class="col-md-12 col-lg-12 col-xl-12">
                    <h6 class="text-muted font-semibold"><?php echo $translations['Total Orders']?></h6>
                    <h6 class="font-extrabold mb-0" id="totalOrders">-</h6>
                </div>

            </div>





            <div class="card col-12 col-md-6 p-5 d-flex justify-center text-center m-3">

                <div class="col-md-12 col-lg-12 mb-2 col-xl-12 d-flex flex-column align-items-center justify-content-center">
                    <div class="stats-icon red mb-2 d-flex justify-content-center align-items-center" style="height: 50px; width: 50px;">
                        <i class="iconly-boldArrow---Right-Circle "></i>
                    </div>
                </div>

                <div class="col-md-12 col-lg-12 col-xl-12">
                    <h6 class="text-muted font-semibold"><?php echo $translations['Today\'s Order']?></h6>
                    <h6 class="font-extrabold mb-0" id="todaysOrders">-</h6>
                </div>

            </div>




        </div>
</div>
</section>
</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<script>
    function getOrderCounts() {
        $.ajax({
            type: "get",
            url: base_path + "/Cashier/Dashboard/getOrderCounts",
            data: {},
            success: function(response) {
                if (response) {
                    var res = JSON.parse(response);
                    $("#totalOrders").text(res['totalOrders']);
                    $("#todaysOrders").text(res['todaysOrders']);

                }
            }
        });
    }
    $(document).ready(function() {
        getOrderCounts();
    });
</script>