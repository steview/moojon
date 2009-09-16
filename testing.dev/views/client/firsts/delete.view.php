<h1>Delete first</h1>
<ul id="actions">
	<li><?php echo link_to($first, first_uri($first)); ?></li>
	<li><?php echo link_to('Edit', edit_first_uri($first)); ?></li>
</ul>
<h2><?php echo $first; ?></h2>
<?php partial('delete_form', array('first' => $first)); ?>