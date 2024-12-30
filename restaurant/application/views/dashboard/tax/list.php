<style>
    .select2-container--classic .select2-selection--single,
    .select2-container--default .select2-selection--multiple,
    .select2-container--default .select2-selection--single,
    .select2-container--default .select2-selection--single .select2-selection__arrow,
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        border-color: rgba(0, 0, 0, 0.25);
        height: auto;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background: #1ca1c1;
        color: white
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected],
    .select2-container--default .select2-results__option[aria-selected=true] {
        background: #1ca1c1
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice>span {
        color: white !important;
        forn-weight: bold
    }
</style>
<div id="main-content">

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Tax Rates</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../Dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tax Rates</li>
                        </ol>
                    </nav>
                </div>

            </div>
        </div>
        <?php if ($insertRights == 1) { ?>
            <div id="maindiv" class="container">
                <div class="row">

                    <div class="col-12 col-md-12 text-center order-md-1 order-last" id="leftdiv">

                        <button id="add_category_text" class='add_tax'>
                            Add new Tax Rate
                        </button>

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
                            <h5>Tax Rates List</h5>
                        </div>

                    </div>

                </div>
                <div class="card-body" id="print_div">
                    <table class="table table-responsive table-striped" id="datatableTax">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Tax Rate %</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </section>
        <!-- Basic Tables end -->
    </div>
</div>

<hr style="width:100%;">

<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Tax Groups</h3>
                </div>
            </div>
        </div>
        <?php if ($insertRights == 1) { ?>
            <div id="maindiv" class="container">
                <div class="row">

                    <div class="col-12 col-md-12 text-center order-md-1 order-last" id="leftdiv">

                        <button id="add_category_text" class="add_group"> Add new Tax Group</button>

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
                            <h5>Tax Groups List</h5>
                        </div>


                    </div>

                </div>
                <div class="card-body" id="print_div1">
                    <table class="table table-striped" id="datatableTaxGroup">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Taxes</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </section>
        <!-- Basic Tables end -->
    </div>
</div>
</div>
</div>
</body>
<div class="modal fade text-left" id="generl_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id='modal_label'>Add Taxes</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body1">
                                <form id="taxForm" class="form" data-parsley-validate>
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Name : <b style="color:red">*</b></label>
                                                <div class="col-md-8">
                                                    <input type="text" id="taxName" class="form-control" placeholder="Tax Name" name="taxName" required>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-12 col-12">
                                            <div class="form-group row">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Tax Rate % : <b style="color:red">*</b></label>
                                                <div class="col-md-8">
                                                    <input type="text" id="taxPer" class="form-control" placeholder="Tax Rate" name="taxPer" required>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row">
                                                <label for="status" class="ccl-sm-4 col-form-label text-left">Active</label>
                                                <div class="col-sm-8 checkbox">
                                                    <input type="checkbox" name="taxisActive" id="taxisActive" checked class=" " style="width:25px; height:25px">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <input type="hidden" class="form-control" id="taxCode" name="taxCode">
                                            <?php if ($insertRights == 1) { ?>
                                                <button type="submit" class="btn btn-primary" id="saveTaxBtn">Save</button>
                                            <?php } ?>
                                            <button type="button" class="btn btn-light-secondary" id="closeTaxBtn" data-bs-dismiss="modal">Close</button>
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

<div class="modal fade text-left" id="generl_modal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modal_label2">Add Tax Groups</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body1">
                                <form id="groupForm" class="form" data-parsley-validate>
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row">
                                                <label for="category-name-column" class="col-md-5 form-label text-left">Tax Group Name : <b style="color:red">*</b></label>
                                                <div class="col-md-7">
                                                    <input type="text" id="taxGroupName" class="form-control" placeholder="Tax Group Name" name="taxGroupName" required>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row">
                                                <label for="category-name-column" class="col-md-5 form-label text-left">Taxes : <b style="color:red">*</b></label>
                                                <div class="col-md-7">
                                                    <select class="form-select select2" id="taxes" name="taxes[]" required multiple="multiple" data-border-variation="accent-2" required style="width:100%">
                                                        <?php
                                                        if ($taxes) {
                                                            foreach ($taxes->result() as $tr) {
                                                                echo "<option value='" . $tr->code . "'>" . $tr->taxName . "</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 col-12">
                                            <div class="form-group row">
                                                <label for="category-name-column" class="col-md-5 form-label text-left">Tax Group Ref : <b style="color:red">*</b></label>
                                                <div class="col-md-7">
                                                    <input type="text" id="taxGroupRef" class="form-control" placeholder="Tax Group Ref" name="taxGroupRef">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row">
                                                <label for="status" class="col-sm-5 col-form-label text-left">Active</label>
                                                <div class="col-sm-7 checkbox">
                                                    <input type="checkbox" name="taxGroupisActive" id="taxGroupisActive" checked class=" " style="width:25px; height:25px">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <input type="hidden" class="form-control" id="taxGroupCode" name="taxGroupCode">
                                            <?php if ($insertRights == 1) { ?>
                                                <button type="submit" class="btn btn-primary" id="saveTaxGroupBtn">Save</button>
                                            <?php } ?>
                                            <button type="button" class="btn btn-light-secondary" id="closeTaxGroupBtn" data-bs-dismiss="modal">Close</button>
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
        loadTaxTable();
        loadTaxGroupTable();
    });
    $('#taxPer').keypress(function(e) {
        var charCode = (e.which) ? e.which : event.keyCode
        if (String.fromCharCode(charCode).match(/[^0-9]/g))
            return false;
    });
    $('.add_tax').click(function() {
        $('#generl_modal').modal('show');
        $('#modal_label').text('Add Tax');
        $('#saveTaxBtn').text('Save');
        $('#code').val('');
        $('#taxName').val('');
        $('#taxPer').val('');
        $("#isActive").prop("checked", true);

    });

    $('.add_group').click(function() {
        $('#generl_modal2').modal('show');
        $('#modal_label2').text('Add Group');
        $('#saveTaxGroupBtn').text('Save');
        $('#taxGroupCode').val('');
        $('#taxGroupName').val('');
        $('#taxGroupRef').val('');
        $('#taxGroupisActive').prop('checked', false);
        $("#taxes").val(null).trigger('change.select2');
    });

    function loadTaxTable() {
        if ($.fn.DataTable.isDataTable("#datatableTax")) {
            $('#datatableTax').DataTable().clear().destroy();
        }
        var dataTable = $('#datatableTax').DataTable({
            stateSave: true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "searching": true,
            "ajax": {
                url: base_path + "tax/getTaxList",
                type: "GET",
                "complete": function(response) {
                    $('.edit_tax').click(function() {
                        var taxCode = $(this).data('seq');
                        $.ajax({
                            url: base_path + "tax/editTax",
                            type: 'POST',
                            data: {
                                'taxCode': taxCode,
                            },
                            success: function(response) {
                                var obj = JSON.parse(response);
                                if (obj.status) {
                                    $('#generl_modal').modal('show');
                                    $('#modal_label').text('Update tax');
                                    $('#saveTaxBtn').text('Update');
                                    $('#taxCode').val(obj.code);
                                    $('#taxName').val(obj.taxName);
                                    $('#taxPer').val(obj.taxPer);
                                    if (obj.isActive == 1) {
                                        $("#taxisActive").prop("checked", true);
                                    }
                                } else {
                                    toastr('Something Wend Wrong', 'Edit Tax', {
                                        progressBar: true
                                    })
                                }
                            }
                        });
                    });
                    $('.delete_tax').on("click", function() {
                        var code = $(this).data('seq');
                        $.ajax({
                            url: base_path + "tax/checkTax",
                            type: 'POST',
                            data: {
                                'code': code
                            },
                            success: function(response) {
                                var obj = JSON.parse(response);
                                var validtext = '';
                                if (obj.cnt > 0) {
                                    var validtext = 'This tax is used in further tax groups...'
                                }
                            }
                        });
                        swal({

                            title: "Are you sure you want to delete this?",
                            text: validtext,
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
                                    url: base_path + "tax/deleteTax",
                                    type: 'POST',
                                    data: {
                                        'code': code
                                    },
                                    beforeSend: function() {

                                    },
                                    success: function(data) {
                                        swal.close()
                                        var obj = JSON.parse(data);
                                        if (obj.status) {
                                            loadTaxTable();
                                            $('#taxes').html('');
                                            $('#taxes').append(obj.taxes);
                                            toastr.success('Tax deleted successfully', 'Tax', {
                                                "progressBar": true
                                            });
                                        } else {
                                            toastr.error('Tax not deleted', 'Tax', {
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

    $("#taxForm").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var form = $(this);
        form.parsley().validate();
        if (form.parsley().isValid()) {
            if ($('#taxPer').val() > 0) {
                var isActive = 0;
                if ($("#taxisActive").is(':checked')) {
                    isActive = 1;
                }
                formData.append('isActive', isActive);
                $.ajax({
                    url: base_path + "tax/saveTax",
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#saveTaxBtn').prop('disabled', true);
                        $('#saveTaxBtn').text('Please wait..');
                        $('#closeTaxBtn').prop('disabled', true);
                    },
                    success: function(response) {
                        $('#saveTaxBtn').prop('disabled', false);
                        if ($('#taxCode').val() != '') {
                            $('#saveTaxBtn').text('Update');
                        } else {
                            $('#saveTaxBtn').text('Save');
                        }
                        $('#closeTaxBtn').prop('disabled', false);
                        var obj = JSON.parse(response);
                        if (obj.status) {
                            toastr.success(obj.message, 'Tax Rates', {
                                "progressBar": true
                            });
                            loadTaxTable()
                            if ($('#taxCode').val() != '') {
                                $('#generl_modal').modal('hide');
                            } else {
                                $('#taxCode').val('');
                                $('#taxName').val('');
                                $('#taxPer').val('');
                                // $('#taxisActive').prop('checked', false);

                            }
                            $('#taxes').html('');
                            $('#taxes').append(obj.taxes);
                        } else {
                            toastr.error(obj.message, 'Tax Rates', {
                                "progressBar": true
                            });
                        }
                    }
                });
            } else {
                toastr.error('Tax per should be greater than 0', 'Tax Rates', {
                    "progressBar": true
                });
            }
        }
    });

    function loadTaxGroupTable() {
        if ($.fn.DataTable.isDataTable("#datatableTaxGroup")) {
            $('#datatableTaxGroup').DataTable().clear().destroy();
        }
        var dataTable = $('#datatableTaxGroup').DataTable({
            stateSave: true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "searching": true,
            "ajax": {
                url: base_path + "tax/getTaxGroupList",
                type: "GET",
                "complete": function(response) {
                    $('.edit_group').click(function() {
                        var code = $(this).data('seq');
                        $.ajax({
                            url: base_path + "tax/editTaxGroup",
                            type: 'POST',
                            data: {
                                'code': code,
                            },
                            success: function(response) {

                                var obj = JSON.parse(response);
                                if (obj.status) {
                                    $('#generl_modal2').modal('show');
                                    $('#taxes').append(obj.optionList);
                                    $('#modal_label2').text('Update Group');
                                    $('#saveTaxGroupBtn').text('Update');
                                    $('#taxGroupCode').val(obj.taxGroupCode);
                                    $('#taxGroupName').val(obj.taxGroupName);
                                    $('#taxGroupRef').val(obj.taxGroupRef);
                                    $('#taxes').val(obj.taxes).change();
                                    if (obj.isActive == 1) {
                                        $("#taxGroupisActive").prop("checked", true);
                                    }
                                } else {
                                    toastr('Something Wend Wrong', 'Edit Group', {
                                        progressBar: true
                                    })
                                }
                            }
                        });
                    });
                    $('.delete_group').on("click", function() {
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
                                    url: base_path + "tax/deleteTaxGroup",
                                    type: 'POST',
                                    data: {
                                        'code': code
                                    },
                                    beforeSend: function() {

                                    },
                                    success: function(data) {
                                        swal.close()
                                        if (data) {
                                            loadTaxGroupTable()
                                            toastr.success('Tax Group deleted successfully', 'Tax Group', {
                                                "progressBar": true
                                            });
                                        } else {
                                            toastr.error('Tax Group not deleted', 'Tax Group', {
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

    $("#groupForm").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var form = $(this);
        form.parsley().validate();
        if (form.parsley().isValid()) {
            var isActive = 0;
            if ($("#taxGroupisActive").is(':checked')) {
                isActive = 1;
            }
            formData.append('isActive', isActive);
            $.ajax({
                url: base_path + "tax/saveTaxGroup",
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#saveTaxGroupBtn').prop('disabled', true);
                    $('#saveTaxGroupBtn').text('Please wait..');
                    $('#closeTaxGroupBtn').prop('disabled', true);
                },
                success: function(response) {
                    $('#saveTaxGroupBtn').prop('disabled', false);
                    if ($('#taxGroupCode').val() != '') {
                        $('#saveTaxGroupBtn').text('Update');
                    } else {
                        $('#saveTaxGroupBtn').text('Save');
                    }
                    $('#closeTaxGroupBtn').prop('disabled', false);
                    var obj = JSON.parse(response);
                    if (obj.status) {
                        toastr.success(obj.message, 'Tax Group', {
                            "progressBar": true
                        });
                        loadTaxGroupTable();
                        if ($('#taxGroupCode').val() != '') {
                            $('#generl_modal2').modal('hide');
                        } else {
                            $('#taxGroupCode').val('');
                            $('#taxGroupName').val('');
                            $('#taxGroupRef').val('');
                            //$('#taxGroupisActive').prop('checked', false);
                            $("#taxes").val('').trigger('change');
                        }
                    } else {
                        toastr.error(obj.message, 'Tax Group', {
                            "progressBar": true
                        });
                    }
                }
            });
        }
    })
</script>