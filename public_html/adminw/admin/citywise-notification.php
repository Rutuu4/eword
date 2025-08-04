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
              Manage Citywise Notification List (Last 100 Days)
               <a href="master/create-citywise-notification.php" class="btn btn-primary pull-right" >Create Notification</a>
              </h4>
              <?php

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
                                                <th>Sr No</th>
                                                <th>Date</th>
                                                <th>Title</th>
<!--                                                 <th>message</th> -->
                                                <th>Image</th>
                                              <th>Website Link </th>

                                               <!--  <th>Status</th> -->
                                               <th>View Details</th>
                                                <th>Detele</th>
                                                
                                                
                                               
                                            </tr>
                </thead>
                <tbody>
                <?php
                  $qry="SELECT mobile_notification.*,m_main_courses.name AS main_course_name, GROUP_CONCAT(m_city.name ORDER BY m_city.name) AS city_name_list FROM mobile_notification 
                  left join m_main_courses on m_main_courses.id=mobile_notification.main_course_id
                  LEFT JOIN m_city  ON  FIND_IN_SET(m_city.id, mobile_notification.city_ids)
                  WHERE mobile_notification.id>0 and mobile_notification.notification_type=2";
                  if(!empty($fromdate) AND  !empty($todate)){  $qry .= " && mobile_notification.datee between '$fromdate' and  '$todate'"; }
                  if(empty($fromdate) or  empty($todate)){ $qry .= " && mobile_notification.datee >= DATE_SUB(CURDATE(),INTERVAL 100 DAY)"; }  
                  $qry .= " group by mobile_notification.id order by mobile_notification.id desc";
                  $result=$conn->query($qry);
                  $i = 1;
                  while($row = $result->fetch_array()){
                     $date= date("d-m-Y", strtotime($row['datee']));
                       $status=$row['status'];
                    if($status==1){ $statuss="<span class=\"label label-success\">Active</span>"; } else { $statuss="<span class=\"label label-warning\">Deactive</span>"; } 
                  
                ?>
               <tr>
                                                <td><?=$i++;?></td>
                                                <td><?=$date;?></td>
                                                <td><?=$row['title'];?></td>
                                                <td><?php if(!empty($row['img'])){ ?> <img src="../../<?=$row['img']?>" style="height: 70px"> <?php } ?></td>
                                                <!-- <td><?=$row['mesage'];?></td>  -->
                                                <!-- <td><?=$row['city_name_list'];?></td> -->
                                                <td><?=$row['website_link'];?></td>
                                               

                                                 <!-- <td><?=$statuss;?></td> -->
                                                 <td><a href="master/edit-citywise-notification.php?key=<?=base64_encode($row['id'])?>" class="btn btn-warning"><i class="fa fa-eye"></i> </a> </td>
                                
                  <td>
                    

                     <a button class="btn btn-danger btn-sm" onClick="window.open('master/delete-notification.php?id=<?=$row['id'];?>',   'win1','width=950, height=800, menubar=no ,scrollbars=yes,top=50,left=100')"><i class="fa fa-trash"></i> Delete </button></a>
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
  $('#datatable').DataTable({
    "pageLength": 25  // Set default number of rows per page
  });


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
