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
		
		<?php if (moojon_flash::has('notification') == true) { ?>
			<p><?php echo moojon_flash::get('notification'); ?></p>
		<?php } ?>
		
		YIELD
	
	</div><!-- /content -->
	
	<div id="footer">
	
	</div><!-- /footer -->
	
</div><!-- /container -->

<?php moojon_debug::render(); ?>

</body>
</html>