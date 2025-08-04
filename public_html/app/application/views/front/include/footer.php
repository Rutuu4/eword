<?php
  $this->flashdata = $this->session->flashdata('message');
  $linkedin_url =  'https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id='.$this->config->item('linkedin_client_id').'&redirect_uri='.urlencode($this->config->item('linkedin_candidate_callback_url')).'&state='.$this->config->item('linkedin_state').'&scope='.urlencode($this->config->item('linkedin_scope'));
?>

<footer class="footer1">

  <!-- ===== Start of Footer Information & Links Section ===== -->
  <div class="footer-info ptb80">
      <div class="container">

          <!-- 1st Footer Column -->
          <div class="col-md-3 col-sm-6 col-xs-6 footer-about">

              <!-- Your Logo Here -->
              <a href="<?= $this->config->item('candidate_base_url') ?>">
                  <img src="<?= $this->config->item('image_url') ?>RC.png" alt="Logo">
              </a>

              <!-- Small Description -->
              <p class="pt40">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type changed.</p>

              <!-- Info -->
              <ul class="nopadding">
                  <li><i class="fa fa-map-marker"></i>New York City, USA</li>
                  <li><i class="fa fa-phone"></i>(123) 456 789 0012</li>
                  <li><i class="fa fa-envelope-o"></i>youremail@cariera.com</li>
              </ul>
          </div>

          <!-- 2nd Footer Column -->
          <div class="col-md-3 col-sm-6 col-xs-6 footer-links">
              <h3>useful links</h3>

              <!-- Links -->
              <ul class="nopadding">
                  <li><a href=""><i class="fa fa-angle-double-right"></i>add job</a></li>
                  <li><a href=""><i class="fa fa-angle-double-right"></i>blog</a></li>
                  <li><a href=""><i class="fa fa-angle-double-right"></i>find jobs</a></li>
                  <li><a href=""><i class="fa fa-angle-double-right"></i>FAQ</a></li>
                  <li><a href=""><i class="fa fa-angle-double-right"></i>login</a></li>
                  <li><a href=""><i class="fa fa-angle-double-right"></i>privacy policy</a></li>
                  <li><a href=""><i class="fa fa-angle-double-right"></i>register</a></li>
                  <li><a href=""><i class="fa fa-angle-double-right"></i>shop</a></li>
                  <li><a href=""><i class="fa fa-angle-double-right"></i>submit resume</a></li>
              </ul>
          </div>

          <!-- 3rd Footer Column -->
          <div class="col-md-3 col-sm-6 col-xs-6 footer-posts">
              <h3>popular posts</h3>

              <!-- Single Post 1 -->
              <div class="footer-blog-post">

                  <!-- Thumbnail -->
                  <div class="thumbnail-post">
                      <a href="">
                          <img src="" alt="">
                      </a>
                  </div>

                  <!-- Link -->
                  <div class="post-info">
                      <a href="">blog post 1</a>
                      <span>1 day ago</span>
                  </div>
              </div>

              <!-- Single Post 2 -->
              <div class="footer-blog-post">

                  <!-- Thumbnail -->
                  <div class="thumbnail-post">
                      <a href="">
                          <img src="" alt="">
                      </a>
                  </div>

                  <!-- Link -->
                  <div class="post-info">
                      <a href="">blog post 2</a>
                      <span>2 day ago</span>
                  </div>
              </div>

          </div>

          <!-- 4th Footer Column -->
          <div class="col-md-3 col-sm-6 col-xs-6 footer-newsletter">
              <h3>extra</h3>
              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
          </div>

      </div>
  </div>
  <!-- ===== End of Footer Information & Links Section ===== -->


  <!-- ===== Start of Footer Copyright Section ===== -->
  <div class="copyright ptb40">
      <div class="container">

          <div class="col-md-6 col-sm-6 col-xs-12">
              <span>Copyright &copy; <a href="<?= base_url() ?>"><?php echo SITE_NAME; ?></a>. All Rights Reserved</span>
          </div>

          <div class="col-md-6 col-sm-6 col-xs-12">
              <!-- Start of Social Media Buttons -->
              <ul class="social-btns list-inline text-right">
                  <!-- Social Media -->
                  <li>
                      <a href="#" class="social-btn-roll facebook">
                          <div class="social-btn-roll-icons">
                              <i class="social-btn-roll-icon fa fa-facebook"></i>
                              <i class="social-btn-roll-icon fa fa-facebook"></i>
                          </div>
                      </a>
                  </li>

                  <!-- Social Media -->
                  <li>
                      <a href="#" class="social-btn-roll twitter">
                          <div class="social-btn-roll-icons">
                              <i class="social-btn-roll-icon fa fa-twitter"></i>
                              <i class="social-btn-roll-icon fa fa-twitter"></i>
                          </div>
                      </a>
                  </li>

                  <!-- Social Media -->
                  <li>
                      <a href="#" class="social-btn-roll google-plus">
                          <div class="social-btn-roll-icons">
                              <i class="social-btn-roll-icon fa fa-google-plus"></i>
                              <i class="social-btn-roll-icon fa fa-google-plus"></i>
                          </div>
                      </a>
                  </li>

                  <!-- Social Media -->
                  <li>
                      <a href="#" class="social-btn-roll instagram">
                          <div class="social-btn-roll-icons">
                              <i class="social-btn-roll-icon fa fa-instagram"></i>
                              <i class="social-btn-roll-icon fa fa-instagram"></i>
                          </div>
                      </a>
                  </li>

                  <!-- Social Media -->
                  <li>
                      <a href="#" class="social-btn-roll linkedin">
                          <div class="social-btn-roll-icons">
                              <i class="social-btn-roll-icon fa fa-linkedin"></i>
                              <i class="social-btn-roll-icon fa fa-linkedin"></i>
                          </div>
                      </a>
                  </li>

                  <!-- Social Media -->
                  <li>
                      <a href="#" class="social-btn-roll rss">
                          <div class="social-btn-roll-icons">
                              <i class="social-btn-roll-icon fa fa-rss"></i>
                              <i class="social-btn-roll-icon fa fa-rss"></i>
                          </div>
                      </a>
                  </li>
              </ul>
              <!-- End of Social Media Buttons -->
          </div>

      </div>
  </div>
  <!-- ===== End of Footer Copyright Section ===== -->
</footer>

<a href="#" class="back-top"><i class="fa fa-chevron-up"></i></a>

<div class="cd-user-modal" id="login_popup">
  <!-- this is the entire modal form, including the background -->
  <div class="cd-user-modal-container">
      <!-- this is the container wrapper -->
      <ul class="cd-switcher">
          <li><a href="#0">Sign in</a></li>
          <li><a href="#1">New account</a></li>
      </ul>

      <div id="cd-login">
          <div class="text-center">
            <a class="btn btn-blue btn-effect linkedin-connect" href="<?= $linkedin_url ?>"><i class="fa fa-linkedin"></i>&nbsp;&nbsp;&nbsp;Connect with LinkedIn</a>
          </div>
          <!-- log in form -->
          <?php
              $attributes = array('class' => 'cd-form parsley-form', 'id' => 'candidate_signin', 'name' => 'candidates_signin', 'novalidate' => '','autocomplete'=>'off', 'data-parsley-validate' => '');
              echo form_open_multipart('',$attributes);
          ?>
              <p class="fieldset">
                  <label class="image-replace cd-username" for="<?= $this->lang->line('email') ?>"><?= $this->lang->line('email') ?></label>
                  <input class="full-width has-padding has-border" id="signin_email" name="signin_email" type="email" placeholder="<?= $this->lang->line('email') ?>*" required>
              </p>
              <p class="fieldset">
                  <label class="image-replace cd-password" for="signin-password"><?= $this->lang->line('password') ?></label>
                  <input class="full-width has-padding has-border" id="signin_password" name="signin_password" type="password" placeholder="<?= $this->lang->line('password') ?>*" required>
              </p>
              <!-- <p class="fieldset">
                  <input type="checkbox" id="remember-me" checked>
                  <label for="remember-me">Remember me</label>
              </p> -->
              <p class="fieldset">
                  <button type="submit" id="candidate_sighin" name="candidate_sighin" value="candidate_sighin" class="btn btn-blue btn-effect" onclick="return candidateSignIn('candidate_signin');">Login</button>
              </p>
          </form>
      </div>
      <!-- cd-login -->

      <div id="cd-signup">
          <div class="text-center">
            <a class="btn btn-blue btn-effect linkedin-connect" href="<?= $linkedin_url ?>"><i class="fa fa-linkedin"></i>&nbsp;&nbsp;&nbsp;Connect with LinkedIn</a>
          </div>
          <!-- sign up form -->
          <?php
              $attributes = array('class' => 'cd-form parsley-form', 'id' => 'candidate_signup', 'name' => 'candidate_signup', 'novalidate' => '','autocomplete'=>'off', 'data-parsley-validate' => '');
              echo form_open_multipart('',$attributes);
          ?>
              <p class="fieldset">
                  <label class="image-replace cd-username" for="<?= $this->lang->line('first_name') ?>"><?= $this->lang->line('first_name') ?></label>
                  <input class="full-width has-padding has-border" onkeydown="return alphaOnly(event);" id="first_name" name="first_name" type="text" placeholder="<?= $this->lang->line('first_name') ?>*" required>
              </p>

              <p class="fieldset">
                  <label class="image-replace cd-username" for="<?= $this->lang->line('last_name') ?>"><?= $this->lang->line('last_name') ?></label>
                  <input class="full-width has-padding has-border" onkeydown="return alphaOnly(event);" id="last_name" name="last_name" type="text" placeholder="<?= $this->lang->line('last_name') ?>*" required>
              </p>

              <p class="fieldset">
                  <label class="image-replace cd-username" for="<?= $this->lang->line('email') ?>"><?= $this->lang->line('email') ?></label>
                  <input class="full-width has-padding has-border" id="email" name="email" type="email" placeholder="<?= $this->lang->line('email') ?>*" required>
              </p>

              <p class="fieldset">
                  <label class="image-replace cd-password" for="signin-password"><?= $this->lang->line('password') ?></label>
                  <input class="full-width has-padding has-border" id="password" name="password" type="password" placeholder="<?= $this->lang->line('password') ?>*" minlength="8" required>
              </p>

              <p class="fieldset">
                  <label class="image-replace cd-password" for="signin-password"><?= $this->lang->line('confirm_password') ?></label>
                  <input class="full-width has-padding has-border" id="cnfpassword" name="cnfpassword" type="password" data-parsley-equalto="#password"  minlength="8" placeholder="<?= $this->lang->line('confirm_password') ?>*" required>
              </p>

              <p class="fieldset">
                  <label class="image-replace cd-username" for="<?= $this->lang->line('phone_no') ?>"><?= $this->lang->line('phone_no') ?></label>
                  <input class="full-width has-padding has-border" id="phone_no" name="phone_no" type="text" placeholder="<?= $this->lang->line('phone_no') ?>*" minlength="11" maxlength="15"  onkeypress="return isNumberKey(event);"  required>
              </p>

              <p class="fieldset">
                  <input type="checkbox" class="" id="accept-terms" required>
                  <label for="accept-terms">I agree to the <a href="#0">Terms</a></label>
              </p>
              <p class="fieldset">
                  <button class="btn btn-blue btn-effect" id="candidate_signup" name="candidate_signup" type="submit" onclick="return setdefaultdata();" value="candidate_signup">Create Account</button>
              </p>
          </form>
      </div>
      <!-- cd-signup -->
  </div>
  <!-- cd-user-modal-container -->
</div>
<script type="text/javascript">
  window.ParsleyConfig = {
    trigger: 'focusout',
  };


$(function(){    
  $(document).on('change', '#signin_email', function() {
    check_email(this.value,'',1);
  });
  
  $(document).on('change', '#email', function() {
    check_email(this.value,'',2);
  });

});

$(document).on('click', '.linkedin-connect', function() {
  $.ajax({
        type: "POST",
        url: "<?php echo $this->config->item('candidate_base_url') . 'home/redirect'; ?>",
        async: false,
        data: {'redirect_uri': "<?= uri_string() ?>", 'query_string': "<?= $_SERVER['QUERY_STRING'] ?>"},
        success: function(data)
        {
          
        }
    });
});

function setdefaultdata()
{
    if ($('#candidate_signup').parsley().isValid()) {
        $.ajax({
          type: "POST",
          url: "<?php echo $this->config->item('candidate_base_url') . 'home/candidate_signup'; ?>",
          dataType: 'json',
          async: false,
          data: {'first_name': $('#first_name').val(),'last_name': $('#last_name').val(),'email': $('#email').val(),'password': $('#password').val(),'phone_no': $('#phone_no').val()},
          success: function(data)
          {
            location.reload();
          }
      });
      return false;
    }
}

var message;
var status;
var icon;
function candidateSignIn(formid)
{
    var email;
    var password;
    if(formid == 'candidate_login')
    {
      email = $('#login_email').val();
      password = $('#login_password').val();
    }
    else if(formid == 'candidate_signin')
    {
      email = $('#signin_email').val();
      password = $('#signin_password').val();
    }
    if ($('#'+formid).parsley().isValid()) {
        $.ajax({
          type: "POST",
          url: "<?php echo $this->config->item('candidate_base_url') . 'home/candidate_signin'; ?>",
          dataType: 'json',
          async: false,
          data: {'email': email,'password': password},
          success: function(data)
          {
            if(data.status == 'success')
            {
              location.reload();
            }
            else if(data.status == 'danger' || data.status == 'warning')
            {
              message = data.message;
              icon = 'fa fa-warning';
              status  = data.status;
              $.notify({
                icon: ''+icon+'',
                message: ''+message+''
              },{
                type: ''+status+'',
              });
            }
          }
      });
      return false;
    }
}

function check_email(email, id,type)
{
    var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;

    if(email != '')
    {
      if(expr.test(email))
      {
        if(type == 2)
        {
          $.ajax({
              type: "POST",
              url: "<?php echo $this->config->item('candidate_base_url') . 'home/check_email'; ?>",
              dataType: 'json',
              async: false,
              data: {'email': email},
              success: function(data)
              {

                  if (data == '1')
                  {
                      var msg = "This email already existing ! Please select other email."
                  }
                  else if (data == '2')
                  {
                      var msg = "This email address is not valid ! Please select valid email address."
                  }

                  if (data == '1' || data == '2')
                  {
                    $("#candidate_signup").prop("disabled", true);
                      $.alert({
                          title: 'Alert!',
                          content: msg,
                          buttons: {
                            confirm:{btnClass:'hide'},
                            cancel:{
                              text:'ok',
                              action: function(){
                                if(type == 2)
                                {
                                  $('#email').val('');
                                  $('#email').focus();
                                  $("#candidate_signup").prop("disabled", false);
                                }
                                else
                                {
                                  $('#signin_email').val('');
                                  $('#signin_email').focus();
                                  $("#candidate_sighin").prop("disabled", false);
                                }
                              }
                            },
                          },
                      });
                  }
              }
          });
          return false;
        }
      }
      else
      {
        $.alert({
            title: 'Alert!',
            content: 'This email address is not valid ! Please select valid email address.',
            buttons: {
              confirm:{btnClass:'hide'},
              cancel:{
                text:'ok',
                action: function(){
                    if(type == 2)
                    {
                      $('#contact_email').val('');
                      $('#contact_email').focus();
                      $("#candidate_signup").prop("disabled", false);
                    }
                    else
                    {
                      $('#signin_email').val('');
                      $('#signin_email').focus();
                      $("#candidate_sighin").prop("disabled", false);
                    }
                }
              },
            },
        });
      }
    }
}
</script>
<script type="text/javascript" src="<?= $this->config->item('js_url') ?>modernizr-2.6.2.min.js"></script>
<script type="text/javascript" src="<?= $this->config->item('js_url') ?>bootstrap.min.js"></script>
<script type="text/javascript" src="<?= $this->config->item('js_url') ?>jquery-ui.min.js"></script>
<script type="text/javascript" src="<?= $this->config->item('js_url') ?>parsley.min.js"></script>
<script type="text/javascript" src="<?= $this->config->item('js_url') ?>bootstrap-notify.min.js"></script>
<script type="text/javascript" src="<?= $this->config->item('js_url') ?>jquery-confirm.js"></script>
<script type="text/javascript" src="<?= $this->config->item('js_url') ?>jquery.blockUI.js"></script>
<script type="text/javascript" src="<?= $this->config->item('js_url') ?>bootstrap-select.min.js"></script>
<script type="text/javascript" src="<?= $this->config->item('js_url') ?>swiper.min.js"></script>
<script type="text/javascript" src="<?= $this->config->item('js_url') ?>jquery.inview.min.js"></script>
<script type="text/javascript" src="<?= $this->config->item('js_url') ?>owl.carousel.min.js"></script>
<script type="text/javascript" src="<?= $this->config->item('js_url') ?>tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="<?= $this->config->item('js_url') ?>isotope.min.js"></script>
<script type="text/javascript" src="<?= $this->config->item('js_url') ?>tag-it.js"></script>
<script type="text/javascript" src="<?= $this->config->item('js_url') ?>tooltipster.bundle.min.js"></script>
<script type="text/javascript" src="<?= $this->config->item('js_url') ?>s3.fine-uploader.min.js"></script>

<script type="text/javascript" src="<?= $this->config->item('js_url') ?>jquery.ajaxchimp.js"></script>
<script type="text/javascript" src="<?= $this->config->item('js_url') ?>jquery.countTo.js"></script>
<script type="text/javascript" src="<?= $this->config->item('js_url') ?>jquery.magnific-popup.min.js"></script>
<script type="text/javascript" src="<?= $this->config->item('js_url') ?>jquery.easypiechart.min.js"></script>
<script type="text/javascript" src="<?= $this->config->item('js_url') ?>countdown.js"></script>

<script type="text/javascript" src="<?= $this->config->item('js_url') ?>custom.js"></script>
<script type="text/javascript" src="<?= $this->config->item('js_url') ?>jquery.mousewheel.min.js"></script>
<script type="text/javascript" src="<?= $this->config->item('js_url') ?>common.js"></script>

<script type="text/template" id="qq-template">
  <div class="qq-uploader-selector qq-uploader" qq-drop-area-text="Drop files here">
      <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
          <span class="qq-upload-drop-area-text-selector"></span>
      </div>
      <div class="qq-upload-button-selector qq-upload-button">
          <div>Upload a file</div>
      </div>
      <span class="qq-drop-processing-selector qq-drop-processing">
          <span>Processing dropped files...</span>
          <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
      </span>
      <ul class="qq-upload-list-selector qq-upload-list" aria-live="polite" aria-relevant="additions removals">
          <li>
              <div class="qq-progress-bar-container-selector">
                  <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
              </div>
              <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
              <img class="qq-thumbnail-selector" qq-max-size="100" qq-server-scale>
              <span class="qq-upload-file-selector qq-upload-file"></span>
              <span class="qq-edit-filename-icon-selector qq-edit-filename-icon" aria-label="Edit filename"></span>
              <input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
              <span class="qq-upload-size-selector qq-upload-size"></span>
              <button type="button" class="qq-btn qq-upload-cancel-selector qq-upload-cancel">Cancel</button>
              <button type="button" class="qq-btn qq-upload-retry-selector qq-upload-retry">Retry</button>
              <button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete">Delete</button>
              <span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
          </li>
      </ul>

      <dialog class="qq-alert-dialog-selector">
          <div class="qq-dialog-message-selector"></div>
          <div class="qq-dialog-buttons">
              <button type="button" class="qq-cancel-button-selector">Close</button>
          </div>
      </dialog>

      <dialog class="qq-confirm-dialog-selector">
          <div class="qq-dialog-message-selector"></div>
          <div class="qq-dialog-buttons">
              <button type="button" class="qq-cancel-button-selector">No</button>
              <button type="button" class="qq-ok-button-selector">Yes</button>
          </div>
      </dialog>

      <dialog class="qq-prompt-dialog-selector">
          <div class="qq-dialog-message-selector"></div>
          <input type="text">
          <div class="qq-dialog-buttons">
              <button type="button" class="qq-cancel-button-selector">Cancel</button>
              <button type="button" class="qq-ok-button-selector">Ok</button>
          </div>
      </dialog>
  </div>
</script>

<script>

$(document).ready(function() {
    $('.tooltips').tooltipster({
        theme: 'tooltipster-noir'
    });
});

$.notifyDefaults({
    z_index: 1040,
    delay: 5000,
    allow_dismiss: true,
    placement: {
		from: "bottom",
		align: "right"
	},
    animate: {
      enter: 'animated fadeInUp',
      exit: 'animated fadeOutDown'
    },
  });
  <?php if(!empty($this->flashdata)) 
  { 
    if($this->flashdata['status'] == "danger") 
    {
  ?>
    $.notify({
      icon: 'fa fa-warning',
      message: "<?= $this->flashdata['message'] ?>"
    },{
      type: 'danger',
    });
  <?php 
    }
    elseif($this->flashdata['status'] == "success")
    {
  ?>
    $.notify({
      icon: 'fa fa-success',
      message: "<?= $this->flashdata['message'] ?>"
    },{
      type: 'success',
    });
  <?php
    }
    elseif($this->flashdata['status'] == "warning")
    {
  ?>
    $.notify({
      icon: 'fa fa-warning',
      message: "<?= $this->flashdata['message'] ?>"
    },{
      type: 'warning',
    });
  <?php
    }
  } 
  ?>

 jconfirm.defaults = {
    title: 'Confirm!',
    titleClass: '',
    type: 'red',
    draggable: true,
    alignMiddle: true,
    typeAnimated: true,
    content: 'Are you sure to continue?',
    buttons: {
      confirm: {
            text: 'Yes',
            action: function () {
            },
            btnClass: 'btn-blue'
        },
        cancel: {
            text: 'No',
            action: function () {
            },
            btnClass: 'btn-red'
        },
    },
    defaultButtons: {},
    contentLoaded: function(data, status, xhr){
    },
    icon: 'fa fa-warning',
    bgOpacity: null,
    theme: 'material',
    animation: 'zoom',
    closeAnimation: 'zoom',
    animationSpeed: 400,
    animationBounce: 1.2,
    rtl: false,
    container: 'body',
    containerFluid: false,
    backgroundDismiss: false,
    backgroundDismissAnimation: 'shake',
    autoClose: false,
    closeIcon: null,
    closeIconClass: false,
    watchInterval: 100,
    columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
    boxWidth: '50%',
    scrollToPreviousElement: true,
    scrollToPreviousElementAnimate: true,
    useBootstrap: true,
    offsetTop: 50,
    offsetBottom: 50,
    dragWindowGap: 15,
    bootstrapClasses: {
        container: 'container',
        containerFluid: 'container-fluid',
        row: 'row',
    },
    onContentReady: function () {},
    onOpenBefore: function () {},
    onOpen: function () {},
    onClose: function () {},
    onDestroy: function () {},
    onAction: function () {}
};
</script>
</body>
</html>