<h1>New post</h1>
<ul id="actions">
	<li><?php echo link_to('Posts', posts_uri()); ?></li>
</ul>
<?php partial('form', array('post' => $post)); ?>