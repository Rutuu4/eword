<?php 
include("../../database.php"); 

if($_POST['h1']==1)
{  

  $name=mysqli_real_escape_string($conn,$_POST['name']);
  $video_link=mysqli_real_escape_string($conn,$_POST['video_link']);
  $status=mysqli_real_escape_string($conn,$_POST['status']);
  $create_datetime=date("Y-m-d H:i:s");

  $main_courses_id=mysqli_real_escape_string($conn,$_POST['main_courses_id']);
  $courses_details_id=mysqli_real_escape_string($conn,$_POST['courses_details_id']);
  $details=mysqli_real_escape_string($conn,$_POST['details']);
  $status=mysqli_real_escape_string($conn,$_POST['status']);
  $website_list=mysqli_real_escape_string($conn,$_POST['website_list']);


  $qry_chk="SELECT main_courses_id,name from m_exrta_course where id=$courses_details_id";
  $result_chk=$conn->query($qry_chk);
  $row_chk=$result_chk->fetch_array();

  $main_courses_id=$row_chk['main_courses_id'];
  $name=$row_chk['name'];





$alias=preg_replace('!\s+!', '-',preg_replace("/[^A-Za-z0-9 \s+]/",' ',trim(mysqli_real_escape_string($conn,$_POST['name']), " ")));
$alias=substr(strip_tags($alias), 0, 50);
$alias=strtolower($alias);
$alias_iimmgg=preg_replace('!\s+!', '-',preg_replace("/[^A-Za-z0-9 \s+]/",' ',substr(strip_tags($alias), 0, 30)));  

$qm1=$_POST['qm1'];

$idir = "courses-details/";     
$idirr = "../../../courses-details/"; 
 
//$userfile_extn=explode(".",strtolower($_FILES['pdf_file']['name']));
$userfile_extn[1]=strtolower(pathinfo($_FILES['pdf_file']['name'], PATHINFO_EXTENSION));

//image validation jpeg,png images
if (($userfile_extn[1] != "pdf") && ($userfile_extn[1] != "PDF") && ($userfile_extn[1] != "")) 
{
$msg="Image File Extention Invalid , Please Upload Valid Image";    
    
}else{

//copy to images to folder
if(!$_FILES['pdf_file']['tmp_name']==""){
$copy = copy($_FILES['pdf_file']['tmp_name'], $idirr."".$alias_iimmgg."-1-".time().".".$userfile_extn[1]);
$pdf_file=$idir."".$alias_iimmgg."-1-".time().".".$userfile_extn[1];
if($pdf_file==$idir."."){ $pdf_file=""; }

} }



if(!empty($courses_details_id))
{

  $course_name_list=array();
  $qry_course="SELECT name from m_exrta_course where id='$courses_details_id'";
  $result_course=$conn->query($qry_course);
  $row_course=$result_course->fetch_array();
  $exrta_cours_name=trim($row_course['name']);

}
else
{
  $exrta_cours_name='';
}

  $qry_chk="SELECT MAX(id) AS max_id  FROM admission_process";    
  $result_chk=$conn->query($qry_chk);
  $row_chk=$result_chk->fetch_array();
  $display_order=$row_chk['max_id']+1;




  $qury1="INSERT INTO admission_process(user_id, create_datetime, main_courses_id, courses_details_id, name, video_link, details, pdf_file, status,website_list,exrta_cours_name,display_order) VALUES ('$login_id','$create_datetime','$main_courses_id','$courses_details_id','$name','$video_link','$details','$pdf_file','$status','$website_list','$exrta_cours_name','$display_order')";          
  $sq1=$conn->query($qury1);
  $last_idd=mysqli_insert_id($conn);


  if(!empty($_POST['website_link']))
  {
          for($i=0; $i<count($_POST['website_link']); $i++)
          {
             $website_link=$_POST['website_link'][$i];
             $insert_qry_array_img_final[] = "(".$last_idd.",'".$website_link."')";
          }
          $qury2="insert into admission_process_website_link_txn( admission_process_id,website_link) values ".implode(',',$insert_qry_array_img_final)."";   
          $sq2 = $conn->query($qury2);
  }

 




  if($last_idd)
    {  
      header("location:../manage-admission.php"); 
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
      <h1>Add Admission Process </h1>
    </section>

    <section class="content">

      <div class="box box-success">
     
        <div class="box-body">
          <form action="" method="POST" id="" class="form-horizontal" enctype="multipart/form-data">
            <input name="h1" type="hidden" id="h1" value="1" />
          
             
             <div class="form-group">
                      <label for="usernamee" class="col-sm-2">Select Course :</label>
                      <div class="col-sm-8">

                           <select name="courses_details_id"  id="courses_details_id"  class="form-control select2"  required>
                            <option value=""> Select Sub Course </option>
                            <?php 
                            $sqlb="SELECT id,name FROM m_exrta_course where status=1  and is_hide=0 GROUP BY TRIM(LOWER(name)) order by name ASC";
                            $resultb = $conn->query($sqlb);
                            while($rowb = $resultb->fetch_array())
                            {
                              ?>
                              <option value="<?=$rowb['id'];?>"> <?=$rowb['name'];?> </option>
                              <?php } ?>
                                  </select>
                      </div>
                    </div>  
    

            <!-- <div class="form-group">
              <label class="control-label col-sm-2">Name  :</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="name"  id="name" placeholder="Enter Name " value="<?=$row['name'];?>"  >
              </div>
            </div>
 -->
            <!--  <div class="form-group">
              <label class="control-label col-sm-2">Video Link  :</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="video_link"  id="video_link" placeholder="Enter Video Link " value="<?=$row['video_link'];?>" >
              </div>
            </div> -->

              <div class="form-group">
                <label for="passwrod" class="col-sm-2">Description :</label>
                <div class="col-sm-8">
                     
                    <textarea class="form-control textarea" placeholder="Description" style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="details" id="details" ></textarea>
                        
                </div>
            </div>

             <div class="form-group">
                <label for="email" class="col-sm-2">PDF File:</label>
                <div class="col-sm-8">
                     <input name="pdf_file" id="pdf_file" type="file"  class="file" multiple=true data-preview-file-type="any">
                </div>
            </div>
                 
            <div class="input_img_fields_wrap">
                <button class="add_field_button btn btn-primary btn-file" style="margin-bottom:20px;"><i class="fa fa-plus" aria-hidden="true"></i> Add More Link</button>
             <div class="form-group">
                <label for="passwrod" class="col-sm-2">Website Link :</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="website_link[]"  id="website_link" placeholder="Enter Website Link " >
                </div>
            </div>
          </div>


           
            <div class="form-group">
                      <label class="col-sm-2">Status :</label>
                      <div class="col-sm-6">
                        <div class="col-sm-3 col-xs-6">
                          <label>
                          <input name="status" type="radio" value="1" checked="checked" />
                          Active</label>
                        </div>
                        <div class="col-sm-6 col-xs-6">
                          <label>
                          <input name="status" type="radio" value="0"   />
                          Deactive</label>
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
     //bootstrap WYSIHTML5 - text editor
   $(".textarea").wysihtml5({
    toolbar: {
        "link": false // Disables the Insert Link option
    }
});
});
</script>
<script>
$(document).ready(function(){
   $("#main_courses_id").change(function(){
    var main_courses_id = $(this).find(":selected").val();
    if(main_courses_id != ""){
      $.ajax({
        type: "POST",
        url: "master/ajax/ajax-course.php",
        data: {main_courses_id:main_courses_id},
        success: function(response) {
          $("#courseopt").html(response);
        }
      });
    }else{
      $("#courseopt").html("<option value=''>Select Main Course First</option>");
    }
  }); });
  </script>
  <script>
  //add remove script
  $(document).ready(function() {
    var max_fields      = 6; //maximum input boxes allowed
    var wrapper         = $(".input_img_fields_wrap"); //Fields wrapper
    var add_button      = $(".add_field_button"); //Add button ID
   
    var x = 1; //initlal text box count
    $(add_button).on("click", function(e) { //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $(wrapper).append('<div class="form-group" style="position:relative;"><a href="#" class="remove_field" style="top:6px; position:absolute; z-index:99; right:133px;width: 23px;height: 23px;text-align: center;border-radius: 50%; color:#fff;background:#3c8dbc;"><i class="fa fa-times" aria-hidden="true"></i> </a><label for="email" class="col-sm-2">Website Link :</label><div class="col-sm-8"><input type="text" class="form-control" name="website_link[]"  id="website_link" placeholder="Enter Website Link "  required></div></div>');
      
      $(".file").fileinput();
            $(".file").fileinput({'showUpload':false, 'previewFileType':'any'});
        }
    });    
   
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove(); x--;
    })
});
</script>

</body>
</html>
