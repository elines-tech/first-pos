<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>All Subscribers</h3>
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Subscribers</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                        <h5>Filter</h5>
                    </div>
                </div>
                <hr>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <label class="form-label lng">Package Subscribers</label>
                        <select class="form-select" name="subscriber" id="subscriber">
                            <option value="">Select</option>
                            <option value="subscription">Subscribers</option>
                            <option value="trial">Free Trial</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label lng">Subscritption Status</label>
                        <select class="form-select" name="planstatus" id="planstatus">
                            <option value="">Select</option>
                            <option value="expired">Expired</option>
                            <option value="active">Active</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label lng">From Date</label>
                        <input type="date" style="padding: 5px;" class="form-control" id="fromDate" name="fromDate" value="<?= date('Y-m-d', strtotime(' - 7 days')) ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label lng">To Date</label>
                        <input type="date" style="padding: 5px;" class="form-control" id="toDate" name="toDate" value="<?= date('Y-m-d') ?>">
                    </div>

                    <div class="col-md-4 mt-3">
                        <label class="form-label lng">Category</label>
                        <select class="form-select" name="category" id="category">
                            <option value="">Select</option>
                            <option value="restaurant">Restaurant</option>
                            <option value="supermarket">Supermarket</option>
                        </select>
                    </div>
                    <div class="col-md-4 mt-3">
                        <label class="form-label lng">Name</label>
                        <select class="form-select" name="name" id="name">
                            <option value="">Select</option>
                            <?php
                            if ($subscribers) {
                                foreach ($subscribers->result() as $c) {
                                    echo '<option value="' . $c->name . '">' . $c->name . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4 mt-3">
                        <label class="form-label lng">Phone</label>
                        <select class="form-select" name="phone" id="phone">
                            <option value="">Select</option>
                            <?php
                            if ($subscribers) {
                                foreach ($subscribers->result() as $c) {
                                    echo '<option value="' . $c->phone . '">' . $c->phone . '</option>';
                                }
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex mt-4 justify-content-center">
                            <button type="button" class="btn btn-success" id="btnSearch">Search</button>
                            <button type="reset" class="btn btn-light-secondary" id="btnClear">Clear</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                        <h5>Subscribers List</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="dataTable-Restaurant" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Company Code</th>
                            <th>Company Name</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Register Date</th>
                            <th>Category</th>
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
                                </form>
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
        getDataTable("", "", fromDate, toDate, "", "", "");
        $(".buttons-html5").removeClass('btn-primary').addClass('btn-primary sub_1');
        $(".dt_buttons").removeClass('flex_wrap');
        $('#btnSearch').on('click', function(e) {
            var subscriber = $("#subscriber").val();
            var status = $("#planstatus").val();
            var fromDate = $("#fromDate").val();
            var toDate = $("#toDate").val();
            var category = $("#category").val();
            var name = $("#name").val();
            var phone = $("#phone").val();
            getDataTable(subscriber, status, fromDate, toDate, category, name, phone);
        });
        $('#btnClear').on('click', function(e) {
            $("#subscriber").val('').trigger('change');
            $("#category").val('').trigger('change');
            $("#planstatus").val('').trigger('change');
            $("#name").val('').trigger('change');
            $("#phone").val('').trigger('change');
            $("#fromDate").val('<?= date('Y-m-d', strtotime(' - 7 days')) ?>')
            $("#toDate").val('<?= date('Y-m-d') ?>')
            getDataTable("", "", "<?= date('Y-m-d', strtotime(' - 7 days')) ?>", "<?= date('Y-m-d') ?>", "", "", "");
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
                            getDataTable("", "", "<?= date('Y-m-d', strtotime(date("Y-m-d") . ' - 7 days')) ?>", "<?= date('Y-m-d') ?>", "", "", "");
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

    function getDataTable(subscriber, status, fromDate, toDate, category, name, phone) {
        $.fn.DataTable.ext.errMode = 'none';
        if ($.fn.DataTable.isDataTable("#dataTable-Restaurant")) {
            $('#dataTable-Restaurant').DataTable().clear().destroy();
        }
        var dataTable = $('#dataTable-Restaurant').DataTable({
            stateSave: true,
            lengthMenu: [10, 25, 50, 200, 500, 700, 1000],
            processing: true,
            serverSide: true,
            scrollX: true,
            ordering: true,
            searching: true,
            paging: true,
            ajax: {
                url: base_path + "client/getList",
                data: {
                    'subscriber': subscriber,
                    'status': status,
                    'fromDate': fromDate,
                    'toDate': toDate,
                    'category': category,
                    'name': name,
                    'phone': phone
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