<link href="<?= base_url("assets/init_site/orderstyle.css") ?>" rel="stylesheet" />

<div class="row mt-2">
	<div class="col-3">
	</div>
	<?php
		$result = $recipeDeatils->result()[0];
		$productImage = 'assets/food.png';
		if($result->productImage!='' && $result->productImage!=NULL){
			$productImage=$result->productImage;
		}
	?>
    <div class="col-6">
        <div class="card">
			<div class="card-header">
				<h2 style="border-bottom:2px solid black"><i><?= $result->productEngName?></i><p style="font-size:13px;"><?= $result->categoryName.'( '.$result->categorySName.' )'.', '.$result->subcategoryName.'( '.$result->subcategorySName.' )'?></p><span style="font-size:18px;float:right;color:red">Preparing Minutes : <?= $result->preparationTime?></span></h2>
			</div>
            <div class="card-body">
                <div class="row mt-1" >
					<div class="col-12" style="text-align:center">
						<img src="<?= base_url().$productImage ?>" style="width:700px;height:350px;border-radius:10px" alt="Product Image">
					</div>
				</div>
				<div class="row mt-2">
					<div class="col-12"><h3><i>About : </i></h3><p style="font-size:17px;"><?= $result->productEngDesc?></p></div>
					<div class="col-12">
						<h3><i>Ingrdients:</i></h3>
						<ul>
							<?php
								if(${$productCode}){
									foreach(${$productCode}->result() as $rcl){
										echo '<li>'.$rcl->itemQty.' '.$rcl->unitSName.' '.$rcl->itemEngName.'</li>';
									}
								}
							?>
						</ul>
					</div>
				</div>
				<div class="row mt-1">
					<div class="col-12">
						<h3><i>Directions:</i></h3>
						<div class="col-12" style="margin-left:15px;"><?= $result->recipeDirection ?></div>
					</div>
				</div>
            </div>
        </div>
    </div>
	<div class="col-3"></div>
</div>
