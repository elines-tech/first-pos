
<?php include '../restaurant/config.php'; ?>

<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3><?php echo $translations['Quotation']?></h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../Dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Quotation</li>
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
                            <a id="add_category" href="<?php echo base_url(); ?>Quotation/add">
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
                        <div class="col-md-10">
                            <label class="form-label lng"><?php echo $translations['Remark']?></label>
                            <div class="form-group mandatory">
                                <select class="form-select" name="remark" id="remark">
                                    <option value=""><?php echo $translations['Select Remark']?></option>
                                    <option value="Not interested">Not interested</option>
                                    <option value="Ask to call later">Ask to call later</option>
                                    <option value="Next follow up date"> Next follow up date</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="d-flex mt-4">
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
                            <h5><?php echo $translations['Quotation List']?></h5>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="print_div">
                    <table class="table table-striped" id="dataTableQuotation">
                        <thead>
                            <tr>
                                <th><?php echo $translations['Sr No']?></th>
                                <th><?php echo $translations['Code']?></th>
                                <th><?php echo $translations['Date']?></th>
                                <th><?php echo $translations['Event Name']?></th>
                                <th><?php echo $translations['Number of Peoples']?></th>
                                <th><?php echo $translations['Remark']?></th>
                                <th><?php echo $translations['Action']?></th>
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
        $('.cancel').removeClass('btn-default').addClass('btn-info');
        getDataTable();
        $('#btnSearch').on('click', function(e) {

            var data3 = $("#remark").find('option:selected');
            var remark = data3.val();
            getDataTable(remark);
        });
        $('#btnClear').on('click', function(e) {
            $("#remark").val('').trigger('change')
            getDataTable("");
        });
    });

    function getDataTable(remark) {
        $.fn.DataTable.ext.errMode = 'none';
        if ($.fn.DataTable.isDataTable("#dataTableQuotation")) {
            $('#dataTableQuotation').DataTable().clear().destroy();
        }
        var dataTable = $('#dataTableQuotation').DataTable({
            stateSave: true,
            lengthMenu: [10, 25, 50, 200, 500, 700, 1000],
            processing: true,
            serverSide: true,
            ordering: true,
            searching: true,
            paging: true,
            ajax: {
                url: base_path + "Quotation/getquotationList",
                data: {
                    remark: remark
                },
                type: "GET",
                complete: function(response) {
                    operations();
                }
            }
        });
    }

    function operations() {
        $('.delete_quotation').click(function() {
            var code = $(this).attr('id');
            swal({
                //title: "Are you sure?",
                //text: "You want to delete recipe " + code,
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
                        url: base_path + "Quotation/deletequotation",
                        type: 'POST',
                        data: {
                            'code': code
                        },
                        success: function(data) {
                            swal.close();
                            if (data) {
                                getDataTable();
                                toastr.success('Quotation deleted successfully', 'Quotation', {
                                    "progressBar": true
                                });
                            } else {
                                toastr.error('Quotation Not Deleted', 'Quotation', {
                                    "progressBar": true
                                });
                            }
                        }
                    });
                } else {
                    swal.close()
                }
            });
        });
    }
</script>