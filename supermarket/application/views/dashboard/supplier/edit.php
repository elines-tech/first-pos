<?php include '../supermarket/config.php'; ?>


<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3><?php echo $translations['Supplier']?></h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../../dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
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
                            <h3><?php echo $translations['Update Supplier']?><span style="float:right"><a id="cancelDefaultButton" href="<?= base_url() ?>supplier/listRecords" class="btn btn-sm btn-primary m-1"><?php echo $translations['Back']?></a></span></h3>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <form class="form" action="<?= base_url('Supplier/update') ?>" method="post" enctype="multipart/form-data" data-parsley-validate>
                                    <?php
                                    echo "<div class='text-danger text-center' id='error_message'>";
                                    if (isset($error_message)) {
                                        echo $error_message;
                                    }
                                    echo "</div>";
                                    ?>
                                    <input type="hidden" id="code" readonly name="code" class="form-control" value="<?= $supplierData[0]['code'] ?>">
                                    <div class="row">
                                        <div class="col-md-3 col-12">

                                            <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                                <div class="card card-custom gutter-b bg-white border-0">
                                                    <div class="card-body">
                                                        <h3 class="mt-0 header-title lng text-center"><?php echo $translations['Image']?></h3>

                                                        <div class="col-md-12 col-sm-6 col-xs-6 mb-2 p-0 text-center">
                                                            <?php if ($supplierData[0]['supplierImage']  != "") { ?>
                                                                <img class="img-thumbnail mb-2" width="120px" id="logo_icon" src="<?= base_url() . $supplierData[0]['supplierImage']  ?>" data-src="">
                                                            <?php } else { ?>
                                                                <img class="img-thumbnail mb-2" width="120px" id="logo_icon" src="https://sub.kaemsoftware.com/development/assets/images/faces/default-img.jpg" data-src="">
                                                            <?php } ?>

                                                            <input class="form-control" type="file" id="formFile" style="padding: 5px;" name="supplierImage">
                                                        </div>
                                                        <!-- <div class="col-md-12 col-sm-6 col-xs-6 mb-2 p-0 text-center">
                                            <button type="button" data-val="single" class="btn btn-primary up_media lng" id="upload">Upload Image</button>
                                            <button type="button" class="btn btn-danger lng" key="clear" id="clear_img">Clear</button>
                                        </div>-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-9 col-12">
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group mandatory">
                                                        <label for="supplier-name" class="form-label mb-2"><?php echo $translations['Name']?></label>
                                                        <input type="text" id="supplier-name" class="form-control" placeholder="Supplier Name" name="suppliername" data-parsley-required="true" value="<?= $supplierData[0]['supplierName'] ?>" onkeypress="return  ValidateAlpha(event)">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group  mandatory">
                                                        <label for="arabicname-column" class="form-label mb-2"><?php echo $translations['Arabic Name']?></label>
                                                        <input type="text" id="arabicname" class="form-control" placeholder="Arabic Name" name="arabicname" value="<?= $supplierData[0]['arabicName'] ?>" data-parsley-required="true">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group  mandatory">
                                                        <label for="company-name" class="form-label mb-2"><?php echo $translations['Company Name']?></label>
                                                        <input type="text" id="companyname" class="form-control" placeholder="Company Name" name="companyname" value="<?= $supplierData[0]['companyName'] ?>" onkeypress="return  ValidateAlpha(event)" data-parsley-required="true">
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-12">
                                                    <div class="form-group  mandatory">
                                                        <label for="Phone" class="form-label mb-2"><?php echo $translations['Phone']?></label>
                                                        <div class="input-group">

                                                            <!--<div class="input-group-prepend">
                                                                <select class="form-select" id="countryCode" onchange="setPattern()" name="countryCode" required>
                                                                    <option value="+966" <?php if ($supplierData[0]['countryCode'] == '966') {
                                                                                                echo 'selected';
                                                                                            } ?> data-pattern="^\d{9}$" selected>+966 SAU</option>
                                                                    <option value="+971" <?php if ($supplierData[0]['countryCode'] == '971') {
                                                                                                echo 'selected';
                                                                                            } ?> data-pattern="^\d{9}$">+971 UAE</option>
                                                                </select>
                                                            </div>-->

                                                            <input type="text" class="form-control" id="phone" placeholder="Phone" name="phone" value="<?= $supplierData[0]['phone'] ?>" onkeypress="return isNumberKey(event)" data-parsley-required="true" data-parsley-pattern-message="Invalid Phone Number" />
                                                            <?php echo form_error('phone', '<span class="error text-danger text-right">', '</span>'); ?>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="email-column" class="form-label mb-2"><?php echo $translations['Email']?></label>
                                                        <input type="email" id="email" class="form-control" placeholder="Email" name="email" value="<?= $supplierData[0]['email'] ?>" pattern="^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$" data-parsley-type-message="Valid Email is required">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="financial" class="form-label mb-2"><?php echo $translations['Financial Account']?></label>
                                                        <input type="text" id="financial" class="form-control" placeholder="Financial Account" name="financial" value="<?= $supplierData[0]['financialAccount'] ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 col-12">
                                                    <div class="form-group">
                                                        <label for="addr-column" class="form-label mb-2"><?php echo $translations['Address']?></label>
                                                        <textarea class="form-control" placeholder="Address" id="address" name="address"><?= $supplierData[0]['address'] ?></textarea>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">

                                                        <label for="Country" class="form-label mb-2"><?php echo $translations['Country']?></label>
                                                        <!--<input type="text" id="country" class="form-control" placeholder="Country Name" name="country" value="<?= $supplierData[0]['country'] ?>" onkeypress="return  ValidateAlpha(event)">-->
                                                        <?php
                                                        $country = file_get_contents('assets/country.json');
                                                        $items = json_decode($country, true);

                                                        ?>
                                                        <select class="form-select select2" name="country" id="country">
                                                            <?php foreach ($items as $item) {
                                                                if ($item['country'] == $supplierData[0]['country']) {
                                                            ?>
                                                                    <option value="<?= $item['country'] ?>" selected><?= $item['country'] ?></option>
                                                                <?php } else { ?>
                                                                    <option value="<?= $item['country'] ?>"><?= $item['country'] ?></option>
                                                            <?php }
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">

                                                        <label for="State" class="form-label mb-2"><?php echo $translations['State']?></label>
                                                        <input type="text" id="state" class="form-control" placeholder="State Name" name="state" value="<?= $supplierData[0]['state'] ?>" onkeypress="return  ValidateAlpha(event)">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="City" class="form-label mb-2"><?php echo $translations['City']?></label>
                                                        <input type="text" id="city" class="form-control" placeholder="City" name="city" value="<?= $supplierData[0]['city'] ?>" onkeypress="return  ValidateAlpha(event)">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="pincode" class="form-label mb-2"><?php echo $translations['Postal Code']?></label>
                                                        <input type="text" id="pincode" class="form-control" placeholder="Postal Code" name="pincode" value="<?= $supplierData[0]['postalCode'] ?>" onkeypress="return isNumberKey(event)">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">

                                                <div class="col-md-6 col-12 d-none">
                                                    <div class="form-group ">
                                                        <label for="Tax" class="form-label"><?php echo $translations['Tax (%)']?></label>
                                                        <input type="text" id="tax" class="form-control" placeholder="Tax" name="tax" value="<?= $supplierData[0]['tax'] ?>" onkeypress="return isNumberKey(event)">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">

                                                <div class="col-md-2 col-12">
                                                    <div class="form-group">
                                                        <label class="form-label lng" key="status"><?php echo $translations['Status']?></label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend"><span class="input-group-text bg-soft-primary">
                                                                    <input type="checkbox" name="isActive" class="form-check-input" <?php if ($supplierData[0]['isActive'] == 1) {
                                                                                                                                        echo "checked";
                                                                                                                                    } ?>></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <button id="saveDefaultButton" type="submit" class="btn btn-success"><?php echo $translations['Update']?></button>
                                            <!--<button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>-->
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- // Basic multiple Column Form section end -->

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
                    $("#logo_icon")
                        .attr("src", event.target.result);
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>