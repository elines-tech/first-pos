<div class="page-heading m-4">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h5 class="page-title">New Package</h5>
                <div class="d-flex align-items-center">
                </div>
            </div>
            <div class="col-5 align-self-center text-end">
                <a href="<?= base_url("packages/listRecords") ?>" class="btn btn-sm btn-dark">Back</a>
            </div>
        </div>
    </div>
</div>
<div class="page-content m-4">
    <section class="container col-md-6">
        <form action="">
            <div class="card">
                <div class="card-header">
                    <h5>Create</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <label for="type">Package For</label>
                            <select name="type" id="type" class="form-select">
                                <option value="supermaket">Supermarket</option>
                                <option value="restaurant">Restaurant</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" class="form-control" />
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="description">Description</label>
                            <textarea type="text" name="description" id="description" class="form-control" rows="5"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="freetrialdays">Free Trial Days</label>
                            <input type="number" name="freetrialdays" id="freetrialdays" step="1" min="1" max="60" class="form-control" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="costperuser">Cost (per user)</label>
                            <input type="number" name="costperuser" id="costperuser" class="form-control" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="costperbranch">Cost (per Branch)</label>
                            <input type="number" name="costperbranch" id="costperbranch" class="form-control" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="numberofbranches">No. of Branches</label>
                            <input type="number" name="numberofbranches" id="numberofbranches" class="form-control" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="numberofusers">No. of users</label>
                            <input type="number" name="numberofusers" id="numberofusers" class="form-control" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tax">Tax (%)</label>
                            <input type="number" name="tax" id="tax" class="form-control" />
                        </div>

                    </div>
                    <div class="row">
                        <div><b>Monthly Subscription Charges </b> <small>(30 Days = 1 Month)</small></div>

                        <div class="col-md-6 mb-3">
                            <label for="monthlyprice">Price (Exl. Tax)</label>
                            <input type="number" name="monthlyprice" id="monthlyprice" class="form-control" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="monthlyfinalprice">Final Price (Incl. Tax)</label>
                            <input type="number" name="monthlyfinalprice" id="monthlyfinalprice" class="form-control" />
                        </div>
                    </div>
                    <div class="row">
                        <div><b>Yearly Subscription Charges</b> <small>(365 Days = 1 Year)</small> </div>
                        <div class="col-md-6 mb-3">
                            <label for="yearlyprice">Price (Exl. Tax)</label>
                            <input type="number" name="yearlyprice" id="yearlyprice" class="form-control" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="monltyfinalprice">Final Price (Incl. Tax)</label>
                            <input type="number" name="yearlyfinalprice" id="yearlyfinalprice" class="form-control" />
                        </div>
                    </div>
                    <divc class="row">
                        <div class="col-md-12 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="isActive" checked>
                                <label class="form-check-label" for="isActive">
                                    Active
                                </label>
                            </div>
                        </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </section>
</div>
<script>
</script>