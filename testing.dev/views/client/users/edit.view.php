<h1>Edit user</h1>
<ul id="actions">
	<li><?php echo link_to($user, user_uri($user)); ?></li>
	<li><?php echo link_to('Delete', delete_user_uri($user)); ?></li>
</ul>
<?php partial('form', array('user' => $user)); ?>