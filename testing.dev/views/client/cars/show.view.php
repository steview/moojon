<h1>Car</h1>
<ul id="actions">
	<li><?php echo a_tag('Edit', edit_car_uri($car)); ?></li>
	<li><?php echo a_tag('Delete', delete_car_uri($car)); ?></li>
</ul>
<h2><?php echo $car; ?></h2>
<?php echo dl_for($car); ?>