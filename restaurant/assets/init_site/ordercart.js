const custName = $("input#custName");
const custPhone = $("input#custPhone");
const branchCode = $("input#branchCode");
const tableSection = $("input#tableSection");
const tableNumber = $("input#tableNumber");
const prdDetails = $("div.prd-det");
const addonTblBody = $("table#addonTable tbody");
const addonTbl = $("table#addonTable");
const preloader = $("div#preloader");
const cartProducts = $("#cart-products");

const cNm = $("#c-nm");
const cPh = $("#c-ph");
const cTbl = $("#c-tbl");
const cSec = $("#c-sec");

let windowFullScreen = false;
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
	fetchDraftOrders();
});

window.onscroll = function () {
	myFunction();
};

var header = document.querySelector("div.cat-pos");
var sticky = header.offsetTop;

function myFunction() {
	if (window.pageYOffset > sticky) {
		$(".cat-pos").hide();
		$(".cat-tp").show();
	} else {
		$(".cat-pos").show();
		$(".cat-tp").hide();
	}
}

function validatePhone() {
	var countryCode = $("#countryCode").val();
	$("#modaltablePhoneNo").data("parsley-pattern", countryCode);
}

$(document).on("click", "#mdlTblCust .close", function (e) {
	e.preventDefault();
	$("#mdlTblCust").modal("hide");
	//window.location.replace(base_path + "order/listRecords");
});

$(document).on("click", ".new_order", function (e) {
	e.preventDefault();
	$("#mdlTblCust").modal("show");
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
		var tblName = $("#modaltableCode").find(":selected").text();
		var zoneName = $("#modaltableCode").find(":selected").data("zone");
		var phone = $("#modaltablePhoneNo").val().trim();
		var name = $("#modaltableName").val().trim();

		custName.val(name);
		custPhone.val(phone);
		branchCode.val(branch);
		tableSection.val(section);
		tableNumber.val(tableNo);

		cNm.text(name);
		cPh.text(phone);
		cSec.text(zoneName);
		cTbl.text(tblName);

		$("#mdlTblCust").modal("hide");
	}
});

$(document).on("click", "button.prod-btn", function (e) {
	e.preventDefault();
	var phone = custPhone.val().trim();
	var name = custName.val().trim();
	var branch = branchCode.val().trim();
	var section = tableSection.val().trim();
	var table = tableNumber.val().trim();
	if (phone === "" && table === "") {
		toastr.error("Please provide customer and table details first", "Incomple Data", {
			progressBar: true
		});
	} else {
		prdDetails.empty();
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
					if (response.status === "200") {
						prdDetails.append(response.data);
						setTimeout(function () {
							$("#mdlProduct").modal("show"); preloader.fadeOut(300);
							$(`h4#totalProductAmount`).html($("#productAmount").val());
						}, 200);
					} else {
						toastr.error("Failed to get product details", "Failed", {
							progressBar: true,
							onHidden: function () {
								preloader.fadeOut(300);
							}
						});
					}
				},
				error: function () {
					toastr.error("Something went wrong", "Opps..", {
						progressBar: true,
						onHidden: function () {
							preloader.fadeOut(300);
						}
					});
				}
			});
		} else {
			toastr.error("Something went wrong", "Opps..", {
				progressBar: true,
			});
		}
	}
});

$(document).on("click", "button#add_to_cart", function (e) {
	e.preventDefault();
	var btn = $(this);
	var productCode = $("#productCode").val();
	var variantCode = $("#variantCode").val();
	var productCombo = $("#productCombo").val();
	var productNames = $("#productNames").val();
	var productTaxPercent = Number($("#productTaxPercent").val());
	var productTaxAmount = Number($("#productTaxAmount").val());
	var productQty = $("#productQty").val();
	var productPrice = Number($("#productPrice").val());
	var productAmount = Number($("#productAmount").val());
	var productComboCodes = $("#productComboCodes").val();
	var productComboNames = $("#productComboNames").val();
	var customizeItems = [];
	var addonItems = [];
	var cstItemsLen = $(".cstItmQty").length;
	var extItemsLen = $(".extItmQty").length;
	if (cstItemsLen > 0) {
		$(`.cstItmQty`).each(function (index, element) {
			var id = $(this).attr("id");
			var itemId = id.substr(7);
			if ($(`#cstItem${itemId}`).is(":checked")) {
				var itemQty = Number($(`#cstQty_${itemId}`).val());
				var ingQty = Number($(`#cstItemConsQty_${itemId}`).val());
				var consumeQty = itemQty * ingQty;
				var itemAmount = $(`#cstItemAmount_${itemId}`).val();
				var cstItemObject = {
					"itemCode": itemId,
					"itemTitle": $(`#cstItemTitle_${itemId}`).val(),
					"itemName": $(`#cstItemNames_${itemId}`).val(),
					"itemPrice": $(`#cstItemPrice_${itemId}`).val(),
					"itemUnitCode": $(`#cstItemUnitCode_${itemId}`).val(),
					"itemQty": itemQty,
					"itemConsumeQty": consumeQty,
					"itemAmount": itemAmount
				};
				customizeItems.push(cstItemObject);
			}
		});
	}
	//calculate extra item qty
	if (extItemsLen > 0) {
		$(`.extItmQty`).each(function (index, element) {
			var id = $(this).attr("id");
			var itemId = id.substr(7);
			if ($(`#extItem${itemId}`).is(":checked")) {
				if (Number($(this).val()) > 0) {
					var itemQty = Number($(`#extQty_${itemId}`).val());
					var ingQty = Number($(`#extItemConsQty_${itemId}`).val());
					var consumeQty = itemQty * ingQty;
					var itemAmount = $(`#extItemAmount_${itemId}`).val();
					var addonItemObject = {
						"itemCode": itemId,
						"itemTitle": $(`#extItemTitle_${itemId}`).val(),
						"itemName": $(`#extItemNames_${itemId}`).val(),
						"itemPrice": $(`#extItemPrice_${itemId}`).val(),
						"itemUnitCode": $(`#extItemUnitCode_${itemId}`).val(),
						"itemQty": itemQty,
						"itemConsumeQty": consumeQty,
						"itemAmount": itemAmount
					};
					addonItems.push(addonItemObject);
				}
			}
		});
	}

	var productPrice = (Number(productAmount) - Number(productTaxAmount)).toFixed(2);

	var postData = {
		branchCode: branchCode.val(),
		tableSection: tableSection.val(),
		tableNumber: tableNumber.val(),
		custPhone: custPhone.val(),
		custName: custName.val(),
		productCode: productCode,
		variantCode: variantCode,
		productQty: productQty,
		productPrice: productPrice,
		customizeItems: JSON.stringify(customizeItems),
		addonItems: JSON.stringify(addonItems),
		tax: productTaxPercent,
		taxAmount: productTaxAmount,
		totalPrice: productAmount,
		productCombo: productCombo,
		cmbProducts: productComboCodes,
		cmbProductsNames: productComboNames
	};

	$.ajax({
		url: base_path + "Api/order/cartAddProduct",
		type: "POST",
		data: postData,
		dataType: "JSON",
		beforeSend: function () {
			btn.prop("disabled", true);
			btn.html('Wait <i class="fa fa-spinner spin"></i> ...');
		},
		success: function (response) {
			if (response.status === "200") {
				if ($("#isDraft").val() == 1) {
					toastr.error(
						"Please process draft order first then add item to cart", "Error", {
						progressBar: true,
						onHidden: function () {
							btn.prop("disabled", false);
							btn.html("Add To cart");
							$("#totalProductAmount").text("0.00");
							$("#mdlProduct").modal("hide");
						},
					});
				} else {

					addItemToCart(response.data);
					toastr.success("Product/Item is added to cart..", "Success", {
						progressBar: true,
						onHidden: function () {
							btn.prop("disabled", false);
							btn.html("Add To cart");
							$("#totalProductAmount").text("0.00");
							$("#mdlProduct").modal("hide");
						},
					});
				}
			} else if (response.status === "300") {
				toastr.error(response.message, "Cannot Add!", {
					progressBar: true,
					onHidden: function () {
						btn.prop("disabled", false);
						btn.html("Add To cart");
						$("#totalProductAmount").text("0.00");
						$("#mdlProduct").modal("hide");
					},
				});
			} else {
				toastr.error(response.message, "Cannot Add!", {
					progressBar: true,
					onHidden: function () {
						btn.prop("disabled", false);
						btn.html("Add To cart");
						$("#totalProductAmount").text("0.00");
						$("#mdlProduct").modal("hide");
					},
				});
			}
		},
		error: function () {
			btn.prop("disabled", false);
			btn.html("Add To cart");
			toastr.error(response.message, "Cannot Add!", {
				progressBar: true,
				onHidden: function () {
					$("#mdlProduct").modal("hide");
				},
			});
		}
	});
});

$(document).on("click", "button.btnMinus", function (e) {
	e.preventDefault();
	const prdCode = $(this).data("product-code");
	const index = cartData.findIndex(function (product) {
		return product.productCode == prdCode;
	});
	if (index !== -1) {
		const cartItem = cartData[index];
		$("button.btnMinus").attr("disabled", true);
		$("button.btnPlus").attr("disabled", true);
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
			$("button.btnMinus").removeAttr("disabled");
			$("button.btnPlus").removeAttr("disabled");
		}
	}
});

$(document).on("click", "button.btnPlus", function (e) {
	e.preventDefault();
	var btn = $(this);
	const prdCode = $(this).data("product-code");
	const index = cartData.findIndex(function (product) {
		return product.productCode == prdCode;
	});
	if (index !== -1) {
		const cartItem = cartData[index];
		var currentCartItem = cartItem;
		currentCartItem.productQty + 1;
		currentCartItem.branchCode = branchCode.val();
		if (cartItem.productQty < 100) {
			$.ajax({
				type: "POST",
				url: base_path + "Api/order/checkProductInStock",
				data: currentCartItem,
				dataType: "JSON",
				async: false,
				time: 0,
				beforeSend: function () {
					$("button.btnMinus").attr("disabled", true);
					$("button.btnPlus").attr("disabled", true);
				},
				success: function (response) {
					if (response.status === "200") {
						const addons = cartItem.addOns;
						let addonTotal = 0;
						if (addons.length > 0) {
							addons.forEach(function (item) {
								const addonamount =
									Number(item.addonPrice) * Number(item.addonQty);
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
					} else {
						toastr.error(
							"Item is out of stock. You cannot add more items to cart",
							"Opps",
							{
								progressBar: true,
								onHidden: function () {
									$("button.btnMinus").removeAttr("disabled");
									$("button.btnPlus").removeAttr("disabled");
								},
							}
						);
					}
				},
				error: function () {
					$("button.btnMinus").removeAttr("disabled");
					$("button.btnPlus").removeAttr("disabled");
				},
				complete: function () {
					$("button.btnMinus").removeAttr("disabled");
					$("button.btnPlus").removeAttr("disabled");
				},
			});
		}
	}
});

function addItemToCart(data) {
	var extraText = customizeText = "";
	var comboArr = data.comboProductItemsName;
	if (comboArr !== null && comboArr.length > 0) {
		extraText = "Combo Includes :<br>";
		for (i = 0; i < comboArr.length; i++) {
			extraText =
				extraText +
				'<span class="badge bg-success" style="margin-right:5px;">' +
				comboArr[i].productName +
				"</span>";
		}
	}

	var addonArr = data.addonItems;
	if (addonArr != null && addonArr.length > 0) {
		extraText = "Addons :<br>";
		for (i = 0; i < addonArr.length; i++) {
			var item = addonArr[i];
			var addonText = `
				<span class="badge" style="background:#c71585;color:#ffffff">
					${item.itemTitle} - Qty : ${item.itemQty}
				</span>
			`;
			extraText += addonText;
		}
	}

	var customizeArr = data.customizeItems;
	if (customizeArr != null && customizeArr.length > 0) {
		customizeText = "Customized :<br>";
		for (i = 0; i < customizeArr.length; i++) {
			var item = customizeArr[i];
			var custText = `
				<span class="badge" style="background:#665d1e;color:#ffffff">
					${item.itemTitle} - Qty : ${item.itemQty}
				</span>
			`;
			customizeText += custText;
		}
	}

	var productHtml = `
        <div class="col-12 mb-2" id="cart-item-${data.productCode}">  
            <div class="row cartrow align-items-center">
                <div class="col-3 mx-2">
                    <div class="cartimg"> 
                        <img src="${data.productImage}" />
                    </div>
                </div>
                <div class="col">
					<div class="row">
					 <a href="javascript:void(0)" class="text-danger float-end rmv-cart-item" data-product-code="${data.productCode}"><i class="fa fa-times"></i></a>
					</div>
                    <h5 class="text-muted mb-1"> ${data.productName} <span class="float-end">${data.productPrice}</span></h5>`;
	if (extraText != "") {
		productHtml += `<div class="mb-2"><h6>${extraText}</h6></div>`;
	}
	if (customizeText != "") {
		productHtml += `<div class="mb-2"><h6>${customizeText}</h6></div>`;
	}
	productHtml += `
					<div class="mb-2 d-none">
						<div class="input-group d-none w-80">
							<div class="input-group-prepend">
								<button type="button" class="btn btn-sm btn-outline-danger btnMinus" data-product-code="${data.productCode}"> <i class="fa fa-minus"></i> </button>
							</div>
							<input type="hidden" id="isDraft" value="0" name="isDraft">
							<input type="number" readonly value="${data.productQty}" id="cart-qty-${data.productCode}" class="form-control text-center" aria-label="Amount (to the nearest dollar)">
							<div class="input-group-append">
								<button type="button" class="btn btn-sm btn-outline-danger btnPlus" data-product-code="${data.productCode}"> <i class="fa fa-plus"></i> </button>
							</div>
						</div>  
					</div>               
					<div class="col-12">
						<b> Qty : <span id="prd-amt-${data.productCode}" style="font-weight:600">${data.productQty}</span></b>
						<b> Price :<span id="prd-amt-${data.productCode}" style="font-weight:600">${data.totalPrice}</span></b>
					</div>
                </div>
            </div>
        </div>
    `;
	cartProductCodes.push(data.productCode);
	cartData.push(data);
	$("button.draft-order").css("display", "inline");
	$("button.btn-place-order").removeClass("w-100").addClass("w-50");
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
			custName: $("input#custName").val().trim(),
			custPhone: $("input#custPhone").val().trim(),
			branchCode: $("input#branchCode").val().trim(),
			tableSection: $("input#tableSection").val().trim(),
			tableNumber: $("input#tableNumber").val().trim(),
			products: cartData,
			isDraft: $("input#isDraft").val().trim(),
			draftId: $("input#draftId").val().trim(),
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
								window.location.replace(base_path + "Cashier/order/listRecords");
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

function fetchDraftOrders() {
	$.ajax({
		type: "get",
		url: base_path + "Api/order/fetchDraftOrders",
		data: {},
		dataType: "json",
		beforeSend: function () {
			$("#draft-loader").show();
		},
		success: function (response) {
			if (response.status === "200") {
				$("#draft-orders").html(response.data);
			}
		},
		complete: function () {
			setTimeout(() => {
				$("#draft-loader").hide();
			}, 400);
		},
	});
}

$(document).on("click", "button.trash-draft-order", function (e) {
	e.preventDefault();
	swal(
		{
			title: "Trash Draft Order",
			text: "Are you sure you want to remove order from draft?",
			icon: "warning",
			showCancelButton: true,
			confirmButtonText: "Yes",
			cancelButtonText: "No",
			closeOnClickOutside: false,
		},
		function (result) {
			if (result) {
				var btn = $(this);
				var draftId = $(this).data("draft-id");
				$.ajax({
					type: "POST",
					url: base_path + "Api/order/trashDraftOrder",
					data: {
						draftId: draftId,
					},
					dataType: "JSON",
					beforeSend: function () {
						btn.html('<i class="fa fa-circle-o-notch spin"></i>');
						btn.prop("disabled", true);
					},
					success: function (response) {
						if (response.status === "200") {
							toastr.success(
								"Draf-order was removed successfully.",
								"Success",
								{
									progressBar: true,
								}
							);
							fetchDraftOrders();
							cartProductCodes, (cartData = []);
							if ($("#isDraft").val() == 1) {
								$("#section_title").html(
									'Current Order/Cart <span id="cartCount">0</span>'
								);
								$(".displayTitles").val("");
								$(".hiddenInputs").val("");
								$(".cart-products").html("");
								$(".cartTotal").html("0.00");
								$(".draft-order").removeClass("d-none");
								$(".btn-place-order").removeClass("w-100").addClass("w-50");
							}
						} else {
							toastr.error("Failed to remove the draft-order...", "Failed", {
								progressBar: true,
							});
						}
					},
					complete: function () {
						btn.html("<i class='fa fa-trash'></i>");
						btn.prop("disabled", false);
					},
				});
			}
		}
	);
});

$(document).on("click", "a.trash-draft-order", function (e) {
	e.preventDefault();
	swal(
		{
			title: "Trash Draft Order",
			text: "Are you sure you want to remove order from draft?",
			icon: "warning",
			showCancelButton: true,
			confirmButtonText: "Yes",
			cancelButtonText: "No",
			closeOnClickOutside: false,
		},
		function (result) {
			if (result) {
				var btn = $(this);
				var draftId = $(this).data("draft-id");
				$.ajax({
					type: "POST",
					url: base_path + "Api/order/trashDraftOrder",
					data: {
						draftId: draftId,
					},
					dataType: "JSON",
					beforeSend: function () {
						btn.html('<i class="fa fa-circle-o-notch spin"></i>');
						btn.prop("disabled", true);
					},
					success: function (response) {
						if (response.status === "200") {
							toastr.success(response.message, "Success", {
								progressBar: true,
							});
							fetchDraftOrders();
							cartProductCodes, (cartData = []);
							if ($("#isDraft").val() == 1) {
								$("#section_title").html(
									'Current Order/Cart <span id="cartCount">0</span>'
								);
								$(".displayTitles").val("");
								$(".hiddenInputs").val("");
								$(".cart-products").html("");
								$(".cartTotal").html("0.00");
								$(".draft-order").removeClass("d-none");
								$(".btn-place-order").removeClass("w-100").addClass("w-50");
							}
						} else {
							toastr.error(response.message, "Failed", {
								progressBar: true,
							});
						}
					},
					complete: function () {
						btn.html("<i class='fa fa-trash'></i>");
						btn.prop("disabled", false);
					},
				});
			} else {
				swal.close();
			}
		}
	);
});

$(document).on("click", "button.trash-order", function (e) {
	e.preventDefault();
	swal(
		{
			title: "Trash Order",
			text: "Are you sure you want to remove product from cart from draft?",
			icon: "warning",
			showCancelButton: true,
			confirmButtonText: "Yes",
			cancelButtonText: "No",
			closeOnClickOutside: false,
		},
		function (result) {
			if (result) {
				$("#cartCount").text(Number(0).toFixed(2));
				$("#cartTotal").text(Number(0).toFixed(2));
				$("#cart-products").html("");
				cartProductCodes, (cartData = []);

				$("#section_title").html(
					'Current Order/Cart <span id="cartCount">0</span>'
				);
				$(".displayTitles").val("");
				$(".hiddenInputs").val("");
				$(".cart-products").html("");
				$(".cartTotal").html("0.00");
				$(".draft-order").removeClass("d-none");
				$(".btn-place-order").removeClass("w-100").addClass("w-50");
			}
		}
	);
});

$(document).on("click", "button.draft-order", function (e) {
	e.preventDefault();
	var btn = $(this);

	if (cartData.length > 0) {
		const orderData = {
			custName: $("input#custName").val().trim(),
			custPhone: $("input#custPhone").val().trim(),
			branchCode: $("input#branchCode").val().trim(),
			tableSection: $("input#tableSection").val().trim(),
			tableNumber: $("input#tableNumber").val().trim(),
			products: cartData,
		};

		$.ajax({
			type: "POST",
			url: base_path + "Api/order/draftOrder",
			data: orderData,
			dataType: "JSON",
			beforeSend: function () {
				btn.html('<i class="fa fa-circle-o-notch spin"></i> Wait...');
				btn.prop("disabled", true);
			},
			success: function (response) {
				if (response.status === "200") {
					toastr.success(response.message, "Success", {
						progressBar: true,
					});

					setTimeout(() => {
						cartData = [];
						cartProductCodes = [];
						cartProducts.empty();
						custName.val("");
						custPhone.val("");
						branchCode.val("");
						tableSection.val("");
						tableNumber.val("");
						cNm.text("");
						cPh.text("");
						cSec.text("");
						cTbl.text("");
						$("#cartCount").text("0");
						$("#cartTotal").text("0");
						//$("#mdlTblCust").modal("show");
						$("#modaltableCode").val("");
						$("#modaltablePhoneNo").val("");
						$("#modaltableName").val("");
					}, 300);

					fetchDraftOrders();
				} else {
					toastr.success(response.message, "Failed", {
						progressBar: true,
					});
				}
			},
			complete: function () {
				btn.html("<i class='fa fa-copy'></i> Draft");
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

$(document).on("click", ".btn-full-screen", function (e) {
	e.preventDefault();
	if (!windowFullScreen) {
		var element = document.querySelector(".catproduct");
		if (element.requestFullscreen) {
			windowFullScreen = true;
			element.requestFullscreen();
		} else if (element.webkitRequestFullscreen) {
			/* Safari */
			windowFullScreen = true;
			element.webkitRequestFullscreen();
		} else if (element.msRequestFullscreen) {
			/* IE11 */
			windowFullScreen = true;
			element.msRequestFullscreen();
		}
		$(this).html('<i class="fa fa-compress"></i>');
	} else {
		if (document.exitFullscreen) {
			windowFullScreen = false;
			document.exitFullscreen();
		} else if (document.webkitExitFullscreen) {
			/* Safari */
			windowFullScreen = false;
			document.webkitExitFullscreen();
		} else if (document.msExitFullscreen) {
			/* IE11 */
			windowFullScreen = false;
			document.msExitFullscreen();
		}
		$(this).html('<i class="fa fa-expand"></i>');
	}
});

$(document).on("click", "a.draft-checkout", function (e) {
	e.preventDefault();
	var btn = $(this);
	var draftId = $(this).data("draft-id");
	$.ajax({
		type: "GET",
		url: base_path + "Api/order/fetchDraftOrderById",
		data: {
			draftId: draftId,
		},
		dataType: "JSON",
		beforeSend: function () {
			btn.html('<i class="fa fa-circle-o-notch spin"></i>');
			btn.prop("disabled", true);
		},
		success: function (response) {
			if (response.status === "200") {
				draftData = response.draftData[0];
				cart_body = `<input type="hidden" id="draftId" value="${draftData.id}" name="draftId">
					<input type="hidden" id="isDraft" value="1" name="isDraft">
					<input type="hidden" id="custName" name="custName" readonly value="${draftData.custName}">
                    <input type="hidden" id="custPhone" name="custPhone" readonly value="${draftData.custPhone}">
                    <input type="hidden" id="branchCode" name="branchCode" readonly value="${draftData.branchCode}">
                    <input type="hidden" id="tableSection" name="tableSection" readonly value="${draftData.tableSection}">
                    <input type="hidden" id="tableNumber" name="tableNumber" readonly value="${draftData.tableNumber}">
                    <div>
                        <div>Name <b id="c-nm">${draftData.custName}</b></div>
                        <div>Phone <b id="c-ph">${draftData.custPhone}</b></div>
                        <div>
                            <span>Table <b id="c-tbl">${draftData.tblNo}</b></span>
                            <span>Sector <b id="c-sec">${draftData.tableSection}</span>
                        </div>
                    </div>
                    <div class="row g-0" id="cart-products">
                    </div>`;

				$("html, body").animate(
					{
						scrollTop: $("#cartDiv").offset().top,
					},
					600
				);
				$("button#trashOrderBtn")
					.removeClass("trash-order")
					.addClass("trash-draft-order");
				$("button#trashOrderBtn").data("draft-id", draftData.id);
				$("button.draft-order").css("display", "none");
				$("button.btn-place-order").removeClass("w-50").addClass("w-100");
				$("#section_title").html(
					'Draft Order/Cart <span id="cartCount">' +
					response.data.length +
					"</span>"
				);
				$("#cart_body").html(cart_body);
				addDraftItemToPlace(response.data);
				console.log(response.data);
			} else {
				toastr.error(response.message, "Failed", {
					progressBar: true,
				});
			}
		},
		complete: function () {
			btn.html("<i class='fa fa-check'></i>");
			btn.prop("disabled", false);
		},
	});
});

function addDraftItemToPlace(pertData) {
	cartData.push(pertData);
	var productList = "";
	var totalPrice = 0;
	for (i = 0; i < pertData.length; i++) {
		var totalPrice = Number(
			Number(totalPrice) + Number(pertData[i]["totalPrice"])
		).toFixed(2);
		var extraText = "";
		var comboArr = pertData[i]["comboProductItems"];
		if (comboArr.length > 0) {
			extraText = "Combo Includes :<br>";
			for (j = 0; j < comboArr.length; j++) {
				extraText =
					extraText +
					'<span class="badge bg-success" style="margin-right:5px;">' +
					comboArr[j].productName +
					"</span>";
			}
		}

		var addonArr = pertData[i]["addOns"];
		if (addonArr.length > 0) {
			extraText = "Addons :<br>";
			for (k = 0; k < addonArr.length; k++) {
				extraText =
					extraText +
					addonArr[k].addonQty +
					" " +
					addonArr[k].addonTitle +
					"<span style='float:right'>" +
					addonArr[k].addonPrice +
					"</span><br>";
			}
		}
		var productList =
			productList +
			`<div class="col-12 mb-1" id="cart-item-${pertData[i]["productCode"]}">  
            <div class="row cartrow align-items-center">
                <div class="col-3 mx-2">
                    <div class="cartimg"> 
                        <img src="${pertData[i]["productImage"]}" />
                    </div>
                </div>
                <div class="col">
					<div class="row">
					 <a href="javascript:void(0)" class="text-danger float-end rmv-cart-item" data-product-code="${pertData[i]["productCode"]}"><i class="fa fa-times"></i></a>
					</div>
                    <h5 class="text-muted">${pertData[i]["productName"]}<span class="float-end">${pertData[i]["productPrice"]}</span></h5>`;
		if (extraText != "") {
			productList =
				productList +
				`<div class="mb-2">
						<h6>${extraText}</h6>
					</div>`;
		}
		productList =
			productList +
			`<div class="mb-2">
                        <div class="input-group w-80">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-sm btn-outline-danger btnMinus" data-product-code="${pertData[i]["productCode"]}"> <i class="fa fa-minus"></i> </button>
                            </div>
                            <input type="text" readonly value="${pertData[i]["productQty"]}" id="cart-qty-${pertData[i]["productCode"]}" class="form-control text-center" aria-label="Amount (to the nearest dollar)">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-sm btn-outline-danger btnPlus" data-product-code="${pertData[i]["productCode"]}"> <i class="fa fa-plus"></i> </button>
                            </div>
                        </div>  
                    </div>               
                    <div class="text-right"><span id="prd-amt-${pertData[i]["productCode"]}" style="font-weight:600">${pertData[i]["totalPrice"]}</span></div>
                </div>
            </div>
        </div>`;
		cartProductCodes.push(pertData[i]["productCode"]);
	}
	$("#cart-products").html("");
	$("#cart-products").html(productList);
	$("#cartTotal").text(totalPrice);
}

$(document).on("change", ".extChks", function (e) {
	var extItmID = $(this).val();
	if ($(this).is(":checked")) {
		$(`#extQty_${extItmID}`).removeAttr('readonly');
	} else {
		$(`#extQty_${extItmID}`).attr('readonly', true);
		$(`#extQty_${extItmID}`).val(0);
		$(`#extItemAmount_${extItmID}`).val('0.00');
	}
});

$(document).on("change", ".cstChks", function (e) {
	var cstItmId = $(this).val();
	if ($(this).is(":checked")) {
		$(`#cstQty_${cstItmId}`).removeAttr('readonly');
	} else {
		$(`#cstQty_${cstItmId}`).attr('readonly', true);
		$(`#cstQty_${cstItmId}`).val(0);
		$(`#cstItemAmount_${cstItmId}`).val('0.00');
	}
});

$(document).on("change keyup", ".extItmQty", function (e) {
	var id = $(this).attr("id");
	var itemId = id.substr(7);
	var qty = Number($(this).val());
	var extItemQty = $(`#extItemQty_${itemId}`).val();
	var extItemPrice = $(`#extItemPrice_${itemId}`).val();
	var consumeQty = Number(qty) * Number(extItemQty);
	$(`#extItemConsQty_${itemId}`).val(consumeQty);
	var itemAmount = (Number(qty) * Number(extItemPrice)).toFixed(2);
	$(`#extItemAmount_${itemId}`).val(itemAmount);
	setTimeout(function () {
		calculateItemPrices();
	}, 100);
});

$(document).on("change keyup", ".cstItmQty", function (e) {
	var id = $(this).attr("id");
	var itemId = id.substr(7);
	var qty = Number($(this).val());
	var cstItemQty = $(`#cstItemQty_${itemId}`).val();
	var cstItemPrice = $(`#cstItemPrice_${itemId}`).val();
	var consumeQty = Number(qty) * Number(cstItemQty);
	$(`#cstItemConsQty_${itemId}`).val(consumeQty);
	var itemAmount = (Number(qty) * Number(cstItemPrice)).toFixed(2);
	$(`#cstItemAmount_${itemId}`).val(itemAmount);
	setTimeout(function () {
		calculateItemPrices();
	}, 100);
});

$(document).on("change keyup", "input[id='productQty']", function () {
	calculateItemPrices();
});

function calculateItemPrices() {
	var totalProductAmount = Number(0);
	var cstItemsLen = $(".cstItmQty").length;
	var extItemsLen = $(".extItmQty").length;
	var productQty = Number($(`#productQty`).val());
	var productTaxPercent = Number($(`#productTaxPercent`).val());
	var productCombo = $(`#productCombo`).val();
	var productTaxAmount = Number($(`#productTaxAmount`).val());
	var productPrice = Number($(`#productPrice`).val());
	var productAmount = $(`#productAmount`);
	var ingredientsCost = Number(0);
	var productAmountValue = Number(productPrice) * Number(productQty);
	// calculate customizeItem Cost
	if (cstItemsLen > 0) {
		$(`.cstItmQty`).each(function (index, element) {
			var id = $(this).attr("id");
			var itemId = id.substr(7);
			if ($(`#cstItem${itemId}`).is(":checked")) {
				ingredientsCost = Number(ingredientsCost) + Number($(`#cstItemAmount_${itemId}`).val());
			}
		});
	}
	// calculate extra item qty
	if (extItemsLen > 0) {
		$(`.extItmQty`).each(function (index, element) {
			var id = $(this).attr("id");
			var itemId = id.substr(7);
			if ($(`#extItem${itemId}`).is(":checked")) {
				if (Number($(this).val()) > 0) {
					ingredientsCost = Number(ingredientsCost) + Number($(`#extItemAmount_${itemId}`).val());
				}
			}
		});
	}
	// add extra items, customize items price to products price 
	productAmountValue = productAmountValue + ingredientsCost;
	if (productCombo === "1") {
		totalProductAmount = (Number(productAmountValue) + Number(productTaxAmount)).toFixed(2);
		$(`#productTaxAmount`).val(productTaxAmount);
		$(`#totalProductAmount`).text(totalProductAmount);
		productAmount.val(totalProductAmount);
	} else {
		var taxAmount = (Number(productAmountValue) * (Number(productTaxPercent) * 0.01)).toFixed(2);
		$(`#productTaxAmount`).val(taxAmount);
		totalProductAmount = (Number(productAmountValue) + Number(taxAmount)).toFixed(2);
		$(`#totalProductAmount`).text(totalProductAmount);
		productAmount.val(totalProductAmount);
	}
}