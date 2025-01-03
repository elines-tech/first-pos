<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Category</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Category</li>
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
                            <a id="add_category" class="add_category"><i class="fa fa-plus-circle cursor_pointer"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <!-- Basic Tables start -->
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                            <h5>Category List</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="print_div">
                    <table class="table table-striped" id="datatableCategory">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Code</th>
                                <th>Category Name</th>
                                <!-- <th>Short Name</th>-->
                                <th>Icon</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>
</div>
</div>
</body>
<div class="modal fade text-left" id="generl_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
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
                                            <div class="form-group row mandatory" id="nameDiv">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Name</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="categoryName" class="form-control" placeholder="Enter Name" name="categoryName" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12 d-none">
                                            <div class="form-group row mandatory" id="shortNameDiv">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Short Name</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="categorySName" class="form-control" maxlength="3" placeholder="Enter Short Name" name="categorySName">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Description :</label>
                                                <div class="col-md-8">
                                                    <textarea id="description" rows="6" class="form-control" placeholder="" name="description"></textarea>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-12 col-12" id="file_uploadDiv">
                                            <div class="form-group row">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Icon :</label>
                                                <div class="col-md-8">
                                                    <input type="file" id="categoryIcon" class="form-control" name="categoryIcon" style="padding: 5px;">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-12 col-12">
                                            <div class="form-group row">
                                                <label for="status" class="col-sm-4 col-form-label text-left">Active : </label>
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
<script>
    $(document).ready(function() {
        $('.cancel').removeClass('btn-default').addClass('btn-info');
        loadCategory();
    });

    $('.add_category').click(function() {
        $('#categoryForm').parsley().destroy();
        $('#generl_modal').modal('show');
        $('#modal_label').text('Add Category');
        $('#saveCategoryBtn').text('Save');
        $('#code').val('');
        $('#file_uploadDiv').removeClass('d-none');
        $('#saveCategoryBtn').removeClass('d-none');
        $('#categoryName').prop('disabled', false)
        $('#categorySName').prop('disabled', false)
        $('#description').prop('disabled', false)
        $("#isActive").prop("disabled", false);
        $('#categorySName').val('');
        $('#categoryName').val('');
        $('#categoryIcon').val('');
        $('#description').val('');
        $('#iconImg').attr('src', '');
        $('#previewDiv').addClass('d-none');
        $("#isActive").prop("checked", true);
    });

    function loadCategory() {
        if ($.fn.DataTable.isDataTable("#datatableCategory")) {
            $('#datatableCategory').DataTable().clear().destroy();
        }
        var dataTable = $('#datatableCategory').DataTable({
            stateSave: true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "searching": true,
            "ajax": {
                url: base_path + "category/getCategoryList",
                type: "GET",
                "complete": function(response) {
                    $('.edit_category').click(function() {
                        var code = $(this).data('seq');
                        var type = $(this).data('type');
                        $.ajax({
                            url: base_path + "category/editCategory",
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
                                        $('#categoryName').prop('disabled', true)
                                        $('#categorySName').prop('disabled', true)
                                        $('#description').prop('disabled', true)
                                        $("#isActive").prop("disabled", true);
                                    } else {
                                        $('#file_uploadDiv').removeClass('d-none');
                                        $('#saveCategoryBtn').removeClass('d-none');
                                        $('#modal_label').text('Update Category');
                                        $('#saveCategoryBtn').text('Update');
                                        $('#categoryName').prop('disabled', false)
                                        $('#categorySName').prop('disabled', false)
                                        $('#description').prop('disabled', false)
                                        $("#isActive").prop("disabled", false);
                                    }

                                    $('#nameDiv').removeClass('is-invalid');
                                    $('#shortNameDiv').removeClass('is-invalid');
                                    $('#code').val(obj.code);
                                    $('#categoryName').val(obj.categoryName);
                                    $('#categorySName').val(obj.categorySName);
                                    $('#description').val(obj.description);
                                    $('#iconImg').attr('src', '');
                                    $('#categoryIcon').val('');
                                    $('#previousIcon').val(obj.previousIcon);
                                    $('#previewDiv').addClass('d-none');
                                    if (obj.icon != '') {
                                        $('#previewDiv').removeClass('d-none');
                                        $('#iconImg').attr('src', obj.icon);
                                        $('#previousIcon').val(obj.previousIcon);
                                    }
                                    if (obj.isActive == 1) {
                                        $("#isActive").prop("checked", true);
                                    }
                                } else {
                                    toastr('Something Wend Wrong', 'Edit Category', {
                                        progressBar: true
                                    })
                                }
                            }
                        });
                    });
                    $('.delete_category').on("click", function() {
                        var code = $(this).data('seq');
                        swal({
                            //title: "You want to delete category "+code,
                            title: "Are you sure you want to delete this?",
                            type: "warning",
                            showCancelButton: !0,
                            confirmButtonColor: "#DD6B55",
                            //cancelButtonColor: "#DD6B55",
                            confirmButtonText: "Yes, delete it!",
                            cancelButtonText: "No, cancel it!",
                            closeOnConfirm: !1,
                            closeOnCancel: !1
                        }, function(e) {
                            if (e) {
                                $.ajax({
                                    url: base_path + "category/deleteCategory",
                                    type: 'POST',
                                    data: {
                                        'code': code
                                    },
                                    beforeSend: function() {

                                    },
                                    success: function(data) {
                                        swal.close();
                                        if (data) {
                                            toastr.success('Category deleted successfully', 'Category', {
                                                "progressBar": true
                                            });
                                            loadCategory();
                                        } else {
                                            toastr.error('Category not deleted', 'Category', {
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
                url: base_path + "category/saveCategory",
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
                    if ($('#code').val() != '') {
                        $('#saveCategoryBtn').text('Update');
                    } else {
                        $('#saveCategoryBtn').text('Save');
                    }
                    $('#closeCategoryBtn').prop('disabled', false);
                    var obj = JSON.parse(response);
                    if (obj.status) {
                        toastr.success(obj.message, 'Category', {
                            "progressBar": true
                        });
                        loadCategory()
                        if ($('#code').val() != '') {
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