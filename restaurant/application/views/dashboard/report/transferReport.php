<style>
    .dataTables_filter {
        float: right;
    }

    .dataTables_length {
        float: left;
    }

    .dt_buttons {
        float: right;
    }
</style>

<?php include '../restaurant/config.php'; ?>


<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3><?php echo $translations['Transfer Report']?></h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../Dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Transfer Report</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                            <h5><?php echo $translations['Filter']?></h5>
                        </div>
                    </div>
                    <hr>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label class="form-label lng"><?php echo $translations['Branch']?></label>
                            <div class="form-group mandatory">
                                <?php if ($branchCode != "") { ?>
                                    <input type="hidden" class="form-control" name="branch" id="bCode" value="<?= $branchCode; ?>" readonly>
                                    <input type="text" class="form-control" name="fromBranch" value="<?= $branchName; ?>" readonly>

                                <?php } else { ?>
                                    <select class="form-select select2" name="branch" id="branch">

                                    </select>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="d-flex justify-content-center mt-4">
                                <button type="button" class="btn btn-success" id="btnSearch"><?php echo $translations['Search']?></button>
                                <button type="reset" class="btn btn-light-secondary" id="btnClear"><?php echo $translations['Clear']?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body" id="print_div">
                    <table class="table table-striped" id="datatableTable">
                        <thead>
                            <tr>
                                <th><?php echo $translations['Sr No']?></th>
                                <th><?php echo $translations['Code']?></th>
                                <th><?php echo $translations['Date']?></th>
                                <th><?php echo $translations['Branch from']?></th>
                                <th><?php echo $translations['Branch to']?></th>
                                <th><?php echo $translations['Price']?></th>
                                <th><?php echo $translations['Action']?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>

<table id="transferReport" class="table table-striped table-bordered d-none">
    <thead>
        <tr>
            <th>Sr No</th>
            <th>Code</th>
            <th>Date</th>
            <th>From</th>
            <th>To</th>
            <th>Total Amount</th>
        </tr>
    </thead>
</table>

<div class="modal fade text-left" id="generl_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-modal="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header justify-content-between">
                <h4 class="modal-title font-size-h4 text-center lng">Transfer</h4>
            </div>
            <div class="modal-body panel-body">
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        loadTable();
        $(".buttons-html5").removeClass('btn-primary').addClass('btn-printFormat');
        $(".dt_buttons").removeClass('flex_wrap');
        $('#btnSearch').on('click', function(e) {
            if ($("#branch").val() != "") {
                var branchCode = $("#branch").val();
            } else {
                var branchCode = $("#bCode").val();
            }
            loadTable(branchCode);
        });

        $('#btnClear').on('click', function(e) {
            $("#branch").val('').trigger('change')
            loadTable("");
        });

        $("#branch").select2({
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
    });

    function loadTable(branchCode) {
        if ($.fn.DataTable.isDataTable("#datatableTable")) {
            $('#datatableTable').DataTable().clear().destroy();
        }

        jQuery.fn.DataTable.Api.register('buttons.exportData()', function(options) {
            if (this.context.length) {
                var jsonResult = $.ajax({
                    url: base_path + "report/getTransferList",
                    data: {
                        'export': 1,
                        'branchCode': branchCode
                    },
                    type: "GET",
                    success: function(result) {},
                    async: false
                });
                var jencode = JSON.parse(jsonResult.responseText);
                return {
                    body: jencode.data,
                    header: $("#transferReport thead tr th").map(function() {
                        return this.innerHTML;
                    }).get()
                };
            }
        });

        var dataTable = $('#datatableTable').DataTable({
            dom: 'B<"flex-wrap mt-2"fl>trip',
            buttons: [{
                extend: 'excelHtml5',
                title: 'Transfer Report'
            }, {
                extend: 'pdf',
                title: 'Transfer Report'
            }],
            stateSave: true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "searching": true,
            "ajax": {
                url: base_path + "report/getTransferList",
                type: "GET",
                data: {
                    'branchCode': branchCode
                },
                "complete": function(response) {
                    $('.edit_group').click(function() {
                        var code = $(this).data('seq');
                        $.ajax({
                            url: base_path + "Transfer/view",
                            type: 'POST',
                            data: {
                                'code': code,
                            },
                            success: function(response) {
                                $('#generl_modal').modal('show');
                                $(".panel-body").html(response);
                            }
                        });
                    });
                }
            }
        });
    }
</script>