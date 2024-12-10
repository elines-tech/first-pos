<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>User</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><i class="fa fa-dashboard"></i> Dashboard</li>
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
                            <h3>Update User<span style="float:right"><a href="<?= base_url() ?>users/listRecords" class="btn btn-sm btn-primary">Back</a></span></h3>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <?php
                                    echo "<div class='text-danger text-center' id='error_message'>";
                                    if (isset($error_message)) {
                                        echo $error_message;
                                    }
                                    echo "</div>";
                                    ?>
                                    <form class="form" id="editUserForm" enctype="multipart/form-data" data-parsley-validate method="post" action="<?php echo base_url(); ?>Users/update">
                                        <input type="hidden" id="code" readonly name="code" class="form-control" value="<?= $userData[0]['code'] ?>">
                                        <input type="hidden" name="invoicePreference" readonly value="autocut">
                                        <div class="row">
                                            <div class="col-md-4 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="product-name" class="form-label">Branch Name</label>
													
													<?php if($branchCode!=""){?>
														  <input type="hidden" class="form-control" name="branchname" id="branchname" value="<?= $branchCode; ?>" readonly>
														  <input type="text" class="form-control" name="branch" value="<?= $branchName; ?>" readonly>
													<?php } else{?>
                                                    <select class="form-select" name="branchname" id="branchname" data-parsley-required="true" onchange="getBranchCounters()">
                                                        <option value="">Select Branch</option>
                                                        <?php if ($branchdata) {
                                                            foreach ($branchdata->result() as $branch) {
                                                                $selected = $userData[0]['userBranchCode'] == $branch->code ? 'selected' : '';
                                                                echo '<option value="' . $branch->code . '"' . $selected . '>' . $branch->branchName . '</option>';
                                                            }
                                                        } ?>
                                                    </select>
													<?php } ?>
                                                </div>
                                                <?php echo form_error('branchname', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="arabicname-column" class="form-label">Name</label>
                                                    <input type="text" id="name" class="form-control" placeholder="Name" name="name" value="<?= $userData[0]['name'] ?>" onkeypress="return  ValidateAlpha(event)" data-parsley-required="true">
                                                </div>
                                                <?php echo form_error('name', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="arabicname-column" class="form-label">User Name</label>
                                                    <input type="text" id="username" class="form-control" placeholder="User Name" name="username" value="<?= $userData[0]['userName'] ?>" oninput="this.value=this.value.replace(/[^a-z]/gi,'')" data-parsley-required="true" maxlength="20" 
													data-parsley-minlength="3" data-parsley-minlength-message="You need to enter at least 3 characters" data-parsley-trigger="change">
                                                </div>
                                                <?php echo form_error('username', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="arabicname-column" class="form-label">User Language</label>
                                                    <select class="form-select" name="userlanguage" id="userlanguage" data-parsley-required="true">
                                                        <option value="">Select Language</option>
                                                        <option value="English" <?= $userData[0]['userLang'] == 'English' ? 'selected' : '' ?>>English</option>
                                                        <option value="Arabic" <?= $userData[0]['userLang'] == 'Arabic' ? 'selected' : '' ?>>Arabic</option>
                                                        <option value="Hindi" <?= $userData[0]['userLang'] == 'Hindi' ? 'selected' : '' ?>>Hindi</option>
                                                        <option value="Urdu" <?= $userData[0]['userLang'] == 'Urdu' ? 'selected' : '' ?>>Urdu</option>
                                                    </select>
                                                </div>
                                                <?php echo form_error('userlanguage', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="arabicname-column" class="form-label">User Employee Number</label>
                                                    <input type="text" id="userempnumber" class="form-control" placeholder="User Employee Number" name="userempnumber" value="<?= $userData[0]['userEmpNo'] ?>" data-parsley-required="true" onkeypress="return isNumberKey(event)">
                                                </div>
                                                <?php echo form_error('userempnumber', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="arabicname-column" class="form-label">User Email</label>
                                                    <input type="email" id="useremail" class="form-control" placeholder="Email" name="useremail" data-parsley-required="true" value="<?= $userData[0]['userEmail'] ?>" pattern="^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$" data-parsley-type-message="Valid Email is required">
                                                </div>
                                                <?php echo form_error('useremail', '<span class="error text-danger text-right">', '</span>'); ?>

                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="product-name" class="form-label">User Role</label>
                                                    <select class=" form-control" name="userrole" id="userrole" data-parsley-required="true">
                                                        <option value="">Select Role</option>
                                                        <?php if ($roledata) {
                                                            foreach ($roledata->result() as $role) {
                                                                $selected = $userData[0]['userRole'] == $role->code ? 'selected' : '';
                                                                echo '<option value="' . $role->code . '"' . $selected . '>' . $role->role . '</option>';
                                                            }
                                                        } ?>
                                                    </select>
                                                </div>
                                                <?php echo form_error('userrole', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>
                                            <div class="col-md-4 col-12 d-none" id="counterDiv">
                                                <div class="form-group mandatory">
                                                    <label for="product-name" class="form-label">User Counter</label>
                                                    <select class=" form-select select2" name="userCounter" id="userCounter" style="width:100%" <?= $userData[0]['userRole'] == 'R_5' ? 'required' : '' ?>>
                                                        <option value="">Select Counter</option>
                                                        <?php 
                                                        if ($counterdata) {
                                                            foreach ($counterdata->result() as $cnt) {
                                                                if ($cnt->branchCode == $userData[0]['userBranchCode']) {
                                                                    $selected = $userData[0]['userCounter'] == $cnt->code ? 'selected' : '';
                                                                    echo '<option value="' . $cnt->code . '"  ' . $selected . '>' . $cnt->counterName . '</option>';
                                                                }
                                                            }
                                                        } 
                                                        ?>
                                                    </select>
                                                </div>
                                                <?php echo form_error('userCounter', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>
                                            <script>
                                                var role = '<?= $userData[0]['userRole'] ?>'
                                                if (role == 'R_5') {
                                                    $('#counterDiv').removeClass('d-none');
                                                }
                                            </script>
                                            <div class="col-md-4" id="loginPinDetails" style="display:<?= $userData[0]['userRole'] == 'R_5' ? '' : 'none' ?>">
                                                <div class="form-group mandatory">
                                                    <label class="form-label">Login Pin</label>
                                                    <input type="text" id="loginpin" value="<?= $userData[0]['loginpin'] ?>" class="form-control" placeholder="Login Pin" name="loginpin">

                                                </div>
                                            </div>
                                            <?php echo form_error('loginpin', '<span class="error text-danger text-right">', '</span>'); ?>
                                            <div class="col-md-4 mt-sm-4" id="loginPinDetails1" style="display:<?= $userData[0]['userRole'] == 'R_5' ? '' : 'none' ?>">
                                                <a class="btn btn-light-secondary me-1 mb-1 cursor-pointer" id="reLoginPin" onclick="randomNumber();">Regenerate</a>
                                            </div>
                                            <div id="userDetails" style="display:<?= $userData[0]['userRole'] != 'R_5' ? '' : 'none' ?>">
                                                <div class="row">
                                                    <div class="col-md-4 col-12">
                                                        <div class="form-group">
                                                            <label for="arabicname-column" class="form-label">Password</label>
                                                            <input type="password" id="password" class="form-control" placeholder="Password" name="password">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-12">
                                                        <div class="form-group">
                                                            <label for="arabicname-column" class="form-label">Confirm Password</label>
                                                            <input type="password" id="confirmpassword" class="form-control" placeholder="Confirm Password" name="confirmpassword" onchange="checkPasswordMatch();">
                                                        </div>
                                                        <div id="CheckPasswordMatch" style="color:#e66060;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">Image</label>
                                                        <div>
                                                            <?php if ($userData[0]['userImage'] != "") { ?>
                                                                <img class="img-thumbnail mb-2" width="120px" id="userimg" src="<?= base_url() . $userData[0]['userImage'] ?>" data-src="">
                                                            <?php } else { ?>
                                                                <img class="img-thumbnail mb-2" width="120px" id="userimg" src="/assets/images/faces/default-img.jpg" data-src="">
                                                            <?php } ?>
                                                        </div>
                                                        <input class="form-control" type="file" id="formFile" name="userImage">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="isActive" id="isActive" <?= ($userData[0]['isActive'] == 1) ? "checked" : "" ?>>
                                                            <label class="form-check-label" for="isActive">
                                                                Active
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-success white me-1 mb-1 sub_2" id="editUser">Update</button>
                                                <!--<button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>-->
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
        <!-- // Basic multiple Column Form section end -->
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
            $('#editUser').prop('disabled', true);
            $("#CheckPasswordMatch").html("Passwords does not match!");
        } else {
            $('#editUser').prop('disabled', false);
            $("#CheckPasswordMatch").html("");
        }
    }

    function randomNumber() {
        var number = Math.floor(1000 + Math.random() * 9999);
        $('#loginpin').val(number);
    }
    $(document).ready(function() {
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
                $("#loginPinDetails1").css('display', 'none');
                //$("#username").attr("data-parsley-required", true);
                //$("#password").attr("data-parsley-required", true);
                //$("#confirmpassword").attr("data-parsley-required", true);
                //$("#username").attr("data-parsley-required-message", "Username is required.");
                //$("#password").attr("data-parsley-required-message", "Password is required.");
                //$("#confirmpassword").attr("data-parsley-required-message", "Confirm Password is required.");
                $("#loginpin").attr("data-parsley-required", false);
                $("#loginpin").val('');
            } else {
                $("#userDetails").css('display', 'none');
                $("#loginPinDetails").css('display', 'inline');
                $("#loginPinDetails1").css('display', 'inline');
                //$("#username").attr("data-parsley-required", false);
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