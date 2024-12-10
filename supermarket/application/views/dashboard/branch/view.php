<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Branch</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Branch</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row mb-2 float-right">
                <div class="col-12 col-md-12">
                    <a href="<?= base_url() ?>branch/listRecords" class="btn btn-primary text-center">Back</a>
                    <?php if($updateRights==1){ ?>
					<button class="btn btn-primary text-center edit_branch">Edit Branch</button>
                    <?php } ?>
				</div>
            </div>
        </div>
        <div class="row">
            <section class="section col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                                <h5>Branch</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        if ($branch) {
                            foreach ($branch->result() as $br) {
                        ?>
                                <div class="row mb-1">
                                    <form class="form">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="branchName">Branch Name</label>
                                                <input type="hidden" class="form-control-line" name="branchCode" id="branchCode" value="<?= $br->code ?>">
                                                <input type="text" class="form-control-line" id="branchName" placeholder="Branch Name" value="<?= $br->branchName ?>" name="branchName" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="taxGroup">Tax Group</label>
                                                <input type="text" class="form-control-line" id="taxGroup" value="<?= $br->taxGroupName ?>" name="" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <label for="openingFrom">Opening From</label>
                                                <input type="text" class="form-control-line" id="openingFrom" value="<?= $br->openingFrom ?>" name="" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="openingTo">Opening To</label>
                                                <input type="text" class="form-control-line" id="openingTo" value="<?= $br->openingTo ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <label for="openingFrom">Tax Reg Name</label>
                                                <input type="text" class="form-control-line" id="taxRegName" value="<?= $br->branchTaxRegName ?>" name="modalbranchTaxRegName" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="openingTo">Tax Reg No</label>
                                                <input type="text" class="form-control-line" id="taxRegNo" value="<?= $br->branchTaxRegNo ?>" name="branchTaxRegNo" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <label for="openingFrom">Phone</label>
                                                <input type="text" class="form-control-line" id="phone" value="<?= $br->branchPhoneNo ?>" name="branchPhoneNo" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="openingTo">Address</label>
                                                <input type="text" class="form-control-line" id="address" value="<?= $br->branchAddress ?>" name="branchAddress" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <label for="openingFrom">Latitude</label>
                                                <input type="text" class="form-control-line" id="latitude" placeholder="Latitude" value="<?= $br->branchLat ?>" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="openingTo">Longitude</label>
                                                <input type="text" class="form-control-line" id="longitude" placeholder="Longitude" value="<?= $br->branchLong ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <label for="openingFrom">Receipt Header</label>
                                                <input type="text" class="form-control-line" id="receiptHeader" value="<?= $br->receiptHead ?>" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="openingTo">Receipt Footer</label>
                                                <input type="text" class="form-control-line" id="receiptFooter" value="<?= $br->receiptFoot ?>" readonly>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                        <?php }
                        } ?>
                    </div>
                </div>
            </section>
            <section class="section col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                                <h5>Users</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table" id="datatableUsers">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Employee Number</th>
                                    <th>Phone</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </section>
        </div>
        <!-- Basic Tables end -->
    </div>
</div>
</div>
</div>
</body>
<div class="modal fade text-left" id="generl_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Update Branch</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body1">

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
        var branchCode = $('#branchCode').val();
        // getBranchDetails();
        //debugger
        loadUserTable(branchCode);
    });


    $('.edit_branch').click(function() {
        var branchCode = $('#branchCode').val();
        $.ajax({
            url: base_path + "branch/edit",
            type: 'POST',
            data: {
                'code': branchCode,
            },
            success: function(response) {
                $('#generl_modal').modal('show');
                $(".panel-body1").html(response);
            }
        });
    });

    function loadUserTable(branchCode) {
        if ($.fn.DataTable.isDataTable("#datatableUsers")) {
            $('#datatableUsers').DataTable().clear().destroy();
        }
        var dataTable = $('#datatableUsers').DataTable({
            stateSave: true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "searching": true,
            "ajax": {
                url: base_path + "branch/getUserList",
                type: "GET",
                data: {
                    'branchCode': branchCode,
                },
                "complete": function(response) {}
            }
        });
    }

    $(document).on("click", "button#updateBranchBtn", function(e) {
        $('#updateBranchForm').parsley();
        const form = document.getElementById('updateBranchForm');
        var formData = new FormData(form);
        var isValid = true;
        e.preventDefault();
        $("#updateBranchForm .form-control").each(function(e) {
            if ($(this).parsley().validate() !== true) isValid = false;
        });
        $("#updateBranchForm .form-select").each(function(e) {
            if ($(this).parsley().validate() !== true) isValid = false;
        });
        if (isValid) {
            var isActive = 0;
            var openingFrom = $('#modalopeningFrom').val();
            var openingTo = $('#modalopeningTo').val();
            if (openingFrom != '' && openingTo != '' && openingTo <= openingFrom) {
                toastr.error('Opening to time should be greater than from time', 'Branch', {
                    "progressBar": true
                });
                $('#modalopeningTo').focus();
                return false;
            }
            if ($("#modalisActive").is(':checked')) {
                isActive = 1;
            }
            formData.append('modalisActive', isActive);
            $.ajax({
                url: base_path + "branch/updateBranch",
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#updateBranchBtn').prop('disabled', true);
                    $('#updateBranchBtn').text('Please wait..');
                    $('#closeBranchBtn').prop('disabled', true);
                },
                success: function(response) {
                    $('#updateBranchBtn').prop('disabled', false);
                    $('#updateBranchBtn').text('Update');
                    $('#closeBranchBtn').prop('disabled', false);
                    var obj = JSON.parse(response);
                    if (obj.status) {
                        toastr.success(obj.message, 'Branch', {
                            "progressBar": true
                        });
                        location.reload();
                        $('#generl_modal').modal('hide');
                        $(".panel-body").html('');
                    } else {
                        toastr.error(obj.message, 'Branch', {
                            "progressBar": true
                        });
                    }
                }
            });
        }
    });
</script>