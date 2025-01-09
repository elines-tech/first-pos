<?php include '../supermarket/config.php'; ?>

<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3><?php echo $translations['Stock']?></h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../dashboard/listRecords"><i class="fa fa-dashboard"></i><?php echo $translations['Dashboard']?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $translations['Stock']?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Basic Tables start -->
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

                        <div class="col-md-6">
                            <label class="form-label lng mb-2"><?php echo $translations['Branch']?></label>
                            <div class="form-group mandatory">
                                <select class="form-select" name="branch" id="branch" <?php if ($branchCode != "") { ?>disabled <?php } ?>>
                                    <option value="<?php echo $branchCode; ?>"><?php echo $branchName; ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label lng mb-2"><?php echo $translations['Product']?></label>
                            <div class="form-group mandatory">
                                <select class="form-select select2" name="product" id="product">

                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="d-flex mt-4 text-center justify-content-center">
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
                            <h5><?php echo $translations['Stock List']?></h5>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="print_div">
                    <table class="table table-striped" id="dataTableStock">
                        <thead>
                            <tr>
                                <th><?php echo $translations['Sr No']?></th>
                                <th><?php echo $translations['Branch']?></th>
                                <th><?php echo $translations['Product Variant']?></th>
                                <th><?php echo $translations['Stock']?></th>
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
            var product = $("#product").val();
            var branch = $("#branch").val();
            getDataTable(branch, product);
        });
        $('#btnClear').on('click', function(e) {
            $("#product").val('').trigger('change');
            $("#branch").val('').trigger('change');
            getDataTable("", "");
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

        $("#product").select2({
            placeholder: "Select Product",
            allowClear: true,
            ajax: {
                url: base_path + 'Common/getProductForStock',
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

    function getDataTable(branch, product) {
        $.fn.DataTable.ext.errMode = 'none';
        if ($.fn.DataTable.isDataTable("#dataTableStock")) {
            $('#dataTableStock').DataTable().clear().destroy();
        }
        var dataTable = $('#dataTableStock').DataTable({
            stateSave: true,
            lengthMenu: [10, 25, 50, 200, 500, 700, 1000],
            processing: true,
            serverSide: true,
            ordering: true,
            searching: true,
            paging: true,
            ajax: {
                url: base_path + "stock/getStockList",
                data: {
                    product: product,
                    branch: branch
                },
                type: "GET",
                complete: function(response) {}
            }
        });
    }
</script>