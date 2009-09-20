<h1>Cars</h1>
<ul id="actions">
	<li><?php echo link_to('New', new_car_uri()); ?></li>
</ul>
<?php echo table_for($cars); ?>