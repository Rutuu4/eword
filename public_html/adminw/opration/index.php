<?php
  include("../database.php");


/*  //TOTAL PRODUCT
  $totalpro = $conn->query("SELECT COUNT(id) AS totalpro FROM product WHERE status=1 ")->fetch_object();

  //TOTAL PRODUCT CATEGORY
  $totalproductcategory = $conn->query("SELECT COUNT(id) AS totalproductcategort FROM `product_category` WHERE status=1 ")->fetch_object();

  //TOTAL PRODUCT Supplier
  $total_supplier = $conn->query("SELECT COUNT(id) AS totalsupplier FROM `m_supplier` WHERE status=1 ")->fetch_object();

  $total_customer  = $conn->query("SELECT COUNT(id) AS totalcustomer FROM `m_customer` WHERE status=1 ")->fetch_object();*/

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?=$softtitle?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <?php include("includes/css-scripts.php"); ?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body class="<?=$bodyclass?>">

<div class="wrapper">

  <?php include("includes/header.php"); ?>
  <?php include("includes/sidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content">
      <div class="row" style="margin-bottom: 50px">
        
             <div><p style="text-align: center;font-size: 30px;font-weight: bold">Wel-Come to HI-NUC Engineering...!!! </p></div>

      </div>
       <div class="row">
        <div class="col-md-6 col-sm-12 col-xs-12">

          <div class="box box-success">
            <div class="box-body">
              <h4>
                Reorder Product Report
              </h4>
            </div>
          </div>

          <div class="box">
            <div class="box-body table-responsive">
              <table id="datatable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Category Name</th>
                  <th>Product Name</th>
                  <th>Reorder Quantit</th>
                  <th>Available Quantit</th>
                  <th>Sign</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  $qry="SELECT product.*,product_category.name AS category_name from product 
                  left join product_category on product.product_category_id=product_category.id
                  where product.status=1";
                  $result=$conn->query($qry);
                  $i = 1;
                  while($row = $result->fetch_array()){
                 if($row['reorder_qty']>$row['product_qty'])  
                 {?>
                     <tr>
                  <td><?=$i++;?></td>
                  <td><?=$row['category_name'];?></td>
                  <td><?=$row['product_name'];?></td>
                  <td><?=$row['reorder_qty'];?></td>
                  <td><?=$row['product_qty'];?></td>
                  <td><img src="../images/q3.jpg" width="30" height="16" /></td>
                 </tr>
                <?php } } ?>
              </table>
            </div>
          </div>
        </div>
        </div>
      </div>
      <div class="clearfix"></div>
    </section>
  </div>
  <!-- MODAL -->
  <div class="modal fade" id="welcomemodal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Welcome <?=ucfirst($userinfo->name)?> to <?=$softtitle?></h4>
        </div>
      </div>
    </div> 
  </div>

  <?php include("includes/footer.php"); ?>

  <div class="control-sidebar-bg"></div>
</div>

<?php include("includes/js-scripts.php"); ?>
<script src="../dist/js/jquery.cookie.min.js"></script>
<script>
if($.cookie('welcook') != 'DialogShown'){
  $('#welcomemodal').modal('show');
  setCookie();
}

function setCookie(){
  $.cookie('welcook', 'DialogShown',
  {
    expires: Date.now() + (24 * 60 * 60 * 1000) // now add 24 hours
  });
}
</script>
</body>
</html>
