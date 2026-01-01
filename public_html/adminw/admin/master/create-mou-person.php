<?php
include("../../database.php");
$existingTypes = [];

$typeQuery = mysqli_query($conn, "SELECT type FROM mou_person");
while ($row = mysqli_fetch_assoc($typeQuery)) {
    $existingTypes[] = $row['type'];
}


if ($_POST['h1'] == 1) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    // ❌ Prevent duplicate type insertion
    $check = mysqli_query($conn, "SELECT id FROM mou_person WHERE type = '$type' LIMIT 1");

    if (mysqli_num_rows($check) > 0) {
        echo "<script>
        alert('This type already exists. Only one entry per type is allowed.');
        window.history.back();
    </script>";
        exit;
    }

    // Insert query
    $query = "INSERT INTO mou_person(name, phone_number, type, status) 
              VALUES ('$name', '$phone_number', '$type', '$status')";

    $result = $conn->query($query);

    if (mysqli_affected_rows($conn) >= 1) {
        header("location:../manage-mou-person.php");
        exit;
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
                <h1>Add Mou Data</h1>
            </section>

            <section class="content">

                <div class="box box-success">
                    <div class="box-body">
                        <form action="" method="POST" class="form-horizontal" enctype="multipart/form-data">
                            <input name="h1" type="hidden" id="h1" value="1" />

                            <!-- Position Name -->
                            <div class="form-group">
                                <label class="control-label col-sm-2">Name :</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="name" id="name"
                                        placeholder="Enter Position Name">
                                </div>
                            </div>

                            <!-- Phone Number -->
                            <div class="form-group">
                                <label class="control-label col-sm-2">Phone Number :</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="phone_number" id="phone_number" maxlength="10"
                                        placeholder="Enter Phone Number" required>
                                </div>
                            </div>

                            <!-- Type Select -->
                            <div class="form-group">
                                <label class="control-label col-sm-2">Type :</label>
                                <div class="col-sm-8">
                                    <select name="type" class="form-control select2" required>
                                        <option value="">-- Select Type --</option>

                                        <option value="foreign"
                                            <?= in_array('foreign', $existingTypes) ? 'disabled' : '' ?>>
                                            Foreign Education
                                        </option>

                                        <option value="college"
                                            <?= in_array('college', $existingTypes) ? 'disabled' : '' ?>>
                                            College
                                        </option>

                                        <option value="tuition"
                                            <?= in_array('tuition', $existingTypes) ? 'disabled' : '' ?>>
                                            Tuition Training
                                        </option>

                                        <option value="internship"
                                            <?= in_array('internship', $existingTypes) ? 'disabled' : '' ?>>
                                            Project Internship
                                        </option>

                                        <option value="job"
                                            <?= in_array('job', $existingTypes) ? 'disabled' : '' ?>>
                                            Job Placement
                                        </option>
                                    </select>

                                </div>
                            </div>

                            <!-- Status -->
                            <div class="form-group">
                                <label class="col-sm-2">Status :</label>
                                <div class="col-sm-6">
                                    <div class="col-sm-3 col-xs-6">
                                        <label>
                                            <input name="status" type="radio" value="1" checked="checked" />
                                            Active
                                        </label>
                                    </div>
                                    <div class="col-sm-6 col-xs-6">
                                        <label>
                                            <input name="status" type="radio" value="0" />
                                            Deactive
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" align="right">
                                <button type="submit" class="btn btn-success">Save changes</button>
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
            $(".select2").select2();
            $(".textarea").wysihtml5();
        });
    </script>
</body>

</html>