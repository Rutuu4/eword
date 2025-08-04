<?php 
include("../../database.php"); 

$error_message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save'])) {
  $code = mysqli_real_escape_string($conn, $_POST['code']);
  $amount_off = mysqli_real_escape_string($conn, $_POST['amount_off']);
  $total_quantity = (int) $_POST['total_quantity'];
  $status = mysqli_real_escape_string($conn,$_POST['status']);

    // Convert date formats to Y-m-d H:i:s
  $start_date = DateTime::createFromFormat('d-m-Y', $_POST['start_date']);
  $end_date = DateTime::createFromFormat('d-m-Y', $_POST['end_date']);

  if ($start_date && $end_date) {
    $start_date = $start_date->format('Y-m-d H:i:s');
    $end_date = $end_date->format('Y-m-d H:i:s');

    if ($end_date <= $start_date) {
      $error_message = "End date cannot be earlier than start date.";
    } else {

      $insertqry = "INSERT INTO promocode (code, amount_off, start_date, end_date, total_quantity, sold_quantity, status)
      VALUES ('$code', '$amount_off', '$start_date', '$end_date', '$total_quantity', 0, '$status')";

      $exeUpdate = $conn->query($insertqry);

      if ($exeUpdate) {
        header("Location: ../admin-promocode.php");
        exit;
      } else {
        $error_message = "Error updating promocode: " . $conn->error;
      }
    }
  } else {
    $error_message = "Invalid date format.";
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
  <style>
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
  </style>
</head>
<body class="<?=$bodyclass?>">
  <div class="wrapper">
    <?php include("../includes/header.php"); ?>
    <?php include("../includes/sidebar.php"); ?>

    <div class="content-wrapper">
      <section class="content-header">
        <h1>Add Promocode</h1>
      </section>

      <section class="content">

        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Add Promocode</h3>
          </div>

          <div class="box-body">
            <div class="col-sm-10">
             <?php if (!empty($error_message)): ?>
              <div class="alert alert-danger" style="margin-bottom: 20px;">
                <?php echo $error_message; ?>
              </div>
            <?php endif; ?>
            <form action="" method="POST" class="form-horizontal" id="addPromocodeForm">

              <div class="form-group">
                <label class="control-label col-sm-2">Code :</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="code"  placeholder="Please enter code">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2">Amount Off :</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="amount_off"  placeholder="Please enter amount">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-2">Total Quantity :</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="total_quantity"  placeholder="Please enter total quantity">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-2">Start Date :</label>
                <div class="col-sm-10">
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control datepicker" name="start_date" placeholder="Start Date">
                  </div>
                  <label class="error" for="start_date" style="display:none;"></label>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-2">End Date :</label>
                <div class="col-sm-10">
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control datepicker" name="end_date" placeholder="End Date">
                  </div>
                  <label class="error" for="end_date" style="display:none;"></label>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2">Status :</label>
                <div class="col-sm-10" style="padding:7px;">
                  <input type="radio" name="status" value="1" checked=""> Active
                  &nbsp;&nbsp;&nbsp;
                  <input type="radio" name="status" value="0"> Deactive
                </div>
              </div>
              <div class="col-md-12" align="right">
                <input type="submit" name="save" value="Save" class="btn btn-success">
              </div>
            </form>
          </div>
          <div class="col-sm-5">

          </div>
        </div>

      </div>
    </section>
  </div>
  <?php include("../includes/footer.php"); ?>
</div>
<?php include("../includes/js-scripts.php"); ?>
<script>
  $(document).ready(function(){

    $(".select2").select2();

    $.validator.addMethod("dateOrder", function(value, element) {
      var start = $('input[name="start_date"]').val();
      var end = $('input[name="end_date"]').val();

      if (!start || !end) return true;

      var partsStart = start.split('-').reverse().join('-');
      var partsEnd = end.split('-').reverse().join('-');

      return new Date(partsEnd) >= new Date(partsStart);
    }, "End date must be greater than or equal to start date.");

    $( "#addPromocodeForm" ).validate({
      rules: {
        code: { required: true  },
        amount_off : { required: true,number: true },
        start_date: { required: true },
        end_date: { required: true , dateOrder: true },
        total_quantity: { required: true, number: true,  },
      },
      messages: {
        code:  "Code field is required" ,
        amount_off: {
          required: "Amount Off field is required.",
          number: "Please enter a valid number."
        },
        start_date: "Start Date field is required" ,
        end_date: {
          required: "End Date field is required" ,
          dateOrder: "End date must be greater than or equal to start date."
        },
        total_quantity: {
          required: "Total Quantity field is required.",
          number: "Please enter a valid number."
        },
      }
    });
    $('.datepicker').datepicker({
      autoclose: true,
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      startDate: "today" 
    });
  });

</script>
</body>
</html>
