<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Reservation</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../Dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Reservation</li>
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
                                <i class="fa fa-plus-circle cursor_pointer"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                            <h5>Reservation List</h5>
                        </div>

                    </div>
                </div>
                <div class="card-body" id="print_div">
                    <table class="table table-striped" id="reservationTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Customer Name</th>
                                <th>Mobile Number</th>
                                <th>Branch</th>
                                <th>Sector</th>
                                <th>Table No.</th>
                                <th>No. of Persons</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Date</th>
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

<div class="modal fade text-left" id="generl_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Book Table</h5>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body1">
                                <form class="form" id="reservationForm" data-parsley-validate>
                                    <div class="row">


                                        <div class="form-group col-md-6 col-12 mandatory">
                                            <label for="category-name-column" class="col-md-12 form-label text-left">Customer Name <a class="add_customer"><i class="fa fa-plus-circle cursor_pointer" style="font-size:18px;"></i></a></label>

                                            <select class="form-select select2" style="width:100%" name="customerName" id="customerName" required onchange="getMobileNumber();">
                                                <option value="">Select Customer</option>
                                                <?php
                                                if ($customer) {
                                                    foreach ($customer->result() as $cust) {
                                                        echo "<option value='" . $cust->code . "'>" . $cust->name . "</option>";
                                                    }
                                                }
                                                ?>
                                            </select>

                                        </div>


                                        <div class="form-group col-md-6 col-12 mandatory">
                                            <label for="qty-column" class="form-label text-left text-nowrap">Mobile No.</label>
                                            <div class="input-group">
                                                <select name="countrycode" id="countrycode">
                                                    <option value="+966">+966 (SAR)</option>
                                                    <option value="+971">+971 (UAE)</option>
                                                </select>

                                                <input type="number" class="form-control" name="mobilephone" id="mobilephone" required>
                                            </div>

                                        </div>


                                        <div class="form-group col-md-6 col-12 mandatory">
                                            <label class="form-label text-left">Branch</label>
                                            <?php if ($branchCode != "") { ?>
                                                <input type="hidden" class="form-control" id="branch" name="branchCode" value="<?= $branchCode; ?>" readonly>
                                                <input type="text" class="form-control" name="branchName" value="<?= $branchName; ?>" readonly>
                                            <?php } else { ?>
                                                <select class="form-select select2" style="width:100%" name="branchCode" id="branchCode" data-parsley-required="true" required>

                                                </select>
                                            <?php } ?>
                                        </div>


                                        <div class="form-group col-md-6 col-12 mandatory">
                                            <label class="form-label text-left">Sector</label>
                                            <select class="form-select select2" style="width:100%" name="sector" id="sector"
                                                data-parsley-required="true" required>

                                            </select>
                                        </div>


                                        <div class="form-group col-md-6 col-12 mandatory">
                                            <label for="category-name-column" class="form-label text-left">Table No.</label>
                                            <select class="form-select select2" style="width:100%" name="tableNumber" id="tableNumber" data-parsley-required="true" required>

                                            </select>

                                        </div>


                                        <div class="form-group col-md-6 col-12 mandatory">
                                            <label for="category-name-column" class="form-label text-left">No. of Peoples</label>
                                            <input type="text" id="numberofpeople" class="form-control" placeholder="Peoples" name="numberofpeople" onkeypress="return isNumberKey(event)" required>
                                        </div>


                                        <div class="form-group col-md-12 col-12 mandatory">
                                            <label for="qty-column" class="form-label text-left"> Reservation Date</label>
                                            <input type="date" id="date" class="form-control" value="<?= date('Y-m-d') ?>" name="date" required>
                                        </div>


                                        <div class="form-group col-md-6 col-12 mandatory">
                                            <label for="qty-column" class="form-label text-left">Start Time</label>
                                            <input type="time" id="stime" class="form-control" placeholder="0:30:00" name="stime" required>
                                        </div>

                                        <div class="form-group col-md-6 col-12 mandatory">
                                            <label for="qty-column" class="form-label text-left">End Time</label>
                                            <input type="time" id="etime" class="form-control" onchange="validateToTime()" placeholder="0:30:00" name="etime" required>
                                        </div>



                                    </div>

                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <?php if ($insertRights == 1) { ?>
                                                <button type="submit" class="btn btn-primary" id="saveTableBtn">Save</button>
                                            <?php } ?>
                                            <button type="reset" class="btn btn-light-secondary" data-bs-dismiss="modal" id="closeTableBtn">Close</button>
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
                <h5>Update Table Booking</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">

                            <div class="panel-body1 panel-body">

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
                <h5>View Table Booking</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body1 panel-body-new">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="generl_modal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Create Customer</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body1 panel-body-new">
                                <div class="row">
                                    <form class="form" id="submitcustomerForm" data-parsley-validate>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory">
                                                <label class="form-label text-left col-md-4">Customer Name</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" id="name" name="name" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory">
                                                <label class="form-label text-left col-md-4">Email</label>
                                                <div class="col-md-8">
                                                    <input type="email" class="form-control" id="email" name="email" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group row mandatory">
                                                <label class="form-label text-left col-md-4">Phone </label>
                                                <div class="col-md-8">
                                                    <div class="input-group">
                                                        <select name="countrycode" id="countrycode">
                                                            <option value="+966">+966 (SAR)</option>
                                                            <option value="+971">+971 (UAE)</option>
                                                        </select>

                                                        <input type="number" class="form-control" name="phone" id="phone" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary" id="saveCustomerBtn">Save</button>
                                        <button type="reset" class="btn btn-light-secondary" data-bs-dismiss="modal" id="closeCustomerBtn">Close</button>
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
        $('#customerName').select2({
            dropdownParent: $('#generl_modal')
        });
        $('#tableNumber').select2({
            dropdownParent: $('#generl_modal')
        });
        $("#sector").select2({
            placeholder: "Select Sector",
            allowClear: true
        });
        $("#tableNumber").select2({
            placeholder: "Select Table",
            allowClear: true
        });
        $('.add_customer').click(function() {
            $('#generl_modal3').modal('show');
            $('#generl_modal').modal('hide');
        });

        $("#branchCode").select2({
            placeholder: "Select Branch",
            allowClear: true,
            ajax: {
                url: base_path + 'Common/getBranch',
                type: "get",
                delay: 250,
                dataType: 'json',
                data: function(params) {
                    var query = {
                        search: params.term
                    }
                    return query;
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        }).on("select2:select", function(e) {
            var branch = $(e.currentTarget).val();
            getSector(branch);
        });

    });

    function getSector(branch) {
        debugger
        $("#sector").select2({
            placeholder: "Select Sector",
            allowClear: true,
            ajax: {
                url: base_path + 'Common/getZoneByBranch',
                type: "get",
                delay: 250,
                dataType: 'json',
                data: function(params) {
                    var query = {
                        search: params.term,
                        branch: branch,
                    }

                    return query;
                },
                processResults: function(response) {

                    return {
                        results: response
                    };

                },
                cache: true
            }
        }).on("select2:select", function(e) {
            var sector = $(e.currentTarget).val();
            getTable(sector);
        });
    }

    function getTable(sector) {
        $("#tableNumber").select2({
            placeholder: "Select Table",
            allowClear: true,
            ajax: {
                url: base_path + 'Common/getTableBySector',
                type: "get",
                delay: 250,
                dataType: 'json',
                data: function(params) {
                    var query = {
                        search: params.term,
                        sector: sector,
                    }

                    return query;
                },
                processResults: function(response) {

                    return {
                        results: response
                    };

                },
                cache: true
            }
        });

    }


    function getMobileNumber(id) {
        var code = $('#customerName').val();
        if (code != '') {
            $.ajax({
                url: base_path + "Reservation/getData",
                type: 'POST',
                data: {
                    'code': code
                },
                success: function(response) {
                    var obj = JSON.parse(response);
                    if (obj.status == true) {
                        $('#mobilephone').val(obj.mobile);
                        $('#countrycode').val(obj.countryCode)
                    } else {
                        $('#mobilephone').val('')
                    }
                }
            })
        }
    }


    function loadTable() {
        if ($.fn.DataTable.isDataTable("#reservationTable")) {
            $('#reservationTable').DataTable().clear().destroy();
        }
        var dataTable = $('#reservationTable').DataTable({
            stateSave: true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "searching": true,
            "ajax": {
                url: base_path + "Reservation/getReservationList",
                type: "GET",
                "complete": function(response) {
                    /* $('.edit_booktable').click(function() {
                        var code = $(this).data('seq');
                        $.ajax({
                            url: base_path + "Reservation/edit",
                            type: 'POST',
                            data: {
                                'code': code,
                            },
                            success: function(response) {
                                $('#generl_modal1').modal('show');
                                $(".panel-body").html(response);
                                $('#modalcustomerName').select2({
                                    dropdownParent: $('#generl_modal1')
                                });
								
								$('#tableNumber').select2({
                                    dropdownParent: $('#generl_modal1')
                                });
                            }
                        });
                    });*/

                    $('.view_booktable').click(function() {
                        var code = $(this).data('seq');
                        $.ajax({
                            url: base_path + "Reservation/view",
                            type: 'POST',
                            data: {
                                'code': code,
                            },
                            success: function(response) {
                                $('#generl_modal2').modal('show');
                                $(".panel-body-new").html(response);

                            }
                        });
                    });
                    $('.delete_booktable').on("click", function() {
                        var code = $(this).data('seq');
                        swal({
                            //title: "You want to delete branch "+code,
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
                                    url: base_path + "Reservation/deleteTable",
                                    type: 'POST',
                                    data: {
                                        'code': code
                                    },
                                    beforeSend: function() {

                                    },
                                    success: function(data) {
                                        swal.close();
                                        if (data) {
                                            toastr.success('Table booked deleted successfully', 'Table Booked', {
                                                "progressBar": true
                                            });
                                            loadTable();
                                        } else {
                                            toastr.error('Table booked deleted', 'Table Booked', {
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

    function isNumberKey(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    function validateToTime() {
        var endTime = $('#etime').val();
        var startTime = $('#stime').val();
        if (endTime <= startTime && startTime != "" && endTime != "") {
            toastr.error(" End time should be greater than start time", 'Table Booked', {
                "progressBar": true
            });
            $('#stime').val('');
            $('#etime').val('');
            return false;
        }
    }

    $("#reservationForm").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var form = $(this);
        form.parsley().validate();
        if (form.parsley().isValid()) {
            var startTime = $('#stime').val();
            var endtime = $('#etime').val();
            if (startTime != '' && endtime != '' && endtime <= startTime) {
                toastr.error(' End time should be greater than start time', 'Table Booked', {
                    "progressBar": true
                });
                $('#stime').val('');
                $('#etime').val('');
                return false;
            }
            $.ajax({
                url: base_path + "Reservation/tableReservation",
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#saveTableBtn').prop('disabled', true);
                    $('#saveTableBtn').text('Please wait..');
                    $('#closeTableBtn').prop('disabled', true);
                },
                success: function(response) {
                    $('#saveTableBtn').prop('disabled', false);
                    $('#saveTableBtn').text('Save');
                    $('#closeTableBtn').prop('disabled', false);
                    var obj = JSON.parse(response);
                    if (obj.status) {
                        $('#generl_modal').modal('hide');
                        toastr.success(obj.message, 'Table Booked', {
                            "progressBar": true
                        });
                        loadTable()
                        $("#customerName").val('').trigger('change');
                        $('#mobilephone').val('');
                        $('#tableNumber').val('').trigger('change');
                        $('#numberofpeople').val('');
                        $('#date').val('');
                        $('#stime').val('');
                        $('#etime').val('');
                    } else {
                        toastr.error(obj.message, 'Table Booked', {
                            "progressBar": true
                        });
                    }
                }
            });
        }
    });


    $("#submitcustomerForm").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var form = $(this);
        form.parsley().validate();
        if (form.parsley().isValid()) {
            $.ajax({
                url: base_path + "Reservation/saveCustomer",
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#saveCustomerBtn').prop('disabled', true);
                    $('#saveCustomerBtn').text('Please wait..');
                    $('#closeCustomerBtn').prop('disabled', true);
                },
                success: function(response) {
                    $('#saveCustomerBtn').prop('disabled', false);
                    $('#closeCustomerBtn').text('Save');
                    var obj = JSON.parse(response);
                    if (obj.status) {
                        toastr.success(obj.message, 'Customer', {
                            "progressBar": true,
                            onHidden: function() {
                                window.location.href = base_path + "Reservation/listRecords";
                            }
                        });
                        $('#generl_modal3').modal('hide');

                    } else {
                        toastr.error(obj.message, 'Customer', {
                            "progressBar": true
                        });
                    }
                }
            });
        }
    });

    $(document).on("click", "button#updateTableBtn", function(e) {
        $('#reservationUpdateForm').parsley();
        const form = document.getElementById('reservationUpdateForm');
        var formData = new FormData(form);
        var isValid = true;
        e.preventDefault();
        $("#reservationUpdateForm .form-control").each(function(e) {
            if ($(this).parsley().validate() !== true) isValid = false;
        });
        $("#reservationUpdateForm .form-select").each(function(e) {
            if ($(this).parsley().validate() !== true) isValid = false;
        });
        if (isValid) {

            var startTime = $('#modalstime').val();
            var endTime = $('#modaletime').val();
            if (startTime != '' && endTime != '' && endTime <= startTime) {
                toastr.error('End time should be greater than Start time', 'Table Booked', {
                    "progressBar": true
                });
                $('#modaletime').val('');
                $('#modalstime').val('');
                $('#modaletime').focus();
                return false;
            }
            $.ajax({
                url: base_path + "Reservation/updateReservation",
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#updateTableBtn').prop('disabled', true);
                    $('#updateTableBtn').text('Please wait..');
                    $('#closeTableBtn').prop('disabled', true);
                },
                success: function(response) {
                    $('#updateTableBtn').prop('disabled', false);
                    $('#updateTableBtn').text('Update');
                    $('#closeTableBtn').prop('disabled', false);
                    var obj = JSON.parse(response);
                    if (obj.status) {
                        toastr.success(obj.message, 'Table Book', {
                            "progressBar": true
                        });
                        loadTable()
                        $('#generl_modal1').modal('hide');
                        $(".panel-body").html('');
                    } else {
                        toastr.error(obj.message, 'Table Book', {
                            "progressBar": true
                        });
                    }
                }
            });
        }
    });
</script>