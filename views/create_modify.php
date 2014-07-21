
<p><?php echo lang('create_modify_copy');?></p>

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
			<td><?php echo lang('Application');?>:</td><td><input type="text" name="application" value="<?php echo $application ;?>" /></td>
		</tr>		
		<tr>
			<td><?php echo lang('client_id');?>:</td><td><input type="text" name="client_id" value="<?php echo $client_id ;?>" /></td>
		</tr>		
		<tr>
			<td><?php echo lang('client_secret');?>:</td><td><input type="text" name="client_secret" value="<?php echo $client_secret ;?>" /></td>
		</tr>
		<tr>
			<td><?php echo lang('website_url');?>:</td><td><input readonly type="text" name="website_url" value="<?php echo site_url() ;?>" /></td>
		</tr>
		<tr>
			<td><?php echo lang('redirect_uri');?>:</td><td><input readonly type="text" name="redirect_uri" value="<?php echo $redirect_uri ;?>" /></td>
		</tr>
	</tbody>
</table>
<?php if(isset($app_id)) {?>
<input type="hidden" name="app_id" value="<?php echo $app_id ;?>" />
<?php } ?>
<p><input class="submit" type="submit" name="submit" value="Save" /></p>
<?php echo form_close();?>

<?php if($method=="modify") {?>
<table class="mainTable solo" cellpadding="0" cellspacing="0" border="0">
	<thead>
		<tr>
			<th colspan="2">
			Authentication
			</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td colspan="2">
			<?php if(false === $authenticated) { ?>
			<p><?php echo lang('ig_auth_prompt');?>:</p>
			<?php } else { ?>	
			<p><?php echo lang('ig_auth_current');?> <a href="https://instagram.com/accounts/manage_access" target="_blank"><?php echo lang('ig_manage_apps');?></a><p>
			<?php } ?>
			</td>
		</tr>	
	</tbody>
</table>
<?php if(false === $authenticated) { ?>
<p><a id="ig-authorize" class="btn submit" href="javascript:void(0);"><?php echo lang('authorize') ;?></a></p>
<?php } ?>			
<script>
	(function($){
		$(document).ready(function(){
			$('#ig-authorize').on('click',function(){
				window.open("https://api.instagram.com/oauth/authorize/?client_id=<?php echo $client_id ;?>&redirect_uri=<?php echo $redirect_uri;?>&response_type=code","",'width=400,height=320');
			})
		});
	})(jQuery)
</script>
<?php } ?>






