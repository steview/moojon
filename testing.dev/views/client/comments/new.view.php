<h1>New comment</h1>
<ul id="actions">
	<li><?php echo link_to('Comments', comments_uri()); ?></li>
</ul>
<?php partial('form', array('comment' => $comment)); ?>