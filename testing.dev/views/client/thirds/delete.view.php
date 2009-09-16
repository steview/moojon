<h1>Delete third</h1>
<ul id="actions">
	<li><?php echo link_to($third, third_uri($third)); ?></li>
	<li><?php echo link_to('Edit', edit_third_uri($third)); ?></li>
</ul>
<h2><?php echo $third; ?></h2>
<?php partial('delete_form', array('third' => $third)); ?>