<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Role</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Role</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
		<?php if($insertRights==1){ ?>
        <div id="maindiv" class="container">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                    <h2><a class="add_role"><i class="fa fa-plus-circle cursor_pointer"></i></a></h2>
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
                            <h5>Role List</h5>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first" id="rightdiv">
                            <div class="btn-group btn-group-sm float-lg-end" role="group" aria-label="Basic example">
                                <!--<button type="button" class="btn btn-primary sub_1" onclick="printDiv('print_div')">Print</button>-->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="print_div">
                    <table class="table table-striped" id="datatableRole">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Code</th>
                                <th>Role</th>
                                <th>Created</th>
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
                <h5 id='modal_label'>Add Role</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body1">
                                <form id="roleForm" class="form" data-parsley-validate>
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Role</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="role" class="form-control" placeholder="Enter role" name="role" required>
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
                                            <button type="submit" class="btn btn-primary white me-2 mb-1 sub_1" id="saveRoleBtn" onclick="save_role()">Save</button>
                                            <button type="button" class="btn btn-light-secondary me-1 mb-1" id="closeRoleBtn" data-bs-dismiss="modal">Close</button>
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

    $('.add_role').click(function() {
        $('#roleForm').parsley().destroy();
        $('#generl_modal').modal('show');
        $('#modal_label').text('Add Role');
        $('#saveRoleBtn').text('Save');
        $('#code').val('');
        $('#role').val('');
        $("#isActive").prop("checked", true);
    });

    function loadTable() {
        if ($.fn.DataTable.isDataTable("#datatableRole")) {
            $('#datatableRole').DataTable().clear().destroy();
        }
        var dataTable = $('#datatableRole').DataTable({
            stateSave: true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "searching": true,
            "ajax": {
                url: base_path + "role/getRoleList",
                type: "GET",
                "complete": function(response) {
                    $('.edit_role').click(function() {
                        var code = $(this).data('seq');
                        $.ajax({
                            url: base_path + "role/editRole",
                            type: 'POST',
                            data: {
                                'code': code,
                            },
                            success: function(response) {
                                var obj = JSON.parse(response);
                                if (obj.status) {
                                    $('#roleForm').parsley().destroy();
                                    $('#generl_modal').modal('show');
                                    $('#modal_label').text('Update Role');
                                    $('#saveRoleBtn').text('Update');
                                    $('#code').val(obj.code);
                                    $('#role').val(obj.role);
                                    if (obj.isActive == 1) {
                                        $("#isActive").prop("checked", true);
                                    }
                                } else {
                                    toastr.error('Something Wend Wrong', 'Edit Role', {
                                        progressBar: true
                                    })
                                }
                            }
                        });
                    });
                    $('.delete_role').on("click", function() {
                        var code = $(this).data('seq');
                        swal({
                            //title: "You want to delete Role "+code,
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
                                    url: base_path + "role/deleteRole",
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
                                            toastr.success('Role deleted successfully', 'Role', {
                                                "progressBar": true
                                            });
                                        } else {
                                            toastr.error('Role not deleted', 'Role', {
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

    $("#roleForm").submit(function(e) {
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
                url: base_path + "role/saveRole",
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#saveRoleBtn').prop('disabled', true);
                    $('#saveRoleBtn').text('Please wait..');
                    $('#closeRoleBtn').prop('disabled', true);
                },
                success: function(response) {
                    $('#saveRoleBtn').prop('disabled', false);
                    if (code != '') {
                        $('#saveRoleBtn').text('Update');
                    } else {
                        $('#saveRoleBtn').text('Save');
                    }
                    $('#closeRoleBtn').prop('disabled', false);
                    var obj = JSON.parse(response);
                    if (obj.status) {
                        toastr.success(obj.message, 'Role', {
                            "progressBar": true
                        });
                        loadTable()
                        if (code != '') {
                            $('#generl_modal').modal('hide');
                        } else {
                            $('#code').val('');
                            $('#role').val('');
                            $('#isActive').prop('checked', false);
                        }
                    } else {
                        toastr.error(obj.message, 'Role', {
                            "progressBar": true
                        });
                    }
                }
            });
        }
    })
</script>