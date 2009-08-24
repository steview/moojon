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
	<link href="/css/core.css" rel="stylesheet" type="text/css" media="screen, projection" />
	<link href="/css/layout.css" rel="stylesheet" type="text/css" media="screen, projection" />
	<link href="/css/form.css" rel="stylesheet" type="text/css" media="screen, projection" />
	<link href="/css/print.css" rel="stylesheet" type="text/css" media="print" />
	<script type="text/javascript" src="/js/jquery.js"></script>
	<script type="text/javascript" src="/js/jquery.flash.js"></script>
	<script type="text/javascript" src="/js/site.js"></script>
</head>
<body id="body_" class="js-disabled"> 

<ul id="skiplinks">
	<li><a accesskey="s" href="#content_main">Skip to main content</a></li>
</ul>

<div id="container">

	<div id="header">
	
		<a href="/" id="logo" title="View the homepage"><img src="/images/logo.gif" alt="My new site" width="" height="" /></a>
		
	</div><!-- /header -->
	
	<div id="nav">
	
		<ul>
			<li id="nav_home"><a href="/" class="replace" accesskey="1">Home <span></span></a></li>
			<li id="nav_about"><a href="/.php" class="replace">About us <span></span></a></li>
			<li id="nav_contact"><a href="/.php" class="replace">Contact us <span></span></a></li>
			<li id="nav_"><a href="/.php" class="replace"> <span></span></a></li>
		</ul>
	
	</div><!-- /nav -->
	
	<div id="content">
		
		<?php if (moojon_flash::has('notification')) { ?>
			<p><?php echo moojon_flash::get('notification'); ?></p>
		<?php } ?>
		
		YIELD
		
	</div><!-- /content -->
	
	<div id="footer">
	
		<ul>
			<li>© <?php echo date("Y") ?> domain.com</li>
			<li><a href="/terms.php" accesskey="8">Terms &amp; Conditions</a></li>
			<li><a href="/privacy.php">Privacy Policy</a></li>
			<li id="kyan"><a href="http://www.kyanmedia.com/" target="_blank">Made by Kyanmedia</a></li>
		</ul>
		
	</div><!-- /footer -->

</div><!-- /container -->

</body>
</html>