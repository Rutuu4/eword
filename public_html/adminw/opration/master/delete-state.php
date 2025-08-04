<?php 
	include("../../database.php");  

	$key = mysqli_real_escape_string($conn,$_POST['key']);

	if(!empty($key) && $_SERVER['REQUEST_METHOD'] == "POST"){

		$deleteSql = $conn->query("DELETE FROM `m_state` WHERE id = $key");

		if($deleteSql){
			echo "TRUE";
		}

	}

?>