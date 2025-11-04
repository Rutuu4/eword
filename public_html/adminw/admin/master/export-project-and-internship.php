<?php
error_reporting(0);
include("../../database.php");

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=project_and_internship_list.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "<table border='1'>";
echo "<tr>
        <th>Sr No.</th>
        <th>Name</th>
        <th>Category (Courses)</th>
        <th>Is MOU</th>
        <th>WhatsApp Number</th>
        <th>City</th>
        <th>Status</th>
    </tr>";

$i = 0;
$qry = "SELECT project_and_internship.*, 
               COALESCE(NULLIF(project_and_internship.whatsapp_number, ''), '-') AS whatsapp_number, 
               city.name AS city,
               GROUP_CONCAT(DISTINCT p_courses.name ORDER BY p_courses.name ASC) AS course_names
        FROM project_and_internship
        LEFT JOIN project_and_internship_courses 
            ON project_and_internship_courses.project_and_internship_id = project_and_internship.id
        LEFT JOIN p_courses 
            ON p_courses.id = project_and_internship_courses.course_id
        LEFT JOIN city 
            ON city.id = project_and_internship.city_id
        GROUP BY project_and_internship.id
        ORDER BY project_and_internship.consultancy_name ASC;";

$result = $conn->query($qry);

while ($row = $result->fetch_array()) {
    $i++;
    $status = $row['status'] == 1 ? "Active" : "Deactive";
    $is_mou = $row['mou_is_present'] == 1 ? "True" : "False";

    echo "<tr>
            <td>{$i}</td>
            <td>{$row['consultancy_name']}</td>
            <td>" . (!empty($row['course_names']) ? $row['course_names'] : '-') . "</td>
            <td>{$is_mou}</td>
            <td>{$row['whatsapp_number']}</td>
            <td>{$row['city']}</td>
            <td>{$status}</td>
          </tr>";
}

echo "</table>";
?>