<h1>Update first</h1>
<ul id="actions">
	<li><?php echo link_to($first, first_uri($first)); ?></li>
	<li><?php echo link_to('Delete', delete_first_uri($first)); ?></li>
</ul>
<?php partial('form', array('first' => $first)); ?>