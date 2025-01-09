<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Email Settings</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><i class="fa fa-dashboard"></i><?php echo $translations['Dashboard']?></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $translations['Settings']?></li>
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
                                $settingcode = "STG_1";
                                $settingName = "Email Settings";
                                $maildriver = "";
                                $host = "";
                                $port = "";
                                $username = "";
                                $password = "";
                                $encryption = "";
                                $fromaddress = "";
                                $fromname = "";
                                if ($setting) {
                                    $setting = $setting->result()[0];
                                    $settingValue = $setting->settingValue;
                                    if ($settingValue != "") {
                                        $jsonSetting = json_decode($settingValue);
                                        $maildriver = $jsonSetting->maildriver;
                                        $host = $jsonSetting->host;
                                        $port = $jsonSetting->port;
                                        $username = $jsonSetting->username;
                                        $password = $jsonSetting->password;
                                        $encryption = $jsonSetting->encryption;
                                        $fromaddress = $jsonSetting->fromaddress;
                                        $fromname = $jsonSetting->fromname;
                                    }
                                }
                                ?>
                                <div class="row">
                                    <form class="form" id="editUserForm" enctype="multipart/form-data" data-parsley-validate method="post" action="<?php echo base_url('setting/update_email'); ?>">
                                        <input type="hidden" id="settingCode" readonly name="settingCode" class="form-control" value="<?= $settingCode ?>">
                                        <div class="row">
                                            <div class="col-md-4 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="maildriver" class="form-label">Mail Driver</label>
                                                    <input type="text" id="maildriver" minlength="1" class="form-control" name="maildriver" value="<?= $maildriver ?>" data-parsley-required="true">
                                                </div>
                                                <?php echo form_error('maildriver', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="host" class="form-label">Host</label>
                                                    <input type="text" id="host" minlength="3" class="form-control" name="host" value="<?= $host ?>" data-parsley-required="true">
                                                </div>
                                                <?php echo form_error('host', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="port" class="form-label">Port</label>
                                                    <input type="number" id="port" minlength="2" maxlength="6" class="form-control" name="port" value="<?= $port ?>" data-parsley-required="true" onkeypress="isNumberKey(this)">
                                                </div>
                                                <?php echo form_error('port', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="username" class="form-label">Username</label>
                                                    <input type="text" id="username" minlength="1" class="form-control" name="username" value="<?= $username ?>" data-parsley-required="true">
                                                </div>
                                                <?php echo form_error('username', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="password" class="form-label">Password</label>
                                                    <input type="password" id="password" class="form-control" name="password" value="<?= $password ?>" data-parsley-required="true">
                                                </div>
                                                <?php echo form_error('password', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="encryption" class="form-label">Encryption</label>
                                                    <input type="text" id="encryption" class="form-control" name="encryption" value="<?= $encryption ?>" data-parsley-required="true">
                                                </div>
                                                <?php echo form_error('encryption', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="fromaddress" class="form-label">From Address</label>
                                                    <input type="email" id="fromaddress" class="form-control" name="fromaddress" value="<?= $fromaddress ?>" data-parsley-required="true">
                                                </div>
                                                <?php echo form_error('fromaddress', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="fromname" class="form-label">From Name</label>
                                                    <input type="text" id="fromname" minlength="3" maxlength="100" class="form-control" name="fromname" value="<?= $fromname ?>" data-parsley-required="true">
                                                </div>
                                                <?php echo form_error('fromname', '<span class="error text-danger text-right">', '</span>'); ?>
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
<script type="text/javascript">
    function isNumberKey(evt) {
        var charCode = evt.which ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) return false;
        return true;
    }

    function ValidateAlpha(evt) {
        var keyCode = evt.which ? evt.which : evt.keyCode;
        if (keyCode > 47 && keyCode < 58) return false;
        return true;
    }

    function randomNumber() {
        var number = Math.floor(1000 + Math.random() * 9999);
        $('#loginpin').val(number);
    }

    $(document).ready(function() {

        var data = '<?php echo $this->session->flashdata('message'); ?>';
        if (data != '') {
            var obj = JSON.parse(data);
            if (obj.status) {
                toastr.success(obj.message, 'Email Settings', {
                    "progressBar": true
                });
            } else {
                toastr.error(obj.message, 'Email Settings', {
                    "progressBar": true
                });
            }
        }

    });
</script>