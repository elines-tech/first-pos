<nav class="navbar navbar-light">
    <div class="container d-block">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <a href="<?php echo base_url(); ?>Users/listrecords"><i class="fa fa-times fa-2x"></i></a>
            </div>
        </div>
    </div>
</nav>
<div class="container">
    <section id="multiple-column-form" class="mt-5">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Update User</h3>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-6">
                                    <form class="form" id="editUserForm" enctype="multipart/form-data" data-parsley-validate method="post" action="<?php echo base_url(); ?>Users/update">
                                        <?php
                                        echo "<div class='text-danger text-center' id='error_message'>";
                                        if (isset($error_message)) {
                                            echo $error_message;
                                        }
                                        echo "</div>";
                                        ?>
                                        <input type="hidden" id="code" readonly name="code" class="form-control" value="<?= $userData[0]['code'] ?>">
                                        <div class="row">
                                            <div class="col-md-12 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="product-name" class="form-label">Branch Name</label>
                                                    <input type="hidden" readonly name="invoicePreference" value="autocut" />
                                                    <?php if($branchCode!=""){?>
														  <input type="hidden" class="form-control" name="branchname" id="branchname" value="<?= $branchCode; ?>" readonly>
														  <input type="text" class="form-control" name="branch" value="<?= $branchName; ?>" readonly>
													<?php } else{?>
													<select class="form-select" name="branchname" id="branchname" data-parsley-required="true">
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
                                            <div class="col-md-12 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="arabicname-column" class="form-label">User Name</label>
                                                    <input type="text" id="username" class="form-control" placeholder="User Name" name="username" value="<?= $userData[0]['userName'] ?>" oninput="this.value=this.value.replace(/[^a-z]/gi,'')" data-parsley-required="true" maxlength="20" 
													data-parsley-minlength="3" data-parsley-minlength-message="You need to enter at least 3 characters" data-parsley-trigger="change">
                                                </div>
                                                <?php echo form_error('username', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>
                                            <div class="col-md-12 col-12">
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
                                            <div class="col-md-12 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="arabicname-column" class="form-label">User Employee Number</label>
                                                    <input type="text" id="userempnumber" class="form-control" placeholder="User Employee Number" name="userempnumber" value="<?= $userData[0]['userEmpNo'] ?>" data-parsley-required="true" onkeypress="return isNumberKey(event)">
                                                </div>
                                                <?php echo form_error('userempnumber', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>
                                            <div class="col-md-12 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="arabicname-column" class="form-label">User Email</label>
                                                    <input type="email" id="useremail" class="form-control" placeholder="Email" name="useremail" data-parsley-required="true" value="<?= $userData[0]['userEmail'] ?>" pattern="^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$" data-parsley-type-message="Valid Email is required">
                                                </div>
                                                <?php echo form_error('useremail', '<span class="error text-danger text-right">', '</span>'); ?>
                                            </div>
                                            <div class="col-md-12 col-12">
                                                <div class="form-group mandatory">
                                                    <label for="product-name" class="form-label">User Role</label>
                                                    <select class="form-select" name="userrole" id="userrole" data-parsley-required="true">
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
                                            <div id="userDetails" style="display:<?= $userData[0]['userRole'] != 'R_6' ? '' : 'none' ?>">

                                                <div class="row">
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label for="arabicname-column" class="form-label">Password</label>
                                                            <input type="password" id="password" class="form-control" placeholder="Password" name="password">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label for="arabicname-column" class="form-label">Confirm Password</label>
                                                            <input type="password" id="confirmpassword" class="form-control" placeholder="Confirm Password" name="confirmpassword" onchange="checkPasswordMatch();">
                                                        </div>
                                                        <div id="CheckPasswordMatch" style="color:#e66060;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="loginPinDetails" style="display:<?= $userData[0]['userRole'] == 'R_6' ? '' : 'none' ?>">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group mandatory">
                                                            <label class="form-label">Login Pin</label>
                                                            <input type="text" id="loginpin" value="<?= $userData[0]['loginpin'] ?>" class="form-control" placeholder="Login Pin" name="loginpin" readonly>

                                                        </div>
                                                    </div>
                                                    <?php echo form_error('loginpin', '<span class="error text-danger text-right">', '</span>'); ?>

                                                    <div class="col-md-4 mt-sm-4">

                                                        <a class="btn btn-light-secondary me-1 mb-1 cursor-pointer" id="reLoginPin" onclick="randomNumber();">Regenerate</a>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Image</label>
                                                    <div class="col-md-5 col-sm-6 col-xs-6 mb-2 p-0 text-left">
                                                        <?php if ($userData[0]['userImage'] != "") { ?>
                                                            <img class="img-thumbnail mb-2" width="120px" id="userimg" src="<?= base_url() . $userData[0]['userImage'] ?>" data-src="">
                                                        <?php } else { ?>
                                                            <img class="img-thumbnail mb-2" width="120px" id="userimg" src="/assets/images/faces/default-img.jpg" data-src="">
                                                        <?php } ?>

                                                        <input class="form-control" type="file" id="formFile" name="userImage">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-12 mb-3">
                                                <div class="form-group row">
                                                    <label for="status" class="col-sm-2 col-form-label">Active:</label>
                                                    <div class="col-sm-10">
                                                        <div class="checkbox col-md-4 col-form-label">
                                                            <input type="checkbox" name="isActive" id="isActive" class=" " style="width:25px; height:25px" <?php if ($userData[0]['isActive'] == 1) {
                                                                                                                                                                echo "checked";
                                                                                                                                                            } ?>>
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
                                <div class="col-sm-3"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- // Basic multiple Column Form section end -->
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
        debugger;
        var confirmPassword = $("#confirmpassword").val();
        if (password != confirmPassword) {
            $('#submit').prop('disabled', true);
            $("#CheckPasswordMatch").html("Passwords does not match!");
        } else {
            $('#submit').prop('disabled', false);
            $("#CheckPasswordMatch").html("");
        }
    }

    function randomNumber() {
        var number = Math.floor(1000 + Math.random() * 9999);
        $('#loginpin').val(number);
    }
    $(document).ready(function() {

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
        $("body").on("change", "#userrole", function(e) {
            debugger
            var thisVal = $(this).find('option:selected').val();
            var thisVal = $(this).val();
            if (thisVal != "R_6") {
                $("#userDetails").show();
                $("#loginPinDetails").hide();

                $("#password").attr("data-parsley-required", true);
                $("#confirmpassword").attr("data-parsley-required", true);

                $("#password").attr("data-parsley-required-message", "Password is required.");
                $("#confirmpassword").attr("data-parsley-required-message", "Confirm Password is required.");
                $("#loginpin").attr("data-parsley-required", false);


            } else {
                $("#userDetails").hide();
                $("#loginPinDetails").show();

                $("#password").attr("data-parsley-required", false);
                $("#confirmpassword").attr("data-parsley-required", false);
                $("#loginpin").attr("data-parsley-required", true);
                $("#loginpin").attr("data-parsley-required-message", "Login Pin is required.");
            }
        });


    });


    /* $(document).on("click", "button#editUser", function(e) {
         $('#editUserForm').parsley();
         const form = document.getElementById('editUserForm');
         var formData = new FormData(form);
         var isValid = true;
         event.preventDefault();
         $("#editUserForm .form-control").each(function(e) {
             if ($(this).parsley().validate() !== true) isValid = false;
         });
         $("#editUserForm .form-select").each(function(e) {
             if ($(this).parsley().validate() !== true) isValid = false;
         });
         if (isValid) {
             let email = $("#useremail").val();
             let empno = $("#userempnumber").val();
             let code = $("#code").val();
             var isActive = 0;
             if (empno != "") {
                 $.ajax({
                     type: 'get',
                     async: false,
                     url: '<?php echo base_url() . 'Users/empnoDuplicateOnUpdate' ?>',
                     data: {
                         'empno': empno,
                         'code': code,
                     },
                     dataType: "JSON",
                     success: function(response) {
                         if (response.status == true) {
                             $("#empnoDuplicate").text(response.message);
                             setTimeout(() => {
                                 $("#empnoDuplicate").empty();
                             }, 5000);
                             return false;
                         } else {
                             cnt++;
                         }
                     }
                 });
             }
             if (email != "") {
                 $.ajax({
                     type: 'get',
                     url: '<?php echo base_url() . 'Users/emailDuplicateOnUpdate' ?>',
                     async: false,
                     data: {
                         'email': email,
                         'code': code,
                     },
                     dataType: "JSON",
                     success: function(response) {
                         if (response.status == true) {
                             $("#emailDuplicate").text(response.message);
                             setTimeout(() => {
                                 $("#emailDuplicate").empty();
                             }, 5000);
                             return false;
                         } else {
                             cnt++;
                         }

                     }
                 });
             }
             if (cnt >= 2) {
                 $.ajax({
                     url: base_path + "Users/update",
                     type: 'POST',
                     data: formData,
                     cache: false,
                     contentType: false,
                     processData: false,
                     beforeSend: function() {
                         $('#editUser').prop('disabled', true);
                         $('#editUser').text('Please wait..');
                     },
                     success: function(response) {
                         $('#editUser').prop('disabled', false);
                         var obj = JSON.parse(response);
                         if (obj.status) {
                             toastr.success(obj.message, 'Users', {
                                 "progressBar": true
                             });
                             window.location.href = base_path + "Users/listRecords";
                         } else {
                             toastr.error(obj.message, 'Users', {
                                 "progressBar": true
                             });
                         }
                     }
                 });
             }
             console.log(true);
         } else {
             console.log("failed");
         }
     });*/
</script>