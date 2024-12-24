<nav class="navbar navbar-light">
    <div class="container d-block">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last"><a href="<?php echo base_url(); ?>ProductCombo/listrecords"><i id="exitButton" class="fa fa-times fa-2x"></i></a></div>

        </div>
    </div>
</nav>


<div class="container">

    <!-- // Basic multiple Column Form section start -->
    <section id="multiple-column-form" class="mt-5 mb-5">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Update ProductCombo</h3>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form id="productComboEditForm" class="form" data-parsley-validate="">
                                <div class="row">

                                    <div class="row col-md-12 col-12">

                                        <div class="col-md-6 col-12 form-group mandatory">
                                            <label for="var-name-column" class="form-label">English Name</label>
                                            <input type="hidden" id="ccode" name="ccode" data-parsley-required="true" value="<?= $productCombo->code ?>">
                                            <input type="text" id="cname1" class="form-control" placeholder="Combo Name" name="cname1" data-parsley-required="true" value="<?= $productCombo->productComboName ?>">

                                        </div>

                                        <div class="col-md-6 col-12 form-group mandatory">
                                            <label for="var-name-column" class="form-label">Arabic Name</label>
                                            <input type="text" id="c_arabicname" class="form-control reqClass" placeholder="Combos/Meals Arabic Name" name="carabicname1" data-parsley-required="true" data-parsley-required-message="Product Combo Name is required." value="<?= $productCombo->productComboArabicName ?>">
                                        </div>

                                    </div>


                                    <div class="row col-md-12 col-12">
                                        <div class="col-md-6 col-12 form-group mandatory">
                                            <label for="var-name-column" class="form-label">Hindi Name</label>
                                            <input type="text" id="c_hindiname" class="form-control reqClass" placeholder="Combos/Meals Hindi Name" name="chindiname1" data-parsley-required="true" data-parsley-required-message="Product Combo Name is required." value="<?= $productCombo->productComboHindiName ?>">

                                        </div>

                                        <div class="col-md-6 col-12 form-group mandatory">
                                            <label for="var-name-column" class="form-label">Urdu Name</label>
                                            <input type="text" id="c_urduname" class="form-control reqClass" placeholder="Combos/Meals Urdu Name" name="curduname1" data-parsley-required="true" data-parsley-required-message="Product Combo Name is required." value="<?= $productCombo->productComboUrduName ?>">

                                        </div>
                                    </div>


                                    <?php
                                    $option = 0;
                                    ?>

                                    <div class="row col-md-12 col-12">

                                        <div class="col-md-12 col-12 form-group">
                                            <label for="var-name-column" class="col-md-4 form-label text-left">Products & Price</label>
                                            <?php
                                            $rws = "";
                                            if ($productComboLines) {
                                                foreach ($productComboLines as $item) {
                                                    $option++;
                                                    $rws .= '<div class="row mb-2" id="inputFormRowLine' . $option . '">';
                                                    $rws .= '<div class="col-md-5">';
                                                    $rws .= '<select class="form-select select2 proTitleEdit duplicateCheckUpdate reqClass" name="pro_name_line[]" id="pro_name_line' . $option . '" data-id="' . $option . '" style="width:100%" data-parsley-required="true" data-parsley-required-message="Product Name is required." onchange="checkDuplicateProductUpdate("' . $option . '")">';
                                                    $rws .= '<option value ="">Select Product</option>';

                                                    if ($productdata) {
                                                        foreach ($productdata->result() as $product) {
                                                            $selected = $item->productCode == $product->code ? 'selected' : '';
                                                            $rws .= '<option value="' . $product->code . '"' . $selected . '>' . $product->productEngName . '</option>';
                                                        }
                                                    }
                                                    $rws .= '</select>  
												</div>
												<div class="col-md-3"><input class="form-control subTotalPriceEdit reqClass" placeholder="Price" id="pr_price_line' . $option . '" name="pr_price_line[]" type="number" required data-parsley-required-message="Required"  value="' . $item->productPrice . '">
												</div>
												<div class="col-md-3">
                                                        <input class="form-control productTaxEdit reqClass" onkeypress="return isNumber(event)" placeholder="Tax" id="tax_price_line' . $option . '" name="tax_price_line[]" type="text" data-parsley-required="true" value="' . $item->productTaxPrice . '">
                                                 </div>
												 <div class="col-md-1">
												 <input type="hidden" name="rowCode[]"  id="rowCode' . $option . '" value="' . $item->code . '">
												 <button type="button" class="btn btn-sm btn-danger" id="remove_option' . $option . '" onclick="delete_row(' . $option . ')"><i class="fa fa-trash"></i></button>
												</div>
											</div>';
                                                }
                                            }
                                            echo $rws;
                                            ?>
                                            <div id="add_row_line"></div>
                                            <div class="row mb-2" id="inputFormRowLine0">
                                                <div class="col-md-5">
                                                    <select class="form-select select2 proTitleEdit duplicateCheckUpdate" style="width:100%" name="pro_name_line[]" id="pro_name_line0" data-id="0" onchange="checkDuplicateProductUpdate(0)">
                                                        <option value="">Select Product</option>
                                                        <?php if ($productdata) {
                                                            foreach ($productdata->result() as $product) {
                                                                echo '<option value="' . $product->code . '" >' . $product->productEngName . '</option>';
                                                            }
                                                        } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3"><input class="form-control subTotalPriceEdit" placeholder="Price" id="pr_price_line0" name="pr_price_line[]" type="text"></div>
                                                <div class="col-md-3">
                                                    <input class="form-control productTaxEdit reqClass" onkeypress="return isNumber(event)" placeholder="Tax" id="tax_price_line0" name="tax_price_line[]" type="text">
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="button" class="btn btn-sm btn-success" id="add_option_edit" data-seq="0"><i class="fa fa-plus"></i></button>
                                                </div>
                                            </div>

                                        </div>


                                        <div class="col-md-12 col-12 form-group row">
                                            <div class="col-md-4">
                                                <input type="hidden" readonly id="optionLine" name="optionLine" value="<?= $option ?>">
                                            </div>
                                        </div>

                                    </div>


                                    <div class="row col-md-12 mb-2 col-12">
                                        <div class="col-md-4 col-12 form-group">
                                            <label for="pric-column" class="form-label">Total Price</label>
                                            <input type="text" id="price1" class="form-control" placeholder="Combo price" name="price1" value="<?= $productCombo->productComboPrice ?>" readonly>
                                        </div>
                                        <div class="col-md-4 col-12 form-group">
                                            <label for="pric-column" class="form-label">Total Tax Amount</label>
                                            <input type="text" id="totalTaxAmount" class="form-control reqClass" placeholder="Total Tax Amount" onkeypress="return isNumber(event)" name="totalTaxAmount" value="<?= $productCombo->taxAmount ?>" readonly>
                                        </div>
                                        <div class="col-md-4 col-12 form-group">
                                            <label for="pric-column" class="form-label">Final Amount</label>
                                            <input type="text" id="finalAmount" class="form-control reqClass" placeholder="Final Price" onkeypress="return isNumber(event)" name="finalAmount" value="<?= $productCombo->productFinalPrice ?>" readonly>
                                        </div>
                                    </div>



                                    <div class="row col-md-12 col-12">

                                        <div class="col-md-11 col-12 form-group row">
                                            <label for="var-name-column" class="form-label">Category</label>
                                            <div class="form-group mandatory">
                                                <select class="form-select select2" id="productcategory1" style="width:100%" name="productcategory1">
                                                    <option value="">Select</option>
                                                    <?php
                                                    if ($category) {
                                                        foreach ($category->result() as $cat) {
                                                            echo "<optgroup label='Category - " . $cat->categoryName . "'>";
                                                            if ($subcategory) {
                                                                foreach ($subcategory->result() as $sub) {
                                                                    if ($sub->categoryCode == $cat->code) {
                                                                        if ($productCombo->productCategoryCode == $sub->code) {
                                                                            echo "<option value='" . $sub->code . "' data-category-code='" . $cat->code . "' selected>" . $sub->subcategoryName . "</option>";
                                                                        } else {
                                                                            echo "<option value='" . $sub->code . "' data-category-code='" . $cat->code . "'>" . $sub->subcategoryName . "</option>";
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            echo "</optgroup>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-1 col-12">
                                            <div class="form-group text-center">
                                                <label for="status" class="form-label">Active</label>
                                                <div class="checkbox">
                                                    <input type="checkbox" <?= $productCombo->isActive ? "checked" : "" ?> name="isActive1" id="isActive" value="1" style="width:25px; height:25px">
                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                    <!--
                                    <?php
                                    $option = 0;
                                    ?>
                                    <div class="col-md-12 col-12">
                                        <div class="form-group">
                                            <label for="var-name-column" class="col-md-4 form-label text-left">Products & Price</label>
                                            <?php
                                            $rws = "";
                                            if ($productComboLines) {
                                                foreach ($productComboLines as $item) {
                                                    $option++;
                                                    $rws .= '<div class="row mb-2" id="inputFormRowLine' . $option . '">';
                                                    $rws .= '<div class="col-md-5">';
                                                    $rws .= '<select class="form-select select2 proTitleEdit duplicateCheckUpdate reqClass" name="pro_name_line[]" id="pro_name_line' . $option . '" data-id="' . $option . '" style="width:100%" data-parsley-required="true" data-parsley-required-message="Product Name is required." onchange="checkDuplicateProductUpdate("' . $option . '")">';
                                                    $rws .= '<option value ="">Select Product</option>';

                                                    if ($productdata) {
                                                        foreach ($productdata->result() as $product) {
                                                            $selected = $item->productCode == $product->code ? 'selected' : '';
                                                            $rws .= '<option value="' . $product->code . '"' . $selected . '>' . $product->productEngName . '</option>';
                                                        }
                                                    }
                                                    $rws .= '</select>  
												</div>
												<div class="col-md-3"><input class="form-control subTotalPriceEdit reqClass" placeholder="Price" id="pr_price_line' . $option . '" name="pr_price_line[]" type="number" required data-parsley-required-message="Required"  value="' . $item->productPrice . '">
												</div>
												<div class="col-md-3">
                                                        <input class="form-control productTaxEdit reqClass" onkeypress="return isNumber(event)" placeholder="Tax" id="tax_price_line' . $option . '" name="tax_price_line[]" type="text" data-parsley-required="true" value="' . $item->productTaxPrice . '">
                                                 </div>
												 <div class="col-md-1">
												 <input type="hidden" name="rowCode[]"  id="rowCode' . $option . '" value="' . $item->code . '">
												 <button type="button" class="btn btn-sm btn-danger" id="remove_option' . $option . '" onclick="delete_row(' . $option . ')"><i class="fa fa-trash"></i></button>
												</div>
											</div>';
                                                }
                                            }
                                            echo $rws;
                                            ?>
                                            <div id="add_row_line"></div>
                                            <div class="row mb-2" id="inputFormRowLine0">
                                                <div class="col-md-5">
                                                    <select class="form-select select2 proTitleEdit duplicateCheckUpdate" style="width:100%" name="pro_name_line[]" id="pro_name_line0" data-id="0" onchange="checkDuplicateProductUpdate(0)">
                                                        <option value="">Select Product</option>
                                                        <?php if ($productdata) {
                                                            foreach ($productdata->result() as $product) {
                                                                echo '<option value="' . $product->code . '" >' . $product->productEngName . '</option>';
                                                            }
                                                        } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3"><input class="form-control subTotalPriceEdit" placeholder="Price" id="pr_price_line0" name="pr_price_line[]" type="text"></div>
                                                <div class="col-md-3">
                                                    <input class="form-control productTaxEdit reqClass" onkeypress="return isNumber(event)" placeholder="Tax" id="tax_price_line0" name="tax_price_line[]" type="text">
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="button" class="btn btn-sm btn-success" id="add_option_edit" data-seq="0"><i class="fa fa-plus"></i></button>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                <input type="hidden" readonly id="optionLine" name="optionLine" value="<?= $option ?>">


                                            </div>
                                        </div>
                                    </div>
                                                    -->



                                    <div class="justify-content-center items-center col-md-12 col-12">


                                        <div class="col-md-6 col-6">
                                            <?php if ($productCombo->productComboImage != "") { ?>
                                                <img width="100" height="150" src="<?= base_url() . $productCombo->productComboImage ?>" data-src="">
                                            <?php } else { ?>
                                                <img width="100" height="150" src="/assets/images/faces/default-img.jpg" data-src="">
                                            <?php } ?>
                                        </div>

                                        <div class="col-md-6 col-12 form-group" id="file_uploadDiv">
                                            <label for="productImage" class="form-label">Product Image :</label>
                                            <input type="file" id="productComboImage" class="form-control" name="productComboImage">
                                        </div>

                                    </div>

                                </div>


                                <div class="row">
                                    <div class="col-12 d-flex justify-content-end">
                                        <input type="hidden" class="form-control" id="productComboCode1" name="productComboCode1" value="1">
                                        <?php if ($updateRights == 1) { ?>
                                            <button type="button" class="btn btn-primary" id="updateProductCombo">Update</button>
                                        <?php } ?>
                                        <button type="reset" class="btn btn-light-secondary" data-bs-dismiss="modal" id="closeProductCombo">Reset</button>
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
    google.load("elements", "1", {
        packages: "transliteration"
    });

    function onLoadEdit() {
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

        var control1 = new google.elements.transliteration.TransliterationControl(options);
        control1.makeTransliteratable(['c_hindiname']);
        var controlUrdu1 = new google.elements.transliteration.TransliterationControl(optionsUrdu);
        controlUrdu1.makeTransliteratable(['c_urduname']);
        var controlArabic1 = new google.elements.transliteration.TransliterationControl(optionsArabic);
        controlArabic1.makeTransliteratable(['c_arabicname']);
        // var keyVal = 32; // Space key
    }
    google.setOnLoadCallback(onLoadEdit);
    $(document).ready(function() {
        $('.cancel').removeClass('btn-default').addClass('btn-info');

        $("body").on("click", "#add_option_edit", function(e) {
            e.preventDefault();
            var sum = 0;
            var option_count = $("#optionLine").val();
            var pro_name = $('#pro_name_line0').val();
            var pr_price = $('#pr_price_line0').val();
            var tax_price = $('#tax_price_line0').val();
            var row = $(this).data('seq');
            if ($('#pro_name_line' + row).val() != "" && $('#pr_price_line' + row).val() != "" && $('#tax_price_line' + row).val() != "") {} else {
                toastr.error('Please provide all the details', 'Products Combo', {
                    "progressBar": false
                });
                return;
            }
            option_count++;
            $.ajax({
                url: base_path + 'index.php/ProductCombo/getProductData',
                type: 'post',
                success: function(response) {
                    var res = JSON.parse(response);
                    if (res.status == 'true') {
                        var html = `<div class="row mb-2" id="inputFormRowLine${option_count}">
                                <div class="col-md-5">
                                    <select id="pro_name_line${option_count}" name="pro_name_line[]" class="form-select proTitleEdit duplicateCheckUpdate select2" data-id="${option_count}" data-seq="${option_count}" data-parsley-required="true" data-parsley-required-message="Product Name is required." onchange="checkDuplicateProductUpdate(${option_count})" ></select>
                            </div>
                                <div class="col-md-3">
                                    <input class="form-control subTotalPriceEdit" id="pr_price_line${option_count}" placeholder="Price" name="pr_price_line[]" type="text" value="${pr_price}"> 
                                </div>
								  <div class="col-md-3">
                                             <input class="form-control productTaxEdit reqClass" onkeypress="return isNumber(event)" placeholder="Tax" id="tax_price_line${option_count}" name="tax_price_line[]" type="text" data-parsley-required="true" value="${tax_price}">
                                   </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-sm btn-danger" id="remove_option${option_count}" onclick="remove_option_line(${option_count});"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>`;
                        $("input#optionLine").val(option_count);
                        $("div#add_row_line").append(html);
                        $('#productComboEditForm').parsley().refresh();
                        $('select#pro_name_line' + option_count).append(res.products);
                        $('select#pro_name_line' + option_count).val(pro_name);
                        $('.select2').select2({
                            tags: true,
                            width: '100%'
                        });
                        $("#pro_name_line0").val(null).trigger('change.select2');
                        $('#pr_price_line0').val('');
                        $('#tax_price_line0').val('');
                    } else {
                        toastr.error("Something went Wrong.", 'Product', {
                            "progressBar": true
                        });
                    }
                }
            });
        });


        $("body").on('change', "select.proTitleEdit", function(e) {
            var sum = 0;
            var taxSum = 0;
            var id = $(this).data('id');
            var code = $(this).val();
            var url = base_path + 'index.php/ProductCombo/getPrice';
            $.ajax({
                url: url,
                method: "POST",
                data: {
                    'code': code
                },
                success: function(response) {

                    var res = JSON.parse(response);
                    if (res.status == 'true') {

                        $("#pr_price_line" + id).val(res.price);
                        $("#tax_price_line" + id).val(res.taxprice);
                        $('.subTotalPriceEdit').each(function() {
                            sum += Number($(this).val());
                        });
                        $('.productTaxEdit').each(function() {
                            taxSum += Number($(this).val());
                        });
                        $("input#price1").val(sum);
                        $("#totalTaxAmount").val(taxSum);
                        $("#finalAmount").val(sum + taxSum);

                    }
                }
            });

        });
        $("body").on('change', "input.subTotalPriceEdit", function(e) {
            var sum = 0;
            var taxSum = 0;
            $('.subTotalPriceEdit').each(function() {
                sum += Number($(this).val());
            });
            $('.productTaxEdit').each(function() {
                taxSum += Number($(this).val());
            });
            $("#price1").val(sum);
            $("#totalTaxAmount").val(taxSum);
            $("#finalAmount").val(sum + taxSum);

        });

        $("body").on('change', "input.productTaxEdit", function(e) {
            var sum = 0;
            var taxSum = 0;
            $('.subTotalPriceEdit').each(function() {
                sum += Number($(this).val());
            });
            $('.productTaxEdit').each(function() {
                taxSum += Number($(this).val());
            });
            $("input#price1").val(sum);
            $("input#totalTaxAmount").val(taxSum);
            $("input#finalAmount").val(sum + taxSum);

        });

        $(document).on("click", "button#updateProductCombo", function(e) {
            $('#productComboEditForm').parsley();
            const form = document.getElementById('productComboEditForm');
            var formData = new FormData(form);
            var isValid = true;
            event.preventDefault();
            $("#productComboEditForm .reqClass").each(function(e) {
                if ($(this).parsley().validate() !== true) isValid = false;
            });

            if (isValid) {

                var isActive = 0;
                if ($("#isActive").is(':checked')) {
                    isActive = 1;
                }
                formData.append('isActive', isActive);
                $.ajax({
                    url: base_path + "ProductCombo/update",
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#updateProductCombo').prop('disabled', true);
                        $('#updateProductCombo').text('Please wait..');
                        $('#closeProductCombo').prop('disabled', true);
                    },

                    success: function(response) {
                        $('#updateProductCombo').prop('disabled', false);
                        if ($('#ccode').val() != '') {
                            $('#updateProductCombo').text('Update');
                        } else {
                            $('#updateProductCombo').text('Save');
                        }
                        $('#closeProductCombo').prop('disabled', false);
                        var obj = JSON.parse(response);
                        if (obj.status) {
                            toastr.success(obj.message, 'Product Combo', {
                                "progressBar": true
                            });

                            window.location.href = base_path + 'index.php/ProductCombo/listRecords';
                        } else {
                            toastr.error(obj.message, 'Product Combo', {
                                "progressBar": true
                            });
                        }
                    }
                });
            } else {
                console.log("failed");
            }
        });


    });

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    function delete_row(id) {
        var sum = 0;
        var taxSum = 0;
        var lineCode = $("#rowCode" + id).val();

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
                    url: base_path + "ProductCombo/deleteProductComboItem",
                    type: 'POST',
                    data: {
                        'lineCode': lineCode,
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
                                        $("div#inputFormRowLine" + id).remove();
                                        $('.subTotalPriceEdit').each(function() {
                                            sum += Number($(this).val());
                                        });
                                        $('.productTaxEdit').each(function() {
                                            taxSum += Number($(this).val());
                                        });
                                        $("#price1").val(sum);
                                        $("#totalTaxAmount").val(taxSum);
                                        $("#finalAmount").val(sum + taxSum);
                                    }
                                });
                        } else {
                            toastr.success('Record Not Deleted', 'Failed', {
                                "progressBar": true
                            });
                        }
                    }
                });
            } else {
                swal.close();
            }
        });
    }

    function remove_option_line(op) {
        var sum = 0;
        var taxSum = 0;
        //var counter = $("#optionLine").val();
        var counter = op;
        if (counter == 0) {
            alert("Not allowed");
            return false;
        }
        //counter--;
        $("div#inputFormRowLine" + counter).remove();
        counter = counter - 1
        //$("#optionLine").val(counter);

        $('.subTotalPriceEdit').each(function() {
            sum += Number($(this).val());
        });
        $('.productTaxEdit').each(function() {
            taxSum += Number($(this).val());
        });
        $("#price1").val(sum);
        $("#totalTaxAmount").val(taxSum);
        $("#finalAmount").val(sum + taxSum);
    }

    function checkDuplicateProductUpdate(id) {
        var pro_name = $('#pro_name_line' + id).val();
        $(".duplicateCheckUpdate").each(function() {
            var pro_name_row = $(this).val();
            var row_id = $(this).data('id');
            if (pro_name == pro_name_row && row_id != id) {
                toastr.error('Product already exists..Please select another..', 'Product Combo', {
                    "progressBar": true
                });
                $("#pro_name_line" + id).val(null).trigger('change.select2');
                $('#pr_price_line' + id).val('');
                $('#pro_name_line' + id).focus();
                $('#productComboEditForm').parsley().destroy();
                return false;
            }
        });
    }
</script>