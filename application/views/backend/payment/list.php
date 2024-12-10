<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>All Payments (Subscriptions)</h3>
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url('dashboard/listRecords') ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Payments</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                        <h5>Filter</h5>
                    </div>
                </div>
                <hr>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <label class="form-label lng">Company Name</label>
                        <select class="form-select" name="companyname" id="companyname">
                            <option value="">Select</option>
                            <?php
                            if ($clients) {
                                foreach ($clients->result() as $c) {
                                    echo '<option value="' . $c->companyname . '">' . $c->companyname . '</option>';
                                }
                            } ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label lng">Payment Status</label>
                        <select class="form-select" name="paymentstatus" id="paymentstatus">
                            <option value="">Select</option>
                            <option value="SUCCESS">Success</option>
                            <option value="FAIL">Fail</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label lng">From Date</label>
                        <input type="date" class="form-control" id="fromDate" name="fromDate" value="<?= date('Y-m-d', strtotime(' - 7 days')) ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label lng">To Date</label>
                        <input type="date" class="form-control" id="toDate" name="toDate" value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label lng">Payment ID</label>
                        <select class="form-select" name="paymentid" id="paymentid">
                            <option value="">Select</option>
                            <?php
                            if ($payment) {
                                foreach ($payment->result() as $p) {
                                    if ($p->paymentId != '' && $p->paymentId != null) {
                                        echo '<option value="' . $p->paymentId . '">' . $p->paymentId . '</option>';
                                    }
                                }
                            } ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label lng">Receipt ID</label>
                        <select class="form-select" name="receiptid" id="receiptid">
                            <option value="">Select</option>
                            <?php
                            if ($payment) {
                                foreach ($payment->result() as $p) {
                                    if ($p->receiptId != '' && $p->receiptId != null) {
                                        echo '<option value="' . $p->receiptId . '">' . $p->receiptId . '</option>';
                                    }
                                }
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="d-flex mt-4">
                            <button type="button" class="btn btn-success white me-1 mb-1 sub_1" id="btnSearch">Search</button>
                            <button type="reset" class="btn btn-light-secondary me-1 mb-1" id="btnClear">Clear</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                        <h5>Clients List</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="dataTable-Payment" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Company Name</th>
                            <th>Subscriber Name</th>
                            <th>Category</th>
                            <th>Amount Pay</th>
                            <th>Payment Date</th>
                            <th>Receipt ID</th>
                            <th>Payment ID</th>
                            <th>Payment Status</th>
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
<div class="modal fade text-left" id="generl_modal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id='modal_label1'>Send Email</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body2">
                                <form id="groupForm" class="form">
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row">
                                                <label class="col-md-4 form-label text-left">Email</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="email" class="form-control" name="email" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-4 form-label text-left">Subject</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="subject" class="form-control" name="subject">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-4 form-label text-left">Message</label>
                                                <div class="col-md-8">
                                                    <textarea type="text" name="message" id="message" class="form-control" rows="4"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="button" class="btn btn-primary white me-2 mb-1 sub_1" id="sendEmail">Send</button>
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
                <h5 id='modal_label1'>Send SMS</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body2">
                                <form id="groupForm" class="form">
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row">
                                                <label class="col-md-4 form-label text-left">Phone</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="mobile" class="form-control" name="mobile" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-4 form-label text-left">Message</label>
                                                <div class="col-md-8">
                                                    <textarea type="text" name="smsmessage" id="smsmessage" class="form-control" rows="4"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="button" class="btn btn-primary white me-2 mb-1 sub_1" id="sendSMS">Send</button>
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
<script src="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>
<script src="<?= base_url('assets/js/pages/datatables.js') ?>"></script>
<script>
    $(document).ready(function() {
        $('.cancel').removeClass('btn-default').addClass('btn-info');
        setTimeout(function() {
            $('#msg').fadeOut('fast');
        }, 6000);

        var fromDate = $("#fromDate").val();
        var toDate = $("#toDate").val();
        getDataTable("", "", fromDate, toDate, "", "");
        $(".buttons-html5").removeClass('btn-primary').addClass('btn-primary sub_1');
        $(".dt_buttons").removeClass('flex_wrap');
        $('#btnSearch').on('click', function(e) {
            var companyname = $("#companyname").val();
            var paymentstatus = $("#paymentstatus").val();
            var fromDate = $("#fromDate").val();
            var toDate = $("#toDate").val();
            var paymentid = $("#paymentid").val();
            var receiptid = $("#receiptid").val();
            getDataTable(companyname, paymentstatus, fromDate, toDate, paymentid, receiptid);
        });
        $('#btnClear').on('click', function(e) {
            $("#companyname").val('').trigger('change');
            $("#paymentstatus").val('').trigger('change');
            $("#paymentid").val('').trigger('change');
            $("#receiptid").val('').trigger('change');
            $("#fromDate").val('<?= date('Y-m-d', strtotime(' - 7 days')) ?>')
            $("#toDate").val('<?= date('Y-m-d') ?>')
            getDataTable("", "", "<?= date('Y-m-d', strtotime(' - 7 days')) ?>", "<?= date('Y-m-d') ?>", "", "");
        });

        $("body").on("change", "#toDate", function(e) {
            var endDate = $(this).val();
            var startDate = $('#fromDate').val();
            if (startDate > endDate) {
                $("#toDate").val('<?= date('Y-m-d') ?>');
                toastr.success("The End Date should be greater than the Start date.", "Client", {
                    "progressBar": true
                });
                return false
            }
        });
        $("body").on("change", "#fromDate", function(e) {
            var endDate = $('#toDate').val();
            if (endDate != "") {
                var startDate = $(this).val();
                if (startDate > endDate) {
                    $("#fromDate").val('<?= date('Y-m-d', strtotime(' - 7 days')) ?>')
                    toastr.success("The End Date should be greater than the Start date.", "Client", {
                        "progressBar": true
                    });
                    return false
                }
            }
        });
        $('#sendSMS').click(function() {
            var phone = $("#mobile").val();
            var message = $('#smsmessage').val();
            if (message == "") {
                toastr.error("Message is required.", 'Client', {
                    "progressBar": true
                });
                return false;
            } else {
                $.ajax({
                    url: base_path + "client/sendSms",
                    type: 'POST',
                    data: {
                        'phone': phone,
                        'message': message
                    },
                    success: function(response) {
                        var obj = JSON.parse(response);
                        if (obj.status == true) {
                            $('#generl_modal2').modal('hide');
                            toastr.success(obj.message, 'Client', {
                                "progressBar": true
                            });
                            getDataTable("", "", "<?= date('Y-m-d', strtotime(date('Y-m-d') . ' - 7 days')) ?>", "<?= date('Y-m-d') ?>", "", "", "");
                        } else {
                            $('#generl_modal2').modal('show');
                            toastr.error(obj.message, 'Client', {
                                "progressBar": true
                            });
                        }
                    }
                });
            }
        });
    });

    function getDataTable(companyname, paymentstatus, fromDate, toDate, paymentid, receiptid) {
        $.fn.DataTable.ext.errMode = 'none';
        if ($.fn.DataTable.isDataTable("#dataTable-Payment")) {
            $('#dataTable-Payment').DataTable().clear().destroy();
        }
        var dataTable = $('#dataTable-Payment').DataTable({
            stateSave: true,
            lengthMenu: [10, 25, 50, 200, 500, 700, 1000],
            processing: true,
            serverSide: true,
            scrollX: true,
            ordering: true,
            searching: true,
            paging: true,
            ajax: {
                url: base_path + "payment/getList",
                data: {
                    'companyname': companyname,
                    'paymentstatus': paymentstatus,
                    'fromDate': fromDate,
                    'toDate': toDate,
                    'paymentid': paymentid,
                    'receiptid': receiptid
                },
                type: "GET",
                complete: function(response) {
                    //email send
                    $('.apply_email').click(function() {
                        var email = $(this).data('email');
                        $('#generl_modal1').modal('show');
                        $('#email').val(email);
                    });
                    $('#sendEmail').click(function() {
                        var email = $("#email").val();
                        var subject = $("#subject").val();
                        var message = $('#message').val();
                        if (subject == "") {
                            toastr.error("Subject is required.", 'Client', {
                                "progressBar": true
                            });
                            return false;

                        } else {
                            $.ajax({
                                url: base_path + "client/sendEmail",
                                type: 'POST',
                                data: {
                                    'email': email,
                                    'subject': subject,
                                    'message': message
                                },
                                success: function(response) {

                                    var obj = JSON.parse(response);
                                    if (obj.status == true) {
                                        $('#generl_modal1').modal('hide');
                                        toastr.success(obj.message, 'Client', {
                                            "progressBar": true
                                        });
                                        getDataTable("", "", "<?= date('Y-m-d', strtotime(' - 7 days')) ?>", "<?= date('Y-m-d') ?>", "", "", "");
                                    } else {
                                        $('#generl_modal1').modal('show');
                                        toastr.error(obj.message, 'Client', {
                                            "progressBar": true
                                        });
                                    }
                                }
                            });
                        }
                    });

                    //send sms

                    $('.apply_sms').click(function() {
                        var phone = $(this).data('phone');
                        $('#generl_modal2').modal('show');
                        $('#mobile').val(phone);
                    });

                    $('#sendSMS').click(function() {
                        var phone = $("#mobile").val();
                        var message = $('#smsmessage').val();
                        if (message == "") {
                            toastr.error("Message is required.", 'Client', {
                                "progressBar": true
                            });
                            return false;

                        } else {
                            $.ajax({
                                url: base_path + "client/sendSms",
                                type: 'POST',
                                data: {
                                    'phone': phone,
                                    'message': message
                                },
                                success: function(response) {
                                    var obj = JSON.parse(response);
                                    if (obj.status == true) {
                                        $('#generl_modal2').modal('hide');
                                        toastr.success(obj.message, 'Client', {
                                            "progressBar": true
                                        });
                                        getDataTable("", "", "<?= date('Y-m-d', strtotime(' - 7 days')) ?>", "<?= date('Y-m-d') ?>", "", "", "");
                                    } else {
                                        $('#generl_modal2').modal('show');
                                        toastr.error(obj.message, 'Client', {
                                            "progressBar": true
                                        });
                                    }
                                }
                            });
                        }
                    });
                }
            }
        });
    }
</script>