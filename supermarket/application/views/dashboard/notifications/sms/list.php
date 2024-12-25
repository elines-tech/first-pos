<style>
    .dataTables_filter {
        float: right;
    }

    .dataTables_length {
        float: left;
    }

    .dt_buttons {
        float: right;
    }
</style>
<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>SMS Templates</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><i class="fa fa-dashboard"></i> Dashboard</li>
                            <li class="breadcrumb-item active" aria-current="page">SMS Templates List</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="row">
					     <?php if($insertRights==1){ ?>
                        <div class="col-12 text-end" id="leftdiv">
                            <a id="saveDefaultButton" href="javascript:void(0)" class="btn btn-sm btn-primary btn-new"><i class="fa fa-plus"></i> Template</a>
                        </div>
						 <?php } ?>
                    </div>
                </div>
                <div class="card-body" id="print_div">
                    <table class="table table-striped" id="dbtable">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Template Name</th>
                                <th>Template</th>
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

<div class="modal fade text-left" id="dataModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id='modal_label'>Add Template</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body1">
                                <form id="templateForm" class="form" data-parsley-validate>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <input type="hidden" class="form-control" id="code" name="code">
                                            <label for="templateName" class="form-label text-left">Template Name</label>
                                            <input type="text" id="templateName" class="form-control" placeholder="Template Name" name="templateName" required>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="template" class="form-label text-left">Message Text</label>
                                            <textarea type="text" id="template" class="form-control" placeholder="Message" name="template" rows="4" required></textarea>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" value="" name="isActive" id="isActive" checked>
                                                <label class="form-check-label" for="isActive">
                                                    Active
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary" id="btnSave">Save</button>
                                            <button type="button" class="btn btn-light-secondary" id="btnClose" data-bs-dismiss="modal">Close</button>
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
        loadTable();
    });

    $(document).on("click", "a.btn-new", function(e) {
        e.preventDefault();
        $("#dataModal").modal("show");
    });

    function actions() {
        $(document).on("click", "a.btn-view", function(e) {
            e.preventDefault();
            var code = $(this).data("id");
            $.ajax({
                url: base_path + "smsnotification/edit",
                type: 'POST',
                data: {
                    'code': code,
                },
                dataType: "JSON",
                success: function(response) {
                    var obj = response;
                    if (obj.status) {
                        $('#modal_label').text('View Template');
                        $('#btnSave').addClass('d-none');
                        $('#templateName').prop('disabled', true);
                        $('#template').prop('disabled', true);
                        $("#isActive").prop("disabled", true);
                        $('#templateName').val(obj.templateName);
                        $("#template").val(obj.template);
                        $('#subject').val(obj.template);
                        if (obj.isActive == 1) {
                            $("#isActive").prop("checked", true);
                        }
                        $('#dataModal').modal('show');
                    } else {
                        toastr.warning('Failed to fetch details', 'Opps', {
                            progressBar: true
                        })
                    }
                },
                error: function() {
                    toastr.error('Something Wend Wrong', 'Opps', {
                        progressBar: true
                    })
                }
            });
        });
        $(document).on("click", "a.btn-edit", function(e) {
            e.preventDefault();
            var code = $(this).data("id");
            $.ajax({
                url: base_path + "smsnotification/edit",
                type: 'POST',
                data: {
                    'code': code,
                },
                dataType: "JSON",
                success: function(response) {
                    var obj = response;
                    if (obj.status) {
                        $('#templateForm').parsley().destroy();
                        $('#btnSave').removeClass('d-none').text('Update');
                        $('#modal_label').text('Edit Template');
                        $('#template').prop('disabled', false)
                        $("#isActive").prop("disabled", false);
                        $('#templateName').val(obj.templateName);
                        $('#code').val(obj.code);
                        $('#template').val(obj.template);
                        if (obj.isActive == 1) {
                            $("#isActive").prop("checked", true);
                        }
                        $('#dataModal').modal('show');
                    } else {
                        toastr.warning('Failed to fetch details', 'Opps', {
                            progressBar: true
                        });
                    }
                },
                error: function() {
                    toastr.error('Something Wend Wrong', 'Opps', {
                        progressBar: true
                    });
                }
            });
        });
        $(document).on("click", "a.btn-delete", function(e) {
            e.preventDefault();
            var code = $(this).data('id');
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
                        url: base_path + "smsnotification/delete",
                        type: 'POST',
                        data: {
                            'code': code
                        },
                        success: function(data) {
                            swal.close();
                            if (data) {
                                toastr.success('Email Template deleted successfully', 'Success', {
                                    "progressBar": true
                                });
                                loadTable();
                            } else {
                                toastr.error('Email Template not deleted', 'Failed', {
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

    $("#templateForm").submit(function(e) {
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
                url: base_path + "smsnotification/save",
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#btnSave').prop('disabled', true);
                    $('#btnSave').text('Please wait..');
                    $('#btnClose').prop('disabled', true);
                },
                success: function(response) {
                    $('#btnSave').prop('disabled', false);
                    if ($('#code').val() != '') {
                        $('#btnSave').text('Update');
                    } else {
                        $('#btnSave').text('Save');
                    }
                    $('#btnClose').prop('disabled', false);
                    var obj = JSON.parse(response);
                    if (obj.status) {
                        toastr.success(obj.message, 'Success', {
                            "progressBar": true
                        });
                        loadTable()
                        if ($('#code').val() != '') {
                            $('#dataModal').modal('hide');
                        } else {
                            $('#code').val('');
                            $('#templateName').val('');
                            $('#template').val('');
                        }
                    } else {
                        toastr.error(obj.message, 'Sms Tempalte', {
                            "progressBar": true
                        });
                    }
                }
            });
        }
    });

    function loadTable() {
        if ($.fn.DataTable.isDataTable("#dbtable")) {
            $('#dbtable').DataTable().clear().destroy();
        }
        var dataTable = $('#dbtable').DataTable({
            stateSave: false,
            "processing": true,
            "serverSide": true,
            "order": [],
            "searching": true,
            "ajax": {
                url: base_path + "smsnotification/list",
                type: "GET",
                data: {},
                "complete": function(response) {
                    actions();
                }
            }
        });
    }
</script>