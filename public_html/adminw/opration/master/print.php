<?php 
  
include("../../database.php"); 


	if($_GET['key']!="")
{
	 $getid = base64_decode($_GET['key']);

   $getqry = $conn->query("SELECT user_id,register1.name as vandor_name FROM product_order LEFT JOIN register1
ON product_order.user_id = register1.id where order_id='$getid' ");
   $row = $getqry->fetch_array();

}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>

<style media="print" type="text/css">
 @media print {
  body * { visibility: hidden; font-family:calibri; } 
  #PrintDiv * { visibility: visible;
}
  

  #PrintDiv { display: block; }
    
   } 
</style> 

<style>
 
td { border:1px solid #ececec;font-size: 16px;font-weight: normal;}
body { font-family:calibri;}
</style>
<body>


<div id="PrintDiv">



<table width="100%" border="1" cellpadding="2" cellspacing="0">
  <tr>
    <td colspan="4" align="center"><span style="padding:6px 0; font-size:24px;font-weight:bold;">Nilrise-Pharma
    </span></td>
    </tr>
  <tr>
    <td width="15%"><strong>Vandor Name</strong></td>
    <td>
      <?php echo $row['vandor_name']; ?>
    </td>
     <td align="right"><strong>Order No</strong></td>
    <td>
      <?php
      echo $getid;
      ?>
    </td>
    </tr>
  <tr>
    <td colspan="4">
    <table width="100%" cellspacing="0">
    
      <tr>
      <td><strong>Sr No.</strong></td>
      <td><strong>product Name</strong></td>
      <td align="center"><strong>Quantity</strong></td>
      <td align="center"><strong>Unit</strong></td>
      <td align="center"><strong>Oreder Date</strong></td>
    
    </tr>
 <?php
                  $getuserqry = $conn->query("SELECT *,product.name as product_name,m_unit.name as unit_name FROM product_order LEFT JOIN product
ON product_order.product_id = product.id LEFT JOIN m_unit ON product_order.unit=m_unit.id   where order_id='$getid' ");
                  $i = 1;
                  while($rowuser = $getuserqry->fetch_array()){

                      $date=date("d-m-Y", strtotime($rowuser['created_date']));
                ?>
                <tr>
                  <td><?=$i++;?></td>
                  <td><?=$rowuser['product_name']?></td>
                  <td><?=$rowuser['quantity']?></td>
                  <td><?=$rowuser['unit_name']?></td>
                  <td><?php echo $date ; ?></td>
                </tr>
                <?php } ?>
    </table>
    
    </td>
    </tr>
  

</table>

</div>
<p></p>
<input name="Submit" type="submit" class="style133" onclick="window.print()" value="Print" />
</body>
</html>
