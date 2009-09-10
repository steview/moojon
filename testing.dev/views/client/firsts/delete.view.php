<h1>Delete first</h1>

<div id="content_main">

<ul>
	<li><?php echo link_to($first, first_uri($first)); ?></li>
	<li><?php echo link_to('Edit', edit_first_uri($first)); ?></li>
</ul>

<h2><?php echo $first; ?></h2>

<?php
partial('delete_form', array('first' => $first));
?>

</div><!-- /content_main -->

<div id="content_sub">



</div><!-- /content_sub -->