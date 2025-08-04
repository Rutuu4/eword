<?php
  $uri1 = !empty($this->router->uri->segments[1])?$this->router->uri->segments[1]:'';
  $uri2 = !empty($this->router->uri->segments[2])?$this->router->uri->segments[2]:'';
  $candidate_session = $this->session->userdata($this->config->item('siteslug').'_candidate_session');
?>
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

<title><?php echo ucwords(!empty($this->page_title)?$this->page_title:$this->config->item('sitename')); ?></title>

<!-- Favicon Icon -->
<link rel="icon" href="<?php echo base_url(); ?>images/favicon/favicon.png" type="image/png">
<!-- <link rel="icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/x-icon">
<link rel="icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/icon"> -->

<link href="https://fonts.googleapis.com/css?family=Raleway:300,400,400i,700,800|Varela+Round" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

<link rel="stylesheet" type="text/css" href="<?= $this->config->item('css_url') ?>bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?= $this->config->item('css_url') ?>animate.css">
<link rel="stylesheet" type="text/css" href="<?= $this->config->item('css_url') ?>owl.carousel.min.css">
<link rel="stylesheet" type="text/css" href="<?= $this->config->item('css_url') ?>style.css">
<link rel="stylesheet" type="text/css" href="<?= $this->config->item('css_url') ?>responsive.css">
<link rel="stylesheet" type="text/css" href="<?= $this->config->item('css_url') ?>jquery-confirm.css">
<link rel="stylesheet" type="text/css" href="<?= $this->config->item('css_url') ?>icomoon.css">
<link rel="stylesheet" type="text/css" href="<?= $this->config->item('css_url') ?>flexslider.css">
<link rel="stylesheet" type="text/css" href="<?= $this->config->item('css_url') ?>jquery.tagit.css">
<link rel="stylesheet" type="text/css" href="<?= $this->config->item('css_url') ?>tagit.ui-zendesk.css">
<link rel="stylesheet" type="text/css" href="<?= $this->config->item('css_url') ?>tooltipster.bundle.min.css">
<link rel="stylesheet" type="text/css" href="<?= $this->config->item('css_url') ?>fine-uploader.min.css">
<link rel="stylesheet" type="text/css" href="<?= $this->config->item('css_url') ?>candidate-custom.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<script src="<?= $this->config->item('js_url') ?>jquery-3.2.1.min.js"></script>
</head>

<body>
<!-- <div class="fh5co-loader"></div> -->
  
  <header class="header1">
        <nav class="navbar navbar-default navbar-static-top fluid_header centered">
            <div class="container">
                
                <!-- Logo -->
                <div class="col-md-2 col-sm-6 col-xs-8 nopadding">
                    <a class="navbar-brand nomargin" href="<?= base_url() ?>"><img src="<?= $this->config->item('image_url') ?>RC.png" alt="logo"></a>
                    <!-- INSERT YOUR LOGO HERE -->
                </div>

                <!-- ======== Start of Main Menu ======== -->
                <div class="col-md-10 col-sm-6 col-xs-4 nopadding">
                    <div class="navbar-header page-scroll">
                        <button type="button" class="navbar-toggle toggle-menu menu-right push-body" data-toggle="collapse" data-target="#main-nav" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>

                    <!-- Start of Main Nav -->
                    <div class="collapse navbar-collapse cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right" id="main-nav">
                        <ul class="nav navbar-nav pull-right">

                            <!-- Mobile Menu Title -->
                            <li class="mobile-title">
                                <h4>main menu</h4></li>

                            <!-- Simple Menu Item -->
                            <li class="simple-menu <?= (empty($uri1) || $uri1 == 'dashboard')?'active':'' ?>">
                                <a href="<?= $this->config->item('candidate_base_url') ?>" role="button">Home</a>
                            </li>

                            <li class="simple-menu <?= ($uri1 == 'jobs')?'active':'' ?>">
                                <a href="<?= $this->config->item('candidate_base_url').'jobs' ?>" role="button">Jobs</a>
                            </li>

                            <?php if(!empty($candidate_session)) { ?>
                                <li class="simple-menu <?= ($uri1 == 'message')?'active':'' ?>">
                                    <a href="<?= base_url('message') ?>" class="" role="button">Message</a>
                                </li>
                            <?php } ?>

                            <!-- Mega Menu Item -->
                            <?php /*
                            <li class="dropdown mega-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">pages<i class="fa fa-angle-down"></i></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <!-- Start of Mega Menu Inner -->
                                        <div class="mega-menu-inner">
                                            <div class="row">
                                                <ul class="col-md-4">
                                                    <li class="menu-title">pages 1</li>
                                                    <li><a href="about-us.html">about us</a></li>
                                                    <li><a href="contact-1.html">contact us 1</a></li>
                                                    <li><a href="contact-2.html">contact us 2</a></li>
                                                    <li><a href="companies.html">companies</a></li>
                                                    <li><a href="company-page-1.html">company page 1</a></li>
                                                    <li><a href="company-page-2.html">company page 2</a></li>
                                                </ul>

                                                <ul class="col-md-4">
                                                    <li class="menu-title">pages 2</li>
                                                    <li><a href="candidate-profile-1.html">candidate profile 1</a></li>
                                                    <li><a href="candidate-profile-2.html">candidate profile 2</a></li>
                                                    <li><a href="candidate-profile-3.html">candidate profile 3</a></li>
                                                    <li><a href="faq.html">faq</a></li>
                                                    <li><a href="job-page.html">job page</a></li>
                                                    <li><a href="privacy-policy.html">privacy policy</a></li>
                                                </ul>

                                                <ul class="col-md-4">
                                                    <li class="menu-title">pages 3</li>
                                                    <li><a href="404.html">404</a></li>
                                                    <li><a href="404-2.html">404 ver. 2</a></li>
                                                    <li><a href="coming-soon.html">coming soon</a></li>
                                                    <li><a href="login.html">login</a></li>
                                                    <li><a href="register.html">register</a></li>
                                                    <li><a href="lost-password.html">lost password</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <!-- End of Mega Menu Inner -->
                                    </li>
                                </ul>
                            </li>
                            */ ?>
                            <!-- End of Mega Menu Item -->

                            <!-- Simple Menu Item -->
                            <?php /*
                            <li class="dropdown simple-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">elements<i class="fa fa-angle-down"></i></a>
                                <ul class="dropdown-menu">
                                    <!-- Dropdown Submenu -->
                                    <li class="dropdown-submenu">
                                        <a href="#">headers<i class="fa fa-angle-right"></i></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="header1.html">header 1 - default</a></li>
                                            <li><a href="header2.html">header 2 - logo top</a></li>
                                            <li><a href="header3.html">header 3 - top bar</a></li>
                                            <li><a href="header4.html">header 4 - sticky</a></li>
                                        </ul>
                                    </li>

                                    <!-- Dropdown Submenu -->
                                    <li class="dropdown-submenu">
                                        <a href="#">footers<i class="fa fa-angle-right"></i></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="footer1.html">default</a></li>
                                            <li><a href="footer2.html">light</a></li>
                                            <li><a href="footer3.html">dark</a></li>
                                            <li><a href="footer4.html">simple</a></li>
                                        </ul>
                                    </li>

                                    <!-- Dropdown Submenu -->
                                    <li class="dropdown-submenu">
                                        <a href="#">page headers<i class="fa fa-angle-right"></i></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="page-header1.html">default</a></li>
                                            <li><a href="page-header2.html">light</a></li>
                                            <li><a href="page-header3.html">dark</a></li>
                                            <li><a href="page-header4.html">parallax</a></li>
                                        </ul>
                                    </li>

                                    <li><a href="buttons.html">buttons</a></li>
                                    <li><a href="pricing-tables.html">pricing tables</a></li>
                                    <li><a href="typography.html">typography</a></li>
                                </ul>
                            </li>
                            */ ?>
                            <!-- Login Menu Item -->
                            <?php if(!empty($candidate_session)) { ?>
                                <li class="dropdown simple-menu <?= ($uri1 == 'my-account' || $uri1 == 'notification')?'active':'' ?>">
                                    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" class="btn btn-blue"><i class="fa fa-user"></i>&nbsp;<?= $candidate_session['name'] ?>&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-down"></i></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?= $this->config->item('candidate_base_url').'my-account' ?>" class="" role="button">MY ACCOUNT</a></li>
                                        <li><a href="<?= $this->config->item('candidate_base_url').'notification' ?>" role="button">NOTIFICATIONS</a></li>
                                        <li><a onClick="logout();" href="javascript:void(0)" ><i class="fa fa-sign-out"></i>LOGOUT</a></li>
                                    </ul>
                                </li>
                            <?php } else { 
                                ?>
                                <li class="menu-item login-btn">
                                    <a id="modal_trigger" href="javascript:void(0)" role="button"><i class="fa fa-lock"></i>Login</a>
                                </li>
                            <?php } ?>

                        </ul>
                    </div>
                    <!-- End of Main Nav -->
                </div>
                <!-- ======== End of Main Menu ======== -->

            </div>
        </nav>
    </header>
<script>

$.ajaxSetup({
    beforeSend: function() {
      $.blockUI(); 
    },
    complete: function() {
      $.unblockUI(); 
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

function logout()
{
    $('#login_popup').addClass('cd-user-modal').removeClass('cd-user-modal is-visible');
    $.confirm({
        content: 'Are you sure you want to logout?',
        buttons: {
            confirm: {
                action: function(){
                    window.location="<?= base_url('logout') ?>";
                }
            },
        },
    });
}

</script>