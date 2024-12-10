<?php
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        .bgcol {
            background-color: #c3c3c3;
        }
    </style>
</head>

<body>
    <?php
    $seller = [
        "name" => $company['cmpname'],
        "vatno" => $company['cmpvatno']
    ];
    $sellerAddress = json_decode($company['address'], true);
    $clientAddress = json_decode($client['address'], true);
    ?>
    <div id="invoice-wrap" style="width:800px;margin:0 auto;padding:10px">
        <table style="width:100%;border-collapse:collapse">
            <thead>
                <tr>
                    <th style="width:8%"><img src="<?= $base64QrImg ?>" alt="" width="100px"></th>
                    <th style="width:30%"></th>
                    <th style="width:62%">
                        <div style="text-align:center;width:100%;font-size:1.0rem;">TAX INVOCIE</div>
                        <table style="width:100%;border-collapse:collapse">
                            <thead>
                                <tr>
                                    <th style="width:50%;text-align:center;font-size:0.8rem;">Invoice Issue Date</th>
                                    <th style="width:50%;text-align:center;font-size:0.8rem;">Invoice Number</th>
                                </tr>
                                <tr>
                                    <th><?= date('Y/m/d h:i A', strtotime($payment['addDate'])) ?></th>
                                    <th><?= $payment['receiptId'] ?></th>
                                </tr>
                            </thead>
                        </table>
                    </th>
                </tr>
            </thead>
        </table>

        <div style="width:100%;height:10px"></div>
        <table style="width:100%;border-collapse:collapse;font-size:0.9rem">
            <thead class="bgcol" style="background-color: #c3c3c3;">
                <tr>
                    <th width="16.66%" style="text-align:left;border:1px solid silver;padding:5px;">Seller</th>
                    <th width="16.66%" style="border:1px solid silver;padding:5px;"></th>
                    <th width="16.66%" style="text-align:right;border:1px solid silver;padding:5px;"></th>
                    <th width="16.66%" style="text-align:left;border:1px solid silver;padding:5px;">Buyer</th>
                    <th width="16.66%" style="border:1px solid silver;padding:5px;"></th>
                    <th width="16.66%" style="text-align:right;border:1px solid silver;padding:5px;"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding:2px;border: 1px solid silver;">Name</td>
                    <td style="padding:2px;border: 1px solid silver;"><?= $seller['name'] ?></td>
                    <td style="padding:2px;text-align:right;border:1px solid silver;"></td>
                    <td style="padding:2px;border: 1px solid silver;">Name</td>
                    <td style="padding:2px;border: 1px solid silver;"><?= $client['name'] ?></td>
                    <td style="padding:2px;text-align:right;border:1px solid silver;"></td>
                </tr>
                <tr>
                    <td style="padding:2px;border: 1px solid silver;">Building No</td>
                    <td style="padding:2px;border: 1px solid silver;"><?= $sellerAddress['buildingNo'] ?></td>
                    <td style="padding:2px;text-align:right;border:1px solid silver;"></td>
                    <td style="padding:2px;border: 1px solid silver;">Building No</td>
                    <td style="padding:2px;border: 1px solid silver;"><?= $clientAddress['buildingNo'] ?></td>
                    <td style="padding:2px;text-align:right;border:1px solid silver;"></td>
                </tr>
                <tr>
                    <td style="padding:2px;border: 1px solid silver;">Street Name</td>
                    <td style="padding:2px;border: 1px solid silver;"><?= $sellerAddress['streetName'] ?></td>
                    <td style="padding:2px;text-align:right;border:1px solid silver;"></td>
                    <td style="padding:2px;border: 1px solid silver;">Street Name</td>
                    <td style="padding:2px;border: 1px solid silver;"><?= $clientAddress['streetName'] ?></td>
                    <td style="padding:2px;text-align:right;border:1px solid silver;"></td>
                </tr>
                <tr>
                    <td style="padding:2px;border: 1px solid silver;">District</td>
                    <td style="padding:2px;border: 1px solid silver;"><?= $sellerAddress['district'] ?></td>
                    <td style="padding:2px;text-align:right;border:1px solid silver;"></td>
                    <td style="padding:2px;border: 1px solid silver;">District</td>
                    <td style="padding:2px;border: 1px solid silver;"><?= $clientAddress['district'] ?></td>
                    <td style="padding:2px;text-align:right;border:1px solid silver;"></td>
                </tr>
                <tr>
                    <td style="padding:2px;border: 1px solid silver;">City</td>
                    <td style="padding:2px;border: 1px solid silver;"><?= $sellerAddress['city'] ?></td>
                    <td style="padding:2px;text-align:right;border:1px solid silver;"></td>
                    <td style="padding:2px;border: 1px solid silver;">City</td>
                    <td style="padding:2px;border: 1px solid silver;"><?= $clientAddress['city'] ?></td>
                    <td style="padding:2px;text-align:right;border:1px solid silver;"></td>
                </tr>
                <tr>
                    <td style="padding:2px;border: 1px solid silver;">Country</td>
                    <td style="padding:2px;border: 1px solid silver;"><?= $sellerAddress['country'] == 966 ? "SAUDI ARABIA" : "UNITED ARAB EMIRATES"  ?></td>
                    <td style="padding:2px;text-align:right;border:1px solid silver;"></td>
                    <td style="padding:2px;border: 1px solid silver;">Country</td>
                    <td style="padding:2px;border: 1px solid silver;"><?= $clientAddress['country'] == 966 ? "SAUDI ARABIA" : "UNITED ARAB EMIRATES"  ?></td>
                    <td style="padding:2px;text-align:right;border:1px solid silver;"></td>
                </tr>
                <tr>
                    <td style="padding:2px;border: 1px solid silver;">Postal Code</td>
                    <td style="padding:2px;border: 1px solid silver;"><?= $sellerAddress['postalCode'] ?></td>
                    <td style="padding:2px;text-align:right;border:1px solid silver;"></td>
                    <td style="padding:2px;border: 1px solid silver;">Postal Code</td>
                    <td style="padding:2px;border: 1px solid silver;"><?= $clientAddress['postalCode'] ?></td>
                    <td style="padding:2px;text-align:right;border:1px solid silver;"></td>
                </tr>
                <tr>
                    <td style="padding:2px;border: 1px solid silver;">VAT Number</td>
                    <td style="padding:2px;border: 1px solid silver;"><?= $seller['vatno'] ?></td>
                    <td style="padding:2px;text-align:right;border:1px solid silver; "></td>
                    <td style="padding:2px;border: 1px solid silver;">VAT Number</td>
                    <td style="padding:2px;border: 1px solid silver;"><?= $client['vatno'] ?></td>
                    <td style="padding:2px;text-align:right;border:1px solid silver; "></td>
                </tr>
            </tbody>
        </table>
        <div style="font-size:0.9rem;width:100%">
            <div style="width:100%;height:10px"></div>
            <table style="width:100%;border-collapse:collapse">
                <thead style="background-color: #c3c3c3;">
                    <tr>
                        <th style="text-align:left;border:1px solid silver;padding:5px ">Line Items</th>
                        <th style="text-align:right;border:1px solid silver;padding:5px "></th>
                    </tr>
                </thead>
            </table>
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr>
                        <th width="15%" style="border:1px solid silver;text-align:center;">
                            <span>Nature of Goods or Services</span><br>
                        </th>
                        <th width="10%" style="border:1px solid silver;text-align:center;">
                            <span>Unit Price</span><br>
                        </th>
                        <th width="10%" style="border:1px solid silver;text-align:center;">
                            <span>Quantity</span><br>
                        </th>
                        <th width="10%" style="border:1px solid silver;text-align:center;">
                            <span>Taxable Amount</span><br>
                        </th>
                        <th width="10%" style="border:1px solid silver;text-align:center;">
                            <span>Discount</span><br>
                        </th>
                        <th width="10%" style="border:1px solid silver;text-align:center;">
                            <span>Tax Rate</span><br>
                        </th>
                        <th width="10%" style="border:1px solid silver;text-align:center;">
                            <span>Tax Amount</span><br>
                        </th>
                        <th width="15%" style="border:1px solid silver;text-align:center;">
                            <span>Item Subtotal (Including VAT)</span><br>
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
                            $row2 .= '<td style="padding:2px;text-align:left;border:1px solid silver;">Addon Branches</td>';
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

                        if ($payment['addonBranches'] != "0") {
                            $b = number_format($planconfig['costperbranch']  * $payment['addonBranches'], 2, ".", "");
                            $ta = number_format($a * ($planconfig['tax'] / 100), 2, ".", "");
                            $row2 = '<tr>';
                            $row2 .= '<td style="padding:2px;text-align:left;border:1px solid silver;">Addon Branches</td>';
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
                    }
                    ?>
                </tbody>
            </table>
            <div style="width:100%;height:10px"></div>
            <table style="width:100%;border-collapse:collapse">
                <thead style="background-color: #c3c3c3;">
                    <tr>
                        <th style="text-align:left;border:1px solid silver;padding:5px" colspan="2">Total Amounts</th>
                        <th style="text-align:right;border:1px solid silver;padding:5px" colspan="2"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding:2px;text-align:right;border:1px solid silver" width="30%"></td>
                        <td style="padding:2px;text-align:left;border:1px solid silver" width="25%">Total (Exlcuding VAT)</td>
                        <td style="padding:2px;text-align:right;border:1px solid silver" width="25%"></td>
                        <td style="padding:2px;text-align:right;border:1px solid silver" width="20%"><?= $payment['subtotal'] ?></td>
                    </tr>
                    <tr>
                        <td style="padding:2px;text-align:right;border:1px solid silver" width="30%"></td>
                        <td style="padding:2px;text-align:left;border:1px solid silver" width="25%">Discount</td>
                        <td style="padding:2px;text-align:right;border:1px solid silver" width="25%"></td>
                        <td style="padding:2px;text-align:right;border:1px solid silver" width="20%">0.00</td>
                    </tr>
                    <tr>
                        <td style="padding:2px;text-align:right;border:1px solid silver" width="30%"></td>
                        <td style="padding:2px;text-align:left;border:1px solid silver" width="25%">Total Taxable Amount (Exlcuding VAT)</td>
                        <td style="padding:2px;text-align:right;border:1px solid silver" width="25%"></td>
                        <td style="padding:2px;text-align:right;border:1px solid silver" width="20%"><?= $payment['subtotal'] ?></td>
                    </tr>
                    <tr>
                        <td style="padding:2px;text-align:right;border:1px solid silver" width="30%"></td>
                        <td style="padding:2px;text-align:left;border:1px solid silver" width="25%">Total VAT</td>
                        <td style="padding:2px;text-align:right;border:1px solid silver" width="25%"></td>
                        <td style="padding:2px;text-align:right;border:1px solid silver" width="20%"><?= $payment['taxtotal'] ?></td>
                    </tr>
                    <tr>
                        <td style="padding:2px;text-align:right;border:1px solid silver" width="30%"></td>
                        <td style="padding:2px;text-align:left;border:1px solid silver" width="25%">Total Amount Due</td>
                        <td style="padding:2px;text-align:right;border:1px solid silver" width="25%"></td>
                        <td style="padding:2px;text-align:right;border:1px solid silver" width="20%"><b><?= $payment['amount'] ?></b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>