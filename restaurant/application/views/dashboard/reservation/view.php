<form class="form" id="reservationUpdateForm" data-parsley-validate>
    <?php
    if ($restable) {
        foreach ($restable->result() as $row) { ?>
            <div class="row">


                <div class="col-md-6 col-12">
                    <div class="form-group row mandatory">
                        <label for="category-name-column" class="col-md-12 form-label text-left">Customer Name</label>
                        <div class="col-md-12">
                            <input type="hidden" class="form-control" name="resCode" id="resCode" value="<?= $row->code ?>">
                            <select class="form-select select2" style="width:100%" name="modalcustomerName" id="modalcustomerName" disabled>
                                <option value="">Select Customer</option>
                                <?php
                                if ($customer) {
                                    foreach ($customer->result() as $cust) {
                                        if ($row->customerCode == $cust->code) {
                                            echo "<option value='" . $cust->code . "' selected>" . $cust->name . "</option>";
                                        } else {
                                            echo "<option value='" . $cust->code . "'>" . $cust->name . "</option>";
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                </div>



                <div class="form-group col-md-6 col-12 mandatory">
                    <label for="qty-column" class="form-label text-left">Mobile No.</label>
                    <input type="text" id="modalmobilephone" class="form-control" value="<?= $row->customerMobile ?>" placeholder="Mobile Phone" readonly name="modalmobilephone" maxlength="12" onkeypress="return isNumberKey(event)" required>
                </div>



                <div class="form-group col-md-6 col-12 mandatory">
                    <label class="form-label text-left">Branch</label>
                    <?php if ($branchCode != "") { ?>
                        <input type="hidden" class="form-control" name="branchCode" value="<?= $branchCode; ?>" readonly>
                        <input type="text" class="form-control" name="branchName" value="<?= $branchName; ?>" readonly>
                    <?php } else { ?>
                        <input type="text" class="form-control" name="branchName" value="<?= $row->branchName ?>" readonly>

                    <?php } ?>
                </div>


                <div class="form-group col-md-6 col-12 mandatory">
                    <label class="form-label text-left">Sector</label>
                    <input type="text" class="form-control" name="sector" value="<?= $row->zoneName ?>" readonly>
                </div>


                <div class="form-group col-md-6 col-12 mandatory">
                    <label class="form-label text-left">Table</label>
                    <input type="text" class="form-control" name="table" value="<?= $row->tablenumber ?>" readonly>
                </div>



                <div class="form-group col-md-6 col-12 mandatory">
                    <label for="category-name-column" class="form-label text-left">No. of Peoples</label>
                    <input type="text" id="modalnumberofpeople" class="form-control" readonly placeholder="Peoples" name="modalnumberofpeople" value="<?= $row->noOfPeople ?>" onkeypress="return isNumberKey(event)" required>
                </div>



                <div class="form-group col-md-12 col-12 mandatory">
                    <label for="qty-column" class="form-label text-left col-md-4"> Reservation Date</label>
                    <input type="date" id="modaldate" class="form-control" name="modaldate" readonly value="<?= date('Y-m-d', strtotime($row->resDate)) ?>" required>
                </div>




                <div class="form-group col-md-6 col-12 mandatory">
                    <label for="qty-column" class="form-label text-left">Start Time</label>
                    <input type="time" id="modalstime" class="form-control" readonly placeholder="0:30:00" name="modalstime" value="<?= $row->startTime ?>" required>
                </div>



                <div class="form-group col-md-6 col-12 mandatory">
                    <label for="qty-column" class="form-label text-left">End Time</label>
                    <input type="time" id="modaletime" class="form-control" readonly placeholder="0:30:00" name="modaletime" value="<?= $row->endTime ?>" required>
                </div>



            </div>

            <div class="row">
                <div class="col-12 d-flex justify-content-end">

                    <button type="reset" class="btn btn-light-secondary me-1 mb-1" data-bs-dismiss="modal" id="closeTableBtn">Close</button>
                </div>
            </div>
    <?php }
    } ?>
</form>