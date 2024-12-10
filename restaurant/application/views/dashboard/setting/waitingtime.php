<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Waiting Times</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><i class="fa fa-dashboard"></i> Dashboard</li>
                            <li class="breadcrumb-item active" aria-current="page">Settings</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="row match-height">
                <div class="col-12 mb-3">
                    <a href="<?= base_url("setting/listRecords") ?>" class="btn btn-info float-end">Back</a>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <?php if ($this->session->flashdata("err")) {
                                    $msg = $this->session->flashdata("err");
                                ?>
                                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                                        <i class="fa fa-times"></i>
                                        <div> <?= $msg ?></div>
                                    </div>
                                <?php
                                }
                                ?>
                                <div class="row">
                                    <form class="form" id="editUserForm" enctype="multipart/form-data" data-parsley-validate method="post" action="<?php echo base_url('setting/update_waitingtime'); ?>">
                                        <?php
                                        $settingcode = "STG_2";
                                        $settingValue = $settingName = "";
                                        if ($setting) {
                                            $setting = $setting->result()[0];
                                            $settingName = $setting->settingName;
                                            $settingValue = $setting->settingValue;
                                        }
                                        ?>
                                        <input type="hidden" id="settingCode" readonly name="settingCode" class="form-control" value="<?= $settingCode ?>">
                                        <div class="row">
                                            <div class="col-md-4 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="arabicname-column" class="form-label">Waiting Time (seconds)</label>
                                                    <input type="text" id="settingValue" minlength="2" maxlength="4" min="45" max="3600" class="form-control" placeholder="User Name" name="settingValue" value="<?= $settingValue ?>" data-parsley-required="true">
                                                </div>
                                                <?php echo form_error('settingValue', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-success white me-1 mb-1 sub_2" id="editUser">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<script>
    $(document).ready(function() {
        var data = '<?php echo $this->session->flashdata('message'); ?>';
        if (data != '') {
            var obj = JSON.parse(data);
            if (obj.status) {
                toastr.success(obj.message, 'Waiting Time', {
                    "progressBar": true
                });
            } else {
                toastr.error(obj.message, 'Waiting Time', {
                    "progressBar": true
                });
            }
        }
    });
</script>