<?php
include("../../database.php");

if ($_POST['h1'] == 1) {

  $id = mysqli_real_escape_string($conn, $_POST['id']);
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $name = str_replace("'", "", $name);
  $name = str_replace('"', '', $name);
  $name = preg_replace('/^[^a-zA-Z0-9]+/', '', $name);
  $video_link = mysqli_real_escape_string($conn, $_POST['video_link']);
  $status = mysqli_real_escape_string($conn, $_POST['status']);
  $create_datetime = date("Y-m-d H:i:s");

  $college_university_type_id = mysqli_real_escape_string($conn, $_POST['college_university_type_id']);
  $city_id = mysqli_real_escape_string($conn, $_POST['city_id']);
  $website_link = mysqli_real_escape_string($conn, $_POST['website_link']);
  $status = mysqli_real_escape_string($conn, $_POST['status']);
  $course_ids = implode(",", $_POST['course_ids']);
  $currentPage = mysqli_real_escape_string($conn, $_POST['current_page']);

  if (!empty($course_ids)) {

    $course_name_list = array();
    $qry_course = "SELECT name from courses_details where id IN($course_ids)";
    $result_course = $conn->query($qry_course);
    while ($row_course = $result_course->fetch_array()) {
      $course_name_list[] = trim($row_course['name']);
    }

    if (!empty($course_name_list)) {
      $course_name_list = implode(",", $course_name_list);
    }
  }



  $qury1 = "UPDATE college_university_details SET user_id='$login_id',college_university_type_id='$college_university_type_id',name='$name',city_id='$city_id',website_link='$website_link',course_ids='$course_ids',status='$status',course_name_list='$course_name_list' where id='$id'";
  $sq1 = $conn->query($qury1);

  if (mysqli_affected_rows($conn) >= 1) {
    header("location:../manage-college-university.php?page=" . $currentPage);
  }
}

?>
<!DOCTYPE html>
<html>

<head>
  <base href="<?= $base_path ?>">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= $softtitle ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <?php include("../includes/css-scripts.php"); ?>
  <style>
    .error {
      color: red;
    }

    .control-label {
      text-align: left !important;
    }

    .form-control {
      display: block;
      width: 100%;
      height: 34px;
      padding: 6px 12px;
      font-size: 14px;
      line-height: 1.42857143;
      background-color: #fff;
      background-image: none;
      border: 1px solid #ccc;
    }

    .select2-container {
      width: 100% !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
      background-color: #3c8dbc;
      border-color: #367fa9;
      padding: 1px 10px;
      color: #ffffff;
    }

    .select2-container--default .select2-search--inline .select2-search__field {
      width: 100% !important;
    }

    .select2-container--default.select2-container--open {
      width: 100% !important;
    }

    .select2-container {
      width: 100% !important;
    }

    sup {
      color: #CC3300;
      font-size: 14px;
      top: -4px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
      color: #ffffff;
      cursor: pointer;
      display: inline-block;
      font-weight: bold;
      margin-right: 2px;
    }
  </style>
</head>

<body class="<?= $bodyclass ?>">

  <?php

  if (!empty($_GET['key'])) {

    $id = base64_decode($_GET['key']);
    $page = isset($_GET['page']) ? $_GET['page'] : 0;

    $qry = "SELECT * FROM college_university_details WHERE id='$id'";

    $result = $conn->query($qry);
    $row = $result->fetch_array();
    $status = $row['status'];
    $course_ids = $row['course_ids'];
  }
  ?>


  <div class="wrapper">
    <?php include("../includes/header.php"); ?>
    <?php include("../includes/sidebar.php"); ?>

    <div class="content-wrapper">
      <section class="content-header">
        <h1>Update College University </h1>
      </section>

      <section class="content">

        <div class="box box-success">

          <div class="box-body">
            <form action="" method="POST" id="" class="form-horizontal" enctype="multipart/form-data">
              <input name="h1" type="hidden" id="h1" value="1" />
              <input name="id" type="hidden" id="id" value="<?= $id; ?>" />
              <input name="qm1" type="hidden" id="qm1" value="<?= $qm1 ?>" />
              <input name="current_page" type="hidden" id="current_page" value="<?= $page ?>" />


              <div class="form-group">
                <label for="usernamee" class="col-sm-2">Type :</label>
                <div class="col-sm-8">

                  <select name="college_university_type_id" id="college_university_type_id" class="form-control" required>
                    <option value=""> Select Type </option>
                    <?php
                    $sqlb = "SELECT id,name FROM m_college_university_type where status=1";
                    $resultb = $conn->query($sqlb);
                    while ($rowb = $resultb->fetch_array()) {
                    ?>
                      <option <?php if ($row['college_university_type_id'] == $rowb['id']) {
                                echo "selected";
                              } ?> value="<?= $rowb['id']; ?>"> <?= $rowb['name']; ?> </option>
                    <?php } ?>
                  </select>
                </div>
              </div>


              <div class="form-group">
                <label class="control-label col-sm-2">Name :</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name " value="<?= $row['name']; ?>" required>
                </div>
              </div>

              <div class="form-group">
                <label for="usernamee" class="col-sm-2">City :</label>
                <div class="col-sm-8">

                  <select name="city_id" id="city_id" class="form-control" required>
                    <option value=""> Select City </option>
                    <?php
                    $sqlb = "SELECT id,name FROM m_city where status=1 order by name ASC";
                    $resultb = $conn->query($sqlb);
                    while ($rowb = $resultb->fetch_array()) {
                    ?>
                      <option <?php if ($row['city_id'] == $rowb['id']) {
                                echo "selected";
                              } ?> value="<?= $rowb['id']; ?>"> <?= $rowb['name']; ?> </option>
                    <?php } ?>
                  </select>
                </div>
              </div>


              <div class="form-group">
                <label class="control-label col-sm-2">Website Link :</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="website_link" id="website_link" placeholder="Enter Website" value="<?= $row['website_link']; ?>">
                </div>
              </div>
              <?php
              $qry_chk1 = "SELECT id,name from m_main_courses where status=1 order by display_order ASC";
              $result_chk1 = $conn->query($qry_chk1);
              while ($row_chk1 = $result_chk1->fetch_array()) {
                $main_courses_id = $row_chk1['id'];

              ?>


                <div class="form-group">
                  <label class="control-label col-sm-3" style="font-size: 18px;color: red;"><?= $row_chk1['name'] ?> </label>
                </div>


                <?php
                $qry_chk = "SELECT id,name from m_exrta_course where status=1 and main_courses_id='$main_courses_id' order by id ASC";
                $result_chk = $conn->query($qry_chk);
                while ($row_chk = $result_chk->fetch_array()) {
                  $extra_course_id = $row_chk['id'];

                ?>
                  <hr>
                  <div class="form-group">
                    <label for="usernamee" class="col-sm-3"><?= $row_chk['name'] ?> :</label>


                    <?php
                    $sql_fv = "SELECT id, name FROM courses_details WHERE status=1 AND extra_course_id='$extra_course_id'";
                    $result_fv = $conn->query($sql_fv);
                    while ($row_fv = $result_fv->fetch_array()) {
                    ?>
                      <div class="col-sm-3">
                        <div class="checkbox">
                          <label style="font-size:10px">
                            <input type="checkbox" name="course_ids[]" <?php $fam_val = explode(",", $course_ids);
                                                                        for ($fs = 0; $fs < count($fam_val); $fs++) {
                                                                          if ($row_fv['id'] == $fam_val[$fs]) { ?> checked <?php }
                                                                                                                        } ?> value="<?= $row_fv['id']; ?>"> <?= $row_fv['name']; ?>
                          </label>
                        </div>
                      </div>
                    <?php } ?>
                  </div>




              <?php }
              } ?>


              <div class="form-group">
                <label class="col-sm-2">Status :</label>
                <div class="col-sm-6">
                  <div class="col-sm-3 col-xs-6">
                    <label>
                      <input name="status" type="radio" value="1" <?php if ($status == 1) {  ?>checked="checked" <?php } ?> />
                      Active</label>
                  </div>
                  <div class="col-sm-6 col-xs-6">
                    <label>
                      <input name="status" type="radio" value="0" <?php if ($status == 0) {  ?>checked="checked" <?php } ?> />
                      Deactive</label>
                  </div>
                </div>
              </div>


              <div class="col-md-12" align="right">
                <button type="submit" class="btn btn-success ">Save changes</button>
              </div>

            </form>
          </div>
        </div>
      </section>
    </div>
    <?php include("../includes/footer.php"); ?>
  </div>
  <?php include("../includes/js-scripts.php"); ?>
  <script>
    $(document).ready(function() {
      //Select2
      $(".select2").select2();
    });
  </script>
  <script>
    $(document).ready(function() {
      //Select2
      $(".select2").select2();
      //bootstrap WYSIHTML5 - text editor
      $(".textarea").wysihtml5();
    });
  </script>


</body>

</html>