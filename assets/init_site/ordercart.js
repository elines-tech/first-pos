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

let cartData = [];
let cartProductCodes = [];

$(document).ready(function () {
	var phone = custPhone.val().trim();
	var name = custName.val().trim();
	var branch = branchCode.val().trim();
	var section = tableSection.val().trim();
	var table = tableNumber.val().trim();
	if (phone === "" && table === "") {
		setTimeout(function () {
			$("#mdlTblCust").modal("show");
		}, 500);
	}
	$(".owl-carousel").owlCarousel({
		loop: false,
		margin: 10,
		nav: true,
		dots: false,
		navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
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
});

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

function validatePhone() {
	var countryCode = $("#countryCode").val();
	$("#modaltablePhoneNo").data("parsley-pattern", countryCode);
}

$(document).on("click", "#mdlTblCust .close", function (e) {
	e.preventDefault();
	if (confirm("Do you reawant to close the order? ")) window.location.replace(base_path + "order/listRecords");
	else return false;
});

$(document).on("click", "#mdlTblCust #bookTableBtn", function (e) {
	e.preventDefault();
	$("#newCustomer").parsley();
	var isValid = true;
	$("#newCustomer .form-control").each(function (e) {
		if ($(this).parsley().validate() !== true) isValid = false;
	});
	if (isValid) {
		var tableNo = $("#modaltableCode").val();
		var branch = $("#modaltableCode").find(":selected").data("table-branch");
		var section = $("#modaltableCode").find(":selected").data("table-section");
		var phone = $("#modaltablePhoneNo").val().trim();
		var name = $("#modaltableName").val().trim();

		custName.val(name);
		custPhone.val(phone);
		branchCode.val(branch);
		tableSection.val(section);
		tableNumber.val(tableNo);

		$("#mdlTblCust").modal("hide");
	}
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
				addOnCode: addonCode,
				addonName: addonName,
				addonQty: addonQty,
				addonPrice: addonPrice,
			};
			addOnArray.push(addon);
		}
	}
	var addOns = JSON.stringify(addOnArray);

	var comboProductsArray = [];
	if (productCombo === "1") {
		var comboItemsArray = comboProductCode.split(",");
		if (comboItemsArray.length > 0) {
			for (let index = 0; index < comboItemsArray.length; index++) {
				const element = comboItemsArray[index];
				var cmbItem = {
					productCode: element,
					productQty: productQty,
				};
				comboProductsArray.push(cmbItem);
			}
		}
	}

	var cmbProducts = JSON.stringify(comboProductsArray);

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
			var singleItemPrice = Number(cartItem.productPrice) / Number(cartItem.productQty);
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
			var singleItemPrice = Number(cartItem.productPrice) / Number(cartItem.productQty);
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
	var productHtml = `
        <div class="col-12 mb-2" id="cart-item-${data.productCode}">  
            <div class="row g-0 cartrow align-items-center">
                <div class="col-3 mx-2">
                    <div class="cartimg"> 
                        <img src="${data.productImage}" />
                    </div>
                </div>
                <div class="col">
                    <h5 class="text-muted mb-2">
                        ${data.productName} 
                        <a href="javascript:void(0)" class="text-danger float-end rmv-cart-item" data-product-code="${data.productCode}"><i class="fa fa-times"></i></a>
                    </h5>    
                    <div class="mb-2">
                        <div class="input-group w-50">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-sm btn-outline-danger btnMinus" data-product-code="${data.productCode}"> <i class="fa fa-minus"></i> </button>
                            </div>
                            <input type="number" readonly value="${data.productQty}" id="cart-qty-${data.productCode}" class="form-control text-center" aria-label="Amount (to the nearest dollar)">
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
					swal(
						{
							title: response.message,
							text: "Click 'Continue' for new order or 'Cancel' to go back to orders list...",
							icon: "success",
							showCancelButton: true,
							confirmButtonText: "Continue",
							closeOnClickOutside: false,
						},
						function (result) {
							if (result) {
								window.location.reload();
							} else {
								window.location.replace(base_path + "order/listRecords");
							}
						}
					);
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
		toastr.warning("No items are present in your cart. Please at least add one (1) item/product to the cart", "Opps..", {
			progressBar: true,
		});
		return false;
	}
});
