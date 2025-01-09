<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Sector Zone</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../Dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Sector Zone</li>
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
                            <a id="add_category" class="add_zone"><i class="fa fa-plus-circle cursor_pointer"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <section class="section items-center justify-content-center">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                            <h5>Sector Zone List</h5>
                        </div>

                    </div>
                </div>
                <div class="card-body" id="print_div">
                    <table class="table table-striped" id="datatableTable">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Branch</th>
                                <th>Zone Name</th>
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
                <h5 id='modal_label'>Add Sector Zone</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body1">
                                <form id="zoneForm" class="form" data-parsley-validate>
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Branch</label>
                                                <div class="col-md-8">
                                                    <?php if ($branchCode != "") { ?>
                                                        <input type="text" class="form-control" name="branchCode" value="<?= $branchName; ?>" readonly>
                                                    <?php } else { ?>
                                                        <select class="form-select select2 validate" style="width:100%" id="branchCode" name="branchCode" required>
                                                            <option value="">Select</option>
                                                            <?php
                                                            if ($branches) {
                                                                foreach ($branches->result() as $br) {
                                                                    echo "<option value='" . $br->code . "'>" . $br->branchName . "</option>'";
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Zone Name</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="zoneName" class="form-control validate" placeholder="Enter zone name" name="zoneName" required>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group d-flex mt-3 col-md-12 col-12 text-center items-center justify-content-center row">
                                            <!--<div class="form-group row ">-->
                                            <label for="status" class="col-sm-2 form-label">Active</label>
                                            <!--<div class="col-sm-8 checkbox">-->
                                            <input type="checkbox" name="isActive" checked id="isActive" class=" " style="width:25px; height:25px">
                                        </div>
                                        <!--</div>-->
                                    </div>


                            </div>
                            <div class="row">
                                <div class="col-12 d-flex justify-content-end">
                                    <input type="hidden" class="form-control validate" id="code" name="code">
                                    <button type="submit" class="btn btn-primary" id="saveTableZoneBtn">Save</button>
                                    <button type="button" class="btn btn-light-secondary" id="closeTableZoneBtn" data-bs-dismiss="modal">Close</button>
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
        $('#branchCode').select2({
            dropdownParent: $('#generl_modal')
        });
    });

    $('.add_zone').click(function() {
        $('#generl_modal').modal('show');
        $('#zoneForm').parsley().destroy();
        $('#modal_label').text('Add Sector Zone');
        $('#saveTableZoneBtn').removeClass('d-none');
        $('#saveTableZoneBtn').text('Save');
        $('#code').val('');
        $("#branchCode").val(null).trigger('change.select2');
        $('#zoneName').val('');
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
                url: base_path + "sectorzone/getSectorzoneList",
                type: "GET",
                "complete": function(response) {
                    $('.edit_zone').click(function() {
                        var code = $(this).data('seq');
                        var type = $(this).data('type');
                        $.ajax({
                            url: base_path + "sectorzone/editZone",
                            type: 'POST',
                            data: {
                                'code': code,
                            },
                            success: function(response) {
                                var obj = JSON.parse(response);
                                if (obj.status) {
                                    $('#zoneForm').parsley().destroy();
                                    $('#generl_modal').modal('show');
                                    if (type == 1) {
                                        $('#modal_label').text('View Sector Zone');
                                        $('#saveTableZoneBtn').addClass('d-none');
                                    } else {
                                        $('#modal_label').text('Update Sector Zone');
                                        $('#saveTableZoneBtn').removeClass('d-none');
                                        $('#saveTableZoneBtn').text('Update');
                                    }
                                    $('#code').val(obj.id);
                                    $('#branchCode').val(obj.branchCode).select2();
                                    $('#zoneName').val(obj.zoneName);
                                    if (obj.isActive == 1) {
                                        $("#isActive").prop("checked", true);
                                    }
                                } else {
                                    toastr.error('Something Wend Wrong', 'Edit Table Zone', {
                                        progressBar: true
                                    })
                                }
                            }
                        });
                    });
                    $('.delete_zone').on("click", function() {
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
                                    url: base_path + "sectorzone/deleteZone",
                                    type: 'POST',
                                    data: {
                                        'code': code
                                    },
                                    success: function(data) {
                                        swal.close();
                                        if (data) {
                                            loadTable();
                                            toastr.success('Sector Zone deleted successfully', 'Table Zone', {
                                                "progressBar": true
                                            });
                                        } else {
                                            toastr.error('Sector Zone not deleted', 'Table Zone', {
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

    $("#zoneForm").submit(function(e) {
        e.preventDefault();
        $('#zoneForm').parsley();
        var isValid = true;
        event.preventDefault();
        $("#zoneForm .validate").each(function(e) {
            if ($(this).parsley().validate() !== true) isValid = false;
        });
        if (isValid) {
            let formData = new FormData();
            formData.append("branchCode", $("#branchCode").val());
            formData.append("code", $("#code").val());
            formData.append("zoneName", $("#zoneName").val());
            var isActive = 0;
            if ($("#isActive").is(':checked')) {
                isActive = 1;
            }
            formData.append('isActive', isActive);
            $.ajax({
                url: base_path + "sectorzone/saveZone",
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "JSON",
                beforeSend: function() {
                    $('#saveTableZoneBtn').prop('disabled', true);
                    $('#saveTableZoneBtn').text('Please wait..');
                    $('#closeTableZoneBtn').prop('disabled', true);
                },
                success: function(response) {
                    $('#saveTableZoneBtn').prop('disabled', false);
                    if ($('#code').val() != '') {
                        $('#saveTableZoneBtn').text('Update');
                    } else {
                        $('#saveTableZoneBtn').text('Save');
                    }
                    $('#closeTableZoneBtn').prop('disabled', false);
                    if (response.status) {
                        toastr.success(response.message, 'Sector Zone', {
                            "progressBar": true,
                            "onHidden": function() {
                                window.location.href = base_path + "Sectorzone/listRecords";
                                if ($('#code').val() != '') {
                                    $('#generl_modal').modal('hide');
                                } else {
                                    $('#code').val('');
                                    $("#branchCode").val(null).trigger('change');
                                    $('#zoneName').val('');
                                }
                                loadTable();

                            }
                        });
                    } else {
                        toastr.error(response.message, 'Sector Zone', {
                            "progressBar": true
                        });
                    }
                    $('#zoneForm').parsley("refresh");
                },
                error: function() {
                    toastr.error(response.message, 'Sector Zone', {
                        "progressBar": true,
                        "onHidden": function() {
                            $('#saveTableZoneBtn').prop('disabled', true);
                            if ($('#code').val() != '') {
                                $('#saveTableZoneBtn').text('Update');
                            } else {
                                $('#saveTableZoneBtn').text('Save');
                            }
                            $('#closeTableZoneBtn').prop('disabled', true);
                        }
                    });
                }
            });
        }
    });
</script>