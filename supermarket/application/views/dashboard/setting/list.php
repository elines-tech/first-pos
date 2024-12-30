<style>
    .card-footer {
        border: none !important;
        padding: 15px;
    }

    .card-body h4 {
        font-size: 1.0rem;
    }
</style>
<?php
$emailData = $smsData = $skuprefix = false;
if ($setting) {
    foreach ($setting->result() as $s) {
        switch ($s->code) {
            case "STG_1":
                $skuprefix =  $s;
                break;
            case "STG_2":
                $emailData =  $s;
                break;
            case "STG_3":
                $smsData =  $s;
                break;
        }
    }
}
?>
<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Settings</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Settings</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php if ($this->session->flashdata("err")) {
                    $msg = $this->session->flashdata("err");
                ?>
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="fa fa-times"></i>
                        <div> <?= $msg ?></div>
                    </div>
                <?php
                } ?>
                <div class="card">
                    <div class="card-body">

                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active col-md-4 col-12" id="nav-skuprefix-tab" data-bs-toggle="tab" data-bs-target="#nav-skuprefix" type="button" role="tab" aria-controls="nav-home" aria-selected="true">SKU Prefix</button>
                                <button class="nav-link col-md-4 col-12" id="nav-email-tab" data-bs-toggle="tab" data-bs-target="#nav-email" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Email</button>
                                <button class="nav-link col-md-4 col-12" id="nav-textsms-tab" data-bs-toggle="tab" data-bs-target="#nav-textsms" type="button" role="tab" aria-controls="nav-textssms" aria-selected="false">Text-SMS</button>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-skuprefix" role="tabpanel" aria-labelledby="nav-serviceharge-tab">
                                <div class="col-12 p-3">
                                    <?php
                                    $settingValue = "";
                                    if ($skuprefix) {
                                        $settingValue = $skuprefix->settingValue;
                                    }
                                    ?>
                                    <form class="form" id="frmSkuPrefix" enctype="multipart/form-data" data-parsley-validate method="post" action="<?php echo base_url('setting/skuprefix'); ?>">
                                        <input type="hidden" id="settingCode" readonly name="settingCode" class="form-control" value="STG_1">
                                        <div class="row">
                                            <div class="col-md-12 text-center col-12">
                                                <div class="form-group mandatory">
                                                    <label for="arabicname-column" class="form-label">SKU Prefix</label>
                                                    <input type="text" id="settingValue" minLength="1" maxlength="3" class="form-control" placeholder="User Name" name="settingValue" value="<?= $settingValue ?>" data-parsley-required="true">
                                                </div>
                                                <?php echo form_error('settingValue', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>
                                            <?php if ($updateRights == 1) { ?>
                                                <div class="col-12 text-center mt-3">
                                                    <button id="saveDefaultButton" type="submit" class="btn btn-success">Update</button>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="nav-email" role="tabpanel" aria-labelledby="nav-email-tab">
                                <div class="col-12 p-3">
                                    <?php
                                    $maildriver = "";
                                    $host = "";
                                    $port = "";
                                    $username = "";
                                    $password = "";
                                    $fromaddress = "";
                                    $fromname = "";
                                    if ($emailData) {
                                        $settingValue = $emailData->settingValue;
                                        if ($settingValue != "") {
                                            $jsonSetting = json_decode($settingValue);
                                            $maildriver = $jsonSetting->maildriver;
                                            $host = $jsonSetting->host;
                                            $port = $jsonSetting->port;
                                            $username = $jsonSetting->username;
                                            $password = $jsonSetting->password;
                                            $fromaddress = $jsonSetting->fromaddress;
                                            $fromname = $jsonSetting->fromname;
                                        }
                                    }
                                    ?>
                                    <form class="form" id="frmEmail" enctype="multipart/form-data" data-parsley-validate method="post" action="<?php echo base_url('setting/update_email'); ?>">
                                        <input type="hidden" id="settingCode" readonly name="settingCode" class="form-control" value="STG_2">
                                        <div class="row">
                                            <div class="col-md-4 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="maildriver" class="form-label">Mail Driver</label>
                                                    <select id="maildriver" class="form-select" name="maildriver" value="<?= $maildriver ?>" data-parsley-required="true">
                                                        <option value="smpt" selected>SMTP</option>
                                                    </select>
                                                </div>
                                                <?php echo form_error('maildriver', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="host" class="form-label">Host</label>
                                                    <input type="text" id="host" minlength="3" class="form-control" placeholder="Ex. google.com" name="host" value="<?= $host ?>" data-parsley-required="true">
                                                </div>
                                                <?php echo form_error('host', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="port" class="form-label">Port</label>
                                                    <input type="number" id="port" step="1" minlength="2" maxlength="6" placeholder="Ex. 465" class="form-control" name="port" value="<?= $port ?>" data-parsley-required="true" onkeypress="isNumberKey(this)">
                                                </div>
                                                <?php echo form_error('port', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="username" class="form-label">Username</label>
                                                    <input type="text" id="username" minlength="1" class="form-control" placeholder="Ex. sample@mail.com" name="username" value="<?= $username ?>" data-parsley-required="true">
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
                                                    <label for="fromaddress" class="form-label">From Address</label>
                                                    <input type="email" id="fromaddress" class="form-control" name="fromaddress" placeholder="Ex. sample@mail.com" value="<?= $fromaddress ?>" data-parsley-required="true">
                                                </div>
                                                <?php echo form_error('fromaddress', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="fromname" class="form-label">From Name</label>
                                                    <input type="text" id="fromname" minlength="3" maxlength="100" class="form-control" placeholder="Ex. Name of Email" name="fromname" value="<?= $fromname ?>" data-parsley-required="true">
                                                </div>
                                                <?php echo form_error('fromname', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>
                                            <?php if ($updateRights == 1) { ?>
                                                <div class="col-12 mt-3 text-center">
                                                    <button id="saveDefaultButton" type="submit" class="btn btn-success">Update</button>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-textsms" role="tabpanel" aria-labelledby="nav-sms-tab">
                                <?php
                                $formUrl = $smsprovider = "";
                                if ($smsData) {
                                    if ($smsData->settingValue != "") {
                                        $jsonData = json_decode($smsData->settingValue);
                                        $smsprovider = $jsonData->smsprovider;
                                        switch ($smsprovider) {
                                            case 'TWILIO':
                                                $formUrl = base_url("setting/twilio");
                                                break;
                                            case 'PLIVO':
                                                $formUrl = base_url("setting/plivo");
                                                break;
                                        }
                                    }
                                }
                                ?>
                                <div class="col-12 p-3">
                                    <form class="form" id="frmSms" enctype="multipart/form-data" data-parsley-validate method="post" action="<?= $formUrl ?>">
                                        <input type="hidden" id="settingCode" readonly name="settingCode" class="form-control" value="STG_3">
                                        <div class="row">
                                            <div class="col-md-12 text-center col-12">
                                                <div class="form-group mandatory">
                                                    <label for="maildriver" class="form-label">Provider</label>
                                                    <select id="smsprovider" minlength="1" class="form-select" name="smsprovider" value="<?= $maildriver ?>" data-parsley-required="true">
                                                        <option value="">Select</option>
                                                        <option value="TWILIO" <?= $smsprovider == "TWILIO" ? "selected" : "" ?>>TWILIO</option>
                                                        <option value="PLIVO" <?= $smsprovider == "PLIVO" ? "selected" : "" ?>>PLIVO</option>
                                                    </select>
                                                </div>
                                                <?php echo form_error('smsprovider', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>
                                        </div>
                                        <div class="row" id="sms-config-field">
                                            <?php
                                            if ($smsData) {
                                                if ($smsData->settingValue != "") {
                                                    $jsonData = json_decode($smsData->settingValue);
                                                    if ($jsonData->smsprovider == "TWILIO") {
                                            ?>
                                                        <div class="col-md-6 col-12">
                                                            <div class="form-group mandatory">
                                                                <label for="sid" class="form-label">SID</label>
                                                                <input type="text" id="sid" class="form-control" name="sid" minlength="34" value="<?= $jsonData->sid ?>" data-parsley-required="true">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-12">
                                                            <div class="form-group mandatory">
                                                                <label for="token" class="form-label">Auth Token</label>
                                                                <input type="text" id="token" class="form-control" name="token" minlength="32" value="<?= $jsonData->token ?>" data-parsley-required="true">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-12">
                                                            <div class="form-group mandatory">
                                                                <label for="twilionumber" class="form-label">Twilio Number</label>
                                                                <input type="tel" id="twilionumber" class="form-control" name="twilionumber" value="<?= $jsonData->twilionumber ?>" data-parsley-required="true">
                                                            </div>
                                                        </div>
                                                    <?php
                                                    } else if ($jsonData->smsprovider == "PLIVO") {
                                                    ?>
                                                        <div class="col-md-6 col-12">
                                                            <div class="form-group mandatory">
                                                                <label for="authid" class="form-label">Auth ID</label>
                                                                <input type="text" id="authid" class="form-control" name="authid" minlength="15" value="<?= $jsonData->authid ?>" data-parsley-required="true">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-12">
                                                            <div class="form-group mandatory">
                                                                <label for="authtoken" class="form-label">Auth Token</label>
                                                                <input type="text" id="authtoken" class="form-control" name="authtoken" minlength="15" value="<?= $jsonData->authtoken ?>" data-parsley-required="true">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-12">
                                                            <div class="form-group mandatory">
                                                                <label for="senderId" class="form-label">Sender ID</label>
                                                                <input type="text" id="senderId" class="form-control" name="senderId" minlength="15" value="<?= $jsonData->senderId ?>" data-parsley-required="true">
                                                            </div>
                                                        </div>
                                            <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </div>
                                        <?php if ($updateRights == 1) { ?>
                                            <div class="row">
                                                <div class="col-12 mt-3 text-center">
                                                    <button id="saveDefaultButton" type="submit" class="btn btn-success">Update</button>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                toastr.success(obj.message, 'Settings', {
                    "progressBar": true
                });
            } else {
                toastr.error(obj.message, 'Settings', {
                    "progressBar": true
                });
            }
        }
    });

    $(document).on("change", "#smsprovider", function(e) {
        var html = "";
        switch ($(this).val()) {
            case "TWILIO":
                html = `
                    <div class="col-md-6 col-12">
                        <div class="form-group mandatory">
                            <label for="sid" class="form-label">SID</label>
                            <input type="text" id="sid" class="form-control" name="sid" minlength="34" value="" data-parsley-required="true">
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form-group mandatory">
                            <label for="token" class="form-label">Auth Token</label>
                            <input type="text" id="token" class="form-control" name="token" minlength="32" value="" data-parsley-required="true">
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form-group mandatory">
                            <label for="twilionumber" class="form-label">Twilio Number</label>
                            <input type="tel" id="twilionumber" class="form-control" name="twilionumber" value="" data-parsley-required="true">
                        </div>
                    </div>
                `;
                $("#sms-config-field").html(html);
                $("#frmSms").attr("action", "<?= base_url("setting/twilio") ?>");
                break;
            case "PLIVO":
                html = `
                    <div class="col-md-6 col-12">
                        <div class="form-group mandatory">
                            <label for="authid" class="form-label">Auth ID</label>
                            <input type="text" id="authid" class="form-control" name="authid" minlength="15" value="" data-parsley-required="true">
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form-group mandatory">
                            <label for="authtoken" class="form-label">Auth Token</label>
                            <input type="text" id="authtoken" class="form-control" minlength="15" name="authtoken" value="" data-parsley-required="true">
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form-group mandatory">
                            <label for="senderId" class="form-label">Sender ID</label>
                            <input type="text" id="senderId" class="form-control" minlength="15" name="senderId" value="" data-parsley-required="true">
                        </div>
                    </div>
                `;
                $("#sms-config-field").html(html);
                $("#frmSms").attr("action", "<?= base_url("setting/plivo") ?>");
                break;
        }
    });
</script>