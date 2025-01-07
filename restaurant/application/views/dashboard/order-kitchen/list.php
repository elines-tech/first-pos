<link href="<?= base_url("assets/init_site/orderstyle.css") ?>" rel="stylesheet" />

<?php include '../restaurant/config.php'; ?>


<div id="maindiv" class="container-fluid my-2">
    <div class="row">
        <div class="col-7" id="leftdiv">
            <h5><?php echo $translations['Kitchen Orders']?></h5>
            <small><?php echo $translations['Orders will be auto refreshed in every 1 minute...']?></small>
        </div>
        <div class="col-5 align-self-center">
            <div class="d-flex no-block justify-content-end align-items-center">
                <h5 class="text-danger mx-1 mt-1 font-medium"><span id="timeOut"></span>'s</h5>
                <a id="cancelDefault" class="btn btn-sm btn-light-secondary mx-1" href="<?= base_url('Kitchen/listRecords') ?>"><i class="fa fa-angle-left"></i><?php echo $translations['Back']?></a>
            </div>
        </div>
    </div>
</div>

<div class="row g-2" style="min-height:500px;">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <input type="hidden" id="branchid" value="<?php echo $branch; ?>">
                <div class="row g-2" id="kot-Orders">
                </div>
            </div>
        </div>
    </div>
</div>




<div class="modal fade text-left" id="generl_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel130" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="kotId"></h5>
            </div>
            <div class="modal-body p-2">
                <div class="panel-body"></div>
            </div>
        </div>
    </div>
</div>


<script src="<?= base_url("assets/init_site/kitchenorderlist.js") ?>"></script>