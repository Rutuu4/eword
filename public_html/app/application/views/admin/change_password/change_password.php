<?php 
$admin_type = $this->router->uri->segments[1];
$viewname = $this->router->uri->segments[2];
?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
        <i class="fa fa-key"></i> Change Password
        <!-- <small>Edit setting for the website</small> -->
        <a class="btn bg-red pull-right" href="<?= $this->config->item('admin_base_url') ?>"><i class="fa fa-reply"></i> Back</a> 
    </h1>
  </section>
  <section class="content">
    <div class="row">
        <div class="col-sm-8">
            <div class="box">
                <div class="box-header">
                    <!-- <h3 class="box-title">List of all the admins</h3> -->
                </div>
                <div class="box-body">
        					 <form class="form parsley-form" enctype="multipart/form-data" name="<?= $viewname;?>" id="<?= $viewname;?>" method="post" accept-charset="utf-8" action="<?= $this->config->item('admin_base_url')?>change_password/admin_change_password" data-parsley-validate>	
        					 
        					 	<div class="form-group">
        							<label for="oldpassword">Old password<span style="color:#F00">*</span></label>
        							<input minlength="6" type="password" name="oldpassword" id="oldpassword" class="form-control parsley-validated" type="text" placeholder="Old password" required/>
        						</div>
        						
                    <div class="form-group">
        							<label for="password">New password<span style="color:#F00">*</span></label>
        							<input minlength="6" type="password" name="password" id="password" class="form-control parsley-validated" placeholder="New password" type="text" required/>
        						</div>
                    
                    <div class="form-group">
        							<label for="cpassword">Confirm password<span style="color:#F00">*</span></label>
        							<input type="password" name="cpassword" placeholder="Confirm password" id="cpassword" class="form-control parsley-validated" type="text" data-parsley-equalto="#password" required/>
        						</div>
        						
                    <div class="form-group">
                      <input type="hidden" name="id" value="<?= !empty($editRecord[0]['id'])?$editRecord[0]['id']:'';?>" />
                      <button type="submit" class="btn bg-green" title="Save Record"><i class="fa fa-save"></i> Save</button>
                      <a type="button" class="btn btn-adn" href="<?php echo base_url('admin/dashboard'); ?>" id="cancel"><i class="fa fa-remove"></i> Cancel</a>
						        </div>
					       </form>
				      </div>
          </div>
        </div>
    </div>
  </section>
</div>