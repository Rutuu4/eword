<?php
  include("../database.php");
   if(empty($_GET['key'])){
    echo "<a href='index.php'>Server Issue Click Here to Go back</a>";
    exit;
  }else{
    $getid = base64_decode($_GET['key']);
  }


?> 
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?=$softtitle?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <?php include("includes/css-scripts.php"); ?>

</head>
<body class="<?=$bodyclass?>">
 
<div class="wrapper">

  <?php include("includes/header.php"); ?>
  <?php include("includes/sidebar.php"); ?>

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
               Verify PO Details
              </h4>
            </div>
          </div>
          <div class="box">
            <div class="box-body table-responsive">
            <table id="datatable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Verify-Date</th>
                  <th>Verify-Person</th>
                  <th>Challan No</th>
                  <th>GRN-No</th> 
                  <th>PO Number</th> 
                  <th>Invoice No</th>
                  <th>Category Name</th>
                  <th>Product Name</th>
                  <th>Quantity</th>
                  <th>Verify Quantity</th>
                  <th>Status</th>
                </tr> 
                </thead>
                <tbody>
                <?php
          $qry="SELECT verify_po.*,product.product_name,user.name AS user_name,po_product.quantity,po_product.quantity_running_stock,po.po_number,product_category.name AS category_name from verify_po 
           left join product on verify_po.product_id=product.id
           left join product_category on product.product_category_id=product_category.id
           left join user on verify_po.u_id=user.id
           left join po_product on verify_po.po_product_id=po_product.id 
           left join po on verify_po.po_id=po.id 
           where verify_po.po_id='$getid'";
           $result=$conn->query($qry); 
                  $i = 1;
                  while($row = $result->fetch_array()){
                  if(!empty($row['created_date'])){ $date=date("d-m-Y", strtotime($row['created_date'])); } else{ $date='';}
                    $status = "<span class='badge bg-green'>verify</span>";
                ?>
                <tr>
                  <td><?=$i++;?></td>
                  <td><?=$date;?></td>
                  <td><?=$row['user_name'];?></td>
                  <td><?=$row['chalan_no'];?></td>
                  <td><?=$row['grn_no'];?></td>
                  <td><?=$row['po_number'];?></td>
                  <td><?=$row['invoice_no'];?></td>
                  <td><?=$row['category_name']; ?></td>
                  <td><?=$row['product_name']; ?></td>
                  <td><?=$row['quantity'];?></td>
                  <td><?=$row['verify_quantity'];?></td>
                  <td><?=$status?></td>
                </tr>
                <?php } ?>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>

  </div>
  <?php include("includes/footer.php"); ?>
</div>

<?php include("includes/js-scripts.php"); ?>
<script>
$(document).ready(function(){
  //datatable
  $('#datatable').DataTable()

});
</script>
</body>
</html>
