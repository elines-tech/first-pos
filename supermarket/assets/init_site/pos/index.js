const preloader = $("div.preloader");
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
const createCustomerButton = document.querySelector(
  "button.btn-create-customer"
);

const applyGiftButton = document.querySelector("button.btn-apply-gift");
const giftCode = document.querySelector("input[id='giftNo']");

const newCustomerPhone = document.querySelector("input[id='newCustomerPhone']");
const newCustomerName = document.querySelector("input[id='newCustomerName']");
const barcodeText = document.querySelector("input[id='barcodeText']");

const tempOrderId = document.querySelector("input[id='tempOrderId']");
const customerCode = document.querySelector("input[id='customerCode']");
const customerName = document.querySelector("input[id='customerName']");
const customerPhone = document.querySelector("input[id='customerPhone']");
const amountdet = document.querySelector("input[id='amountdet']");

const itemsCount = document.querySelector(".itemsCountText");
const subTotal = document.querySelector(".subTotalText");
const discountTotal = document.querySelector(".discountTotalText");
const offerDiscountTotal = document.querySelector(".offerDiscountText");
const taxTotal = document.querySelector(".taxTotalText");
const giftDiscountTotal = document.querySelector(".giftDiscountText");
const payableTotal = document.querySelector(".payableTotalText");
const finalamount = document.querySelector(".finalAmountText");

const offerInfo = document.querySelector(".offerInfo");
const giftcardInfo = document.querySelector(".giftcardInfo");

const offerCode = document.querySelector("input[id='offerCode']");
const offerDiscount = document.querySelector(".offerDiscount");

const API_BASE = document.querySelector("meta[name='basesite']").content;
const posApiUrl = API_BASE + "Api/posorder/";
const lang = document
  .querySelector("meta[name='userlang']")
  .content.toLowerCase();
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
  ////debugger;
  if (product.variantName == null || product.variantName == "") {
  } else {
    var variantText = " | " + product.variantName;
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
            <td class="text-end">${product.taxAmount}</td>
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
            <td class="text-end">${product.taxAmount}</td>
            <td class="text-end">${product.totalPrice}</td>
        </tr>`;
    $("#order-rows").append(html);
  }

  let hasorderrows = document.querySelectorAll("tr.order-row");

  itemsCount.innerHTML = hasorderrows.length;
  subTotal.innerHTML = tempOrder.subTotal;
  discountTotal.innerHTML = tempOrder.discountTotal;
  offerDiscountTotal.innerHTML = Number(tempOrder.offerDiscountTotal).toFixed(
    2
  );
  giftDiscountTotal.innerHTML = Number(tempOrder.giftDiscount).toFixed(2);
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
        customerName: customerName.value,
        customerPhone: customerPhone.value,
      },
      dataType: "JSON",
      beforeSend: function () {
        preloader.show();
      },
      success: function (response) {
        if (response.status === "200") {
          var tempOrder = response.data.order;
          var tempProduct = response.data.product;
          push_product_row(tempProduct, tempOrder);
          preloader.delay(300).fadeOut(200);
          barcodeText.value = null;
          barcodeText.focus();
        } else {
          swal({ title: "Oops", text: response.message, type: "error", closeOnClickOutside: false }, function (e) {
            if (e) {
              preloader.delay(300).fadeOut(200);
              barcodeText.value = null;
              barcodeText.focus();
            }
          });
        }
      },
      error: function () {
        swal({ title: "Oops", text: "Cannot add product the cart. Please try with different barcode-product", type: "error", closeOnClickOutside: false }, function (e) {
          if (e) {
            barcodeText.value = null;
            barcodeText.focus();
            preloader.delay(300).fadeOut(200);
          }
        });
      }
    });
  }
}

function barcodehandler(e) {
  if (e.target.value.length >= 8) {
    fetchItemAddOrder(barcodeText.value);
  }
}

function clearCurrentOrderFromUI() {
  itemsCount.innerHTML = "0";
  /** set values to blank */
  orderrows.innerHTML =
    orderrows.innerHTML =
    barcodeText.value =
    customerPhone.value =
    customerName.value =
    customerCode.value =
    tempOrderId.value =
    offerCode.value =
    giftCode.value =
    amountdet.value =
    "";
  /** set texts to 0.00 */
  subTotal.innerHTML =
    offerDiscountTotal.innerHTML =
    giftDiscountTotal.innerHTML =
    discountTotal.innerHTML =
    taxTotal.innerHTML =
    payableTotal.innerHTML =
    finalamount.innerHTML =
    "0.00";
  /** remove set attributes */
  giftCode.removeAttribute("disabled");
  offerCode.removeAttribute("disabled");
  draftOrderButton.removeAttribute("disabled");
  offerCode.removeAttribute("readonly");
  giftCode.removeAttribute("readonly");

  /** set focus */
  barcodeText.value = "";
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
  var item = $(this).data("productid");
  var tempPrdId = $(this).data("temprowid");
  var rowCount = $("#productTable tr").length - 1;
  var conText = "";
  if (rowCount <= 1) {
    conText = "Do you want to cancel the current order completely? Action is not reversible.";
  } else {
    conText = "Want to remove the added product?";
  }
  swal(
    {
      title: "Cancel Order?",
      text: conText,
      type: "warning",
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
              $("#tmp-row-" + item).remove();
              barcodeText.focus();
              toastr.success(response.message, "Success", {
                progressBar: true,
              });
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

function setAmounts(amountData) {
  //debugger;
  subTotal.innerHTML = amountData.subTotal;
  offerDiscountTotal.innerHTML = amountData.totalOfferDiscount;
  giftDiscountTotal.innerHTML = amountData.totalGiftDiscount;
  taxTotal.innerHTML = amountData.totalTax;
  payableTotal.innerHTML = amountData.totalPayable;
  finalamount.innerHTML = amountData.totalPayable;
  amountdet.value = JSON.stringify(amountData);
}

function apply_offer_gift() {
  var btnOffer = $("button.btn-add-offer");
  var btnGift = $("button.btn-apply-gift");
  $.ajax({
    type: "POST",
    url: `${posApiUrl}applyOfferGift`,
    data: {
      orderId: tempOrderId.value,
      giftCode: giftCode.value,
      offerCode: offerCode.value,
    },
    dataType: "JSON",
    beforeSend: function () {
      $(".preloader").show();
      btnOffer.attr("disabled", true);
      btnGift.attr("disabled", true);
    },
    success: function (response) {
      if (response.status === 200) {
        setAmounts(response.data);
        if (giftCode.value != "") {
          giftCode.setAttribute("readonly", true);
        }
        if (offerCode.value != "") {
          offerCode.setAttribute("readonly", true);
        }
        draftOrderButton.setAttribute("disabled", true);
        toastr.success(response.message, "Extra Discount", {
          progressBar: true,
        });
      } else {
        toastr.error(response.message, "Opps!", {
          progressBar: true,
        });
      }
    },
    complete: function () {
      $(".preloader").fadeOut(400);
      setTimeout(() => {
        btnOffer.removeAttr("disabled");
        btnGift.removeAttr("disabled");
      }, 300);
    },
  });
}

addOfferButton.addEventListener("click", (e) => {
  e.preventDefault();
  var btn = this;
  if (tempOrderId.value != "" && tempOrderId.value != undefined) {
    var total = Number(payableTotal.innerHTML);
    if (total > 0) {
      if (offerCode.value == "" && offerCode.value == undefined) {
        toastr.warning("Please enter offer code...", "Opps", {
          progressBar: true,
        });
        return false;
      }
      //call the main function where gift or offer is applied on order
      apply_offer_gift("offer");
    } else {
      toastr.warning("Total payable should be greater than 0 (zero).", "Opps", {
        progressBar: true,
      });
      return false;
    }
  } else {
    toastr.warning("No order is selected or items are not present", "Opps", {
      progressBar: true,
    });
    return false;
  }
});

applyGiftButton.addEventListener("click", (e) => {
  e.preventDefault();
  var btn = $(".btn-apply-gift");
  if (tempOrderId.value != "" && tempOrderId.value != undefined) {
    var total = Number(payableTotal.innerHTML);
    if (total > 0) {
      if (giftCode.value == "" && giftCode.value == undefined) {
        toastr.warning("Please enter gift no", "Opps", { progressBar: true });
        return false;
      }
      //call the main function where gift or offer is applied on order
      apply_offer_gift();
    } else {
      toastr.warning("Total payable should be greater than 0 (zero).", "Opps", {
        progressBar: true,
      });
      return false;
    }
  } else {
    toastr.warning("No order is selected or items are not present", "Opps", {
      progressBar: true,
    });
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
  if (tempOrderId.value != "" && tempOrderId.value != undefined) {
    swal(
      {
        type: "warning",
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
                toastr.success(response.message, "Success", {
                  progressBar: true,
                });
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
  } else {
    toastr.error("No order present to clear..", "Opps", {
      progressBar: true,
    });
  }
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
        amountdet: amountdet.value,
        offerCode: offerCode.value,
        giftCode: giftCode.value,
      },
      timeout: 0,
      dataType: "JSON",
      beforeSend: function () {
        $(".preloader").show();
      },
      success: function (response) {
        if (response.status === "200") {
          const title = `Success #${response.txnId}`;
          $("li#dord-" + tempOrderId.value).remove();
          clearCurrentOrderFromUI();
          toastr.success(response.message + " Printing Receipt...", title, {
            progressBar: true,
            positionClass: "toast-top-center",
            onHidden: function () {
              window.location.replace(
                `${API_BASE}Cashier/Pos/print/${response.txnId}`
              );
            },
          });
        } else {
          toastr.error(response.message, "Opps", {
            progressBar: true,
            positionClass: "toast-top-center",
          });
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
      type: "error",
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
  $("div.tb-order-box[data-draft-order-id='" + order.orderId + "']").addClass(
    "active"
  );
  if (products.length > 0) {
    orderrows.innerHTML = "";
    for (let index = 0; index < products.length; index++) {
      const product = products[index];
      var productName = "";
      var variantText = "";
      if (product.variantName == null || product.variantName == "") {
      } else {
        var variantText = " | " + product.variantName;
      }
      var discount = Number(product.discountPrice).toFixed(2);

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

      var html = `
                <tr id="tmp-row-${row_id}" class="order-row flash">
                    <td>
                        <button title="Delete Item?" type="button" class="btn-del-item" data-productid="${row_id}"><i class="fa fa-times-circle-o"></i></button>
                    </td>
                    <td>${productName}</td>
                    <td class="text-end">${product.price}</td>
                    <td class="text-end">${product.qty}</td>
                    <td class="text-end">${discount}</td>
                    <td class="text-end">${product.taxPercent}</td>
                    <td class="text-end">${product.tax}</td>
                    <td class="text-end">${product.totalPrice}</td>
                </tr>
                `;
      $("#order-rows").append(html);
      itemsCount++;
    }
  }

  tempOrderId.value = order.orderId;
  /** set texts */
  itemsCount.innerHTML = itemsCount;
  subTotal.innerHTML = order.subTotal;
  discountTotal.innerHTML = order.discountTotal;
  offerDiscountTotal.innerHTML = Number(order.offerDiscountTotal).toFixed(2);
  giftDiscountTotal.innerHTML = Number(order.giftDiscountTotal).toFixed(2);
  taxTotal.innerHTML = order.taxTotal;
  payableTotal.innerHTML = order.totalPayable;
  finalamount.innerHTML = order.totalPayable;
  /** set values */
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
        toastr.error(response.message, "Opps", {
          progressBar: true,
          positionClass: "toast-top-center",
        });
      }
    },
    complete: function () {
      //hide loading icon after fetchoing ther recordś
    },
  });
}

$(document).on("click", "span.text-order", function (e) {
  e.stopPropagation();
  var orderId = $(e.currentTarget)
    .closest("div.tb-order-box")
    .attr("data-draft-order-id");
  if (orderId !== undefined && orderId !== "") {
    findOrder(orderId);
  }
});

$(document).on("click", "span.cancel-order", function (e) {
  e.stopPropagation();
  var orderId = $(e.currentTarget)
    .closest("div.tb-order-box")
    .attr("data-draft-order-id");
  if (orderId !== undefined && orderId !== "") {
    swal(
      {
        title: "Cancel Order?",
        text: "Once delete, you will not be able to recover the order again!",
        type: "warning",
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
                toastr.error("Order was deleted successfully.", "Success", {
                  progressBar: true,
                  positionClass: "toast-top-center",
                });
              } else {
                toastr.error(response.message, "Failed", {
                  progressBar: true,
                  positionClass: "toast-top-center",
                });
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
