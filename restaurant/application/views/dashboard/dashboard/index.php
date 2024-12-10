<div class="page-heading m-5">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h5 class="page-title">Inventory Dashboard (Updates in Every 60 sec)</h5>
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
                                    <div class="stats-icon green mb-2">
                                        <i class="iconly-boldAdd-User"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Suppliers</h6>
                                    <h6 class="font-extrabold mb-0" id="countSupplier">-</h6>
                                </div>
                                <div class="col-sm-12 text-right">
                                    <a class='btn btn-md btn-light-secondary font-bold mt-3' href="<?= base_url(); ?>supplier/listRecords">View</a>
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
                                        <i class="iconly-boldArrow---Right-Circle "></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Items</h6>
                                    <h6 class="font-extrabold mb-0" id="countItem">-</h6>
                                </div>
                                <div class="col-sm-12 text-right">
                                    <a class='btn btn-light-secondary font-bold mt-3' href="<?= base_url(); ?>item/listRecords">View</a>
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
                                    <h6 class="text-muted font-semibold">Net Purchase</h6>
                                    <h6 class="font-extrabold mb-0" id="totalPurchases">10</h6>
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
                                        <i class="iconly-boldCategory"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Extras/Product</h6>
                                    <h6 class="font-extrabold mb-0" id="countProduct">-</h6>
                                </div>
                                <div class="col-sm-12 text-right">
                                    <a class='btn btn-md btn-light-secondary font-bold mt-3' href="<?= base_url(); ?>product/listRecords">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-12">
            <div class="row">
                <div class="col-sm-5">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="p-2">Category Wise Stock</h5>
                            <canvas id="chart_1"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-sm-7">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="p-2">Inward List</h5>
                            <div class="table-responsive">
                                <table class="table table-striped table-responsive display" style="width:100%" id="dataTableInward">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Inward Date</th>
                                            <th>Branch</th>
                                            <th>Supplier</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                </table>
                                <div class="col-sm-12 text-right">
                                    <a class='btn btn-md btn-light-secondary font-bold mt-3' href="<?= base_url(); ?>inward/listRecords">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<script>
    var i = 0;
    $("#timeOut").text(i);
    var chart1 = null;

    function getChartData() {
        $.ajax({
            url: base_path + "/Dashboard/getCategoryWiseStock",
            method: "GET",
            dataType: 'JSON',
            success: function(response) {
                drawChart_1(response['data']['label'], response['data']['data'], response['data']['color']);
            }
        });
    }

    function drawChart_1(label, data, color) {
        var ctx = document.getElementById("chart_1");
        if (chart1) chart1.destroy();
        if (label !== null || data !== undefined) {
            chart1 = new Chart(ctx, {
                type: "pie",
                data: {
                    labels: label,
                    datasets: [{
                        data: data,
                        backgroundColor: color,
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        display: false
                    },
                    title: {
                        display: false,
                    }
                }
            });
        }
    }

    function getOrderCounts() {
        $.ajax({
            type: "get",
            url: base_path + "/Dashboard/getOrderCounts",
            data: {},
            success: function(response) {
                if (response) {
                    var res = JSON.parse(response);
                    $("#countItem").text(res['countItem']);
                    $("#countProduct").text(res['countProduct']);
                    $("#countSupplier").text(res['countSupplier']);
                    $("#totalPurchases").text(res['totalPurchase']);
                }
            }
        });
    }

    function getDataTable() {
        $.fn.DataTable.ext.errMode = 'none';
        if ($.fn.DataTable.isDataTable("#dataTableInward")) {
            $('#dataTableInward').DataTable().clear().destroy();
        }
        var dataTable = $('#dataTableInward').DataTable({
            stateSave: true,
            responsive: true,
            lengthMenu: [5],
            processing: true,
            scrollX: true,
            serverSide: true,
            ordering: true,
            searching: true,
            paging: true,
            ajax: {
                url: base_path + "Dashboard/getInwardList",
                data: {},
                type: "GET",
                complete: function(response) {
                    //operations();
                }
            }
        });
    }


    $(document).ready(function() {
        getDataTable();
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
        getChartData();
    });
</script>