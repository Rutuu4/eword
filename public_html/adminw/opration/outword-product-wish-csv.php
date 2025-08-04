<?php
include("../database.php"); 

$fromdate=$_REQUEST['fromdate'];
$todate=$_REQUEST['todate'];
$product_id=$_REQUEST['product_id'];

if(!empty($product_id))
{

    $qry="SELECT product_order_trans.*,user.name AS user_name,m_customer.name AS customer_name,product.product_name,product_category.name AS category_name from product_order_trans
        left join product_order on product_order_trans.order_id=product_order.id
        left join user on product_order.user_id=user.id
        left join m_customer on product_order.customer_id=m_customer.id
        left join product on product_order_trans.product_id=product.id
        left join product_category on product.product_category_id=product_category.id
        where product_id='$product_id'";
          if(!empty($fromdate) AND  !empty($todate)){  $qry .= " && product_order_trans.created_date between '$fromdate' and  '$todate'"; }
           if(empty($fromdate) or  empty($todate)){ $qry .= " && product_order_trans.created_date BETWEEN CURDATE()+1 - INTERVAL 30 DAY AND CURDATE()+1 "; }  
          $result=$conn->query($qry);

    $delimiter = ",";


    $filename = "Outword-Product-Wish-Report_".  date('d-m-Y') . ".csv"; // Create file name
     
    //create a file pointer
    $f = fopen('php://memory', 'w'); 
     
    //set column headers
    $fields = array('Challan No','Create Date','Create Outword','Customer Name','Category Name','Product Name','Outword Quantity');
    fputcsv($f, $fields, $delimiter);
     
    //output each row of the data, format line as csv and write to file pointer
    while($row = $result->fetch_assoc()){
        if(!empty($row['created_date'])){ $date=date("d-m-Y", strtotime($row['created_date'])); } else{ $date='';}
        
        $lineData = array($row['order_id'],$date,$row['user_name'],$row['customer_name'],$row['category_name'],$row['product_name'],$row['quantity']);
        fputcsv($f, $lineData, $delimiter);
    }
    //move back to beginning of file
    fseek($f, 0);
     
    //set headers to download file rather than displayed
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');
     
    //output all remaining data on a file pointer
    fpassthru($f);
 
 }
 else
 {
    header("location:outword-product-wish.php");
 }
?>