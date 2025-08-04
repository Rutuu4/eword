<?php
include("../../database.php");
$gstvalue = 18;

	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$qid = mysqli_real_escape_string($conn,$_POST['qid']);
		if(!empty($qid))
		{
			//DELETE QUOT VALUE AND UPDATE
			$getQuotVal = $conn->query("SELECT * FROM `quotation_data` WHERE id = ".$qid)->fetch_object();
			
			$registerid = $getQuotVal->register_id; 
			$qamount = $getQuotVal->q_amount;
			$qamountgst = $qamount * 18 / 100;

			$newnetamount = $getQuotVal->net_amount - $qamount;

			$total_amount1 = $getQuotVal->total_amount;

			$a = $qamount + $qamountgst;
			$total_amount = $total_amount1 - $a;


			$updateTotal = $conn->query("UPDATE `quotation_data` SET `net_amount`='$newnetamount',`total_amount`='$total_amount' WHERE register_id = ".$registerid);
			$updateTotal2 = $conn->query("UPDATE `register` SET `q_total_amount`='$total_amount' WHERE id = ".$registerid);

			$deleteQuotation = "DELETE FROM `quotation_data` WHERE id = ".$qid;
			$exe = $conn->query($deleteQuotation);

			if($updateTotal && $updateTotal2 && $exe){
				echo "TRUE";
			}

		}
	}
?>