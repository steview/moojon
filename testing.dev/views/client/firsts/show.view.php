<h1><?php echo $first; ?></h1>
<ul id="actions">
	<li><?php echo link_to('Edit', edit_first_uri($first)); ?></li>
	<li><?php echo link_to('Delete', delete_first_uri($first)); ?></li>
</ul>
<?php partial('dl', array('first' => $first)); ?>