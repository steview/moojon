<h1>Update third</h1>
<ul id="actions">
	<li><?php echo link_to($third, third_uri($third)); ?></li>
	<li><?php echo link_to('Delete', delete_third_uri($third)); ?></li>
</ul>
<?php partial('form', array('third' => $third)); ?>