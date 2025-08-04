<?php 
	include("../../database.php"); 

	$key = mysqli_real_escape_string($conn,$_POST['key']);

	if(!empty($key) && $_SERVER['REQUEST_METHOD'] == "POST"){

		$deleteSql = $conn->query("DELETE FROM `register1` WHERE id = $key");

		if($deleteSql){
			echo "TRUE";
		}

	}

?>