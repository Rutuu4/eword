<?php 
include("../../database.php"); 

if ($_POST['h1'] == 1) {  
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $position_name = mysqli_real_escape_string($conn, $_POST['position_name']);
    $salary = mysqli_real_escape_string($conn, $_POST['salary']);
    $required_experience = mysqli_real_escape_string($conn, $_POST['required_experience']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $query = "UPDATE j_openings 
              SET position_name = '$position_name',
                  salary = '$salary',
                  required_experience = '$required_experience',
                  status = '$status'
              WHERE id = '$id'";

    $result = $conn->query($query); 

    if (mysqli_affected_rows($conn) >= 1) {
        header("location:../manage-job-and-placement-openning.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <base href="<?=$base_path?>">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=$softtitle?></title>
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

<body class="<?=$bodyclass?>">

    <?php

if(!empty($_GET['key'])){

  $id=base64_decode($_GET['key']);
 
  $qry="SELECT * FROM j_openings WHERE id='$id'";  

  $result = $conn->query($qry);
  $row = $result->fetch_array();
  $status=$row['status'];



}
?>


    <div class="wrapper">
        <?php include("../includes/header.php"); ?>
        <?php include("../includes/sidebar.php"); ?>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>Update Project and Internship Details</h1>
            </section>

            <section class="content">

                <div class="box box-success">

                    <div class="box-body">
                        <form action="" method="POST" id="" class="form-horizontal" enctype="multipart/form-data">
                            <!-- Hidden Inputs -->
                            <input name="h1" type="hidden" id="h1" value="1" />
                            <input name="id" type="hidden" id="id" value="<?=$id;?>" />

                            <!-- Position Name -->
                            <div class="form-group">
                                <label class="control-label col-sm-2">Position Name :</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="position_name" id="position_name"
                                        placeholder="Enter Position Name"
                                        value="<?=htmlspecialchars($row['position_name']);?>" required>
                                </div>
                            </div>

                            <!-- Salary -->
                            <div class="form-group">
                                <label class="control-label col-sm-2">Salary :</label>
                                <div class="col-sm-8">
                                    <input type="number" step="0.01" class="form-control" name="salary" id="salary"
                                        placeholder="Enter Salary" value="<?=htmlspecialchars($row['salary']);?>"
                                        required>
                                </div>
                            </div>

                            <!-- Required Experience -->
                            <div class="form-group">
                                <label class="control-label col-sm-2">Required Experience :</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="required_experience"
                                        id="required_experience" placeholder="e.g., 2+ years"
                                        value="<?=htmlspecialchars($row['required_experience']);?>" required>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="form-group">
                                <label class="col-sm-2">Status :</label>
                                <div class="col-sm-6">
                                    <div class="col-sm-3 col-xs-6">
                                        <label>
                                            <input name="status" type="radio" value="1"
                                                <?=($status == 1) ? 'checked' : ''?> />
                                            Active
                                        </label>
                                    </div>
                                    <div class="col-sm-6 col-xs-6">
                                        <label>
                                            <input name="status" type="radio" value="0"
                                                <?=($status == 0) ? 'checked' : ''?> />
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