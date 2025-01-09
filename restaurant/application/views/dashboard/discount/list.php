<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Discount</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../Dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Discount</li>
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
                            <a id="add_category" class="add_discount"><i class="fa fa-plus-circle cursor_pointer"></i></a>
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
                            <h5>Discount List</h5>
                        </div>

                    </div>
                </div>
                <div class="card-body" id="print_div">
                    <table class="table table-striped" id="datatableTable">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Code</th>
                                <th>Discount</th>
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
                <h5 id='modal_label'>Add Discount</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body1">
                                <form id="discountForm" class="form" data-parsley-validate>
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Discount</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="discount" onkeypress="return isNumber(event)" class="form-control" placeholder="Enter discount" name="discount" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row ">
                                                <label for="status" class="col-sm-4 col-form-label text-left">Active</label>
                                                <div class="col-sm-8 checkbox">
                                                    <input type="checkbox" name="isActive" checked id="isActive" class=" " style="width:25px; height:25px">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <input type="hidden" class="form-control" id="code" name="code">
                                            <button type="submit" class="btn btn-primary" id="saveDiscountBtn">Save</button>
                                            <button type="button" class="btn btn-light-secondary" id="closeDiscountBtn" data-bs-dismiss="modal">Close</button>
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
    $('.add_discount').click(function() {
        $('#discountForm').parsley().destroy();
        $('#generl_modal').modal('show');
        $('#modal_label').text('Add Discount');
        $('#saveDiscountBtn').removeClass('d-none');
        $('#saveDiscountBtn').text('Save');
        $('#code').val('');
        $('#discount').val('');
        $("#isActive").prop("checked", true);
    });

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 46 || charCode > 57 || charCode == 47)) {
            return false;
        }
        return true;
    }

    function loadTable() {
        if ($.fn.DataTable.isDataTable("#datatableTable")) {
            $('#datatableTable').DataTable().clear().destroy();
        }
        var dataTable = $('#datatableTable').DataTable({
            stateSave: true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "searching": true,
            "ajax": {
                url: base_path + "discount/getDiscountList",
                type: "GET",
                "complete": function(response) {
                    $('.edit_discount').click(function() {
                        var code = $(this).data('seq');
                        var type = $(this).data('type');
                        $.ajax({
                            url: base_path + "discount/editDiscount",
                            type: 'POST',
                            data: {
                                'code': code,
                            },
                            success: function(response) {
                                var obj = JSON.parse(response);
                                if (obj.status) {
                                    $('#discountForm').parsley().destroy();
                                    $('#generl_modal').modal('show');
                                    if (type == 1) {
                                        $('#modal_label').text('View Discount');
                                        $('#saveDiscountBtn').addClass('d-none');
                                    } else {
                                        $('#modal_label').text('Update Discount');
                                        $('#saveDiscountBtn').removeClass('d-none');
                                        $('#saveDiscountBtn').text('Update');
                                    }
                                    $('#code').val(obj.code);
                                    $('#discount').val(obj.discount);
                                    if (obj.isActive == 1) {
                                        $("#isActive").prop("checked", true);
                                    }
                                } else {
                                    toastr.error('Something Wend Wrong', 'Edit Discount', {
                                        progressBar: true
                                    })
                                }
                            }
                        });
                    });
                    $('.delete_discount').on("click", function() {
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
                                    url: base_path + "discount/deleteDiscount",
                                    type: 'POST',
                                    data: {
                                        'code': code
                                    },
                                    success: function(data) {
                                        swal.close();
                                        if (data) {
                                            loadTable();
                                            toastr.success('Discount deleted successfully', 'Discount', {
                                                "progressBar": true
                                            });
                                        } else {
                                            toastr.error('Discount not deleted', 'Discount', {
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

    $("#discountForm").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var form = $(this);
        form.parsley().validate();
        if (form.parsley().isValid()) {
            if (isNaN($('#discount').val())) {
                toastr.error('Invalid discount', 'Discount', {
                    "progressBar": true
                });
            } else {
                if ($('#discount').val() > 0) {
                    var isActive = 0;
                    if ($("#isActive").is(':checked')) {
                        isActive = 1;
                    }
                    formData.append('isActive', isActive);
                    $.ajax({
                        url: base_path + "discount/saveDiscount",
                        type: 'POST',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function() {
                            $('#saveDiscountBtn').prop('disabled', true);
                            $('#saveDiscountBtn').text('Please wait..');
                            $('#closeDiscountBtn').prop('disabled', true);
                        },
                        success: function(response) {
                            $('#saveDiscountBtn').prop('disabled', false);
                            if ($('#code').val() != '') {
                                $('#saveDiscountBtn').text('Update');
                            } else {
                                $('#saveDiscountBtn').text('Save');
                            }
                            $('#closeDiscountBtn').prop('disabled', false);
                            var obj = JSON.parse(response);
                            if (obj.status) {
                                toastr.success(obj.message, 'Discount', {
                                    "progressBar": true
                                });
                                loadTable()
                                if ($('#code').val() != '') {
                                    $('#generl_modal').modal('hide');
                                } else {
                                    $('#code').val('');
                                    $('#discount').val('');
                                }
                            } else {
                                toastr.error(obj.message, 'Discount', {
                                    "progressBar": true
                                });
                            }
                        }
                    });
                } else {
                    toastr.error('Discount should be greater than zero', 'Discount', {
                        "progressBar": true
                    });
                }
            }
        }
    });
</script>