<style>
.dataTables_filter{
	float:right;
}
.dataTables_length{
	float:left;
}
.dt_buttons{
	float:right;
}
</style>
<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Account Expense Report</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><i class="fa fa-dashboard"></i> Dashboard</li>
                            <li class="breadcrumb-item active" aria-current="page">Account Expense Report</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
       
        <section class="section">
			<div class="card">
			<div class="card-header">
			        <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                            <h5>Filter</h5>
                        </div>
                    </div>
					<hr>
			      <div class="row mt-3">
						<div class="col-md-3">
						    <label class="form-label lng">From Date</label>
							<div class="form-group mandatory">
								<input type="date" class="form-control" id="fromDate" name="fromDate" value="<?= date('Y-m-d',strtotime(' - 7 days'))?>">
							</div>
						</div>
						<div class="col-md-3">
						    <label class="form-label lng">To Date</label>
							<div class="form-group mandatory">
								<input type="date" class="form-control" id="toDate" name="toDate" value="<?= date('Y-m-d')?>">
							</div>
						</div>
					    <div class="col-md-3">
						    <label class="form-label lng">Branch</label>
							<div class="form-group mandatory">
							    <?php if($branchCode!=""){?>
								 <input type="hidden" class="form-control" name="branch" id="bCode"  value="<?= $branchCode; ?>" readonly>
								 <input type="text" class="form-control" name="fromBranch" value="<?= $branchName; ?>" readonly>
								
								<?php } else{?>
								<select class="form-select select2" name="branch" id="branch">
									<option value="">Select </option>
									 <?php if ($branch) {
										foreach ($branch->result() as $br) {
											echo '<option value="' . $br->code . '">' . $br->branchName . '</option>';
										}
									} ?>
								</select>
								<?php } ?>
							</div>
						</div>
                        <div style="text-align:center;">
						   <div class="d-flex mt-2">
								<button type="button" class="btn btn-success white me-1 mb-1 sub_1" id="btnSearch">Search</button>
								<button type="reset" class="btn btn-light-secondary me-1 mb-1" id="btnClear">Clear</button>
							</div>
						</div>
					</div>
				</div>
			</div>
            <div class="card">
                <div class="card-body" id="print_div">
                    <table class="table table-striped" id="datatableAccountExpense">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Code</th>
                                <th>Date</th>
                                <th>Branch</th>
                                <th>Title</th>
                                <th>Cost</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
				<div class="col-sm-4 offset-sm-8 mt-1">
					<h4 class="border m-2">Total Cost- <span id="total" class="float-right">0.00</span></h4>
				</div>
            </div>
        </section>
    </div>
</div>
</div>
</div>
</body>
<table id="accountExpense" class="table table-striped table-bordered d-none">
	<thead>
		<tr>
			<th>Sr No</th>
			<th>Code</th>
			<th>Date</th>
			<th>Branch</th>
			<th>Title</th>
			<th>Cost</th>
		</tr>
	</thead>
</table> 
<div class="modal fade text-left" id="generl_modal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id='modal_label1'>View Account Expense Details</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body2">
                                <form id="viewcategoryForm">
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Branch</label>
                                                <div class="col-md-8">
                                                    <select class="form-select select2" style="width:100%" name="viewbranch" id="viewbranch" disabled>
                                                       
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Date</label>
                                                <div class="col-md-8">
                                                    <input type="date" id="viewdate" class="form-control" name="viewdate" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory">
                                                <label for="expensename" class="col-md-4 form-label text-left">Expense Name:</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="viewexpensename" class="form-control" placeholder="Expense Name" name="viewexpensename" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row mandatory">
                                                <label for="expensecost" class="col-md-4 form-label text-left">Expense Cost:</label>
                                                <div class="col-md-8">
                                                    <input type="text" id="viewexpensecost" class="form-control" placeholder="Expense Cost" name="viewexpensecost" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row">
                                                <label for="expensedescription" class="col-md-4 form-label text-left">Expense Description :</label>
                                                <div class="col-md-8">
                                                    <textarea id="viewexpensedescription" rows="6" class="form-control" name="viewexpensedescription" readonly></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="button" class="btn btn-light-secondary me-1 mb-1" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
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
		var fromDate = $("#fromDate").val();
	    var toDate = $("#toDate").val();
        loadTable('',fromDate,toDate);
		$(".buttons-html5").removeClass('btn-primary').addClass('btn-primary sub_1');
		$(".dt_buttons").removeClass('flex_wrap');
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
		$('#btnSearch').on('click', function(e) {
			if($("#branch").val()!=""){
			    var branchCode = $("#branch").val();
			}else{
				var branchCode = $("#bCode").val();
			}
			var fromDate = $("#fromDate").val();
			var toDate = $("#toDate").val();
			loadTable(branchCode,fromDate,toDate);
		});
		$('#btnClear').on('click', function(e) {
			$("#branch").val('').trigger('change')
			$("#cashierCode").val('').trigger('change')
			$("#fromDate").val('<?= date('Y-m-d',strtotime(' - 7 days'))?>')
			$("#toDate").val('<?= date('Y-m-d')?>')
			loadTable("","","<?= date('Y-m-d',strtotime(' - 7 days'))?>","<?= date('Y-m-d')?>");
			$(".buttons-html5").removeClass('btn-primary').addClass('btn-primary sub_1');
		});
		$("body").on("change","#toDate",function(e){
		var endDate = $(this).val();
		var startDate =$('#fromDate').val();
		if(startDate  > endDate){
			$("#toDate").val('<?= date('Y-m-d')?>')
			toastr.success("The End Date should be greater than the Start date.","Purchase",{"progressBar":true});
			return false
		  }
	    });
		$("body").on("change","#fromDate",function(e){
			var endDate = $('#toDate').val();		
			if(endDate!=""){
				
				var startDate =$(this).val();
				if(startDate  > endDate){
				$("#fromDate").val('<?= date('Y-m-d',strtotime(' - 7 days'))?>')
				toastr.success("The End Date should be greater than the Start date.","Purchase",{"progressBar":true});
				return false 
				}
			  }
			});
    });
	
	function getTotalCost(branchCode,fromDate,toDate){       
		   $.ajax({
				url:base_path+"ExpenseReport/totalAmount",
				method:"get",
				data:{
					'branchCode':branchCode,
					'fromDate':fromDate,
					'toDate':toDate,
				},
				datatype:"text",
				success: function(data) {
					  $("#total").text(data) ;
				}
			});
	}
    function loadTable(branchCode,fromDate,toDate) {
        if ($.fn.DataTable.isDataTable("#datatableAccountExpense")) {
            $('#datatableAccountExpense').DataTable().clear().destroy();
        }
		jQuery.fn.DataTable.Api.register( 'buttons.exportData()', function ( options ) {
				if ( this.context.length ) {
					var jsonResult = $.ajax({
						url: base_path+"ExpenseReport/getAccountExpenseList",
						data: {
							'export':1,
							'branchCode':branchCode,
							'fromDate':fromDate,
							'toDate':toDate,
						},
						type:"GET", 
						success: function (result) {
						},
						async: false
					});
					var jencode=JSON.parse(jsonResult.responseText);
					return {body: jencode.data, header: $("#accountExpense thead tr th").map(function() { return this.innerHTML; }).get()};
				}
			});
        var dataTable = $('#datatableAccountExpense').DataTable({
			dom:'B<"flex-wrap mt-2"fl>trip',
			buttons: [{
                extend: 'excel',
                title: 'Account Expense Report'
            },
			{
                extend: 'pdf',
                title: 'Account Expense Report'
            }],
            stateSave: false,
            "processing": true,
            "serverSide": true,
            "order": [],
            "searching": true,
            "ajax": {
                url: base_path + "ExpenseReport/getAccountExpenseList",
                type: "GET",
				data:{
					'branchCode':branchCode,
					'fromDate':fromDate,
					'toDate':toDate,
					'export':0
				},
                "complete": function(response) {
					$('#total').text((response.responseJSON.total));
                     $('.view_account_expense').click(function() {
                        
                        var code = $(this).data('seq');
                        $.ajax({
                            url: base_path + "ExpenseReport/viewAccountExpense",
                            type: 'POST',
                            data: {
                                'code': code,
                            },
                            success: function(response) {
                                
                                var obj = JSON.parse(response);
                                if (obj.status) {
                                    $('#generl_modal1').modal('show');
                                    $('#viewexpensename').val(obj.expensename);
                                    $('#viewexpensecost').val(obj.expensecost);
                                    $('#viewdate').val(obj.date);
                                    $('#viewexpensedescription').val(obj.expensedescription);
                                    $('#viewbranch').empty();
                                    $('#viewbranch').append(obj.branch);
                                } else {
                                    toastr('Something Wend Wrong', 'Account Expense', {
                                        progressBar: true
                                    })
                                }
                            }
                        });
                    });
               }
            }
        });
    }
</script>