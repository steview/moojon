<h1>Users</h1>
<ul id="actions">
	<li><?php echo link_to('New', new_user_uri()); ?></li>
</ul>
<?php echo table_for($users); ?>