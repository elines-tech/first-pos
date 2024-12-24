<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Product Variants</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Product Variants</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="container">
            <section id="multiple-column-form" class="mt-5">
                <div class="row match-height">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3>
                                    Product Variants
                                    <?php if ($productData) {
                                        foreach ($productData->result() as $row) {  ?>
                                            <span class="float-end">
                                                <a id="cancelDefaultButton" type="button" href="<?= base_url() ?>product/edit/<?= $row->code ?>" class="btn btn-sm btn-primary m-1">Back</a>
                                            </span>
                                    <?php }
                                    } ?>
                                </h3>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form id="variantsForm" class="form" method="post" enctype="multipart/form-data" action="<?= base_url('Product/saveVariantsLine') ?>" data-parsley-validate>
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
                                                        <div class="col-md-12 col-12">
                                                            <div class="form-group text-center">
                                                                <label for="product-name" class="form-label">Product Name</label>
                                                                <input type="text" id="product-name" class="form-control" placeholder="Product Name" name="product-name" value="<?= $row->productEngName ?>" readonly>
                                                                <input type="hidden" id="productCode" name="productCode" value="<?= $row->code ?>" class="form-control-line" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                            <?php }
                                            } ?>
                                        </div>
                                        <table class="table table-hover" id="variantsTable">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Variants SKU</th>
                                                    <th class="text-center">Variants Name<i class="text-danger">*</i></th>
                                                    <th class="text-center">Status<i class="text-danger">*</i></th>
                                                    <th class="text-center" style="width:40px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="table-rows">
                                                <?php
                                                $i = 0;
                                                if ($productVariants) {
                                                    foreach ($productVariants->result_array() as $co) {
                                                ?>
                                                        <tr id="row<?= $i ?>" class="inrows removeclassP<?= $i ?>">
                                                            <td>
                                                                <input type="text" class="form-control" name="variantSKU[]" id="variantSKU<?= $i ?>" value="<?= $co['sku'] ?>" onchange="checkDuplicateSKU(<?= $i ?>)">
                                                            </td>
                                                            <td class="span3 supplier">
                                                                <input type="hidden" class="form-control" name="extrasLineCode[]" id="extrasLineCode<?= $i ?>" value="<?= $co['code'] ?>">
                                                                <input type="text" class="form-control" name="variantName[]" id="variantName<?= $i ?>" value="<?= $co['variantName'] ?>" onchange="checkDuplicateItem(<?= $i ?>)">
                                                            </td>
                                                            <td>
                                                                <div class="form-check text-center">
                                                                    <input type="hidden" name="isActive[]" id="tisActive<?= $i ?>" value="<?= $co['isActive'] == 1 ? 1 : 0 ?>" readonly>
                                                                    <input class="form-check-input" type="checkbox" id="isActive<?= $i ?>" <?= $co['isActive'] == 1 ? "checked" : "" ?>>
                                                                    <label class="form-check-label" for="isActive<?= $i ?>">
                                                                        Active
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <a href="#" class="btn btn-danger" onclick="delete_row(<?= $i ?>,'<?= $co['code'] ?>')"><i class="fa fa-trash"></i>
                                                            </td>
                                                        </tr>
                                                <?php
                                                        $i += 1;
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                        <div id="pricesection_add_btn">
                                            <div class="col-md-1 mb-3">
                                                <?php if ($i == 1) { ?>
                                                    <button id="view" class="btn btn-success" type="button" onclick="add_row(1,'add');"><i class="fa fa-plus"></i></button>
                                                <?php } else { ?>
                                                    <button id="view" class="btn btn-success" type="button" onclick="add_row(<?= $i - 1 ?>,'edit');"><i class="fa fa-plus"></i></button>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-success" id="saveProductExtras">Save</button>
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
        </div>
    </div>
</div>
<script>
    var room = 1;
    var objTo = document.querySelector('#table-rows');
    $(document).on("click", ".form-check-input", function(e) {
        var id = $(this).attr("id");
        if ($(this).is(":checked")) {
            $(`#t${id}`).val("1");
        } else {
            $(`#t${id}`).val("0");
        }
    });

    function checkDuplicateItem(id) {
        var table = document.getElementById("variantsTable");
        var table_len = (table.rows.length) - 1;
        var tr = table.getElementsByTagName("tr");
        var variantName = document.getElementById("variantName" + id).value.toLowerCase();
        if (variantName != "") {
            for (i = 1; i <= table_len; i++) {
                var rowClass = tr[i].classList;
                var row_id = rowClass[1].substring(12);
                var variantName_row = document.getElementById("variantName" + row_id).value.toLowerCase();
                if (variantName_row == variantName && row_id != id) {
                    toastr.error('Variant already exists', 'Duplicate Product Variant', {
                        "progressBar": true
                    });
                    document.getElementById("variantName" + id).value = "";
                    document.getElementById("variantName" + id).focus();
                    return 1;
                }
            }
        }
    }


    function checkDuplicateSKU(id) {
        var table = document.getElementById("variantsTable");
        var table_len = (table.rows.length) - 1;
        var tr = table.getElementsByTagName("tr");
        var variantSKU = document.getElementById("variantSKU" + id).value.toLowerCase();
        if (variantSKU != "") {
            for (i = 1; i <= table_len; i++) {
                var rowClass = tr[i].classList;
                var row_id = rowClass[1].substring(12);
                var variantSKU_row = document.getElementById("variantSKU" + row_id).value.toLowerCase();
                if (variantSKU_row == variantSKU && row_id != id) {
                    toastr.error('SKU already exists', 'Duplicate Product Variant SKU', {
                        "progressBar": true
                    });
                    document.getElementById("variantSKU" + id).value = "";
                    document.getElementById("variantSKU" + id).focus();
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
        var sellingPrice = Number($('#sellingPrice' + id).val());
        if (sellingPrice != '' && sellingPrice > 0) {} else {
            $('#sellingPrice' + id).val('');
            toastr.error('Selling price should be greater than 0', 'Products Variants', {
                "progressBar": false
            });
            return false;
        }
    }

    function checkQty(id) {
        var sellingQty = Number($('#sellingQty' + id).val());
        if (sellingQty != '' && sellingQty > 0) {} else {
            $('#sellingQty' + id).val('');
            toastr.error('Selling Quantity should be greater than 0', 'Products Variants', {
                "progressBar": false
            });
            return false;
        }

    }

    function delete_row(id, lineCode) {
        swal({
            title: "Are you sure?",
            text: "You want to delete this product variant",
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel it!",
            closeOnConfirm: !1,
            closeOnCancel: true
        }, function(e) {
            if (e) {
                $.ajax({
                    url: base_path + "Product/deleteVariantsLine",
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

    function add_row(count, flag) {
        if (flag == 'edit') {
            if (room == 1) {
                room = count;
            }
        } else room = count;

        if (room == undefined || room == null) room = 0;
        variantSKU = $(`#variantSKU${room}`).val();
        extraLineCode = $(`#extrasLineCode${room}`).val();
        variantName = $(`#variantName${room}`).val();
        if ($(`#variantName${room}`).length > 0) {
            if (variantName == "" || variantName == null) {
                $(`#variantName${room}`).val(null);
                $(`#variantName${room}`).focus();
                toastr.error("Please enter variant name", "Product Extras");
                return false;
            }
        }
        $(".add_btn").empty().append('<button type="button" class="btn btn-danger remove_price_fields" data-id="' + room + '"><i class="fa fa-trash"></i></button>');
        room++;
        var divtest = document.createElement("tr");
        divtest.setAttribute("class", "inrows removeclassP" + room);
        var element = `
			<td>
				<input type="text" class="form-control" name="variantSKU[]" id="variantSKU${room}" onchange="checkDuplicateSKU(${room})">
			</td>
			<td class="span3 supplier">
				<input type="hidden" class="form-control" name="extrasLineCode[]" id="extrasLineCode${room}">
				<input type="text" class="form-control" name="variantName[]" id="variantName${room}" onchange="checkDuplicateItem(${room})">
			</td>
            <td>
                <div class="form-check text-center">
                    <input type="hidden" name="isActive[]" id="tisActive${room}" value="1" readonly>
                    <input class="form-check-input" type="checkbox" value="1" id="isActive${room}" checked>
                    <label class="form-check-label" for="isActive${room}">
                        Active
                    </label>
                </div>
			</td>
			<td>
				<button type="button" class="btn btn-danger remove_price_fields" data-id="${room}"><i class="fa fa-trash"></i></button>
			</td>
		`;
        divtest.innerHTML = element;
        objTo.appendChild(divtest);
        $("#pricesection_add_btn").empty().append('<div class="col-md-1 mb-3"><button id="view" type="button" data-flag="edit" data-id="' + room + '" class="btn btn-success add_fields"><i class="fa fa-plus"></i></button></div>');
    }

    $(document).ready(function() {

        var today = new Date().toISOString().split('T')[0];

        $("body").delegate(".add_fields", "click", function() {
            var pos = $(this).data('id');
            var flag = $(this).data('flag');
            add_row(pos, flag);
        });

        $("body").delegate(".remove_price_fields", "click", function(e) {
            e.preventDefault();;
            var remove_room_id = Number($(this).data('id'));
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
                    $(".removeclassP" + remove_room_id).remove();
                    $("#pricesection_add_btn").empty().append('<div class="col-md-1 mb-3"><button type="button" data-id="' + (remove_room_id - 1) + '" class="btn btn-success add_fields" ><i class="fa fa-plus"></i></button></div>');
                    calculateTotal();
                } else {
                    swal.close();
                }
            });
        });

        var data = '<?php echo $this->session->flashdata('message');
                    unset($_SESSION['message']); ?>';
        if (data != '') {
            var obj = JSON.parse(data);
            if (obj.status) {
                toastr.success(obj.message, 'Product', {
                    "progressBar": true
                });
            } else {
                toastr.error(obj.message, 'Product', {
                    "progressBar": true
                });
            }
        }


    });
</script>