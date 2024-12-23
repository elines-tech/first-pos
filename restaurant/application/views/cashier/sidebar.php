<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="align-items-center">
                <div class="logo">
                    <!--<a href="#"><img src="<?= base_url() ?>assets/images/logo/logomain.png" alt="Logo" srcset=""></a>-->
                    <a href="#"><img src="<?= base_url() ?>assets/images/logo/Group.svg" alt="Logo" srcset=""></a>
                </div>
                <div class="sidebar-toggler  x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-item"><a href="<?= base_url(); ?>Cashier/dashboard" class='sidebar-link'><i class="fa fa-dashboard"></i><span>Dashboard</span></a>
                <li class="sidebar-item"><a href="<?= base_url(); ?>Cashier/order/listRecords" class='sidebar-link'><i class="fa fa-shopping-cart"></i><span>POS Order</span></a>
                <li class="sidebar-item"><a href="<?= base_url(); ?>Cashier/orderList/getOrders" class='sidebar-link'><i class="fa fa-th-list"></i><span>Order List</span></a>
                <li class="sidebar-item has-sub "><a href="#" class='sidebar-link'><i class="fa fa-file"></i><span>Report</span></a>
                    <ul class="submenu ">
                        <li class="submenu-item"><a href="<?= base_url(); ?>Cashier/report/getDayClosingReport" class="side-anchors" data-attr="report">Day Closing Report</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>