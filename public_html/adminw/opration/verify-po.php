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
               Verify PO List (Last 30 Days)
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
                  <th>verify-Date</th>
                  <th>Challan No</th>
                  <th>PO Number</th>  
                  <th>Invoice No</th>
                  <th>Status</th>
                  <th>View Po-Details</th>
                  <th>GRN Details</th>
                </tr>
                </thead>
                <tbody>
                <?php
           $qry="SELECT verify_po.*,po.po_number from verify_po 
           left join po on verify_po.po_id=po.id";
           if(!empty($fromdate) AND  !empty($todate)){  $qry .= " where verify_po.created_date between '$fromdate' and  '$todate'"; }
           if(empty($fromdate) or  empty($todate)){ $qry .= " where verify_po.created_date BETWEEN CURDATE()+1 - INTERVAL 30 DAY AND CURDATE()+1 "; } 
          $qry .= " group by verify_po.po_id desc";
          $result=$conn->query($qry);
                  $i = 1;
                  while($row = $result->fetch_array()){
                    $po_id=$row['po_id'];
                      if(!empty($row['created_date'])){ $date=date("d-m-Y", strtotime($row['created_date'])); } else{ $date='';}

                        $status = "<span class='badge bg-green'>verify</span>";
                ?>
                <tr>
                  <td><?=$i++;?></td>
                  <td><?=$date;?></td>
                  <td><?=$row['chalan_no'];?></td>
                  <td><?=$row['po_number'];?></td>
                  <td><?=$row['invoice_no'];?></td>
                  <td><?=$status?></td>
                  <td>
                    <a href="master/view-verify-po-details.php?key=<?=base64_encode($row['po_id'])?>" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                  </td>
                  <td> 
                    <?php
                     $qry1="SELECT * from verify_po where po_id='$po_id' group by grn_no";
                     $result1=$conn->query($qry1);
                     while($row1=$result1->fetch_array())
                     { ?>
                       <a href="master/view-verify-grn-wise-details.php?key=<?=base64_encode($row1['grn_no'])?>" class="btn btn-warning"><i class="fa fa-edit"></i></a>  
                     <?php } ?>
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
     $('#datatable').DataTable()
  });
</script>
</body>
</html>
