<?php 
include("../../database.php"); 

if($_POST['h1']==1)
{  


  $name=mysqli_real_escape_string($conn,$_POST['name']);
  $status=mysqli_real_escape_string($conn,$_POST['status']);
  $link=mysqli_real_escape_string($conn,$_POST['link']);


$alias_iimmgg=date("Ymd");
$datee=date('Y-m-d'); 
$id=mysqli_real_escape_string($conn,$_POST['id']);


$qm1=$_POST['qm1'];

$idir = "banner-images/";     
$idirr = "../../../banner-images/"; 
 
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



$qury1="UPDATE banner SET name='$name',link='$link',img='$photos',status='$status' WHERE id='$id'";          
$sq1 = $conn->query($qury1);

if(mysqli_affected_rows($conn)>=1){  header("location:../manage-banner.php"); }

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
 
  $qry="SELECT * FROM banner WHERE id='$id'";  

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
      <h1>Update Banner</h1>
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
             <input name="qm" type="hidden" id="id" value="<?=$qm;?>" />


                <div class="form-group">
              <label class="control-label col-sm-2">Name  :</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="name"  id="name" placeholder="Enter Name " value="<?=$row['name'];?>"  required>
              </div>
            </div>
              <div class="form-group">
              <label class="control-label col-sm-2">Website Link :</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="link"  id="link" placeholder="Enter Website Link " value="<?=$row['link'];?>" required>
              </div>
            </div>
           

            <div class="form-group">
                <label for="email" class="col-sm-2">Photo :</label>
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
