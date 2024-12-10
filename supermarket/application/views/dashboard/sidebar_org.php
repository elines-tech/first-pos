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
			   <li class="sidebar-item"><a href="<?= base_url(); ?>Dashboard/listRecords" class='sidebar-link'><i class="fa fa-cubes"></i><span>Dashboard</span></a>          
                </li>
                <li class="sidebar-item has-sub "><a href="#" class='sidebar-link'><i class="fa fa-tags"></i><span>Product</span></a>
                    <ul class="submenu ">
                        <li class="submenu-item"><a href="<?= base_url(); ?>brand/listRecords" class="side-anchors" data-attr="brand">Brand</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>category/listRecords" class="side-anchors" data-attr="category">Category</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>subcategory/listRecords" class="side-anchors" data-attr="subcategory">Subcategory</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>product/listRecords" class="side-anchors" data-attr="product">Product</a></li>
                    </ul>
                </li>
                <li class="sidebar-item has-sub "><a href="#" class='sidebar-link'><i class="fa fa-barcode"></i><span>Inventory</span></a>
                    <ul class="submenu ">
                        <li class="submenu-item"><a href="<?= base_url(); ?>supplier/listRecords" class="side-anchors" data-attr="supplier">Supplier</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>inward/listRecords" class="side-anchors" data-attr="inward">Inward</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>inwardReturn/listRecords" class="side-anchors" data-attr="Inwardreturn">Inward Return</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>stock/listRecords" class="side-anchors" data-attr="stock">Stock</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>transfer/listRecords" class="side-anchors" data-attr="transfer">Transfer</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>barcode/listRecords" class="side-anchors" data-attr="barcode">Barcode Print</a></li>
                    </ul>
                </li>
				 <li class="sidebar-item has-sub "><a href="#" class='sidebar-link'><i class="fa fa-tags"></i><span>Account</span></a>
                    <ul class="submenu ">
                        <li class="submenu-item"><a href="<?= base_url(); ?>AccountExpense/listRecords" class="side-anchors" data-attr="accountexpense">Expense</a></li>
                    </ul>
                </li>
				<li class="sidebar-item has-sub "><a href="#" class='sidebar-link'><i class="fa fa-users" aria-hidden="true"></i><span>Customer</span></a>
                    <ul class="submenu">
                        <li class="submenu-item "><a href="<?= base_url(); ?>Customer/listRecords" class="side-anchors" data-attr="customer">Customer</a></li>
                        <li class="submenu-item "><a href="<?= base_url(); ?>CustomerGroup/listRecords" class="side-anchors" data-attr="customergroup">Customer Groups</a></li>
                    </ul>
                </li>
                <!--<li class="sidebar-item"><a href="<?= base_url('pos') ?>" class='sidebar-link'><i class="fa fa-barcode"></i><span>POS (Order)</span></a>-->
				<li class="sidebar-item has-sub "><a href="#" class='sidebar-link'><i class="fa fa-file"></i><span>Report</span></a>
                    <ul class="submenu ">
                        <li class="submenu-item"><a href="<?= base_url(); ?>order/listRecords" class="side-anchors" data-attr="order">Order List</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>report/getDayClosingReport" class="side-anchors" data-attr="report">Day Closing Report</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>ExpenseReport/getExpenseReport" class="side-anchors" data-attr="expensereport">Account Expense Report</a></li>
						 <li class="submenu-item"><a href="<?= base_url(); ?>PurchaseReport/getPurchaseReport" class="side-anchors" data-attr="purchasereport">Purchase Report</a></li>
                    </ul>
                </li>
                <li class="sidebar-item has-sub ">
                    <a href="#" class='sidebar-link'> <i class="fa fa-cog" aria-hidden="true"></i><span>Configuration</span></a>
                    <ul class="submenu">
                        <li class="submenu-item"><a href="<?= base_url(); ?>branch/listRecords" class="side-anchors" data-attr="branch">Branch</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>tax/listRecords" class="side-anchors" data-attr="tax">Taxes & Groups</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>baseunit/listRecords" class="side-anchors" data-attr="baseunit">Base Unit</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>unit/listRecords" class="side-anchors" data-attr="unit">Unit</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>counter/listRecords" class="side-anchors" data-attr="counter">Counter</a></li>
						<li class="submenu-item"><a href="<?= base_url(); ?>role/listRecords" class="side-anchors" data-attr="role">Users Roles</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>users/listRecords" class="side-anchors" data-attr="users">Users List</a></li>
                        <li class="submenu-item"><a href="<?= base_url(); ?>setting/listRecords" class="side-anchors" data-attr="setting">Setting</a></li>
                    </ul>
                </li>
				 <li class="sidebar-item has-sub "><a href="#" class='sidebar-link'><i class="fa fa-file"></i><span>Offers</span></a>
                    <ul class="submenu ">                
                        <li class="submenu-item"><a href="<?= base_url(); ?>offer/listRecords" class="side-anchors" data-attr="offer">Offer</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>