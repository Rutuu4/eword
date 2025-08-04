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
               Manage Refer Report
                 <!--  <a href="master/create-register-user.php" class="btn btn-primary pull-right" >Create Register User</a> -->
              </h4>
            </div>
          </div>

          <div class="box">
            <div class="box-body table-responsive">
              <table id="datatable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Sr No.</th>
                  <th>Name</th>
                  <th>Mobile</th>
                  <th>Refer Count</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  $i = 0;
                  $qry = "SELECT refer_user_id, COUNT(registration.id) AS refer_count FROM registration where registration.refer_user_id>0 group by registration.refer_user_id";
                  $result = $conn->query($qry);
                  while($row = $result->fetch_array()){
                    $registration_id=$row['refer_user_id'];

                    $qry_inner="SELECT id,fullname,username from registration where id='$registration_id'";
                    $result_inner = $conn->query($qry_inner);
                    $row_inner = $result_inner->fetch_array();


                    $i++;
                  $status=$row['status'];
                  if($status==1){ $statuss="<span class=\"label label-success\">Active</span>"; } else { $statuss="<span class=\"label label-warning\">Deactive</span>"; }
                      
                ?>
                <tr>
                  <td ><?=$i;?></td>
                   <td ><?=$row_inner['fullname'];?></td>
                  <td ><?=$row_inner['username'];?></td>
                  <td ><?=$row['refer_count'];?></td>
                                
                  <td >
                    <a href="master/view-refer-details.php?id=<?=$registration_id;?>" class="btn btn-warning"><i class="fa fa-eye"></i></a>
                    
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

  $(".deletestate").click(function(){
    var key = $(this).data("key");
    if (confirm('Are you sure you want to delete this?')) {
      $.ajax({
        url: 'master/delete-state.php',
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
