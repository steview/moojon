<h1><?php echo $third; ?></h1>
<ul id="actions">
	<li><?php echo link_to('Edit', edit_third_uri($third)); ?></li>
	<li><?php echo link_to('Delete', delete_third_uri($third)); ?></li>
</ul>
<?php partial('dl', array('third' => $third)); ?>