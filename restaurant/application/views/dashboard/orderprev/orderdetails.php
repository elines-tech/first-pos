
	<div class="card card-custom gutter-b bg-white border-0">
		<div class="card-body">
			<div class="row invoice-head">
				<div class="row col-md-12">
					<div class="col-md-3 ">
						<div class="text-center1">
							<a href="index.php"><img src="../assets/images/logo/logomain.png" alt="Logo" srcset="" width="120px" height="40px"></a>
						</div>
						<span class="btn btn-outline-info m-r-15 p-10  mt-2" style="cursor: unset;font-size: 14px;padding: 4px 12px;">Billing from </span>
						<div class="text-center1 mt-2">
							<h6 class="mb-2"><strong class="lng"> Dhaka Restaurant</strong> </br></h6>
							<span>98 Green Road, Farmgate, Dhaka-1215. </br></span>
							<span> Print Date: <?= date('d-m-Y h:i A', strtotime($orderData->addDate)) ?> </span>
							<div class="text-center1  mt-2">
								<input type="hidden" class="form-control" id="orderCode" name="orderCode" value="<?= $orderData->code?>">
								<h6 class="mb-2"><strong class="lng">Payment Status: Paid</strong> </h6>
								<h6 class="mb-2"><strong class="lng">Payment Method: Cash</strong> </h6>
							</div>
						</div>
					</div>
					<div class="col-md-6"></div>
					<div class="col-md-3 text-left mb-0 p-0 float-lg-end">
						<div class="text-center1 ">
							<h3 class="mb-2"><strong class="lng">Invoice</strong> </h3>
							<span>Invoice No: 0045<br>
								Order Status: Pending order<br>
								Billing date : <?= date('d-m-Y h:i A', strtotime($orderData->addDate)) ?><br>
								Order : <?= $orderData->code ?></span>
						</div>
						<span class="btn btn-outline-info m-r-15 p-10  mt-2" style="cursor: unset;font-size: 14px;padding: 4px 12px;">Billing to </span>
						<div class="text-center1  mt-2">
							<h6 class="mb-2"><strong class="lng">Ali</strong> </h6>
							<span>Address: kolhapur<br>
								Mobile No.: 675889877898<br></span>

						</div>
					</div>
				</div>
			</div>
			<div class="row mt-4">
				<div class=" table-responsive px-2" id="printableTable" style="overflow-x:hidden !important;">
					<table class="table table-bordered table-hover" id="orderLine">
						<thead>
							<tr>
								<th class="text-center" width="50px">S.No.</th>
								<th class="text-center" width="250px">Product Name</th>
								<th class="text-center" width="150px"> Unit Price</th>
								<th class="text-center" width="100px">Qty</th>
								<th class="text-center" width="150px"> Total Amount</th>
							</tr>
						</thead>
						<tbody id="addPurchaseItem">
						</tbody>
					</table>
					<table class="table table-bordered table-hover">
						<tfoot>
							<tr>
								<td colspan="4" class="text-right"><b>Subtotal :</b></td>
								<td class="text-center"><?= number_format($orderData->subtotal, 2, '.', '') ?></td>
							</tr>
							<tr>
								<td colspan="4" class="text-right"><b>Discount :</b></td>
								<td class="text-center"><?= number_format($orderData->discount, 2, '.', '') ?></td>
							</tr>
							<tr>
								<td colspan="4" class="text-right"><b>Service Charges :</b></td>
								<td class="text-center">0.00</td>
							</tr>
							<tr>
								<td colspan="4" class="text-right"><b>Tax :</b></td>
								<td class="text-center">40.00</td>
							</tr>
							<tr>
								<td colspan="4" class="text-right"><b>Grand Total :</b></td>
								<td class="text-center"><?= number_format($orderData->grandTotal, 2, '.', '') ?></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>

<script>
    $(document).ready(function() {
        loadTable();
	});
    function loadTable() {
        if ($.fn.DataTable.isDataTable("#orderLine")) {
            $('#orderLine').DataTable().clear().destroy();
        }
        var orderCode = $('#orderCode').val();
        var dataTable = $('#orderLine').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "searching": false,
            "ajax": {
                url: base_path + "order/getOrderLineEntries",
                type: "GET",
                data: {
                    'orderCode': orderCode
                },
                "complete": function(response) {

                }
            }
        });
    }
</script>