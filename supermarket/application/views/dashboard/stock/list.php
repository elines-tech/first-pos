<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Stock</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><i class="fa fa-dashboard"></i> Dashboard</li>
                            <li class="breadcrumb-item active" aria-current="page">Stock</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Basic Tables start -->
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                            <h5>Filter</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                       
						<div class="col-md-4">
                            <label class="form-label lng">Branch</label>
                            <div class="form-group mandatory">
                              <select class="form-select" name="branch" id="branch"  <?php if($branchCode!=""){?>disabled <?php }?>>
									   <option value="<?php echo $branchCode;?>"><?php echo $branchName;?></option>               
								</select> 
                            </div>
                        </div>
						 <div class="col-md-4">
                            <label class="form-label lng">Product</label>
                            <div class="form-group mandatory">
                                <select class="form-select select2" name="product" id="product">
                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex mt-4">
                                <button type="button" class="btn btn-success white me-1 mb-1 sub_1" id="btnSearch">Search</button>
                                <button type="reset" class="btn btn-light-secondary me-1 mb-1" id="btnClear">Clear</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                            <h5>Stock List</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="print_div">
                    <table class="table table-striped" id="dataTableStock">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Branch</th>
                                <th>Product Variant</th>
                                <th>Stock</th>
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
        getDataTable();
        $('#btnSearch').on('click', function(e) {
            var product = $("#product").val();
            var branch = $("#branch").val();
            getDataTable(branch,product);
        });
        $('#btnClear').on('click', function(e) {
            $("#product").val('').trigger('change');
            $("#branch").val('').trigger('change');
            getDataTable("","");
        });
		$("#branch").select2({
			    placeholder: "Select Branch",
                allowClear: true,
				ajax: {
					url:  base_path+'Common/getBranch',
					type: "get",
					delay:250,
					dataType: 'json',
					data: function (params) { 
						var query = {
                            search: params.term
                          }
                          return query;
					}, 
					processResults: function (response) {
						return {
							results: response
						};
					},
					cache: true
				}	
		});
		
		$("#product").select2({
			    placeholder: "Select Product",
                allowClear: true,
				ajax: {
					url:  base_path+'Common/getProductForStock',
					type: "get",
					delay:250,
					dataType: 'json',
					data: function (params) { 
						var query = {
                            search: params.term
                          }
                          return query;
					}, 
					processResults: function (response) {
						return {
							results: response
						};
					},
					cache: true
				}	
		});
    });

    function getDataTable(branch,product) {
        $.fn.DataTable.ext.errMode = 'none';
        if ($.fn.DataTable.isDataTable("#dataTableStock")) {
            $('#dataTableStock').DataTable().clear().destroy();
        }
        var dataTable = $('#dataTableStock').DataTable({
            stateSave: true,
            lengthMenu: [10, 25, 50, 200, 500, 700, 1000],
            processing: true,
            serverSide: true,
            ordering: true,
            searching: true,
            paging: true,
            ajax: {
                url: base_path + "stock/getStockList",
                data: {
                    product: product,
                    branch: branch
                },
                type: "GET",
                complete: function(response) {}
            }
        });
    }
</script>