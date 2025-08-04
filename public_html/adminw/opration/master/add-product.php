<?php 
  include("../../database.php"); 

  if(isset($_POST['save']) && strlen($_POST['product_name'])>1){

    $product_category_id=mysqli_real_escape_string($conn,$_POST['product_category_id']);
    $product_sku=mysqli_real_escape_string($conn,$_POST['product_sku']);
    $product_name=mysqli_real_escape_string($conn,$_POST['product_name']);
    $product_unit=mysqli_real_escape_string($conn,$_POST['product_unit']);
    $description=mysqli_real_escape_string($conn,$_POST['description']);
    $hsn_code=mysqli_real_escape_string($conn,$_POST['hsn_code']);
    $gst_tax=mysqli_real_escape_string($conn,$_POST['gst_tax']);
    $status=mysqli_real_escape_string($conn,$_POST['status']);
    $reorder_qty=mysqli_real_escape_string($conn,$_POST['reorder_qty']);



      $insertqry="INSERT INTO product(product_category_id, product_sku, product_name, product_unit, status, gst_tax, description, hsn_code,reorder_qty) VALUES ('$product_category_id','$product_sku','$product_name','$product_unit','$status','$gst_tax','$description','$hsn_code','$reorder_qty')";
    $exeUpdate = $conn->query($insertqry);
    if($exeUpdate)
    { 
        header("Location: ../manage-product.php");
        exit;
    }
    else{
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
      color: #d84444;
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
      <h1>Add Product</h1>
    </section>

    <section class="content">

      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Add Data Here</h3>
        </div>

        <div class="box-body">
          <div class="col-sm-7">
            <form action="" method="POST" class="form-horizontal" id="adduserform" enctype="multipart/form-data">
              <div class="form-group">
                <label class="control-label col-sm-3">Product Category <span class="text-red"></span></label>
                <div class="col-sm-9 ">
                  <select class="form-control" name="product_category_id" required="">
                    <option value="">Select Product Category </label></option>
                    <?php 
                      $query1 = $conn->query("SELECT * FROM product_category WHERE status = 1");
                      while($row1 = $query1->fetch_array()){
                    ?>
                    <option value="<?=$row1[0]?>"><?=$row1[1]?></option>
                  <?php } ?>
                  </select>
                </div>
              </div>
               <div class="form-group">
                <label class="control-label col-sm-3">Product Name :</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="product_name" required placeholder="Product Name">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-3">Product Unit <span class="text-red"></span></label>
                <div class="col-sm-9 ">
                  <select class="form-control" name="product_unit" required="" id="stateopt">
                    <option value="">Select Product Unit </label></option>
                    <?php 
                      $query1 = $conn->query("SELECT * FROM m_unit WHERE status = 1");
                      while($row1 = $query1->fetch_array()){
                    ?>
                    <option value="<?=$row1[0]?>"><?=$row1[1]?></option>
                  <?php } ?>
                  </select>
                </div>
              </div>
             <div class="form-group">
                      <label for="passwrod" class="col-sm-3">Description :</label>
                      <div class="col-sm-9">    
                         <textarea class="textarea form-control" placeholder="Description" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="description" id="details"></textarea>
                        
                      </div>
                </div>
               <div class="form-group">
                <label class="control-label col-sm-3">Re-order Quantity:</label>
                <div class="col-sm-9">
                  <input type="number" class="form-control" name="reorder_qty" required placeholder="Re-order Quantity">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-3">HSN Code:</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="hsn_code"  placeholder="HSN Code">
                </div>
              </div> 
                <div class="form-group">
                <label class="control-label col-sm-3">GST Tax(%):</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="gst_tax"  placeholder="GST Tax(%)">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-3">Status :</label>
                <div class="col-sm-9" style="padding:7px;">
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
          <div class="col-sm-5">
           
          </div>
        </div>
        
      </div>
    </section>
  </div>
  <?php include("../includes/footer.php"); ?>
</div>
<?php include("../includes/js-scripts.php"); ?>
</body>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<!-- Select2 -->
<script src="../plugins/select2/select2.full.min.js"></script>
<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();

     //bootstrap WYSIHTML5 - text editor
    $(".textarea").wysihtml5();

    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,
    format: "dd/mm/yy" 
    });

   
  });
  
</script>
</html>
