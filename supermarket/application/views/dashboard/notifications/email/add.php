<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3><?php echo $translations['Add Template']?></h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><i class="fa fa-dashboard"></i> Dashboard</li>
                            <li class="breadcrumb-item active" aria-current="page">Email Template</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <form action="<?= base_url("emailnotification/save") ?>" method="post">
            <section class="section">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                                <h5>Add new template</h5>
                            </div>
                            <div class="col-12 col-md-6 text-end">
                                <a href="<?= base_url('emailnotification/template') ?>" class="btn btn-sm btn-info">Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="print_div">
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label class="form-label lng">Subject</label>
                                <div class="form-group mandatory">
                                    <input type="text" class="form-control" id="subject" name="subject" value="">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label lng">Message</label>
                                <div class="form-group mandatory">
                                    <textarea type="date" class="form-control" id="message" name="message"></textarea>
                                </div>
                            </div>
                            <div style="text-align:center;">
                                <div class="d-flex mt-2">
                                    <button type="submit" class="btn btn-success white me-1 mb-1 sub_1" id="btnSearch">Search</button>
                                    <button type="reset" class="btn btn-light-secondary me-1 mb-1" id="btnClear">Clear</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </form>
    </div>
</div>