<?php include_once("header.php"); ?>
<body>
    <nav class="navbar navbar-light">
        <div class="container d-block">
           <div class="row"> 
            <div class="col-12 col-md-6 order-md-1 order-last"><a href="customer-list.php"><i class="fa fa-times fa-2x"></i></a></div>

                            <div class="col-12 col-md-6 order-md-1 order-first">
                                <button type="submit" class="btn btn-success white mr-2 lng float-lg-end sub_1" id="sub_1" key="save_close">  Save  </button>
                            </div>
                        </div>
        </div>
    </nav>
   

<div class="container">
    
    <!-- // Basic multiple Column Form section start -->
    <section id="multiple-column-form" class="mt-5">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Add Customer</h3>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form" data-parsley-validate>
                                <div class="row">
                                     <div class="col-md-3 col-12">

<div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                <div class="card card-custom gutter-b bg-white border-0">
                                    <div class="card-body">
                                        <h3 class="mt-0 header-title lng text-center">Image</h3>

                                        <div class="col-md-12 col-sm-6 col-xs-6 mb-2 p-0 text-center">
                                            <img class="img-thumbnail mb-2" width="120px" id="logo_icon" src="../assets/images/faces/default-img.jpg" data-src="">
                                             <input class="form-control" type="file" id="formFile">
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
                                            <label for="supplier-name" class="form-label">Name</label>
                                            <input type="text" id="supplier-name" class="form-control" placeholder="Supplier Name" name="supplier-name" data-parsley-required="true">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="arabicname-column" class="form-label">Arabic Name</label>
                                            <input type="text" id="arabicname" class="form-control" placeholder="Arabic Name" name="arabicname" >
                                        </div>
                                    </div>
                                </div>
                                 <div class="row">
                                   <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="company-name" class="form-label">Company Name</label>
                                            <input type="text" id="company-name" class="form-control" placeholder="Company Name" name="company-name">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="email-column" class="form-label">Email</label>
                                            <input type="email" id="email" class="form-control" placeholder="Email" name="email" >
                                        </div>
                                    </div>
                                </div>
                                 <div class="row">
                                    <div class="col-md-6 col-12">
                                     <div class="form-group row align-items-center">
                                                        
                                                            <label for="Country" class="form-label">Country</label>
                                            
                                                      
                                                            <fieldset class="form-group">
                                        <select class="form-select" id="country" name="country">
                                            <option value="">Select Country</option>
                                            <option value="">Package 2</option>
                                            <option value="">Package 3</option>
                                        </select>
                                    </fieldset>
                                                      
</div>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                     <div class="form-group row align-items-center">
                                                        
                                                            <label for="State" class="form-label">State</label>
                                            
                                                      
                                                            <fieldset class="form-group">
                                        <select class="form-select" id="state" name="state">
                                            <option value="">Select State</option>
                                            <option value="">Package 2</option>
                                            <option value="">Package 3</option>
                                        </select>
                                    </fieldset>
                                                      
</div>
                                                    </div>
                                                </div>
                                                 <div class="row">
                                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="City" class="form-label">City</label>
                                            <input type="text" id="city" class="form-control" placeholder="City" name="city">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="pincode" class="form-label">Postal Code</label>
                                            <input type="number" id="pincode" class="form-control" placeholder="Postal Code" name="pincode" >
                                        </div>
                                    </div>
                                </div>
                                 <div class="row">
                                      <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="Phone" class="form-label">Phone</label>
                                            <input type="number" id="phone" class="form-control" placeholder="Phone" name="phone">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="Tax" class="form-label">Tax #</label>
                                            <input type="text" id="tax" class="form-control" placeholder="Tax" name="tax" >
                                        </div>
                                    </div>
                                </div>
                                 <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="financial" class="form-label">Financial Account</label>
                                            <input type="text" id="financial" class="form-control" placeholder="Financial Account" name="financial" disabled="">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-12">
                                        <div class="form-group">
                              <label class="form-label lng" key="status">Status</label>
                              <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text bg-soft-primary">
                                    <input type="checkbox" name="status"  class="form-check-input" checked=""></span>
                                </div>
                              </div>
                            </div>
                                            
                                    </div>
                                </div>
 <div class="row">
                                    <div class="col-md-12 col-12">
                                        <div class="form-group">
                                            <label for="addr-column" class="form-label">Address</label>
                                             <textarea class="form-control" placeholder="Address"
                                id="address" name="address"></textarea>
                                        </div>
                                    </div>
                                   
</div>
                                    </div>
                                   
                                </div>
                               
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-success white me-3 mb-1 sub_1">Save</button>
                                        <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
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


</body>
                  <?php include_once("footer.php"); ?>

