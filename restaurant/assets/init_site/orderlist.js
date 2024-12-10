let orders = [];
let reservations = [];
let kotOrdersRow = $("#kot-orders-row");
let tableReservation = $("#table-reservation");
let reservationInterval = (1000 * 120);
let branchCode = document.querySelector("meta[name='branchCode']").content;
let userLang = document.querySelector("meta[name='userLang']").content;

function fetchOrders() {
  $.ajax({
    type: "GET",
    url: base_path + "Api/order/fetchOrders",
    data: {
      branchCode: branchCode
    },
    dataType: "JSON",
    beforeSend: function () {
      $("#order-loader").show();
    },
    success: function (response) {
      if (response.status === "200") {
        orders = response.data;
        setTimeout(() => {
          setOrderUI();
        }, 300);
      }
    },
    complete: function () {
      setTimeout(() => {
        $("#order-loader").hide();
      }, 400);
    },
  });
}

function fetchReservedTables() {
  $.ajax({
    type: "GET",
    url: base_path + "Api/order/getReservedTables",
    data: {
      branchCode: branchCode
    },
    dataType: "JSON",
    beforeSend: function () {
      $("#tblres-loader").show();
    },
    success: function (response) {
      if (response.status === "200") {
        reservations = response.data;
        console.log(reservations);
        setTimeout(() => {
          setReservations();
        }, 300);
      }
    },
    complete: function () {
      setTimeout(() => {
        $("#tblres-loader").hide();
      }, 400);
    },
  });
}

function setReservations() {
  if (reservations.length > 0) {
    tableReservation.empty();
    for (let index = 0; index < reservations.length; index++) {
      const reservation = reservations[index];
      var buttons = "";
      if (reservation.orderOngoing !== "1") {
        buttons = `<button type="button" class="btn btn-sm btn-success btn-ord-prs" data-id="${reservation.revCode}">Order Processing</button>`;
      } else {
        buttons = `<button type="button" class="btn btn-sm btn-danger btn-cnl-rsvr" data-id="${reservation.revCode}"> Cancel Reservation </button>`;
      }
      var htmlRender = `
        <div class="col-12 mb-2">
          <div class="card">
            <div class="card-body p-1">
              <div> Name : ${reservation.customerName} </div>
              <div> Phone : ${reservation.customerMobile} </div>
              <div style="font-size:0.85rem;margin-bottom:5px">
                <span class="me-2">Sector : <b>${reservation.zoneName}</b></span>
                <span class="me-2">Table : <b>${reservation.tableNumber}</b></span>                
              </div>
              <div style="font-size:0.85rem;margin-bottom:5px">
                <span class="me-2"> Date : <b>${reservation.resDate}</b></span>        
                <span class="me-2"> From : <b>${reservation.startTime}</b> To :<b>${reservation.endTime}</b></span>         
              </div>              
              <div>
                ${buttons}
              </div>
            </div>
          </div>
        </div>
      `;
      tableReservation.append(htmlRender);
    }
  }
}

$(document).on("click", "button.btn-cnl-rsvr", function (e) {
  var btn = $(this);
  var reservCode = $(this).data("id");
  swal(
    {
      title: "Cancel Reservation",
      text: "Are you sure you want to cancel the reservation for the selected table?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes",
      cancelButtonText: "No",
      closeOnClickOutside: false,
    },
    function (result) {
      if (result) {
        $.ajax({
          type: "POST",
          url: base_path + "Api/order/freeTableReservaion",
          data: {
            reservCode: reservCode,
          },
          dataType: "JSON",
          beforeSend: function () {
            btn.html('<i class="fa fa-circle-o-notch spin"></i>');
            btn.prop("disabled", true);
          },
          success: function (response) {
            if (response.status === "200") {
              toastr.success(
                "Reservation cancelled successfully",
                "Success",
                {
                  progressBar: true,
                }
              );
              fetchReservedTables();
            } else {
              toastr.error("Failed to take the action. Please try agian later", "Failed", {
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

$(document).on("click", "button.btn-ord-prs", function (e) {
  toastr.error("Order is being placed & processing from this reserved table. Check KOT's list", "Opps");
});

function setOrderUI() {
  if (orders.length > 0) {
    for (let index = 0; index < orders.length; index++) {
      const order = orders[index];
      const htmlRender = renderOrderHtmlCard(order, index);
      kotOrdersRow.append(htmlRender);
    }
  }
}

function renderOrderHtmlCard(order, index) {
  var html = "";
  if (order.kots.length > 0) {
    var kots = order.kots;
    var kotHtml = "";
    var badgeStatus = "";
    kots.forEach((kot) => {
      switch (kot.status) {
        case "PND":
          badgeStatus = '<b class="badge-pnd">Pending ...</b>';
          break;
        case "PRE":
          badgeStatus = '<b class="badge-pre">Preparing ...</b>';
          break;
        case "RTS":
          badgeStatus = '<b class="badge-rts">Ready-to-Serve ...</b>';
          break;
      }

      kotHtml += `
            <div class="cx-kot-item kotBox${index}" id="kotDiv_${kot.id}${kot.kotNumber}">
                <div class="cx-kot-item-title">
                    KOT No.: <b>${kot.kotNumber}</b>
					<button class="cx-op-trash float-end removeKot" data-kot="${kot.kotNumber}" data-index="${index}"  data-seq="${kot.id}" title="Trash KOT"><i class="fa fa-trash"></i></button>
                    <button class="cx-op-kot float-end kotDetails" data-kot="${kot.kotNumber}" data-seq="${kot.id}" title="View KOT"><i class="fa fa-eye"></i></button>
                </div>
                <div class="kot-status">${badgeStatus}</div>
            </div>`;
    });

    var form = `
            <form action="${base_path}Cashier/order/add" method="post" class="ms-1 w-100">
                <input type="hidden" id="branchCode${index}" name="branchCode" value="${order.branchCode}" readonly/>
                <input type="hidden" id="tableNumber${index}" name="tableNumber" value="${order.tableNumber}" readonly/>
                <input type="hidden" id="tableSection${index}" name="tableSection" value="${order.tableSection}" readonly/>
                <input type="hidden" id="custPhone${index}" name="custPhone" value="${order.custPhone}" readonly/>
                <input type="hidden" name="custName" value="${order.custName}" readonly/>
                <button type="submit" class="cx-new-kot float-end" title="New Order"><i class="fa fa-plus"></i></button>
            </form>
        `;

    var checkoutForm = `
            <form action="${base_path}Cashier/order/checkout" method="post" class="w-100">
                <input type="hidden" id="branchCode${index}" name="branchCode" value="${order.branchCode}" readonly/>
                <input type="hidden" id="tableNumber${index}" name="tableNumber" value="${order.tableNumber}" readonly/>
                <input type="hidden" id="tableSection${index}" name="tableSection" value="${order.tableSection}" readonly/>
                <input type="hidden" id="custPhone${index}" name="custPhone" value="${order.custPhone}" readonly/>
                <input type="hidden" name="custName" value="${order.custName}" readonly/>
                <button type="submit" class="btn btn-sm btn-success" title="Checkout">Checkout</button>
            </form>
        `;

    html = `<div class="col-sm-6 col-md-4 col-lg-3" id="orx-${order.custPhone
      }-${order.tableNumber}-${order.branchCode}">
            <div class="card ord-cx" id="orderDiv_${index + 1}">
                <span class="ord-cx-no">Order - ${index + 1}</span>
                <div class="ord-cx-top">
                    <div class="cx-phone">Phone : <span>${order.custPhone
      }</span></div>
                    <div class="cx-phone">Name : <span>${order.custName
      }</span></div>
                    <div class="cx-tbl-sec"><span class="cx-blk">${order.zoneName
      }</span><span class="cx-blk">${order.tblNo}</span></div>
                </div>                
                <div class="ord-cx-kot">
                    <div class="cx-kot-title">KOT'S ${form}</div>
                    <div class="cx-kot-list" >${kotHtml}</div>
                </div>
                <div class="ord-cx-hr"></div>
                <div class="ord-cx-btm">                    
                    ${checkoutForm}                                        
                </div>
            </div>
        </div>`;
  }
  return html;
}

function updateCartPrices(
  currentqty,
  isCombo,
  type,
  cartPrdId,
  originalComboPrice = "",
  cartId = ""
) {
  if (type == 1) {
    var btn = $("#addQty" + cartPrdId);
    var icon = '<i class="fa fa-plus" aria-hidden="true"></i>';
  } else {
    var btn = $("#minusQty" + cartPrdId);
    var icon = '<i class="fa fa-minus" aria-hidden="true"></i>';
  }
  if (currentqty == 1 && type == 2) {
    toastr.error("Quantity must be 1", "Invalid Quantity", {
      progressBar: true,
    });
    return false;
  } else {
    $.ajax({
      url: base_path + "Cashier/order/updateCartPrices",
      type: "POST",
      data: {
        type: type,
        cartPrdId: cartPrdId,
        isCombo: isCombo,
        cartId: cartId,
        originalComboPrice: originalComboPrice,
        currentqty: currentqty,
      },
      beforeSend: function () {
        btn.prop("disabled", true);
        btn.html('<i class="fa fa-spinner spin"></i>');
      },
      success: function (response) {
        if (response != "") {
          var res = response.split("$$");
          $(".panel-body").html("");
          $(".panel-body").html(res[0]);
        }
      },
      complete: function () {
        btn.prop("disabled", false);
        btn.html(icon);
      },
    });
  }
}

function removeAddon(productextraCode, cartPrdId) {
  var btn = $("#removeAddon" + productextraCode);
  var productprice = $("#cartProductPrice" + cartPrdId).text();
  $.ajax({
    url: base_path + "Cashier/order/removeAddon",
    type: "POST",
    data: {
      productextraCode: productextraCode,
      cartPrdId: cartPrdId,
    },
    beforeSend: function () {
      btn.prop("disabled", true);
      btn.html('<i class="fa fa-spinner spin"></i>');
    },
    success: function (response) {
      var obj = JSON.parse(response);
      if (obj.status) {
        $("#cartTotalPrice" + cartPrdId).text(obj.totalPrice);
        $("#addon_row" + productextraCode).remove();
        if (productprice == obj.totalPrice) {
          $("#extraDiv" + cartPrdId).remove();
        }
      } else {
        toastr.error(obj.message, "Opps", { progressBar: true });
      }
    },
    complete: function () {
      btn.prop("disabled", false);
      btn.html("<i class='fa fa-times'></i>");
    },
  });
}

function deleteProduct(cartPrdId) {
  var btn = $("#productRemoveBtn" + cartPrdId);
  $.ajax({
    url: base_path + "Cashier/order/deleteCartProduct",
    type: "POST",
    data: {
      cartId: cartPrdId,
    },
    beforeSend: function () {
      btn.prop("disabled", true);
      btn.html('<i class="fa fa-spinner spin"></i>');
    },
    success: function (response) {
      var obj = JSON.parse(response);
      if (obj.status) {
        $("#productDiv" + cartPrdId).remove();
      } else {
        toastr.error(obj.message, "Opps", { progressBar: true });
      }
    },
    complete: function () {
      btn.prop("disabled", false);
      btn.html("<i class='fa fa-trash'></i>");
    },
  });
}

$(function () {
  debugger
  fetchOrders();
  fetchReservedTables();
  setInterval(function () {
    fetchReservedTables();
  }, reservationInterval);
});

$("body").on("click", ".close", function (e) {
  $("#generl_modal").modal("hide");
});

$("body").on("click", ".kotDetails", function (e) {
  var cartId = $(this).data("seq");
  var kotNumber = $(this).data("kot");
  var btn = $(this);
  $.ajax({
    url: base_path + "order/getKotDetails",
    type: "POST",
    data: {
      cartId: cartId,
    },
    beforeSend: function () {
      btn.prop("disabled", true);
      btn.html('<i class="fa fa-spinner spin"></i>');
    },
    success: function (response) {
      if (response != "") {
        var res = response.split("$$");
        $("#generl_modal").modal("show");
        $("#kotId").html("KOT Number: " + kotNumber);
        $(".panel-body").html(res[0]);
      }
    },
    complete: function () {
      btn.prop("disabled", false);
      btn.html("<i class='fa fa-eye'></i>");
    },
  });
});

/*$("body").on("click", ".checkout-order", function (e) {
  var btn = $(this);
  var index = $(this).data("index");
  var branchCode = $("#branchCode" + index).val();
  var tableSection = $("#tableSection" + index).val();
  var tableNumber = $("#tableNumber" + index).val();
  var custPhone = $("#custPhone" + index).val();
  $.ajax({
    url: base_path + "order/getOrderDetails",
    type: "POST",
    data: {
      branchCode: branchCode,
      tableSection: tableSection,
      tableNumber: tableNumber,
      custPhone: custPhone,
      isDraft:1
    },
    beforeSend: function () {
      btn.prop("disabled", true);
      btn.html('Wait <i class="fa fa-spinner spin"></i>...');
    },
    success: function (response) {
      var obj = JSON.parse(response);
      if (obj.status) {
        $("#generl_modal1").modal("show");
        $(".select2").select2();
        $(".panel-body1").html(obj.orderHtml);
      } else {
        swal("No data found", "Failed", "error");
      }
    },
    complete: function () {
      btn.prop("disabled", false);
      btn.html("Checkout");
    },
  });
});*/

$(document).on("click", "button.draft-order", function (e) {
  e.preventDefault();
  var btn = $(this);
  var index = $(this).data("index");
  var branchCode = $("#branchCode" + index).val();
  var tableSection = $("#tableSection" + index).val();
  var tableNumber = $("#tableNumber" + index).val();
  var custPhone = $("#custPhone" + index).val();
  $.ajax({
    type: "post",
    url: base_path + "Api/order/draftOrder",
    data: {
      branchCode: branchCode,
      tableNumber: tableNumber,
      tableSection: tableSection,
      custPhone: custPhone,
    },
    dataType: "JSON",
    beforeSend: function () {
      btn.prop("disabled", true);
      btn.html('Wait <i class="fa fa-spinner spin"></i>...');
    },
    success: function (response) {
      if (response.status === "200") {
        toastr.success(response.message, "Success", {
          progressBar: true,
        });
        setTimeout(function () {
          fetchDraftOrders();
        }, 300);
        $("#orx-" + custPhone + "-" + tableNumber + "-" + branchCode).remove();
      } else {
        toastr.error(response.message, "Opps", {
          progressBar: true,
        });
      }
    },
    complete: function () {
      btn.prop("disabled", false);
      btn.html("Draft");
    },
  });
});

$("body").on("click", ".removeKot", function (e) {
  var cartId = $(this).data("seq");
  var kotNumber = $(this).data("kot");
  var index = $(this).data("index");
  var btn = $(this);
  var branchCode = $("#branchCode" + index).val();
  var tableNumber = $("#tableNumber" + index).val();
  var custPhone = $("#custPhone" + index).val();
  var kotCount = $(".kotBox" + index).length;
  $.ajax({
    url: base_path + "Cashier/order/removeKot",
    type: "POST",
    data: {
      kotNumber: kotNumber,
      cartId: cartId,
    },
    beforeSend: function () {
      btn.prop("disabled", true);
      btn.html('<i class="fa fa-spinner spin"></i>');
    },
    success: function (response) {
      var obj = JSON.parse(response);
      if (obj.status) {
        if (kotCount == 1) {
          $(
            "#orx-" + custPhone + "-" + tableNumber + "-" + branchCode
          ).remove();
        }
        $("#kotDiv_" + cartId + kotNumber).remove();
      } else {
        toastr.error(obj.message, "Opps", { progressBar: true });
      }
    },
    complete: function () {
      btn.prop("disabled", false);
      btn.html("<i class='fa fa-trash'></i>");
    },
  });
});
