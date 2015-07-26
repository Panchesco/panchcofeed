<?php if(FALSE === $authenticated) { ?>
	<a href="javascript:void(0);" class="submit ig-authorize" data-client_id="<?php echo $client_id;?>" data-app_id="<?php echo $app_id;?>"><?php echo lang('authorize');?></a> 
<?php } else { ?>
	<?php echo lang('ig_auth_current');?>
<?php } ?>