<?php
  include("../../database.php"); 
?>
<!DOCTYPE html>
<html>
<head>
<base href="<?=$relative_path;?>">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?=$softtitle;?></title>
  <meta name="robots" content="noindex,nofollow">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php include("../includes/css-scripts.php"); ?>
</head>
<body>
  	
     <div class="box">
    <div class="box-header well" data-original-title="data-original-title">
      <h2 style="font-size:17px;margin:0;"><i class="fa fa-pencil-square-o"></i> Delete Scheme Descount Details</h2>
    </div>
    <div class="box-content">
    <form id="form1" name="form1" method="post" action="" onSubmit="return selIt();" enctype="multipart/form-data" >
  <table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
    <tr>
      <td><table width="280" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="280" height="41"><div align="center" class="text-blue">Are You sure , You want to Delete .? </div></td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="7%"><input name="h101" type="hidden" id="h101" value="1" />
                  </td>
                  <td width="93%"><div class="form-actions">
            <button type="submit" class="btn btn-primary pull-left" style="margin-left:50px;">Delete</button>
            <button type="reset" class="btn" style="margin-left:10px;">Cancel</button>
      </div></td>
                 
                </tr>
            </table></td>
          </tr>
      </table></td>
    </tr>
  </table>
</form>
</div>
</div>
<?php 
$a=$_POST['h101'];
if($a==1)
{
	 
if($_GET['id']!="")
{
$id =mysqli_real_escape_string($conn,$_GET['id']);


$qury1="Delete From m_scheme_discount_slab  where id='$id'";					
$sq1=$conn->query($qury1);


if($sq1)
{
echo("<script language=\"javascript\">");
echo("window.close()");
echo("</script>");

}
else
{
error_log(mysqli_error());
echo("<script language=\"javascript\">");
echo("window.close()");
echo("</script>");
}
}
}
 ?>  
</body>
<script>
    window.onunload = refreshParent;
    function refreshParent() {
        window.opener.location.reload();
    }
</script>
</html>
