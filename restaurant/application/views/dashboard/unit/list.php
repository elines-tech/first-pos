<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Unit</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Unit</li>
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
                            <a id="add_category" class="add_unit"><i class="fa fa-plus-circle cursor_pointer"></i></a>
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
                            <h5>Unit List</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="print_div">
                    <table class="table table-striped" id="datatableUnit">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Short Name</th>
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
                <h5 id='modal_label'>Add Unit</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body1">
                                <form id="unitForm" class="form" data-parsley-validate>
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory" id="nameDiv">
                                                <label for="Unit-name-column" class="col-md-4 form-label text-left">Name</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="unitName" class="form-control" placeholder="Enter Name" name="unitName" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory" id="shortNameDiv">
                                                <label for="Unit-name-column" class="col-md-4 form-label text-left">Short Name</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="unitSName" class="form-control" maxlength="3" placeholder="Enter Short Name" name="unitSName" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row">
                                                <label for="Unit-name-column" class="col-md-4 form-label text-left">Description : </label>
                                                <div class="col-md-8">
                                                    <textarea id="description" rows="6" class="form-control" placeholder="" name="description"></textarea>
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
                                    </div>

                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <input type="hidden" class="form-control" id="code" name="code">
                                            <?php if ($insertRights == 1) { ?>
                                                <button type="submit" class="btn btn-primary" id="saveUnitBtn">Save</button>
                                            <?php } ?>
                                            <button type="button" class="btn btn-light-secondary" id="closeUnitBtn" data-bs-dismiss="modal">Close</button>
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
        loadUnit();
    });
    $('.add_unit').click(function() {
        $('#unitForm').parsley().destroy();
        $('#generl_modal').modal('show');
        $('#saveUnitBtn').removeClass('d-none');
        $('#modal_label').text('Add Unit');
        $('#saveUnitBtn').text('Save');
        $('#code').val('');
        $('#unitName').val('');
        $('#unitSName').val('');
        $('#description').val('');
        $("#isActive").prop("checked", true);
    });

    function loadUnit() {
        if ($.fn.DataTable.isDataTable("#datatableUnit")) {
            $('#datatableUnit').DataTable().clear().destroy();
        }
        var dataTable = $('#datatableUnit').DataTable({
            stateSave: true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "searching": true,
            "ajax": {
                url: base_path + "unit/getUnitList",
                type: "GET",
                "complete": function(response) {
                    $('.edit_unit').click(function() {
                        var code = $(this).data('seq');
                        var type = $(this).data('type');
                        $.ajax({
                            url: base_path + "unit/editUnit",
                            type: 'POST',
                            data: {
                                'code': code,
                            },
                            success: function(response) {
                                var obj = JSON.parse(response);
                                if (obj.status) {
                                    $('#unitForm').parsley().destroy();
                                    $('#generl_modal').modal('show');
                                    if (type == 1) {
                                        $('#modal_label').text('View Unit');
                                        $('#saveUnitBtn').addClass('d-none');
                                    } else {
                                        $('#saveUnitBtn').removeClass('d-none');
                                        $('#modal_label').text('Update Unit');
                                        $('#saveUnitBtn').text('Update');
                                    }
                                    $('#nameDiv').removeClass('is-invalid');
                                    $('#shortNameDiv').removeClass('is-invalid');
                                    $('#code').val(obj.code);
                                    $('#unitName').val(obj.unitName);
                                    $('#unitSName').val(obj.unitSName);
                                    $('#description').val(obj.description);
                                    if (obj.isActive == 1) {
                                        $("#isActive").prop("checked", true);
                                    }
                                } else {
                                    toastr.error('Something Wend Wrong', 'Edit Unit', {
                                        progressBar: true
                                    })
                                }
                            }
                        });
                    });
                    $('.delete_unit').on("click", function() {
                        var code = $(this).data('seq');
                        swal({
                            //title: "You want to delete Unit "+code,
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
                                    url: base_path + "unit/deleteUnit",
                                    type: 'POST',
                                    data: {
                                        'code': code
                                    },
                                    beforeSend: function() {

                                    },
                                    success: function(data) {
                                        swal.close();
                                        if (data) {
                                            loadUnit();
                                            toastr.success('Unit deleted successfully', 'Unit', {
                                                "progressBar": true
                                            });
                                        } else {
                                            toastr.error('Unit not deleted', 'Unit', {
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

    $("#unitForm").submit(function(e) {
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
                url: base_path + "unit/saveUnit",
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#saveUnitBtn').prop('disabled', true);
                    $('#saveUnitBtn').text('Please wait..');
                    $('#closeUnitBtn').prop('disabled', true);
                },
                success: function(response) {
                    $('#saveUnitBtn').prop('disabled', false);
                    if ($('#code').val() != '') {
                        $('#saveUnitBtn').text('Update');
                    } else {
                        $('#saveUnitBtn').text('Save');
                    }
                    $('#closeUnitBtn').prop('disabled', false);
                    var obj = JSON.parse(response);
                    if (obj.status) {
                        toastr.success(obj.message, 'Unit', {
                            "progressBar": true
                        });
                        loadUnit()
                        if ($('#code').val() != '') {
                            $('#generl_modal').modal('hide');
                        } else {
                            $('#code').val('');
                            $('#unitName').val('');
                            $('#unitSName').val('');
                            $('#description').val('');
                            $('#isActive').prop('checked', false)
                        }
                    } else {
                        toastr.error(obj.message, 'Unit', {
                            "progressBar": true
                        });
                    }
                }
            });
        }
    });
</script>