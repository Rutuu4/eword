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
                Product List
                <a href="master/add-product.php" class="btn btn-link pull-right" style="color: blue;">Want to Add Product ?</a>
              </h4>
            </div>
          </div>

          <div class="box">
            <div class="box-body table-responsive">
              <table id="datatable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Product Category</th>
                  <th>Product Name</th>
                  <th>Product Unit</th>
                  <th>Product Quantity</th>
                  <th>product Re-Order Qty</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  $qry="SELECT product.*,product_category.name as product_category,m_unit.name as unit_name FROM product left join product_category on product.product_category_id=product_category.id left join m_unit on product.product_unit=m_unit.id";
                  $getqry=$conn->query($qry);
                  $i = 1;
                  while($rowproduct = $getqry->fetch_array()){
                    //set status
                    if($rowproduct['status'] == 1){
                      $status = "<span class='badge bg-green'>Active</span>";
                    }else{
                      $status = "<span class='badge bg-red'>Dective</span>";
                    }
                     if($rowproduct['stockstatus'] == 1){
                      $stockstatus = "<span class='badge bg-green'>Active</span>";
                    }else{
                      $stockstatus = "<span class='badge bg-red'>Dective</span>";
                    }
                ?>
                <tr>
                  <td><?=$i++;?></td>
                  <td><?php echo $rowproduct['product_category']; ?></td>
                  <td><?php echo $rowproduct['product_name']; ?></td>
                  <td><?php echo $rowproduct['unit_name']; ?></td>
                  <td><?php echo $rowproduct['product_qty']; ?></td>
                  <td><?php echo $rowproduct['reorder_qty']; ?></td>
                  <td><?=$status?></td> 
                  <td>
                    <a href="master/edit-product.php?key=<?=base64_encode($rowproduct[0])?>" class="btn btn-warning"><i class="fa fa-edit"></i> Edit</a>
                     <span class="btn btn-danger btn-sm deleteproduct" data-key="<?=$rowproduct[0]?>"><i class="fa fa-trash"></i></span>
                  </td>
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
