<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
$path_comman = $this->config->item('base_url').'admin/'.$viewname.'/';
?>

<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-user-secret"></i> <?= $this->lang->line('admin_management'); ?>
      <small>List of all the admins</small>
    </h1>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <!-- <h3 class="box-title">List of all the admins</h3> -->
          </div>
          <div class="box-body">
            <div class="row form-inline">
              <div class="col-sm-2">
                <?php $per_page = per_page_array();?>
                <label>Show 
                <select class="form-control input-sm" name="table_enteries" onchange="changepages();" id="perpage">
                  <?php foreach ($per_page as $key => $value) 
                  {
                      ?>
                      <option <?php if (!empty($perpage) && $perpage == $value) { echo 'selected="selected"'; } ?> value="<?=$key?>"><?=$value?></option>
                      <?php
                  }
                  ?>
                </select>
                entries</label>
              </div>
              <div class="col-sm-3">
                <label>Action: </label>
                <select class="form-control input-sm" name="table_action" id="submit_actions">
                  <option value="">Select</option>
                  <option value="delete">Delete</option>
                  <option value="publish">Publish</option>
                  <option value="unpublish">Unpublish</option>
                </select>
                <button class="btn bg-blue" id="allcheck"><i class="fa fa-caret-right"></i> Submit</button>
              </div>
              <div class="col-sm-5">
                <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
                <input class="" type="hidden" name="path_comman" id="path_comman" value="<?=!empty($path_comman)?$path_comman:''?>">
                <label>Search: 
                  <input type="search" name="searchtext" id="searchtext" class="form-control input-sm" value="<?=!empty($searchtext)?$searchtext:''?>">
                  <button onclick="search_data('changesearch')" class="btn bg-success"><i class="fa fa-search"></i>&nbsp;&nbsp;&nbsp;Search</button>
                  <button class="btn bg-info" onclick="clearfilter_data()"><i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;View all</button>
                </label>
              </div>
               <div class="col-sm-2">
                <a class="btn bg-blue" href="<?=base_url('admin/'.$viewname.'/add_record');?>">
                  <i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp;Add New Record
                </a> 
              </div>
            </div>

            <div class="row form-inline">
              <div class="col-sm-6">
                <label id="cnt_selected">0 Record Selected</label> | 
                <a class="" onclick="remove_selection();" href="javascript:void(0);">Remove Selection</a>
              </div>
            </div>
            
            <div id="common_div">
              <?php $this->load->view('admin/'.$viewname.'/ajax_list') ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<script type="text/javascript" src="<?=$this->config->item('js_url')?>admin/common.js"></script>
<script type="text/javascript">
  function send_email(id)
    {      
      if(id != '')
      {
        $.confirm({
          content: 'Are you sure you want to send the credentials to the user? If password is not set it would create new password or else it would reset the password.',
          buttons: {
              confirm: {
                text: 'Yes',
                action: function(){
                  $.ajax({
                    type: "POST",
                    url: "<?php echo $this->config->item('admin_base_url').$viewname.'/send_email';?>",
                    dataType: 'json',
                    data: {'id':id},
                    success: function(data){
                      if(data.flag != '')
                      {
                        $.notify({ message: data.msg },{ type: data.flag, });
                      }
                    }
                  });
                }
              },
          },
        });
      }
      else
      {
        $.alert({
            title: 'Alert!',
            content: "Unable to send the mail. Please contact the administrator.",
            buttons: {
              confirm:{btnClass:'hide'},
              cancel:{text:'ok'},
            },
        });
      }
    }
</script>