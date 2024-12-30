<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Account Expense</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Account Expense</li>
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
                            <a id="add_category" class="add_account_expense"><i class="fa fa-plus-circle cursor_pointer"></i></a>
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
                            <h5>Account Expense List</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="print_div">
                    <table class="table table-striped" id="datatableAccountExpense">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Code</th>
                                <th>Date</th>
                                <th>Branch</th>
                                <th>Title</th>
                                <th>Cost</th>
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
                <h5 id='modal_label'>Add Account Expense</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body1">
                                <form id="accountExpenseForm" class="form" data-parsley-validate>
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Branch</label>
                                                <div class="col-md-8">
                                                    <?php if ($branchCode != "") { ?>
                                                        <input type="text" class="form-control" name="branchName" value="<?= $branchName; ?>" readonly>
                                                    <?php } else { ?>
                                                        <select class="form-select select2" style="width:100%" name="branch" id="branch" required data-parsley-required-message="Branch is required">
                                                            <option value="">Select</option>
                                                            <?php
                                                            if ($branch) {
                                                                foreach ($branch->result() as $br) {
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
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Date</label>
                                                <div class="col-md-8">
                                                    <input type="date" id="date" class="form-control" value="<?= date('Y-m-d') ?>" name="date" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory">
                                                <label for="expensename" class="col-md-4 form-label text-left">Expense Name:</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="expensename" class="form-control" placeholder="Expense Name" name="expensename" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory">
                                                <label for="expensecost" class="col-md-4 form-label text-left">Expense Cost:</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="expensecost" class="form-control" placeholder="Expense Cost" name="expensecost" onkeypress="return isDecimal(event,this)" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row">
                                                <label for="expensedescription" class="col-md-4 form-label text-left">Expense Description :</label>
                                                <div class="col-md-8">
                                                    <textarea id="expensedescription" rows="6" class="form-control" name="expensedescription"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <input type="hidden" class="form-control" id="code" name="code">
                                            <button type="submit" class="btn btn-primary" id="saveAccountExpenseBtn">Save</button>
                                            <button type="button" class="btn btn-light-secondary" id="closeAccountExpenseBtn" data-bs-dismiss="modal">Close</button>
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
                <h5 id='modal_label1'>View Account Expense Details</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body2">
                                <form id="viewcategoryForm">
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Branch</label>
                                                <div class="col-md-8">
                                                    <select class="form-select select2" style="width:100%" name="viewbranch" id="viewbranch" disabled>
                                                        <option value="">Select</option>
                                                        <?php
                                                        if ($branch) {
                                                            foreach ($branch->result() as $br) {
                                                                echo "<option value='" . $br->code . "'>" . $br->branchName . "</option>'";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Date</label>
                                                <div class="col-md-8">
                                                    <input type="date" id="viewdate" class="form-control" name="viewdate" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory">
                                                <label for="expensename" class="col-md-4 form-label text-left">Expense Name:</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="viewexpensename" class="form-control" placeholder="Expense Name" name="viewexpensename" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory">
                                                <label for="expensecost" class="col-md-4 form-label text-left">Expense Cost:</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="viewexpensecost" class="form-control" placeholder="Expense Cost" name="viewexpensecost" readonly onkeypress="return isDecimal(event,this)">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row">
                                                <label for="expensedescription" class="col-md-4 form-label text-left">Expense Description :</label>
                                                <div class="col-md-8">
                                                    <textarea id="viewexpensedescription" rows="6" class="form-control" name="viewexpensedescription" readonly></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <button id="cancelDefaultButton" type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
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
        loadAccountExpense();
    });

    function isDecimal(evt, element) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (
            (charCode != 46 || $(element).val().indexOf('.') != -1) && // “.” CHECK DOT, AND ONLY ONE.
            (charCode < 48 || charCode > 57))
            return false;
        return true;
    }
    $('.add_account_expense').click(function() {
        $('#accountExpenseForm').parsley().destroy();
        $('#generl_modal').modal('show');
        $('#modal_label').text('Add Account Expense');
        $('#saveAccountExpenseBtn').text('Save');
        $('#code').val('');
        $('#saveAccountExpenseBtn').removeClass('d-none');
        $("#branch").val(null).trigger('change.select2');
        $('#date').val("<?= date('Y-m-d') ?>");
        $('#expensename').val('');
        $('#expensecost').val('');
        $('#expensedescription').val();
    });

    function loadAccountExpense() {
        if ($.fn.DataTable.isDataTable("#datatableAccountExpense")) {
            $('#datatableAccountExpense').DataTable().clear().destroy();
        }

        var dataTable = $('#datatableAccountExpense').DataTable({
            stateSave: true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "searching": true,
            "ajax": {
                url: base_path + "AccountExpense/getAccountExpenseList",
                type: "GET",
                "complete": function(response) {
                    $('.edit_account_expense').click(function() {

                        var code = $(this).data('seq');
                        var type = $(this).data('type');
                        $.ajax({
                            url: base_path + "AccountExpense/editAccountExpense",
                            type: 'POST',
                            data: {
                                'code': code,
                            },
                            success: function(response) {

                                var obj = JSON.parse(response);
                                if (obj.status) {
                                    $('#accountExpenseForm').parsley().destroy();
                                    $('#generl_modal').modal('show');
                                    if (type == 2) {

                                        $('#saveAccountExpenseBtn').removeClass('d-none');
                                        $('#modal_label').text('Update Account Expense');
                                        $('#saveAccountExpenseBtn').text('Update');
                                    }
                                    $('#code').val(obj.code);
                                    $('#expensename').val(obj.expensename);
                                    $('#expensecost').val(obj.expensecost);
                                    $('#date').val(obj.date);
                                    $('#expensedescription').val(obj.expensedescription);
                                    $('#branch').empty();
                                    $('#branch').append(obj.branch);
                                } else {
                                    toastr('Something Wend Wrong', 'Account Expense', {
                                        progressBar: true
                                    })
                                }
                            }
                        });
                    });

                    $('.view_account_expense').click(function() {

                        var code = $(this).data('seq');
                        $.ajax({
                            url: base_path + "AccountExpense/editAccountExpense",
                            type: 'POST',
                            data: {
                                'code': code,
                            },
                            success: function(response) {

                                var obj = JSON.parse(response);
                                if (obj.status) {
                                    $('#generl_modal1').modal('show');
                                    $('#viewexpensename').val(obj.expensename);
                                    $('#viewexpensecost').val(obj.expensecost);
                                    $('#viewdate').val(obj.date);
                                    $('#viewexpensedescription').val(obj.expensedescription);
                                    $('#viewbranch').empty();
                                    $('#viewbranch').append(obj.branch);
                                } else {
                                    toastr('Something Wend Wrong', 'Account Expense', {
                                        progressBar: true
                                    })
                                }
                            }
                        });
                    });

                    $('.delete_account_expense').on("click", function() {
                        var code = $(this).data('seq');
                        swal({
                            //title: "You want to delete category "+code,
                            title: "Are you sure you want to delete this?",
                            type: "warning",
                            showCancelButton: !0,
                            confirmButtonColor: "#DD6B55",
                            //cancelButtonColor: "#DD6B55",
                            confirmButtonText: "Yes, delete it!",
                            cancelButtonText: "No, cancel it!",
                            closeOnConfirm: !1,
                            closeOnCancel: !1
                        }, function(e) {
                            if (e) {
                                $.ajax({
                                    url: base_path + "AccountExpense/deleteAccountExpense",
                                    type: 'POST',
                                    data: {
                                        'code': code
                                    },
                                    beforeSend: function() {

                                    },
                                    success: function(data) {
                                        swal.close();
                                        if (data) {
                                            toastr.success('Account Expense deleted successfully', 'Account Expense', {
                                                "progressBar": true
                                            });
                                            loadAccountExpense();
                                        } else {
                                            toastr.error('Account Expense not deleted', 'Account Expense', {
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

    $("#accountExpenseForm").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var form = $(this);
        form.parsley().validate();
        if (form.parsley().isValid()) {
            $.ajax({
                url: base_path + "AccountExpense/saveAccountExpense",
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#saveAccountExpenseBtn').prop('disabled', true);
                    $('#saveAccountExpenseBtn').text('Please wait..');
                    $('#closeAccountExpenseBtn').prop('disabled', true);
                },
                success: function(response) {
                    $('#saveAccountExpenseBtn').prop('disabled', false);
                    if ($('#code').val() != '') {
                        $('#saveAccountExpenseBtn').text('Update');
                    } else {
                        $('#saveAccountExpenseBtn').text('Save');
                    }
                    $('#closeAccountExpenseBtn').prop('disabled', false);
                    var obj = JSON.parse(response);
                    if (obj.status) {
                        toastr.success(obj.message, 'Account Expense', {
                            "progressBar": true
                        });
                        loadAccountExpense()
                        if (code != '') {
                            $('#generl_modal').modal('hide');
                        } else {
                            $('#code').val('');
                            $('#expensename').val('');
                            $('#expensecost').val('');
                            $('#expensedescription').val('');
                            $('#date').val('');
                        }
                    } else {
                        toastr.error(obj.message, 'Account Expense', {
                            "progressBar": true
                        });
                    }
                }
            });
        }
    });
</script>