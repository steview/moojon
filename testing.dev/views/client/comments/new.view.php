<h1>New comment</h1>
<ul id="actions">
	<li><?php echo a_tag('Comments', comments_uri()); ?></li>
</ul>
<?php partial('form', array('comment' => $comment)); ?>