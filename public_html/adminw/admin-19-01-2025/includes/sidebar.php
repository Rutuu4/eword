	<aside class="main-sidebar">
    <section class="sidebar">
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?=$profileimgg?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?=ucfirst($name)?></p>
          <?=ucfirst($usertype)?>
        </div>
      </div>
     
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header"></li>
        <li class="active">
          <a href="index.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
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
            <li><a href="admin-user.php"><i class="fa fa-circle-o"></i> Admin Users</a></li>
            <li><a href="application-admin-user.php"><i class="fa fa-circle-o"></i> Application  Admin</a></li>
            <li><a href="register-user.php"><i class="fa fa-circle-o"></i> Register Users</a></li>
           <!--  <li><a href="refer-report.php"><i class="fa fa-circle-o"></i>Refer Report </a></li> -->
          </ul>
        </li>
         <li class="treeview">
          <a href="#">
            <i class="fa fa-users"></i>
            <span>Subscription Management</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
         <ul class="treeview-menu">
            <li><a href="subscription-package.php"><i class="fa fa-circle-o"></i>Subscription Package</a></li>
            <li><a href="active-subscription.php"><i class="fa fa-circle-o"></i> Active Subscription</a></li>
            <li><a href="payment-fail-subscription.php"><i class="fa fa-circle-o"></i> Payment Fail Subscription</a></li>
           
          
          </ul>
        </li>


        <li class="treeview">
          <a href="#">
            <i class="fa fa-database"></i>
            <span>Master</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
         <ul class="treeview-menu">
           <li><a href="manage-banner.php"><i class="fa fa-circle-o"></i> Banner List</a></li>
           <li><a href="manage-city.php"><i class="fa fa-circle-o"></i> City List</a></li>
            <li><a href="manage-year.php"><i class="fa fa-circle-o"></i> Year List</a></li>

             <li><a href="manage-main-menu-video.php"><i class="fa fa-circle-o"></i> Main Manu Video</a></li>
            
          
                
          </ul>
        </li>

       

          <li class="treeview">
          <a href="#">
            <i class="fa fa-file"></i>
            <span>Upload With CSV</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
         <ul class="treeview-menu">
             <!-- <li><a href="master/csv-main-course.php"><i class="fa fa-circle-o"></i>Upload Main Course CSV</a></li> -->
             <li><a href="master/csv-sub-course.php"><i class="fa fa-circle-o"></i>Upload Sub Course CSV</a></li>
            <li><a href="master/csv-course.php"><i class="fa fa-circle-o"></i>Upload Course CSV</a></li>
           <li><a href="master/csv-college-university.php"><i class="fa fa-circle-o"></i>Upload College University CSV</a></li>
            <li><a href="master/csv-website.php"><i class="fa fa-circle-o"></i>Upload Website CSV</a></li>
          
                
          </ul>
        </li>

     


        <li class="treeview">
          <a href="#">
            <i class="fa fa-book"></i>
            <span>Manage Course </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
         <ul class="treeview-menu">
          
            <li><a href="manage-main-course.php"><i class="fa fa-circle-o"></i> Main Course List</a></li>
            <li><a href="manage-sub-course.php"><i class="fa fa-circle-o"></i> Sub Course List</a></li>
            <li><a href="manage-course.php"><i class="fa fa-circle-o"></i> Course List</a></li>
       
                
          </ul>
        </li>

        <li class="treeview">
          <a href="#">
            <i class="fa fa-bank"></i>
            <span>Manage College/University </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
         <ul class="treeview-menu">
          
            <li><a href="manage-college-university-type.php"><i class="fa fa-circle-o"></i> College University Type</a></li>

             <li><a href="manage-college-university.php"><i class="fa fa-circle-o"></i> College University List</a></li>

              

                
          </ul>
        </li>

        <li class="treeview">
          <a href="#">
            <i class="fa fa-question-circle"></i>
            <span>Manage Question </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
         <ul class="treeview-menu">
          
            <li><a href="manage-question.php"><i class="fa fa-circle-o"></i> Question List</a></li>

                
          </ul>
        </li>
        
         <li class="treeview">
          <a href="#">
            <i class="fa fa-graduation-cap"></i>
            <span>Manage Admission Process</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
         <ul class="treeview-menu">
          
            <li><a href="manage-admission.php"><i class="fa fa-circle-o"></i> Admission Process List</a></li>

                
          </ul>
        </li>

           <li class="treeview">
          <a href="#">
            <i class="fa fa-globe"></i>
            <span>Manage Website </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
         <ul class="treeview-menu">
          
            <li><a href="manage-website.php"><i class="fa fa-circle-o"></i> Website List</a></li>
           

                
          </ul>
        </li>

        <li class="treeview">
          <a href="#">
            <i class="fa fa-gavel"></i>
            <span>Manage Cut off </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
         <ul class="treeview-menu">
          
            <li><a href="manage-cut-off.php"><i class="fa fa-circle-o"></i> Cut off List</a></li>

                
          </ul>
        </li>

        <li class="treeview">
          <a href="#">
            <i class="fa fa-drivers-license"></i>
            <span>Manage Scholarship/Loan </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
         <ul class="treeview-menu">
          
            <li><a href="manage-scholarship-loan.php"><i class="fa fa-circle-o"></i> Scholarship/Loan List</a></li>

                
          </ul>
        </li>
         <li class="treeview">
          <a href="#">
            <i class="fa fa-folder-open-o"></i>
            <span>Manage Document </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
         <ul class="treeview-menu">
          
            <li><a href="manage-document.php"><i class="fa fa-circle-o"></i> Document List</a></li>

                
          </ul>
        </li>
         <li class="treeview">
          <a href="#">
            <i class="fa fa-comment-o"></i>
            <span>Manage Chat </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
         <ul class="treeview-menu">
          <li><a href="manage-chat-course.php"><i class="fa fa-circle-o"></i> Chat Course</a></li>
          <li><a href="manage-chat-room-group.php"><i class="fa fa-circle-o"></i> Chat Room Group</a></li>
                
          </ul>
        </li>

        <li class="treeview">
          <a href="#">
            <i class="fa fa-bullhorn"></i>
            <span>Manage Help & Support </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
         <ul class="treeview-menu">
          <li>
          <a href="contact-details.php"><i class="fa fa-user"></i> <span>Manage Help & Support</span></a>
        </li>
                
          </ul>
        </li>

        <li class="treeview">
          <a href="#">
            <i class="fa fa-comment"></i>
            <span>Manage Notification</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
         <ul class="treeview-menu">
         <li><a href="coursewise-notification.php"><i class="fa fa-circle-o"></i> <span>Coursewise Notification</span></a> </li>
         <li><a href="citywise-notification.php"><i class="fa fa-circle-o"></i> <span>Citywise Notification</span></a> </li>

                
          </ul>
        </li>



        
    


        <li>
          <a href="logout.php"><i class="fa fa-power-off"></i> <span>Logout</span></a>
        </li>        
      </ul>
    </section>
  </aside>