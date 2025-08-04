<?php
include("../../database.php");
include("../../session.php");
	
	//User Id
	$uid = $usersessionid;

	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$id = $_POST['id'];

		if(!empty($id))
		{
			//check if exist record avail or not
			$chhk = $conn->query("SELECT * FROM `bank_visit_record` WHERE id = ".$id." ");

			if($chhk->num_rows > 0){

				$chkdata = $chhk->fetch_object();

				$op = '	<div class="row">
							<textarea class="form-control" rows="8">'.$chkdata->remarks.'</textarea>
						</div>';
				echo $op;
				
			}
		}
	}
?>
