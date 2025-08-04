<?php 
  include("../../database.php"); 

if($_POST['h1']==1)
{  

$alias_iimmgg=date("YmdHIS  "); 
$title=mysqli_real_escape_string($conn,$_POST['title']);
$mesage=mysqli_real_escape_string($conn,$_POST['mesage']);
$datee=date('Y-m-d');


//img upload to dir
$idir = "notification-img/"; 
$idirr = "../../../notification-img/";  
$userfile_extn=explode(".",strtolower($_FILES['img']['name']));

//image validation jpeg,png images
if (($userfile_extn[1] != "jpg") && ($userfile_extn[1] != "jpeg") && ($userfile_extn[1] != "png") &&($userfile_extn[1] != "")) 
{
$msg="Image File Extention Invalid , Please Upload Valid Image";    
    
}else{

//copy to images to folder
if(!$_FILES['img']['tmp_name']==""){
$copy = copy($_FILES['img']['tmp_name'], $idirr."".$alias_iimmgg."-".time().".".$userfile_extn[1]);
$img=$idir."".$alias_iimmgg."-".time().".".$userfile_extn[1];
if($img==$idir."."){ $img=""; }

}

$details=mysqli_real_escape_string($conn,$_POST['details']);
$status=mysqli_real_escape_string($conn,$_POST['status']);
$website_link=mysqli_real_escape_string($conn,$_POST['website_link']);

$notification_type=1;
if(!empty($_POST['course_ids']))
{
    $course_ids=implode(",",$_POST['course_ids']);
}
$main_course_id=mysqli_real_escape_string($conn,$_POST['main_course_id']);

$qury1="INSERT INTO mobile_notification(title, mesage,datee, img,details,status,website_link,notification_type,course_ids,main_course_id) VALUES ('$title','$mesage','$datee','$img','$details','$status','$website_link','$notification_type','$course_ids','$main_course_id')";

//echo $qury1;          
          
$sq1 = $conn->query($qury1);  
$last_idd=mysqli_insert_id($conn);


if(!empty($img))
{
  $imageUrl=$web_url.$img; 
}
else
{
  $imageUrl='';
}



            if (!empty($course_ids)) 
            {
                $course_ids_array = explode(',', $course_ids);
                foreach ($course_ids_array as $course_id) {
                    $conditions[] = "FIND_IN_SET('$course_id', registration.course_details_ids) > 0";
                }
            }

            $wherestring='';
            if (!empty($conditions)) 
            {
                $wherestring = " AND (" . implode(' OR ', $conditions) . ")";
            }

      $qry="SELECT id from registration where status=1 and device_type!=0  and device_token!='' $wherestring ";
      $result_reg=$conn->query($qry);
      $rowcount=mysqli_num_rows($result_reg);
      $page_size=1000;
      $per_page   = $page * $page_size;
      $total_page = ceil($rowcount / $page_size);  

      for($i=0;$i<$total_page;$i++)
      { 
        $start_limit=$i.'000';
        $qry="SELECT device_token,device_type from registration where status=1 and device_type!=0 and device_token!='' $wherestring  limit $start_limit,$page_size";
        $result_reg=$conn->query($qry);
        while($row_reg= $result_reg->fetch_array())
        { 
          if($row_reg['device_type']==1)
          {
              $divice_token_android[] = $row_reg['device_token'];
          }

        }


            if(!empty($divice_token_android))
            {
                
                include("push-notification-android-multiple.php");
               
            }
            
           
      }


if(!$last_idd=="")
{ 
  header("location:../coursewise-notification.php"); 
}

} }

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
      <h1>Create Notification</h1>
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
              <label class="control-label col-sm-2">Notification Title :</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="title"  id="title" placeholder="Enter Notification Title"  required>
              </div>
            </div>

             <div class="form-group">
              <label class="control-label col-sm-2">Notification mesage :</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="mesage"  id="mesage" placeholder="Enter Notification mesage"  required>
              </div>
            </div>


            <div class="form-group">
                <label for="email" class="col-sm-2">Notification Image :</label>
                <div class="col-sm-8">
                     <input name="img" id="img" type="file"  class="file" multiple=true data-preview-file-type="any">
                </div>
            </div>

            <div class="form-group">
                <label for="passwrod" class="col-sm-2">Description :</label>
                <div class="col-sm-8">
                     
                    <textarea class="form-control textarea" placeholder="Description" style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="details" id="details" ></textarea>
                        
                </div>
            </div>
              <div class="form-group">
              <label class="control-label col-sm-2">Website Link :</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="website_link"  id="website_link" placeholder="Enter Website Link " >
              </div>
            </div>

              <div class="form-group">
                      <label for="usernamee" class="col-sm-2"> Main Course :</label>
                      <div class="col-sm-8">

                           <select name="main_course_id"  id="main_courses_id"  class="form-control" >
                            <option value=""> Select Main Course </option>
                            <?php 
                            $sqlb="SELECT id,name FROM m_main_courses where status=1";
                            $resultb = $conn->query($sqlb);
                            while($rowb = $resultb->fetch_array())
                            {
                              ?>
                              <option  value="<?=$rowb['id'];?>"> <?=$rowb['name'];?> </option>
                              <?php } ?>
                                  </select>
                      </div>
                    </div>
                      <div id="showextra">
                      </div>

            
            <div class="form-group">
              <label class="control-label col-sm-2">Status :</label>
              <div class="col-sm-10" >
                <input type="radio" name="status" value="1" checked=""> Active
                &nbsp;&nbsp;&nbsp;
                <input type="radio" name="status" value="0" > Deactive
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
     //bootstrap WYSIHTML5 - text editor
    $(".textarea").wysihtml5();
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
                url: "master/ajax/ajax-show-courses-details.php",
                data: { main_courses_id: main_courses_id },
                success: function (response) {
                    $("#showextra").html(response);
                   
                }
            });
        }
    });
});

  </script>
</body>
</html>
