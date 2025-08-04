<?php 
  include("../../database.php"); 
 
  if(isset($_POST['save']))
  {

    $name=mysqli_real_escape_string($conn,$_POST['name']);
    $mobile=mysqli_real_escape_string($conn,$_POST['mobile']);
    $alternative_phone=mysqli_real_escape_string($conn,$_POST['alternative_phone']);
    $email=mysqli_real_escape_string($conn,$_POST['email']);
    $gstno=mysqli_real_escape_string($conn,$_POST['gstno']);
    $address=mysqli_real_escape_string($conn,$_POST['address']);
    $city=mysqli_real_escape_string($conn,$_POST['city']);
    $state=mysqli_real_escape_string($conn,$_POST['state']);
    $status=mysqli_real_escape_string($conn,$_POST['status']);
    $input_date=date('Y-m-d');

    $query="INSERT INTO m_customer(name, mobile, alternative_phone, email,address, gstno, city, state, status, input_date) VALUES ('$name','$mobile','$alternative_phone','$email','$address','$gstno','$city','$state','$status','$input_date')";
    $sq1=$conn->query($query);
    if($sq1)
    {

        header("location:../manage-customer.php");  
     
      
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
      <h1>Add Customer</h1>
    </section>

    <section class="content">

      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Add Data Here</h3>
        </div>


        <div class="box-body">
          <form action="" method="POST" id="" class="form-horizontal" enctype="multipart/form-data">

            <div class="form-group">
              <label class="control-label col-sm-2">Customer Name:</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="name" placeholder="Name of Customer" required>
              </div>
            </div>
             <div class="form-group">
              <label class="control-label col-sm-2">Mobile:</label>
              <div class="col-sm-8">
                <input type="tel" class="form-control" name="mobile" placeholder="Mobile" pattern="[0-9]{10}" required>
              </div>
            </div>
              <div class="form-group">
              <label class="control-label col-sm-2">Aternative Phone:</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="alternative_phone" placeholder="Aternative Phone" >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2">Email:</label>
              <div class="col-sm-8">
                <input type="email" class="form-control" name="email" placeholder="Email" required>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2">Address:</label>
              <div class="col-sm-8">
                <textarea name="address" type="text"  class="form-control" id="address" placeholder="Address"></textarea>
              </div>
            </div>
             <div class="form-group">
              <label class="control-label col-sm-2">City:</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="city" placeholder="City" required>
              </div>
            </div>
          <div class="form-group">
              <label class="control-label col-sm-2">State:</label>
              <div class="col-sm-8">
                <select class="form-control" name="state" required="" id="stateopt">
                    <option value="">Select State</label></option>
                    <?php 
                      $query1 ="SELECT * FROM m_state WHERE status = 1";
                      $result=$conn->query($query1);
                      while($row1 = $result->fetch_array()){
                    ?>
                    <option value="<?=$row1['id']?>"><?=$row1['name']?></option>
                  <?php } ?>
                  </select>
              </div>
            </div>   
            <div class="form-group">
              <label class="control-label col-sm-2">GSTIN No:</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="gstno" placeholder="GSTIN No" >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2">Status :</label>
              <div class="col-sm-8" style="padding:7px;">
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
