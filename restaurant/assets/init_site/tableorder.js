const orderBox = $("#order-box");
const cartBox = $("#cart-box");
const custName = $("input#custName");
const custPhone = $("input#custPhone");
const branchCode = $("input#branchCode");
const tableSection = $("input#tableSection");
const tableNumber = $("input#tableNumber");
const productTblBody = $("table#productTable tbody");
const addonTblBody = $("table#addonTable tbody");
const addonTbl = $("table#addonTable");
const preloader = $("div#preloader");
const cartProducts = $("#cart-products");
const lang = "english";
let cartData = [];
let cartProductCodes = [];

function getMyOrders() {
	$.ajax({
		type: "get",
		url: base_path + "restaurant/getMyOrders",
		data: {
			branchCode: branchCode.val().trim(),
			tableSection: tableSection.val().trim(),
			tableNumber: tableNumber.val().trim(),
			custPhone: custPhone.val().trim(),
		},
		success: function (response) {
			if (response) {
				$("#kot-orders").empty();
				setTimeout(() => {
					$("#kot-orders").append(response);
				}, 300);
			}
		},
	});
}

window.onscroll = function () {
	myFunction();
};

var header = document.querySelector("div.cat-slider");
var sticky = header.offsetTop;

function myFunction() {
	if (window.pageYOffset > sticky) {
		header.classList.add("stick-top");
	} else {
		header.classList.remove("stick-top");
	}
}

$(document).ready(function () {
	var phone = custPhone.val().trim();
	var tNo = tableNumber.val().trim();
	//if (phone === "") {
		//setTimeout(function () {
			$("#mdlTblCust").modal("show");
		//}, 500);
	//}
	//getMyOrders();
	$(".owl-carousel").owlCarousel({
		loop: false,
		margin: 10,
		nav: true,
		dots: false,
		navText: [
			"<i class='fa fa-angle-left'></i>",
			"<i class='fa fa-angle-right'></i>",
		],
		responsive: {
			0: {
				items: 2,
			},
			600: {
				items: 3,
			},
			1000: {
				items: 4,
			},
		},
	});
	$("a[href*=\\#]:not([href=\\#])").on("click", function () {
		var target = $(this.hash);
		target = target.length ? target : $("[name=" + this.hash.substr(1) + "]");
		if (target.length) {
			$("html,body").animate(
				{
					scrollTop: target.offset().top - 25,
				},
				1000
			);
			return false;
		}
	});
	validatePhone();
});

$(document).on("click", "a.new-order", function (e) {
	e.preventDefault();
	cartBox.show();
	orderBox.hide();
});

$(document).on("click", "a.my-orders", function (e) {
	e.preventDefault();
	cartBox.hide();
	orderBox.show();
});
/*
$(document).on("click", "#mdlTblCust #bookTableBtn", function (e) {
	e.preventDefault();
	$("#newCustomer").parsley();
	var phone = custPhone.val().trim();
	var tNo = tableNumber.val().trim();
	var isValid = true;
	$("#newCustomer .form-control").each(function (e) {
		if ($(this).parsley().validate() !== true) isValid = false;
	});
	if (isValid) {
		$.ajax({
			type: "POST",
			url: base_path + "Api/order/checkReservedTable",
			data: {
				phone: phone,
				tNo: tNo,
			},
			dataType: "JSON",
			success: function (response) {
				if (response.status == "200") {
					var phone = $("#modaltablePhoneNo").val().trim();
					var name = $("#modaltableName").val().trim();
					custName.val(name);
					custPhone.val(phone);
					$("#mdlTblCust").modal("hide");
					cartBox.show();
					orderBox.hide();
				} else {
					toastr.error(
						"Table is in processing...Please wait and try after sometime",
						"Failed",
						{
							progressBar: true,
						}
					);
				}
			},
		});
	}
});
*/
$(document).on("click", "#mdlTblCust .close", function (e) {
	e.preventDefault();
	if (confirm("Do you reaally want to exit ? ")) {
		window.open("", "_self");
		window.close();
	} else return false;
});

$(document).on("click", "button.prod-btn", function (e) {
	e.preventDefault();
	productTblBody.empty();
	addonTblBody.empty();
	const productCode = $(this).data("productcode");
	const comboProduct = $(this).data("combo-product");
	if (productCode !== "" && productCode !== undefined) {
		$.ajax({
			url: base_path + "Api/order/fetchProductDetails",
			data: {
				productCode: productCode,
				comboProduct: comboProduct,
				lang: lang,
			},
			type: "GET",
			dataType: "JSON",
			beforeSend: function () {
				preloader.show();
			},
			success: function (response) {
				var obj = response.data;
				if (response.status === "200") {
					productTblBody.append(obj.varientHtml);
					if (obj.addonHtml !== "") {
						addonTblBody.append(obj.addonHtml);
						addonTbl.show();
					} else {
						addonTbl.hide();
					}
					setTimeout(function () {
						$("#mdlProduct").modal("show");
					}, 200);
				} else {
					toastr.error("Failed to get product details", "Failed", {
						progressBar: true,
					});
				}
			},
			complete: function () {
				preloader.fadeOut(300);
			},
		});
	} else {
		toastr("Something went wrong", "Opps..", {
			progressBar: true,
		});
	}
});

$(document).on("click", "button#add_to_cart", function (e) {
	e.preventDefault();
	var btn = $(this);
	var productCode = $("#productCode").val();
	var variantCode = $("#variantCode").val();
	var productQty = $("#productQty").val();
	var productPrice = $("#productPrice").val();
	var productCombo = $("#productCombo").val();
	var comboProductCode = $("#comboProductCode").val();
	var comboProductNames = $("#comboProductNames").val();
	var table = document.getElementById("addonTable");
	var table_len = table.rows.length - 1;
	var tr = table.getElementsByTagName("tr");
	var addOnArray = [];
	var totalPrice = productPrice;
	for (i = 1; i <= table_len; i++) {
		var id = tr[i].id.substring(3);
		var addonCode = document.getElementById("addonCode" + id).value;
		var addonName = document.getElementById("addonName" + id).value;
		var addonQty = document.getElementById("addonQty" + id).value;
		var addonPrice = document.getElementById("addonPrice" + id).value;
		var addonsCheck = document.getElementById("addons_" + i);
		if (addonsCheck.checked == true) {
			totalPrice = Number(totalPrice) + Number(addonPrice);
			var addon = {
				addonCode: addonCode,
				addonTitle: addonName,
				addonQty: addonQty,
				addonPrice: addonPrice,
			};
			addOnArray.push(addon);
		}
	}
	var addOns = JSON.stringify(addOnArray);

	var comboProductsArray = [];
	var comboProductsNamesArray = [];
	if (productCombo === "1") {
		var comboItemsArray = comboProductCode.split(",");
		var comboItemsNameArray = comboProductNames.split(",");
		if (comboItemsArray.length > 0) {
			for (let index = 0; index < comboItemsArray.length; index++) {
				const element = comboItemsArray[index];
				var cmbItem = {
					productCode: element,
					productQty: productQty,
				};
				var cmbItemName = {
					productName: comboItemsNameArray[index],
				};
				comboProductsArray.push(cmbItem);
				comboProductsNamesArray.push(cmbItemName);
			}
		}
	}

	var cmbProducts = JSON.stringify(comboProductsArray);
	var comboProductsNames = JSON.stringify(comboProductsNamesArray);
	if (cartProductCodes.indexOf(productCode) === -1) {
		$.ajax({
			url: base_path + "Api/order/cartAddProduct",
			type: "POST",
			data: {
				productCode: productCode,
				variantCode: variantCode,
				productQty: productQty,
				productPrice: productPrice,
				addOns: addOns,
				totalPrice: totalPrice,
				productCombo: productCombo,
				cmbProductsNames: comboProductsNames,
				cmbProducts: cmbProducts,
				branchCode: branchCode.val(),
				tableSection: tableSection.val(),
				tableNumber: tableNumber.val(),
				custPhone: custPhone.val(),
				custName: custName.val(),
			},
			dataType: "JSON",
			beforeSend: function () {
				btn.prop("disabled", true);
				btn.html('Wait <i class="fa fa-spinner spin"></i> ...');
			},
			success: function (response) {
				if (response.status === "200") {
					addItemToCart(response.data);
					toastr.success("Product/Item is added to cart..", "Success", {
						progressBar: true,
					});
					$("#mdlProduct").modal("hide");
				} else {
					toastr.error("Failed to add the product/item to cart...", "Failed", {
						progressBar: true,
					});
				}
			},
			complete: function () {
				btn.prop("disabled", false);
				btn.html("Add To cart");
			},
		});
	} else {
		toastr.warning("Item already exists...", "Opps..", {
			progressBar: true,
		});
	}
});

$(document).on("click", ".btnMinus", function (e) {
	e.preventDefault();
	const prdCode = $(this).data("product-code");
	const index = cartData.findIndex(function (product) {
		return product.productCode == prdCode;
	});
	if (index !== -1) {
		const cartItem = cartData[index];
		if (cartItem.productQty > 1) {
			const addons = cartItem.addOns;
			let addonTotal = 0;
			if (addons.length > 0) {
				addons.forEach(function (item) {
					const addonamount = Number(item.addonPrice) * Number(item.addonQty);
					addonTotal += addonamount;
				});
			}
			var singleItemPrice =
				Number(cartItem.productPrice) / Number(cartItem.productQty);
			cartItem.productQty--;
			$("input[id='cart-qty-" + prdCode + "'").val(cartItem.productQty);
			var itemPrice = singleItemPrice * cartItem.productQty;
			var finalItemPrice = itemPrice + addonTotal;
			$("#prd-amt-" + prdCode).text(finalItemPrice);
			cartData[index].productQty = cartItem.productQty;
			cartData[index].productPrice = itemPrice;
			cartData[index].totalPrice = finalItemPrice;
			calculate();
		}
	}
});

$(document).on("click", ".btnPlus", function (e) {
	e.preventDefault();
	const prdCode = $(this).data("product-code");
	const index = cartData.findIndex(function (product) {
		return product.productCode == prdCode;
	});
	if (index !== -1) {
		const cartItem = cartData[index];
		if (cartItem.productQty < 100) {
			const addons = cartItem.addOns;
			let addonTotal = 0;
			if (addons.length > 0) {
				addons.forEach(function (item) {
					const addonamount = Number(item.addonPrice) * Number(item.addonQty);
					addonTotal += addonamount;
				});
			}
			var singleItemPrice =
				Number(cartItem.productPrice) / Number(cartItem.productQty);
			cartItem.productQty++;
			$("input[id='cart-qty-" + prdCode + "'").val(cartItem.productQty);
			var itemPrice = singleItemPrice * cartItem.productQty;
			var finalItemPrice = itemPrice + addonTotal;
			$("#prd-amt-" + prdCode).text(finalItemPrice);
			cartData[index].productQty = cartItem.productQty;
			cartData[index].productPrice = itemPrice;
			cartData[index].totalPrice = finalItemPrice;
			calculate();
		}
	}
});

function addItemToCart(data) {
	var extraText = "";
	var comboArr = data.comboProductItemsName;
	if (comboArr.length > 0) {
		extraText = "Combo Includes :<br>";
		for (i = 0; i < comboArr.length; i++) {
			extraText =
				extraText +
				'<span class="badge bg-success" style="margin-right:5px;">' +
				comboArr[i].productName +
				"</span>";
		}
	}

	var addonArr = data.addOns;
	if (addonArr.length > 0) {
		extraText = "Addons :<br>";
		for (i = 0; i < addonArr.length; i++) {
			extraText =
				extraText +
				addonArr[i].addonTitle +
				"<span style='float:right;'>" +
				addonArr[i].addonPrice +
				"</span><br>";
		}
	}
	var productHtml = `
        <div class="col-12 mb-2" id="cart-item-${data.productCode}">  
            <div class="row g-0 cartrow align-items-center">
                <div class="col-3 mx-2">
                    <div class="cartimg"> 
                        <img src="${data.productImage}" />
                    </div>
                </div>
                <div class="col">
					<div class="row">
					 <a href="javascript:void(0)" class="text-danger float-end rmv-cart-item" data-product-code="${data.productCode
		}"><i class="fa fa-times"></i></a>
					</div>
                    <h5 class="text-muted mb-1">${data.productName
		}<span class="float-end">${Number(
			data.productPrice
		).toFixed(2)}</span></h5>`;
	if (extraText != "") {
		productHtml =
			productHtml +
			`<div class="mb-2">
							<h6>${extraText}</h6>
						</div>`;
	}
	productHtml =
		productHtml +
		`<div class="mb-2">
                        <div class="input-group w-50">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-sm btn-outline-danger btnMinus" data-product-code="${data.productCode}"> <i class="fa fa-minus"></i> </button>
                            </div>
                            <input type="text" readonly value="${data.productQty}" id="cart-qty-${data.productCode}" class="form-control text-center" aria-label="Amount (to the nearest dollar)">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-sm btn-outline-danger btnPlus" data-product-code="${data.productCode}"> <i class="fa fa-plus"></i> </button>
                            </div>
                        </div>  
                    </div>               
                    <div class="text-right"><span id="prd-amt-${data.productCode}" style="font-weight:600">${data.totalPrice}</span></div>
                </div>
            </div>
        </div>
    `;
	cartProductCodes.push(data.productCode);
	cartData.push(data);
	cartProducts.append(productHtml);
	calculate();
}

$(document).on("change", "input.addonQtyChange", function (e) {
	var id = $(this).data("id");
	var addonQty = Number($("#addonQty" + id).val());
	if (addonQty > 0) {
		var previous = $("#oldaddonPrice" + id).val();
		var addonSubTotal = $("#addonSubTotal" + id).text();
		var subTotal = Number(previous) * Number(addonQty);
		$("#addonPrice" + id).val(Number(subTotal).toFixed(2));
		$("#addonSubTotal" + id).text(Number(subTotal).toFixed(2));
	}
});

$(document).on("change", "input.variantPriceChange", function (e) {
	var productQty = Number($("#productQty").val());
	if (productQty > 0) {
		var previous = $("#oldproductPrice").val();
		var subTotal = Number(previous) * Number(productQty);
		$("#productPrice").val(Number(subTotal).toFixed(2));
		$("#productPriceTotal").text(Number(subTotal).toFixed(2));
	} else {
		return false;
	}
});

$(document).on("click", "a.rmv-cart-item", function (e) {
	e.preventDefault();
	var prodCode = $(this).data("product-code");
	if (cartProductCodes.indexOf(prodCode) !== -1) {
		const index1 = cartProductCodes.indexOf(prodCode);
		cartProductCodes.splice(index1, 1);
		const index = cartData.findIndex(function (product) {
			return product.productCode == prodCode;
		});
		if (index !== -1) {
			cartData.splice(index, 1);
			$("#cart-item-" + prodCode).remove();
			calculate();
		}
	}
});

function calculate() {
	let total = 0;
	let countItems = cartData.length;
	if (countItems > 0) {
		cartData.forEach((cartItem) => {
			total += Number(cartItem.totalPrice);
		});
	}
	$("#cartCount").text(countItems);
	$("#cartTotal").text(total.toFixed(2));
}

$(document).on("click", "button.btn-place-order", function (e) {
	e.preventDefault();
	const btn = $(this);
	if (cartData.length > 0) {
		const orderData = {
			branchCode: branchCode.val().trim(),
			tableSection: tableSection.val().trim(),
			tableNumber: tableNumber.val().trim(),
			custName: custName.val().trim(),
			custPhone: custPhone.val().trim(),
			products: cartData,
		};

		$.ajax({
			type: "POST",
			url: base_path + "Api/order/cartPlaceOrder",
			data: orderData,
			dataType: "JSON",
			beforeSend: function () {
				btn.html('<i class="fa fa-circle-o-notch"></i> Wait...');
				btn.prop("disabled", true);
			},
			success: function (response) {
				if (response.status === "200") {
					cartBox.hide();
					orderBox.show();
					$("a.new-order").show();
					$("a.my-orders").hide();
					getMyOrders();
				} else {
					swal({
						icon: "error",
						title: "Oops...",
						text: response.message,
					});
				}
			},
			complete: function () {
				btn.html("Place Order");
				btn.prop("disabled", false);
			},
		});
	} else {
		toastr.warning(
			"No items are present in your cart. Please at least add one (1) item/product to the cart",
			"Opps..",
			{
				progressBar: true,
			}
		);
		return false;
	}
});

$(document).on("click", "button.cancel-kot-order", function (e) {
	e.preventDefault();
	var cartId = $(this).data("cart-id");
	swal(
		{
			title: "Cancel Order?",
			text: "Do you really want to cancel the selected order? Make Sure that action is not reversible...",
			type: "warning",
			showCancelButton: !0,
			buttons: {
				cancel: {
					text: "Exit",
					className: "btn btn-sm btn-danger",
					closeModal: false,
				},
				confirm: {
					text: "Confirm",
					className: "btn btn-sm btn-success",
					closeModal: false,
				},
			},
			closeOnConfirm: !1,
			closeOnCancel: !1,
		},
		function (e) {
			if (e) {
				$.ajax({
					type: "POST",
					url: base_path + "restaurant/cancel/kotorder",
					data: { cartId: cartId },
					dataType: "JSON",
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
											$("#kot-order-" + cartId).remove();
											getMyOrders();
										}, 400);
									}
								}
							);
						} else {
							swal(
								{
									title: "Opps...",
									text: response.message,
									type: "warning",
								},
								function (isConfirm) {
									if (isConfirm) {
										getMyOrders();
									}
								}
							);
						}
					},
				});
			} else {
				swal("Okay", "No action was taken on the order", "info");
			}
		}
	);
});

function validatePhone() {
	var countryCode = $("#countryCode").val();
	$("#modaltablePhoneNo").data("parsley-pattern", countryCode);
}

$(document).on("click", "#bookTableBtn", function (e) {
	e.preventDefault();
	$("#newCustomer").parsley();
	var phoneNumber = $("#modaltablePhoneNo").val().trim();
	var countryCode = $("#countryCode").val().trim();
	var tableNumber = $("#tableNumber").val().trim();
	var branchCode = $("#branchCode").val().trim();
	var tableSection = $("#tableSection").val().trim();
	var isValid = true;
	$("#newCustomer .form-control").each(function (e) {
		if ($(this).parsley().validate() !== true) isValid = false;
	});
	if (isValid) {
		$.ajax({
			type: "POST",
			url: base_path + "Tableorder/checkBookedTable",
			data: {
				countryCode: countryCode,
				tableNumber: tableNumber,
				phoneNumber: phoneNumber,
				branchCode: branchCode,
				tableSection: tableSection
			},
			success: function (response) {
				var obj = JSON.parse(response);
				if (obj.status == true) {
					var phone = $("#modaltablePhoneNo").val().trim();
					var name = $("#modaltableName").val().trim();
					$("#custName").val(name);
					$("#custPhone").val(phone);
					$("#mdlTblCust").modal("hide");
					cartBox.show();
					orderBox.hide();
					getMyOrders();
				} else {
					toastr.error(obj.message, 'Table Reservation', {
						"progressBar": true
					});
				}
			},
		});
	}
});