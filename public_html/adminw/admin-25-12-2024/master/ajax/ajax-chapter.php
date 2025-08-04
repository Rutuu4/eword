<?php
include("../../../database.php");  

   if($_SERVER['REQUEST_METHOD'] == "POST")
   {
     	$book_id = $_POST['book_id'];

      if(!empty($book_id))
      {
         $selectState = "SELECT * FROM m_chapter WHERE book_id = '".$book_id."'";
         $get = $conn->query($selectState);

         $output  = "<option value=''>Select Chapter</option>";

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