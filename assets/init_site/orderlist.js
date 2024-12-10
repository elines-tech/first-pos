let orders = [];
const kotOrdersRow = $("#kot-orders-row");

function fetchOrders() {
	$.ajax({
		type: "GET",
		url: base_path + "Api/order/fetchOrders",
		dataType: "JSON",
		beforeSend: function () {},
		success: function (response) {
			if (response.status === "200") {
				orders = response.data;
				setTimeout(() => {
					setOrderUI();
				}, 300);
			}
		},
	});
}

function setOrderUI() {
	if (orders.length > 0) {
		for (let index = 0; index < orders.length; index++) {
			const order = orders[index];
			const htmlRender = renderOrderHtmlCard(order, index);
			kotOrdersRow.append(htmlRender);
		}
	}
}

function renderOrderHtmlCard(order,index) {
	var html = "";

	if (order.kots.length > 0) {
		var kots = order.kots;
		var kottags = "";
		var badgeStatus = "";
		kots.forEach((kot) => {
			switch (kot.status) {
				case "PND":
					badgeStatus = '<b class="badge badge-pnd"><i class="fa fa-square-o"></i> Pending</b>';
					break;
				case "PRE":
					badgeStatus = '<b class="badge badge-pre"><i class="fa fa-circle-o-notch"></i> Preparing</b>';
					break;
				case "RTS":
					badgeStatus = '<b class="badge badge-rts"><i class="fa fa-check-square-o"></i> Ready to Serve</b>';
					break;
			}
			
			kottags += `
            <div class="box"> 
                <div class="box-head">
                    <div class="row g-0">
                        <div class="col-6 col-sm-6"> KOT No. : <b>${kot.kotNumber}</b></div> 
                        <div class="col-6 col-sm-6"> Status : ${badgeStatus}</div>     
                    </div>
                </div>
                <div class="box-foot">
                    <button type="button" class="box-button kotDetails" id="kotBtn" data-kot="${kot.kotNumber}" data-seq="${kot.id}"> View Details </button>
                </div>
            </div>`;
		});

		var form = `
            <form action="${base_path}order/add" method="post" class="ms-1">
                <input type="hidden" id="branchCode${index}" name="branchCode" value="${order.branchCode}" readonly/>
                <input type="hidden" id="tableNumber${index}" name="tableNumber" value="${order.tableNumber}" readonly/>
                <input type="hidden" id="tableSection${index}" name="tableSection" value="${order.tableSection}" readonly/>
                <input type="hidden" id="custPhone${index}" name="custPhone" value="${order.custPhone}" readonly/>
                <input type="hidden" name="custName" value="${order.custName}" readonly/>
                <button type="submit" class="btn btn-sm btn-primary">New Order</button>
            </form>
        `;
        
		html = `
            <div class="col-sm-6 col-md-4">
                <div class="card">
                    <div class="card-header">
                        ORDER  <br/>
                        Customer Phone <span class="float-end"><b>${order.custPhone}</b></span>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item">
                                Table Section <span class="float-end">${order.zoneName}</span>
                            </li>
                            <li class="list-group-item">
                                Table Number <span class="float-end">${order.tblNo}</span>
                            </li> 
                            <li class="list-group-item">
                                Customer Name <span class="float-end">${order.custName}</span>
                            </li> 
                        </ul>
                        <div class="mt-2">
                            ${kottags}
                        </div>
                    </div>
                    <div class="card-footer p-2">
                        <div class="d-flex">
                            <button type="button" class="btn btn-sm btn-danger orderDetails" id="orderBtn" data-index="${index}"> View Order</button>
                            ${form}
                        </div>
                    </div>                
                </div>
            </div>
        `;
	}
	return html;
}

$(function () {
	fetchOrders();
});
 $("body").on("click", ".kotDetails", function(e) {
    var cartId = $(this).data('seq');
    var kotNumber = $(this).data('kot');
	var btn = $(this);
    $.ajax({
        url: base_path + "order/getKotDetails",
        type: 'POST',
        data: {
            'cartId': cartId,
        },
		beforeSend: function () {
			btn.prop("disabled", true);
			btn.html('Wait <i class="fa fa-spinner spin"></i>...');
		},
        success: function(response) {
			btn.prop("disabled", false);
			btn.html('View Details');
			if(response!=''){
				$('#generl_modal').modal('show');
				$('#kotId').html('KOT Number: '+kotNumber)
				$(".panel-body").html(response);
			}
        }
    });
});

    $("body").on("click", ".orderDetails", function(e) {
		var btn = $(this);
        var index = $(this).data('index');
        var branchCode = $('#branchCode'+index).val();
        var tableSection = $('#tableSection'+index).val();
        var tableNumber = $('#tableNumber'+index).val();
        var custPhone = $('#custPhone'+index).val();
        $.ajax({
            url: base_path + "order/getOrderDetails",
            type: 'POST',
            data: {
                'branchCode': branchCode,
                'tableSection': tableSection,
                'tableNumber': tableNumber,
                'custPhone': custPhone,
            },
			beforeSend: function () {
				btn.prop("disabled", true);
				btn.html('Wait <i class="fa fa-spinner spin"></i>...');
			},
            success: function(response) {
				btn.prop("disabled", false);
				btn.html('View Order');
				var obj = JSON.parse(response)
				if(obj.status){
					$('#generl_modal1').modal('show');
					$('.select2').select2();
					$(".panel-body1").html(obj.orderHtml);
				}else{
					swal("No data found", "Failed", "error");
						
				}
            }
        });
    });
	



