<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Table</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../Dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Table</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <?php if ($insertRights == 1) { ?>
            <div id="maindiv" class="container">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                        <a id="saveDefault" class=" btn btn-sm btn-info add_bulk" href="#bulk_modal" data-bs-toggle="modal" data-bs-target="#bulk_modal">Create Bulk</a>
                        <div class="floating-action-button">
                            <a id="add_category" class="add_table"><i class="fa fa-plus-circle cursor_pointer"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                            <h5>Table List</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="print_div">
                    <table class="table table-striped" id="datatableTable">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Code</th>
                                <th>Branch</th>
                                <th>Sector Zone</th>
                                <th>Table Number</th>
                                <th>Table Seats</th>
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
 
<div class="modal fade text-left" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" id="generl_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id='modal_label'>Add Table</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body1">
                                <form id="tableForm" class="form" data-parsley-validate>
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Branch</label>
                                                <div class="col-md-8">
                                                    <?php if ($branchCode != "") { ?>
                                                        <input type="hidden" class="form-control" name="branchCode" id="branchCode" value="<?= $branchCode; ?>" readonly>
                                                        <input type="text" class="form-control" name="branchName" value="<?= $branchName; ?>" readonly>
                                                    <?php } else { ?>
                                                        <select class="form-select select2 branchCode" id="branchCode" name="branchCode" style="width:100%" required>
                                                        </select>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Sector Zone</label>
                                                <div class="col-md-8">
                                                    <select class="form-select select2" id="zoneCode" name="zoneCode" style="width:100%" required>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Table Seats</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="tableSeats" class="form-control" placeholder="Table Seats" name="tableSeats" required>
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
                                        <div class="col-md-12 col-12 d-none" id="previewDiv">
                                            <div class="form-group row">
                                                <label for="status" class="col-sm-4 col-form-label text-left">Table QR</label>
                                                <div class="col-sm-8 text-center" id="previewImgDiv">
                                                    <img src="" id="qrCodeImg" height="250" width="250">
                                                    <br />
                                                    <button class="btn btn-info mt-1 downloadQr" id="downloadQr" name="downloadQr">Download</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row card mb-2" id="noteDiv">
                                        <div class="card-body">Note : Qr Code will be generated after saving the record</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <input type="hidden" id="urlToken" class="form-control" name="urlToken">
                                            <input type="hidden" class="form-control" id="code" name="code">
                                            <?php if ($insertRights == 1) { ?>
                                                <button type="submit" class="btn btn-primary" id="saveTableBtn">Save</button>
                                            <?php } ?>
                                            <button type="button" class="btn btn-light-secondary" id="closeTableBtn" data-bs-dismiss="modal">Close</button>
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
<div class="modal fade text-left" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" id="bulk_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id='modal_label'>Add Table (Bulk)</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body1">
                                <form id="bulkForm" class="form" data-parsley-validate>
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Branch</label>
                                                <div class="col-md-8">
                                                    <?php if ($branchCode != "") { ?>
                                                        <input type="hidden" class="form-control" name="bulkbranchCode" id="bulkbranchCode" value="<?= $branchCode; ?>" readonly>
                                                        <input type="text" class="form-control" name="branchName" value="<?= $branchName; ?>" readonly>
                                                    <?php } else { ?>
                                                        <select class="form-select select2 bulkbranchCode" id="bulkbranchCode" name="bulkbranchCode" style="width:100%" required>
                                                        </select>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Sector Zone</label>
                                                <div class="col-md-8">
                                                    <select class="form-select select2" id="bulkzoneCode" name="bulkzoneCode" required style="width:100%">
                                                        <option value="">Select</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">No of tables</label>
                                                <div class="col-md-8">
                                                    <input type="number" id="noOfTables" class="form-control" placeholder="Enter table count" name="noOfTables" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Table Seats</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="bulktableSeats" class="form-control" placeholder="Table Seats" name="bulktableSeats" required>
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
                                    <div class="row mt-3">
                                        <div class="col-12 d-flex mt-3 justify-content-end">
                                            <?php if ($insertRights == 1) { ?>
                                                <button type="submit" class="btn btn-primary" id="saveTableBulkBtn">Save</button>
                                            <?php } ?>
                                            <button type="button" class="btn btn-light-secondary" id="closeTableBulkBtn" data-bs-dismiss="modal">Close</button>
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
    var bCode = "<?php echo $branchCode ?>";
    $(document).ready(function() {
        $('.cancel').removeClass('btn-default').addClass('btn-info');
        loadTable();
        resetDropdown();
        if (bCode != "") {
            var branchCode = bCode;
        } else {
            var branchCode = $("#branchCode").val();
        }
        $(".bulkbranchCode").select2({
            placeholder: "Select Branch",
            allowClear: true,
            ajax: {
                url: base_path + 'Common/getBranchesForZone',
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

        $('#bulkzoneCode').select2({
            dropdownParent: $('#bulk_modal'),
            placeholder: "Select Zone",
            allowClear: true,
            ajax: {
                url: base_path + 'Common/getZoneByBranch',
                type: "get",
                delay: 250,
                dataType: 'json',
                data: function(params) {
                    var query = {
                        search: params.term,
                        branch: $("#bulkbranchCode").val()
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
    $('#tableNumber').on('keypress', function(event) {
        var regex = new RegExp("^[a-zA-Z0-9]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
            event.preventDefault();
            return false;
        }
    });
    $(".downloadQr").on("click", function() {
        var urlToken = $('#urlToken').val();
        var code = $('#code').val();
        if (urlToken != '') {
            $.ajax({
                url: base_path + "table/downloadQrImg",
                type: 'POST',
                data: {
                    'urlToken': urlToken,
                    'code': code
                },
                beforeSend: function() {
                    $('#downloadQr').text('Please wait...');
                    $('#downloadQr').attr('disabled', true);
                },
                success: function(response) {
                    var obj = JSON.parse(response);
                    if (obj.status) {
                        var link = document.createElement('a');
                        var image = $('#qrCodeImg').attr('src');
                        link.href = obj.qrImg;
                        link.download = image.split("/").pop();
                        link.dispatchEvent(new MouseEvent('click'));
                        $('#downloadQr').text('Download');
                        $('#downloadQr').attr('disabled', false);
                        var imagePath = obj.file;
                        $.ajax({
                            url: base_path + "table/removeFile",
                            type: 'POST',
                            data: {
                                'file': imagePath
                            },
                            success: function(response) {
                                //debugger
                            }
                        });
                    } else {
                        toastr.error('Failed to download qr image..', 'Download Qr image', {
                            progressBar: true
                        })
                    }
                }
            });
        } else {
            toastr.error('Failed to download qr image..', 'Download Qr image', {
                progressBar: true
            })
        }
    });

    $('.add_table').click(function() {
        $('#generl_modal').modal('show');
        $('#modal_label').text('Add Table');
        $('#saveTableBtn').removeClass('d-none');
        $('#saveTableBtn').text('Save');
        $('#code').val('');
        $('#tableSeats').val('');
        $("#zoneCode").val(null).trigger('change.select2');
        $('#zoneCode').prop('disabled', false);
        $('#tableForm').parsley().destroy();
        $('#previewDiv').addClass('d-none');
        $('#noteDiv').removeClass('d-none');
        $("#isActive").prop("checked", true);
    });

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
                url: base_path + "table/getTableList",
                type: "GET",
                "complete": function(response) {
                    $('.edit_table').click(function() {
                        //debugger
                        var code = $(this).data('seq');
                        var type = $(this).data('type');
                        $.ajax({
                            url: base_path + "table/editTable",
                            type: 'POST',
                            data: {
                                'code': code,
                            },
                            success: function(response) {
                                var obj = JSON.parse(response);
                                if (obj.status) {
                                    $('#tableForm').parsley().destroy();
                                    $('#generl_modal').modal('show');
                                    if (type == 1) {
                                        $('#modal_label').text('View table');
                                        $('#saveTableBtn').addClass('d-none');
                                        $('#tableSeats').prop('disabled', true);
                                    } else {
                                        $('#modal_label').text('Update table');
                                        $('#saveTableBtn').removeClass('d-none');
                                        $('#saveTableBtn').text('Update');
                                        $('#tableSeats').prop('disabled', false);
                                    }
                                    $('#code').val(obj.code);
                                    console.log("branches", obj.branches);
                                    console.log("zones", obj.zones);
                                    $('#tableSeats').val(obj.tableSeats);
                                    $("#zoneCode").empty();
                                    $(".branchCode").empty();
                                    $.each(obj.branches, function(index, value) {
                                        $("#branchCode").append(`<option value="${value.code}">${value.branchName}</option>`);
                                    });
                                    $('.branchCode').select2('destroy');
                                    $(".branchCode").val(obj.branchCode);
                                    $(".branchCode").attr("disabled", true);
                                    $.each(obj.zones, function(index, value) {
                                        $("#zoneCode").append(`<option value="${value.id}">${value.zoneName}</option>`);
                                    });
                                    $('#zoneCode').select2('destroy');
                                    $("#zoneCode").val(obj.zoneCode);
                                    $("#zoneCode").attr("disabled", true);
                                    $('#urlToken').val(obj.urlToken);
                                    $('#noteDiv').addClass('d-none');
                                    $('#previewDiv').removeClass('d-none');
                                    $('#qrCodeImg').attr('src', obj.qrCodeImage);
                                    if (obj.isActive == 1) {
                                        $("#isActive").prop("checked", true);
                                    }
                                } else {
                                    toastr.error('Something Wend Wrong', 'Edit Table', {
                                        progressBar: true
                                    })
                                }
                            }
                        });
                    });
                    $('.delete_table').on("click", function() {
                        var code = $(this).data('seq');
                        swal({
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
                                    url: base_path + "table/deleteTable",
                                    type: 'POST',
                                    data: {
                                        'code': code
                                    },
                                    beforeSend: function() {

                                    },
                                    success: function(data) {
                                        swal.close();
                                        if (data) {
                                            loadTable();
                                            toastr.success('Table deleted successfully', 'Table', {
                                                "progressBar": true
                                            });
                                        } else {
                                            toastr.error('Table not deleted', 'Table', {
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

    function resetDropdown() {
        $("#branchCode").removeAttr("disabled");
        $("#zoneCode").removeAttr("disabled");
        $(".branchCode").select2({
            placeholder: "Select Branch",
            allowClear: true,
            ajax: {
                url: base_path + 'Common/getBranch',
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

        $('#zoneCode').select2({
            dropdownParent: $('#generl_modal'),
            placeholder: "Select Zone",
            allowClear: true,
            ajax: {
                url: base_path + 'Common/getZoneByBranch',
                type: "get",
                delay: 250,
                dataType: 'json',
                data: function(params) {
                    var query = {
                        search: params.term,
                        branch: $("#branchCode").val()
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

    $("#tableForm").submit(function(e) {
        e.preventDefault();
        e.stopPropagation();
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
                url: base_path + "table/saveTable",
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#saveTableBtn').prop('disabled', true);
                    $('#saveTableBtn').text('Please wait..');
                    $('#closeTableBtn').prop('disabled', true);
                },
                success: function(response) {
                    $('#saveTableBtn').prop('disabled', false);
                    if ($('#code').val() != '') {
                        $('#saveTableBtn').text('Update');
                    } else {
                        $('#saveTableBtn').text('Save');
                    }
                    $('#closeTableBtn').prop('disabled', false);
                    var obj = JSON.parse(response);
                    if (obj.status) {
                        toastr.success(obj.message, 'Table', {
                            "progressBar": true
                        });
                        loadTable();
                        resetDropdown();
                        if ($('#code').val() != '') {
                            $('#generl_modal').modal('hide');
                        } else {
                            $('#code').val('');
                            $("#zoneCode").val('').trigger('change');
                            $('#tableSeats').val('');
                        }
                    } else {
                        toastr.error(obj.message, 'Table', {
                            "progressBar": true
                        });
                    }
                }
            });
        }
    });
    $("#bulkForm").submit(function(e) {
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
                url: base_path + "table/saveTableBulk",
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#saveTableBulkBtn').prop('disabled', true);
                    $('#saveTableBulkBtn').text('Please wait..');
                    $('#closeTableBulkBtn').prop('disabled', true);
                },
                success: function(response) {
                    $('#saveTableBulkBtn').prop('disabled', false);
                    $('#saveTableBulkBtn').text('Save');
                    $('#closeTableBulkBtn').prop('disabled', false);
                    var obj = JSON.parse(response);
                    if (obj.status) {
                        toastr.success(obj.message, 'Table Bulk', {
                            "progressBar": true
                        });
                        loadTable();
                        $("#bulkzoneCode").val('').trigger('change');
                        //$("#bulkzoneCode").val("");
                        $("#noOfTables").val("");
                    } else {
                        toastr.error(obj.message, 'Table Bulk', {
                            "progressBar": true
                        });
                    }
                }
            });
        }
    });
    $(document).on("click", "#closeTableBtn", function() {
        resetDropdown();
    });
</script>