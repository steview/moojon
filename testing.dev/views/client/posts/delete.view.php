<h1>Delete post</h1>
<ul id="actions">
	<li><?php echo a_tag($post, post_uri($post)); ?></li>
	<li><?php echo a_tag('Edit', edit_post_uri($post)); ?></li>
</ul>
<h2><?php echo $post; ?></h2>
<?php echo delete_form_for($post); ?>