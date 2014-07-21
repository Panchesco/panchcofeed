<?php echo form_open('C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=panchcofeed'.AMP.'method=delete', '')?>
<?php foreach($damned as $app_id):?>
	<?=form_hidden('delete[]', $app_id)?>
<?php endforeach;?>
<p class="shun"><?=lang('delete_question')?></p>
<ul>
<?php foreach($apps as $key=>$row) {?>
	<li><?php echo $row->application ;?></li>
<?php }?>
</ul>
<p class="notice"><?=lang('action_can_not_be_undone')?></p>

<p>
	<?=form_submit(array('name' => 'submit', 'value' => lang('delete'), 'class' => 'submit'))?> <?php echo anchor('?/cp/addons_modules/show_module_cp?module=panchcofeed'.AMP.'method=cancel',lang('cancel'));?></p>
<?php echo form_close()?>