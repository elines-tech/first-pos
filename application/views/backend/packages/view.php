<div class="page-heading m-4">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h5 class="page-title">Subscription Plans</h5>
                <div class="d-flex align-items-center">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="page-content m-4">
    <div class="container">
        <div class="row">
            <?php
            if ($supermarket) {
                foreach ($supermarket->result() as $item) { ?>
                    <div class="col-sm-6">
                        <form action="">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Supermarket<span style="float:right"><a href="<?= base_url() ?>packages/edit/<?= $item->code ?>" class="btn btn-sm btn-primary">Edit</a></span></h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="title">Title</label>
                                            <input type="text" name="restitle" id="restitle" readonly class="form-control" value="<?= $item->title ?>" />
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="description">Description</label>
                                            <textarea type="text" name="resdescription" id="resdescription" readonly class="form-control" rows="5"><?= $item->description ?></textarea>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="freetrialdays">Free Trial Days</label>
                                            <input type="number" name="resfreetrialdays" id="resfreetrialdays" readonly class="form-control" value="<?= $item->freetrialday ?>" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="tax">Tax (%)</label>
                                            <input type="text" name="restax" id="restax" readonly class="form-control" value="<?= $item->tax ?>" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="numberofusers">No. of users</label>
                                            <input type="text" name="resnumberofusers" id="resnumberofusers" readonly class="form-control" value="<?= $item->noofusers ?>" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="costperuser">Cost (per user)</label>
                                            <input type="text" name="rescostperuser" id="rescostperuser" readonly class="form-control" value="<?= $item->costperuser ?>" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="numberofbranches">No. of Branches</label>
                                            <input type="text" name="resnumberofbranches" id="resnumberofbranches" readonly class="form-control" value="<?= $item->noofbranch ?>" />
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="costperbranch">Cost (per Branch)</label>
                                            <input type="text" name="rescostperbranch" id="rescostperbranch" readonly class="form-control" value="<?= $item->costperbranch ?>" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div><b>Per User Charges </b></div>
                                        <div class="col-md-6 mb-3">
                                            <label>Monthly</label>
                                            <input type="text" name="resmonthlyperuserprice" id="resmonthlyperuserprice" readonly class="form-control" value="<?= $item->monthlyperusercharges ?>" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label>Yearly</label>
                                            <input type="text" name="resyearlyperuserprice" id="resyearlyperuserprice" readonly class="form-control" value="<?= $item->yearlyperusercharges ?>" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div><b>Per Branch Charges </b></div>
                                        <div class="col-md-6 mb-3">
                                            <label>Monthly</label>
                                            <input type="text" name="resmonthlyperbranchprice" id="resmonthlyperbranchprice" readonly class="form-control" value="<?= $item->monthlyperbranchcharges ?>" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label>Yearly</label>
                                            <input type="text" name="resyearlyperbranchprice" id="resyearlyperbranchprice" readonly class="form-control" value="<?= $item->yearlyperbranchcharges ?>" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div><b>Monthly Subscription Charges </b> <small>(30 Days = 1 Month)</small></div>
                                        <div class="col-md-6 mb-3">
                                            <label for="monthlyprice">Price (Exl. Tax)</label>
                                            <input type="text" name="resmonthlyprice" id="resmonthlyprice" readonly class="form-control" value="<?= $item->monthlychargesextax ?>" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="monthlyfinalprice">Final Price (Incl. Tax)</label>
                                            <input type="text" name="resmonthlyfinalprice" id="resmonthlyfinalprice" readonly class="form-control" value="<?= $item->monthlychargesincltax ?>" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div><b>Yearly Subscription Charges</b> <small>(365 Days = 1 Year)</small> </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="yearlyprice">Price (Exl. Tax)</label>
                                            <input type="text" name="resyearlyprice" id="resyearlyprice" readonly class="form-control" value="<?= $item->yearlychargesextax ?>" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="monltyfinalprice">Final Price (Incl. Tax)</label>
                                            <input type="text" name="resyearlyfinalprice" id="resyearlyfinalprice" readonly class="form-control" value="<?= $item->yearlychargesincltax ?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
            <?php }
            }  
            if ($restaurant) {
                foreach ($restaurant->result() as $item) { ?>
                    <div class="col-sm-6">
                        <form action="">
                            <div class="card">
                                <div class="card-header">
                                    <h5> Restaurant<span style="float:right"><a href="<?= base_url() ?>packages/edit/<?= $item->code ?>" class="btn btn-sm btn-primary">Edit</a></span></h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" id="title" readonly class="form-control" value="<?= $item->title ?>" />
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="description">Description</label>
                                            <textarea type="text" name="description" id="description" readonly class="form-control" rows="5"><?= $item->description ?></textarea>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="freetrialdays">Free Trial Days</label>
                                            <input type="number" name="freetrialdays" id="freetrialdays" readonly class="form-control" value="<?= $item->freetrialday ?>" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="tax">Tax (%)</label>
                                            <input type="text" name="tax" id="tax" readonly class="form-control" value="<?= $item->tax ?>" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="numberofusers">No. of users</label>
                                            <input type="text" name="numberofusers" id="numberofusers" readonly class="form-control" value="<?= $item->noofusers ?>" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="costperuser">Cost (per user)</label>
                                            <input type="text" name="costperuser" id="costperuser" readonly class="form-control" value="<?= $item->costperuser ?>" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="numberofbranches">No. of Branches</label>
                                            <input type="text" name="numberofbranches" id="numberofbranches" readonly class="form-control" value="<?= $item->noofbranch ?>" />
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="costperbranch">Cost (per Branch)</label>
                                            <input type="text" name="costperbranch" id="costperbranch" readonly class="form-control" value="<?= $item->costperbranch ?>" />
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
                                            <input type="number" name="monthlyprice" id="monthlyprice" readonly class="form-control" value="<?= $item->monthlychargesextax ?>" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="monthlyfinalprice">Final Price (Incl. Tax)</label>
                                            <input type="number" name="monthlyfinalprice" id="monthlyfinalprice" readonly class="form-control" value="<?= $item->monthlychargesincltax ?>" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div><b>Yearly Subscription Charges</b> <small>(365 Days = 1 Year)</small> </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="yearlyprice">Price (Exl. Tax)</label>
                                            <input type="number" name="yearlyprice" id="yearlyprice" readonly class="form-control" value="<?= $item->yearlychargesextax ?>" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="monltyfinalprice">Final Price (Incl. Tax)</label>
                                            <input type="number" name="yearlyfinalprice" id="yearlyfinalprice" readonly class="form-control" value="<?= $item->yearlychargesincltax ?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
            <?php }
            } ?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        var data = '<?php echo $this->session->flashdata('message'); ?>';
        if (data != '') {
            var obj = JSON.parse(data);
            if (obj.status) {
                toastr.success(obj.message, 'Product', {
                    "progressBar": true
                });
            } else {
                toastr.error(obj.message, 'Product', {
                    "progressBar": true
                });
            }
        }
    });
</script>