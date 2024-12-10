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
                            <li class="breadcrumb-item"><i class="fa fa-dashboard"></i> Dashboard</li>
                            <li class="breadcrumb-item active" aria-current="page">Product</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <?php if ($insertRights == 1) { ?>
            <div id="maindiv" class="container">
                <div class="row">

                    <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                        <h2><a href="<?php echo base_url(); ?>Product/add"><i class="fa fa-plus-circle"></i></a></h2>
                    </div>
                </div>
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
                <div class="card-body" id="print_div">
                    <div class="table-responseive">
                        <table class="table table-striped" id="dataTable-Product" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sr no</th>
                                    <th>Code</th>
                                    <th>SKU</th>
                                    <th>Brand</th>
                                    <th>Category</th>
                                    <th>Subcategory</th>
                                    <th>Product Name</th>
                                    <th>Unit</th>
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
            ordering: false,
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