<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Product</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Product</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="row match-height">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3>Add Product<span style="float:right"><a id="cancelDefaultButton" href="<?= base_url() ?>product/listRecords" class="btn btn-sm btn-primary">Back</a></span></h3>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <form class="form" action="<?= base_url('product/save') ?>" method="post" enctype="multipart/form-data" data-parsley-validate>
                                    <?php
                                    echo "<div class='text-danger text-center' id='error_message'>";
                                    if (isset($error_message)) {
                                        echo $error_message;
                                    }
                                    echo "</div>";
                                    ?>
                                    <div class="row">
                                        <div class="col-md-7 col-12">


                                            <div class="row">


                                                <div class="col-md-12 row col-12">

                                                    <div class="form-group col-md-6 col-12 mandatory">
                                                        <label for="product-arabic-name" class="form-label">Arabic Name</label>
                                                        <input type="text" id="product-arabic-name" value="<?= set_value('product-arabic-name') ?>" class="form-control" placeholder="Arabic Name" name="product-arabic-name" data-parsley-required="true">
                                                    </div>
                                                    <?php echo form_error('product-arabic-name', '<span class="error text-danger text-right">', '</span>'); ?>


                                                    <div class="form-group col-md-6 col-12 mandatory">
                                                        <label for="product-english-name" class="form-label">English Name</label>
                                                        <input type="text" value="<?= set_value('product-english-name') ?>" id="product-english-name" class="form-control" placeholder="Product Name" name="product-english-name" data-parsley-required="true">
                                                    </div>
                                                    <?php echo form_error('product-english-name', '<span class="error text-danger text-right">', '</span>'); ?>

                                                </div>


                                                <div class="row col-md-12 col-12">

                                                    <div class="form-group col-md-6 col-12 mandatory">
                                                        <label for="product-hindi-name">Hindi</label>
                                                        <input type="text" id="product-hindi-name" class="form-control" value="<?= set_value('product-hindi-name') ?>" placeholder="Hindi Name" name="product-hindi-name">
                                                    </div>
                                                    <?php echo form_error('product-hindi-name', '<span class="error text-danger text-right">', '</span>'); ?>


                                                    <div class="form-group col-md-6 col-12 mandatory">
                                                        <label for="product-urdu-name">Urdu</label>
                                                        <input type="text" id="product-urdu-name" class="form-control" placeholder="Urdu Name" name="product-urdu-name" value="<?= set_value('product-urdu-name') ?>">
                                                    </div>
                                                    <?php echo form_error('product-urdu-name', '<span class="error text-danger text-right">', '</span>'); ?>


                                                </div>


                                                <div class="row col-md-12 col-12">

                                                    <div class="form-group col-md-6 col-12">
                                                        <label for="desc-column" class="form-label">Arabic Description</label>
                                                        <textarea class="form-control summernote" placeholder="Product Arabic Description" id="product-arabic-description" name="product-arabic-description" maxlength='2000' data-parsley-minlength="20" data-parsley-minlength-message="You need to enter at least 20 characters" data-parsley-trigger="change"><?= set_value('product-arabic-description') ?></textarea>
                                                    </div>
                                                    <?php echo form_error('product-arabic-description', '<span class="error text-danger text-right">', '</span>'); ?>

                                                    <div class="form-group col-md-6 col-12">
                                                        <label for="desc-column" class="form-label">English Description</label>

                                                        <textarea class="form-control summernote" placeholder="Product English Description" id="product-english-description" name="product-english-description" maxlength='2000' data-parsley-minlength="20" data-parsley-minlength-message="You need to enter at least 20 characters" data-parsley-trigger="change"><?= set_value('product-english-description') ?></textarea>
                                                    </div>
                                                    <?php echo form_error('product-english-description', '<span class="error text-danger text-right">', '</span>'); ?>

                                                </div>


                                                <div class="row col-md-12 col-12">

                                                    <div class="form-group col-md-6 col-12">
                                                        <label for="desc-column" class="form-label">Hindi Description</label>
                                                        <textarea class="form-control summernote" placeholder="Product Hindi Description" id="product-hindi-description" name="product-hindi-description" maxlength='2000' data-parsley-minlength="20" data-parsley-minlength-message="You need to enter at least 20 characters" data-parsley-trigger="change"><?= set_value('product-hindi-description') ?></textarea>
                                                    </div>
                                                    <?php echo form_error('product-hindi-description', '<span class="error text-danger text-right">', '</span>'); ?>


                                                    <div class="form-group col-md-6 col-12">
                                                        <label for="desc-column" class="form-label">Urdu Description</label>
                                                        <textarea class="form-control summernote" placeholder="Product Urdu Description" id="product-urdu-description" name="product-urdu-description" maxlength='2000' data-parsley-minlength="20" data-parsley-minlength-message="You need to enter at least 20 characters" data-parsley-trigger="change"><?= set_value('product-urdu-description') ?></textarea>
                                                    </div>
                                                    <?php echo form_error('product-urdu-description', '<span class="error text-danger text-right">', '</span>'); ?>


                                                </div>


                                            </div>



                                            <div class="row">


                                                <div class="row col-md-12 col-12">

                                                    <div class="form-group col-md-6 col-12">
                                                        <label for="productprice" class="form-label">SKU</label>
                                                        <input type="text" class="form-control" name="productsku" id="productsku" value="<?= set_value('productsku') ?>">
                                                    </div>

                                                    <div class="form-group col-md-6 col-12">
                                                        <label for="productprice" class="form-label">Alert Quantity</label>
                                                        <input type="text" min="1" class="form-control" name="alertQty" id="alertQty" onkeypress="return isDecimal(event)" value="0">
                                                    </div>

                                                </div>

                                                <!--
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group mandatory">
                                                        <label for="productprice" class="form-label">Price</label>
                                                        <input type="text" min="1" class="form-control" name="productprice" id="productprice" data-parsley-required="true" data-parsley-required-message="Product Price is required." value="< set_value('productprice') ?>" onkeypress="return isDecimal(event)">
                                                    </div>
                                                </div>
                                                -->


                                                <div class="col-md-12 row mb-2 col-12">
                                                    <div class="form-group mandatory">
                                                        <label for="productprice" class="form-label">Tax Group</label>
                                                        <select class="form-select select2" name="producttaxgroup" style="width:100%" id="producttaxgroup" data-parsley-required="true" data-parsley-required-message="Product Tax Group is required.">
                                                        </select>
                                                    </div>
                                                </div>


                                            </div>


                                            <div class="row col-md-12 mb-3 col-12">

                                                <div class="form-check col-md-6 col-12 mandatory">
                                                    <div class="checkbox form-check col-md-6 col-12 mandatory">
                                                        <label for="checkbox1">Status</label>
                                                        <input type="checkbox" id="isActive" class="form-check-input" name="isActive">
                                                    </div>
                                                </div>

                                                <div class="form-check col-md-6 col-12 mandatory">
                                                    <div class="checkbox form-check col-md-6 col-12 mandatory">
                                                        <label for="checkbox1">has Variants?</label>
                                                        <input type="checkbox" id="isVariants" class="form-check-input" name="isVariants">
                                                    </div>
                                                </div>

                                            </div>




                                        </div>




                                        <div class="col-md-5 col-12">
                                            <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                                <div class="card card-custom gutter-b bg-white border-0">
                                                    <div class="card-body">
                                                        <div class="col-md-12 col-sm-6 col-xs-6">


                                                            <div class="form-group mandatory">
                                                                <label for="productbrand" class="form-label">Product Brand </label>
                                                                <!--<a id="smallButton" class="add_brand m-1"><i class="fa fa-plus-circle cursor_pointer" style="font-size:25px;"></i></a>-->
                                                                <select class="form-select select2" style="width:100%" name="productbrand" id="productbrand" data-parsley-required="true" data-parsley-required-message="Product Brand is required.">

                                                                </select>
                                                            </div>


                                                            <div class="form-group mandatory">
                                                                <label for="productcategory" class="form-label">Product Category</label>
                                                                <!--<a id="smallButton" class="add_category m-1"><i class="fa fa-plus-circle cursor_pointer" style="font-size:25px;"></i></a>-->
                                                                <select class="form-select select2" style="width:100%" name="productcategory" id="productcategory" data-parsley-required="true" data-parsley-required-message="Product Category is required.">

                                                                </select>
                                                            </div>


                                                            <div class="form-group">
                                                                <label for="productsubcategory" class="form-label">Product Subcategory</label>
                                                                <!--<a id="smallButton" class="add_subcategory m-1"><i class="fa fa-plus-circle cursor_pointer" style="font-size:25px;"></i></a>-->
                                                                <!--<input type="hidden" class="form-control" id="dbproductsubcategory" name="dbproductsubcategory" value="">-->
                                                                <select class="form-select select2" style="width:100%" name="productsubcategory" id="productsubcategory">

                                                                </select>
                                                            </div>


                                                            <div class="form-group mandatory">
                                                                <label for="productunit" class="form-label">Product Unit</label>
                                                                <select class="form-select select2" style="width:100%" name="productUnit" id="productUnit" data-parsley-required="true" data-parsley-required-message="Product Unit is required.">

                                                                </select>
                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                                <div class="card card-custom gutter-b bg-white border-0">
                                                    <div class="card-body">
                                                        <h3 class="mt-0 header-title lng">Product Image</h3>

                                                        <div class="col-md-12 col-sm-12 col-xs-12 mb-2 p-0 text-center">
                                                            <img class="img-thumbnail mb-2" width="120px" id="logo_icon" data-src="<?= base_url("assets/images/bgfood.png") ?>" src="<?= base_url("assets/images/bgfood.png") ?>" data-src="">
                                                            <input class="form-control" type="file" id="formFile" name="productImage" style="padding: 5px;">
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-end">
                                                <button id="saveDefaultButton" type="submit" class="btn btn-success white">Save</button>
                                                <button id="cancelDefaultButton" type="reset" class="btn btn-light-secondary">Reset</button>
                                            </div>
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

<div class="modal fade text-left" id="generl_modal" tabindex="-1" Brand="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" Brand="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id='modal_label'>Add Brand</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body1">
                                <form id="BrandForm" class="form" data-parsley-validate>
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Brand</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="brand" class="form-control" placeholder="Enter brand name" name="brand" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary" id="saveBrandBtn" onclick="save_brand()">Save</button>
                                            <button type="button" class="btn btn-light-secondary" id="closeBrandBtn" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade text-left" id="category_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id='modal_label'>Add Category</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body1">
                                <form id="categoryForm" class="form" data-parsley-validate>
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Name</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="categoryName" class="form-control" placeholder="Enter Name" name="categoryName" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary" id="saveCategoryBtn">Save</button>
                                            <button type="button" class="btn btn-light-secondary" id="closeCategoryBtn" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade text-left" id="subcategory_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id='modal_label'>Add Subcategory</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body1">
                                <form id="subcategoryForm" class="form" data-parsley-validate>
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Category</label>
                                                <div class="col-md-8">
                                                    <select class="form-select select2" style="width:100%" name="scategory" id="scategory" data-parsley-required="true" data-parsley-required-message="Category is required.">

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Name</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="subcategoryName" class="form-control" placeholder="Enter Name" name="subcategoryName" required>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary" id="saveSubCategoryBtn">Save</button>
                                            <button type="button" class="btn btn-light-secondary" id="closeSubCategoryBtn" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src=<?php echo base_url() . 'assets/js/google_jsapi.js'; ?>></script>
<script>
    $(document).ready(function() {
        /*$(".summernote").summernote({
        		placeholder: 'Write here...',
        		height: 50
        	});*/
    });

    function isNumberKey(evt) {
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

    function ValidateAlpha(evt) {
        var keyCode = evt.which ? evt.which : evt.keyCode;
        if (keyCode > 47 && keyCode < 58) return false;
        return true;
    }

    function getSubCategory(categoryCode) {
        $("#productsubcategory").select2({
            placeholder: "Select Subcategory",
            allowClear: true,
            ajax: {
                url: base_path + 'Common/getProductSubcategory',
                type: "get",
                delay: 250,
                dataType: 'json',
                data: function(params) {
                    var query = {
                        search: params.term,
                        categoryCode: categoryCode,
                    }

                    return query;
                },
                processResults: function(response) {

                    return {
                        results: response
                    };

                },
                cache: true
            }
        });
    }

    google.load("elements", "1", {
        packages: "transliteration"
    });

    function onLoad() {
        console.log(google.elements.transliteration.LanguageCode);
        var options = {
            sourceLanguage: google.elements.transliteration.LanguageCode.ENGLISH,
            destinationLanguage: [google.elements.transliteration.LanguageCode.HINDI],
            shortcutKey: 'ctrl+g',
            transliterationEnabled: true
        };

        var optionsUrdu = {
            sourceLanguage: google.elements.transliteration.LanguageCode.ENGLISH,
            destinationLanguage: [google.elements.transliteration.LanguageCode.URDU],
            shortcutKey: 'ctrl+g',
            transliterationEnabled: true
        };

        var optionsArabic = {
            sourceLanguage: google.elements.transliteration.LanguageCode.ENGLISH,
            destinationLanguage: [google.elements.transliteration.LanguageCode.ARABIC],
            shortcutKey: 'ctrl+g',
            transliterationEnabled: true
        };

        var control = new google.elements.transliteration.TransliterationControl(options);
        control.makeTransliteratable(['product-hindi-name']);
        control.makeTransliteratable(['product-hindi-description']);
        var controlUrdu = new google.elements.transliteration.TransliterationControl(optionsUrdu);
        controlUrdu.makeTransliteratable(['product-urdu-name']);
        controlUrdu.makeTransliteratable(['product-urdu-description']);
        var controlArabic = new google.elements.transliteration.TransliterationControl(optionsArabic);
        controlArabic.makeTransliteratable(['product-arabic-name']);
        controlArabic.makeTransliteratable(['product-arabic-description']);

    }
    google.setOnLoadCallback(onLoad);
    $(document).ready(function() {
        $('.add_brand').click(function() {
            $('#generl_modal').modal('show');
            $('#brand').val('');
        });

        $('.add_category').click(function() {
            $('#category_modal').modal('show');
            $('#categoryName').val('');
        });

        $('.add_subcategory').click(function() {
            $('#subcategory_modal').modal('show');
        });

        $("#scategory").select2({
            placeholder: "Select Category",
            allowClear: true,
            dropdownParent: $('#subcategory_modal .modal-content'),
            ajax: {
                url: base_path + 'Common/getProductCategory',
                type: "get",
                delay: 250,
                dataType: 'json',
                data: function(params) {
                    var query = {
                        search: params.term
                    }
                    return query;
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $("#formFile").change(function() {
            const file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    $("#logo_icon")
                        .attr("src", event.target.result);
                };
                reader.readAsDataURL(file);
            }
        });

        $("#productsubcategory").select2({
            placeholder: "Select Subcategory",
            allow: true
        });
        $("#productcategory").select2({
            placeholder: "Select Category",
            allowClear: true,
            ajax: {
                url: base_path + 'Common/getProductCategory',
                type: "get",
                delay: 250,
                dataType: 'json',
                data: function(params) {
                    var query = {
                        search: params.term
                    }
                    return query;
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        }).on("select2:select", function(e) {
            var categoryCode = $(e.currentTarget).val();
            getSubCategory(categoryCode);
        });

        $("#productbrand").select2({
            placeholder: "Select Brand",

            allowClear: true,
            ajax: {
                url: base_path + 'Common/getBrand',
                type: "get",
                delay: 250,
                dataType: 'json',
                data: function(params) {
                    var query = {
                        search: params.term
                    }

                    return query;
                },
                processResults: function(response) {

                    return {
                        results: response
                    };

                },
                cache: true
            }
        });

        $("#productUnit").select2({
            placeholder: "Select Unit",
            allowClear: true,
            ajax: {
                url: base_path + 'Common/getUnit',
                type: "get",
                delay: 250,
                dataType: 'json',
                data: function(params) {
                    var query = {
                        search: params.term
                    }

                    return query;
                },
                processResults: function(response) {

                    return {
                        results: response
                    };

                },
                cache: true
            }
        });

        $("#producttaxgroup").select2({
            placeholder: "Select Tax Group",
            allowClear: true,
            ajax: {
                url: base_path + 'Common/getTaxGroup',
                type: "get",
                delay: 250,
                dataType: 'json',
                data: function(params) {
                    var query = {
                        search: params.term
                    }
                    return query;
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $("#BrandForm").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var form = $(this);
            form.parsley().validate();
            if (form.parsley().isValid()) {
                $.ajax({
                    url: base_path + "Product/saveBrand",
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#saveBrandBtn').prop('disabled', true);
                        $('#saveBrandBtn').text('Please wait..');
                        $('#closeBrandBtn').prop('disabled', true);
                    },
                    success: function(response) {
                        $('#saveBrandBtn').prop('disabled', false);
                        $('#saveBrandBtn').text('Save');
                        $('#closeBrandBtn').prop('disabled', false);
                        var obj = JSON.parse(response);
                        if (obj.status) {
                            toastr.success(obj.message, 'Brand', {
                                "progressBar": true
                            });
                            $('#generl_modal').modal('hide');
                        } else {
                            toastr.error(obj.message, 'Brand', {
                                "progressBar": true
                            });
                        }
                    }
                });
            }
        });

        $("#categoryForm").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var form = $(this);
            form.parsley().validate();
            if (form.parsley().isValid()) {
                var isActive = 0;
                if ($("#isActive").is(':checked')) {
                    isActive = 1;
                }
                formData.append('isActive', isActive);
                $.ajax({
                    url: base_path + "Product/saveCategory",
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#saveCategoryBtn').prop('disabled', true);
                        $('#saveCategoryBtn').text('Please wait..');
                        $('#closeCategoryBtn').prop('disabled', true);
                    },
                    success: function(response) {
                        $('#saveCategoryBtn').prop('disabled', false);
                        $('#saveCategoryBtn').text('Save');
                        var obj = JSON.parse(response);
                        if (obj.status) {
                            toastr.success(obj.message, 'Category', {
                                "progressBar": true
                            });
                            $('#category_modal').modal('hide');

                        } else {
                            toastr.error(obj.message, 'Category', {
                                "progressBar": true
                            });
                        }
                    }
                });
            }
        });

        $("#subcategoryForm").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var form = $(this);
            form.parsley().validate();
            if (form.parsley().isValid()) {
                $.ajax({
                    url: base_path + "Product/savesubCategory",
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#saveSubCategoryBtn').prop('disabled', true);
                        $('#saveSubCategoryBtn').text('Please wait..');
                        $('#closeSubCategoryBtn').prop('disabled', true);
                    },
                    success: function(response) {
                        $('#saveSubCategoryBtn').prop('disabled', false);
                        $('#saveSubCategoryBtn').text('Save');
                        $('#closeSubCategoryBtn').prop('disabled', false);
                        var obj = JSON.parse(response);
                        if (obj.status) {
                            toastr.success(obj.message, 'Subcategory', {
                                "progressBar": true
                            });

                            $('#subcategory_modal').modal('hide');
                            $("#scategory").val(null).trigger('change');
                            $('#subcategoryName').val('');
                        } else {
                            toastr.error(obj.message, 'Subcategory', {
                                "progressBar": true
                            });
                        }
                    }
                });
            }
        });

    });

    function getSubcategoryList() {
        var categoryCode = $('#productcategory').val();
        var subcategoryCode = $('#dbproductsubcategory').val();
        if (categoryCode != '') {
            $.ajax({
                url: base_path + "product/getSubCategoryList",
                type: 'POST',
                data: {
                    'categoryCode': categoryCode,
                    'subcategoryCode': subcategoryCode
                },
                success: function(response) {

                    var obj = JSON.parse(response);
                    if (obj.status) {
                        $('#productsubcategory').html(obj.subHtml);
                    } else {
                        $("#productsubcategory").val(null).trigger('change.select2');
                        $("#productsubcategory").html('');
                        $("#productsubcategory").html('<option value="">Select</option>');
                    }
                }
            })
        } else {
            $("#productsubcategory").val(null).trigger('change.select2');
            $("#productsubcategory").html('');
            $("#productsubcategory").html('<option value="">Select</option>');
        }
    }
</script>