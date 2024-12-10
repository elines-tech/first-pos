<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Profile</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><i class="fa fa-dashboard"></i> Dashboard</li>
                            <li class="breadcrumb-item active" aria-current="page">Profile Update</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        
        <section class="section">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                               
                                    <form class="form" id="editUserForm" enctype="multipart/form-data" data-parsley-validate method="post" action="<?php echo base_url(); ?>Profile/update"> 
                                        <?php
                                        echo "<div class='text-danger text-center' id='error_message'>";
                                        if (isset($error_message)) {
                                            echo $error_message;
                                        }    
                                        echo "</div>";        
                                        ?>
                                        <input type="hidden" id="code" readonly name="code" class="form-control" value="<?= $userData[0]['code'] ?>">
                                        <div class="row">
										    <div class="col-md-4 col-12">
                                                <div class="form-group mandatory">
                                                    <label class="form-label">Full Name</label>
                                                    <input type="test" id="fullname" class="form-control" placeholder="Full Name" name="fullname" data-parsley-required="true" value="<?= $userData[0]['name'] ?>">
                                                </div>
                                                <?php echo form_error('fullname', '<span class="error text-danger text-right">', '</span>'); ?>

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
                                                    <label for="arabicname-column" class="form-label">User Email</label>
                                                    <input type="email" id="useremail" class="form-control" placeholder="Email" name="useremail" data-parsley-required="true" value="<?= $userData[0]['userEmail'] ?>" pattern="^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$" data-parsley-type-message="Valid Email is required">
                                                </div>
                                                <?php echo form_error('useremail', '<span class="error text-danger text-right">', '</span>'); ?>

                                            </div>
                                                                 
                                               <div class="row">
												<div class="col-md-4 col-12">
                                                    <div class="form-group mandatory">
                                                        <label for="arabicname-column" class="form-label">User Name</label>
                                                        <input type="text" id="username" class="form-control" placeholder="User Name" name="username" value="<?= $userData[0]['userName'] ?>" oninput="this.value=this.value.replace(/[^a-z]/gi,'')" data-parsley-required="true" maxlength="20" 
													data-parsley-minlength="3" data-parsley-minlength-message="You need to enter at least 3 characters" data-parsley-trigger="change">
                                                    </div>
                                                    <?php echo form_error('username', '<span class="error text-danger text-right">', '</span>'); ?>
                                                </div> 
                                                  <div class="col-md-4">
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
		
		var data = '<?php echo $this->session->flashdata('message'); unset($_SESSION['message']);?>';
        if (data != '') {
            var obj = JSON.parse(data);
            if (obj.status) {
                toastr.success(obj.message, 'User', {
                    "progressBar": true
                });
            } else {
                toastr.error(obj.message, 'User', {
                    "progressBar": true
                });
            }
        }

    });
	
</script>