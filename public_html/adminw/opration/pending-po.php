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
               Pending PO
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
                  <th>Create-Date</th>
                  <th>Challan No</th>
                  <th>PO No</th>
                  <th>PO Date</th>
                  <th>Supplier Name</th>
                  <th>Status</th>
                  <th>Verify Po</th>
                </tr>
                </thead>
                <tbody>
                <?php
           $qry="SELECT po.*,m_supplier.name from po left join m_supplier on po.supplier_id=m_supplier.id where po.status=1";
          if(!empty($fromdate) AND  !empty($todate)){  $qry .= " && po.created_date between '$fromdate' and  '$todate'"; } 
           $qry .= " order by po.created_date desc";
          $result=$conn->query($qry);
                  $i = 1;
                  while($row = $result->fetch_array()){
                      if(!empty($row['created_date'])){ $date=date("d-m-Y", strtotime($row['created_date'])); } else{ $date='';}
                       if(!empty($row['po_date'])){ $po_date=date("d-m-Y", strtotime($row['po_date'])); } else{ $po_date='';}
            if($row['status'] == 1){ $status = "<span class='badge bg-blue'>Pending</span>";}else{ $status = "<span class='badge bg-red'>Verify</span>"; }
                ?>
                <tr>
                  <td><?=$i++;?></td>
                  <td><?=$date;?></td>
                  <td><?=$row['id'];?></td>
                  <td><?=$row['po_number'];?></td>
                  <td><?=$po_date;?></td>
                  <td><?=$row['name'];?></td>
                  <td><?=$status?></td>
                  <td>
                    <a href="master/edit-po-pending.php?key=<?=base64_encode($row['id'])?>" class="btn btn-warning"><i class="fa fa-edit"></i></a>
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
