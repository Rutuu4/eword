<?php
  include("../database.php");
    $current_date = date('d-m-Y');

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
              <h4 class="col-sm-10">
              Purchase Order
              </h4>
              <div class="col-sm-2">
               <a href="master/create-po.php" class="btn btn-primary" role="button">CREATE PO</a>
               </div>
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
                  <th>View Details</th>
                  <th>Edit</th>
                  <th>view Transction</th> 
                </tr>
                </thead>
                <tbody>
                <?php
           $qry="SELECT po.*,m_supplier.name from po left join m_supplier on po.supplier_id=m_supplier.id where po.status=(1 or 0)";
          if(!empty($fromdate) AND  !empty($todate)){  $qry .= " && po.created_date between '$fromdate' and  '$todate'"; }
           if(empty($fromdate) or  empty($todate)){ $qry .= " && po.created_date BETWEEN CURDATE()+1 - INTERVAL 30 DAY AND CURDATE()+1 "; }  
           $qry .= " order by po.created_date desc";
          $result=$conn->query($qry); 
                  $i = 1;
                  while($row = $result->fetch_array()){
                      if(!empty($row['created_date'])){ $date=date("d-m-Y", strtotime($row['created_date'])); } else{ $date='';}
                       if(!empty($row['po_date'])){ $po_date=date("d-m-Y", strtotime($row['po_date'])); } else{ $po_date='';}
            if($row['status'] == 1){ $status = "<span class='badge bg-green'>Pending</span>";}else{ $status = "<span class='badge bg-red'>Verified</span>"; }
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
                    <a href="master/view-po-order.php?key=<?=base64_encode($row['id'])?>" class="btn btn-warning"><i class="fa fa-eye"></i></a>
                  </td>
                   <?php if($designaton_id==2)
                  { 
                          if($date >= $current_date)
                          { ?>
                             <td>
                              <a href="master/edit-po.php?key=<?=base64_encode($row['id'])?>" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                             </td>
                    <?php } 
                          else
                          { ?>
                              <td></td>
                          <?php }
                  } 
                  else
                  { ?>
                             <td>
                              <a href="master/edit-po.php?key=<?=base64_encode($row['id'])?>" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                             </td>
            <?php } ?>
                   <td>
                    <a href="transaction-report.php?txn_type=<?php echo 3 ; ?>&&challan_id=<?php echo $row['id'];?>" class="btn btn-warning"><i class="fa fa-eye"></i></a>
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
