<div class="page-heading m-4">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title">View Payment History</h3>
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('/dashboard/listRecords') ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                        <li class="breadcrumb-item" aria-current="page">Payments</li>
                        <li class="breadcrumb-item active" aria-current="page">View</li>
                    </ol>
                </nav>
            </div>
            <div class="col-5 align-self-center text-end">
                <a href="<?= base_url() ?>payment/listRecords" class="btn btn-sm btn-primary">Back</a>
            </div>
        </div>
    </div>
</div>
<div class="page-content m-4">
    <div class="container">
        <div class="col-md-12">
            <?php
            foreach ($payments->result() as $item) {
                if ($item->expiryDate < date('Y-m-d H:i:s') && $item->status == "EXPIRED") {
                    $class = "border-danger";
                } else {
                    $class = "border-success";
                }
            ?>
                <div class="card border <?= $class ?>">
                    <div class="card-header">
                        <h5>Payment Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 mb-2">
                                <label>Company Name</label>
                                <input type="hidden" name="clientcode" id="clientcode" readonly class="form-control" value="<?= $item->clientCode ?>" />
                                <input type="text" name="companyname" id="companyname" readonly class="form-control" value="<?= $item->companyname ?>" />
                            </div>
                            <div class="col-md-4 col-sm-12 mb-2">
                                <label>Plan</label>
                                <input type="text" name="plan" id="plan" readonly class="form-control" value="<?= $item->title ?>" />
                            </div>
                            <div class="col-md-4 col-sm-12 mb-2">
                                <label for="tax">Invoice Date</label>
                                <input type="text" name="date" id="date" readonly class="form-control" value="<?= date('d/m/Y h:i A', strtotime($item->paymentDate)) ?>" />
                            </div>
                            <div class="col-md-4 col-sm-12 mb-2">
                                <label>Payment Status</label>
                                <input type="text" name="paymentstatus" id="paymentstatus" readonly class="form-control" value="<?= $item->paymentStatus ?>" />
                            </div>
                            <div class="col-md-4 col-sm-12 mb-2">
                                <label>Receipt Number</label>
                                <input type="text" name="paymentstatus" id="paymentstatus" readonly class="form-control" value="<?= $item->receiptId ?>" />
                            </div>
                            <div class="col-md-4 col-sm-12 mb-2">
                                <label>Payment Number</label>
                                <input type="text" name="paymentstatus" id="paymentstatus" readonly class="form-control" value="<?= $item->paymentId ?>" />
                            </div>
                            <div class="col-md-4 col-sm-12 mb-2">
                                <label>Payment Status</label>
                                <input type="text" name="paymentstatus" id="paymentstatus" readonly class="form-control" value="<?= $item->paymentStatus ?>" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12 mb-2">
                                <label>Service Period</label>
                                <div class="input-daterange input-group" id="productDateRange">
                                    <input type="text" class="form-control date-inputmask col-sm-5" name="startdate" readonly value="<?= date('d/m/Y', strtotime($item->startDate)) ?>" />
                                    <span class="btn btn-info text-white">TO</span>
                                    <input type="text" class="form-control date-inputmask toDate" name="enddate" readonly value="<?= date('d/m/Y', strtotime($item->expiryDate)) ?>" />
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12 mb-2">
                                <label>Type</label>
                                <input type="text" readonly class="form-control" readonly value="<?= $item->type ?>" />
                            </div>
                            <div class="col-md-3 col-sm-12 mb-2">
                                <label>Duration</label>
                                <input type="text" readonly class="form-control" readonly value="<?= $item->period ?>" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-12 mb-2">
                                <label>Total Users</label>
                                <input type="text" readonly class="form-control" readonly value="<?= $item->defaultUser + $item->addonUsers ?>" />
                            </div>
                            <div class="col-md-4 col-sm-12 mb-2">
                                <label>Total Branches</label>
                                <input type="text" readonly class="form-control" readonly value="<?= $item->defaultBranches + $item->addonBranches ?>" />
                            </div>
                            <div class="col-md-4 col-sm-12 mb-2">
                                <label>Tax Percent</label>
                                <input type="text" readonly class="form-control" readonly value="<?= $item->taxpercent ?>" />
                            </div>
                            <div class="col-md-4 col-sm-12 mb-2">
                                <label>Subtotal Amount</label>
                                <input type="text" readonly class="form-control" readonly value="<?= $item->subtotal ?>" />
                            </div>
                            <div class="col-md-4 col-sm-12 mb-2">
                                <label>Tax Amount</label>
                                <input type="text" readonly class="form-control" readonly value="<?= $item->taxtotal ?>" />
                            </div>
                            <div class="col-md-4 col-sm-12 mb-2">
                                <label>Total Amount</label>
                                <input type="text" readonly class="form-control" readonly value="<?= $item->amount ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="card-footer p-3">
                        <?php
                        if ($item->expiryDate > date('Y-m-d H:i:s')  && $item->status == "ACTIVE") { ?>
                            <div class="row">
                                <div class="col-sm-6">
                                    <span>Do you want to expire this plan?</span><br>
                                    <button type="button" style="cursor:pointer;" id="<?= $item->code ?>" class="btn btn-danger expired">Expire Current Plan</button>
                                </div>
                                <div class="col-sm-6">
                                    <span>Do you want to upgrade this plan?</span><br>
                                    <button type="button" style="cursor:pointer;" id="<?= $item->code ?>" data-val="<?= date('d/m/Y', strtotime($item->expiryDate)) ?>" class="btn btn-info expiredDays">Upgrade Current Plan</button>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>


<div class="modal fade text-left" id="generl_modal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id='modal_label1'>Expired Plan</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body2">
                                <form id="expiryPlan" class="form">
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row">
                                                <label class="col-md-4 form-label text-left">Date</label>
                                                <div class="col-md-8">
                                                    <input type="hidden" id="plancode" class="form-control" name="code">
                                                    <input type="date" id="expirydate" class="form-control" name="expirydate">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="button" class="btn btn-primary white me-2 mb-1 sub_1" id="planExpired">Send</button>
                                            <button type="button" class="btn btn-light-secondary me-1 mb-1" data-bs-dismiss="modal">Close</button>
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

<div class="modal fade text-left" id="generl_modal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id='modal_label1'>Upgrade Plan</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body2">
                                <form id="extraDay" class="form">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label class="form-label text-left">Current Expiry Date</label>
                                            <input type="hidden" id="plancode1" class="form-control" name="code1">
                                            <input type="text" id="oldexpirydate" class="form-control" name="oldexpirydate" readonly>

                                        </div>
                                        <div class="col-sm-12">
                                            <label class="form-label text-left">No of days</label>
                                            <input type="text" id="noofdays" class="form-control" name="noofdays" onchange="calculateDate();checkDays();" onkeypress="return isNumber(event)" maxlength="3">

                                        </div>
                                        <div class="col-sm-12">
                                            <label class="form-label text-left">New Expired Date</label>
                                            <input type="hidden" id="newdate" class="form-control" name="newdate" readonly>
                                            <input type="text" id="newexpirydate" class="form-control" name="newexpirydate" readonly>

                                        </div>

                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="button" class="btn btn-primary white me-2 mb-1 sub_1" id="extraDays">Update</button>
                                            <button type="button" class="btn btn-light-secondary me-1 mb-1" data-bs-dismiss="modal">Close</button>
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

<script src="<?= base_url('assets/js/moment.min.js') ?>"></script>
<script>
    function calculateDate() {
        var oldExDate = document.getElementById('oldexpirydate').value;
        var noofdays = document.getElementById('noofdays').value;
        var endDate = moment(oldExDate, "DD/MM/YYYY").add(noofdays, "days");
        var format_end_date = moment(endDate).format("DD/MM/YYYY");
        var format_date = moment(endDate).format("YYYY-MM-DD");
        $("#newexpirydate").val(format_end_date);
        $("#newdate").val(format_date);
    }

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    function checkDays() {
        var days = $('#noofdays').val();
        if (days != '' && days == 0) {
            $('#noofdays').val('');
            toastr.error('Enter Valid days', 'Subscription Plan', {
                "progressBar": false
            });
            return false;
        }
    }

    $(document).ready(function() {
        $('.cancel').removeClass('btn-default').addClass('btn-info');
        var dtToday = new Date();
        var month = dtToday.getMonth() + 1;
        var day = dtToday.getDate();
        var year = dtToday.getFullYear();
        if (month < 10)
            month = '0' + month.toString();
        if (day < 10)
            day = '0' + day.toString();

        var maxDate = year + '-' + month + '-' + day;
        $('#expirydate').attr('min', maxDate);

        $('.expired').click(function() {
            var code = $(this).attr('id');
            swal({
                title: "Are you sure to expired this plan?",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: !1,
                closeOnCancel: !1
            }, function(e) {
                if (e) {
                    $.ajax({
                        url: base_path + "payment/expiryPlan",
                        type: 'POST',
                        data: {
                            'code': code
                        },
                        success: function(response) {
                            swal.close()
                            var obj = JSON.parse(response);
                            if (obj.status == true) {
                                $('#generl_modal1').modal('hide');
                                toastr.success(obj.message, 'Subscription Plan', {
                                    "progressBar": true
                                });
                                window.location.reload();

                            } else {
                                $('#generl_modal1').modal('show');
                                toastr.error(obj.message, 'Subscription Plan', {
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

        /*$('#planExpired').click(function() {
		var plancode = $("#plancode").val();
		var expiredDate = $("#expirydate").val();
		if (expiredDate == "") {
			toastr.error("Expirydate is required.", 'Subscription Plan', {
				"progressBar": true
			});
			return false;

		} else {
			$.ajax({
				url: base_path + "payment/expiryPlan",
				type: 'POST',
				data: {
					'plancode': plancode,
					'expireddate': expiredDate
					
				},
				success: function(response) {

					var obj = JSON.parse(response);
					if (obj.status == true) {
						$('#generl_modal1').modal('hide');
						toastr.success(obj.message, 'Subscription Plan', {
							"progressBar": true
						});
						window.location.reload();
						
					} else {
						$('#generl_modal1').modal('show');
						toastr.error(obj.message, 'Subscription Plan', {
							"progressBar": true
						});
					}
				}
			});
		}
	});*/

        $('.expiredDays').click(function() {
            var oldexpirydate = $(this).data('val');
            var plancode = $(this).attr('id');
            $('#generl_modal2').modal('show');
            $('#oldexpirydate').val(oldexpirydate);
            $('#plancode1').val(plancode);
        });


        $('#extraDays').click(function() {
            debugger
            var plancode1 = $("#plancode1").val();
            var expiredDate = $("#newdate").val();
            var clientcode = $("#clientcode").val();
            var noofdays = $("#noofdays").val();
            if (noofdays == "" || noofdays == 0) {
                toastr.error("Valid days required.", 'Subscription Plan', {
                    "progressBar": true
                });
                return false;

            } else {
                $.ajax({
                    url: base_path + "payment/extraDays",
                    type: 'POST',
                    data: {
                        'plancode': plancode1,
                        'expireddate': expiredDate,
                        'noofdays': noofdays,
                        'clientcode': clientcode

                    },
                    success: function(response) {

                        var obj = JSON.parse(response);
                        if (obj.status == true) {
                            $('#generl_modal2').modal('hide');
                            toastr.success(obj.message, 'Subscription Plan', {
                                "progressBar": true
                            });
                            window.location.reload();

                        } else {
                            $('#generl_modal2').modal('show');
                            toastr.error(obj.message, 'Subscription Plan', {
                                "progressBar": true
                            });
                        }
                    }
                });
            }
        });
    });
</script>