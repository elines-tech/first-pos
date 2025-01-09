<?php include '../restaurant/config.php'; ?>

<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3><?php echo $translations['Transfer']?></h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../Dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Transfer</li>
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
                            <a id="add_category" href="<?php echo base_url(); ?>Transfer/add">
                                <i class="fa fa-plus-circle"></i></a>
                        </div>
                    </div>

                </div>
            </div>
        <?php } ?>

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <h5><?php echo $translations['Transfer List']?></h5>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="transferTable">
                        <thead>
                            <tr>
                                <th><?php echo $translations['Sr No']?></th>
                                <th><?php echo $translations['Code']?></th>
                                <th><?php echo $translations['Date']?></th>
                                <th><?php echo $translations['Branch from']?></th>
                                <th><?php echo $translations['Branch to']?></th>
                                <th><?php echo $translations['Price']?></th>
                                <th><?php echo $translations['Status']?></th>
                                <th><?php echo $translations['Approve']?></th>
                                <th><?php echo $translations['Action']?></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>

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

        var data = '<?php echo $this->session->flashdata('test'); ?>';
        if (data != '') {
            var obj = JSON.parse(data);
            if (obj.status) {
                toastr.success(obj.test, 'Transfer', {
                    "progressBar": true
                });
            } else {
                toastr.error(obj.test, 'Transfer', {
                    "progressBar": true
                });
            }
        }

        $('.cancel').removeClass('btn-default').addClass('btn-info');
        getDataTable();
    });

    function getDataTable() {
        $.fn.DataTable.ext.errMode = 'none';
        if ($.fn.DataTable.isDataTable("#transferTable")) {
            $('#transferTable').DataTable().clear().destroy();
        }
        var dataTable = $('#transferTable').DataTable({
            stateSave: true,
            lengthMenu: [10, 25, 50, 200, 500, 700, 1000],
            processing: true,
            serverSide: true,
            ordering: true,
            searching: true,
            paging: true,
            ajax: {
                url: base_path + "Transfer/getTransferList",
                data: {},
                type: "GET",
                complete: function(response) {
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

                    $('.delete_group').click(function() {
                        var code = $(this).data('seq');
                        swal({
                            //title: "Are you sure?",
                            title: "<?php echo $translations['Are you sure you want to delete this?']?>",
                            type: "warning",
                            showCancelButton: !0,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "<?php echo $translations['Yes, delete it!']?>",
                            cancelButtonText: "<?php echo $translations['No, cancel it!']?>",
                            closeOnConfirm: !1,
                            closeOnCancel: !1
                        }, function(e) {
                            if (e) {
                                $.ajax({
                                    url: base_path + "Transfer/deleteTransfer",
                                    type: 'POST',
                                    data: {
                                        'code': code
                                    },
                                    success: function(data) {
                                        swal.close()
                                        if (data) {
                                            getDataTable()
                                            toastr.success('Transfer deleted successfully', 'Transfer', {
                                                "progressBar": true
                                            });
                                        } else {
                                            toastr.error('Transfer not deleted', 'Transfer', {
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
            }
        });
    }
</script>