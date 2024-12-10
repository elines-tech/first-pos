<nav class="navbar navbar-light">
    <div class="container d-block">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last"><a href="<?php echo base_url(); ?>order/listRecords"><i class="fa fa-times fa-2x"></i></a></div>
        </div>
    </div>
</nav>
<div class="container">
    <section id="multiple-column-form" class="mt-5 catproduct">
        <div id="maindiv" class="container">
			 <div class="row">
				 <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
					  <h2><a href="<?= base_url()?>order/add" ><i class="fa fa-plus-circle"></i></a></h2>
				 </div>
			</div>
        </div>
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">My Orders</h3>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
								<div class="col-md-12 col-12" id="ordersDiv">
								</div>
								<input type="hidden" class="form-control" id="tableCode" name="tableCode">
							</div>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
</body>




<script>
$(document).ready(function() {
		var tableCode=$('#tableCode').val();
        getOrderLists(tableCode);
   });
    function getOrderLists(tableCode){
		$.ajax({
			url: base_path + "order/getOrderLists",
			type:'POST',
			data:{
				'tableCode':tableCode
			},
			success: function(response) {
				var obj = JSON.parse(response)
				$('#ordersDiv').html('');
				$('#ordersDiv').append(obj.orderHtml);
			}
		});
	}
	 $("body").on("click", ".approve", function(e) {
            var orderCode = $(this).data("id");
            swal({
                title: "Are you sure you want to confirm this order?",
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
                        url: base_path + "order/updateOrderStatus",
                        type: 'POST',
                        data: {
                            'orderCode': orderCode,
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
                                            getOrderLists();
                                        }
                                    });
                            } else {
                                toastr.error(res.message, 'Order', {
                                    "progressBar": true
                                });
                                getOrderLists();
                            }
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
                url: base_path + "order/getOrderDetails",
                type: 'POST',
                data: {
                    'orderCode': orderCode,
                },
                success: function(response) {
					var obj = JSON.parse(response)
					if(obj.status){
						$('#generl_modal').modal('show');
						$(".panel-body").html(obj.orderHtml);
					}else{
						swal("No data found", "Failed", "error");
                    
					}
                }
            });
        });
</script>
