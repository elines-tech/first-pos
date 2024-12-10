var offerData = [];
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
                calculateAmount();
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

$("body").on("click", ".applyCoupon", function (e) {
    var id = $(this).data("index");
    var couponCode = $("#code" + id)
        .val()
        .trim();
    var couponCodeText = $("#text" + id)
        .val()
        .trim();
    var voutureType = $("#voutureType" + id)
        .val()
        .trim();
    var minAmt = $("#minAmt" + id)
        .val()
        .trim();
    var totalPrice = $("#totalPrice").val().trim();
    if (Number(totalPrice) >= Number(minAmt)) {
        var offerDetails = (offerData = []);
        offerDetails = {
            type: $("#voutureType" + id)
                .val()
                .trim(),
            code: $("#code" + id)
                .val()
                .trim(),
            offerText: $("#text" + id)
                .val()
                .trim(),
            offerType: $("#type" + id)
                .val()
                .trim(),
            discount: $("#discount" + id)
                .val()
                .trim(),
            minAmt: $("#minAmt" + id)
                .val()
                .trim(),
            capLimit: $("#capLimit" + id)
                .val()
                .trim(),
            flatAmt: $("#flatAmount" + id)
                .val()
                .trim(),
        };
        offerData.push(offerDetails);
        var offerDataString = JSON.stringify(offerData);
        var btn = $("#applyBtn" + couponCode);
        btn.prop("disabled", true);
        btn.html('<i class="fa fa-spinner spin"></i>');
        calculateAmount(offerDataString, couponCode, couponCodeText);
    } else {
        toastr.error(
            "Cannot apply the offer/coupon. Order amount should be greater than or equal to minimum amount (" + minAmt + ")",
            "Not Applicable",
            {
                progressBar: true
            }
        );
    }
});

$(document).on("click", ".paynow", function () {
    $("#billForm").parsley();
    const form = document.getElementById("billForm");
    var formData = new FormData(form);
    var orderType = $("#orderType").val();
    var offerDataString = JSON.stringify(offerData);
    var subTotal = $("#subTotalText").text();
    var discount = $("#discountText").text();
    var discountPer = $("#discount").val();
    var tax = $("#actualTax").text();
    var serviceCharges = $("#serviceChargesText").text();
    var grandTotal = $("#grandTotalText").text();
    var tableCode = $("#tableCode").val();
    var remark = $("#remark").val();
    var paymentMode = $("#payment_method").val();
    var isValid = true;
    /*$("#billForm .form-control").each(function(e) {
              if ($(this).parsley().validate() !== true) isValid = false;
          });*/
    $("#billForm .form-select").each(function (e) {
        if ($(this).parsley().validate() !== true) isValid = false;
    });
    if (isValid) {
        formData.append("orderType", orderType);
        formData.append("paymentMode", paymentMode);
        formData.append("serviceCharges", serviceCharges);
        formData.append("tax", tax);
        formData.append("discount", discount);
        formData.append("subTotal", subTotal);
        formData.append("grandTotal", grandTotal);
        formData.append("tableCode", tableCode);
        formData.append("discountPer", discountPer);
        formData.append("offerData", offerDataString);
        formData.append("remark", remark);
        $.ajax({
            url: base_path + "Cashier/order/placeOrder",
            type: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "JSON",
            beforeSend: function () {
                $("#paynowBtn").prop("disabled", true);
                $("#paynowBtn").html(
                    'Please wait <i class="fa fa-spinner spin"></i>...'
                );
            },
            success: function (response) {
                $("#paynowBtn").prop("disabled", false);
                if (response.status) {
                    toastr.success(response.message, "Order", {
                        progressBar: true,
                        onHidden: function () {
                            window.location.replace(
                                base_path + "Cashier/order/print/" + response.orderCode
                            );
                        },
                    });
                } else {
                    toastr.error(response.message, "Order", {
                        progressBar: true,
                    });
                }
            },
            complete: function () {
                $("#paynowBtn").prop("disabled", false);
                $("#paynowBtn").text("Pay now");
            },
        });
    }
});

$(function () {
    calculateAmount();
    couponData();
});

function couponData() {
    var keywordsearch = $("#couponSearch").val();
    $.ajax({
        url: base_path + "Cashier/order/getCouponData",
        type: "POST",
        data: {
            keywordsearch: keywordsearch,
        },
        beforeSend: function () {
            $("#couponloader").removeClass("d-none");
        },
        success: function (response) {
            $("#couponDiv").html(response);
        },
        complete: function () {
            $("#couponloader").addClass("d-none");
        },
    });
}

function calculateAmount(
    offerDataString = [],
    couponCode = "",
    couponCodeText = ""
) {
    var cartId = $("#cartId").val();
    var cartPrdId = $("input[name='cartPrdId[]']")
        .map(function () {
            return $(this).val();
        })
        .get();
    var productCodes = $("input[name='productCode[]']")
        .map(function () {
            return $(this).val();
        })
        .get();
    var comboproductCodes = $("input[name='comboproductCode[]']")
        .map(function () {
            return $(this).val();
        })
        .get();
    var discount = $("#discount").val();
    if (couponCode == "") {
        var offerDataString = JSON.stringify(offerData);
    }
    $.ajax({
        url: base_path + "Cashier/order/calculateAmount",
        type: "POST",
        data: {
            offerDataString: offerDataString,
            cartPrdId: cartPrdId,
            productCodes: productCodes,
            comboproductCodes: comboproductCodes,
            discount: discount,
            cartId: cartId,
        },
        dataType: "JSON",
        beforeSend: function () {
            $("#loader").removeClass("d-none");
        },
        success: function (response) {
            var obj = response;
            if (obj.status === 200) {
                $("#subTotalText").text(obj.subTotal);
                $("#discountText").text(obj.discount);
                $("#subtotalafterdiscountText").text(obj.subtotalafterdiscount);
                $("#totalTax").text(obj.totalTax);
                $("#actualTax").text(obj.actualTax);
                $("#serviceChargesText").text(obj.serviceCharges);
                $("#grandTotalText").text(obj.grandTotal);
            } else {
                toastr.error(
                    "Couldnot perfom the calculation. May the products in order are been removed. Please refresh the page and try agian...",
                    "Opps",
                    {
                        progressBar: true
                    }
                );
            }
        },
        complete: function () {
            $("#loader").addClass("d-none");
            if (couponCode != "") {
                $("#applyBtn" + couponCode).prop("disabled", false);
                $(".applyCoupon").html("Apply");
                $(".applyCoupon").removeClass("btn-warning").addClass("btn-success");
                $("#applyBtn" + couponCode)
                    .removeClass("btn-success")
                    .addClass("btn-warning");
                $("#applyBtn" + couponCode).html("Applied"); 
                $("#couponCode").val(couponCodeText);
                $("#couponCodeValue").val(couponCode);
            }
        },
    });
}
