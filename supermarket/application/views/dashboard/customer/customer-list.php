<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Customer</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Customer</li>
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
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label lng">Customer</label>
                            <div class="form-group mandatory">
                                <select class="form-select select2" name="customer" id="customer">
                                    <option value="">Select Customer</option>
                                    <?php if ($customer) {
                                        foreach ($customer->result() as $br) {
                                            echo '<option value="' . $br->name . '">' . $br->name . '</option>';
                                        }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label lng">Customer Group</label>
                            <div class="form-group mandatory">
                                <select class="form-select select2" name="customergroup" id="customergroup">
                                    <option value="">Select Customer Group</option>
                                    <?php
                                    if ($groupdata) {
                                        foreach ($groupdata->result() as $Data) {
                                            echo "<option value='" . $Data->code . "'>" . $Data->customerGroupName . "</option>'";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="d-flex text-center justify-content-center mt-4">
                                <button type="button" class="btn btn-success" id="btnSearch">Search</button>
                                <button type="reset" class="btn btn-light-secondary" id="btnClear">Clear</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                            <h5>Customer List</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="print_div">
                    <table class="table table-striped" id="dataTableCustomer" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Customer Group</th>
                                <th>Email</th>
                                <th>Country</th>
                                <th>State</th>
                                <th>City</th>
                                <th>Pincode</th>
                                <th>Phone</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>

        </section>
        <!-- Basic Tables end -->
    </div>
</div>

<div class="modal fade text-left" id="generl_modal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id='modal_label1'>Customer Group</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body2">
                                <form id="groupForm" class="form">
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group row">
                                                <label for="category-name-column" class="col-md-4 form-label text-left">Name</label>
                                                <div class="col-md-8">
                                                    <select class="form-select" name="groupname" id="groupname" required data-parsley-required-message="Custome Groupname is required">
                                                        <option value="">Select</option>
                                                        <?php
                                                        if ($groupdata) {
                                                            foreach ($groupdata->result() as $Data) {
                                                                echo "<option value='" . $Data->code . "'>" . $Data->customerGroupName . "</option>'";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <input type="hidden" name="customerCode" value="" id="customerCode">
                                            <button type="button" class="btn btn-primary white me-2 mb-1 sub_1" id="applyGroup">Apply</button>
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
        getDataTable();
        $('#btnSearch').on('click', function(e) {
            debugger
            var data3 = $("#customer").find('option:selected');
            var customer = data3.val();
            var data1 = $("#customergroup").find('option:selected');
            var customergroup = data1.val();
            getDataTable(customer, customergroup);
        });
        $('#btnClear').on('click', function(e) {
            $("#customer").val('').trigger('change');
            $("#customergroup").val('').trigger('change')
            getDataTable("");
        });
    });

    function getDataTable(customer, customergroup) {
        $.fn.DataTable.ext.errMode = 'none';
        if ($.fn.DataTable.isDataTable("#dataTableCustomer")) {
            $('#dataTableCustomer').DataTable().clear().destroy();
        }
        var dataTable = $('#dataTableCustomer').DataTable({
            stateSave: true,
            lengthMenu: [10, 25, 50, 200, 500, 700, 1000],
            processing: true,
            serverSide: true,
            scrollX: true,
            ordering: true,
            searching: true,
            paging: true,
            ajax: {
                url: base_path + "Customer/getCustomerList",
                data: {
                    customer: customer,
                    customergroup: customergroup
                },
                type: "GET",
                complete: function(response) {
                    $('.apply_group').click(function() {
                        debugger
                        var code = $(this).data('seq');
                        $('#generl_modal1').modal('show');
                        $('#customerCode').val(code);
                    });
                    $('#applyGroup').click(function() {
                        var groupcode = $("#groupname").val();
                        var custcode = $('#customerCode').val();
                        $.ajax({
                            url: base_path + "Customer/updateCustomerGroup",
                            type: 'POST',
                            data: {
                                'groupcode': groupcode,
                                'custcode': custcode
                            },
                            success: function(response) {
                                var obj = JSON.parse(response);
                                if (obj.status == true) {
                                    $('#generl_modal1').modal('hide');
                                    toastr.success(obj.message, 'Customer', {
                                        "progressBar": true
                                    });
                                    getDataTable();
                                } else {
                                    $('#generl_modal1').modal('show');
                                    toastr.error(obj.message, 'Customer', {
                                        "progressBar": true
                                    });
                                }
                            }
                        });
                    });
                }
            }
        });
    }
</script>