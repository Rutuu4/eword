
<?php 
  include("../../database.php"); 
  include("../../session.php");
$getid = base64_decode($_GET['key']);

$qry="SELECT product_order.*,user.name as username,m_customer.name AS customer_name,m_customer.mobile AS customer_phone,m_customer.email AS customer_email,m_customer.city AS customer_city,m_state.name AS state_name from product_order 
  left join user on product_order.user_id=user.id
  left join  m_customer ON product_order.customer_id = m_customer.id
  left join m_state on m_customer.state=m_state.id
   where product_order.id='$getid'";
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
  <style media="print" type="text/css">
 @media print {
  body * { visibility: hidden; } 
  #PrintDiv * { visibility: visible;
}
  

  #PrintDiv { display: block; }
  
  
    
   } 
</style>
</head>
<body class="<?=$bodyclass?>">
   
<div class="wrapper">
  <?php include("../includes/header.php"); ?>
  <?php include("../includes/sidebar.php"); ?>
  
  <div class="content-wrapper">
<div id="PrintDiv">
    <section class="col-lg-12 connectedSortable">
          <!-- Custom tabs (Charts with tabs)-->
          <!-- /.box-header -->
          <form class="form-horizontal" id="form1" name="form1" method="post" action="" onSubmit="return selIt();" enctype="multipart/form-data" >
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Outword Order-Details</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                     <table id="example1" class="table table-bordered table-striped" style="margin-bottom: 20px">
                                        <thead>
                  <th>Challan No</th>
                  <th>Challan Date</th>
                  <th>Invoice No</th>
                  <th>Invoice Date</th>
                  <th>Create Outword</th>
                  </thead>
                 <tbody>
                <tr>
                   <td><?=$row1['id'];?></td>
                   <td><?=date('d-m-Y', strtotime($row1['created_date']));?></td>
                   <td> <?=$row1['invoice_id'];?></td>
                  <td><?=date('d-m-Y', strtotime($row1['invoice_date']));?></td>
                   <td> <?=$row1['username'];?></td>
                </tr>
                                          
                </tbody>
      </table>   

            
      <table id="example1" class="table table-bordered table-striped">
        <thead>
                  <th>#</th>
                  <th>Product Category</th>
                  <th>product Name</th>
                  <th>Quantity</th>
        </thead>
        <tbody>
<?php
$getuserqry = $conn->query("SELECT product_order_trans.id,product_order_trans.quantity,product.product_name as product_name,product_category.name AS category_name  from  product_order_trans 
  left join product on product_order_trans.product_id=product.id 
  left join product_category on product.product_category_id=product_category.id
  where product_order_trans.order_id='$getid' ");
                  $i = 1;
                  while($row = $getuserqry->fetch_array()){
                    $status=$row['status'];
                ?>
                <tr>
                  <td><?=$i++;?></td>
                  <td><?=$row['category_name'];?></td>
                  <td><?=$row['product_name'];?></td>
                  <td><?=$row['quantity'];?></td>
                </tr>
            <?php } ?>
        </tbody>
     </table>                                   
                                    
                                    
            </div>
            <!-- /.box-body -->
          </div>
          
          
          <div class="box" style="border-top:none;">
            <div class="box-content">
              <div class="box box-info col-md-6">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-pencil-square-o"></i>Customer Details</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                    
         <table id="example1" class="table table-bordered table-striped" style="margin-bottom: 20px">
                                        <thead>
                  <th>Customer Name </th>
                  <th>Customer Phone</th>
                  <th>Customer Email</th>
                  <th>Customer City</th>
                  <th>Customer State</th>
                  <th>Order Status</th>
                  </thead>
                 <tbody>
                <tr>
                   <td><?php echo $row1['customer_name'];  ?></td>
                   <td><?php echo $row1['customer_phone'];  ?></td>
                   <td><?php echo $row1['customer_email'];  ?></td>
                   <td><?php echo $row1['customer_city'];  ?></td>
                   <td><?php echo $row1['state_name'];  ?></td>
                   <td>Confirm</td>
                </tr>
                                          
                </tbody>
      </table>   
                  
                  <div class="clearfix"></div>
                  
                  <!-- /.box-body -->
                  <!-- /.box-footer -->  
              </div>
            </div>            
          </div>
          </form>
        </section>
 
  </div>
  <center><input name="Submit" type="submit" class="btn btn-info " onClick="window.print()" value="Print" /></center>
</div>
</div>
   
  <?php include("../includes/footer.php"); ?>
</div>
 

<?php include("../includes/js-scripts.php"); ?>
</body>
</html>