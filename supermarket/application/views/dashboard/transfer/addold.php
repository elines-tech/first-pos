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
                            <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
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
                        <h3>Add Transfer<span style="float:right"><a href="<?= base_url()?>transfer/listRecords" class="btn btn-sm btn-primary">Back</a></span></h3>
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
                                <div class="form-group row">
                                    <div class="col-md-3 col-sm-12 col-xs-12 mb-3">
                                        <div class="form-group mandatory">
                                            <label class="form-label">Date</label>
                                            <input type="date" class="form-control bg-white" name="transferDate" id="transferDate" value="<?= date('Y-m-d') ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xs-12 mb-3">
                                        <div class="form-group mandatory">
                                            <label class="form-label">From Branch</label>
                                            <div class="col-md-10 col-sm-12 col-xs-12">
                                                <input type="hidden" class="form-control" id="transferCode" name="transferCode">
                                                <select class="form-select select2" name="fromBranch" id="fromBranch" data-parsley-required="true" style="width:100%" required onchange="getBranchBatches()">
                                                   
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xs-12 mb-3">
                                        <div class="form-group mandatory">
                                            <label class="form-label">To Branch</label>
                                            <div class="col-md-10 col-sm-12 col-xs-12">
                                                <select class="form-select select2" name="toBranch" id="toBranch" data-parsley-required="true" required>
                                                   
                                                </select>
                                            </div>

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
                                        <tr id="row0">
										    <td>
											   <select class="form-select select2" id="batchCode0" name="batchCode[]" onchange="checkDuplicateItem(0);getItem(0);">
                                                    
                                                </select>											
											</td>
                                            <td class="span3 supplier">
                                                <input type="hidden" class="form-control" name="transferLineCode[]" id="transferLineCode0">
                                                <select class="form-select select2" id="itemCode0" name="itemCode[]" onchange="getItemStorageUnit(0);calculate_subTotal(0)">
                                                   
                                                </select>
                                            </td>
											<td class="text-right">
                                                <input type="text" class="form-control" name="itemQty0" id="itemQty[]" onchange="checkQty(0);" onkeypress="return isNumber(event)" onkeyup="calculate_subTotal(0)">
                                            </td>
                                            <td class="text-right">
                                                <input type="text" class="form-control" name="itemPrice0" id="itemPrice[]" onchange="checkPrice(0);"  disabled onkeypress="return isDecimal(event)" onkeyup="calculate_subTotal(0)">

                                            </td> 
                                          
                                            <td class="text-right">
                                                <input type="text" class="form-control" name="itemUnit[]" id="itemUnit0" disabled>
                                                <input type="hidden" class="form-control" name="dbitemUnit[]" id="dbitemUnit0" value="">
                                            </td>
                                            <td class="test">
                                                <input type="text" class="form-control" name="subTotal[]" id="subTotal0" disabled>
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-success" onclick="add_row()"><i class="fa fa-plus"></i>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5" class="text-right"><b>Total :</b></td>
                                            <td colspan="6">
                                                <input type="text" id="total" class="text-right form-control" name="total" value="0.00" readonly="readonly" autocomplete="off">
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-success white me-2 mb-1 sub_1" id="saveTransferBtn">Save</button>
                                        <button type="reset" class="btn btn-light-secondary me-1 mb-1" id="cancelTransferBtn">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- // Basic multiple Column Form section end -->
</div>
</div>
</div>
<script>
    $(document).ready(function() {
		$("#fromBranch").select2({
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
		$("#toBranch").select2({
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
        $('.cancel').removeClass('btn-default').addClass('btn-info');
        var today = new Date().toISOString().split('T')[0];
        document.getElementsByName("transferDate")[0].setAttribute('max', today);
        $("#transferForm").on("submit", function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var form = $(this);
            form.parsley().validate();
            if (form.parsley().isValid()) {
				 var fbranchCode = $('#fromBranch').val();
                 var tbranchCode = $('#toBranch').val();
                formData.append('total', $('#total').val())
                formData.append('approve','0')
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
                            $('#saveTransferBtn').prop('disabled', true);
                            $('#cancelTransferBtn').prop('disabled', true);
                            $('#saveTransferBtn').text('Please wait..');
                        },
                        success: function(data) {
                            $('#saveTransferBtn').text('Save');
                            $('#saveTransferBtn').prop('disabled', false);
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
												approve:0,
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
            e.preventDefault();
            var toBranch = $("#toBranch").val();
            var fromBranch = $('#fromBranch').val();
            if (toBranch == fromBranch) {
                $("#toBranch").val('');
                $('#fromBranch').val('');
                toastr.error("From branch and To branch should not be same", 'Transfer', {
                    "progressBar": true
                });
                return false;

            }
        });
        var branchCode = "";
        $("body").on("change", "#fromBranch", function(e) {
            e.preventDefault();
            var toBranch = $("#toBranch").val();
            var fromBranch = $('#fromBranch').val();
            if (toBranch == fromBranch) {
                $("#toBranch").val('');
                $('#fromBranch').val('');
                toastr.error("From branch and To branch should not be same", 'Transfer', {
                    "progressBar": true
                });
                return false;
            }
        });
    });


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

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
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
        var itemQty = Number($('#itemQty' + id).val()).toFixed(2);
        var itemPrice = Number($('#itemPrice' + id).val()).toFixed(2);
        var availableStock = Number($('#itemCode' + id).find(':selected').data('stock')).toFixed(2);
        if (availableStock > 0 && itemQty > 0 && availableStock < itemQty) {
            $('#itemQty' + id).val('');
			calculateTotal();
            toastr.error('Stock not present', 'Transfer Products', {
                "progressBar": false
            });
            return false;
			
        } else {
            subTotal = itemQty * itemPrice;
            $('input#subTotal' + id).val(subTotal.toFixed(2));
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
        $('input#total').val(total.toFixed(2));
    }
	
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
					   $('select#itemCode'+id).prop('disabled', false);
					   $('select#itemCode'+id).select2();
						$('select#itemCode'+id).html('');
						$('select#itemCode'+id).val('').trigger('change.select2');
						$('select#itemCode'+id).html(res.items);
					} else {
						$('#batchCode' + id).val('');
						$('select#itemCode'+id).val('').trigger('change.select2');
					   $('select#itemCode' +id).prop('disabled', true);
						toastr.error("No items are available in the selected batch. Please choose another.", 'Invalid Batch', {"progressBar": true});
					}
				}
			});
		}
	}

    function getItemStorageUnit(id) {
		debugger
	    var itemCode = $('#itemCode' + id).find(':selected').data('val');
		var itemPrice = $('#itemCode' + id).find(':selected').data('price');
        if (itemCode != '' && itemCode!=undefined) {
			$('#itemPrice' + id).val(itemPrice);
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
            '<td><select class="form-select select2" name="batchCode' + id + '" id="batchCode' + id + '" onchange="checkDuplicateItem('+id+');getItem('+id+');" >' +
            document.getElementById("batchCode0").innerHTML +
            '</select>' +
            '</td>' +
			'<td><select class="form-select select2" name="itemCode' + id + '" id="itemCode' + id + '" onchange="getItemStorageUnit('+id+');calculate_subTotal('+id+')">' +
            document.getElementById("itemCode0").innerHTML +
            '</select>' +
            '<input type="hidden" class="form-control" name="transferLineCode' + id + '" id="transferLineCode' + id + '" value="' + transferLineCode + '"></td>' +
            '<td><input type="text" class="form-control" name="itemQty' + id + '" id="itemQty' + id + '" value="' + itemQty + '" onkeyup="calculate_subTotal(' + id + ')" onchange="checkQty(' + id + ');" onkeypress="return isNumber(event)"></td>' +           
		    '<td><input type="text" class="form-control" name="itemPrice' + id + '" id="itemPrice' + id + '" value="' + itemPrice + '" onkeyup="calculate_subTotal(' + id + ')" disabled onchange="checkPrice(' + id + ');" onkeypress="return isDecimal(event)"></td>' +           
			'<td><input type="text" class="form-control" name="itemUnit' + id + '" id="itemUnit' + id + '" disabled value="'+itemUnit+'"><input type="hidden" class="form-control" name="dbitemUnit' + id + '" id="dbitemUnit' + id + '" disabled value="'+dbitemUnit+'"></td>' +
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
        document.getElementById("itemCode0").innerHTML = "";
        document.getElementById("itemCode0").disabled = true;
        document.getElementById("subTotal0").value = "";
        document.getElementById("batchCode0").focus();
        if (table_len > 2) {
            $('#fromBranch').attr('disabled', true);
        }
    }

    function delete_row(id) {
        var table = document.getElementById("transferTable");
        var table_len = (table.rows.length) - 2;
        var tr = table.getElementsByTagName("tr");
        swal({
            title: "Are you sure?",
            text: "You want to delete this item ",
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel it!",
            closeOnConfirm: !1,
            closeOnCancel: !1
        }, function(e) {
            if (e) {
                swal.close();
                var row = document.getElementById("row" + id);
                row.parentNode.removeChild(row);
                if (table_len == 2) {
                    $('#fromBranch').attr('disabled', false);
                }
            } else {
                swal.close();
            }
        });
    }
</script>