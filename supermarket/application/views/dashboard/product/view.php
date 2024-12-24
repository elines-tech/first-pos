<style>
    .select2-container--classic .select2-selection--single,
    .select2-container--default .select2-selection--multiple,
    .select2-container--default .select2-selection--single,
    .select2-container--default .select2-selection--single .select2-selection__arrow,
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        border-color: rgba(0, 0, 0, 0.25);
        height: auto;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background: #1ca1c1;
        color: white
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected],
    .select2-container--default .select2-results__option[aria-selected=true] {
        background: #1ca1c1
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice>span {
        color: white !important;
        forn-weight: bold
    }
</style>
<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Product</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Product</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="row match-height">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3>View Product<span style="float:right"><a id="cancelDefaultButton" href="<?= base_url() ?>product/listRecords" class="btn btn-sm btn-primary">Back</a></span></h3>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <input type="hidden" id="code" readonly name="code" class="form-control" value="<?= $productData[0]['code'] ?>">
                                <div class="row">
                                    <div class="col-md-7 col-12">
                                        <div class="row">


                                            <div class="row col-md-12 col-12">


                                                <div class="form-group col-md-6 col-12 mandatory">
                                                    <label for="product-arabic-name" class="form-label">Arabic Name</label>
                                                    <input type="text" id="product-arabic-name" class="form-control" disabled placeholder="Arabic Name" name="product-arabic-name" readonly value="<?= $productData[0]['productArbName'] ?>">
                                                </div>

                                                <div class="form-group col-md-6 col-12 mandatory">
                                                    <label for="product-english-name" class="form-label">English Name</label>
                                                    <input type="text" id="product-english-name" class="form-control" disabled placeholder="Product Name" name="product-english-name" data-parsley-required="true" value="<?= $productData[0]['productEngName'] ?>">
                                                </div>

                                            </div>


                                            <div class="row col-md-12 col-12">

                                                <div class="form-group col-md-6 col-12">
                                                    <label for="desc-column" class="form-label">Arabic Description</label>
                                                    <textarea class="form-control" placeholder="Product Arabic Description" disabled id="product-arabic-description" name="product-arabic-description" maxlength='2000' readonly><?= $productData[0]['productArbDesc'] ?></textarea>
                                                </div>


                                                <div class="form-group col-md-6 col-12">
                                                    <label for="desc-column" class="form-label">English Description</label>
                                                    <textarea class="form-control" placeholder="Product English Description" disabled id="product-english-description" name="product-english-description" maxlength='2000' readonly><?= $productData[0]['productEngDesc'] ?></textarea>
                                                </div>
                                            </div>







                                            <div class="row col-md-12 col-12">

                                                <div class="form-group col-md-6 col-12 mandatory">
                                                    <label for="product-hindi-name" class="form-label">Hindi</label>
                                                    <input type="text" id="product-hindi-name" class="form-control" disabled placeholder="Hindi Name" name="product-hindi-name" readonly value="<?= $productData[0]['productHinName'] ?>">
                                                </div>

                                                <div class="form-group col-md-6 col-12 mandatory">
                                                    <label for="product-urdu-name" class="form-label">Urdu</label>
                                                    <input type="text" id="product-urdu-name" class="form-control" disabled placeholder="Urdu Name" name="product-urdu-name" readonly value="<?= $productData[0]['productUrduName'] ?>">
                                                </div>


                                            </div>



                                            <div class="row col-md-12 col-12">
                                                <div class="form-group col-md-6 col-12">
                                                    <label for="desc-column" class="form-label">Hindi Description</label>
                                                    <textarea class="form-control" placeholder="Product Hindi Description" disabled id="product-hindi-description" name="product-hindi-description" maxlength='2000' readonly><?= $productData[0]['productHinDesc'] ?></textarea>
                                                </div>

                                                <div class="form-group col-md-6 col-12">
                                                    <label for="desc-column" class="form-label">Urdu Description</label>
                                                    <textarea class="form-control" placeholder="Product Urdu Description" disabled id="product-urdu-description" name="product-urdu-description" maxlength='2000' readonly><?= $productData[0]['productUrduDesc'] ?></textarea>
                                                </div>
                                            </div>








                                        </div>



                                        <div class="row">


                                            <div class="row col-md-12 col-12">

                                                <div class="form-group col-md-6 col-12 mandatory">
                                                    <label for="productprice" class="form-label">SKU</label>
                                                    <input type="text" class="form-control" name="productsku" id="productsku" disabled value="<?= $productData[0]['sku'] ?>">
                                                </div>

                                                <div class="form-group col-md-6 col-12">
                                                    <label for="productprice" class="form-label">Alert Quantity</label>
                                                    <input type="text" min="1" class="form-control" name="alertQty" id="alertQty" disabled value="<?= $productData[0]['alertQty'] ?>">
                                                </div>

                                            </div>


                                            <!--
                                            <div class="col-md-3 col-12">
                                                <div class="form-group mandatory">
													<label for="productprice" class="form-label">Price</label>
													<input type="text" min="1" class="form-control" name="productprice" id="productprice" disabled  value="< $productData[0]['productPrice'] ?>">
												</div>
											</div>
                                            -->



                                            <div class="row col-md-12 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="productprice" class="form-label">Tax Group</label>
                                                    <select class="form-select select2" disabled name="producttaxgroup" id="producttaxgroup">
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

                                        <div class="checkbox mb-5 form-check mandatory mt-2 col-md-12 col-12 d-flex justify-content-center text-center">
                                            <label for="checkbox1 mr-2">Status</label>
                                            <?php if ($productData[0]['isActive'] == 1) {
                                                echo " <span class='badge bg-success'>Active</span>";
                                            } else {
                                                echo "<span class='badge bg-danger'>Inactive</span>";
                                            }

                                            ?>
                                        </div>

                                    </div>
                                    <div class="col-md-5 col-12">
                                        <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                            <div class="card card-custom gutter-b bg-white border-0">
                                                <div class="card-body">
                                                    <div class="col-md-12 col-sm-6 col-xs-6">
                                                        <div class="form-group mandatory">
                                                            <label for="productbrand">Product Brand</label>
                                                            <select class="form-select select2" disabled name="productbrand" id="productbrand" data-parsley-required="true" data-parsley-required-message="Product Brand is required.">
                                                                <option value="">Select </option>
                                                                <?php if ($branddata) {
                                                                    foreach ($branddata->result() as $brd) {
                                                                        $selected = $productData[0]['brandCode'] == $brd->code ? 'selected' : '';
                                                                        echo '<option value="' . $brd->code . '"' . $selected . '>' . $brd->brandName . '</option>';
                                                                    }
                                                                } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group mandatory">
                                                            <label for="productcategory">Product Category</label>
                                                            <select class="form-select select2" name="productcategory" style="width:100%" id="productcategory" disabled>
                                                                <option value="">Select Category</option>
                                                                <?php if ($categorydata) {
                                                                    foreach ($categorydata->result() as $category) {
                                                                        $selected = $productData[0]['productCategory'] == $category->code ? 'selected' : '';
                                                                        echo '<option value="' . $category->code . '"' . $selected . '>' . $category->categoryName . '</option>';
                                                                    }
                                                                } ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group mandatory">
                                                            <label for="productsubcategory">Product Subcategory</label>
                                                            <input type="hidden" class="form-control" id="dbproductsubcategory" name="dbproductsubcategory" value="<?= $productData[0]['productSubcategory'] ?>">
                                                            <select class="form-select select2" style="width:100%" disabled name="productsubcategory" id="productsubcategory" data-parsley-required="true" data-parsley-required-message="Product Subcategory is required.">
                                                                <option value="">Select </option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group mandatory">
                                                            <label for="productcategory">Product Unit</label>
                                                            <select class="form-select select2" name="productUnit" disabled id="productUnit" data-parsley-required="true" data-parsley-required-message="Product Unit is required.">
                                                                <option value="">Select </option>
                                                                <?php if ($unitdata) {
                                                                    foreach ($unitdata->result() as $unit) {
                                                                        $selected = $productData[0]['storageUnit'] == $unit->code ? 'selected' : '';
                                                                        echo '<option value="' . $unit->code . '"' . $selected . '>' . $unit->unitName . '</option>';
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
                                                    <h3 class="mt-0 header-title lng">Product Images</h3>

                                                    <div class="col-md-12 text-center col-sm-12 col-xs-12 mb-2 p-0">
                                                        <?php if ($productData[0]['productImage'] != "") { ?>
                                                            <img class="img-thumbnail mb-2" width="120px" id="logo_icon" src="<?= base_url() . $productData[0]['productImage'] ?>" data-src="">
                                                        <?php } else { ?>
                                                            <img class="img-thumbnail mb-2" width="120px" id="logo_icon" src="https://sub.kaemsoftware.com/development/assets/images/faces/default-img.jpg" data-src="">
                                                        <?php } ?>
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
        getSubcategoryList()
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

    });

    function getSubcategoryList() {
        var categoryCode = $('#productcategory').val();
        var subcategoryCode = $('#dbproductsubcategory').val();
        if (categoryCode != '') {
            $.ajax({
                url: base_path + "product/getSubCategoryList",
                type: 'POST',
                data: {
                    'categoryCode': categoryCode,
                    'subcategoryCode': subcategoryCode
                },
                success: function(response) {
                    var obj = JSON.parse(response);
                    if (obj.status) {
                        $('#productsubcategory').append(obj.subHtml);
                    } else {
                        $("#productsubcategory").val(null).trigger('change.select2');
                        $("#productsubcategory").html('');
                        $("#productsubcategory").append('<option value="">Select</option>');
                    }
                }
            })
        } else {
            $("#productsubcategory").val(null).trigger('change.select2');
            $("#productsubcategory").html('');
            $("#productsubcategory").append('<option value="">Select</option>');
        }
    }
</script>