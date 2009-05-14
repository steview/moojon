<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Page name - Site name</title>
	<meta name="description" content="***************************" />
	<meta name="keywords" content="**insert keywords**" />
	<meta http-equiv="Content-Language" content="en-gb" />
	<meta name="author" content="http://www.kyanmedia.com" />
	<link href="/favicon.ico" rel="shortcut icon" />
	<?php echo moojon_quick_tags::render_css_tags(); ?>
	<?php echo moojon_quick_tags::render_js_tags(); ?>
</head>
<body id="body_" class="js-disabled"> 

<ul id="skiplinks">
	<li><a accesskey="s" href="#content_main">Skip to main content</a></li>
</ul>

<div id="container">

	<div id="header">
	
	</div><!-- /header -->
	
	<div id="nav">
	
	</div><!-- /nav -->
	
	<div id="content">
		
		<?php if (moojon_flash::has('message') == true) { ?>
			<p><?php echo moojon_flash::get('message'); ?></p>
		<?php } ?>
		
		<?php echo YIELD; ?>
	
	</div><!-- /content -->
	
	<div id="footer">
	
	</div><!-- /footer -->
	
	<div style="clear:both">
		<h2>Session</h2>
		<ul>
			<?php foreach ($_SESSION as $key => $value) {
				echo "<li>$key: ";
				if (is_array($value) == true) {
					print_r($value);
				} else {
					echo $value;
				}
				echo "</li>";
			} ?>
		</ul>
		<h2>Cookies</h2>
		<ul>
			<?php foreach ($_COOKIE as $key => $value) {
				echo "<li>$key: $value</li>";
			} ?>
		</ul>
		<h2>URI</h2>
		<ul>
			<li>App: <?php echo get_class($this); ?></li>
			<li>Controller: <?php echo $this->controller_name; ?></li>
			<li>Action: <?php echo $this->action_name; ?></li>
		</ul>
		<h2>Paths</h2>
		<ul>
			<li>app_path: <?php echo moojon_paths::get_app_path(); ?></li>
			<li>controller_path: <?php echo moojon_paths::get_controller_path($this->controller_name); ?></li>
			<li>layout_path: <?php echo moojon_paths::get_layout_path($this->get_layout()); ?></li>
			<li>view_path: <?php echo moojon_paths::get_view_path($this->get_view()); ?></li>
		</ul>
		<p><?php //print_r(moojon_uri::get_apps()); ?></p>
		<p><?php //print_r(moojon_uri::get_controllers(get_class($this))); ?></p>
	</div>

</div><!-- /container -->

</body>
</html>