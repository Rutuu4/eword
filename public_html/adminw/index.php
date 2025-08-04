<?php

include("include/database.php");

function cleanInP($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
$error = "";
$uname = "";
 
if(isset($_POST['login'])){

  $uname = cleanInP($_POST['uname']);
  $psw = cleanInP($_POST['psw']);
  $unametitle = $uname;

  $chkUser = $conn->query("SELECT * FROM `user` WHERE username = '".$uname."' AND password = '".$psw."' ");
  $numrow = $chkUser->num_rows;
  if($numrow > 0){
    $rowuser = $chkUser->fetch_array();
    $usertype = $rowuser['typee'];
    $userid = $rowuser['id'];
   /* $usertype = $userdata->typee;
    $userid = $userdata->id;*/
    

    //SET LAST LOGIN
    $datetime = date('Y-m-d h:i:s');
    $lastloginupdate = $conn->query("UPDATE `user` SET `last_login`= '$datetime' WHERE id = ".$userid."");
    $_SESSION['usersession'] = $userid;
    $_SESSION['usertype'] = $rowuser['typee'];
    $_SESSION['designaton_id'] = $rowuser['designaton_id'];
    
    header("Location: $usertype/");
    exit();
  }else{
    $error = 2;
  }
}else{
    $unametitle = "User";
}


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
    <a href="">Welcome <?=ucfirst($unametitle)?> .!</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>
    <?php if($error == 2){ ?>
    <div class="alert alert-danger alert-dismissible">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>Uy .!</strong> Your Credentials are Invalid.
    </div>
    <?php } ?>
    <form action="" method="post" id="loginform">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Enter Username" autocomplete="off" name="uname" value="<?=$uname?>" required="">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Enter Password" name="psw" required="">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
      	<!--
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox"> &nbsp; Remember Me
            </label>
          </div>  
        </div>
    	-->
    	<div class="col-xs-8"></div>
        <div class="col-xs-4">
          <button type="submit" name="login" id="loginbutton" class="btn btn-warning btn-block btn-flat">Sign In <i class="fa fa-sign-in"></i></button>
        </div>
      </div>
    </form>

  </div>
</div>

<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="plugins/iCheck/icheck.min.js"></script>
<script src="bower_components/jquery-validation/jquery.validate.min.js"></script>
<script src="bower_components/jquery-validation/additional-methods.min.js"></script>
<script>
$(document).ready(function(){

  $('input').iCheck({
    checkboxClass: 'icheckbox_square-blue',
    radioClass: 'iradio_square-blue',
    increaseArea: '20%' // optional
  });
  
});
</script>
</body>
</html>