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
              Active Subscription List (Last 30 Days)
              </h4>
              <?php

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
                  <th>Date</th>
                  <th>Expire Date</th>
                  <th>Package Name</th>
                  <th>Package Course</th>
                  <th>User Name</th>
                  <th>User Mobile</th>
                  <th>Amount</th>
                  <th>Transaction ID</th>
                  <th>Status</th>
                  <th>View Details</th>
                </tr>
                </thead> 
                <tbody>
                <?php
                $date=date('Y-m-d');
            $qry="SELECT registration.id,package_subscription_order.subscription_expire_date,package_subscription_order.amount,package_subscription_order.txnid,package_subscription_order.date,registration.fullname,registration.username AS user_mobile_number,subscription_package.name AS subscription_package_name,chat_course.name AS chat_course_name  FROM package_subscription_order 
            LEFT JOIN registration ON package_subscription_order.user_id = registration.id
            LEFT JOIN  subscription_package ON package_subscription_order.subscription_package_id = subscription_package.id
            LEFT JOIN chat_course ON chat_course.id = registration.chat_course_id
            where package_subscription_order.payment_gateway_success_status=1 and package_subscription_order.subscription_expire_date>'$date'";
          if(!empty($fromdate) AND  !empty($todate)){  $qry .= " && package_subscription_order.date between '$fromdate' and  '$todate'"; }
           if(empty($fromdate) or  empty($todate)){ $qry .= " && package_subscription_order.date >= DATE_SUB(CURDATE(),INTERVAL 30 DAY)"; }  
           $qry .= " order by package_subscription_order.date desc";
           $result=$conn->query($qry);
                  $i = 1;
                  while($row = $result->fetch_array()){
                      $status = "<span class='badge bg-green'>Success</span>";
                      if(!empty($row['date'])){ $date=date("d-m-Y", strtotime($row['date'])); }
                      else{ $date='';}
                ?>
                <tr>
                  <td><?=$i++;?></td>
                  <td><?php echo $date; ?></td>
                  <td><?=date("d-m-Y", strtotime($row['subscription_expire_date']));?></td>
                  <td><?=$row['subscription_package_name'];?></td>
                   <td><?=$row['chat_course_name'];?></td>
                  <td><?=$row['fullname'];?></td>
                  <td><?=$row['user_mobile_number'];?></td>
                  <td><?=$row['amount'];?></td>
                  <td><?=$row['txnid'];?></td>
                  <td><?=$status;?></td>
                  <td><a href="master/edit-register-user.php?id=<?php echo $row['id'];?>" class="btn btn-warning"><i class="fa fa-eye"></i></a></td>
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
