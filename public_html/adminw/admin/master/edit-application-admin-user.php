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

$interesting_main_course_id=mysqli_real_escape_string($conn,$_POST['interesting_main_course_id']);

if(!empty($_POST['course_details_ids']))
{
    $course_details_ids=implode(",",$_POST['course_details_ids']);
}

if(!empty($_POST['interesting_city_ids']))
{
    $interesting_city_ids=implode(",",$_POST['interesting_city_ids']);
}



$is_chat_block=mysqli_real_escape_string($conn,$_POST['is_chat_block']);

  $qury1="Update registration set username='$username', password='$password',fullname='$fullname' where id='$id'";          
          
   // echo $qury1
$sq1 = $conn->query($qury1);
  header("location:../application-admin-user.php"); 

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
      background-color: #fff;
      background-image: none;
      border: 1px solid #ccc;
    }
    .select2-container { width: 100% !important; }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #3c8dbc;
    border-color: #367fa9;
    padding: 1px 10px;
    color: #ffffff;
}
    .select2-container--default .select2-search--inline .select2-search__field { width:100% !important;}
.select2-container--default.select2-container--open { width:100% !important;}
.select2-container { width:100% !important;}
sup{  color:#CC3300; font-size:14px; top: -4px; }
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: #ffffff;
    cursor: pointer;
    display: inline-block;
    font-weight: bold;
    margin-right: 2px;
}
  </style>
</head>
<body class="<?=$bodyclass?>">

 <?php

       if(!empty($_GET['key'])){

  $id=base64_decode($_GET['key']);
     
      $qry="SELECT * FROM registration WHERE id='$id'";  

      $result = $conn->query($qry);
      $row = $result->fetch_array();
      $status=$row['status'];
      $interesting_main_course_id=$row['interesting_main_course_id'];
      $course_details_ids=$row['course_details_ids'];
      $interesting_city_ids=$row['interesting_city_ids'];
      $is_chat_block=$row['is_chat_block'];

      
    }
  ?>  


<div class="wrapper">
  <?php include("../includes/header.php"); ?>
  <?php include("../includes/sidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Update Application User</h1>
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
               <label class="control-label col-sm-2">Username (Mobile Number):</label>
                  <div class="col-sm-8">
                      <input 
    type="text" 
    class="form-control" 
    name="username" 
    id="username" 
    value="<?= $row['username']; ?>" 
    placeholder="Enter Username as Mobile" 
    required 
    pattern="\d{10}" 
    title="Please enter exactly 10 digits.">
                  </div>
           </div>



           <div class="form-group">
               <label class="control-label col-sm-2">Name:</label>
                  <div class="col-sm-8">
                      <input type="text" class="form-control" name="fullname"  value="<?=$row['fullname'];?>"  id="first_name" placeholder="Enter Fullname"  required>
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
<script>
$(document).ready(function(){
   $("#main_courses_id").change(function(){
    var main_courses_id = $(this).find(":selected").val();
    if(main_courses_id != ""){
      $.ajax({
        type: "POST",
        url: "master/ajax/ajax-show-courses-details.php",
        data: {main_courses_id:main_courses_id},
        success: function(response) {
          $("#showextra").html(response);
        }
      });
    }
  }); });
  </script>
  <script>
document.getElementById("username").addEventListener("input", function() {
    const pattern = /^\d{10}$/;
    if (!pattern.test(this.value)) {
        this.setCustomValidity("Please enter exactly 10 digits.");
    } else {
        this.setCustomValidity("");
    }
});
</script>

</body>
</html>
