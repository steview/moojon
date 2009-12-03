<h1>Edit user</h1>
<ul id="actions">
	<li><?php echo a_tag($user, user_uri($user)); ?></li>
	<li><?php echo a_tag('Delete', delete_user_uri($user)); ?></li>
</ul>
<?php partial('form', array('user' => $user)); ?>