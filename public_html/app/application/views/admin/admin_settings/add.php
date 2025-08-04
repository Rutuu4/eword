<?php 
$viewname = $this->router->uri->segments[2];
$formAction = !empty($editRecord)?'update_data':'update_data'; 
$path = $viewname.'/'.$formAction;
$head_title = !empty($editRecord)?'Edit':'Add New';
?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
        <i class="fa fa-gear"></i> Admin Setting
        <small>Edit setting for the website</small>
        <a class="btn bg-red pull-right" href="<?= $this->config->item('admin_base_url') ?>"><i class="fa fa-reply"></i> Back</a>
    </h1>
  </section>
  <section class="content">
    <div class="row">
        <div class="col-sm-9">
            <div class="box">
                <div class="box-header">
                    <!-- <h3 class="box-title">List of all the admins</h3> -->
                </div>
                <div class="box-body">
                  <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?= $this->config->item('admin_base_url')?><?php echo $path?>" data-parsley-validate>
                  <div class="form-group">
                    <label for="sitename">Site Name<span style="color:#F00">*</span></label>
                    <input id="sitename" name="sitename" class="form-control parsley-validated" type="text" value="<?= !empty($editRecord[0]['sitename'])?$editRecord[0]['sitename']:'';?>" placeholder="Site Name" required>
                  </div>

                  <div class="form-group">
                    <label for="admin_email">Admin Email<span style="color:#F00">*</span></label>
                    <input name="admin_email" id="admin_email" class="form-control parsley-validated" type="email" value="<?= !empty($editRecord[0]['admin_email'])?$editRecord[0]['admin_email']:'';?>" placeholder="Admin Email" required>
                  </div>

                  <div class="form-group">
                    <label for="address">Address<span style="color:#F00">*</span></label>
                    <input name="address" id="address" class="form-control parsley-validated" type="text" value="<?= !empty($editRecord[0]['address'])?$editRecord[0]['address']:'';?>" placeholder="Address Line 1" required>
                  </div>

                  <div class="form-group">
                    <label for="contact_number">Contact Number<span style="color:#F00">*</span></label>
                    <input name="contact_number" id="contact_number" class="form-control parsley-validated" minlength ="10" maxlength ="12" class="form-control parsley-validated" data-type="digits" value="<?= !empty($editRecord[0]['contact_number'])?$editRecord[0]['contact_number']:'';?>" placeholder="Contact Number" onkeypress="return isNumberKey(event);" required>
                  </div>

                  <div class="form-group">
                    <label for="contact_email">Contact Email<span style="color:#F00">*</span></label>
                    <input name="contact_email" id="contact_email" class="form-control parsley-validated" type="email" value="<?= !empty($editRecord[0]['contact_email'])?$editRecord[0]['contact_email']:'';?>" placeholder="Contact Email" required>
                  </div>

                  <h4>Social Accounts</h4>

                  <h4>Enter the details of the email account to be used to send the mails</h4>
                  <div class="form-group">
                    <label for="smtp_host">SMTP Host<span style="color:#F00">*</span></label>
                    <input name="smtp_host" id="smtp_host" class="form-control parsley-validated" type="text"  value="<?= !empty($editRecord[0]['smtp_host'])?$editRecord[0]['smtp_host']:'';?>" placeholder="SMTP Host" required>
                  </div>

                  <div class="form-group">
                    <label for="smtp_user">SMTP Email</label>
                    <input name="smtp_user" id="smtp_user" class="form-control parsley-validated" type="email"  value="<?= !empty($editRecord[0]['smtp_user'])?$editRecord[0]['smtp_user']:'';?>" placeholder="SMTP Email">
                  </div>

                  <div class="form-group">
                    <label for="smtp_pass">SMTP Pass</label>
                    <input name="smtp_pass" id="smtp_pass" class="form-control parsley-validated" type="password"  value="<?= !empty($editRecord[0]['smtp_pass'])?$editRecord[0]['smtp_pass']:'';?>" placeholder="SMTP Pass">
                  </div>

                  <div class="form-group">
                    <label for="protocol">Protocol</label>
                    <input name="protocol" id="protocol" class="form-control parsley-validated" type="text"  value="<?= !empty($editRecord[0]['protocol'])?$editRecord[0]['protocol']:'';?>" placeholder="Protocol">
                  </div>

                  <div class="form-group">
                    <label for="smtp_port">SMTP Port</label>
                    <input name="smtp_port" id="smtp_port" class="form-control parsley-validated" type="text"  value="<?= !empty($editRecord[0]['smtp_port'])?$editRecord[0]['smtp_port']:'';?>" placeholder="SMTP Port">
                  </div>

                  <div class="form-group">
                    <label for="smtp_timeout">SMTP Timeout</label>
                    <input name="smtp_timeout" id="smtp_timeout" class="form-control parsley-validated" type="text"  value="<?= !empty($editRecord[0]['smtp_timeout'])?$editRecord[0]['smtp_timeout']:'';?>" placeholder="SMTP Timeout">
                  </div>

                  <div class="form-group">
                  <input type="hidden" name="id" value="<?= !empty($editRecord[0]['id'])?$editRecord[0]['id']:'';?>" />
                  <button type="submit" class="btn bg-green" id="save" onclick="setdefaultdata()"><i class="fa fa-save"></i> Save</button>
                    <a type="button" class="btn btn-adn" href="<?php echo base_url('admin/dashboard'); ?>" id="cancel"><i class="fa fa-remove"></i> Cancel</a>
                  </div>
                  </form>
              </div>
          </div>
        </div>
    </div>
  </section>
</div>

<script type="text/javascript">
    
function isNumberKey(evt)
{
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57))
    return false;

  return true;
}
    
function setdefaultdata()
{
    if ($('#<?= $viewname ?>').parsley().isValid()) 
    {
        $.blockUI();
        $('#<?= $viewname ?>').submit();
    }
}

$(document).on('ready', function(e) {
  $(".textarea").wysihtml5();
});

function showimagepreview(input,preview) 
{
  var nm = $(input).attr("name");
  var fileUpload = document.getElementById(nm);

  var maximum = input.files[0].size/1024;
  if (input.files && input.files[0] && maximum <= 5120) 
  {
    var arr1  = input.files[0]['name'].split('.');
    var arr   = arr1[arr1.length - 1].toLowerCase(); 
    if(arr == 'jpg' || arr == 'jpeg' || arr == 'png')
    {
      //Initiate the FileReader object.
      var reader = new FileReader();
      //Read the contents of Image File.
      reader.readAsDataURL(fileUpload.files[0]);
      reader.onload = function (e)
      {
        //Initiate the JavaScript Image object.
        var image = new Image();

        //Set the Base64 string return from FileReader as source.
        image.src = e.target.result;

        //Validate the File Height and Width.
        image.onload = function ()
        {
          // var height = this.height;
          // var width = this.width;
          // if (height >= imgHeight && width >= imgWidth) 
          // {
            var filerdr = new FileReader();
            filerdr.onload = function(e) {
              $('#uploadPreview'+preview).attr('src', e.target.result);
            }
            filerdr.readAsDataURL(input.files[0]);
          // }
          // else
          // {
          //   $.confirm({'title': 'Alert','message': " <strong> Height and Width must not exceed 620px width. "+"<strong></strong>",'buttons': {'ok' : {'class'  : 'btn_center alert_ok'}}});
          //     return false;
          // }
          };
        }
      }
      else
      {
        $.alert({
            title: 'Alert!',
            content: "Please upload jpg | jpeg | png file only",
            buttons: {
              confirm:{btnClass:'hide'},
              cancel:
              {
                text:'ok',
                action: function(){
                    $("#"+nm).val('');
                },
              },
            },
        });
        return false;
      } 
    }
    else
    {
      $.alert({
          title: 'Alert!',
          content: "Maximum upload size 5 MB.",
          buttons: {
            confirm:{btnClass:'hide'},
            cancel:
            {
              text:'ok',
              action: function(){
                  $("#"+nm).val('');
              },
            },
          },
      });
      return false;
    }
  
}
</script>
