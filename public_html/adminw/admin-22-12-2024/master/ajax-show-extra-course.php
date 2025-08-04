<?php
include("../../../database.php");  

   if($_SERVER['REQUEST_METHOD'] == "POST")
   {
     	$language_id = $_POST['language_id'];

      if(!empty($language_id))
      {
         $selectState = "SELECT * FROM m_book_type WHERE language_id = '".$language_id."'";
         $get = $conn->query($selectState);

         $output  = "<option value=''>Select Book Type</option>";

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