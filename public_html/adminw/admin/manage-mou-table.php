<?php
include("../database.php");
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title><?= $softtitle ?></title>
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <?php include("includes/css-scripts.php"); ?>

    <style>
        /* SIMPLE TAB STYLE */
        .simple-tabs {
            list-style: none;
            padding: 0;
            margin: 0 0 15px 0;
            display: flex;
            border-bottom: 2px solid #ddd;
        }

        .simple-tabs li {
            padding: 10px 18px;
            cursor: pointer;
            border: 1px solid #ddd;
            border-bottom: none;
            background: #f4f6f9;
            margin-right: 5px;
            font-weight: 600;
        }

        .simple-tabs li.active {
            background: #fff;
            color: #3c8dbc;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }
    </style>
</head>

<body class="<?= $bodyclass ?>">
    <div class="wrapper">

        <?php include("includes/header.php"); ?>
        <?php include("includes/sidebar.php"); ?>

        <div class="content-wrapper">
            <section class="content">

                <!-- ================= TABS ================= -->
                <ul class="simple-tabs">
                    <li class="active" data-tab="tab1">Foreign Education</li>
                    <li data-tab="tab2">Job Placement</li>
                    <li data-tab="tab3">College</li>
                    <li data-tab="tab4">Project Internship</li>
                    <li data-tab="tab5">Tuition Training</li>
                </ul>

                <!-- ================= TAB 1 ================= -->
                <div id="tab1" class="tab-content active">

                    <h4>
                        Foreign Education List
                        <a href="master/export-applications.php?type=foreign"
                            class="btn btn-success pull-right"
                            style="margin-bottom: 10px;">
                            <i class="fa fa-file-excel-o"></i> Export
                        </a>
                    </h4>

                    <table id="datatable1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Country</th>
                                <th>Institute</th>
                                <th>Whatsapp</th>
                                <th>Date</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $i = 0;

                            $sql = "
                                SELECT 
                                    fsa.name,
                                    fsa.course_for_applying,
                                    fsa.preferred_country,
                                    fsa.whatsapp_number,
                                    fsa.created_at,
                                    fe.consultancy_name
                                FROM f_student_application AS fsa
                                LEFT JOIN foreign_education AS fe
                                    ON fsa.foreign_education_id = fe.id
                                ORDER BY fsa.id DESC
                            ";

                            $res = $conn->query($sql);

                            while ($row = $res->fetch_assoc()) {
                                $i++;
                            ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['course_for_applying']) ?></td>
                                    <td><?= htmlspecialchars($row['preferred_country']) ?></td>
                                    <td><?= $row['consultancy_name'] ? htmlspecialchars($row['consultancy_name']) : '-' ?></td>
                                    <td><?= $row['whatsapp_number'] ? htmlspecialchars($row['whatsapp_number']) : '-' ?></td>
                                    <td><?= date('d-m-Y', strtotime($row['created_at'])) ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                </div>


                <!-- ================= TAB 2 ================= -->
                <div id="tab2" class="tab-content">

                    <h4>
                        Job Placement List
                        <a href="master/export-applications.php?type=job" class="btn btn-success pull-right" style="margin-bottom: 10px;">
                            <i class="fa fa-file-excel-o"></i> Export
                        </a>
                    </h4>

                    <table id="datatable2" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Company Name</th>
                                <th>Role</th>
                                <th>Experience</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            $res = $conn->query(" SELECT 
                ja.name,
                ja.phone_number,
                jp.company_name,
                ja.role_applying_for,
                ja.year_of_experience,
                ja.created_at
            FROM job_application ja
            LEFT JOIN job_placements jp 
                ON jp.id = ja.company_id
            ORDER BY ja.id DESC");
                            while ($row = $res->fetch_assoc()) {
                                $i++; ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= $row['name'] ?></td>
                                    <td><?= $row['phone_number'] ?></td>
                                    <td><?= $row['company_name'] ?? '-' ?></td>
                                    <td><?= $row['role_applying_for'] ?></td>
                                    <td><?= $row['year_of_experience'] ?></td>
                                    <td><?= date('d-m-Y', strtotime($row['created_at'])) ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                </div>

                <!-- ================= TAB 3 ================= -->
                <div id="tab3" class="tab-content">

                    <h4>
                        College List
                        <a href="master/export-applications.php?type=college" class="btn btn-success pull-right" style="margin-bottom: 10px;">
                            <i class="fa fa-file-excel-o"></i> Export
                        </a>
                    </h4>

                    <table id="datatable3" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Course</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            $res = $conn->query("SELECT name,contact_number,course_type,created_at FROM college_application_form ORDER BY id DESC");
                            while ($row = $res->fetch_assoc()) {
                                $i++; ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= $row['name'] ?></td>
                                    <td><?= $row['contact_number'] ?></td>
                                    <td><?= $row['course_type'] ?></td>
                                    <td><?= date('d-m-Y', strtotime($row['created_at'])) ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                </div>

                <!-- ========================================== -->
                <div id="tab4" class="tab-content">

                    <h4>
                        Project Internship List
                        <a href="master/export-applications.php?type=internship"
                            class="btn btn-success pull-right"
                            style="margin-bottom: 10px;">
                            <i class="fa fa-file-excel-o"></i> Export
                        </a>
                    </h4>

                    <table id="datatable4" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Student Number</th>
                                <th>Domain</th>
                                <th>Job Type</th>
                                <th>Institute Name</th>
                                <th>Mou Number</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $i = 0;

                            $sql = "
                        SELECT 
                            psa.name,
                            psa.phone_number,
                            psa.domain,
                            psa.job_type,
                            psa.whatsapp_number,
                            psa.created_at,
                            pai.consultancy_name
                        FROM p_student_application psa
                        LEFT JOIN project_and_internship pai
                            ON psa.project_and_internship_id = pai.id
                        ORDER BY psa.id DESC
                    ";

                            $res = $conn->query($sql);

                            while ($row = $res->fetch_assoc()) {
                                $i++;
                            ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['phone_number']) ?></td>
                                    <td><?= htmlspecialchars($row['domain']) ?></td>
                                    <td><?= htmlspecialchars($row['job_type']) ?></td>
                                    <td><?= htmlspecialchars($row['consultancy_name'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($row['whatsapp_number']) ?></td>
                                    <td><?= date('d-m-Y', strtotime($row['created_at'])) ?></td>
                                </tr>
                            <?php } ?>

                        </tbody>
                    </table>

                </div>

                <!--=====================================-->

                <div id="tab5" class="tab-content">

                    <h4>
                        Tuition Training List
                        <a href="master/export-applications.php?type=tuition" class="btn btn-success pull-right" style="margin-bottom: 10px;">
                            <i class="fa fa-file-excel-o"></i> Export
                        </a>
                    </h4>

                    <table id="datatable5" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Course</th>
                                <th>Class Type</th>
                                <th>Institute Name</th>
                                <th>Whatsapp</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;

                            $res = $conn->query("
                            SELECT 
                                tsa.name,
                                tsa.phone_number,
                                tsa.course_applying,
                                tsa.class_type,
                                tsa.whatsapp_number,
                                tsa.created_at,
                                tat.consultancy_name
                            FROM t_student_application AS tsa
                            LEFT JOIN tuition_and_training AS tat
                                ON tsa.tuition_and_training_id = tat.id
                            ORDER BY tsa.id DESC
                        ");

                            while ($row = $res->fetch_assoc()) {
                                $i++;
                            ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['phone_number']) ?></td>
                                    <td><?= htmlspecialchars($row['course_applying']) ?></td>
                                    <td><?= htmlspecialchars($row['class_type']) ?></td>
                                    <td><?= htmlspecialchars($row['consultancy_name'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($row['whatsapp_number']) ?></td>
                                    <td><?= date('d-m-Y', strtotime($row['created_at'])) ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                </div>


            </section>
        </div>

        <?php include("includes/footer.php"); ?>
    </div>

    <?php include("includes/js-scripts.php"); ?>

    <script>
        $(document).ready(function() {

            console.log('Simple tabs loaded');

            // Init DataTables
            $('#datatable1,#datatable2,#datatable3,#datatable4,#datatable5').DataTable({
                pageLength: 25
            });

            // Simple tab click
            $('.simple-tabs li').click(function() {

                let tabId = $(this).data('tab');

                $('.simple-tabs li').removeClass('active');
                $(this).addClass('active');

                $('.tab-content').removeClass('active');
                $('#' + tabId).addClass('active');

                // Fix datatable width
                $.fn.dataTable.tables({
                    visible: true,
                    api: true
                }).columns.adjust();

            });

        });
    </script>

</body>

</html>