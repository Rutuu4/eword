<?php
   include('../../../database.php');

   $po_product_id=mysqli_real_escape_string($conn,$_POST['po_product_id']);

if($po_product_id>0)
{

		$inword_product_delete="DELETE from po_product where id=$po_product_id";
   		$inword_deleted = $conn->query($inword_product_delete);
   		echo json_encode(array('lg_valid'=>"TRUE"));
    
}

?>