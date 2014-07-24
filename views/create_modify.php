<p><?php echo lang('create_modify');?></p>
<?php echo form_open('C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=panchcofeed'.AMP.'method='. $method . AMP.'app_id='.$app_id, '')?>
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
			<td><?php echo lang('website_url');?>:</td><td><input readonly type="text" name="website_url" value="<?php echo site_url() ;?>" /></td>
		</tr>
		<tr>
			<td><?php echo lang('redirect_uri');?>:</td><td><input readonly type="text" name="redirect_uri" value="<?php echo $redirect_uri ;?>" /></td>
		</tr>
		<tr>
			<td><?php echo lang('Application');?>:</td><td><input type="text" name="application" value="<?php echo $application ;?>" /></td>
		</tr>		
		<tr>
			<td><?php echo lang('client_id');?>:</td><td><input type="text" name="client_id" value="<?php echo $client_id ;?>" /></td>
		</tr>		
		<tr>
			<td><?php echo lang('client_secret');?>:</td><td><input type="text" name="client_secret" value="<?php echo $client_secret ;?>" /></td>
		</tr>

	</tbody>
</table>
<?php if(isset($app_id)) {?>
<input type="hidden" name="app_id" value="<?php echo $app_id ;?>" />
<?php } ?>
<p>
<?php if($method == "create") { ?>
<input class="submit" type="submit" name="submit" value="<?php echo lang('create');?>" />
<?php } else { ?>
<input class="submit" type="submit" name="submit" value="<?php echo lang('update');?>" />
<?php } ?></p>
<?php echo form_close();?>











