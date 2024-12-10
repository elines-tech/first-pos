<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Product Variant</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Product Variant</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div id="maindiv" class="container">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                    <h2><a href="<?= base_url()?>variant/add"><i class="fa fa-plus-circle cursor_pointer"></i></a></h2>
                </div>
            </div>
        </div>
        <!-- Basic Tables start -->
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                            <h5>Product Variant List</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="print_div">
                    <table class="table table-striped" id="datatableVariant">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Product</th>
                                <th>Variation</th>
                                <th>Selling Unit</th>
								<th>Selling Quantity</th> 
								<th>Price</th> 
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>
</div>
</div>
</body>

<script>
    $(document).ready(function() {
        loadVariant();
    });
    function loadVariant() {
        if ($.fn.DataTable.isDataTable("#datatableVariant")) {
            $('#datatableVariant').DataTable().clear().destroy();
        }
        var dataTable = $('#datatableVariant').DataTable({
            stateSave: true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "searching": true,
            "ajax": {
                url: base_path + "variant/getVariantList",
                type: "GET",
                "complete": function(response) {
                
                }
            }
        });
    }
</script>