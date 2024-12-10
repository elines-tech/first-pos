<div id="main" class='layout-navbar'>
            <header class='mb'>
                <nav class="navbar navbar-expand navbar-light navbar-top">
                    <div class="container-fluid">
                        <a href="#" class="burger-btn d-block">
                            <i class="bi bi-justify fs-3"></i>
                        </a>

                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto mb-lg-0">
                                <li class="nav-item dropdown me-1">
                                    <a class="nav-link active dropdown-toggle text-gray-600" href="#" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class='bi bi-envelope bi-sub fs-4'></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                        <li>
                                            <h6 class="dropdown-header">Mail</h6>
                                        </li>
                                        <li><a class="dropdown-item" href="#">No new mail</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown me-3">
                                    <a class="nav-link active dropdown-toggle text-gray-600" href="#" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                        <i class='bi bi-bell bi-sub fs-4'></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="dropdownMenuButton">
                                        <li class="dropdown-header">
                                            <h6>Notifications</h6>
                                        </li>
                                        <li class="dropdown-item notification-item">
                                            <a class="d-flex align-items-center" href="#">
                                                <div class="notification-icon bg-primary">
                                                    <i class="bi bi-cart-check"></i>
                                                </div>
                                                <div class="notification-text ms-4">
                                                    <p class="notification-title font-bold">Successfully check out</p>
                                                    <p class="notification-subtitle font-thin text-sm">Order ID #256</p>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="dropdown-item notification-item">
                                            <a class="d-flex align-items-center" href="#">
                                                <div class="notification-icon bg-success">
                                                    <i class="bi bi-file-earmark-check"></i>
                                                </div>
                                                <div class="notification-text ms-4">
                                                    <p class="notification-title font-bold">Homework submitted</p>
                                                    <p class="notification-subtitle font-thin text-sm">Algebra math homework</p>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <p class="text-center py-2 mb-0"><a href="#">See all notification</a></p>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                            <div class="dropdown">
                                <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="user-menu d-flex">
                                        <div class="user-name text-end me-3">
                                            <h6 class="mb-0 text-gray-600">John Ducky</h6>
                                            <p class="mb-0 text-sm text-gray-600">Administrator</p>
                                        </div>
                                        <div class="user-img d-flex align-items-center">
                                            <div class="avatar avatar-md">
                                                <img src="../assets/images/faces/1.jpg">
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton" style="min-width: 11rem;">
                                    <li>
                                        <h6 class="dropdown-header">Hello, John!</h6>
                                    </li>
                                    <li><a class="dropdown-item" href="#"><i class="icon-mid bi bi-person me-2"></i> My
                                            Profile</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="icon-mid bi bi-gear me-2"></i>
                                            Settings</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="icon-mid bi bi-wallet me-2"></i>
                                            Wallet</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="#"><i class="icon-mid bi bi-box-arrow-left me-2"></i> Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>
            <div id="main-content">
                
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>User Roles</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Usesr Roles</li>
                    </ol>
                </nav>
            </div>

        </div>
    </div>
    <div id="maindiv" class="container">
     <div class="row">
        
            <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">

                 <h2><a href="#generl_modal" data-bs-toggle="modal"
                            data-bs-target="#generl_modal"><i class="fa fa-plus-circle cursor_pointer"></i></a></h2>
           </div>
            </div>
        </div>      
    <!-- Basic Tables start -->
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="row">
        
            <div class="col-12 col-md-6 order-md-1 order-last" id="leftdiv">
                <h5>Users Role List</h5>
                
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first" id="rightdiv">
            <div class="btn-group btn-group-sm float-lg-end" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-primary sub_1" onclick="printDiv('print_div')">Print</button>
                        </div>
            </div>
        </div>
        
            </div>
            <div class="card-body" id="print_div">
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Role Name</th>
                            
                             <th>Created</th>
                            
                            
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                             <td>Admin</td>
                             
                            <td>2022-07-19 19:30:36</td>
                            
                           
                             <td>
                               
                                  <a href="#generl_modal1" data-bs-toggle="modal"
                            data-bs-target="#generl_modal1"><i id="edt" title="Edit" class="fa fa-pencil text-info cursor_pointer" ></i></a>
                                  <i id="dlt" title="Delete" class="fa fa-trash text-danger mx-2 cursor_pointer" data-cdd="1f306216-4132-4cc0-aabe-afbc602c3670"></i></td>
                            
                        </tr>
                         <tr>
                           <td>1</td>
                             <td>Admin</td>
                             
                            <td>2022-07-19 19:30:36</td>
                            
                           
                             <td>
                               
                                  <a href="#generl_modal1" data-bs-toggle="modal"
                            data-bs-target="#generl_modal1"><i id="edt" title="Edit" class="fa fa-pencil text-info cursor_pointer" ></i></a>
                                  <i id="dlt" title="Delete" class="fa fa-trash text-danger mx-2 cursor_pointer" data-cdd="1f306216-4132-4cc0-aabe-afbc602c3670"></i></td>
                            
                        </tr>
                         <tr>
                           <td>1</td>
                             <td>Admin</td>
                             
                            <td>2022-07-19 19:30:36</td>
                            
                           
                             <td>
                               
                                  <a href="#generl_modal1" data-bs-toggle="modal"
                            data-bs-target="#generl_modal1"><i id="edt" title="Edit" class="fa fa-pencil text-info cursor_pointer" ></i></a>
                                  <i id="dlt" title="Delete" class="fa fa-trash text-danger mx-2 cursor_pointer" data-cdd="1f306216-4132-4cc0-aabe-afbc602c3670"></i></td>
                            
                            
                        </tr>
                         
                       
                        
                    </tbody>
                </table>
            </div>
        </div>

    </section>
    <!-- Basic Tables end -->
</div>
</div>
</div>
</div>
</body>
<div class="modal fade text-left" id="generl_modal" tabindex="-1" role="dialog"
                                            aria-labelledby="myModalLabel130" aria-hidden="true" >
                                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
                                                role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5>Add user Role</h5>
                                                    </div>
                                                    
                                                    <div class="modal-body">
                                                       <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel">
               
                <div class="panel-body1">
                    

<form class="form" data-parsley-validate>
                                <div class="row">
                                     <div class="col-md-12 col-12">
                                        <div class="form-group row">
                            <label for="category-name-column" class="col-md-4 form-label text-left">Name</label>
                            <div class="col-md-8">
                                 <input type="text" id="rolename" class="form-control" placeholder="Role Name" name="rolename" >
                            </div>
                        </div>
                                       
                                    </div>
                                     
                                   
                                    
                                </div>
                               
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary white me-2 mb-1 sub_1">Save</button>
                                        <button type="reset" class="btn btn-light-secondary me-1 mb-1" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </form>
                                   </div>  
            </div>
        </div>
    </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>


<div class="modal fade text-left" id="generl_modal1" tabindex="-1" role="dialog"
                                            aria-labelledby="myModalLabel130" aria-hidden="true" >
                                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
                                                role="document">
                                                <div class="modal-content">
                                                    
                                                    <div class="modal-header">
                                                        <h5>Update Role</h5>
                                                    </div>
                                                    <div class="modal-body">
                                                       <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel">
               
                <div class="panel-body1">
                    

<form class="form" data-parsley-validate>
                                <div class="row">
                                     <div class="col-md-12 col-12">
                                        <div class="form-group row">
                            <label for="category-name-column" class="col-md-4 form-label text-left">Name</label>
                            <div class="col-md-8">
                                 <input type="text" id="rolename" class="form-control" placeholder="Role Name" name="rolename" >
                            </div>
                        </div>
                                       
                                    </div>
                                     
                                   
                                    
                                    
                                </div>
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary white me-2 mb-1 sub_1">Update</button>
                                        <button type="reset" class="btn btn-light-secondary me-1 mb-1" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </form>
                                   </div>  
            </div>
        </div>
    </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>