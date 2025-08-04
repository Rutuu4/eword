<?php
include("../../../database.php");  

   if($_SERVER['REQUEST_METHOD'] == "POST")
   {
     	$customer_id = $_POST['customer_id'];

      if(!empty($customer_id))
      {
         $selectState = "SELECT * FROM register WHERE `center` = '".$customer_id."'";
         $get = $conn->query($selectState);

         $output  = "<option value=''>Select Lead Assign</option>";

         while ($row = $get->fetch_array()) {
            $output .= "<option value='".$row['id']."'>".$row['name']."</option>";
         }

         if($get->num_rows > 0){
            echo $output;
         }else{
            echo "<option value=''>No Value Available</option>";
         }


      }
   }
?>