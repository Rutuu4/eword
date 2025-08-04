<!DOCTYPE HTML>
<!--[if lt IE 7 ]> <html xmlns="http://www.w3.org/1999/xhtml" class="ie ie6"> <![endif]-->
<!--[if IE 7 ]>    <html xmlns="http://www.w3.org/1999/xhtml" class="ie ie7"> <![endif]-->
<!--[if IE 8 ]>    <html xmlns="http://www.w3.org/1999/xhtml" class="ie ie8"> <![endif]-->
<!--[if IE 9 ]>    <html xmlns="http://www.w3.org/1999/xhtml" class="ie ie9"> <![endif]-->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html" charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="keywords" content="Website"/>
<meta name="description" content="Website Description"/>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

<title><?php echo ucwords(!empty($this->page_title)?$this->page_title:$this->config->item('sitename')); ?> - Admin</title>

<!-- Favicon Icon -->
<link rel="icon" href="<?php echo base_url(); ?>images/favicon/favicon.png" type="image/png">
<!-- <link rel="icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/x-icon">
<link rel="icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/icon"> -->

<link rel="stylesheet" href="<?= $this->config->item('css_url') ?>bootstrap.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

<link rel="stylesheet" href="<?= $this->config->item('css_url') ?>admin/AdminLTE.min.css">
<link rel="stylesheet" href="<?= $this->config->item('css_url') ?>admin/_all-skins.min.css">
<link rel="stylesheet" href="<?= $this->config->item('css_url') ?>admin/custom.css">
<link rel="stylesheet" href="<?= $this->config->item('css_url') ?>admin/animate.css">
<link rel="stylesheet" href="<?= $this->config->item('css_url') ?>jquery-confirm.css">
<link rel="stylesheet" href="<?= $this->config->item('css_url') ?>bootstrap3-wysihtml5.min.css">
<link rel="stylesheet" type="text/css" href="<?= $this->config->item('css_url') ?>tooltipster.bundle.min.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<script type="text/javascript" src="<?= $this->config->item('js_url') ?>jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="<?=$this->config->item('js_url')?>common.js"></script>
</head>
<?php 
$notifications = admin_notification();
?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

 <header class="main-header">
  <a href="<?=base_url('admin/dashboard');?>" alt="Logo" class="logo">
    
    <span class="logo-mini"><img alt="Admin" src="<?=!empty($this->site_info[0]['site_logo']) && file_exists($this->config->item('image_site_logo').$this->site_info[0]['site_logo'])?$this->config->item('image_site_logo_url').$this->site_info[0]['site_logo']:$this->config->item('base_url').'images/logo.png'?>" style="width: 100%; height: 100%"/></span>

    <span class="logo-lg" style="display: inline">
    <img alt="Admin" src="<?=!empty($this->site_info[0]['site_logo']) && file_exists($this->config->item('image_site_logo').$this->site_info[0]['site_logo'])?$this->config->item('image_site_logo_url').$this->site_info[0]['site_logo']:$this->config->item('image_url').'logo.png'?>" style="height: 100%"/>
    </span>
             
  </a>
  <nav class="navbar navbar-static-top">
    

    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning"><?php echo !empty($notifications)?count($notifications):'' ?></span>
            </a>
            <ul class="dropdown-menu">
              <?php 
                if(!empty($notifications))
                {
                ?>
                  <li class="header">You have <?php echo !empty($notifications)?count($notifications):'' ?> notifications</li>
                  <?php 
                }
                ?> 
                <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <?php 
                    if(!empty($notifications))
                    {
                      foreach ($notifications as $value) {                      
                        $json = json_decode($value['notification']);
                        if(!empty($json))
                            $url = $json->url;
                        else
                            $url = 'javascript:void(0);';
                        $font_awesome = 'fa fa-check-square';
                    ?>
                        <li>
                          <a href="<?=$url?>">
                            <i class="<?=$font_awesome?> text-aqua"></i>
                            <?php echo !empty($value['text'])?$value['text']:''?>
                          </a>
                        </li>
                  <?php
                      }
                    }
                  ?>
                  
                </ul>
              </li>
              <!-- <li class="footer"><a href="#">View all</a></li> -->
            </ul>
          </li>
          

          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?= $this->config->item('image_url').'user.png' ?>" class="user-image" alt="User Image">
              <span class="hidden-xs">
                <?php
                   if(!empty($this->admin_session['name'])){ 
                      echo $this->admin_session['name'];
                   } else {
                      echo "Admin User";
                   }
                  ?>
              </span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header" style="height: auto">
                <img src="<?= $this->config->item('image_url').'user.png' ?>" class="img-circle" alt="User Image">

                <p>
                  <?php
                   if(!empty($this->admin_session['name'])){ 
                      echo $this->admin_session['name'];
                   } else {
                      echo "Admin User";
                   }
                  ?>
                  <small class="thought">Thought of the day</small>
                  <small><?= date("F jS, Y"); ?></small>
                </p>
              </li>

              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-12 text-center">
                    <a href="<?php echo base_url('admin/change_password')?>" class="btn btn-default btn-flat">Change Password</a>
                  </div>
                </div>
                <!-- /.row -->
              </li>

              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?php echo base_url('admin/admin_settings')?>" class="btn btn-default btn-flat">Settings</a>
                </div>
                <div class="pull-right">
                  <a href="javascript:void(0);" class="btn btn-default btn-flat" onClick="logout();">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          
          <!-- <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li> -->
        </ul>
      </div>
    </nav>
</header>
 <!-- header --> 
<script>
// $.getJSON("https://quotesondesign.com/wp-json/posts?filter[orderby]=rand&filter[posts_per_page]=1&callback=", function(a) {
//   $(".thought").append(a[0].content + "<p>&mdash; " + a[0].title + "</p>")
// });

$(document).on('ready', function(e) {
    $.ajax({
      url: 'https://quotesondesign.com/wp-json/posts?filter[orderby]=rand&filter[posts_per_page]=1',
      success: function(data) {
          $(".thought").html(data[0].content + "<p>&mdash; " + data[0].title + "</p>")
        },
      error: function() {
          $.unblockUI();
        },
      });
  });

$.ajaxSetup({
    beforeSend: function() {
      $.blockUI(); 
    },
    complete: function() {
      $.unblockUI(); 
      $('.tooltips').tooltipster({
        theme: 'tooltipster-noir'
      });
    },
    error: function(jqXHR, textStatus, errorThrown) {
      $.unblockUI();
      $.alert({
        title: 'Alert!',
        content: "Something went wrong. Please try again or contact the administrator!!!",
        buttons: {
          confirm:{btnClass:'hide'},
          cancel:{text:'ok'},
        },
      });
    }
});


</script>