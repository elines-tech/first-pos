<?php include '../supermarket/config.php'; ?>

<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3><?php echo $translations['Inward Return']?></h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../dashboard/listRecords"><i class="fa fa-dashboard"></i><?php echo $translations['Dashboard']?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $translations['Inward Return']?></li>
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
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-10">
                            <label class="form-label lng"><?php echo $translations['Branch']?></label>
                            <div class="form-group mandatory">
                                <select class="form-select" name="branch" id="branch" <?php if ($branchCode != "") { ?>disabled <?php } ?>>
                                    <option value="<?php echo $branchCode; ?>"><?php echo $branchName; ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="d-flex mt-4 justify-content-center">
                                <button type="button" class="btn btn-success" id="btnSearch"><?php echo $translations['Search']?></button>
                                <button type="reset" class="btn btn-light-secondary" id="btnClear"><?php echo $translations['Clear']?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                            <h5><?php echo $translations['Inward Return List']?></h5>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="print_div">
                    <table class="table table-striped table-responsive display" id="dataTableInward">
                        <thead>
                            <tr>
                                <th><?php echo $translations['Sr No']?></th>
                                <th><?php echo $translations['Code']?></th>
                                <th><?php echo $translations['Batch No']?></th>
                                <th><?php echo $translations['Inward Date']?></th>
                                <th><?php echo $translations['Branch']?></th>
                                <th><?php echo $translations['Supplier']?></th>
                                <!--<th>No Of Returns</th>-->
                                <th><?php echo $translations['Action']?></th>
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
        getDataTable();
        $('#btnSearch').on('click', function(e) {
            var branch = $("#branch").val();
            getDataTable(branch);
        });
        $('#btnClear').on('click', function(e) {
            $("#branch").val('').trigger('change')
            getDataTable("");
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

    function getDataTable(branch) {
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
                url: base_path + "inwardReturn/getReturnList",
                data: {
                    'branch': branch
                },
                type: "GET",
                complete: function(response) {}
            }
        });
    }
</script>