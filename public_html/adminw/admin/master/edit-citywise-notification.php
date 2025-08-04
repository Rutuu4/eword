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

$website_link=mysqli_real_escape_string($conn,$_POST['website_link']);

if(!empty($_POST['city_ids']))
{
    $city_ids=implode(",",$_POST['city_ids']);
}


/*  $qury1="UPDATE mobile_notification SET title='$title',mesage='$mesage',img='$photos',details='$details',status='$status',website_link='$website_link',city_ids='$city_ids' WHERE id='$id'";          
  $sq1=$conn->query($qury1);*/

  if(mysqli_affected_rows($conn)>=1){  header("location:../citywise-notification.php"); }

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
  $city_ids=$row['city_ids'];

}
?> 


<div class="wrapper">
  <?php include("../includes/header.php"); ?>
  <?php include("../includes/sidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>View Citywise Notification Details</h1>
    </section>

    <section class="content">

      <div class="box box-success">
     
        <div class="box-body">
          <form action="" method="POST" id="" class="form-horizontal" enctype="multipart/form-data">
            <input name="h1" type="hidden" id="h1" value="1" />
             <input name="id" type="hidden" id="id" value="<?=$id;?>" />
              <input name="qm" type="hidden" id="id" value="<?=$qm;?>" />


             
               <div class="form-group">
              <label class="control-label col-sm-2"> Title :</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="title"  id="title" placeholder="Enter  Title" value="<?=$row['title'];?>"  readonly>
              </div>
            </div>

             <div class="form-group">
              <label class="control-label col-sm-2">Short mesage :</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="mesage"  id="mesage" placeholder="Enter Short mesage" value="<?=$row['mesage'];?>"  readonly>
              </div>
            </div>

              <div class="form-group">
                <label for="email" class="col-sm-2"> Image   :</label>
                
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
                     
                    <textarea class="form-control textarea" placeholder="Description" style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="details" id="details" readonly><?=$row['details'];?></textarea>
                        
                </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-2">Website Link :</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="website_link"  id="website_link" placeholder="Enter Website Link " readonly value="<?=$row['website_link'];?>" >
              </div>
            </div>
             <div class="form-group">
    <label for="usernamee" class="col-sm-2">Select City :</label>
    <div class="col-sm-8 row">
        <?php 
            $sql_fv = "SELECT id, name FROM m_city WHERE status=1 order by name ASC ";
            $result_fv = $conn->query($sql_fv);
            while ($row_fv = $result_fv->fetch_array()) {
        ?>
            <div class="col-sm-3">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" <?php $fam_val=explode(",",$city_ids);  for($fs=0;$fs<count($fam_val);$fs++) { if($row_fv['id'] == $fam_val[$fs]) { ?> checked <?php } } ?> name="city_ids[]" value="<?=$row_fv['id'];?>" disabled> <?=$row_fv['name'];?>
                    </label>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
           
            <!--<div class="form-group">
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
                    </div>-->

            
          <!--  <div class="col-md-12" align="right">
              <button type="submit" class="btn btn-success ">Save changes</button>
            </div>-->

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
