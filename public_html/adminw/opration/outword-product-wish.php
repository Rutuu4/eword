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
            <!--
            <div class="box-header">
              <h3 class="box-title">Hover Data Table</h3>
            </div>
            --> 
             
            <div class="box-body">
              <h4>
               Product-Wise Outword List (Last 30 Days)
              </h4>
              <?php

                  $f_date=$_POST['fromdate'];
                  $to_date=$_POST['todate'];
                  $product_id=$_POST['product_id'];

                    if(!empty($f_date) AND !empty($to_date))
                    {
                      $fromdate=date('Y-m-d', strtotime($f_date));
                      $todate=date('Y-m-d 23:59:59' , strtotime($to_date));
                    }
              ?>
              <form id="form4" name="form4" method="post" action="">
              <div class="form-group">
              
                <div class="form-group">
                  <div class="col-sm-2">
                    <label>Select Product</label>
                    <div>
                      <select name="product_id" class="form-control" required>
                        <option value="">Select Product</option>
                        <?php
                          $getu = $conn->query("SELECT * FROM product WHERE status = 1");
                          while($rowu = $getu->fetch_array()){
                        ?>
                        <option <?php if($product_id == $rowu['id'])echo "selected"; ?> value="<?=$rowu['id']?>"><?=$rowu['product_name']?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="col-sm-2 col-md-2 col-xs-12">
                <label>From Date</label>
                <div class="input-group date ">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                <input type="text" class="form-control pull-right datepicker" id="fromstatuss" name="fromdate" placeholder="From Date" value="<?=$f_date;?>">
                </div>
                </div>
                <div class="col-sm-2 col-md-2 col-xs-12">
                <label>To Date</label>
                <div class="input-group date ">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                   <input type="text" class="form-control pull-right datepicker" id="tostatuss" name="todate" placeholder="To Date" value="<?=$to_date;?>" >
                </div>
                </div>
               <div class="col-lg-2" style="padding-top:24px">
                    <button name="search_process" class="btn btn-primary pull-left"><i class="fa fa-search"></i>Search</button>
                    <a style="margin-left: 10px" href="outword-product-wish-csv.php?fromdate=<?php echo $fromdate; ?>&&todate=<?php echo $todate; ?>&&product_id=<?php echo $product_id; ?>" class="btn btn-primary" role="button">Export CSV</a>
                  </div>
                <!-- /.input group -->
               </div>
              </form>
            </div>
          </div>


          <div class="box">
            <div class="box-body table-responsive">
                           <table id="datatable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Challan No</th>
                  <th>Create Date</th>
                  <th>Create Outword</th>
                  <th>Customer Name</th>
                  <th>Category Name</th>
                  <th>Product Name</th>
                  <th>Outword Quantity</th>
                  <th>View Details</th>
                </tr>
                </thead>
                <tbody>
                <?php
       if(!empty($product_id))
       {
        $qry="SELECT product_order_trans.*,user.name AS user_name,m_customer.name AS customer_name,product.product_name,product_category.name AS category_name from product_order_trans
        left join product_order on product_order_trans.order_id=product_order.id
        left join user on product_order.user_id=user.id
        left join m_customer on product_order.customer_id=m_customer.id
        left join product on product_order_trans.product_id=product.id
        left join product_category on product.product_category_id=product_category.id
        where product_id='$product_id'";
          if(!empty($fromdate) AND  !empty($todate)){  $qry .= " && product_order_trans.created_date between '$fromdate' and  '$todate'"; }
           if(empty($fromdate) or  empty($todate)){ $qry .= " && product_order_trans.created_date BETWEEN CURDATE()+1 - INTERVAL 30 DAY AND CURDATE()+1 "; }  
          $result=$conn->query($qry);
                  $i = 1;
          while($row = $result->fetch_array()){
          if(!empty($row['created_date'])){ $date=date("d-m-Y", strtotime($row['created_date'])); } else{ $date='';}
                ?>
                <tr>
                  <td><?=$i++;?></td>
                  <td><?=$row['order_id'];?></td>
                  <td><?=$date;?></td>
                  <td><?=$row['user_name'];?></td>
                  <td><?=$row['customer_name'];?></td>
                  <td><?=$row['category_name'];?></td>
                  <td><?=$row['product_name'];?></td>
                  <td><?=$row['quantity'];?></td>
                  <td>
                    <a href="master/view-order.php?key=<?=base64_encode($row['order_id'])?>" class="btn btn-warning"><i class="fa fa-eye"></i></a>
                  </td>
                </tr>
                <?php }  } ?>
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
  $(function () {
     $('#datatable').DataTable()
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,
     format: 'dd-mm-yyyy',
     todayHighlight: true 
    });
    
  });
</script>
</body>
</html>
