<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Update Item</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Item</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row">
            <section class="section col-12 col-md-12">
                <form id="updateitemForm" class="form" data-parsley-validate>
                    <?php
                    if ($itemData) {
                        foreach ($itemData->result() as $br) { ?>
                            <input type="hidden" class="form-control" id="modalItemCode" name="modalItemCode" value="<?= $br->code ?>">
                            <div class="card">


                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-7">
                                            <h5>Update Item</h5>
                                        </div>
                                        <div class="col-5 text-end">
                                            <a href="<?= base_url('item/listRecords') ?>"><button id="cancelDefault" type="button" class="btn btn-primary">Back</button></a>
                                        </div>
                                    </div>
                                </div>


                                <div class="card-body">
                                    <div class="row">


                                        <div class="col-md-12 row col-12 mb-3">


                                            <div class="form-group col-md-6 col-12 mandatory">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Item Name</label>
                                                <input type="text" id="modalItemName" class="form-control" placeholder="Item Name" value="<?= $br->itemEngName ?>" name="modalItemName" required>
                                            </div>


                                            <div class="form-group col-md-6 col-12 mandatory">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Category</label>
                                                <select class="form-select select2" name="category" id="category" required data-parsley-required-message="Category is required">
                                                    <option value="">Select</option>
                                                    <?php
                                                    if ($categorydata) {
                                                        foreach ($categorydata->result() as $catData) {
                                                            if ($catData->code == $br->categoryCode) {
                                                                echo "<option value='" . $catData->code . "' selected >" . $catData->categoryName . "</option>'";
                                                            } else {
                                                                echo "<option value='" . $catData->code . "'>" . $catData->categoryName . "</option>'";
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>


                                        </div>


                                        <div class="col-md-12 row col-12 mb-3">


                                            <div class="form-group col-md-6 col-12 mandatory">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Storage Unit</label>
                                                <select class="form-select select2" name="modalItemUnit" id="modalItemUnit" required>
                                                    <option value="">Select</option>
                                                    <?php
                                                    if ($units) {
                                                        foreach ($units->result() as $tc) {
                                                            if ($tc->code == $br->storageUnit) {
                                                                echo "<option value='" . $tc->code . "' selected>" . $tc->unitName . "</option>";
                                                            } else {
                                                                echo "<option value='" . $tc->code . "'>" . $tc->unitName . "</option>'";
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>


                                            <div class="form-group col-md-6 col-12 mandatory">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Ingredient Unit</label>
                                                <select class="form-select select2" name="modalIngredientUnit" id="modalIngredientUnit" required>
                                                    <option value="">Select</option>
                                                    <?php
                                                    if ($units) {
                                                        foreach ($units->result() as $tc) {
                                                            if ($tc->code == $br->ingredientUnit) {
                                                                echo "<option value='" . $tc->code . "' selected>" . $tc->unitName . "</option>";
                                                            } else {
                                                                echo "<option value='" . $tc->code . "'>" . $tc->unitName . "</option>'";
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>



                                        </div>



                                        <div class="col-md-12 col-12 row mb-3">


                                            <div class="form-group col-md-6 col-12 mandatory">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Ingredient Factor</label>
                                                <input type="text" id="modalIngredientFactor" class="form-control" placeholder="Ingredient Factor" name="modalIngredientFactor" required onkeypress="return isNumber(event)" value="<?= $br->ingredientFactor ?>">
                                            </div>


                                            <div class="form-group col-md-6 col-12 mandatory">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Item Price</label>
                                                <input type="text" id="modalItemPrice" class="form-control" name="modalItemPrice" required onkeypress="return isNumber(event)" onkeypress="return isNumber(event)" value="<?= $br->itemPrice ?>">
                                            </div>


                                        </div>



                                        <div class="col-md-12 col-12 row mb-3">

                                            <div class="form-group col-md-12 col-12">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Item Arabic Name</label>
                                                <input type="text" id="modalItemArbName" class="form-control" name="modalItemArbName" value="<?= $br->itemArbName ?>">
                                            </div>

                                        </div>


                                        <div class="col-md-12 col-12 row mb-3">

                                            <div class="form-group col-md-6 col-12">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Item Hindi Name</label>
                                                <input type="text" id="modalItemHinName" class="form-control" name="modalItemHinName" value="<?= $br->itemHinName ?>">
                                            </div>

                                            <div class="form-group col-md-6 col-12">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Item Urdu Name</label>
                                                <input type="text" id="modalItemUrduName" class="form-control" name="modalItemUrduName" value="<?= $br->itemUrduName ?>">
                                            </div>

                                        </div>




                                        <div class="col-md-12 col-12 row mb-3">

                                            <div class="form-group col-md-6 col-12">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Item Arabic Description</label>
                                                <textarea rows="4" id="modalItemArbDesc" class="form-control" name="modalItemArbDesc"><?= $br->itemArbDesc ?></textarea>
                                            </div>

                                            <div class="form-group col-md-6 col-12">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Item Description</label>
                                                <textarea rows="4" type="text" id="modalItemDesc" class="form-control" name="modalItemDesc"><?= $br->itemEngDesc ?></textarea>
                                            </div>

                                        </div>



                                        <div class="col-md-12 col-12 row mb-3">

                                            <div class="form-group col-md-6 col-12">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Item Hindi Description</label>
                                                <textarea rows="4" type="text" id="modalItemHinDesc" class="form-control" name="modalItemHinDesc"><?= $br->itemHinDesc ?></textarea>
                                            </div>

                                            <div class="form-group col-md-6 col-12">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Item Urdu Description</label>
                                                <textarea rows="4" type="text" id="modalItemUrduDesc" class="form-control" name="modalItemUrduDesc"><?= $br->itemUrduDesc ?></textarea>
                                            </div>

                                        </div>


                                        <div class="form-group d-flex col-md-12 col-12 text-center items-center justify-content-center row">
                                            <!--<div class="col-sm-8 checkbox">-->
                                            <input type="checkbox" name="modalisActive" id="modalisActive" <?= $br->isActive == 1 ? 'checked' : ""  ?> class=" " style="width:25px; height:25px">
                                            <!--</div>-->
                                        </div>


                                    </div>
                                </div>

                                <div class="card-footer col-12 text-end">
                                    <button type="button" class="btn btn-primary" id="updateItemBtn">Update</button>
                                </div>

                            </div>
                    <?php
                        }
                    }
                    ?>
                </form>
            </section>
        </div>
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

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
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
        control.makeTransliteratable(['modalItemHinName']);
        control.makeTransliteratable(['modalItemHinDesc']);
        var controlUrdu = new google.elements.transliteration.TransliterationControl(optionsUrdu);
        controlUrdu.makeTransliteratable(['modalItemUrduName']);
        controlUrdu.makeTransliteratable(['modalItemUrduDesc']);
        var controlArabic = new google.elements.transliteration.TransliterationControl(optionsArabic);
        controlArabic.makeTransliteratable(['modalItemArbName']);
        controlArabic.makeTransliteratable(['modalItemArbDesc']);
    }

    google.setOnLoadCallback(onLoad);

    $(document).ready(function() {

        $("#category").select2({
            placeholder: "Select Category",
            allowClear: true,
            ajax: {
                url: base_path + 'Common/getItemCategory',
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
        $("#itemUnit").select2({
            placeholder: "Select Itemunit",
            allowClear: true,
            dropdownParent: $('#generl_modal .modal-content'),
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
        $("#ingredientUnit").select2({
            placeholder: "Select Ingredientunit",
            allowClear: true,
            dropdownParent: $('#generl_modal .modal-content'),
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
    });

    $(document).on("click", "button#updateItemBtn", function(e) {
        $('#updateitemForm').parsley();
        const form = document.getElementById('updateitemForm');
        var formData = new FormData(form);
        var isValid = true;
        e.preventDefault();
        $("#updateitemForm .form-control").each(function(e) {
            if ($(this).parsley().validate() !== true) isValid = false;
        });
        $("#updateitemForm .form-select").each(function(e) {
            if ($(this).parsley().validate() !== true) isValid = false;
        });
        if (isValid) {
            var isActive = 0;
            if ($("#modalisActive").is(':checked')) {
                isActive = 1;
            }
            formData.append('modalisActive', isActive);
            $.ajax({
                url: base_path + "item/updateItem",
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "JSON",
                beforeSend: function() {
                    $('#updateItemBtn').prop('disabled', true);
                    $('#updateItemBtn').text('Please wait..');
                },
                success: function(response) {
                    if (response.status) {
                        toastr.success(response.message, 'Item', {
                            "progressBar": true,
                            "onHidden": function() {
                                window.location.replace("<?= base_url('item/listRecords') ?>");
                            }
                        });
                    } else {
                        $('#updateItemBtn').prop('disabled', false);
                        $('#updateItemBtn').text('Update');
                        toastr.error(response.message, 'Item', {
                            "progressBar": true
                        });
                    }
                },
                error: function() {
                    toastr.error("Something went wrong. Please try again later", 'Item', {
                        "progressBar": true,
                        "onHidden": function() {
                            window.location.replace("<?= base_url('item/listRecords') ?>");
                        }
                    });
                }
            });
        }
    });
</script>