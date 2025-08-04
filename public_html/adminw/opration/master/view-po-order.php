
<?php 
  include("../../database.php"); 
  include("../../session.php");
$getid = base64_decode($_GET['key']);

$qry="SELECT po.*,user.name,m_supplier.name AS supplier_name,m_supplier.mobile,m_supplier.email,m_supplier.city,m_state.name AS State_name from  po 
left join user on po.user_id=user.id
left join m_supplier on po.supplier_id=m_supplier.id
left join m_state on m_supplier.state=m_state.id
where po.id='$getid'";
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
              <h3 class="box-title">PO Order-Details</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                     <table id="example1" class="table table-bordered table-striped" style="margin-bottom: 20px">
                                        <thead>
                  <th>Challan No</th>
                  <th>Challan Date</th>
                  <th>Invoice No</th>
                  <th>Invoice Date</th>
                  <th>Create PO</th>
                  </thead>
                 <tbody>
                <tr>
                   <td><?=$row1['id'];?></td>
                   <td><?=date('d-m-Y', strtotime($row1['created_date']));?></td>
                   <td> <?=$row1['invoice_no'];?></td>
                  <td><?=date('d-m-Y', strtotime($row1['invoice_date']));?></td>
                   <td> <?=$row1['name'];?></td>
                </tr>
                                          
                </tbody>
      </table>   

            
      <table id="example1" class="table table-bordered table-striped">
        <thead>
                  <th>#</th>
                  <th>Product Category</th>
                  <th>product Name</th>
                  <th>Quantity</th>
                  <th>Running Quantity</th>
        </thead>
        <tbody>
<?php

  $qry1="SELECT po_product.*, product.product_name as product_name,product_category.name AS category_name from po_product 
  left join product on po_product.product_id=product.id 
  left join product_category on product.product_category_id=product_category.id
  where po_id='$getid'";
  $rs1=$conn->query($qry1);
                  $i = 1;
                  while($row = $rs1->fetch_array()){
                    $status=$row['status'];
                ?>
                <tr>
                  <td><?=$i++;?></td>
                  <td><?=$row['category_name'];?></td>
                  <td><?=$row['product_name'];?></td>
                  <td><?=$row['quantity'];?></td>
                  <td><?=$row['quantity_running_stock'];?></td>
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
                  <h3 class="box-title"><i class="fa fa-pencil-square-o"></i>Supplier Details</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                    
         <table id="example1" class="table table-bordered table-striped" style="margin-bottom: 20px">
                                        <thead>
                  <th>Supplier Name </th>
                  <th>Supplier Phone</th>
                  <th>Supplier Email</th>
                  <th>Supplier City</th>
                  <th>Supplier State</th>
                  </thead>
                 <tbody>
                <tr>
                   <td><?php echo $row1['supplier_name'];  ?></td>
                   <td><?php echo $row1['mobile'];  ?></td>
                   <td><?php echo $row1['email'];  ?></td>
                   <td><?php echo $row1['city'];  ?></td>
                   <td><?php echo $row1['State_name'];  ?></td>
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