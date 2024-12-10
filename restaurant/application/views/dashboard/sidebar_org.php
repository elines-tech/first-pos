<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="align-items-center">
                <div class="logo">
                    <a href="#"><img src="<?= base_url() ?>assets/images/logo/logomain.png" alt="Logo" srcset=""></a>
                </div>
                <div class="sidebar-toggler  x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-item has-sub active "><a href="#" class='sidebar-link'><i class="fa fa-cubes"></i><span>Dashboard</span></a>
                    <ul class="submenu ">
                        <li class="submenu-item"><a href="<?= base_url(); ?>Dashboard/listRecordsSales" class="side-anchors" data-attr="category">Sales</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>Dashboard/listRecords" class="side-anchors" data-attr="unit">Inventory</a></li>

                    </ul>
                </li>
                <li class="sidebar-item has-sub "><a href="#" class='sidebar-link'><i class="fa fa-cubes"></i><span>Menu</span></a>
                    <ul class="submenu ">

                        <li class="submenu-item"><a href="<?= base_url(); ?>ProductCategory/listRecords" class="side-anchors" data-attr="productcategory">Product Category</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>ProductSubcategory/listRecords" class="side-anchors" data-attr="productsubcategory">Product SubCategory</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>product/listRecords" class="side-anchors" data-attr="product">Product</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>recipe/listRecords" class="side-anchors" data-attr="recipe">Recipe</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>ProductCombo/listRecords" class="side-anchors" data-attr="productcombo">Product Combo</a></li>

                    </ul>
                </li>
                <li class="sidebar-item has-sub "><a href="#" class='sidebar-link'><i class="fa fa-tags"></i><span>Inventory</span></a>
                    <ul class="submenu ">
                        <li class="submenu-item"><a href="<?= base_url(); ?>category/listRecords" class="side-anchors" data-attr="category">Item Category</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>item/listRecords" class="side-anchors" data-attr="item">Item</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>supplier/listRecords" class="side-anchors" data-attr="supplier">Supplier</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>inward/listRecords" class="side-anchors" data-attr="inward">Inward</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>stock/listRecords" class="side-anchors" data-attr="stock">Stock</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>transfer/listRecords" class="side-anchors" data-attr="transfer">Transfer</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>inwardReturn/listRecords" class="side-anchors" data-attr="inwardreturn">Inward Return</a></li>
                    </ul>
                </li>
                <li class="sidebar-item has-sub "><a href="#" class='sidebar-link'><i class="fa fa-tags"></i><span>Account</span></a>
                    <ul class="submenu ">
                        <li class="submenu-item"><a href="<?= base_url(); ?>AccountExpense/listRecords" class="side-anchors" data-attr="accountexpense">Expense</a></li>
                    </ul>
                </li>
                <li class="sidebar-item has-sub ">
                    <a href="#" class='sidebar-link'>
                        <i class="fa fa-users" aria-hidden="true"></i><span>Customer</span>
                    </a>
                    <ul class="submenu">
                        <li class="submenu-item ">
                            <a href="<?= base_url(); ?>Customer/listRecords" class="side-anchors" data-attr="customer">Customer</a>
                        </li>
                        <li class="submenu-item ">
                            <a href="<?= base_url(); ?>CustomerGroup/listRecords" class="side-anchors" data-attr="customergroup">Customer Groups</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item has-sub "><a href="#" class='sidebar-link'><i class="fa fa-shopping-cart"></i><span>Orders</span></a>
                    <ul class="submenu ">
                        <li class="submenu-item"><a href="<?= base_url(); ?>kitchen/listRecords" class="side-anchors" data-attr="kitchen">Kitchen Orders</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>order/listRecords" class="side-anchors" data-attr="order">Counters Orders</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>OrderList/getOrders" class="side-anchors" data-attr="OrderList">Orders</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>quotation/listRecords" class="side-anchors" data-attr="quotation">Sale Quotation</a></li>
                    </ul>
                </li>

                <li class="sidebar-item has-sub "><a href="#" class='sidebar-link'><i class="fa fa-file"></i><span>Reports</span></a>
                    <ul class="submenu ">
                        <li class="submenu-item"><a href="<?= base_url(); ?>saleReport/list" class="side-anchors" data-attr="saleReport">Sale Report</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>purchaseReport/list" class="side-anchors" data-attr="purchaseReport">Purchase Report</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>transferReport/list" class="side-anchors" data-attr="transferReport">Transfer Report</a></li>
                    </ul>
                </li>
                <li class="sidebar-item has-sub ">
                    <a href="index.php" class='sidebar-link'>
                        <i class="fa fa-cog" aria-hidden="true"></i>
                        <span>Configuration</span>
                    </a>
                    <ul class="submenu">
                        <li class="submenu-item"><a href="<?= base_url(); ?>branch/listRecords" class="side-anchors" data-attr="branch">Branch</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>sectorzone/listRecords" class="side-anchors" data-attr="sectorzone">Sector Zone</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>table/listRecords" class="side-anchors" data-attr="table">Table</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>Reservation/listRecords" class="side-anchors" data-attr="reservation">Table Reservation</a></li>
						<li class="submenu-item"><a href="<?= base_url(); ?>role/listRecords" class="side-anchors" data-attr="role">User Roles</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>users/listRecords" class="side-anchors" data-attr="users">Users List</a></li>       
                        <li class="submenu-item"><a href="<?= base_url(); ?>tax/listRecords" class="side-anchors" data-attr="tax">Taxes & Groups</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>unit/listRecords" class="side-anchors" data-attr="unit">Unit</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>roleWiseRights/listRecords" class="side-anchors" data-attr="rolewiserights">Role Wise Rights</a></li>

                    </ul>
                </li>
                <li class="sidebar-item has-sub "><a href="#" class='sidebar-link'><i class="fa fa-file"></i><span>Offers/Discounts</span></a>
                    <ul class="submenu ">
                        <li class="submenu-item"><a href="<?= base_url(); ?>discount/listRecords" class="side-anchors" data-attr="discount">Discount</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>coupon/listRecords" class="side-anchors" data-attr="coupon">Coupon</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>offer/listRecords" class="side-anchors" data-attr="offer">Offer</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>