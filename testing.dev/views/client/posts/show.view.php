<h1>Post</h1>
<ul id="actions">
	<li><?php echo link_to('Edit', edit_post_uri($post)); ?></li>
	<li><?php echo link_to('Delete', delete_post_uri($post)); ?></li>
</ul>
<h2><?php echo $post; ?></h2>
<?php echo dl_for($post); ?>