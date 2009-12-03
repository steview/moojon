<h1>Delete car user</h1>
<ul id="actions">
	<li><?php echo a_tag($car_user, car_user_uri($car_user)); ?></li>
	<li><?php echo a_tag('Edit', edit_car_user_uri($car_user)); ?></li>
</ul>
<h2><?php echo $car_user; ?></h2>
<?php echo delete_form_for($car_user); ?>