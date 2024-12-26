<nav class="navbar navbar-light">
    <div class="container d-block">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <a href="<?php echo base_url(); ?>Reservation/listrecords"><i id="exitButton" class="fa fa-times fa-2x"></i></a>
            </div>
        </div>
    </div>
</nav>
<div class="container">
    <!-- // Basic multiple Column Form section start -->
    <section id="multiple-column-form" class="mt-5">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Update Reservation</h3>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form" id="reservationUpdateForm" data-parsley-validate>
                                <?php
                                if ($restable) {
                                    foreach ($restable->result() as $row) { ?>
                                        <div class="row">
                                            <div class="col-md-12 col-12">
                                                <div class="form-group row mandatory">
                                                    <label for="category-name-column" class="col-md-3 form-label text-left">Customer Name</label>
                                                    <div class="col-md-6">
                                                        <input type="hidden" class="form-control" name="resCode" id="resCode" value="<?= $row->code ?>">
                                                        <select class="form-select select2" style="width:100%" name="modalcustomerName" id="modalcustomerName" required onchange="getMobileNumber();">
                                                            <option value="">Select Customer</option>
                                                            <?php
                                                            if ($customer) {
                                                                foreach ($customer->result() as $cust) {
                                                                    if ($row->customerCode == $cust->code) {
                                                                        echo "<option value='" . $cust->code . "' selected>" . $cust->name . "</option>";
                                                                    } else {
                                                                        echo "<option value='" . $cust->code . "'>" . $cust->name . "</option>";
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-12 col-12">
                                                <div class="form-group row mandatory">
                                                    <label for="qty-column" class="form-label text-left col-md-3">Mobile No.</label>
                                                    <div class="col-md-6">
                                                        <?php
                                                        $number = $row->customerMobile;
                                                        $countryCode = substr($number, 0, 4);
                                                        $mobile = substr($number, 4);
                                                        ?>
                                                        <div class="input-group">
                                                            <select name="countrycode" id="countrycode">
                                                                <option value="+966" <?php if ($countryCode == '+966') { ?>selected<?php } ?>>+966 (SAR)</option>
                                                                <option value="+971" <?php if ($countryCode == '+971') { ?>selected<?php } ?>>+971 (UAE)</option>
                                                            </select>

                                                            <input type="number" class="form-control" name="modalmobilephone" id="modalmobilephone" required value="<?= $mobile; ?>">
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-12 col-12">
                                                <div class="form-group row mandatory">
                                                    <label class="col-md-3 form-label text-left">Branch</label>
                                                    <div class="col-md-6">
                                                        <?php if ($branchCode != "") { ?>
                                                            <input type="hidden" class="form-control" name="branchCode" value="<?= $branchCode; ?>" readonly>
                                                            <input type="text" class="form-control" name="branchName" value="<?= $branchName; ?>" readonly>
                                                        <?php } else { ?>
                                                            <select class="form-select select2" style="width:100%" name="branchCode" id="branchCode" data-parsley-required="true" required>
                                                                <option value="<?= $row->branchCode ?>"><?= $row->branchName ?></option>
                                                            </select>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-12">
                                                <div class="form-group row mandatory">
                                                    <label class="col-md-3 form-label text-left">Sector</label>
                                                    <div class="col-md-6">
                                                        <select class="form-select select2" style="width:100%" name="sector" id="sector" data-parsley-required="true" required>
                                                            <option value="<?= $row->sectorCode ?>"><?= $row->zoneName ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-12">
                                                <div class="form-group row mandatory">
                                                    <label for="category-name-column" class="col-md-3 form-label text-left">Table No.</label>
                                                    <div class="col-md-6">
                                                        <select class="form-select select2" style="width:100%" name="tableNumber" id="tableNumber" data-parsley-required="true" required>
                                                            <option value="<?= $row->tableNumber ?>"><?= $row->tablenumber ?></option>
                                                        </select>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-12">
                                                <div class="form-group row mandatory">
                                                    <label for="category-name-column" class="col-md-3 form-label text-left">No. of Peoples</label>
                                                    <div class="col-md-6">
                                                        <input type="text" id="modalnumberofpeople" class="form-control" placeholder="Peoples" name="modalnumberofpeople" value="<?= $row->noOfPeople ?>" onkeypress="return isNumberKey(event)" required>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-12 col-12">
                                                <div class="form-group row mandatory">
                                                    <label for="qty-column" class="form-label text-left col-md-3"> Reservation Date</label>
                                                    <div class="col-md-6">
                                                        <input type="date" id="modaldate" class="form-control" name="modaldate" value="<?= date('Y-m-d', strtotime($row->resDate)) ?>" required>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-12 col-12">
                                                <div class="form-group row mandatory">
                                                    <label for="qty-column" class="form-label text-left col-md-3">Start Time</label>
                                                    <div class="col-md-6">
                                                        <input type="time" id="modalstime" class="form-control" placeholder="0:30:00" name="modalstime" value="<?= $row->startTime ?>" required>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-12 col-12">
                                                <div class="form-group row mandatory">
                                                    <label for="qty-column" class="form-label text-left col-md-3">End Time</label>
                                                    <div class="col-md-6">
                                                        <input type="time" id="modaletime" class="form-control" onchange="validateToTime()" placeholder="0:30:00" name="modaletime" value="<?= $row->endTime ?>" required>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-end">
                                                <?php if ($updateRights == 1) { ?>
                                                    <button type="submit" class="btn btn-primary" id="updateTableBtn">Update</button>
                                                <?php } ?>
                                                <button type="reset" class="btn btn-light-secondary" onclick="window.location.reload();" id="closeTableBtn">Reset</button>
                                            </div>
                                        </div>
                                <?php }
                                } ?>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- // Basic multiple Column Form section end -->
</div>

<script>
    $(document).ready(function() {
        $("#sector").select2({
            placeholder: "Select Sector",
            allowClear: true
        });
        $("#tableNumber").select2({
            placeholder: "Select Table",
            allowClear: true
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
        var code = $('#modalcustomerName').val();
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
                        $('#modalmobilephone').val(obj.mobile);
                        $('#countrycode').val(obj.countryCode);

                    } else {
                        $('#modalmobilephone').val('');
                    }
                }
            })
        }
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
                            "progressBar": true,
                            onHidden: function() {
                                window.location.href = base_path + "Reservation/listRecords";
                            }
                        });
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