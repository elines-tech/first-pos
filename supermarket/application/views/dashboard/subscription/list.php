<?php include '../supermarket/config.php'; ?>

<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3><?php echo $translations['All Subscriptions']?></h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Subscriptions History</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 col-md-12 order-md-1 order-last" id="leftdiv">
                            <h5><?php echo $translations['Filters']?></h5>
                        </div>
                    </div>
                    <hr>
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label class="form-label lng"><?php echo $translations['Receipt No.']?></label>
                            <div class="form-group mandatory">
                                <select class="form-select select2" name="receiptId" id="receiptId">
                                    <option value="">--Select from below--</option>
                                    <?php
                                    if ($subscriptions) {
                                        foreach ($subscriptions->result() as $r) {
                                            echo '<option value="' . $r->receiptId . '">' . $r->receiptId . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label lng"><?php echo $translations['Payment Id']?></label>
                            <div class="form-group mandatory">
                                <select class="form-select select2" name="paymentId" id="paymentId">
                                    <option value="">--Select from below--</option>
                                    <?php
                                    if ($subscriptions) {
                                        foreach ($subscriptions->result() as $r) {
                                            if ($r->paymentId != "") {
                                                echo '<option value="' . $r->paymentId . '">' . $r->paymentId . '</option>';
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label lng"><?php echo $translations['From Date']?></label>
                            <div class="form-group mandatory">
                                <input type="date" class="form-control" id="fromDate" name="fromDate" value="<?= date('Y-m-d', strtotime(' - 1 month')) ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label lng"><?php echo $translations['To Date']?></label>
                            <div class="form-group mandatory">
                                <input type="date" class="form-control" id="toDate" name="toDate" value="<?= date('Y-m-d') ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="d-flex text-center justify-content-center mt-4">
                                <button type="button" class="btn btn-success" id="btnSearch"><?php echo $translations['Search']?></button>
                                <button type="reset" class="btn btn-light-secondary" id="btnClear"><?php echo $translations['Clear']?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body" id="print_div">
                    <table class="table table-striped table-responsive display" id="tbl-subscription">
                        <thead>
                            <tr>
                                <th><?php echo $translations['Sr No']?></th>
                                <th><?php echo $translations['Receipt Id']?></th>
                                <th><?php echo $translations['Date']?></th>
                                <th><?php echo $translations['Payment Id']?></th>
                                <th><?php echo $translations['Amount']?></th>
                                <th><?php echo $translations['Service Period']?></th>
                                <th><?php echo $translations['Type']?></th>
                                <th><?php echo $translations['Status']?></th>
                                <th><?php echo $translations['Action']?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>
<script>
    $(document).ready(function() {

        function getDataTable(receiptId, paymentId, fromDate, toDate) {
            $.fn.DataTable.ext.errMode = 'none';
            if ($.fn.DataTable.isDataTable("#tbl-subscription")) {
                $('#tbl-subscription').DataTable().clear().destroy();
            }
            var dataTable = $('#tbl-subscription').DataTable({
                stateSave: false,
                responsive: true,
                lengthMenu: [10, 25, 50, 200],
                processing: true,
                serverSide: true,
                ordering: true,
                searching: true,
                paging: true,
                ajax: {
                    url: base_path + "subscriptions/getList",
                    data: {
                        'receiptId': receiptId,
                        'paymentId': paymentId,
                        'fromDate': fromDate,
                        'toDate': toDate
                    },
                    type: "GET",
                    complete: function(response) {}
                }
            });
        }

        $('.cancel').removeClass('btn-default').addClass('btn-info');

        getDataTable("", "", "", "");

        $(".buttons-html5").removeClass('btn-primary').addClass('btn-primary sub_1');

        $(".dt_buttons").removeClass('flex_wrap');

        $('#btnSearch').on('click', function(e) {
            var receiptId = $("#receiptId").val();
            var paymentId = $("#paymentId").val();
            var fromDate = $("#fromDate").val();
            var toDate = $("#toDate").val();
            getDataTable(receiptId, paymentId, fromDate, toDate);
        });

        $('#btnClear').on('click', function(e) {
            $("#frombranch").val('').trigger('change');
            $("#tobranch").val('').trigger('change');
            $("#fromDate").val('<?= date('Y-m-d', strtotime(' - 1 month')) ?>');
            $("#toDate").val('<?= date('Y-m-d') ?>');
            getDataTable("", "", "", "");
        });

        $("body").on("change", "#toDate", function(e) {
            var endDate = $(this).val();
            var startDate = $('#fromDate').val();
            if (startDate > endDate) {
                $("#toDate").val('<?= date('Y-m-d') ?>')
                toastr.success("The End Date should be greater than the Start date.", "Opps", {
                    "progressBar": true
                });
                return false;
            }
        });

        $("body").on("change", "#fromDate", function(e) {
            var endDate = $('#toDate').val();
            if (endDate != "") {
                var startDate = $(this).val();
                if (startDate > endDate) {
                    $("#fromDate").val('<?= date('Y-m-d', strtotime(' - 1 month')) ?>')
                    toastr.success("The End Date should be greater than the Start date.", "Opps", {
                        "progressBar": true
                    });
                    return false;
                }
            }
        });

        var data = '<?php echo $this->session->flashdata('message'); ?>';
        if (data != '') {
            var obj = JSON.parse(data);
            if (obj.status) {
                toastr.success(obj.message, 'Subscriptions', {
                    "progressBar": true
                });
            } else {
                toastr.error(obj.message, 'Subscriptions', {
                    "progressBar": true
                });
            }
        }
    });
</script>