<h1>Delete car user</h1>
<ul id="actions">
	<li><?php echo link_to($car_user, car_user_uri($car_user)); ?></li>
	<li><?php echo link_to('Edit', edit_car_user_uri($car_user)); ?></li>
</ul>
<h2><?php echo $car_user; ?></h2>
<?php echo delete_form_for($car_user); ?>