<?php 
  include("../../database.php"); 

if($_POST['h1']==1)
{  

$alias_iimmgg=date("YmdHIS  "); 
$title=mysqli_real_escape_string($conn,$_POST['title']);
$mesage=mysqli_real_escape_string($conn,$_POST['mesage']);
$datee=date('Y-m-d');


//img upload to dir
$idir = "notification-img/"; 
$idirr = "../../../notification-img/";  
$userfile_extn=explode(".",strtolower($_FILES['img']['name']));

//image validation jpeg,png images
if (($userfile_extn[1] != "jpg") && ($userfile_extn[1] != "jpeg") && ($userfile_extn[1] != "png") &&($userfile_extn[1] != "")) 
{
$msg="Image File Extention Invalid , Please Upload Valid Image";    
    
}else{

//copy to images to folder
if(!$_FILES['img']['tmp_name']==""){
$copy = copy($_FILES['img']['tmp_name'], $idirr."".$alias_iimmgg."-".time().".".$userfile_extn[1]);
$img=$idir."".$alias_iimmgg."-".time().".".$userfile_extn[1];
if($img==$idir."."){ $img=""; }

}

$details=mysqli_real_escape_string($conn,$_POST['details']);
$status=mysqli_real_escape_string($conn,$_POST['status']);

$qury1="INSERT INTO mobile_notification(title, mesage,datee, img,details,status) VALUES ('$title','$mesage','$datee','$img','$details','$status')";

//echo $qury1;          
          
$sq1 = $conn->query($qury1);  

$last_idd=mysqli_insert_id($conn);
if(!$last_idd==""){ header("location:../notification.php"); }

} }

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
<div class="wrapper">
  <?php include("../includes/header.php"); ?>
  <?php include("../includes/sidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Create Product</h1>
    </section>

    <section class="content">

      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Add Data Here</h3>
        </div>

        <div class="box-body">
          <form action="" method="POST" id="" class="form-horizontal" enctype="multipart/form-data">
            <input name="h1" type="hidden" id="h1" value="1" />


            <div class="form-group">
              <label class="control-label col-sm-2">Notification Title :</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="title"  id="title" placeholder="Enter Notification Title"  required>
              </div>
            </div>

             <div class="form-group">
              <label class="control-label col-sm-2">Notification mesage :</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="mesage"  id="mesage" placeholder="Enter Notification mesage"  required>
              </div>
            </div>


            <div class="form-group">
                <label for="email" class="col-sm-2">Notification Image :</label>
                <div class="col-sm-8">
                     <input name="img" id="img" type="file"  class="file" multiple=true data-preview-file-type="any">
                </div>
            </div>

            <div class="form-group">
                <label for="passwrod" class="col-sm-2">Description :</label>
                <div class="col-sm-8">
                     
                    <textarea class="form-control textarea" placeholder="Description" style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="details" id="details" ></textarea>
                        
                </div>
            </div>




            <div class="form-group">
              <label class="control-label col-sm-2">Status :</label>
              <div class="col-sm-10" >
                <input type="radio" name="status" value="1" checked=""> Active
                &nbsp;&nbsp;&nbsp;
                <input type="radio" name="status" value="0" > Deactive
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
