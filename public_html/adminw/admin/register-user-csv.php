<?php
include("../database.php");

$qry="SELECT 
    registration.*, 
    m_main_courses.name AS main_course_name, 
    m_exrta_course.name AS exrta_course_name, 
    GROUP_CONCAT(courses_details.name ORDER BY courses_details.id) AS course_name_list, 
    GROUP_CONCAT(DISTINCT m_city.name ORDER BY m_city.id) AS city_name_list 
FROM registration 
LEFT JOIN m_main_courses 
    ON m_main_courses.id = registration.interesting_main_course_id 
LEFT JOIN m_exrta_course 
    ON m_exrta_course.id = registration.interesting_exrta_course_id 
LEFT JOIN m_city 
    ON FIND_IN_SET(m_city.id, registration.interesting_city_ids) 
LEFT JOIN courses_details 
    ON FIND_IN_SET(courses_details.id, registration.course_details_ids) 
    AND (registration.interesting_exrta_course_id != 1 OR registration.interesting_exrta_course_id IS NULL)
WHERE registration.is_app_admin = 0 ";
$qry .= " GROUP BY registration.id ORDER BY registration.id DESC";     
$result = $conn->query($qry);

    $delimiter = ","; 


    $filename = "Register-User-Report_".  date('d-m-Y') . ".csv"; // Create file name
     
    //create a file pointer
    $f = fopen('php://memory', 'w'); 
     
    //set column headers
    $fields = array('Date','Username','Name','Email','Residence City','Current Study','College/University','Study City','Interesting Main Course','Exrta Course','Interesting Course','Interesting City','Status','Chat Status');
    fputcsv($f, $fields, $delimiter);
     
    //output each row of the data, format line as csv and write to file pointer
    while($row = $result->fetch_assoc())
    {

if (!empty($row['datee'])) {  
    $datee = date("d-m-Y", strtotime($row['datee'])); 
    $datee = "\t" . $datee; // Add tab space to force Excel to treat it as text
} else {  
    $datee = '';  
}

 $status=$row['status'];
if($status==1){ $statuss="Active"; } else { $statuss="Deactive"; }

 $is_chat_block=$row['is_chat_block'];
if($is_chat_block==1){ $is_chat_blockk="Block"; } else { $is_chat_blockk="Active"; }


    
        
        $lineData = array($datee,$row['username'],$row['fullname'],$row['email'],$row['city'],$row['current_study'],$row['college_university_name'],$row['study_city'],$row['main_course_name'],$row['exrta_course_name'],$row['course_name_list'],$row['city_name_list'],$statuss,$is_chat_blockk);
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