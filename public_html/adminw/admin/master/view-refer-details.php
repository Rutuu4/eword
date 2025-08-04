<?php 
  include("../../database.php"); 




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
.datepicker-dropdown {
  z-index: 99999 !important;

}

  </style>
</head>
<body class="<?=$bodyclass?>">
<?php
   if(empty($_GET['id'])){
    echo "<a href='index.php'>Server Issue Click Here to Go back</a>";
    exit;
  }
  else
  {
          $getid = $_GET['id'];
          $qry1="SELECT id,fullname,username from registration where id='$getid'";
          $result1 = $conn->query($qry1);
          $row = $result1->fetch_array();
          

  }

?>
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
        <!-- Left col -->
          <section class="col-lg-12 connectedSortable">
          <!-- Custom tabs (Charts with tabs)-->
          <!-- /.box-header -->
          <div class="box" style="border-top:none;">
            <div class="box-content">
              <div class="box box-info col-md-6">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-pencil-square-o"></i> VIEW REFER  DETAILS</h3>
                </div>
                            <div class="box-body">
          </div>
               
              </div>
            </div>            
          </div>
        </section>
        <section class="col-lg-12 connectedSortable">
          <!-- Custom tabs (Charts with tabs)-->
          <!-- /.box-header -->
          <div class="box">
            
            <!-- /.box-header -->
            <div class="box-body">
             <form class="form-horizontal" id="form-search-product" name="form-search-product"  autocomplete="off">

          <div class="col-md-12">
            

                     <div class="col-md-6">
                        <div class="form-group">
                      <label for="address" class="col-sm-5">Main User Name:</label>
                      <div class="col-sm-7">
                     <?=$row['fullname'];?>

                      </div>
                    </div>
              
                </div>

                  

                      <div class="col-md-6">
                        <div class="form-group">
                      <label for="address" class="col-sm-4">Main User Mobile:</label>
                      <div class="col-sm-8">
                      <?=$row['username'];?>

                      </div>
                    </div>

  



           

          </div>
          

             

              </form>                          
            
                                                 
                                    
                                    
            </div>
            <!-- /.box-body -->
          </div>
          
          
          <div class="col-md-12" style="padding:0;">
          <div class="box">
          <div class="box-body">
              <h4>
              Refer User List
               
              </h4>
           <form class="form-horizontal" id="form-product-update" name="form1" method="post" action="" onSubmit="return selIt();" enctype="multipart/form-data" autocomplete="off">
           <input name="h1" type="hidden" id="h1" value="1">
             <input name="purchase_invoice_id" type="hidden"  value="<?=$row['id']?>">
             <input name="purchase_invoice_qty" type="hidden"  value="<?=$purchase_invoice_qty;?>">
           <input name="redirect_page" type="hidden" id="redirect_page" value="<?=$_SERVER['HTTP_REFERER'];?>" />
            <input name="id" type="hidden" id="id" value="<?=$getid;?>">

          <table id="example1" class="table table-bordered table-striped" style="margin-bottom:10px;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                 <th>Join Date</th> 
                                                <th>Member Name</th>
                                                <th>Member Number</th>
                                                <th>Member Residence City</th>
                                                <th>Member Study City</th>
                                                <th>Member Current Study</th>
                                                <th>Member College/University</th>
                                              <!--   <th>Remove</th> -->
                                                <!-- <th>Group Status</th>
                                                <th>Action</th> -->
                                                
                                            
                                            </tr>
                                        </thead>
                                        <tbody>

<?php
$i=1;
$qry_purchase_tnx="SELECT * FROM registration where registration.refer_user_id='$getid'";
$result_purchase_tnx=$conn->query($qry_purchase_tnx);
while($row_purchase_tnx=$result_purchase_tnx->fetch_array())
{ 
 $status=$row_purchase_tnx['is_chat_block'];
 if($status==0){ $statuss="<span class=\"label label-success\">Active</span>"; } else { $statuss="<span class=\"label label-danger\">Blocked</span>"; } 
?>                                          
                                         <tr>
                                          <td><?=$i++;?></td>
                                          <td><?=date("d-m-Y", strtotime($row_purchase_tnx['datee']));?></td>
                                          <td><?=$row_purchase_tnx['fullname'];?></td>
                                          <td><?=$row_purchase_tnx['username'];?></td>
                                          <td ><?=$row_purchase_tnx['city'];?></td>
                                          <td ><?=$row_purchase_tnx['study_city'];?></td>
                                            <td ><?=$row_purchase_tnx['current_study'];?></td>
                                            <td ><?=$row_purchase_tnx['college_university_name'];?></td>
                                         
                                        

                                          </tr>   
<?php } ?>                                            
                                        </tbody>
                                        <tfoot>
                                         
                                        </tfoot>
                                    </table>

          
          <div class="col-md-12">
          <div class="box " style="border-top:none;">
            <div class="box-content">
              <div class="box box-info">
                <div class="box-header with-border">
                </div>
                <div class="box-body" style="text-align:center;">

                  
                 
               
                
              </div>
            </div>            
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
  $('#invoice_date').on('change', function() {
  invoice_date = $(this).val();
      if(invoice_date != ""){
      $.ajax({
        type: "POST",
        url: "master/ajax/ajax-set-session-invoice-date.php",
        data: {invoice_date:invoice_date},
        
      })
    }
});
</script>

<script type="text/javascript">
  $('#invoice_no').on('change', function() {
  invoice_no = $(this).val();
      if(invoice_no != ""){
      $.ajax({
        type: "POST",
        url: "master/ajax/ajax-set-session-invoice-number.php",
        data: {invoice_no:invoice_no},
        
      })
    }
});
</script>
<script type="text/javascript">
  $('#supplier_id').on('change', function() {
  supplier_id = $(this).val();
      if(supplier_id != ""){
      $.ajax({
        type: "POST",
        url: "master/ajax/ajax-set-session-supplier-id.php",
        data: {supplier_id:supplier_id},
        success: function(response) {
          location.reload();
        }
        
      })
    }
});
</script>


  <script>
$(document).ready(function(){
    $("#serach_product_name").change(function(){
      var val = $(this).val();
      $.ajax({
              type : "POST",
              url : "master/ajax/ajax-check-shade.php",
              data : {val:val},
              success : function(data) {
                if(data == 'supplier')
                {
                    $("#serach_product_name").val('');
                    $("#exist").html("Select Supplier First");
                }
                else
                {
                      if(data == 'false')
                      {
                      $("#serach_product_name").val('');
                      $("#exist").html("Shade Number Not Exist.");
                      }
                      else
                      {
                        $("#exist").html("");
                      }
                  
                }
                
              }
          });
    });
});  
</script>
<script>
$(document).ready(function(){
    $("#serach_product_weight").change(function()
    {
      var product_weight = $(this).val();

      product_bori =Math.ceil(product_weight/29.99);

      $("#serach_product_bori").val(product_bori);
    }); 
});

</script>
<script> 
$(document).ready(function(){

var max_fields= 51; //maximum input boxes allowed
var x = 1;
$('#form-search-product').on('submit', function (e) { 

e.preventDefault();
var dis_product_name, dis_product_weight,dis_product_bori;

   
dis_product_name=$('#serach_product_name').val();  
dis_product_weight=$('#serach_product_weight').val();   
dis_product_bori=$('#serach_product_bori').val(); 

dis_product_weight=parseFloat(dis_product_weight).toFixed(3);



var markup = '<tr><td><i class="fa fa-close remove-row" style="color: #fff;cursor: pointer;background: #ec2814;border-radius: 50%;padding: 3px 5px;"></i></td><td>  <input type="hidden" name="updated_id[]" value=""><input type="text" name="product_name[]" id="serach_product_name" placeholder="Shade No" class="form-control" value="'+dis_product_name+'" required=""></td><td><input type="number" name="product_weight[]" placeholder="product_weight" step="any" class="form-control updatecartproduct" value="'+dis_product_weight+'" required=""></td><td><input type="text" name="product_bori[]" placeholder="Bori" class="form-control updatecartproductbori" value="'+dis_product_bori+'" required="" ></td></tr>';

if(x < max_fields){ 
x++;
$("#example1 tbody").append(markup);
$(".updatecartproduct").trigger("change");
$('#form-search-product')[0].reset();
}
});


$(document).on('change',".updatecartproduct", function()
{ 

var dis_product_weight=0, dis_product_bori=0, dis_total_product_weight=0, dis_total_product_bori=0;  
$("input[name='product_weight[]']").each(function() { 

  dis_product_weight=$(this).val(); 

  dis_total_product_weight +=parseFloat(dis_product_weight);

  dis_product_bori=Math.ceil(dis_product_weight/29.99);

  $(this).closest('tr').find("td input[name='product_bori[]']").val(dis_product_bori); 

  dis_total_product_bori +=parseFloat(dis_product_bori);

});

$("#total_product_weight").text(dis_total_product_weight);    
$("#total_product_bori").text(dis_total_product_bori); 

});


$(document).on('change',".updatecartproductbori", function()
{ 

var dis_product_bori=0, dis_total_product_bori=0;  
$("input[name='product_bori[]']").each(function() { 

  dis_product_bori=$(this).val(); 


  $(this).closest('tr').find("td input[name='product_bori[]']").val(dis_product_bori); 

  dis_total_product_bori +=parseFloat(dis_product_bori);

});
   
$("#total_product_bori").text(dis_total_product_bori); 

});



$("#example1").on('click','.remove-row',function(){$(this).parent().parent().remove();   $(".updatecartproduct").trigger("change");  });

$('.datepicker').datepicker({ autoclose: true,  format: "dd-mm-yyyy"  });
$(".select2").select2();

});
</script>
<script>
  $(document).on('click', '.product_delete', function(e) {
    e.preventDefault();
        var purchase_invoice_txn_id = $(this).data("depid");
    if(purchase_invoice_txn_id>0){   
       $.ajax({
            type: "POST",
            url: "master/ajax/ajax-delete-purchase-invoice-txn.php",
            data: {purchase_invoice_txn_id:purchase_invoice_txn_id},
            dataType:'json',
            success: function(data){   
      if($.trim(data.lg_valid) === "TRUE"){
      location.reload();
            } } 
    }); 
  } 
  });
</script>
</script>
</section>
</div>
</div>
</body>
</html>