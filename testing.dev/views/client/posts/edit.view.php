<h1>Edit post</h1>
<ul id="actions">
	<li><?php echo a_tag($post, post_uri($post)); ?></li>
	<li><?php echo a_tag('Delete', delete_post_uri($post)); ?></li>
</ul>
<?php partial('form', array('post' => $post)); ?>