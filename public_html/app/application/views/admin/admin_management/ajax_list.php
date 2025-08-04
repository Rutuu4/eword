<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
$viewname = $this->router->uri->segments[2];

if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
?>

<table class="table table-bordered table-striped table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter">
    <thead>
        <tr>
            <th width="5%" class="sorting_disabled text-center" rowspan="1" colspan="1"> 
                <div class="">
                    <input type="checkbox" class="selecctall" id="selecctall">
                </div>
            </th>
            <th width="30%" data-direction="desc" data-sortable="true" class="<?php if (isset($sortfield) && $sortfield == 'name') {
                if ($sortby == 'asc') {
                    echo "sorting_desc";
                } else {
                    echo "sorting_asc";
                }
            } ?>" rowspan="1" colspan="1">
                <a href="javascript:void(0);" onclick="applysortfilte_contact('name', '<?php echo $sorttypepass; ?>')">
                    <?= ucwords($this->lang->line('name')) ?>
                </a>
            </th>
            <th width="50%" class="<?php if (isset($sortfield) && $sortfield == 'email') {
                if ($sortby == 'asc') {
                    echo "sorting_desc";
                } else {
                    echo "sorting_asc";
                }
            } ?>" rowspan="1" colspan="1">
                <a href="javascript:void(0);" onclick="applysortfilte_contact('email', '<?php echo $sorttypepass; ?>')">
                    <?= ucwords($this->lang->line('email')) ?>
                </a>
            </th>
            <th width="15%" class="sorting_disabled" rowspan="1" colspan="1">
                <?= ucwords($this->lang->line('action')) ?>
            </th>
        </tr>
    </thead>

    <tbody>
    <?php
    if (!empty($datalist) && count($datalist) > 0) {
    $i = !empty($this->router->uri->segments[4]) ? $this->router->uri->segments[4] + 1 : 1;
    foreach ($datalist as $row) {
        ?>
            <tr <?php if ($i % 2 == 1) { ?>class="bgtitle" <?php } ?> >
                <td class="text-center">
                    <div class="" style="position: relative;">
                    <?php if($this->admin_session['id'] != $row['id']) { ?>
                        <input type="checkbox" class="checkbox1 mycheckbox" name="check[]" value="<?php echo $row['id'] ?>">
                    <?php } ?>
                    </div>
                </td>
                <td><?php echo ucfirst($row['name']) ?></td>
                <td><?php echo $row['email'] ?></td>
                <td>
                    <?php if (!empty($row['status']) && $row['status'] == 1 && $this->admin_session['id'] != $row['id']) { ?>
                      <a class="btn btn-xs bg-green tooltips" href="javascript:void(0)" onclick="return status_change('0',<?= $row['id'] ?>)" title="<?= $this->lang->line('active') ?>"><i class="fa fa-check"></i>
                      </a>
                    <?php } else if($this->admin_session['id'] != $row['id']) { ?>
                        <a class="btn btn-xs bg-yellow tooltips" href="javascript:void(0)" onclick="return status_change('1',<?= $row['id'] ?>)" title="<?= $this->lang->line('inactive') ?>"> 
                            <i class="fa fa-ban"></i>
                       </a>
                    <?php } else { ?>
                        <button class="btn btn-xs bg-green tooltips disabled" title="<?= $this->lang->line('active') ?>"> 
                            <i class="fa fa-check"></i>
                       </button>
                    <?php } ?>
                    <?php if($this->admin_session['id'] != $row['id'])
                    { ?>
                    <a class="btn btn-xs bg-light-blue tooltips" href="javascript:void(0);" onclick="send_email('<?= $row['id'] ?>');" title="<?= $this->lang->line('resend_mail') ?>">
                      <i class="fa fa-envelope"></i>
                    </a>
                    <?php } else { ?>
                    <button class="btn btn-xs bg-light-blue tooltips disabled" title="<?= $this->lang->line('resend_mail') ?>"> <i class="fa fa-envelope"></i> </button>
                    <?php } ?>
                    <a class="btn btn-xs bg-blue tooltips" href="<?= $this->config->item('admin_base_url') . $viewname; ?>/edit_record/<?= $row['id'] ?>" title="<?= $this->lang->line('edit') ?>"><i class="fa fa-pencil"></i></a>
                    <?php if($this->admin_session['id'] != $row['id'])
                    { ?>
                    <button class="btn btn-xs bg-red tooltips" onclick="delete_record('<?php echo $row['id'] ?>', '<?php echo rawurlencode(ucfirst(strtolower($row['name']))) ?>');" title="<?= $this->lang->line('delete') ?>"> <i class="fa fa-times"></i> </button>
                    <?php } else { ?>
                    <button class="btn btn-xs bg-red tooltips disabled" title="<?= $this->lang->line('delete') ?>"> <i class="fa fa-times"></i> </button>
                    <?php } ?>
                    
                    <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                    <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" />
                </td>
            </tr>
    <?php }
    } else { ?>
        <tr>
            <td class="text-center" colspan="100%">
                <?= $this->lang->line('no_record_found') ?>
            </td>
        </tr> 
    <?php } ?>
</tbody>
</table>

<div class="row">
    <div class="col-sm-12 text-center">
        <div class="pagination float-right" id="common_tb">
            <?php
            if (isset($pagination)) {
                echo $pagination;
            }
            ?>
        </div>
    </div>
</div>