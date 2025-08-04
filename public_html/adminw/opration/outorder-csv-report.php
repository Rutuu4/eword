<?php
include("../database.php"); 

$status=$_REQUEST['status'];
$fromdate=$_REQUEST['fromdate']; 
$todate=$_REQUEST['todate'];

$sq1="SELECT product_order.id,product_order.created_date,product_order.invoice_id,product_order.order_name,product_order.order_email,product_order.order_phone_no,product_order.order_address,user.name as user_name,m_customer.name AS customer_name,m_customer.mobile AS customer_phone,m_customer.email AS customer_email,m_customer.address AS customer_address,m_customer.city AS customer_city,m_state.name AS state_name from product_order
    left join user on product_order.user_id=user.id
    left join  m_customer ON product_order.customer_id = m_customer.id
    left join m_state on m_customer.state=m_state.id ";  
    if(!empty($fromdate) AND  !empty($todate)){ $sq1 .= " where product_order.created_date between '$fromdate' and  '$todate'"; }
    if(empty($fromdate) or  empty($todate)){ $sq1 .= " where product_order.created_date BETWEEN CURDATE()+1 - INTERVAL 30 DAY AND CURDATE()+1 "; }  
    $sq1 .= " order by product_order.created_date desc";
    $query=$conn->query($sq1); 

    $delimiter = ",";
    $f_name='Outword-Order-Report_'; 

    $filename = $f_name.  date('d-m-Y') . ".csv"; // Create file name
     
    //create a file pointer
    $f = fopen('php://memory', 'w'); 
     
    //set column headers
    $fields = array('Created Date','Challan No','Invoice Id','Create Username','Product Category','Product Name','Outword Quantity','Quantity Running-Stock','Customer Name','Customer Email','Customer Phone','Customer Address','Customer City','Customer State');
    fputcsv($f, $fields, $delimiter);
     
    //output each row of the data, format line as csv and write to file pointer
    while($row = $query->fetch_assoc()){
        $order_id=$row['id'];
        $date=date('d-m-Y', strtotime($row['created_date']));

         $tmp_category = array();
         $tmp_pname = array();
         $tmp_pqty = array();
         $tmp_runnin_stock = array();

        $productqry="SELECT product_order_trans.quantity,product_order_trans.order_id,product.product_name as product_name,product.product_qty AS running_stock,product_category.name  AS category_name from product_order_trans 
        left join product on product_order_trans.product_id=product.id
        left join product_category on product.product_category_id=product_category.id 
        where product_order_trans.order_id='$order_id'";
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

        
    $lineData = array($date,$row['id'],$row['invoice_id'],$row['user_name'],$category,$product_name,$product_quantity,$product_running_stock,$row['customer_name'],$row['customer_email'],$row['customer_phone'],$row['customer_address'],$row['customer_city'],$row['state_name']);
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