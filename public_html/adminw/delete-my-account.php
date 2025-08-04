<?php include("include/database.php");


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
<div><span>Delete Account</span> </div>
</div>
<div class="divider divider-sm"></div>
 <div class="col-md-3"></div>
<div class="col-sm-6">
<div class="rect-nohover rect-equal-height" style="background:#fff;text-align: center;">
<h2>Delete Account</h2>
<div class="divider-xs"></div>
<p style="text-align: left;"><i class="fa fa-circle"></i> If you delete your account permanently from our eCommerce store, then all of your basic information will be deleted permanently. Your name, email ID, address, mobile number and all other basic information will be deleted from our site once you delete your account.</p>
<p style="font-size:20px;">Are You Sure You Wan't To Delete Account ?</p>
<div class="col-md-3"></div>
<div class="col-md-3">
<a href="delete-account-confrim.php" style="background: #dc1515;color: #fff;font-size: 22px;display: block;padding: 15px 0;border-radius: 4px;">Yes</a>
</div>
<div class="col-md-3">
<a href="delete-my-account-login.php" style="background: #0a9909;color: #fff;font-size: 22px;display: block;padding: 15px 0;border-radius: 4px;">No</a>
</div>
</div>
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
