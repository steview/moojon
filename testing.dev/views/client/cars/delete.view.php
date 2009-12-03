<h1>Delete car</h1>
<ul id="actions">
	<li><?php echo a_tag($car, car_uri($car)); ?></li>
	<li><?php echo a_tag('Edit', edit_car_uri($car)); ?></li>
</ul>
<h2><?php echo $car; ?></h2>
<?php echo delete_form_for($car); ?>