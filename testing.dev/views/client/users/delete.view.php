<h1>Delete user</h1>
<ul id="actions">
	<li><?php echo a_tag($user, user_uri($user)); ?></li>
	<li><?php echo a_tag('Edit', edit_user_uri($user)); ?></li>
</ul>
<h2><?php echo $user; ?></h2>
<?php echo delete_form_for($user); ?>