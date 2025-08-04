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
               Manage Chat Room Group
                  <a href="master/create-chat-room-group.php" class="btn btn-primary pull-right" >Create Chat Room Group</a>
              </h4>
            </div>
          </div>

          <div class="box">
            <div class="box-body table-responsive">
              <table id="datatable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Sr No.</th>
                  <th>Chat Course Name</th>
                  <th>Group Name </th>
                  <th>Group Member</th>
                  <th>Show Status</th>
                 <!--  <th>Status</th> -->
                  <!-- <th>Action</th> -->
                  <th>Group Details</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  $i = 0;
                  $qry = "SELECT 
    chat_room_group.*,
    chat_course.name AS chat_course_name,
    COUNT(registration.id) AS group_member_count,
    COUNT(CASE WHEN registration.is_app_admin = 0 THEN 1 END) AS non_admin_count
FROM 
    chat_room_group
LEFT JOIN 
    chat_course ON chat_course.id = chat_room_group.chat_course_id
LEFT JOIN 
    registration ON registration.chat_room_group_id = chat_room_group.id
GROUP BY 
    chat_room_group.id
ORDER BY 
    chat_course.id DESC;
";
                  $result = $conn->query($qry);
                  while($row = $result->fetch_array()){
                    $i++;
                     $status=$row['status'];
                    if($status==1){ $statuss="<span class=\"label label-success\">Active</span>"; } else { $statuss="<span class=\"label label-warning\">Deactive</span>"; } 

                     $is_hide=$row['is_hide'];
                    if($is_hide==1){ $is_hidee="<span class=\"label label-danger\">Hide</span>"; } else { $is_hidee="<span class=\"label label-primary\">Show</span>"; } 
                ?>
                <tr>
                  <td><?=$i;?></td>
                  <td><?=$row['chat_course_name'];?></td>
                  <td><?=$row['name'];?></td>
                  <th><?=$row['non_admin_count'];?></th>
                  <!-- <td><?=$statuss;?></td> -->
                   <td><?=$is_hidee;?></td>
                                
                 <!--  <td>
                    <a href="master/edit-chat-room-group.php?key=<?=base64_encode($row['id'])?>" class="btn btn-warning"><i class="fa fa-edit"></i> Edit</a>

                  </td> -->
                   <td>
                    <a href="master/view-chat-group-details.php?key=<?=base64_encode($row['id'])?>" class="btn btn-success"><i class="fa fa-eye"></i></a>
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
