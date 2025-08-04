<?php
include("../database.php");
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= $softtitle ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <?php include("includes/css-scripts.php"); ?>

</head>

<body class="<?= $bodyclass ?>">

  <div class="wrapper">

    <?php include("includes/header.php"); ?>
    <?php include("includes/sidebar.php"); ?>

    <div class="content-wrapper">

      <section class="content">
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <!--
            <div class="box-header">
              <h3 class="box-title">Hover Data Table</h3>
            </div>
              -->
              <div class="box-body">
                <h4>
                  Manage College University List
                  <a href="manage-college-university-csv.php" class="btn btn-primary pull-right" style="margin-left:10px;">Export Excel</a>
                  <a href="master/create-college-university.php" class="btn btn-primary pull-right">Create College/University</a>
                </h4>
              </div>
            </div>

            <div class="box">
              <div class="box-body table-responsive">
                <table id="datatable" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Sr No.</th>
                      <th>Type</th>
                      <th>Name </th>
                      <th>Sub Main Course </th>
                      <th>Course </th>
                      <th>City </th>
                      <th>Website Link</th>
                      <th>Status</th>
                      <th>Action</th>

                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $i = 0;
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

                    while ($row = $result->fetch_array()) {
                      $i++;
                      $status = $row['status'];
                      if ($status == 1) {
                        $statuss = "<span class=\"label label-success\">Active</span>";
                      } else {
                        $statuss = "<span class=\"label label-warning\">Deactive</span>";
                      }
                    ?>
                      <tr>
                        <td><?= $i; ?></td>
                        <td><?= $row['college_university_type_name']; ?></td>
                        <td><?= $row['name']; ?></td>
                        <td><?= $row['sub_main_course_names']; ?></td>
                        <td><?= $row['course_names']; ?></td>
                        <td><?= $row['city_name']; ?></td>
                        <td><?= $row['website_link']; ?></td>
                        <td><?= $statuss; ?></td>


                        <td>
                          <a button class="btn btn-warning edit-button" data-id="<?= base64_encode($row['id']) ?>"><i class="fa fa-edit"></i> Edit</a>

                          <a button class="btn btn-danger btn-sm delete-button" data-id="<?= base64_encode($row['id']) ?>" ><i class="fa fa-trash"></i> Delete </button></a>

                        </td>
                      </tr>
                    <?php } ?>
                </table>
              </div>
            </div>
          </div>
        </div>
      </section>

    </div>
    <?php include("includes/footer.php"); ?>
  </div>

  <?php include("includes/js-scripts.php"); ?>
  <script>
    $(document).ready(function() {
      //datatable
      var table = $('#datatable').DataTable({
        "pageLength": 25 // Set default number of rows per page
      });

      const urlParams = new URLSearchParams(window.location.search);
      const pageNumber = urlParams.get('page');

      if (pageNumber) {
        table.page(parseInt(pageNumber) - 1).draw(false); // Navigate to the correct page (DataTables uses 0-based indexing)
      } else { 
        table.page(0).draw(false);
      }

      $(document).on('click', '.edit-button', function(e) {
        e.preventDefault();

        var id = $(this).data("id");
        var page = table.page.info().page + 1;

        var newUrl = "master/edit-college-university.php?key=" + id + "&page=" + page;
        window.location.href = newUrl;
      });

      $(document).on('click', '.delete-button', function(e) {
        var idToDelete = $(this).data("id");
        var currentPage = table.page.info().page + 1;

        window.open('master/delete-college-university.php?id=' + idToDelete + '&page=' + currentPage,
          'win1',
          'width=950, height=800, menubar=no ,scrollbars=yes,top=50,left=100');
      });


      $(".deletestate").click(function() {
        var key = $(this).data("key");
        if (confirm('Are you sure you want to delete this?')) {
          $.ajax({
            url: 'master/delete-state.php',
            type: "POST",
            data: {
              key: key
            },
            success: function(response) {
              if (response == "TRUE" && response != "") {
                location.reload();
              } else {
                alert("Please Try Again .!");
              }
            }
          });
        }
      });
    });
  </script>
</body>

</html>