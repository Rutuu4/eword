<?php
include("../../database.php");

if ($_POST['h101'] == 1) {
    if ($_GET['id'] != "") {
        $id = $_GET['id'];

        // Begin transaction to ensure atomic operations
        $conn->begin_transaction();

        try {

            // Now delete the record from the f_courses table
            $conn->query("DELETE FROM city_project WHERE id = '$id'");

            // Commit the transaction if all queries were successful
            $conn->commit();

            echo ("<script language=\"javascript\">");
            echo ("alert('Record deleted successfully');");
            echo ("window.close();");
            echo ("</script>");
        } catch (Exception $e) {
            // Rollback the transaction in case of any error
            $conn->rollback();

            error_log(mysqli_error($conn));
            echo ("<script language=\"javascript\">");
            echo ("alert('Error deleting record. Please try again.');");
            echo ("window.close();");
            echo ("</script>");
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <base href="<?= $relative_path; ?>">
    <?php include("../includes/css-scripts.php"); ?>
</head>

<body>

    <div class="box">
        <div class="box-header well" data-original-title="data-original-title">
            <h2 style="font-size:17px;margin:0;"><i class="fa fa-pencil-square-o"></i> Delete </h2>
        </div>
        <div class="box-content">
            <form id="form1" name="form1" method="post" action="" onSubmit="return selIt();"
                enctype="multipart/form-data">
                <table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
                    <tr>
                        <td>
                            <table width="280" border="0" align="center" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td width="280" height="41">
                                        <div align="center" class="text-blue">Are You sure , You want to Delete .?
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td width="7%"><input name="h101" type="hidden" id="h101" value="1" />
                                                </td>
                                                <td width="93%">
                                                    <div class="form-actions">
                                                        <button type="submit" class="btn btn-primary pull-left"
                                                            style="margin-left:50px;">Delete</button>
                                                        <button type="reset" class="btn"
                                                            style="margin-left:10px;">Cancel</button>
                                                    </div>
                                                </td>

                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</body>
<?php include("../includes/js-scripts.php"); ?>
<script>
    window.onunload = refreshParent;

    function refreshParent() {
        window.opener.location.reload();
    }
</script>

</html>