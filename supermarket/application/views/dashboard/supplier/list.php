<?php include '../supermarket/config.php'; ?>

<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3><?php echo $translations['Supplier']?></h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Supplier</li>
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
                            <a id="add_category" href="<?php echo base_url(); ?>supplier/add"><i class="fa fa-plus-circle"></i></a>
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
                            <h5><?php echo $translations['Supplier List']?></h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-reponsive">
                        <table class="table table-striped" id="dataTable-Supplier" style="width:100%">
                            <thead>
                                <tr>
                                    <th><?php echo $translations['Sr No']?></th>
                                    <th><?php echo $translations['Code']?></th>
                                    <th><?php echo $translations['Name']?></th>
                                    <th><?php echo $translations['Arabic Name']?></th>
                                    <th><?php echo $translations['Company Name']?></th>
                                    <th><?php echo $translations['Email']?></th>
                                    <th><?php echo $translations['Phone']?></th>
                                    <th><?php echo $translations['Status']?></th>
                                    <th><?php echo $translations['Action']?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.cancel').removeClass('btn-default').addClass('btn-info');
        getDataTable();
        setTimeout(function() {
            $('#msg').fadeOut('fast');
        }, 6000);
    });

    function getDataTable() {
        $.fn.DataTable.ext.errMode = 'none';
        if ($.fn.DataTable.isDataTable("#dataTable-Supplier")) {
            $('#dataTable-Supplier').DataTable().clear().destroy();
        }
        var dataTable = $('#dataTable-Supplier').DataTable({
            stateSave: true,
            lengthMenu: [10, 25, 50, 200, 500, 700, 1000],
            processing: true,
            serverSide: true,
            scrollX: true,
            ordering: false,
            searching: true,
            paging: true,
            ajax: {
                url: base_path + "Supplier/getList",
                data: {},
                type: "GET",
                complete: function(response) {
                    operations();
                }
            }
        });
    }

    function operations() {
        $('.delete_id').click(function() {
            var code = $(this).attr('id');
            swal({
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
                        url: base_path + "Supplier/delete",
                        type: 'POST',
                        data: {
                            'code': code
                        },
                        success: function(data) {
                            swal.close();
                            if (data) {
                                getDataTable();
                                toastr.success('Supplier deleted successfully', 'Supplier', {
                                    "progressBar": true
                                });
                            } else {
                                toastr.error('Supplier not deleted', 'Supplier', {
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

    $(document).ready(function() {
        var data = '<?php echo $this->session->flashdata('message');
                    unset($_SESSION['message']); ?>';
        if (data != '') {
            var obj = JSON.parse(data);
            if (obj.status) {
                toastr.success(obj.message, 'Supplier', {
                    "progressBar": true
                });
            } else {
                toastr.error(obj.message, 'Supplier', {
                    "progressBar": true
                });
            }
        }
    });
</script>