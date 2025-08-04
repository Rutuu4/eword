<?php 
include("../../database.php"); 

if($_POST['h1']==1)
{  

  $id=mysqli_real_escape_string($conn,$_POST['id']);
  $name=mysqli_real_escape_string($conn,$_POST['name']);
  $status=mysqli_real_escape_string($conn,$_POST['status']);
  $details=mysqli_real_escape_string($conn,$_POST['details']);
  $link=mysqli_real_escape_string($conn,$_POST['link']);
  $display_order=mysqli_real_escape_string($conn,$_POST['display_order']);

  $qury1="UPDATE documents_details SET name='$name',details='$details',link='$link',status='$status',display_order='$display_order' WHERE id='$id'";          
  $sq1=$conn->query($qury1);

  if(mysqli_affected_rows($conn)>=1){  header("location:../manage-document.php"); }

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
 
  $qry="SELECT * FROM documents_details WHERE id='$id'";  

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
      <h1>Update Document </h1>
    </section>

    <section class="content">

      <div class="box box-success">
     
        <div class="box-body">
          <form action="" method="POST" id="" class="form-horizontal" enctype="multipart/form-data">
            <input name="h1" type="hidden" id="h1" value="1" />
             <input name="id" type="hidden" id="id" value="<?=$id;?>" />


             <div class="form-group">
              <label class="control-label col-sm-2">Name  :</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="name"  id="name" placeholder="Enter Name " value="<?=$row['name'];?>"  required>
              </div>
            </div>
           
           <div class="form-group">
                <label for="passwrod" class="col-sm-2"> Description :</label>
                <div class="col-sm-8">
                     
                    <textarea class="form-control" placeholder="Description" style="width: 100%; height: 70px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="details" id="details" ><?=$row['details'];?></textarea>
                        
                </div>
            </div>
            

   
            <div class="form-group">
              <label class="control-label col-sm-2">Website Link  :</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="link"  id="link" placeholder="Enter Website Link " value="<?=$row['link'];?>"  required>
              </div>
            </div>
             <div class="form-group">
              <label class="control-label col-sm-2">Display Order  :</label>
              <div class="col-sm-8">
                <input type="number" class="form-control" name="display_order"  id="display_order" placeholder="Enter Display Order " value="<?=$row['display_order'];?>"  required>
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
});
</script>

</body>
</html>
