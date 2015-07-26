<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
</head>
<body>
<p><?php echo $msg ?></p>
<p><a href="javascript:void(0);" onclick="self.close();"><?php echo $close_window;?></a></p>

<script>
window.onunload = refreshParent;
    function refreshParent() {
        window.opener.location.reload();
    }
</script>

</body>
</html>