<?php 
include("include/database.php");
$errors = '';


if(empty($_POST['name'])  || empty($_POST['mobile'])  ||  empty($_POST['email'])  ||  empty($_POST['details'])){
    $errors .= "\n Error: all fields are required";
}

 
$name = $_POST['name']; 

$mobile = $_POST['mobile'];
 
$email = $_POST['email'];
 $details = $_POST['details'];
$datee=date("Y-m-d , H:i:s");
$userip=$_SERVER['REMOTE_ADDR'];
$date=date("Y/m/d ");

 

	$to = 'info@eworldeducation.in';  
	$email_subject = "New Inquiry In Website from: $name";
	$email_body = "You have received a new message. ".
	" Here are the details: \n Name: $name  \n Mobile: $mobile \n Email: $email \n Details: $details  ";
	$headers = 'From: inquiry@eworldeducation.in' . "\r\n" .
    'Reply-To: inquiry@eworldeducation.in' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
	
	mail($to,$email_subject,$email_body,$headers);
	//redirect to the 'thank you' page
	header('Location:thanks.php');
	exit();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> 
<html>
<head>
	<title>Contact form handler</title>
</head>

<body>
<!-- This page is displayed only if there is some error -->
<?php
echo nl2br($errors);
?>


</body>
</html>