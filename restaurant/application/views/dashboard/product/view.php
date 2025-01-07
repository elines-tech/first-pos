<nav class="navbar navbar-light">
    <div class="container d-block">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last"><a href="<?php echo base_url(); ?>Product/listrecords"><i id='exitButton' class="fa fa-times fa-2x"></i></a></div>

        </div>
    </div>
</nav>


<?php include '../restaurant/config.php'; ?>


<div class="container">

    <!-- // Basic multiple Column Form section start -->
    <section id="multiple-column-form" class="mt-5 mb-5">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?php echo $translations['View Product']?></h3>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <input type="hidden" id="code" readonly name="code" class="form-control" value="<?= $productData[0]['code'] ?>">
                            <div class="row">
                                <div class="col-md-7 col-12">
                                    <div class="row">


                                        <div class="col-md-6 col-12">
                                            <div class="form-group mandatory">
                                                <label for="product-arabic-name" class="form-label"><?php echo $translations['Arabic Name']?></label>
                                                <input type="text" id="product-arabic-name" class="form-control" placeholder="Arabic Name" name="product-arabic-name" readonly value="<?= $productData[0]['productArbName'] ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <div class="form-group mandatory">
                                                <label for="product-english-name" class="form-label"><?php echo $translations['English Name']?></label>
                                                <input type="text" id="product-english-name" class="form-control" placeholder="Product Name" name="product-english-name" data-parsley-required="true" value="<?= $productData[0]['productEngName'] ?>">
                                            </div>
                                        </div>


                                        <div class="col-md-6 col-12">
                                            <div class="form-group mandatory">
                                                <label for="product-hindi-name" class="form-label"><?php echo $translations['Hindi']?></label>
                                                <input type="text" id="product-hindi-name" class="form-control" placeholder="Hindi Name" name="product-hindi-name" readonly value="<?= $productData[0]['productHinName'] ?>">
                                            </div>
                                        </div>


                                        <div class="col-md-6 col-12">
                                            <div class="form-group mandatory">
                                                <label for="product-urdu-name" class="form-label"><?php echo $translations['Urdu']?></label>
                                                <input type="text" id="product-urdu-name" class="form-control" placeholder="Urdu Name" name="product-urdu-name" readonly value="<?= $productData[0]['productUrduName'] ?>">
                                            </div>
                                        </div>


                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="desc-column" class="form-label"><?php echo $translations['Arabic Description']?></label>
                                                <textarea class="form-control" placeholder="Product Arabic Description" id="product-arabic-description" name="product-arabic-description" maxlength='2000' readonly><?= $productData[0]['productArbDesc'] ?></textarea>
                                            </div>
                                        </div>



                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="desc-column" class="form-label"><?php echo $translations['English Description']?></label>
                                                <textarea class="form-control" placeholder="Product English Description" id="product-english-description" name="product-english-description" maxlength='2000' readonly><?= $productData[0]['productEngDesc'] ?></textarea>
                                            </div>
                                        </div>



                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="desc-column" class="form-label"><?php echo $translations['Hindi Description']?></label>
                                                <textarea class="form-control" placeholder="Product Hindi Description" id="product-hindi-description" name="product-hindi-description" maxlength='2000' readonly><?= $productData[0]['productHinDesc'] ?></textarea>
                                            </div>
                                        </div>



                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="desc-column" class="form-label"><?php echo $translations['Urdu Description']?></label>
                                                <textarea class="form-control" placeholder="Product Urdu Description" id="product-urdu-description" name="product-urdu-description" maxlength='2000' readonly><?= $productData[0]['productUrduDesc'] ?></textarea>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-6 col-6 mb-3">
                                            <div class="form-group">
                                                <label class="form-label lng"><?php echo $translations['Cooking Time (Minutes)']?></label> 
                                                <input name="productcookingtime" type="text" class="form-control timepicker3 hasDatepicker" id="productcookingtime" placeholder="00:00" autocomplete="off" value="<?= $productData[0]['preparationTime'] ?>" readonly>

                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6">
                                            <div class="form-group">
                                                <label for="number-of-person-served" class="form-label"><?php echo $translations['Number of persons served']?></label>
                                                <input type="text" class="form-control" id="number_of_person_served" name="number_of_person_served" value="<?= $productData[0]['numberOfPersonServed'] ?>" readonly>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-4 col-12 mb-3">

                                        <div class="d-flex align-items-center">


                                            <div class="form-check mandatory">
                                                <div class="checkbox">
                                                    <label for="checkbox1"><?php echo $translations['Status']?></label>
                                                    <?php if ($productData[0]['isActive'] == 1) {
                                                        echo " <span class='badge bg-success mt-2'>Active</span>";
                                                    } else {
                                                        echo "<span class='badge bg-danger mt-2'>Inactive</span>";
                                                    }

                                                    ?>
                                                </div>
                                            </div>


                                            <div class="form-check">
                                                <div class="checkbox">
                                                    <label for="checkbox2"><?php echo $translations['Is it an Addon?']?></label>
                                                    <?php if ($productData[0]['isAddOn'] == 1) {
                                                        echo " <span class='badge bg-success mt-2'>Active</span>";
                                                    } else {
                                                        echo "<span class='badge bg-danger mt-2'>Inactive</span>";
                                                    }

                                                    ?>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                    <!--<div class="col-md-4 col-6 mb-3">
                                        <div class="form-check">
                                            <div class="checkbox">
                                                <label for="checkbox2">Is it an Addon?</label>
                                                <?php if ($productData[0]['isAddOn'] == 1) {
                                                    echo " <span class='badge bg-success mt-2'>Active</span>";
                                                } else {
                                                    echo "<span class='badge bg-danger mt-2'>Inactive</span>";
                                                }

                                                ?>
                                            </div>
                                        </div>
                                    </div>-->

                                    
                                </div>
                                <div class="col-md-5 col-12">
                                    <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                        <div class="card card-custom gutter-b bg-white border-0">
                                            <div class="card-body">
                                                <div class="col-md-12 col-sm-6 col-xs-6">
                                                    <h3 class="mt-0 header-title lng"><?php echo $translations['Product Category']?><b style="color:red">*</b></h3>
                                                    <div class="form-group mandatory">
                                                        <select class="form-select" name="productcategory" id="productcategory" disabled>
                                                            <option value="">Select Category</option>
                                                            <?php if ($categorydata) {
                                                                foreach ($categorydata->result() as $category) {
                                                                    $selected = $productData[0]['productCategory'] == $category->code ? 'selected' : '';
                                                                    echo '<option value="' . $category->code . '"' . $selected . '>' . $category->categoryName . '</option>';
                                                                }
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-6 col-xs-6">
                                                    <h3 class="mt-0 header-title lng"><?php echo $translations['Product Subcategory']?><b style="color:red">*</b></h3>
                                                    <div class="form-group mandatory">
                                                        <select class="form-select" name="productsubcategory" id="productsubcategory" disabled>
                                                            <option value="">Select Subcategory</option>
                                                            <?php if ($subcategorydata) {
                                                                foreach ($subcategorydata->result() as $subcategory) {
                                                                    $selected = $productData[0]['subcategory'] == $subcategory->code ? 'selected' : '';
                                                                    echo '<option value="' . $subcategory->code . '"' . $selected . '>' . $subcategory->subcategoryName . '</option>';
                                                                }
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                        <div class="card card-custom gutter-b bg-white border-0">
                                            <div class="card-body">
                                                <h3 class="mt-0 header-title lng"><?php echo $translations['Product Image']?></h3>

                                                <div class="col-md-5 col-sm-6 col-xs-6 mb-2 p-0 text-left">
                                                    <?php if ($productData[0]['productImage'] != "") { ?>
                                                        <img class="img-thumbnail mb-2" width="120px" id="logo_icon" src="<?= base_url() . $productData[0]['productImage'] ?>" data-src="">
                                                    <?php } else { ?>
                                                        <img class="img-thumbnail mb-2" width="120px" id="logo_icon" src="https://sub.kaemsoftware.com/development/assets/images/faces/default-img.jpg" data-src="">
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                        <div class="card card-custom gutter-b bg-white border-0">
                                            <div class="card-body">
                                                <div class="form-group m-3 row align-items-center">
                                                    <div class=" col-md-4">
                                                        <label for="productpricingmethod" class="form-label"><?php echo $translations['Pricing Method']?></label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="form-group mb-3 d-flex">
                                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                                <select name="productpricingmethod" id="productpricingmethod" class="form-select" disabled>
                                                                    <option value="1" <?= $productData[0]['productMethod'] == '1' ? 'selected' : '' ?>>Fixed Price</option>
                                                                    <option value="2" <?= $productData[0]['productMethod'] == '2' ? 'selected' : '' ?>>Open Price</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group m-3 mt-2 row align-items-center mandatory" id="priceShow" style="display:<?= $productData[0]['productMethod'] == '1' ? '' : 'none' ?>">
                                                    <div class=" col-md-4">
                                                        <label class="col-form-label lng"><?php echo $translations['Price (SAR)']?><b style="color:red">*</b></label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control" name="productprice" id="productprice" readonly value="<?= $productData[0]['productPrice'] ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group m-3 row align-items-center">
                                                    <div class=" col-md-4">
                                                        <label for="producttaxgroup" class="form-label"><?php echo $translations['Tax Group']?><b style="color:red">*</b></label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="form-group mandatory">
                                                            <select class="form-select" name="producttaxgroup" id="producttaxgroup" readonly>
                                                                <option value="">Select Tax Group</option>
                                                                <?php if ($taxGroupData) {
                                                                    foreach ($taxGroupData->result() as $tax) {
                                                                        $selected = $productData[0]['productTaxGrp'] == $tax->code ? 'selected' : '';
                                                                        echo '<option value="' . $tax->code . '" ' . $selected . '>' . $tax->taxGroupName . '</option>';
                                                                    }
                                                                } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group m-3 row align-items-center">
                                                    <div class=" col-md-4">
                                                        <label for="productcostingmethod" class="form-label"><?php echo $translations['Costing Method']?></label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="form-group mb-3 d-flex">
                                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                                <select name="productcostingmethod" id="productcostingmethod" class="form-select" disabled>
                                                                    <option value="1" <?= $productData[0]['costingMethod'] == '1' ? 'selected' : '' ?>>Fixed Cost</option>
                                                                    <option value="2" <?= $productData[0]['costingMethod'] == '2' ? 'selected' : '' ?>>From Ingredients</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group m-3 mt-2 row align-items-center mandatory" id="costingShow" style="display:<?= $productData[0]['costingMethod'] == '2' ? 'none' : '' ?>">
                                                    <div class=" col-md-4">
                                                        <label class="col-form-label lng"><?php echo $translations['Cost (SAR)']?><b style="color:red">*</b></label>
                                                    </div>

                                                    <div class="col-md-8">
                                                        <input type="text" id="productcost" class="form-control" name="productcost" id="productcost" readonly value="<?= $productData[0]['productFixedCost'] ?>">
                                                    </div>

                                                </div>
                                                <div class="form-group m-3 mt-2 row align-items-center">
                                                    <div class=" col-md-4">
                                                        <label class="col-form-label lng"><?php echo $translations['Product Calories']?></label>
                                                    </div>

                                                    <div class="col-md-8">
                                                        <input type="text" id="productcalories" class="form-control" name="productcalories" readonly value="<?= $productData[0]['productCalories'] ?>">
                                                    </div>

                                                </div>

                                                <div class="form-group m-3 row align-items-center">
                                                    <div class=" col-md-4">
                                                        <label class="col-form-label lng"><?php echo $translations['Branches']?></label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <select class="choices form-select multiple-remove" name="branches[]" multiple="multiple" id="branches" disabled>
                                                            <?php
                                                            $inActiveBranches = json_decode($productData[0]['inActiveBranches'], true);

                                                            if ($branchdata) {
                                                                foreach ($branchdata->result() as $branch) {
                                                                    if ($inActiveBranches != "") {
                                                                        if (in_array($branch->code, $inActiveBranches)) {
                                                                            echo '<option value="' . $branch->code . '" selected>' . $branch->branchName . '</option>';
                                                                        } else {
                                                                            echo '<option value="' . $branch->code . '">' . $branch->branchName . '</option>';
                                                                        }
                                                                    } else {
                                                                        echo '<option value="' . $branch->code . '">' . $branch->branchName . '</option>';
                                                                    }
                                                                }
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- // Basic multiple Column Form section end -->
</div>
<script>
    function isNumberKey(evt) {
        var charCode = evt.which ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) return false;
        return true;
    }

    function ValidateAlpha(evt) {
        var keyCode = evt.which ? evt.which : evt.keyCode;
        if (keyCode > 47 && keyCode < 58) return false;
        return true;
    }

    $(document).ready(function() {
        $("#formFile").change(function() {
            const file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    $("#logo_icon")
                        .attr("src", event.target.result);
                };
                reader.readAsDataURL(file);
            }
        });
        $("body").on("change", "#productpricingmethod", function(e) {
            var thisVal = $(this).val();
            if (thisVal == "1") {
                $("#priceShow").show();
                $("#productprice").attr("data-parsley-required", true);

            } else {
                $("#priceShow").hide();
                $("#productprice").removeAttr("data-parsley-required");
            }
        });
        $("body").on("change", "#productcostingmethod", function(e) {
            var thisVal = $(this).val();
            if (thisVal == "1") {
                $("#costingShow").show();
                $("#productcost").attr("data-parsley-required", true);

            } else {
                $("#costingShow").hide();
                $("#productcost").removeAttr("data-parsley-required");
            }
        });
    });
</script>