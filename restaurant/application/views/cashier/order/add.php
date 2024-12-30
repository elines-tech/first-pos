<?php
$optgroup = "";
if ($sectors) {
    foreach ($sectors->result() as $sector) {
        $html = "";
        if ($tables) {
            $sectorhasTables = false;
            $opts = "";
            foreach ($tables->result() as $table) {
                if ($table->zoneCode == $sector->id) {
                    $sectorhasTables = true;
                    $opts  .= "<option value='" . $table->code . "' data-zone='" . $sector->zoneName . "' data-table-branch='" . $sector->branchCode . "' data-table-section='" . $table->zoneCode . "'>" . $table->tableNumber . "</option>";
                }
            }
            if ($sectorhasTables) $html = "<optgroup label='Sector - " . $sector->zoneName . "'></optgroup>$opts";
        }
        $optgroup .= $html;
    }
}
$items = "";
if ($categories) {
    foreach ($categories->result() as $category) {
        $items .= '<div class="categorybox"><a href="#' . $category->code . '" class="category-link">' .  ucwords(strtolower($category->categoryName)) . '</a></div>';
    }
}
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css" />
<link rel="stylesheet" href="<?= base_url("assets/init_site/orderaddstyle.css") ?>" />

<body id="login">

    <div class="container-fluid pos-window">
        <div class="cat-slider cat-tp" style="display:none">
            <div class="owl-carousel owl-theme">
                <?php echo $items; ?>
            </div>
        </div>
        <div class="row my-2">
            <div class="col-8">
                <?php
                if (!empty($company)) {
                    echo '<h3 class="mb-1">' . ucwords($company['companyname']) . '</h3>';
                }
                if (!empty($branch)) {
                    echo '<div>[' . ucwords($branch['branchName']) . '] <b> Date ' . date('d-m-Y') . '</b></div>';
                }
                ?>
            </div>
            <div class="col-4 text-end">
                <a id="saveDefault" class="btn btn-sm btn-primary new_order" href="javascript:void(0);" title="New-Order">New Order <i class="fa fa-plus"></i></a>
                <a class="btn btn-sm btn-outline-primary btn-full-screen" href="javascript:void(0)" title="Full-Screen"><i class="fa fa-expand"></i></a>
                <a class="btn btn-sm btn-outline-dark" href="<?= base_url('Cashier/order/listRecords') ?>" title="Close page and back to orders"><i class="fa fa-times"></i></a>
            </div>
        </div>
        <div class="row pos-section">
            <div class="col-md-7 pos-prd">
                <div class="cat-slider cat-pos">
                    <div class="owl-carousel owl-theme">
                        <?php echo $items; ?>
                    </div>
                </div>
                <div class="cat-products">
                    <?php
                    if (!empty($categoryProducts)) {
                        foreach ($categoryProducts as  $category) {
                            $products = $category['products'];
                            $comboProducts = $category['comboProducts'];
                            $pCount = count($category['products']);
                            $cpCount = count($category['comboProducts']);
                            if ($pCount > 0 || $cpCount > 0) {
                    ?>
                                <div class="cat-title mt-3" id="<?= $category['categoryCode'] ?>"><?= ucwords(strtolower($category['categoryName'])) ?></div>
                                <div class="row g-2 my-1">
                                    <?php
                                    foreach ($products as $product) {
                                        $prdimage = ($product['productImage'] != "" && $product['productImage'] != NULL) ? $product['productImage'] : "assets/food.png";
                                        if ($lang == "urdu") {
                                            $productName = $product['productUrduName'];
                                        } else if ($lang == "arabic") {
                                            $productName = $product['productArbName'];
                                        } else if ($lang == "hindi") {
                                            $productName = $product['productHinName'];
                                        } else {
                                            $productName = $product['productEngName'];
                                        }
                                    ?>
                                        <div class="col-sm-4 col-md-4 col-lg-3">
                                            <div class="card prod-card">
                                                <div class="prod-card-img">
                                                    <img src="<?= base_url($prdimage) ?>" class="card-img-top" alt="<?= $productName ?>">
                                                </div>
                                                <div class="prod-body">
                                                    <h5 class="prod-title"><?= ucwords($productName) ?></h5>
                                                    <button type="button" class="prod-btn" data-combo-product="0" data-productcode="<?= $product['code']; ?>">Add To Cart</button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    foreach ($comboProducts as $product) {
                                        $prdimage = ($product['productComboImage'] != "" && $product['productComboImage'] != NULL) ? $product['productComboImage'] : "assets/food.png";
                                        if ($lang == "urdu") {
                                            $productName = $product['productComboUrduName'];
                                        } else if ($lang == "arabic") {
                                            $productName = $product['productComboArabicName'];
                                        } else if ($lang == "hindi") {
                                            $productName = $product['productComboHindiName'];
                                        } else {
                                            $productName = $product['productComboName'];
                                        }
                                    ?>
                                        <div class="col-sm-4 col-md-4 col-lg-3">
                                            <div class="card prod-card">
                                                <div class="prod-card-img">
                                                    <img src="<?= base_url($prdimage) ?>" class="card-img-top" alt="<?= $productName ?>">
                                                </div>
                                                <div class="prod-body">
                                                    <h5 class="prod-title"><?= ucwords($productName) ?></h5>
                                                    <button type="button" class="prod-btn" data-combo-product="1" data-productcode="<?= $product['code']; ?>">Add To Cart</button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                    <?php
                            }
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="col-md-5 pos-ord">
                <div class="row">
                    <div class="col-12">
                        <div class="cx-cart" id="cartDiv">
                            <div class="cx-cart-top">
                                <div class="section-title" id="section_title">
                                    Current Order/Cart <span id="cartCount">0</span>
                                </div>
                                <button class="trash-order" id="trashOrderBtn"><i class="fa fa-trash"></i></button>
                            </div>
                            <div class="cx-cart-body" id="cart_body">
                                <input type="hidden" id="draftId" class='hiddenInputs' value="" name="draftId">
                                <input type="hidden" id="isDraft" class='hiddenInputs' name="isDraft" readonly value="">
                                <input type="hidden" id="custName" class='hiddenInputs' name="custName" readonly value="<?= $custName ?>">
                                <input type="hidden" id="custPhone" class='hiddenInputs' name="custPhone" readonly value="<?= $custPhone ?>">
                                <input type="hidden" id="branchCode" class='hiddenInputs' name="branchCode" readonly value="<?= $branchCode ?>">
                                <input type="hidden" id="tableSection" class='hiddenInputs' name="tableSection" readonly value="<?= $tableSection ?>">
                                <input type="hidden" id="tableNumber" class='hiddenInputs' name="tableNumber" readonly value="<?= $tableNumber ?>">
                                <div>
                                    <div>Name <b id="c-nm" class="displayTitles"><?= $custName ?></b></div>
                                    <div>Phone <b id="c-ph" class="displayTitles"><?= $custPhone ?></b></div>
                                    <div>
                                        <span>Table <b id="c-tbl" class="displayTitles"><?= $tableSection ?></b></span>
                                        <span>Sector <b id="c-sec" class="displayTitles"><?= $tableNumber ?></b></span>
                                    </div>
                                </div>
                                <div class="row g-0" id="cart-products">
                                </div>
                            </div>
                            <div class="cx-cart-footer">
                                <div>
                                    Total Price
                                    <div class="float-end" id="cartTotal">0.00</div>
                                </div>
                                <div class="my-2 w-100 d-flex">
                                    <button id="cancelDefault" type="button" class="btn btn-outline-danger btn-sm draft-order w-50 m-1"><i class="fa fa-copy"></i> Draft</button>
                                    <button id="saveDefault" type="button" class="btn btn-primary btn-place-order w-50 m-1"> <i class="fa fa-check"></i> Place Order</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="draft-cart">
                            <div class="draft-cart-top">
                                <div class="card-title">
                                    Drafts
                                    <button type="button" data-bs-toggle="collapse" href="#draft-data" class="toggle-draft-table float-end"><b><i class="fa fa-chevron-down"></i></b></button>
                                </div>
                            </div>
                            <div class="draft-cart-body collapse" id="draft-data">
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Table</th>
                                                <th>Phone</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="draft-orders">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="mdlTblCust" data-bs-keyboard="false" data-bs-backdrop="static" role="dialog" aria-labelledby="mdlTblCust" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Book Table First</h3>
                    <button type="button" class="close" style="color: red;">×</button>
                </div>
                <div class="modal-body">
                    <form class="extablk" id="newCustomer" method="post" data-parsley-validate="">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label for="modaltableCode">Select Table</label>
                                <select id="modaltableCode" name="modaltableCode" class="form-select" required>
                                    <option value="">Select</option>
                                    <?= $optgroup; ?>
                                </select>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="modaltablePhoneNo">Enter Phone Number</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <select class="form-select" id="countryCode" name="countryCode" required>
                                            <!--
                                            onchange="validatePhone()"
                                            <option value="^((?:[+?0?0?966]+)(?:\s?\d{2})(?:\s?\d{7}))$" selected>+966 SAU</option>
                                            <option value="^(?:\+971|00971|0)?(?:50|51|52|55|56|2|3|4|6|7|9)\d{7}$">UAE</option>
                                        -->
                                            <option value="^\d{10}$" selected>+966 SAU</option>
                                            <option value="^\d{10}$">+971 UAE</option>
                                        </select>
                                    </div>
                                    <input type="number" class="form-control" id="modaltablePhoneNo" name="modaltablePhoneNo" required />
                                    <!-- data-parsley-pattern="^\d{10}$" -->
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="modaltableName">Enter Name (Optional)</label>
                                <input class="form-control" id="modaltableName" name="modaltableName" data-parsley-pattern="^[a-zA-Z\s]+$" />
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="bookTableBtn" class="btn btn-sm btn-success">Book Table</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="mdlProduct" data-bs-keyboard="false" data-bs-backdrop="static" role="dialog" aria-labelledby="mdlProduct" aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addToCart" class="extablk" method="post">
                    <div class="modal-header" style="align-items: center;">
                        <h3>Add Item/Product</h3>
                        <button type="button" class="close" style="color: red;" data-bs-dismiss="modal">×</button>
                    </div>
                    <div class="modal-body prd-det" style="padding: 0px 12px 12px 12px">
                    </div>
                    <div class="modal-footer justify-content-between">
                        <div>
                            <span>Total Amount (Incl. Tax)</span><br>
                            <h4 id="totalProductAmount">0.00</h4>
                        </div>
                        <button type="button" class="btn btn-success" id="add_to_cart">Add To cart</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="<?= base_url("assets/init_site/ordercart.js") ?>" differ></script>

</body>