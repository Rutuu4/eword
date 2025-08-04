<?php 
include("../../database.php"); 

if($_POST['h1']==1)
{  

  $id=mysqli_real_escape_string($conn,$_POST['id']);
  $title_name=mysqli_real_escape_string($conn,$_POST['title_name']);
  $video_link=mysqli_real_escape_string($conn,$_POST['video_link']);
  $status=mysqli_real_escape_string($conn,$_POST['status']);
  $details=mysqli_real_escape_string($conn,$_POST['details']);
  $alias_iimmgg=date("Ymd");
  $qm1=$_POST['qm1'];

    $idir = "notification-img/"; 
    $idirr = "../../../notification-img/";   
     
    $userfile_extn=explode(".",strtolower($_FILES['photos']['name']));

    //image validation jpeg,png images
    if (($userfile_extn[1] != "jpg") && ($userfile_extn[1] != "jpeg") && ($userfile_extn[1] != "png") &&($userfile_extn[1] != "")) 
    {
    $msg="Image File Extention Invalid , Please Upload Valid Image";    
        
    }else{

    //copy to images to folder
    if(!$_FILES['photos']['tmp_name']==""){
    $copy = copy($_FILES['photos']['tmp_name'], $idirr."".$alias_iimmgg."-1-".time().".".$userfile_extn[1]);
    $photos=$idir."".$alias_iimmgg."-1-".time().".".$userfile_extn[1];
    if($photos==$idir."."){ $photos=""; } else{ unlink("../../$qm1"); }

    } }

    if($photos==""){ $photos=$qm1; }
    $title=mysqli_real_escape_string($conn,$_POST['title']);
$mesage=mysqli_real_escape_string($conn,$_POST['mesage']);

  $qury1="UPDATE mobile_notification SET title='$title',mesage='$mesage',img='$photos',details='$details',status='$status' WHERE id='$id'";          
  $sq1=$conn->query($qury1);

  if(mysqli_affected_rows($conn)>=1){  header("location:../notification.php"); }

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

if(!empty($_GET['key'])){

  $id=base64_decode($_GET['key']);
 
  $qry="SELECT * FROM mobile_notification WHERE id='$id'";  

  $result = $conn->query($qry);
  $row = $result->fetch_array();
  $status=$row['status'];
  $qm1=$row['img'];

}
?> 


<div class="wrapper">
  <?php include("../includes/header.php"); ?>
  <?php include("../includes/sidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Update Notification </h1>
    </section>

    <section class="content">

      <div class="box box-success">
     
        <div class="box-body">
          <form action="" method="POST" id="" class="form-horizontal" enctype="multipart/form-data">
            <input name="h1" type="hidden" id="h1" value="1" />
             <input name="id" type="hidden" id="id" value="<?=$id;?>" />
              <input name="qm" type="hidden" id="id" value="<?=$qm;?>" />


             
               <div class="form-group">
              <label class="control-label col-sm-2">Notification Title :</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="title"  id="title" placeholder="Enter Notification Title" value="<?=$row['title'];?>"  required>
              </div>
            </div>

             <div class="form-group">
              <label class="control-label col-sm-2">Notification mesage :</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="mesage"  id="mesage" placeholder="Enter Notification mesage" value="<?=$row['mesage'];?>"   required>
              </div>
            </div>

              <div class="form-group">
                <label for="email" class="col-sm-2">Image   :</label>
                <div class="col-sm-8">
                     <input name="photos" id="photos" type="file"  class="file" multiple=true data-preview-file-type="any">
                     <input name="qm1" type="hidden" id="qm1" value="<?=$qm1;?>" />
                </div>
            </div>

            <?php if(!$qm1==""){ ?>               
                   <div class="file-preview"  style="width:65%;margin-left:17%;">
                   <div class="file-preview-status text-center text-success"></div>
                   <!--<div class="close fileinput-remove text-right">×</div>-->
                   <div class="file-preview-thumbnails">
                <div class="file-preview-frame"><img src="../../<?=$define_all_photos_base_path_folder;?><?=$qm1;?>" class="file-preview-image"></div>Current Photo</div>
                   <div class="clearfix"></div></div>
                   <?php } ?>


             <div class="form-group">
                <label for="passwrod" class="col-sm-2">Description :</label>
                <div class="col-sm-8">
                     
                    <textarea class="form-control textarea" placeholder="Description" style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="details" id="details" ><?=$row['details'];?></textarea>
                        
                </div>
            </div>


           
            <div class="form-group">
                      <label class="col-sm-2">Status :</label>
                      <div class="col-sm-6">
                        <div class="col-sm-3 col-xs-6">
                          <label>
                          <input name="status" type="radio" value="1" <?php if($status==1){  ?>checked="checked"<?php } ?>/>
                          Active</label>
                        </div>
                        <div class="col-sm-6 col-xs-6">
                          <label>
                          <input name="status" type="radio" value="0"  <?php if($status==0){  ?>checked="checked"<?php } ?> />
                          Deactive</label>
                        </div>
                      </div>
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
     //bootstrap WYSIHTML5 - text editor
    $(".textarea").wysihtml5();
});
</script>
<script>
$(document).ready(function(){
   $("#main_courses_id").change(function(){
    var main_courses_id = $(this).find(":selected").val();
    if(main_courses_id != ""){
      $.ajax({
        type: "POST",
        url: "master/ajax/ajax-show-extra-course.php",
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
