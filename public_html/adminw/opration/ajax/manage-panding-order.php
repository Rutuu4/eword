<?php
  include("../database.php");
  include("../session.php");
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
              <div class="col-sm-2">
              <h4>
                Pending-Order List
              </h4>
            </div>
            <div class="col-sm-4">
              <a style="margin-left: 10px" href="panding-order-csv.php" class="btn btn-primary" role="button">Export CSV</a>
              </div>
            </div>
          </div>
 
          <div class="box">
            <div class="box-body table-responsive">
              <table id="datatable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Order Date</th>
                  <th>Create Order</th>
                  <th>Create Mobile</th>
                  <th>Order Name</th>
                  <th>Order Phone</th>
                  <th>Grand Total</th>
                  <th>Status</th>
                  <th>Action</th>
                  <th>Edit Order</th>
                <!--   <th>Edit Order</th> -->
                </tr>
                </thead>
                <tbody>
                <?php
                  $getuserqry = $conn->query("SELECT product_order.id,product_order.order_name,product_order.order_phone_no,product_order.created_date,product_order.grand_total_amount,register1.name as create_name,register1.mobile as create_phone FROM product_order LEFT JOIN register1
ON product_order.user_id = register1.id where product_order.status=1 order by product_order.created_date desc");
                  $i = 1;
                  while($roworder = $getuserqry->fetch_array()){
                      $status = "<span class='badge bg-blue'>Pending</span>";
                      if(!empty($roworder['created_date'])){ $date=date("d-m-Y", strtotime($roworder['created_date'])); }
                      else{ $date='';}
                ?>
                <tr>
                  <td><?=$i++;?></td>
                  <td><?php echo $date ?></td>
                  <td><?=$roworder['create_name'];?></td>
                  <td><?=$roworder['create_phone'];?></td>
                  <td><?=$roworder['order_name'];?></td>
                  <td><?=$roworder['order_phone_no'];?></td>
                  <td><?=$roworder['grand_total_amount'];?></td>
                  <td><?=$status?></td>
                  <td>
                    <a href="master/edit-order.php?key=<?=base64_encode($roworder['id'])?>" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                  </td>
               <td>
                   <a href="master/edit-order-qty.php?key=<?=base64_encode($roworder['id'])?>" class="btn btn-warning"><i class="fa fa-edit"></i>Edit</a>
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

  $(".deleteuser").click(function(){
    var key = $(this).data("key");
    if (confirm('Are you sure you want to delete this?')) {
      $.ajax({
        url: 'master/delete-distributor.php',
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
