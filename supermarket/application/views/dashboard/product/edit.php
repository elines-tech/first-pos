<?php include '../supermarket/config.php'; ?>

<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3><?php echo $translations['Product']?></h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../../dashboard/listRecords"><i class="fa fa-dashboard"></i><?php echo $translations['Dashboard']?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $translations['Product']?></li>
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
                            <h3><?php echo $translations['Update Product']?><span style="float:right">
                                    <div class="d-flex"><?php if ($productData[0]['hasVariants'] == 1) { ?><a id="productVariants" href="<?= base_url() ?>Product/variant/<?= $productData[0]['code'] ?>" class="btn btn-sm btn-primary m-1"><?php echo $translations['Product Variants']?></a><?php } ?><a id="cancelDefaultButton" href="<?= base_url() ?>product/listRecords" class="btn btn-sm btn-primary m-1"><?php echo $translations['Back']?></a></div>
                                </span></h3>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <form class="form" action="<?= base_url('product/update') ?>" method="post" enctype="multipart/form-data" data-parsley-validate>
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


                                                <div class="row col-md-12 col-12">

                                                    <div class="form-group col-md-6 col-12 mandatory">
                                                        <label for="product-arabic-name" class="form-label"><?php echo $translations['Arabic Name']?></label>
                                                        <input type="text" id="product-arabic-name" class="form-control" placeholder="Arabic Name" name="product-arabic-name" data-parsley-required="true" value="<?= $productData[0]['productArbName'] ?>">
                                                    </div>


                                                    <div class="form-group col-md-6 col-12 mandatory">
                                                        <label for="product-english-name" class="form-label"><?php echo $translations['English Name']?></label>
                                                        <input type="text" id="product-english-name" class="form-control" placeholder="English Name" name="product-english-name" data-parsley-required="true" value="<?= $productData[0]['productEngName'] ?>">
                                                    </div>
                                                </div>


                                                <div class="row col-md-12 col-12">

                                                    <div class="form-group col-md-6 col-12">
                                                        <label for="desc-column"><?php echo $translations['Arabic Description']?></label>
                                                        <textarea class="form-control" placeholder="Product Arabic Description" id="product-arabic-description" name="product-arabic-description" maxlength='2000' data-parsley-minlength="20" data-parsley-minlength-message="You need to enter at least 20 characters" data-parsley-trigger="change"><?= $productData[0]['productArbDesc'] ?></textarea>
                                                    </div>


                                                    <div class="form-group col-md-6 col-12">
                                                        <label for="desc-column"><?php echo $translations['English Description']?></label>
                                                        <textarea class="form-control" placeholder="Product English Description" id="product-english-description" name="product-english-description" maxlength='2000' data-parsley-minlength="20" data-parsley-minlength-message="You need to enter at least 20 characters" data-parsley-trigger="change"><?= $productData[0]['productEngDesc'] ?></textarea>
                                                    </div>
                                                </div>



                                                <div class="row col-md-12 col-12">


                                                    <div class="form-group col-md-6 col-12 mandatory">
                                                        <label for="product-hindi-name"><?php echo $translations['Hindi']?></label>
                                                        <input type="text" id="product-hindi-name" class="form-control" placeholder="Hindi Name" name="product-hindi-name" value="<?= $productData[0]['productHinName'] ?>">
                                                    </div>


                                                    <div class="form-group col-md-6 col-12 mandatory">
                                                        <label for="product-urdu-name"><?php echo $translations['Urdu']?></label>
                                                        <input type="text" id="product-urdu-name" class="form-control" placeholder="Urdu Name" name="product-urdu-name" value="<?= $productData[0]['productUrduName'] ?>">
                                                    </div>
                                                </div>


                                                <div class="row col-md-12 col-12">


                                                    <div class="form-group col-md-6 col-12">
                                                        <label for="desc-column"><?php echo $translations['Hindi Description']?></label>
                                                        <textarea class="form-control" placeholder="Product Hindi Description" id="product-hindi-description" name="product-hindi-description" maxlength='2000' data-parsley-minlength="20" data-parsley-minlength-message="You need to enter at least 20 characters" data-parsley-trigger="change"><?= $productData[0]['productHinDesc'] ?></textarea>
                                                    </div>

                                                    <div class="form-group form-group col-md-6 col-12">
                                                        <label for="desc-column"><?php echo $translations['Urdu Description']?></label>
                                                        <textarea class="form-control" placeholder="Product Urdu Description" id="product-urdu-description" name="product-urdu-description" maxlength='2000' data-parsley-minlength="20" data-parsley-minlength-message="You need to enter at least 20 characters" data-parsley-trigger="change"><?= $productData[0]['productUrduDesc'] ?></textarea>
                                                    </div>

                                                </div>


                                            </div>



                                            <div class="row">

                                                <div class="row col-md-12 col-12">

                                                    <div class="form-group col-md-6 col-12 mandatory">
                                                        <label for="productprice" class="form-label"><?php echo $translations['SKU']?></label>
                                                        <input type="text" class="form-control" name="productsku" id="productsku" value="<?= $productData[0]['sku'] ?>" data-parsley-required="true" data-parsley-required-message="Product Price is required." value="<?= set_value('productsku') ?>">
                                                    </div>

                                                    <div class="form-group col-md-6 col-12">
                                                        <label for="productprice" class="form-label"><?php echo $translations['Alert Quantity']?></label>
                                                        <input type="text" min="1" class="form-control" name="alertQty" id="alertQty" value="<?php $productData[0]['alertQty'] != "" ? $productData[0]['alertQty'] : 0 ?>" onkeypress="return isDecimal(event)">
                                                    </div>


                                                </div>


                                                <!--
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group mandatory">
                                                        <label for="productprice" class="form-label">Price</label>
                                                        <input type="text" min="1" class="form-control" name="productprice" id="productprice" data-parsley-required="true" data-parsley-required-message="Product Price is required." value="< $productData[0]['productPrice'] ?>" onkeypress="return isDecimal(event)">
                                                    </div>
                                                </div>
                                                -->



                                                <div class="row col-md-12 col-12">
                                                    <div class="form-group mandatory">
                                                        <label for="productprice" class="form-label"><?php echo $translations['Tax Group']?></label>
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


                                            <div class="row col-md-12 col-12 mb-3">

                                                <div class="form-check col-md-6 col-12 mandatory">
                                                    <div class="checkbox form-check col-md-6 col-12 mandatory">
                                                        <label for="checkbox1"><?php echo $translations['Status']?></label>
                                                        <input type="checkbox" id="isActive" class="form-check-input" name="isActive" <?php if ($productData[0]['isActive'] == 1) {
                                                                                                                                            echo "checked";
                                                                                                                                        } ?>>
                                                    </div>
                                                </div>

                                                <div class="form-check col-md-6 col-12 mandatory">
                                                    <div class="checkbox form-check col-md-6 col-12 mandatory">
                                                        <label><?php echo $translations['has Variants?']?></label>
                                                        <input type="checkbox" id="isVariants" class="form-check-input" name="isVariants" <?php if ($productData[0]['hasVariants'] == 1) {
                                                                                                                                                echo "checked";
                                                                                                                                            } ?>>
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
                                                                <label for="productbrand" class="form-label"><?php echo $translations['Product Brand']?></label>
                                                                <select class="form-select select2" name="productbrand" id="productbrand" data-parsley-required="true" data-parsley-required-message="Product Brand is required.">
                                                                    <option value="">Select </option>
                                                                    <?php if ($branddata) {
                                                                        foreach ($branddata->result() as $brd) {
                                                                            $selected = $productData[0]['brandCode'] == $brd->code ? 'selected' : '';
                                                                            echo '<option value="' . $brd->code . '"' . $selected . '>' . $brd->brandName . '</option>';
                                                                        }
                                                                    } ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group mandatory">
                                                                <label for="productcategory" class="form-label"><?php echo $translations['Product Category']?></label>
                                                                <select class="form-select select2" name="productcategory" id="productcategory" onchange="getSubcategoryList()" data-parsley-required="true" data-parsley-required-message="Product Category is required.">
                                                                    <option value="">Select </option>
                                                                    <?php if ($categorydata) {
                                                                        foreach ($categorydata->result() as $category) {
                                                                            $selected = $productData[0]['productCategory'] == $category->code ? 'selected' : '';
                                                                            echo '<option value="' . $category->code . '"' . $selected . '>' . $category->categoryName . '</option>';
                                                                        }
                                                                    } ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="productsubcategory" class="form-label"><?php echo $translations['Product Subcategory']?></label>
                                                                <input type="hidden" class="form-control" id="dbproductsubcategory" name="dbproductsubcategory" value="<?= $productData[0]['productSubcategory'] ?>">
                                                                <select class="form-select select2" style="width:100%" name="productsubcategory" id="productsubcategory">
                                                                    <option value="">Select </option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group mandatory">
                                                                <label for="productcategory" class="form-label"><?php echo $translations['Product Unit']?></label>
                                                                <select class="form-select select2" name="productUnit" id="productUnit" data-parsley-required="true" data-parsley-required-message="Product Unit is required.">
                                                                    <option value="">Select </option>
                                                                    <?php if ($unitdata) {
                                                                        foreach ($unitdata->result() as $unit) {
                                                                            $selected = $productData[0]['storageUnit'] == $unit->code ? 'selected' : '';
                                                                            echo '<option value="' . $unit->code . '"' . $selected . '>' . $unit->unitName . '</option>';
                                                                        }
                                                                    } ?>
                                                                </select>
                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                                <div class="card card-custom gutter-b bg-white border-0">
                                                    <div class="card-body">
                                                        <h3 class="mt-0 header-title lng"><?php echo $translations['Product Images']?></h3>

                                                        <div class="col-md-12 col-sm-12 col-xs-12 text-center mb-2 p-0 text-left">
                                                            <?php if ($productData[0]['productImage'] != "") { ?>
                                                                <img class="img-thumbnail mb-2" width="120px" id="logo_icon" src="<?= base_url() . $productData[0]['productImage'] ?>" data-src="">
                                                            <?php } else { ?>
                                                                <img class="img-thumbnail mb-2" width="120px" id="logo_icon" src="https://sub.kaemsoftware.com/development/assets/images/faces/default-img.jpg" data-src="">
                                                            <?php } ?>
                                                            <input class="form-control" type="file" id="formFile" name="productImage" style="padding: 5px;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <button id="saveDefaultButton" type="submit" class="btn btn-success"><?php echo $translations['Update']?></button>
                                            <button id="cancelDefaultButton" type="reset" class="btn btn-light-secondary"><?php echo $translations['Reset']?></button>
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

    }
    google.setOnLoadCallback(onLoad);

    $(document).ready(function() {
        getSubcategoryList()
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