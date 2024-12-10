<div class="tab-content bg-light kitorder" id="myTabContent">
	<div class="container tab-pane fade show active" id="allcat" role="tabpanel" aria-labelledby="home-tab">
		<div class="ref row"  style="position:relative; z-index:10;">
			<?php 
			if($orderDetails){ 
			foreach($orderDetails->result() as $row) {
				if($row->orderStatus=='PND'){
					$orderStatus = 'Pending';
				}elseif($row->orderStatus=='PRO'){
					$orderStatus = 'Processing';
				}elseif($row->orderStatus=='RTS'){
					$orderStatus = 'Ready to serve';
				}
				?>
			<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4 col-p-3 d-inblock">
				<div class="modalheader bg-info container">
					<div class="food_item_top">
						<div class="row mb-2 item_inner">
							<h4 class="col-md-6 kf_info text-left"><strong>Table No. :</strong></h4>
							<h4 class="col-md-6 kf_info text-right"><strong><?= $row->tableNumber ?></strong></h4>
							<h4 class="col-md-6 kf_info text-left"><strong>Order No. :</strong></h4>
							<h4 class="col-md-6 kf_info text-right"><strong><?= $row->code ?></strong></h4>
							<h4 class="col-md-6 kf_info text-left"><strong>Order Status:</strong></h4>
							<h4 class="col-md-6 kf_info text-right"><strong><?= $orderStatus ?></strong></h4>
							<h4 class="col-md-6 kf_info text-left"><strong>Customer:</strong></h4>
							<h4 class="col-md-6 kf_info text-right"><strong><?= $row->name ?></strong></h4>
							<h4 class="col-md-6 kf_info text-left"><strong>Date :</strong></h4>
							<h4 class="col-md-6 kf_info text-right"><strong><?= date('d-m-Y h:i A',strtotime($row->addDate))?></strong></h4>
						</div>
					</div>
                </div>
				<div class="cooking_time">
                    <h4 class="kf_info">Cooking Time: <?= $row->totalpreparationTime ?> m</h4>
                </div>
				<div class="modal-body kitbody">
					<form class="extablk" method="post">
						<div class="lastdiv"> 
							<?php 
							if($row->orderStatus=='PND') { ?>
								<a href="<?= base_url() ?>order/edit/<?= $row->code?>" class="btn btn-success lng sub_1" id="custorder">Edit</a>
							<?php } ?>
							<?php 
							if($row->orderStatus=='RTS') { ?>
								<a class="btn btn-success lng sub_1 approve" id="custorder" data-id="<?= $row->code?>">Confirm</a>
							<?php } ?>
                            <a class="btn btn-success lng sub_1 orderDetails" id="custorder" data-seq="<?= $row->code?>">Order Details</a>
                        </div> 
                    </form>
				</div>
            </div>
			<?php } 
			}else{ ?>
			<h5 class="text-center">No orders found</h5>	
			<?php } ?>
		</div>
    </div>
</div>
 <div class="modal fade text-left" id="generl_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-modal="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl " role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="page-heading">
                        <div class="page-title">
                            <div class="row">
                                <div class="col-12 col-md-6 order-md-1 order-last">
                                    <h3>Orders</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-3 mb-2" id="rightdiv">
                            <div class="btn-group btn-group-sm " role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-primary sub_1" onclick="printDiv('print_div')">Print</button>


                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-12 mx-auto panel-body" id="print_div">

                    </div>
                </div>
            </div>
        </div>
    </div>