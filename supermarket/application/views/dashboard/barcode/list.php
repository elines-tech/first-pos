<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Barcode</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
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
                            <a id="add_category" href="<?php echo base_url(); ?>barcode/add"><i class="fa fa-plus-circle"></i></a>
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
                            <h5>Barcode List</h5>
                        </div>

                    </div>
                </div>
                <div class="card-body" id="print_div">
                    <table class="table table-striped table-responsive display" id="datatableBarcode">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Batch</th>
                                <th>Barcode</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Selling Price</th>
                                <th>Discount Price</th>
                                <th>Tax Percent</th>
                                <th>Tax Amount</th>
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
<div class="modal fade text-left" id="generl_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id='modal_label'>Generate Barcode</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body1">
                                <form id="barcodeForm" method="post" action="<?= base_url() ?>barcode/generateBarcode" class="form" data-parsley-validate>
                                    <div class="row">
                                        <div class="col-md-5 col-12">
                                            <label class="form-label">Barcode Quantity : <b style="color:red">*</b></label>
                                            <input type="text" id="barcodeQty" class="form-control" name="barcodeQty" required>
                                        </div>

                                        <div class="col-md-5 col-12" style="margin-top:32px;">
                                            <input type="hidden" class="form-control" id="lineCode" name="lineCode">
                                            <button type="submit" class="btn btn-primary white sub_1" id="generateBtn">Generate</button>
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
<script>
    $(document).ready(function() {
        $('.cancel').removeClass('btn-default').addClass('btn-info');
        getDataTable();
    });

    function getDataTable() {
        $.fn.DataTable.ext.errMode = 'none';
        if ($.fn.DataTable.isDataTable("#datatableBarcode")) {
            $('#datatableBarcode').DataTable().clear().destroy();
        }
        var dataTable = $('#datatableBarcode').DataTable({
            stateSave: true,
            responsive: true,
            lengthMenu: [10, 25, 50, 200, 500, 700, 1000],
            processing: true,
            serverSide: true,
            ordering: true,
            searching: true,
            paging: true,
            ajax: {
                url: base_path + "barcode/getBarcodeList",
                data: {},
                type: "GET",
                complete: function(response) {
                    operations();
                }
            }
        });
    }

    function operations() {
        $('.delete_barcode').click(function() {
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
                        url: base_path + "barcode/deleteBarcode",
                        type: 'POST',
                        data: {
                            'code': code
                        },
                        success: function(data) {
                            swal.close()
                            if (data) {
                                getDataTable()
                                toastr.success('Barcode deleted successfully', 'Barcode', {
                                    "progressBar": true
                                });
                            } else {
                                toastr.error('Barcode not deleted', 'Barcode', {
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