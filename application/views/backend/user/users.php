<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Users</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">User</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>


    <!--<div id="maindiv" class="container">-->
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
            <div class="floating-action-button">
                <a id="add_category" href="<?php echo base_url(); ?>users/add"><i class="fa fa-plus-circle"></i></a>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="row">

                    <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                        <h5>Users List</h5>
                    </div>

                </div>
            </div>
            <div class="card-body" id="print_div">
                <table class="table table-striped" id="dataTable-User">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Code</th>
                            <th>User name</th>
                            <th>Name</th>
                            <th>Employee Number</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

    </section>
    <!--</div>-->


</div>
<script src="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>
<script src="<?= base_url('assets/js/pages/datatables.js') ?>"></script>
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
        if ($.fn.DataTable.isDataTable("#dataTable-User")) {
            $('#dataTable-User').DataTable().clear().destroy();
        }
        var dataTable = $('#dataTable-User').DataTable({
            stateSave: true,
            lengthMenu: [10, 25, 50, 200, 500, 700, 1000],
            processing: true,
            serverSide: true,
            ordering: true,
            searching: true,
            paging: true,
            ajax: {
                url: base_path + "users/getList",
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
                //text: "You want to delete User Record of " + code,
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
                        url: base_path + "users/delete",
                        type: 'POST',
                        data: {
                            'code': code
                        },
                        success: function(data) {
                            swal.close()
                            if (data) {
                                getDataTable();
                                toastr.success('User deleted successfully', 'User', {
                                    "progressBar": true
                                });
                                swal.close()
                            } else {
                                toastr.success('User Not Deleted', 'Failed', {
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
        var data = '<?php echo $this->session->flashdata('message'); ?>';
        if (data != '') {
            var obj = JSON.parse(data);
            if (obj.status) {
                toastr.success(obj.message, 'User', {
                    "progressBar": true
                });
            } else {
                toastr.error(obj.message, 'User', {
                    "progressBar": true
                });
            }
        }
    });
</script>