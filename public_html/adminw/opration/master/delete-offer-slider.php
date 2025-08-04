<?php 
	include("../../database.php"); 

	$key = mysqli_real_escape_string($conn,$_POST['key']);

	if(!empty($key) && $_SERVER['REQUEST_METHOD'] == "POST"){


		$getdata = $conn->query("SELECT * FROM `offer_slider` WHERE id = $key")->fetch_object();

		 $path=$getdata->image;

		 unlink("../../offer-img/$path");

		$deleteSql = $conn->query("DELETE FROM `offer_slider` WHERE id = $key");

		if($deleteSql){
			echo "TRUE";
		}

	}

?>