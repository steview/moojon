<h1>Seconds</h1>
<ul id="actions">
	<li><?php echo link_to('New', new_second_uri()); ?></li>
</ul>
<?php partial('table', array('seconds' => $seconds)); ?>