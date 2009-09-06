<h1>New first</h1>

<div id="content_main">

<ul>
	<li><?php echo link_to('Firsts', firsts_uri()); ?></li>
</ul>

<?php
partial('new_form', array('first' => $first));
?>

</div><!-- /content_main -->

<div id="content_sub">



</div><!-- /content_sub -->