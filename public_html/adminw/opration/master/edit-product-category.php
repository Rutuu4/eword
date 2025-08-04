
<?php 
  include("../../database.php"); 
  $message1 = "";

  if(empty($_GET['key'])){
    echo "<a href='index.php'>Server Issue Click Here to Go back</a>";
    exit;
  }else{
    $getid = base64_decode($_GET['key']);
    $getdata = $conn->query("SELECT * FROM product_category WHERE id = $getid")->fetch_object();
  }

  if(isset($_POST['save'])){

   $name = mysqli_real_escape_string($conn,$_POST['name']);
    $status = $_POST['status'];

    $updateqry = "UPDATE product_category SET name='$name',status='$status' WHERE id = $getid";
    $exeUpdate = $conn->query($updateqry);
    if($exeUpdate){
      if($conn->affected_rows > 0){
        header("Location: ../manage-product-category.php");
        exit;
      }
      else
      {
        $message1 = "There is Nothing To Update";
      }
    }
    else
    {
      header("Location: index.php");
    }
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
  </style>
</head>
<body class="<?=$bodyclass?>">
<div class="wrapper">
  <?php include("../includes/header.php"); ?>
  <?php include("../includes/sidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Update Product Category</h1>
    </section>

    <section class="content">

      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Update Data Here</h3>
        </div>

        <div class="box-body">
          <form action="" method="POST" class="form-horizontal">

            <div class="form-group">
              <label class="control-label col-sm-2">Product Category Name</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="name" value="<?=$getdata->name?>" required>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2">Status :</label>
              <div class="col-sm-10" style="padding:7px;">
                <input type="radio" name="status" <?php if($getdata->status == 1)echo "checked"; ?> value="1"> Active
                &nbsp;&nbsp;&nbsp;
                <input type="radio" name="status" <?php if($getdata->status == 0)echo "checked"; ?> value="0"> Deactive
              </div>
            </div>

            <div class="col-md-12" align="right">
              <input type="submit" name="save" value="Save" class="btn btn-success">
              <p class="text-red"><?=$message1?></p>
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
