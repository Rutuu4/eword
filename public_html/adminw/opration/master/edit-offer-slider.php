<?php 
  include("../../database.php"); 



  if(empty($_GET['key'])){
    echo "<a href='index.php'>Server Issue Click Here to Go back</a>";
    exit;
  }else{
    $getid = base64_decode($_GET['key']);
    $getdata = $conn->query("SELECT * FROM `offer_slider` WHERE id = $getid")->fetch_object();

     $img11=$getdata->image;

  }
  
  if(isset($_POST['submit'])){

    $id = $getid;

    $name = $_POST['name'];
    $status = $_POST['status'];

    $alias_iimmgg=preg_replace('!\s+!', '-',preg_replace("/[^A-Za-z0-9 \s+]/",' ',substr(strip_tags($name), 0, 30)));

       //img upload to dir
$idir = ""; 
$idirr = "../../offer-img/";   
$userfile_extn=explode(".",strtolower($_FILES['img1']['name']));

//image validation jpeg,png images
if (($userfile_extn[1] != "jpg") && ($userfile_extn[1] != "jpeg") && ($userfile_extn[1] != "png") &&($userfile_extn[1] != "")) 
{
$msg="Image File Extention Invalid , Please Upload Valid Image";    

}else{

//copy to images to folder
if(!$_FILES['img1']['tmp_name']==""){
$copy = copy($_FILES['img1']['tmp_name'], $idirr."".$alias_iimmgg."-".time().".".$userfile_extn[1]);
$img1=$idir."".$alias_iimmgg."-".time().".".$userfile_extn[1];
if($img1==$idir."."){ $img1=$img11; }else{ unlink("../../offer-img/$img11"); }

} }

if($img1==""){ $img1=$img11; }



    $insertqry = "UPDATE `offer_slider` SET `name`='$name',`image`='$img1',`status`= '$status' WHERE id='$id'";
    $exeUpdate = $conn->query($insertqry);
    if($exeUpdate){
        header("Location: ../manage-offer-slider.php");
        exit;
    }else{
      header("Location: ../index.php");
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
      color: #d84444;
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
  </style>
</head>
<body class="<?=$bodyclass?>">
<div class="wrapper">
  <?php include("../includes/header.php"); ?>
  <?php include("../includes/sidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Edit offer-slider Image <?=$softtitle?></h1>
    </section>

    <section class="content">

      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Edit slider Data Here</h3>
        </div>

        <div class="box-body">
          <div class="col-sm-7">
            <form action="" method="POST" class="form-horizontal" id="adduserform" enctype="multipart/form-data">

              <div class="form-group">
                <label class="control-label col-sm-2">slider Name:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="name"  value="<?=$getdata->name?>" >
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2">slider icon:</label>
                <div class="col-sm-10">
                  <div>
                     <img src="../offer-img/<?=$getdata->image?>" height="70" width="120" style="margin-bottom: 5px;border: 2px solid black;">
                  </div>
                  <input type="file" class="form-control" name="img1">
                </div>
              </div>


              <div class="form-group">
                <label class="control-label col-sm-2">Status :</label>
                 <div class="col-sm-10" style="padding:7px;">
                  <input type="radio" name="status" value="1" <?php if($getdata->status==1){?>checked="checked"<?php } ?> />Active
                  &nbsp;&nbsp;&nbsp;
                  <input type="radio" name="status" value="0"  <?php if($getdata->status==0){?>checked="checked"<?php } ?> />Deactive
                </div>
              </div>
              <div class="col-md-12" align="right">
                <input type="submit" name="submit" value="Add Data" class="btn btn-success">
              </div>

            </form>
          </div>
          <div class="col-sm-5">
           
          </div>
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
  //Validations
  $( "#adduserform" ).validate({
    rules: {
      name: { required: true  },
    },
    messages: {
      name:  "Name is Required" ,
    }
  });
});
</script>
</body>
</html>
