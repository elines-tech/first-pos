<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Inward</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Inward</li>
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
                            <a id="add_category" href="<?= base_url() ?>inward/add"><i class="fa fa-plus-circle"></i></a>
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
                        <div class="col-9">
                            <h5>Filter </h5>
                        </div>
                    </div>
                    <hr>
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label class="form-label lng">From Branch</label>
                            <div class="form-group mandatory">
                                <select class="form-select select2" name="frombranch" id="frombranch" <?php if ($branchCode != "") { ?>disabled <?php } ?>>
                                    <?php if ($branchCode != "") { ?>
                                        <option value="<?php echo $branchCode; ?>"><?php echo $branchName; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label lng">To Branch</label>
                            <div class="form-group mandatory">
                                <select class="form-select select2" name="tobranch" id="tobranch">
                                    <option value="">Select Branch</option>
                                    <?php if ($branch) {
                                        echo "<optgroup label='Branch'>";
                                        foreach ($branch->result() as $br) {

                                            echo '<option value="' . $br->code . '">' . $br->branchName . '</option>';
                                        }
                                        echo "</optgroup>";
                                    } ?>
                                    <?php if ($supplier) {
                                        echo "<optgroup label='supplier'>";
                                        foreach ($supplier->result() as $sr) {

                                            echo '<option value="' . $sr->code . '">' . $sr->supplierName . '</option>';
                                        }
                                        echo "</optgroup>";
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
                            <div class="d-flex text-center justify-content-center mt-4">
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
                            <h5>Inward List</h5>
                        </div>

                    </div>
                </div>
                <div class="card-body" id="print_div">
                    <table class="table table-striped table-responsive display" id="dataTableInward">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Batch No</th>
                                <th>Inward Date</th>
                                <th>Branch</th>
                                <th>Supplier</th>
                                <th>Total</th>
                                <th>Approved</th>
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
        $("#frombranch").select2({
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
        });
        $('.cancel').removeClass('btn-default').addClass('btn-info');
        var fromDate = $("#fromDate").val();
        var toDate = $("#toDate").val();
        getDataTable("", "", fromDate, toDate, "");
        $(".buttons-html5").removeClass('btn-primary').addClass('btn-primary sub_1');
        $(".dt_buttons").removeClass('flex_wrap');
        $('#btnSearch').on('click', function(e) {
            var frombranchCode = $("#frombranch").val();
            var tobranchCode = $("#tobranch").val();
            var fromDate = $("#fromDate").val();
            var toDate = $("#toDate").val();
            var supplierCode = $("#supplier").val();
            getDataTable(frombranchCode, tobranchCode, fromDate, toDate, supplierCode);
        });
        $('#btnClear').on('click', function(e) {
            $("#frombranch").val('').trigger('change');
            $("#tobranch").val('').trigger('change');
            $("#fromDate").val('<?= date('Y-m-d', strtotime(' - 7 days')) ?>')
            $("#toDate").val('<?= date('Y-m-d') ?>')
            getDataTable("", "", "<?= date('Y-m-d', strtotime(' - 7 days')) ?>", "<?= date('Y-m-d') ?>", "");
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

    function getDataTable(frombranchCode, tobranchCode, fromDate, toDate, supplierCode) {
        $.fn.DataTable.ext.errMode = 'none';
        if ($.fn.DataTable.isDataTable("#dataTableInward")) {
            $('#dataTableInward').DataTable().clear().destroy();
        }
        var dataTable = $('#dataTableInward').DataTable({
            stateSave: true,
            responsive: true,
            lengthMenu: [10, 25, 50, 200, 500, 700, 1000],
            processing: true,
            serverSide: true,
            ordering: true,
            searching: true,
            paging: true,
            ajax: {
                url: base_path + "inward/getInwardList",
                data: {
                    'frombranchCode': frombranchCode,
                    'tobranchCode': tobranchCode,
                    'fromDate': fromDate,
                    'toDate': toDate,
                    'supplierCode': supplierCode
                },
                type: "GET",
                complete: function(response) {
                    operations();
                }
            }
        });
    }

    function operations() {
        $('.delete_inward').click(function() {
            var code = $(this).attr('id');
            swal({
                //title: "Are you sure?",
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
                        url: base_path + "inward/deleteInward",
                        type: 'POST',
                        data: {
                            'code': code
                        },
                        success: function(data) {
                            swal.close()
                            if (data) {
                                getDataTable()
                                toastr.success('Inward deleted successfully', 'Inward', {
                                    "progressBar": true
                                });
                            } else {
                                toastr.error('Inward not deleted', 'Inward', {
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
</script>