var i = 0;

$("#timeOut").text(i);
var branchID=$("#branchid").val();
function getOrderCounts() {
	$.ajax({
		type: "get",
		url: base_path + "kitchen/getKOTOrders",
		data: {branchID:branchID},
		success: function (response) {
			if (response) {
				$("#kot-Orders").empty();
				$("#kot-Orders").append(response);
			}
		},
	});
}

$(document).ready(function () {
	getOrderCounts();
	setInterval(function (e) {
		if (i == 60) {
			i = 0;
			$("#timeOut").text(i);
			getOrderCounts();
		} else {
			i++;
			$("#timeOut").text(i);
		}
	}, 1000);
});

$(document).on("click", "button.view-order-details", function (e) {
	e.preventDefault();
	var btn = $(this);
	var cartId = $(this).data("cart-id");
	var kotNumber = $(this).data("kotnumber");
	$.ajax({
		type: "POST",
		url: base_path + "Kitchen/getKotDetails",
		data: {
			cartId: cartId,
		},
		beforeSend: function () {
			btn.prop("disabled", true);
			btn.html('Wait <i class="fa fa-spinner spin"></i>...');
		},
		success: function (response) {
			if (response != "") {
				$("#generl_modal").modal("show");
				$("#kotId").html("KOT Number: " + kotNumber);
				$(".panel-body").html(response);
			}
		},
		complete: function () {
			btn.prop("disabled", false);
			btn.html("View Order");
		},
	});
});

$(document).on("click", "button.btn-preparing", function (e) {
	e.preventDefault();
	var btn = $(this);
	var cartId = $(this).data("cart-id");
	swal(
		{
			title: "Confirm Order?",
			text: "Do you want to set order status to preparing? The action is not reversible..",
			type: "warning",
			showCancelButton: !0,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Ok",
			cancelButtonText: "Cancel",
			closeOnConfirm: !1,
			closeOnCancel: !1,
		},
		function (e) {
			if (e) {
				$.ajax({
					type: "POST",
					url: base_path + "Api/kitchen/changeStatus",
					data: {
						cartId: cartId,
						kotStatus: "PRE",
					},
					dataType: "JSON",
					beforeSend: function () {
						btn.prop("disabled", true);
						btn.html('Wait <i class="fa fa-spinner spin"></i>');
					},
					success: function (response) {
						if (response.status === "200") {
							swal(
								{
									title: "Success",
									text: response.message,
									type: "success",
								},
								function (isConfirm) {
									if (isConfirm) {
										getOrderCounts();
									}
								}
							);
						} else {
							toastr.error(response.message, "Opps", {
								progressBar: true,
							});
							getOrderCounts();
						}
					},
				});
			} else {
				swal("Cancelled", "Failed to update the order...", "error");
			}
		}
	);
});

$(document).on("click", "button.btn-ready-serve", function (e) {
	var btn = $(this);
	var cartId = $(this).data("cart-id");
	swal(
		{
			title: "Ready to Serve?",
			text: "Do you want to change the status of KOT order to Ready to Serve? This action is not reversible.",
			type: "success",
			showCancelButton: !0,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Ok",
			cancelButtonText: "Cancel",
			closeOnConfirm: !1,
			closeOnCancel: !1,
		},
		function (e) {
			if (e) {
				$.ajax({
					type: "POST",
					url: base_path + "Api/kitchen/changeStatus",
					data: {
						cartId: cartId,
						kotStatus: "RTS",
					},
					dataType: "JSON",
					beforeSend: function () {
						btn.prop("disabled", true);
					},
					success: function (response) {
						if (response.status === "200") {
							swal(
								{
									title: "Success",
									text: response.message,
									type: "success",
								},
								function (isConfirm) {
									if (isConfirm) {
										setTimeout(() => {
											$("#kot-order-box-" + cartId).remove();
										}, 400);
									}
								}
							);
						} else {
							toastr.error(response.message, "Opps", {
								progressBar: true,
							});
							getOrderCounts();
						}
					},
					complete: function () {
						btn.prop("disabled", false);
					},
				});
			} else {
				swal("Cancelled", "Failed to update the order...", "error");
			}
		}
	);
});
