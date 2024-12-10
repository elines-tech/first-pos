 <form class="form" id="updateBranchForm" data-parsley-validate>
     <div class="row">
         <?php
            if ($branch) {
                foreach ($branch->result() as $br) { ?>
                 <div class="col-md-12 col-12">
                     <div class="form-group row mandatory">
                         <label for="category-name-column" class="col-md-4 form-label text-left">Name</label>
                         <div class="col-md-8">
                             <input type="hidden" class="form-control" name="branchCode" id="branchCode" value="<?= $br->code ?>">
                             <input type="text" id="modalbranchName" class="form-control" placeholder="Name" name="modalbranchName" required value="<?= $br->branchName ?>">
                         </div>
                     </div>

                 </div>
                 <div class="col-md-12 col-12">
                     <div class="form-group row mandatory">
                         <label for="category-name-column" class="col-md-4 form-label text-left">Tax Group</label>
                         <div class="col-md-8">
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
                 </div>
                 <div class="col-md-12 col-12">
                     <div class="form-group row">
                         <label for="category-name-column" class="col-md-4 form-label text-left">Tax Registration Name</label>
                         <div class="col-md-8">
                             <input type="text" id="modalbranchTaxRegName" class="form-control" name="modalbranchTaxRegName" value="<?= $br->branchTaxRegName ?>">
                         </div>
                     </div>
                 </div>
                 <div class="col-md-12 col-12">
                     <div class="form-group row">
                         <label for="category-name-column" class="col-md-4 form-label text-left">Tax Number</label>
                         <div class="col-md-8">
                             <input type="text" id="modalbranchTaxRegNo" class="form-control" name="modalbranchTaxRegNo" value="<?= $br->branchTaxRegNo ?>">
                         </div>
                     </div>
                 </div>
                 <div class="col-md-12 col-12">
                     <div class="form-group row">
                         <label for="category-name-column" class="col-md-4 form-label text-left">Opening From</label>
                         <div class="col-md-8">
                             <input type="time" id="modalopeningFrom" class="form-control" name="modalopeningFrom" value="<?= $br->openingFrom ?>">
                         </div>
                     </div>
                 </div>
                 <div class="col-md-12 col-12">
                     <div class="form-group row">
                         <label for="category-name-column" class="col-md-4 form-label text-left">Opening To</label>
                         <div class="col-md-8">
                             <input type="time" id="modalopeningTo" onchange="validateToTime()" class="form-control" name="modalopeningTo" value="<?= $br->openingTo ?>">
                         </div>
                     </div>
                 </div>
                 <div class="col-md-12 col-12">
                     <div class="form-group row">
                         <label for="category-name-column" class="col-md-4 form-label text-left">Phone</label>
                         <div class="col-md-8">
                             <input type="text" id="modalbranchPhoneNo" class="form-control" name="modalbranchPhoneNo" value="<?= $br->branchPhoneNo ?>">
                         </div>
                     </div>
                 </div>
                 <div class="col-md-12 col-12">
                     <div class="form-group row">
                         <label for="category-name-column" class="col-md-4 form-label text-left">Address</label>
                         <div class="col-md-8">
                             <textarea rows="3" id="modalbranchAddress" class="form-control" name="modalbranchAddress"><?= $br->branchAddress ?></textarea>
                         </div>
                     </div>
                 </div>
                 <div class="col-md-12 col-12">
                     <div class="form-group row">
                         <label for="category-name-column" class="col-md-4 form-label text-left">Latitude</label>
                         <div class="col-md-8">
                             <input type="text" id="modalbranchLat" class="form-control" name="modalbranchLat" value="<?= $br->branchLat ?>">
                         </div>
                     </div>
                 </div>
                 <div class="col-md-12 col-12">
                     <div class="form-group row">
                         <label for="category-name-column" class="col-md-4 form-label text-left">Longitude</label>
                         <div class="col-md-8">
                             <input type="text" id="modalbranchLong" class="form-control" name="modalbranchLong" value="<?= $br->branchLong ?>">
                         </div>
                     </div>
                 </div>
                 <div class="col-md-12 col-12">
                     <div class="form-group row">
                         <label for="category-name-column" class="col-md-4 form-label text-left">Receipt Header</label>
                         <div class="col-md-8">
                             <textarea rows="3" id="modalreceiptHead" class="form-control" name="modalreceiptHead"><?= $br->receiptHead ?></textarea>
                         </div>
                     </div>
                 </div>
                 <div class="col-md-12 col-12">
                     <div class="form-group row">
                         <label for="category-name-column" class="col-md-4 form-label text-left">Receipt Foot</label>
                         <div class="col-md-8">
                             <textarea rows="3" id="modalreceiptFoot" class="form-control" name="modalreceiptFoot"><?= $br->receiptFoot ?></textarea>
                         </div>
                     </div>
                 </div>
                 <div class="col-md-12 col-12">
                     <div class="form-group row">
                         <label for="status" class="col-sm-4 col-form-label text-left">Active</label>
                         <div class="col-sm-8 checkbox">
                             <input type="checkbox" name="modalisActive" id="modalisActive" <?php if ($br->isActive == 1) {
                                                                                        echo 'checked';
                                                                                    } ?> class=" " style="width:25px; height:25px">
                         </div>
                     </div>
                 </div>
     </div>

     <div class="row">
         <div class="col-12 d-flex justify-content-end">
             <button type="button" class="btn btn-primary white me-2 mb-1 sub_1" id="updateBranchBtn">Update</button>
             <button type="button" class="btn btn-light-secondary me-1 mb-1" id="closeBranchBtn" data-bs-dismiss="modal">Close</button>
         </div>
     </div>
 <?php }
            } ?>
 </form>