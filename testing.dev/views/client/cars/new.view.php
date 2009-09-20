<h1>New car</h1>
<ul id="actions">
	<li><?php echo link_to('Cars', cars_uri()); ?></li>
</ul>
<?php partial('form', array('car' => $car)); ?>