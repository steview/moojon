<h1>Firsts</h1>

<div id="content_main">

<ul>
	<li><?php echo link_to('Create', 'client/firsts/create'); ?></li>
</ul>

<?php
partial('table', array('firsts' => $firsts));
?>

</div><!-- /content_main -->

<div id="content_sub">



</div><!-- /content_sub -->