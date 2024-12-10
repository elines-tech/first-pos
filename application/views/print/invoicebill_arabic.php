<!DOCTYPE html>
<html lang="en">

<head>
</head>

<body>
    <?php
    $seller = [
        "name" => "TKMN SOFTWARE",
        "vatno" => "1234557483003"
    ];
    $sellerAddress = [
        "buildingNo" => 12,
        "streetName" =>  "AT TAKHASSUSI ROAD,ALMATHAR ASH SHAMALI ",
        "district" => "CENTRAL PROVINCE",
        "city" => "Riyadh",
        "postalCode" =>  12332,
        "country" => 966,
    ];
    $payment = $payment->result_array()[0];
    $clientAddress = json_decode($client['address'], true);
    ?>
    <div id="invoice-wrap" style="width:800px;margin:0 auto;border:2px solid silver;padding:10px">
        <table style="width:100%;border-collapse:collapse">
            <td width="8%">
                <img class="img-qrcode" src="<?= $base64QrImg ?>" alt="QRCODE" style="width:100px;height:100px" />
            </td>
            <td width="30%">
            </td>
            <td width="62%">
                <h3 style="text-align:center">TAX INVOICE</h3>
                <table style="width:100%;border-collapse:collapse">
                    <tr>
                        <td width="50%" style="text-align: center;">Invoice Issue Date</td>
                        <td width="50%" style="text-align: center;">Invoice Number</td>
                    </tr>
                    <tr>
                        <th><?= date('Y/m/d h:i A', strtotime($payment['addDate'])) ?></th>
                        <th><?= $payment['receiptId'] ?></th>
                    </tr>
                </table>
            </td>
        </table>
        <div style="width:100%;height:10px"></div>
        <table style="width:100%;border-collapse:collapse;font-size:0.9rem">
            <thead style="background-color: #c3c3c3;">
                <tr>
                    <th width="16.66%" style="text-align:left;border:1px solid silver;padding:5px;">Seller</th>
                    <th width="16.66%" style="border:1px solid silver;padding:5px;"></th>
                    <th width="16.66%" style="text-align:right;border:1px solid silver;padding:5px;">البائع</th>
                    <th width="16.66%" style="text-align:left;border:1px solid silver;padding:5px;">Buyer</th>
                    <th width="16.66%" style="border:1px solid silver;padding:5px;"></th>
                    <th width="16.66%" style="text-align:right;border:1px solid silver;padding:5px;">مشتر</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding:2px;border: 1px solid silver;">Name</td>
                    <td style="padding:2px;border: 1px solid silver;"><?= $seller['name'] ?></td>
                    <td style="padding:2px;text-align:right;border:1px solid silver; ">اسم</td>
                    <td style="padding:2px;border: 1px solid silver;">Name</td>
                    <td style="padding:2px;border: 1px solid silver;"><?= $client['name'] ?></td>
                    <td style="padding:2px;text-align:right;border:1px solid silver; ">اسم</td>
                </tr>
                <tr>
                    <td style="padding:2px;border: 1px solid silver;">Building No</td>
                    <td style="padding:2px;border: 1px solid silver;"><?= $sellerAddress['buildingNo'] ?></td>
                    <td style="padding:2px;text-align:right;border:1px solid silver; ">رقم المبنى</td>
                    <td style="padding:2px;border: 1px solid silver;">Building No</td>
                    <td style="padding:2px;border: 1px solid silver;"><?= $clientAddress['buildingNo'] ?></td>
                    <td style="padding:2px;text-align:right;border:1px solid silver; ">رقم المبنى</td>
                </tr>
                <tr>
                    <td style="padding:2px;border: 1px solid silver;">Street Name</td>
                    <td style="padding:2px;border: 1px solid silver;"><?= $sellerAddress['streetName'] ?></td>
                    <td style="padding:2px;text-align:right;border:1px solid silver; ">اسم الشارع</td>
                    <td style="padding:2px;border: 1px solid silver;">Street Name</td>
                    <td style="padding:2px;border: 1px solid silver;"><?= $clientAddress['streetName'] ?></td>
                    <td style="padding:2px;text-align:right;border:1px solid silver; ">اسم الشارع</td>
                </tr>
                <tr>
                    <td style="padding:2px;border: 1px solid silver;">District</td>
                    <td style="padding:2px;border: 1px solid silver;"><?= $sellerAddress['district'] ?></td>
                    <td style="padding:2px;text-align:right;border:1px solid silver; ">يصرف</td>
                    <td style="padding:2px;border: 1px solid silver;">District</td>
                    <td style="padding:2px;border: 1px solid silver;"><?= $clientAddress['district'] ?></td>
                    <td style="padding:2px;text-align:right;border:1px solid silver; ">يصرف</td>
                </tr>
                <tr>
                    <td style="padding:2px;border: 1px solid silver;">City</td>
                    <td style="padding:2px;border: 1px solid silver;"><?= $sellerAddress['city'] ?></td>
                    <td style="padding:2px;text-align:right;border:1px solid silver; ">مدينة</td>
                    <td style="padding:2px;border: 1px solid silver;">City</td>
                    <td style="padding:2px;border: 1px solid silver;"><?= $clientAddress['city'] ?></td>
                    <td style="padding:2px;text-align:right;border:1px solid silver; ">مدينة</td>
                </tr>
                <tr>
                    <td style="padding:2px;border: 1px solid silver;">Country</td>
                    <td style="padding:2px;border: 1px solid silver;"><?= $sellerAddress['country'] == 966 ? "SAUDI ARABIA" : "UNITED ARAB EMIRATES"  ?></td>
                    <td style="padding:2px;text-align:right;border:1px solid silver; ">دولة</td>
                    <td style="padding:2px;border: 1px solid silver;">Country</td>
                    <td style="padding:2px;border: 1px solid silver;"><?= $clientAddress['country'] == 966 ? "SAUDI ARABIA" : "UNITED ARAB EMIRATES"  ?></td>
                    <td style="padding:2px;text-align:right;border:1px solid silver; ">دولة</td>
                </tr>
                <tr>
                    <td style="padding:2px;border: 1px solid silver;">Postal Code</td>
                    <td style="padding:2px;border: 1px solid silver;"><?= $sellerAddress['postalCode'] ?></td>
                    <td style="padding:2px;text-align:right;border:1px solid silver; ">رمز بريدي</td>
                    <td style="padding:2px;border: 1px solid silver;">Postal Code</td>
                    <td style="padding:2px;border: 1px solid silver;"><?= $clientAddress['postalCode'] ?></td>
                    <td style="padding:2px;text-align:right;border:1px solid silver; ">رمز بريدي</td>
                </tr>
                <tr>
                    <td style="padding:2px;border: 1px solid silver;">VAT Number</td>
                    <td style="padding:2px;border: 1px solid silver;"><?= $seller['vatno'] ?></td>
                    <td style="padding:2px;text-align:right;border:1px solid silver; ">ظريبه الشراء</td>
                    <td style="padding:2px;border: 1px solid silver;">VAT Number</td>
                    <td style="padding:2px;border: 1px solid silver;"><?= $client['vatno'] ?></td>
                    <td style="padding:2px;text-align:right;border:1px solid silver; ">ظريبه الشراء</td>
                </tr>
            </tbody>
        </table>
        <div style="width:100%;height:10px"></div>
        <table style="width:100%;border-collapse:collapse">
            <thead style="background-color: #c3c3c3;">
                <tr>
                    <th style="text-align:left;border:1px solid silver;padding:5px ">Line Items</th>
                    <th style="text-align:right;border:1px solid silver;padding:5px ">عناصر الخط</th>
                </tr>
            </thead>
        </table>
        <table style="width:100%;border-collapse:collapse">
            <thead>
                <tr>
                    <th width="15%" style="font-size:0.9rem;border:1px solid silver;text-align:center;">
                        <span>Nature of Goods or Services</span><br>
                        <span>طبيعة السلع أو الخدمات</span>
                    </th>
                    <th width="10%" style="font-size:0.9rem;border:1px solid silver;text-align:center;">
                        <span>Unit Price</span><br>
                        <span>سعر الوحدة</span>
                    </th>
                    <th width="10%" style="font-size:0.9rem;border:1px solid silver;text-align:center;">
                        <span>Quantity</span><br>
                        <span>الكميه</span>
                    </th>
                    <th width="10%" style="font-size:0.9rem;border:1px solid silver;text-align:center;">
                        <span>Taxable Amount</span><br>
                        <span>المبلغ الخاضع للضريبة</span>
                    </th>
                    <th width="10%" style="font-size:0.9rem;border:1px solid silver;text-align:center;">
                        <span>Discount</span><br>
                        <span>خصم</span>
                    </th>
                    <th width="10%" style="font-size:0.9rem;border:1px solid silver;text-align:center;">
                        <span>Tax Rate</span><br>
                        <span>معدل الضريبة</span>
                    </th>
                    <th width="10%" style="font-size:0.9rem;border:1px solid silver;text-align:center;">
                        <span>Tax Amount</span><br>
                        <span>مبلغ الضريبة</span>
                    </th>
                    <th width="15%" style="font-size:0.9rem;border:1px solid silver;text-align:right;">
                        <span>Item Subtotal (Including VAT)</span><br>
                        <span>المجموع الفرعي للبند (بما في ذلك ضريبة القيمة المضافة)</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $planconfig = json_decode($payment['planconfig'], true);
                if ($payment["type"] == "subscription") {
                    $taxValue = number_format($payment['basicCharge'] * ($planconfig['tax'] * 0.01), 2, ".", "");
                    $row = '<tr>';
                    $row .= '<td style="padding:2px;text-align:left;border:1px solid silver;">' . ucwords($payment['category']) . ' Subscription </td>';
                    if ($payment['period'] == "year") {
                        $row .= '<td style="padding:2px;text-align:right;border:1px solid silver;">' . $planconfig['yearlychargesextax'] . '</td>';
                    } else {
                        $row .= '<td style="padding:2px;text-align:right;border:1px solid silver;">' . $planconfig['monthlychargesextax'] . '</td>';
                    }
                    $row .= '<td style="padding:2px;text-align:right;border:1px solid silver;">1</td>';
                    if ($payment['period'] == "year") {
                        $row .= '<td style="padding:2px;text-align:right;border:1px solid silver;">' . $planconfig['yearlychargesextax'] . '</td>';
                    } else {
                        $row .= '<td style="padding:2px;text-align:right;border:1px solid silver;">' . $planconfig['monthlychargesextax'] . '</td>';
                    }
                    $row .= '<td style="padding:2px;text-align:right;border:1px solid silver;">0</td>';
                    $row .= '<td style="padding:2px;text-align:right;border:1px solid silver;">' . $planconfig['tax'] . '</td>';
                    $row .= '<td style="padding:2px;text-align:right;border:1px solid silver;">' . $taxValue . '</td>';
                    if ($payment['period'] == "year") {
                        $row .= '<td style="padding:2px;text-align:right;border:1px solid silver;">' . $planconfig['yearlychargesincltax'] . '</td>';
                    } else {
                        $row .= '<td style="padding:2px;text-align:right;border:1px solid silver;">' . $planconfig['monthlychargesincltax'] . '</td>';
                    }
                    $row .= '</tr>';
                    echo $row;


                    if ($payment['addonUsers'] != "0") {
                        $a = number_format($planconfig['costperuser']  * $payment['addonUsers'], 2, ".", "");
                        $ta = number_format($a * ($planconfig['tax'] / 100), 2, ".", "");
                        $row1 = '<tr>';
                        $row1 .= '<td style="padding:2px;text-align:left;border:1px solid silver;">Addon Users</td>';
                        $row1 .= '<td style="padding:2px;text-align:right;border:1px solid silver;">' . $planconfig['costperuser'] . '</td>';
                        $row1 .= '<td style="padding:2px;text-align:right;border:1px solid silver;">' . $payment['addonUsers'] . '</td>';
                        $row1 .= '<td style="padding:2px;text-align:right;border:1px solid silver;">' . $a . '</td>';
                        $row1 .= '<td style="padding:2px;text-align:right;border:1px solid silver;">0</td>';
                        $row1 .= '<td style="padding:2px;text-align:right;border:1px solid silver;">' . $planconfig['tax'] . '</td>';
                        $row1 .= '<td style="padding:2px;text-align:right;border:1px solid silver;">' . $ta . '</td>';
                        $row1 .= '<td style="padding:2px;text-align:right;border:1px solid silver;">' . $payment['addonUserPrice'] . '</td>';
                        $row1 .= '</tr>';
                        echo $row1;
                    }

                    if ($payment['addonBranches'] != "0") {
                        $b = number_format($planconfig['costperbranch']  * $payment['addonBranches'], 2, ".", "");
                        $ta = number_format($a * ($planconfig['tax'] / 100), 2, ".", "");
                        $row2 = '<tr>';
                        $row2 .= '<td style="padding:2px;text-align:left;border:1px solid silver;">Addon Users</td>';
                        $row2 .= '<td style="padding:2px;text-align:right;border:1px solid silver;">' . $planconfig['costperbranch'] . '</td>';
                        $row2 .= '<td style="padding:2px;text-align:right;border:1px solid silver;">' . $payment['addonBranches'] . '</td>';
                        $row2 .= '<td style="padding:2px;text-align:right;border:1px solid silver;">' . $b . '</td>';
                        $row2 .= '<td style="padding:2px;text-align:right;border:1px solid silver;">0</td>';
                        $row2 .= '<td style="padding:2px;text-align:right;border:1px solid silver;">' . $planconfig['tax'] . '</td>';
                        $row2 .= '<td style="padding:2px;text-align:right;border:1px solid silver;">' . $ta . '</td>';
                        $row2 .= '<td style="padding:2px;text-align:right;border:1px solid silver;">' . $payment['addonBranchPrice'] . '</td>';
                        $row2 .= '</tr>';
                        echo $row2;
                    }
                } else if ($payment["type"] == "addon") {
                    if ($payment['addonUsers'] != "0") {
                        $a = number_format($planconfig['costperuser']  * $payment['addonUsers'], 2, ".", "");
                        $ta = number_format($a * ($planconfig['tax'] / 100), 2, ".", "");
                        $row1 = '<tr>';
                        $row1 .= '<td style="padding:2px;text-align:left;border:1px solid silver;">Addon Users</td>';
                        $row1 .= '<td style="padding:2px;text-align:right;border:1px solid silver;">' . $planconfig['costperuser'] . '</td>';
                        $row1 .= '<td style="padding:2px;text-align:right;border:1px solid silver;">' . $payment['addonUsers'] . '</td>';
                        $row1 .= '<td style="padding:2px;text-align:right;border:1px solid silver;">' . $a . '</td>';
                        $row1 .= '<td style="padding:2px;text-align:right;border:1px solid silver;">0</td>';
                        $row1 .= '<td style="padding:2px;text-align:right;border:1px solid silver;">' . $planconfig['tax'] . '</td>';
                        $row1 .= '<td style="padding:2px;text-align:right;border:1px solid silver;">' . $ta . '</td>';
                        $row1 .= '<td style="padding:2px;text-align:right;border:1px solid silver;">' . $payment['addonUserPrice'] . '</td>';
                        $row1 .= '</tr>';
                        echo $row1;
                    }

                    if ($payment['addonUsers'] != "0") {
                        $row2 = "";
                        echo $row2;
                    }
                }
                ?>
            </tbody>
        </table>
        <div style="width:100%;height:10px"></div>
        <table style="width:100%;border-collapse:collapse">
            <thead style="background-color: #c3c3c3;">
                <tr>
                    <th style="text-align:left;border:1px solid silver;padding:5px" colspan="2">Total Amounts</th>
                    <th style="text-align:right;border:1px solid silver;padding:5px" colspan="2">إجمالي المبالغ</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding:2px;text-align:right;border:1px solid silver" width="30%"></td>
                    <td style="padding:2px;text-align:left;border:1px solid silver" width="25%">Total (Exlcuding VAT)</td>
                    <td style="padding:2px;text-align:right;border:1px solid silver" width="25%">الإجمالي (باستثناء الضرائب)</td>
                    <td style="padding:2px;text-align:right;border:1px solid silver" width="20%"><?= $payment['subtotal'] ?></td>
                </tr>
                <tr>
                    <td style="padding:2px;text-align:right;border:1px solid silver" width="30%"></td>
                    <td style="padding:2px;text-align:left;border:1px solid silver" width="25%">Discount</td>
                    <td style="padding:2px;text-align:right;border:1px solid silver" width="25%">تخفيض</td>
                    <td style="padding:2px;text-align:right;border:1px solid silver" width="20%">0.00</td>
                </tr>
                <tr>
                    <td style="padding:2px;text-align:right;border:1px solid silver" width="30%"></td>
                    <td style="padding:2px;text-align:left;border:1px solid silver" width="25%">Total Taxable Amount (Exlcuding VAT)</td>
                    <td style="padding:2px;text-align:right;border:1px solid silver" width="25%">إجمالي المبلغ الخاضع للضريبة (باستثناء ضريبة القيمة المضافة)</td>
                    <td style="padding:2px;text-align:right;border:1px solid silver" width="20%"><?= $payment['subtotal'] ?></td>
                </tr>
                <tr>
                    <td style="padding:2px;text-align:right;border:1px solid silver" width="30%"></td>
                    <td style="padding:2px;text-align:left;border:1px solid silver" width="25%">Total VAT</td>
                    <td style="padding:2px;text-align:right;border:1px solid silver" width="25%">إجمالي ضريبة القيمة المضافة</td>
                    <td style="padding:2px;text-align:right;border:1px solid silver" width="20%"><?= $payment['taxtotal'] ?></td>
                </tr>
                <tr>
                    <td style="padding:2px;text-align:right;border:1px solid silver" width="30%"></td>
                    <td style="padding:2px;text-align:left;border:1px solid silver" width="25%">Total Amount Due</td>
                    <td style="padding:2px;text-align:right;border:1px solid silver" width="25%">إجمالي المبلغ المستحق</td>
                    <td style="padding:2px;text-align:right;border:1px solid silver" width="20%"><b><?= $payment['amount'] ?></b></td>
                </tr>
            </tbody>
        </table>

    </div>
</body>

</html>