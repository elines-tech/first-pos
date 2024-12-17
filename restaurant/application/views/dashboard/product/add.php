<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #c4c8d3 !important;
    }
</style>
<nav class="navbar navbar-light">
    <div class="container d-block">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <a href="<?php echo base_url(); ?>Product/listrecords"><i id='exitButton' class="fa fa-times fa-2x"></i></a>
            </div>
        </div>
    </div>
</nav>
<div class="container">
    <section id="multiple-column-form" class="mt-5 mb-5">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Add Product</h3>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form" action="<?= base_url('Product/save') ?>" method="post" enctype="multipart/form-data" data-parsley-validate>
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

                                            <div class="col-md-6 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="product-arabic-name" class="form-label">Arabic Name</label>
                                                    <input type="text" id="product-arabic-name" value="<?= set_value('product-arabic-name') ?>" class="form-control" placeholder="Arabic Name" name="product-arabic-name" data-parsley-required="true">
                                                </div>
                                                <?php echo form_error('product-arabic-name', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="product-english-name" class="form-label">English Name</label>
                                                    <input type="text" value="<?= set_value('product-english-name') ?>" id="product-english-name" class="form-control" placeholder="English Name" name="product-english-name" data-parsley-required="true">
                                                </div>
                                                <?php echo form_error('product-english-name', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="product-hindi-name" class="form-label">Hindi</label>
                                                    <input type="text" id="product-hindi-name" class="form-control" value="<?= set_value('product-hindi-name') ?>" placeholder="Hindi Name" name="product-hindi-name">
                                                </div>
                                                <?php echo form_error('product-hindi-name', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="product-urdu-name" class="form-label">Urdu</label>
                                                    <input type="text" id="product-urdu-name" class="form-control" value="<?= set_value('product-urdu-name') ?>" placeholder="Urdu Name" name="product-urdu-name">
                                                </div>
                                                <?php echo form_error('product-urdu-name', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>


                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="desc-column" class="form-label">Arabic Description</label>
                                                    <textarea class="form-control" placeholder="Product Arabic Description" id="product-arabic-description" name="product-arabic-description" maxlength='1000' data-parsley-minlength="10" data-parsley-minlength-message="You need to enter at least 10 characters" data-parsley-trigger="change"><?= set_value('product-arabic-description') ?></textarea>
                                                </div>
                                                <?php echo form_error('product-arabic-description', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>


                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="desc-column" class="form-label">English Description</label>
                                                    <textarea class="form-control" placeholder="Product English Description" id="product-english-description" name="product-english-description" maxlength='1000' data-parsley-minlength="10" data-parsley-minlength-message="You need to enter at least 10 characters" data-parsley-trigger="change"><?= set_value('product-english-description') ?></textarea>
                                                </div>
                                                <?php echo form_error('product-english-description', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>


                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="desc-column" class="form-label">Hindi Description</label>
                                                    <textarea class="form-control" placeholder="Product Hindi Description" id="product-hindi-description" name="product-hindi-description" maxlength='1000' data-parsley-minlength="10" data-parsley-minlength-message="You need to enter at least 10 characters" data-parsley-trigger="change"><?= set_value('product-hindi-description') ?></textarea>
                                                </div>
                                                <?php echo form_error('product-hindi-description', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>



                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="desc-column" class="form-label">Urdu Description</label>
                                                    <textarea class="form-control" placeholder="Product Urdu Description" id="product-urdu-description" name="product-urdu-description" maxlength='1000' data-parsley-minlength="10" data-parsley-minlength-message="You need to enter at least 10 characters" data-parsley-trigger="change"><?= set_value('product-urdu-description') ?></textarea>
                                                </div>
                                                <?php echo form_error('product-urdu-description', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>

                                        </div>


                                        <div class="row">
                                            <div class="col-md-6 col-6 mb-3">
                                                <div class="form-group mandatory">
                                                    <label class="form-label lng">Cooking Time (Minutes)</label>
                                                    <input name="productcookingtime" type="text" class="form-control timepicker3 hasDatepicker" id="productcookingtime" autocomplete="off" value="<?= set_value('productcookingtime') ?>" onkeypress="return isNumberKey(event)" data-parsley-required="true">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-6">
                                                <div class="form-group mandatory">
                                                    <label for="number-of-person-served" class="form-label">Number of persons served</label>
                                                    <input type="text" class="form-control" id="number_of_person_served" name="number_of_person_served" onkeypress="return isNumberKey(event)" value="<?= set_value('number_of_person_served') ?>" data-parsley-required="true">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-4 col-12 mb-3">

                                            <div class="d-flex align-items-center">

                                                <div class="form-check mandatory">
                                                    <div class="checkbox">
                                                        <label for="checkbox1">Status</label>
                                                        <input type="checkbox" id="isActive" class="form-check-input" name="isActive">
                                                    </div>
                                                </div>

                                                <div class="ms-5 form-check">
                                                    <div class="checkbox">
                                                        <label for="checkbox2">Is it an Addon?</label>
                                                        <input type="checkbox" id="isAddOnProduct" class="form-check-input" name="isAddOnProduct">
                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                        <!--<div class="col-md-4 col-6 mb-3">
                                            <div class="form-check">
                                                <div class="checkbox">
                                                    <label for="checkbox2">Is it an Addon?</label>
                                                    <input type="checkbox" id="isAddOnProduct" class="form-check-input" name="isAddOnProduct">
                                                </div>
                                            </div>
                                        </div>-->

                                    </div>


                                    <div class="col-md-5 col-12">
                                        <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                            <div class="card card-custom gutter-b bg-white border-0">
                                                <div class="card-body">
                                                    <div class="col-md-12 col-sm-6 col-xs-6">
                                                        <h3 class="mt-0 header-title lng">Product Category <b style="color:red">*</b></h3>
                                                        <div class="form-group mandatory">
                                                            <select class="form-select select2" style="width:100%" name="productcategory" id="productcategory" data-parsley-required="true" data-parsley-required-message="Product Category is required.">
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 col-sm-6 col-xs-6">
                                                        <h3 class="mt-0 header-title lng">Product Subcategory</h3>
                                                        <div class="form-group mandatory">
                                                            <select class="form-select select2" style="width:100%" name="productsubcategory" id="productsubcategory">
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
                                                    <div class="col-md-12">
                                                        <img class="img-thumbnail mb-2" width="110px" id="logo_icon" src="<?= base_url('assets/images/food.png') ?>" data-src="">
                                                        <input class="form-control" type="file" id="formFile" name="productImage">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                            <div class="card card-custom gutter-b bg-white border-0">
                                                <div class="card-body">
                                                    <div style="display:none ;">
                                                        <label for="productpricingmethod" class="form-label">Pricing Method </label>
                                                        <input type="hidden" name="productpricingmethod" value="1" readonly>
                                                    </div>
                                                    <div class="form-group m-3 mt-2 row align-items-center mandatory" id="priceShow">
                                                        <div class=" col-md-4">
                                                            <label class="col-form-label lng">Price (SAR) <b style="color:red">*</b></label>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <input type="text" min="1" class="form-control" name="productprice" id="productprice" data-parsley-required="true" data-parsley-required-message="Product Price is required." value="<?= set_value('productprice') ?>" onkeypress="return isDecimal(event)">
                                                        </div>
                                                    </div>
                                                    <div class="form-group m-3 row align-items-center">
                                                        <div class=" col-md-4">
                                                            <label for="producttaxgroup" class="form-label">Tax Group <b style="color:red">*</b></label>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="form-group mandatory">
                                                                <select class="form-select select2" style="width:100%" name="producttaxgroup" id="producttaxgroup" data-parsley-required="true" data-parsley-required-message="Product Tax Group is required.">
                                                                    <option value="">Select Tax Group</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group m-3 mt-2 row align-items-center">
                                                        <div class="col-md-4">
                                                            <label class="col-form-label lng">Product Calories</label>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <input type="text" id="productcalories" class="form-control" name="productcalories" value="<?= set_value('productcalories') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group m-3 mt-2 row align-items-center mandatory" id="costingShow">
                                                        <!--<div class="form-group m-3 row">-->
                                                        <div class=" col-md-4">
                                                            <label class="col-form-label lng">Branches</label>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <select class="form-select select2" name="branches[]" multiple="multiple" id="branches">
                                                            </select>
                                                        </div>
                                                        <!--</div>-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <?php if ($insertRights == 1) { ?>
                                                <button id="saveCategoryBtn" type="submit" class="btn btn-success">Save</button>
                                            <?php } ?>
                                            <button id="closeCategoryBtn" type="reset" class="btn btn-light-secondary">Reset</button>
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
<script type="text/javascript" src=<?php echo base_url() . 'assets/js/google_jsapi.js'; ?>></script>
<script>
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
        // var keyVal = 32; // Space key

        /* $("#product-english-name").on('keydown', function(event) {
             if (event.keyCode === 32 || event.keyCode === 9) {
                 var engText = $("#product-english-name").val() + " ";
                 var engTextArray = engText.split(" ");
                 $("#product-hindi-name").val($("#product-hindi-name").val() + engTextArray[engTextArray.length - 2]);
                 //alert(event.keyCode);  
                 document.getElementById("product-hindi-name").focus();
                 $("#product-hindi-name").trigger({
                     type: 'keypress',
                     keyCode: keyVal,
                     which: keyVal,
                     charCode: keyVal
                 });
             }
         });*/
    }
    google.setOnLoadCallback(onLoad);
    $(document).ready(function() {
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

        $("#branches").select2({
            placeholder: "Select Branch Name",
            allowClear: true,
            ajax: {
                url: base_path + 'Common/getBranch',
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

        /*$("body").delegate('#productcategory', "change", function() {
            var productCategory = $(this).find('option:selected').attr('id');
            if (productCategory != "") {
                $.ajax({
                    url: base_path + 'Product/getSubCategoryList',
                    type: "POST",
                    data: {
                        'productCategory': productCategory
                    },
                    success: function(response) {
                        if (response != "") {
                            $("select#productsubcategory").attr('disabled', false);
                            $('select#productsubcategory').html(response);

                        } else {
                            var opt = '<option value="">Select Subcategory</option>'
                            $('select#productsubcategory').html(opt);
                            $("select#productsubcategory").attr('disabled', true);
                        }
                    }
                });
            }
        });*/

        $("body").on("change", "#productpricingmethod", function(e) {
            var thisVal = $(this).val();
            if (thisVal == "1") {
                $("#priceShow").show();
                $("#productprice").attr("data-parsley-required", true);

            } else {
                $("#priceShow").hide();
                $("#productprice").removeAttr("data-parsley-required");
            }
        });
        $("body").on("change", "#productcostingmethod", function(e) {
            var thisVal = $(this).val();
            if (thisVal == "1") {
                $("#costingShow").show();
                $("#productcost").attr("data-parsley-required", true);

            } else {
                $("#costingShow").hide();
                $("#productcost").removeAttr("data-parsley-required");
            }
        });
    });
</script>