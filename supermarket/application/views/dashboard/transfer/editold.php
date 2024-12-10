<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Transfer</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><i class="fa fa-dashboard"></i> Dashboard</li>
                            <li class="breadcrumb-item active" aria-current="page">Transfer</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

<div class="container">
    <section id="multiple-column-form" class="mt-5">
         <div class="row match-height">
             <div class="col-12">
                 <div class="card">
                     <div class="card-header">
                         <h3>Edit Transfer<span style="float:right"><a href="<?= base_url()?>transfer/listRecords" class="btn btn-sm btn-primary">Back</a></span></h3>
                     </div>
                     <div class="card-content">
                         <div class="card-body">
                             <form id="transferForm" class="form" data-parsley-validate>
                                 <?php
                                 echo "<div class='text-danger text-center' id='error_message'>";
                                 if (isset($error_message)) {
                                     echo $error_message;
                                 }
                                 echo "</div>";
                                 ?>
                                <?php if ($inwardData) {
                                    $result = $inwardData->result_array()[0];
                                ?>
                                    <div class="form-group row">
                                        <div class="col-md-3 col-sm-12 col-xs-12 mb-3">
                                            <label class="form-label lng">Date<i class="text-danger">*</i></label>
                                            <input type="date" class="form-control bg-white" name="transferDate" id="transferDate" disabled value="<?= date('Y-m-d', strtotime($result['inwardDate'])) ?>">
                                        </div>
                                        <div class="col-md-3 col-sm-12 col-xs-12 mb-3">
                                            <label class="form-label lng">From Branch</label>
                                            <div class="form-group mb-3 d-flex">
                                                <div class="col-md-10 col-sm-12 col-xs-12">
                                                    <input type="hidden" class="form-control" id="transferCode" name="transferCode" value="<?= $result['code'] ?>">
                                                    <input type="hidden" class="form-control" id="fromBranch" name="fromBranch" value="<?= $result['branchCode'] ?>">
                                                    <input type="text" class="form-control" id="fromBranchName" disabled name="fromBranchName" value="<?= $result['fromBranchName'] ?>" readonly>

                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12 col-xs-12 mb-3">
                                            <label class="form-label lng">To Branch</label>
                                            <div class="form-group mb-3 d-flex">
                                                <div class="col-md-10 col-sm-12 col-xs-12">
                                                    <input type="hidden" class="form-control" id="toBranch" name="toBranch" value="<?= $result['supplierCode'] ?>">
                                                    <input type="text" class="form-control" id="toBranchName" disabled name="toBranchName" value="<?= $result['toBranchName'] ?>" readonly>
												</div>
												<a href="<?php echo base_url(); ?>Branch/listRecords" class="col-md-2 col-sm-12 col-xs-12 circleico btn btn-primary a_plus text-light d-flex align-items-center justify-content-center rounded-circle shadow-sm " style="">
													<i class="fa fa-plus"></i>
												</a>
											</div>
										</div>
									</div>
									<table class="table table-hover" id="transferTable">
										<thead>
											<tr>
                                                <th style="width:25%">Batch No<i class="text-danger">*</i></th>
												<th style="width:25%">Product<i class="text-danger">*</i></th>         
												<th style="width:12%">Qty<i class="text-danger">*</i></th>
												<th style="width:12%">Price</th>
												<th style="width:12%">UOM</th>
												<th style="width:12%">Sub Total</th>
												<th style="width:2%">Action</th> 
											</tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 0;
                                            if ($inwardLineEntries) {
                                                foreach ($inwardLineEntries->result_array() as $co) {
                                                    $i++;
                                            ?>
                                            <tr id="row<?= $i ?>">
												<td class="span3 supplier">
                                                    <input type="hidden" class="form-control" name="transferLineCode<?= $i ?>" id="transferLineCode<?= $i ?>" value="<?= $co['code'] ?>">
                                                    <select class="form-select select2" id="batchCode<?= $i ?>" name="batchCode<?= $i ?>" onchange="checkDuplicateItem(<?= $i ?>);getItem(<?= $i?>);" style="width:100%">
                                                        <option value="">Select</option>
                                                        <?php
                                                        if ($branches) {
                                                            foreach ($branches->result() as $it) {
																echo $co['fromBatchNo'];
                                                                if ($it->batchNo == $co['fromBatchNo']) {
                                                                    echo "<option value='" . $it->batchNo . "' selected>" . $it->batchNo . "</option>";
                                                                } else {
                                                                    echo "<option value='" . $it->batchNo . "'>" . $it->batchNo . "</option>";
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td class="span3 supplier">
                                                    <input type="hidden" class="form-control" name="transferLineCode<?= $i ?>" id="transferLineCode<?= $i ?>" value="<?= $co['code'] ?>">
                                                    <select class="form-select select2" id="itemCode<?= $i ?>" name="itemCode<?= $i ?>" onchange="checkDuplicateItem(<?= $i ?>);getItemStorageUnit(<?= $i ?>);calculate_subTotal(<?= $i ?>);">
                                                      <?php
													  echo $prdArr[$i-1];
													  ?>
													</select>
												</td>
												<td class="text-right">
													<input type="text" class="form-control" name="itemQty<?= $i ?>" id="itemQty<?= $i ?>" value="<?= $co['productQty'] ?>" onchange="checkQty(<?= $i ?>);" onkeypress="return isNumber(event)" onkeyup="calculate_subTotal(<?= $i ?>)">
												</td>
			
												<td class="text-right">
													<input type="text" class="form-control" name="itemPrice<?= $i ?>" id="itemPrice<?= $i ?>" value="<?= $co['productPrice'] ?>" disabled onchange="checkPrice(<?= $i ?>);" onkeypress="return isNumber(event)" onkeyup="calculate_subTotal(<?= $i ?>)">
												</td>
												
												<td class="text-right">
													<input type="text" class="form-control" name="itemUnit<?= $i ?>" id="itemUnit<?= $i ?>" disabled value="<?= $co['unitName'] ?>">
													<input type="hidden" class="form-control" name="dbitemUnit<?= $i ?>" id="dbitemUnit<?= $i ?>" value="<?= $co['productUnit'] ?>">
												</td>
												<td class="test">
													<input type="text" class="form-control subtotal" name="subTotal<?= $i ?>" id="subTotal<?= $i ?>" disabled value="<?= $co['subTotal'] ?>">
												</td>
                                                <td>
                                                    <a href="#" class="btn btn-danger" onclick="delete_row(<?= $i ?>,'<?= $co['code'] ?>')"><i class="fa fa-trash"></i>
                                                </td>
                                            </tr>
										<?php
											}
										} ?>
										<tr id="row0">
											<td>
												<select class="form-select select2" style="width:100%;"id="batchCode0" name="batchCode0" onchange="checkDuplicateItem(0);getItem(0);">
												</select>											
											</td>
											<td class="span3 supplier">
												<input type="hidden" class="form-control" name="transferLineCode0" id="transferLineCode0">
												<select class="form-select" id="itemCode0" name="itemCode0" onchange="checkDuplicateItem(0);getItemStorageUnit(0);calculate_subTotal(0)">
													<option value="">Select Product</option>
													<?php
													if ($items) {
														foreach ($items->result() as $it) {
															echo "<option value='" . $it->code . "' data-stock='" . $it->stock . "' data-price='" . $it->price . "'>" . $it->itemEngName . "</option>";
														}
													}
													?>
												</select>
                                            </td>
											<td class="text-right">
												<input type="text" class="form-control" name="itemQty0" onchange="checkQty(0);" id="itemQty0" onkeypress="return isNumber(event)" onkeyup="calculate_subTotal(0)">
											</td>
											<td class="text-right">
												<input type="text" class="form-control" name="itemPrice0" onchange="checkPrice(0);" id="itemPrice0" disabled onkeypress="return isDecimal(event)" onkeyup="calculate_subTotal(0)">
											</td>
											
											<td class="text-right">
												<input type="text" class="form-control" name="itemUnit0" id="itemUnit0" disabled>
												<input type="hidden" class="form-control" name="dbitemUnit0" id="dbitemUnit0" value="">
											</td>
                                            <td class="test">
                                                <input type="text" class="form-control subtotal" name="subTotal0" id="subTotal0" disabled>
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-success" onclick="add_row()"><i class="fa fa-plus"></i>
                                            </td>
                                        </tr>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="5" class="text-right"><b>Total :</b></td>
											<td class="text-right">
												<input type="text" id="total" class="text-right form-control" name="total" value="<?= $result['total'] ?>" readonly autocomplete="off">
											</td>
										</tr>
									</tfoot>
								</table>
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-end">
										<button type="submit" class="btn btn-primary white me-2 mb-1 sub_1 submitBtn" id="approveTransferBtn" value="1">Save & Approve</button>
                                        <button type="submit" class="btn btn-success white me-2 mb-1 sub_1 submitBtn" id="saveTransferBtn" value="2">Save</button>
   
                                        <button type="reset" class="btn btn-light-secondary me-1 mb-1" id="cancelTransferBtn">Reset</button>
                                    </div>
                                </div>
                            <?php }else{
								echo '<div class="text-center"><h5>No data found</h5></div>';
							}
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
       </div>
   </section>
                   </div>
			</div>
			</div>
         <script>
                        $(document).ready(function() {
							getBranchBatches();
                            $('.cancel').removeClass('btn-default').addClass('btn-info');
							
                            var today = new Date().toISOString().split('T')[0];
                            document.getElementsByName("transferDate")[0].setAttribute('max', today);
                             $(".submitBtn").click(function (e) {
            e.preventDefault();
            
            var approve = $(this).val();
			var btnId=$(this).attr('id')
            var formData = new FormData($('#transferForm')[0]);
            var form = $('#transferForm');
            form.parsley().validate();
            if (form.parsley().isValid()) {
				var fbranchCode = $('#fromBranch').val();
                var tbranchCode = $('#toBranch').val();
                formData.append('total', $('#total').val())
                formData.append('approve', approve)
                formData.append('fromBranch', fbranchCode)
               
                var table = document.getElementById("transferTable");
                var table_len = (table.rows.length) - 2;
                var tr = table.getElementsByTagName("tr");
                if (table_len == 1 && ($('#itemCode0').val() == '' || $('#itemQty0').val() == '' || $('#itemUnit0').val() == '' || $('#itemPrice0').val() == '' || $('#subTotal0').val() == '')) {
                    toastr.error('Please provide at least one entry', 'Inward', {
                        "progressBar": true
                    });
                } else {
                    $.ajax({
                        type: "POST",
                        url: base_path + "transfer/saveTransfer",
                        enctype: 'multipart/form-data',
                        contentType: false,
                        processData: false,
                        data: formData,
                        beforeSend: function() {
                            $('#'+btnId).prop('disabled', true);
                            $('#cancelTransferBtn').prop('disabled', true);
                            $('#'+btnId).text('Please wait..');
                        },
                        success: function(data) {
                            $('#'+btnId).text('Save');
                            $('#'+btnId).prop('disabled', false);
                            $('#cancelTransferBtn').prop('disabled', false);
                            var obj = JSON.parse(data);
                            if (obj.status) {
                                var ajax_complete = 0;
                                var num = 0;
                                for (i = 1; i <= table_len; i++) {
                                    var id = tr[i].id.substring(3);
                                    document.getElementById("transferCode").value = obj.transferCode;
                                    var fbranchCode = $('#fromBranch').val();
                                    var tbranchCode = $('#toBranch').val();
                                    var transferCode = document.getElementById("transferCode").value;
                                    var transferLineCode = document.getElementById("transferLineCode" + id).value;
                                    var batchNo = document.getElementById("batchCode" + id).value;
                                    var itemCode = document.getElementById("itemCode" + id).value;
                                    var itemUnit = document.getElementById("itemUnit" + id).value;
                                    var dbitemUnit = document.getElementById("dbitemUnit" + id).value;
                                    var itemQty = document.getElementById("itemQty" + id).value;
                                    var itemPrice = document.getElementById("itemPrice" + id).value;
                                    var subTotal = document.getElementById("subTotal" + id).value;
									var variantCode=$("#itemCode" +id).find('option:selected').attr('id');
									var productCode=$("#itemCode" +id).find('option:selected').attr('data-val');
									var sku=$("#itemCode" +id).find('option:selected').data('inw-sku');
									var tax=$("#itemCode" +id).find('option:selected').data('tax');
									var expirydate=$("#itemCode" +id).find('option:selected').data('expirydate');
                                    if (itemCode != '' && itemUnit != '' && itemQty != '' && itemPrice != '' && subTotal != '') {
                                        num++;
                                        $.ajax({
                                            type: 'post',
                                            url: base_path + "transfer/saveTransferLine",
                                            data: {
                                                transferCode: transferCode,
												batchNo:obj.batchNo,
												fromBatchNo:batchNo,
                                                transferLineCode: transferLineCode,
                                                itemCode: itemCode,
                                                itemUnit: dbitemUnit,
                                                itemQty: itemQty,
                                                itemPrice: itemPrice,
                                                subTotal: subTotal,
                                                fromBranch: fbranchCode,
                                                toBranch: tbranchCode,
												variantCode:variantCode,
												productCode:productCode,
												sku:sku,
												expirydate:expirydate,
												tax:tax,
												approve:approve,
                                            },
                                            success: function(response) {
                                                ajax_complete++;
                                                if (num == ajax_complete) {
                                                    toastr.success(obj.message, 'Transfer added', {
                                                        "progressBar": true
                                                    });

                                                    location.href = base_path + "Transfer/listRecords"
                                                } else {}
                                            }
                                        });
                                    }
                                }
                            } else {
                                toastr.error(obj.message, 'Transfer', {
                                    "progressBar": true
                                });
                            }
                        }
                    });
                }
            }
        });


                            $("body").on("change", "#toBranch", function(e) {
                                debugger
                                e.preventDefault();
                                var toBranch = $("#toBranch").val();
                                var fromBranch = $('#fromBranch').val();
                                if (toBranch == fromBranch) {
                                    toastr.error("Select different branch", 'Branch', {
                                        "progressBar": true
                                    });
                                    return false;
                                    //$("#toBranch").val('');
                                    //$('#fromBranch').val('');
                                }

                            });
                        });
function getItem(id) {
		var  batchCode  = $('#batchCode' + id).val();
		if(batchCode!='' && batchCode!=undefined){
        $.ajax({
            url: base_path + 'transfer/getItems',
            data: {
                'batchCode': batchCode
            },
            type: 'post',
            success: function(response) {
                var res = JSON.parse(response);
                if (res.status) {
                    //$('select#itemCode'+id).attr('disabled', false);
                    $('select#itemCode'+id).empty();
                    $('select#itemCode'+id).html(res.items);
                } else {
                    $('#batchCode' + id).val('');
                   // $('select#itemCode' +id).attr('disabled', true);
                    toastr.error("No items are available in the selected batch. Please choose another.", 'Invalid Batch', {"progressBar": true});
				}
            }
		});
		}
	}

                        function checkDuplicateItem(id) {
                            var table = document.getElementById("transferTable");
                            var table_len = (table.rows.length) - 2;
                            var tr = table.getElementsByTagName("tr");
                            var itemCode = document.getElementById("itemCode" + id).value.toLowerCase();
                            if (itemCode != "") {
                                for (i = 1; i <= table_len; i++) {
                                    var row_id = tr[i].id.substring(3);
                                    var itemCode_row = document.getElementById("itemCode" + row_id).value.toLowerCase();
                                    if (itemCode_row == itemCode && row_id != id) {
                                        toastr.error('Item already exists', 'Duplicate inward item', {
                                            "progressBar": true
                                        });
                                        document.getElementById("itemCode" + id).value = "";
                                        document.getElementById("itemUnit" + id).value = "";
                                        document.getElementById("itemCode" + id).focus();
                                        return 1;
                                    }
                                }
                            }
                        }

                        function isNumber(evt) {
                            evt = (evt) ? evt : window.event;
                            var charCode = (evt.which) ? evt.which : evt.keyCode;
                            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                                return false;
                            }
                            return true;
                        }


                        function isDecimal(evt) {
                            evt = (evt) ? evt : window.event;
                            var charCode = (evt.which) ? evt.which : evt.keyCode;
                            if ((charCode >= 48 && charCode <= 57) || (charCode >= 96 && charCode <= 105) || charCode == 8 || charCode == 9 || charCode == 37 ||
                                charCode == 39 || charCode == 46 || charCode == 190) {
                                return true;
                            } else {
                                return false;
                            }
                        }

                       function checkPrice(id) {
        var itemPrice = $('#itemPrice' + id).val();
        if (itemPrice != '' && itemPrice == 0) {
            $('#itemPrice' + id).val('');
            toastr.error('Item price should be greater than 0', 'Transfer Products', {
                "progressBar": false
            });
            return false;
        }
    }

    function checkQty(id) {
        var itemQty = $('#itemQty' + id).val();
        if (itemQty != '' && itemQty == 0) {
            $('#itemQty' + id).val('');
            toastr.error('Item Quantity should be greater than 0', 'Transfer Products', {
                "progressBar": false
            });
            return false;
        }

    }
	function getBranchBatches() {
		var branchCode = $('#fromBranch').val();
		if (branchCode != '' && branchCode!=undefined) {
			$.ajax({
				url: base_path + "transfer/getBranchBatches",
				type: 'POST',
				data: {
					'branchCode': branchCode
				},
				success: function(response) {
					var obj = JSON.parse(response);
					if(obj.status){
						$('#batchCode0').html(obj.batchHtml);
						
					}else{
						$("#batchCode0").val(null).trigger('change.select2');
						$("#batchCode0").html('');
					}
				}
			})
		}
	}

                        function calculate_subTotal(id) {
                            var itemQty = Number($('#itemQty' + id).val());
                            var availableStock = $('#itemCode' + id).find(':selected').data('stock');
                            if (availableStock > 0 && itemQty > 0 && availableStock < itemQty) {
                                toastr.error('Stock not present', 'Transfer Products', {
                                    "progressBar": false
                                });
                                return;
                                $('#itemQty' + id).val('');
                            } else {
                                var itemPrice = Number($('#itemPrice' + id).val())
                                subTotal = itemQty * itemPrice;
                                $('input#subTotal' + id).val(subTotal);
                                calculateTotal();
                            }
                        }

                        function calculateTotal() {
                            var table = document.getElementById("transferTable");
                            var table_len = (table.rows.length) - 2;
                            var tr = table.getElementsByTagName("tr");
                            var total = 0;
                            for (i = 1; i <= table_len; i++) {
                                var id = tr[i].id.substring(3);
                                total = Number(total) + Number($('#subTotal' + id).val());
                            }
                            $('input#total').val(total);
                        }

                        

    function getItemStorageUnit(id) { 
	    var itemCode = $('#itemCode' + id).find(':selected').data('val');
		var itemPrice = $('#itemCode' + id).find(':selected').data('price');
		$('#itemPrice' + id).val(itemPrice);
        if (itemCode != '' && itemCode!=undefined) {
            $.ajax({
                url: base_path + "transfer/getItemStorageUnit",
                type: 'POST',
                data: {
                    'itemCode': itemCode
                },
                success: function(response) {
					var obj=JSON.parse(response);
                    if (obj.status) {
                        $('#itemUnit' + id).val(obj.unitText)
                        $('#dbitemUnit' + id).val(obj.unitCode)
                    } else {
                        $("#itemUnit" + id).val('')
                        $("#dbitemUnit" + id).val('')
                    }
                }
            })
        }
    }

    function add_row() {
        var table = document.getElementById("transferTable");
        var table_len = (table.rows.length) - 2;
        var tr = table.getElementsByTagName("tr");
        var id = 0;
        var transferLineCode = document.getElementById("transferLineCode0").value;
        var itemCode = document.getElementById("itemCode0").value;
		var batchCode = document.getElementById("batchCode0").value;
        var itemUnit = document.getElementById("itemUnit0").value;
        var dbitemUnit = document.getElementById("dbitemUnit0").value;
        var itemQty = document.getElementById("itemQty0").value;
        var itemPrice = document.getElementById("itemPrice0").value;
        var subTotal = document.getElementById("subTotal0").value;
        if (itemCode != '' && itemUnit != '' && itemPrice != '' && itemQty != '' && subTotal != '') {} else {
            toastr.error('Please provide all the details', 'Transfer Products', {
                "progressBar": false
            });
            return;
        }

        for (i = 1; i < table_len; i++) {
            var n = tr[i].id.substring(3);
            var id = n;
        }
        id++;
        var row = table.insertRow(table_len).outerHTML = '<tr id="row' + id + '" class="newitem">' +
            '<td><select class="form-select select2" name="batchCode' + id + '" id="batchCode' + id + '" >' +
            document.getElementById("batchCode0").innerHTML +
            '</select>' +
            '</td>' +
			'<td><select class="form-select select2" name="itemCode' + id + '" id="itemCode' + id + '" >' +
            document.getElementById("itemCode0").innerHTML +
            '</select>' +
            '<input type="hidden" class="form-control" name="transferLineCode' + id + '" id="transferLineCode' + id + '" value="' + transferLineCode + '"></td>' +
            '<td><input type="text" class="form-control" name="itemQty' + id + '" id="itemQty' + id + '" value="' + itemQty + '" onkeyup="calculate_subTotal(' + id + ')" onchange="checkQty(' + id + ');" onkeypress="return isNumber(event)"></td>' +           
		    '<td><input type="text" class="form-control" name="itemPrice' + id + '" id="itemPrice' + id + '" value="' + itemPrice + '" onkeyup="calculate_subTotal(' + id + ')" disabled onchange="checkPrice(' + id + ');" onkeypress="return isDecimal(event)"></td>' +           
			'<td><input type="text" class="form-control" name="itemUnit' + id + '" id="itemUnit' + id + '" disabled value="'+itemUnit+'"><input type="hidden" class="form-control" name="dbitemUnit' + id + '" id="dbitemUnit' + id + '" disabled value="'+itemUnitCode+'"></td>' +
            '<td><input type="text" class="form-control" name="subTotal' + id + '" id="subTotal' + id + '" value="' + subTotal + '" disabled></td>' +
            '<td><a href="#" class="btn btn-danger" onclick="delete_row(' + id + ')"><i class="fa fa-trash"></i></a>' +
            '</td>' +
            '</tr>';
			
        document.getElementById("itemCode" + id).value = itemCode;
		document.getElementById("batchCode" + id).value = batchCode
        //document.getElementById("itemCode0").value = "";
        $("#itemCode0").val('').trigger('change');
		$("#batchCode0").val('').trigger('change');
		 $('.select2').select2({
			tags: true,
			width: '100%'
		  });
        document.getElementById("itemQty0").value = "";
        document.getElementById("itemUnit0").value = "";
		//document.getElementById("batchCode0").value = "";
        document.getElementById("itemPrice0").value = "";
        document.getElementById("subTotal0").value = "";
        document.getElementById("itemCode0").focus();
        if (table_len > 2) {
            $('#fromBranch').attr('disabled', true);
        }
    }

                        function delete_row(id, lineCode) {
                            swal({
                                title: "Are you sure?",
                                text: "You want to delete this product ",
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
                                        url: base_path + "Transfer/deleteTransferLine",
                                        type: 'POST',
                                        data: {
                                            'lineCode': lineCode
                                        },
                                        dataType: "JSON",
                                        success: function(response) {
                                            if (response) {
                                                swal({
                                                        title: "Completed",
                                                        text: "Successfully Deleted",
                                                        type: "success"
                                                    },
                                                    function(isConfirm) {
                                                        if (isConfirm) {
															swal.close();
                                                            var row = document.getElementById("row" + id);
                                                            row.parentNode.removeChild(row);
                                                            var sum = 0;
                                                            $('.subtotal').each(function() {
                                                                sum += Number($(this).val());
                                                            });
                                                            $("input#total").val(sum);
                                                        }
                                                    });
                                            } else {
												swal.close();
                                                toastr.success('Record Not Deleted', 'Failed', {
                                                    "progressBar": true
                                                });
                                            }
                                        }
                                    });
                                }else{
									swal.close();
								}
                            });
                        }
                    </script>