<?php
include("../../../database.php");  

   if($_SERVER['REQUEST_METHOD'] == "POST")
   {
     	$main_courses_id = $_POST['main_courses_id'];
      if($main_courses_id>0)
      { ?>

        <div class="form-group">
    <label for="usernamee" class="col-sm-2">Select Course :</label>
    <div class="col-sm-8 row">
        <?php 
            $sql_fv = "SELECT id, name FROM courses_details WHERE status=1 AND main_courses_id='$main_courses_id'";
            $result_fv = $conn->query($sql_fv);
            while ($row_fv = $result_fv->fetch_array()) {
        ?>
            <div class="col-sm-4">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="course_ids[]" value="<?=$row_fv['id'];?>"> <?=$row_fv['name'];?>
                    </label>
                </div>
            </div>
        <?php } ?>
    </div>
</div>







     <?php }

     
   }

?>