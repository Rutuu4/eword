<?php
  $this->flashdata = $this->session->flashdata('message');
?>

<footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Developed by:</b> <a href="mailto:rathodnishant@gmail.com">Nishant (<a href="tel:+919428011170">+91 9428011170</a>)</a>
    </div>
    <strong>Copyright &copy; <?=date('Y');?></strong> All rights reserved.
  </footer>

<div class="control-sidebar-bg"></div>
</div>
<script type="text/javascript">
  window.ParsleyConfig = {
    trigger: 'focusout',
  };
</script>
<script src="<?= $this->config->item('js_url') ?>bootstrap.min.js"></script>
<script src="<?= $this->config->item('js_url') ?>jquery-ui.min.js"></script>
<script src="<?= $this->config->item('js_url') ?>app.min.js"></script>
<script src="<?= $this->config->item('js_url') ?>parsley.min.js"></script>
<script src="<?= $this->config->item('js_url') ?>bootstrap-notify.min.js"></script>
<script src="<?= $this->config->item('js_url') ?>jquery-confirm.js"></script>
<script src="<?= $this->config->item('js_url') ?>jquery.blockUI.js"></script>
<script src="<?= $this->config->item('js_url') ?>bootstrap3-wysihtml5.all.min.js"></script>
<script src="<?= $this->config->item('js_url') ?>tooltipster.bundle.min.js"></script>
<script>

$(document).ready(function() {
    $('.tooltips').tooltipster({
        theme: 'tooltipster-noir'
    });
});

$.notifyDefaults({
    z_index: 1031,
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
    keyboardEnabled: true,
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