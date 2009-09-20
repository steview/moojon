<h1>Car</h1>
<ul id="actions">
	<li><?php echo link_to('Edit', edit_car_uri($car)); ?></li>
	<li><?php echo link_to('Delete', delete_car_uri($car)); ?></li>
</ul>
<h2><?php echo $car; ?></h2>
<?php echo dl_for($car); ?>