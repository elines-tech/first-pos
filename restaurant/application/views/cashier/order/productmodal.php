<?php
if ($comboProduct == 0) {
    $productName = $productDetails->productEngName;
    switch ($lang) {
        case "arabic":
            $productName = $productDetails->productArbName;
            break;
        case "hindi":
            $productName = $productDetails->productHinName;
            break;
        case "urdu":
            $productName = $productDetails->productUrduName;
            break;
    }
    $productNames = [
        "productEngName" => $productDetails->productEngName,
        "productArbName" => $productDetails->productArbName,
        "productHinName" => $productDetails->productHinName,
        "productUrduName" => $productDetails->productUrduName
    ];
    $productNames = base64_encode(stripslashes(json_encode($productNames)));
    $productCode = $productDetails->code;
    $productPrice = number_format($productDetails->productPrice, 2, '.', '');
    $productTaxAmount = number_format(($productPrice * ($productTaxPercent * 0.01)), 2, '.', '');
    $productTotal  =  number_format($productPrice + $productTaxAmount, 2, '.', '');
    $productComboNames = "";
    $productComboCodes = "";
} else {
    $productName = $productDetails->productComboName;
    switch ($lang) {
        case "arabic":
            $productName = $productDetails->productComboArabicName != "" ? $productDetails->productComboHindiName :  $productDetails->productComboName;
            break;
        case "hindi":
            $productName = $productDetails->productComboHindiName != "" ? $productDetails->productComboHindiName :  $productDetails->productComboName;
            break;
        case "urdu":
            $productName = $productDetails->productComboUrduName != "" ? $productDetails->productComboUrduName :  $productDetails->productComboName;
            break;
    }
    $productNames = [
        "productEngName" => $productDetails->productComboName,
        "productArbName" => $productDetails->productComboArabicName,
        "productHinName" => $productDetails->productComboHindiName,
        "productUrduName" => $productDetails->productComboUrduName
    ];
    $productNames = base64_encode(stripslashes(json_encode($productNames)));
    $productCode = $productDetails->code;
    $productPrice = number_format($productDetails->productComboPrice, 2, '.', '');
    $productTaxPercent =  $productTaxAmount = number_format($productDetails->taxAmount, 2, '.', '');
    $productTotal =  number_format($productDetails->productFinalPrice, 2, '.', '');
    $productComboNames = base64_encode(stripslashes(json_encode($productComoboNames)));
    $productComboCodes = base64_encode(stripslashes(json_encode($prdCodes)));
}
?>
<div class="row mr-item-rw">
    <div class="col-8">
        <strong><?= $productName ?></strong>
        <div class="input-group input-group-sm">
            <span class="input-group-text">Quantity</span>
            <input type="number" name="productQty" id="productQty" class="form-control form-control-sm text-center" value="1" min="1" />
        </div>
        <input type="hidden" name="productCode" id="productCode" value="<?= $produtCode ?>" />
        <input type="hidden" name="variantCode" readonly value="" id="variantCode" />
        <input type="hidden" name="productCombo" id="productCombo" value="<?= $comboProduct ?>" />
        <input type="hidden" name="productNames" id="productNames" value="<?= $productNames ?>" />
        <input type="hidden" name="productComboCodes" id="productComboCodes" value="<?= $productComboCodes ?>" />
        <input type="hidden" name="productComboNames" id="productComboNames" value="<?= $productComboNames ?>" />
        <input type="hidden" name="productTaxPercent" id="productTaxPercent" value="<?= $productTaxPercent ?>" readonly>
        <input type="hidden" name="productTaxAmount" id="productTaxAmount" value="<?= $productTaxAmount ?>" />
        <input type="hidden" name="productPrice" id="productPrice" value="<?= $productPrice ?>" />
    </div>
    <div class="col-4">
        <span>Amount</span>
        <br />
        <input type="text" class="form-control form-control-sm no-border" readonly name="productAmount" id="productAmount" value="<?= $productTotal ?>" />
    </div>
    <?php
    if ($comboProduct == 1) {
    ?>
        <div class="col-12">
            <div>Combo Products</div>
            <?php
            foreach ($productComoboNames as $k => $v) {
                $cmbPrdName = $v['productEngName'];
                switch ($lang) {
                    case "arabic":
                        $cmbPrdName = $v['productArbName'] != "" ? $v['productArbName'] : $v['productEngName'];
                        break;
                    case "hindi":
                        $cmbPrdName = $v['productHinName'] != "" ? $v['productHinName'] : $v['productEngName'];
                        break;
                    case "urdu":
                        $cmbPrdName = $v['productUrduName'] != "" ? $v['productUrduName'] : $v['productEngName'];
                        break;
                }
                echo '<span class="badge bg-success me-1">' . $cmbPrdName . '</span>';
            }
            ?>

        </div>
    <?php
    }
    ?>
</div>
<?php

if ($customizes) {
    echo '<div class="ctztitle">Customizable</div>';
    $ctitems = "";
    foreach ($customizes->result() as $customize) {
        $customizeItemName = $customize->itemEngName;
        switch ($lang) {
            case "arabic":
                $customizeItemName = ($customize->itemArbName != "" && $customize->itemArbName != null) ? $customize->itemArbName : $customize->itemEngName;
                break;
            case "hindi":
                $customizeItemName = ($customize->itemHinName != "" && $customize->itemHinName != null) ? $customize->itemHinName : $customize->itemEngName;
                break;
            case "urdu":
                $customizeItemName = ($customize->itemUrduName != "" && $customize->itemUrduName != null) ? $customize->itemUrduName : $customize->itemEngName;
                break;
        }

        $cstNames = [
            "itemEngName" => $customize->itemEngName,
            "itemArbName" => $customize->itemArbName,
            "itemHinName" => $customize->itemHinName,
            "itemUrduName" => $customize->itemUrduName
        ];

        $cstNames = base64_encode(stripslashes(json_encode($cstNames)));

        $ctitem = '<div class="row">';
        $ctitem .= '<div class="col-8">';
        $ctitem .= '<input type="hidden" readonly id="cstItemTitle_' . $customize->itemCode . '" value="' . $customizeItemName . '">';
        $ctitem .= '<input type="hidden" readonly id="cstItemNames_' . $customize->itemCode . '" value="' . $cstNames . '">';
        $ctitem .= '<input type="hidden" readonly id="cstItemQty_' . $customize->itemCode . '" value="' . $customize->itemQty . '">';
        $ctitem .= '<input type="hidden" readonly id="cstItemPrice_' . $customize->itemCode . '" value="' . $customize->itemCost . '">';
        $ctitem .= '<input type="hidden" readonly id="cstItemUnitCode_' . $customize->itemCode . '" value="' . $customize->unitCode . '">';
        $ctitem .= '<input type="hidden" readonly id="cstItemConsQty_' . $customize->itemCode . '" value="0">';
        $ctitem .= '<div class="form-check">';
        $ctitem .= '   <input class="form-check-input cstChks" type="checkbox" value="' . $customize->itemCode . '" id="cstItem' . $customize->itemCode . '"/>';
        $ctitem .= '   <label class="form-check-label" for="cstItem' . $customize->itemCode . '"> ' . $customizeItemName . ' (' . $customize->itemCost . ') </label>';
        $ctitem .= '</div>';
        $ctitem .= '<div class="input-group input-group-sm">';
        $ctitem .= '   <span class="input-group-text">Quantity</span>';
        $ctitem .= '   <input type="number" id="cstQty_' . $customize->itemCode . '" class="form-control form-control-sm text-center cstItmQty" value="0" min="0" readonly/>';
        $ctitem .= '</div>';
        $ctitem .= '</div>';
        $ctitem .= '<div class="col-4">';
        $ctitem .= '<span>Price</span><br /><input type="text" class="form-control form-control-sm no-border" readonly id="cstItemAmount_' . $customize->itemCode . '" value="0.00" />';
        $ctitem .= '</div>';
        $ctitem .= '</div>';
        $ctitems .= $ctitem;
    }
    echo $ctitems;
}
if ($extraitems) {
    echo '<div class="adntitle">Extra Items</div>';
    $extitems = "";
    foreach ($extraitems->result() as $extraitem) {
        $extraitemItemName = $extraitem->itemEngName;
        switch ($lang) {
            case "arabic":
                $customizeItemName = ($extraitem->itemArbName != "" && $extraitem->itemArbName != null) ? $extraitem->itemArbName : $extraitem->itemEngName;
                break;
            case "hindi":
                $extraitemItemName = ($extraitem->itemHinName != "" && $extraitem->itemHinName != null) ? $extraitem->itemHinName : $extraitem->itemEngName;
                break;
            case "urdu":
                $extraitemItemName = ($extraitem->itemUrduName != "" && $extraitem->itemUrduName != null) ? $extraitem->itemUrduName : $extraitem->itemEngName;
                break;
        }

        $extNames = [
            "itemEngName" => $extraitem->itemEngName,
            "itemArbName" => $extraitem->itemArbName,
            "itemHinName" => $extraitem->itemHinName,
            "itemUrduName" => $extraitem->itemUrduName
        ];

        $extNames = base64_encode(stripslashes(json_encode($extNames)));

        $extitem = '<div class="row">';
        $extitem .= '<div class="col-8">';
        $extitem .= '<input type="hidden" readonly id="extItemTitle_' . $extraitem->itemCode . '" value="' . $extraitemItemName . '">';
        $extitem .= '<input type="hidden" readonly id="extItemNames_' . $extraitem->itemCode . '" value="' . $extNames . '">';
        $extitem .= '<input type="hidden" readonly id="extItemQty_' . $extraitem->itemCode . '" value="' . $extraitem->itemQty . '">';
        $extitem .= '<input type="hidden" readonly id="extItemPrice_' . $extraitem->itemCode . '" value="' . $extraitem->price . '">';
        $extitem .= '<input type="hidden" readonly id="extItemUnitCode_' . $extraitem->itemCode . '" value="' . $extraitem->unitCode . '">';
        $extitem .= '<input type="hidden" readonly id="extItemConsQty_' . $extraitem->itemCode . '" value="0">';
        $extitem .= '<div class="form-check">';
        $extitem .= '   <input class="form-check-input extChks" type="checkbox" value="' . $extraitem->itemCode . '" id="extItem' . $extraitem->itemCode . '"/>';
        $extitem .= '   <label class="form-check-label" for="extItem' . $extraitem->itemCode . '"> ' . $extraitemItemName . ' (' . $extraitem->price . ') </label>';
        $extitem .= '</div>';
        $extitem .= '<div class="input-group input-group-sm">';
        $extitem .=     '<span class="input-group-text">Quantity</span>';
        $extitem .=     '<input type="number" id="extQty_' . $extraitem->itemCode . '" class="form-control form-control-sm text-center extItmQty" value="0" min="0" readonly/>';
        $extitem .= '</div>';
        $extitem .= '</div>';
        $extitem .=    '<div class="col-4">';
        $extitem .=       '<span>Price</span><br /><input type="text" class="form-control form-control-sm no-border" readonly  id="extItemAmount_' . $extraitem->itemCode . '" value="0.00" />';
        $extitem .=   '</div>';
        $extitem .= '</div>';
        $extitems .= $extitem;
    }
    echo $extitems;
}
?>