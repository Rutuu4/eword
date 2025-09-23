<?php
  include("../database.php");
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=$softtitle?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <?php include("includes/css-scripts.php"); ?>

</head>

<body class="<?=$bodyclass?>">

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
                                    Foreign Education List
                                    <a href="master/create-foreign-education.php"
                                        class="btn btn-primary pull-right">Create
                                        Foreign Education </a>
                                </h4>
                            </div>
                        </div>

                        <div class="box">
                            <div class="box-body table-responsive">
                                <table id="datatable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th style="width:20%">Name</th>
                                            <th style="width:20%">Course</th>
                                            <th>Establishment Year </th>
                                            <th>City </th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                 
                  $i = 0;
                  $qry = "SELECT foreign_education.*,   city.name as city,
       COALESCE(f_courses.name, 'No Course Assigned') AS course_name 
FROM foreign_education 
LEFT JOIN f_courses ON f_courses.id = foreign_education.course_id 
   LEFT JOIN city ON city.id = foreign_education.city
ORDER BY 
    CASE 
        WHEN foreign_education.consultancy_name REGEXP '^[઀-૿]' THEN 0 
        ELSE 1 
    END,
    foreign_education.consultancy_name ASC;
";
                  $result = $conn->query($qry);
                  while($row = $result->fetch_array()){
                      $i++;
                      $status = $row['status'];
                      if($status == 1){
                          $statuss = "<span class=\"label label-success\">Active</span>";
                      } else {
                          $statuss = "<span class=\"label label-warning\">Deactive</span>";
                      }
                ?>
                                        <tr>
                                            <td><?=$i;?></td>
                                            <td><?=$row['consultancy_name'];?></td>
                                            <td><?=$row['course_name'];?></td>
                                            <td><?=$row['establishment_year'];?></td>
                                            <td><?=$row['city'];?></td>
                                            <td><?=$statuss;?></td>

                                            <td>
                                                <a href="master/edit-foreign-education.php?key=<?=base64_encode($row['id'])?>"
                                                    class="btn btn-warning"><i class="fa fa-edit"></i> Edit</a>

                                                <a button class="btn btn-danger btn-sm"
                                                    onClick="window.open('master/delete-foreign-education.php?id=<?=$row['id'];?>',   'win1','width=950, height=800, menubar=no ,scrollbars=yes,top=50,left=100')"><i
                                                        class="fa fa-trash"></i> Delete </button></a>

                                                <!--   <a button class="btn btn-danger btn-sm" onClick="window.open('master/delete-message-type.php?id=<?=$row['id'];?>',   'win1','width=950, height=800, menubar=no ,scrollbars=yes,top=50,left=100')"><i class="fa fa-trash"></i> Delete </button></a> -->


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
        $('#datatable').DataTable({
            "pageLength": 25 // Set default number of rows per page
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