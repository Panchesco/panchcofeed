<?php if(count($apps)==0) { ?>

<p><a href="<?php echo BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=panchcofeed'.AMP.'method=create'; ?>">Add your first app</a></p>

<?php } else { ?>

<?php echo form_open('', '','') ; ?>
<h2><?php echo lang('mcp_index_title') ;?></h2>
<table class="mainTable" cellpadding="0" cellspacing="0" border="0">
	<thead>
		<tr>
			<th>ID</th>
			<th>Application</th>
		</tr>		
	</thead>
	<tbody>
	<?php foreach($apps as $row) {?>
		<tr>
			<td><?php echo $row['app_id'] ;?></td>
			<td><a href="<?php echo BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=panchcofeed'.AMP.'method=modify'.AMP.'app_id='.$row['app_id']; ?>"><?php echo $row['application'] ;?></a></td>
		</tr>
	<?php } ?>
		</tbody>
</table>
<div class="tableFooter">
</div>
<?php echo form_close();?>
<?php } ?>
