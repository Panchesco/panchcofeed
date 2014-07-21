<?php if(count($apps)==0) { ?>

<p><a href="<?php echo BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=panchcofeed'.AMP.'method=create'; ?>"><?php echo lang('add_first_app');?></a></p>

<?php } else { ?>
<?php echo form_open(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=panchcofeed'.AMP.'method=delete_confirm', '') ; ?>
<h2><?php echo lang('mcp_index_title') ;?></h2>
<table class="mainTable" cellpadding="0" cellspacing="0" border="0">
	<thead>
		<tr>
			<th>ID</th>
			<th>Application</th>
			<th><input type="checkbox" id="select_all" name="select_all" value="" class"toggle_all" /> Delete</th>
		</tr>		
	</thead>
	<tbody>
	<?php foreach($apps as $row) {?>
		<tr>
			<td><?php echo $row['app_id'] ;?></td>
			<td><a href="<?php echo BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=panchcofeed'.AMP.'method=modify'.AMP.'app_id='.$row['app_id']; ?>"><?php echo $row['application'] ;?></a></td>
			<td><input type="checkbox" id="delete_box_<?php echo $row['app_id'];?>" name="toggle[]" value="<?php echo $row['app_id'];?>" /></td>
		</tr>
	<?php } ?>
		</tbody>
</table>
<p><input class="submit" type="submit" value="Delete" /></p>

<div class="tableFooter">
</div>
<?php echo form_close();?>
<?php } ?>
