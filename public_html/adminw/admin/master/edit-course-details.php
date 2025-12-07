<?php
include("../../database.php");

if ($_POST['h1'] == 1) {
  $id = mysqli_real_escape_string($conn, $_POST['id']);
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $status = mysqli_real_escape_string($conn, $_POST['status']);
  $create_datetime = date("Y-m-d H:i:s");

  $main_courses_id = mysqli_real_escape_string($conn, $_POST['main_courses_id']);
  $extra_course_id = mysqli_real_escape_string($conn, $_POST['extra_course_id']);
  $details = mysqli_real_escape_string($conn, $_POST['details']);

  $alias = preg_replace('!\s+!', '-', preg_replace("/[^A-Za-z0-9 \s+]/", ' ', trim(mysqli_real_escape_string($conn, $_POST['name']), " ")));
  $alias = substr(strip_tags($alias), 0, 50);
  $alias = strtolower($alias);
  $alias_iimmgg = preg_replace('!\s+!', '-', preg_replace("/[^A-Za-z0-9 \s+]/", ' ', substr(strip_tags($alias), 0, 30)));

  $qm1 = $_POST['qm1'];

  $idir = "courses-details/";
  $idirr = "../../../courses-details/";
  $userfile_extn[1] = strtolower(pathinfo($_FILES['pdf_file']['name'], PATHINFO_EXTENSION));

  if (($userfile_extn[1] != "pdf") && ($userfile_extn[1] != "PDF") && ($userfile_extn[1] != "")) {
    $msg = "Invalid file type. Please upload a PDF";
  } else {
    if (!$_FILES['pdf_file']['tmp_name'] == "") {
      $copy = copy($_FILES['pdf_file']['tmp_name'], $idirr . "" . $alias_iimmgg . "-1-" . time() . "." . $userfile_extn[1]);
      $pdf_file = $idir . "" . $alias_iimmgg . "-1-" . time() . "." . $userfile_extn[1];
      if ($pdf_file == $idir . ".") {
        $pdf_file = "";
      }
    }
  }
  if ($pdf_file == "") {
    $pdf_file = $qm1;
  }

  // 1. Update only course details (no video_link column anymore)
  $qury1 = "UPDATE courses_details 
          SET main_courses_id='$main_courses_id',
              extra_course_id='$extra_course_id',
              details='$details',
              pdf_file='$pdf_file',
              status='$status'
          WHERE id='$id'";
  $sq1 = $conn->query($qury1);

  if ($sq1) {
    // 2. Delete old video links for this course
    $conn->query("DELETE FROM course_videos WHERE course_details_id='$id'");

    // 3. Insert new video links
    if (!empty($_POST['video_link']) && is_array($_POST['video_link'])) {
      $display_order = 1;
      foreach ($_POST['video_link'] as $index => $link) {
        $name = mysqli_real_escape_string($conn, $_POST['video_name'][$index]);
        $order = isset($_POST['display_order'][$index]) ? (int)$_POST['display_order'][$index] : $index + 1;

        if (trim($link) != "" || trim($name) != "") {
          $link = mysqli_real_escape_string($conn, $link);
          $qury2 = "INSERT INTO course_videos (
                    main_course_id, 
                    course_details_id, 
                    video_name,
                    video_link, 
                    user_id, 
                    create_datetime, 
                    status, 
                    isExtra, 
                    display_order
                ) VALUES (
                    '$main_courses_id',
                    '$id',
                    '$name',
                    '$link',
                    '$login_id',
                    '$create_datetime',
                    '$status',
                    0,
                    $order
                )";
          $conn->query($qury2);
        }
      }
    }

    header("location:../manage-course.php");
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
      color: #555;
      background-color: #fff;
      background-image: none;
      border: 1px solid #ccc;
    }

    .select2-container {
      width: 100% !important;
    }
  </style>
</head>

<body class="<?= $bodyclass ?>">

  <?php

  if (!empty($_GET['key'])) {

    $id = base64_decode($_GET['key']);

    $qry = "SELECT * FROM courses_details WHERE id='$id'";

    $result = $conn->query($qry);
    $row = $result->fetch_array();
    $status = $row['status'];
    $qm1 = $row['pdf_file'];
    $main_courses_id = $row['main_courses_id'];
  }
  ?>


  <div class="wrapper">
    <?php include("../includes/header.php"); ?>
    <?php include("../includes/sidebar.php"); ?>

    <div class="content-wrapper">
      <section class="content-header">
        <h1>Update Course Details</h1>
      </section>

      <section class="content">

        <div class="box box-success">

          <div class="box-body">
            <form action="" method="POST" id="" class="form-horizontal" enctype="multipart/form-data">
              <input name="h1" type="hidden" id="h1" value="1" />
              <input name="id" type="hidden" id="id" value="<?= $id; ?>" />
              <input name="qm1" type="hidden" id="qm1" value="<?= $qm1 ?>" />


              <div class="form-group">
                <label for="usernamee" class="col-sm-2">Main Course :</label>
                <div class="col-sm-8">

                  <select name="main_courses_id" id="main_courses_id" class="form-control" required>
                    <option value=""> Select Main Course </option>
                    <?php
                    $sqlb = "SELECT id,name FROM m_main_courses where status=1 order by display_order";
                    $resultb = $conn->query($sqlb);
                    while ($rowb = $resultb->fetch_array()) {
                    ?>
                      <option <?php if ($row['main_courses_id'] == $rowb['id']) {
                                echo "selected";
                              } ?> value="<?= $rowb['id']; ?>"> <?= $rowb['name']; ?> </option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div id="showextra">

                <?php
                if ($row['extra_course_id'] > 0) { ?>


                  <div class="form-group">
                    <label for="usernamee" class="col-sm-2">Exrta Course :</label>
                    <div class="col-sm-8">

                      <select name="extra_course_id" id="extra_course_id" class="form-control select2" required>
                        <option value=""> Select Exrta Course </option>
                        <?php
                        $sqlb = "SELECT id,name FROM m_exrta_course where status=1 and main_courses_id='$main_courses_id'";
                        $resultb = $conn->query($sqlb);
                        while ($rowb = $resultb->fetch_array()) {
                        ?>
                          <option <?php if ($row['extra_course_id'] == $rowb['id']) {
                                    echo "selected";
                                  } ?> value="<?= $rowb['id']; ?>"> <?= $rowb['name']; ?> </option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                <?php } ?>
              </div>




              <div class="form-group">
                <div class="col-sm-12" id="video-links-wrapper" style="display:flex; flex-direction:column; gap:10px;">
                  <?php
                  $videoQry = $conn->query("SELECT * FROM course_videos WHERE course_details_id='$id' ORDER BY display_order");
                  $i = 1;
                  if ($videoQry->num_rows > 0) {
                    while ($v = $videoQry->fetch_assoc()) { ?>
                      <div class="video-field" style="display:flex; gap:10px; margin-bottom:5px; align-items:flex-end;">
                        <div style="flex:1;">
                          <label>Name <?= $i; ?> :</label>
                          <input type="text" class="form-control" name="video_name[]" value="<?= $v['video_name']; ?>" placeholder="Enter Name">
                        </div>
                        <div style="flex:1;">
                          <label>Video Link <?= $i; ?> :</label>
                          <input type="text" class="form-control" name="video_link[]" value="<?= $v['video_link']; ?>" placeholder="Enter Video Link">
                        </div>
                        <div style="flex:0.5;">
                          <label>Display Order <?= $i ?> :</label>
                          <input type="number" class="form-control" name="display_order[]" value="<?= $v['display_order']; ?>" placeholder="Order">
                        </div>
                        <?php if ($i > 1) { ?>
                          <button type="button" class="btn btn-danger remove-video">×</button>
                        <?php } ?>
                      </div>

                    <?php $i++;
                    }
                  } else { ?>
                    <div class="video-field" style="display:flex; gap:10px; margin-bottom:5px; align-items:flex-end;">
                      <div style="flex:1;">
                        <label>Name 1 :</label>
                        <input type="text" class="form-control" name="video_name[]" placeholder="Enter Name">
                      </div>
                      <div style="flex:1;">
                        <label>Video Link 1 :</label>
                        <input type="text" class="form-control" name="video_link[]" placeholder="Enter Video Link">
                      </div>
                      <div style="flex:0.5;">
                        <label>Display Order <?= $i ?> :</label>
                        <input type="number" class="form-control" name="display_order[]" value="<?= $v['display_order']; ?>" placeholder="Order">
                      </div>

                    </div>
                  <?php } ?>
                </div>
                <div class="col-sm-12" style="margin-top:10px;">
                  <button type="button" class="btn btn-success" onclick="addVideoField()">+</button>
                </div>
              </div>




              <div class="form-group">
                <label for="passwrod" class="col-sm-2">Description :</label>
                <div class="col-sm-8">

                  <textarea class="form-control textarea" placeholder="Description" style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="details" id="details"><?= $row['details']; ?></textarea>

                </div>
              </div>
              <?php if (!$qm1 == "") { ?>
                <div class="form-group">
                  <label for="name" class="col-sm-2">Current File</label>
                  <div class="col-sm-2">
                    <a href="../../<?php echo $qm1; ?>" target="_blank" class="btn btn-info" role="button">View Current File </a>
                  </div>
                  <div class="col-sm-1">
                    <a button class="btn btn-danger btn-sm" onClick="window.open('master/delete-course-pdf.php?id=<?= $id; ?>',   'win1','width=950, height=800, menubar=no ,scrollbars=yes,top=50,left=100')"><i class="fa fa-times"></i></button></a>
                  </div>

                </div>
              <?php  } ?>

              <div class="form-group">
                <label for="email" class="col-sm-2">PDF File:</label>
                <div class="col-sm-8">
                  <input name="pdf_file" id="pdf_file" type="file" class="file" multiple=true data-preview-file-type="any">
                </div>
              </div>



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
  <script>
    $(document).ready(function() {
      $("#main_courses_id").change(function() {
        var main_courses_id = $(this).find(":selected").val();
        if (main_courses_id != "") {
          $.ajax({
            type: "POST",
            url: "master/ajax/ajax-show-extra-course.php",
            data: {
              main_courses_id: main_courses_id
            },
            success: function(response) {
              $("#showextra").html(response);
            }
          });
        }
      });
    });
  </script>
  <script>
    // Start from how many already exist in DB
    let videoCounter = document.querySelectorAll("#video-links-wrapper .video-field").length;

    function addVideoField() {
      videoCounter++;

      let wrapper = document.getElementById("video-links-wrapper");
      let newField = document.createElement("div");
      newField.classList.add("video-field");
      newField.style.cssText = "display:flex; gap:10px; margin-bottom:5px; align-items:flex-end;";

      newField.innerHTML = `
    <div style="flex:1;">
      <label>Name ${videoCounter} :</label>
      <input type="text" class="form-control" name="video_name[]" placeholder="Enter Name">
    </div>
    <div style="flex:1;">
      <label>Video Link ${videoCounter} :</label>
      <input type="text" class="form-control" name="video_link[]" placeholder="Enter Video Link">
    </div>
    <div style="flex:0.5;">
      <label>Display Order ${videoCounter} :</label>
      <input type="number" class="form-control" name="display_order[]" value="${videoCounter}" placeholder="Order">
    </div>
    <button type="button" class="btn btn-danger remove-video">×</button>
  `;

      wrapper.appendChild(newField);
    }




    // Event delegation for remove buttons
    document.getElementById("video-links-wrapper").addEventListener("click", function(e) {
      if (e.target.classList.contains("remove-video")) {
        e.target.parentElement.remove();
        // NOTE: we don't decrement videoCounter → so new adds keep incrementing
      }
    });
  </script>
</body>

</html>