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
               Manage Admission Process List
                  <a href="master/create-admission.php" class="btn btn-primary pull-right" >Create Admission Process</a>
              </h4>
            </div>
          </div>

          <div class="box">
            <div class="box-body table-responsive">
              <table id="datatable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Sr No.</th>
                  <th>Main Courses Name</th>
                  <th>Course Name</th>
                  <th>Name </th>
                  <th>Video Link</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  $i = 0;
                  $qry = "SELECT admission_process.*,m_main_courses.name AS main_courses_name,courses_details.name AS courses_details_name FROM admission_process 
                  left join m_main_courses on m_main_courses.id=admission_process.main_courses_id
                  left join courses_details on courses_details.id=admission_process.courses_details_id
                  order by id ASC";
                  $result = $conn->query($qry);
                  while($row = $result->fetch_array()){
                    $i++;
                     $status=$row['status'];
                    if($status==1){ $statuss="<span class=\"label label-success\">Active</span>"; } else { $statuss="<span class=\"label label-warning\">Deactive</span>"; } 
                ?>
                <tr>
                  <td><?=$i;?></td>
                  <td><?=$row['main_courses_name'];?></td>
                  <td><?=$row['courses_details_name'];?></td>
                  <td><?=$row['name'];?></td>
                  <td><?=$row['video_link'];?></td>
                  <td><?=$statuss;?></td>
                                
                  <td>
                    <a href="master/edit-admission.php?key=<?=base64_encode($row['id'])?>" class="btn btn-warning"><i class="fa fa-edit"></i> Edit</a>

                   <!--   <a button class="btn btn-danger btn-sm" onClick="window.open('master/delete-message-type.php?id=<?=$row['id'];?>',   'win1','width=950, height=800, menubar=no ,scrollbars=yes,top=50,left=100')"><i class="fa fa-trash"></i> Delete </button></a> -->


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
