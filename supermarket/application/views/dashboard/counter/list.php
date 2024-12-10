<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Counter</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><i class="fa fa-dashboard"></i> Dashboard</li>
                            <li class="breadcrumb-item active" aria-current="page">Counter</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
		<?php if($insertRights==1){ ?>
        <div id="maindiv" class="container">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                    <h2><a class="add_counter"><i class="fa fa-plus-circle cursor_pointer"></i></a></h2>
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
                            <h5>Counter List</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="print_div">
                    <table class="table table-striped" id="datatableCounter">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Code</th>
                                <th>Branch</th>
                                <th>Counter Name</th>
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
                <h5 id='modal_label'>Add Counter</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body1">
                                <form id="counterForm" class="form" data-parsley-validate>
                                    <div class="row">
									   <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory">
                                                <label  class="col-md-4 form-label text-left">Branch</label>
                                                <div class="col-md-8">
												    <?php if($branchCode!=""){?>		
														  <input type="text" class="form-control" name="branchName" value="<?= $branchName; ?>" readonly>
													<?php } else{?>
                                                    <select class="form-select select2" style="width:100%" name="branchCode" id="branchCode" required>
                                                      
                                                    </select>
													<?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory" id="nameDiv">
                                                <label class="col-md-4 form-label text-left">Name</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="counterName" class="form-control" onkeypress="return isalphanumeric(event)" placeholder="Enter Name" name="counterName" required>
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
                                            <input type="hidden" class="form-control" id="counterCode" name="counterCode">
                                            <button type="submit" class="btn btn-primary white me-2 mb-1 sub_1" id="saveCounterBtn">Save</button>
                                            <button type="button" class="btn btn-light-secondary me-1 mb-1" id="closeCounterBtn" data-bs-dismiss="modal">Close</button>
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
		$("#branchCode").select2({
			     dropdownParent: $('#generl_modal .modal-content'),
				 placeholder: "Select Branch",
                 allowClear: true,
				ajax: {
					url:  base_path+'Common/getBranch', 
					type: "get",
					delay:250,
					dataType: 'json',
					data: function (params) {
						var query = {
                            search: params.term
                          }
                          return query;
					}, 
					processResults: function (response) {
						return {
							results: response
						};
					},
					cache: true
				}	
		  });
        loadCounter();
    });
    $('.add_counter').click(function() {
        $('#counterForm').parsley().destroy();
        $('#generl_modal').modal('show');
        $('#saveCounterBtn').removeClass('d-none');
        $('#modal_label').text('Add Counter');
        $('#saveCounterBtn').text('Save');
        $('#counterCode').val('');
        $("#branchCode").val(null).trigger('change.select2');
        $('#counterName').val('');
        $("#isActive").prop("checked", true);
    });
	function isalphanumeric(e) {
        var keyCode = e.keyCode == 0 ? e.charCode : e.keyCode;
		var ret = ((keyCode >= 48 && keyCode <= 57) || (keyCode >= 65 && keyCode <= 90) || (keyCode >= 97 && keyCode <=122) || (keyCode == 32));
			return ret;
    }
    function loadCounter() {
        if ($.fn.DataTable.isDataTable("#datatableCounter")) {
            $('#datatableCounter').DataTable().clear().destroy();
        }
        var dataTable = $('#datatableCounter').DataTable({
            stateSave: true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "searching": true,
            "ajax": {
                url: base_path + "counter/getCounterList",
                type: "GET",
                "complete": function(response) {
                    $('.edit_counter').click(function() { 	
                        var code = $(this).data('seq');
                        var type = $(this).data('type');
                        $.ajax({
                            url: base_path + "counter/editCounter",
                            type: 'POST',
                            data: {
                                'code': code,
                            },
                            success: function(response) {
                                var obj = JSON.parse(response);
                                if (obj.status) {
                                    $('#counterForm').parsley().destroy();
                                    $('#generl_modal').modal('show');
                                    if (type == 1) {
                                        $('#modal_label').text('View Counter');
                                        $('#saveCounterBtn').addClass('d-none');
                                    } else {
                                        $('#saveCounterBtn').removeClass('d-none');
                                        $('#modal_label').text('Update Counter');
                                        $('#saveCounterBtn').text('Update');
                                    }              
                                    $('#counterCode').val(obj.code);
									//$("#branchCode").select2("data", { id: obj.branchCode, text: obj.branchName }); 
                                    //$('#branchCode').val(obj.branchCode).select2(); 
									var newOption = new Option(obj.branchName, obj.branchCode, true, true);
									// Append it to the select
									$('#branchCode').append(newOption).trigger('change'); 
                                    $('#counterName').val(obj.counterName);
									
									$("#branchCode").select2({
										 dropdownParent: $('#generl_modal .modal-content'),
										 placeholder: "Select Branch",
										 allowClear: true,
										ajax: {
											url:  base_path+'Common/getBranch', 
											type: "get",
											delay:250,
											dataType: 'json',
											data: function (params) {
												var query = {
													search: params.term
												  }
												  return query;
											}, 
											processResults: function (response) {
												return {
													results: response
												};
											},
											cache: true
										}	
								  });
									
                                    if (obj.isActive == 1) {
                                        $("#isActive").prop("checked", true);
                                    }
                                } else {
                                    toastr.error('Something Wend Wrong', 'Edit Counter', {
                                        progressBar: true
                                    })
                                }
                            }
                        });
                    });
                    $('.delete_counter').on("click", function() {
                        var code = $(this).data('seq');
                        swal({
                            //title: "You want to delete Counter "+code,
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
                                    url: base_path + "counter/deleteCounter",
                                    type: 'POST',
                                    data: {
                                        'code': code
                                    },
                                    beforeSend: function() {

                                    },
                                    success: function(data) {
                                        swal.close();
                                        if (data) {
                                            loadCounter();
                                            toastr.success('Counter deleted successfully', 'Counter', {
                                                "progressBar": true
                                            });
                                        } else {
                                            toastr.error('Counter not deleted', 'Counter', {
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
	
    $("#counterForm").submit(function(e) {
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
                url: base_path + "counter/saveCounter",
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#saveCounterBtn').prop('disabled', true);
                    $('#saveCounterBtn').text('Please wait..');
                    $('#closeCounterBtn').prop('disabled', true);
                },
                success: function(response) {
                    $('#saveCounterBtn').prop('disabled', false);
                    if ($('#counterCode').val() != '') {
                        $('#saveCounterBtn').text('Update');
                    } else {
                        $('#saveCounterBtn').text('Save');
                    }
                    $('#closeCounterBtn').prop('disabled', false);
                    var obj = JSON.parse(response);
                    if (obj.status) {
                        toastr.success(obj.message, 'Counter', {
                            "progressBar": true
                        });
                        loadCounter()
                        if ($('#counterCode').val() != '') {
                            $('#generl_modal').modal('hide');
                        } else {
                            $('#counterCode').val('');
                            $("#branchCode").val(null).trigger('change.select2');
                            $('#counterName').val('');
                        }
                    } else {
                        toastr.error(obj.message, 'Counter', {
                            "progressBar": true
                        });
                    }
                }
            });
        }
    });
</script>