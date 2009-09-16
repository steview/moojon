<h1>Firsts</h1>
<ul id="actions">
	<li><?php echo link_to('New', new_first_uri()); ?></li>
</ul>
<?php partial('table', array('firsts' => $firsts)); ?>