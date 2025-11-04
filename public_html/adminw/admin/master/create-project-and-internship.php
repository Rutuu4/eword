<?php
include("../../database.php");

if ($_POST['h1'] == 1) {
    // Fetch all POST values safely
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $website_link = mysqli_real_escape_string($conn, $_POST['website_link']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $is_mou = isset($_POST['is_mou']) ? 1 : 0;
    $whatsapp_number = mysqli_real_escape_string($conn, $_POST['whatsapp_number'] ?? '');
    $city_id = mysqli_real_escape_string($conn, $_POST['city_id']);
    $near_by_area = mysqli_real_escape_string($conn, $_POST['near_by_area']);
    $class_type_ids = $_POST['class_type_id'] ?? [];
    $class_type_str = mysqli_real_escape_string($conn, implode(',', $class_type_ids));



    // Insert into foreign_education table WITHOUT country_id, visa_type_id, exam_type_id
    // (since those are now handled via linking tables)
    // Build insert fields and values dynamically
    $fields = [
        "consultancy_name" => "'$name'",
        "institute_web_url" => "'$website_link'",
        "status" => "'$status'",
        "mou_is_present" => "'$is_mou'",
        "whatsapp_number" => "'$whatsapp_number'",
        "city_id" => "'$city_id'",
        "job_type" => "'$class_type_str'",
        "nearby_area" => "'$near_by_area'"
    ];


    // Construct the query
    $columns = implode(", ", array_keys($fields));
    $values = implode(", ", array_values($fields));

    $insert = "INSERT INTO project_and_internship ($columns) VALUES ($values)";

    $result = $conn->query($insert);
    if ($result) {
        $foreign_education_id = $conn->insert_id;

        // Insert countries (multiple)
        if (!empty($_POST['courses_id']) && is_array($_POST['courses_id'])) {
            $values = [];
            foreach ($_POST['courses_id'] as $courses_id) {
                $courses_id = (int)$courses_id;
                if ($courses_id > 0) {
                    $values[] = "($foreign_education_id, $courses_id)";
                }
            }
            if (!empty($values)) {
                $sql_countries = "INSERT INTO project_and_internship_courses (project_and_internship_id, course_id) VALUES " . implode(',', $values);
                $conn->query($sql_countries);
            }
        }



        // Redirect after successful insertion
        header("Location: ../manage-project-and-internship.php");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
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
                <h1>Add Project and Internship</h1>
            </section>

            <section class="content">

                <div class="box box-success">

                    <div class="box-body">
                        <form action="" method="POST" id="" class="form-horizontal" enctype="multipart/form-data">
                            <input name="h1" type="hidden" id="h1" value="1" />



                            <div class="form-group">
                                <label class="control-label col-sm-2">Name :</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="name" id="name"
                                        placeholder="Enter Name " required>
                                </div>
                            </div>



                            <div class="form-group">
                                <label for="usernamee" class="col-sm-2">Category :</label>
                                <div class="col-sm-8">

                                    <select name="courses_id[]" id="courses_id" class="form-control select2" multiple>
                                        <option value="" disabled> Select Category </option>
                                        <?php
                                        $sqlb = "SELECT id,name FROM p_courses where status=1";
                                        $resultb = $conn->query($sqlb);
                                        while ($rowb = $resultb->fetch_array()) {
                                        ?>
                                        <option value="<?= $rowb['id']; ?>"> <?= $rowb['name']; ?> </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="passwrod" class="col-sm-2">Near By Area :</label>
                                <div class="col-sm-8">

                                    <textarea class="form-control textarea" placeholder="Enter near by area"
                                        style="width: 100%; height: 50px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"
                                        name="near_by_area" id="near_by_area"></textarea>

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="city_id" class="col-sm-2">City :</label>
                                <div class="col-sm-8">

                                    <select name="city_id" id="city_id" class="form-control" required>
                                        <option value=""> Select City</option>
                                        <?php
                                        $sqlb = "SELECT id,name FROM city";
                                        $resultb = $conn->query($sqlb);
                                        while ($rowb = $resultb->fetch_array()) {
                                        ?>
                                        <option value="<?= $rowb['id']; ?>"> <?= $rowb['name']; ?> </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group"> <label class="control-label col-sm-2">Is MOU Present?</label>
                                <div class="col-sm-8"> <label><input type="checkbox" name="is_mou" id="is_mou"
                                            value="1"> Yes</label> </div>
                            </div>
                            <div class="form-group" id="whatsapp_group" style="display: none;"> <label
                                    class="control-label col-sm-2">WhatsApp Number:</label>
                                <div class="col-sm-8"> <input type="text" class="form-control" maxlength="10"
                                        name="whatsapp_number" id="whatsapp_number" placeholder="Enter WhatsApp Number">
                                </div>
                            </div>



                            <div class="form-group">
                                <label for="class_type_id" class="col-sm-2">Job Type :</label>
                                <div class="col-sm-8">
                                    <select name="class_type_id[]" id="class_type_id" class="form-control select2"
                                        multiple required>
                                        <option value="Part_time">Part Time</option>
                                        <option value="Full_time">Full Time</option>
                                        <option value="Remote">Remote</option>
                                    </select>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Institute Link :</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="website_link" id="website_link"
                                        placeholder="Enter Institute Link">
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
        // Initialize Select2
        $(".select2").select2();

        // Toggle WhatsApp number required based on MOU checkbox
        $('#is_mou').change(function() {
            if ($(this).is(':checked')) {
                $('#whatsapp_group').show();
                $('#whatsapp_number').prop('required', true);
            } else {
                $('#whatsapp_group').hide();
                $('#whatsapp_number').prop('required', false);
            }
        });


    });
    </script>




</body>

</html>