<?php 
    include('../../../database.php');
	
	$keyword = mysqli_real_escape_string($conn,$_POST['keyword']);
	$category_id = mysqli_real_escape_string($conn,$_POST['category_id']);
	$sql = "SELECT * $search_qry_select_column FROM product WHERE product_name LIKE '$keyword%'";
	 if(!empty($category_id)){  $sql .= " && product_category_id = '$category_id' "; }
	$result=$conn->query($sql);
	
?>
	
<ul id="country-list">
<?php
if ($result->num_rows > 0) {
		
		while($row_sku_serach = $result->fetch_array()) {
		$product_category_id=$row_sku_serach['product_category_id']; 
		$qry1="SELECT name AS category_name from product_category where id='$product_category_id'";
		$result1=$conn->query($qry1);
		$row1=$result1->fetch_array();
		
?>
<li onClick="selectproductsku(<?=$row_sku_serach['id'];?>,'<?=$row1['category_name'];?>','<?=$row_sku_serach['product_name'];?>', 1);">
<?php if($define_skucode_activation==1 && $define_create_order_search_option_activation==1 && $define_create_order_search_by_productsku==2){  echo $row_sku_serach['product_name']." - "; } ?><?=$row_sku_serach['product_name'];?></li>			
<?php 
		}
		
	}
	$conn->close(); 
	
?>	
 
</ul>
 