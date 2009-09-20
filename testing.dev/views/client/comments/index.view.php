<h1>Comments</h1>
<ul id="actions">
	<li><?php echo link_to('New', new_comment_uri()); ?></li>
</ul>
<?php echo table_for($comments); ?>