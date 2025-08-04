<?php 
include("../../database.php"); 

$username=$_POST['username'];
$id=$_POST['id'];

if(!$username=="") {
$qry="SELECT count(id)as chk_username FROM registration where username='$username' and id!='$id' ";   
$result = $conn->query($qry);
$row = $result->fetch_array();

if($row['chk_username']>0) { $chk=1;  }
}


if($_POST['h1']==1 &&  $chk!=1)
{  

$date=date('Y-m-d');
$datee=date('Y-m-d H:i:s'); 
$id=mysqli_real_escape_string($conn,$_POST['id']);
$username=mysqli_real_escape_string($conn,$_POST['username']);
$password=mysqli_real_escape_string($conn,$_POST['password']);
$fullname=mysqli_real_escape_string($conn,$_POST['fullname']);

$email=mysqli_real_escape_string($conn,$_POST['email']);

$city=mysqli_real_escape_string($conn,$_POST['city']);
$status=mysqli_real_escape_string($conn,$_POST['status']);



  $qury1="Update registration set username='$username', password='$password',fullname='$fullname',email='$email',city='$city',status='$status' where id='$id'";          
          
   // echo $qury1;

$sq1 = $conn->query($qury1);

if(mysqli_affected_rows($conn)>=1){  header("location:../register-user.php"); }

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
      color: red;
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
    .select2-container { width: 100% !important; }
  </style>
</head>
<body class="<?=$bodyclass?>">

 <?php

    if($_GET['id']!="")
    {

      $id=$_GET['id'];
     
      $qry="SELECT * FROM registration WHERE id='$id'";  

      $result = $conn->query($qry);
      $row = $result->fetch_array();
      $status=$row['status'];
      
    }
  ?>  


<div class="wrapper">
  <?php include("../includes/header.php"); ?>
  <?php include("../includes/sidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Update Register User</h1>
    </section>

    <section class="content">

      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Add Data Here</h3>
        </div>

        <div class="box-body">
          <form action="" method="POST" id="" class="form-horizontal" enctype="multipart/form-data">
            <input name="h1" type="hidden" id="h1" value="1" />
            <input name="id" type="hidden" id="id" value="<?=$id;?>" />


            <div class="form-group">
               <label class="control-label col-sm-2">Username:</label>
                  <div class="col-sm-8">
                      <input type="text" class="form-control" name="username"  id="username"  value="<?=$row['username'];?>"  placeholder="Enter Username as Email"  required>
                  </div>
           </div>



           <div class="form-group">
               <label class="control-label col-sm-2">Name:</label>
                  <div class="col-sm-8">
                      <input type="text" class="form-control" name="fullname"  value="<?=$row['fullname'];?>"  id="first_name" placeholder="Enter Fullname"  required>
                  </div>
           </div>



           <div class="form-group">
               <label class="control-label col-sm-2">Email:</label>
                  <div class="col-sm-8">
                      <input type="text" class="form-control" name="email"   value="<?=$row['email'];?>"   id="email" placeholder="Enter Email"  required>
                  </div>
           </div>




            <div class="form-group">
              <label class="control-label col-sm-2">City:</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="city"  id="city"  value="<?=$row['city'];?>"  placeholder="Enter City"  required>
              </div>
            </div>


            

           
            <div class="form-group">
                <label class="col-sm-2">Status :</label>
                <div class="col-sm-6">
                    <div class="col-sm-3 col-xs-6">
                        <label><input name="status" type="radio" value="1" <?php if($status==1){  ?>checked="checked"<?php } ?>/>
                          Active</label>
                    </div>
                    <div class="col-sm-6 col-xs-6">
                        <label><input name="status" type="radio" value="0"  <?php if($status==0){  ?>checked="checked"<?php } ?> />
                          Deactive</label>
                    </div>
                </div>
            </div>


            <div class="col-sm-12" style="color:#FF3333">
                 <?php if($chk==1){ echo "User is Already registered, Please Renter username";} ?>
            </div>



            <div class="col-md-12" align="right">
              <button type="submit" class="btn btn-success ">Save changes</button>
            </div>

          </form>
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
});
</script>

<script>
  $(function () {
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,
      todayHighlight:true,
     format: 'dd-mm-yyyy',
    });
    
  });
</script>

</body>
</html>
