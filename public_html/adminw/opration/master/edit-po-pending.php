<?php 
  include("../../database.php"); 

if(isset($_POST['submit1']) and $_POST['h1']==1)
{
    
        $count = count($_POST['product_id']);
        $po_id = $_POST['po_id'];
        $created_date=date('Y-m-d'); 
        $chalan_no = $_POST['chalan_no'];
        $invoice_no = $_POST['invoice_no'];
        $grn_no = $_POST['grn_no'];
        $grn_date = date("Y-m-d", strtotime($_POST['grn_date']));
        $status= $_POST['status'];

        $qry="UPDATE PO SET status='$status' where id='$po_id'";
        $po_update=$conn->query($qry);

        for($i=0;$i<$count;$i++)
        {
          $product_id = $_POST['product_id'][$i];
          $verify_quantity = $_POST['verify_quantity'][$i];
          $po_product_id = $_POST['po_product_id'][$i];

             if($verify_quantity>0)
             {
              $qry1="INSERT INTO verify_po(u_id, po_id, po_product_id , product_id, chalan_no, invoice_no, verify_quantity, created_date, grn_no, grn_date)VALUES('$login_id','$po_id','$po_product_id','$product_id',
              '$chalan_no','$invoice_no','$verify_quantity','$created_date','$grn_no','$grn_date')";
              $sq1=$conn->query($qry1);

             $qry2="UPDATE po_product SET quantity_running_stock= quantity_running_stock-'$verify_quantity' WHERE po_id='$po_id' and product_id='$product_id'";
              $sq2=$conn->query($qry2);
             }   

             $po_prodcut="SELECT quantity_running_stock from po_product where po_id='$po_id' and product_id='$product_id'";
              $result1=$conn->query($po_prodcut);
              $rows=$result1->fetch_array();
             $quantity_running_stock= $rows['quantity_running_stock'];
             if($quantity_running_stock<=0)
             {
                 $qry3="UPDATE po_product SET status=0  WHERE po_id='$po_id' and product_id='$product_id'";
                  $sq3=$conn->query($qry3);
             }
        }
        $all_po_product="SELECT status FROM po_product where po_id='$po_id' and status=1";
        $result_po_product=$conn->query($all_po_product);
        $rowcount=mysqli_num_rows($result_po_product);
        if($rowcount<=0)
        {
              $qry4="UPDATE po SET status=0 where id='$po_id'";   
              $sq4=$conn->query($qry4);
        }


        header("location:../pending-po.php");  
        
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
               Verify PO
              </h4>                
            </div>
            <!-- /.box-body -->

          </div>
          
          
          <div class="col-md-12" style="padding:0;">
          <div class="box">
          <div class="box-body">
            <form class="form-horizontal" id="form-product-update" name="form1" method="post" action="" onSubmit="return selIt();" enctype="multipart/form-data" autocomplete="off">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group" style="padding: 35px">
                      <label for="passwrod" class="col-sm-4">GRN No :</label>
                      <div class="col-sm-8">
                      <input type="text" class="form-control"  name="grn_no" required>
                      <input type="hidden" name="h1" value="1">
                      </div>
                    </div>
              </div>
              <div class="col-md-3"> 
                 <div class="form-group"  style="padding:35px">
                      <label for="passwrod" class="col-sm-4">GRN Date :</label>
                     <div class="col-sm-8">
                     <input type="text" class="form-control pull-right datepicker" name="grn_date" value="<?php echo date('d-m-Y'); ?>" required>
                      </div>
                    </div>

              </div>
               <div class="col-md-6"> 
                <div class="form-group" style="padding-top:35px">
                      <label class="control-label col-sm-2">PO Status :</label>
                      <div class="col-sm-10" style="padding:7px;">
                        <input type="radio" name="status" value="1" checked=""> Pending P0
                        &nbsp;&nbsp;&nbsp;
                        <input type="radio" name="status" value="0"> Closed PO
                      </div>
                    </div>

              </div>
              
            </div>
          <table id="example1" class="table table-bordered table-striped" style="margin-bottom:28px;">
                                        <thead>
                                               <tr>
                                                <th>Category Name</th>
                                                <th>Product Name</th>
                                                <th>Total Quantity</th>
                                                <th>Available Quantity</th>
                                                <th>Verify Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
<?php
$getid = base64_decode($_GET['key']);
$qry1="SELECT * FROM po where id='$getid'";
$result1=$conn->query($qry1);
$row1=$result1->fetch_array();
$po_id=$row1['id'];

 if(!empty($row1['request_date'])){ $request_date=date("d-m-Y", strtotime($row1['request_date'])); } else{ $request_date='';}
  if(!empty($row1['po_date'])){ $po_date=date("d-m-Y", strtotime($row1['po_date'])); } else{ $po_date='';}
  if(!empty($row1['invoice_date'])){ $invoice_date=date("d-m-Y", strtotime($row1['invoice_date'])); } else{ $invoice_date='';}
    if(!empty($row1['acceptation_date'])){ $acceptation_date=date("d-m-Y", strtotime($row1['acceptation_date'])); } else{ $acceptation_date='';}
?>
    
  <input type="hidden" name="invoice_no"  value="<?php echo $row1['invoice_no']; ?>">
  <input type="hidden" name="chalan_no" class="form-control"  value="<?php echo $row1['id']; ?>"></td>


<?php
$qry="SELECT po_product.*,product.product_name,product.id AS product_id,product_category.name AS category_name FROM po_product 
  left join product on po_product.product_id=Product.id 
  left join product_category on product.product_category_id=product_category.id
  where po_product.po_id='$getid' and po_product.status=1";
$result=$conn->query($qry);
while($row=$result->fetch_array())
{
?>
    <tr>
    <input type="hidden" name="po_product_id[]" value="<?php echo $row['id'];?>">  
    <input type="hidden" name="po_id" value="<?php echo $po_id ;  ?>">
    <input type="hidden" name="product_id[]" class="form-control"  value="<?php echo $row['product_id']; ?> ">
    <td><p><?php echo $row['category_name'] ?></p></td>
    <td><p><?php echo $row['product_name'] ?></p></td>
    <td><p><?php echo $row['quantity'] ?></p></td>
     <td><p><?php echo $row['quantity_running_stock'] ?></p></td>
  <?php if($row['quantity']>0) {?>
          <td><input type="number" class="form-control updatecartproduct" name="verify_quantity[]" pattern="[0-9]{1,20}" style="width:70px;"/></td>
    <?php } ?>

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
                     <div class="clearfix"></div>
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
                     <div class="clearfix"></div>
                  </div>
                    <div class="clearfix"></div>
                  <div class="box-footer">
                    <button type="submit" name="submit1" class="btn btn-info ">Save</button>
                    <button type="reset" onClick="history.go(-1);" class="btn btn-default pull-right">Cancel</button>
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