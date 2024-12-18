<div id="main-content">

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Product Combos/Meals</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Product Combos/Meals</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <?php if ($insertRights == 1) { ?> 
            <div id="maindiv" class="container">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                        <div class="floating-action-button">
                            <a id="add_category" href="#generl_modal" data-bs-toggle="modal" data-bs-target="#generl_modal">
                                <i class="fa fa-plus-circle cursor_pointer"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="row">
            <section class="section col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">

                            <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                                <h5>Product Combos/Meals List</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-responsive" id="datatable-ProductCombo">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Code</th>
                                    <th>Combos/Meals Name</th>
                                    <th>Subcategory Name</th>
                                    <th>Combos/Meals Price</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="generl_modal" tabindex="-1" role="dialog" aria-labelledby="29" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modal-label">Add Combos/Meals</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body">
                                <form id="productComboAddForm" class="form" data-parsley-validate>
                                    <div class="row">

                                        <div class="row col-md-12 col-12">
                                            <div class="col-md-6 col-12 form-group mandatory">
                                                <label for="var-name-column" class="form-label">English Name</label>
                                                <input type="text" id="cname" class="form-control reqClass" placeholder="Combos/Meals English Name" name="cname" data-parsley-required="true" data-parsley-required-message="Product Combo Name is required.">

                                            </div>
                                            <div class="col-md-6 col-12 form-group mandatory">
                                                <label for="var-name-column" class="form-label">Arabic Name</label>
                                                <input type="text" id="carabicname" class="form-control reqClass" placeholder="Combos/Meals Arabic Name" name="carabicname" data-parsley-required="true" data-parsley-required-message="Product Combo Name is required.">
                                            </div>
                                        </div>


                                        <div class="row col-md-12 mb-3 col-12">

                                            <div class="col-md-6 col-12 form-group mandatory">
                                                <label for="var-name-column">Hindi Name</label>
                                                <input type="text" id="chindiname" class="form-control reqClass" placeholder="Combos/Meals Hindi Name" name="chindiname" maxlength="150">
                                            </div>
                                            <div class="col-md-6 col-12 form-group mandatory">
                                                <label for="var-name-column">Urdu Name</label>
                                                <input type="text" id="curduname" class="form-control reqClass" placeholder="Combos/Meals Urdu Name" name="curduname" maxlength="150">
                                            </div>
                                            
                                        </div>






                                        <div class="col-md-12 col-12">
                                            <div class="form-group mandatory">
                                                <label for="var-name-column" class="col-md-4 form-label text-left">Products & Price</label>
                                                <div id="add_row"></div>
                                                <div class="col-md-12 row mb-2" id="inputFormRow">
                                                    <div class="col-md-5">
                                                        <!--<input class="form-control" placeholder="Product Name" id="pro_name<?= $option ?>" name="pro_name[]" type="text" data-parsley-required="true">-->
                                                        <select class="form-select proTitle select2" name="pro_name[]" id="pro_name<?= $option ?>" style="width:100%" data-id="<?= $option ?>" data-parsley-required="true" onchange="checkDuplicateProduct(<?= $option ?>)" data-parsley-required-message="Product Name is required.">
                                                            <option value="">Select Product</option>
                                                            <?php if ($productdata) {
                                                                foreach ($productdata->result() as $product) {
                                                                    echo '<option value="' . $product->code . '">' . $product->productEngName . '</option>';
                                                                }
                                                            } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input class="form-control subTotalPrice" onkeypress="return isNumber(event)" placeholder="Price" id="pr_price<?= $option ?>" name="pr_price[]" type="text" data-parsley-required="true">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input class="form-control productTax" onkeypress="return isNumber(event)" placeholder="Tax" id="tax_price<?= $option ?>" name="tax_price[]" type="text" data-parsley-required="true">
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button type="button" class="btn btn-sm btn-success" id="add_option" data-seq="<?= $option ?>"><i class="fa fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <input type="hidden" readonly id="option" name="option" value="<?= $option ?>">
                                                </div>
                                            </div>
                                        </div>





                                        <div class="row mb-2 col-md-12 col-12">

                                            <div class="col-md-4 col-12 form-group">
                                                <label for="pric-column" class="form-label">Sub Total</label>
                                                <input type="text" id="price" class="form-control reqClass" placeholder="Total price" onkeypress="return isNumber(event)" name="price" readonly>
                                            </div>
                                            <div class="col-md-4 col-12 form-group">
                                                <label for="pric-column" class="form-label">Total Tax Amount</label>
                                                <input type="text" id="totalTaxAmount" class="form-control reqClass" placeholder="Total Tax Amount" onkeypress="return isNumber(event)" name="totalTaxAmount" readonly>
                                            </div>
                                            <div class="col-md-4 col-12 form-group">
                                                <label for="pric-column" class="form-label">Final Amount</label>
                                                <input type="text" id="finalAmount" class="form-control reqClass" placeholder="Final Price" onkeypress="return isNumber(event)" name="finalAmount" readonly>
                                            </div>

                                        </div>


                                        <div class="row mb-2 col-md-12 col-12">

                                            <div class="col-md-11 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="var-name-column" class="form-label">Category</label>
                                                    <select class="form-select select2" id="productcategory" style="width:100%" name="productcategory" data-parsley-required="true" data-parsley-required-message="Product Category is required.">
                                                        <option>Select Category</option>
                                                        <?php
                                                        if ($category) {
                                                            foreach ($category->result() as $cat) {
                                                                echo "<optgroup label='Category - " . $cat->categoryName . "'>";
                                                                if ($subcategory) {
                                                                    foreach ($subcategory->result() as $sub) {
                                                                        if ($sub->categoryCode == $cat->code) {
                                                                            echo "<option value='" . $sub->code . "' data-category-code='" . $cat->code . "'>" . $sub->subcategoryName . "</option>";
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

                                            <div class="col-md-1 col-6">
                                                <div class="form-group row text-center">
                                                    <label for="status" class="form-label">Active</label>
                                                    <div class="checkbox">
                                                        <input type="checkbox" name="isActive" id="isActive" checked style="width:25px; height:25px">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>



                                        <?php
                                        $option = 0;
                                        ?>









                                        <div class="col-md-4 col-12" id="file_uploadDiv">
                                            <div class="form-group">
                                                <label for="productImage" class="form-label">Combo Image :</label>
                                                <input type="file" id="productComboImage" class="form-control" name="productComboImage">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <input type="hidden" class="form-control" id="productComboCode" name="productComboCode">
                                            <button type="button" class="btn btn-primary" id="saveProductCombo">Save</button>
                                            <button id="cancelRecipeBtn" type="button" class="btn btn-light-secondary" data-bs-dismiss="modal" id="closeProductCombo">Close</button>
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

<div class="modal fade text-left" id="generl_modal1" tabindex="-1" role="dialog" aria-labelledby="30" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Update Combos/Meals</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body panel-body1">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="generl_modal2" tabindex="-1" role="dialog" aria-labelledby="31" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5>View Combos/Meals</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body panel-body2">
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
        control.makeTransliteratable(['chindiname']);
        var controlUrdu = new google.elements.transliteration.TransliterationControl(optionsUrdu);
        controlUrdu.makeTransliteratable(['curduname']);
        var controlArabic = new google.elements.transliteration.TransliterationControl(optionsArabic);
        controlArabic.makeTransliteratable(['carabicname']);

        // var keyVal = 32; // Space key
    }
    google.setOnLoadCallback(onLoad);

    function onLoadEdit() {
        console.log("called");
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
    $(document).ready(function() {
        $('.cancel').removeClass('btn-default').addClass('btn-info');
        loadProductComboTable();
        $('.select2').select2({
            dropdownParent: $('#generl_modal')
        });
        $("body").on("click", "#add_option", function(e) {
            e.preventDefault();
            var option = $('#option').val();
            var row = $(this).data('seq');
            var pro_name = $('#pro_name0').val();
            var pr_price = $('#pr_price0').val();
            var tax_price = $('#tax_price0').val();
            var sum = 0;

            if ($('#pro_name' + row).val() != "" && $('#pr_price' + row).val() != "" && $('#tax_price0').val() != "") {} else {
                toastr.error('Please provide all the details', 'Products Combo', {
                    "progressBar": false
                });
                return;
            }
            $.ajax({
                url: base_path + 'index.php/ProductCombo/getProductData',
                type: 'post',
                success: function(response) {
                    var res = JSON.parse(response);
                    if (res.status == 'true') {
                        var option_count = $("#option").val();
                        option_count++;
                        var html = `<div class="col-md-12 row mb-2" id="inputFormRow${option_count}">
                                <div class="col-md-5">
                                    <select id="pro_name${option_count}" name="pro_name[]" class="form-select proTitle duplicateCheck select2" data-id="${option_count}" data-seq="${option_count}"  value="${pro_name}" data-parsley-required="true" data-parsley-required-message="Product Name is required." onchange="checkDuplicateProduct(${option_count})" style="width:100%"></select>
                            </div>
                                <div class="col-md-3">
                                    <input class="form-control subTotalPrice" onkeypress="return isNumber(event)" id="pr_price${option_count}" placeholder="Price" name="pr_price[]" type="text"  value="${pr_price}"> 
                                </div>
								<div class="col-md-3">
                                       <input class="form-control productTax" onkeypress="return isNumber(event)" placeholder="Tax" id="tax_price${option_count}" name="tax_price[]" type="text" value="${tax_price}">
                                 </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-sm btn-danger" id="remove_option" data-seq="${option_count}"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>`;
                        $("#option").val(option_count);
                        $("#add_row").append(html);
                        $('#productComboAddForm').parsley().refresh();
                        $('select#pro_name' + option_count).append(res.products);
                        $('select#pro_name' + option_count).val(pro_name);
                        //$('#pro_name0').val('');
                        $("#pro_name0").val(null).trigger('change.select2');
                        $('#pr_price0').val('');
                        $('#tax_price0').val('');
                    } else {
                        toastr.error("Something went Wrong.", 'Product', {
                            "progressBar": true
                        });
                    }
                }
            });
        });

        $("body").on('change', "select.proTitle", function(e) {
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

                        $("#pr_price" + id).val(res.price);
                        $("#tax_price" + id).val(res.taxprice);
                        $('.subTotalPrice').each(function() {
                            sum += Number($(this).val());
                        });
                        $('.productTax').each(function() {
                            taxSum += Number($(this).val());
                        });
                        $("#price").val(sum);
                        $("#totalTaxAmount").val(taxSum);
                        $("#finalAmount").val(sum + taxSum);

                    }
                }
            });

        });

        $("body").on('change', "input.subTotalPrice", function(e) {
            var sum = 0;
            var taxSum = 0;
            $('.subTotalPrice').each(function() {
                sum += Number($(this).val());
            });
            $('.productTax').each(function() {
                taxSum += Number($(this).val());
            });
            $("input#price").val(sum);
            $("input#totalTaxAmount").val(taxSum);
            $("input#finalAmount").val(sum + taxSum);

        });

        $("body").on('change', "input.productTax", function(e) {
            var sum = 0;
            var taxSum = 0;
            $('.subTotalPrice').each(function() {
                sum += Number($(this).val());
            });
            $('.productTax').each(function() {
                taxSum += Number($(this).val());
            });
            $("input#price").val(sum);
            $("input#totalTaxAmount").val(taxSum);
            $("input#finalAmount").val(sum + taxSum);

        });

        $("body").on("click", "#remove_option", function(e) {
            var sum = 0;
            var taxSum = 0;
            var opt = $(this).data('seq');
            //var counter = $("#option").val();
            var counter = opt;

            if (counter == 0) {
                alert("Not allowed");
                return false;
            }
            //counter--;

            $("#inputFormRow" + counter).remove();
            counter = counter - 1
            $("#option").val(counter);

            $('.subTotalPrice').each(function() {
                sum += Number($(this).val());
            });
            $('.productTax').each(function() {
                taxSum += Number($(this).val());
            });
            $("input#price").val(sum);
            $("input#totalTaxAmount").val(taxSum);
            $("input#finalAmount").val(sum + taxSum);
        });


        $(document).on("click", "button#saveProductCombo", function(e) {
            event.preventDefault();
            $('#productComboAddForm').parsley();
            const form = document.getElementById('productComboAddForm');
            var formData = new FormData(form);
            var isValid = true;

            $("#productComboAddForm .reqClass").each(function(e) {
                if ($(this).parsley().validate() !== true) isValid = false;
            });

            var pro_name = $('#pro_name0').val();
            var pr_price = $('#pr_price0').val();
            if (pro_name != "" && pr_price != "") {} else {
                toastr.error('Please provide all the details', 'Products Combo', {
                    "progressBar": false
                });
                return;
            }

            if (isValid) {
                /*var productName= $("input[name='pro_name[]']").val();
                var productName = $("input[name='pro_name[]']").map(function() {
                	return $(this).val();
                }).get();
                if(productName.length>=2){*/
                var isActive = 0;
                if ($("#isActive").is(':checked')) {
                    isActive = 1;
                }
                formData.append('isActive', isActive);
                $.ajax({
                    url: base_path + "ProductCombo/save",
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#saveProductCombo').prop('disabled', true);
                        $('#saveProductCombo').text('Please wait..');
                        $('#closeProductCombo').prop('disabled', true);
                    },
                    success: function(response) {
                        $('#saveProductCombo').prop('disabled', false);
                        /*if ($('#productComboCode').val() != '') {
                        	$('#saveProductCombo').text('Update');
                        } else {*/
                        $('#saveProductCombo').text('Save');
                        //}
                        $('#closeProductCombo').prop('disabled', false);
                        var obj = JSON.parse(response);
                        if (obj.status) {
                            toastr.success(obj.message, 'Product Combo', {
                                "progressBar": true,
                                onHidden: function() {
                                    window.location.reload();
                                }
                            });
                            //loadProductComboTable();

                        } else {
                            toastr.error(obj.message, 'Product Combo', {
                                "progressBar": true
                            });
                        }
                    }

                });
                console.log('complete');
                /*} else {
					 toastr.error('Product Combo must have atleast two products present', 'Product Combo', {
                        "progressBar": true
                    });
				}*/
            } else {
                console.log('complete');
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


    function save_productcombo() {
        var productComboCode = $('#productComboCode').val();
        var productComboName = $('#cname').val();
        var productCategory = $('#productcategory').val();
        var productComboPrice = $('#price').val();
        var productName = $("input[name='pro_name[]']").map(function() {
            return $(this).val();
        }).get();
        var productPrice = $("input[name='pr_price[]']").map(function() {
            return $(this).val();
        }).get();
        var isActive = 0;

        if ($("#isActive").is(':checked')) {
            isActive = 1;
        }
        if (productName.length >= 2) {
            $.ajax({
                url: base_path + "ProductCombo/save",
                type: 'POST',
                data: {
                    'productComboName': productComboName,
                    'productCategory': productCategory,
                    'productComboPrice': productComboPrice,
                    'productComboCode': productComboCode,
                    'productName': productName,
                    'productPrice': productPrice,
                    'isActive': isActive
                },
                beforeSend: function() {
                    $('#saveProductCombo').prop('disabled', true);
                    $('#saveProductCombo').text('Please wait..');
                    $('#closeProductCombo').prop('disabled', true);
                },
                success: function(response) {
                    $('#saveProductCombo').prop('disabled', false);
                    if (productComboCode != '') {
                        $('#saveProductCombo').text('Update');
                    } else {
                        $('#saveProductCombo').text('Save');
                    }
                    $('#closeProductCombo').prop('disabled', false);
                    var obj = JSON.parse(response);
                    if (obj.status) {
                        toastr.success(obj.message, 'Product Combo', {
                            "progressBar": true,
                            onHidden: function() {
                                window.location.reload();
                            }
                        });
                    } else {
                        toastr.error(obj.message, 'Product Combo', {
                            "progressBar": true
                        });
                    }
                }
            });
        } else {
            toastr.error('Product Combo must have atleast two products present', 'Product Combo', {
                "progressBar": true
            });
        }
    }

    function loadProductComboTable() {
        if ($.fn.DataTable.isDataTable("#datatable-ProductCombo")) {
            $('#datatable-ProductCombo').DataTable().clear().destroy();
        }
        var dataTable = $('#datatable-ProductCombo').DataTable({
            stateSave: true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "searching": true,
            "ajax": {
                url: base_path + "ProductCombo/getProductComboList",
                type: "GET",
                "complete": function(response) {
                    /*$('.edit_group').click(function() {
                        var code = $(this).data('seq');
                        $.ajax({
                            url: base_path + "ProductCombo/editProductCombo",
                            type: 'POST',
                            data: {
                                'code': code,
                            },
                            success: function(response) {
                                $('#generl_modal1').modal('show');
                                $(".panel-body1").html(response);
                                $('.select2').select2({
                                    dropdownParent: $('#generl_modal1')
                                });
								    google.setOnLoadCallback(onLoadEdit);
                            }
                        });
                    });*/
                    $('.view_group').click(function() {
                        var code = $(this).data('seq');
                        $.ajax({
                            url: base_path + "ProductCombo/viewProductCombo",
                            type: 'POST',
                            data: {
                                'code': code,
                            },
                            success: function(response) {
                                $('#generl_modal2').modal('show');
                                $(".panel-body2").html(response);
                            }
                        });
                    });
                    $('.delete_group').on("click", function() {
                        var code = $(this).data('seq');
                        swal({
                            title: "You want to delete table " + code,
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
                                    url: base_path + "ProductCombo/deleteProductCombo",
                                    type: 'POST',
                                    data: {
                                        'code': code
                                    },
                                    beforeSend: function() {

                                    },
                                    success: function(data) {
                                        swal.close();
                                        if (data) {
                                            toastr.success('Product Combo deleted successfully', 'Product Combo', {
                                                "progressBar": true
                                            });
                                            loadProductComboTable();
                                        } else {
                                            toastr.error('Product Combo not deleted', 'Product Combo', {
                                                "progressBar": true
                                            });
                                            loadProductComboTable();
                                        }
                                    }
                                });
                            } else {
                                swal.close()
                            }
                        });
                    });
                }
            }
        });
    }

    function checkDuplicateProduct(id) {
        var pro_name = $('#pro_name' + id).val()
        $(".duplicateCheck").each(function() {
            var pro_name_row = $(this).val();
            if (pro_name == pro_name_row) {
                toastr.error('Product already exists..Please select another..', 'Product Combo', {
                    "progressBar": true
                });
                $("#pro_name" + id).val(null).trigger('change.select2');
                $('#pr_price' + id).val('');
                $('#pro_name' + id).focus();
                $('#productComboAddForm').parsley().destroy();
                return false;
            }
        });
    }
</script>