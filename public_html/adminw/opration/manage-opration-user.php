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
                Opration Users List
                <a href="master/add-opration-user.php" class="btn btn-link pull-right" style="color: blue;">Want to Add User ?</a>
              </h4>
            </div>
          </div>

          <div class="box">
            <div class="box-body table-responsive">
              <table id="datatable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Username</th>
                  <th>Full-Name</th>
                  <th>Mobile</th>
                  <th>Email</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  $getuserqry = $conn->query("SELECT * FROM `user` WHERE designaton_id=2");
                  $i = 1;
                  while($rowuser = $getuserqry->fetch_array()){
                    //set status
                    if($rowuser['status'] == 1){
                      $status = "<span class='badge bg-green'>Active</span>";
                    }else{
                      $status = "<span class='badge bg-red'>Dective</span>";
                    }
                ?>
                <tr>
                  <td><?=$i++;?></td>
                  <td><?=$rowuser['name']?></td>
                  <td><?=$rowuser['mobile']?></td>
                  <td><?=$rowuser['email']?></td>
                  <td><?=$rowuser['name']?></td>
                  <td><?=$status?></td>
                  <td>
                    <a href="master/edit-opration-user.php?key=<?=base64_encode($rowuser[0])?>" class="btn btn-warning"><i class="fa fa-edit"></i> Edit</a>
                    
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

  $(".deletecity").click(function(){
    var key = $(this).data("key");
    if (confirm('Are you sure you want to delete this?')) {
      alert('You Cant Delete Admin User');
    }
  });
});
</script>
</body>
</html>
