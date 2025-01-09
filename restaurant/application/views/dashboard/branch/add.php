<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Branch</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../Dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Branch</li>
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
                            <a id="add_category" href="#generl_modal" data-bs-toggle="modal" data-bs-target="#generl_modal">
                                <i class="fa fa-plus-circle cursor_pointer"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="row">
            <section class="section col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                                <h5>Branch List</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" id="datatableBranch">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Tax Group</th>
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
        <!-- Basic Tables end -->
    </div>
</div>
</div>
</div>
<div class="modal fade text-left" id="generl_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content mb-5">
            <div class="modal-header">
                <h5>Add Branch</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body">
                                <form id="branchForm" class="form" data-parsley-validate>
                                    <div class="row">


                                        <div class="row col-md-12 mb-2 col-12">


                                            <div class="form-group col-md-6 col-12 mandatory">
                                                <label for="category-name-column" class="form-label">Name</label>
                                                <input type="text" id="branchName" class="form-control" placeholder="Branch Name" name="branchName" required>
                                            </div>

                                            <div class="form-group col-md-6 col-12 mandatory">
                                                <label for="category-name-column" class="form-label">Tax Group</label>
                                                <select class="form-select select2" style="width:100%" name="taxGroup" id="taxGroup" required>
                                                    <option value="">Select</option>
                                                    <?php
                                                    if ($taxGroups) {
                                                        foreach ($taxGroups->result() as $tc) {
                                                            echo "<option value='" . $tc->code . "'>" . $tc->taxGroupName . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                        </div>



                                        <div class="row col-md-12 mb-2 col-12">

                                            <div class="form-group col-md-6 col-12">
                                                <label for="category-name-column" class="col-md-4 text-nowrap form-label text-left">Tax Registration Name</label>
                                                <input type="text" id="branchTaxRegName" class="form-control" name="branchTaxRegName">
                                            </div>


                                            <div class="form-group col-md-6 col-12">
                                                <label for="category-name-column" class="col-md-4 text-nowrap form-label text-left">Tax Number</label>
                                                <input type="text" id="branchTaxRegNo" class="form-control" name="branchTaxRegNo">
                                            </div>


                                        </div>


                                        <div class="col-md-12 col-12 mb-2 row">

                                            <div class="form-group col-md-6 col-12">
                                                <label for="category-name-column" class="col-md-4 text-nowrap form-label text-left">Opening From</label>
                                                <input type="time" id="openingFrom" class="form-control" name="openingFrom">
                                            </div>


                                            <div class="form-group col-md-6 col-12">
                                                <label for="category-name-column" class="col-md-4 text-nowrap form-label text-left">Opening To</label>
                                                <input type="time" id="openingTo" onchange="validateToTime()" class="form-control" name="openingTo">
                                            </div>


                                        </div>


                                        <div class="col-md-12 col-12 mb-2 row">

                                            <div class="form-group col-md-12 col-12">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Phone</label>
                                                <input type="text" id="branchPhoneNo" class="form-control" name="branchPhoneNo">
                                            </div>

                                        </div>



                                        <div class="col-md-12 col-12 mb-2 row">

                                            <div class="form-group col-md-6 col-12">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Latitude</label>
                                                <input type="text" id="branchLat" class="form-control" name="branchLat">
                                            </div>

                                            <div class="form-group col-md-6 col-12">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Longitude</label>
                                                <input type="text" id="branchLong" class="form-control" name="branchLong">
                                            </div>

                                        </div>


                                        <div class="col-md-12 col-12 mb-2 row">

                                            <div class="form-group col-md-12 col-12">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Address</label>
                                                <textarea rows="3" id="branchAddress" class="form-control" name="branchAddress"></textarea>
                                            </div>

                                        </div>


                                        <div class="col-md-12 col-12 mb-2 row">

                                            <div class="form-group col-md-6 col-12">
                                                <label for="category-name-column" class="col-md-4 text-nowrap form-label text-left">Receipt Header</label>
                                                <textarea rows="3" id="receiptHead" class="form-control" name="receiptHead"></textarea>
                                            </div>

                                            <div class="form-group col-md-6 col-12">
                                                <label for="category-name-column" class="col-md-4 text-nowrap form-label text-left">Receipt Foot</label>
                                                <textarea rows="3" id="receiptFoot" class="form-control" name="receiptFoot"></textarea>
                                            </div>

                                        </div>



                                        <div class="form-group d-flex col-md-12 col-12 text-center items-center justify-content-center row">
                                            <!--<div class="form-group row">-->
                                            <label for="status" class="col-sm-2 form-label">Active</label>
                                            <!--<div class="col-sm-8 checkbox">-->
                                            <input type="checkbox" name="isActive" id="isActive" checked class=" " style="width:25px; height:25px">
                                            <!--</div>-->
                                            <!--</div>-->
                                        </div>



                                    </div>

                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <?php if ($insertRights == 1) { ?>
                                                <button type="submit" class="btn btn-primary" id="saveBranchBtn">Save</button>
                                            <?php } ?>
                                            <button type="button" class="btn btn-light-secondary" id="closeBranchBtn" data-bs-dismiss="modal">Close</button>
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
<div class="modal fade text-left" id="generl_modal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Update Branch</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body1">

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
        $('#taxGroup').select2({
            dropdownParent: $('#generl_modal')
        });
    });

    function loadTable() {
        if ($.fn.DataTable.isDataTable("#datatableBranch")) {
            $('#datatableBranch').DataTable().clear().destroy();
        }
        var dataTable = $('#datatableBranch').DataTable({
            stateSave: true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "searching": true,
            "ajax": {
                url: base_path + "branch/getBranchList",
                type: "GET",
                "complete": function(response) {
                    $('.edit_branch').click(function() {
                        var code = $(this).data('seq');
                        $.ajax({
                            url: base_path + "branch/edit",
                            type: 'POST',
                            data: {
                                'code': code,
                            },
                            success: function(response) {
                                $('#generl_modal1').modal('show');
                                $(".panel-body1").html(response);
                                $('#modaltaxGroup').select2({
                                    dropdownParent: $('#generl_modal1')
                                });
                            }
                        });
                    });
                    $('.delete_branch').on("click", function() {
                        var code = $(this).data('seq');
                        swal({
                            //title: "You want to delete branch "+code,
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
                                    url: base_path + "branch/deleteBranch",
                                    type: 'POST',
                                    data: {
                                        'code': code
                                    },
                                    beforeSend: function() {

                                    },
                                    success: function(data) {
                                        swal.close();
                                        if (data) {
                                            toastr.success('Branch deleted successfully', 'Branch', {
                                                "progressBar": true
                                            });
                                            loadTable();
                                        } else {
                                            toastr.error('Branch not deleted', 'Branch', {
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

    $("#branchForm").submit(function(e) {
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
                url: base_path + "branch/saveBranch",
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#saveBranchBtn').prop('disabled', true);
                    $('#saveBranchBtn').text('Please wait..');
                    $('#closeBranchBtn').prop('disabled', true);
                },
                success: function(response) {
                    $('#saveBranchBtn').prop('disabled', false);
                    $('#saveBranchBtn').text('Save');
                    $('#closeBranchBtn').prop('disabled', false);
                    var obj = JSON.parse(response);
                    if (obj.status) {
                        $('#generl_modal1').modal('hide');
                        toastr.success(obj.message, 'Branch', {
                            "progressBar": true
                        });
                        loadTable()
                        $('#branchName').val('');
                        $("#taxGroup").val('').trigger('change');
                        $('#branchTaxRegNo').val('');
                        $('#branchTaxRegName').val('');
                        $('#openingFrom').val('');
                        $('#openingTo').val('');
                        $('#branchPhoneNo').val('');
                        $('#branchAddress').val('');
                        $('#branchLat').val('');
                        $('#branchLong').val('');
                        $('#receiptHead').val('');
                        $('#receiptFoot').val('');
                    } else {
                        toastr.error(obj.message, 'Branch', {
                            "progressBar": true
                        });
                    }
                }
            });
        }
    });
    $(document).on("click", "button#updateBranchBtn", function(e) {
        $('#updateBranchForm').parsley();
        const form = document.getElementById('updateBranchForm');
        var formData = new FormData(form);
        var isValid = true;
        e.preventDefault();
        $("#updateBranchForm .form-control").each(function(e) {
            if ($(this).parsley().validate() !== true) isValid = false;
        });
        $("#updateBranchForm .form-select").each(function(e) {
            if ($(this).parsley().validate() !== true) isValid = false;
        });
        if (isValid) {
            var isActive = 0;
            var openingFrom = $('#modalopeningFrom').val();
            var openingTo = $('#modalopeningTo').val();
            if (openingFrom != '' && openingTo != '' && openingTo <= openingFrom) {
                toastr.error('Opening to time should be greater than from time', 'Branch', {
                    "progressBar": true
                });
                $('#modalopeningTo').focus();
                return false;
            }
            if ($("#modalisActive").is(':checked')) {
                isActive = 1;
            }
            formData.append('modalisActive', isActive);
            $.ajax({
                url: base_path + "branch/updateBranch",
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#updateBranchBtn').prop('disabled', true);
                    $('#updateBranchBtn').text('Please wait..');
                    $('#closeBranchBtn').prop('disabled', true);
                },
                success: function(response) {
                    $('#updateBranchBtn').prop('disabled', false);
                    $('#updateBranchBtn').text('Update');
                    $('#closeBranchBtn').prop('disabled', false);
                    var obj = JSON.parse(response);
                    if (obj.status) {
                        toastr.success(obj.message, 'Branch', {
                            "progressBar": true
                        });
                        loadTable()
                        $('#generl_modal1').modal('hide');
                        $(".panel-body1").html('');
                    } else {
                        toastr.error(obj.message, 'Branch', {
                            "progressBar": true
                        });
                    }
                }
            });
        }
    });

    function validateToTime() {
        var openingTo = $('#openingTo').val();
        var openingFrom = $('#openingFrom').val();
        if (openingTo <= openingFrom && openingTo != "" && openingFrom != "") {
            toastr.error("Opening to time should be greater than opening from time", 'Branch', {
                "progressBar": true
            });
            $('#openingTo').val('')
            return false;
        }
    }
</script>