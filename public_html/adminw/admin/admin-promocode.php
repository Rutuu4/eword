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
             Manage Promocode
             <a href="master/add-promocode.php" class="btn btn-primary pull-right" >Create Promcode</a>
           </h4>
         </div>
       </div>

       <div class="box">
        <div class="box-body table-responsive">
          <table id="datatable" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Sr No.</th>
                <th>Code</th>
                <th>Amount Off</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Total Quantity</th>
                <th>Available Quantity</th>
                <th>Sold Quantity</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $i = 0;
              $qry = "SELECT * from promocode order by id desc";
              $result = $conn->query($qry);
              while($row = $result->fetch_array()){
                $i++;

                $availableQuantity = $row['total_quantity'] - $row['sold_quantity'];
                $status=$row['status'];
                if($status==1){ $statuss="<span class=\"label label-success\">Active</span>"; } else { $statuss="<span class=\"label label-warning\">Deactive</span>"; }

                ?>
                <tr>
                  <td ><?=$i;?></td>
                  <td ><?=$row['code']?></td>
                  <td ><?=$row['amount_off'];?></td>
                  <td ><?=date("d-m-Y", strtotime($row['start_date']));?></td>
                  <td ><?=date("d-m-Y", strtotime($row['end_date']));?></td>
                  <td ><?=$row['total_quantity'];?></td>
                  <td ><?=$availableQuantity?></td>
                  <td ><?=$row['sold_quantity'];?></td>
                  <td ><?=$statuss;?></td>
                  <td>
                    <a href="master/edit-promocode.php?key=<?=base64_encode($row['0'])?>" class="btn btn-warning"><i class="fa fa-edit"></i> Edit</a>
                    <a button class="btn btn-danger btn-sm" onClick="window.open('master/delete-promocode.php?id=<?=$row['id'];?>',   'win1','width=950, height=800, menubar=no ,scrollbars=yes,top=50,left=100')"><i class="fa fa-trash"></i> Delete </button></a>
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
    $('#datatable').DataTable({
      "pageLength": 25  // Set default number of rows per page
    });
  });
</script>
</body>
</html>
