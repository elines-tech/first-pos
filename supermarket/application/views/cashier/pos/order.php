<?php
error_reporting(0);
$session_key = $this->session->userdata('cash_key' . CASH_SESS_KEY);
$role = ($this->session->userdata['cash_logged_in' . $session_key]['role']);
if (isset($this->session->userdata['cash_logged_in' . $session_key])) {
    $code = ($this->session->userdata['cash_logged_in' . $session_key]['code']);
    $username =  ($this->session->userdata['cash_logged_in' . $session_key]['username']);
    $name = ($this->session->userdata['cash_logged_in' . $session_key]['name']);
    $lang =  ($this->session->userdata['cash_logged_in' . $session_key]['lang']);
    $loginpin = ($this->session->userdata['cash_logged_in' . $session_key]['loginpin']);
    $role = ($this->session->userdata['cash_logged_in' . $session_key]['role']);
    $avatar = ($this->session->userdata['cash_logged_in' . $session_key]['userImage']);
} else {
    return redirect('Cashier/login', 'refresh');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="basesite" content="<?= base_url() ?>" />
    <meta name="userlang" content="<?= $lang ?>" />
    <meta name="userCode" content="<?= $code ?>" />
    <title>Point of Sale | <?= AppName ?></title>
    <link href="<?= base_url('assets/init_site/pos/bootstrap.min.css') ?>" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <link href="<?= base_url('assets/admin/assets/libs/sweetalert2/dist/sweet-alert.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/admin/assets/libs/toastr/build/toastr.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/init_site/pos/index.css') ?>" rel="stylesheet" />
</head>

<?php include '../supermarket/config.php'; ?>


<body>
    <div class="preloader">
        <span class="loader"></span>
    </div>
    <div class="main-pos-parent">
        <div class="pos-top-section mb-1">
            <div class="row g-0">
                <div class="col-2">
                    <a title="Back to Dashboard?" href="<?= base_url('Cashier/dashboard') ?>" class="btn btn-sm btn-secondary"><i class="fa fa-arrow-back" aria-hidden="true"></i><?php echo $translations['Dashboard']?></a>
                </div>
                <div class="col-10">
                    <ul style="cursor: pointer;" data-bs-toggle="dropdown" aria-expanded="false" class="float-end pos-ses-user">
                        <li><?= date('d-M-Y') ?></li>
                        <li>
                            <div class="user-detail"><span><?= ucwords($username) ?></span> <span><?= ucwords(strtolower($role)) ?></span></div>
                        </li>
                        <li>
                            <?php
                            $userAvatar = base_url('assets/images/user.png');
                            if ($avatar != "") $userAvatar = base_url($avatar);
                            ?>
                            <img src="<?= $userAvatar ?>" class="user-avatar" alt="<?= ucwords($username) ?>" />
                        </li>
                    </ul>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton" style="min-width: 11rem;">
                        <li>
                            <h6 class="dropdown-header">Hello, <?= $name ?>!</h6>
                        </li>
                        <form class="form" enctype="multipart/form-data" data-parsley-validate method="post" action="<?php echo base_url(); ?>Cashier/Profile/view">
                            <input type="hidden" name="code" value="<?= $code ?>">
                            <li><button class="dropdown-item" type="submit"><i class="icon-mid bi bi-person me-2"></i><?php echo $translations['My Profile']?></a></li>
                        </form>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="<?php echo base_url(); ?>Cashier/Authentication/logout"><i class="icon-mid bi bi-box-arrow-left me-2"></i><?php echo $translations['Logout']?></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="order-control my-1">
                <div class="row">
                    <div class="col-sm-3 text-center">
                        <button id="saveDefaultButton" class="btn-current-order w-100"><?php echo $translations['Current Order']?></button>
                    </div>
                    <div class="col-sm-9">
                        <div class="order-tabs <?= count($draftOrders) == 0 ? 'd-none' : '' ?>">
                            <button class="btn-prev" type="button"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i></button>
                            <ul class="tb-order-list">
                                <?php
                                $draftHtml = "";
                                $ocnt = 1;
                                foreach ($draftOrders as $draftOrder) {
                                    $draftHtml .= '<li id="dord-' . $draftOrder['orderId'] . '"><div class="tb-order-box" data-draft-order-id="' . $draftOrder['orderId'] . '"><span class="fa fa-times cancel-order"></span> <span class="text-order">Order #' . $ocnt . '</span></div></li>';
                                    $ocnt++;
                                }
                                echo $draftHtml;
                                ?>
                            </ul>
                            <button class="btn-next" type="button"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-2">
                <div class="col-sm-12 col-md-8 ">
                    <div class="order-item-section">
                        <div class="row g-2">
                            <div class="col-12 mb-3">
                                <div class="row g-2">
                                    <div class="col-8 col-sm-8 col-md-6">
                                        <input type="text" class="form-control" id="barcodeText" tabindex="1" placeholder=<?php echo $translations["Barcode"]?> aria-label="Barcode" aria-describedby="button-addon2" />
                                    </div>
                                    <div class="col-4 col-sm-4 col-md-6">
                                        <button class="btn btn-find-barcode" type="button" tabindex="2" id="button-addon2"><?php echo $translations['Search']?></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-3" style="height: 350px; overflow-y: auto">
                                <table class="table table-sm p-1" id="productTable">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th><?php echo $translations['Item']?></th>
                                            <th class="text-end"><?php echo $translations['Price']?></th>
                                            <th class="text-end"><?php echo $translations['Qty']?></th>
                                            <th class="text-end"><?php echo $translations['Discount']?></th>
                                            <th class="text-end"><?php echo $translations['Tax (%)']?></th>
                                            <th class="text-end"><?php echo $translations['Tax Amount']?></th>
                                            <th class="text-end"><?php echo $translations['Amount']?></th>
                                        </tr>
                                    </thead>
                                    <tbody id="order-rows">

                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="row g-0 sum-row mb-3">
                                    <div class="col-md-3 col-sm-6">
                                        <div class="sum-box">
                                            <div class="sum-box-title"><?php echo $translations['Items']?></div>
                                            <div class="sum-box-value itemsCountText">0</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="sum-box">
                                            <div class="sum-box-title"><?php echo $translations['Total']?></div>
                                            <div class="sum-box-value subTotalText">0.00</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="sum-box">
                                            <div class="sum-box-title"><?php echo $translations['Discount']?></div>
                                            <div class="sum-box-value discountTotalText">0.00</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="sum-box nobr">
                                            <div class="sum-box-title"><?php echo $translations['Offer Discount']?></div>
                                            <div class="sum-box-value offerDiscountText">0.00</div>
                                            <div class="offerInfo"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-0 sum-row">
                                    <div class="col-md-3 col-sm-6">
                                        <div class="sum-box">
                                            <div class="sum-box-title"><?php echo $translations['Gift Discount']?></div>
                                            <div class="sum-box-value giftDiscountText">0.00</div>
                                            <div class="giftcardInfo"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="sum-box">
                                            <div class="sum-box-title"><?php echo $translations['Tax Amount']?></div>
                                            <div class="sum-box-value taxTotalText">0.00</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="sum-box nobr">
                                            <div class="sum-box-title"><?php echo $translations['Total Payable']?></div>
                                            <div class="sum-box-value payableTotalText">0.00</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4">
                    <div class="order-item-section">
                        <div class="row g-2">
                            <div class="col-8 col-sm-8 col-md-8 col-lg-6">
                                <input type="text" class="form-control" id="offerCode" name="offerCode" placeholder=<?php echo $translations["Offer Code"]?> />
                            </div>
                            <div class="col-4 col-sm-4 col-md-4 col-lg-2">
                                <button title="Add Coupon" type="button" class="btn btn-add-offer"><span><?php echo $translations['Add']?></span></button>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-4 text-center">
                                <button title="Add New Customer" type="button" class="btn btn-new-customer"><i class="fa fa-user-circle-o"></i> <span><?php echo $translations['Add Customer']?></span></button>
                            </div>
                        </div>
                        <div class="row g-2 mt-2">
                            <div class="col-8 col-sm-8 col-md-8 col-lg-6">
                                <input type="text" class="form-control" id="giftNo" name="giftNo" placeholder=<?php echo $translations["Gift No"]?> />
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-4 text-center">
                                <button title="Apply Gift" type="button" class="btn btn-apply-gift"><span><?php echo $translations['Apply Gift']?></span></button>
                            </div>
                        </div>
                        <div class="row g-2 m-35">
                            <div class="col-12 mb-1">
                                <input type="hidden" id="amountdet" name="amountdet" readonly placeholder="amountdet">
                                <input type="hidden" id="tempOrderId" name="tempOrderId" readonly placeholder="tempOrderId">
                                <input type="hidden" id="customerCode" name="customerCode" readonly placeholder="customerCode">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-user"></i></span>
                                    <input class="form-control form-control-sm" id="customerName" name="customerName" placeholder=<?php echo $translations["Name"]?> readonly>
                                </div>
                            </div>
                            <div class="col-12 mb-1">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-phone"></i></span>
                                    <input class="form-control form-control-sm" id="customerPhone" name="customerPhone" placeholder=<?php echo $translations["Phone"]?> readonly>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="final-amt-box"><span class="finalAmountText">0.00</span></div>
                            </div>
                        </div>
                        <div class="row g-2 mb-1">
                            <div class="col text-center">
                                <button class="btn btn-cash"><?php echo $translations['Cash']?></button>
                            </div>
                            <div class="col text-center">
                                <button class="btn btn-card"><?php echo $translations['Credit/Debit Card']?></button>
                            </div>
                        </div>


                        <div class="row g-2 mb-1"> 
                            <div style="display: none;" class="col text-center">
                                <button class="btn btn-upi">UPI</button>
                            </div>
                            <div style="display: none;" class="col text-center">
                                <button class="btn btn-netbank">Net Banking</button>
                            </div>


                        <div class="row g-2 m-35">
                            <div class="col text-center">
                                <button class="btn btn-draft-order"><i class="fa fa-inbox"></i><span><?php echo $translations['Draft Order']?></span></button>
                            </div>
                            <div class="col text-center">
                                <button class="btn btn-clear-order"><i class="fa fa-refresh"></i><span><?php echo $translations['Clear Invoice']?></span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="newCustomerModel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="newCustomerLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newCustomerLabel"><?php echo $translations['New Customer?']?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="frmNewCust">
                        <div class="row">
                            <div class="form-group col-12 mb-3">
                                <label for="customerName"><?php echo $translations['Name of Customer']?></label>
                                <input type="text" class="form-control" name="newCustomerName" id="newCustomerName" data-parsley-pattern="^[a-zA-Z\s]+$">
                            </div>
                            <div class="form-group col-12">
                                <label for="newCustomerPhone"><?php echo $translations['Contact Number']?></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <select class="form-select" style="height: 100%;">
                                            <option value="+971">UAE</option>
                                            <option value="+966">SAR</option>
                                        </select>
                                    </div>
                                    <input type="number" class="form-control" id="newCustomerPhone" name="newCustomerPhone" required>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"><?php echo $translations['Close']?></button>
                    <button type="button" class="btn btn-sm btn-primary btn-create-customer"><?php echo $translations['Create?']?></button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="giftModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="newCustomerLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newCustomerLabel">Apply Gift</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="frmNewCust">
                        <div class="row">
                            <div class="form-group col-12 mb-3">
                                <label for="customerName">Name of Customer</label>
                                <input type="text" class="form-control" name="newCustomerName" id="newCustomerName" data-parsley-pattern="^[a-zA-Z\s]+$">
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-sm btn-primary btn-create-customer">Create?</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/extensions/parsleyjs/parsley.min.js') ?>"></script>
    <script src="<?= base_url('assets/admin/assets/libs/toastr/build/toastr.min.js') ?>"></script>
    <script src="<?= base_url('assets/admin/assets/libs/sweetalert2/dist/sweet-alert.min.js') ?>"></script>
    <script src="<?= base_url('assets/init_site/pos/index.js') ?>"></script>
</body>

</html>