<h1>Delete car</h1>
<ul id="actions">
	<li><?php echo link_to($car, car_uri($car)); ?></li>
	<li><?php echo link_to('Edit', edit_car_uri($car)); ?></li>
</ul>
<h2><?php echo $car; ?></h2>
<?php echo delete_form_for($car); ?>