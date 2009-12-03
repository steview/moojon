<h1>Car users</h1>
<ul id="actions">
	<li><?php echo a_tag('New', new_car_user_uri()); ?></li>
</ul>
<?php echo table_for($car_users); ?>