<?php 
  include("../../database.php"); 
$login_id;
if(isset($_POST['submit'])  && count($_POST['pname'])>=1)
{

  $order_id=$_POST['order_id'];
  $remark=mysqli_real_escape_string($conn,$_POST['remark']);
  $created_date=date("Y-m-d"); 
  $invoice_id=mysqli_real_escape_string($conn,$_POST['invoice_id']);
  $invoice_date =date("Y-m-d", strtotime($_POST['invoice_date']));
  $customer_id=mysqli_real_escape_string($conn,$_POST['customer_id']);
  $txn_date=date('Y-m-d h:i:s');


  for($ckoutt=0;$ckoutt<count($_POST['pname']);$ckoutt++) 
  { 
    $updated_id=$_POST['updated_id'][$ckoutt];
    $product_id=$_POST['pid'][$ckoutt];
    $qry_productunit="SELECT product_unit from product where id='$product_id'";
    $result_productunit=$conn->query($qry_productunit);
    $row_productunit=$result_productunit->fetch_array();
    $unit=$row_productunit['product_unit'];
        $quantity=$_POST['pqty'][$ckoutt];
        $old_product_qty=$_POST['old_product_qty'][$ckoutt];
        $status=1;

  $qry_order="UPDATE product_order SET invoice_id='$invoice_id',invoice_date='$invoice_date',customer_id='$customer_id' WHERE id='$order_id'";
  $sq1=$conn->query($qry_order);
        
        if($updated_id>0)
        {

          $product_update="UPDATE product_order_trans SET product_id='$product_id', quantity='$quantity', unit='$unit', status='$status' where id='$updated_id'";
          $sq3 = $conn->query($product_update);



          if($quantity > $old_product_qty)
          {
            $runing_quantity=$quantity-$old_product_qty;

            $qry_update="UPDATE product SET product_qty=product_qty-$runing_quantity WHERE id ='$product_id'";
            $sq2=$conn->query($qry_update);   

          }
          else
          {
            $runing_quantity=$old_product_qty-$quantity;

             $qry_update="UPDATE product SET product_qty=product_qty+$runing_quantity WHERE id ='$product_id'";
            $sq2=$conn->query($qry_update);                 
          }
        }
        else
        {

          if($product_id>0)
          {
             $qry_update="UPDATE product SET product_qty=product_qty-$quantity WHERE id ='$product_id'";
           $sq4=$conn->query($qry_update);

           $insert_product_array[] ="(NULL,'$order_id','$product_id','$quantity','$unit','$status','$created_date')";
            
          }
          
        }
         
      }
        if($order_id>0 && count($insert_product_array)>0)
        {
        
           $qury2="insert into product_order_trans(id, order_id, product_id, quantity, unit, status, created_date) 
         values ".implode(',',$insert_product_array)."";        
          $sq3=$conn->query($qury2); 

        }
        if($sq1==TRUE or $sq3==TRUE)
        {
              $qury5="INSERT INTO txn_history_report(txn_type, txn_id, uid, txn_date) VALUES ('2','$order_id','$login_id','$txn_date')";
              $sq5=$conn->query($qury5);
        }

        //query success (COMMIT) and fail (ROLLBACK) if condition
          if($sq1 && $sq2 && $sq3 && $sq4 && $sq5){
          $conn->query("COMMIT");
          }else{
          $conn->query("ROLLBACK");
           }           
        
        header("location: ../outword-order-report.php");
        
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
             <form class="form-horizontal" id="form-search-product" name="form-search-product"  autocomplete="off">
             <input type="hidden" id="search_product_id"  class="form-control"  value=""/>
             <input type="hidden" id="define_create_order_search_option_activation"  class="form-control"  value="<?=$define_create_order_search_option_activation;?>"/>
             <input type="hidden" id="dis_gst_charge_apply"  class="form-control"  value="<?=$dis_gst_charge_apply;?>"/>
             <input type="hidden" id="dis_gst_charge_by_default_fixed"  class="form-control"  value="<?=$dis_gst_charge_by_default_fixed;?>"/>
             <input type="hidden" id="gst_tax_perproduct"  class="form-control"  value="0"/>
             <input type="hidden" id="define_advanced_order_product_additional_parameter_shopcart_filter_activation"  class="form-control"  value="<?=$define_advanced_order_product_additional_parameter_shopcart_filter_activation;?>"/>
             
             
             
             
              <table id="example2" class="table table-bordered table-striped" style="margin-bottom:12px;">
                                       
                                            <tr>
                                               <th style="border:1px solid #c2d3de;background: #f0f8fd;"> 
                    <select class="form-control" name="category_id" required="" id="mySelect">
                    <option value="">Select Category</option>
                    <?php 
                      $query2 ="SELECT * FROM product_category WHERE status = 1";
                      $result2=$conn->query($query2);
                      while($row2 = $result2->fetch_array()){
                    ?>
                    <option value="<?=$row2['id']?>"><?=$row2['name']?></option>
                  <?php } ?>
                  </select>
                        </th>  
                      <th style="border:1px solid #c2d3de;background: #f0f8fd;"> 
                      <input type="text" id="search-box" placeholder="Search Product" class="form-control" autocomplete="off" value=""/>
                      <div id="suggesstion-box"></div></th>
                                                <th style="border:1px solid #c2d3de;background: #f0f8fd;"><input type="text" name="serach_product_name" id="serach_product_name" placeholder="Product Name" class="form-control" value="" required></th>
                                                <th style="border:1px solid #c2d3de;background: #f0f8fd;"><input type="text" name="serach_product_qty" id="serach_product_qty" placeholder="Quantity" class="form-control" value="" required pattern="[0-9]{1,20}" style="width:125px;"></th>
                                               <th style="border:1px solid #c2d3de;background: #f0f8fd;"><input name="submit" type="submit" class="add btn btn-primary" value="+ ADD"></th>
                                            </tr>
           </table>
              </form>                          
            
                                                 
                                    
                                    
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
                                                <th><?php if($define_create_order_search_option_activation==1){ ?>Product Sku <?php } ?></th>
                                                <th>Product Category</th>
                                                <th>Product Name</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
<?php
$getid = base64_decode($_GET['key']);
$qry1="SELECT * FROM product_order where id='$getid'";
$result1=$conn->query($qry1);
$row1=$result1->fetch_array();
$order_id=$row1['id'];
$customer_id=$row1['customer_id'];
$created_date = date("d-m-Y", strtotime($row1['created_date']));
$invoice_id = $row1['invoice_id']; 
$invoice_date = date("d-m-Y", strtotime($row1['invoice_date']));

$qry="SELECT product_order_trans.*,Product.product_name,product_category.name AS category_name FROM product_order_trans left join Product on product_order_trans.product_id=Product.id
left join product_category on product.product_category_id=product_category.id
 where product_order_trans.order_id='$getid'";
$result=$conn->query($qry);
while($row=$result->fetch_array())
{
  $old_product_qty=$row['quantity'];

?>

                                         <tr><td><i class="fa fa-close product_delete" data-depid="<?php echo $row['id']; ?>" style="color: #fff;cursor: pointer;background: #ec2814;border-radius: 50%;padding: 3px 5px;"></i></td><td></td>
                                           <input type="hidden" name="order_id" class="form-control"  value="<?=$order_id;?>">
                                          <input type="hidden" name="updated_id[]" class="form-control"  value="<?=$row['id'];?>"> <input type="hidden" name="pid[]" class="form-control"  value="<?=$row['product_id'];?>">
                                           <input type="hidden" name="old_product_qty[]" value="<?php echo $old_product_qty ; ?>"></td>
                                           <td><input name="cname[]" class="form-control" style="width:200px;" type="text" required value="<?php echo $row['category_name']; ?>"><span style="display:inline-block;font-weight:bold;"></td><td><input name="pname[]" class="form-control" style="width:250px;" type="text" required value="<?php echo $row['product_name']; ?>"><td><input type="number" class="form-control updatecartproduct" name="pqty[]" pattern="[0-9]{1,20}" style="width:90px;" required value="<?php echo $row['quantity']; ?>"/></td></tr>
<?php } ?>
                                        </tbody>
                                        <tfoot>
                                        </tfoot>
                                    </table>
                                              <div class="box" style="border-top:none;">
            <div class="box-content">
              <div class="box box-info col-md-6">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-pencil-square-o"></i> OUTWORD DETAILS</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                    
                
          <div class="col-md-6">
            <div class="form-group" style="margin-bottom: 35px">
                      <label for="passwrod" class="col-sm-4">Challan No :</label>
                      <div class="col-sm-8">
                      <input type="text" class="form-control" value="<?php echo $order_id; ?>" disabled>
                      </div>
                    </div>
                     <div class="clearfix"></div>
                     <div class="form-group" style="margin-bottom: 35px">
                      <label for="passwrod" class="col-sm-4">Invoice No :</label>
                      <div class="col-sm-8">
                  <input type="text" class="form-control" name="invoice_id" value="<?php echo $invoice_id; ?>" required>
                      </div>
                    </div>
                     <div class="clearfix"></div>
                    <div class="form-group">
                      <label class="control-label col-sm-4">Customer : </label>
                      <div class="col-sm-8">
                        <select class="form-control" name="customer_id" required>
                          <option value="">Select Customer</option>
                          <?php
                            $qry3 = $conn->query("SELECT * FROM m_customer WHERE status = 1");
                            while($row3 = $qry3->fetch_array()){
                              ?>
                              <option <?php if($customer_id==$row3['id']){ echo "selected" ; } ?> value="<?=$row3['id']?>"><?=$row3['name']?></option>
                              <?php
                            }
                          ?>
                        </select>
                      </div>
                  </div>
                     <div class="clearfix"></div>
      
            </div>
              <div class="col-md-6">
              <div class="form-group" style="margin-bottom: 35px">
                      <label for="passwrod" class="col-sm-4">Challan Date :</label>
                      <div class="col-sm-8">
                       <input type="text" class="form-control pull-right datepicker" name="request_date" value="<?php echo $created_date; ?>" disabled>
                      </div>
                    </div>
                     <div class="clearfix"></div>
                       <div class="form-group"  style="margin-bottom: 35px">
                      <label for="passwrod" class="col-sm-4">Invoice Date :</label>
                     <div class="col-sm-8">
                     <input type="text" class="form-control pull-right datepicker" name="invoice_date" value="<?php echo $invoice_date ; ?>" required>
                      </div>
                    </div>  
                     <div class="clearfix"></div>
                   </div>
                  
                  <div class="clearfix"></div>
                  
                  <!-- /.box-body -->
                  <div class="box-footer">
                    <button type="submit" name="submit" class="btn btn-info ">Save changes</button>
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
  $('#mySelect').on('change', function() {
  category_id = $(this).val();
});
</script>
<script>
function selectproductsku(product_id, product_category, product_name, product_qty) {  
$("#search_product_id").val(product_id);
$("#search-box").val(product_category);
$("#serach_product_name").val(product_name);
$("#serach_product_qty").val(product_qty);
$("#suggesstion-box").hide();
}

$(document).ready(function(){
  $("#search-box").keyup(function(){  
    $.ajax({
    type: "POST",
    url: "master/ajax/auto-complete-serach-find-category1.php",
    data: {keyword: $(this).val(), category_id : category_id},
    beforeSend: function(){
      $("#search-box").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
    },
    success: function(data){  
      $("#suggesstion-box").show();
      $("#suggesstion-box").html(data);
      //alert(data);
      $("#search-box").css("background","#FFF");
    }
    });
  });



$('#form-search-product').on('submit', function (e) { 
e.preventDefault();
var dis_product_id, dis_product_category, dis_product_name, dis_product_img, dis_product_qty=0, dafault_not_availbale_img ;
dis_product_id=$('#search_product_id').val();      
dis_product_category=$('#search-box').val();              
dis_product_name=$('#serach_product_name').val();     
dis_product_qty=$('#serach_product_qty').val();       
define_create_order_search_option_activation=$('#define_create_order_search_option_activation').val();



var markup = '<tr><td><i class="fa fa-close remove-row" style="color: #fff;cursor: pointer;background: #ec2814;border-radius: 50%;padding: 3px 5px;"></i></td>';

//serach product sku 0 1
if(define_create_order_search_option_activation==1){
markup +='<td><input type="hidden" name="updated_id[]" class="form-control"  value=""><input type="hidden" name="pid[]" class="form-control"  value="'+dis_product_id+'"></td>';
}else{
markup +='<td><input type="hidden" name="updated_id[]" class="form-control"  value=""><input type="hidden" name="pid[]" class="form-control"  value="'+dis_product_id+'"></td>';
}


markup +='<td><input name="cname[]" class="form-control" style="width:200px;" type="text" required value="'+dis_product_category+'"><span style="display:inline-block;font-weight:bold;"></td><td><input name="pname[]" class="form-control" style="width:200px;" type="text" required value="'+dis_product_name+'"><span style="display:inline-block;font-weight:bold;"></td><td><input type="number" class="form-control updatecartproduct" name="pqty[]" pattern="[0-9]{1,20}" style="width:70px;" required value="'+dis_product_qty+'"/></td></tr>';
  
if(dis_product_id>0){ $("#example1 tbody").prepend(markup); }else{ alert('Select Appropriate Product');  }
$('#form-search-product')[0].reset(); $("#search_product_id").val(0);
$(".updatecartproduct").trigger("change");
});


$("#example1").on('click','.remove-row',function(){$(this).parent().parent().remove();   $(".updatecartproduct").trigger("change");  });

$(".select2").select2();
$('.datepicker').datepicker({
      autoclose: true,
     format: 'dd-mm-yyyy' 
    });

});
$(document).on('click', '.product_delete', function(e) {
    e.preventDefault();
        var order_product_id = $(this).data("depid");
    if(order_product_id>0){   
       $.ajax({
            type: "POST",
            url: "master/ajax/ajax-delete-order-product.php",
            data: {order_product_id:order_product_id},
            dataType:'json',
            success: function(data){   
      if($.trim(data.lg_valid) === "TRUE"){
      location.reload();
            } } 
    }); 
  } 
  });

</script>
</body>
</html>