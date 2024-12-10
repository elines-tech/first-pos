<div class="page-heading m-5">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h5 class="page-title">Dashboard (Updates in Every 60 sec)</h5>
                <div class="d-flex align-items-center">

                </div>
            </div>
            <div class="col-5 text-right">
                <h4><span id="timeOut"></span>'s</h4>
            </div>
        </div>
    </div>
</div>
<div class="page-content m-5">
    <section class="row">
        <div class="col-12 col-lg-12">
            <div class="row">
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon purple mb-2">
                                        <i class="iconly-boldArrow---Right-Circle "></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Orders</h6>
                                    <h6 class="font-extrabold mb-0" id="countOrders">-</h6>
                                </div>
                                <div class="col-sm-12 text-right">
                                    <a class='btn btn-light-secondary font-bold mt-3' href="#">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon green mb-2">
                                        <i class="iconly-boldCategory"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Dining</h6>
                                    <h6 class="font-extrabold mb-0">10</h6>
                                </div>
                                <div class="col-sm-12 text-right">
                                    <a class='btn btn-md btn-light-secondary font-bold mt-3' href="#">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon purple mb-2">
                                        <i class="iconly-boldHome"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Pickup</h6>
                                    <h6 class="font-extrabold mb-0">10</h6>
                                </div>
                                <div class="col-sm-12 text-right">
                                    <a class='btn btn-md btn-light-secondary font-bold mt-3' href="#">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon green mb-2">
                                        <i class="iconly-boldAdd-User"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Delivery</h6>
                                    <h6 class="font-extrabold mb-0">10</h6>
                                </div>
                                <div class="col-sm-12 text-right">
                                    <a class='btn btn-md btn-light-secondary font-bold mt-3' href="#">View</a>
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
    var i = 0;
    $("#timeOut").text(i);

    function getOrderCounts() {
        $.ajax({
            type: "get",
            url: base_path + "/Dashboard/getInventoryCounts",
            data: {},
            success: function(response) {
                if (response) {
                    var res = JSON.parse(response);
                    $("#countOrders").text(res['countOrders']);

                }
            }
        });
    }

    $(document).ready(function() {
        setInterval(function(e) {
            if (i == 30) {
                i = 0;
                $("#timeOut").text(i);
                //getOrderCounts();
            } else {
                i++;
                $("#timeOut").text(i);
            }
        }, 1000);
        getOrderCounts();
    });
</script>