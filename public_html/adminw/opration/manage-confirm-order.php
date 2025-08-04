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
               Confirm Outword List (Last 30 Days)
              </h4>
              <?php

                   $state_id=$_POST['state_id'];
                  $f_date=$_POST['fromdate'];
                  $to_date=$_POST['todate'];

                    if(!empty($f_date) AND !empty($to_date))
                    {
                      $fromdate=date('Y-m-d', strtotime($f_date));
                      $todate=date('Y-m-d 23:59:59' , strtotime($to_date));
                    }
              ?>
              <form id="form4" name="form4" method="post" action="">
              <div class="form-group">
              
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
                    <a style="margin-left: 10px" href="order-csv-report.php?status=<?php echo '2'; ?>&&fromdate=<?php echo $fromdate; ?>&&todate=<?php echo $todate; ?>" class="btn btn-primary" role="button">Export CSV</a>
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
                  <th>Outword Id</th>
                  <th>Outword Date</th>
                  <th>Create Outword</th>
                  <th>customer Outword</th>
                  <th>customer Phone</th>
                  <th>Customer Email</th>
                  <th>Status</th>
                  <th>Edit</th>
                  <th>View</th>
                </tr>
                </thead>
                <tbody>
                <?php
          $qry="SELECT product_order.id,product_order.created_date,product_order.grand_total_amount,user.name as create_name,m_customer.name AS customer_name,m_customer.mobile AS customer_phone,m_customer.email AS customer_email  FROM product_order 
            LEFT JOIN user ON product_order.user_id = user.id
            LEFT JOIN  m_customer ON product_order.customer_id = m_customer.id
            where product_order.status=2";
          if(!empty($fromdate) AND  !empty($todate)){  $qry .= " && product_order.created_date between '$fromdate' and  '$todate'"; }
           if(empty($fromdate) or  empty($todate)){ $qry .= " && product_order.created_date BETWEEN CURDATE()+1 - INTERVAL 30 DAY AND CURDATE()+1 "; }  
           $qry .= " order by product_order.created_date desc";
          $result=$conn->query($qry);
                  $i = 1;
                  while($row = $result->fetch_array()){
                      $status = "<span class='badge bg-green'>Confirm</span>";
                      if(!empty($row['created_date'])){ $date=date("d-m-Y", strtotime($row['created_date'])); }
                      else{ $date='';}
                ?>
                <tr>
                  <td><?=$i++;?></td>
                  <td><?=$row['id'];?></td>
                  <td><?php echo $date ?></td>
                  <td><?=$row['create_name'];?></td>
                  <td><?=$row['customer_name'];?></td>
                  <td><?=$row['customer_phone'];?></td>
                  <td><?=$row['customer_email'];?></td>
                  <td><?=$status?></td>
                  <td>
                    <a href="master/edit-order.php?key=<?=base64_encode($row['id'])?>" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                  </td>
                  <td>
                    <a href="master/view-order.php?key=<?=base64_encode($row['id'])?>" class="btn btn-warning"><i class="fa fa-eye"></i></a>
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
  $(function () {
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
