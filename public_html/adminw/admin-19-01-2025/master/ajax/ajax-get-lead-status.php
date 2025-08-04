<?php
include("../../../database.php");  

   if($_SERVER['REQUEST_METHOD'] == "POST")
   {
     	$customer_id = $_POST['customer_id'];
       $source_id = $_POST['source_id'];
      

      if(!empty($customer_id))
      {
         $selectState="SELECT * FROM m_crm_lead_status WHERE status=1 and (team_id = '".$customer_id."' || team_id = '".$source_id."') && (team_id = '".$customer_id."' || team_id = '".$source_id."')";
         $get = $conn->query($selectState);

         $output  = "<option value=''>Select Lead Status</option>";

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