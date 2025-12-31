	<aside class="main-sidebar">
		<section class="sidebar">
			<div class="user-panel">
				<div class="pull-left image">
					<img src="<?= $profileimgg ?>" class="img-circle" alt="User Image">
				</div>
				<div class="pull-left info">
					<p><?= ucfirst($name) ?></p>
					<?= ucfirst($usertype) ?>
				</div>
			</div>

			<!-- sidebar menu: : style can be found in sidebar.less -->
			<ul class="sidebar-menu" data-widget="tree">
				<li class="header"></li>
				<li class="active">
					<a href="index.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
				</li>


				<li class="treeview">
					<a href="#">
						<i class="fa fa-users"></i>
						<span>Users Management</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li><a href="admin-user.php"><i class="fa fa-circle-o"></i> Admin Users</a></li>
						<li><a href="application-admin-user.php"><i class="fa fa-circle-o"></i> Application Admin</a></li>
						<li><a href="register-user.php"><i class="fa fa-circle-o"></i> Register Users</a></li>
						<li><a href="refer-report.php"><i class="fa fa-circle-o"></i>Refer Report </a></li>
					</ul>
				</li>
				<!-- <li class="treeview">
	                <a href="#">
	                    <i class="fa fa-users"></i>
	                    <span>Subscription Management</span>
	                    <span class="pull-right-container">
	                        <i class="fa fa-angle-left pull-right"></i>
	                    </span>
	                </a>
	                <ul class="treeview-menu">
	                    <li><a href="subscription-package.php"><i class="fa fa-circle-o"></i>Subscription Package</a></li>
	                    <li><a href="active-subscription.php"><i class="fa fa-circle-o"></i> Active Subscription</a></li>
						
						
	                </ul>
	            </li> -->
				<!-- <li><a href="payment-fail-subscription.php"><i class="fa fa-circle-o"></i> Payment Fail Subscription</a></li>-->


				<li class="treeview">
					<a href="#">
						<i class="fa fa-database"></i>
						<span>Master</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li><a href="manage-banner.php"><i class="fa fa-circle-o"></i> Banner List</a></li>
						<li><a href="manage-city.php"><i class="fa fa-circle-o"></i> City List</a></li>
						<li><a href="manage-year.php"><i class="fa fa-circle-o"></i> Year List</a></li>

						<li><a href="manage-main-menu-video.php"><i class="fa fa-circle-o"></i> Main Manu Video</a></li>



					</ul>
				</li>



				<li class="treeview">
					<a href="#">
						<i class="fa fa-file"></i>
						<span>Upload With CSV</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<!-- <li><a href="master/csv-main-course.php"><i class="fa fa-circle-o"></i>Upload Main Course CSV</a></li> -->
						<li><a href="master/csv-sub-course.php"><i class="fa fa-circle-o"></i>Upload Sub Course CSV</a>
						</li>
						<li><a href="master/csv-course.php"><i class="fa fa-circle-o"></i>Upload Course CSV</a></li>
						<li><a href="master/csv-college-university.php"><i class="fa fa-circle-o"></i>Upload College
								University CSV</a></li>
						<li><a href="master/csv-college-university-course.php"><i class="fa fa-circle-o"></i>Add College
								University Course</a></li>
						<li><a href="master/csv-website.php"><i class="fa fa-circle-o"></i>Upload Website CSV</a></li>
						<li><a href="master/csv-update-course-video-link.php"><i class="fa fa-circle-o"></i>Update Course
								Video Link CSV</a></li>


					</ul>
				</li>




				<li class="treeview">
					<a href="#">
						<i class="fa fa-book"></i>
						<span>Manage Course </span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">

						<li><a href="manage-main-course.php"><i class="fa fa-circle-o"></i> Main Course List</a></li>
						<li><a href="manage-sub-course.php"><i class="fa fa-circle-o"></i> Sub Course List</a></li>
						<li><a href="manage-course.php"><i class="fa fa-circle-o"></i> Course List</a></li>


					</ul>
				</li>

				<li class="treeview">
					<a href="#">
						<i class="fa fa-bank"></i>
						<span>Manage College/University </span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">

						<li><a href="manage-college-university-type.php"><i class="fa fa-circle-o"></i> College University
								Type</a></li>

						<li><a href="manage-college-university.php"><i class="fa fa-circle-o"></i> College University
								List</a></li>




					</ul>
				</li>
				<li class="treeview">
					<a href="#">
						<i class="fa fa-graduation-cap"></i>
						<span>Manage Admission Process</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">

						<li><a href="manage-admission.php"><i class="fa fa-circle-o"></i> Admission Process List</a></li>


					</ul>
				</li>
				<li class="">
					<a href="manage-mou-table.php"> <i class="fa fa-table"></i> <span>Mou Listing</span></a>
				</li>
				<li class="">
					<a href="manage-mou-person.php"> <i class="fa fa-user"></i> <span>Mou Person Data</span></a>
				</li>
				<li class="treeview">
					<a href="#">
						<i class="fa fa-globe"></i>
						<span>Foreign Education </span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">

						<li><a href="manage-foreign-education.php"><i class="fa fa-circle-o"></i> Foreign Education</a>
						</li>




						<li><a href="manage-foreign-education-course.php"><i class="fa fa-circle-o"></i> Foreign Education
								Course</a></li>
						<li><a href="manage-city-education.php"><i class="fa fa-circle-o"></i>Foreign Education City</a></li>
						<li><a href="manage-country.php"><i class="fa fa-circle-o"></i> Country</a></li>
						<li><a href="manage-exam-type.php"><i class="fa fa-circle-o"></i> Exam Type</a></li>
						<li><a href="manage-visa-type.php"><i class="fa fa-circle-o"></i> Visa Type</a></li>


					</ul>
				</li>
				<li class="treeview">
					<a href="#">
						<i class="fa fa-graduation-cap"></i>
						<span>Tuition Training </span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">

						<li><a href="manage-tuition-and-trainning.php"><i class="fa fa-circle-o"></i> Tuition Training</a>
						</li>



						<li><a href="manage-tuition-and-trainning-course.php"><i class="fa fa-circle-o"></i> Tuition
								Training Course</a></li>
						<li><a href="manage-city-tuition.php"><i class="fa fa-circle-o"></i>Tuition Training City</a></li>


					</ul>
				</li>
				<li class="treeview">
					<a href="#">
						<i class="fa fa-briefcase"></i>
						<span> Project Internship </span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">

						<li><a href="manage-project-and-internship.php"><i class="fa fa-circle-o"></i> Project
								Internship</a></li>



						<li><a href="manage-project-and-internship-course.php"><i class="fa fa-circle-o"></i> Project
								Internship Course</a></li>
						<li><a href="manage-city-project.php"><i class="fa fa-circle-o"></i>Project
								Internship City</a></li>


					</ul>
				</li>


				<li class="treeview">
					<a href="#">
						<i class="fa fa-question-circle"></i>
						<span>Manage Question </span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">

						<li><a href="manage-question.php"><i class="fa fa-circle-o"></i> Question List</a></li>


					</ul>
				</li>


				<li class="treeview">
					<a href="#">
						<i class="fa fa-briefcase"></i>
						<span> Job Placement </span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">

						<li><a href="manage-job-and-placement.php"><i class="fa fa-circle-o"></i> Job Placement</a></li>



						<li><a href="manage-job-and-placement-openning.php"><i class="fa fa-circle-o"></i> Job Placement
								Openning</a></li>
						<li><a href="manage-city-job.php"><i class="fa fa-circle-o"></i>Job Placement City</a></li>


					</ul>
				</li>
				<li class="treeview">
					<a href="#">
						<i class="fa fa-globe"></i>
						<span>Manage Website </span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">

						<li><a href="manage-website.php"><i class="fa fa-circle-o"></i> Website List</a></li>



					</ul>
				</li>

				<li class="treeview">
					<a href="#">
						<i class="fa fa-gavel"></i>
						<span>Manage Cut off </span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">

						<li><a href="manage-cut-off.php"><i class="fa fa-circle-o"></i> Cut off List</a></li>


					</ul>
				</li>

				<li class="treeview">
					<a href="#">
						<i class="fa fa-drivers-license"></i>
						<span>Manage Scholarship/Loan </span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">

						<li><a href="manage-scholarship-loan.php"><i class="fa fa-circle-o"></i> Scholarship/Loan List</a>
						</li>


					</ul>
				</li>
				<li class="treeview">
					<a href="#">
						<i class="fa fa-folder-open-o"></i>
						<span>Manage Document </span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">

						<li><a href="manage-document.php"><i class="fa fa-circle-o"></i> Document List</a></li>


					</ul>
				</li>
				<li class="treeview">
					<a href="#">
						<i class="fa fa-comment-o"></i>
						<span>Manage Whatsapp Links </span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li><a href="manage-wp-links.php"><i class="fa fa-circle-o"></i> Whatsapp Links</a></li>
						<!-- <li><a href="manage-chat-room-group.php"><i class="fa fa-circle-o"></i> Chat Room Group</a></li> -->

					</ul>
				</li>

				<li class="treeview">
					<a href="#">
						<i class="fa fa-bullhorn"></i>
						<span>Manage Help & Support </span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li>
							<a href="contact-details.php"><i class="fa fa-user"></i> <span>Manage Help & Support</span></a>
						</li>

					</ul>
				</li>

				<li class="treeview">
					<a href="#">
						<i class="fa fa-comment"></i>
						<span>Manage Notification</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li><a href="coursewise-notification.php"><i class="fa fa-circle-o"></i> <span>Coursewise
									Notification</span></a> </li>
						<li><a href="citywise-notification.php"><i class="fa fa-circle-o"></i> <span>Citywise
									Notification</span></a> </li>
						<li><a href="register-user-notification"><i class="fa fa-circle-o"></i> <span>Register User
									Notification</span></a> </li>

					</ul>
				</li>

				<!-- <li class="treeview">
					<a href="#">
						<i class="fa fa-gift"></i>
						<span>Manage Promocodes</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li><a href="admin-promocode.php"><i class="fa fa-circle-o"></i> <span>Promocode List</span></a>
						</li>
					</ul>
				</li> -->





				<li>
					<a href="logout.php"><i class="fa fa-power-off"></i> <span>Logout</span></a>
				</li>
			</ul>
		</section>
	</aside>