  <aside class="main-sidebar">
    <section class="sidebar">
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?=$profileimgg?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?=ucfirst($userinfo->name)?></p>
          <?=ucfirst($userinfo->typee)?>
        </div>
      </div>
     
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header"></li>
        <li class="active">
          <a href="index.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
        </li>
       <!--  <li class="treeview">
          <a href="#">
            <i class="fa fa-file-code-o"></i>
            <span>Masters</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          
          <ul class="treeview-menu">
            <li><a href="manage-state.php"><i class="fa fa-circle-o"></i> Manage State</a></li>
            <li><a href="manage-unit.php"><i class="fa fa-circle-o"></i> Manage Unit</a></li>  
            <li><a href="manage-supplier.php"><i class="fa fa-circle-o"></i> Manage Supplier</a></li>
            <li><a href="manage-customer.php"><i class="fa fa-circle-o"></i> Manage Customer</a></li>  
            
          </ul>
        </li>

        <li class="treeview">
          <a href="#">
            <i class="fa fa-users"></i>
            <span>Users Management</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
         <ul class="treeview-menu">
            <li><a href="manage-admin-user.php"><i class="fa fa-circle-o"></i> Admin Users</a></li>
            <li><a href="manage-opration-user.php"><i class="fa fa-circle-o"></i> Opration Users</a></li>
          </ul>
        </li>
        
        <li class="treeview">
          <a href="#">
            <i class="fa fa-archive"></i>
            <span>Manage Product</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             <li> <a href="manage-product-category.php"><i class="fa fa-product-hunt"></i> <span>Product Category</span></a></li>
             <li> <a href="manage-product.php"><i class="fa fa-product-hunt"></i> <span>Product</span></a></li>
          </ul>
        </li> -->
         
           <li class="treeview">
          <a href="#">
            <i class="fa fa-archive"></i>
            <span>Manage Inword</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             <li> <a href="master/create-inword.php"><i class="fa fa-product-hunt"></i> <span>Create Inword</span></a></li>
             <li> <a href="view-inword.php"><i class="fa fa-product-hunt"></i> <span>Inword Order Report</span></a></li>
          <li> <a href="inword-product-wish.php"><i class="fa fa-product-hunt"></i> <span>Inword Product-Wise Report</span></a></li>
          </ul>
        </li>
         <li class="treeview">
          <a href="#">
            <i class="fa fa-archive"></i>
            <span>Manage Outword</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             <li><a href="master/create-order.php"><i class="fa fa-circle-o"></i>Create Outword</a></li>
            <li><a href="outword-order-report.php"><i class="fa fa-circle-o"></i>Outword Order Report</a></li>
             <li> <a href="outword-product-wish.php"><i class="fa fa-product-hunt"></i> <span>Outword Product-Wise Report</span></a></li>
          </ul>
        </li>
         <li class="treeview">
          <a href="#">
            <i class="fa fa-archive"></i>
            <span>Manage PO</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             <li> <a href="purchase-order.php"><i class="fa fa-product-hunt"></i> <span>Purchase Order</span></a></li>
              <li> <a href="pending-po.php"><i class="fa fa-product-hunt"></i> <span>Pending PO</span></a></li>
              <li> <a href="verify-po.php"><i class="fa fa-product-hunt"></i> <span>verified PO</span></a></li>
              <li> <a href="verified-grn-details.php"><i class="fa fa-product-hunt"></i> <span>Verified GRN Report</span></a></li>
               <!--  <li> <a href="verified-grn-report.php"><i class="fa fa-product-hunt"></i> <span>Verified GRN Report</span></a></li> -->
          </ul>
        </li>
         <li class="treeview">
          <a href="#">
            <i class="fa fa-archive"></i>
            <span>Manage Report</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="report-product-stock.php"><i class="fa fa-circle-o"></i>Product Stock</a></li>
             <li><a href="transaction-report.php"><i class="fa fa-circle-o"></i>Transaction Report</a></li>
              <li> <a href="view-inword.php"><i class="fa fa-product-hunt"></i> <span>Inword Order Report</span></a></li>
          <li> <a href="inword-product-wish.php"><i class="fa fa-product-hunt"></i> <span>Inword Product-Wise Report</span></a></li>
           <li><a href="outword-order-report.php"><i class="fa fa-circle-o"></i>Outword Order Report</a></li>
             <li> <a href="outword-product-wish.php"><i class="fa fa-product-hunt"></i> <span>Outword Product-Wise Report</span></a></li>
              <li> <a href="reorder-product-report.php"><i class="fa fa-product-hunt"></i> <span>Reorder Product Report</span></a></li>
                <li> <a href="search-inword-outword-report.php"><i class="fa fa-product-hunt"></i> <span>Search IN/OUT Word Report</span></a></li>
          </ul>
        </li>  
        <li>
          <a href="profile.php"><i class="fa fa-user"></i> <span>My Profile</span></a>
        </li>    
        <li>
          <a href="logout.php"><i class="fa fa-user"></i> <span>Logout</span></a>
        </li>        
      </ul>
    </section>
  </aside>