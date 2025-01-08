<?php include '../supermarket/config.php'; ?>

<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3><?php echo $translations['User']?></h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../dashboard/listRecords"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">User</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="row match-height">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3><?php echo $translations['Add User']?><span style="float:right"><a id="cancelDefaultButton" href="<?= base_url() ?>users/listRecords" class="btn btn-sm btn-primary"><?php echo $translations['Back']?></a></span></h3>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <?php
                                echo "<div class='text-danger text-center' id='error_message'>";
                                if (isset($error_message)) {
                                    echo $error_message;
                                }
                                echo "</div>";
                                ?>
                                <form class="form" method="post" action="<?php echo base_url(); ?>Users/save" id="addUserForm" enctype="multipart/form-data" data-parsley-validate="">
                                    <input type="hidden" name="invoicePreference" readonly value="autocut">
                                    <div class="row">


                                        <div class="col-md-12 col-12">
                                            <div class="form-group mandatory">
                                                <label for="product-name" class="form-label"><?php echo $translations['Branch Name']?></label>
                                                <?php if ($branchCode != "") { ?>
                                                    <input type="hidden" class="form-control" name="branchname" id="branchname" value="<?= $branchCode; ?>" readonly>
                                                    <input type="text" class="form-control" name="branch" value="<?= $branchName; ?>" readonly>
                                                <?php } else { ?>
                                                    <select class="form-select select2 branchname" name="branchname" id="branchname" data-parsley-required="true">
                                                    </select>
                                                <?php } ?>
                                            </div>
                                            <?php echo form_error('branchname', '<span class="error text-danger text-right">', '</span>'); ?>
                                        </div>


                                        <div class="col-md-6 col-12">
                                            <div class="form-group mandatory">
                                                <label for="arabicname-column" class="form-label"><?php echo $translations['Name']?></label>
                                                <input type="text" id="name" value="<?= set_value('name') ?>" class="form-control" placeholder="Name" name="name" onkeypress="return  ValidateAlpha(event)" data-parsley-required="true">
                                            </div>
                                            <?php echo form_error('name', '<span class="error text-danger text-right">', '</span>'); ?>
                                        </div>


                                        <div class="col-md-6 col-12">
                                            <div class="form-group mandatory">
                                                <label for="arabicname-column" class="form-label"><?php echo $translations['User Name']?></label>
                                                <input type="text" id="username" value="<?= set_value('username') ?>" class="form-control" placeholder="User Name" name="username" oninput="this.value=this.value.replace(/[^a-z]/gi,'')" data-parsley-required="true" maxlength="20" data-parsley-minlength="3" data-parsley-minlength-message="You need to enter at least 3 characters" data-parsley-trigger="change">
                                            </div>
                                            <?php echo form_error('username', '<span class="error text-danger text-right">', '</span>'); ?>
                                        </div>





                                        <div class="col-md-6 col-12">
                                            <div class="form-group mandatory">
                                                <label for="arabicname-column" class="form-label"><?php echo $translations['User Email']?></label>
                                                <input type="email" id="useremail" value="<?= set_value('useremail') ?>" class="form-control" placeholder="Email" name="useremail" data-parsley-required="true" pattern="^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$" data-parsley-type-message="Valid Email is required">
                                            </div>
                                            <?php echo form_error('useremail', '<span class="error text-danger text-right">', '</span>'); ?>
                                        </div>




                                        <div class="col-md-6 col-12">
                                            <div class="form-group mandatory">
                                                <label for="arabicname-column" class="form-label"><?php echo $translations['User Employee Number']?></label>
                                                <input type="text" id="userempnumber" value="<?= set_value('userempnumber') ?>" class="form-control" placeholder="User Employee Number" name="userempnumber" data-parsley-required="true" onkeypress="return isNumberKey(event)">
                                            </div>
                                            <?php echo form_error('userempnumber', '<span class="error text-danger text-right">', '</span>'); ?>
                                        </div>




                                        <div class="col-md-6 col-12">
                                            <div class="form-group mandatory">
                                                <label for="arabicname-column" class="form-label"><?php echo $translations['User Language']?></label>
                                                <select class="form-select select2" name="userlanguage" id="userlanguage" data-parsley-required="true">
                                                    <option value=""><?php echo $translations['Select Language']?></option>
                                                    <option value="English" <?= set_select('userlanguage', 'English', False) ?>>English</option>
                                                    <option value="Arabic" <?= set_select('userlanguage', 'Arabic', False) ?>>Arabic</option>
                                                    <option value="Hindi" <?= set_select('userlanguage', 'Hindi', False) ?>>Hindi</option>
                                                    <option value="Urdu" <?= set_select('userlanguage', 'Urdu', False) ?>>Urdu</option>
                                                </select>
                                            </div>
                                            <?php echo form_error('userlanguage', '<span class="error text-danger text-right">', '</span>'); ?>
                                        </div>





                                        <div class="col-md-6 col-12">
                                            <div class="form-group mandatory">
                                                <label for="product-name" class="form-label"><?php echo $translations['User Role']?></label>
                                                <select class=" form-select select2" name="userrole" id="userrole" data-parsley-required="true">
                                                    <option value=""><?php echo $translations['Select Role']?></option>
                                                    <?php if ($roledata) {
                                                        foreach ($roledata->result() as $role) {
                                                            echo '<option value="' . $role->code . '">' . $role->role . '</option>';
                                                        }
                                                    } ?>
                                                </select>
                                            </div>
                                            <?php echo form_error('userrole', '<span class="error text-danger text-right">', '</span>'); ?>
                                        </div>


                                        <div class="col-md-6 col-12 d-none" id="counterDiv">
                                            <div class="form-group mandatory">
                                                <label for="product-name" class="form-label"><?php echo $translations['User Counter']?></label>
                                                <select class=" form-select select2" name="userCounter" id="userCounter" style="width:100%">

                                                </select>
                                            </div>
                                            <?php echo form_error('userCounter', '<span class="error text-danger text-right">', '</span>'); ?>
                                        </div>


                                        <div class="col-md-6 col-12" style="display:none;" id="loginPinDetails">
                                            <div class="form-group mandatory">
                                                <label class="form-label"><?php echo $translations['Login Pin']?></label>
                                                <input type="text" id="loginpin" value="<?= $loginpin ?>" class="form-control" placeholder="Login Pin" name="loginpin">
                                            </div>
                                            <?php echo form_error('loginpin', '<span class="error text-danger text-right">', '</span>'); ?>
                                        </div>


                                        <div id="userDetails" style="display:none;">
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group mandatory">
                                                        <label for="arabicname-column" class="form-label"><?php echo $translations['Password']?></label>
                                                        <input type="password" id="password" class="form-control" placeholder="Password" name="password">
                                                    </div>
                                                </div>


                                                <div class="col-md-6 col-12">
                                                    <div class="form-group mandatory">
                                                        <label for="arabicname-column" class="form-label"><?php echo $translations['Confirm Password']?></label>
                                                        <input type="password" id="confirmpassword" class="form-control" placeholder="Confirm Password" name="confirmpassword" onchange="checkPasswordMatch();">
                                                    </div>
                                                    <div id="CheckPasswordMatch" style="color:#e66060;"></div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                    <div class="row">

                                        <div class="col-md-12 col-12 mt-3">
                                            <div class="form-group col-md-12 col-12 text-center">
                                                <!--<label class="form-label">Image</label>-->
                                                <img class="img-thumbnail mb-2" width="120px" id="userimg" src="../assets/images/faces/default-img.jpg" data-src="">
                                                <input class="form-control" type="file" id="formFile" name="userImage" style="padding: 5px;">
                                            </div>
                                        </div>


                                        <div class="col-md-12 col-12 mb-3">
                                            <div class="form-group text-center">
                                                <div class="form-check col-md-12 col-12 d-flex justify-content-center text-center">
                                                    <input class="form-check-input mx-2" type="checkbox" name="isActive" id="isActive" checked>
                                                    <label class="form-check-label" for="isActive">
                                                        <?php echo $translations['Active']?>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-success" id="submit"><?php echo $translations['Save']?></button>
                                            <button type="button" class="btn btn-light-secondary" id="reset"><?php echo $translations['Reset']?></button>
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
<script type="text/javascript">
    var cnt = 0;

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

    function checkPasswordMatch() {
        var password = $("#password").val();
        var confirmPassword = $("#confirmpassword").val();
        if (password != confirmPassword) {
            $('#submit').prop('disabled', true);
            $("#CheckPasswordMatch").html("Passwords does not match!");
        } else {
            $('#submit').prop('disabled', false);
            $("#CheckPasswordMatch").html("");
        }
    }

    $(document).ready(function() {
        $('#reset').on('click', function(e) {
            window.location.reload();
        });
        $(".branchname").select2({
            placeholder: "Select Branch",
            allowClear: true,
            ajax: {
                url: base_path + 'Common/getBranch',
                type: "get",
                delay: 250,
                dataType: 'json',
                data: function(params) {
                    var query = {
                        search: params.term
                    }
                    return query;
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $("#userCounter").select2({
            placeholder: "Select Counter",
            allowClear: true,
            ajax: {
                url: base_path + 'Common/getCounterByBranch',
                type: "get",
                delay: 250,
                dataType: 'json',
                data: function(params) {
                    var query = {
                        search: params.term,
                        branch: $("#branchname").val()
                    }
                    return query;
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $("body").on("change", "#userrole", function(e) {
            var thisVal = $(this).find('option:selected').val();
            var thisVal = $(this).val();
            if (thisVal == 'R_5') {
                $('#counterDiv').removeClass('d-none');
                $('#userCounter').attr("data-parsley-required", true);
                $("#userCounter").attr("data-parsley-required-message", "Counter is required");
            } else {
                $('#counterDiv').addClass('d-none');
                $('#userCounter').attr("data-parsley-required", false);
                $("#password").attr("data-parsley-required-message", "");
            }
            if (thisVal != "R_5") {
                $("#userDetails").css('display', 'inline');
                $("#loginPinDetails").css('display', 'none');
                $("#password").attr("data-parsley-required", true);
                $("#confirmpassword").attr("data-parsley-required", true);
                $("#password").attr("data-parsley-required-message", "Password is required.");
                $("#confirmpassword").attr("data-parsley-required-message", "Confirm Password is required.");
                $("#loginpin").attr("data-parsley-required", false);
            } else {
                $("#userDetails").css('display', 'none');
                $("#loginPinDetails").css('display', 'inline');
                $("#password").attr("data-parsley-required", false);
                $("#confirmpassword").attr("data-parsley-required", false);
                $("#loginpin").attr("data-parsley-required", true);
                $("#loginpin").attr("data-parsley-required-message", "Login Pin is required.");
            }
        });

        $("#formFile").change(function() {
            const file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    $("#userimg")
                        .attr("src", event.target.result);
                };
                reader.readAsDataURL(file);
            }
        });

    });

    function getBranchCounters(id) {
        var branchCode = $('#branchname').val();
        if (branchCode != '') {
            $.ajax({
                url: base_path + 'users/getBranchCounters',
                data: {
                    'branchCode': branchCode
                },
                type: 'post',
                success: function(response) {
                    var res = JSON.parse(response);
                    if (res.status) {
                        $('select#userCounter').empty();
                        $('select#userCounter').append(res.counters);
                    } else {
                        $('#branchname' + id).val('');
                        $('select#userCounter' + id).attr('disabled', true);
                    }
                }
            });
        }
    }
</script>