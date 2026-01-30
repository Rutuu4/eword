<?php
include("../../database.php");

if ($_POST['h1'] == 1) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    // Multiple courses handling
    $courses_ids = $_POST['courses_id'] ?? [];
    $courses_ids_str = implode(',', $courses_ids); // Converts array → comma string


    // Handle empty or null course_id
    $establishment_year = mysqli_real_escape_string($conn, $_POST['establishment_year']);
    $city_id = mysqli_real_escape_string($conn, $_POST['city_id']);
    $near_by_area = mysqli_real_escape_string($conn, $_POST['near_by_area']);
    $website_link = mysqli_real_escape_string($conn, $_POST['website_link']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    // $dob = mysqli_real_escape_string($conn, $_POST['dob'] ?? '');

    $is_mou = isset($_POST['is_mou']) ? 1 : 0;
    $whatsapp_number = $is_mou ? mysqli_real_escape_string($conn, $_POST['whatsapp_number']) : '';

    // Handle Country, Visa Type, Exam Type (mapping tables)
    $country_ids = $_POST['country_id'] ?? [];
    $visa_type_ids = $_POST['visa_type_id'] ?? [];
    $exam_type_ids = $_POST['exam_type_id'] ?? [];

    // Construct the Update Query
    $update_query = "UPDATE foreign_education 
                 SET consultancy_name='$name', 
                     establishment_year='$establishment_year',
                    --  dob='$dob',
                      course_ids='$courses_ids_str',
                     city='$city_id',
                     nearby_area='$near_by_area',
                     institute_url='$website_link',
                     mou_present='$is_mou',
                     whats_app_number='$whatsapp_number',
                     status='$status'
                 WHERE id='$id'";



    // Only update course_id if it is provided (not null or empty)
    // After executing $update_query
    $conn->query($update_query);


    // Update country mappings
    $conn->query("DELETE FROM foreign_education_countries WHERE foreign_education_id = '$id'");
    foreach ($country_ids as $country_id) {
        $country_id = mysqli_real_escape_string($conn, $country_id);
        $conn->query("INSERT INTO foreign_education_countries(foreign_education_id, country_id) VALUES('$id', '$country_id')");
    }

    // Update visa mappings
    $conn->query("DELETE FROM foreign_education_visa_types WHERE foreign_education_id = '$id'");
    foreach ($visa_type_ids as $visa_id) {
        $visa_id = mysqli_real_escape_string($conn, $visa_id);
        $conn->query("INSERT INTO foreign_education_visa_types(foreign_education_id, visa_type_id) VALUES('$id', '$visa_id')");
    }

    // Update exam mappings
    $conn->query("DELETE FROM foreign_education_exam_types WHERE foreign_education_id = '$id'");
    foreach ($exam_type_ids as $exam_id) {
        $exam_id = mysqli_real_escape_string($conn, $exam_id);
        $conn->query("INSERT INTO foreign_education_exam_types(foreign_education_id, exam_type_id) VALUES('$id', '$exam_id')");
    }

    header("location:../manage-foreign-education.php");
    exit;
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

        // Fetch main foreign_education record
        $qry = "SELECT * FROM foreign_education WHERE id='$id'";
        $result = $conn->query($qry);
        $row = $result->fetch_array();

        $status = $row['status'];
        $qm1 = $row['pdf_file'];
        // Fetch selected course IDs
        $selected_course_ids = !empty($row['course_ids']) ? explode(',', $row['course_ids']) : [];


        // Fetch selected country IDs
        $selected_country_ids = [];
        $res = $conn->query("SELECT country_id FROM foreign_education_countries WHERE foreign_education_id = '$id'");
        while ($r = $res->fetch_assoc()) {
            $selected_country_ids[] = $r['country_id'];
        }

        // Fetch selected visa type IDs
        $selected_visa_ids = [];
        $res = $conn->query("SELECT visa_type_id FROM foreign_education_visa_types WHERE foreign_education_id = '$id'");
        while ($r = $res->fetch_assoc()) {
            $selected_visa_ids[] = $r['visa_type_id'];
        }

        // Fetch selected exam type IDs
        $selected_exam_ids = [];
        $res = $conn->query("SELECT exam_type_id FROM foreign_education_exam_types WHERE foreign_education_id = '$id'");
        while ($r = $res->fetch_assoc()) {
            $selected_exam_ids[] = $r['exam_type_id'];
        }
    }

    ?>


    <div class="wrapper">
        <?php include("../includes/header.php"); ?>
        <?php include("../includes/sidebar.php"); ?>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>Update Foreign Education</h1>
            </section>

            <section class="content">

                <div class="box box-success">

                    <div class="box-body">
                        <form action="" method="POST" id="" class="form-horizontal" enctype="multipart/form-data">
                            <input name="h1" type="hidden" id="h1" value="1" />
                            <input name="id" type="hidden" id="id" value="<?= $id; ?>" />
                            <input name="qm1" type="hidden" id="qm1" value="<?= $qm1 ?>" />
                            <div class="form-group">
                                <label class="control-label col-sm-2">Name :</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="name" id="name"
                                        placeholder="Enter Name " value="<?= $row['consultancy_name']; ?>" required>
                                </div>
                            </div>
                            <?php
                            $establishment_date = date('Y-m-d', strtotime($row['establishment_year']));
                            ?>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Establishment Year :</label>
                                <div class="col-sm-8">
                                    <input type="date" class="form-control" name="establishment_year"
                                        id="establishment_year" value="<?= $establishment_date; ?>" required>
                                </div>
                            </div>
                            <!-- <div class="form-group">
                                <label class="control-label col-sm-2">Date of Birth (DOB):</label>
                                <div class="col-sm-8">
                                    <input type="date" class="form-control" name="dob" id="dob"
                                        value="<?= htmlspecialchars($row['dob'] ?? ''); ?>">
                                </div>
                            </div> -->

                            <div class="form-group">
                                <label for="usernamee" class="col-sm-2">Course :</label>
                                <div class="col-sm-8">
                                    <select name="courses_id[]" id="courses_id" class="form-control select2" multiple>
                                        <option value="" disabled>Select Course</option>
                                        <?php
                                        $sqlb = "SELECT id, name FROM f_courses WHERE status = 1";
                                        $resultb = $conn->query($sqlb);
                                        while ($rowb = $resultb->fetch_array()) {
                                            $selected = in_array($rowb['id'], $selected_course_ids) ? 'selected' : '';
                                            echo "<option value='{$rowb['id']}' $selected>{$rowb['name']}</option>";
                                        }
                                        ?>
                                    </select>


                                </div>
                            </div>
                            <div class="form-group">
                                <label for="passwrod" class="col-sm-2">Near By Area :</label>
                                <div class="col-sm-8">

                                    <textarea class="form-control textarea" placeholder="Enter near by area"
                                        style="width: 100%; height: 50px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"
                                        name="near_by_area" id="near_by_area"><?= $row['nearby_area']; ?></textarea>

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="usernamee" class="col-sm-2">City :</label>
                                <div class="col-sm-8">

                                    <select name="city_id" id="city_id" class="form-control" required>
                                        <option value=""> Select City </option>
                                        <?php
                                        $sqlb = "SELECT id,name FROM city_education where status=1 ORDER BY name ASC";
                                        $resultb = $conn->query($sqlb);
                                        while ($rowb = $resultb->fetch_array()) {
                                        ?>
                                            <option <?php if ($row['city'] == $rowb['id']) {
                                                        echo "selected";
                                                    } ?> value="<?= $rowb['id']; ?>"> <?= $rowb['name']; ?> </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Is MOU Present?</label>
                                <div class="col-sm-8">
                                    <label><input type="checkbox" name="is_mou" id="is_mou" value="1"
                                            <?php echo ($row['mou_present'] == 1) ? 'checked' : ''; ?>> Yes</label>
                                </div>
                            </div>
                            <div class="form-group" id="whatsapp_group" style="display: none;">
                                <label class="control-label col-sm-2">WhatsApp Number:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="whatsapp_number" id="whatsapp_number"
                                        maxlength="15" value="<?= $row['whats_app_number']; ?>"
                                        placeholder="Enter WhatsApp Number">
                                </div>
                            </div>



                            <div class="form-group">
                                <label for="usernamee" class="col-sm-2">Country :</label>
                                <div class="col-sm-8">

                                    <select name="country_id[]" id="country_id" class="form-control select2" multiple>
                                        <option value="" disabled> Select Country </option>
                                        <?php
                                        $sqlb = "SELECT id,name FROM country where status=1";
                                        $resultb = $conn->query($sqlb);
                                        while ($rowb = $resultb->fetch_array()) {
                                        ?>
                                            <option <?php if (in_array($rowb['id'], $selected_country_ids)) {
                                                        echo "selected";
                                                    } ?> value="<?= $rowb['id']; ?>"> <?= $rowb['name']; ?> </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="usernamee" class="col-sm-2">Visa Type :</label>
                                <div class="col-sm-8">

                                    <select name="visa_type_id[]" id="visa_type_id" class="form-control select2"
                                        multiple>
                                        <option value="" disabled> Select Visa Type </option>
                                        <?php
                                        $sqlb = "SELECT id,name FROM visa_type where status=1";
                                        $resultb = $conn->query($sqlb);
                                        while ($rowb = $resultb->fetch_array()) {
                                        ?>
                                            <option <?php if (in_array($rowb['id'], $selected_visa_ids)) {
                                                        echo "selected";
                                                    } ?> value="<?= $rowb['id']; ?>"> <?= $rowb['name']; ?> </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="usernamee" class="col-sm-2">Exam Type :</label>
                                <div class="col-sm-8">

                                    <select name="exam_type_id[]" id="exam_type_id" class="form-control select2"
                                        multiple>
                                        <option value="" disabled> Select Exam Type </option>
                                        <?php
                                        $sqlb = "SELECT id,name FROM exam_type where status=1";
                                        $resultb = $conn->query($sqlb);
                                        while ($rowb = $resultb->fetch_array()) {
                                        ?>
                                            <option <?php if (in_array($rowb['id'], $selected_exam_ids)) {
                                                        echo "selected";
                                                    } ?> value="<?= $rowb['id']; ?>"> <?= $rowb['name']; ?> </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Institute Link :</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="website_link" id="website_link"
                                        placeholder="Enter Institute Link" value="<?= $row['institute_url']; ?>">
                                </div>
                            </div>



                            <div class="form-group">
                                <label class="col-sm-2">Status :</label>
                                <div class="col-sm-6">
                                    <div class="col-sm-3 col-xs-6">
                                        <label>
                                            <input name="status" type="radio" value="1"
                                                <?php if ($status == 1) {  ?>checked="checked" <?php } ?> />
                                            Active</label>
                                    </div>
                                    <div class="col-sm-6 col-xs-6">
                                        <label>
                                            <input name="status" type="radio" value="0"
                                                <?php if ($status == 0) {  ?>checked="checked" <?php } ?> />
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

            function toggleWhatsappField() {
                if ($('#is_mou').is(':checked')) {
                    $('#whatsapp_group').show();
                    $('#whatsapp_number').attr('required', true);
                } else {
                    $('#whatsapp_group').hide();
                    $('#whatsapp_number').removeAttr('required');
                }
            }

            $('#is_mou').change(toggleWhatsappField);
            $('#courses_id').change(function() {
                $('#country_id').attr('required', $(this).val() !== "");
            });

            // Call on page load
            toggleWhatsappField();

        });
    </script>
    <script>
        $(document).ready(function() {
            //Select2
            $(".select2").select2();
            //bootstrap WYSIHTML5 - text editor
            // $(".textarea").wysihtml5();

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


</body>

</html>