<h1>Thirds</h1>
<ul id="actions">
	<li><?php echo link_to('New', new_third_uri()); ?></li>
</ul>
<?php partial('table', array('thirds' => $thirds)); ?>