<?php
error_reporting(0);
include("../../database.php");

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=tuition_and_training_list.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "<table border='1'>";
echo "<tr style='background-color:#D6EEEE; font-weight:bold;'>
        <th>Sr No.</th>
        <th>Name</th>
        <th>Courses</th>
        <th>Is MOU</th>
        <th>WhatsApp Number</th>
        <th>City</th>
        <th>Near By Area</th>
        <th>Institution Link</th>
        <th>Class Type</th>
        <th>Status</th>
    </tr>";

$i = 0;
$qry = "SELECT tuition_and_training.*, 
               COALESCE(NULLIF(tuition_and_training.whats_app_number, ''), '-') AS whats_app_number,
               city.name AS city_name,
               GROUP_CONCAT(DISTINCT t_courses.name ORDER BY t_courses.name ASC) AS course_names
        FROM tuition_and_training
        LEFT JOIN tuition_and_training_courses 
            ON tuition_and_training_courses.tuition_and_training_id = tuition_and_training.id
        LEFT JOIN t_courses 
            ON t_courses.id = tuition_and_training_courses.course_id
        LEFT JOIN city 
            ON city.id = tuition_and_training.city
        GROUP BY tuition_and_training.id
        ORDER BY tuition_and_training.consultancy_name ASC;";

$result = $conn->query($qry);
while ($row = $result->fetch_array()) {
    $i++;

    $status = $row['status'] == 1 ? "Active" : "Deactive";
    $is_mou = $row['mou_is_present'] == 1 ? "True" : "False";
    $class_type = '-';

    // convert comma-separated class type into readable text
    if (!empty($row['class_type'])) {
        $types = explode(',', $row['class_type']);
        $class_type = implode(' | ', $types);
    }

    echo "<tr>
            <td>{$i}</td>
            <td>{$row['consultancy_name']}</td>
            <td>" . (!empty($row['course_names']) ? $row['course_names'] : '-') . "</td>
            <td>{$is_mou}</td>
            <td>{$row['whats_app_number']}</td>
            <td>" . (!empty($row['city_name']) ? $row['city_name'] : '-') . "</td>
            <td>" . (!empty($row['nearby_area']) ? $row['nearby_area'] : '-') . "</td>
            <td>" . (!empty($row['institute_web_url']) ? $row['institute_web_url'] : '-') . "</td>
            <td>{$class_type}</td>
            <td>{$status}</td>
          </tr>";
}

echo "</table>";
