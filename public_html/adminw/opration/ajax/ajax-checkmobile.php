<?php
include("../../database.php");

	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$myval = $_POST['myval'];
		if(!empty($myval))
		{
			$selectMob = "SELECT * FROM register WHERE `mobile1` = '".$myval."'";
			$get = $conn->query($selectMob);

			if($get->num_rows > 0){
				echo "This Mobile Number Is Already Exist.";
			}else{
				echo "";
			}


		}
	}
?>