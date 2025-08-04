<?php
include("../../../database.php");  

   if($_SERVER['REQUEST_METHOD'] == "POST")
   {
     	$extra_course_id = $_POST['extra_course_id'];
      if($extra_course_id>0)
      { ?>

        <div class="form-group">
    <label for="usernamee" class="col-sm-2">Select Course :</label>
    <div class="col-sm-8 row">
        <?php 
            $sql_fv = "SELECT id, name FROM courses_details WHERE status=1 AND extra_course_id='$extra_course_id'";
            $result_fv = $conn->query($sql_fv);
            while ($row_fv = $result_fv->fetch_array()) {
        ?>
            <div class="col-sm-4">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="course_details_ids[]" value="<?=$row_fv['id'];?>"> <?=$row_fv['name'];?>
                    </label>
                </div>
            </div>
        <?php } ?>
    </div>
</div>







     <?php }

     
   }

?>