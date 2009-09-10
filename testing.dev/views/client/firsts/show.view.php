<h1><?php echo $first; ?></h1>

<div id="content_main">

<ul>
	<li><?php echo link_to('Edit', edit_first_uri($first)); ?></li>
	<li><?php echo link_to('Delete', delete_first_uri($first)); ?></li>
</ul>

<?php
partial('dl', array('first' => $first));
?>

</div><!-- /content_main -->

<div id="content_sub">



</div><!-- /content_sub -->