<?php 
include("../../database.php"); 

if($_POST['h1']==1)
{

$id=mysqli_real_escape_string($conn,$_POST['id']);
$name=mysqli_real_escape_string($conn,$_POST['name']);

$details=mysqli_real_escape_string($conn,$_POST['details']);

$status=mysqli_real_escape_string($conn,$_POST['status']);

      
$qury1="UPDATE m_help_support SET name='$name',details='$details',status='$status' WHERE id='$id'";       
$sq1 = $conn->query($qury1);


 header("location:../contact-details.php"); 


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
  if($_GET['id']!="")
{
   $id =$_GET['id'];
   
  $qry="select * from m_help_support WHERE id=$id";
$result = $conn->query($qry);
$row = $result->fetch_array();
    $name=htmlspecialchars($row['name']);
    $details=$row['details'];
    $status=$row['status'];
  }
?>


<div class="wrapper">
  <?php include("../includes/header.php"); ?>
  <?php include("../includes/sidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Update Contact Details</h1>
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
              <label class="control-label col-sm-2">Title :</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name" required value="<?=$name;?>" />
              </div>
            </div>

             <div class="form-group">
              <label class="control-label col-sm-2">Title :</label>
              <div class="col-sm-8">
                     <textarea class="textarea form-control" placeholder="Place Details" style="width: 100%; height: 300px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="details" id="details" ><?=$details;?></textarea>
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
