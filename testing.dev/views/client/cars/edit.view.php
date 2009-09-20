<h1>Edit car</h1>
<ul id="actions">
	<li><?php echo link_to($car, car_uri($car)); ?></li>
	<li><?php echo link_to('Delete', delete_car_uri($car)); ?></li>
</ul>
<?php partial('form', array('car' => $car)); ?>