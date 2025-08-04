<?php
   include('../../../database.php');

   $order_product_id=mysqli_real_escape_string($conn,$_POST['order_product_id']);

if($order_product_id>0)
{
		$qry_qty="SELECT product_id,quantity from product_order_trans where id='$order_product_id'";
		$result_qty = $conn->query($qry_qty);
		$row_qty = $result_qty->fetch_array();
		$product_id=$row_qty['product_id'];
		$adition_qty=$row_qty['quantity'];



		$outword_product_delete="DELETE from product_order_trans where id=$order_product_id";
   		$outword_deleted = $conn->query($outword_product_delete);


   		if($outword_deleted==TRUE)
   		{

   		   $qry_update="UPDATE product SET product_qty=product_qty+$adition_qty WHERE id ='$product_id'";
            $sq4=$conn->query($qry_update);	
   			echo json_encode(array('lg_valid'=>"TRUE"));
   		}
   		
    
}
 
?>