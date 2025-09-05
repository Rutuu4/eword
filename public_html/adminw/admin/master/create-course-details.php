<?php
include("../../database.php");

if ($_POST['h1'] == 1) {

  $video_names = $_POST['video_name'];
  $video_links = $_POST['video_link']; // this is an array now
  $video_display_orders = $_POST['video_display_order'];
  $video_link = json_encode($video_links, JSON_UNESCAPED_SLASHES);

  $status = mysqli_real_escape_string($conn, $_POST['status']);
  $create_datetime = date("Y-m-d H:i:s");

  $main_courses_id = mysqli_real_escape_string($conn, $_POST['main_courses_id']);
  $extra_course_id = mysqli_real_escape_string($conn, $_POST['extra_course_id']);
  $details = mysqli_real_escape_string($conn, $_POST['details']);
  $status = mysqli_real_escape_string($conn, $_POST['status']);



  $alias = preg_replace('!\s+!', '-', preg_replace("/[^A-Za-z0-9 \s+]/", ' ', trim(mysqli_real_escape_string($conn, $_POST['name']), " ")));
  $alias = substr(strip_tags($alias), 0, 50);
  $alias = strtolower($alias);
  $alias_iimmgg = preg_replace('!\s+!', '-', preg_replace("/[^A-Za-z0-9 \s+]/", ' ', substr(strip_tags($alias), 0, 30)));

  $qm1 = $_POST['qm1'];

  $idir = "courses-details/";
  $idirr = "../../../courses-details/";

  //$userfile_extn=explode(".",strtolower($_FILES['pdf_file']['name']));

  $userfile_extn[1] = strtolower(pathinfo($_FILES['pdf_file']['name'], PATHINFO_EXTENSION));

  //image validation jpeg,png images
  if ($userfile_extn[1] == "") {
    $msg = "Image File Extention Invalid , Please Upload Valid Image";
  } else {

    //copy to images to folder
    if (!$_FILES['pdf_file']['tmp_name'] == "") {
      $copy = copy($_FILES['pdf_file']['tmp_name'], $idirr . "" . $alias_iimmgg . "-1-" . time() . "." . $userfile_extn[1]);
      $pdf_file = $idir . "" . $alias_iimmgg . "-1-" . time() . "." . $userfile_extn[1];
      if ($pdf_file == $idir . ".") {
        $pdf_file = "";
      }
    }
  }





  $qury1 = "INSERT INTO courses_details(
              user_id, 
              create_datetime, 
              main_courses_id, 
              extra_course_id, 
              name, 
              details, 
              pdf_file, 
              status
          ) 
          VALUES (
              '$login_id',
              '$create_datetime',
              '$main_courses_id',
              '$extra_course_id',
              '$name',
              '$details',
              '$pdf_file',
              '$status'
          )";

  if ($conn->query($qury1)) {
    $course_details_id = $conn->insert_id; // get last inserted ID

    // Insert multiple video links into course_videos
    if (!empty($video_links)) {
      $display_order = 1;
      foreach ($video_links as $key => $link) {
        $link = trim($link);
        $vname = mysqli_real_escape_string($conn, $video_names[$key]);
        $vorder = (int)$video_display_orders[$key];

        if ($link != "" && $vname != "") {
          $link = mysqli_real_escape_string($conn, $link);

          $qury2 = "INSERT INTO course_videos (
                    main_course_id, 
                    course_details_id, 
                    video_link, 
                    video_name,
                    user_id, 
                    create_datetime, 
                    status, 
                    isExtra, 
                    display_order
                ) 
                VALUES (
                    '$main_courses_id',
                    '$course_details_id',
                    '$link',
                    '$vname',
                    '$login_id',
                    '$create_datetime',
                    '$status',
                    0,
                    $vorder
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

  <div class="wrapper">
    <?php include("../includes/header.php"); ?>
    <?php include("../includes/sidebar.php"); ?>

    <div class="content-wrapper">
      <section class="content-header">
        <h1>Add Course Details</h1>
      </section>

      <section class="content">

        <div class="box box-success">

          <div class="box-body">
            <form action="" method="POST" id="" class="form-horizontal" enctype="multipart/form-data">
              <input name="h1" type="hidden" id="h1" value="1" />


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
                      <option value="<?= $rowb['id']; ?>"> <?= $rowb['name']; ?> </option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div id="showextra">

              </div>

              <div class="form-group">
                <div class="col-sm-12" id="video_links_wrapper"> <!-- ✅ ADD THIS WRAPPER -->

                  <!-- First Row -->
                  <div class="video-link-group mb-2">
                    <div class="row">
                      <div class="col-sm-4">
                        <label>Name 1 :</label>
                        <input type="text" class="form-control" name="video_name[]" placeholder="Enter Name">
                      </div>
                      <div class="col-sm-4">
                        <label>Video Link 1 :</label>
                        <input type="text" class="form-control" name="video_link[]" placeholder="Enter Video Link">
                      </div>
                      <div class="col-sm-2">
                        <label>Display Order 1 :</label>
                        <input type="number" class="form-control" name="video_display_order[]" placeholder="1" value="1" min="1">
                      </div>
                      <div class="col-sm-2 d-flex align-items-end">
                        <button type="button" class="btn btn-success add-video-link">+</button>
                      </div>
                    </div>
                  </div>

                </div>
              </div>






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
                      <input name="status" type="radio" value="1" checked="checked" />
                      Active</label>
                  </div>
                  <div class="col-sm-6 col-xs-6">
                    <label>
                      <input name="status" type="radio" value="0" />
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
    $(document).ready(function() {
      let index = 1;

      // Add new row
      $(document).on('click', '.add-video-link', function() {
        index++;
        let newGroup = `
  <div class="video-link-group mb-2">
    <div class="row">
      <div class="col-sm-4">
        <label>Name ${index} :</label>
        <input type="text" class="form-control" name="video_name[]" placeholder="Enter Name">
      </div>
      <div class="col-sm-4">
        <label>Video Link ${index} :</label>
        <input type="text" class="form-control" name="video_link[]" placeholder="Enter Video Link">
      </div>
      <div class="col-sm-2">
        <label>Display Order ${index} :</label>
        <input type="number" class="form-control" name="video_display_order[]" value="${index}" min="1">
      </div>
      <div class="col-sm-2 d-flex align-items-end">
        <button type="button" class="btn btn-danger remove-video-link">-</button>
      </div>
    </div>
  </div>`;

        $("#video_links_wrapper").append(newGroup);
      });

      // Remove row + relabel
      // Remove row + relabel
      $(document).on('click', '.remove-video-link', function() {
        $(this).closest('.video-link-group').remove();

        // Re-label all rows
        index = 0;
        $('#video_links_wrapper .video-link-group').each(function() {
          index++;
          $(this).find('label').eq(0).text("Name " + index + " :");
          $(this).find('label').eq(1).text("Video Link " + index + " :");
          $(this).find('label').eq(2).text("Display Order " + index + " :");
          $(this).find('input[name="video_display_order[]"]').val(index); // optionally reset value
        });
      });

    });
  </script>


</body>

</html>