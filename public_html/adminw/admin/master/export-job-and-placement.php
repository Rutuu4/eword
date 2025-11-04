<?php
error_reporting(0);
include("../../database.php");

// Set headers for Excel download
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=job_and_placement_list.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Query with all joined and required fields
$qry = "SELECT 
    jp.*,
    COALESCE(NULLIF(jp.whatsapp_number, ''), '-') AS whatsapp_number,
    c.name AS city,
    GROUP_CONCAT(DISTINCT jo.position_name ORDER BY jo.position_name ASC) AS opening_names
FROM job_placements jp
LEFT JOIN job_placements_openings jpo ON jpo.job_placement_id = jp.id
LEFT JOIN j_openings jo ON jo.id = jpo.opening_id
LEFT JOIN city c ON c.id = jp.city_id
GROUP BY jp.id
ORDER BY jp.company_name ASC;";

$result = $conn->query($qry);

// Start Excel table
echo "<table border='1'>";
echo "<tr style='background-color:#D6EEEE; font-weight:bold;'>
        <th>Sr. No.</th>
        <th>Company Name</th>
        <th>Openings</th>
        <th>Salary</th>
        <th>Required Experience</th>
        <th>Near By Area</th>
        <th>City</th>
        <th>Institute Link</th>
        <th>Is MOU</th>
        <th>WhatsApp Number</th>
        <th>Status</th>
      </tr>";

$i = 1;
while ($row = $result->fetch_assoc()) {
  $status = $row['status'] == 1 ? 'Active' : 'Deactive';
  $is_mou = $row['is_mou'] == 1 ? 'True' : 'False';
  $openings = !empty($row['opening_names']) ? $row['opening_names'] : 'No Opening Assigned';
  $salary = !empty($row['salary']) ? $row['salary'] : '-';
  $required_experience = !empty($row['required_experience']) ? $row['required_experience'] : '-';
  $nearby_area = !empty($row['nearby_area']) ? $row['nearby_area'] : '-';
  $institute_link = !empty($row['company_website']) ? $row['company_website'] : '-';

  echo "<tr>
            <td>{$i}</td>
            <td>{$row['company_name']}</td>
            <td>{$openings}</td>
            <td>{$salary}</td>
            <td>{$required_experience}</td>
            <td>{$nearby_area}</td>
            <td>{$row['city']}</td>
            <td>{$institute_link}</td>
            <td>{$is_mou}</td>
            <td>{$row['whatsapp_number']}</td>
            <td>{$status}</td>
          </tr>";
  $i++;
}
echo "</table>";
exit;
