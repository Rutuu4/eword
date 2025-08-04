<?php
include("../../../database.php");  

   if($_SERVER['REQUEST_METHOD'] == "POST")
   {
     	$main_courses_id = $_POST['main_courses_id'];

      if(!empty($main_courses_id))
      {
         $selectState = "SELECT id,name FROM  courses_details WHERE main_courses_id = '".$main_courses_id."'";
         $get = $conn->query($selectState);

         $output  = "<option value=''>Select Course</option>";

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