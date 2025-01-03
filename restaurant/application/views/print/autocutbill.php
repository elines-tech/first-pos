<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bill Print | <?= AppName ?> </title>
    <link rel="stylesheet" href="<?= base_url("assets/ini_site/autocut.css") ?>" />
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
</head>

<body>
    <?php
    $branchName = $receiptHeader = $receiptFooter = "";
    if (!empty($branch)) {
        $branchName = $branch['branchName'];
        $branchHeader = $branch['receiptHead'];
        $receiptFooter = $branch['receiptFoot'];
    }
    if (!empty($order)) {
    ?>
        <div style="width:100%;margin:0 auto;text-align:center;margin:15px;" id="btn-block">
            <button type="button" id="print" style="background-color: #37B181;color: #ffffff;transition: none;margin-right: 10px;border: none;border-radius: 10px; padding:15px">Print</button>
            <button type="button" id="back" style="background-color: red;color: #ffffff;transition: none;margin-right: 10px;border: none;border-radius: 10px; padding:15px">Back</button>
        </div>
        <div id="posbill-wrap">
            <div id="inv-header">
                <?php
                if ($receiptHeader != "") {
                    echo '<div id="branch-header" class="txt-center">' . $receiptHeader . '</div>';
                }
                ?>
                <div id="inv-title">Tax Invoice</div>
                <div id="inv-no">INVOIVCE NO: <?= $order['code'] ?></div>
                <div id="store-nm"><?= $company['companyname'] ?></div>
                <div id="branch-nm">[<?= $branchName ?>]</div>
                <div id="store-addr">
                    <?php
                    $address = json_decode($company['address'], true);
                    if (!empty($address)) {
                        $cmpaddress = $address['buildingNo'] . ", " . $address['streetName'] . ", " . $address['district'] . ", " . $address['city'];
                        if ($address['country'] == "966") $cmpaddress .= $address['postalCode'] . ", Saudi Arabia";
                        else $cmpaddress .= $address['postalCode'] . ", United Arab Emirates";
                        echo $cmpaddress;
                    }
                    ?>
                </div>
                <div id="inv-date">Date: <?= date('Y/m/d h:i A', strtotime($order['addDate'])) ?></div>
                <div id="vat-regno">VAT: <?= $company['vatno'] ?></div>
            </div>
            <div id="inv-body">
                <table id="prod-items">
                    <thead>
                        <tr>
                            <th width="30%" class="txt-left">Product</th>
                            <th class="txt-center">Price</th>
                            <th class="txt-center">Qty</th>
                            <th class="txt-center">Tax</th>
                            <th class="txt-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($order['orderItems'])) {
                            $products = $order['orderItems'];
                            for ($i = 0; $i <  count($products); $i++) {
                                $product = $products[$i];
                                $aryPrdName = json_decode($product['prodNames'], true);
                                switch ($userlang) {
                                    case 'english':
                                        $prodName = $aryPrdName['productEngName'];
                                        break;
                                    case 'arabic':
                                        $prodName = $aryPrdName['productAraName'];
                                        break;
                                    case 'urdu':
                                        $prodName = $aryPrdName['productUrduName'];
                                        break;
                                    case 'hindi':
                                        $prodName = $aryPrdName['productHinName'];
                                        break;
                                }
                        ?>
                                <tr>
                                    <td class="txt-left"><?= $prodName ?></td>
                                    <td class="txt-center"><?= $product['productPrice'] ?></td>
                                    <td class="txt-center"><?= $product['productQty'] ?></td>
                                    <td class="txt-center">0.00</td>
                                    <td class="txt-right"><?= $product['totalPrice'] ?></td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <table class="amt-smry">
                <tr>
                    <th class="txt-left">Items total</th>
                    <th class="txt-right"><?= number_format($order['subtotal'], 2, ".", "") ?></th>
                </tr>
                <tr>
                    <th class="txt-left">Total Products Discount</th>
                    <th class="txt-right"><?= number_format($order['discount'], 2, ".", "") ?></th>
                </tr>

                <tr>
                    <th class="txt-left">Total Tax</th>
                    <th class="txt-right"><?= number_format($order['tax'], 2, ".", "") ?></th>
                </tr>
            </table>
            <div class="dash-bdr"></div>
            <table class="amt-smry">
                <tr>
                    <th class="big-txt txt-left">Total</th>
                    <th class="big-txt txt-right"><?= number_format($order['grandTotal'], 2, ".", "") ?></th>
                </tr>
            </table>
            <div id="inv-footer">
                <?php
                if ($receiptFooter != "") {
                    echo '<div id="branch-footer" class="txt-center">' . $receiptFooter . '</div>';
                }
                ?>
                <div class="txt-center">Thank you. Visit Again</div>
                <div class="txt-center">
                    <img style="width:100px;height:100px" src="<?= $base64QrImg ?>" alt="">
                </div>
            </div>
        </div>
    <?php
    }
    ?>
    <script>
        var print_btn = document.querySelector("#print");
        var back_btn = document.querySelector("#back");
        var btn_block = document.querySelector("#btn-block");

        function hideall() {
            print_btn.style.display = "none";
            back_btn.style.display = "none";
            btn_block.style.display = "none";
            var is_chrome = function() {
                return Boolean(window.chrome);
            }
            if (is_chrome) {
                window.print();
                setTimeout(function() {
                    window.close();
                }, 10000);
                //give them 10 seconds to print, then close
            } else {
                window.print();
                window.close();
            }
        }

        window.onafterprint = function() {
            print_btn.style.display = "";
            back_btn.style.display = "";
            btn_block.style.display = "";
        }

        jQuery(document).bind("keyup keydown", function(e) {
            if (e.ctrlKey && e.keyCode == 80) {
                print_btn.style.display = "none";
                back_btn.style.display = "none";
                btn_block.style.display = "none";
            }
        });

        print_btn.addEventListener("click", function(e) {
            hideall();
        });

        back_btn.addEventListener("click", function(e) {
            javascript: history.go(-1);
            window.location.href("<?= base_url('Cashier/order/listRecords') ?>");
        });
    </script>
</body>

</html>