<div class="list-group">
    <?php
    if ($cartProducts) {
        $cartProducts = $cartProducts->result();
        for ($i = 0; $i < count($cartProducts); $i++) {
            $cartProduct = $cartProducts[$i];
            $productNames = json_decode($cartProduct->prodNames);
            switch ($userLang) {
                case "english":
                    $prodName = $productNames->productEngName;
                    break;
                case "hindi":
                    $prodName = $productNames->productHinName;
                    break;
                case "urdu":
                    $prodName = $productNames->productUrduName;
                    break;
                case "arabic":
                    $prodName = $productNames->productAraName;
                    break;
            }
            $customizes = json_decode($cartProduct->customizes);
            $addons = json_decode($cartProduct->addOns);
    ?>

            <div class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1"><?= $prodName ?></h5>
                    <small>Qty : <b><?= $cartProduct->productQty ?></b></small>
                </div>
                <p class="mb-1">
                    <?php
                        if($cartProduct->isCombo==1) {
                            
                        }
                    ?>
                </p>
                <p class="mb-1">
                    <?php
                    if (!empty($addons)) {
                        echo ' <div>Addon Items</div>';
                        foreach ($addons as $item) {
                            $itemNames = str_replace("'", '"', $item->itemName);
                            $itemNames = json_decode($itemNames);
                            switch ($userLang) {
                                case "english":
                                    $itemName = $itemNames->itemEngName;
                                    break;
                                case "hindi":
                                    $itemName = $itemNames->itemHinName;
                                    break;
                                case "urdu":
                                    $itemName = $itemNames->itemUrduName;
                                    break;
                                case "arabic":
                                    $itemName = $itemNames->itemArbName;
                                    break;
                            }
                            echo '<div>';
                            if ($item->itemQty > 0) {
                                echo '<div class="p-1 mb-1" style="border:1px solid green;border-radius:5px">' . $itemName . '<span class="float-end text-primary">Qty : <b>' . $item->itemQty . '</b></span></div>';
                            }
                            echo '</div>';
                        }
                    }
                    ?>
                </p>

                <p class="mb-1">
                    <?php
                    if (!empty($customizes)) {
                        echo '<div>Customized Items</div>';
                        foreach ($customizes as $item) {
                            $itemNames = str_replace("'", '"', $item->itemName);
                            $itemNames = json_decode($itemNames);
                            switch ($userLang) {
                                case "english":
                                    $itemName = $itemNames->itemEngName;
                                    break;
                                case "hindi":
                                    $itemName = $itemNames->itemHinName;
                                    break;
                                case "urdu":
                                    $itemName = $itemNames->itemUrduName;
                                    break;
                                case "arabic":
                                    $itemName = $itemNames->itemArbName;
                                    break;
                            }
                            echo '<div>';
                            if ($item->itemQty > 0) {
                                echo '<div class="p-1 mb-1" style="border:1px solid green;border-radius:5px">' . $itemName . '<span class="float-end text-primary">Qty: <b>' . $item->itemQty . '</b></span></div>';
                            } else {
                                echo '<div class="p-1 mb-1"  style="border:1px solid red;border-radius:5px">' . $itemName . '<span class="float-end text-danger"> Do-Not Cook </span></div>';
                            }
                            echo '</div>';
                        }
                    }
                    ?>
                </p>
                <small>
                    <a style="color:#ffffff;background:#525356;padding:0px 5px;border-radius:5px" target="_blank" href="<?= base_url('kitchen/viewRecipe/' . $cartProduct->productCode) ?>">View Recipe</a>
                </small>
            </div>
    <?php
        }
    } else {
        echo '<h4> Not products found in list </h4>';
    }
    ?>
</div>