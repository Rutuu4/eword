<?php 
include("../../database.php"); 

if($_POST['h1']==1)
{  

  $main_courses_id=mysqli_real_escape_string($conn,$_POST['main_courses_id']);
  $name=mysqli_real_escape_string($conn,$_POST['name']);
  $status=mysqli_real_escape_string($conn,$_POST['status']);
  $create_datetime=date("Y-m-d H:i:s");

  $qury1="INSERT INTO m_exrta_course(main_courses_id, name, status, user_id, create_datetime) VALUES ('$main_courses_id','$name','$status','$login_id','$create_datetime')";
  
  // echo $qury1;         
  $sq1 = $conn->query($qury1);  
  $last_idd=mysqli_insert_id($conn);



 $qry_insert = "INSERT INTO courses_details (user_id, create_datetime, main_courses_id, name, status, extra_course_id)   VALUES ('$login_id','$create_datetime','$main_courses_id','$name','$status','$last_idd')";
  $stmt_insert = $conn->query($qry_insert);

  if(!$last_idd==""){ header("location:../manage-sub-course.php"); }

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
      color: #555;
      background-color: #fff;
      background-image: none;
      border: 1px solid #ccc;
    }
    .select2-container { width: 100% !important; }
  </style>
</head>
<body class="<?=$bodyclass?>">
<div class="wrapper">
  <?php include("../includes/header.php"); ?>
  <?php include("../includes/sidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Create Sub Course</h1>
    </section>

    <section class="content">

      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Add Data Here</h3>
        </div>

        <div class="box-body">
          <form action="" method="POST" id="" class="form-horizontal" enctype="multipart/form-data">
            <input name="h1" type="hidden" id="h1" value="1" />

             <div class="form-group">
                      <label for="usernamee" class="col-sm-2">Main Course :</label>
                      <div class="col-sm-8">

                           <select name="main_courses_id"  id="main_courses_id"  class="form-control select2"  required>
                            <option value=""> Select Main Course </option>
                            <?php 
                            $sqlb="SELECT id,name FROM m_main_courses where status=1 order by display_order ASC";
                            $resultb = $conn->query($sqlb);
                            while($rowb = $resultb->fetch_array())
                            {
                              ?>
                              <option value="<?=$rowb['id'];?>"> <?=$rowb['name'];?> </option>
                              <?php } ?>
                                  </select>
                      </div>
                    </div>
          


            <div class="form-group">
              <label class="control-label col-sm-2">Name :</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="name"  id="name" placeholder="Enter Name "  required>
              </div>
            </div>

             <!-- <div class="form-group">
              <label class="control-label col-sm-2">Member Limit :</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="member_limit"  id="member_limit" placeholder="Enter Member Limit "  required>
              </div>
            </div> -->
<!-- 
            <div class="form-group">
                <label class="col-sm-2">Show Status :</label>
                <div class="col-sm-6">
                   
                   <div class="col-sm-3 col-xs-6">
                       <label><input name="is_hide" type="radio" value="0"  checked="checked" />Show</label>
                   </div>
                   <div class="col-sm-3 col-xs-6">
                       <label><input name="is_hide" type="radio" value="1" />Hide</label>
                   </div>
                </div>
            </div> -->

                       
            <div class="form-group">
                <label class="col-sm-2">Status :</label>
                <div class="col-sm-6">
                   <div class="col-sm-3 col-xs-6">
                       <label><input name="status" type="radio" value="1" checked="checked"/>Active</label>
                   </div>
                   <div class="col-sm-6 col-xs-6">
                       <label><input name="status" type="radio" value="0"   />Deactive</label>
                   </div>
                </div>
            </div>

            
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


</body>
</html>
