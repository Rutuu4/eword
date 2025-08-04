<?php
include("../database.php");



$fromdate=$_POST['fromdate'];
$todate=$_POST['todate']; 

       $date=date('Y-m-d');
            $qry="SELECT registration.id,package_subscription_order.subscription_expire_date,package_subscription_order.amount,package_subscription_order.txnid,package_subscription_order.date,registration.fullname,registration.username AS user_mobile_number,subscription_package.name AS subscription_package_name,chat_course.name AS chat_course_name  FROM package_subscription_order 
            LEFT JOIN registration ON package_subscription_order.user_id = registration.id
            LEFT JOIN  subscription_package ON package_subscription_order.subscription_package_id = subscription_package.id
            LEFT JOIN chat_course ON chat_course.id = registration.chat_course_id
            where package_subscription_order.payment_gateway_success_status=1 and package_subscription_order.subscription_expire_date>'$date'";
          if(!empty($fromdate) AND  !empty($todate)){  $qry .= " && package_subscription_order.date between '$fromdate' and  '$todate'"; }
           if(empty($fromdate) or  empty($todate)){ $qry .= " && package_subscription_order.date >= DATE_SUB(CURDATE(),INTERVAL 365 DAY)"; }  
           $qry .= " order by package_subscription_order.date desc";    
$result = $conn->query($qry);

    $delimiter = ","; 


    $filename = "Active-Subscription-Report_".  date('d-m-Y') . ".csv"; // Create file name
     
    //create a file pointer
    $f = fopen('php://memory', 'w'); 
     
    //set column headers
    $fields = array('Date','Expire Date','Package Name','Package Chat Course','User Name','User Mobile','Amount','Transaction ID','Status');
    fputcsv($f, $fields, $delimiter);
     
    //output each row of the data, format line as csv and write to file pointer
    while($row = $result->fetch_assoc())
    {

if (!empty($row['date'])) {  
    $date = date("d-m-Y", strtotime($row['date'])); 
    $date = "\t" . $date; // Add tab space to force Excel to treat it as text
} 
else 
{  
    $date = '';  
}

$subscription_expire_date=date("d-m-Y", strtotime($row['subscription_expire_date']));

 $status = "Success";

    
        
        $lineData = array($date,$subscription_expire_date,$row['subscription_package_name'],$row['chat_course_name'],$row['fullname'],$row['user_mobile_number'],$row['amount'],$row['txnid'],$status);
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