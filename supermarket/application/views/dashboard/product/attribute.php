<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Product Attribute</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Product Attribute</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

<div class="container">
    <!-- // Basic multiple Column Form section start -->
    <section class="section">
        <div class="row match-height">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Product Attribute</h3>
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
                            <h6>Product Attribute</h6>
                            <hr />
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label  class="form-label">Attribute</label>
                                        <select id="attribute" name="attribute" class="form-select">
                                            <option value="">Select</option>
                                             <?php if ($attributes) {
												foreach ($attributes->result() as $opt) { ?>
													<option value="<?= $opt->code ?>" id="<?= $opt->attributeName ?>"><?= $opt->attributeName ?></option>
											<?php }
											} ?>
                                        </select>
										<input readonly type="hidden" id="attCode" name="attCode">
                                    </div>
                                </div>
							</div>
							<div class="row">
                                <div class="col-sm-4  mb-3">
                                    <button type="button" class="btn btn-info btn-sm" id="addAttribute"><I class="fa fa-plus"></i> Add Product Attribute</button>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <!-- Nav tabs -->
                                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                            <?php
                                            if ($productattribute) {
                                                foreach ($productattribute->result_array() as $c) {
                                                    echo '<button class="nav-link" id="' . $c['code'] . '-tab" data-bs-toggle="tab" data-bs-target="#' . $c['code'] . '" role="tab"><div class="row"><div class="col-md-6">' . $c['attributeName']. '</div><div class="col-md-6"> <span class="mr-1 ml-2 btn btn-warning mx-3 deleteProductAttribute" id="deltab_' . $c['code'] . '"><i class="fa fa-trash"></i></span><span class="mr-1 btn btn-danger btn-xs editProductAttribute" id="edttab_' . $c['code'] . '"><i class="fa fa-pencil-square"></i></span></div></div></button>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="tab-content" id="v-pills-tabContent">
                                            <?php
                                            if ($productattribute) {
                                                foreach ($productattribute->result_array() as $c) { 
                                                   echo '<div class="tab-pane fade" id="' . $c['code'] . '" role="tabpanel" aria-labelledby="' . $c['code'] . '-tab">';
                                                  $html='<div class="row">
                                                    <div class="col-sm-5 mb-3">
                            							<label for="subTitle'.$c['code'].'">Product Option Title</label> 
                            							<input id="subTitle'.$c['code'].'" name="subTitle" class="form-control subAddonTitle">  	
                            						</div>                            
                            						<div class="col-sm-4 mt-4">  
                            							<button type="button" class="btn btn-info btn-sm addAttributeLine" data-id="'.$c['code'].'"><i class="fa fa-plus"></i> Add Option</button>
                            						</div>
                                                </div>';
                                                echo $html;
                                                    echo '<table style="width:100%" class="table table-bordered" id="tbl' . $c['code'] . '"><thead><tr><th>Name</th><th>#</th></tr></thead><tbody id="tbd' . $c['code'] . '">';
                                                    if ($productattributeline) {
                                                        foreach ($productattributeline->result_array() as $line) {
                                                            if ($line['productAttCode'] == $c['code']) {                                                                
                                                                $option = '<div class="d-flex"><a class="btn btn-sm btn-danger text-white lineDelete m-1" data-id="' . $line['code'] . '"><i class="fa fa-trash"></i></a></div>';                                                               
                                                                $row = '<tr id="row_' . $line['code'] . '"><td>' . $line['subTitle'] . '</td><td>' . $option . '</td></tr>';
                                                               
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
<div>
</div>

<script>
    $(document).ready(function() {

        function clearAttribute() {
            $("#attCode").val("");
            $("#attribute").val("");
        }

        function clearSubAttribute() {
            $(".subAddonTitle").val("");
        }
        $("body").on('click', ".lineDelete", function(e) {
            var urldelete = base_path + 'index.php/Product/deleteAttributeLine';
            var code = $(this).data('id'); {
                $.ajax({
                    url: urldelete,
                    data: {
                        'code': code
                    },
                    type: 'post',
                    success: function(response) {
                        if (response == true) {
                            toastr.success("Product Option deleted successfully", 'Product Attribute', {
                                "progressBar": true
                            });
                            $("#row_" + code).remove();
                        } else {
                            toastr.error("Failed to delete Product Option", 'Product Attribute', {
                                "progressBar": true
                            });
                        }
                    }
                });
            }
        });


        $("body").on('click', ".addAttributeLine", function(e) {
            debugger
            var attributeCode = $(this).data('id');
            var subTitle = $("#subTitle" + attributeCode).val().trim();
            var url = base_path + 'index.php/Product/addAttributeLine';
            if (subTitle != '') {
                        $.ajax({
                            url: url,
                            data: {
                                'attributeCode': attributeCode,
                                'subTitle': subTitle
                            },
                            type: 'post',
                            success: function(response) {
                                var res = JSON.parse(response);
                                var subtitle = subTitle;
                                if (res.status == 'true') {
                                    clearSubAttribute();
                                    toastr.success(res.message, 'Product Attribute', {
                                        "progressBar": true
                                    });   
                                    var option = '<a class="btn btn-sm text-white btn-danger lineDelete" data-id="' + res.code + '"><i class="fa fa-trash"></i></a>';
                                    var row = '<tr id="row_' + res.code + '"><td>' + subTitle + '</td><td>' + option + '</td></tr>';
                                    $("#tbd" + attributeCode).append(row);
                                } else if (res.status == 'false') {
                                    toastr.error(res.message, 'Product Attribute', {
                                        "progressBar": true
                                    });
                                } else {
                                    toastr.info(res.message, 'Product Attribute', {
                                        "progressBar": true
                                    });
                                }
                            }
                        });
            } else {
                toastr.error('Subtitle is required.', 'Product Attribute', {
                    "progressBar": true
                });
                $("#subTitle" + attributeCode).focus();
            }
        });
        $("body").on('click', ".deleteProductAttribute", function(e) {
            var code = $(this).attr('id');
            code = code.substring(7);

            $.ajax({
                url: base_path + 'index.php/Product/deleteAttribute',
                data: {
                    'code': code
                },
                type: 'post',
                success: function(response) {
                    if (response == true) {
                        toastr.success("Product Attributes deleted successfully", 'Product Attribute', {
                            "progressBar": true
                        });
                        var prevAnchor = $('#' + code + '-tab').prev('a').attr('id');
                        $("#" + prevAnchor).addClass('active');
                        $("#" + prevAnchor).addClass('show');
                        $("#" + code + "-tab").remove();
                        $("#" + code).remove();
                        $("#deltab_" + code).remove();

                    } else {
                        toastr.error("Failed to delete Product Attributes", 'Product Attributes', {
                            "progressBar": true
                        });
                    }
                }
            });
        });


        $("body").on('click', ".editProductAttribute", function(e) {
			debugger
            var code = $(this).attr('id');
            code = code.substring(7);
            $.ajax({
                url: base_path + 'index.php/Product/getAttribute',
                data: {
                    'code': code,

                },
                type: 'post',
                success: function(response) {
                    if (response != "") {
                        var res = JSON.parse(response);
                        $("#attCode").val(res.code);
						$('select#attribute').empty();
						$('select#attribute').append(res.attribute);
                        
                    } else {
                        toastr.error("Something went Wrong.", 'Attribute', {
                            "progressBar": true
                        });
                    }
                } 
            });
        });


        $("body").on('click', "#addAttribute", function(e) {
            debugger
            var attCode = $("#attCode").val().trim();
			var attributeName=$('#attribute').find('option:selected').attr('id');
			var productCode=$("#productCode").val().trim();
            var attributecode = $("#attribute").val().trim();
            var url = base_path + 'index.php/Product/addAttribute';
            if (attCode != "" && attCode != undefined) url = base_path + 'index.php/Product/updateAttribute';
           
            if (attributecode!="") {
                if (attributecode != "" && attributecode != undefined) {
                    $.ajax({
                        url: url,
                        data: {
                            'attributecode': attributecode,
                            'productAttributeCode':attCode,
                            'productCode':	productCode						
                        },
                        type: 'post',
                        success: function(response) {
                            var res = JSON.parse(response);
                            if (res.status == 'true') {
                                if (attCode != "" && attCode != undefined) {
                                    clearAttribute();
                                    $('#' + attCode + '-tab').html('<div class="row"><div class="col-md-6">' + attributeName + '</div><div class="col-md-6"> <span class="mr-1 btn btn-warning mx-3 deleteCustomizedCategory" id="deltab_' + attCode + '"><i class="fa fa-trash"></i></span><span class="mr-1 btn btn-danger btn-xs edit" id="edttab_' + attCode + '"><i class="fa fa-pencil-square"></i></span></div></div>');
                                    toastr.success(res.message, 'Product Attribute', {
                                        "progressBar": true
                                    });
                                } else {
                                    clearAttribute();
                                    $(".nav-link").removeClass('active');
                                    $(".tab-pane").removeClass('active');
                                    debugger;
                                    toastr.success(res.message, 'Product Attribute', {
                                        "progressBar": true
                                    });

                                    var html = '<button class="nav-link active" id="' + res.code + '-tab" data-bs-toggle="tab" data-bs-target="#' + res.code + '" role="tab" aria-selected="true"><div class="row"><div class="col-md-6">' + attributeName + '</div><div class="col-md-6"><span class="mr-1 ml-2 btn btn-warning mx-3 deleteProductAttribute" id="deltab_' + res.code + '"><i class="fa fa-trash"></i></span><span class="mr-1 btn btn-danger btn-xs editProductAttribute" id="edttab_' + res.code + '"><i class="fa fa-pencil-square"></i></span></div></div></button>';
                                   
									var inputSubHtml = '<div class="row">';
									inputSubHtml += '<div class="col-sm-5 col-12"><div class="form-group"><label for="subTitle' + res.code + '" id="titleChange' + res.code + '" >Product Option Title</label><input id="subTitle' + res.code + '" name="subTitle" class="form-control subAddonTitle"></div></div>';
									inputSubHtml += '<div class="col-sm-4 col-12 mt-4"><button type="button" class="btn btn-info btn-sm addAttributeLine" data-id="' + res.code + '" id="subCategoryChange' + res.code + '" ><i class="fa fa-plus"></i>Add Option</button></div>'; 
									inputSubHtml += '</div>';
							  
                                    var table = '<table style="width:100%" class="table table-bordered" id="tbl' + res.code + '"><thead><tr><th>Name</th><th>#</th></tr></thead><tbody id="tbd' + res.code + '">';
                                    var html2 = '<div class="tab-pane fade show active" id="' + res.code + '" role="tabpanel" aria-labelledby="' + res.code + '-tab">' + inputSubHtml + table + '</div>';
                                    $(".nav-pills").append(html);
                                    $("#v-pills-tabContent").append(html2); 
                            
                                }
                            } else if (res.status == 'false') {
                                clearAttribute();
                                toastr.error(res.message, 'Product Attribute', {
                                    "progressBar": true
                                });
                            } else {
                                clearAttribute();
                                toastr.info(res.message, 'Product Attribute', {
                                    "progressBar": true
                                });
                            }
                        }
                    });
                } else {
                    toastr.error('Attribute is required.', 'Product Attribute', {
                        "progressBar": true
                    });
                }
            } else {
                toastr.error('Valid Attribute is required.', 'Product Attribute', {
                    "progressBar": true
                });
                $("#attribute").val('');
                $("#attribute").focus();
            }
        });
    });
</script>