<nav class="navbar navbar-light">
    <div class="container d-block">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last"><a href="<?php echo base_url(); ?>Product/listrecords"><i class="fa fa-times fa-2x"></i></a></div>
        </div>
    </div>
</nav>
<div class="container">
    <!-- // Basic multiple Column Form section start -->
    <section id="multiple-column-form" class="mt-5">
        <div class="row match-height">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Product Addons</h3>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <h6>Product Details</h6>
                            <hr />
                            <?php if ($productData) {
                                foreach ($productData->result() as $row) {  ?>
                                    <div class="form-row">
                                        <div class="col-md-7 col-12">
                                            <div class="form-group mandatory">
                                                <label for="product-name" class="form-label">Product Name</label>
                                                <input type="text" id="product-name" class="form-control" placeholder="Product Name" name="product-name" value="<?= $row->productEngName ?>" readonly>
                                                <input type="hidden" id="productCode" name="productCode" value="<?= $row->code ?>" class="form-control-line" readonly>
                                            </div>
                                        </div>
                                    </div>
                            <?php }
                            } ?>
                        </div>

                        <div class="card-body">
                            <h6>Customize Addons</h6>
                            <hr />
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="categoryTitle" class="form-label">Category</label>
                                        <input id="categoryTitle" name="categoryTitle" class="form-control">
                                        <input readonly type="hidden" id="customizedCategoryCode" name="customizedCategoryCode">
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="categoryType" class="form-label">Addon/Variations</label>
                                        <select id="categoryType" name="categoryType" class="form-select">
                                            <option value="">Select Type</option>
                                            <option value="variation">Variation</option>
                                            <option value="addon">Add On</option>
                                            <option value="product">Product</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="categoryType" class="form-label">Status</label>
                                        <div class="form-check">
                                            <div class="checkbox">
                                                <label for="checkbox1">Enabled</label>
                                                <input type="checkbox" value="1" class="form-check-input" id="isCateEnabled" name="isCateEnabled" checked>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4  mb-3">
                                    <button type="button" class="btn btn-info btn-sm" id="addCustomizedCategory"><I class="fa fa-plus"></i> Category</button>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <!-- Nav tabs -->
                                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                            <?php
                                            if ($categories) {
                                                foreach ($categories->result_array() as $c) {
                                                    if ($c['categoryType'] == 'variation') {
                                                        $categoryType = 'Variation';
                                                    } else if ($c['categoryType'] == 'addon') {
                                                        $categoryType = 'Add On';
                                                    } else {
                                                        $categoryType = 'Product';
                                                    }
                                                    $enabled = "Disabled";
                                                    if ($c['isEnabled'] == 1) {
                                                        $enabled = "Enabled";
                                                    }
                                                    echo '<button class="nav-link" id="' . $c['code'] . '-tab" data-bs-toggle="tab" data-bs-target="#' . $c['code'] . '" role="tab"><div class="row"><div class="col-md-7">' . $c['categoryTitle'] . ' - ' . $categoryType . ' - ' . $enabled . '</div><div class="col-md-5"> <span class="mr-1 ml-2 btn btn-warning mx-3 deleteCustomizedCategory" id="deltab_' . $c['code'] . '"><i class="fa fa-trash"></i></span><span class="mr-1 btn btn-danger btn-xs editCustomizedCategory" id="edttab_' . $c['code'] . '" data-seq="' . $categoryType . '" ><i class="fa fa-pencil-square"></i></span></div></div></button>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="tab-content" id="v-pills-tabContent">
                                            <?php
                                            if ($categories) {
                                                foreach ($categories->result_array() as $c) {
                                                    echo '<div class="tab-pane fade" id="' . $c['code'] . '" role="tabpanel" aria-labelledby="' . $c['code'] . '-tab">';
                                                    $html = '<div class="row">
                                                            <div class="col-sm-4 col-12">                            		  
                                                                <div class="form-group">';
                                                    if ($c['categoryType'] == 'variation') {
                                                        $html .= '<label for="subTitle' . $c['code'] . '">Variation Title</label>';
                                                    } else  if ($c['categoryType'] == 'addon') {
                                                        $html .= '<label for="subTitle' . $c['code'] . '">Addon Name</label>';
                                                    } else {
                                                        $html .= '<label for="subTitle' . $c['code'] . '">Product Name </label>';
                                                    }
                                                    if ($c['categoryType'] == 'product') {
                                                        $html .= '<select id="subTitle' . $c['code'] . '" name="subTitle" class="form-control subAddonTitle"  data-id="' . $c['code'] . '" data-seq="' . $c['categoryType'] . '">
                                                        <option value="">Select Product</option>';
                                                        if ($products && $products->num_rows() > 0) {
                                                            foreach ($products->result() as $prd) {
                                                                $html .= '<option value="' . $prd->code . '">' . $prd->productEngName . '</option>';
                                                            }
                                                        }
                                                        $html .= '</select>
                                                        </div>	
                                                        </div>
                                                        <div class="col-sm-4  col-12">
                                                            <div class="form-group">';
                                                    } else {
                                                        $html .= '<input id="subTitle' . $c['code'] . '" name="subTitle" class="form-control subAddonTitle">
                                                        </div>	
                                                        </div>
                                                        <div class="col-sm-4  col-12">
                                                            <div class="form-group">';
                                                    }

                                                    if ($c['categoryType'] == 'variation') {
                                                        $html .= ' <label for="price'  . $c['code'] . '">Variation Price</label>';
                                                    } else  if ($c['categoryType'] == 'addon') {
                                                        $html .= '<label for="price' . $c['code'] . '">Addon Price</label>';
                                                    } else {
                                                        $html .= '<label for="price' . $c['code'] . '">Product Price</label>';
                                                    }

                                                    $html .= '<input type="number" id="price' . $c['code'] . '" name="price" class="form-control subAddonPrice">
                                                                </div>                 	
                                                            </div>';

                                                    $html .= '<div class="col-sm-4  col-12"> 
                                                                <div class="form-group">
                                                                <label for="categoryType" class="form-label">Status</label>
                                                                <div class="form-check">
                                                                    <div class="checkbox">
                                                                        <label for="isEnabled' . $c['code'] . '">Enabled</label>
                                                                        <input type="checkbox"  value="1" class="form-check-input subAddonEnabled" id="isEnabled' . $c['code'] . '" name="isEnabled" checked>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4  col-12">';
                                                    if ($c['categoryType'] == 'variation') {
                                                        $html .= ' <button type="button" class="btn btn-info btn-sm addSubCategory mb-3" data-id="' . $c['code'] . '"><I class="fa fa-plus"></i> Variation</button>';
                                                    } else if ($c['categoryType'] == 'addon') {
                                                        $html .= ' <button type="button" class="btn btn-info btn-sm addSubCategory mb-3" data-id="' . $c['code'] . '"><I class="fa fa-plus"></i> Addon </button>';
                                                    } else {
                                                        $html .= ' <button type="button" class="btn btn-info btn-sm addSubCategory mb-3" data-id="' . $c['code'] . '"><I class="fa fa-plus"></i> Product </button>';
                                                    }
                                                    $html .= '</div>';
                                                    $html .= '</div>';
                                                    echo $html;
                                                    echo '<table style="width:100%" class="table table-bordered" id="tbl' . $c['code'] . '"><thead><tr><th>Name</th><th>Price</th><th>Status</th><th>#</th></tr></thead><tbody id="tbd' . $c['code'] . '">';
                                                    if ($categoriesline) {
                                                        foreach ($categoriesline->result_array() as $line) {
                                                            if ($line['customizedCategoryCode'] == $c['code']) {
                                                                $enabled = $line['isEnabled'] == 1 ? 'Yes' : 'No';
                                                                if ($enabled == 'Yes') $enabled = '<span class="badge bg-success">' . $enabled . '</span>';
                                                                else  $enabled = '<span class="badge bg-danger">' . $enabled . '</span>';
                                                                $option = '<a class="btn btn-sm btn-danger text-white lineDelete" data-id="' . $line['code'] . '"><i class="fa fa-trash"></i></a>';
                                                                if ($c['categoryType'] == 'product') {
                                                                    $row = '<tr id="row_' . $line['code'] . '"><td>' . $line['productEngName'] . '</td><td>' . $line['price'] . '</td><td>' . $enabled . '</td><td>' . $option . '</td></tr>';
                                                                } else {
                                                                    $row = '<tr id="row_' . $line['code'] . '"><td>' . $line['subCategoryTitle'] . '</td><td>' . $line['price'] . '</td><td>' . $enabled . '</td><td>' . $option . '</td></tr>';
                                                                }
                                                                echo $row;
                                                            }
                                                        }
                                                    }
                                                    echo '</tbody></table>';
                                                    echo '</div>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
    </section>
</div>

<script>
    $(document).ready(function() {

        function clearCategory() {
            $("#customizedCategoryCode").val("");
            $("#categoryTitle").val("");
            $("#categoryType").val("");
            $("#isCateEnabled").prop('checked', false);
        }

        function clearSubCategory() {
            $(".subAddonTitle").val("");
            $(".subAddonPrice").val("");
            $(".subAddonEnabled").prop('checked', false);
        }
        $("body").on('click', ".lineDelete", function(e) {
            var urldelete = base_path + 'index.php/Product/deleteAddonLine';
            var code = $(this).data('id'); {
                $.ajax({
                    url: urldelete,
                    data: {
                        'code': code
                    },
                    type: 'post',
                    success: function(response) {
                        if (response == true) {
                            toastr.success("Add-on deleted successfully", 'Delete Add-on', {
                                "progressBar": true
                            });
                            $("#row_" + code).remove();
                        } else {
                            toastr.error("Failed to delete Add-on", 'Delete Add-on', {
                                "progressBar": true
                            });
                        }
                    }
                });
            }
        });

        $("body").on('click', ".addSubCategory", function(e) {
            debugger
            var cateCode = $(this).data('id');
            var subTitle = $("#subTitle" + cateCode).val().trim();
            var categoryType = $("#subTitle" + cateCode).data('seq');
            var price = $("#price" + cateCode).val().trim();
            var url = base_path + 'index.php/Product/addAddonLine';
            var isEnabled = 0;
            if ($("#isEnabled" + cateCode).is(':checked')) isEnabled = 1;
            if (subTitle != '') {
                if (price != '') {
                    if (subTitle.length > 2) {
                        $.ajax({
                            url: url,
                            data: {
                                'cateCode': cateCode,
                                'subTitle': subTitle,
                                'price': price,
                                'categoryType': categoryType,
                                'isEnabled': isEnabled
                            },
                            type: 'post',
                            success: function(response) {
                                var res = JSON.parse(response);
                                var subtitle = subTitle;
                                if (res.status == 'true') {
                                    clearSubCategory();
                                    toastr.success(res.message, 'Add On', {
                                        "progressBar": true
                                    });
                                    if (res.title != '') {
                                        subtitle = res.title;
                                    }
                                    var enabled = '<span class="badge bg-danger">No</span>';
                                    if (isEnabled == 1) enabled = '<span class="badge bg-success">Yes</span>';
                                    var option = '<a class="btn btn-sm text-white btn-danger lineDelete" data-id="' + res.code + '"><i class="fa fa-trash"></i></a>';
                                    var row = '<tr id="row_' + res.code + '"><td>' + subtitle + '</td><td>' + price + '</td><td>' + enabled + '</td><td>' + option + '</td></tr>';
                                    $("#tbd" + cateCode).append(row);
                                } else if (res.status == 'false') {
                                    toastr.error(res.message, 'Product', {
                                        "progressBar": true
                                    });
                                } else {
                                    toastr.info(res.message, 'Product', {
                                        "progressBar": true
                                    });
                                }
                            }
                        });
                    } else {
                        toastr.error('Valid Subtitle is required.', 'Product', {
                            "progressBar": true
                        });
                        $("#subTitle" + cateCode).val('');
                        $("#subTitle" + cateCode).focus();
                    }
                } else {
                    toastr.error('Price is required', 'Product', {
                        "progressBar": true
                    });
                    $("#price" + cateCode).focus();
                }
            } else {
                toastr.error('Subtitle is required.', 'Product', {
                    "progressBar": true
                });
                $("#subTitle" + cateCode).focus();
            }
        });
        $("body").on('click', ".deleteCustomizedCategory", function(e) {
            var code = $(this).attr('id');
            code = code.substring(7);

            $.ajax({
                url: base_path + 'index.php/Product/deleteAddonCategory',
                data: {
                    'code': code
                },
                type: 'post',
                success: function(response) {
                    if (response == true) {
                        toastr.success("Category deleted successfully", 'Delete Category', {
                            "progressBar": true
                        });
                        var prevAnchor = $('#' + code + '-tab').prev('a').attr('id');
                        $("#" + prevAnchor).addClass('active');
                        $("#" + prevAnchor).addClass('show');
                        $("#" + code + "-tab").remove();
                        $("#" + code).remove();
                        $("#deltab_" + code).remove();

                    } else {
                        toastr.error("Failed to delete Category", 'Delete Category', {
                            "progressBar": true
                        });
                    }
                }
            });
        });


        $("body").on('click', ".editCustomizedCategory", function(e) {
            var code = $(this).attr('id');
            code = code.substring(7);
            $.ajax({
                url: base_path + 'index.php/Product/getAddonCategoryData',
                data: {
                    'code': code,

                },
                type: 'post',
                success: function(response) {
                    if (response != "") {
                        var res = JSON.parse(response);
                        // console.log(res.id);
                        $("#customizedCategoryCode").val(res.code);
                        $("#categoryTitle").val(res.categoryTitle);
                        $("#categoryType").val(res.categoryType);
                        if (res.isActive == 1) {
                            $("#isCateEnabled").prop('checked', true);
                        } else {
                            $("#isCateEnabled").prop('checked', false);
                        }
                    } else {
                        toastr.error("Something went Wrong.", 'Edit Category', {
                            "progressBar": true
                        });
                    }
                }
            });
        });


        $("body").on('click', "#addCustomizedCategory", function(e) {
            debugger
            var customizedCategoryCode = $("#customizedCategoryCode").val().trim();
            var categoryTitle = $("#categoryTitle").val().trim();
            var categoryType = $("#categoryType").val().trim();
            var productCode = $("#productCode").val().trim();
            var url = base_path + 'index.php/Product/addAddonCategory';
            if (customizedCategoryCode != "" && customizedCategoryCode != undefined) url = base_path + 'index.php/Product/updateAddonCategory';
            var isCateEnabled = 0;
            if ($("#isCateEnabled").is(':checked')) isCateEnabled = 1;
            if (categoryType == 'variation') {
                var categoryTypeTitle = 'Variation';
            } else if (categoryType == 'addon') {
                var categoryTypeTitle = 'Add On';
            } else {
                var categoryTypeTitle = 'Product';
            }
            if (categoryTitle.length > 3) {
                if (categoryType != "" && categoryType != undefined) {
                    $.ajax({
                        url: url,
                        data: {
                            'customizedCategoryCode': customizedCategoryCode,
                            'productCode': productCode,
                            'categoryTitle': categoryTitle,
                            'categoryType': categoryType,
                            'isCateEnabled': isCateEnabled
                        },
                        type: 'post',
                        success: function(response) {
                            var res = JSON.parse(response);
                            if (res.status == 'true') {
                                if (customizedCategoryCode != "" && customizedCategoryCode != undefined) {
                                    clearCategory();
                                    var updatedtitle = res.updatedtitle;
                                    $('#' + customizedCategoryCode + '-tab').html('<div class="row"><div class="col-md-6">' + updatedtitle + ' - ' + categoryTypeTitle + '</div><div class="col-md-6"> <span class="mr-1 btn btn-warning mx-3 deleteCustomizedCategory" id="deltab_' + customizedCategoryCode + '"><i class="fa fa-trash"></i></span><span class="mr-1 btn btn-danger btn-xs editCustomizedCategory" id="edttab_' + customizedCategoryCode + '"><i class="fa fa-pencil-square"></i></span></div></div>');
                                    toastr.success(res.message, 'Category', {
                                        "progressBar": true
                                    });
                                } else {
                                    clearCategory();
                                    $(".nav-link").removeClass('active');
                                    $(".tab-pane").removeClass('active');
                                    debugger;
                                    toastr.success(res.message, 'Addon Category', {
                                        "progressBar": true
                                    });

                                    var isStatus = 'Disabled';
                                    if (isCateEnabled == 1) isStatus = 'Enabled';

                                    var html = '<button class="nav-link active" id="' + res.code + '-tab" data-bs-toggle="tab" data-bs-target="#' + res.code + '" role="tab" aria-selected="true"><div class="row"><div class="col-md-7">' + categoryTitle + ' - ' + categoryTypeTitle + ' - ' + isStatus + '</div><div class="col-md-5"><span class="mr-1 ml-2 btn btn-warning mx-3 deleteCustomizedCategory" id="deltab_' + res.code + '"><i class="fa fa-trash"></i></span><span class="mr-1 btn btn-danger btn-xs editCustomizedCategory" id="edttab_' + res.code + '"><i class="fa fa-pencil-square"></i></span></div></div></button>';
                                    if (categoryType == 'product') {
                                        var inputSubHtml = '<div class="row">';
                                        inputSubHtml += '<div class="col-sm-5 col-12"><div class="form-group"><label for="productcode' + res.code + '" id="titleChange' + res.code + '" >Product Name</label><select id="subTitle' + res.code + '" name="subTitle" class="form-control subAddonTitle" data-id="' + res.code + '" data-seq="' + categoryType + '"></select></div></div>';
                                        inputSubHtml += '<div class="col-sm-5 col-12"> <div class="form-group"><label for="price' + res.code + '" id="priceChange' + res.code + '">Product Price</label><input type="text" id="price' + res.code + '" name="price" class="form-control subAddonPrice" readonly></div></div>';
                                        inputSubHtml += '<div class="col-sm-2 col-12"><div class="form-group"><label for="categoryType" class="form-label">Status</label><div class="form-check"><div class="checkbox"><label for="isEnabled' + res.code + '">Enabled</label><input type="checkbox"  value="1" class="form-check-input subAddonEnabled" id="isEnabled' + res.code + '" name="isEnabled" checked></div></div></div></div>';
                                        inputSubHtml += '<div class="col-sm-4 col-12"><button type="button" class="btn btn-info btn-sm addSubCategory mb-3" data-id="' + res.code + '" id="subCategoryChange' + res.code + '" ><i class="fa fa-plus"></i> Product</button></div>';
                                        inputSubHtml += '</div>';
                                    } else {
                                        var inputSubHtml = '<div class="row">';
                                        inputSubHtml += '<div class="col-sm-5 col-12"><div class="form-group"><label for="subTitle' + res.code + '" id="titleChange' + res.code + '" >Addon Name</label><input id="subTitle' + res.code + '" name="subTitle" class="form-control subAddonTitle"></div></div>';
                                        inputSubHtml += '<div class="col-sm-5 col-12"> <div class="form-group"><label for="price' + res.code + '" id="priceChange' + res.code + '">Addon Price</label><input type="number" id="price' + res.code + '" name="price" class="form-control subAddonPrice"></div></div>';
                                        inputSubHtml += '<div class="col-sm-2 col-12"><div class="form-group"><label for="categoryType" class="form-label">Status</label><div class="form-check"><div class="checkbox"><label for="isEnabled' + res.code + '">Enabled</label><input type="checkbox"  value="1" class="form-check-input subAddonEnabled" id="isEnabled' + res.code + '" name="isEnabled" checked></div></div></div></div>';
                                        inputSubHtml += '<div class="col-sm-4 col-12"><button type="button" class="btn btn-info btn-sm addSubCategory mb-3" data-id="' + res.code + '" id="subCategoryChange' + res.code + '" ><i class="fa fa-plus"></i> Sub Category</button></div>';
                                        inputSubHtml += '</div>';
                                    }

                                    var table = '<table style="width:100%" class="table table-bordered" id="tbl' + res.code + '"><thead><tr><th>Name</th><th>Price</th><th>Enabled</th><th>#</th></tr></thead><tbody id="tbd' + res.code + '">';
                                    var html2 = '<div class="tab-pane fade show active" id="' + res.code + '" role="tabpanel" aria-labelledby="' + res.code + '-tab">' + inputSubHtml + table + '</div>';
                                    $(".nav-pills").append(html);
                                    $("#v-pills-tabContent").append(html2);
                                    if (categoryType == 'variation') {
                                        $("label#titleChange" + res.code).html("Variation Name");
                                        $("label#priceChange" + res.code).html("Variation Price");
                                        $("button#subCategoryChange" + res.code).html('<i class="fa fa-plus"></i>Variation');
                                    } else if (categoryType == 'addon') {
                                        $("label#titleChange" + res.code).html("Addon Name");
                                        $("label#priceChange" + res.code).html("Addon Price");
                                        $("button#subCategoryChange" + res.code).html('<i class="fa fa-plus"></i>Addon');
                                    } else {
                                        $('select#subTitle' + res.code).append(res.products);
                                    }
                                }
                            } else if (res.status == 'false') {
                                clearCategory();
                                toastr.error(res.message, 'Product', {
                                    "progressBar": true
                                });
                            } else {
                                clearCategory();
                                toastr.info(res.message, 'Product', {
                                    "progressBar": true
                                });
                            }
                        }
                    });
                } else {
                    toastr.error('Type is required.', 'Product', {
                        "progressBar": true
                    });
                }
            } else {
                toastr.error('Valid Category is required.', 'Product', {
                    "progressBar": true
                });
                $("#categoryTitle").val('');
                $("#categoryTitle").focus();
            }
        });

        $("body").on('change', "select.subAddonTitle", function(e) {
            debugger
            var id = $(this).data('id');
            var code = $(this).val();
            var url = base_path + 'index.php/Product/getPrice';
            $.ajax({
                url: url,
                method: "POST",
                data: {
                    'code': code
                },
                success: function(response) {
                    debugger
                    var res = JSON.parse(response);
                    if (res.status == 'true') {
                        $("#price" + id).val(res.price);
                    }
                }
            });

        });
    });
</script>