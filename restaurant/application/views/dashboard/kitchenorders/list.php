<nav class="navbar navbar-light">
    <div class="container d-block">
        <div class="row">
            <!--<div class="col-12 col-md-6 order-md-1 order-last"><a href="index.php"><i class="fa fa-times fa-2x"></i></a></div>-->
        </div>
    </div>
</nav>

<div class="container" id="addKitchenOrders">

</div>

<script src="<?= base_url() ?>assets/js/bootstrap.js"></script>
<script src="<?= base_url() ?>assets/js/app.js"></script>

<!-- Need: Apexcharts -->
<script src="<?= base_url() ?>assets/extensions/apexcharts/apexcharts.min.js"></script>
<script src="<?= base_url() ?>assets/js/pages/dashboard.js"></script>

<script src="<?= base_url() ?>assets/extensions/jquery/jquery.min.js"></script>
<script src="<?= base_url() ?>assets/extensions/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="<?= base_url() ?>assets/js/pages/form-element-select.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.js"></script>
<script src="<?= base_url() ?>assets/js/pages/datatables.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>assets/js/webix.js">


<script src="<?= base_url() ?>assets/extensions/parsleyjs/parsley.min.js"></script>
<script src="<?= base_url() ?>assets/js/pages/parsley.js"></script>


<script src="<?php echo base_url() . 'assets/admin/assets/libs/toastr/build/toastr.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/admin/assets/libs/sweetalert2/dist/sweet-alert.min.js'; ?>"></script>

<script>
    $(window).on('load', function() { // makes sure the whole site is loaded 
        $('#status').fadeOut(); // will first fade out the loading animation 
        $('#preloader').delay(250).fadeOut('slow'); // will fade out the white DIV that covers the website. 
        $('body').delay(250).css({
            'overflow': 'visible'
        });
    });
</script>

<script>
    var i = 0;

    function getKitchenOrder() {
        $.ajax({
            type: "get",
            url: base_path + "KitchenOrders/getKitchenOrder",
            data: {},
            success: function(response) {
                //debugger;
                if (response) {
                    $("#addKitchenOrders").empty();
                    $("#addKitchenOrders").append(response);
                }
            }
        });
    }
    $(document).ready(function() {

        $("body").on("click", ".actionBtn", function(e) {
            var orderCode = $(this).data("id");
            var orderStatus = $(this).data("status");
            var dataAction = $(this).data("action");
            swal({
                title: "Are you sure?",
                text: dataAction,
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ok",
                cancelButtonText: "Cancel",
                closeOnConfirm: !1,
                closeOnCancel: !1
            }, function(e) {
                if (e) {
                    $.ajax({
                        url: base_path + "KitchenOrders/updateOrderStatus",
                        type: 'POST',
                        data: {
                            'orderCode': orderCode,
                            'orderStatus': orderStatus
                        },
                        success: function(response) {
                            var res = JSON.parse(response);
                            if (res.status) {
                                swal({
                                        title: "Order",
                                        text: res.message,
                                        type: "success"
                                    },
                                    function(isConfirm) {
                                        if (isConfirm) {
                                            getKitchenOrder();
                                        }
                                    });
                            } else {
                                toastr.error(res.message, 'Order', {
                                    "progressBar": true
                                });
                                getKitchenOrder();
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            var errorMsg = 'Ajax request failed: ' + xhr.responseText;
                            alert(errorMsg);
                        }
                    });
                } else {
                    swal("Cancelled", "Failed to update the order...", "error");
                }
            });
        });


        $("body").on("click", ".orderDetails", function(e) {
            var orderCode = $(this).data('seq');
            $.ajax({
                url: base_path + "KitchenOrders/getOrderDetails",
                type: 'POST',
                data: {
                    'orderCode': orderCode,
                },
                success: function(response) {
                    $('#generl_modal').modal('show');
                    $(".panel-body").html(response);
                }
            });
        });

        //loadTable();   
        /* setInterval(function(e) {
             //console.log("Time = ", i);
             if (i == 60) {
                 i = 0;
                 //$("#timeOut").text(i);
                 getKitchenOrder();
             } else {
                 i++;
                 //$("#timeOut").text(i);
             }
         }, 1000);*/
        getKitchenOrder();
    });
</script>

</body>

</html>