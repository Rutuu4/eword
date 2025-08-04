<?php
include("../../database.php");
session_start();
$designation_type=$_SESSION['desid']; 



	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$desid = $_POST['desval'];
		
		if(!empty($desid))
		{
			$_SESSION['desid'] = "$desid";
			echo$selectState = "SELECT * FROM register1 WHERE manager_id !=0  and status = 1 and $desid !=1 and designation_type=1";
			$get = $conn->query($selectState);

			$output  = "<option value=''>Select manager</option>";

			while ($row = $get->fetch_array()) {
				$output .= "<option value='".$row[0]."'>Name:  ".$row[2]."   &  Mobile: ".$row[4]."</option>";
			}

			if($get->num_rows > 0){
				echo $output;
			}else{
				echo "<option value=''>No Manager Available</option>";
			}


		}
		$manager_id = $_POST['managerval'];
		if(!empty($manager_id))
		{

			$select1 = "SELECT * FROM register1 where sales_id !=0 and status = 1 and manager_id='$manager_id'and designation_type !='$designation_type' and designation_type=2 ";
			$get1 = $conn->query($select1);

			$output  = "<option value=''>Select Sales manager</option>";

			while ($row1 = $get1->fetch_array()) {
				$output .= "<option value='".$row1[0]."'>Name:  ".$row1[2]."   &  Mobile: ".$row1[4]."</option>";
			}

			if($get1->num_rows > 0){
				echo $output;
			}else{
				echo "<option value=''>No Salse Available</option>";
			}

		}
		$salse_id = $_POST['salseval'];
		if(!empty($salse_id))
		{
			$select1 = "SELECT * FROM register1 where stockist_id !=0 and sales_id='$salse_id' and designation_type !='$designation_type' ";
			$get1 = $conn->query($select1);

			$output  = "<option value=''>Select Stockist</option>";

			while ($row1 = $get1->fetch_array()) {
				$output .= "<option value='".$row1[0]."'>Name:  ".$row1[2]."   &  Mobile: ".$row1[4]."</option>";
			}

			if($get1->num_rows > 0){
				echo $output;
			}else{
				echo "<option value=''>No Stockist Available</option>";
			}

		}

	}
?>