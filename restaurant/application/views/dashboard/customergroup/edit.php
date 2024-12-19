<form class="form" method="post" id="updateGroupForm" enctype="multipart/form-data" data-parsley-validate>
    <?php foreach ($query->result() as $row) {  ?>
        <div class="row">
            <div class="col-md-12 col-12">
                <div class="form-group mandatory">
                    <label for="unit-name-column" class="form-label">Group Name</label>
                    <input type="text" id="groupname" class="form-control" placeholder="Group Name" value="<?= $row->customerGroupName ?>" name="groupname" data-parsley-required="true">
                    <span class="text-danger" id="err"></span>
                </div>
            </div>
            <div class="col-md-12 col-12">
                <div class="form-group row">
                    <label for="status" class="form-label">Active</label>
                    <div class="col-sm-4 checkbox">
                        <input type="checkbox" name="isActive" id="isActive" <?php if ($row->isActive == 1) {
                                                                                    echo "checked";
                                                                                } ?> style="width:25px; height:25px">
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="code" name="code" value="<?= $row->code ?>" class="form-control" readonly>
        <div class="row">
            <div class="col-12 d-flex justify-content-end">
                <?php if ($updateRights == 1) { ?>
                    <button type="button" class="btn btn-primary" id="updateGroupName">Update</button>
                <?php } ?>

            </div>
        </div>
    <?php } ?>
</form>