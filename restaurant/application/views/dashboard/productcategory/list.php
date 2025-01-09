<?php include '../restaurant/config.php'; ?>

<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3><?php echo $translations['Product Category']?></h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../Dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Product Category</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <?php
        if ($insertRights == 1) { ?>
            <div id="maindiv" class="container justify-content-center text-center">
                <!--<div class="row">-->
                    <!--<div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">-->
                        <div class="floating-action-button">
                            <a id="add_category" class="add_category d-flex align-items-center justify-content-center">
                                <i class="fa fa-plus-circle cursor_pointer"></i></a>
                        </div>
                    
                <!--</div>-->
                <!--</div>-->
            </div>
        <?php } ?>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                            <h5><?php echo $translations['Product Category List']?></h5>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="print_div">
                    <table class="table table-striped" id="datatableProductCategory">
                        <thead>
                            <tr>
                                <th><?php echo $translations['Sr No']?></th>
                                <th><?php echo $translations['Category Code']?></th>
                                <th><?php echo $translations['Name']?></th>
                                <th><?php echo $translations['Icon']?></th>
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
<div class="modal fade text-left" id="generl_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id='modal_label'><?php echo $translations['Add Product Category'];?></h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body1">
                                <form id="categoryForm" class="form" data-parsley-validate>
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory" id="nameDiv">
                                                <label for="category-name-column" class="col-md-4 form-label text-left"><?php echo $translations['Name']?></label>
                                                <div class="col-md-8">
                                                    <input type="text" id="categoryName" class="form-control" placeholder="Enter Name" name="categoryName" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12 d-none">
                                            <div class="form-group row mandatory" id="shortNameDiv">
                                                <label for="category-name-column" class="col-md-4 form-label text-left"><?php echo $translations['Short Name']?></label>
                                                <div class="col-md-8">
                                                    <input type="text" id="categorySName" class="form-control" maxlength="3" placeholder="Enter Short Name" name="categorySName">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row">
                                                <label for="category-name-column" class="col-md-4 form-label text-left"><?php echo $translations['Description']?></label>
                                                <div class="col-md-8">
                                                    <textarea id="description" rows="6" class="form-control" placeholder="" name="description"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12" id="file_uploadDiv">
                                            <div class="form-group row">
                                                <label for="category-name-column" class="col-md-4 form-label text-left"><?php echo $translations['Icon']?></label>
                                                <div class="col-md-8">
                                                    <input type="file" id="categoryIcon" class="form-control" name="categoryIcon">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row">
                                                <label for="status" class="col-sm-4 col-form-label text-left"><?php echo $translations['Active']?> </label>
                                                <div class="col-sm-8 checkbox">
                                                    <input type="checkbox" name="isActive" id="isActive" class=" " style="width:25px; height:25px">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12 d-none" id="previewDiv">
                                            <div class="form-group row">
                                                <div class="col-sm-12 text-center">
                                                    <img src="" id="iconImg" height="300" width="300">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <input type="hidden" class="form-control" id="code" name="code">
                                            <input type="hidden" class="form-control" id="previousIcon" name="previousIcon">
                                            <?php if ($insertRights == 1) { ?>
                                                <button type="submit" class="btn btn-primary" id="saveCategoryBtn"><?php echo $translations['Save']?></button>
                                            <?php } ?>
                                            <button type="button" class="btn btn-light-secondary" id="closeCategoryBtn" data-bs-dismiss="modal"><?php echo $translations['Close']?></button>
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


<div class="modal fade text-left" id="generl_modal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id='modal_label1'><?php echo $translations['View Product Category']?></h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body2">
                                <form id="viewcategoryForm">
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row">
                                                <label for="category-name-column" class="col-md-4 form-label text-left"><?php echo $translations['Name']?></label>
                                                <div class="col-md-8">
                                                    <input type="text" id="viewcategoryName" class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12 d-none">
                                            <div class="form-group row">
                                                <label for="category-name-column" class="col-md-4 form-label text-left"><?php echo $translations['Short Name']?></label>
                                                <div class="col-md-8">
                                                    <input type="text" id="viewcategorySName" class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row">
                                                <label for="category-name-column" class="col-md-4 form-label text-left"><?php echo $translations['Description']?></label>
                                                <div class="col-md-8">
                                                    <textarea id="viewdescription" rows="6" class="form-control"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12 d-none" id="viewpreviewDiv">
                                            <div class="form-group row">
                                                <div class="col-sm-12 text-center">
                                                    <img src="" id="viewiconImg" height="300" width="300">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <button id="closeView" type="button" class="btn btn-light-secondary me-1 mb-1" data-bs-dismiss="modal"><?php echo $translations['Close']?></button>
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
<script>
    $(document).ready(function() {
        $('.cancel').removeClass('btn-default').addClass('btn-info');
        loadCategory();
    });

    $('.add_category').click(function() {
        $('#categoryForm').parsley().destroy();
        $('#generl_modal').modal('show');
        $('#modal_label').text('Add Product Category');
        $('#saveCategoryBtn').text('Save');
        $('#code').val('');
        $('#file_uploadDiv').removeClass('d-none');
        $('#saveCategoryBtn').removeClass('d-none');
        $('#categorySName').val('');
        $('#categoryName').val('');
        $('#categoryIcon').val('');
        $('#description').val('');
        $('#iconImg').attr('src', '');
        $('#previewDiv').addClass('d-none');
        $("#isActive").prop("checked", true);
    });

    function loadCategory() {
        if ($.fn.DataTable.isDataTable("#datatableProductCategory")) {
            $('#datatableProductCategory').DataTable().clear().destroy();
        }
        var dataTable = $('#datatableProductCategory').DataTable({
            stateSave: true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "searching": true,
            "ajax": {
                url: base_path + "ProductCategory/getCategoryList",
                type: "GET",
                "complete": function(response) {
                    $('.edit_category').click(function() {
                        var code = $(this).data('seq');
                        var type = $(this).data('type');
                        $.ajax({
                            url: base_path + "ProductCategory/editCategory",
                            type: 'POST',
                            data: {
                                'code': code,
                            },
                            success: function(response) {
                                var obj = JSON.parse(response);
                                if (obj.status) {
                                    $('#categoryForm').parsley().destroy();
                                    $('#generl_modal').modal('show');
                                    if (type == 1) {
                                        $('#modal_label').text('View Category');
                                        $('#file_uploadDiv').addClass('d-none');
                                        $('#saveCategoryBtn').addClass('d-none');
                                    } else {
                                        $('#file_uploadDiv').removeClass('d-none');
                                        $('#saveCategoryBtn').removeClass('d-none');
                                        $('#modal_label').text('Update Category');
                                        $('#saveCategoryBtn').text('Update');
                                    }
                                    $('#nameDiv').removeClass('is-invalid');
                                    $('#shortNameDiv').removeClass('is-invalid');
                                    $('#code').val(obj.code);
                                    $('#categoryName').val(obj.categoryName);
                                    $('#categorySName').val(obj.categorySName);
                                    $('#description').val(obj.description);
                                    if (obj.icon != '') {
                                        $('#previewDiv').removeClass('d-none');
                                        $('#iconImg').attr('src', obj.icon);
                                        $('#previousIcon').val(obj.previousIcon);
                                    }
                                    if (obj.isActive == 1) {
                                        $("#isActive").prop("checked", true);
                                    }
                                } else {
                                    toastr('Something Wend Wrong', 'Edit Product Category', {
                                        progressBar: true
                                    })
                                }
                            }
                        });
                    });

                    $('.view_category').click(function() {
                        var code = $(this).data('seq');
                        var type = $(this).data('type');
                        $.ajax({
                            url: base_path + "ProductCategory/viewCategory",
                            type: 'POST',
                            data: {
                                'code': code,
                            },
                            success: function(response) {
                                var obj = JSON.parse(response);
                                if (obj.status) {
                                    $('#generl_modal1').modal('show');
                                    if (type == 1) {
                                        $('#modal_label1').text('View Product Category');
                                    }
                                    $('#viewcategoryName').val(obj.categoryName);
                                    $('#viewcategorySName').val(obj.categorySName);
                                    $('#viewdescription').val(obj.description);
                                    if (obj.icon != '') {
                                        $('#viewpreviewDiv').removeClass('d-none');
                                        $('#viewiconImg').attr('src', obj.icon);
                                    }
                                    if (obj.isActive == 1) {
                                        $("#isActive").prop("checked", true);
                                    }
                                }
                            }
                        });
                    });
                    $('.delete_category').on("click", function() {
                        var code = $(this).data('seq');
                        swal({
                            //title: "You want to delete category "+code,
                            title: "<?php echo $translations['Are you sure you want to delete this?']?>",
                            type: "warning",
                            showCancelButton: !0,
                            confirmButtonColor: "#DD6B55",
                            //cancelButtonColor: "#DD6B55",
                            confirmButtonText: "<?php echo $translations['Yes, delete it!']?>",
                            cancelButtonText: "<?php echo $translations['No, cancel it!']?>",
                            closeOnConfirm: !1,
                            closeOnCancel: !1
                        }, function(e) {
                            if (e) {
                                $.ajax({
                                    url: base_path + "ProductCategory/deleteCategory",
                                    type: 'POST',
                                    data: {
                                        'code': code
                                    },
                                    beforeSend: function() {},
                                    success: function(data) {
                                        swal.close();
                                        if (data) {
                                            toastr.success('Product Category deleted successfully', 'Product Category', {
                                                "progressBar": true
                                            });
                                            loadCategory();
                                        } else {
                                            toastr.error('Product Category not deleted', 'Product Category', {
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
                url: base_path + "ProductCategory/saveCategory",
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "JSON",
                beforeSend: function() {
                    $('#saveCategoryBtn').prop('disabled', true);
                    $('#saveCategoryBtn').text('Please wait..');
                    $('#closeCategoryBtn').prop('disabled', true);
                },
                success: function(response) {
                    $('#saveCategoryBtn').prop('disabled', false);
                    if ($('#code').val() != '') {
                        $('#saveCategoryBtn').text('Update');
                    } else {
                        $('#saveCategoryBtn').text('Save');
                    }
                    $('#closeCategoryBtn').prop('disabled', false);

                    if (response.status) {
                        toastr.success(response.message, 'Category', {
                            "progressBar": true,
                            "onHidden": function() {
                                loadCategory();
                            }
                        });
                        if (code != '') {
                            $('#generl_modal').modal('hide');
                        } else {
                            $('#code').val('');
                            $('#categoryName').val('');
                            $('#categorySName').val('');
                            $('#description').val('');
                            $('#categoryIcon').val('');
                            $('#iconImg').attr('src', '');
                        }
                    } else {
                        toastr.error(obj.message, 'Category', {
                            "progressBar": true
                        });
                    }
                }
            });
        }
    });
</script>