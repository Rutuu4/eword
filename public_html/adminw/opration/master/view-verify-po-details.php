<?php 
  include("../../database.php"); 
  if(empty($_GET['key'])){
    echo "<a href='index.php'>Server Issue Click Here to Go back</a>";
    exit;
  }else{
    $getid = base64_decode($_GET['key']);
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
  </style>
</head>
<body class="<?=$bodyclass?>">
<div class="wrapper">
  <?php include("../includes/header.php"); ?>
  <?php include("../includes/sidebar.php"); ?>
  <div class="content-wrapper">

           <section class="col-lg-12 connectedSortable">
          <!-- Custom tabs (Charts with tabs)-->
          <!-- /.box-header -->
          <div class="box">
            
            <!-- /.box-header -->
            <div class="box-body">  
              <h4>
               Verify Purchase Order Details
              </h4>                
            </div>
            <!-- /.box-body -->
          </div>
          
          
          <div class="col-md-12" style="padding:0;">
          <div class="box">
          <div class="box-body">
           <form class="form-horizontal" id="form-product-update" name="form1" method="post" action="" onSubmit="return selIt();" enctype="multipart/form-data" autocomplete="off">
           <input name="h1" type="hidden" id="h1" value="<?=$_SESSION['token'] ?>">
                   <table id="example1" class="table table-bordered table-striped" style="margin-bottom:28px;">
                                        <thead>
                                               <tr>
                                                <th>#</th>
                                                <th>Varify Date</th>
                                                <th>GRN No</th>
                                                <th>Product Category</th>
                                                <th>Product Name</th>
                                                <th>Varify Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
<?php
$qry1="SELECT * FROM po where id='$getid'";
$result1=$conn->query($qry1);
$row1=$result1->fetch_array();
$po_id=$row1['id'];

 if(!empty($row1['request_date'])){ $request_date=date("d-m-Y", strtotime($row1['request_date'])); } else{ $request_date='';}
  if(!empty($row1['po_date'])){ $po_date=date("d-m-Y", strtotime($row1['po_date'])); } else{ $po_date='';}
  if(!empty($row1['invoice_date'])){ $invoice_date=date("d-m-Y", strtotime($row1['invoice_date'])); } else{ $invoice_date='';}
  if(!empty($row1['acceptation_date'])){ $acceptation_date=date("d-m-Y", strtotime($row1['acceptation_date'])); } else{ $acceptation_date='';}


$qry="SELECT verify_po.*,product.product_name,product_category.name AS category_name from verify_po 
left join product on verify_po.product_id=product.id
left join product_category on product.product_category_id=product_category.id
where verify_po.po_id='$getid'";
$result=$conn->query($qry);
$i=1;
while($row=$result->fetch_array())
{
  if(!empty($row['created_date'])){ $created_date=date("d-m-Y", strtotime($row['created_date'])); } else{ $created_date='';}
?>
    <tr>  
    <td><p><?php echo $i++; ?></p></td>
    <td><p><?php echo $created_date; ?></p></td>
    <td><p><?php echo $row['grn_no']; ?></p></td>
    <td><p><?php echo $row['category_name']; ?></p></td>
    <td><p><?php echo $row['product_name']; ?></p></td>
    <td><p><?php echo $row['verify_quantity']; ?></p></td>


    </tr>
<?php } ?>  
                                        </tbody>
                                        <tfoot>
                                        </tfoot>
                                    </table>
          <div class="box" style="border-top:none;">
            <div class="box-content">
              <div class="box box-info col-md-6">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-pencil-square-o"></i>PO DETAILS</h3>
                </div>
<div class="col-md-6">
            <div class="form-group" style="margin-bottom: 35px">
                      <label for="passwrod" class="col-sm-4">Challan No :</label>
                      <div class="col-sm-8">
                      <input type="text" class="form-control" value="<?=$row1['id'];?>" disabled>
                      </div>
                    </div>
                     <div class="clearfix"></div>
                     <div class="form-group" style="margin-bottom: 35px">
                      <label for="passwrod" class="col-sm-4">PO No :</label>
                      <div class="col-sm-8">
                      <input type="text" class="form-control" name="po_number" value="<?=$row1['po_number'];?>" disabled>
                      </div>
                    </div>
                     <div class="clearfix"></div>
                     <div class="clearfix"></div>
                     <div class="form-group" style="margin-bottom: 35px">
                      <label for="passwrod" class="col-sm-4">Invoice No :</label>
                      <div class="col-sm-8">
                      <input type="text" class="form-control"  value="<?php echo $row1['invoice_no']; ?>" name="invoice_no" disabled>
                      </div>
                    </div>
                    <div class="clearfix"></div>
                     <div class="form-group" style="margin-bottom: 35px">
                      <label for="passwrod" class="col-sm-4">Acceptation No :</label>
                      <div class="col-sm-8">
                      <input type="text" class="form-control" value="<?php echo $row1['acceptation_no']; ?>" name="acceptation_no" disabled>
                      </div>
                    </div>
                    <div class="clearfix"></div>
                     <div class="form-group">
                      <label for="passwrod" class="col-sm-4">Select-Supplier :</label>
                       <div class="col-sm-8">

                        <select class="form-control" name="supplier_id" disabled>
                          <option value="">Select-Supplier</option>
                          <?php
                            $qrys = $conn->query("SELECT * FROM m_supplier WHERE status = 1");
                            while($rows = $qrys->fetch_array()){
                              ?>
                              <option <?php if($row1['supplier_id']==$rows['id']) echo "selected";?> value="<?=$rows['id']?>"><?=$rows['name']?>  => <?=$rows['city']?></option>
                              <?php
                            }
                          ?>
                        </select>
                      </div>
                    </div>

                    
            </div>
            <div class="col-md-6">
                    <div class="clearfix"></div>
              <div class="form-group" style="margin-bottom: 35px">
                      <label for="passwrod" class="col-sm-4">Challan  Date :</label>
                      <div class="col-sm-8">
                       <input type="text" class="form-control pull-right datepicker" name="request_date" value="<?php echo $request_date; ?>" disabled>
                      </div>
                    </div>
                     <div class="clearfix"></div>
                     <div class="form-group"  style="margin-bottom: 35px">
                      <label for="passwrod" class="col-sm-4">PO Date :</label>
                     <div class="col-sm-8">
                     <input type="text" class="form-control pull-right datepicker" name="po_date" value="<?php echo $po_date ; ?>" disabled>
                      </div>
                    </div>
                     <div class="clearfix"></div>
                      <div class="form-group"  style="margin-bottom: 35px">
                      <label for="passwrod" class="col-sm-4">Invoice Date :</label>
                     <div class="col-sm-8">
                     <input type="text" class="form-control pull-right datepicker" name="invoice_date" value="<?php echo $invoice_date; ?>" disabled>
                      </div>
                    </div>
                     <div class="clearfix"></div>
                      <div class="form-group"  style="margin-bottom: 35px">
                      <label for="passwrod" class="col-sm-4">Acceptation Date :</label>
                     <div class="col-sm-8">
                     <input type="text" class="form-control pull-right datepicker" name="acceptation_date" value="<?php echo $acceptation_date; ?>" disabled>
                      </div>
                    </div>
                  </div>
                  <!-- /.box-footer -->  
              </div>
            </div>            
          </div>
          

          </form>
          
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
  <?php include("../includes/footer.php"); ?>
</div>

<?php include("../includes/js-scripts.php"); ?>
<script type="text/javascript">
  $('.datepicker').datepicker({
      autoclose: true,
     format: 'dd-mm-yyyy' 
    });
</script>
</body>
</html>