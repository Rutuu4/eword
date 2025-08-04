<?php
include("../database.php"); 

$fromdate=$_REQUEST['fromdate'];
$todate=$_REQUEST['todate'];
$product_id=$_REQUEST['product_id'];

if(!empty($product_id))
{

        $qry="SELECT inword_product.*,inword.po_number,inword.invoice_no,user.name AS user_name,product_category.name As category_name,m_supplier.name AS supplier_name,product.product_name from inword_product
           left join inword on inword_product.inword_id=inword.id
           left join user on inword.user_id=user.id
           left join m_supplier on inword.supplier_id=m_supplier.id
           left join product_category on inword_product.category_id=product_category.id
           left join product on inword_product.product_id=product.id
          where product_id='$product_id'";
          if(!empty($fromdate) AND  !empty($todate)){  $qry .= " && inword_product.created_date between '$fromdate' and  '$todate'"; }
           if(empty($fromdate) or  empty($todate)){ $qry .= " && inword_product.created_date BETWEEN CURDATE()+1 - INTERVAL 30 DAY AND CURDATE()+1 "; }  
          $result=$conn->query($qry);

    $delimiter = ",";


    $filename = "Inword-Product-Wish-Report_".  date('d-m-Y') . ".csv"; // Create file name
     
    //create a file pointer
    $f = fopen('php://memory', 'w'); 
     
    //set column headers
    $fields = array('Challan No','Create Date','Create Inword','Supplier Name','PO Number','Invoice No','Category Name','Product Name','Outword Quantity');
    fputcsv($f, $fields, $delimiter);
     
    //output each row of the data, format line as csv and write to file pointer
    while($row = $result->fetch_assoc()){
        if(!empty($row['created_date'])){ $date=date("d-m-Y", strtotime($row['created_date'])); } else{ $date='';}
        
        $lineData = array($row['inword_id'],$date,$row['user_name'],$row['supplier_name'],$row['po_number'],$row['invoice_no'],$row['category_name'],$row['product_name'],$row['quantity']);
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
    header("location:inword-product-wish.php");
 }
?>