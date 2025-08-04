<?php
include("../database.php"); 

$sq1="SELECT product_order.id,product_order.created_date,product_order.invoice_id,product_order.sub_total_amount,product_order.cash_discount_amount,product_order.scheme_discount_amount,product_order.net_total_amount,product_order.cgst_tax_amount,product_order.sgst_tax_amount,product_order.igst_tax_amount,product_order.grand_total_amount,product_order.order_name,product_order.order_email,product_order.order_phone_no,product_order.order_address,register1.name as user_name from product_order left join register1 on product_order.user_id=register1.id where product_order.status=1 order by product_order.created_date desc"; 
    $query=$conn->query($sq1); 

    $delimiter = ",";


    $filename = "Panding-Order-Report_".  date('d-m-Y') . ".csv"; // Create file name
     
    //create a file pointer
    $f = fopen('php://memory', 'w'); 
     
    //set column headers
    $fields = array('Created Date','Invoice Id','Create Username','Product Name','Product Quantity','Sub Total Amount','Cash Discount Amount','Scheme Discount Amount','Net Total Amount','CGST Tax Amount','SGST Tax Amount','Igst Tax Amount','Grand Total Amount','Distributor Name','Customer Name','Customer Email','Customer Phone','Customer Address');
    fputcsv($f, $fields, $delimiter);
     
    //output each row of the data, format line as csv and write to file pointer
    while($row = $query->fetch_assoc()){
        $order_id=$row['id'];
        $date=date('d-m-Y', strtotime($row['created_date']));

         $tmp_pname = array();
         $tmp_pqty = array();

        $productqry="SELECT product_order_trans.quantity,product.product_name as product_name from product_order_trans left join product on product_order_trans.product_id=product.id  where product_order_trans.order_id='$order_id'";
        $reuslt_p = $conn->query($productqry);
        while($productrow = $reuslt_p->fetch_array())
        {
              $tmp_pname[]=$productrow['product_name'];
              $tmp_pqty[]=$productrow['quantity'];
        }
         $product_name=implode(", ",$tmp_pname);
         $product_quantity=implode(",",$tmp_pqty);
        
        $lineData = array($date,$row['invoice_id'],$row['user_name'],$product_name,$product_quantity,$row['sub_total_amount'],$row['cash_discount_amount'],$row['scheme_discount_amount'],$row['net_total_amount'],$row['cgst_tax_amount'],$row['sgst_tax_amount'],$row['igst_tax_amount'],$row['grand_total_amount'],$row['order_name'],$row['order_email'],$row['order_phone_no'],$row['order_address']);
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