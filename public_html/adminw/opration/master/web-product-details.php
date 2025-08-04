<?php 
  include("../../database.php"); 

if(empty($_GET['key'])){
    echo "<a href='index.php'>Server Issue Click Here to Go back</a>";
    exit;
  }else{
    $getid = base64_decode($_GET['key']);

    $qry="SELECT product.*,product_category.name from product  left join product_category on product.product_category_id=product_category.id where product.id='$getid'";
    $result=$conn->query($qry);
    $get_row=$result->fetch_array();

    $status=$get_row['status'];
    $id=$get_row['id'];
    $qm1=$get_row['product_img'];

  }


  if(isset($_POST['submit'])){

    $id=$_POST['id'];
    $qm1=$_POST['qm1'];

   $composition=mysqli_real_escape_string($conn,$_POST['composition']);
   $technical_details=mysqli_real_escape_string($conn,$_POST['technical_details']);
   $dose=mysqli_real_escape_string($conn,$_POST['dose']); 
   $used=mysqli_real_escape_string($conn,$_POST['used']);
   $status=mysqli_real_escape_string($conn,$_POST['status']);

  $alias_iimmgg=preg_replace('!\s+!', '-',preg_replace("/[^A-Za-z0-9 \s+]/",' ',substr(strip_tags($get_row['product_name']), 0, 30)));

      $idir = "product-img/"; 
      $idirr = "../../../product-img/";   
      $userfile_extn=explode(".",strtolower($_FILES['product_img']['name']));

    //image validation jpeg,png images
    if (($userfile_extn[1] != "jpg") && ($userfile_extn[1] != "jpeg") && ($userfile_extn[1] != "png")  && ($userfile_extn[1] != "PNG") &&($userfile_extn[1] != "")) 
    {
      $msg="Image File Extention Invalid , Please Upload Valid Image";    

    }else{

    //copy to images to folder
    if(!$_FILES['product_img']['tmp_name']==""){
    $copy = copy($_FILES['product_img']['tmp_name'], $idirr."".$alias_iimmgg."-".time().".".$userfile_extn[1]);
    $product_img=$idir."".$alias_iimmgg."-".time().".".$userfile_extn[1];
    if($product_img==$idir."."){ $product_img=$qm1; }else{ unlink("../../../$qm1"); }

  } 
    if($product_img==""){ $product_img=$qm1; } }



$updateqry = "UPDATE product SET product_img='$product_img',status='$status',composition='$composition',technical_details='$technical_details',dose='$dose',used='$used' WHERE id='$id'";
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

 <section class="col-lg-12 connectedSortable">

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Product Details</h3>
            </div>
        <div class="box-body">
            <div class="col-md-6">
            <div class="form-group" style="margin-bottom: 35px">
                      <label for="passwrod" class="col-sm-4">Product Name :</label>
                      <div class="col-sm-8">
                       <?=$get_row['product_name'];?>
                      </div>
                    </div>
                     <div class="clearfix"></div>
                     <div class="form-group">
                      <label for="passwrod" class="col-sm-4">Product Category :</label>
                      <div class="col-sm-8">
                       <?=$get_row['name'];?>
                      </div>
                    </div>
                     <div class="clearfix"></div>
                     
                    
            </div>
            <div class="col-md-6">
              <div class="form-group" style="margin-bottom: 35px">
                      <label for="passwrod" class="col-sm-4">Product MRP :</label>
                      <div class="col-sm-8">
                       <?=$get_row['mrpprice'];?>
                      </div>
                    </div>
                     <div class="clearfix"></div>
                     <div class="form-group">
                      <label for="passwrod" class="col-sm-4">Product Packaging :</label>
                     <div class="col-sm-8">
                     <?=$get_row['packaging'];?>
                      </div>
                    </div>
                    
            </div>
                     <div class="clearfix"></div>

                      <hr style="border: 3px solid #4a7ab2;border-radius: 5px;">
          <div class="col-sm-10">
            <form action="" method="POST" class="form-horizontal" id="adduserform" enctype="multipart/form-data">
                <div class="form-group">
                      <label for="passwrod" class="col-sm-3">Composition :</label>
                      <div class="col-sm-9">    
                         <textarea class="textarea form-control" placeholder="Composition" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="composition" id="details"><?php echo $get_row['composition']; ?></textarea>
                         <input type="hidden" name="id" value="<?php echo $id ; ?>"> 
                         <input type="hidden" name="qm1" value="<?php echo $qm1; ?>" >
                        
                      </div>
                </div>
                 <div class="form-group">
                      <label for="passwrod" class="col-sm-3">Technical Details :</label>
                      <div class="col-sm-9">    
                         <textarea class="textarea form-control" placeholder="Technical Details" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="technical_details" id="details"><?php echo $get_row['technical_details']; ?></textarea>
                        
                      </div>
                </div>
                 <div class="form-group">
                      <label for="passwrod" class="col-sm-3">Dose :</label>
                      <div class="col-sm-9">    
                         <textarea class="textarea form-control" placeholder="Dose" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="dose" id="details"><?php echo $get_row['dose']; ?></textarea>
                        
                      </div>
                </div>
                 <div class="form-group">
                      <label for="passwrod" class="col-sm-3">Use :</label>
                      <div class="col-sm-9">    
                         <textarea class="textarea form-control" placeholder="Use" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="used" id="details"><?php echo $get_row['used']; ?></textarea>
                        
                      </div>
                </div>
                 <?php if(!$qm1==""){ ?>  
              <div class="form-group">
                <label class="control-label col-sm-3">Current Image :</label>
                <div class="col-sm-9">
                   <img style="height: 100px;width: 100px;margin-bottom: 15px" src="../../<?php echo $qm1 ?>">
                   <input name="product_img" id="qimage" type="file"  class="file" multiple=true data-preview-file-type="any">
                </div>
              </div>             
          
               <?php }
               else
               {
                ?>

                 <div class="form-group">
                <label class="control-label col-sm-3">Image :</label>
                <div class="col-sm-9">
                   <input name="product_img" id="qimage" type="file"  class="file" multiple=true data-preview-file-type="any">
                </div>
              </div>
              <?php } ?>




              <div class="form-group">
              <label class="control-label col-sm-3">Status :</label>
              <div class="col-sm-9" style="padding:7px;">
                <input type="radio" name="status" <?php if($status == 1)echo "checked"; ?> value="1"> Active
                &nbsp;&nbsp;&nbsp;
                <input type="radio" name="status" <?php if($status == 0)echo "checked"; ?> value="0"> Deactive
              </div>
            </div>
              <div class="col-md-12" align="right">
                <input type="submit" name="submit" value="UPDATE" class="btn btn-success">
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
