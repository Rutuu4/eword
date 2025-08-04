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
                 Product Stock Report
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
                  <th>Running Stock</th>
                </tr>
                </thead>
                <tbody>
                <?php
                 $i=1;
                $qry="SELECT product.*,product_category.name AS category_name,m_unit.name AS unit_name from product 
                left join product_category on product.product_category_id=product_category.id
                left join m_unit on product.product_unit=m_unit.id   
                where product.status=1";
                $result=$conn->query($qry);
                while($row=$result->fetch_array())
                {   
                ?>
                <tr>
                  <td><?=$i++;?></td>   
                  <td><?php echo $row['category_name']?></td>
                  <td><?php echo $row['product_name']?></td>
                  <td><?php echo $row['unit_name']?></td>
                  <td><?php echo $row['product_qty']?></td>
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
