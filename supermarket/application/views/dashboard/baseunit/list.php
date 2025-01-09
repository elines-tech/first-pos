<?php include '../supermarket/config.php'; ?>

<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3><?php echo $translations['Base Unit']?></h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../dashboard/listRecords"><i class="fa fa-dashboard"></i><?php echo $translations['Dashboard']?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $translations['Base Unit']?></li>
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
                            <h5><?php echo $translations['Base Unit List']?></h5>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="print_div">
                    <table class="table table-striped" id="datatableBaseUnit">
                        <thead>
                            <tr>
                                <th><?php echo $translations['Sr No']?></th>
                                <th><?php echo $translations['Code']?></th>
                                <th><?php echo $translations['Base Unit Name']?></th>
                                <th><?php echo $translations['Short Name']?></th>
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
</div>
</body>
<div class="modal fade text-left" id="generl_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id='modal_label'><?php echo $translations['Add Base Unit']?></h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body1">
                                <form id="baseunitForm" class="form" data-parsley-validate>
                                    <div class="row">


                                        <div class="form-group col-md-12 col-12 text-center mandatory" id="nameDiv">
                                            <label for="BaseUnit-name-column" class="form-label text-left"><?php echo $translations['Name']?></label>
                                            <input type="text" id="baseunitName" class="form-control" placeholder="Enter Name" name="baseunitName" required>
                                        </div>


                                        <div class="form-group col-md-12 col-12 text-center mandatory" id="shortNameDiv">
                                            <label for="Unit-name-column" class="form-label text-left"><?php echo $translations['Short Name']?></label>
                                            <input type="text" id="baseunitSName" class="form-control" maxlength="3" placeholder="Enter Short Name" name="baseunitSName" required>
                                        </div>


                                        <div class="form-group col-md-12 col-12 text-center">
                                            <label for="Unit-name-column" class="form-label text-left"><?php echo $translations['Description']?></label>
                                            <textarea id="description" rows="6" class="form-control" placeholder="" name="description"></textarea>
                                        </div>


                                        <div class="form-group d-flex col-md-12 col-12 text-center items-center justify-content-start row">
                                            <!--<div class="form-group row">-->
                                            <label for="status" class="col-sm-2 form-label"><?php echo $translations['Active']?></label>
                                            <!--<div class="col-sm-8 checkbox">-->
                                            <input type="checkbox" name="isActive" id="isActive" class="mt-2" style="width:25px; height:25px">
                                            <!--</div>-->
                                            <!--</div>-->
                                        </div>


                                    </div>

                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <input type="hidden" class="form-control" id="code" name="code">
                                            <button type="submit" class="btn btn-primary" id="saveUnitBtn"><?php echo $translations['Save']?></button>
                                            <button type="button" class="btn btn-light-secondary" id="closeUnitBtn" data-bs-dismiss="modal"><?php echo $translations['Close']?></button>
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
        loadBaseUnit();
    });
    $('.add_unit').click(function() {
        $('#baseunitForm').parsley().destroy();
        $('#generl_modal').modal('show');
        $('#saveUnitBtn').removeClass('d-none');
        $('#modal_label').text('Add Unit');
        $('#saveUnitBtn').text('Save');
        $('#code').val('');
        $('#baseunitName').val('');
        $('#baseunitSName').val('');
        $('#description').val('');
        $("#isActive").prop("checked", true);
    });

    function loadBaseUnit() {
        if ($.fn.DataTable.isDataTable("#datatableBaseUnit")) {
            $('#datatableBaseUnit').DataTable().clear().destroy();
        }
        var dataTable = $('#datatableBaseUnit').DataTable({
            stateSave: true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "searching": true,
            "ajax": {
                url: base_path + "baseunit/getBaseUnitList",
                type: "GET",
                "complete": function(response) {
                    $('.edit_baseunit').click(function() {
                        var code = $(this).data('seq');
                        var type = $(this).data('type');
                        $.ajax({
                            url: base_path + "baseunit/editBaseUnit",
                            type: 'POST',
                            data: {
                                'code': code,
                            },
                            success: function(response) {
                                var obj = JSON.parse(response);
                                if (obj.status) {
                                    $('#baseunitForm').parsley().destroy();
                                    $('#generl_modal').modal('show');
                                    if (type == 1) {
                                        $('#modal_label').text('View Base Unit');
                                        $('#saveUnitBtn').addClass('d-none');
                                    } else {
                                        $('#saveUnitBtn').removeClass('d-none');
                                        $('#modal_label').text('Update Unit');
                                        $('#saveUnitBtn').text('Update');
                                    }
                                    $('#nameDiv').removeClass('is-invalid');
                                    $('#shortNameDiv').removeClass('is-invalid');
                                    $('#code').val(obj.code);
                                    $('#baseunitName').val(obj.baseunitName);
                                    $('#baseunitSName').val(obj.baseunitSName);
                                    $('#description').val(obj.description);
                                    if (obj.isActive == 1) {
                                        $("#isActive").prop("checked", true);
                                    }
                                } else {
                                    toastr.error('Something Wend Wrong', 'Edit Base Unit', {
                                        progressBar: true
                                    })
                                }
                            }
                        });
                    });
                    $('.delete_baseunit').on("click", function() {
                        var code = $(this).data('seq');
                        swal({
                            //title: "You want to delete Unit "+code,
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
                                    url: base_path + "baseunit/deleteBaseUnit",
                                    type: 'POST',
                                    data: {
                                        'code': code
                                    },
                                    beforeSend: function() {

                                    },
                                    success: function(data) {
                                        swal.close();
                                        if (data) {
                                            loadBaseUnit();
                                            toastr.success('Base Unit deleted successfully', 'Base Unit', {
                                                "progressBar": true
                                            });
                                        } else {
                                            toastr.error('Base Unit not deleted', 'Base Unit', {
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

    $("#baseunitForm").submit(function(e) {
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
                url: base_path + "baseunit/saveBaseUnit",
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
                        toastr.success(obj.message, 'Base Unit', {
                            "progressBar": true
                        });
                        loadBaseUnit()
                        if ($('#code').val() != '') {
                            $('#generl_modal').modal('hide');
                        } else {
                            $('#code').val('');
                            $('#baseunitName').val('');
                            $('#baseunitSName').val('');
                            $('#description').val('');
                            $('#isActive').prop('checked', false)
                        }
                    } else {
                        toastr.error(obj.message, 'Base Unit', {
                            "progressBar": true
                        });
                    }
                }
            });
        }
    });
</script>