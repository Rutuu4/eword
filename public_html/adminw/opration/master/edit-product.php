<?php 
  include("../../database.php"); 

if(empty($_GET['key'])){
    echo "<a href='index.php'>Server Issue Click Here to Go back</a>";
    exit;
  }else{
    $getid = base64_decode($_GET['key']);

    $qry="SELECT * from product where id='$getid'";
    $result=$conn->query($qry);
    $get_row=$result->fetch_array();

    $product_category_id=$get_row['product_category_id'];
    $status=$get_row['status'];
    $product_unit=$get_row['product_unit'];
    $id=$get_row['id'];

  }


 if(isset($_POST['save']) && strlen($_POST['product_name'])>1){

 
    $id=$_POST['id'];
    $product_category_id=mysqli_real_escape_string($conn,$_POST['product_category_id']);
    $product_sku=mysqli_real_escape_string($conn,$_POST['product_sku']);
    $product_name=mysqli_real_escape_string($conn,$_POST['product_name']);
    $product_unit=mysqli_real_escape_string($conn,$_POST['product_unit']);
    $description=mysqli_real_escape_string($conn,$_POST['description']);
    $hsn_code=mysqli_real_escape_string($conn,$_POST['hsn_code']);
    $gst_tax=mysqli_real_escape_string($conn,$_POST['gst_tax']);
    $status=mysqli_real_escape_string($conn,$_POST['status']);   
    $reorder_qty=mysqli_real_escape_string($conn,$_POST['reorder_qty']); 
    

      $updateqry = "UPDATE product SET product_category_id='$product_category_id',product_sku='$product_sku',product_name='$product_name',product_unit='$product_unit',hsn_code='$hsn_code',gst_tax='$gst_tax',status='$status',description='$description',reorder_qty='$reorder_qty'  WHERE id='$id'";

    $exeUpdate = $conn->query($updateqry);
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
      <h1>Update Product To <?=$softtitle?></h1>
    </section>

    <section class="content">

      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Update Data Here</h3>
        </div>

        <div class="box-body">
          <div class="col-sm-7">
            <form action="" method="POST" class="form-horizontal" id="adduserform" enctype="multipart/form-data">
               <div class="form-group">
                          <label class="control-label col-sm-3">Product Category :</label>
                          <div class="col-sm-9">
                            <select class="form-control" name="product_category_id">
                              <option value="">Select Product Category </label></option>
                              <?php 
                                $query1 = $conn->query("SELECT * FROM product_category WHERE status = 1");
                                while($row1 = $query1->fetch_array()){
                              ?>
                              <option <?php if($product_category_id == $row1[0])echo "selected"; ?> value="<?=$row1[0]?>"><?=$row1[1]?></option>
                            <?php } ?>
                            </select>
                          </div>
                        </div>

               <div class="form-group">
                <label class="control-label col-sm-3">Product Name :</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" value="<?php echo $get_row['product_name'] ; ?>" name="product_name" required placeholder="Product Name">
                  <input type="hidden" name="id" value="<?php echo $id ; ?>"> 
                  <input type="hidden" name="qm1" value="<?php echo $qm1; ?>" >
                </div>
              </div>
                 <div class="form-group">
                          <label class="control-label col-sm-3">Product Unit :</label>
                          <div class="col-sm-9">
                            <select class="form-control" name="product_unit">
                              <option value="">Select Product Unit </label></option>
                              <?php 
                                $query1 = $conn->query("SELECT * FROM m_unit WHERE status = 1");
                                while($row1 = $query1->fetch_array()){
                              ?>
                              <option <?php if($product_unit == $row1[0])echo "selected"; ?> value="<?=$row1[0]?>"><?=$row1[1]?></option>
                            <?php } ?>
                            </select>
                          </div>
                        </div>
               <div class="form-group">
                      <label for="passwrod" class="col-sm-3">Description :</label>
                      <div class="col-sm-9">    
                         <textarea class="textarea form-control" placeholder="Description" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="description" id="details"><?php echo $get_row['description']; ?></textarea>
                        
                      </div>
                </div>
                 <div class="form-group">
                <label class="control-label col-sm-3">Product Quantity:</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="product_qty" value="<?php echo $get_row['product_qty']; ?>" disabled>
                </div>
              </div>
               <div class="form-group">
                <label class="control-label col-sm-3">Re-order Quantity:</label>
                <div class="col-sm-9">
                  <input type="number" class="form-control" name="reorder_qty"  value="<?php echo $get_row['reorder_qty']; ?>" placeholder="Re-order Quantity" required>
                </div>
              </div>
        
              <div class="form-group">
                <label class="control-label col-sm-3">HSN Code:</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="hsn_code"  placeholder="HSN Code" value="<?php echo $get_row['hsn_code']; ?>">
                </div>
              </div>
                <div class="form-group">
                <label class="control-label col-sm-3">GST Tax(%):</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="gst_tax"  placeholder="GST Tax(%)" value="<?php echo $get_row['gst_tax']; ?>">
                </div>
              </div>    
              <div class="form-group">
              <label class="control-label col-sm-3">Status :</label>
              <div class="col-sm-9" style="padding:7px;">
                <input type="radio" name="status" <?php if($status == 1)echo "checked"; ?> value="1"> Active
                &nbsp;&nbsp;&nbsp;
                <input type="radio" name="status" <?php if($status == 0)echo "checked"; ?> value="0"> Deactive
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
