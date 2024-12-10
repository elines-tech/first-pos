<div class="page-heading m-4">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h5 class="page-title">Dashboard</h5>
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
				 
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon green mb-2">
                                        <i class="iconly-boldAdd-User"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">Total Orders</h6>
                                    <h6 class="font-extrabold mb-0" id="totalOrders">-</h6>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon purple mb-2">
                                        <i class="iconly-boldArrow---Right-Circle "></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">Today's Order</h6>
                                    <h6 class="font-extrabold mb-0" id="todaysOrders">-</h6>
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