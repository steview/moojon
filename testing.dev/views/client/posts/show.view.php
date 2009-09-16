<h1><?php echo $post; ?></h1>
<ul id="actions">
	<li><?php echo link_to('Edit', edit_post_uri($post)); ?></li>
	<li><?php echo link_to('Delete', delete_post_uri($post)); ?></li>
</ul>
<?php partial('dl', array('post' => $post)); ?>