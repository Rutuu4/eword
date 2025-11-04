<?php
include("../../database.php"); 

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=foreign_education_list.xls");

echo "<table border='1'>";
echo "<tr>
<th>Sr No.</th>
<th>Consultancy Name</th>
<th>Course</th>
<th>Is MOU</th>
<th>WhatsApp Number</th>
<th>City</th>
<th>Status</th>
</tr>";

$qry = "SELECT 
    foreign_education.*, 
    COALESCE(NULLIF(foreign_education.whats_app_number, ''), '-') AS whats_app_number,
    city.name as city,
    COALESCE(f_courses.name, 'No Course Assigned') AS course_name
FROM foreign_education 
LEFT JOIN f_courses ON f_courses.id = foreign_education.course_id 
LEFT JOIN city ON city.id = foreign_education.city
ORDER BY foreign_education.consultancy_name ASC";

$result = $conn->query($qry);
$i = 0;
while ($row = $result->fetch_array()) {
    $i++;
    $status = $row['status'] == 1 ? 'Active' : 'Inactive';
    $is_mou = $row['mou_present'] == 1 ? 'True' : 'False';

    echo "<tr>
        <td>{$i}</td>
        <td>{$row['consultancy_name']}</td>
        <td>{$row['course_name']}</td>
        <td>{$is_mou}</td>
        <td>{$row['whats_app_number']}</td>
        <td>{$row['city']}</td>
        <td>{$status}</td>
    </tr>";
}

echo "</table>";
exit;
?>