<?php

include("include/database.php");

 

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Log in Here</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <style>
  body{
    background: url('dist/img/bgimage.jpg') no-repeat 0px 0px!important;
    background-size: cover!important;
    background-attachment: fixed!important;
    background-position: center!important;
    height: auto;
  } 
  .error{
    color: #c10e0e;
    font-style: italic;
  }
  .login-logo a, .register-logo a{
    color: white;
    text-shadow: 0px 2px 22px #ff1210;
  }
  .help-inline{
    border: 1px solid #c10e0e;
    font-style: italic;
  }
  </style>
</head>
<body class="hold-transition login-page" >
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b style="font-size: 26px;">Gujarati Christian Connect</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    

    <form action="delete-my-account.php" method="post">
      <div class="form-group has-feedback">
        <input type="email" class="form-control" placeholder="Email">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
         
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

     
    <!-- /.social-auth-links -->

    

  </div>
  <!-- /.login-box-body -->
</div>
</body>
</html>