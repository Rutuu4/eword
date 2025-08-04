<?php
  $this->flashdata = $this->session->flashdata('message');
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

<title><?= $this->config->item('sitename') . ' Admin Login' ?></title>

<link rel="stylesheet" href="<?= $this->config->item('css_url') ?>bootstrap.min.css">
<link rel="stylesheet" href="<?= $this->config->item('css_url') ?>admin/AdminLTE.min.css">
<link rel="stylesheet" href="<?= $this->config->item('css_url') ?>admin/custom.css">
<link rel="stylesheet" href="<?= $this->config->item('css_url') ?>admin/animate.css">

<!-- Favicon Icon -->
<link rel="icon" href="<?php echo base_url(); ?>images/favicon/favicon.png" type="image/png">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

</head>

<body class="hold-transition login-page">
<div class="login-box box" id="login-container">
  <div class="login-logo">
    <a href="<?= base_url('admin') ?>"><img src="<?=!empty($this->site_info[0]['site_logo']) && file_exists($this->config->item('image_site_logo').$this->site_info[0]['site_logo'])?$this->config->item('image_site_logo_url').$this->site_info[0]['site_logo']:$this->config->item('base_url').'images/logo.png'?>" alt="Site Logo" style="width: 50%"></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <h4 class="login-box-msg">Welcome to <?= $this->config->item('sitename') ?> Admin Section</h4>
    <h5 class="login-box-msg">Sign in to get access</h5>

    <form action="" method="post" class="parsley-form" id="login-form" data-parsley-validate>
      <div class="form-group has-feedback">
        <input type="email" class="form-control parsley-validated" id="email" name="email" placeholder="Email*" required autofocus>
        <span class="fa fa-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control parsley-validated" id="password" name="password" placeholder="Password*" required>
        <span class="fa fa-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-12">
          <button type="submit" class="btn bg-blue btn-block" id="login-btn" onclick="checkIfValid()">Sign In</button>
        </div>
      </div>
    </form>

    <br/>
    <a class="btn btn-default btn-block" href="javascript:void(0);" onClick="hide_show();">Forgot Password?</a> 
  </div>
  <!-- /.login-box-body -->
</div>
<input type="hidden" id="forgot_active">
<div id="login-container" class="login-box box forgot" style="display:none;">
    <div class="login-logo">
    <a href="<?= base_url('admin') ?>"><img src="<?=!empty($this->site_info[0]['site_logo']) && file_exists($this->config->item('image_site_logo').$this->site_info[0]['site_logo'])?$this->config->item('image_site_logo_url').$this->site_info[0]['site_logo']:$this->config->item('base_url').'images/logo.png'?>" alt="Site Logo" style="width: 50%"></a>
    </div>
    <div class="login-box-body">
    <h5 class="login-box-msg">Welcome to <?= $this->config->item('sitename') ?> Admin Section</h5>
    <h3 class="login-box-msg">Forgot Password?</h3>
    <form class="parsley-form" id="login-form12" method="post" action="" novalidate>
      <div class="form-group has-feedback">
        <input id="email" placeholder="Email*" autofocus type="email" name="forgot_email" class="form-control parsley-validated" data-required="true">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <button type="submit" class="btn bg-blue btn-block" id="login-btn" onclick="checkIfValid()">Reset Password</button>
      </div>
    </form>
    <a class="btn btn-default btn-block" href="javascript:void(0);" onClick="show();">Back To Login</a> 
    </div>
</div>
<!-- /.login-box -->

<script src="<?= $this->config->item('js_url') ?>jquery-2.2.3.min.js"></script>
<script src="<?= $this->config->item('js_url') ?>bootstrap.min.js"></script>
<script src="<?= $this->config->item('js_url') ?>parsley.min.js"></script>
<script src="<?= $this->config->item('js_url') ?>bootstrap-notify.min.js"></script>

<script>
  function hide_show()
  {
     $('#login-container').hide();
     $('#forgot_active').val('1');
     $('.forgot').show();
  }
  function show()
  {
     $('#login-container').show();
     $('#forgot_active').val('');
     $('.forgot').hide();
  }

  $(document).ready(function() {
     $("#div_msg").fadeOut(4000);
  });

  function checkIfValid()
  {   
    var forgot_flag = $('#forgot_active').val();
    if(forgot_flag == 1)
    {
      if($('#login-form12').parsley().isValid())
      {
        $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
      }
    }
    else
    {
      if($('#login-form').parsley().isValid())
      {
        $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
      } 
    }
  }

  $.notifyDefaults({
    z_index: 1031,
    delay: 5000,
    allow_dismiss: true,
    animate: {
      enter: 'animated fadeInDown',
      exit: 'animated fadeOutUp'
    },
  });
  <?php if(!empty($this->flashdata)) 
  { 
    if($this->flashdata['status'] == "danger") 
    {
  ?>
    $.notify({
      icon: 'fa fa-warning',
      message: "<?= $this->flashdata['message'] ?>"
    },{
      type: 'danger',
    });
  <?php 
    }
    elseif($this->flashdata['status'] == "success")
    {
  ?>
    $.notify({
      icon: 'fa fa-success',
      message: "<?= $this->flashdata['message'] ?>"
    },{
      type: 'success',
    });
  <?php
    }
  } 
  ?>
</script>
</body>
</html>
