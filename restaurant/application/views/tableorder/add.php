<?php
if (!empty($table)) {
    $lang = "english";
    $custPhone = isset($mainOrder['custPhone']) ? $mainOrder['custPhone'] : "";
    $custName = isset($mainOrder['custName']) ?  $mainOrder['custName'] : "";
    $items = "";
    if ($categories) {
        foreach ($categories->result() as $category) {
            $items .= '<div class="categorybox"><a href="#' . $category->code . '" class="category-link">' .  ucwords(strtolower($category->categoryName)) . '</a></div>';
        }
    }
?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css" />
    <link href="<?= base_url("assets/init_site/orderstyle.css") ?>" rel="stylesheet" />
    <div id="maindiv" class="container-fluid my-2">
        <div class="row">
            <div class="col-7" id="leftdiv">
                <h5> Table - <b><?= $table['tableNumber'] ?></b></h5>
                <small>Order</small>
            </div>
            <div class="col-5 text-end">
                <a class="btn btn-sm btn-primary new-order mx-1" href="javascript:void(0)"><i class="fa fa-plus-circle"></i> New Order </a>
                <a class="btn btn-sm btn-primary my-orders mx-1" href="javascript:void(0)"><i class="fa fa-bars"></i> My Orders </a>
            </div>
        </div>
    </div>
    <div class="row g-2">
        <div class="col-12" id="cart-box" style="display:none">
            <div class="row">
                <div class="col-md-7 col-lg-8">
                    <div class="cat-slider">
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
                                            $prdimage = "assets/food.png";
                                            if ($lang == "urdu") {
                                                $productName = $product['productComboName'];
                                            } else if ($lang == "arabic") {
                                                $productName = $product['productComboName'];
                                            } else if ($lang == "hindi") {
                                                $productName = $product['productComboName'];
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
                <div class="col-md-5 col-lg-4">
                    <div class="oct-bx" style="height:250px">
                        <div class="order-cart p-2">
                            <div class="order-cart-header">
                                <b>Your Cart <span id="cartCount">0</span></b>
                            </div>
                            <div class="order-cart-body">
                                <div class="row" id="cart-products">
                                </div>
                            </div>
                            <div class="order-cart-footer">
                                <div>
                                    Total Price
                                    <div class="float-end" id="cartTotal">0.00</div>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-primary btn-place-order w-100">Place Order</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-12" id="order-box" style="<?= $custPhone == "" ? 'display: none;' : '' ?>">
            <form>
                <input type="hidden" id="custName" name="custName" readonly value="<?= $custName ?>">
                <input type="hidden" id="custPhone" name="custPhone" readonly value="<?= $custPhone ?>">
                <input type="hidden" id="branchCode" name="branchCode" readonly value="<?= $table['branchCode'] ?>">
                <input type="hidden" id="tableSection" name="tableSection" readonly value="<?= $table['zoneCode'] ?>">
                <input type="hidden" id="tableNumber" name="tableNumber" readonly value="<?= $table['code'] ?>">
                <h5>My Orders</h5>
                <div class="p-2" id="kot-orders">
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade text-left" id="mdlTblCust" data-bs-keyboard="false" data-bs-backdrop="static" role="dialog" aria-labelledby="mdlTblCust" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Book Table First</h3>
                    <button type="button" class="close">×</button>
                </div>
                <div class="modal-body">
                    <form class="extablk" id="newCustomer" method="post" data-parsley-validate="">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label for="modaltablePhoneNo">Enter Phone Number</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <select class="form-select" id="countryCode" name="countryCode" required>
                                            <!--
                                            onchange="validatePhone()"
                                            <option value="^((?:[+?0?0?966]+)(?:\s?\d{2})(?:\s?\d{7}))$" selected>SAU</option>
                                            <option value="^(?:\+971|00971|0)?(?:50|51|52|55|56|2|3|4|6|7|9)\d{7}$">UAE</option>
                                            -->
                                            <!--<option value="^\d{10}$" selected>+966 SAU</option>
                                            <option value="^\d{10}$">+971 UAE</option>-->
											<option value="+966">+966 (SAR)</option>
											<option value="+971">+971 (UAE)</option>
                                        </select>
                                    </div>
                                    <input type="number" class="form-control" id="modaltablePhoneNo" name="modaltablePhoneNo" required />
                                    <!-- data-parsley-pattern="^\d{10}$"? -->
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="addToCart" class="extablk" method="post">
                    <div class="modal-header" style="align-items: center;">
                        <h3>Add Item/Product</h3>
                        <button type="button" class="close" data-bs-dismiss="modal">×</button>
                    </div>
                    <div class="modal-body addonsinfo">
                        <table class="table table-bordered table-hover bg-white" id="productTable">
                            <thead>
                                <tr>
                                    <th class="text-center">Product</th>
                                    <th class="text-center wp_100">Quantity</th>
                                    <th class="text-center wp_120">Price</th>
                                </tr>
                            </thead>
                            <tbody id="addItem">
                            </tbody>
                        </table>
                        <table class="table table-bordered table-hover bg-white" id="addonTable">
                            <thead>
                                <tr>
                                    <th class="text-center"></th>
                                    <th class="text-center">Extras Name</th>
                                    <th class="text-center wp_100">Quantity</th>
                                    <th class="text-center">Price </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="add_to_cart">Add To cart</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="<?= base_url("assets/init_site/tableorder.js") ?>"></script>
<?php } ?>