<h1>Edit car</h1>
<ul id="actions">
	<li><?php echo a_tag($car, car_uri($car)); ?></li>
	<li><?php echo a_tag('Delete', delete_car_uri($car)); ?></li>
</ul>
<?php partial('form', array('car' => $car)); ?>