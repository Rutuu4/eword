<?php
include("../../database.php");

if ($_POST['h1'] == 1) {
    // Fetch all POST values safely
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $website_link = mysqli_real_escape_string($conn, $_POST['website_link']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $establishment_year = mysqli_real_escape_string($conn, $_POST['establishment_year']);
    $is_mou = isset($_POST['is_mou']) ? 1 : 0;
    $whatsapp_number = mysqli_real_escape_string($conn, $_POST['whatsapp_number'] ?? '');
    // $dob = mysqli_real_escape_string($conn, $_POST['dob'] ?? '');

    $courses = isset($_POST['courses_id']) ? $_POST['courses_id'] : [];
    $course_ids = !empty($courses) ? implode(',', array_map('intval', $courses)) : '';

    $city_id = mysqli_real_escape_string($conn, $_POST['city_id']);
    $near_by_area = mysqli_real_escape_string($conn, $_POST['near_by_area']);


    // Insert into foreign_education table WITHOUT country_id, visa_type_id, exam_type_id
    // (since those are now handled via linking tables)
    // Build insert fields and values dynamically
    $fields = [
        "consultancy_name" => "'$name'",
        "institute_url" => "'$website_link'",
        "status" => "'$status'",
        "establishment_year" => "'$establishment_year'",
        "mou_present" => "'$is_mou'",
        "whats_app_number" => "'$whatsapp_number'",
        "city" => "'$city_id'",
        "nearby_area" => "'$near_by_area'"
    ];

    if (!empty($course_ids)) {
        $fields["course_ids"] = "'$course_ids'";
    }

    // if (!empty($dob)) {
    //     $fields["dob"] = "'$dob'";
    // }

    // Construct the query
    $columns = implode(", ", array_keys($fields));
    $values = implode(", ", array_values($fields));

    $insert = "INSERT INTO foreign_education ($columns) VALUES ($values)";


    echo $insert;
    $result = $conn->query($insert);
    if ($result) {
        $foreign_education_id = $conn->insert_id;

        // Insert countries (multiple)
        if (!empty($_POST['country_id']) && is_array($_POST['country_id'])) {
            $values = [];
            foreach ($_POST['country_id'] as $country_id) {
                $country_id = (int)$country_id;
                if ($country_id > 0) {
                    $values[] = "($foreign_education_id, $country_id)";
                }
            }
            if (!empty($values)) {
                $sql_countries = "INSERT INTO foreign_education_countries (foreign_education_id, country_id) VALUES " . implode(',', $values);
                $conn->query($sql_countries);
            }
        }

        // Insert visa types (multiple)
        if (!empty($_POST['visa_type_id']) && is_array($_POST['visa_type_id'])) {
            $values = [];
            foreach ($_POST['visa_type_id'] as $visa_type_id) {
                $visa_type_id = (int)$visa_type_id;
                if ($visa_type_id > 0) {
                    $values[] = "($foreign_education_id, $visa_type_id)";
                }
            }
            if (!empty($values)) {
                $sql_visas = "INSERT INTO foreign_education_visa_types (foreign_education_id, visa_type_id) VALUES " . implode(',', $values);
                $conn->query($sql_visas);
            }
        }

        // Insert exam types (multiple)
        if (!empty($_POST['exam_type_id']) && is_array($_POST['exam_type_id'])) {
            $values = [];
            foreach ($_POST['exam_type_id'] as $exam_type_id) {
                $exam_type_id = (int)$exam_type_id;
                if ($exam_type_id > 0) {
                    $values[] = "($foreign_education_id, $exam_type_id)";
                }
            }
            if (!empty($values)) {
                $sql_exams = "INSERT INTO foreign_education_exam_types (foreign_education_id, exam_type_id) VALUES " . implode(',', $values);
                $conn->query($sql_exams);
            }
        }

        // Redirect after successful insertion
        header("Location: ../manage-foreign-education.php");
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
                <h1>Add Foreign Education</h1>
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
                                <label class="control-label col-sm-2">Establishment Year :</label>
                                <div class="col-sm-8">
                                    <input type="date" class="form-control" name="establishment_year"
                                        id="establishment_year" required>
                                </div>
                            </div>

                            <!-- <div class="form-group">
                                <label class="control-label col-sm-2">Date of Birth :</label>
                                <div class="col-sm-8">
                                    <input type="date" class="form-control" name="dob" id="dob" required>
                                </div>
                            </div> -->

                            <div class="form-group">
                                <label for="usernamee" class="col-sm-2">Course :</label>
                                <div class="col-sm-8">

                                    <select name="courses_id[]" id="courses_id" class="form-control select2" multiple
                                        required>
                                        <option value="" disabled>Select Course</option>
                                        <?php
                                        $sqlb = "SELECT id,name FROM f_courses WHERE status=1";
                                        $resultb = $conn->query($sqlb);
                                        while ($rowb = $resultb->fetch_array()) {
                                            echo "<option value='{$rowb['id']}'>{$rowb['name']}</option>";
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
                                        name="near_by_area" id="near_by_area"></textarea>

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="city_id" class="col-sm-2">City :</label>
                                <div class="col-sm-8">

                                    <select name="city_id" id="city_id" class="form-control" required>
                                        <option value=""> Select City</option>
                                        <?php
                                        $sqlb = "SELECT id,name FROM city_education where status=1 ORDER BY name ASC";
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
                                <label for="country_id" class="col-sm-2">Country :</label>
                                <div class="col-sm-8">

                                    <select name="country_id[]" id="country_id" class="form-control select2" multiple>

                                        <option value="" disabled> Select Country</option>
                                        <?php
                                        $sqlb = "SELECT id,name FROM country where status=1";
                                        $resultb = $conn->query($sqlb);
                                        while ($rowb = $resultb->fetch_array()) {
                                        ?>
                                            <option value="<?= $rowb['id']; ?>"> <?= $rowb['name']; ?> </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="visa_type_id" class="col-sm-2">Visa Type :</label>
                                <div class="col-sm-8">

                                    <select name="visa_type_id[]" id="visa_type_id" class="form-control select2"
                                        multiple>
                                        <option value="" disabled> Select Visa Type </option>
                                        <?php
                                        $sqlb = "SELECT id,name FROM visa_type where status=1";
                                        $resultb = $conn->query($sqlb);
                                        while ($rowb = $resultb->fetch_array()) {
                                        ?>
                                            <option value="<?= $rowb['id']; ?>"> <?= $rowb['name']; ?> </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exam_type_id" class="col-sm-2">Exam Type :</label>
                                <div class="col-sm-8">

                                    <select name="exam_type_id[]" id="exam_type_id" class="form-control select2"
                                        multiple>

                                        <option value="" disabled> Select Exam Type </option>
                                        <?php
                                        $sqlb = "SELECT id,name FROM exam_type where status=1";
                                        $resultb = $conn->query($sqlb);
                                        while ($rowb = $resultb->fetch_array()) {
                                        ?>
                                            <option value="<?= $rowb['id']; ?>"> <?= $rowb['name']; ?> </option>
                                        <?php } ?>
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

            // Dynamic required logic: If course selected, country is required
            $('#courses_id').change(function() {
                let courseSelected = $(this).val();
                if (courseSelected) {
                    $('#country_id').attr('required', true);
                } else {
                    $('#country_id').removeAttr('required');
                }
            });

            // Trigger change on load in case of pre-filled form
            $('#courses_id').trigger('change');
        });
    </script>




</body>

</html>