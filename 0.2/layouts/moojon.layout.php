<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Page name - Site name</title>
	<meta name="description" content="***************************" />
	<meta http-equiv="Content-Language" content="en-gb" />
	<link href="/favicon.ico" rel="shortcut icon" />
	<link href="/css/moojon.css" rel="stylesheet" type="text/css" media="screen, projection" />
	<script type="text/javascript" src="/js/project.js"></script>
</head>
<body id="body_" class="js-disabled">
<div id="container">
	<div id="header"><img src="/images/logo.png" alt="Moojon logo" width="250" height="70" /></div>
	<ul id="nav">
		<li><?php echo link_to('Index', '/'); ?></li>
		<li><?php echo link_to('Users', users_uri()); ?></li>
		<li><?php echo link_to('Posts', posts_uri()); ?></li>
		<li><?php echo link_to('Comments', comments_uri()); ?></li>
	</ul>
	<div id="content">
	<?php if (moojon_flash::has('notification')) { ?>
		<p class="flash"><?php echo moojon_flash::get('notification'); ?></p>
	<?php } ?>
	YIELD
	</div>
	<div id="footer"></div>
</div>
</body>
</html>