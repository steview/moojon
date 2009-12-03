<h1>New post</h1>
<ul id="actions">
	<li><?php echo a_tag('Posts', posts_uri()); ?></li>
</ul>
<?php partial('form', array('post' => $post)); ?>