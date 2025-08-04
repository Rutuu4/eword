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
  if($main_courses_id==1)
  {
    $extra_course_id=mysqli_real_escape_string($conn,$_POST['extra_course_id']);
  }
  else
  {
    $extra_course_id=0;
  }

  
  $details=mysqli_real_escape_string($conn,$_POST['details']);
  $status=mysqli_real_escape_string($conn,$_POST['status']);
  $alias=preg_replace('!\s+!', '-',preg_replace("/[^A-Za-z0-9 \s+]/",' ',trim(mysqli_real_escape_string($conn,$_POST['name']), " ")));
  $alias=substr(strip_tags($alias), 0, 50);
  $alias=strtolower($alias);
  $alias_iimmgg=preg_replace('!\s+!', '-',preg_replace("/[^A-Za-z0-9 \s+]/",' ',substr(strip_tags($alias), 0, 30)));


  $qm1=$_POST['qm1'];

$idir = "cutoff/";     
$idirr = "../../../cutoff/"; 
 
//$userfile_extn=explode(".",strtolower($_FILES['pdf_file']['name']));
$userfile_extn[1]=strtolower(pathinfo($_FILES['pdf_file']['name'], PATHINFO_EXTENSION));

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

$short_details=mysqli_real_escape_string($conn,$_POST['short_details']);
$year_id=mysqli_real_escape_string($conn,$_POST['year_id']);


 $display_order=mysqli_real_escape_string($conn,$_POST['display_order']);

$qury1="UPDATE cut_off_details SET year_id='$year_id',name='$name',short_details='$short_details',details='$details',pdf_file='$pdf_file',status='$status',display_order='$display_order'  where id='$id'";
 $sq1 = $conn->query($qury1); 

  if(mysqli_affected_rows($conn)>=1){  header("location:../manage-cut-off.php"); }

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
 
  $qry="SELECT * FROM cut_off_details WHERE id='$id'";  

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
      <h1>Update Cut Off Details</h1>
    </section>

    <section class="content">

      <div class="box box-success">
     
        <div class="box-body">
          <form action="" method="POST" id="" class="form-horizontal" enctype="multipart/form-data">
            <input name="h1" type="hidden" id="h1" value="1" />
             <input name="id" type="hidden" id="id" value="<?=$id;?>" />
               <input name="qm1" type="hidden" id="qm1" value="<?=$qm1?>" />


                     <div class="form-group">
                      <label for="usernamee" class="col-sm-2">Year :</label>
                      <div class="col-sm-8">

                           <select name="year_id"  id="year_id"  class="form-control select2"  required>
                            <option value=""> Select Year </option>
                            <?php 
                            $sqlb="SELECT id,name FROM m_year where status=1";
                            $resultb = $conn->query($sqlb);
                            while($rowb = $resultb->fetch_array())
                            {
                              ?>
                              <option <?php if($row['year_id']==$rowb['id']){ echo "selected"; } ?> value="<?=$rowb['id'];?>"> <?=$rowb['name'];?> </option>
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
                <label for="passwrod" class="col-sm-2">Short Description :</label>
                <div class="col-sm-8">
                     
                    <textarea class="form-control" placeholder="Description" style="width: 100%; height: 70px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="short_details" id="short_details" ><?=$row['short_details'];?></textarea>
                        
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
                      <div class="col-sm-2">
                        <a href="../../<?php echo $qm1 ; ?>" target="_blank" class="btn btn-info" role="button">View Current File </a>
                      </div>
                       <div class="col-sm-1">
                       <a button class="btn btn-danger btn-sm" onClick="window.open('master/delete-cut-off-pdf.php?id=<?=$id;?>',   'win1','width=950, height=800, menubar=no ,scrollbars=yes,top=50,left=100')"><i class="fa fa-times"></i></button></a>
                     </div>
                </div>
                 <?php  } ?>

             <div class="form-group">
                <label for="email" class="col-sm-2">PDF File:</label>
                <div class="col-sm-8">
                     <input name="pdf_file" id="pdf_file" type="file"  class="file" multiple=true data-preview-file-type="any">
                </div>
            </div>

               <div class="form-group">
              <label class="control-label col-sm-2">Display Order  :</label>
              <div class="col-sm-8">
                <input type="number" class="form-control" name="display_order"  id="display_order" placeholder="Enter Display Order " value="<?=$row['display_order'];?>"  required>
              </div>
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



</body>
</html>
