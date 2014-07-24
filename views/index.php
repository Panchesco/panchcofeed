<?php if(count($apps)==0) { ?>

<p><a href="<?php echo BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=panchcofeed'.AMP.'method=create'; ?>"><?php echo lang('add_app');?></a></p>

<?php } else { ?>
<?php echo form_open(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=panchcofeed'.AMP.'method=delete_confirm', '') ; ?>
<h2><?php echo lang('mcp_index_title') ;?></h2>
<table class="mainTable" cellpadding="0" cellspacing="0" border="0">
	<thead>
		<tr>
			<th><?php echo lang('id');?></th>
			<th><?php echo lang('application');?></th>
			<th><?php echo lang('ig_authorization');?></th>
			<th><input type="checkbox" id="select_all" name="select_all" value="" class"toggle_all" /> <?php echo lang('delete');?></th>
		</tr>		
	</thead>
	<tbody>
	<?php foreach($apps as $row) {?>
		<tr>
			<td><?php echo $row['app_id'] ;?></td>
			<td><a href="<?php echo BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=panchcofeed'.AMP.'method=modify'.AMP.'app_id='.$row['app_id']; ?>"><?php echo $row['application'] ;?></a></td>
			<td class="authentication-cell" data-app_id="<?php echo $row['app_id'];?>" data-client_id="<?php echo $row['client_id'];?>" data-redirect_uri="<?php echo $row['redirect_uri'];?>">
				<img src="<?php echo site_url('themes/third_party/panchcofeed/imgs/list-row-ajax-loader.gif');?>" alt="Checking authentication status" />
			</td>
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
<script>
(function($){
	$(document).ready(function(){
	
	function popupwindow(url, title, w, h) 
	{
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}
		
	
	
	
		function igAuthWin(clientId,redirectUri)
		{
	
			url = 
			popupwindow("https://api.instagram.com/oauth/authorize/?client_id="+clientId+"&redirect_uri="+redirectUri+"&response_type=code",'ig_auth',400,320);

			/*window.open("https://api.instagram.com/oauth/authorize/?client_id="+clientId+"&redirect_uri="+redirectUri+"&response_type=code","ig_auth",'width=400,height=320');*/

		}
	
			$(".authentication-cell").each(function(){
			
				var redirectUri	= $(this).attr("data-redirect_uri");
				var appId		= $(this).attr("data-app_id");
				var clientId	= $(this).attr('data-client_id');
				var accessToken	= $(this).attr("data-access_token");
				var targ		= $(this);
				
				$.ajax({
					type: "POST",
					url: "<?php echo $auth_confirm_url;?>"+appId,
					data: {
						app_id: appId,
						access_token: accessToken,
						client_id: clientId,
					}
				})
				.done(function(data){
					$(targ).html(data);	
					$(targ).children("a").click(function(){
						igAuthWin(clientId,redirectUri);
					});
				});
			});	
	});

})(jQuery)
</script>
