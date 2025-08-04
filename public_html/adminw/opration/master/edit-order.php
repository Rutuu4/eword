<?php 
  include("../../database.php"); 
if ($_SESSION['usersession']=="") 
{
header('Location:../index.php');
}
 
$getid = base64_decode($_GET['key']);

  $qry="SELECT product_order.*,user.name AS order_create_name,m_customer.name AS customer_name,m_customer.mobile AS customer_phone,m_customer.address AS customer_address,m_customer.city AS customer_city,m_state.name AS state_name from product_order  
  left join user on product_order.user_id=user.id
  left join m_customer on product_order.customer_id=m_customer.id
  left join m_state on m_customer.state=m_state.id
   where product_order.id='$getid' "; 
  $rs=$conn->query($qry); 
  $row1=$rs->fetch_array();
  $statuss=$row1['status']; 
 
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

    <section class="col-lg-12 connectedSortable">
          <!-- Custom tabs (Charts with tabs)-->
          <!-- /.box-header -->
          <form class="form-horizontal" id="form1" name="form1" method="post" action="" onSubmit="return selIt();" enctype="multipart/form-data" >
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Order Details</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <div class="col-md-6">
            <div class="form-group">
                      <label for="passwrod" class="col-sm-4">Order ID :</label>
                      <div class="col-sm-8">
                       <?=$row1['id'];?>
                      </div>
                    </div>
                     <div class="clearfix"></div>
                     <div class="clearfix"></div>
                    <div class="form-group">
                      <label for="passwrod" class="col-sm-4">Order Date :</label>
                      <div class="col-sm-8">
                      <?=date('d-m-Y H:i:s', strtotime($row1['created_date']));?>
                      </div>
                    </div>
                    <div class="clearfix"></div>
                     <div class="form-group">
                      <label for="passwrod" class="col-sm-4">Customer Name:</label>
                      <div class="col-sm-8">
                     <?=$row1['customer_name'];?>
                      </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                      <label for="passwrod" class="col-sm-4">Customer Address :</label>
                      <div class="col-sm-8">
                      <?=$row1['customer_address'];?>
                      </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                      <label for="passwrod" class="col-sm-4">Customer State :</label>
                      <div class="col-sm-8">
                      <?=$row1['state_name'];?>
                      </div>
                    </div>
            </div>
            <div class="col-md-6">
                     <div class="form-group">
                      <label for="passwrod" class="col-sm-4">Order Create Name: </label>
                     <div class="col-sm-8">
                     <?=$row1['order_create_name'];?>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="passwrod" class="col-sm-4">Invoice No :</label>
                      <div class="col-sm-8">
                       <?=$row1['invoice_id'];?>
                      </div>
                    </div>
                    <div class="clearfix"></div>
                      <div class="form-group">
                      <label for="passwrod" class="col-sm-4">Customer Phone:</label>
                      <div class="col-sm-8">
                     <?=$row1['customer_phone'];?>
                      </div>
                    </div>
                    <div class="clearfix"></div>
                      <div class="form-group">
                      <label for="passwrod" class="col-sm-4">Customer City:</label>
                      <div class="col-sm-8">
                     <?=$row1['customer_city'];?>
                      </div>
                    </div>
            </div>
                     <div class="clearfix" style="margin-bottom: 37px "></div>

            
              <table id="example1" class="table table-bordered table-striped">
                  <thead>
                        <th>#</th>
                        <th>product Name</th>
                        <th>Quantity</th>
                  </thead>
                  <tbody>
                <?php
                $getuserqry = $conn->query("SELECT product_order_trans.id, product.id as product_id,product_order_trans.quantity,product.product_name as product_name from  product_order_trans 
                  left join product on product_order_trans.product_id=product.id 
                  where product_order_trans.order_id='$getid' ");
                  $i = 1;
                  while($row = $getuserqry->fetch_array()){  
                ?>
                <tr>
                  <td><?=$i++;?></td>
                  <td><?=$row['product_name'];?></td>
                    <td><?=$row['quantity'];?></td>
                </tr>  
                <?php } ?>
                </tbody>
               <tfoot>
              </tfoot>
            </table>                                   
                                    
                                    
            </div>
            <!-- /.box-body -->
          </div>
          
          
          <div class="box" style="border-top:none;">
            <div class="box-content">
              <div class="box box-info col-md-6">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-pencil-square-o"></i> Order Status Details</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                  <div class="col-md-6 box-body"> 
             <div class="form-group">
                      <label for="address" class="col-sm-4">Remark :</label>
                      <div class="col-sm-8">
                  <input type="hidden" name="h1" value="1">
                  <input type="hidden" name="old_status" value="<?php echo $row1['status']; ?>">
                  <textarea name="remark" type="text"  class="form-control" id="remark" placeholder="Remark"><?=$row1['remark'];?></textarea>
                      
                      </div>
                    </div>
                  </div>
                  
                  <div class="clearfix"></div>
                  
                  <!-- /.box-body -->
                  <div class="box-footer">
                    <button type="submit" name="update" class="btn btn-info ">Save changes</button>
                    <button type="reset" onClick="history.go(-1);" class="btn btn-default pull-right">Cancel</button>
                  </div>
                  <!-- /.box-footer -->  
              </div>
            </div>            
          </div>
          </form>
        </section>

  </div>
  <?php include("../includes/footer.php"); ?>
</div>

<?php include("../includes/js-scripts.php"); ?>
</body>
</html>