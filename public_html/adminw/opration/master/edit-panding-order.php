
<?php 
  include("../../database.php"); 

$getid = base64_decode($_GET['key']);


if(isset($_POST['update']))
{

$status=$_POST['status'];

$updateqry="UPDATE product_order  SET status='$status' WHERE order_id='$getid'";
$order_update=$conn->query($updateqry);

if($order_update==true)
{
    //header("location:../manage-panding-order.php");
    echo "<script>location.href='../manage-panding-order.php';</script>";
    exit();
    
}
else
{
     header("location:../index.php");
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

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <!--
            <div class="box-header">
              <h3 class="box-title">Hover Data Table</h3>
            </div>
            -->
             <div class="box-body">
              <h4>
                Pending-Order
              </h4>
            </div>
          </div>

          <div class="box">
            <div class="box-body table-responsive">
              <table id="datatable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>product Name</th>
                  <th>Product Image</th>
                  <th>Quantity</th>
                  <th>Unit</th>
                  <th>Oreder Date</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  $getuserqry = $conn->query("SELECT *,product.name as product_name,product.v_img1,m_unit.name as unit_name FROM product_order LEFT JOIN product
ON product_order.product_id = product.id LEFT JOIN m_unit ON product_order.unit=m_unit.id   where order_id='$getid' ");
                  $i = 1;
                  while($rowuser = $getuserqry->fetch_array()){

                      $date=date("d-m-Y", strtotime($rowuser['created_date']));
                ?>
                <tr>
                  <td><?=$i++;?></td>
                  <td><?=$rowuser['product_name']?></td>
                   <td><img style="height: 100px;width: 100px" src="../product-img/<?=$rowuser['v_img1']?>"></td>
                  <td><?=$rowuser['quantity']?></td>
                  <td><?=$rowuser['unit_name']?></td>
                  <td><?php echo $date ; ?></td>
                </tr>
                <?php } ?>
              </table>
             <form action="" method="POST">
              <div class="form-group" style="margin-top: 25px">
              <label class="control-label col-sm-2">Order Status :</label>
              <div class="col-sm-10" >
                <input type="hidden" name="hi" value="h1">
                <input type="radio" name="status" value="1" checked=""> Pending 
                &nbsp;&nbsp;&nbsp;
                <input type="radio" name="status" value="2"> Confirm
                &nbsp;&nbsp;&nbsp;
                <input type="radio" name="status" value="3"> Received
              </div>
            </div>

            <div class="col-md-1" align="right" style="margin-top: 25px">
              <input type="submit" name="update" value="update" class="btn btn-success">
            </div>
          </form>
            </div>
          </div>
        </div>
      </div>
    </section>

  </div>
  <?php include("../includes/footer.php"); ?>
</div>

<?php include("../includes/js-scripts.php"); ?>
</body>
</html>