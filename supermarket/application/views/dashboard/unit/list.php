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
                            <li class="breadcrumb-item"><a href="../dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
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
                                <th>Base unit</th>
                                <th>Name</th>
                                <th>Short Name</th>
                                <th>Rounding Precision</th>
                                <th>Conversion Factor</th>
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
                                            <div class="form-group row mandatory">
                                                <label class="col-md-4 form-label text-left">Base Unit</label>
                                                <div class="col-md-8">
                                                    <select class="form-select select2" style="width:100%" name="baseUnit" id="baseUnit" required>

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
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
                                                <label for="Unit-name-column" class="col-md-4 form-label text-left">Description</label>
                                                <div class="col-md-8">
                                                    <textarea id="description" rows="6" class="form-control" placeholder="" name="description"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory">
                                                <label class="col-md-4 form-label text-left">Round Precision</label>
                                                <div class="col-md-8">
                                                    <select class="form-select" required name="rounding" id="rounding">
                                                        <option value="0">0</option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory">
                                                <label for="Unit-name-column" class="col-md-4 form-label text-nowrap text-left">Conversion Factor</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="conversionFactor" class="form-control" onkeypress="return isDecimal(event)" placeholder="Enter Conversion Factor Number" name="conversionFactor" onchange="checkConversionFactor()" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row">
                                                <label for="status" class="col-sm-4 col-form-label text-left">Active : </label>
                                                <div class="col-sm-8 checkbox">
                                                    <input type="checkbox" name="isActive" id="isActive" class="mt-2" style="width:25px; height:25px">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <input type="hidden" class="form-control" id="code" name="code">
                                            <button type="submit" class="btn btn-primary" id="saveUnitBtn">Save</button>
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
        $("#baseUnit").select2({
            placeholder: "Select Base Unit",
            dropdownParent: $('#generl_modal .modal-content'),
            allowClear: true,
            ajax: {
                url: base_path + 'Common/getBaseUnit',
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
    $('.add_unit').click(function() {
        $('#unitForm').parsley().destroy();
        $('#generl_modal').modal('show');
        $('#saveUnitBtn').removeClass('d-none');
        $('#modal_label').text('Add Unit');
        $('#saveUnitBtn').text('Save');
        $('#code').val('');
        $("#baseUnit").val('').trigger('change.select2');
        $('#unitName').val('');
        $('#unitSName').val('');
        $('#conversionFactor').val('');
        $('#rounding').val('');
        $('#baseUnit').prop('disabled', false);
        $('#unitName').prop('disabled', false);
        $('#unitSName').prop('disabled', false);
        $('#description').prop('disabled', false);
        $('#rounding').prop('disabled', false);
        $('#conversionFactor').prop('disabled', false);
        $('#isActive').prop('disabled', false);
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
                                        $('#baseUnit').prop('disabled', true);
                                        $('#unitName').prop('disabled', true);
                                        $('#unitSName').prop('disabled', true);
                                        $('#description').prop('disabled', true);
                                        $('#rounding').prop('disabled', true);
                                        $('#conversionFactor').prop('disabled', true);
                                        $('#isActive').prop('disabled', true);
                                    } else {
                                        $('#saveUnitBtn').removeClass('d-none');
                                        $('#modal_label').text('Update Unit');
                                        $('#saveUnitBtn').text('Update');
                                        $('#baseUnit').prop('disabled', false);
                                        $('#unitName').prop('disabled', false);
                                        $('#unitSName').prop('disabled', false);
                                        $('#description').prop('disabled', false);
                                        $('#rounding').prop('disabled', false);
                                        $('#conversionFactor').prop('disabled', false);
                                        $('#isActive').prop('disabled', false);
                                    }
                                    $('#nameDiv').removeClass('is-invalid');
                                    $('#shortNameDiv').removeClass('is-invalid');
                                    $('#code').val(obj.code);
                                    $('#baseUnit').val(obj.baseUnitCode).select2();
                                    $('#unitName').val(obj.unitName);
                                    $('#unitSName').val(obj.unitSName);
                                    $('#description').val(obj.description);
                                    $('#rounding').val(obj.unitRounding);
                                    $('#conversionFactor').val(obj.conversionFactor);
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

    function rPrecision() {
        var rPrecision = Number($('#rounding').val());
        if (isNaN(rPrecision)) {
            $('#rounding').val('');
            toastr.success("Enter valid Number", 'Unit', {
                "progressBar": true
            });
        } else {
            $('#rounding').val(rPrecision.toFixed(3));

        }
    }

    function checkConversionFactor() {
        var conversionFactor = Number($('#conversionFactor').val());
        if (conversionFactor == '' || conversionFactor == 0) {
            toastr.error("Invalid conversion factor..", 'Conversion Factor', {
                "progressBar": true
            });
            $('#conversionFactor').val('');
            $('#conversionFactor').val(1);
            $('#conversionFactor').focus();
        }
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
                            $("#baseUnit").val('').trigger('change.select2');
                            $('#unitName').val('');
                            $('#unitSName').val('');
                            $('#rounding').val('');
                            $('#description').val('');
                            $('#conversionFactor').val('');
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