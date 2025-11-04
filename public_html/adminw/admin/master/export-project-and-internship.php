<?php
error_reporting(0);
include("../../database.php");

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=project_and_internship_list.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "<table border='1'>";
echo "<tr style='background-color:#D6EEEE; font-weight:bold;'>
        <th>Sr No.</th>
        <th>Name</th>
        <th>Category</th>
        <th>Is MOU</th>
        <th>WhatsApp Number</th>
        <th>City</th>
        <th>Near By Area</th>
        <th>Institution Link</th>
        <th>Job Type</th>
        <th>Status</th>
    </tr>";

$i = 0;
$qry = "SELECT 
            project_and_internship.*, 
            COALESCE(NULLIF(project_and_internship.whatsapp_number, ''), '-') AS whatsapp_number,
            city.name AS city_name,
            GROUP_CONCAT(DISTINCT p_courses.name ORDER BY p_courses.name ASC) AS category_names
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

    // Convert job type string to readable format
    $job_type = '-';
    if (!empty($row['job_type'])) {
        $job_types = explode(',', $row['job_type']);
        $job_type = implode(' | ', $job_types);
    }

    echo "<tr>
            <td>{$i}</td>
            <td>{$row['consultancy_name']}</td>
            <td>" . (!empty($row['category_names']) ? $row['category_names'] : '-') . "</td>
            <td>{$is_mou}</td>
            <td>{$row['whatsapp_number']}</td>
            <td>" . (!empty($row['city_name']) ? $row['city_name'] : '-') . "</td>
            <td>" . (!empty($row['nearby_area']) ? $row['nearby_area'] : '-') . "</td>
            <td>" . (!empty($row['institute_web_url']) ? $row['institute_web_url'] : '-') . "</td>
            <td>{$job_type}</td>
            <td>{$status}</td>
          </tr>";
}

echo "</table>";
