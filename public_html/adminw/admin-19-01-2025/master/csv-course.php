<?php 
  include("../../database.php"); 
    $error_msg=[];

if ($_POST['h1'] == 1 && $_POST['main_courses_id'] > 0) {
    $insert_array = [];
    $create_datetime = date('Y-m-d H:i:s');
    $main_courses_id = $_POST['main_courses_id'];
     $extra_course_id=mysqli_real_escape_string($conn,$_POST['extra_course_id']);
    $filename = $_FILES["file"]["tmp_name"];

    // Define allowed MIME types
    $allowed_mimes = ['text/plain', 'text/csv', 'application/vnd.ms-excel'];

    // Check file size and MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $file_mime = finfo_file($finfo, $filename);
    finfo_close($finfo);

    if ($_FILES["file"]["size"] > 0) {
        if (($handle = fopen($filename, "r")) !== false) {
            $i = 0;
            $error_msg = [];
            $error_sku = [];
            $header = fgetcsv($handle); // Read the header row

            if ($header && !empty($header[0])) { // Ensure header row is valid
                while (($data = fgetcsv($handle)) !== false) {
                    $name = mysqli_real_escape_string($conn, trim($data[0]));
                    $video_link = mysqli_real_escape_string($conn, trim($data[1]));
                    //$details = mysqli_real_escape_string($conn, preg_replace('/[^a-zA-Z0-9\s]/', '', $data[2]));
                    //$details = str_replace(",", "/", $data[2]); // Replace commas with slashes
                    $details='';
                    // Check if the name already exists
                   $qry_chk = "SELECT id FROM courses_details WHERE name = ? AND main_courses_id = ?";
                    $stmt_chk = $conn->prepare($qry_chk);
                    $stmt_chk->bind_param("si", $name, $main_courses_id);  
                    // "s" for $name (string), "i" for $main_courses_id (integer)
                    $stmt_chk->execute();
                    $stmt_chk->store_result();

                    if ($stmt_chk->num_rows > 0) {
                        $error_msg[] = 'This Name Already Exists';
                        $error_sku[] = $name;
                    } else {
                        // Insert the data
                        $status = 1;
                        $qry_insert = "INSERT INTO courses_details (user_id, create_datetime, main_courses_id, name, video_link, details, status, extra_course_id) VALUES ('$login_id','$create_datetime','$main_courses_id','$name','$video_link','$details','$status','$extra_course_id')";
                        $stmt_insert = $conn->query($qry_insert);
                       
                    }

                  
                }
            } else {
                echo "<span class=\"style97\">CSV Data Format Invalid. Please Re-enter CSV Column Fields as Per Rules.</span>";
            }

            fclose($handle);
        }
    } else {
        echo "<span class=\"style97\">Only CSV files are allowed. Please upload a valid CSV file.</span>";
    }

    // Redirect if no errors
    if (empty($error_msg)) {
        header("Location: ../manage-course.php");
        exit;
    }
}


?>
 <!DOCTYPE html>
<html> 
<head>
<base href="<?=$base_path?>">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?=$softtitle?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <?php include("../includes/css-scripts.php"); ?>

  <style >
    .error{
      color: #d84444;
    }
    .control-label{
      text-align: left!important;
    }
    .form-control{
      display: block;
      width: 100%;
      height: 34px;
      padding: 6px 12px;
      font-size: 14px;
      line-height: 1.42857143;
      color: #555;
      background-color: #fff;
      background-image: none;
      border: 1px solid #ccc;
  
}
 #country-list li {
    padding: 3px 6px;
    background: #fff;
    color: #5d5d5d;
  }
    #country-list {
    float: left;
    list-style: none;
    margin-top: -1px;
    padding: 0;
    width: 20%;
    position: absolute;
    z-index: 9;
    border: 1px solid #e4e4e4;
    border-radius: 3px;

    }
    #country-list li:hover {
    background: #3c8dbc;
    cursor: pointer;
    color: #fff;
      }
.datepicker-dropdown {
  z-index: 99999 !important;

}

  </style>
</head>
<body class="<?=$bodyclass?>">
<div class="wrapper">
  <?php include("../includes/header.php"); ?>
  <?php include("../includes/sidebar.php"); ?>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row"> </div>
      <!-- /.row -->
      <!-- Main row -->
      <div class="row">
            <form class="form-horizontal" id="form-product-update" name="form1" method="post" action="" onSubmit="return selIt();" enctype="multipart/form-data" autocomplete="off">
              <input type="hidden" name="h1" value="1">
        <!-- Left col -->
          <section class="col-lg-12 connectedSortable">
          <!-- Custom tabs (Charts with tabs)-->
          <!-- /.box-header -->
          <div class="box" style="border-top:none;">
            <div class="box-content">
              <div class="box box-info col-md-6">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-pencil-square-o"></i> CREATE COURSE WITH CSV </h3>
                </div>
                     <div class="box-body">
                       <div class="form-group">
                      <label for="usernamee" class="col-sm-2">Main Course :</label>
                      <div class="col-sm-8">

                           <select name="main_courses_id"  id="main_courses_id"  class="form-control select2"  required>
                            <option value=""> Select Main Course </option>
                            <?php 
                            $sqlb="SELECT id,name FROM m_main_courses where status=1";
                            $resultb = $conn->query($sqlb);
                            while($rowb = $resultb->fetch_array())
                            {
                              ?>
                              <option value="<?=$rowb['id'];?>"> <?=$rowb['name'];?> </option>
                              <?php } ?>
                                  </select>
                      </div>
                    </div>
                    <div id="showextra">
                      
                    </div>
                              <div class="form-group">
                      <label for="email" class="col-sm-2">Upload File  :</label>
                      <div class="col-sm-7">
                        <input name="file" id="qimage" type="file"  class="file" multiple=true data-preview-file-type="any">
                      </div>
                      <div class="col-sm-1">
                      </div>
                        <div class="col-sm-1">
                          <a href="../../download-course.csv" download="download-course.csv" class="btn btn-success" download role="button">Dwonload Demo File</a>
                        </div>
                    </div>

           
          </div>      
               
              </div>
            </div>            
          </div>
        </section>
        <section class="col-lg-12 connectedSortable">
          <!-- Custom tabs (Charts with tabs)-->
          <!-- /.box-header -->
         
          
          <div class="col-md-12" style="padding:0;">
          <div class="box">
          <div class="box-body">

           <input name="h1" type="hidden" id="h1" value="1">
             <input name="purchase_invoice_id" type="hidden"  value="<?=$row['id']?>">
             <input name="purchase_invoice_qty" type="hidden"  value="<?=$purchase_invoice_qty;?>">
           <input name="redirect_page" type="hidden" id="redirect_page" value="<?=$_SERVER['HTTP_REFERER'];?>" />
          
          <div class="col-md-12">
          <div class="box " style="border-top:none;">
            <div class="box-content">
              <div class="box box-info">
                <div class="box-header with-border">
                </div>
                <div class="box-body">
                                 
               
                  
                  <!-- /.box-body -->
                  <div class="box-footer" style="border-top:none;">
                    <button type="submit" class="btn btn-info ">Save changes</button>
                    <button type="reset" onClick="history.go(-1);" class="btn btn-default pull-right">Cancel</button>
                  </div>
                  <!-- /.box-footer -->
               
                
              </div>
            </div>            
          </div>
          </div>
           </form>

<form action="master/not-insert-order-list" method="post">
  


 <?php 
 if(count($error_msg)>0) { ?>   

<div class="box" style="margin-top:32px;">
  <div class="box-header with-border">
                  <h3 class="box-title"> Not Insert Details List </h3>
                </div>
            <div class="box-body">
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Error Details</th>
                      <th>Name</th>
                      
                    </tr>
                  </thead>

                   <tbody>

<?php
  $j=1;
for($i=0; $i<count($error_msg); $i++)
{

?>

                    <tr>
                      <td><?=$j;?></td>
                      <td><?=$error_msg[$i];?></td>
                      <td><?=$error_sku[$i];?></td>
                    </tr>
<?php $j++; } ?>

</tr>
                  </tbody>

                </table>
              </div>
            </div>
          </div>
         </form>
<?php } ?>


          </div>
 

         
          
           <?php if(strlen($msg)>1){ ?>
          <div class="col-md-12">

          <div class="panel">
             
            <div class="panel-body">

              <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?=$msg;?> <a href="" class="alert-link"></a>.
              </div>
 
            </div>
          </div><!-- panel -->

        </div>
          <?php } ?>  
          </div>
          </div>
           </div>
        </section>
         
        </div>
        <!-- /.Left col -->
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <!--<section class="col-lg-4 connectedSortable">

      
               

        </section>-->
        <!-- right col -->
      </div>
  <?php include("../includes/footer.php"); ?>
</div>

<?php include("../includes/js-scripts.php"); ?>
<script type="text/javascript">
  $('#mySelect').on('change', function() {
  platfrom_id = $(this).val();
});
</script>

<script>
$(document).ready(function(){
   $("#companyid").change(function(){
    var companyid = $(this).find(":selected").val();
    if(companyid != ""){
      $.ajax({
        type: "POST",
        url: "master/ajax/ajax-company-id-store-in-session.php",
        data: {companyid:companyid},
        success: function(response) {
          location.reload();
        }
      });
    }
  });
});
</script>
<script type="text/javascript">
  $(".select2").select2();
$('.datepicker').datepicker({ autoclose: true,  format: "dd-mm-yyyy"  });
</script>
<script type="text/javascript">
  $('#order_date').on('change', function() {
  order_date = $(this).val();
  //alert(order_date);

      if(order_date != ""){
      $.ajax({
        type: "POST",
        url: "master/ajax/ajax-set-session-order-date.php",
        data: {order_date:order_date},
        
      })
    }
});
</script>

<script>
$(document).ready(function(){
   $("#main_courses_id").change(function(){
    var main_courses_id = $(this).find(":selected").val();
    if(main_courses_id != ""){
      $.ajax({
        type: "POST",
        url: "master/ajax/ajax-show-extra-course.php",
        data: {main_courses_id:main_courses_id},
        success: function(response) {
          $("#showextra").html(response);
        }
      });
    }
  }); });
  </script>
 </body>
</html>