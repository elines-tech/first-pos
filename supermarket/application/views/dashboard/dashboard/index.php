<?php include '../supermarket/config.php'; ?>


<div class="page-heading m-4">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h5 class="page-title">Dashboard </h5>
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


                <div class="col-6 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon red mb-2">
                                        <i class="iconly-boldArrow---Right-Circle "></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 mb-10 col-xxl-7">
                                    <h6 class="text-muted font-semibold"><?php echo $translations['Orders']?></h6>
                                    <h6 class="font-extrabold mb-0"><?= $countOrders?></h6>
                                </div>
								 <div id="view" class="col-sm-12 text-right">
                                    <a class='btn btn-md btn-light-secondary font-bold mt-3' href="<?= base_url(); ?>order/listRecords"><?php echo $translations['View']?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-6 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon blue mb-2">
                                        <i class="iconly-boldCategory"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 mb-10 col-xxl-7">
                                    <h6 class="text-muted font-semibold"><?php echo $translations['Brand']?></h6>
                                    <h6 class="font-extrabold mb-0"><?= $countBrand?></h6>
                                </div>
                                 <div id="view" class="col-sm-12 text-right">
                                    <a class='btn btn-md btn-light-secondary font-bold mt-3' href="<?= base_url(); ?>brand/listRecords"><?php echo $translations['View']?></a>
                                </div>
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
                                <div class="col-md-8 col-lg-12 col-xl-12 mb-10 col-xxl-7">
                                    <h6 class="text-muted font-semibold"><?php echo $translations['Category']?></h6>
                                    <h6 class="font-extrabold mb-0"><?= $countCategory ?></h6>
                                </div>
                                <div id="view" class="col-sm-12 text-right">
                                    <a class='btn btn-md btn-light-secondary font-bold mt-3' href="<?= base_url(); ?>category/listRecords"><?php echo $translations['View']?></a>
                                </div>
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
                                        <i class="iconly-boldCategory"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 mb-10 col-xxl-7">
                                    <h6 class="text-muted font-semibold"><?php echo $translations['Products']?></h6>
                                    <h6 class="font-extrabold mb-0"><?= $countProduct?></h6>
                                </div>
                                <div id="view" class="col-sm-12 text-right">
                                    <a class='btn btn-md btn-light-secondary font-bold mt-3' href="<?= base_url(); ?>product/listRecords"><?php echo $translations['View']?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


				
				  <div class="col-6 col-lg-6 col-md-6 mt-2">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon red mb-2">
                                        <i class="iconly-boldWallet"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 mb-10 col-xxl-7">
                                    <h6 class="text-muted font-semibold"><?php echo $translations['Supplier']?></h6>
                                    <h6 class="font-extrabold mb-0" id="totalSale"><?= $countSupplier?></h6>
                                </div>
                                <div id="view" class="col-sm-12 text-right">
                                    <a class='btn btn-md btn-light-secondary font-bold mt-3' href="<?= base_url(); ?>supplier/listRecords"><?php echo $translations['View']?></a>
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
  
    function getOrderCounts() {
        $.ajax({
            type: "get",
            url: base_path + "/Dashboard/getSalesCount",
            data: {},
            success: function(response) {
                if (response) {
                    var res = JSON.parse(response);
                    $("#countOrders").text(res['countOrders']);
                    $("#countDining").text(res['countOrders']);
					$("#totalSale").text(res['totalSale']);
					$("#totalDiscount").text(res['totalDiscount']);
					$("#totalTax").text(res['totalTax']);
					$("#totalCustomer").text(res['totalCustomer']);
                }
            }
        });
    }

    $(document).ready(function() {
        
        getOrderCounts();
    });
</script>