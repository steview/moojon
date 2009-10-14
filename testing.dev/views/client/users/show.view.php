<h1>User</h1>
<ul id="actions">
	<li><?php echo link_to('Edit', edit_user_uri($user)); ?></li>
	<li><?php echo link_to('Delete', delete_user_uri($user)); ?></li>
</ul>
<h2><?php echo $user; ?></h2>
<?php
echo dl_for($user);
echo relationship_tables($user);
?>