<?php 
  include("../../database.php"); 
    $error_msg=[];

if($_POST['h1']==1)
{

      $create_datetime=date('Y-m-d H:i:s');
      $main_courses_id = $_POST['main_courses_id'];

      $filename=$_FILES["file"]["tmp_name"];
      $mimes = array('text/plain', 'text/csv', 'text/comma-separated-values','application/vnd.ms-excel');
      if($_FILES["file"]["size"] > 0 && in_array($_FILES['file']['type'],$mimes))
      {

              $handle = fopen($filename, "r"); 
              if($handle)
              { 

                      $array = explode("\n", fread($handle, filesize($filename)));

                      $total_array=count($array)-1;
                      $i=0;

                      $chkdata=explode(",", $array[0]);

                      $name=$chkdata[0];

                      if(!empty($name))
                      {

                                  while($i<$total_array-1)
                                  {
                                        $i++;
                                        $data=explode(",", $array[$i]);
                                        $name=$data[0];
                                       

                                        $qry_chk="SELECT id FROM m_exrta_course where name='$name'";
                                        $result_chk=$conn->query($qry_chk);
                                        $rowcount=mysqli_num_rows($result_chk);
                                        if($rowcount>0)
                                        {
                                                
                                                $error_msg[]='This Name Already Exists';
                                                $error_sku[]=$data[0];

                                        }
                                        else
                                        {
                                           $insert_array[] ="('$main_courses_id','$login_id','$create_datetime','$name','1')";
                                        }


                                        
                                                                             
                                  }
                                  
                      }
              }
              else
              { 
                  echo "<span class=\"style97\">CSV Data Format Invalid.. Plz Renter CSV Column Field as Per Rules</span>"; 
              }
      }
      else
      { 
        echo "<span class=\"style97\">Only CSV file allow to upload , Please Renter CSV File upload.</span>"; 
      }


      if(!empty($insert_array))
      {

         echo $qury2="INSERT INTO m_exrta_course(main_courses_id,user_id, create_datetime, name, status)
          values".implode(',',$insert_array)."";
        $sq2=$conn->query($qury2);

      }

       
if(empty($error_msg))
{
  header("location:../manage-sub-course.php");
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
                  <h3 class="box-title"><i class="fa fa-pencil-square-o"></i> CREATE MAIN COURSE WITH CSV </h3>
                </div>
                     <div class="box-body">
                       <div class="form-group">
                      <label for="address" class="col-sm-2">Select Main Course:</label>
                      <div class="col-sm-5">
                    <select class="form-control" name="main_courses_id"  id="main_courses_id" required="">
                          <option value="">Select Main Course</option>
                          <?php
                            $qrys = $conn->query("SELECT id,name FROM m_main_courses WHERE status = 1 and isExtra=1");
                            while($rows = $qrys->fetch_array()){
                              ?>
                              <option value="<?=$rows['id']?>"><?=$rows['name']?> </option>
                              <?php
                            }
                          ?>
                        </select>
                      </div>
                    </div>

                              <div class="form-group">
                      <label for="email" class="col-sm-2">Upload File  :</label>
                      <div class="col-sm-5">
                        <input name="file" id="qimage" type="file"  class="file" multiple=true data-preview-file-type="any">
                      </div>
                      <div class="col-sm-2">
                      </div>
                        <div class="col-sm-1">
                          <a href="../../download-sub-course.csv" download="download-sub-course.csv" class="btn btn-success" download role="button">Dwonload Demo File</a>
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

 </body>
</html>