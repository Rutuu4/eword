<?php 
include("../../database.php"); 


$msg='';

if($_POST['h1']==1)
{  


    if($_POST['payment_gateway_success_status']==1)
    {
        $id=$_POST['id'];
        $date=date('Y-m-d');
        $datee=date('Y-m-d H:i:s'); 

        $payment_gateway_success_status=mysqli_real_escape_string($conn,$_POST['payment_gateway_success_status']);
        $txnid_temp=mysqli_real_escape_string($conn,$_POST['txnid']);


        if(!empty($txnid_temp))
        {
            $heading='manually';
            $txnid=$txnid_temp.'-'.$heading;
            $subscription_expire_date= date('Y-m-d', strtotime('+365 days'));



            $qry_update="UPDATE package_subscription_order SET payment_gateway_success_status='$payment_gateway_success_status',txnid='$txnid',subscription_expire_date='$subscription_expire_date' 
            WHERE id='$id'";
            $sq1update=$conn->query($qry_update);

            $qry_chk="SELECT registration.id AS register_id,registration.chat_course_id FROM package_subscription_order 
            left join registration ON registration.id=package_subscription_order.user_id
            where package_subscription_order.id='$id'";
            $result_chk=$conn->query($qry_chk);
            $row_chk=$result_chk->fetch_array();
            $chat_course_id = $row_chk['chat_course_id']; 
            $register_id=$row_chk['register_id']; 

            $qry_grp_chk = "
                SELECT 
                    chat_room_group.id, 
                    COUNT(DISTINCT chat_room_txn.id) AS chat_room_group_joining_msg_count
                FROM chat_room_group
                LEFT JOIN registration 
                    ON registration.chat_room_group_id = chat_room_group.id 
                    AND registration.is_app_admin = 0
                LEFT JOIN chat_room_txn 
                    ON chat_room_txn.chat_room_group_id = chat_room_group.id
                WHERE chat_room_group.chat_course_id = '$chat_course_id'
                GROUP BY chat_room_group.id
                HAVING COUNT(registration.id) < 500";
            $resukt_grp_chk=$conn->query($qry_grp_chk);
            $rowcount_grp_chk=mysqli_num_rows($resukt_grp_chk);

            if($rowcount_grp_chk>0)
            {
                         $row_grp_chk=$resukt_grp_chk->fetch_array();

                         $chat_room_group_id = $row_grp_chk['id'];
                         $chat_room_group_joining_msg_count = $row_grp_chk['chat_room_group_joining_msg_count'];
                         $chat_room_group_joining_datetime=date("Y-m-d H:i:s");


                         $qry_update="UPDATE `registration` SET chat_room_group_id='$chat_room_group_id',chat_room_group_joining_msg_count='$chat_room_group_joining_msg_count',chat_room_group_joining_datetime='$chat_room_group_joining_datetime' where id='$register_id' ";
                         $sq1_update=$conn->query($qry_update);

            }
            else
            {

                $qry_chat_grp=" SELECT chat_room_group.id,chat_course.name AS chat_course_name FROM chat_room_group
                LEFT JOIN chat_course ON chat_course.id = chat_room_group.chat_course_id
                WHERE chat_room_group.chat_course_id = '$chat_course_id'";
                $result_chat_grp=$conn->query($qry_chat_grp);
                $row_chat_grp=$result_chat_grp->fetch_array();
                $chat_course_name = $row_chat_grp['chat_course_name'];


                $chat_course_id             = $chat_course_id;
                $name             = $chat_course_name.' Group-'.$group_count+1;
                $status=1;
                $member_limit=500;

                $qry_insrt="INSERT INTO chat_room_group(chat_course_id, name, status, member_limit) VALUES ('$chat_course_id','$name','$status','$member_limit')";
                $sq1_insrt=$conn->query($qry_insrt);
                $chat_room_group_id = mysqli_insert_id($conn);

                 $chat_room_group_joining_datetime=date("Y-m-d H:i:s");

                $qry_update="UPDATE `registration` SET chat_room_group_id='$chat_room_group_id',chat_room_group_joining_datetime='$chat_room_group_joining_datetime' where id='$register_id' ";
                $sq1_update=$conn->query($qry_update);

            }

             header("location:../payment-fail-subscription.php"); 

        }
        else
        {
            $msg="Transaction Not Empty";
        }

    }
    else
    {
         $msg="Payment Gatway Status Success Compulsory";
    }



} 

?>
<!DOCTYPE html>
<html>
<head>
<base href="<?=$base_path?>">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?=$softtitle?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <?php include("../includes/css-scripts.php"); ?>
<style>
    .error{
      color: red;
    }
    .control-label{
      text-align: left!important;
    }
    .form-control{
      display: block;
      width: 100%;
      height: 34px;
      padding: 6px 12px;
      font-size: 14px;
      line-height: 1.42857143;
      background-color: #fff;
      background-image: none;
      border: 1px solid #ccc;
    }
    .select2-container { width: 100% !important; }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #3c8dbc;
    border-color: #367fa9;
    padding: 1px 10px;
    color: #ffffff;
}
    .select2-container--default .select2-search--inline .select2-search__field { width:100% !important;}
.select2-container--default.select2-container--open { width:100% !important;}
.select2-container { width:100% !important;}
sup{  color:#CC3300; font-size:14px; top: -4px; }
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: #ffffff;
    cursor: pointer;
    display: inline-block;
    font-weight: bold;
    margin-right: 2px;
}
  </style>
</head>
<body class="<?=$bodyclass?>">

 <?php

   if(!empty($_GET['key'])){

        $id=base64_decode($_GET['key']);
     
      $qry="SELECT package_subscription_order.id,package_subscription_order.subscription_expire_date,package_subscription_order.amount,package_subscription_order.txnid,package_subscription_order.date,registration.*,subscription_package.name AS subscription_package_name,chat_course.name AS chat_course_name,package_subscription_order.payment_gateway_success_status  FROM package_subscription_order 
            LEFT JOIN registration ON package_subscription_order.user_id = registration.id
            LEFT JOIN  subscription_package ON package_subscription_order.subscription_package_id = subscription_package.id
            LEFT JOIN chat_course ON chat_course.id = registration.chat_course_id
            where package_subscription_order.payment_gateway_success_status=0 and package_subscription_order.id='$id'";  

      $result = $conn->query($qry);
      $row = $result->fetch_array();


      $interesting_main_course_id=$row['interesting_main_course_id'];
      $course_details_ids=$row['course_details_ids'];
      $interesting_city_ids=$row['interesting_city_ids'];
      $is_chat_block=$row['is_chat_block'];
      $interesting_exrta_course_id=$row['interesting_exrta_course_id'];

      $payment_gateway_success_status=$row['payment_gateway_success_status'];
      
    }
  ?>  


<div class="wrapper">
  <?php include("../includes/header.php"); ?>
  <?php include("../includes/sidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Update Register User</h1>
    </section>

    <section class="content">

      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Add Data Here</h3>
        </div>

        <div class="box-body">
          <form action="" method="POST" id="" class="form-horizontal" enctype="multipart/form-data">

             <div class="col-sm-12" style="color:#FF3333;font-weight: bold;">
                 <?php if(!empty($msg)){ echo $msg; } ?>
            </div>

            <input name="h1" type="hidden" id="h1" value="1" />
            <input name="id" type="hidden" id="id" value="<?=$id;?>" />


            <div class="form-group">
               <label class="control-label col-sm-2">Username:</label>
                  <div class="col-sm-8">
                      <input type="text" class="form-control" name="username"  id="username"  value="<?=$row['username'];?>"  placeholder="Enter Username as Email"  disabled>
                  </div>
           </div>



           <div class="form-group">
               <label class="control-label col-sm-2">Name:</label>
                  <div class="col-sm-8">
                      <input type="text" class="form-control" name="fullname"  value="<?=$row['fullname'];?>"  id="first_name" placeholder="Enter Fullname"  disabled>
                  </div>
           </div>



          <!--  <div class="form-group">
               <label class="control-label col-sm-2">Email:</label>
                  <div class="col-sm-8">
                      <input type="text" class="form-control" name="email"   value="<?=$row['email'];?>"   id="email" placeholder="Enter Email" >
                  </div>
           </div> -->
            <div class="form-group">
               <label class="control-label col-sm-2">Residence City/District:</label>
                  <div class="col-sm-8">
                      <input type="text" class="form-control" name="city"   value="<?=$row['city'];?>"   id="city" placeholder="Enter Residence City/District" disabled>
                  </div>
           </div>

        <!--    
            <div class="form-group">
               <label class="control-label col-sm-2">Current Study:</label>
                  <div class="col-sm-8">
                      <input type="text" class="form-control" name="current_study"   value="<?=$row['current_study'];?>"   id="city" placeholder="Enter Current Study" >
                  </div>
           </div>
 -->           <!-- <div class="form-group">
               <label class="control-label col-sm-2">College/University Name:</label>
                  <div class="col-sm-8">
                      <input type="text" class="form-control" name="college_university_name"   value="<?=$row['college_university_name'];?>"   id="city" placeholder="Enter College/University Name" >
                  </div>
           </div> -->

         <!--   <div class="form-group">
                      <label for="usernamee" class="col-sm-2">Study City/District :</label>
                      <div class="col-sm-8">

                           <select name="study_city_id"  id="study_city_id"  class="form-control" >
                            <option value=""> Select Study City/District </option>
                            <?php 
                            $sqlb="SELECT id,name FROM m_city where status=1 order by name ASC";
                            $resultb = $conn->query($sqlb);
                            while($rowb = $resultb->fetch_array())
                            {
                              ?>
                              <option <?php if($row['study_city_id']==$rowb['id']){ echo "selected"; } ?> value="<?=$rowb['id'];?>"> <?=$rowb['name'];?> </option>
                              <?php } ?>
                                  </select>
                      </div>
                    </div>
 -->

              <div class="form-group">
                      <label for="usernamee" class="col-sm-2">Interesting Main Course :</label>
                      <div class="col-sm-8">

                           <select name="interesting_main_course_id"  id="main_courses_id"  class="form-control" disabled>
                            <option value=""> Select Main Course </option>
                            <?php 
                            $sqlb="SELECT id,name FROM m_main_courses where status=1";
                            $resultb = $conn->query($sqlb);
                            while($rowb = $resultb->fetch_array())
                            {
                              ?>
                              <option <?php if($row['interesting_main_course_id']==$rowb['id']) { echo "selected"; } ?> value="<?=$rowb['id'];?>"> <?=$rowb['name'];?> </option>
                              <?php } ?>
                                  </select>
                      </div>
                    </div>
                                    <div id="showextra">

<?php
if($row['interesting_exrta_course_id']>0)
{ ?>


   <div class="form-group">
                      <label for="usernamee" class="col-sm-2">Sub Course :</label>
                      <div class="col-sm-8">

                           <select name="interesting_exrta_course_id"  id="extra_course_id"  class="form-control"  disabled>
                            <option value=""> Select Exrta Course </option>
                            <?php 
                            $sqlb="SELECT id,name FROM m_exrta_course where status=1 and main_courses_id='$interesting_main_course_id'";
                            $resultb = $conn->query($sqlb);
                            while($rowb = $resultb->fetch_array())
                            {
                              ?>
                              <option <?php if($row['interesting_exrta_course_id']==$rowb['id']){ echo "selected"; } ?> value="<?=$rowb['id'];?>"> <?=$rowb['name'];?> </option>
                              <?php } ?>
                                  </select>
                      </div>
                    </div>  
<?php } ?>
</div>

                     <div id="showextra1">

                      <?php

                      if($interesting_main_course_id>0)
                      {
                      ?>
                                  <div class="form-group">
    <label for="usernamee" class="col-sm-2">Interesting Courses :</label>
    <div class="col-sm-8 row">
        <?php 
        if($row['interesting_exrta_course_id']>0)
        {
            $sql_fv = "SELECT id, name FROM courses_details WHERE status=1 AND extra_course_id='$interesting_exrta_course_id'";
        }
        else
        {
            $sql_fv = "SELECT id, name FROM courses_details WHERE status=1 AND main_courses_id='$interesting_main_course_id'";
        }
        
        $result_fv = $conn->query($sql_fv);
        if (!is_array($course_details_ids)) {
            $course_details_ids = explode(',', $course_details_ids); 
        }

        while ($row_fv = $result_fv->fetch_assoc()) {
            $isChecked = in_array($row_fv['id'], $course_details_ids) ? 'checked' : '';
            ?>
            <div class="col-sm-4">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="course_details_ids[]" <?= $isChecked; ?> value="<?= htmlspecialchars($row_fv['id']); ?>" disabled> <?= htmlspecialchars($row_fv['name']); ?>
                    </label>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

                      <?php } ?>
                      
                    </div>




                    <div class="form-group">
                            <label for="usernamee" class="col-sm-2">Interesting City :</label>
                            <div class="col-sm-8">

                                <select name="interesting_city_ids[]" id="interesting_city_ids[]"  multiple="multiple" class="form-control select2"  data-placeholder="Select Interesting City" disabled >
                                
                                  <?php 
                                        $sql_fv="SELECT id,name FROM m_city where status=1 order by name ASC";
                                        $result_fv = $conn->query($sql_fv);
                                        while($row_fv = $result_fv->fetch_array())
                                    {
                                    ?>
                                        <option value="<?=$row_fv['id'];?>" <?php $fam_val=explode(",",$interesting_city_ids);  for($fs=0;$fs<count($fam_val);$fs++) { if($row_fv['id'] == $fam_val[$fs]) { ?> selected="selected" <?php } } ?>> <?=$row_fv['name'];?> </option>
               
                                  <?php } ?>
                                </select>
                            </div>
                        </div>
               <div class="form-group">
                      <label for="usernamee" class="col-sm-2">Chat Course :</label>
                      <div class="col-sm-8">

                           <select name="chat_course_id"  id="chat_course_id"  class="form-control" disabled>
                            <option value=""> Select Chat Course </option>
                            <?php 
                            $sqlb="SELECT id,name FROM chat_course where status=1";
                            $resultb = $conn->query($sqlb);
                            while($rowb = $resultb->fetch_array())
                            {
                              ?>
                              <option <?php if($row['chat_course_id']==$rowb['id']) { echo "selected"; } ?> value="<?=$rowb['id'];?>"> <?=$rowb['name'];?> </option>
                              <?php } ?>
                                  </select>
                      </div>
                    </div>
             

   <div class="form-group">
               <label class="control-label col-sm-2">Transaction ID:</label>
                  <div class="col-sm-8">
                      <input type="text" class="form-control" name="txnid"  value="<?=$row['txnid'];?>"  id="first_name" placeholder="Enter Transaction ID"  required>
                  </div>
           </div>

           
            <div class="form-group">
                <label class="col-sm-2">Payment Gatway Status :</label>
                <div class="col-sm-6">
                    <div class="col-sm-3 col-xs-6">
                        <label><input name="payment_gateway_success_status" type="radio" value="1" <?php if($payment_gateway_success_status==1){  ?>checked="checked"<?php } ?>/>
                          Success</label>
                    </div>
                    <div class="col-sm-6 col-xs-6">
                        <label><input name="payment_gateway_success_status" type="radio" value="0"  <?php if($payment_gateway_success_status==0){  ?>checked="checked"<?php } ?> />
                          Failed</label>
                    </div>
                </div>
            </div>

<!--             <div class="form-group">
                <label class="col-sm-2">Chat Status :</label>
                <div class="col-sm-6">
                    <div class="col-sm-3 col-xs-6">
                        <label><input name="is_chat_block" type="radio" value="0" <?php if($is_chat_block!=1){  ?>checked="checked"<?php } ?>/>
                          Active</label>
                    </div>
                    <div class="col-sm-6 col-xs-6">
                        <label><input name="is_chat_block" type="radio" value="1"  <?php if($is_chat_block==1){  ?>checked="checked"<?php } ?> />
                          Blocked</label>
                    </div>
                </div>
            </div>

 -->




           



            <div class="col-md-12" align="right">
              <button type="submit" class="btn btn-success ">Save changes</button>
            </div>

          </form>
        </div>
      </div>
    </section>
  </div>
  <?php include("../includes/footer.php"); ?>
</div>
<?php include("../includes/js-scripts.php"); ?>
<script>
$(document).ready(function(){
  //Select2
  $(".select2").select2();
});
</script>

<script>
  $(function () {
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,
      todayHighlight:true,
     format: 'dd-mm-yyyy',
    });
    
  });
</script>
<script>
$(document).ready(function () {
    // Main course change event
    $("#main_courses_id").change(function () {
        var main_courses_id = $(this).find(":selected").val();
        if (main_courses_id != "") {
            $.ajax({
                type: "POST",
                url: "master/ajax/ajax-show-courses-details-register.php",
                data: { main_courses_id: main_courses_id },
                success: function (response) {
                    $("#showextra").html(response);
                    $("#showextra1").html('');
                }
            });
        }
    });

    // Extra course change event (handle dynamically created elements)
    $(document).on("change", "#extra_course_id", function () {
        var extra_course_id = $(this).find(":selected").val();
        if (extra_course_id != "") {
            $.ajax({
                type: "POST",
                url: "master/ajax/ajax-show-courses-details-new.php",
                data: { extra_course_id: extra_course_id },
                success: function (response) {
                    $("#showextra1").html(response);
                }
            });
        }
    });
});


  </script>
</body>
</html>
