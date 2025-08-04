<?php
  include("../database.php");
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
            <div class="box-body">
              <h4>
              Verified GRN Report
                
              </h4>
              
               <?php 
                  $challan_id = $_POST['challan_id'];
                  $grn_no = $_POST['grn_no'];
              ?>
              <form id="form4" name="form4" method="post" action="">
                 
                <div class="form-group">
                  <div class="col-sm-2">
                    <label>Challan No</label>
                    <div>
                      <input type="text" class="form-control" value="<?php echo $challan_id ; ?>" name="challan_id" required>
                    </div>
                  </div>
                </div>
                 <div class="form-group">
                  <div class="col-sm-2">
                    <label>GRN No</label>
                    <div>
                      <input type="text" class="form-control" value="<?php echo $grn_no ; ?>" name="grn_no" required>
                    </div>
                  </div>
                </div>

               <div class="col-lg-3" style="padding-top:24px">
                    <button style="display: inline-block;" name="search_process" class="btn btn-primary pull-left"><i class="fa fa-search"></i>Search</button>
                  </div>
                <!-- /.input group -->
               </div>
              </form>
            </div>
          <div class="box">
            <div class="box-body table-responsive">
            <table id="datatable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Verify-Date</th>
                  <th>Verify-Person</th>
                  <th>GRN-No</th>
                  <th>Challan No</th> 
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
                if(!empty($challan_id) && !empty($grn_no))
                {
          $qry="SELECT verify_po.*,product.product_name,user.name AS user_name,po_product.quantity,po_product.quantity_running_stock,po.po_number,product_category.name AS category_name from verify_po 
           left join product on verify_po.product_id=product.id
           left join product_category on product.product_category_id=product_category.id
           left join user on verify_po.u_id=user.id
           left join po_product on verify_po.po_product_id=po_product.id 
           left join po on verify_po.po_id=po.id 
           where verify_po.po_id='$challan_id' and verify_po.grn_no='$grn_no'";
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
                  <td><?=$row['grn_no'];?></td>
                  <td><?=$row['chalan_no'];?></td>
                  <td><?=$row['po_number'];?></td>
                  <td><?=$row['invoice_no'];?></td>
                  <td><?=$row['category_name']; ?></td>
                  <td><?=$row['product_name']; ?></td>
                  <td><?=$row['quantity'];?></td>
                  <td><?=$row['verify_quantity'];?></td>
                  <td><?=$status?></td>
                </tr>
                <?php } } ?>
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
