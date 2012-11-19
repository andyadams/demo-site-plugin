<!HTML>
<html>
<head><title>Sign in to demo site</title></head>
<body>
	<h1>Sign in to demo site</h1>
	<form method="post" action="<?php echo admin_url( 'admin.php' ); ?>">
		<input type="submit" value="Sign in">
		<input type="hidden" name="action" value="demo_site_plugin_login" />
	</form>
</body>
</html>