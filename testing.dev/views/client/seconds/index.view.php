<h1>Seconds</h1>

<div id="content_main">

<ul>
	<li><?php echo link_to('New', new_second_uri()); ?></li>
</ul>

<?php
partial('table', array('seconds' => $seconds));
?>

</div><!-- /content_main -->

<div id="content_sub">



</div><!-- /content_sub -->