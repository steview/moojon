<h1>Delete second</h1>

<div id="content_main">

<ul>
	<li><?php echo link_to($second, second_uri($second)); ?></li>
	<li><?php echo link_to('Edit', edit_second_uri($second)); ?></li>
</ul>

<h2><?php echo $second; ?></h2>

<?php
partial('delete_form', array('second' => $second));
?>

</div><!-- /content_main -->

<div id="content_sub">



</div><!-- /content_sub -->