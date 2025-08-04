<?php include("include/database.php");
  function cleanInP($data) 
  {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
  

 
$username=mysqli_real_escape_string($conn,$_POST['username']); 
$password=mysqli_real_escape_string($conn,$_POST['password']); 
 


if($_POST['h1']==1  && strlen($username)>5)
{ 

$sql="SELECT id FROM registration WHERE username='$username'";
$result=$conn->query($sql);
$row= $result->fetch_array();
$web_user=$row['id'];
$count=$result->num_rows;

if($web_user>0)
{
$_SESSION['web_user']=$web_user;

?>
<script>
    window.location = 'delete-my-account.php';
</script>
<?php
exit();
}
else
{
    $msg.="Username and Password Authentication Failed!";
}


}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<title>Delete My Account</title>
<meta name="keywords" content="Delete My Account"/>
<meta name="description" content="Delete My Account">
<meta name="robots" content="index,follow">

<link rel="stylesheet" href="dist/css/AdminLTE.min.css" />
<link rel="stylesheet" href="dist/css/skins/_all-skins.min.css" />
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
<style>
        .navbar.navbar-compact #pts-mainnav {
            left: 0;
            position: fixed;
            right: 0;
            top: 0;
        }
    </style>
</head>
<body class="responsive">



 




<div class="main-inner-right-block" style="margin:10px;display:inline-block;width:100%;">
 
<section class="content">
 
<div class="subtitle">
<div style="text-align:center;"><span style="font-weight:bold;font-size:22px;">Login</span> </div>
</div>
<div class="divider divider-sm"></div>
 
<div class="col-sm-3">

</div>
<div class="col-sm-6">
<div class="rect-nohover rect-equal-height" style="height: 418px;">
<div class="divider-xs"></div>
<div class="divider-sm"></div>
<div class="form-group">
 <form id="login_form" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
  <input name="h1" type="hidden" id="h1" value="1">
<div class="form-group">
<label class="control-label " for="username">User Name as Mobile</label>
                     <input type="text" name="username" id="username" class="form-control" >
</div>
                     
<input type="submit" class="btn btn-primary btn-md-sm btn-bottom" value="Login" />
</form>
</div>
</div>
<div class="divider divider-lg"></div>
</div>

 
</section>
</div>
 <?php include('include/footer.php');?>
              
              <?php include('include/sidebar.php');?>

                <!-- //end Footer -->
           
            
            
                        
<script src="js/jquery.min.js" type="text/javascript"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/application.js"></script>
<script src="js/addtocart.js"></script>  
 <script src="js/jquery-validate/jquery.validate.js" type="text/javascript"></script>
<script src="js/jquery-validate/additional_method_validate.js" type="text/javascript"></script>
 <script>
    function loginform(){ 
   
   $( "#login_form" ).validate({
         onfocusout: function(element) {
            this.element(element);
            //console.log('onfocusout fired');
         },
     
    rules: {
      
    username:  {required: true,  number:true, minlength:6, maxlength:18 }, 
    password: {minlength:3,maxlength:20,required: true }
   
    },
     
      messages: {
    
    
    username:   { required: "Enter a Mobile Number" , number: "Valid Numeric Mobile Number", minlength: "6 to 18 Digit Mobile Valid", maxlength: "6 to 18 Digit Mobile Valid"},
    password: {required: "Enter a Password" , minlength: "Password Minimum 3 Character", maxlength: "Password Maximum 20 Character"}

      },
   
  });
  

  
}

loginform();
    </script>
    <script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip(); 
});
</script>      
<?php include('include/footer-main-all-content-before-body.php');?>	
<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [{
    "@type": "ListItem",
    "position": 1,
    "item": {
      "@id": "<?=$relative_path;?>",
      "name": "Home"
    }
  },{
    "@type": "ListItem",
    "position": 2,
    "item": {
      "@id": "<?=$relative_path;?>login",
      "name": "Login"      
    }
  }]
}
</script>
</body>
</html>
