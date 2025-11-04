<?php
error_reporting(0);
include("../../database.php");

// Set headers for Excel download
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=job_and_placement_list.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Query the data
$qry = "SELECT 
    job_placements.*,
    COALESCE(NULLIF(job_placements.whatsapp_number, ''), '-') AS whatsapp_number,
    city.name as city,
    GROUP_CONCAT(DISTINCT j_openings.position_name ORDER BY j_openings.position_name ASC) AS openning_names
FROM job_placements
LEFT JOIN job_placements_openings ON job_placements_openings.job_placement_id = job_placements.id
LEFT JOIN j_openings ON j_openings.id = job_placements_openings.opening_id
LEFT JOIN city ON city.id = job_placements.city_id
GROUP BY job_placements.id
ORDER BY job_placements.company_name ASC;";

$result = $conn->query($qry);

// Start Excel output
echo "<table border='1'>";
echo "<tr style='background-color:#D6EEEE; font-weight:bold;'>
        <th>Sr. No.</th>
        <th>Company Name</th>
        <th>Openings</th>
        <th>Is MOU</th>
        <th>WhatsApp Number</th>
        <th>City</th>
        <th>Status</th>
      </tr>";

$i = 1;
while ($row = $result->fetch_assoc()) {
    $status = $row['status'] == 1 ? 'Active' : 'Deactive';
    $is_mou = $row['is_mou'] == 1 ? 'True' : 'False';
    $openings = !empty($row['openning_names']) ? $row['openning_names'] : 'No Opening Assigned';

    echo "<tr>
            <td>{$i}</td>
            <td>{$row['company_name']}</td>
            <td>{$openings}</td>
            <td>{$is_mou}</td>
            <td>{$row['whatsapp_number']}</td>
            <td>{$row['city']}</td>
            <td>{$status}</td>
          </tr>";
    $i++;
}
echo "</table>";
exit;
?>