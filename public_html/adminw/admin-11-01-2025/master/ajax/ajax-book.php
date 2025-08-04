<?php
include("../../../database.php");  

   if($_SERVER['REQUEST_METHOD'] == "POST")
   {
     	$book_type_id = $_POST['book_type_id'];

      if(!empty($book_type_id))
      {
         $selectState = "SELECT * FROM m_book WHERE book_type_id = '".$book_type_id."'";
         $get = $conn->query($selectState);

         $output  = "<option value=''>Select Book</option>";

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