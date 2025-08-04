<?php
$viewname   = $this->router->uri->segments[2];
$formAction = !empty($editRecord) ? 'update_data' : 'insert_data';
$path       = $viewname . '/' . $formAction;
$is_edit    = !empty($editRecord) ? "Edit Admin" : "Add New Admin";
$edit_data  = !empty($editRecord) ? '1' : '';
?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
        <?= $this->lang->line('admin_management'); ?>
        <small><?= $is_edit ?></small>
        <a class="btn bg-red pull-right" href="<?= $this->config->item('admin_base_url').$viewname; ?>"><i class="fa fa-reply"></i> Back</a> 
    </h1>
  </section>
  <section class="content">
    <div class="row">
        <div class="col-sm-8">
            <div class="box">
                <div class="box-header">
                    <!-- <h3 class="box-title">List of all the admins</h3> -->
                </div>

                <div class="col-sm-12">
                    <div id="div_msg">
                        <?php 
                            if (validation_errors() !== "") { ?>
                                <div class="alert alert-danger">
                                    <a href="javascript:void(0)" class="close close-message" aria-label="close" title="Close">&times;</a>
                                    <?php echo validation_errors(); ?>
                                </div>
                            <?php } ?>
                    </div>
                </div>
                  
                <div class="box-body">
                    <?php
                        $attributes = array('class' => 'parsley-form', 'id' => $viewname, 'name' => $viewname);
                        echo form_open_multipart($this->config->item('admin_base_url') . '' . $path, $attributes);
                    ?>  
                        <div class="form-group">
                            <label for="name"><?= $this->lang->line('name') ?><span style="color:#F00">*</span></label>
                            <input id="name" name="name" placeholder="<?= $this->lang->line('name') ?>" class="form-control parsley-validated" type="text" value="<?= !empty($editRecord[0]['name']) ? htmlentities($editRecord[0]['name']) : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email"><?= $this->lang->line('email') ?><?=!empty($edit_data)?'':'<span style="color:#F00">*</span>';?></label>
                            <?php if(!empty($edit_data)){ ?>
                            <br><label><?= !empty($editRecord[0]['email']) ? $editRecord[0]['email'] : ''; ?></label>
                            <?php } else{ ?>
                            <input id="email" placeholder="<?= $this->lang->line('email') ?>" class="form-control parsley-validated" type="text" name="email" value="<?= !empty($editRecord[0]['email']) ? $editRecord[0]['email'] : ''; ?>" onchange="check_email(this.value,<?= !empty($editRecord[0]['id']) ? $editRecord[0]['id'] : '0'; ?>);" required>
                            <?php } ?>                                    
                        </div>
                        <?php
                        if (empty($editRecord[0]['id'])) {
                            ?>
                            <div class="form-group">
                                <label for="password"><?= $this->lang->line('new_password') ?><span style="color:#F00">*</span></label>
                                <input id="password" name="password" placeholder="<?= $this->lang->line('new_password') ?>" class="form-control parsley-validated" type="password" minlength="6" required>
                            </div>
                            <div class="form-group">
                                <label for="cpassword"><?= $this->lang->line('confirm_password') ?><span style="color:#F00">*</span></label>
                                <input type="password" class="form-control parsley-validated" data-parsley-equalto="#password" placeholder="<?= $this->lang->line('confirm_password') ?>" name="npassword" id="cpassword" class="form-control parsley-validated" required minlength="6"/>
                            </div>
                        <?php
                        }
                        ?>
                        <div class="form-group">
                            <input type="hidden" name="id" value="<?= !empty($editRecord[0]['id']) ? $editRecord[0]['id'] : ''; ?>" />
                            <button type="submit" onclick="return setdefaultdata();" class="btn bg-green" id="save" name="save" title="<?php echo $this->lang->line('save_record'); ?>" value="submitForm"><i class="fa fa-save"></i> Save</button>
                            <a type="button" class="btn btn-adn" href="<?= $this->config->item('admin_base_url').$viewname; ?>" id="cancel"><i class="fa fa-remove"></i> Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
  </section>
</div>

<script type="text/javascript">

function setdefaultdata()
{
    if ($('#<?= $viewname ?>').parsley().isValid()) {
        $.blockUI();
    }
}
function isNumberKey(evt)
{
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;
}
function check_email(email, id)
{
    $.ajax({
        type: "POST",
        url: "<?php echo $this->config->item('admin_base_url') . $viewname . '/check_email'; ?>",
        dataType: 'json',
        async: false,
        data: {'email': email, 'id': id},
        success: function(data)
        {
            if (data == '1')
            {
                $("#save").prop("disabled", true);
                $.alert({
                    title: 'Alert!',
                    content: "This email already existing ! Please select other email.",
                    buttons: {
                      confirm:{btnClass:'hide'},
                      cancel:{
                        text:'ok',
                        action: function(){
                                $('#email').val('');
                                $('#email').focus();
                                $("#save").prop("disabled", false);
                            }
                      },
                    },
                  });
            }
            if (data == '2')
            {
                $("#save").prop("disabled", true);
                $.alert({
                    title: 'Alert!',
                    content: "This email address is not valid ! Please select valid email address.",
                    buttons: {
                      confirm:{btnClass:'hide'},
                      cancel:{
                        text:'ok',
                        action: function(){
                                $('#email').val('');
                                $('#email').focus();
                                $("#save").prop("disabled", false);
                            }
                      },
                    },
                  });
            }

        }
    });
    return false;
}
</script>