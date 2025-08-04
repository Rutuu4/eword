<?php
include("include/database.php");

$web_user=$_SESSION['web_user'];
$qry_delete_account="DELETE FROM registration WHERE id='$web_user'";
$sq1_delete=$conn->query($qry_delete_account);

//unset varaible
unset($_SESSION['web_user']);

if($sq1_delete)
{ ?>
    <script>
    window.location = 'delete-my-account-login.php';
</script>
<?php }  ?>


