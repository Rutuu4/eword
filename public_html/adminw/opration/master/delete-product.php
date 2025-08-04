<?php 
	include("../../database.php");  

	$key = mysqli_real_escape_string($conn,$_POST['key']);

	if(!empty($key) && $_SERVER['REQUEST_METHOD'] == "POST"){

		$getdata = $conn->query("SELECT * FROM `product` WHERE id = $key")->fetch_object();

		 $path=$getdata->product_img;

		 unlink("../../$path");
		$deleteSql = $conn->query("DELETE FROM `product` WHERE id = $key");

		if($deleteSql){
			echo "TRUE";
		}

	}

?>