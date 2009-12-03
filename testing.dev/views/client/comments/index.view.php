<h1>Comments</h1>
<ul id="actions">
	<li><?php echo a_tag('New', new_comment_uri()); ?></li>
</ul>
<?php echo table_for($comments); ?>