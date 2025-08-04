<?php 
include("../../database.php"); 

if($_POST['h1']==1)
{  

  $name=mysqli_real_escape_string($conn,$_POST['name']);
  $status=mysqli_real_escape_string($conn,$_POST['status']);


  $qury1="insert into m_year(id,name,status)values(NULL,'$name','$status')";
  
  // echo $qury1;         
  $sq1 = $conn->query($qury1);  
  $last_idd=mysqli_insert_id($conn);
  if(!$last_idd==""){ header("location:../manage-year.php"); }

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
      <h1>Create Year</h1>
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
              <label class="control-label col-sm-2">Name :</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="name"  id="name" placeholder="Enter Name "  required>
              </div>
            </div>

            
                       
            <div class="form-group">
                <label class="col-sm-2">Status :</label>
                <div class="col-sm-6">
                   <div class="col-sm-3 col-xs-6">
                       <label><input name="status" type="radio" value="1" checked="checked"/>Active</label>
                   </div>
                   <div class="col-sm-6 col-xs-6">
                       <label><input name="status" type="radio" value="0"   />Deactive</label>
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
