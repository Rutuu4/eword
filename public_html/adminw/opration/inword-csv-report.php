<?php
include("../database.php"); 

$fromdate=$_REQUEST['fromdate'];
$todate=$_REQUEST['todate'];

    $sq1="SELECT inword.*,m_supplier.name AS supplier_name,user.name AS user_name from inword 
    left join m_supplier on inword.supplier_id=m_supplier.id  
    left join user on inword.user_id=user.id 
    where inword.status=1";
    if(!empty($fromdate) AND  !empty($todate)){ $sq1 .= " && inword.created_date between '$fromdate' and  '$todate'"; }
    if(empty($fromdate) or  empty($todate)){ $sq1 .= " && inword.created_date BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()+1 "; }  
    $sq1 .= " order by inword.created_date desc";
    $query=$conn->query($sq1); 

    $delimiter = ",";
    $f_name='Inword-Report_';
    $filename = $f_name.  date('d-m-Y') . ".csv"; // Create file name
     
    //create a file pointer
    $f = fopen('php://memory', 'w'); 
     
    //set column headers
    $fields = array('Created Date','Created User','Challan No','PO No','PO Date','Request Date','Supplier Name','Product Category','Product Name','Inwrod Quantity','Quantity Running-Stock');
    fputcsv($f, $fields, $delimiter);
     
    //output each row of the data, format line as csv and write to file pointer
    while($row = $query->fetch_assoc()){
        $inword_id=$row['id'];
        $created_date=date('d-m-Y', strtotime($row['created_date']));
        $request_date=date('d-m-Y', strtotime($row['request_date']));
        $po_date=date('d-m-Y', strtotime($row['po_date']));


       
        $tmp_category = array();
        $tmp_pname = array();
        $tmp_pqty = array();
        $tmp_runnin_stock = array();

        $productqry="SELECT inword_product.quantity,inword_product.batch_no,inword_product.purchase_price,inword_product.quantity_running_stock,product_category.name  AS category_name,product.product_name,product.product_qty AS running_stock from inword_product 
        left join product_category on inword_product.category_id=product_category.id
        left join product on inword_product.product_id=product.id  

                where inword_product.inword_id='$inword_id'";
        $reuslt_p = $conn->query($productqry);
        while($productrow = $reuslt_p->fetch_array())
        {
              $tmp_category[]=$productrow['category_name'];  
              $tmp_pname[]=$productrow['product_name'];
              $tmp_pqty[]=$productrow['quantity'];
              $tmp_runnin_stock[]=$productrow['running_stock'];

        }
         $category=implode(", ",$tmp_category);
         $product_name=implode(", ",$tmp_pname);
         $product_quantity=implode(",",$tmp_pqty);
         $product_running_stock=implode(",",$tmp_runnin_stock);

         
        
        $lineData = array($created_date,$row['user_name'],$row['id'],$row['po_number'],$po_date,$request_date,$row['supplier_name'],$category,$product_name,$product_quantity,$product_running_stock);
        fputcsv($f, $lineData, $delimiter);
    }
    //move back to beginning of file
    fseek($f, 0);
     
    //set headers to download file rather than displayed
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');
     
    //output all remaining data on a file pointer
    fpassthru($f);
 
?>