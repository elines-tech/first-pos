<?php include '../supermarket/config.php'; ?>

<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3><?php echo $translations['Purchase Report']?></h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../../dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Purchase View</li>
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
                            <h3><?php echo $translations['Purchase View']?><span style="float:right"><a id="cancelDefaultButton" href="<?= base_url() ?>purchaseReport/getPurchaseReport" class="btn btn-sm btn-primary"><?php echo $translations['Back']?></a></span></h3>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <?php
                                if ($inwardData) {
                                    $result = $inwardData->result_array()[0];
                                ?>
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="row">
                                                <div class="col-md-3 col-12">
                                                    <div class="form-group mandatory">
                                                        <label for="" class="form-label"><?php echo $translations['Batch No']?></label>
                                                        <input type="text" id="batchNo" disabled class="form-control" name="batchNo" required disabled value="<?= $result['batchNo'] ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-12">
                                                    <div class="form-group mandatory">
                                                        <label for="" class="form-label"><?php echo $translations['Inward']?></label>
                                                        <input type="date" id="inwardDate" disabled class="form-control" name="inwardDate" id="inwardDate" value="<?= date('Y-m-d', strtotime($result['inwardDate'])) ?>" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-12">
                                                    <div class="form-group mandatory">
                                                        <label for="product-name" class="form-label"><?php echo $translations['Branch']?></label>
                                                        <input type="hidden" class="form-control" id="inwardCode" name="inwardCode" value="<?= $result['code'] ?>">
                                                        <select class="form-select select2" disabled name="branchCode" id="branchCode" data-parsley-required="true" required>
                                                            <option value="">Select</option>
                                                            <?php if ($branch) {
                                                                foreach ($branch->result() as $br) {
                                                                    if ($result['branchCode'] == $br->code) {
                                                                        echo '<option value="' . $br->code . '" selected>' . $br->branchName . '</option>';
                                                                    } else {
                                                                        echo '<option value="' . $br->code . '">' . $br->branchName . '</option>';
                                                                    }
                                                                }
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-12">
                                                    <div class="form-group mandatory">
                                                        <label for="product-name" class="form-label"><?php echo $translations['Supplier']?></label>
                                                        <select class="form-select select2" disabled name="supplierCode" id="supplierCode" data-parsley-required="true" required>
                                                            <option value="">Select</option>
                                                            <?php if ($supplier) {
                                                                foreach ($supplier->result() as $sr) {
                                                                    if ($result['supplierCode'] == $sr->code) {
                                                                        echo '<option value="' . $sr->code . '" selected>' . $sr->supplierName . '</option>';
                                                                    } else {
                                                                        echo '<option value="' . $sr->code . '">' . $sr->supplierName . '</option>';
                                                                    }
                                                                }
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 col-12">
                                                    <div class="form-group">
                                                        <label for="product-name" class="form-label"><?php echo $translations['Reference']?></label>
                                                        <input type="text" class="form-control" name="refNo" id="refNo" value="<?= $result['ref'] ?>" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 col-12">
                                                    <table id="pert_tbl" class="table table-sm table-stripped" style="width:100%;">
                                                        <thead>
                                                            <tr>
                                                                <th width="15%"><?php echo $translations['Product']?></th>
                                                                <th width="10%"><?php echo $translations['Unit']?></th>
                                                                <th width="12%"><?php echo $translations['Expiry Date']?></th>
                                                                <th width="12%"><?php echo $translations['Quantity']?></th>
                                                                <th width="15%"><?php echo $translations['Price']?></th>
                                                                <th width="12%"><?php echo $translations['Tax']?></th>
                                                                <th width="15%"><?php echo $translations['Subtotal']?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $i = 0;
                                                            if ($inwardLineEntries) {
                                                                foreach ($inwardLineEntries->result_array() as $co) {
                                                                    $i++;
                                                                    $expiryDate = '';
                                                                    if ($co['expiryDate'] != '' && $co['expiryDate'] != '0000-00-00' && $co['expiryDate'] != NULL) {
                                                                        $expiryDate = date('d/m/Y', strtotime($co['expiryDate']));
                                                                    }
                                                            ?>
                                                                    <tr id="row<?= $i ?>">
                                                                        <td><?= $co['proNameVarName'] ?></td>
                                                                        <td><?= $co['unitName'] ?></td>

                                                                        <td><?= $expiryDate ?></td>

                                                                        <td><?= $co['productQty'] ?></td>
                                                                        <td><?= $co['productPrice'] ?></td>

                                                                        <td> <?= $co['tax'] ?></td>

                                                                        <td><?= $co['subTotal'] ?></td>


                                                                    </tr>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="6" class="text-right"><b><?php echo $translations['Total']?></b></td>
                                                                <td colspan="7"><?= $result['total'] ?></td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>