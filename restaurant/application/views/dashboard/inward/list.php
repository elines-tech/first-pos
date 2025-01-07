<?php include '../restaurant/config.php'; ?>

<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3><?php echo $translations['Inward']?></h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../Dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
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
                            <a id="add_category" href="<?php echo base_url(); ?>inward/add">
                                <i class="fa fa-plus-circle"></i></a>
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
                        <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                            <h5><?php echo $translations['Filter']?></h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">


                        <div class="col-md-6">
                            <label class="form-label lng"><?php echo $translations['Branch']?></label>
                            <div class="form-group">
                                <select class="form-select select2" name="branch" id="branch" <?php if ($branchCode != "") { ?>disabled <?php } ?>>
                                    <?php if ($branchCode != "") { ?>
                                        <option value="<?php echo $branchCode; ?>"><?php echo $branchName; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <label class="form-label lng"><?php echo $translations['Supplier']?></label>
                            <div class="form-group">
                                <select class="form-select select2" name="supplier" id="supplier">

                                </select>
                            </div>
                        </div>



                        <?php
                        $previousDate = date('Y-m-d', strtotime('- 7 days'));
                        ?>
                        <div class="col-md-12">
                            <div class="input-daterange input-group">
                                <span> <label><?php echo $translations['Inward Dates']?></label> </span>


                                <div class="input-daterange mt-2 input-group" id="productDateRange">
                                    <input type="date" class="form-control col-md-12 col-12" name="start" id="fromDate" value="<?= $previousDate ?>" />
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-info b-0 text-white"><?php echo $translations['TO']?></span>
                                    </div>
                                    <input type="date" class="form-control" name="end" id="toDate" value="<?= date('Y-m-d') ?>" />
                                </div>



                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="d-flex text-center justify-content-center mt-4">
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
                            <h5><?php echo $translations['Inward List']?></h5>
                        </div>

                    </div>
                </div>
                <div class="card-body" id="print_div">
                    <table class="table table-striped table-responsive display" id="dataTableInward">
                        <thead>
                            <tr>
                                <th><?php echo $translations['Sr No']?></th>
                                <th><?php echo $translations['Code']?></th>
                                <th><?php echo $translations['Inward Date']?></th>
                                <th><?php echo $translations['Branch']?></th>
                                <th><?php echo $translations['Supplier']?></th>
                                <th><?php echo $translations['Total']?></th>
                                <th><?php echo $translations['Status']?></th>
                                <th><?php echo $translations['Approve']?></th>
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
        $('.cancel').removeClass('btn-default').addClass('btn-info');

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

        $("#supplier").select2({
            placeholder: "Select Supplier",
            allowClear: true,
            ajax: {
                url: base_path + 'Common/getSupplier',
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

        var fromDate = $("#fromDate").val();
        var toDate = $("#toDate").val();
        getDataTable('', '', fromDate, toDate);
        $('#btnSearch').on('click', function(e) {
            var data3 = $("#branch").find('option:selected');
            var branch = data3.val();

            var data4 = $("#supplier").find('option:selected');
            var supplier = data4.val();

            var fromDate = $("#fromDate").val();
            var toDate = $("#toDate").val();

            getDataTable(branch, supplier, fromDate, toDate);
        });
        $('#btnClear').on('click', function(e) {
            $("#branch").val('').trigger('change');
            $("#supplier").val('').trigger('change')
            getDataTable("");
        });
    });

    function getDataTable(branch, supplier, fromDate, toDate) {
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
                    branch: branch,
                    supplier: supplier,
                    fromDate: fromDate,
                    toDate: toDate
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