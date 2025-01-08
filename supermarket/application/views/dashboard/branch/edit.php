<?php include '../supermarket/config.php'; ?>

<form class="form" id="updateBranchForm" data-parsley-validate>
    <div class="row">
        <?php
        if ($branch) {
            foreach ($branch->result() as $br) { ?>


                <div class="row col-md-12 col-12">


                    <div class="form-group col-md-6 col-12 mandatory">
                        <label for="category-name-column" class="col-md-4 form-label text-left"><?php echo $translations['Name'] ?></label>
                        <input type="hidden" class="form-control" name="branchCode" id="branchCode" value="<?= $br->code ?>">
                        <input type="text" id="modalbranchName" class="form-control" placeholder="Name" name="modalbranchName" required value="<?= $br->branchName ?>">
                    </div>


                    <div class="form-group col-md-6 col-12 mandatory">
                        <label for="category-name-column" class="col-md-4 form-label text-nowrap text-left"><?php echo $translations['Tax Group']?></label>
                        <select class="form-select select2" style="width:100%" name="modaltaxGroup" id="modaltaxGroup" required>
                            <option value="">Select</option>
                            <?php
                            if ($taxGroups) {
                                foreach ($taxGroups->result() as $tc) {
                                    if ($tc->code == $br->taxGroup) {
                                        echo "<option value='" . $tc->code . "' selected>" . $tc->taxGroupName . "</option>";
                                    } else {
                                        echo "<option value='" . $tc->code . "'>" . $tc->taxGroupName . "</option>";
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>



                </div>





                <div class="row col-md-12 col-12">

                    <div class="form-group col-md-6 col-12">
                        <label for="category-name-column" class="col-md-4 form-label text-nowrap text-left"><?php echo $translations['Tax Registration Name']?></label>
                        <input type="text" id="modalbranchTaxRegName" class="form-control" name="modalbranchTaxRegName" value="<?= $br->branchTaxRegName ?>">
                    </div>

                    <div class="form-group col-md-6 col-12">
                        <label for="category-name-column" class="col-md-4 form-label text-nowrap text-left"><?php echo $translations['Tax Number']?></label>
                        <input type="text" id="modalbranchTaxRegNo" class="form-control" name="modalbranchTaxRegNo" value="<?= $br->branchTaxRegNo ?>">
                    </div>

                </div>



                <div class="row col-md-12 col-12">
                    <div class="form-group col-md-6 col-12">
                        <label for="category-name-column" class="col-md-4 form-label text-nowrap text-left"><?php echo $translations['Opening From']?></label>
                        <input type="time" id="modalopeningFrom" class="form-control" name="modalopeningFrom" value="<?= $br->openingFrom ?>">
                    </div>

                    <div class="form-group col-md-6 col-12">
                        <label for="category-name-column" class="col-md-4 form-label text-nowrap text-left"><?php echo $translations['Opening To']?></label>
                        <input type="time" id="modalopeningTo" onchange="validateToTime()" class="form-control" name="modalopeningTo" value="<?= $br->openingTo ?>">
                    </div>


                </div>



                <div class="row col-md-12 col-12">
                    <div class="form-group col-md-12 col-12">
                        <label for="category-name-column" class="col-md-4 form-label text-left"><?php echo $translations['Phone']?></label>
                        <input type="text" id="modalbranchPhoneNo" class="form-control" name="modalbranchPhoneNo" value="<?= $br->branchPhoneNo ?>">
                    </div>
                </div>


                <div class="row col-md-12 col-12">

                    <div class="form-group col-md-6 col-12">
                        <label for="category-name-column" class="col-md-4 form-label text-left"><?php echo $translations['Latitude']?></label>
                        <input type="text" id="modalbranchLat" class="form-control" name="modalbranchLat" value="<?= $br->branchLat ?>">
                    </div>

                    <div class="form-group col-md-6 col-12">
                        <label for="category-name-column" class="col-md-4 form-label text-left"><?php echo $translations['Longitude']?></label>
                        <input type="text" id="modalbranchLong" class="form-control" name="modalbranchLong" value="<?= $br->branchLong ?>">
                    </div>


                </div>



                <div class="row col-md-12 col-12">
                    <div class="form-group col-md-12 col-12">
                        <label for="category-name-column" class="col-md-4 form-label text-left"><?php echo $translations['Address']?></label>
                        <textarea rows="3" id="modalbranchAddress" class="form-control" name="modalbranchAddress"><?= $br->branchAddress ?></textarea>
                    </div>
                </div>



                <div class="row col-md-12 col-12">

                    <div class="form-group col-md-6 col-12">
                        <label for="category-name-column" class="col-md-4 form-label text-nowrap text-left"><?php echo $translations['Receipt Header']?></label>
                        <textarea rows="3" id="modalreceiptHead" class="form-control" name="modalreceiptHead"><?= $br->receiptHead ?></textarea>
                    </div>

                    <div class="form-group col-md-6 col-12">
                        <label for="category-name-column" class="col-md-4 form-label text-nowrap text-left"><?php echo $translations['Receipt Foot']?></label>
                        <textarea rows="3" id="modalreceiptFoot" class="form-control" name="modalreceiptFoot"><?= $br->receiptFoot ?></textarea>
                    </div>


                </div>







                <div class="form-group d-flex col-md-12 col-12 text-center items-center justify-content-center row">
                    <!--<div class="form-group row">-->
                    <label for="status" class="col-sm-2 form-label"><?php echo $translations['Active']?></label>
                    <!--<div class="col-sm-8 checkbox">-->
                    <input type="checkbox" name="modalisActive" id="modalisActive"
                        <?php if ($br->isActive == 1) {
                            echo 'checked';
                        } ?> class="mt-2" style="width:25px; height:25px">
                    <!--</div>-->
                    <!--</div>-->
                </div>


    </div>

    <div class="row">
        <div class="col-12 d-flex justify-content-end">
            <button type="button" class="btn btn-primary" id="updateBranchBtn"><?php echo $translations['Update']?></button>
            <button type="button" class="btn btn-light-secondary" id="closeBranchBtn" data-bs-dismiss="modal"><?php echo $translations['Close']?></button>
        </div>
    </div>
<?php }
        } ?>
</form>