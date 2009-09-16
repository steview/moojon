<h1><?php echo $second; ?></h1>
<ul id="actions">
	<li><?php echo link_to('Edit', edit_second_uri($second)); ?></li>
	<li><?php echo link_to('Delete', delete_second_uri($second)); ?></li>
</ul>
<?php partial('dl', array('second' => $second)); ?>