<h1>New car user</h1>
<ul id="actions">
	<li><?php echo a_tag('Car users', car_users_uri()); ?></li>
</ul>
<?php partial('form', array('car_user' => $car_user)); ?>