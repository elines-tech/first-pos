<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Order</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../../Cashier/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Order</li>
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
                            <h5>Filter </h5>
                        </div>
                    </div>
                    <hr>
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label class="form-label lng">Orders</label>
                            <div class="form-group mandatory">
                                <select class="form-select select2" name="order" id="order">
                                    <option value="">Select Order</option>
                                    <?php if ($order) {
                                        foreach ($order->result() as $or) {
                                            echo '<option value="' . $or->code . '">' . $or->code . '</option>';
                                        }
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label lng">Customer Mobile Number</label>
                            <div class="form-group mandatory">
                                <select class="form-select select2" name="mobile" id="mobile">
                                    <option value="">Select Mobile Number</option>
                                    <?php if ($customer) {
                                        foreach ($customer->result() as $cr) {
                                            echo '<option value="' . $cr->phone . '">' . $cr->phone . '</option>';
                                        }
                                    } ?>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <label class="form-label lng">From Date</label>
                            <div class="form-group mandatory">
                                <input type="date" class="form-control" id="fromDate" name="fromDate" value="<?= date('Y-m-d', strtotime(' - 7 days')) ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label lng">To Date</label>
                            <div class="form-group mandatory">
                                <input type="date" class="form-control" id="toDate" name="toDate" value="<?= date('Y-m-d') ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-center mt-4">
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
                            <h5>Order List</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="print_div">
                    <table class="table table-striped" id="dataTableOrder">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Branch</th>
                                <th>Order Date</th>
                                <th>Order Code</th>
                                <th>Cashier</th>
                                <th>Counter</th>
                                <th>Customer</th>
                                <th>Total products</th>
                                <th>Grand Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

        </section>
        <!-- Basic Tables end -->
    </div>
</div>
<script>
    $(document).ready(function() {

        var data = '<?php echo $this->session->flashdata('message');
                    unset($_SESSION['message']); ?>';
        if (data != '') {
            var obj = JSON.parse(data);
            if (obj.status) {
                toastr.success(obj.message, 'Inward', {
                    "progressBar": true
                });
            } else {
                toastr.error(obj.message, 'Inward', {
                    "progressBar": true
                });
            }
        }

        var fromDate = $("#fromDate").val();
        var toDate = $("#toDate").val();
        getDataTable("", "", fromDate, toDate);
        //getDataTable("","",fromDate,toDate);
        //$(".buttons-html5").removeClass('btn-primary').addClass('btn-primary sub_1');
        //$(".dt_buttons").removeClass('flex_wrap');
        $('#btnSearch').on('click', function(e) {
            var ordercode = $("#order").val();
            var mobile = $("#mobile").val();
            var fromDate = $("#fromDate").val();
            var toDate = $("#toDate").val();
            getDataTable(ordercode, mobile, fromDate, toDate);
        });
        $('#btnClear').on('click', function(e) {
            $("#order").val('').trigger('change');
            $("#mobile").val('').trigger('change');
            $("#fromDate").val('<?= date('Y-m-d', strtotime(' - 7 days')) ?>')
            $("#toDate").val('<?= date('Y-m-d') ?>')
            getDataTable("", "", "<?= date('Y-m-d', strtotime(' - 7 days')) ?>", "<?= date('Y-m-d') ?>");
        });

        $("body").on("change", "#toDate", function(e) {
            var endDate = $(this).val();
            var startDate = $('#fromDate').val();
            if (startDate > endDate) {
                $("#toDate").val('<?= date('Y-m-d') ?>')
                toastr.success("The End Date should be greater than the Start date.", "Purchase", {
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
                    toastr.success("The End Date should be greater than the Start date.", "Purchase", {
                        "progressBar": true
                    });
                    return false
                }
            }
        });
    });

    function getDataTable(ordercode, mobile, fromDate, toDate) {
        $.fn.DataTable.ext.errMode = 'none';
        if ($.fn.DataTable.isDataTable("#dataTableOrder")) {
            $('#dataTableOrder').DataTable().clear().destroy();
        }
        var dataTable = $('#dataTableOrder').DataTable({
            stateSave: true,
            lengthMenu: [10, 25, 50, 200, 500, 700, 1000],
            processing: true,
            serverSide: true,
            ordering: true,
            searching: true,
            paging: true,
            ajax: {
                url: base_path + "Cashier/OrderReturn/getOrderList",
                data: {
                    'ordercode': ordercode,
                    'mobile': mobile,
                    'fromDate': fromDate,
                    'toDate': toDate
                },
                type: "GET",
                complete: function(response) {

                }
            }
        });
    }
</script>