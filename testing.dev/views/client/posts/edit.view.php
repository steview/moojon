<h1>Update post</h1>
<ul id="actions">
	<li><?php echo link_to($post, post_uri($post)); ?></li>
	<li><?php echo link_to('Delete', delete_post_uri($post)); ?></li>
</ul>
<?php partial('form', array('post' => $post)); ?>