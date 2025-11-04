<?php
include("../../database.php");

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=foreign_education_list.xls");

echo "<table border='1'>";
echo "<tr style='background-color:#D6EEEE; font-weight:bold;'>
<th>Sr No.</th>
<th>Consultancy Name</th>
<th>Courses</th>
<th>Visa Types</th>
<th>Exam Types</th>
<th>Countries</th>
<th>Nearby Area</th>
<th>Institute Link</th>
<th>Is MOU</th>
<th>WhatsApp Number</th>
<th>City</th>
<th>Status</th>
</tr>";

$qry = "
SELECT 
    fe.*,
    city.name AS city_name
FROM foreign_education fe
LEFT JOIN city ON city.id = fe.city
ORDER BY fe.consultancy_name ASC
";

$result = $conn->query($qry);
$i = 0;
while ($row = $result->fetch_assoc()) {
    $i++;

    // ✅ Status and MOU
    $status = $row['status'] == 1 ? 'Active' : 'Inactive';
    $is_mou = $row['mou_present'] == 1 ? 'True' : 'False';

    // ✅ Courses (foreign_education.course_ids stores comma-separated IDs)
    $courses = '-';
    if (!empty($row['course_ids'])) {
        $course_ids = $row['course_ids'];
        $sql_course = "SELECT GROUP_CONCAT(name SEPARATOR ', ') as names FROM f_courses WHERE id IN ($course_ids)";
        $res_course = $conn->query($sql_course);
        $courses = $res_course->fetch_assoc()['names'] ?? '-';
    }

    // ✅ Visa Types
    $visa_types = '-';
    $sql_visa = "
        SELECT GROUP_CONCAT(visa_type.name SEPARATOR ', ') as names 
        FROM foreign_education_visa_types fev
        LEFT JOIN visa_type ON visa_type.id = fev.visa_type_id
        WHERE fev.foreign_education_id = {$row['id']}
    ";
    $res_visa = $conn->query($sql_visa);
    if ($res_visa && $res_visa->num_rows > 0) {
        $visa_types = $res_visa->fetch_assoc()['names'] ?? '-';
    }

    // ✅ Exam Types
    $exam_types = '-';
    $sql_exam = "
        SELECT GROUP_CONCAT(exam_type.name SEPARATOR ', ') as names 
        FROM foreign_education_exam_types fee
        LEFT JOIN exam_type ON exam_type.id = fee.exam_type_id
        WHERE fee.foreign_education_id = {$row['id']}
    ";
    $res_exam = $conn->query($sql_exam);
    if ($res_exam && $res_exam->num_rows > 0) {
        $exam_types = $res_exam->fetch_assoc()['names'] ?? '-';
    }

    // ✅ Countries
    $countries = '-';
    $sql_country = "
        SELECT GROUP_CONCAT(country.name SEPARATOR ', ') as names 
        FROM foreign_education_countries fec
        LEFT JOIN country ON country.id = fec.country_id
        WHERE fec.foreign_education_id = {$row['id']}
    ";
    $res_country = $conn->query($sql_country);
    if ($res_country && $res_country->num_rows > 0) {
        $countries = $res_country->fetch_assoc()['names'] ?? '-';
    }

    // ✅ Output each record
    echo "<tr>
        <td>{$i}</td>
        <td>{$row['consultancy_name']}</td>
        <td>{$courses}</td>
        <td>{$visa_types}</td>
        <td>{$exam_types}</td>
        <td>{$countries}</td>
        <td>{$row['nearby_area']}</td>
        <td>{$row['institute_url']}</td>
        <td>{$is_mou}</td>
        <td>{$row['whats_app_number']}</td>
        <td>{$row['city_name']}</td>
        <td>{$status}</td>
    </tr>";
}

echo "</table>";
exit;
