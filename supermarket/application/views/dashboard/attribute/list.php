<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Attribute</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Attribute</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div id="maindiv" class="container">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                    <h2><a class="add_option"><i class="fa fa-plus-circle cursor_pointer"></i></a></h2>
                </div>
            </div>
        </div>
        <!-- Basic Tables start -->
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                            <h5>Attribute List</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="print_div">
                    <table class="table table-striped" id="datatableOption">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Code</th>
                                <th>Attribute Name</th>
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
                <h5 id='modal_label'>Add Attribute</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body1">
                                <form id="optionForm" class="form" data-parsley-validate>
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory" id="nameDiv">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Name</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="option" class="form-control" placeholder="Option Name" name="option" required>
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
                                            <button type="submit" class="btn btn-primary white me-2 mb-1 sub_1" id="saveOptionBtn">Save</button>
                                            <button type="button" class="btn btn-light-secondary me-1 mb-1" id="closeOptionBtn" data-bs-dismiss="modal">Close</button> 
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
        loadOption();
    });

    $('.add_option').click(function() {
        $('#optionForm').parsley().destroy();
        $('#generl_modal').modal('show');
        $('#modal_label').text('Add Attribute');
        $('#saveOptionBtn').text('Save');
        $('#code').val('');
        $('#saveOptionBtn').removeClass('d-none');
        $('#option').val('');
        $("#isActive").prop("checked", true);
    });

    function loadOption() {
        if ($.fn.DataTable.isDataTable("#datatableOption")) {
            $('#datatableOption').DataTable().clear().destroy();
        }
        var dataTable = $('#datatableOption').DataTable({
            stateSave: true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "searching": true,
            "ajax": {
                url: base_path + "attributes/getAttributeList",
                type: "GET",
                "complete": function(response) {
                    $('.edit_option').click(function() {
                        var code = $(this).data('seq');
                        var type = $(this).data('type');
                        $.ajax({
                            url: base_path + "attributes/editAttribute",
                            type: 'POST',
                            data: {
                                'code': code,
                            },
                            success: function(response) {
                                var obj = JSON.parse(response);
                                if (obj.status) {
                                    $('#optionForm').parsley().destroy();
                                    $('#generl_modal').modal('show');
                                    if (type == 1) {
                                        $('#modal_label').text('View Attribute');
                                        $('#saveOptionBtn').addClass('d-none');
										$('#option').prop('disabled', true);
										$('#isActive').prop('disabled', true);
                                    } else {
                                        $('#saveOptionBtn').removeClass('d-none');
                                        $('#modal_label').text('Update Attribute');
                                        $('#saveOptionBtn').text('Update');
										$('#option').prop('disabled', false);
										$('#isActive').prop('disabled', false);
                                    }

                                    $('#nameDiv').removeClass('is-invalid');
                                    $('#code').val(obj.code);
                                    $('#option').val(obj.attributeName);
                                    if (obj.isActive == 1) {
                                        $("#isActive").prop("checked", true);
                                    }
                                } else {
                                    toastr('Something Wend Wrong', 'Edit Option', {
                                        progressBar: true
                                    })
                                }
                            }
                        });
                    });
                    $('.delete_option').on("click", function() {
                        var code = $(this).data('seq');
                        swal({
                            //title: "You want to delete category "+code,
                            title: "<?php echo $translations['Are you sure you want to delete this?']?>",
                            type: "warning",
                            showCancelButton: !0,
                            confirmButtonColor: "#DD6B55",
                            //cancelButtonColor: "#DD6B55",
                            confirmButtonText: "<?php echo $translations['Yes, delete it!']?>",
                            cancelButtonText: "<?php echo $translations['No, cancel it!']?>",
                            closeOnConfirm: !1,
                            closeOnCancel: !1
                        }, function(e) {
                            if (e) {
                                $.ajax({
                                    url: base_path + "attributes/deleteAttribute",
                                    type: 'POST',
                                    data: {
                                        'code': code
                                    },
                                    beforeSend: function() {

                                    },
                                    success: function(data) {
                                        swal.close();
                                        if (data) {
                                            toastr.success('Attribute deleted successfully', 'Attribute', {
                                                "progressBar": true
                                            });
                                            loadOption();
                                        } else {
                                            toastr.error('Attribute not deleted', 'Attribute', {
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

    $("#optionForm").submit(function(e) {
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
                url: base_path + "attributes/saveAttribute",
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#saveOptionBtn').prop('disabled', true);
                    $('#saveOptionBtn').text('Please wait..');
                    $('#closeOptionBtn').prop('disabled', true);
                },
                success: function(response) {
                    $('#saveOptionBtn').prop('disabled', false);
                    if ($('#code').val() != '') {
                        $('#saveOptionBtn').text('Update');
                    } else {
                        $('#saveOptionBtn').text('Save');
                    }
                    $('#closeOptionBtn').prop('disabled', false);
                    var obj = JSON.parse(response);
                    if (obj.status) {
                        toastr.success(obj.message, 'Option', {
                            "progressBar": true
                        });
                        loadOption()
                        if (code != '') {
                            $('#generl_modal').modal('hide');
							
                        } else {
                            $('#code').val('');
                            $('#option').val('');
                        }
                    } else {
                        toastr.error(obj.message, 'Option', {
                            "progressBar": true
                        });
                    }
                }
            });
        }
    });
</script>