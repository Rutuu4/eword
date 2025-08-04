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
               Manage Admin User
                  <a href="master/add-admin-user.php" class="btn btn-primary pull-right" >Create Admin User</a>
              </h4>
            </div>
          </div>

          <div class="box">
            <div class="box-body table-responsive">
              <table id="datatable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Sr No.</th>
                  <th>Date</th>
                  <th>Username</th>
                  <th>Fullname</th>
                  <th>Mobile</th>
                  <th>Email</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  $i = 0;
                  $qry = "SELECT * from user order by id desc";
                  $result = $conn->query($qry);
                  while($row = $result->fetch_array()){
                    $i++;

                  $status=$row['status'];
                  if($status==1){ $statuss="<span class=\"label label-success\">Active</span>"; } else { $statuss="<span class=\"label label-warning\">Deactive</span>"; }
                      
                ?>
                <tr>
                  <td ><?=$i;?></td>
                  <td ><?=date("d-m-Y", strtotime($row['create_date']));?></td>
                  <td ><?=$row['username'];?></td>
                  <td ><?=$row['name'];?></td>
                  <td ><?=$row['mobile'];?></td>
                  <td ><?=$row['email'];?></td>
                  <td ><?=$statuss;?></td>
                                
                  <td >
                    <a href="master/edit-admin-user.php?id=<?php echo $row['id'];?>" class="btn btn-warning"><i class="fa fa-edit"></i> Edit</a>
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
