<div class="page-heading m-4">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h5 class="page-title">Subscription Plan</h5>
                <div class="d-flex align-items-center">
                </div>
            </div>
            <div class="col-5 align-self-center text-end">
                <a id="back" href="<?= base_url("packages/view") ?>" class="btn btn-sm btn-primary">Back</a>
            </div>
        </div>
    </div>
</div>
<div class="page-content m-4">
    <section class="container col-md-6" style="padding-bottom: 15px;">
        <form class="form" id="editForm" enctype="multipart/form-data" method="post" action="<?php echo base_url(); ?>packages/update" data-parsley-validate>
            <?php
            echo "<div class='text-danger text-center' id='error_message'>";
            if (isset($error_message)) {
                echo $error_message;
            }
            echo "</div>";
            ?>
            <div class="card">
                <div class="card-header">
                    <h5>Edit</h5>
                </div>
                <?php foreach ($supermarket->result() as $item) { ?>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="title">Title</label>
                                <input type="hidden" name="code" id="code" class="form-control" value="<?= $item->code ?>">
                                <input type="text" name="title" id="title" class="form-control" value="<?= $item->title ?>" data-parsley-required="true" data-parsley-required-message="Title is required" />
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="description">Description</label>
                                <textarea type="text" name="description" id="description" class="form-control" rows="5"><?= $item->description ?></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="freetrialdays">Free Trial Days</label>
                                <input type="number" name="freetrialdays" id="freetrialdays" step="1" min="1" max="60" class="form-control" value="<?= $item->freetrialday ?>" data-parsley-required="true" data-parsley-required-message="Trial days are required" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tax">Tax (%)</label>
                                <input type="text" name="tax" id="tax" class="form-control" value="<?= $item->tax ?>" onkeypress="return isDecimal(this, event)" onchange="checkTax();calculatePerUserCharges();calculatePerBranchCharges();calculatePrice();" data-parsley-required="true" data-parsley-required-message="Tax is required" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="numberofusers">No. of users</label>
                                <input type="text" name="numberofusers" id="numberofusers" maxlength="3" class="form-control" value="<?= $item->noofusers ?>" onkeypress="return isNumber(event)" onchange="calculatePerUserCharges();calculatePrice();" data-parsley-required="true" data-parsley-required-message="Number of Users are required" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="costperuser">Cost (per user)</label>
                                <input type="text" name="costperuser" id="costperuser" class="form-control" value="<?= $item->costperuser ?>" onkeypress="return isDecimal(this, event)" onchange="checkCostPerUser();calculatePerUserCharges();calculatePrice();" data-parsley-required="true" data-parsley-required-message="Cost Per User is required" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="numberofbranches">No. of Branches</label>
                                <input type="text" name="numberofbranches" id="numberofbranches" maxlength="2" class="form-control" value="<?= $item->noofbranch ?>" onkeypress="return isNumber(event)" onchange="calculatePerBranchCharges();calculatePrice();" data-parsley-required="true" data-parsley-required-message="Number of branches are required" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="costperbranch">Cost (per Branch)</label>
                                <input type="text" name="costperbranch" id="costperbranch" class="form-control" value="<?= $item->costperbranch ?>" onkeypress="return isDecimal(this, event)" onchange="checkCostPerBranch();calculatePerBranchCharges();calculatePrice();" data-parsley-required="true" data-parsley-required-message="Cost Per Branch is required" />
                            </div>
                        </div>
                        <div class="row">
                            <div><b>Per User Charges </b></div>
                            <div class="col-md-6 mb-3">
                                <label>Monthly</label>
                                <input type="text" name="monthlyperuserprice" id="monthlyperuserprice" readonly class="form-control" value="<?= $item->monthlyperusercharges ?>" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Yearly</label>
                                <input type="text" name="yearlyperuserprice" id="yearlyperuserprice" readonly class="form-control" value="<?= $item->yearlyperusercharges ?>" />
                            </div>
                        </div>
                        <div class="row">
                            <div><b>Per Branch Charges </b></div>
                            <div class="col-md-6 mb-3">
                                <label>Monthly</label>
                                <input type="text" name="monthlyperbranchprice" id="monthlyperbranchprice" readonly class="form-control" value="<?= $item->monthlyperbranchcharges ?>" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Yearly</label>
                                <input type="text" name="yearlyperbranchprice" id="yearlyperbranchprice" readonly class="form-control" value="<?= $item->yearlyperbranchcharges ?>" />
                            </div>
                        </div>
                        <div class="row">
                            <div><b>Monthly Subscription Charges </b> <small>(30 Days = 1 Month)</small></div>

                            <div class="col-md-6 mb-3">
                                <label for="monthlyprice">Price (Exl. Tax)</label>
                                <input type="text" name="monthlyprice" id="monthlyprice" readonly class="form-control" value="<?= $item->monthlychargesextax ?>" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="monthlyfinalprice">Final Price (Incl. Tax)</label>
                                <input type="text" name="monthlyfinalprice" id="monthlyfinalprice" readonly class="form-control" value="<?= $item->monthlychargesincltax ?>" />
                            </div>
                        </div>
                        <div class="row">
                            <div><b>Yearly Subscription Charges</b> <small>(365 Days = 1 Year)</small> </div>
                            <div class="col-md-6 mb-3">
                                <label for="yearlyprice">Price (Exl. Tax)</label>
                                <input type="text" name="yearlyprice" id="yearlyprice" readonly class="form-control" value="<?= $item->yearlychargesextax ?>" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="monltyfinalprice">Final Price (Incl. Tax)</label>
                                <input type="text" name="yearlyfinalprice" id="yearlyfinalprice" readonly class="form-control" value="<?= $item->yearlychargesincltax ?>" />
                            </div>
                        </div>

                        <div class="card-footer d-flex justify-content-end">
                            <button id="edit" type="submit" class="btn btn-primary">Update</button>
                        </div>
                    <?php } ?>
                    </div>
        </form>
    </section>
</div>
<script>
    function isDecimal(txt, evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode == 46) {
            //Check if the text already contains the . character
            if (txt.value.indexOf('.') === -1) {
                return true;
            } else {
                return false;
            }
        } else {
            if (charCode > 31 &&
                (charCode < 48 || charCode > 57))
                return false;
        }
        return true;
    }

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    function calculatePerUserCharges() {
        var tax = Number($('#tax').val());
        var cost = Number($('#costperuser').val());
        var monthlyPrice = (cost / 100) * tax;
        var total = cost + monthlyPrice;
        var yTotal = total * 12;
        $('#monthlyperuserprice').val(total.toFixed(2));
        $('#yearlyperuserprice').val(yTotal.toFixed(2));
    }

    function checkTax() {
        var tax = $('#tax').val();
        if (tax != '' && tax == 0) {
            $('#tax').val('');
            toastr.error('Tax should be greater than 0', 'Package', {
                "progressBar": false
            });
            return false;
        } else {
            $('#tax').val(parseFloat(tax).toFixed(2));
        }
    }

    function checkCostPerUser() {
        var cost = $('#costperuser').val();
        if (cost != '' && cost == 0) {
            $('#costperuser').val('');
            toastr.error('Cost should be greater than 0', 'Package', {
                "progressBar": false
            });
            return false;
        } else {
            $('#costperuser').val(parseFloat(cost).toFixed(2));
        }
    }

    function checkCostPerBranch() {
        var cost = $('#costperbranch').val();
        if (cost != '' && cost == 0) {
            $('#costperbranch').val('');
            toastr.error('Cost should be greater than 0', 'Package', {
                "progressBar": false
            });
            return false;
        } else {
            $('#costperbranch').val(parseFloat(cost).toFixed(2));
        }
    }

    function calculatePerBranchCharges() {
        var tax = Number($('#tax').val());
        var cost = Number($('#costperbranch').val());
        var monthlyPrice = (cost / 100) * tax;
        var total = cost + monthlyPrice;
        var yTotal = total * 12;
        $('#monthlyperbranchprice').val(total.toFixed(2));
        $('#yearlyperbranchprice').val(yTotal.toFixed(2));
    }

    function calculatePrice() {
        var tax = Number($('#tax').val());
        var costUser = Number($('#costperuser').val());
        var costBranch = Number($('#costperbranch').val());
        var countUser = Number($('#numberofusers').val());
        var countBranch = Number($('#numberofbranches').val());
        var total = (costUser * countUser) + (costBranch * countBranch);
        var taxOnTotal = (total / 100) * tax;
        var totalwithTax = total + taxOnTotal;
        $('#monthlyprice').val(total.toFixed(2));
        $('#monthlyfinalprice').val(totalwithTax.toFixed(2));
        var totalYear = total * 12;
        var totalwithTaxYear = totalwithTax * 12;
        $('#yearlyprice').val(totalYear.toFixed(2));
        $('#yearlyfinalprice').val(totalwithTaxYear.toFixed(2));

    }
</script>