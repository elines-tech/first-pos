<nav class="navbar navbar-light">
    <div class="container d-block">
	   <?php if ($productData) {
             foreach ($productData->result() as $row) {  ?>
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last"><a href="<?php echo base_url(); ?>Product/listrecords/<?= $row->code ?>"><i id='exitButton' class="fa fa-times fa-2x"></i></a></div>
        </div>
		 <?php }
         } ?>
    </div>
</nav>


<div class="container">

    <!-- // Basic multiple Column Form section start -->
    <section id="multiple-column-form" class="mt-5">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Product Extras</h3>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form id="extrasForm" class="form" data-parsley-validate>
                                <?php
                                echo "<div class='text-danger text-center' id='error_message'>";
                                if (isset($error_message)) {
                                    echo $error_message;
                                }
                                echo "</div>";
                                ?>
                                <div class="form-group row">
                                    <?php if ($productData) {
                                        foreach ($productData->result() as $row) {  ?>
                                            <div class="form-row">
                                                <div class="col-md-7 col-12">
                                                    <div class="form-group">
                                                        <label for="product-name" class="form-label">Product Name</label>
                                                        <input type="text" id="product-name" class="form-control" placeholder="Product Name" name="product-name" value="<?= $row->productEngName ?>" readonly>
                                                        <input type="hidden" id="productCode" name="productCode" value="<?= $row->code ?>" class="form-control-line" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php }
                                    } ?>
                                </div>
                                <table class="table table-bordered table-hover" id="extrasTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Item Name<i class="text-danger">*</i></th>
                                            <th class="text-center">Unit<i class="text-danger">*</i></th>
                                            <th class="text-center">Qty<i class="text-danger">*</i></th>
                                            <th class="text-center">Cost<i class="text-danger">*</i></th>
                                            <th class="text-center">Customer Price<i class="text-danger">*</i></th>
                                            <th class="text-center" style="width:40px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        if ($productExtras) {
                                            foreach ($productExtras->result_array() as $co) {
                                                $i++;
                                        ?>
                                                <tr id="row<?= $i ?>">
                                                    <td class="span3 supplier">
                                                        <input type="hidden" class="form-control" name="extrasLineCode<?= $i ?>" id="extrasLineCode<?= $i ?>" value="<?= $co['code'] ?>">
                                                        <select class="form-select" id="itemCode<?= $i ?>" name="itemCode<?= $i ?>" onchange="checkDuplicateItem(<?= $i ?>);getItemStorageUnit(<?= $i ?>)">
                                                            <option value="">Select Item</option>
                                                            <?php
                                                            if ($items) {
                                                                foreach ($items->result() as $it) {
                                                                    if ($it->code == $co['itemCode']) {
                                                                        echo "<option value='" . $it->code . "' data-stock='" . $it->stock . "'selected>" . $it->itemEngName . "</option>";
                                                                    } else {
                                                                        echo "<option value='" . $it->code . "' data-stock='" . $it->stock . "'>" . $it->itemEngName . "</option>";
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td class="text-right">
                                                        <select class="form-control" id="itemUnit<?= $i ?>" name="itemUnit<?= $i ?>" disabled>
                                                            <option value=""></option>
                                                            <?php
                                                            if ($unitmaster) {
                                                                foreach ($unitmaster->result() as $um) {
                                                                    if ($um->code == $co['itemUom']) {
                                                                        echo "<option value='" . $um->code . "'selected>" . $um->unitName . "</option>";
                                                                    } else {
                                                                        echo "<option value='" . $um->code . "'>" . $um->unitName . "</option>";
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td class="text-right">
                                                        <input type="text" class="form-control" name="itemQty<?= $i ?>" id="itemQty<?= $i ?>" value="<?= $co['itemQty'] ?>" onchange="checkQty(<?= $i ?>);" onkeypress="return isNumber(event)">
                                                    </td>


                                                    <td class="text-right">
                                                        <input type="text" class="form-control" name="itemCost<?= $i ?>" id="itemCost<?= $i ?>" value="<?= $co['itemPrice'] ?>" onchange="checkPrice(<?= $i ?>);" onkeypress="return isDecimal(event)">

                                                    </td>

                                                    <td class="test">
                                                        <input type="text" class="form-control" name="itemCustPrice<?= $i ?>" id="itemCustPrice<?= $i ?>" value="<?= $co['custPrice'] ?>">
                                                    </td>

                                                    <td>
                                                        <a href="#" class="btn btn-danger" onclick="delete_row(<?= $i ?>,'<?= $co['code'] ?>')"><i class="fa fa-trash"></i>
                                                    </td>
                                                </tr>
                                        <?php }
                                        } ?>
                                        <tr id="row0">
                                            <td class="span3 supplier">
                                                <input type="hidden" class="form-control" name="extrasLineCode0" id="extrasLineCode0">
                                                <select class="form-select" id="itemCode0" name="itemCode0" onchange="checkDuplicateItem(0);getItemStorageUnit(0)">
                                                    <option value="">Select Item</option>
                                                    <?php
                                                    if ($items) {
                                                        foreach ($items->result() as $it) {
                                                            echo "<option value='" . $it->code . "' data-stock='" . $it->stock . "'>" . $it->itemEngName . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td class="text-right">
                                                <select class="form-control" id="itemUnit0" name="itemUnit0" disabled>
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
                                            <td class="text-right">
                                                <input type="text" class="form-control" name="itemQty0" id="itemQty0" onchange="checkQty(0);" onkeypress="return isNumber(event)">
                                            </td>

                                            <td class="text-right">
                                                <input type="text" class="form-control" name="itemCost0" id="itemCost0" onchange="checkPrice(0);" onkeypress="return isDecimal(event)">

                                            </td>

                                            <td class="test">
                                                <input type="text" class="form-control" name="itemCustPrice0" id="itemCustPrice0">
                                            </td>
                                            <td>
                                                <a href="#" id="customize" class="btn btn-success" onclick="add_row()"><i class="fa fa-plus"></i>
                                            </td>
                                        </tr>
                                    </tbody>

                                </table>
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-end">
									<?php if($insertRights==1){ ?>
                                        <button type="submit" class="btn btn-success" id="saveProductExtras">Save</button> 
									<?php } ?>
                                        <button type="reset" class="btn btn-light-secondary" id="cancelProductExtras">Reset</button>
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
        var today = new Date().toISOString().split('T')[0];
        $("#extrasForm").on("submit", function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var form = $(this);
            form.parsley().validate();
            if (form.parsley().isValid()) {
                var table = document.getElementById("extrasTable");
                var table_len = (table.rows.length) - 1;
                var tr = table.getElementsByTagName("tr");
                var table = document.getElementById("extrasTable");
                var table_len = (table.rows.length) - 1;
                var tr = table.getElementsByTagName("tr");
                if (table_len == 1 && ($('#itemCode0').val() == '' || $('#itemQty0').val() == '' || $('#itemUnit0').val() == '' || $('#itemCost0').val() == '' || $('#itemCustPrice0').val() == '')) {
                    toastr.error('Please provide at least one entry', 'Product Extras', {
                        "progressBar": true
                    });
                } else {
                    var ajax_complete = 0;
                    var num = 0;
                    for (i = 1; i <= table_len; i++) {
                        var id = tr[i].id.substring(3);

                        var productCode = document.getElementById("productCode").value;
                        var extrasLineCode = document.getElementById("extrasLineCode" + id).value;
                        var itemCode = document.getElementById("itemCode" + id).value;
                        var itemUnit = document.getElementById("itemUnit" + id).value;
                        var itemQty = document.getElementById("itemQty" + id).value;
                        var itemCost = document.getElementById("itemCost" + id).value;
                        var itemCustPrice = document.getElementById("itemCustPrice" + id).value;
                        if (itemCode != '' && itemUnit != '' && itemQty != '' && itemCost != '' && itemCustPrice != '') {
                            num++;
                            $.ajax({
                                type: 'post',
                                url: base_path + "Product/saveExtrasLine",
                                data: {
                                    productCode: productCode,
                                    extrasLineCode: extrasLineCode,
                                    itemCode: itemCode,
                                    itemUnit: itemUnit,
                                    itemQty: itemQty,
                                    itemCost: itemCost,
                                    itemCustPrice: itemCustPrice,
                                },
                                success: function(response) {
                       
                                    ajax_complete++;
                                    if (num == ajax_complete) {
                                        toastr.success("Product Extras added successfully.", 'Product', {
                                            "progressBar": true
                                        });

                                        location.href = base_path + "Product/edit/"+productCode;
                                    } else {}
                                }
                            });
                        }
                    }
                }
            }
        });
    });


    function checkDuplicateItem(id) {
        var table = document.getElementById("extrasTable");
        var table_len = (table.rows.length) - 1;
        var tr = table.getElementsByTagName("tr");
        var itemCode = document.getElementById("itemCode" + id).value.toLowerCase();
        if (itemCode != "") {
            for (i = 1; i <= table_len; i++) {
                var row_id = tr[i].id.substring(3);
                var itemCode_row = document.getElementById("itemCode" + row_id).value.toLowerCase();
                if (itemCode_row == itemCode && row_id != id) {
                    toastr.error('Item already exists', 'Duplicate item', {
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

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
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

    function checkPrice(id) {
        var itemCost = Number($('#itemCost' + id).val());
        if (itemCost != '' && itemCost > 0) {} else {
            $('#itemCost' + id).val('');
            toastr.error('Item price should be greater than 0', 'Products Extras', {
                "progressBar": false
            });
            return false;
        }
    }

    function checkQty(id) {
        var itemQty = Number($('#itemQty' + id).val());
        if (itemQty != '' && itemQty > 0) {} else {
            $('#itemQty' + id).val('');
            toastr.error('Item Quantity should be greater than 0', 'Products Extras', {
                "progressBar": false
            });
            return false;
        }

    }

    function getItemStorageUnit(id) {
        var itemCode = $('#itemCode' + id).val();
        if (itemCode != '') {
            $.ajax({
                url: base_path + "Product/getItemStorageUnit",
                type: 'POST',
                data: {
                    'itemCode': itemCode
                },
                success: function(response) {
                    if (response != '') {
                        $('select#itemUnit' + id).val(response)
                    } else {
                        $('select#itemUnit' + id).val('')
                    }
                }
            })
        }
    }

    function add_row() {
        var table = document.getElementById("extrasTable");
        var table_len = (table.rows.length) - 1;
        var tr = table.getElementsByTagName("tr");
        var id = 0;
        var extrasLineCode = document.getElementById("extrasLineCode0").value;
        var itemCode = document.getElementById("itemCode0").value;
        var itemUnit = document.getElementById("itemUnit0").value;
        var itemQty = document.getElementById("itemQty0").value;
        var itemCost = document.getElementById("itemCost0").value;
        var itemCustPrice = document.getElementById("itemCustPrice0").value;
        if (itemCode != '' && itemUnit != '' && itemCost != '' && itemQty != '' && itemCustPrice != '') {} else {
            toastr.error('Please provide all the details', 'Products Extras', {
                "progressBar": false
            });
            return;
        }

        for (i = 1; i < table_len; i++) {
            var n = tr[i].id.substring(3);
            var id = n;
        }
        id++;
        var row = table.insertRow(table_len).outerHTML = '<tr id="row' + id + '">' +
            '<td><select class="form-control" name="itemCode' + id + '" id="itemCode' + id + '">' +
            document.getElementById("itemCode0").innerHTML +
            '</select>' +
            '<td><select class="form-control" name="itemUnit' + id + '" id="itemUnit' + id + '" disabled>' +
            document.getElementById("itemUnit0").innerHTML +
            '</select></td>' +
            '<input type="hidden" class="form-control" name="extrasLineCode' + id + '" id="extrasLineCode' + id + '" value="' + extrasLineCode + '"></td>' +
            '<td><input type="text" class="form-control" name="itemQty' + id + '" id="itemQty' + id + '" value="' + itemQty + '" onkeypress="return isNumber(event)" onchange="checkQty(' + id + ');" ></td>' +
            '<td><input type="text" class="form-control" name="itemCost' + id + '" id="itemCost' + id + '" value="' + itemCost + '" onkeypress="return isDecimal(event)" onchange="checkPrice(' + id + ');"></td>' +

            '<td><input type="text" class="form-control" name="itemCustPrice' + id + '" id="itemCustPrice' + id + '" value="' + itemCustPrice + '" disabled></td>' +
            '<td><a href="#" class="btn btn-danger" onclick="delete_row(' + id + ')"><i class="fa fa-trash"></i></a>' +
            '</td>' +
            '</tr>';
        document.getElementById("itemCode" + id).value = itemCode;
        document.getElementById("itemUnit" + id).value = itemUnit;
        document.getElementById("itemCode0").value = "";
        document.getElementById("itemQty0").value = "";
        document.getElementById("itemUnit0").value = "";
        document.getElementById("itemCost0").value = "";
        document.getElementById("itemCustPrice0").value = "";
        document.getElementById("itemCode0").focus();
    }

    function delete_row(id, lineCode) {
        swal({
            title: "Are you sure?",
            text: "You want to delete this product ",
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel it!",
            closeOnConfirm: !1,
            closeOnCancel: !1
        }, function(e) {
            if (e) {
                $.ajax({
                    url: base_path + "Product/deleteExtrasLine",
                    type: 'POST',
                    data: {
                        'lineCode': lineCode
                    },
                    dataType: "JSON",
                    success: function(response) {
                        if (response) {
                            swal({
                                    title: "Completed",
                                    text: "Successfully Deleted",
                                    type: "success"
                                },
                                function(isConfirm) {
                                    if (isConfirm) {
                                        var row = document.getElementById("row" + id);
                                        row.parentNode.removeChild(row);
                                    }
                                });
                        } else {
                            toastr.success('Record Not Deleted', 'Failed', {
                                "progressBar": true
                            });
                        }
                    }
                });
            }
        });
    }
</script>