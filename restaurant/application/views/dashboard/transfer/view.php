<?php include '../restaurant/config.php'; ?>

<div class="container-fluid">
    <div class="row mt-2">
        <div class="col-md-10">
            <div>
                <?php if ($inwardData) {
                    $result = $inwardData->result_array()[0];
                ?>
                    <strong class="font_size_mod lng"><?php echo $translations['Transfer']?></strong> : <strong class="font_size_mod "><?= $result['code'] ?></strong><br>
                    <strong class="font_size_mod lng"><?php echo $translations['Date']?></strong> : <strong class="font_size_mod "><?= date('d/m/Y', strtotime($result['inwardDate'])) ?></strong><br>
                    <strong class="font_size_mod lng"><?php echo $translations['From Branch']?></strong> : <strong class="font_size_mod "><?= $result['fromBranchName'] ?></strong><br>
                    <strong class="font_size_mod lng"><?php echo $translations['To Branch']?></strong> : <strong class="font_size_mod "><?= $result['toBranchName'] ?></strong><br>
                <?php } ?>
            </div>
        </div>

    </div>
</div>

<table class="table table-bordered product-quotation-list mt-3">
    <thead>
        <tr>
            <th class="lng"><?php echo $translations['Item Name']?></th>
            <th class="lng"><?php echo $translations['Price']?></th>
            <th class="lng"><?php echo $translations['Qty']?></th>
            <th class="lng"><?php echo $translations['UOM']?></th>
            <th class="text-right lng"><?php echo $translations['Sub Total']?></th>
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
                    <td>
						<?php 
						if($userLang=='Arabic'){
							$itemName = $co['itemArbName'];
						}elseif($userLang=='Hindi'){
							$itemName = $co['itemHinName'];
						}elseif($userLang=='Urdu'){
							$itemName = $co['itemUrduName'];
						}else{
							$itemName = $co['itemEngName'];
						}
						echo $itemName
						?>
                    </td>
                    <td><?= $co['itemPrice'] ?></td>
                    <td class="text-right"><?= $co['itemQty'] ?></td>
                    <td><?= $co['unitName']?></td>
                    <td class="text-right"><?= $co['subTotal'] ?></td>
                </tr>
        <?php }
        } ?>
    </tbody>

</table>

<div class="overflow-hidden">
    <div class="col-lg-12 col-xl-12 p-0">

        <div class="">
            <div class="col-12 col-md-12">
                <div style="text-align:right">
                    <table class="table right-table mb-0">
                        <tbody>
                            <tr class="d-flex align-items-center justify-content-between">
                                <th class="border-0 font-size-h5 mb-0 font-size-bold lng"><?php echo $translations['Total']?></th>
                                <td class="border-0 justify-content-end m-0 d-flex font-size-base"><?= $result['total'] ?></td>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 d-flex justify-content-end">

                <button id="cancelDefault" type="button" class="btn btn-light-secondary me-1 mb-1" data-bs-dismiss="modal"><?php echo $translations['Close']?></button>
            </div>
        </div>

    </div>

</div>