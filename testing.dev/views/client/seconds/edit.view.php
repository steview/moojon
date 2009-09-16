<h1>Update second</h1>
<ul id="actions">
	<li><?php echo link_to($second, second_uri($second)); ?></li>
	<li><?php echo link_to('Delete', delete_second_uri($second)); ?></li>
</ul>
<?php partial('form', array('second' => $second)); ?>