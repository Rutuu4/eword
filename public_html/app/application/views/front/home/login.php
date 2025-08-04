<?php
  $this->flashdata = $this->session->flashdata('message');
  $linkedin_url =  'https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id='.$this->config->item('linkedin_client_id').'&redirect_uri='.urlencode($this->config->item('linkedin_candidate_callback_url')).'&state='.$this->config->item('linkedin_state').'&scope='.urlencode($this->config->item('linkedin_scope'));
?>
<!-- ===== Start of Login - Register Section ===== -->
<section class="ptb80" id="login">
    <div class="container">
        <div class="col-md-6 col-md-offset-3 col-xs-12">

            <!-- Start of Login Box -->
            <div class="login-box">

                <div class="login-title">
                    <h4>login to <?= $this->config->item('sitename') ?></h4>
                </div>

                <br/>
                
                <div class="text-center">
                    <a class="btn btn-blue btn-effect linkedin-connect" href="<?= $linkedin_url ?>"><i class="fa fa-linkedin"></i>&nbsp;&nbsp;&nbsp;Connect with LinkedIn</a>
                </div>

                <!-- Start of Login Form -->
                <?php
                    $attributes = array('class' => 'parsley-form', 'id' => 'candidate_login', 'name' => 'candidate_login', 'novalidate' => '','autocomplete'=>'off', 'data-parsley-validate' => '');
                    echo form_open_multipart('',$attributes);
                ?>
                    <!-- Form Group -->
                    <div class="form-group">
                        <label><?= $this->lang->line('email') ?></label>
                        <input class="form-control" id="login_email" name="login_email" type="email" placeholder="<?= $this->lang->line('email') ?>*" required>
                    </div>

                    <!-- Form Group -->
                    <div class="form-group">
                        <label for="signin-password"><?= $this->lang->line('password') ?></label>
                        <input class="form-control" id="login_password" name="login_password" type="password" placeholder="<?= $this->lang->line('password') ?>*" required>
                    </div>

                    <!-- Form Group -->
                    <!-- <div class="form-group">
                        <div class="row">
                            <div class="col-xs-6">

                                <input type="checkbox" id="remember-me2">
                                <label for="remember-me2">Remember me?</label>

                            </div>

                            <div class="col-xs-6 text-right">
                                <a href="lost-password.html">Forgot password?</a>
                            </div>
                        </div>
                    </div> -->

                    <!-- Form Group -->
                    <div class="form-group text-center">
                        <button type="submit" id="candidate_login_btn" name="candidate_login_btn" value="candidate_login_btn" class="btn btn-blue btn-effect" onclick="return recruiterSignIn('candidate_login');">Login</button>
                        <!-- <a href="register.html" class="btn btn-blue btn-effect">signup</a> -->
                    </div>

                </form>
                <!-- End of Login Form -->
            </div>
            <!-- End of Login Box -->

        </div>
    </div>
</section>
<!-- ===== End of Login - Register Section ===== -->





<!-- ===== Start of Get Started Section ===== -->
<section class="get-started ptb40">
    <div class="container">
        <div class="row ">
            
            <!-- Column -->
            <div class="col-md-10 col-sm-9 col-xs-12">
                    <h3 class="text-white">20,000+ People trust Cariera! Be one of them today.</h3>
            </div>
            
            <!-- Column -->
            <div class="col-md-2 col-sm-3 col-xs-12">
                <a href="#" class="btn btn-blue btn-effect">get start now</a>
            </div>
            
        </div>
    </div>
</section>
<!-- ===== End of Get Started Section =====