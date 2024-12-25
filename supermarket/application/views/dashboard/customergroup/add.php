<form class="form" method="post" id="saveGroupForm" enctype="multipart/form-data" data-parsley-validate>
    <div class="row">
        <div class="col-md-12 col-12">
            <div class="form-group mandatory">
                <label for="unit-name-column" class="form-label">Group Name</label>
                <input type="text" id="groupname" class="form-control" placeholder="Group Name" name="groupname" data-parsley-required="true">
                <span class="text-danger" id="err"></span>
            </div>
        </div>
        <div class="col-md-12 col-12">
            <div class="form-group row">
                <label for="status" class="form-label">Active</label>
                <div class="col-sm-4 checkbox">
                    <input type="checkbox" name="isActive" id="isActive" checked style="width:25px; height:25px">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 d-flex justify-content-end">
            <button type="button" class="btn btn-primary" id="saveGroupName">Save</button>
            <button id="cancelDefaultButton" type="reset" class="btn btn-light-secondary">Reset</button>
        </div>
    </div>
</form>