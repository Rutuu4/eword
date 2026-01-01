<?php
include("../../database.php");

if ($_POST['h1'] == 1) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $position_name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    // ❌ Prevent duplicate type on update
    $check = mysqli_query(
        $conn,
        "SELECT id FROM mou_person WHERE type = '$type' AND id != '$id' LIMIT 1"
    );

    if (mysqli_num_rows($check) > 0) {
        echo "<script>
        alert('This type is already assigned to another record.');
        window.history.back();
    </script>";
        exit;
    }

    // Update query
    $query = "UPDATE mou_person 
              SET name = '$position_name',
                  phone_number = '$phone_number',
                  type = '$type',
                  status = '$status'
              WHERE id = '$id'";

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

    <?php
    // Fetch data for the selected ID
    if (!empty($_GET['key'])) {
        $id = base64_decode($_GET['key']);
        $qry = "SELECT * FROM mou_person WHERE id='$id'";
        $result = $conn->query($qry);
        $row = $result->fetch_assoc();
        $status = $row['status'];
        $usedTypes = [];
        $typeQuery = mysqli_query(
            $conn,
            "SELECT type FROM mou_person WHERE id != '$id'"
        );

        while ($t = mysqli_fetch_assoc($typeQuery)) {
            $usedTypes[] = $t['type'];
        }
    }
    ?>

    <div class="wrapper">
        <?php include("../includes/header.php"); ?>
        <?php include("../includes/sidebar.php"); ?>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>Edit MOU Person</h1>
            </section>

            <section class="content">
                <div class="box box-success">
                    <div class="box-body">
                        <form action="" method="POST" class="form-horizontal" enctype="multipart/form-data">
                            <!-- Hidden Inputs -->
                            <input name="h1" type="hidden" value="1" />
                            <input name="id" type="hidden" value="<?= $id; ?>" />

                            <!-- Name -->
                            <div class="form-group">
                                <label class="control-label col-sm-2">Name :</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="name"
                                        value="<?= htmlspecialchars($row['name']); ?>">
                                </div>
                            </div>

                            <!-- Phone Number -->
                            <div class="form-group">
                                <label class="control-label col-sm-2">Phone Number :</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="phone_number"
                                        value="<?= htmlspecialchars($row['phone_number']); ?>" required>
                                </div>
                            </div>

                            <!-- Type Select -->
                            <div class="form-group">
                                <label class="control-label col-sm-2">Type :</label>
                                <div class="col-sm-8">
                                    <select name="type" class="form-control select2" required>
                                        <option value="">-- Select Type --</option>

                                        <option value="foreign"
                                            <?= ($row['type'] == 'foreign') ? 'selected' : '' ?>
                                            <?= (in_array('foreign', $usedTypes) && $row['type'] != 'foreign') ? 'disabled' : '' ?>>
                                            Foreign Education
                                        </option>

                                        <option value="college"
                                            <?= ($row['type'] == 'college') ? 'selected' : '' ?>
                                            <?= (in_array('college', $usedTypes) && $row['type'] != 'college') ? 'disabled' : '' ?>>
                                            College
                                        </option>

                                        <option value="tuition"
                                            <?= ($row['type'] == 'tuition') ? 'selected' : '' ?>
                                            <?= (in_array('tuition', $usedTypes) && $row['type'] != 'tuition') ? 'disabled' : '' ?>>
                                            Tuition Training
                                        </option>

                                        <option value="internship"
                                            <?= ($row['type'] == 'internship') ? 'selected' : '' ?>
                                            <?= (in_array('internship', $usedTypes) && $row['type'] != 'internship') ? 'disabled' : '' ?>>
                                            Project Internship
                                        </option>

                                        <option value="job"
                                            <?= ($row['type'] == 'job') ? 'selected' : '' ?>
                                            <?= (in_array('job', $usedTypes) && $row['type'] != 'job') ? 'disabled' : '' ?>>
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
                                            <input name="status" type="radio" value="1" <?= ($status == 1) ? 'checked' : '' ?> />
                                            Active
                                        </label>
                                    </div>
                                    <div class="col-sm-6 col-xs-6">
                                        <label>
                                            <input name="status" type="radio" value="0" <?= ($status == 0) ? 'checked' : '' ?> />
                                            Deactive
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit -->
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
        });
    </script>
</body>

</html>