<style>
    .dataTables_filter {
        float: right;
    }

    .dataTables_length {
        float: left;
    }

    .dt_buttons {
        float: right;
    }
</style>
<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Gift Sell</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><i class="fa fa-dashboard"></i> Dashboard</li>
                            <li class="breadcrumb-item active" aria-current="page">Gift Sell Report</li>
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
                                <input type="date" class="form-control" id="fromDate" name="fromDate" value="<?= date('Y-m-d', strtotime(' - 7 days')) ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label lng">To Date</label>
                            <div class="form-group mandatory">
                                <input type="date" class="form-control" id="toDate" name="toDate" value="<?= date('Y-m-d') ?>">
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
                                <th>Invoice No</th>
                                <th>Gift Details</th>
                                <th>Issued Date</th>
                                <th>Expiry Date</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Total Amount</th>
                                <th>Issued Count</th>
                                <th></th>
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

<table id="accountExpense" class="table table-striped table-bordered d-none">
    <thead>
        <tr>
            <th>Sr No</th>
            <th>Invoice No</th>
            <th>Gift Details</th>
            <th>Issued Date</th>
            <th>Expiry Date</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Total Amount</th>
            <th>Issued Count</th>
        </tr>
    </thead>
</table>

<script>
    function getTotalCost(branchCode, fromDate, toDate) {
        $.ajax({
            url: base_path + "ExpenseReport/totalAmount",
            method: "get",
            data: {
                'branchCode': branchCode,
                'fromDate': fromDate,
                'toDate': toDate,
            },
            datatype: "text",
            success: function(data) {
                $("#total").text(data);
            }
        });
    }

    function loadTable(branchCode, fromDate, toDate) {
        if ($.fn.DataTable.isDataTable("#datatableAccountExpense")) {
            $('#datatableAccountExpense').DataTable().clear().destroy();
        }
        jQuery.fn.DataTable.Api.register('buttons.exportData()', function(options) {
            if (this.context.length) {
                var jsonResult = $.ajax({
                    url: base_path + "GiftcardReport/getListRecords",
                    data: {
                        'export': 1,
                        'fromDate': fromDate,
                        'toDate': toDate,
                    },
                    type: "GET",
                    success: function(result) {},
                    async: false
                });
                var jencode = JSON.parse(jsonResult.responseText);
                return {
                    body: jencode.data,
                    header: $("#accountExpense thead tr th").map(function() {
                        return this.innerHTML;
                    }).get()
                };
            }
        });
        var dataTable = $('#datatableAccountExpense').DataTable({
            dom: 'B<"flex-wrap mt-2"fl>trip',
            buttons: [{
                    extend: 'excel',
                    title: 'Account Expense Report'
                },
                {
                    extend: 'pdf',
                    title: 'Account Expense Report'
                }
            ],
            stateSave: false,
            "processing": true,
            "serverSide": true,
            "order": [],
            "searching": true,
            "ajax": {
                url: base_path + "GiftcardReport/getListRecords",
                type: "GET",
                data: {
                    'branchCode': branchCode,
                    'fromDate': fromDate,
                    'toDate': toDate,
                    'export': 0
                },
                "complete": function(response) {
                    $('#total').text((response.responseJSON.totalAmount));
                }
            }
        });
    }

    $(document).ready(function() {
        var fromDate = $("#fromDate").val();
        var toDate = $("#toDate").val();
        loadTable('', fromDate, toDate);
        $(".buttons-html5").removeClass('btn-primary').addClass('btn-primary sub_1');
        $(".dt_buttons").removeClass('flex_wrap');

        $('#btnSearch').on('click', function(e) {
            var branchCode = ""; // $("#branch").val();
            var fromDate = $("#fromDate").val();
            var toDate = $("#toDate").val();
            loadTable(branchCode, fromDate, toDate);
        });

       $('#btnClear').on('click', function(e) {
			$("#frombranch").val('').trigger('change');
            $("#tobranch").val('').trigger('change');
            $("#fromDate").val('<?= date('Y-m-d',strtotime(' - 7 days'))?>')
			$("#toDate").val('<?= date('Y-m-d')?>')
			loadTable("","","<?= date('Y-m-d',strtotime(' - 7 days'))?>","<?= date('Y-m-d')?>","");
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
</script>