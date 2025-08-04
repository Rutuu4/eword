<?php
include("../../../database.php");  

   if($_SERVER['REQUEST_METHOD'] == "POST")
   {
     	$main_courses_id = $_POST['main_courses_id'];
       $sqlb="SELECT id,name FROM m_exrta_course where status=1 and main_courses_id='$main_courses_id'";
       $resultb = $conn->query($sqlb);
       $rowcount=mysqli_num_rows($resultb);
       if($rowcount>0)
       {
      ?>

         <div class="form-group">
                      <label for="usernamee" class="col-sm-2">Sub Course :</label>
                      <div class="col-sm-7">

                           <select name="extra_course_id"  id="extra_course_id"  class="form-control select2"  required>
                            <option value=""> Select Sub Course </option>
                            <?php 
                            $sqlb="SELECT id,name FROM m_exrta_course where status=1 and main_courses_id='$main_courses_id'";
                            $resultb = $conn->query($sqlb);
                            while($rowb = $resultb->fetch_array())
                            {
                              ?>
                              <option value="<?=$rowb['id'];?>"> <?=$rowb['name'];?> </option>
                              <?php } ?>
                                  </select>
                      </div>
                    </div>




     <?php }
   }
?>