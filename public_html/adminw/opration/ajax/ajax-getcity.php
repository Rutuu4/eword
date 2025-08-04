<?php
include("../../database.php");

	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$stateid = $_POST['stateval'];
		if(!empty($stateid))
		{
			$selectState = "SELECT * FROM m_city WHERE `state_id` = '".$stateid."' and status = 1";
			$get = $conn->query($selectState);

			$output  = "<option value=''>Select City</option>";

			while ($row = $get->fetch_array()) {
				$output .= "<option value='".$row[0]."'>".$row[1]."</option>";
			}

			if($get->num_rows > 0){
				echo $output;
			}else{
				echo "<option value=''>No City Available</option>";
			}


		}
	}
?>