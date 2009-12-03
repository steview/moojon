<h1>New user</h1>
<ul id="actions">
	<li><?php echo a_tag('Users', users_uri()); ?></li>
</ul>
<?php partial('form', array('user' => $user)); ?>