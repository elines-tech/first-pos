<style>
    .select2-container--classic .select2-selection--single,
    .select2-container--default .select2-selection--multiple,
    .select2-container--default .select2-selection--single,
    .select2-container--default .select2-selection--single .select2-selection__arrow,
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        border-color: rgba(0, 0, 0, 0.25);
        height: auto;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background: #1ca1c1;
        color: white
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected],
    .select2-container--default .select2-results__option[aria-selected=true] {
        background: #1ca1c1
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice>span {
        color: white !important;
        font-weight: bold
    }
</style>
<nav class="navbar navbar-light">
    <div class="container d-block">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <a href="<?php echo base_url(); ?>Product/listrecords"><i class="fa fa-times fa-2x"></i></a>
            </div>
        </div>
    </div>
</nav>
<div class="container">
    <section id="multiple-column-form" class="mt-5">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Update Product</h3>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form" action="<?= base_url('Product/update') ?>" method="post" enctype="multipart/form-data" data-parsley-validate>
                                <?php
                                echo "<div class='text-danger text-center' id='error_message'>";
                                if (isset($error_message)) {
                                    echo $error_message;
                                }
                                echo "</div>";
                                ?>
                                <input type="hidden" id="code" readonly name="code" class="form-control" value="<?= $productData[0]['code'] ?>">
                                <div class="row">
                                    <div class="col-md-7 col-12">
                                        <div class="row">
                                            <div class="col-md-12 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="product-english-name" class="form-label">English Name</label>
                                                    <input type="text" id="product-english-name" class="form-control" placeholder="English Name" name="product-english-name" data-parsley-required="true" value="<?= $productData[0]['productEngName'] ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-12">
                                                <div class="form-group">
                                                    <label for="desc-column" class="form-label">Product English Description</label>
                                                    <textarea class="form-control" placeholder="Product English Description" id="product-english-description" name="product-english-description" maxlength='2000' data-parsley-minlength="10" data-parsley-minlength-message="You need to enter at least 10 characters" data-parsley-trigger="change"><?= $productData[0]['productEngDesc'] ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="product-arabic-name" class="form-label">Arabic Name</label>
                                                    <input type="text" id="product-arabic-name" class="form-control" placeholder="Arabic Name" name="product-arabic-name" data-parsley-required="true" value="<?= $productData[0]['productArbName'] ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-12">
                                                <div class="form-group">
                                                    <label for="desc-column" class="form-label">Arabic Description</label>
                                                    <textarea class="form-control" placeholder="Product Arabic Description" id="product-arabic-description" name="product-arabic-description" maxlength='2000' data-parsley-minlength="10" data-parsley-minlength-message="You need to enter at least 10 characters" data-parsley-trigger="change"><?= $productData[0]['productArbDesc'] ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-12">
                                                <div class="form-group">
                                                    <label for="product-hindi-name" class="form-label">Hindi</label>
                                                    <input type="text" id="product-hindi-name" class="form-control" placeholder="Hindi Name" name="product-hindi-name" value="<?= $productData[0]['productHinName'] ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-12">
                                                <div class="form-group">
                                                    <label for="desc-column" class="form-label">Hindi Description</label>
                                                    <textarea class="form-control" placeholder="Product Hindi Description" id="product-hindi-description" name="product-hindi-description" maxlength='2000' data-parsley-minlength="10" data-parsley-minlength-message="You need to enter at least 10 characters" data-parsley-trigger="change"><?= $productData[0]['productHinDesc'] ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-12">
                                                <div class="form-group">
                                                    <label for="product-urdu-name" class="form-label">Urdu</label>
                                                    <input type="text" id="product-urdu-name" class="form-control" placeholder="Urdu Name" name="product-urdu-name" value="<?= $productData[0]['productUrduName'] ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-12">
                                                <div class="form-group">
                                                    <label for="desc-column" class="form-label">Urdu Description</label>
                                                    <textarea class="form-control" placeholder="Product Urdu Description" id="product-urdu-description" name="product-urdu-description" maxlength='2000' data-parsley-minlength="10" data-parsley-minlength-message="You need to enter at least 10 characters" data-parsley-trigger="change"><?= $productData[0]['productUrduDesc'] ?></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 col-6 mb-3">
                                                <div class="form-group mandatory">
                                                    <label class="form-label lng">Cooking Time (Minutes)</label>
                                                    <input name="productcookingtime" type="text" class="form-control timepicker3 hasDatepicker" id="productcookingtime" autocomplete="off" value="<?= $productData[0]['preparationTime'] ?>" onkeypress="return isNumberKey(event)" data-parsley-required="true">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-6">
                                                <div class="form-group mandatory">
                                                    <label for="number-of-person-served" class="form-label">Number of persons served</label>
                                                    <input type="text" class="form-control" id="number_of_person_served" name="number_of_person_served" onkeypress="return isNumberKey(event)" value="<?= $productData[0]['numberOfPersonServed'] ?>" data-parsley-required="true">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12 mb-3">
                                            <div class="form-check mandatory">
                                                <div class="checkbox">
                                                    <label for="checkbox1">Status</label>
                                                    <input type="checkbox" id="isActive" class="form-check-input" name="isActive" <?= ($productData[0]['isActive'] == 1) ? "checked" : "" ?>>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-6 mb-3">
                                            <div class="form-check">
                                                <div class="checkbox">
                                                    <label for="checkbox2">Is it an Addon?</label>
                                                    <input type="checkbox" id="isAddOnProduct" class="form-check-input" name="isAddOnProduct" <?= ($productData[0]['isAddOn'] == 1) ? "checked" : "" ?>>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5 col-12">
                                        <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                            <div class="card card-custom gutter-b bg-white border-0">
                                                <div class="card-body">
                                                    <div class="col-md-12 col-sm-6 col-xs-6">
                                                        <h3 class="mt-0 header-title lng">Product Category <b style="color:red">*</b></h3>
                                                        <div class="form-group mandatory">
                                                            <select class="form-select select2" name="productcategory" id="productcategory" data-parsley-required="true" data-parsley-required-message="Product Category is required.">
                                                                <option value="">Select Category</option>
                                                                <?php if ($categorydata) {
                                                                    foreach ($categorydata->result() as $category) {
                                                                        $selected = $productData[0]['productCategory'] == $category->code ? 'selected' : '';
                                                                        echo '<option value="' . $category->code . '"' . $selected . ' id="' . $category->code . '">' . $category->categoryName . '</option>';
                                                                    }
                                                                } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 col-sm-6 col-xs-6">
                                                        <h3 class="mt-0 header-title lng">Product Subcategory <b style="color:red">*</b></h3>
                                                        <div class="form-group">
                                                            <select class="form-select select2" name="productsubcategory" id="productsubcategory">
                                                                <option value="">Select Subcategory</option>
                                                                <?php
                                                                if ($subcategorydata) {
                                                                    foreach ($subcategorydata->result() as $subcategory) {
                                                                        $selected = $productData[0]['subcategory'] == $subcategory->code ? 'selected' : '';
                                                                        echo '<option value="' . $subcategory->code . '"' . $selected . '>' . $subcategory->subcategoryName . '</option>';
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                            <div class="card card-custom gutter-b bg-white border-0">
                                                <div class="card-body">
                                                    <h3 class="mt-0 header-title lng">Product Images</h3>
                                                    <div class="col-md-12">
                                                        <?php if ($productData[0]['productImage'] != "") { ?>
                                                            <img class="img-thumbnail mb-2" width="120px" id="logo_icon" src="<?= base_url() . $productData[0]['productImage'] ?>" data-src="">
                                                        <?php } else { ?>
                                                            <img class="img-thumbnail mb-2" width="120px" id="logo_icon" src="<?= base_url('assets/images/food.png') ?>" data-src="">
                                                        <?php } ?>
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
                                                    <div class="form-group m-3 mt-2 row align-items-center mandatory" id="priceShow" style="display:<?= $productData[0]['productMethod'] == '1' ? '' : 'none' ?>">
                                                        <div class=" col-md-4">
                                                            <label class="col-form-label lng">Price (SAR) <b style="color:red">*</b></label>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <input type="text" min="1" class="form-control" name="productprice" id="productprice" <?php if ($productData[0]['productMethod'] == '1') { ?>data-parsley-required="true" data-parsley-required-message="Product Price is required." <?php } ?> value="<?= $productData[0]['productPrice'] ?>" onkeypress="return isDecimal(event)">
                                                        </div>
                                                    </div>
                                                    <div class="form-group m-3 row align-items-center">
                                                        <div class=" col-md-4">
                                                            <label for="producttaxgroup" class="form-label">Tax Group <b style="color:red">*</b></label>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="form-group mandatory">
                                                                <select class="form-select select2" name="producttaxgroup" id="producttaxgroup" data-parsley-required="true" data-parsley-required-message="Product Tax Group is required.">
                                                                    <option value="">Select Tax Group</option>
                                                                    <?php if ($taxGroupData) {
                                                                        foreach ($taxGroupData->result() as $tax) {
                                                                            $selected = $productData[0]['productTaxGrp'] == $tax->code ? 'selected' : '';
                                                                            echo '<option value="' . $tax->code . '" ' . $selected . '>' . $tax->taxGroupName . '</option>';
                                                                        }
                                                                    } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group m-3 mt-2 row align-items-center">
                                                        <div class=" col-md-4">
                                                            <label class="col-form-label lng">Product Calories</label>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <input type="text" id="productcalories" class="form-control" name="productcalories" value="<?= $productData[0]['productCalories'] ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group m-3 row align-items-center">
                                                        <div class=" col-md-4">
                                                            <label class="col-form-label lng">Branches</label>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <select class="form-select select2" name="branches[]" multiple="multiple" id="branches">
                                                                <?php
                                                                $inActiveBranches = json_decode($productData[0]['inActiveBranches'], true);
                                                                if ($branchdata) {
                                                                    foreach ($branchdata->result() as $branch) {
                                                                        if ($inActiveBranches != "") {
                                                                            if (in_array($branch->code, $inActiveBranches)) {
                                                                                echo '<option value="' . $branch->code . '" selected>' . $branch->branchName . '</option>';
                                                                            } else {
                                                                                echo '<option value="' . $branch->code . '">' . $branch->branchName . '</option>';
                                                                            }
                                                                        } else {
                                                                            echo '<option value="' . $branch->code . '">' . $branch->branchName . '</option>';
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-end">
                                        <?php if ($updateRights == 1) { ?>
                                            <button type="submit" class="btn btn-success white me-1 mb-1 sub_1">Update</button>
                                        <?php } ?>
                                        <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
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

        $("body").delegate('#productcategory', "change", function() {
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
        });

        $("body").on("change", "#productpricingmethod", function(e) {
            var thisVal = $(this).val();
            if (thisVal == "1") {
                $("#priceShow").show();
                $("#productprice").attr("data-parsley-required", true);
                $("#productprice").attr("data-parsley-required-message", "Product Price is required.");

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
                $("#productcost").attr("data-parsley-required-message", "Product Cost is required.");

            } else {
                $("#costingShow").hide();
                $("#productcost").removeAttr("data-parsley-required");

            }
        });
    });
</script>