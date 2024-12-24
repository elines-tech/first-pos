<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Brand</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Brand</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <?php if ($insertRights == 1) { ?>
            <div id="maindiv" class="container justify-content-center text-center">
                <!--<div class="row">-->
                <!--<div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">-->
                <div class="floating-action-button">
                    <a id="add_category" class="add_brand"><i class="fa fa-plus-circle cursor_pointer"></i></a>
                </div>
                <!--</div>-->
                <!--</div>-->
            </div>
        <?php } ?>
        <!-- Basic Tables start -->
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                            <h5>Brand List</h5>
                        </div>

                    </div>
                </div>
                <div class="card-body" id="print_div">
                    <table class="table table-striped" id="datatableBrand">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Code</th>
                                <th>Brand</th>
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
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row">
                                                <label for="status" class="col-sm-4 col-form-label text-left">Active</label>
                                                <div class="col-sm-8 checkbox">
                                                    <input type="checkbox" name="isActive" id="isActive" class=" " checked style="width:25px; height:25px">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <input type="hidden" class="form-control" id="code" name="code">
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
<script>
    $(document).ready(function() {
        $('.cancel').removeClass('btn-default').addClass('btn-info');
        loadTable();
    });

    $('.add_brand').click(function() {
        $('#saveBrandBtn').removeClass('d-none');
        $('#BrandForm').parsley().destroy();
        $('#generl_modal').modal('show');
        $('#modal_label').text('Add Brand');
        $('#saveBrandBtn').text('Save');
        $('#code').val('');
        $('#brand').prop('disabled', false);
        $('#isActive').prop('disabled', false);
        $('#brand').val('');
        $("#isActive").prop("checked", true);
    });

    function loadTable() {
        if ($.fn.DataTable.isDataTable("#datatableBrand")) {
            $('#datatableBrand').DataTable().clear().destroy();
        }
        var dataTable = $('#datatableBrand').DataTable({
            stateSave: true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "searching": true,
            "ajax": {
                url: base_path + "Brand/getBrandList",
                type: "GET",
                "complete": function(response) {
                    $('.edit_brand').click(function() {
                        var code = $(this).data('seq');
                        var type = $(this).data('type');
                        $.ajax({
                            url: base_path + "Brand/editBrand",
                            type: 'POST',
                            data: {
                                'code': code,
                            },
                            success: function(response) {
                                var obj = JSON.parse(response);
                                if (obj.status) {
                                    $('#BrandForm').parsley().destroy();
                                    $('#generl_modal').modal('show');
                                    if (type == 1) {
                                        $('#modal_label').text('View Brand');
                                        $('#brand').prop('disabled', true);
                                        $("#isActive").prop("disabled", true);
                                        $('#saveBrandBtn').addClass('d-none');
                                    } else {
                                        $('#saveBrandBtn').removeClass('d-none');
                                        $('#brand').prop('disabled', false);
                                        $("#isActive").prop("disabled", false);
                                        $('#modal_label').text('Update Brand');
                                        $('#saveBrandBtn').text('Update');
                                    }
                                    $('#code').val(obj.code);
                                    $('#brand').val(obj.brandName);
                                    if (obj.isActive == 1) {
                                        $("#isActive").prop("checked", true);
                                    } else {
                                        $("#isActive").prop("checked", false);
                                    }
                                } else {
                                    toastr.error('Something Wend Wrong', 'Edit Brand', {
                                        progressBar: true
                                    })
                                }
                            }
                        });
                    });
                    $('.delete_brand').on("click", function() {
                        var code = $(this).data('seq');
                        swal({
                            //title: "You want to delete Brand "+code,
                            title: "Are you sure you want to delete this?",
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
                                    url: base_path + "Brand/deleteBrand",
                                    type: 'POST',
                                    data: {
                                        'code': code
                                    },
                                    beforeSend: function() {

                                    },
                                    success: function(data) {
                                        swal.close()
                                        if (data) {
                                            loadTable();
                                            toastr.success('Brand deleted successfully', 'Brand', {
                                                "progressBar": true
                                            });
                                        } else {
                                            toastr.error('Brand not deleted', 'Brand', {
                                                "progressBar": true
                                            });
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

    $("#BrandForm").submit(function(e) {
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
                url: base_path + "Brand/saveBrand",
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
                    if ($("#code").val() != '') {
                        $('#saveBrandBtn').text('Update');
                    } else {
                        $('#saveBrandBtn').text('Save');
                    }
                    $('#closeBrandBtn').prop('disabled', false);
                    var obj = JSON.parse(response);
                    if (obj.status) {
                        toastr.success(obj.message, 'Brand', {
                            "progressBar": true
                        });
                        loadTable()
                        if ($("#code").val() != '') {
                            $('#generl_modal').modal('hide');
                        } else {
                            $('#code').val('');
                            $('#brand').val('');
                            //$('#isActive').prop('checked', false);
                        }
                    } else {
                        toastr.error(obj.message, 'Brand', {
                            "progressBar": true
                        });
                    }
                }
            });
        }
    })
</script>