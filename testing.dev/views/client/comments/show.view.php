<h1>Comment</h1>
<ul id="actions">
	<li><?php echo a_tag('Edit', edit_comment_uri($comment)); ?></li>
	<li><?php echo a_tag('Delete', delete_comment_uri($comment)); ?></li>
</ul>
<h2><?php echo $comment; ?></h2>
<?php echo dl_for($comment); ?>