<?php 
include("../../database.php"); 

if($_POST['h1']==1)
{  

  $id=mysqli_real_escape_string($conn,$_POST['id']);
  $name=mysqli_real_escape_string($conn,$_POST['name']);
  $video_link=mysqli_real_escape_string($conn,$_POST['video_link']);
  $status=mysqli_real_escape_string($conn,$_POST['status']);
  $create_datetime=date("Y-m-d H:i:s");

  $main_courses_id=mysqli_real_escape_string($conn,$_POST['main_courses_id']);
  $courses_details_id=mysqli_real_escape_string($conn,$_POST['courses_details_id']);
  $details=mysqli_real_escape_string($conn,$_POST['details']);
  $status=mysqli_real_escape_string($conn,$_POST['status']);
  $alias=preg_replace('!\s+!', '-',preg_replace("/[^A-Za-z0-9 \s+]/",' ',trim(mysqli_real_escape_string($conn,$_POST['name']), " ")));
  $alias=substr(strip_tags($alias), 0, 50);
  $alias=strtolower($alias);
  $alias_iimmgg=preg_replace('!\s+!', '-',preg_replace("/[^A-Za-z0-9 \s+]/",' ',substr(strip_tags($alias), 0, 30)));

    $website_list=mysqli_real_escape_string($conn,$_POST['website_list']);


  $qm1=$_POST['qm1'];

$idir = "courses-details/";     
$idirr = "../../../courses-details/"; 
 
$userfile_extn=explode(".",strtolower($_FILES['pdf_file']['name']));

//image validation jpeg,png images
if($userfile_extn[1] == "")
{
$msg="Image File Extention Invalid , Please Upload Valid Image";    
    
}else{

//copy to images to folder
if(!$_FILES['pdf_file']['tmp_name']==""){
$copy = copy($_FILES['pdf_file']['tmp_name'], $idirr."".$alias_iimmgg."-1-".time().".".$userfile_extn[1]);
$pdf_file=$idir."".$alias_iimmgg."-1-".time().".".$userfile_extn[1];
if($pdf_file==$idir."."){ $pdf_file=""; }

} }

if($pdf_file==""){ $pdf_file=$qm1; }


$qury1="UPDATE admission_process SET main_courses_id='$main_courses_id',courses_details_id='$courses_details_id',name='$name',video_link='$video_link',details='$details',pdf_file='$pdf_file',status='$status',website_list='$website_list' where id='$id'";
 $sq1 = $conn->query($qury1); 

  if(!empty($_POST['website_link']))
  {
          for($i=0; $i<count($_POST['website_link']); $i++)
          {
             $website_link=$_POST['website_link'][$i];
             $updated_id=$_POST['updated_id'][$i];

             if($updated_id>0)
             {

              $qry_website_list_update="UPDATE admission_process_website_link_txn SET website_link='$website_link' where id='$updated_id'";
              $sq1_website_list_update=$conn->query($qry_website_list_update);

             }
             else
             {
               $insert_qry_array_img_final[] = "(".$id.",'".$website_link."')";

             }
            
          }

          if(!empty($insert_qry_array_img_final))
          {
             $qury2="insert into admission_process_website_link_txn( admission_process_id,website_link) values ".implode(',',$insert_qry_array_img_final)."";   
             $sq2 = $conn->query($qury2);

          }
         
  }



 header("location:../manage-admission.php"); 

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

 <?php

if(!empty($_GET['key'])){

  $id=base64_decode($_GET['key']);
 
  $qry="SELECT * FROM admission_process WHERE id='$id'";  

  $result = $conn->query($qry);
  $row = $result->fetch_array();
  $status=$row['status'];
  $qm1=$row['pdf_file'];
  $main_courses_id=$row['main_courses_id'];

}
?> 


<div class="wrapper">
  <?php include("../includes/header.php"); ?>
  <?php include("../includes/sidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Update Admission Process </h1>
    </section>

    <section class="content">

      <div class="box box-success">
     
        <div class="box-body">
          <form action="" method="POST" id="" class="form-horizontal" enctype="multipart/form-data">
            <input name="h1" type="hidden" id="h1" value="1" />
             <input name="id" type="hidden" id="id" value="<?=$id;?>" />
               <input name="qm1" type="hidden" id="qm1" value="<?=$qm1?>" />


                     <div class="form-group">
                      <label for="usernamee" class="col-sm-2">Main Course :</label>
                      <div class="col-sm-8">

                           <select name="main_courses_id"  id="main_courses_id"  class="form-control select2"  required>
                            <option value=""> Select Main Course </option>
                            <?php 
                            $sqlb="SELECT id,name FROM m_main_courses where status=1";
                            $resultb = $conn->query($sqlb);
                            while($rowb = $resultb->fetch_array())
                            {
                              ?>
                              <option <?php if($row['main_courses_id']==$rowb['id']){ echo "selected"; } ?> value="<?=$rowb['id'];?>"> <?=$rowb['name'];?> </option>
                              <?php } ?>
                                  </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="usernamee" class="col-sm-2">Select Course :</label>
                      <div class="col-sm-8">

                           <select name="courses_details_id"  id="courseopt"  class="form-control select2"  required>
                            <option value=""> Select Course </option>
                            <?php 
                            $sql_ch="SELECT id,name FROM courses_details WHERE main_courses_id = '".$main_courses_id."'";
                            $result_ch = $conn->query($sql_ch);
                            while($row_ch = $result_ch->fetch_array())
                            {
                              ?>
                              <option value="<?=$row_ch['id'];?>"  <?php  if($row_ch['id']==$row['courses_details_id']) { ?> selected="selected" <?php } ?>> <?=$row_ch['name'];?> </option>
                              <?php } ?>
                                  </select>
                      </div>
                    </div>       

            <div class="form-group">
              <label class="control-label col-sm-2">Name  :</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="name"  id="name" placeholder="Enter Name " value="<?=$row['name'];?>"  required>
              </div>
            </div>

             <div class="form-group">
              <label class="control-label col-sm-2">Video Link  :</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="video_link"  id="video_link" placeholder="Enter Video Link " value="<?=$row['video_link'];?>"  required>
              </div>
            </div>

              <div class="form-group">
                <label for="passwrod" class="col-sm-2">Description :</label>
                <div class="col-sm-8">
                     
                    <textarea class="form-control textarea" placeholder="Description" style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="details" id="details" ><?=$row['details'];?></textarea>
                        
                </div>
            </div>
             <?php if(!$qm1==""){?>   
                    <div class="form-group">
                      <label for="name" class="col-sm-2">Current File</label>
                      <div class="col-sm-7">
                        <a href="../../<?php echo $qm1 ; ?>" target="_blank" class="btn btn-info" role="button">View Current File </a>
                      </div>
                </div>
                 <?php  } ?>

             <div class="form-group">
                <label for="email" class="col-sm-2">PDF File:</label>
                <div class="col-sm-8">
                     <input name="pdf_file" id="pdf_file" type="file"  class="file" multiple=true data-preview-file-type="any">
                </div>
            </div>


            <div class="input_img_fields_wrap">
                <button class="add_field_button btn btn-primary btn-file" style="margin-bottom:20px;"><i class="fa fa-plus" aria-hidden="true"></i> Add More Link</button>
<?php
$qry_website_list="SELECT * FROM admission_process_website_link_txn WHERE admission_process_id='$id'";
$result_website_list=$conn->query($qry_website_list);
while($row_website_list=$result_website_list->fetch_array())
{ ?>

<div class="form-group" style="position:relative;"> <a button class="btn btn-danger btn-sm" onClick="window.open('master/delete-admission-process-website-link_txn.php?id=<?=$row_website_list['id'];?>',   'win1','width=950, height=800, menubar=no ,scrollbars=yes,top=50,left=100')"><i class="fa fa-times"></i></button></a>
                <label for="passwrod" class="col-sm-2">Website Link :</label>
                <div class="col-sm-8">
                  <input type="hidden" name="updated_id[]" value="<?=$row_website_list['id'];?>">
                    <input type="text" class="form-control" name="website_link[]"  id="website_link" placeholder="Enter Website Link " value="<?=$row_website_list['website_link'];?>"  required>
                </div>
            </div>


<?php } ?>


           



          </div>

           
            <div class="form-group">
                      <label class="col-sm-2">Status :</label>
                      <div class="col-sm-6">
                        <div class="col-sm-3 col-xs-6">
                          <label>
                          <input name="status" type="radio" value="1" <?php if($status==1){  ?>checked="checked"<?php } ?>/>
                          Active</label>
                        </div>
                        <div class="col-sm-6 col-xs-6">
                          <label>
                          <input name="status" type="radio" value="0"  <?php if($status==0){  ?>checked="checked"<?php } ?> />
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
});
</script>
<script>
$(document).ready(function(){
  //Select2
  $(".select2").select2();
     //bootstrap WYSIHTML5 - text editor
    $(".textarea").wysihtml5();
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
            $(wrapper).append('<div class="form-group" style="position:relative;"><a href="#" class="remove_field" style="top:6px; position:absolute; z-index:99; right:133px;width: 23px;height: 23px;text-align: center;border-radius: 50%; color:#fff;background:#3c8dbc;"><i class="fa fa-times" aria-hidden="true"></i> </a><label for="email" class="col-sm-2">Website Link :</label><div class="col-sm-8"> <input type="hidden" name="updated_id[]" value=""><input type="text" class="form-control" name="website_link[]"  id="website_link" placeholder="Enter Website Link "  required></div></div>');
      
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
