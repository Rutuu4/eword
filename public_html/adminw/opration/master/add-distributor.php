<?php 
  include("../../database.php"); 

  if(isset($_POST['submit'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];
    $dob = $_POST['dob'];
    $fname = $_POST['fname'];
    $gst_no = $_POST['gst_no'];
	$pan_no = $_POST['pan_no'];
    $address       = $_POST['address'];
    $state         = $_POST['state'];
    $district      = $_POST['city'];
    $status        = $_POST['status'];

    $manager_id=$_POST['manager_id'];
    $sales_id=$_POST['sales_id'];
    $stockist_id=$_POST['stockist_id'];
    $designation_type=$_POST['designation'];
	$sales_target_turnover=$_POST['sales_target_turnover'];
	
	$stockist_operation_type=$_POST['stockist_operation_type'];
	$gst_apply=$_POST['gst_apply'];



   


    $insertqry = "INSERT INTO register1(name, email, mobile, password, dob, fname, gst_no, address, state, district, status,manager_id,sales_id,stockist_id,distributor_id,designation_type,sales_target_turnover,pan_no,gst_apply,stockist_operation_type) VALUES ('$name','$email','$mobile','$password','$dob','$fname','$gst_no','$address','$state','$district','$status','$manager_id','$sales_id','$stockist_id','$distributor_id','$designation_type','$sales_target_turnover','$pan_no','$gst_apply','$stockist_operation_type')";
    $exeUpdate = $conn->query($insertqry);
	$last_idd=mysqli_insert_id($conn);  
	
	//if designation stockist and operation type=1 alter product stock here
	if($designation_type=3 && $stockist_operation_type==1 && $last_idd>0){
	
	//product filter add column to product table
     $qury1="ALTER TABLE product ADD stockist_qty_$last_idd INT NOT NULL";
     $sq1 = $conn->query($qury1);
	}
	
    if($exeUpdate){
        header("Location: ../manage-distributor.php");
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
      <h1>Add Manage Distributor  User to <?=$softtitle?></h1>
    </section>

    <section class="content">

      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Add Data Here</h3>
        </div>

        <div class="box-body">
          <div class="col-sm-7">
            <form action="" method="POST" class="form-horizontal" id="adduserform">

              <div class="form-group">
                <label class="control-label col-sm-3">Designation <span class="text-red">*</span></label>
                <div class="col-sm-9">
                  <select class="form-control" name="designation" required  id="designation">
                    <option value="">Select Designation </label></option>
                    <?php 
                      $query1 = $conn->query("SELECT * FROM m_designation WHERE status = 1");
                      while($row1 = $query1->fetch_array()){
                    ?>
                    <option value="<?=$row1[0]?>"><?=$row1[1]?></option>
                  <?php } ?>
                  </select>
                </div>
              </div>
              
              <div id="ajax-manager-response"></div>
              <div id="ajax-sales-response"></div>
              <div id="ajax-stockist-response"></div>
              
             
              
              
            <div class="form-group">
                <label class="control-label col-sm-3">Mobile(As Username):<span class="text-red">*</span></label>
                <div class="col-sm-9">
                  <input type="number" onKeyPress="if(this.value.length==10) return false;" class="form-control" name="mobile" placeholder="Mobile Number(As Username)" id="phonenumber" required>
                  <span id="phonenumbererr" style="color: red"></span>
                  <?php if($mobileerr == 1){ ?><span class="text-red">Mobile Number is already Exist</span><?php } ?>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-3">Password :</label>
                <div class="col-sm-9">
                  <input type="password" class="form-control" name="password" required  placeholder="Password">
                </div>
              </div>
               <div class="form-group">
                <label class="control-label col-sm-3">Name :</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="name" required placeholder="Name">
                </div>
              </div>
               <div class="form-group">
                <label class="control-label col-sm-3">Email :</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="email"  placeholder="User Email">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-3">DOB :</label>
                <div class="col-sm-9">
                  <input type="date" class="form-control" name="dob"  placeholder="">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-3">Firm name : <span class="text-red">*</span></label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="fname"   placeholder="firm Nane">
                </div>
              </div>
               <div class="form-group">
                <label class="control-label col-sm-3">GST Number :</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" name="gst_no"  placeholder="GST Number">
                </div>
                
                 <div class="col-sm-4">
                   <input type="radio" name="gst_apply" value="1" <?php if($getdata->gst_apply==1 || $getdata->gst_apply==0){?>checked="checked"<?php } ?> />CGST and SGST
                  &nbsp;&nbsp;&nbsp;
                  <input type="radio" name="gst_apply" value="2"  <?php if($getdata->gst_apply==2 ){?>checked="checked"<?php } ?> />IGST
                </div>
              </div>
              
              <div class="form-group">
                <label class="control-label col-sm-3">Pancard Number :</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="pan_no" value="<?=$getdata->pan_no?>" placeholder="Pancard Number">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-3">Address :</label>
                <div class="col-sm-9">
                  <textarea class="form-control" placeholder="Enter Address" name="address"></textarea>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-3">State <span class="text-red">*</span></label>
                <div class="col-sm-9">
                  <select class="form-control" name="state" required="" id="stateopt">
                    <option value="">Select State </label></option>
                    <?php 
                      $query1 = $conn->query("SELECT * FROM `m_state` WHERE status = 1");
                      while($row1 = $query1->fetch_array()){
                    ?>
                    <option value="<?=$row1[0]?>"><?=$row1[1]?></option>
                  <?php } ?>
                  </select>
                </div>
              </div>
                <div class="form-group">
                <label class="control-label col-sm-3">City <span class="text-red">*</span></label>
                <div class="col-sm-9">
                  <select class="form-control" name="city" required="" id="cityopt">
                    <option value="">Select State First</option>
                  </select>
                </div>
              </div>

             

              <div class="form-group">
                <label class="control-label col-sm-3">Status :</label>
                <div class="col-sm-9" style="padding:7px;">
                  <input type="radio" name="status" value="1" checked=""> Active
                  &nbsp;&nbsp;&nbsp;
                  <input type="radio" name="status" value="0"> Deactive
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
  $("#usertype").change(function(){
    var selval = $(this).find(":selected").val();
    if(selval == "sales" || selval == "inqury-insert"){
      $("#teamleaderDiv").slideDown(400);
      $("#teamleader").prop('required',true);
    }else{
      $("#teamleaderDiv").slideUp(400);
      $("#teamleader").prop('required',false);
    }
  });
  $("#stateopt").change(function(){
    var stateval = $(this).find(":selected").val();
    if(stateval != ""){
      $.ajax({
        type: "POST",
        url: "ajax/ajax-getcity.php",
        data: {stateval:stateval},
        success: function(response) {
          $("#cityopt").html(response);
        }
      });
    }else{
      $("#cityopt").html("<option value=''>Select State First</option>");
    }
  });
  //Validations
  $( "#adduserform" ).validate({
    rules: {
      name: { required: true  },
      mobile : { required: true,number: true,minlength:10,maxlength:10  },
     
      username: { required: true  },
      password: { required: true,minlength: 4  },
      typee: {required:true}
    },
    messages: {
      name:  "Name is Required" ,
      mobile:  "Valid Mobile Number is Required" ,
      
      username: "Username is Required" ,
      password:  "Strong Password is Required (4-16 Character)" ,
      typee: "Please Select Designation"
    }
  });
  
   
  $(document).on('change',"#designation", function(){ 
   var designation_id=$("#designation").val();
   
   if(designation_id>0){  $("#ajax-manager-response").html('');
   $("#ajax-sales-response").html('');
   $("#ajax-stockist-response").html('');  }
   
   if(designation_id>1){
   
   $.ajax({
            type: "POST",
            async: false,
            url: "master/ajax/ajax-get-manager.php",
            data: {designation_id:designation_id},
            success: function(response){
           if(!response==""){
           $("#ajax-manager-response").html(response);
           } }
         });	 
    
   
   }
   
   });
   
   
   
   
   $(document).on('change',"#manager_id", function(){ 
   var designation_id=$("#designation").val();
   var manager_id=$("#manager_id").val();

   if(manager_id>0 && designation_id>2){
   $.ajax({
            type: "POST",
            async: false,
            url: "master/ajax/ajax-get-sales.php",
            data: {manager_id:manager_id, designation_id:designation_id},
            success: function(response){
           if(!response==""){
           $("#ajax-sales-response").html(response);
           } }
         });	 
   

   }
   
   });
   
   
   
   $(document).on('change',"#sales_id", function(){ 
   var designation_id=$("#designation").val();
   var manager_id=$("#manager_id").val();
   var sales_id=$("#sales_id").val();

   if(manager_id>0 &&  sales_id>0 && designation_id>3){
   $.ajax({
            type: "POST",
            async: false,
            url: "master/ajax/ajax-get-stockist.php",
            data: {manager_id:manager_id, sales_id:sales_id,  designation_id:designation_id},
            success: function(response){
           if(!response==""){
           $("#ajax-stockist-response").html(response);
           } }
         });	 
   
   
   }

   
   });
   
  
   
   
   
});
</script>
</body>
</html>
