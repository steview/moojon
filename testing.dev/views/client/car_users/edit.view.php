<h1>Edit car user</h1>
<ul id="actions">
	<li><?php echo a_tag($car_user, car_user_uri($car_user)); ?></li>
	<li><?php echo a_tag('Delete', delete_car_user_uri($car_user)); ?></li>
</ul>
<?php partial('form', array('car_user' => $car_user)); ?>