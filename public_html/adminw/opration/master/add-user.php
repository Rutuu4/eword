<?php 
  include("../../database.php"); 
  
  function cleanInP($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  if(isset($_POST['submit'])){

    $typee = $_POST['typee']; 
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $uname = $_POST['username'];
    $psw = $_POST['password'];
    $status = $_POST['status'];
    $center = $_POST['center'];


    $insertqry = "INSERT INTO `user`(center,typee,name,mobile,email,username,password,status) VALUES ('$center','$typee','$name','$mobile','$email','$uname','$psw','$status')";

    $exeUpdate = $conn->query($insertqry);
    if($exeUpdate){
        header("Location: ../manage-employe-user.php");
        exit;
    }else{
      header("Location: ../index.php");
    }

  }

?>
<!DOCTYPE html>
<html>
<head>
<base href="<?=$base_path?>">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?=$softtitle?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <?php include("../includes/css-scripts.php"); ?>
  <style>
    .error{
      color: #d84444;
    }
    .control-label{
      text-align: left!important;
    }
    .form-control{
      display: block;
      width: 100%;
      height: 34px;
      padding: 6px 12px;
      font-size: 14px;
      line-height: 1.42857143;
      color: #555;
      background-color: #fff;
      background-image: none;
      border: 1px solid #ccc;
    }
  </style>
</head>
<body class="<?=$bodyclass?>">
<div class="wrapper">
  <?php include("../includes/header.php"); ?>
  <?php include("../includes/sidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Add User to <?=$softtitle?></h1>
    </section>

    <section class="content">

      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Add Data Here</h3>
        </div>

        <div class="box-body">
          <div class="col-sm-7">
            <form action="" method="POST" class="form-horizontal" id="adduserform">

              <div class="form-group">
                <label class="control-label col-sm-2">Full Name :</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="name" required placeholder="User Full Name">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2">User Mobile :</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="mobile" required placeholder="User Mobile">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2">User Email :</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="email" required placeholder="User Email">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2">Username :</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="username"  placeholder="Username">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2">Password :</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="password"  placeholder="Password">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2">Type  :</label>
                <div class="col-sm-10">
                  <select class="form-control" required="" name="typee" id="usertype">
                    <option value="">Select Type</option>
                    <option value="inqury-insert">Inqury Insert</option>
                    <option value="sales">Sales</option>
                    <option value="salesteam">Sales Team</option>
                    <option value="member">Member </option>
                  </select>
                </div>
              </div>
              <div class="form-group" id="teamleaderDiv" style="display: none;">
                <label class="control-label col-sm-2">Teamleader  :</label>
                <div class="col-sm-10">
                  <select class="form-control" required="" name="center" id="teamleader">
                    <option value="">Select Type</option>
                    <?php $getst = $conn->query("SELECT * FROM `user` WHERE typee = 'salesteam' AND status = 1"); while($rowst = $getst->fetch_array()){?>
                    <option value="<?=$rowst[0]?>"><?=ucfirst($rowst['name'])?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-2">Status :</label>
                <div class="col-sm-10" style="padding:7px;">
                  <input type="radio" name="status" value="1" checked=""> Active
                  &nbsp;&nbsp;&nbsp;
                  <input type="radio" name="status" value="0"> Deactive
                </div>
              </div>
              <div class="col-md-12" align="right">
                <input type="submit" name="submit" value="Add Data" class="btn btn-success">
              </div>

            </form>
          </div>
          <div class="col-sm-5">
           
          </div>
        </div>
        
      </div>
    </section>
  </div>
  <?php include("../includes/footer.php"); ?>
</div>
<?php include("../includes/js-scripts.php"); ?>
<script>
$(document).ready(function(){
  //Select2
  $(".select2").select2();
  $("#usertype").change(function(){
    var selval = $(this).find(":selected").val();
    if(selval == "sales" || selval == "inqury-insert"){
      $("#teamleaderDiv").slideDown(400);
      $("#teamleader").prop('required',true);
    }else{
      $("#teamleaderDiv").slideUp(400);
      $("#teamleader").prop('required',false);
    }
  });
  //Validations
  $( "#adduserform" ).validate({
    rules: {
      name: { required: true  },
      mobile : { required: true,number: true },
      email: { required: true,email: true  },
      username: { required: true  },
      password: { required: true,minlength: 4  },
      typee: {required:true}
    },
    messages: {
      name:  "Name is Required" ,
      mobile:  "Valid Mobile Number is Required" ,
      email:  "Valid Email Address is Required" ,
      username: "Username is Required" ,
      password:  "Strong Password is Required (4-16 Character)" ,
      typee: "Please Select Designation"
    }
  });
});
</script>
</body>
</html>
