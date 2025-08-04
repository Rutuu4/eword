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
               Verify GRN List (Last 30 Days)
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
                  <th>Status</th>
                  <th>View Detials</th>
                </tr> 
                </thead>
                <tbody>
                <?php
          $qry="SELECT verify_po.*,user.name AS user_name,po.po_number from verify_po 
           left join user on verify_po.u_id=user.id
           left join po on verify_po.po_id=po.id ";
            if(!empty($fromdate) AND  !empty($todate)){  $qry .= " where verify_po.created_date between '$fromdate' and  '$todate'"; }
           if(empty($fromdate) or  empty($todate)){ $qry .= " where verify_po.created_date BETWEEN CURDATE()+1 - INTERVAL 30 DAY AND CURDATE()+1 "; } 
           $qry .= " group by verify_po.grn_no";
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
                  <td><?=$status?></td>
                   <td>
                    <a href="master/view-verify-grn-wise-details.php?key=<?=base64_encode($row['grn_no'])?>" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                  </td> 
                </tr>
                <?php  } ?>
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
   $('.datepicker').datepicker({
      autoclose: true,
     format: 'dd-mm-yyyy',
     todayHighlight: true 
    });

});
</script>
</body>
</html>
