<h1>Update second</h1>

<div id="content_main">

<ul>
	<li><?php echo link_to($second, second_uri($second)); ?></li>
	<li><?php echo link_to('Delete', delete_second_uri($second)); ?></li>
</ul>

<?php
partial('form', array('second' => $second));
?>

</div><!-- /content_main -->

<div id="content_sub">



</div><!-- /content_sub -->