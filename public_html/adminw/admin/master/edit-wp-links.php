<?php
include("../../database.php");

if ($_POST['h1'] == 1) {

    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $group_name = mysqli_real_escape_string($conn, $_POST['group_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $whatsapp_joining_link = mysqli_real_escape_string($conn, $_POST['whatsapp_joining_link']);
    $city_id = mysqli_real_escape_string($conn, $_POST['city_id']);



    $qury1 = "Update joining_links set group_name='$group_name', description='$description', whatsapp_joining_link='$whatsapp_joining_link', city_id='$city_id'  where id='$id'";
    $sq1 = $conn->query($qury1);

    if (mysqli_affected_rows($conn) >= 1) {
        header("location:../manage-wp-links.php");
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

        $qry = "SELECT * FROM joining_links WHERE id='$id'";

        $result = $conn->query($qry);
        $row = $result->fetch_array();
        $status = $row['status'];
    }
    ?>


    <div class="wrapper">
        <?php include("../includes/header.php"); ?>
        <?php include("../includes/sidebar.php"); ?>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>Update Whatsapp Link</h1>
            </section>

            <section class="content">

                <div class="box box-success">

                    <div class="box-body">
                        <form action="" method="POST" id="" class="form-horizontal" enctype="multipart/form-data">
                            <input name="h1" type="hidden" id="h1" value="1" />
                            <input name="id" type="hidden" id="id" value="<?= $id; ?>" />


                            <div class="form-group">
                                <label class="control-label col-sm-2">Group Name :</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="group_name" id="group_name" placeholder="Enter Group Name " value="<?= $row['group_name']; ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="passwrod" class="col-sm-2"> Description :</label>
                                <div class="col-sm-8">

                                    <textarea class="form-control" placeholder="Description" style="width: 100%; height: 70px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="description" id="description"><?= $row['description']; ?></textarea>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Whatsapp Joinning Link :</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="whatsapp_joining_link" id="whatsapp_joining_link" placeholder="Enter Whatsapp Joining Link" value="<?= $row['whatsapp_joining_link']; ?>" required>

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="usernamee" class="col-sm-2">City :</label>
                                <div class="col-sm-8">


                                    <select name="city_id" id="city_id" class="form-control" required>
                                        <option value=""> Select City </option>
                                        <?php
                                        $sqlb = "SELECT id,name FROM m_city where status=1";
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

</body>

</html>