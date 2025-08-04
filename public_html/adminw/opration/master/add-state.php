<?php 
  include("../../database.php"); 
  
  function cleanInP($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  if(isset($_POST['save'])){

    $name = cleanInP($_POST['name']);
    $status = $_POST['status'];

    $insertqry = "INSERT INTO `m_state`(`name`, `status`) VALUES('$name','$status')";
    $exeUpdate = $conn->query($insertqry);
    if($exeUpdate){
        header("Location: ../manage-state.php");
        exit;
    }else{
      header("Location: ../index.php");
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
      <h1>Add State</h1>
    </section>

    <section class="content">

      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Add Data Here</h3>
        </div>

        <div class="box-body">
          <form action="" method="POST" class="form-horizontal">

            <div class="form-group">
              <label class="control-label col-sm-2">State Name :</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="name" required>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2">Status :</label>
              <div class="col-sm-10" style="padding:7px;">
                <input type="radio" name="status" value="1" checked=""> Active
                &nbsp;&nbsp;&nbsp;
                <input type="radio" name="status" value="0"> Deactive
              </div>
            </div>

            <div class="col-md-12" align="right">
              <input type="submit" name="save" value="Save" class="btn btn-success">
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
