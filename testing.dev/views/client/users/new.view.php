<h1>New user</h1>
<ul id="actions">
	<li><?php echo link_to('Users', users_uri()); ?></li>
</ul>
<?php partial('form', array('user' => $user)); ?>