<?php include '../restaurant/config.php'; ?>


<div class="row mb-2">

    <div class="row col-md-12 col-12">

        <div class="col-md-6 col-12 form-group">
            <label for="var-name-column" class="form-label"><?php echo $translations['English Name']?></label>
            <input type="hidden" id="ccode" name="ccode" data-parsley-required="true" value="<?= $productCombo->code ?>" readonly>
            <input type="text" id="cname1" class="form-control" placeholder="Combo Name" name="cname1" data-parsley-required="true" value="<?= $productCombo->productComboName ?>" readonly>
        </div>


        <div class="col-md-6 col-12 form-group">
            <label for="var-name-column" class="form-label"><?php echo $translations['Arabic Name']?></label>
            <input type="text" id="carabicname" class="form-control reqClass" placeholder="Combos/Meals Arabic Name" name="carabicname1" readonly value="<?= $productCombo->productComboArabicName ?>">
        </div>

    </div>


    <div class="row col-md-12 col-12">

        <div class="col-md-6 col-12 form-group">
            <label for="var-name-column" class="form-label"><?php echo $translations['Hindi Name']?></label>
            <input type="text" id="chindiname" class="form-control reqClass" placeholder="Combos/Meals Hindi Name" name="chindiname1" readonly value="<?= $productCombo->productComboHindiName ?>">
        </div>


        <div class="col-md-6 col-12 form-group">
            <label for="var-name-column" class="form-label"><?php echo $translations['Urdu Name']?></label>
            <input type="text" id="curduname" class="form-control reqClass" placeholder="Combos/Meals Urdu Name" name="curduname1" readonly value="<?= $productCombo->productComboUrduName ?>">
        </div>

    </div>

    <?php
    $option = 0;
    ?>

    <div class="col-md-12 mb-3 col-12">
        <div class="form-group">
            <label for="var-name-column" class="form-label"><?php echo $translations['Products & Price']?></label>
            <?php
            $rws = "";
            if ($productComboLines) {
                foreach ($productComboLines as $item) {
                    $option++;
                    $rws .= '<div class="col-md-12 row mb-2" id="inputFormRowLine' . $option . '">';
                    $rws .= '<div class="col-md-5">';
                    $rws .= '<select class="form-select proTitleEdit" name="pro_name_line[]" id="pro_name_line' . $option . '" data-id="' . $option . '" data-parsley-required="true" data-parsley-required-message="Product Name is required." disabled>';
                    $rws .= '<option value ="">Select Product</option>';

                    if ($productdata) {
                        foreach ($productdata->result() as $product) {
                            $selected = $item->productCode == $product->code ? 'selected' : '';
                            $rws .= '<option value="' . $product->code . '"' . $selected . '>' . $product->productEngName . '</option>';
                        }
                    }
                    $rws .= '</select> 
                        </div>
                        <div class="col-md-3"><input class="form-control subTotalPriceEdit" placeholder="Price" id="pr_price_line' . $option . '" name="pr_price_line[]" type="number" required data-parsley-required-message="Required"  value="' . $item->productPrice . '" readonly>
                        </div>
						<div class="col-md-3">
                                    <input class="form-control productTaxEdit reqClass" onkeypress="return isNumber(event)" placeholder="Tax" id="tax_price_line' . $option . '" name="tax_price_line[]" type="text" data-parsley-required="true" value="' . $item->productTaxPrice . '" readonly>
                         </div>
                    </div>';
                }
            }
            echo $rws;
            ?>
        </div>
    </div>

    <div class="row col-md-12 mb-2 col-12">
        <div class="col-md-4 col-12 form-group">
            <label for="pric-column" class="form-label"><?php echo $translations['Total Price']?></label>
            <input type="text" id="price1" class="form-control" placeholder="Combo price" name="price1" value="<?= $productCombo->productComboPrice ?>" required>

        </div>
        <div class="col-md-4 col-12 form-group">
            <label for="pric-column" class="form-label"><?php echo $translations['Total Tax Amount']?></label>
            <input type="text" id="totalTaxAmount" class="form-control reqClass" placeholder="Total Tax Amount" onkeypress="return isNumber(event)" name="totalTaxAmount" value="<?= $productCombo->taxAmount ?>" readonly>
        </div>
        <div class="col-md-4 col-12 form-group">
            <label for="pric-column" class="form-label"><?php echo $translations['Final Amount']?></label>
            <input type="text" id="finalAmount" class="form-control reqClass" placeholder="Final Price" onkeypress="return isNumber(event)" name="finalAmount" value="<?= $productCombo->productFinalPrice ?>" readonly>
        </div>
    </div>

    <div class="row mb-2 col-md-12 items-center justify-content-center col-12">
        <div class="row col-md-12 col-12">
            <div class="col-md-11 col-12 form-group row">
                <label for="var-name-column" class="form-label"><?php echo $translations['Category']?></label>
                <div class="form-group mandatory">
                    <select class="form-control" id="productcategory1" name="productcategory1" disabled>
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
            <div class="col-md-1 col-12 mt-4 form-group row">
                <div class="col-sm-4">
                    <?php if ($productCombo->isActive == 1) {
                        echo " <span class='badge bg-success mt-2'>Active</span>";
                    } else {
                        echo "<span class='badge bg-danger mt-2'>Inactive</span>";
                    }

                    ?>
                </div>
            </div>
        </div>


        <!--<div class="col-md-1 col-6 justify-content-end">
            <div class="form-group row">
                <div class="col-sm-4">
                    <?php if ($productCombo->isActive == 1) {
                        echo " <span class='badge bg-success mt-2'>Active</span>";
                    } else {
                        echo "<span class='badge bg-danger mt-2'>Inactive</span>";
                    }

                    ?>
                </div>
            </div>
        </div>-->
    
    </div>




    <div class="col-md-12 col-12" id="file_uploadDiv">
        <div class="col-md-12 col-12 justify-content-center items-center text-center form-group">
            <label for="productImage" class="col-md-12 justify-content-center items-center text-center form-label"><?php echo $translations['Product Image']?></label>
            <?php if ($productCombo->productComboImage != "") { ?>
                <img width="100" height="150" src="<?= base_url() . $productCombo->productComboImage ?>" data-src="">
            <?php } else { ?>
                <img width="100" height="150" src="https://sub.kaemsoftware.com/development/assets/images/combo.png" data-src="">
            <?php } ?>
        </div>
    </div>

    <div class="row">
        <div class="col-12 d-flex justify-content-end">
            <button type="button" class="btn btn-light-secondary me-1 mb-1" data-bs-dismiss="modal" id="closeProductCombo"><?php echo $translations['Close']?></button>
        </div>
    </div>
</div>