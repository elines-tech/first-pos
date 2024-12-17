<div class="page-heading m-5">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h5 class="page-title">Sales Dashboard (Updates in Every 60 sec)</h5>
                <div class="d-flex align-items-center">

                </div>
            </div>
            <div class="col-5 text-right">
                <h4><span id="timeOut"></span>'s</h4>
            </div>
        </div>
		<div class="row d-flex justify-content-center text-center">
		    <!--<div class="col-4 ">-->
            <label class="form-label text-nowrap mr-5">Current showing data for all branches.</label>
            <div class="col-12 d-flex text-center justify-content-center">
			   <?php if($branchCode != "") { ?>
					<input type="hidden" class="form-control" name="branchCode" id="branchCode" value="<?= $branchCode; ?>" readonly>
					<input type="text" class="form-control" name="branch" value="<?= $branchName; ?>" readonly>
				<?php } else { ?>
			   <select class="form-select select2" name="branchCode" id="branchCode">
					<option value="">All Branch</option>
					<?php if ($branchdata) {
						foreach ($branchdata->result() as $branch) {
					?>
							<option value="<?= $branch->code ?>"><?= $branch->branchName ?></option>
					<?php
						}
					} ?>
				</select>
				<?php } ?>
			</div>
		</div>
    </div>
</div>
<div class="page-content m-5">
    <section class="row">

        <div class="col-12 col-lg-12">
            <div class="row">
                
                <div class="col-6 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon red mb-2">
                                        <i class="iconly-boldArrow---Right-Circle "></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Today's Orders</h6>
                                    <h6 class="font-extrabold mb-0" id="countOrders">-</h6>
                                </div>
                                <!--<div class="col-sm-12 text-right">
                                    <a class='btn btn-light-secondary font-bold mt-3' href="#">View</a>
                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">  
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon red mb-2">
                                        <i class="iconly-boldCategory"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Today's Dining</h6>
                                    <h6 class="font-extrabold mb-0" id="countDining">-</h6>
                                </div>
                                <!--<div class="col-sm-12 text-right">
                                    <a class='btn btn-md btn-light-secondary font-bold mt-3' href="#">View</a>
                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon red mb-2">
                                        <i class="iconly-boldHome"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Today's Pickup</h6>
                                    <h6 class="font-extrabold mb-0" id="countPickup">-</h6>
                                </div>
                                <!--<div class="col-sm-12 text-right">
                                    <a class='btn btn-md btn-light-secondary font-bold mt-3' href="#">View</a>
                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon blue mb-2">
                                        <i class="iconly-boldAdd-User"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Today's Delivery</h6>
                                    <h6 class="font-extrabold mb-0" id="countDeliver">-</h6>
                                </div>
                                <!--<div class="col-sm-12 text-right">
                                    <a class='btn btn-md btn-light-secondary font-bold mt-3' href="#">View</a>
                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
				
				  <div class="col-6 col-lg-6 col-md-6 mt-2">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon blue mb-2">
                                        <i class="iconly-boldWallet"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Today's Sale</h6>
                                    <h6 class="font-extrabold mb-0" id="totalSale">-</h6>
                                </div>
                                <!--<div class="col-sm-12 text-right">
                                    <a class='btn btn-light-secondary font-bold mt-3' href="#">View</a>
                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-6 col-md-6 mt-2">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon green mb-2">
                                        <i class="iconly-boldDiscount"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Today's Discount</h6>
                                    <h6 class="font-extrabold mb-0" id="totalDiscount">-</h6>
                                </div>
                                <!--<div class="col-sm-12 text-right">
                                    <a class='btn btn-md btn-light-secondary font-bold mt-3' href="#">View</a>
                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-6 col-md-6 mt-2">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon green mb-2">
                                        <i class="iconly-boldWallet"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Today's Tax</h6>
                                    <h6 class="font-extrabold mb-0" id="totalTax">-</h6>
                                </div>
                                <!--<div class="col-sm-12 text-right">
                                    <a class='btn btn-md btn-light-secondary font-bold mt-3' href="#">View</a>
                                </div>-->
                            </div> 
                        </div>
                    </div>
                </div>

                
                <div class="col-6 col-lg-12 col-md-6 mt-2">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon purple mb-2">
                                        <i class="iconly-boldAdd-User"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Today's Customer</h6>
                                    <h6 class="font-extrabold mb-0" id="totalCustomer">-</h6>
                                </div>
                                <!--<div class="col-sm-12 text-right">
                                    <a class='btn btn-md btn-light-secondary font-bold mt-3' href="#">View</a>
                                </div>-->
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
		var branchCode = $("#branchCode").val();
        $.ajax({
            type: "get",
            url: base_path + "/Dashboard/getSalesCount",
            data: {
					'branchCode': branchCode
			},
            success: function(response) {
                if (response) {
                   var res = JSON.parse(response);
                    $("#countOrders").text(res['countOrders']);
                    $("#countDining").text(res['countDining']);
					$("#countPickup").text(res['countPickup']);
					$("#countDeliver").text(res['countDeliver']);
					$("#totalSale").text(res['totalSale']);
					$("#totalDiscount").text(res['totalDiscount']);
					$("#totalTax").text(res['totalTax']);
					$("#totalCustomer").text(res['totalCustomer']);
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
		
		$("body").on("change", "#branchCode", function(e) {
		var branchCode = $("#branchCode").val();
		if (branchCode != '') {
			$.ajax({
				url: base_path + '/Dashboard/getSalesCount',
				data: {
					'branchCode': branchCode
				},
				type: 'get',
				success: function(response) {
					if (response) {	
                    var res = JSON.parse(response);
                    $("#countOrders").text(res['countOrders']);
                    $("#countDining").text(res['countDining']);
					$("#countPickup").text(res['countPickup']);
					$("#countDeliver").text(res['countDeliver']);
					$("#totalSale").text(res['totalSale']);
					$("#totalDiscount").text(res['totalDiscount']);
					$("#totalTax").text(res['totalTax']);
					$("#totalCustomer").text(res['totalCustomer']);
                  }
				}
			});
		} 
	   });
        
    });
</script>