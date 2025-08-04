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

  $qury1="Update registration set username='$username', password='$password',fullname='$fullname',email='$email',city='$city',status='$status',is_chat_block='$is_chat_block' where id='$id'";          
          
   // echo $qury1
$sq1 = $conn->query($qury1);
  header("location:../register-user.php"); 

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

    if($_GET['id']!="")
    {

      $id=$_GET['id'];
     
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
                      <input type="text" class="form-control" name="email"   value="<?=$row['email'];?>"   id="email" placeholder="Enter Email" >
                  </div>
           </div>

            <div class="form-group">
               <label class="control-label col-sm-2">City:</label>
                  <div class="col-sm-8">
                      <input type="text" class="form-control" name="city"   value="<?=$row['city'];?>"   id="city" placeholder="Enter City" >
                  </div>
           </div>



              <div class="form-group">
                      <label for="usernamee" class="col-sm-2">Interesting Main Course :</label>
                      <div class="col-sm-8">

                           <select name="interesting_main_course_id"  id="main_courses_id"  class="form-control select2"  disabled >
                            <option value=""> Select Main Course </option>
                            <?php 
                            $sqlb="SELECT id,name FROM m_main_courses where status=1";
                            $resultb = $conn->query($sqlb);
                            while($rowb = $resultb->fetch_array())
                            {
                              ?>
                              <option <?php if($row['interesting_main_course_id']==$rowb['id']) { echo "selected"; } ?> value="<?=$rowb['id'];?>"> <?=$rowb['name'];?> </option>
                              <?php } ?>
                                  </select>
                      </div>
                    </div>
<?php
if($row['interesting_exrta_course_id']>0)
{ ?>


   <div class="form-group">
                      <label for="usernamee" class="col-sm-2">Exrta Course :</label>
                      <div class="col-sm-8">

                           <select name="extra_course_id"  id="extra_course_id"  class="form-control select2"  disabled>
                            <option value=""> Select Exrta Course </option>
                            <?php 
                            $sqlb="SELECT id,name FROM m_exrta_course where status=1 and main_courses_id='$interesting_main_course_id'";
                            $resultb = $conn->query($sqlb);
                            while($rowb = $resultb->fetch_array())
                            {
                              ?>
                              <option <?php if($row['interesting_exrta_course_id']==$rowb['id']){ echo "selected"; } ?> value="<?=$rowb['id'];?>"> <?=$rowb['name'];?> </option>
                              <?php } ?>
                                  </select>
                      </div>
                    </div>  
<?php } ?>



                     <div id="showextra">

                      <?php

                      if($interesting_main_course_id>0)
                      {
                      ?>
                                  <div class="form-group">
    <label for="usernamee" class="col-sm-2">Interesting Courses :</label>
    <div class="col-sm-8 row">
        <?php 
        $sql_fv = "SELECT id, name FROM courses_details WHERE status=1 AND main_courses_id='$interesting_main_course_id'";
        $result_fv = $conn->query($sql_fv);
        if (!is_array($course_details_ids)) {
            $course_details_ids = explode(',', $course_details_ids); 
        }

        while ($row_fv = $result_fv->fetch_assoc()) {
            $isChecked = in_array($row_fv['id'], $course_details_ids) ? 'checked' : '';
            ?>
            <div class="col-sm-4">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="course_details_ids[]" <?= $isChecked; ?> value="<?= htmlspecialchars($row_fv['id']); ?>" disabled> <?= htmlspecialchars($row_fv['name']); ?>
                    </label>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

                      <?php } ?>
                      
                    </div>

                    <div class="form-group">
                            <label for="usernamee" class="col-sm-2">Interesting City :</label>
                            <div class="col-sm-8">

                                <select name="interesting_city_ids[]" id="interesting_city_ids[]"  multiple="multiple" class="form-control select2"  data-placeholder="Select Interesting City" disabled >
                                
                                  <?php 
                                        $sql_fv="SELECT id,name FROM m_city where status=1";
                                        $result_fv = $conn->query($sql_fv);
                                        while($row_fv = $result_fv->fetch_array())
                                    {
                                    ?>
                                        <option value="<?=$row_fv['id'];?>" <?php $fam_val=explode(",",$interesting_city_ids);  for($fs=0;$fs<count($fam_val);$fs++) { if($row_fv['id'] == $fam_val[$fs]) { ?> selected="selected" <?php } } ?>> <?=$row_fv['name'];?> </option>
               
                                  <?php } ?>
                                </select>
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

            <div class="form-group">
                <label class="col-sm-2">Chat Status :</label>
                <div class="col-sm-6">
                    <div class="col-sm-3 col-xs-6">
                        <label><input name="is_chat_block" type="radio" value="0" <?php if($is_chat_block!=1){  ?>checked="checked"<?php } ?>/>
                          Active</label>
                    </div>
                    <div class="col-sm-6 col-xs-6">
                        <label><input name="is_chat_block" type="radio" value="1"  <?php if($is_chat_block==1){  ?>checked="checked"<?php } ?> />
                          Blocked</label>
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
</body>
</html>
