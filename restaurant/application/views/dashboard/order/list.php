<link href="<?= base_url("assets/init_site/orderstyle.css") ?>" rel="stylesheet" />

<div class="container" style="min-height:500px;">
    <div class="row my-2">
        <div class="col-7">
            <h5> Counter Orders </h5>
        </div>
        <div class="col-5 text-end">
            <a class="btn btn-sm btn-primary mx-1" href="<?= base_url('order/add') ?>">  <i class="fa fa-plus-circle"></i> New Order</a>
            <a class="btn btn-sm btn-outline-dark mx-1" href="<?= base_url('branch/listRecords') ?>"> <i class="fa fa-angle-left"></i> Back </a>
        </div>
    </div>
    <div class="row g-2">
        <div class="col-md-12">
            <div class="mb-2"><b class="card-title">On-going Orders </b> <i class="fa fa-spinner spin text-primary" id="order-loader"></i></div>
            <div class="row g-3" id="kot-orders-row"></div>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="generl_modal" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="kotId"></h5>
				<button type="button" class="close">×</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-body">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url("assets/init_site/orderlist.js") ?>"></script>