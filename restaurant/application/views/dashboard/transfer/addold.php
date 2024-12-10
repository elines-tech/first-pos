<nav class="navbar navbar-light">
    <div class="container d-block">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last"><a href="<?php echo base_url(); ?>Transfer/listRecords"><i class="fa fa-times fa-2x"></i></a></div>
        </div>
    </div>
</nav>


<div class="container">

    <!-- // Basic multiple Column Form section start -->
    <section id="multiple-column-form" class="mt-5">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Add Transfer</h3>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form id="transferForm" class="form" data-parsley-validate>
                                <?php
                                echo "<div class='text-danger text-center' id='error_message'>";
                                if (isset($error_message)) {
                                    echo $error_message;
                                }
                                echo "</div>";
                                ?>
                                <div class="form-group row">
                                    <div class="col-md-3 col-sm-12 col-xs-12 mb-3">
                                        <div class="form-group mandatory">
                                            <label class="form-label">Date</label>
                                            <input type="date" class="form-control bg-white" name="transferDate" id="transferDate" value="<?= date('Y-m-d') ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xs-12 mb-3">
                                        <div class="form-group mandatory">
                                            <label class="form-label">From Branch</label>
                                            <div class="col-md-10 col-sm-12 col-xs-12">
                                                <input type="hidden" class="form-control" id="transferCode" name="transferCode">
                                                <select class="form-select select2" name="fromBranch" id="fromBranch" data-parsley-required="true" style="width:100%" required>
                                                    <option value="">Select</option>
                                                    <?php if ($branch) {
                                                        foreach ($branch->result() as $br) {
                                                            echo '<option value="' . $br->code . '">' . $br->branchName . '</option>';
                                                        }
                                                    } ?>
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xs-12 mb-3">
                                        <div class="form-group mandatory">
                                            <label class="form-label">To Branch</label>
                                            <div class="col-md-10 col-sm-12 col-xs-12">
                                                <select class="form-select select2" name="toBranch" id="toBranch" data-parsley-required="true" required>
                                                    <option value="">Select</option>
                                                    <?php if ($branch) {
                                                        foreach ($branch->result() as $br) {
                                                            echo '<option value="' . $br->code . '">' . $br->branchName . '</option>';
                                                        }
                                                    } ?>
                                                </select>
                                            </div>

                                            <!--<a href="<?php echo base_url(); ?>Branch/listRecords" class="col-md-2 col-sm-12 col-xs-12 circleico btn btn-primary a_plus text-light d-flex align-items-center justify-content-center rounded-circle shadow-sm " style="">
                                                <i class="fa fa-plus"></i>
                                            </a>-->
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-bordered table-hover" id="transferTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Item Name<i class="text-danger">*</i></th>
                                            <th class="text-center">Price<i class="text-danger">*</i></th>
                                            <th class="text-center">Qty<i class="text-danger">*</i></th>
                                            <th class="text-center">UOM</th>
                                            <th class="text-center">Sub Total</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="row0">
                                            <td class="span3 supplier">
                                                <input type="hidden" class="form-control" name="transferLineCode0" id="transferLineCode0">
                                                <select class="form-select select2" id="itemCode0" name="itemCode0" onchange="checkDuplicateItem(0);getItemStorageUnit(0);calculate_subTotal(0)" disabled>
                                                    <option value="">Select Item</option>
                                                    <?php
                                                    if ($items) {
                                                        foreach ($items->result() as $it) {
                                                            echo "<option value='" . $it->code . "' data-stock='" . $it->stock . "' data-price='" . $it->price . "'>" . $it->itemEngName . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td class="text-right">
                                                <input type="text" class="form-control" name="itemPrice0" id="itemPrice0" onchange="checkPrice(0);"  readonly onkeypress="return isDecimal(event)" onkeyup="calculate_subTotal(0)">

                                            </td>
                                            <td class="text-right">
                                                <input type="text" class="form-control" name="itemQty0" id="itemQty0" onchange="checkQty(0);" onkeypress="return isNumber(event)" onkeyup="calculate_subTotal(0)">
                                            </td>
                                            <td class="text-right">
                                                <select class="form-select select2" id="itemUnit0" name="itemUnit0" disabled>
                                                    <option value=""></option>
                                                    <?php
                                                    if ($unitmaster) {
                                                        foreach ($unitmaster->result() as $um) {
                                                            echo "<option value='" . $um->code . "'>" . $um->unitName . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td class="test">
                                                <input type="text" class="form-control" name="subTotal0" id="subTotal0" disabled>
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-success" onclick="add_row()"><i class="fa fa-plus"></i>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5" class="text-right"><b>Total :</b></td>
                                            <td class="text-right">
                                                <input type="text" id="total" class="text-right form-control" name="total" value="0.00" readonly="readonly" autocomplete="off">
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-end">
									<?php if($insertRights==1){ ?>
                                        <button type="submit" class="btn btn-success white me-2 mb-1 sub_1" id="saveTransferBtn">Transfer</button>
									<?php } ?>
                                        <button type="reset" class="btn btn-light-secondary me-1 mb-1" id="cancelTransferBtn">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- // Basic multiple Column Form section end -->
</div>
<script>
    $(document).ready(function() {
        $('.cancel').removeClass('btn-default').addClass('btn-info');
        var today = new Date().toISOString().split('T')[0];
        document.getElementsByName("transferDate")[0].setAttribute('max', today);
        $("#transferForm").on("submit", function(e) {
            e.preventDefault();
            debugger
            var formData = new FormData(this);
            var form = $(this);
            form.parsley().validate();
            if (form.parsley().isValid()) {
                var table = document.getElementById("transferTable");
                var table_len = (table.rows.length) - 1;
                var tr = table.getElementsByTagName("tr");
                formData.append('total', $('#total').val())
                var table = document.getElementById("transferTable");
                var table_len = (table.rows.length) - 1;
                var tr = table.getElementsByTagName("tr");
                if (table_len == 1 && ($('#itemCode0').val() == '' || $('#itemQty0').val() == '' || $('#itemUnit0').val() == '' || $('#itemPrice0').val() == '' || $('#subTotal0').val() == '')) {
                    toastr.error('Please provide at least one entry', 'Inward', {
                        "progressBar": true
                    });
                } else {
                    $.ajax({
                        type: "POST",
                        url: base_path + "Transfer/saveTransfer",
                        enctype: 'multipart/form-data',
                        contentType: false,
                        processData: false,
                        data: formData,
                        beforeSend: function() {
                            $('#saveTransferBtn').prop('disabled', true);
                            $('#cancelTransferBtn').prop('disabled', true);
                            $('#saveTransferBtn').text('Please wait..');
                        },
                        success: function(data) {
                            $('#saveTransferBtn').text('Save');
                            $('#saveTransferBtn').prop('disabled', false);
                            $('#cancelTransferBtn').prop('disabled', false);
                            var obj = JSON.parse(data);
                            if (obj.status) {
                                var ajax_complete = 0;
                                var num = 0;
                                for (i = 1; i <= table_len; i++) {
                                    var id = tr[i].id.substring(3);
                                    document.getElementById("transferCode").value = obj.transferCode;
                                    var fbranchCode = $('#fromBranch').val();
                                    var tbranchCode = $('#toBranch').val();
                                    var transferCode = document.getElementById("transferCode").value;
                                    var transferLineCode = document.getElementById("transferLineCode" + id).value;
                                    var itemCode = document.getElementById("itemCode" + id).value;
                                    var itemUnit = document.getElementById("itemUnit" + id).value;
                                    var itemQty = document.getElementById("itemQty" + id).value;
                                    var itemPrice = document.getElementById("itemPrice" + id).value;
                                    var subTotal = document.getElementById("subTotal" + id).value;
                                    if (itemCode != '' && itemUnit != '' && itemQty != '' && itemPrice != '' && subTotal != '') {
                                        num++;
                                        $.ajax({
                                            type: 'post',
                                            url: base_path + "Transfer/saveTransferLine",
                                            data: {
                                                transferCode: transferCode,
                                                transferLineCode: transferLineCode,
                                                itemCode: itemCode,
                                                itemUnit: itemUnit,
                                                itemQty: itemQty,
                                                itemPrice: itemPrice,
                                                subTotal: subTotal,
                                                fromBranch: fbranchCode,
                                                toBranch: tbranchCode
                                            },
                                            success: function(response) {
                                                debugger
                                                ajax_complete++;
                                                if (num == ajax_complete) {
                                                    toastr.success(obj.message, 'Transfer added', {
                                                        "progressBar": true
                                                    });

                                                    location.href = base_path + "Transfer/listRecords"
                                                } else {}
                                            }
                                        });
                                    }
                                }
                            } else {
                                toastr.error(obj.message, 'Transfer', {
                                    "progressBar": true
                                });
                            }
                        }
                    });
                }
            }
        });


        $("body").on("change", "#toBranch", function(e) {
            e.preventDefault();
            var toBranch = $("#toBranch").val();
            var fromBranch = $('#fromBranch').val();
            if (toBranch == fromBranch) {
                $("#toBranch").val('');
                $('#fromBranch').val('');
                toastr.error("From branch and To branch should not be same", 'Transfer', {
                    "progressBar": true
                });
                return false;

            }
        });
        var branchCode = "";
        $("body").on("change", "#fromBranch", function(e) {
            e.preventDefault();
            var toBranch = $("#toBranch").val();
            var fromBranch = $('#fromBranch').val();
            if (toBranch == fromBranch) {
                $("#toBranch").val('');
                $('#fromBranch').val('');
                toastr.error("From branch and To branch should not be same", 'Transfer', {
                    "progressBar": true
                });
                return false;

            }
            if (branchCode !== "") {
                $(".newitem").remove();
            }
            branchCode = $('#fromBranch').val();
            $.ajax({
                url: base_path + 'index.php/Transfer/getItems',
                data: {
                    'branchCode': branchCode
                },
                type: 'post',
                success: function(response) {
                    var res = JSON.parse(response);

                    if (res.status == 'true') {
                        $('select#itemCode0').attr('disabled', false);
                        $('select#itemCode0').empty();
                        $('select#itemCode0').append(res.items);
                    } else {
                        $('#fromBranch').val('');
                        $('select#itemCode0').attr('disabled', true);
                        toastr.error("No items are available in the selected branch. Please choose another.", 'Invalid Branch', {
                            "progressBar": true
                        });

                    }
                }
            });
        });


    });


    function checkDuplicateItem(id) {
        var table = document.getElementById("transferTable");
        var table_len = (table.rows.length) - 2;
        var tr = table.getElementsByTagName("tr");
        var itemCode = document.getElementById("itemCode" + id).value.toLowerCase();
        if (itemCode != "") {
            for (i = 1; i <= table_len; i++) {
                var row_id = tr[i].id.substring(3);
                var itemCode_row = document.getElementById("itemCode" + row_id).value.toLowerCase();
                if (itemCode_row == itemCode && row_id != id) {
                    toastr.error('Item already exists', 'Duplicate inward item', {
                        "progressBar": true
                    });
                    document.getElementById("itemCode" + id).value = "";
                    document.getElementById("itemUnit" + id).value = "";
                    document.getElementById("itemCode" + id).focus();
                    return 1;
                }
            }
        }
    }


    function isDecimal(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if ((charCode >= 48 && charCode <= 57) || (charCode >= 96 && charCode <= 105) || charCode == 8 || charCode == 9 || charCode == 37 ||
            charCode == 39 || charCode == 46 || charCode == 190) {
            return true;
        } else {
            return false;
        }
    }

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    function checkPrice(id) {
        var itemPrice = Number($('#itemPrice' + id).val());
        if (itemPrice != '' && itemPrice > 0) {} else {
            $('#itemPrice' + id).val('');
            toastr.error('Item price should be greater than 0', 'Transfer Products', {
                "progressBar": false
            });
            return false;
        }
    }

    function checkQty(id) {
        var itemQty = Number($('#itemQty' + id).val());
        if (itemQty != '' && itemQty > 0) {} else {
            $('#itemQty' + id).val('');
            toastr.error('Item Quantity should be greater than 0', 'Transfer Products', {
                "progressBar": false
            });
            return false;
        }

    }
	

    function calculate_subTotal(id) {

        var itemQty = Number($('#itemQty' + id).val());
        var itemPrice = Number($('#itemPrice' + id).val());
        var availableStock = $('#itemCode' + id).find(':selected').data('stock');
        if (availableStock > 0 && itemQty > 0 && availableStock < itemQty) {
            $('#itemQty' + id).val('');
            toastr.error('Stock not present', 'Transfer Products', {
                "progressBar": false
            });
            return false;

        } else {
            subTotal = itemQty * itemPrice;
            $('input#subTotal' + id).val(subTotal.toFixed(2));
            calculateTotal();
        }
    }

    function calculateTotal() {
        var table = document.getElementById("transferTable");
        var table_len = (table.rows.length) - 2;
        var tr = table.getElementsByTagName("tr");
        var total = 0;
        for (i = 1; i <= table_len; i++) {
            var id = tr[i].id.substring(3);
            total = Number(total) + Number($('#subTotal' + id).val());
        }
        $('input#total').val(total.toFixed(2));
    }

    function getItemStorageUnit(id) {
        var itemCode = $('#itemCode' + id).val();
	    var price = $('#itemCode' + id).find(':selected').data('price');
		$('#itemPrice' + id).val(price);
        if (itemCode != '') {
            $.ajax({
                url: base_path + "Transfer/getItemStorageUnit",
                type: 'POST',
                data: {
                    'itemCode': itemCode
                },
                success: function(response) {
                    if (response != '') {
                        $('#itemUnit' + id).val(response).trigger('change');
                    } else {
                        $("#itemCode" + id).val('').trigger('change');
                    }
                }
            })
        }
    }

    function add_row() {
        var table = document.getElementById("transferTable");
        var table_len = (table.rows.length) - 2;
        var tr = table.getElementsByTagName("tr");
        var id = 0;
        var transferLineCode = document.getElementById("transferLineCode0").value;
        var itemCode = document.getElementById("itemCode0").value;
        var itemUnit = document.getElementById("itemUnit0").value;
        var itemQty = document.getElementById("itemQty0").value;
        var itemPrice = document.getElementById("itemPrice0").value;
        var subTotal = document.getElementById("subTotal0").value;
        if (itemCode != '' && itemUnit != '' && itemPrice != '' && itemQty != '' && subTotal != '') {} else {
            toastr.error('Please provide all the details', 'Transfer Products', {
                "progressBar": false
            });
            return;
        }

        for (i = 1; i < table_len; i++) {
            var n = tr[i].id.substring(3);
            var id = n;
        }
        id++;
        var row = table.insertRow(table_len).outerHTML = '<tr id="row' + id + '" class="newitem">' +
            '<td><select class="form-select select2" name="itemCode' + id + '" id="itemCode' + id + '" >' +
            document.getElementById("itemCode0").innerHTML +
            '</select>' +
            '<input type="hidden" class="form-control" name="transferLineCode' + id + '" id="transferLineCode' + id + '" value="' + transferLineCode + '"></td>' +
            '<td><input type="text" class="form-control" name="itemPrice' + id + '" id="itemPrice' + id + '" value="' + itemPrice + '" onkeyup="calculate_subTotal(' + id + ')" readonly onchange="checkPrice(' + id + ');" onkeypress="return isDecimal(event)"></td>' +
            '<td><input type="text" class="form-control" name="itemQty' + id + '" id="itemQty' + id + '" value="' + itemQty + '" onkeyup="calculate_subTotal(' + id + ')" onchange="checkQty(' + id + ');" onkeypress="return isNumber(event)"></td>' +
            '<td><select class="form-control" name="itemUnit' + id + '" id="itemUnit' + id + '" disabled>' +
            document.getElementById("itemUnit0").innerHTML +
            '</select></td>' +
            '<td><input type="text" class="form-control" name="subTotal' + id + '" id="subTotal' + id + '" value="' + subTotal + '" disabled></td>' +
            '<td><a href="#" class="btn btn-danger" onclick="delete_row(' + id + ')"><i class="fa fa-trash"></i></a>' +
            '</td>' +
            '</tr>';
        document.getElementById("itemCode" + id).value = itemCode;
        document.getElementById("itemUnit" + id).value = itemUnit;
        //document.getElementById("itemCode0").value = "";
        $("#itemCode0").val('').trigger('change');
		 $('.select2').select2({
			tags: true,
			width: '100%'
		  });
        document.getElementById("itemQty0").value = "";
        document.getElementById("itemUnit0").value = "";
        document.getElementById("itemPrice0").value = "";
        document.getElementById("subTotal0").value = "";
        document.getElementById("itemCode0").focus();
        if (table_len > 2) {
            $('#fromBranch').attr('disabled', true);
        }
    }

    function delete_row(id) {
        var table = document.getElementById("transferTable");
        var table_len = (table.rows.length) - 2;
        var tr = table.getElementsByTagName("tr");
        swal({
            title: "Are you sure?",
            text: "You want to delete this item ",
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel it!",
            closeOnConfirm: !1,
            closeOnCancel: !1
        }, function(e) {
            if (e) {
                swal.close();
                var row = document.getElementById("row" + id);
                row.parentNode.removeChild(row);
                if (table_len == 2) {
                    $('#fromBranch').attr('disabled', false);
                }
            } else {
                swal.close();
            }
        });
    }
</script>