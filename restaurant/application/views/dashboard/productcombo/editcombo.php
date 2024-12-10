<form id="productComboEditForm" class="form" data-parsley-validate="">
    <div class="row">
        <div class="col-md-6 col-12">
            <div class="form-group mandatory">
                <label for="var-name-column" class="form-label">English Name</label>
                <input type="hidden" id="ccode" name="ccode" data-parsley-required="true" value="<?= $productCombo->code ?>">
                <input type="text" id="cname1" class="form-control" placeholder="Combo Name" name="cname1" data-parsley-required="true" value="<?= $productCombo->productComboName ?>">
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="form-group mandatory">
                <label for="var-name-column" class="form-label">Arabic Name</label>
                <input type="text" id="c_arabicname" class="form-control reqClass" placeholder="Combos/Meals Arabic Name" name="carabicname1" data-parsley-required="true" data-parsley-required-message="Product Combo Name is required." value="<?= $productCombo->productComboArabicName ?>">
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="form-group mandatory">
                <label for="var-name-column">Hindi Name</label>
                <input type="text" id="c_hindiname" class="form-control reqClass" placeholder="Combos/Meals Hindi Name" name="chindiname1" value="<?= $productCombo->productComboHindiName ?>">
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="form-group mandatory">
                <label for="var-name-column">Urdu Name</label>
                <input type="text" id="c_urduname" class="form-control reqClass" placeholder="Combos/Meals Urdu Name" name="curduname1" value="<?= $productCombo->productComboUrduName ?>">
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="form-group row">
                <label for="var-name-column" class="form-label">Category</label>
                <div class="form-group mandatory">
                    <select class="form-select select2" id="productcategory1" style="width:100%" name="productcategory1">
                        <option value="">Select</option>
                        <?php
                        if ($category) {
                            foreach ($category->result() as $cat) {
                                echo "<optgroup label='Category - " . $cat->categoryName . "'>";
                                if ($subcategory) {
                                    foreach ($subcategory->result() as $sub) {
                                        if ($sub->categoryCode == $cat->code) {
                                            if ($productCombo->productCategoryCode == $sub->code) {
                                                echo "<option value='" . $sub->code . "' data-category-code='" . $cat->code . "' selected>" . $sub->subcategoryName . "</option>";
                                            } else {
                                                echo "<option value='" . $sub->code . "' data-category-code='" . $cat->code . "'>" . $sub->subcategoryName . "</option>";
                                            }
                                        }
                                    }
                                }
                                echo "</optgroup>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="form-group">
                <label for="status" class="form-label">Active</label>
                <div class="checkbox">
                    <input type="checkbox" <?= $productCombo->isActive ? "checked" : "" ?> name="isActive1" id="isActive1" value="1" style="width:25px; height:25px">
                </div>
            </div>
        </div>
        <?php
        $option = 0;
        ?>
        <div class="col-md-12 col-12">
            <div class="form-group row">
                <label for="var-name-column" class="col-md-4 form-label text-left">Products & Price</label>
                <?php
                $rws = "";
                if ($productComboLines) {
                    foreach ($productComboLines as $item) {
                        $option++;
                        $rws .= '<div class="col-md-12 row mb-2" id="inputFormRowLine' . $option . '">';
                        $rws .= '<div class="col-md-7">';
                        $rws .= '<select class="form-select select2 proTitleEdit duplicateCheckUpdate reqClass" name="pro_name_line[]" id="pro_name_line' . $option . '" data-id="' . $option . '" style="width:100%" data-parsley-required="true" data-parsley-required-message="Product Name is required." onchange="checkDuplicateProductUpdate("' . $option . '")">';
                        $rws .= '<option value ="">Select Product</option>';

                        if ($productdata) {
                            foreach ($productdata->result() as $product) {
                                $selected = $item->productCode == $product->code ? 'selected' : '';
                                $rws .= '<option value="' . $product->code . '"' . $selected . '>' . $product->productEngName . '</option>';
                            }
                        }
                        $rws .= '</select>  
                        </div>
                        <div class="col-md-4"><input class="form-control subTotalPriceEdit reqClass" placeholder="Price" id="pr_price_line' . $option . '" name="pr_price_line[]" type="number" required data-parsley-required-message="Required"  value="' . $item->productPrice . '">
                        </div>
						 <div class="col-md-1">
						 <button type="button" class="btn btn-sm btn-danger" id="remove_option' . $option . '" onclick="remove_option_line(' . $option . ');"><i class="fa fa-trash"></i></button>
						</div>
                    </div>';
                    }
                }
                echo $rws;
                ?>
                <div id="add_row_line"></div>
                <div class="col-md-12 row mb-2" id="inputFormRowLine0">
                    <div class="col-md-7">
                        <select class="form-select select2 proTitleEdit duplicateCheckUpdate" style="width:100%" name="pro_name_line[]" id="pro_name_line0" data-id="0" data-parsley-required="true" data-parsley-required-message="Product Name is required." onchange="checkDuplicateProductUpdate(0)">
                            <option value="">Select Product</option>
                            <?php if ($productdata) {
                                foreach ($productdata->result() as $product) {
                                    echo '<option value="' . $product->code . '">' . $product->productEngName . '</option>';
                                }
                            } ?>
                        </select>
                    </div>
                    <div class="col-md-4"><input class="form-control subTotalPriceEdit" placeholder="Price" id="pr_price_line0" name="pr_price_line[]" type="text" data-parsley-required="true"></div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-sm btn-success" id="add_option_edit" data-seq="0"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <input type="hidden" readonly id="optionLine" name="optionLine" value="<?= $option ?>">
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="form-group">
                <label for="pric-column" class="form-label">Total Price</label>
                <input type="text" id="price1" class="form-control" placeholder="Combo price" name="price1" value="<?= $productCombo->productComboPrice ?>" required>
            </div>
        </div>
        <div class="col-md-6 col-12" id="file_uploadDiv">
            <div class="form-group">
                <label for="productImage" class="form-label">Product Image :</label>
                <input type="file" id="productComboImage" class="form-control" name="productComboImage">
            </div>
        </div>
        <div class="col-md-6 col-12">
            <?php if ($productCombo->productComboImage != "") { ?>
                <img width="100" height="150" src="<?= base_url() . $productCombo->productComboImage ?>" data-src="">
            <?php } else { ?>
                <img width="100" height="150" src="https://sub.kaemsoftware.com/development/assets/images/combo.png" data-src="">
            <?php } ?>
        </div>
    </div>
    <div class="row">
        <div class="col-12 d-flex justify-content-end">
            <input type="hidden" class="form-control" id="productComboCode1" name="productComboCode1" value="1">
            <button type="button" class="btn btn-primary white me-2 mb-1 sub_1" id="updateProductCombo">Update</button>
            <button type="button" class="btn btn-light-secondary me-1 mb-1" data-bs-dismiss="modal" id="closeProductCombo">Close</button>
        </div>
    </div>
</form>