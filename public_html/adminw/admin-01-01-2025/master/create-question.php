<?php 
include("../../database.php"); 

if($_POST['h1']==1)
{  

  $title_name=mysqli_real_escape_string($conn,$_POST['title_name']);
  $video_link=mysqli_real_escape_string($conn,$_POST['video_link']);
  $details=mysqli_real_escape_string($conn,$_POST['details']);

  $status=mysqli_real_escape_string($conn,$_POST['status']);
  $create_datetime=date("Y-m-d H:i:s");

  $qury1="INSERT INTO question_list(title_name, video_link, details, status, user_id, create_datetime) VALUES ('$title_name','$video_link','$details','$status','$login_id','$create_datetime')";          
  $sq1=$conn->query($qury1);

  if(mysqli_affected_rows($conn)>=1){  header("location:../manage-question.php"); }

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

<div class="wrapper">
  <?php include("../includes/header.php"); ?>
  <?php include("../includes/sidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Add Question</h1>
    </section>

    <section class="content">

      <div class="box box-success">
     
        <div class="box-body">
          <form action="" method="POST" id="" class="form-horizontal" enctype="multipart/form-data">
            <input name="h1" type="hidden" id="h1" value="1" />
             


            <div class="form-group">
              <label class="control-label col-sm-2">Title Name  :</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="title_name"  id="name" placeholder="Enter Title Name " value="<?=$row['title_name'];?>"  required>
              </div>
            </div>

             <div class="form-group">
              <label class="control-label col-sm-2">Video Link  :</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="video_link"  id="video_link" placeholder="Enter Video Link " value="<?=$row['video_link'];?>"  required>
              </div>
            </div>

            <div class="form-group">
                <label for="passwrod" class="col-sm-2">Description :</label>
                <div class="col-sm-8">
                     
                    <textarea class="form-control textarea" placeholder="Description" style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="details" id="details" ></textarea>
                        
                </div>
            </div>

           
            <div class="form-group">
                      <label class="col-sm-2">Status :</label>
                      <div class="col-sm-6">
                        <div class="col-sm-3 col-xs-6">
                          <label>
                          <input name="status" type="radio" value="1" checked="checked" />
                          Active</label>
                        </div>
                        <div class="col-sm-6 col-xs-6">
                          <label>
                          <input name="status" type="radio" value="0"   />
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
  $(".textarea").wysihtml5();
});
</script>

</body>
</html>
