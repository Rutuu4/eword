<?php
include("../database.php");
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $softtitle ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <?php include("includes/css-scripts.php"); ?>

</head>

<body class="<?= $bodyclass ?>">

    <div class="wrapper">

        <?php include("includes/header.php"); ?>
        <?php include("includes/sidebar.php"); ?>

        <div class="content-wrapper">

            <section class="content">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <!--
            <div class="box-header">
              <h3 class="box-title">Hover Data Table</h3>
            </div>
            -->
                            <div class="box-body">
                                <h4>
                                    Manage Refer Report
                                    <!--  <a href="master/create-register-user.php" class="btn btn-primary pull-right" >Create Register User</a> -->
                                </h4>
                            </div>
                        </div>

                        <div class="box">
                            <div class="box-body table-responsive">
                                <table id="datatable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <thead>
                                                <tr>
                                                    <th>Sr No.</th>
                                                    <th>Refer Username</th>
                                                    <th>Total Referred Users</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $qry = "SELECT refer_username, COUNT(*) as total_referred 
        FROM registration 
        WHERE refer_username != '' 
        GROUP BY refer_username 
        ORDER BY total_referred ASC";

                                        $result = $conn->query($qry);
                                        $i = 0;

                                        while ($row = $result->fetch_array()) {
                                            $i++;
                                        ?>
                                            <tr>
                                                <td><?= $i; ?></td>
                                                <td><?= $row['refer_username']; ?></td>
                                                <td><?= $row['total_referred']; ?></td>
                                                <td><a href="master/refer-details.php?refer_username=<?= urlencode($row['refer_username']); ?>"
                                                        class="btn btn-warning">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </td>

                                            </tr>
                                        <?php } ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
        <?php include("includes/footer.php"); ?>
    </div>

    <?php include("includes/js-scripts.php"); ?>
    <script>
        $(document).ready(function() {
            //datatable
            $('#datatable').DataTable({
                "pageLength": 25 // Set default number of rows per page
            });


            $(".deletestate").click(function() {
                var key = $(this).data("key");
                if (confirm('Are you sure you want to delete this?')) {
                    $.ajax({
                        url: 'master/delete-state.php',
                        type: "POST",
                        data: {
                            key: key
                        },
                        success: function(response) {
                            if (response == "TRUE" && response != "") {
                                location.reload();
                            } else {
                                alert("Please Try Again .!");
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>