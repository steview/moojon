<h1>Delete post</h1>
<ul id="actions">
	<li><?php echo link_to($post, post_uri($post)); ?></li>
	<li><?php echo link_to('Edit', edit_post_uri($post)); ?></li>
</ul>
<h2><?php echo $post; ?></h2>
<?php partial('delete_form', array('post' => $post)); ?>