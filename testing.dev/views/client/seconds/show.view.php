<h1><?php echo $second; ?></h1>

<div id="content_main">

<ul>
	<li><?php echo link_to('Edit', edit_second_uri($second)); ?></li>
	<li><?php echo link_to('Delete', delete_second_uri($second)); ?></li>
</ul>

<?php
partial('dl', array('second' => $second));
?>

</div><!-- /content_main -->

<div id="content_sub">



</div><!-- /content_sub -->