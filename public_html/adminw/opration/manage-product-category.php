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
                Find All Your Product Category Here
                <a href="master/add-product-category.php" class="btn btn-link pull-right" style="color: blue;">Want to  Add Product Category ?</a>
              </h4>
            </div>
          </div>

          <div class="box">
            <div class="box-body table-responsive">
              <table id="datatable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Product Category Name</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  $getcityqry = $conn->query("SELECT * FROM product_category ");
                  $i = 1;
                  while($rowcity = $getcityqry->fetch_array()){
                    //set status
                    if($rowcity['status'] == 1){
                      $status = "<span class='badge bg-green'>Active</span>";
                    }else{
                      $status = "<span class='badge bg-red'>Dective</span>";
                    }
                ?>
                <tr>
                  <td style="width: 10%"><?=$i++;?></td>
                  <td style="width: 40%"><?=$rowcity['name']?></td>
                  <td style="width: 20%"><?=$status?></td>
                  <td style="width: 30%">
                    <a href="master/edit-product-category.php?key=<?=base64_encode($rowcity[0])?>" class="btn btn-warning"><i class="fa fa-edit"></i> Edit</a>
                    <!-- <span class="btn btn-danger btn-sm deletecity" data-key="<?=$rowcity[0]?>"><i class="fa fa-trash"></i></span> -->
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
      $.ajax({
        url: 'master/delete-city.php',
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
