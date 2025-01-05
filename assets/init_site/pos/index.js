const slider = document.querySelector(".tb-order-list");
const prevOrderBtn = document.querySelector(".order-tabs button.btn-prev");
const nextOrderBtn = document.querySelector(".order-tabs button.btn-next");
const addOfferButton = document.querySelector("button.btn-add-offer");
const draftOrderButton = document.querySelector("button.btn-draft-order");
const clearInvoiceButton = document.querySelector("button.btn-clear-order");
const cashPaymentButton = document.querySelector("button.btn-cash");
const cardPaymentButton = document.querySelector("button.btn-card");
const upiPaymentButton = document.querySelector("button.btn-upi");
const netbankPaymentButton = document.querySelector("button.btn-netbank");
const findBarcodeButton = document.querySelector("button.btn-find-barcode");
const createCustomerButton = document.querySelector("button.btn-create-customer");

const applyGiftButton = document.querySelector("button.btn-apply-gift");
const giftCode = document.querySelector("input[id='giftNo']");

const newCustomerPhone = document.querySelector("input[id='newCustomerPhone']");
const newCustomerName = document.querySelector("input[id='newCustomerName']");
const barcodeText = document.querySelector("input[id='barcodeText']");

const tempOrderId = document.querySelector("input[id='tempOrderId']");
const customerCode = document.querySelector("input[id='customerCode']");
const customerName = document.querySelector("input[id='customerName']");
const customerPhone = document.querySelector("input[id='customerPhone']");

const itemsCount = document.querySelector(".itemsCountText");
const subTotal = document.querySelector(".subTotalText");
const discountTotal = document.querySelector(".discountTotalText");
const taxTotal = document.querySelector(".taxTotalText");
const payableTotal = document.querySelector(".payableTotalText");
const finalamount = document.querySelector(".finalAmountText");

const offerCode = document.querySelector("input[id='offerCode']");
const offerDiscount = document.querySelector(".offerDiscount");

const API_BASE = document.querySelector("meta[name='basesite']").content;
const posApiUrl = API_BASE + "Api/posorder/";
const lang = document.querySelector("meta[name='userlang']").content.toLowerCase();
const userCode = document.querySelector("meta[name='userCode']").content;

const orderrows = document.querySelector("#order-rows");

let isDown = false;
let startX;
let scrollLeft;
let scrollStep = 200;

slider.addEventListener("mousedown", (e) => {
	isDown = true;
	slider.classList.add("active");
	startX = e.pageX - slider.offsetLeft;
	scrollLeft = slider.scrollLeft;
});

slider.addEventListener("mouseleave", () => {
	isDown = false;
	slider.classList.remove("active");
});

slider.addEventListener("mouseup", () => {
	isDown = false;
	slider.classList.remove("active");
});

slider.addEventListener("mousemove", (e) => {
	if (!isDown) return;
	e.preventDefault();
	const x = e.pageX - slider.offsetLeft;
	const walk = (x - startX) * 3;
	slider.scrollLeft = scrollLeft - walk;
});

prevOrderBtn.addEventListener("click", (e) => {
	let sl = slider.scrollLeft;
	if (sl - scrollStep <= 0) {
		slider.scrollTo(0, 0);
	} else {
		slider.scrollTo(sl - scrollStep, 0);
	}
});

nextOrderBtn.addEventListener("click", (e) => {
	let sl = slider.scrollLeft,
		cw = slider.scrollWidth;
	if (sl + scrollStep >= cw) {
		slider.scrollTo(cw, 0);
	} else {
		slider.scrollTo(sl + scrollStep, 0);
	}
});

$(document).on("click", "button.btn-new-customer", function (e) {
	e.preventDefault();
	$("#newCustomerModel").modal("show");
});

function push_product_row(product, tempOrder) {
	var productName = "";
	var variantText = "";
	if (product.variantName == null || product.variantName == '') { } else {
		var variantText = ' | ' + product.variantName;
	}
	switch (lang) {
		case "english":
			productName = `${product.productEngName}` + variantText;
			break;
		case "hindi":
			productName = `${product.productHinName}` + variantText;
			break;
		case "urdu":
			productName = `${product.productUrduName}` + variantText;
			break;
		case "arabic":
			productName = `${product.productArbName}` + variantText;
			break;
	}
	var row_id = `${product.barcode}-${product.productCode}-${product.variantCode}`;
	if ($("#tmp-row-" + row_id).length > 0) {
		var innerColumns = `
            <td>
                <button title="Delete Item?" type="button" class="btn-del-item" data-productid="${row_id}" data-temprowid="${product.tempRowId}"><i class="fa fa-times-circle-o"></i></button>
            </td>
            <td>${productName}</td>
            <td class="text-end">${product.sellingPrice}</td>
            <td class="text-end">${product.orderQty}</td>
            <td class="text-end">${product.discountPrice}</td>
            <td class="text-end">${product.taxPercent}</td>
            <td class="text-end">${product.totalPrice}</td>
        `;
		$("#tmp-row-" + row_id)
			.html(innerColumns)
			.removeClass("flash");
		setTimeout(() => {
			$("#tmp-row-" + row_id).addClass("flash");
		}, 100);
	} else {
		var html = `
        <tr id="tmp-row-${row_id}" class="order-row flash">
            <td>
                <button title="Delete Item?" type="button" class="btn-del-item" data-productid="${row_id}" data-temprowid="${product.tempRowId}"><i class="fa fa-times-circle-o"></i></button>
            </td>
            <td>${productName}</td>
            <td class="text-end">${product.sellingPrice}</td>
            <td class="text-end">${product.orderQty}</td>
            <td class="text-end">${product.discountPrice}</td>
            <td class="text-end">${product.taxPercent}</td>
            <td class="text-end">${product.totalPrice}</td>
        </tr>`;
		$("#order-rows").append(html);
	}

	let hasorderrows = document.querySelectorAll("tr.order-row");

	itemsCount.innerHTML = hasorderrows.length;
	subTotal.innerHTML = tempOrder.subTotal;
	discountTotal.innerHTML = tempOrder.discountTotal;
	taxTotal.innerHTML = tempOrder.taxTotal;
	payableTotal.innerHTML = tempOrder.totalPayable;
	finalamount.innerHTML = tempOrder.totalPayable;

	var customer = JSON.parse(tempOrder.customer);
	tempOrderId.value = tempOrder.orderId;
	customerCode.value = customer.customerCode;
	customerName.value = customer.customerName;
	customerPhone.value = customer.customerPhone;
}

function fetchItemAddOrder(barcode) {
	if (barcode !== "" && barcode !== undefined) {
		$.ajax({
			url: `${posApiUrl}fetchProductByBarcode`,
			method: "POST",
			timeout: 0,
			data: {
				userCode: userCode,
				userLang: lang,
				barcode: barcode,
				tempOrderId: tempOrderId.value,
				customerCode: customerCode.value,
				//customerName: customerName.value,
				//customerPhone: customerPhone.value,
				customerName: newCustomerName.value,
				customerPhone: newCustomerPhone.value,
			},
			dataType: "JSON",
			success: function (response) {
				if (response.status === "200") {
					var tempOrder = response.data.order;
					var tempProduct = response.data.product;
					push_product_row(tempProduct, tempOrder);
				} else {
					alert(response.message);
				}
			},
			complete: function () {
				barcodeText.value = "";
			},
		});
	}
}

function barcodehandler(e) {
	if (e.target.value.length > 8) {
		fetchItemAddOrder(barcodeText.value);
	}
}

function clearCurrentOrderFromUI() {
	itemsCount.innerHTML = 0;
	orderrows.innerHTML = orderrows.innerHTML = barcodeText.value = customerPhone.value = customerName.value = customerCode.value = tempOrderId.value = "";
	subTotal.innerHTML = discountTotal.innerHTML = taxTotal.innerHTML = payableTotal.innerHTML = finalamount.innerHTML = "0.00";
	barcodeText.focus();
}

barcodeText.addEventListener("keypress", barcodehandler, false);
barcodeText.addEventListener("input", barcodehandler, false);
barcodeText.addEventListener("paste", barcodehandler, false);

findBarcodeButton.addEventListener("click", (e) => {
	e.preventDefault();
	fetchItemAddOrder(barcodeText.value);
});

$(document).on("click", "button.btn-del-item", function (e) {
	e.preventDefault();
	debugger
	var item = $(this).data("productid");
	var tempPrdId = $(this).data("temprowid");
	var rowCount = $('#productTable tr').length - 1;
	var conText = '';
	if (rowCount == 1) {
		var conText = "Once delete, you will not be able to recover the order again!";
	}
	swal(
		{
			icon: "warning",
			title: "Cancel Order?",
			text: conText,
			showCancelButton: true,
			confirmButtonText: `Confirm`,
			closeOnClickOutside: false,
		},
		function (result) {
			if (result) {
				$.ajax({
					type: "POST",
					url: `${posApiUrl}removeItemFromOrder`,
					data: {
						orderId: tempOrderId.value,
						product: item,
						tempPrdId: tempPrdId,
						rowCount: rowCount,
					},
					dataType: "JSON",
					beforeSend: function () {
						$(".preloader").show();
					},
					success: function (response) {
						$(".preloader").hide();
						if (response.status === "200") {
							$('#tmp-row-' + item).remove();
							toastr.success(response.message, "Success", { progressBar: true });
						} else {
							toastr.error(response.message, "Error", { progressBar: true });
						}
					},
				});
			} else {
				swal("Action was cancelled");
			}
		}
	);
});

createCustomerButton.addEventListener("click", (e) => {
	$("#frmNewCust").parsley();
	var isValid = true;
	$("#frmNewCust .form-control").each(function (e) {
		if ($(this).parsley().validate() !== true) isValid = false;
	});
	if (isValid) {
		$.ajax({
			url: `${posApiUrl}createCustomer`,
			method: "POST",
			timeout: 0,
			data: {
				userCode: userCode,
				name: newCustomerName.value,
				phone: newCustomerPhone.value,
			},
			dataType: "JSON",
			beforeSend: function () {
				e.target.innerHTML = "<i class='fa fa-spinner spin'></i> Wait";
				e.target.setAttribute("disabled", true);
			},
			success: function (response) {
				if (response.status === "200") {
					newCustomerName.value = "";
					newCustomerPhone.value = "";
					customerCode.value = response.data.code;
					customerName.value = response.data.name;
					customerPhone.value = response.data.phone;
					$("#newCustomerModel").modal("hide");
					toastr.success(response.message, "Success", { progressBar: true });
				} else {
					toastr.error(response.message, "Failed", { progressBar: true });
				}
			},
			complete: function () {
				e.target.innerHTML = "Create?";
				e.target.removeAttribute("disabled");
			},
		});
	}
});

addOfferButton.addEventListener("click", (e) => {
	e.preventDefault();
	var btn = this;
	if (tempOrderId.value !== "" && tempOrderId.value !== undefined) {
		var total = Number(payableTotal.innerHTML);
		if (total > 0) {
			if (offerCode.value !== "" && offerCode.value !== undefined) {
				$.ajax({
					type: "POST",
					url: `${posApiUrl}applyOffer`,
					data: {
						orderId: tempOrderId.value,
						offerCode: offerCode.value,
					},
					dataType: "JSON",
					beforeSend: function () {
						btn.innerHTML = "<i class='fa fa-spinner spin></i> Wait..'";
						btn.disabled = true;
					},
					success: function (response) {
						if (response.status === "200") {
							var res = response.data;
							discountTotal.innerHTML = Number(res.discountTotal) + Number(res.offerDiscount);
							payableTotal.innerHTML = res.totalPayable;
							finalamount.innerHTML = res.totalPayable;
							offerCode.disabled = true;
							btn.disabled = true;
							toastr.success(response.message, "Offer Applied", { progressBar: true });
						} else {
							offerCode.disabled = false;
							btn.disabled = false;
							toastr.success(response.message, "Not applicable", { progressBar: true });
						}
					},
					complete: function () {
						btn.innerHTML = "Add";
					},
				});
			} else {
				toastr.warning("Please enter offer code...", "Opps", { progressBar: true });
				return false;
			}
		} else {
			toastr.warning("Total payable should be greater than 0 (zero).", "Opps", { progressBar: true });
			return false;
		}
	} else {
		toastr.warning("No order is selected or items are not present", "Opps", { progressBar: true });
		return false;
	}
});

draftOrderButton.addEventListener("click", (e) => {
	e.preventDefault();
	var btn = this;
	$.ajax({
		url: `${posApiUrl}createDraftOrder`,
		method: "POST",
		timeout: 0,
		data: {
			orderId: tempOrderId.value,
			customerCode: customerCode.value,
			customerName: customerName.value,
			customerPhone: customerPhone.value,
		},
		dataType: "JSON",
		beforeSend: function () {
			btn.innerHTML = "<i class='fa fa-spinner spin'></i> <span>Wait</span>";
			btn.disabled = true;
		},
		success: function (response) {
			if (response.status === "200") {
				if ($(`li#dord-${tempOrderId.value}`).length > 0) {
					$(`li#dord-${tempOrderId.value}`).remove();
				}
				var html = `<li id="dord-${tempOrderId.value}"><div class="tb-order-box" data-draft-order-id="${tempOrderId.value}"><span class="fa fa-times cancel-order"></span> <span class="text-order">Order #${tempOrderId.value}</span></div></li>`;
				$(".tb-order-list").prepend(html);
				$(".order-tabs").removeClass("d-none");
				clearCurrentOrderFromUI();
				toastr.success(response.message, "Success", { progressBar: true });
			} else {
				toastr.error(response.message, "Failed", { progressBar: true });
			}
		},
		complete: function () {
			btn.innerHTML = "<i class='fa fa-inbox'></i> <span>Draft Order</span>";
			btn.disabled = false;
		},
	});
});

clearInvoiceButton.addEventListener("click", (e) => {
	e.preventDefault();
	swal(
		{
			icon: "warning",
			title: "Cancel Order?",
			text: "Once delete, you will not be able to recover the order again!",
			showCancelButton: true,
			confirmButtonText: `Confirm`,
			closeOnClickOutside: false,
		},
		function (result) {
			if (result) {
				$.ajax({
					type: "POST",
					url: `${posApiUrl}/cancelOrder`,
					data: {
						orderId: tempOrderId.value,
					},
					dataType: "JSON",
					success: function (response) {
						if (response.status === "200") {
							$("li#dord-" + tempOrderId.value).remove();
							clearCurrentOrderFromUI();
							toastr.success(response.message, "Success", { progressBar: true });
						} else {
							toastr.error(response.message, "Failed", { progressBar: true });
						}
					},
				});
			} else {
				swal("You can continue with the order or just save as draft");
			}
		}
	);
});

function placeOrder(paymentMode) {
	var rows = $(".order-row").length;
	if (tempOrderId.value !== "" && rows > 0) {
		$.ajax({
			type: "POST",
			url: `${posApiUrl}placeOrder`,
			data: {
				userCode: userCode,
				orderId: tempOrderId.value,
				customerCode: customerCode.value,
				customerName: customerName.value,
				customerPhone: customerPhone.value,
				paymentMode: paymentMode,
			},
			timeout: 0,
			dataType: "JSON",
			beforeSend: function () {
				$(".preloader").show();
			},
			success: function (response) {

				if (response.status === "200") {
					const title = `Success #${response.txnId}`;
					toastr.success(response.message, title, { progressBar: true, positionClass: "toast-top-center" });
					setTimeout(function () {
						$("li#dord-" + tempOrderId.value).remove();
						clearCurrentOrderFromUI();
					}, 300);
				} else {
					toastr.error(response.message, "Opps", { progressBar: true, positionClass: "toast-top-center" });
				}
			},
			complete: function () {
				$(".preloader").fadeOut(400);
			},
		});
	} else {
		swal({
			text: "Either no products are added or order not selected. Cannot checkout empty orders.",
			title: "Empty?",
			icon: "error",
		});
	}
}

cashPaymentButton.addEventListener("click", (e) => {
	e.preventDefault();
	placeOrder("cash");
});

cardPaymentButton.addEventListener("click", (e) => {
	e.preventDefault();
	placeOrder("card");
});

upiPaymentButton.addEventListener("click", (e) => {
	e.preventDefault();
	placeOrder("upi");
});

netbankPaymentButton.addEventListener("click", (e) => {
	e.preventDefault();
	placeOrder("netbanking");
});

function fillOrderToUI(order) {
	var itemsCount = 0;
	var customer = JSON.parse(order.customer);
	var products = order.products;
	$("div.tb-order-box").removeClass("active");
	$("div.tb-order-box[data-draft-order-id='" + order.orderId + "']").addClass("active");
	if (products.length > 0) {
		orderrows.innerHTML = "";
		for (let index = 0; index < products.length; index++) {
			const product = products[index];

			var productName = "";
			switch (lang) {
				case "english":
					productName = `${product.productEngName} | ${product.variantName} `;
					break;
				case "hindi":
					productName = `${product.productHinName} | ${product.variantName} `;
					break;
				case "urdu":
					productName = `${product.productUrduName} | ${product.variantName} `;
					break;
				case "arabic":
					productName = `${product.productArbName} | ${product.variantName} `;
					break;
			}

			var row_id = `${product.barcode}-${product.productCode}-${product.variantCode}`;

			var html = `
                <tr id="tmp-row-${row_id}" class="order-row flash">
                    <td>
                        <button title="Delete Item?" type="button" class="btn-del-item" data-productid="${row_id}"><i class="fa fa-times-circle-o"></i></button>
                    </td>
                    <td>${productName}</td>
                    <td class="text-end">${product.price}</td>
                    <td class="text-end">${product.qty}</td>
                    <td class="text-end">${product.discountPrice}</td>
                    <td class="text-end">${product.taxPercent}</td>
                    <td class="text-end">${product.totalPrice}</td>
                </tr>
                `;
			$("#order-rows").append(html);
			itemsCount++;
		}
	}

	tempOrderId.value = order.orderId;
	itemsCount.innerHTML = itemsCount;
	subTotal.innerHTML = order.subTotal;
	discountTotal.innerHTML = order.discountTotal;
	taxTotal.innerHTML = order.taxTotal;
	payableTotal.innerHTML = order.totalPayable;
	finalamount.innerHTML = order.totalPayable;
	customerCode.value = customer.customerCode;
	customerName.value = customer.customerName;
	customerPhone.value = customer.customerPhone;
}

function findOrder(orderId) {
	$.ajax({
		type: "POST",
		url: `${posApiUrl}fetchOrderDetails`,
		data: {
			userCode: userCode,
			orderId: orderId,
		},
		dataType: "JSON",
		beforeSend: function () {
			//show loading icon while fetching the record
		},
		success: function (response) {
			if (response.status === "200") {
				fillOrderToUI(response.data);
			} else {
				toastr.error(response.message, "Opps", { progressBar: true, positionClass: "toast-top-center" });
			}
		},
		complete: function () {
			//hide loading icon after fetchoing ther recordś
		},
	});
}

$(document).on("click", "span.text-order", function (e) {
	e.stopPropagation();
	var orderId = $(e.currentTarget).closest("div.tb-order-box").attr("data-draft-order-id");
	if (orderId !== undefined && orderId !== "") {
		findOrder(orderId);
	}
});

$(document).on("click", "span.cancel-order", function (e) {
	e.stopPropagation();
	var orderId = $(e.currentTarget).closest("div.tb-order-box").attr("data-draft-order-id");
	if (orderId !== undefined && orderId !== "") {
		swal(
			{
				title: "Cancel Order?",
				text: "Once delete, you will not be able to recover the order again!",
				icon: "warning",
				showCancelButton: true,
				confirmButtonText: `Confirm`,
				closeOnClickOutside: false,
			},
			function (result) {
				if (result) {
					$.ajax({
						type: "POST",
						url: `${posApiUrl}/cancelOrder`,
						data: {
							orderId: orderId,
						},
						dataType: "JSON",
						success: function (response) {
							if (response.status === "200") {
								clearCurrentOrderFromUI();
								toastr.error("Order was deleted successfully.", "Success", { progressBar: true, positionClass: "toast-top-center" });
							} else {
								toastr.error(response.message, "Failed", { progressBar: true, positionClass: "toast-top-center" });
							}
						},
					});
				} else {
					swal("You can continue with the order or just save as draft");
				}
			}
		);
	}
});

$(document).on("click", "div.tb-order-box", function (e) {
	e.preventDefault();
	const orderId = $(this).data("draft-order-id");
	if (orderId !== undefined && orderId !== "") {
		findOrder(orderId);
	}
});

$(function () {
	$(".preloader").fadeOut(300);
	barcodeText.focus();
});

applyGiftButton.addEventListener("click", (e) => {
	e.preventDefault();
	debugger
	var btn = this;
	if (tempOrderId.value !== "" && tempOrderId.value !== undefined) {
		var total = Number(payableTotal.innerHTML);
		if (total > 0) {
			if (giftCode.value !== "" && giftCode.value !== undefined) {
				$.ajax({
					type: "POST",
					url: `${posApiUrl}applyGift`,
					data: {
						orderId: tempOrderId.value,
						giftCode: giftCode.value,
					},
					dataType: "JSON",
					beforeSend: function () {
						btn.text("<i class='fa fa-spinner spin></i> Wait..'");
						btn.disabled = true;
					},
					success: function (response) {
						if (response.status === "200") {
							var res = response.data;
							discountTotal.innerHTML = Number(res.discountTotal) + Number(res.offerDiscount);
							payableTotal.innerHTML = res.totalPayable;
							finalamount.innerHTML = res.totalPayable;
							giftCode.disabled = true;
							btn.disabled = true;
							toastr.success(response.message, "GiftCard Applied", { progressBar: true });
						} else {
							giftCode.disabled = false;
							btn.disabled = false;
							toastr.error(response.message, "Not applicable", { progressBar: true });
						}
					},
					complete: function () {
						btn.text = "Add";
					},
				});
			} else {
				toastr.warning("Please enter gift no", "Opps", { progressBar: true });
				return false;
			}
		} else {
			toastr.warning("Total payable should be greater than 0 (zero).", "Opps", { progressBar: true });
			return false;
		}
	} else {
		toastr.warning("No order is selected or items are not present", "Opps", { progressBar: true });
		return false;
	}
});
