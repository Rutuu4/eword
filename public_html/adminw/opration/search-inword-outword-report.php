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
          <div class="box box-success">
            <!--
            <div class="box-header">
              <h3 class="box-title">Hover Data Table</h3>
            </div>
            -->
            <div class="box-body">
              <h4>
            Search Inword/Outword Report
                
              </h4>
              
               <?php 
                  $txn_type=  $_POST['txn_type'];
                  $challan_id= $_POST['challan_id'];
              ?>
              <form id="form4" name="form4" method="post" action="">
              <div class="form-group">
                 <div class="form-group">
                  <div class="col-sm-2">
                    <label>Select Transaction Type</label>
                    <div>
                      <select name="txn_type" class="form-control" required>
                        <option value="">Transaction Type</option>
                        <?php
                          $getu = $conn->query("SELECT * FROM m_txn_type WHERE status = 1");
                          while($rowu = $getu->fetch_array()){
                        ?>
                        <option <?php if($txn_type == $rowu['id'])echo "selected"; ?> value="<?=$rowu['id']?>"><?=$rowu['name']?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-2">
                    <label>Challan No</label>
                    <div>
                      <input type="text" class="form-control" value="<?php echo $challan_id ; ?>" name="challan_id" required>
                    </div>
                  </div>
                </div>

               <div class="col-lg-3" style="padding-top:24px">
                    <button style="display: inline-block;" name="search_process" class="btn btn-primary pull-left"><i class="fa fa-search"></i>Search</button>
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
                  <th>Transaction Type</th>
                  <th>Transaction Date</th>
                  <th>User Name</th>
                  <th>View Details</th>
                </tr>
                </thead>  
                <tbody>
                <?php 
                
                if($_POST['txn_type'] && $_POST['challan_id'])
                {
                   $txn_type=  $_POST['txn_type'];
                   $challan_id= $_POST['challan_id'];

                }
                else
                {
                   if($_GET['txn_type']>0 && $_GET['challan_id']>0)
                  {

                     $txn_type=$_GET['txn_type'];
                     $challan_id=$_GET['challan_id']; 
                  }
                }
               
          if($txn_type>0 && $challan_id>0){       
          
                 $qry="SELECT txn_history_report.*,user.name AS user_name,m_txn_type.name AS txn_type_name FROM txn_history_report  
                  left join user on txn_history_report.uid=user.id
                  left join m_txn_type on txn_history_report.txn_type=m_txn_type.id
                  WHERE txn_history_report.txn_type='$txn_type' and txn_id='$challan_id' ";
                  $result=$conn->query($qry);
                  $i = 1;
                  while($row = $result->fetch_array()){
                  $date=date("d-m-Y", strtotime($row['txn_date']));
                ?>
                <tr>
                  <td><?=$i++;?></td>
                  <td><?php echo $row['txn_id']; ?></td>
                  <td><?php echo $row['txn_type_name']; ?></td>
                  <td><?php echo $date ?></td>
                  <td><?php echo $row['user_name']; ?></td>
                <?php if($txn_type==1) 
                      { ?>

                  <td>
                    <a href="master/view-inword-order.php?key=<?=base64_encode($row['txn_id'])?>" class="btn btn-warning"><i class="fa fa-eye"></i></a>
                  </td>
                 <?php }
                      if($txn_type==2) 
                      { ?>
                  <td>
                    <a href="master/view-order.php?key=<?=base64_encode($row['txn_id'])?>" class="btn btn-warning"><i class="fa fa-eye"></i></a>
                  </td>
                     <?php }
                        if($txn_type==3)
                        { ?>
                          <td>
                    <a href="master/view-po-order.php?key=<?=base64_encode($row['txn_id'])?>" class="btn btn-warning"><i class="fa fa-eye"></i></a>
                  </td>
              <?php } ?>



                </tr>
                <?php } } ?>
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
