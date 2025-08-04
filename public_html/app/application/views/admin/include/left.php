<?php
/* ---------- Listing And Searching Session Destroy ------------- */
if ($this->uri->segment(2) != 'admin_management') {
  $this->session->unset_userdata($this->lang->line('admin_search_session'));
}
if ($this->uri->segment(2) != 'designation') {
  $this->session->unset_userdata($this->lang->line('designation_search_session'));
}
if ($this->uri->segment(2) != 'keywords') {
  $this->session->unset_userdata($this->lang->line('keywords_search_session'));
}
if ($this->uri->segment(2) != 'employer') {
  $this->session->unset_userdata($this->lang->line('employer_search_session'));
}
if ($this->uri->segment(2) != 'recruiter') {
  $this->session->unset_userdata($this->lang->line('recruiter_search_session'));
}
if ($this->uri->segment(2) != 'referrer') {
  $this->session->unset_userdata($this->lang->line('referrer_search_session'));
}
if ($this->uri->segment(2) != 'candidate') {
  $this->session->unset_userdata($this->lang->line('candidate_search_session'));
}
if ($this->uri->segment(2) != 'city') {
  $this->session->unset_userdata($this->lang->line('city_search_session'));
}
if ($this->uri->segment(2) != 'redeem') {
  $this->session->unset_userdata($this->lang->line('redeem_search_session'));
}
if ($this->uri->segment(2) != 'payment_history') {
  $this->session->unset_userdata($this->lang->line('payment_history_search_session'));
}
if ($this->uri->segment(2) != 'job_category') {
  $this->session->unset_userdata($this->lang->line('job_category_search_session'));
}
if ($this->uri->segment(2) != 'post_jobs') {
  $this->session->unset_userdata($this->lang->line('post_job_search_session'));
}
if ($this->uri->segment(2) != 'notifications') {
  $this->session->unset_userdata($this->lang->line('notifications_search_session'));
}
if ($this->uri->segment(2) != 'commission') {
  $this->session->unset_userdata($this->lang->line('commission_search_session'));
}
/* ----------End Session destroy------------- */
?>

<aside class="main-sidebar">
  <section class="sidebar">
      <!-- <div class="user-panel">
        <div class="pull-left image">
          <p>&nbsp;</p>
        </div>
        <div class="pull-left info">
          <p>Important Notice</p>
          <p>&nbsp;</p>
        </div>
      </div> -->

      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
        <li <?php if ($this->uri->segment(2) == 'dashboard') { ?> class="active" <?php } ?>>
          <a href="<?= base_url('admin/dashboard'); ?>">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            <!-- <span class="pull-right-container">
              <small class="label pull-right bg-green">new</small>
            </span> -->
          </a>
        </li>

        <li <?php if ($this->uri->segment(2) == 'post_jobs') { ?> class="active" <?php } ?>>
          <a href="<?= base_url('admin/post_jobs'); ?>">
            <i class="fa fa-list"></i> <span>Posted jobs</span>
          </a>
        </li>

        <li <?php if ($this->uri->segment(2) == 'employer') { ?> class="active" <?php } ?>>
          <a href="<?= base_url('admin/employer'); ?>">
            <i class="fa  fa fa-users"></i> <span>Employer</span>
          </a>
        </li>

        <li <?php if ($this->uri->segment(2) == 'recruiter') { ?> class="active" <?php } ?>>
          <a href="<?= base_url('admin/recruiter'); ?>">
            <i class="fa  fa fa-users"></i> <span>Recruiter</span>
          </a>
        </li>

        <li <?php if ($this->uri->segment(2) == 'referrer') { ?> class="active" <?php } ?>>
          <a href="<?= base_url('admin/referrer'); ?>">
            <i class="fa  fa fa-users"></i> <span>Referrer</span>
          </a>
        </li>

        <li <?php if ($this->uri->segment(2) == 'candidate') { ?> class="active" <?php } ?>>
          <a href="<?= base_url('admin/candidate'); ?>">
            <i class="fa  fa fa-users"></i> <span>Candidate</span>
          </a>
        </li>

        <li <?php if ($this->uri->segment(2) == 'redeem') { ?> class="active" <?php } ?>>
          <a href="<?= base_url('admin/redeem'); ?>">
            <i class="fa fa-exchange"></i> <span>Redeem</span>
          </a>
        </li>

        <li <?php if ($this->uri->segment(2) == 'payment_history') { ?> class="active" <?php } ?>>
          <a href="<?= base_url('admin/payment_history'); ?>">
            <i class="fa fa-money"></i> <span>Payment History</span>
          </a>
        </li>

        <li <?php if ($this->uri->segment(2) == 'notifications') { ?> class="active" <?php } ?>>
          <a href="<?= base_url('admin/notifications'); ?>">
            <i class="fa fa-bell-o"></i> <span>Notification</span>
          </a>
        </li>

        <?php 
          $setting_modules = array('city','designation','job_category','keywords','admin_settings','change_password','commission');
        ?>
        <li class="treeview <?= in_array($this->uri->segment(2),$setting_modules)?'active':''; ?>">
          <a href="#">
            <i class="fa fa-gears"></i>
            <span>Settings</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>

          <ul class="treeview-menu">
            <li <?php if ($this->uri->segment(2) == 'city') { ?> class="active" <?php } ?>>
              <a href="<?= base_url('admin/city'); ?>">
                <i class="fa fa-globe"></i> <span>City</span>
              </a>
            </li>

            <li <?php if ($this->uri->segment(2) == 'designation') { ?> class="active" <?php } ?>>
              <a href="<?= base_url('admin/designation'); ?>">
                <i class="fa fa-certificate"></i> <span>Designation</span>
              </a>
            </li>

            <li <?php if ($this->uri->segment(2) == 'job_category') { ?> class="active" <?php } ?>>
              <a href="<?= base_url('admin/job_category'); ?>">
                <i class="fa fa-file-text-o"></i> <span>Job category</span>
              </a>
            </li>

            <li <?php if ($this->uri->segment(2) == 'keywords') { ?> class="active" <?php } ?>>
              <a href="<?= base_url('admin/keywords'); ?>">
                <i class="fa fa-key"></i> <span>Keywords</span>
              </a>
            </li>

            <li <?php if ($this->uri->segment(2) == 'admin_settings') { ?> class="active" <?php } ?>>
              <a href="<?= base_url('admin/admin_settings'); ?>">
                <i class="fa fa-gear"></i> <span>Site Settings</span>
              </a>
            </li>
            
            <li <?php if ($this->uri->segment(2) == 'commission') { ?> class="active" <?php } ?>>
              <a href="<?= base_url('admin/commission'); ?>">
                <i class="fa fa-percent"></i> <span>Commission</span>
              </a>
            </li>

            <li <?php if ($this->uri->segment(2) == 'change_password') { ?> class="active" <?php } ?>>
              <a href="<?= base_url('admin/change_password'); ?>">
                <i class="fa fa-key"></i> <span>Change Password</span>
              </a>
            </li>

          </ul>
        </li>

        <li <?php if ($this->uri->segment(2) == 'admin_management') { ?> class="active" <?php } ?>>
          <a href="<?= base_url('admin/admin_management'); ?>">
            <i class="fa fa-user-secret"></i> <span>Admin Management</span>
          </a>
        </li>

        <li>
          <a href="javascript:void(0);" onclick="logout();">
            <i class="fa fa-power-off"></i> <span>Log out</span>
          </a>
        </li>
      </ul>
  </section>
</aside>

<script>
function logout()
{
  $.confirm({
    content: 'Are you sure want to log out?',
    buttons: {
        confirm: {
          action: function(){
              window.location="<?= base_url('admin/logout') ?>";
          }
        },
    },
});
}
</script>