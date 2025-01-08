<?php include '../supermarket/config.php'; ?>

<div class="container-fluid">
    <div class="row  mt-4">
        <div class="col-md-10">
            <div>
                <?php if ($inwardData) {
					$lineData = $inwardLineEntries->result_array()[0];
                    $result = $inwardData->result_array()[0];
                ?>
                    <strong class="font_size_mod lng"><?php echo $translations['Transfer #']?></strong> : <strong class="font_size_mod "><?= $result['code'] ?></strong><br>
                    <strong class="font_size_mod lng"><?php echo $translations['Date:']?></strong> : <strong class="font_size_mod "><?= date('d-m-Y', strtotime($result['inwardDate'])) ?></strong><br>
                    <strong class="font_size_mod lng"><?php echo $translations['From Branch / Batch:']?></strong> : <strong class="font_size_mod "><?= $result['fromBranchName'] .' / '.$lineData['fromBatchNo']?></strong><br>
                    <strong class="font_size_mod lng"><?php echo $translations['To Branch / Batch:']?></strong> : <strong class="font_size_mod "><?= $result['toBranchName'] .' / '.$result['batchNo']?></strong><br>
                <?php } ?>
            </div>
        </div>

    </div>
</div>

<table class="table table-bordered product-quotation-list mt-5">
    <thead>
        <tr>
            <th class="lng"><?php echo $translations['Batch']?></th>
            <th class="lng"><?php echo $translations['Product']?></th>
			<th class="lng"><?php echo $translations['Qty']?></th>
            <th class="lng"><?php echo $translations['Price']?></th>
            <th class="lng"><?php echo $translations['Unit']?></th>
            <th class="lng"><?php echo $translations['Sub Total']?></th>
        </tr>
    </thead>
    <tbody id="v_tbody">
        <?php
        $i = 0;
        if ($inwardLineEntries) {
            foreach ($inwardLineEntries->result_array() as $co) {
                $i++;
        ?>
                <tr>
					<td><?= $co['fromBatchNo']?></td>
                    <td><?= $co['proNameVarName']?></td>
                    <td><?= $co['productQty'] ?></td>
					 <td><?= $co['productPrice'] ?></td>
                    <td><?= $co['unitName'] ?> </td>
                    <td><?= $co['subTotal'] ?></td>
                </tr>
        <?php }
        } ?>
    </tbody>

</table>

<div class="overflow-hidden">
    <div class="col-lg-12 col-xl-12 p-0">

        <div class="row justify-content-end">
            <div class="col-12 col-md-3">
                <div>
                    <table class="table right-table mb-0">

                        <tbody>
                            <tr class="">
                                <th class="border-0 font-size-h5 mb-0 font-size-bold lng"><?php echo $translations['Total']?></th>
                                <th class="border-0 d-flex font-size-base"><?= $result['total'] ?></th>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 d-flex justify-content-end">

                <button id="cancelDefaultButton" type="button" class="btn btn-light-secondary" data-bs-dismiss="modal"><?php echo $translations['Close']?></button>
            </div>
        </div>

    </div>

</div>