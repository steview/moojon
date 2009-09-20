<h1>Edit comment</h1>
<ul id="actions">
	<li><?php echo link_to($comment, comment_uri($comment)); ?></li>
	<li><?php echo link_to('Delete', delete_comment_uri($comment)); ?></li>
</ul>
<?php partial('form', array('comment' => $comment)); ?>