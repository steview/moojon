<h1>Posts</h1>
<ul id="actions">
	<li><?php echo a_tag('New', new_post_uri()); ?></li>
</ul>
<?php echo table_for($posts); ?>