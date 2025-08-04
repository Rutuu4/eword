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

if(!isset($_SESSION['usersession']) && !isset($_SESSION['usertype'])){
	header("Location:logout.php");
	exit();
}
else
{

	$companyinfo="SELECT * FROM companyinfo WHERE id = 1";
    $result_companyinfo=$conn->query($companyinfo);
    $row_companyinfo=$result_companyinfo->fetch_array();
    $softtitle=$row_companyinfo['softtitle'];

    //SESSION VARIABLE

	$usertype=$_SESSION['usertype'];
	$usersessionid = $_SESSION['usersession'];
	$designaton_id = $_SESSION['designaton_id'];


	$qry_userinfo="SELECT * FROM user WHERE id = '$usersessionid'";
	$result_userinfo=$conn->query($qry_userinfo);
	$userinfo=$result_userinfo->fetch_array();
	$login_id=$userinfo['id'];
	$show_name=$userinfo['name'];
	$profile=$userinfo['profile'];	
	



}

//7. GST Charge navigation
$dis_gst_charge_apply=1;                                                           // apply GST charge 1-Active , 0- None (Deactive)
$dis_gst_charge_by_default_fixed=2;                                                //by default fixed rate 1- Active fixed rate, 2-gst apply by seperate product
$dis_gst_charge_by_cgst=2.5;     $dis_gst_charge_by_sgst=2.5;                      //cgst and sgst value put (if default fixed rate)
$define_gst_product_hsncode_module=1;                                              //if this module active ($dis_gst_charge_apply=1 and $dis_gst_charge_by_default_fixed=2)
$define_gst_type_by_stateid_default=8;                                               //gst state by default id from m_state which decide igst or csgst apply
$dis_scheme_discount=1;
$define_reseller_discount_activation=1;

$web_url='https://eworldeducation.in/';
?>