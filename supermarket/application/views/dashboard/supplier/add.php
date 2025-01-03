<style>
    .parsley-pattern {
        color: red;
    }
</style>
<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Supplier</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Supplier</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section>
            <div class="row match-height">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3>Add Supplier<span style="float:right"><a id="cancelDefaultButton" href="<?= base_url() ?>supplier/listRecords" class="btn btn-sm btn-primary">Back</a></span></h3>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <form class="form" action="<?= base_url('Supplier/save') ?>" method="post" enctype="multipart/form-data" data-parsley-validate>
                                    <div class="row">
                                        <div class="col-md-3 col-12">

                                            <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                                <div class="card card-custom gutter-b bg-white border-0">
                                                    <div class="card-body">
                                                        <h3 class="mt-0 header-title lng text-center">Image</h3>

                                                        <div class="col-md-12 col-sm-6 col-xs-6 mb-2 p-0 text-center">
                                                            <img class="img-thumbnail mb-2" width="120px" id="logo_icon" src="../assets/images/faces/default-img.jpg" data-src="">
                                                            <input class="form-control" type="file" id="formFile" name="supplierImage" style="padding: 5px;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>




                                        </div>
                                        <div class="col-md-9 col-12">
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group mandatory">
                                                        <label for="suppliername" class="form-label mb-2">Name</label>
                                                        <input type="text" id="supplier-name" class="form-control" placeholder="Supplier Name" name="suppliername" value="<?= set_value('suppliername') ?>" data-parsley-required="true" onkeypress="return  ValidateAlpha(event)">
                                                    </div>
                                                    <?php echo form_error('suppliername', '<span class="error text-danger text-right">', '</span>'); ?>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group mandatory">
                                                        <label for="arabicname-column" class="form-label mb-2">Arabic Name</label>
                                                        <input type="text" id="arabicname" class="form-control" placeholder="Arabic Name" name="arabicname" value="<?= set_value('arabicname') ?>" data-parsley-required="true">
                                                    </div>
                                                    <?php echo form_error('arabicname', '<span class="error text-danger text-right">', '</span>'); ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group mandatory">
                                                        <label for="company-name" class="form-label mb-2">Company Name</label>
                                                        <input type="text" id="companyname" class="form-control" placeholder="Company Name" name="companyname" value="<?= set_value('companyname') ?>" onkeypress="return  ValidateAlpha(event)" data-parsley-required="true">
                                                    </div>
                                                    <?php echo form_error('companyname', '<span class="error text-danger text-right">', '</span>'); ?>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group mandatory">
                                                        <label for="Phone" class="form-label mb-2">Phone</label>
                                                        <div class="input-group">

                                                            <!--<div class="input-group-prepend">
															<select class="form-select" id="countryCode" onchange="setPattern()" name="countryCode" required>
																<option value="966" data-pattern="^\d{9}$" selected>+966 SAU</option>
																<option value="971" data-pattern="^\d{9}$">+971 UAE</option>
															</select>
														</div>-->

                                                            <input type="text" class="form-control" id="phone" placeholder="Phone" name="phone" value="<?= set_value('phone') ?>" onkeypress="return isNumberKey(event)" data-parsley-required="true" data-parsley-pattern-message="Invalid Phone Number" />
                                                            <?php echo form_error('phone', '<span class="error text-danger text-right">', '</span>'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="email-column" class="form-label mb-2">Email</label>
                                                        <input type="email" id="email" class="form-control" placeholder="Email" name="email" value="<?= set_value('email') ?>" pattern="^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$" data-parsley-type-message="Valid Email is required">
                                                    </div>
                                                    <?php echo form_error('email', '<span class="error text-danger text-right">', '</span>'); ?>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="financial" class="form-label mb-2">Financial Account</label>
                                                        <input type="text" id="financial" class="form-control" placeholder="Financial Account" name="financial" value="<?= set_value('financial') ?>">
                                                    </div>
                                                    <?php echo form_error('financial', '<span class="error text-danger text-right">', '</span>'); ?>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 col-12">
                                                    <div class="form-group">
                                                        <label for="addr-column" class="form-label mb-2">Address</label>
                                                        <textarea class="form-control" placeholder="Address" id="address" name="address"><?= set_value('address') ?></textarea>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">

                                                        <label for="Country" class="form-label mb-2">Country</label>
                                                        <!--<input type="text" id="country" class="form-control" placeholder="Country Name" name="country" value="<?= set_value('country') ?>" onkeypress="return  ValidateAlpha(event)" >-->
                                                        <?php
                                                        $country = file_get_contents('assets/country.json');
                                                        $items = json_decode($country, true);

                                                        ?>
                                                        <select class="form-select select2" name="country" id="country">
                                                            <?php foreach ($items as $item) {
                                                                if ($item['country'] == 'Saudi Arabia') {
                                                            ?>
                                                                    <option value="<?= $item['country'] ?>" selected <?= set_select('country', $item['country'], False) ?>><?= $item['country'] ?></option>
                                                                <?php } else { ?>
                                                                    <option value="<?= $item['country'] ?>" <?= set_select('country', $item['country'], False) ?>><?= $item['country'] ?></option>
                                                            <?php }
                                                            } ?>
                                                        </select>
                                                    </div>
                                                    <?php echo form_error('country', '<span class="error text-danger text-right">', '</span>'); ?>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">

                                                        <label for="State" class="form-label mb-2">State</label>
                                                        <input type="text" id="state" class="form-control" placeholder="State Name" name="state" value="<?= set_value('state') ?>" onkeypress="return  ValidateAlpha(event)">


                                                    </div>
                                                    <?php echo form_error('state', '<span class="error text-danger text-right">', '</span>'); ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="City" class="form-label mb-2">City</label>
                                                        <input type="text" id="city" class="form-control" placeholder="City" name="city" value="<?= set_value('city') ?>" onkeypress="return  ValidateAlpha(event)">
                                                    </div>
                                                    <?php echo form_error('city', '<span class="error text-danger text-right">', '</span>'); ?>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="pincode" class="form-label mb-2">Postal Code</label>
                                                        <input type="text" id="pincode" class="form-control" placeholder="Postal Code" name="pincode" value="<?= set_value('pincode') ?>" onkeypress="return isNumberKey(event)">
                                                    </div>
                                                    <?php echo form_error('pincode', '<span class="error text-danger text-right">', '</span>'); ?>
                                                </div>
                                            </div>
                                            <div class="row mb-2">

                                                <div class="col-md-6 col-12 d-none">
                                                    <div class="form-group">
                                                        <label for="Tax" class="form-label">Tax (%)</label>
                                                        <input type="text" id="tax" class="form-control" placeholder="Tax" name="tax" value="<?= set_value('tax') ?>" onkeypress="return isNumberKey(event)">
                                                    </div>
                                                    <?php echo form_error('tax', '<span class="error text-danger text-right">', '</span>'); ?>
                                                </div>
                                            </div>
                                            <div class="row">

                                                <div class="col-md-2 col-12">
                                                    <div class="form-group">
                                                        <label class="form-label lng" key="status">Status</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend"><span class="input-group-text bg-soft-primary">
                                                                    <input type="checkbox" name="isActive" class="form-check-input" checked=""></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <button id="saveDefaultButton" type="submit" class="btn btn-success">Save</button>
                                            <button id="cancelDefaultButton" type="reset" class="btn btn-light-secondary">Reset</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<script type="text/javascript" src=<?php echo base_url() . 'assets/js/google_jsapi.js'; ?>></script>
<script type="text/javascript">
    function setPattern() {
        var countryCode = $('#countryCode').val();
        if (countryCode != '') {
            var pattern = $('#countryCode').find(':selected').data('pattern');
            $('#phone').attr('data-parsley-pattern', pattern);
        }
    }

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
    google.load("elements", "1", {
        packages: "transliteration"
    });

    function onLoad() {
        var optionsArabic = {
            sourceLanguage: google.elements.transliteration.LanguageCode.ENGLISH,
            destinationLanguage: [google.elements.transliteration.LanguageCode.ARABIC],
            shortcutKey: 'ctrl+g',
            transliterationEnabled: true
        }
        var controlArabic = new google.elements.transliteration.TransliterationControl(optionsArabic);
        controlArabic.makeTransliteratable(['arabicname']);

    }
    google.setOnLoadCallback(onLoad);
    $(document).ready(function() {
        setPattern()
        $("#formFile").change(function() {
            const file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    $("#logo_icon").attr("src", event.target.result);
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>