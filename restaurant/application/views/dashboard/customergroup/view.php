    <?php foreach ($query->result() as $row) {  ?>
        <div class="row">
            <div class="col-md-12 col-12">
                <div class="form-group">
                    <label for="unit-name-column" class="form-label">Group Name</label>
                    <input type="text" id="groupname" class="form-control" placeholder="Group Name" value="<?= $row->customerGroupName ?>" name="groupname" readonly>
                    <span class="text-danger" id="err"></span>
                </div>
            </div>

            <div class="form-group d-flex justify-content-center col-md-12 col-12">

                <!--<label for="status" class="form-label">Active</label>-->
                
                <div class="checkbox">
                    <?php if ($row->isActive == 1) {
                        echo " <span class='badge bg-success mt-2'>Active</span>";
                    } else {
                        echo "<span class='badge bg-danger mt-2'>Inactive</span>";
                    } ?>
                </div>

            </div>


            <div class="row">
                <div class="col-12 d-flex justify-content-end">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal" id="closeProductCombo">Close</button>
                </div>
            </div>
        </div>
    <?php } ?>