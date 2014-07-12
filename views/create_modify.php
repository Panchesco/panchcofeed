<p><?php echo lang('create_modify_copy');?></p>

<?php echo form_open('C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=panchcofeed'.AMP.'method='. $method, '')?>
<table class="mainTable solo" cellpadding="0" cellspacing="0" border="0">
	<thead>
		<tr>
			<th colspan="2">
			Client Settings
			</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?php echo lang('Application');?>:</td><td><input type="text" name="application" value="<?php echo $application ;?>" />
		</tr>		
		<tr>
			<td><?php echo lang('client_id');?>:</td><td><input type="text" name="client_id" value="<?php echo $client_id ;?>" />
		</tr>		
		<tr>
			<td><?php echo lang('client_secret');?>:</td><td><input type="text" name="client_secret" value="<?php echo $client_secret ;?>" />
		</tr>
	</tbody>
</table>
<?php if(isset($app_id)) {?>
<input type="hidden" name="app_id" value="<?php echo $app_id ;?>" />
<?php } ?>
<p><input class="submit" type="submit" name="submit" value="Save" /></p>
<?php echo form_close();?>


