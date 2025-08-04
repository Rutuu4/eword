<?php
   include('../../../database.php');

   $inword_product_id=mysqli_real_escape_string($conn,$_POST['inword_product_id']);

if($inword_product_id>0)
{

		$qry_qty="SELECT product_id,quantity from inword_product where id='$inword_product_id'";
		$result_qty = $conn->query($qry_qty);
		$row_qty = $result_qty->fetch_array();
		$product_id=$row_qty['product_id'];
		$substiction_qty=$row_qty['quantity'];
		$inword_product_delete="DELETE from inword_product where id=$inword_product_id";
   		$inword_deleted = $conn->query($inword_product_delete);
   		if($inword_deleted==TRUE)
   		{
   			$qry_update="UPDATE product SET product_qty=product_qty-$substiction_qty WHERE id ='$product_id'";
            $sq4=$conn->query($qry_update); 
   				
			 echo json_encode(array('lg_valid'=>"TRUE"));

   		}

    
}

?>