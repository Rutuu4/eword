<?php
include("../../database.php");

if ($_POST['h1'] == 1) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);


    $city_id = mysqli_real_escape_string($conn, $_POST['city_id']);
    $near_by_area = mysqli_real_escape_string($conn, $_POST['near_by_area']);
    $website_link = mysqli_real_escape_string($conn, $_POST['website_link']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $class_type_ids = $_POST['class_type_id'] ?? [];
    $class_type_str = mysqli_real_escape_string($conn, implode(',', $class_type_ids));


    $is_mou = isset($_POST['is_mou']) ? 1 : 0;
    $whatsapp_number = $is_mou ? mysqli_real_escape_string($conn, $_POST['whatsapp_number']) : '';

    // Handle Country, Visa Type, Exam Type (mapping tables)
    $courses_ids = $_POST['courses_id'] ?? [];


    // Construct the Update Query
    $update_query = "UPDATE tuition_and_training 
                     SET consultancy_name='$name', 
                         city='$city_id',
                         nearby_area='$near_by_area',
                         institute_web_url='$website_link',
                         mou_is_present='$is_mou',
                         class_type='$class_type_str',
                         whats_app_number='$whatsapp_number',
                         status='$status' ";

    $update_query .= " WHERE id='$id'";
    // Execute the query
    $conn->query($update_query);

    // Update country mappings
    $conn->query("DELETE FROM tuition_and_training_courses WHERE tuition_and_training_id = '$id'");
    foreach ($courses_ids as $courses_id) {
        $courses_id = mysqli_real_escape_string($conn, $courses_id);
        $conn->query("INSERT INTO tuition_and_training_courses(tuition_and_training_id, course_id) VALUES('$id', '$courses_id')");
    }


    header("location:../manage-tuition-and-trainning.php");
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
        $qry = "SELECT * FROM tuition_and_training WHERE id='$id'";
        $result = $conn->query($qry);
        $row = $result->fetch_array();

        $status = $row['status'];

        $selected_class_type = $row['class_type'];

        // Fetch selected country IDs
        $selected_course_ids = [];
        $res = $conn->query("SELECT course_id FROM tuition_and_training_courses WHERE tuition_and_training_id = '$id'");
        while ($r = $res->fetch_assoc()) {
            $selected_course_ids[] = $r['course_id'];
        }
    }

    ?>


    <div class="wrapper">
        <?php include("../includes/header.php"); ?>
        <?php include("../includes/sidebar.php"); ?>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>Update Tuition and Training</h1>
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


                            <div class="form-group">
                                <label for="usernamee" class="col-sm-2">Course :</label>
                                <div class="col-sm-8">

                                    <select name="courses_id[]" id="courses_id" class="form-control select2" multiple>
                                        <option value="" disabled> Select Course </option>
                                        <?php
                                        $sqlb = "SELECT id,name FROM t_courses";
                                        $resultb = $conn->query($sqlb);
                                        while ($rowb = $resultb->fetch_array()) {
                                        ?>
                                            <option <?php if (in_array($rowb['id'], $selected_course_ids)) {
                                                        echo "selected";
                                                    } ?> value="<?= $rowb['id']; ?>"> <?= $rowb['name']; ?> </option>

                                        <?php } ?>
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
                                        $sqlb = "SELECT id,name FROM city";
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
                                            <?php echo ($row['mou_is_present'] == 1) ? 'checked' : ''; ?>> Yes</label>
                                </div>
                            </div>
                            <div class="form-group" id="whatsapp_group" style="display: none;">
                                <label class="control-label col-sm-2">WhatsApp Number:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="whatsapp_number" id="whatsapp_number"
                                        maxlength="10" value="<?= $row['whats_app_number']; ?>"
                                        placeholder="Enter WhatsApp Number">
                                </div>
                            </div>
                            <?php
                            $selected_class_types = !empty($row['class_type']) ? explode(',', $row['class_type']) : [];
                            ?>
                            <div class="form-group">
                                <label for="class_type_id" class="col-sm-2">Class Type :</label>
                                <div class="col-sm-8">
                                    <select name="class_type_id[]" id="class_type_id" class="form-control select2"
                                        multiple required>
                                        <option value="Online"
                                            <?= in_array("Online", $selected_class_types) ? 'selected' : '' ?>>Online
                                        </option>
                                        <option value="Offline"
                                            <?= in_array("Offline", $selected_class_types) ? 'selected' : '' ?>>Offline
                                        </option>
                                        <option value="Remote"
                                            <?= in_array("Remote", $selected_class_types) ? 'selected' : '' ?>>Remote
                                        </option>
                                    </select>
                                </div>
                            </div>



                            <div class="form-group">
                                <label class="control-label col-sm-2">Institute Link :</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="website_link" id="website_link"
                                        placeholder="Enter Institute Link" value="<?= $row['institute_web_url']; ?>">
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



</body>

</html>