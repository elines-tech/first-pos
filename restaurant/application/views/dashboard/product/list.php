<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Product</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Product</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <?php if ($insertRights == 1) { ?>
            <div id="maindiv" class="container">
                <!--<div class="row">-->
                    <!--<div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">-->
                        <div class="floating-action-button">
                            <a id="add_category" href="<?php echo base_url(); ?>Product/add">
                                <i class="fa fa-plus-circle"></i></a>
                            </div>
                    <!--</div>-->
                <!--</div>-->
            </div>
        <?php } ?>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                            <h5>Product List</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" style="width:100%" id="dataTable-Product">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Arabic Name</th>
                                    <th>Hindi Name</th>
                                    <th>Urdu Name</th>
                                    <th>Category</th>
                                    <th>Subcategory</th>
                                    <th>TaxGroup</th>
                                    <th>Photo</th>
                                    <th>Status</th>
                                    <th>Action</th>
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
        if ($.fn.DataTable.isDataTable("#dataTable-Product")) {
            $('#dataTable-Product').DataTable().clear().destroy();
        }
        var dataTable = $('#dataTable-Product').DataTable({
            stateSave: true,
            lengthMenu: [10, 25, 50, 200, 500, 700, 1000],
            processing: true,
            serverSide: true,
            ordering: true,
            scrollX: true,
            searching: true,
            paging: true,
            ajax: {
                url: base_path + "Product/getList",
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
                        url: base_path + "Product/delete",
                        type: 'POST',
                        data: {
                            'code': code
                        },
                        success: function(data) {
                            swal.close()
                            if (data) {
                                getDataTable();
                                toastr.success('Product deleted successfully', 'Product', {
                                    "progressBar": true
                                });
                            } else {
                                toastr.error('Product not deleted', 'Product', {
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

    $(document).ready(function() {
        var data = '<?php echo $this->session->flashdata('message');
                    unset($_SESSION['message']); ?>';
        if (data != '') {
            var obj = JSON.parse(data);
            if (obj.status) {
                toastr.success(obj.message, 'Product', {
                    "progressBar": true
                });
            } else {
                toastr.error(obj.message, 'Product', {
                    "progressBar": true
                });
            }
        }
    });
</script>