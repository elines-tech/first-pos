<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Customer Groups</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Customer Groups</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            <div id="maindiv1" class="col-12 col-md-5">
                <div class="card">
                    <div class="card-header">
                        <div class="col-12 order-md-1 order-last" id="leftdiv">
                            <h5><i class="fa fa-plus-circle"></i> Add Customer Groups</h5>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="card-body" id="appendForm">
                        </div>
                    </div>
                </div>
            </div>
            <section class="section col-12 col-md-7">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                                <h5>Customer Group List</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" id="customerGroup">
                            <thead>
                                <tr>
                                    <th>Srno</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<div class="modal fade text-left" id="generl_modal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5>View Customer Group</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body panel-body2">
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
        loadGroup();
        onloadAddForm();
        var data = '<?php echo $error; ?>';
        if (data != '') {
            var obj = JSON.parse(data);
            if (obj.status) {
                toastr.success(obj.message, 'Customer Group', {
                    "progressBar": true
                });
            } else {
                toastr.error(obj.message, 'Customer Group', {
                    "progressBar": true
                });
            }
        }
    });

    function onloadAddForm() {
        $('#error_message').html('');
        $.ajax({
            url: base_path + "CustomerGroup/add",
            method: "GET",
            datatype: "text",
            success: function(data) {
                if (data != "") {
                    $("#appendForm").html(data);
                    $("#maindiv1").show();
                } else {
                    $("#maindiv1").hide();
                }
            },
            complete: function() {
                // $( "form" ).on( "submit", function(e) {
                $("#saveGroupName").click(function() {
                    var groupName = $('#groupname').val();
                    if (groupName != "") {
                        var fd = new FormData();
                        var other_data = $('#saveGroupForm').serializeArray();
                        $.each(other_data, function(key, input) {
                            fd.append(input.name, input.value);
                        });
                        var isActive = 0;
                        if ($("#isActive").is(':checked')) {
                            isActive = 1;
                        }
                        fd.append('isActive', isActive);
                        $.ajax({
                            type: "POST",
                            url: base_path + "CustomerGroup/save",
                            enctype: 'multipart/form-data',
                            contentType: false,
                            processData: false,
                            data: fd,
                            success: function(data) {
                                var obj = JSON.parse(data);

                                if (obj.status == true) {
                                    toastr.success(obj.message, 'Group Customer', {
                                        "progressBar": true
                                    });
                                    loadGroup();
                                    onloadAddForm();
                                } else {
                                    toastr.error(obj.message, 'Group Customer', {
                                        "progressBar": true
                                    });
                                    loadGroup();
                                    onloadAddForm();
                                }
                            }
                        });
                    } else {
                        $('#err').html('Customer Groupname is required.');
                    }
                });
            }
        });
    }

    function loadGroup() {
        if ($.fn.DataTable.isDataTable("#customerGroup")) {
            $('#customerGroup').DataTable().clear().destroy();
        }
        var dataTable = $('#customerGroup').DataTable({
            stateSave: true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "searching": true,
            "ajax": {
                url: base_path + "CustomerGroup/getGroupList",
                type: "GET",
                "complete": function(response) {
                    $('.delete_group').on("click", function() {
                        var code = $(this).data('seq');
                        swal({
                            title: "You want to delete table " + code,
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
                                    url: base_path + "CustomerGroup/delete",
                                    type: 'POST',
                                    data: {
                                        'code': code
                                    },
                                    beforeSend: function() {

                                    },
                                    success: function(data) {
                                        swal.close();
                                        if (data) {
                                            loadGroup();
                                            toastr.success('Customer Group deleted successfully', 'Customer Group', {
                                                "progressBar": true
                                            });
                                        } else {
                                            toastr.error('Customer Group not deleted', 'Customer Group', {
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
                    $('.view_group').click(function() {
                        var code = $(this).data('seq');
                        $.ajax({
                            url: base_path + "CustomerGroup/view",
                            type: 'POST',
                            data: {
                                'code': code,
                            },
                            success: function(response) {
                                $('#generl_modal2').modal('show');
                                $(".panel-body2").html(response);
                            }
                        });
                    });
                    $(".edit_group").click(function() {
                        var code = $(this).data('seq');
                        $.ajax({
                            url: base_path + "CustomerGroup/edit",
                            method: "post",
                            data: {
                                code: code
                            },
                            datatype: "text",
                            success: function(data) {
                                $("#appendForm").html(data);
                            },
                            complete: function() {

                                $("#backToAdd").click(function() {
                                    onloadAddForm();
                                });
                                $("#updateGroupName").click(function() {
                                    var groupname = $('#groupname').val();
                                    if (groupname != "") {
                                        var fd = new FormData();
                                        var other_data = $('#updateGroupForm').serializeArray();
                                        $.each(other_data, function(key, input) {
                                            fd.append(input.name, input.value);
                                        });

                                        var isActive = 0;
                                        if ($("#isActive").is(':checked')) {
                                            isActive = 1;
                                        }
                                        fd.append('isActive', isActive);

                                        $.ajax({
                                            type: "POST",
                                            url: base_path + "CustomerGroup/update",
                                            enctype: 'multipart/form-data',
                                            contentType: false,
                                            processData: false,
                                            data: fd,
                                            success: function(data) {
                                                var obj = JSON.parse(data);
                                                if (obj.status == true) {
                                                    toastr.success(obj.message, 'Customer Group', {
                                                        "progressBar": true
                                                    });
                                                    loadGroup();
                                                    onloadAddForm();
                                                } else {
                                                    toastr.success(obj.message, 'Customer Group', {
                                                        "progressBar": true
                                                    });
                                                    loadGroup();
                                                    onloadAddForm();
                                                }
                                            }
                                        });
                                    } else {
                                        $('#err').html('Customer Group is required');
                                    }
                                });
                            }
                        });
                    });
                }
            }
        });
    }
</script>