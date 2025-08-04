<?php 

$host="localhost"; // Host name 
$username="root"; // Mysql username 
$password=""; // Mysql password 
$db_name="u260268217_eworld"; // Database name 


$base_path=dirname($_SERVER['PHP_SELF']);
// if($base_path!="/"){  $base_path=$base_path."/"; }
$relative_path="http://".$_SERVER['SERVER_NAME'].$base_path;

$conn = new mysqli($host, $username, $password, $db_name);
//FOR THEAM
$bodyclass = "sidebar-mini wysihtml5-supported skin-green-light";
//$bodyclass = "sidebar-mini wysihtml5-supported skin-red";

date_default_timezone_set('Asia/Kolkata');

error_reporting(0);

session_start();


$define_company_name='GCC';


?>
