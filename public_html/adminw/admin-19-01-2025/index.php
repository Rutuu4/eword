<?php
  include("../database.php");


$qry_product="SELECT id FROM user where status=1"; 
$result_product = $conn->query($qry_product);
$totalpro=mysqli_num_rows($result_product);



$qry_product="SELECT id FROM registration where status=1"; 
$result_product = $conn->query($qry_product);
$totalregister=mysqli_num_rows($result_product);

$qry_main_courses="SELECT id FROM m_main_courses where status=1"; 
$result_main_courses = $conn->query($qry_main_courses);
$total_main_courses=mysqli_num_rows($result_main_courses);


$qry_courses="SELECT id FROM courses_details where status=1"; 
$result_courses = $conn->query($qry_courses);
$total_courses=mysqli_num_rows($result_courses);

$qry_college_university_details="SELECT id FROM college_university_details where status=1"; 
$result_college_university_details = $conn->query($qry_college_university_details);
$total_college_university_details=mysqli_num_rows($result_college_university_details);

$qry_question_list="SELECT id FROM question_list where status=1"; 
$result_question_list = $conn->query($qry_question_list);
$total_question_list=mysqli_num_rows($result_question_list);

$qry_admission_process="SELECT id FROM admission_process where status=1"; 
$result_admission_process = $conn->query($qry_admission_process);
$total_admission_process=mysqli_num_rows($result_admission_process);

$qry_website_list="SELECT id FROM website_list where status=1"; 
$result_website_list = $conn->query($qry_website_list);
$total_website_list=mysqli_num_rows($result_website_list);

$qry_cut_off_details="SELECT id FROM cut_off_details where status=1"; 
$result_cut_off_details = $conn->query($qry_cut_off_details);
$total_cut_off_details=mysqli_num_rows($result_cut_off_details);

$qry_scholarship_loan_details="SELECT id FROM scholarship_loan_details where status=1"; 
$result_scholarship_loan_details = $conn->query($qry_scholarship_loan_details);
$total_scholarship_loan_details=mysqli_num_rows($result_scholarship_loan_details);

$qry_documents_details="SELECT id FROM documents_details where status=1"; 
$result_documents_details = $conn->query($qry_documents_details);
$total_documents_details=mysqli_num_rows($result_documents_details);

$date=date('Y-m-d');
$qry_active="SELECT id from package_subscription_order where subscription_expire_date>'$date'";
$result_active = $conn->query($qry_active);
$total_active=mysqli_num_rows($result_active);

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?=$softtitle?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <?php include("includes/css-scripts.php"); ?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body class="<?=$bodyclass?>">

<div class="wrapper" style="overflow-y: hidden;">

  <?php include("includes/header.php"); ?>
  <?php include("includes/sidebar.php"); ?>

  <div class="content-wrapper">
    <section class="content">
      <div class="row">
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box bg-orange">
            <span class="info-box-icon"><i class="fa fa-user"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Admin User</span>
              <span class="info-box-number"><?=$totalpro?></span>
              <div class="progress">
                <div class="progress-bar" style="width: 70%"></div>
              </div>
                <span class="progress-description">
                  <a style="color: white;" href="manage-admin-user.php">Click For More</a>
                </span>
            </div>
          </div>
        </div>
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box bg-gray">
            <span class="info-box-icon"><i class="fa fa-users"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total Register User</span>
              <span class="info-box-number"><?=$totalregister?></span>
              <div class="progress">
                <div class="progress-bar" style="width: 70%"></div>
              </div>
                <span class="progress-description">
                  <a style="color: white;" href="register-user.php">Click For More</a>
                </span>
            </div>
          </div>
        </div>
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-book"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Active User</span>
              <span class="info-box-number"><?=$total_active?></span>
              <div class="progress">
                <div class="progress-bar" style="width: 70%"></div>
              </div>
                <span class="progress-description">
                  <a style="color: white;" href="active-subscription.php">Click For More</a>
                </span>
            </div>
          </div>
        </div>
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box bg-orange">
            <span class="info-box-icon"><i class="fa fa-book"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total Main Course</span>
              <span class="info-box-number"><?=$total_main_courses?></span>
              <div class="progress">
                <div class="progress-bar" style="width: 70%"></div>
              </div>
                <span class="progress-description">
                  <a style="color: white;" href="manage-main-course.php">Click For More</a>
                </span>
            </div>
          </div>
        </div>
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box bg-gray">
            <span class="info-box-icon"><i class="fa fa-book"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total Course</span>
              <span class="info-box-number"><?=$total_courses?></span>
              <div class="progress">
                <div class="progress-bar" style="width: 70%"></div>
              </div>
                <span class="progress-description">
                  <a style="color: white;" href="manage-course.php">Click For More</a>
                </span>
            </div>
          </div>
        </div>
         <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-bank"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total College University</span>
              <span class="info-box-number"><?=$total_college_university_details?></span>
              <div class="progress">
                <div class="progress-bar" style="width: 70%"></div>
              </div>
                <span class="progress-description">
                  <a style="color: white;" href="manage-college-university.php">Click For More</a>
                </span>
            </div>
          </div>
        </div>
          <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box bg-orange">
            <span class="info-box-icon"><i class="fa fa-question-circle"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total Question</span>
              <span class="info-box-number"><?=$total_question_list?></span>
              <div class="progress">
                <div class="progress-bar" style="width: 70%"></div>
              </div>
                <span class="progress-description">
                  <a style="color: white;" href="manage-question.php">Click For More</a>
                </span>
            </div>
          </div>
        </div>
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box bg-gray">
            <span class="info-box-icon"><i class="fa fa-graduation-cap"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total Admission Process</span>
              <span class="info-box-number"><?=$total_admission_process?></span>
              <div class="progress">
                <div class="progress-bar" style="width: 70%"></div>
              </div>
                <span class="progress-description">
                  <a style="color: white;" href="manage-admission.php">Click For More</a>
                </span>
            </div>
          </div>
        </div>
         <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-globe"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total Website</span>
              <span class="info-box-number"><?=$total_website_list?></span>
              <div class="progress">
                <div class="progress-bar" style="width: 70%"></div>
              </div>
                <span class="progress-description">
                  <a style="color: white;" href="manage-website.php">Click For More</a>
                </span>
            </div>
          </div>
        </div>
         <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box bg-orange">
            <span class="info-box-icon"><i class="fa fa-gavel"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total Cut off</span>
              <span class="info-box-number"><?=$total_cut_off_details?></span>
              <div class="progress">
                <div class="progress-bar" style="width: 70%"></div>
              </div>
                <span class="progress-description">
                  <a style="color: white;" href="manage-cut-off.php">Click For More</a>
                </span>
            </div>
          </div>
        </div>

        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box bg-gray">
            <span class="info-box-icon"><i class="fa fa-drivers-license"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total Scholarship Loan</span>
              <span class="info-box-number"><?=$total_scholarship_loan_details?></span>
              <div class="progress">
                <div class="progress-bar" style="width: 70%"></div>
              </div>
                <span class="progress-description">
                  <a style="color: white;" href="manage-scholarship-loan.php">Click For More</a>
                </span>
            </div>
          </div>
        </div>


         <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-folder-open-o"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total Document</span>
              <span class="info-box-number"><?=$total_documents_details?></span>
              <div class="progress">
                <div class="progress-bar" style="width: 70%"></div>
              </div>
                <span class="progress-description">
                  <a style="color: white;" href="manage-document.php">Click For More</a>
                </span>
            </div>
          </div>
        </div>

       
      </div>
       <hr>

       </div>
      
      </div>

      <div class="clearfix"></div>
      <div class="clearfix"></div>
     
      
      
      
    </section>
  </div>
  <!-- MODAL -->
  <div class="modal fade" id="welcomemodal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Welcome <?=ucfirst($userinfo->name)?> to <?=$softtitle?></h4>
        </div>
      </div>
    </div>
  </div>

  <?php include("includes/footer.php"); ?>

  <div class="control-sidebar-bg"></div>
</div>

<?php include("includes/js-scripts.php"); ?>
<script src="../dist/js/jquery.cookie.min.js"></script>
<script>
if($.cookie('welcook') != 'DialogShown'){
  $('#welcomemodal').modal('show');
  setCookie();
}

function setCookie(){
  $.cookie('welcook', 'DialogShown',
  {
    expires: Date.now() + (24 * 60 * 60 * 1000) // now add 24 hours
  });
}
</script>
</body>
</html>
