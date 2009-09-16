<h1>Delete second</h1>
<ul id="actions">
	<li><?php echo link_to($second, second_uri($second)); ?></li>
	<li><?php echo link_to('Edit', edit_second_uri($second)); ?></li>
</ul>
<h2><?php echo $second; ?></h2>
<?php partial('delete_form', array('second' => $second)); ?>