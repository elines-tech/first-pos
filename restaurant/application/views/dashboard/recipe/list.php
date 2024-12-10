<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Recipe</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Recipe</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
		<?php if($insertRights==1){ ?>
        <div id="maindiv" class="container">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                    <h2><a href="<?php echo base_url(); ?>recipe/add"><i class="fa fa-plus-circle"></i></a></h2>
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
                            <h5>Recipe List</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="print_div">
                    <table class="table table-striped" id="dataTableRecipe">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Code</th>
                                <th>Product Name</th>
                                <th>No Of items</th>
                                <th>Status</th>
                                <th></th>
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
    });

    function getDataTable() {
        $.fn.DataTable.ext.errMode = 'none';
        if ($.fn.DataTable.isDataTable("#dataTableRecipe")) {
            $('#dataTableRecipe').DataTable().clear().destroy();
        }
        var dataTable = $('#dataTableRecipe').DataTable({
            stateSave: true,
            lengthMenu: [10, 25, 50, 200, 500, 700, 1000],
            processing: true,
            serverSide: true,
            ordering: true,
            searching: true,
            paging: true,
            ajax: {
                url: base_path + "recipe/getRecipeList",
                data: {},
                type: "GET",
                complete: function(response) {
                    operations();
                }
            }
        });
    }

    function operations() {
        $('.delete_recipe').click(function() {
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
                        url: base_path + "recipe/deleteRecipe",
                        type: 'POST',
                        data: {
                            'code': code
                        },
                        success: function(data) {
                            swal.close();
                            if (data) {
                                getDataTable();
                                toastr.success('Recipe deleted successfully', 'Recipe', {
                                    "progressBar": true
                                });
                            } else {
                                toastr.error('Recipe Not Deleted', 'Recipe', {
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