<h1>Update first</h1>

<div id="content_main">

<ul>
	<li><?php echo link_to('Read', 'read/id/'.moojon_uri::get('id'), <[controller]>, <[app]>); ?></li>
	<li><?php echo link_to('Destroy', 'destroy/id/'.moojon_uri::get('id'), <[controller]>, <[app]>); ?></li>
</ul>

<?php
partial('form', array('first' => $first));
?>

</div><!-- /content_main -->

<div id="content_sub">



</div><!-- /content_sub -->