<?php include '../restaurant/config.php'; ?>

<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3><?php echo $translations['Item']?></h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../Dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Item</li>
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
                                <h5><?php echo $translations['Item List']?></h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" id="datatableItem">
                            <thead>
                                <tr>
                                    <th><?php echo $translations['Sr No']?></th>
                                    <th><?php echo $translations['Code']?></th>
                                    <th><?php echo $translations['Name']?></th>
                                    <th><?php echo $translations['Category']?></th>
                                    <th><?php echo $translations['Storage Unit']?></th>
                                    <th><?php echo $translations['Price']?></th>
                                    <th><?php echo $translations['Status']?></th>
                                    <th><?php echo $translations['Action']?></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>


<div class="modal fade text-left" id="generl_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content mb-5">
            <div class="modal-header">
                <h5><?php echo $translations['Add Item']?></h5>
            </div>
            <div class="modal-body">
                <form id="itemForm" class="form" data-parsley-validate>
                    <div class="row">


                        <div class="row col-md-12 mb-3 col-12">

                            <div class="form-group col-md-6 col-12 mandatory">
                                <label for="category-name-column" class="form-label text-left"><?php echo $translations['Item English Name']?></label>
                                <input type="text" id="itemName" class="form-control" placeholder="Enter Name" name="itemName" required data-parsley-required-message="Item Name is required">
                            </div>

                            <div class="form-group col-md-6 col-12 mandatory">
                                <label for="category-name-column" class="form-label text-left"><?php echo $translations['Category']?></label>
                                <select class="form-control" style="width:100%" name="category" id="category" required data-parsley-required-message="Category is required">
                                </select>
                            </div>

                        </div>


                        <div class="row col-md-12 mb-3 col-12">

                            <div class="form-group col-md-6 col-12 mandatory">
                                <label for="category-name-column" class="form-label text-left"><?php echo $translations['Storage Unit']?></label>
                                <select class="form-control" name="itemUnit" style="width:100%" id="itemUnit" required data-parsley-required-message="Storage Unit is required">
                                </select>
                            </div>

                            <div class="form-group col-md-6 col-12 mandatory">
                                <label for="category-name-column" class="form-label text-left"><?php echo $translations['Ingredient Unit']?></label>
                                <select class="form-control" name="ingredientUnit" style="width:100%" id="ingredientUnit" required data-parsley-required-message="Ingredient Unit is required">
                                </select>
                            </div>

                        </div>


                        <div class="row col-md-12 mb-3 col-12">

                            <div class="form-group col-md-6 col-12 mandatory">
                                <label for="category-name-column" class="form-label text-left"><?php echo $translations['Ingredient Factor']?></label>
                                <input type="text" id="ingredientFactor" class="form-control" placeholder="Enter Ingredient Factor" name="ingredientFactor" required data-parsley-required-message="Ingredient factor is required" onkeypress="return isNumber(event)">
                            </div>

                            <div class="form-group col-md-6 col-12 mandatory">
                                <label for="category-name-column" class="form-label text-left"><?php echo $translations['Item Price']?></label>
                                <input type="text" id="itemPrice" class="form-control" placeholder="Enter Item Price" name="itemPrice" required data-parsley-required-message="Item Price is required" onkeypress="return isNumber(event)">
                            </div>

                        </div>


                        <div class="row col-md-12 mb-3 col-12">

                            <div class="form-group col-md-6 col-12">
                                <label for="category-name-column" class="form-label text-left"><?php echo $translations['Item Arabic Name']?></label>
                                <input type="text" id="itemArbName" class="form-control" name="itemArbName" value="">
                            </div>

                            <div class="form-group col-md-6 col-12">
                                <label for="category-name-column" class="form-label text-left"><?php echo $translations['Item Hindi Name']?></label>
                                <input type="text" id="itemHinName" class="form-control" name="itemHinName" value="">
                            </div>

                        </div>

                        <div class="row col-md-12 mb-3 col-12">
                            <div class="form-group col-md-12 mb-3 col-12">
                                <label for="category-name-column" class="form-label text-left"><?php echo $translations['Item Urdu Name']?></label>
                                <input type="text" id="itemUrduName" class="form-control" name="itemUrduName" value="">
                            </div>
                        </div>


                        <div class="row col-md-12 mb-3 col-12">

                            <div class="form-group col-md-6 col-12">
                                <label for="category-name-column" class="form-label text-left"><?php echo $translations['Item Arabic Description']?></label>
                                <textarea rows="4" id="itemArbDesc" class="form-control" name="itemArbDesc"></textarea>
                            </div>


                            <div class="form-group col-md-6 col-12">
                                <label for="category-name-column" class="form-label text-left"><?php echo $translations['Item English Description']?></label>
                                <textarea type="text" id="itemDesc" class="form-control" rows="4" name="itemDesc"></textarea>
                            </div>

                        </div>


                        <div class="row col-md-12 mb-3 col-12">

                            <div class="form-group col-md-6 col-12">
                                <label for="category-name-column" class="form-label text-left"><?php echo $translations['Item Hindi Description']?></label>
                                <textarea rows="4" type="text" id="itemHinDesc" class="form-control" name="itemHinDesc"></textarea>
                            </div>


                            <div class="form-group col-md-6 col-12">
                                <label for="category-name-column" class="form-label text-left"><?php echo $translations['Item Urdu Description']?></label>
                                <textarea rows="4" type="text" id="itemUrduDesc" class="form-control" name="itemUrduDesc"><?= $br->itemUrduDesc ?></textarea>
                            </div>

                        </div>



                        <div class="form-group d-flex col-md-12 col-12 text-center items-center justify-content-center row">
                            <label for="status" class="form-label col-sm-2"><?php echo $translations['Active']?></label>
                            <!--<div class="col-sm-9 checkbox">-->
                            <input type="checkbox" name="isActive" id="isActive" checked class=" " style="width:25px; height:25px">
                            <!--</div>-->
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary" id="saveItemBtn"><?php echo $translations['Save']?></button>
                            <button type="button" class="btn btn-light-secondary" id="closeItemBtn" data-bs-dismiss="modal"><?php echo $translations['Close']?></button>
                        </div>
                    </div>
                </form>
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
        control.makeTransliteratable(['itemHinName']);
        control.makeTransliteratable(['itemHinDesc']);
        var controlUrdu = new google.elements.transliteration.TransliterationControl(optionsUrdu);
        controlUrdu.makeTransliteratable(['itemUrduName']);
        controlUrdu.makeTransliteratable(['itemUrduDesc']);
        var controlArabic = new google.elements.transliteration.TransliterationControl(optionsArabic);
        controlArabic.makeTransliteratable(['itemArbName']);
        controlArabic.makeTransliteratable(['itemArbDesc']);
    }

    google.setOnLoadCallback(onLoad);

    $(document).ready(function() {
        $('.cancel').removeClass('btn-default').addClass('btn-info');
        loadTable();
        $("#category").select2({
            placeholder: "Select Category",
            allowClear: true,
            dropdownParent: $('#generl_modal .modal-content'),
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

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    function loadTable() {
        if ($.fn.DataTable.isDataTable("#datatableItem")) {
            $('#datatableItem').DataTable().clear().destroy();
        }
        var dataTable = $('#datatableItem').DataTable({
            stateSave: true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "searching": true,
            "ajax": {
                url: base_path + "item/getItemList",
                type: "GET",
                "complete": function(response) {
                    $('.edit_item').click(function() {
                        var code = $(this).data('seq');
                        $.ajax({
                            url: base_path + "item/edit",
                            type: 'POST',
                            data: {
                                'code': code,
                            },
                            success: function(response) {
                                $('#generl_modal1').modal('show');
                                $(".panel-body1").html(response);
                            }
                        });
                    });
                    $('.delete_item').on("click", function() {
                        var code = $(this).data('seq');
                        swal({
                            title: "<?php echo $translations['Are you sure you want to delete this?']?>",
                            type: "warning",
                            showCancelButton: !0,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "<?php echo $translations['Yes, delete it!']?>",
                            cancelButtonText: "<?php echo $translations['No, cancel it!']?>",
                            closeOnConfirm: !1,
                            closeOnCancel: !1
                        }, function(e) {
                            if (e) {
                                $.ajax({
                                    url: base_path + "item/deleteItem",
                                    type: 'POST',
                                    data: {
                                        'code': code
                                    },
                                    success: function(data) {
                                        swal.close()
                                        if (data) {
                                            toastr.success('Item deleted successfully', 'Item', {
                                                "progressBar": true,
                                                "onHidden": function() {
                                                    loadTable();
                                                }
                                            });
                                        } else {
                                            toastr.error('Item not deleted', 'Item', {
                                                "progressBar": true
                                            });
                                        }
                                    }
                                });
                            } else {
                                swal.close();
                            }
                        });
                    });
                }
            }
        });
    }

    $("#itemForm").submit(function(e) {
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
                url: base_path + "item/saveItem",
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#saveItemBtn').prop('disabled', true);
                    $('#saveItemBtn').text('Please wait..');
                    $('#closeItemBtn').prop('disabled', true);
                },
                success: function(response) {
                    $('#saveItemBtn').prop('disabled', false);
                    $('#saveItemBtn').text('Save');
                    $('#closeItemBtn').prop('disabled', false);
                    var obj = JSON.parse(response);
                    if (obj.status) {
                        toastr.success(obj.message, 'Item', {
                            "progressBar": true
                        });
                        loadTable();
                        $('#itemName').val('');
                        $('#category').val(null);
                        $('#itemUnit').val(null);
                        $('#ingredientUnit').val(null);
                        $('#itemPrice').val('0');
                        $('#itemDesc').val('');
                        $('#ingredientFactor').val("0");
                        $('#isActive').prop('checked', false);
                    } else {
                        toastr.error(obj.message, 'Item', {
                            "progressBar": true
                        });
                    }
                }
            });
        }
    });
</script>