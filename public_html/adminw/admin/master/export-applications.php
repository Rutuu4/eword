<?php
include("../../database.php");

$type = $_GET['type'] ?? '';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=" . $type . "_applications.xls");

echo "<table border='1'>";

switch ($type) {

    /* ================= FOREIGN EDUCATION ================= */
    case 'foreign':

        echo "<tr>
                <th>Name</th>
                <th>Course</th>
                <th>Exam</th>
                <th>Country</th>
                <th>Whatsapp</th>
                <th>Date</th>
              </tr>";

        $res = $conn->query("
            SELECT name, course_for_applying, exam_preference,
                   preferred_country, whatsapp_number, created_at
            FROM f_student_application
            ORDER BY id DESC
        ");

        while ($row = $res->fetch_assoc()) {
            $date = date('Y-m-d', strtotime($row['created_at']));
            echo "<tr>
                    <td>{$row['name']}</td>
                    <td>{$row['course_for_applying']}</td>
                    <td>{$row['exam_preference']}</td>
                    <td>{$row['preferred_country']}</td>
                    <td>{$row['whatsapp_number']}</td>
                      <td>{$date}</td>
                  </tr>";
        }
        break;

    /* ================= JOB PLACEMENT ================= */
    case 'job':

        echo "<tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Experience</th>
                <th>Expected CTC</th>
                <th>Date</th>
              </tr>";

        $res = $conn->query("
            SELECT name, phone_number, role_applying_for,
                   year_of_experience, expected_ctc,created_at
            FROM job_application
            ORDER BY id DESC
        ");

        while ($row = $res->fetch_assoc()) {
            $date = date('Y-m-d', strtotime($row['created_at']));
            echo "<tr>
                    <td>{$row['name']}</td>
                    <td>{$row['phone_number']}</td>
                    <td>{$row['role_applying_for']}</td>
                    <td>{$row['year_of_experience']}</td>
                    <td>{$row['expected_ctc']}</td>
                      <td>{$date}</td>
                  </tr>";
        }
        break;

    /* ================= COLLEGE ================= */
    case 'college':

        echo "<tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Course</th>
                <th>Sub Course</th>
                <th>Passing Year</th>
                <th>Whatsapp</th>
                <th>Date</th>
              </tr>";

        $res = $conn->query("
            SELECT name, contact_number, course_type,
                   sub_course_type, passing_year, whatsAppNumber,created_at
            FROM college_application_form
            ORDER BY id DESC
        ");

        while ($row = $res->fetch_assoc()) {
            $date = date('Y-m-d', strtotime($row['created_at']));
            echo "<tr>
                    <td>{$row['name']}</td>
                    <td>{$row['contact_number']}</td>
                    <td>{$row['course_type']}</td>
                    <td>{$row['sub_course_type']}</td>
                    <td>{$row['passing_year']}</td>
                    <td>{$row['whatsAppNumber']}</td>
                     <td>{$date}</td>
                  </tr>";
        }
        break;

    /* ================= INTERNSHIP ================= */
    case 'internship':

        echo "<tr>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Domain</th>
                                <th>Job Type</th>
                                <th>Whatsapp</th>
                                <th>Date</th>
              </tr>";

        $res = $conn->query("
           SELECT name,phone_number,domain,job_type,whatsapp_number,created_at FROM p_student_application ORDER BY id DESC
        ");

        while ($row = $res->fetch_assoc()) {
            $date = date('Y-m-d', strtotime($row['created_at']));
            echo "<tr>
            
                    <td>{$row['name']}</td>
                    <td>{$row['phone_number']}</td>
                    <td>{$row['domain']}</td>
                    <td>{$row['job_type']}</td>
                    <td>{$row['whatsapp_number']}</td>
                      <td>{$date}</td>
                  </tr>";
        }
        break;

    /* ================= TUITION ================= */
    case 'tuition':

        echo "<tr>
                <th>Name</th>
                                <th>Phone</th>
                                <th>Course</th>
                                <th>Class Type</th>
                                <th>Whatsapp</th>
                                <th>Date</th>
              </tr>";

        $res = $conn->query("
           SELECT name,phone_number,course_applying,class_type,whatsapp_number,created_at FROM t_student_application ORDER BY id DESC
        ");

        while ($row = $res->fetch_assoc()) {
            $date = date('Y-m-d', strtotime($row['created_at']));
            echo "<tr>
                    <td>{$row['name']}</td>
                    <td>{$row['phone_number']}</td>
                    <td>{$row['course_applying']}</td>
                    <td>{$row['class_type']}</td>
                    <td>{$row['whatsapp_number']}</td>
                      <td>{$date}</td>
                  </tr>";
        }
        break;
}

echo "</table>";
exit;
