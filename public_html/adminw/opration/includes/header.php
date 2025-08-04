	<style>
    .dropdown-web{
      box-shadow: 8px 9px 20px 0px #0000008c;
    }
  </style>
  <?php
    if(empty($profile)){
      $profileimgg = "../dist/img/avatar04.png";
    }else{
      $profileimgg = $profile;
    }
  ?>
  <header class="main-header">
    <a href="" class="logo" style="padding: 0px">
      <span class="logo-mini"></span>
      <span class="logo-lg"><b><img src="../logo.png" style="height: 50px;width: 230px;" ></b></span>
    </a>
    <nav class="navbar navbar-static-top">
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?=$profileimgg?>" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo ucfirst($name); ?></span>
            </a>
            <ul class="dropdown-menu dropdown-web">
              <li class="user-header">
                
                <img src="<?=$profileimgg?>" class="img-circle" alt="User Image">
                <p><?=ucfirst($name) ?> - <?=ucfirst($usertype) ?></p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="profile.php" style="color: black;font-size: 16px;" class="btn btn-warning btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="logout.php" style="color: black;font-size: 16px;" class="btn btn-danger btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header> 