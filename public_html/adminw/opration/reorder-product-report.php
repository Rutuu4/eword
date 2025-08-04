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
          <div class="box box-success">
            <!--
            <div class="box-header">
              <h3 class="box-title">Hover Data Table</h3>
            </div>
            -->
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
    </section>
 
  </div>
  <?php include("includes/footer.php"); ?>
</div>

<?php include("includes/js-scripts.php"); ?>
<script>
$(document).ready(function(){
  //datatable
  $('#datatable').DataTable()

  $(".deleteproduct").click(function(){
    var key = $(this).data("key");
    if (confirm('Are you sure you want to delete this?')) {
      $.ajax({
        url: 'master/delete-product.php',
        type: "POST",
        data: {key:key},
        success: function (response){
          if(response == "TRUE" && response != ""){
            location.reload();
          }else{
            alert("Please Try Again .!");
          }
        }
      });
    }
  });
});
</script>
</body>
</html>
