<?php
include("../database.php");

$qry = "SELECT 
college_university_details.id,
college_university_details.name,
college_university_details.website_link,
college_university_details.status,
m_city.name AS city_name,
m_college_university_type.name AS college_university_type_name,

(
  SELECT GROUP_CONCAT(courses_details.name)
  FROM courses_details
  WHERE FIND_IN_SET(courses_details.id, college_university_details.course_ids)
  ) AS course_names,

(
  SELECT GROUP_CONCAT(m_exrta_course.name)
  FROM courses_details
  LEFT JOIN m_exrta_course ON m_exrta_course.id = courses_details.extra_course_id
  WHERE FIND_IN_SET(courses_details.id, college_university_details.course_ids)
  ) AS sub_main_course_names

FROM 
college_university_details
LEFT JOIN 
m_city ON m_city.id = college_university_details.city_id
LEFT JOIN 
m_college_university_type ON m_college_university_type.id = college_university_details.college_university_type_id
ORDER BY 
CASE
WHEN college_university_details.name REGEXP '^[઀-૿]' THEN 0
ELSE 1
END,
CONVERT(college_university_details.name USING utf8mb4) ASC";

$result = $conn->query($qry);

$delimiter = ","; 


    $filename = "College-University-Report_".  date('d-m-Y') . ".csv"; // Create file name

    //create a file pointer
    $f = fopen('php://memory', 'w'); 

    fputs($f, "\xEF\xBB\xBF");

    //set column headers
    $fields = array('Type','Name','Sub Main Course','Course Name','City','Website Link','Status');
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


$lineData = array($row['college_university_type_name'],$row['name'],$row['sub_main_course_names'],$row['course_names'],$row['city_name'],$row['website_link'],$statuss);
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