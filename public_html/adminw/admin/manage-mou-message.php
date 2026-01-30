<?php
include("../database.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'retry') {

    if (!isset($_POST['id'])) {
        echo "INVALID";
        exit;
    }

    $id = intval($_POST['id']);

    // Check current status
    $check = $conn2->query("SELECT status FROM wp_messages WHERE id = $id");
    if (!$check || $check->num_rows === 0) {
        echo "NOT_FOUND";
        exit;
    }

    $row = $check->fetch_assoc();

    if ($row['status'] !== 'failed') {
        echo "NOT_ALLOWED";
        exit;
    }

    $update = $conn2->query("
        UPDATE wp_messages 
        SET status = 'queued', updated_at = NOW()
        WHERE id = $id
    ");

    echo $update ? "SUCCESS" : "ERROR";
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $softtitle ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <?php include("includes/css-scripts.php"); ?>
    <style>
        .retry-loader {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid #fff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 0.6s linear infinite;
            vertical-align: middle;
            margin-right: 6px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>


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
                                    Manage Mou Message List

                                </h4>
                            </div>
                        </div>

                        <div class="box">
                            <div class="box-body table-responsive">
                                <table id="datatable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th style="width:10%">Message Id</th>
                                            <!-- <th style="width:20%">Salary</th> -->
                                            <!-- <th>Video Link</th> -->
                                            <th>Phone Number </th>
                                            <th>MOU Phone Number </th>
                                            <!-- <th>Website Link</th> -->
                                            <th>Message Text</th>
                                            <th>Status</th>
                                            <th>Failure Reason</th>
                                            <th>Deliver Date</th>
                                            <th>Created Date</th>
                                            <th>Updated Date</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        $qry = "SELECT * FROM wp_messages  
ORDER BY id DESC;
";

                                        $result = $conn2->query($qry);
                                        while ($row = $result->fetch_array()) {
                                            $i++;
                                        ?>

                                            <tr>
                                                <td><?= $i; ?></td>

                                                <td><?= $row['message_id']; ?></td>
                                                <td><?= $row['phone_number']; ?></td>
                                                <td><?= !empty($row['mou_phone_number']) ? $row['mou_phone_number'] : '-'; ?></td>
                                                <!-- <td><?= $row['website_link']; ?></td> -->
                                                <td><?= $row['message_text']; ?></td>
                                                <td>
                                                    <?php
                                                    switch ($row['status']) {

                                                        case 'failed':
                                                            echo '
        <span class="label label-danger change-status"
              style="cursor:pointer"
              data-id="' . $row['id'] . '">
            Failed (Click to Retry)
        </span>';
                                                            break;

                                                        case 'queued':
                                                            echo '<span class="label label-info">Queued</span>';
                                                            break;

                                                        case 'sent':
                                                            echo '<span class="label label-primary">Sent</span>';
                                                            break;

                                                        case 'delivered':
                                                            echo '<span class="label label-success">Delivered</span>';
                                                            break;

                                                        case 'read':
                                                            echo '<span class="label label-success">Read</span>';
                                                            break;

                                                        default:
                                                            echo '<span class="label label-default">' . ucfirst($row['status']) . '</span>';
                                                    }
                                                    ?>
                                                </td>


                                                <td><?= $row['failure_reason']; ?></td>
                                                <td><?= $row['delivered_at'] ? date('d-m-Y H:i', strtotime($row['delivered_at'])) : '-'; ?></td>
                                                <td><?= date('d-m-Y H:i', strtotime($row['created_at'])); ?></td>
                                                <td><?= date('d-m-Y H:i', strtotime($row['updated_at'])); ?></td>




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
        $(document).on('click', '.change-status', function() {

            if (!confirm('Change status from FAILED to QUEUED?')) return;

            let btn = $(this);
            let originalText = btn.text();

            btn
                .html('<span class="retry-loader"></span>Retrying...')
                .css('pointer-events', 'none');

            $.ajax({
                url: window.location.href, // ✅ SAME PAGE
                type: 'POST',
                data: {
                    action: 'retry',
                    id: btn.data('id')
                },
                timeout: 5000,
                success: function(res) {

                    if (res.trim() === 'SUCCESS') {
                        btn
                            .removeClass('label-danger')
                            .addClass('label-info')
                            .html('Queued');
                    } else {
                        btn
                            .html(originalText)
                            .css('pointer-events', 'auto');
                        alert('Status update failed: ' + res);
                    }
                }
            });
        });
    </script>

</body>

</html>