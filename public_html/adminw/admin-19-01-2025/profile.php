<?php 
  include("../database.php"); 


  if(isset($_POST['updateProfile']))
  {
      $id=mysqli_real_escape_string($conn,$_POST['id']);
      $name=mysqli_real_escape_string($conn,$_POST['name']);
      $mobile=mysqli_real_escape_string($conn,$_POST['mobile']);
      $email=mysqli_real_escape_string($conn,$_POST['email']);
      $password=mysqli_real_escape_string($conn,$_POST['password']);  


      $qry1="UPDATE user SET password='$password',name='$name',mobile='$mobile',email='$email' WHERE id='$id'";
      $sql1=$conn->query($qry1);
      if($sql1)
      {
        if($conn->affected_rows > 0)
        {
          header("Refresh:0");
          exit;
        }
        else
        {
            $message1 = "There is Nothing To Update";
        }
      }
  }

?>
<!DOCTYPE html>
<html>
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?=$softtitle?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <?php include("includes/css-scripts.php"); ?>
  <link rel="stylesheet" type="text/css" href="../bower_components/upload-file/uploadfile.css">
  <style>
    .error{
      color: red;
    }
  </style>
</head>
<body class="<?=$bodyclass?>">
<div class="wrapper">
  <?php include("includes/header.php"); ?>
  <?php include("includes/sidebar.php"); ?> 

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>My Profile</h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Profile</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><?=ucfirst($userinfo->name);?></h3>
        </div>

        <div class="box-body">
          <div class="row">
          <form action="" method="POST" id="profileForm" enctype="multipart/form-data">
            <div class="col-md-6">
              <div class="form-group">
                <label>Your Name</label>
                <input type="text" name="name" class="form-control" placeholder="Your name" value="<?=$userinfo['name']?>" required>
                <input type="hidden" name="id"value="<?=$userinfo['id']?>">
              </div>
              <div class="form-group">
                <label>Your Mobile</label>
                <input type="text" name="mobile" class="form-control" placeholder="Your Number"  pattern="[0-9]{10}" value="<?=$userinfo['mobile']?>" required>
              </div>
              <div class="form-group">
                <label>Your Email</label>
                <input type="text" name="email" class="form-control" placeholder="Your Email" value="<?=$userinfo['email']?>" required>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Your Username</label>
                <input type="text" class="form-control" disabled="" value="<?=$userinfo['username']?>">
              </div>
              <div class="form-group">
                <label>Your Password</label>
                <input type="text" name="password" class="form-control" placeholder="Your Password" value="<?=$userinfo['password']?>" required>
              </div>
            </div>

            <div class="col-md-12" align="right">
              <input type="submit" name="updateProfile" value="Update Profile" class="btn btn-success">
              <p style="color: red;font-style: italic;"><?=$message1?></p>
            </div>

          </form>
          
         </div>
        </div>
      </div>
    </section>
  </div>
  <?php include("includes/footer.php"); ?>
</div>

<?php include("includes/js-scripts.php"); ?>
<script src="../bower_components/upload-file/jquery.uploadfile.js"></script>
<script>
$(document).ready(function(){
  //Select2
  $(".select2").select2();
  
  //Validations
  $( "#profileForm" ).validate({
    rules: {
      name: { required: true  },
      number: { required: true,number: true  },
      email: { required: true,email: true  },
      psw: { required: true,minlength: 4  },
    },
    messages: {
      name:  "Name is Required" ,
      number:  "Number is Required" ,
      email:  "Email is Required" ,
      email:  "Email is Required" ,
      psw:  "Strong Password is Required (1-4)" ,
    }
  });
});
</script>

</body>
</html>
